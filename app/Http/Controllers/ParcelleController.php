<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParcelleRequest;
use App\Http\Requests\UpdateParcelleRequest;
use App\Models\Culture;
use App\Models\Exploitation;
use App\Models\Exploitant;
use App\Models\Intrant;
use App\Models\Menage;
use App\Models\Parcelle;
use App\Models\PointGPS;
use App\Models\Recensement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;
use App\Models\CultureParcelle;
use App\Models\CultureIntrant;

class ParcelleController extends Controller
{
    public function index(
        Request $request,
        Recensement $recensement,
        Menage $menage,
        Exploitant $exploitant
    ): View|RedirectResponse {
        $this->verifierContexte(
            $recensement,
            $menage,
            $exploitant
        );

        $exploitantAutorise = $this->getExploitantAutorise(
            $recensement,
            $menage,
            $exploitant
        );

        if (
            !$exploitantAutorise ||
            (int) $exploitantAutorise->idExploitant !==
            (int) $exploitant->idExploitant
        ) {
            return back()->with(
                'error',
                'Exploitant introuvable ou accès non autorisé.'
            );
        }

        $exploitation = Exploitation::where(
                'exploitant_id',
                $exploitant->idExploitant
            )
            ->with([
                'parcelles.pointsGPS'
            ])
            ->withCount('parcelles')
            ->first();

        $exploitations = collect();

        if ($exploitation) {
            $exploitations->push($exploitation);
        }

        $parcelles = $exploitation
            ? $exploitation->parcelles
                ->sortBy('numeroParcelle')
                ->values()
            : collect();

        return view('parcelles.index', [
            'recensement'   => $recensement,
            'menage'        => $menage,
            'exploitant'    => $exploitant,
            'exploitation'  => $exploitation,
            'exploitations' => $exploitations,
            'parcelles'     => $parcelles,
        ]);
    }

    public function create(
        Recensement $recensement,
        Menage $menage,
        Exploitant $exploitant
    ): View|RedirectResponse {
        $this->verifierContexte(
            $recensement,
            $menage,
            $exploitant
        );

        $this->getExploitantAutorise(
            $recensement,
            $menage,
            $exploitant
        );

        $exploitation = Exploitation::query()
            ->where(
                'exploitant_id',
                $exploitant->idExploitant
            )
            ->withCount('parcelles')
            ->first();

        if (!$exploitation) {
            return redirect()
                ->route('agent.exploitations.create', [
                    'recensement' => $recensement,
                    'menage' => $menage,
                    'exploitant' => $exploitant,
                ])
                ->with(
                    'error',
                    'Aucune exploitation n’est encore créée pour cet exploitant.'
                );
        }

        $numeroParcelle = $this->genererProchainNumeroParcelle(
            $exploitation
        );

        $cultures = Culture::query()
            ->where('active', true)
            ->orderBy('nomCulture')
            ->get();

        $intrantsReferentiel = Intrant::query()
            ->orderBy('nom')
            ->get();

        return view('parcelles.create', [
            'recensement' => $recensement,
            'menage' => $menage,
            'exploitant' => $exploitant,
            'exploitation' => $exploitation,
            'numeroParcelle' => $numeroParcelle,
            'cultures' => $cultures,
            'intrantsReferentiel' => $intrantsReferentiel,
        ]);
    }

    public function store(
        StoreParcelleRequest $request,
        Recensement $recensement,
        Menage $menage,
        Exploitant $exploitant
    ): RedirectResponse {
        $this->verifierContexte(
            $recensement,
            $menage,
            $exploitant
        );

        $this->getExploitantAutorise(
            $recensement,
            $menage,
            $exploitant
        );

        $exploitation = Exploitation::query()
            ->where(
                'exploitant_id',
                $exploitant->idExploitant
            )
            ->first();

        if (!$exploitation) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Aucune exploitation n’est associée à cet exploitant.'
                );
        }

        $data = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Décodage des points GPS
        |--------------------------------------------------------------------------
        */
        $points = $this->decoderPointsGPS(
            $data['points_gps'] ?? null
        );

        /*
        |--------------------------------------------------------------------------
        | Au minimum 1 point GPS est nécessaire
        |--------------------------------------------------------------------------
        */
        if (count($points) < 1) {
            throw ValidationException::withMessages([
                'points_gps' =>
                    'Veuillez enregistrer au moins 1 point GPS.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Calcul de la superficie
        |--------------------------------------------------------------------------
        |
        | 1 ou 2 points :
        |     la parcelle est enregistrée,
        |     mais la superficie reste à 0.
        |
        | 3 points ou plus :
        |     la superficie est calculée automatiquement.
        |
        |--------------------------------------------------------------------------
        */
        $superficie = 0;

        if (count($points) >= 3) {
            $superficie = $this->calculerSuperficieHa($points);

            if ($superficie <= 0) {
                throw ValidationException::withMessages([
                    'points_gps' =>
                        'Impossible de calculer une superficie valide à partir des points GPS.',
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Génération automatique du numéro de parcelle
        |--------------------------------------------------------------------------
        */
        $numeroParcelle = $this->genererProchainNumeroParcelle(
            $exploitation
        );

        try {

            DB::transaction(function () use (
                $data,
                $points,
                $superficie,
                $numeroParcelle,
                $exploitation
            ) {

                $parcelle = new Parcelle();

                /*
                |--------------------------------------------------------------------------
                | Identification
                |--------------------------------------------------------------------------
                */
                $parcelle->uid = (string) Str::uuid();

                $parcelle->exploitation_id =
                    $exploitation->idExploitation;

                $parcelle->numeroParcelle =
                    $numeroParcelle;

                /*
                |--------------------------------------------------------------------------
                | Superficie calculée automatiquement
                |--------------------------------------------------------------------------
                */
                $parcelle->superficie =
                    $superficie;

                /*
                |--------------------------------------------------------------------------
                | Informations de la parcelle
                |--------------------------------------------------------------------------
                */
                $parcelle->statutParcelle =
                    $data['statutParcelle'] ?? null;

                $parcelle->typeSol =
                    $data['typeSol'] ?? null;

                $parcelle->modeFaireValoir =
                    $data['modeFaireValoir'] ?? null;

                $parcelle->modeIrrigation =
                    $data['modeIrrigation'] ?? null;

                $parcelle->presenceArbres =
                    isset($data['presenceArbres'])
                        ? (bool) $data['presenceArbres']
                        : false;

                $parcelle->observations =
                    $data['observations'] ?? null;

                $parcelle->save();

                /*
                |--------------------------------------------------------------------------
                | Enregistrement des points GPS
                |--------------------------------------------------------------------------
                */
                $this->enregistrerPointsGPS(
                    $parcelle,
                    $points
                );

                /*
                |--------------------------------------------------------------------------
                | Cultures / intrants
                |--------------------------------------------------------------------------
                |
                | Pas encore synchronisés ici.
                |
                |--------------------------------------------------------------------------
                */


                /*
                |--------------------------------------------------------------------------
                | ENREGISTREMENT DES CULTURES ET DES INTRANTS
                |--------------------------------------------------------------------------
                */

                $this->enregistrerCulturesEtIntrants(
                    $parcelle,
                    $data['cultures'] ?? []
                );
            });

            return redirect()
                ->route('agent.parcelles.index', [
                    'recensement' => $recensement,
                    'menage' => $menage,
                    'exploitant' => $exploitant,
                ])
                ->with(
                    'success',
                    'La parcelle ' .
                    $numeroParcelle .
                    ' a été enregistrée avec succès.'
                );

        } catch (Throwable $e) {

            Log::error(
                'Erreur lors de la création d’une parcelle.',
                [
                    'message' =>
                        $e->getMessage(),

                    'exploitant_id' =>
                        $exploitant->idExploitant,

                    'exploitation_id' =>
                        $exploitation->idExploitation,

                    'exception' =>
                        $e,
                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Impossible d’enregistrer la parcelle : ' .
                    $e->getMessage()
                );
        }
    }

    public function edit(
        Recensement $recensement,
        Menage $menage,
        Exploitant $exploitant,
        Parcelle $parcelle
    ): View {
        $this->verifierContexte(
            $recensement,
            $menage,
            $exploitant
        );

        $this->getExploitantAutorise(
            $recensement,
            $menage,
            $exploitant
        );

        $this->verifierParcelleAutorisee(
            $parcelle,
            $exploitant
        );

        $exploitation = Exploitation::query()
            ->where(
                'idExploitation',
                $parcelle->exploitation_id
            )
            ->where(
                'exploitant_id',
                $exploitant->idExploitant
            )
            ->withCount('parcelles')
            ->firstOrFail();

        $parcelle->load([
            'pointsGPS' => function ($query) {
                $query->orderBy('ordre');
            },
        ]);

        $cultures = Culture::query()
            ->where('active', true)
            ->orderBy('nomCulture')
            ->get();

        $intrantsReferentiel = Intrant::query()
            ->where('actif', true)
            ->orderBy('nom')
            ->get();

        return view('agent.parcelles.edit', [
            'recensement' => $recensement,
            'menage' => $menage,
            'exploitant' => $exploitant,
            'exploitation' => $exploitation,
            'parcelle' => $parcelle,
            'cultures' => $cultures,
            'intrantsReferentiel' => $intrantsReferentiel,
        ]);
    }

    public function update(
        UpdateParcelleRequest $request,
        Recensement $recensement,
        Menage $menage,
        Exploitant $exploitant,
        Parcelle $parcelle
    ): RedirectResponse {
        $this->verifierContexte(
            $recensement,
            $menage,
            $exploitant
        );

        $this->getExploitantAutorise(
            $recensement,
            $menage,
            $exploitant
        );

        $this->verifierParcelleAutorisee(
            $parcelle,
            $exploitant
        );

        $data = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Décodage des points GPS
        |--------------------------------------------------------------------------
        */
        $points = $this->decoderPointsGPS(
            $data['points_gps'] ?? null
        );

        /*
        |--------------------------------------------------------------------------
        | Modification :
        | au minimum 1 point GPS
        |--------------------------------------------------------------------------
        */
        if (count($points) < 1) {
            throw ValidationException::withMessages([
                'points_gps' =>
                    'Veuillez enregistrer au moins 1 point GPS.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Calcul de la superficie
        |--------------------------------------------------------------------------
        */
        $superficie = 0;

        if (count($points) >= 3) {
            $superficie = $this->calculerSuperficieHa($points);

            if ($superficie <= 0) {
                throw ValidationException::withMessages([
                    'points_gps' =>
                        'Impossible de calculer une superficie valide à partir des points GPS.',
                ]);
            }
        }

        try {

            DB::transaction(function () use (
                $parcelle,
                $data,
                $points,
                $superficie
            ) {

                /*
                |--------------------------------------------------------------------------
                | Mise à jour de la superficie
                |--------------------------------------------------------------------------
                */
                $parcelle->superficie =
                    $superficie;

                /*
                |--------------------------------------------------------------------------
                | Informations de la parcelle
                |--------------------------------------------------------------------------
                */
                $parcelle->statutParcelle =
                    $data['statutParcelle'] ?? null;

                $parcelle->typeSol =
                    $data['typeSol'] ?? null;

                $parcelle->modeFaireValoir =
                    $data['modeFaireValoir'] ?? null;

                $parcelle->modeIrrigation =
                    $data['modeIrrigation'] ?? null;

                $parcelle->presenceArbres =
                    isset($data['presenceArbres'])
                        ? (bool) $data['presenceArbres']
                        : false;

                $parcelle->observations =
                    $data['observations'] ?? null;

                $parcelle->save();

                /*
                |--------------------------------------------------------------------------
                | Suppression des anciens points GPS
                |--------------------------------------------------------------------------
                */
                PointGPS::query()
                    ->where(
                        'parcelle_id',
                        $parcelle->idParcelle
                    )
                    ->delete();

                /*
                |--------------------------------------------------------------------------
                | Enregistrement des nouveaux points GPS
                |--------------------------------------------------------------------------
                */
                $this->enregistrerPointsGPS(
                    $parcelle,
                    $points
                );

                /*
                |--------------------------------------------------------------------------
                | Cultures / intrants
                |--------------------------------------------------------------------------
                |
                | Pas encore synchronisés ici.
                |
                |--------------------------------------------------------------------------
                */

 


                $this->mettreAJourCulturesEtIntrants(
                    $parcelle,
                    $data['cultures'] ?? []
                );
            });

            return redirect()
                ->route('agent.parcelles.index', [
                    'recensement' => $recensement,
                    'menage' => $menage,
                    'exploitant' => $exploitant,
                ])
                ->with(
                    'success',
                    'La parcelle ' .
                    $parcelle->numeroParcelle .
                    ' a été modifiée avec succès.'
                );

        } catch (Throwable $e) {

            Log::error(
                'Erreur lors de la modification d’une parcelle.',
                [
                    'message' =>
                        $e->getMessage(),

                    'parcelle_id' =>
                        $parcelle->idParcelle,

                    'exception' =>
                        $e,
                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Impossible de modifier la parcelle : ' .
                    $e->getMessage()
                );
        }
    }

    public function destroy(
        Recensement $recensement,
        Menage $menage,
        Exploitant $exploitant,
        Parcelle $parcelle
    ): RedirectResponse {
        $this->verifierContexte(
            $recensement,
            $menage,
            $exploitant
        );

        $this->getExploitantAutorise(
            $recensement,
            $menage,
            $exploitant
        );

        $this->verifierParcelleAutorisee(
            $parcelle,
            $exploitant
        );

        try {

            DB::transaction(function () use ($parcelle) {

                PointGPS::query()
                    ->where(
                        'parcelle_id',
                        $parcelle->idParcelle
                    )
                    ->delete();

                $parcelle->delete();
            });

            return redirect()
                ->route('agent.parcelles.index', [
                    'recensement' => $recensement,
                    'menage' => $menage,
                    'exploitant' => $exploitant,
                ])
                ->with(
                    'success',
                    'La parcelle a été supprimée avec succès.'
                );

        } catch (Throwable $e) {

            Log::error(
                'Erreur lors de la suppression d’une parcelle.',
                [
                    'message' =>
                        $e->getMessage(),

                    'parcelle_id' =>
                        $parcelle->idParcelle,

                    'exception' =>
                        $e,
                ]
            );

            return back()
                ->with(
                    'error',
                    'Impossible de supprimer la parcelle : ' .
                    $e->getMessage()
                );
        }
    }

    private function verifierContexte(
        Recensement $recensement,
        Menage $menage,
        Exploitant $exploitant
    ): void {
        if (
            (int) $menage->recensement_id
            !==
            (int) $recensement->idRecensement
        ) {
            abort(404);
        }

        if (
            (int) $exploitant->menage_id
            !==
            (int) $menage->idMenage
        ) {
            abort(404);
        }
    }

    private function getExploitantAutorise(
        Recensement $recensement,
        Menage $menage,
        Exploitant $exploitant
    ): Exploitant {
        if (
            (int) $exploitant->menage_id
            !==
            (int) $menage->idMenage
        ) {
            abort(403);
        }

        return $exploitant;
    }

    private function verifierParcelleAutorisee(
        Parcelle $parcelle,
        Exploitant $exploitant
    ): void {
        $exploitation = Exploitation::query()
            ->where(
                'idExploitation',
                $parcelle->exploitation_id
            )
            ->first();

        if (!$exploitation) {
            abort(404);
        }

        if (
            (int) $exploitation->exploitant_id
            !==
            (int) $exploitant->idExploitant
        ) {
            abort(403);
        }
    }

    private function genererProchainNumeroParcelle(
        Exploitation $exploitation
    ): string {
        $dernierNumero = Parcelle::query()
            ->where(
                'exploitation_id',
                $exploitation->idExploitation
            )
            ->orderByDesc('idParcelle')
            ->value('numeroParcelle');

        if (!$dernierNumero) {
            return 'P-001';
        }

        if (
            preg_match(
                '/P-(\d+)/',
                $dernierNumero,
                $matches
            )
        ) {
            $numero = ((int) $matches[1]) + 1;
        } else {
            $numero = Parcelle::query()
                ->where(
                    'exploitation_id',
                    $exploitation->idExploitation
                )
                ->count() + 1;
        }

        return 'P-' . str_pad(
            (string) $numero,
            3,
            '0',
            STR_PAD_LEFT
        );
    }

    private function decoderPointsGPS(
        mixed $pointsGps
    ): array {
        if (is_string($pointsGps)) {
            $pointsGps = json_decode(
                $pointsGps,
                true
            );
        }

        if (!is_array($pointsGps)) {
            return [];
        }

        $points = [];

        foreach ($pointsGps as $point) {

            if (!is_array($point)) {
                continue;
            }

            if (
                !isset(
                    $point['latitude'],
                    $point['longitude']
                )
            ) {
                continue;
            }

            $latitude = (float) $point['latitude'];
            $longitude = (float) $point['longitude'];

            if (
                !is_finite($latitude)
                ||
                !is_finite($longitude)
            ) {
                continue;
            }

            if (
                $latitude < -90
                ||
                $latitude > 90
                ||
                $longitude < -180
                ||
                $longitude > 180
            ) {
                continue;
            }

            $points[] = [
                'latitude' =>
                    $latitude,

                'longitude' =>
                    $longitude,

                'precisionGPS' =>
                    isset($point['precisionGPS'])
                        && is_numeric($point['precisionGPS'])
                        ? (float) $point['precisionGPS']
                        : null,

                'altitude' =>
                    isset($point['altitude'])
                        && is_numeric($point['altitude'])
                        ? (float) $point['altitude']
                        : null,

                'ordre' =>
                    isset($point['ordre'])
                        && is_numeric($point['ordre'])
                        ? (int) $point['ordre']
                        : count($points) + 1,
            ];
        }

        foreach ($points as $index => &$point) {
            $point['ordre'] =
                $index + 1;
        }

        unset($point);

        return $points;
    }

    private function calculerSuperficieHa(
        array $points
    ): float {
        /*
        |--------------------------------------------------------------------------
        | Il faut au moins 3 points pour calculer
        | une superficie polygonale.
        |--------------------------------------------------------------------------
        */
        if (count($points) < 3) {
            return 0.0;
        }

        $latitude0 =
            deg2rad(
                (float) $points[0]['latitude']
            );

        $longitude0 =
            deg2rad(
                (float) $points[0]['longitude']
            );

        $rayonTerre = 6371000.0;

        $xy = [];

        foreach ($points as $point) {

            $latitude =
                deg2rad(
                    (float) $point['latitude']
                );

            $longitude =
                deg2rad(
                    (float) $point['longitude']
                );

            $x =
                ($longitude - $longitude0)
                *
                cos($latitude0)
                *
                $rayonTerre;

            $y =
                ($latitude - $latitude0)
                *
                $rayonTerre;

            $xy[] = [
                'x' => $x,
                'y' => $y,
            ];
        }

        $surfaceM2 = 0.0;

        $nombrePoints =
            count($xy);

        for (
            $i = 0;
            $i < $nombrePoints;
            $i++
        ) {

            $j =
                ($i + 1)
                %
                $nombrePoints;

            $surfaceM2 +=
                (
                    $xy[$i]['x']
                    *
                    $xy[$j]['y']
                )
                -
                (
                    $xy[$j]['x']
                    *
                    $xy[$i]['y']
                );
        }

        $surfaceM2 =
            abs($surfaceM2) / 2;

        return round(
            $surfaceM2 / 10000,
            4
        );
    }

    private function enregistrerPointsGPS(
        Parcelle $parcelle,
        array $points
    ): void {
        foreach (
            $points as $index => $point
        ) {

            $pointGPS =
                new PointGPS();

            $pointGPS->parcelle_id =
                $parcelle->idParcelle;

            $pointGPS->latitude =
                $point['latitude'];

            $pointGPS->longitude =
                $point['longitude'];

            $pointGPS->precisionGPS =
                $point['precisionGPS'] ?? null;

            $pointGPS->altitude =
                $point['altitude'] ?? null;

            $pointGPS->ordre =
                $index + 1;

            $pointGPS->save();
        }
    }


    private function enregistrerCulturesEtIntrants(
            Parcelle $parcelle,
            array $cultures
        ): void {

        foreach ($cultures as $cultureData) {

            if (empty($cultureData['culture_id'])) {
                continue;
            }

            $cultureParcelle = CultureParcelle::create([
                'uid'                  => (string) Str::uuid(),
                'parcelle_id'          => $parcelle->idParcelle,
                'culture_id'           => $cultureData['culture_id'],
                'campagneAgricole'     => $cultureData['campagneAgricole'] ?? null,
                'modeCulture'          => $cultureData['modeCulture'] ?? 'principale',
                'superficieCultivee'   => $cultureData['superficieCultivee'] ?: null,
                'dateSemis'            => $cultureData['dateSemis'] ?: null,
                'dateRecoltePrevue'    => $cultureData['dateRecoltePrevue'] ?: null,
                'dateRecolteEffective' => $cultureData['dateRecolteEffective'] ?: null,
                'irriguee'             => !empty($cultureData['irriguee']),
                'etatCulture'          => $cultureData['etatCulture'] ?? 'semis',
                'observations'         => $cultureData['observations'] ?? null,
            ]);

            foreach ($cultureData['intrants'] ?? [] as $intrantData) {

                if (
                    empty($intrantData['intrant_id']) ||
                    empty($intrantData['quantite']) ||
                    empty($intrantData['nombreApplications'])
                ) {
                    continue;
                }

                CultureIntrant::create([
                    'culture_parcelle_id' => $cultureParcelle->idCultureParcelle,
                    'intrant_id'          => $intrantData['intrant_id'],
                    'quantite'            => $intrantData['quantite'],
                    'nombreApplications'  => $intrantData['nombreApplications'],
                    'dateApplication'     => $intrantData['dateApplication'] ?: null,
                    'observations'        => $intrantData['observations'] ?? null,
                ]);
            }
        }
    }


    private function mettreAJourCulturesEtIntrants(
        Parcelle $parcelle,
        array $cultures
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Suppression des anciens intrants
        |--------------------------------------------------------------------------
        */

        $idsCultures = CultureParcelle::where(
            'parcelle_id',
            $parcelle->idParcelle
        )->pluck('idCultureParcelle');

        CultureIntrant::whereIn(
            'culture_parcelle_id',
            $idsCultures
        )->delete();

        /*
        |--------------------------------------------------------------------------
        | Suppression des anciennes cultures
        |--------------------------------------------------------------------------
        */

        CultureParcelle::where(
            'parcelle_id',
            $parcelle->idParcelle
        )->delete();

        /*
        |--------------------------------------------------------------------------
        | Réinsertion
        |--------------------------------------------------------------------------
        */

        $this->enregistrerCulturesEtIntrants(
            $parcelle,
            $cultures
        );
    }
}