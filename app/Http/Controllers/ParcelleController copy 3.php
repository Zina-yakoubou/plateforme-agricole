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



class ParcelleController extends Controller
{
    /* ============================================================
     | INDEX
     * ============================================================ */

    // public function index(
    //     Recensement $recensement,
    //     Menage $menage,
    //     Exploitant $exploitant
    // ): View {
    //     $this->verifierContexte(
    //         $recensement,
    //         $menage,
    //         $exploitant
    //     );

    //     $this->getExploitantAutorise(
    //         $recensement,
    //         $menage,
    //         $exploitant
    //     );

    //     $exploitation = Exploitation::query()
    //         ->where('exploitant_id', $exploitant->idExploitant)
    //         ->with([
    //             'parcelles' => function ($query) {
    //                 $query->orderBy('idParcelle', 'desc');
    //             },
    //         ])
    //         ->withCount('parcelles')
    //         ->first();

    //     if (!$exploitation) {
    //         return view('parcelles.index', [
    //             'recensement' => $recensement,
    //             'menage' => $menage,
    //             'exploitant' => $exploitant,
    //             'exploitation' => null,
    //             'parcelles' => collect(),
    //         ]);
    //     }

    //     return view('parcelles.index', [
    //         'recensement' => $recensement,
    //         'menage' => $menage,
    //         'exploitant' => $exploitant,
    //         'exploitation' => $exploitation,
    //         'parcelles' => $exploitation->parcelles,
    //     ]);
    // }



 
    public function index(
        Request $request,
        Recensement $recensement,
        Menage $menage,
        Exploitant $exploitant
        ): View|RedirectResponse {

        $this->verifierContexte($recensement, $menage, $exploitant);

        $exploitantAutorise = $this->getExploitantAutorise(
            $recensement,
            $menage,
            $exploitant
        );
        if (
            !$exploitantAutorise ||
            (int) $exploitantAutorise->idExploitant !== (int) $exploitant->idExploitant
        ) {
            return back()->with(
                'error',
                'Exploitant introuvable ou accès non autorisé.'
            );
        }

        // Une exploitation par exploitant
        $exploitation = Exploitation::where(
                'exploitant_id',
                $exploitant->idExploitant
            )
            ->with(['parcelles.pointsGPS'])
            ->withCount('parcelles')
            ->first();

        // Collection d'exploitations (utilisée dans la vue)
        $exploitations = collect();

        if ($exploitation) {
            $exploitations->push($exploitation);
        }

        // Parcelles de l'exploitation
        $parcelles = $exploitation
            ? $exploitation->parcelles->sortBy('numeroParcelle')->values()
            : collect();

        return view('parcelles.index', [
            'recensement'   => $recensement,
            'menage'        => $menage,
            'exploitant'    => $exploitant,
            'exploitation'  => $exploitation,   // objet unique
            'exploitations' => $exploitations,  // collection pour la vue
            'parcelles'     => $parcelles,
        ]);
    }


    /* ============================================================
     | CREATE
     * ============================================================ */

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

        /*
         * L'exploitation est créée au niveau de l'exploitant.
         * On ne demande donc pas à l'agent de sélectionner
         * une exploitation dans le formulaire.
         */
        $exploitation = Exploitation::query()
            ->where('exploitant_id', $exploitant->idExploitant)
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

        /*
         * Numéro automatique de la prochaine parcelle.
         * L'agent ne saisit jamais ce numéro.
         */
        $numeroParcelle = $this->genererProchainNumeroParcelle(
            $exploitation
        );

        /*
         * ========================================================
         * RÉFÉRENTIEL DES CULTURES
         * ========================================================
         *
         * Modèle :
         * Culture
         * PK : idCulture
         * Nom : nomCulture
         * Statut : active
         */
        $cultures = Culture::query()
            ->where('active', true)
            ->orderBy('nomCulture')
            ->get();

        /*
         * ========================================================
         * RÉFÉRENTIEL DES INTRANTS
         * ========================================================
         *
         * Modèle :
         * Intrant
         * PK : idIntrant
         * Nom : nom
         * Statut : actif
         */
        $intrantsReferentiel = Intrant::query()
           // ->where('actif', true)
            ->orderBy('nom')
            ->get();

        return view('parcelles.create', [
            'recensement' => $recensement,
            'menage' => $menage,
            'exploitant' => $exploitant,
            'exploitation' => $exploitation,
            'numeroParcelle' => $numeroParcelle,

            /*
             * Ces deux variables sont utilisées
             * directement dans parcelles._form
             */
            'cultures' => $cultures,
            'intrantsReferentiel' => $intrantsReferentiel,
        ]);
    }


    /* ============================================================
     | STORE
     * ============================================================ */

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
            ->where('exploitant_id', $exploitant->idExploitant)
            ->first();

        if (!$exploitation) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Aucune exploitation n’est associée à cet exploitant.'
                );
        }

        /*
         * Validation déjà effectuée par StoreParcelleRequest.
         */
        $data = $request->validated();

        /*
         * ========================================================
         * GPS
         * ========================================================
         *
         * Les points sont envoyés sous forme JSON.
         * La superficie n'est jamais saisie par l'agent.
         */
        $points = $this->decoderPointsGPS(
            $data['points_gps'] ?? null
        );

        if (count($points) < 1) {
            throw ValidationException::withMessages([
                'points_gps' =>
                    'Veuillez enregistrer au moins 1 point GPS pour délimiter la parcelle.',
            ]);
        }

        /*
         * Calcul serveur de la superficie.
         */
        $superficie = $this->calculerSuperficieHa($points);

        if ($superficie <= 0) {
            throw ValidationException::withMessages([
                'points_gps' =>
                    'Impossible de calculer une superficie valide à partir des points GPS.',
            ]);
        }

        /*
         * Numéro automatique.
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
                /*
                 * =================================================
                 * PARCELLE
                 * =================================================
                 */
                $parcelle = new Parcelle();

                $parcelle->uid = (string) Str::uuid();

                $parcelle->exploitation_id =
                    $exploitation->idExploitation;

                /*
                 * Le numéro vient du serveur.
                 */
                $parcelle->numeroParcelle =
                    $numeroParcelle;

                /*
                 * La superficie vient exclusivement
                 * du calcul GPS côté serveur.
                 */
                $parcelle->superficie =
                    $superficie;

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
                 * =================================================
                 * POINTS GPS
                 * =================================================
                 */
                $this->enregistrerPointsGPS(
                    $parcelle,
                    $points
                );

                /*
                 * =================================================
                 * IMPORTANT
                 * =================================================
                 *
                 * Les cultures et intrants seront enregistrés
                 * ici après vérification de CultureParcelle et
                 * du modèle de liaison des intrants.
                 */
            });

            return redirect()
                ->route('agent.parcelles.index', [
                    'recensement' => $recensement,
                    'menage' => $menage,
                    'exploitant' => $exploitant,
                ])
                ->with(
                    'success',
                    'La parcelle ' . $numeroParcelle . ' a été enregistrée avec succès.'
                );

        } catch (Throwable $e) {

            Log::error(
                'Erreur lors de la création d’une parcelle.',
                [
                    'message' => $e->getMessage(),
                    'exploitant_id' => $exploitant->idExploitant,
                    'exploitation_id' => $exploitation->idExploitation,
                    'exception' => $e,
                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Impossible d’enregistrer la parcelle : '
                    . $e->getMessage()
                );
        }
    }


    /* ============================================================
     | EDIT
     * ============================================================ */

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

        /*
         * Vérification de l'exploitation.
         */
        $exploitation = Exploitation::query()
            ->where('idExploitation', $parcelle->exploitation_id)
            ->where('exploitant_id', $exploitant->idExploitant)
            ->withCount('parcelles')
            ->firstOrFail();

        /*
         * Charger les points GPS existants.
         */
        $parcelle->load([
            'pointsGPS' => function ($query) {
                $query->orderBy('ordre');
            },
        ]);

        /*
         * Référentiel des cultures.
         */
        $cultures = Culture::query()
            ->where('active', true)
            ->orderBy('nomCulture')
            ->get();

        /*
         * Référentiel des intrants.
         */
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

            /*
             * Nécessaires pour le même formulaire
             * que celui de création.
             */
            'cultures' => $cultures,
            'intrantsReferentiel' => $intrantsReferentiel,
        ]);
    }


    /* ============================================================
     | UPDATE
     * ============================================================ */

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
         * ========================================================
         * GPS
         * ========================================================
         */
        $points = $this->decoderPointsGPS(
            $data['points_gps'] ?? null
        );

        if (count($points) < 3) {
            throw ValidationException::withMessages([
                'points_gps' =>
                    'Veuillez enregistrer au moins 3 points GPS pour délimiter la parcelle.',
            ]);
        }

        /*
         * Recalcul de la superficie côté serveur.
         */
        $superficie = $this->calculerSuperficieHa($points);

        if ($superficie <= 0) {
            throw ValidationException::withMessages([
                'points_gps' =>
                    'Impossible de calculer une superficie valide à partir des points GPS.',
            ]);
        }

        try {
            DB::transaction(function () use (
                $parcelle,
                $data,
                $points,
                $superficie
            ) {
                /*
                 * Le numéro de parcelle reste celui
                 * déjà attribué.
                 */
                $parcelle->superficie =
                    $superficie;

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
                 * =================================================
                 * REMPLACEMENT DES POINTS GPS
                 * =================================================
                 */
                PointGPS::query()
                    ->where('parcelle_id', $parcelle->idParcelle)
                    ->delete();

                $this->enregistrerPointsGPS(
                    $parcelle,
                    $points
                );

                /*
                 * Les cultures/intrants seront synchronisés
                 * ici après vérification des modèles de liaison.
                 */
            });

            return redirect()
                ->route('agent.parcelles.index', [
                    'recensement' => $recensement,
                    'menage' => $menage,
                    'exploitant' => $exploitant,
                ])
                ->with(
                    'success',
                    'La parcelle ' . $parcelle->numeroParcelle . ' a été modifiée avec succès.'
                );

        } catch (Throwable $e) {

            Log::error(
                'Erreur lors de la modification d’une parcelle.',
                [
                    'message' => $e->getMessage(),
                    'parcelle_id' => $parcelle->idParcelle,
                    'exception' => $e,
                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Impossible de modifier la parcelle : '
                    . $e->getMessage()
                );
        }
    }


    /* ============================================================
     | DESTROY
     * ============================================================ */

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

                /*
                 * Supprimer les points GPS avant la parcelle.
                 */
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
                    'message' => $e->getMessage(),
                    'parcelle_id' => $parcelle->idParcelle,
                    'exception' => $e,
                ]
            );

            return back()
                ->with(
                    'error',
                    'Impossible de supprimer la parcelle : '
                    . $e->getMessage()
                );
        }
    }


    /* ============================================================
     | VÉRIFICATION DU CONTEXTE
     * ============================================================ */

    private function verifierContexte(
        Recensement $recensement,
        Menage $menage,
        Exploitant $exploitant
    ): void {
        /*
         * Le ménage doit appartenir au recensement.
         */
        if (
            (int) $menage->recensement_id
            !==
            (int) $recensement->idRecensement
        ) {
            abort(404);
        }

        /*
         * L'exploitant doit appartenir au ménage.
         */
        if (
            (int) $exploitant->menage_id
            !==
            (int) $menage->idMenage
        ) {
            abort(404);
        }
    }


    /* ============================================================
     | EXPLOITANT AUTORISÉ
     * ============================================================ */

    private function getExploitantAutorise(
        Recensement $recensement,
        Menage $menage,
        Exploitant $exploitant
    ): Exploitant {
        /*
         * Cette méthode conserve la logique de sécurité
         * existante du contrôleur.
         */
        if (
            (int) $exploitant->menage_id
            !==
            (int) $menage->idMenage
        ) {
            abort(403);
        }

        return $exploitant;
    }


    /* ============================================================
     | PARCELLE AUTORISÉE
     * ============================================================ */

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


    /* ============================================================
     | NUMÉRO AUTOMATIQUE
     * ============================================================ */

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

        /*
         * Première parcelle.
         */
        if (!$dernierNumero) {
            return 'P-001';
        }

        /*
         * Extraction du numéro :
         *
         * P-001 -> 1
         * P-002 -> 2
         * etc.
         */
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


    /* ============================================================
     | DÉCODER LES POINTS GPS
     * ============================================================ */

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

            /*
             * Vérification des coordonnées.
             */
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
                'latitude' => $latitude,
                'longitude' => $longitude,

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

        /*
         * Réattribuer l'ordre proprement.
         */
        foreach ($points as $index => &$point) {
            $point['ordre'] = $index + 1;
        }

        unset($point);

        return $points;
    }


    /* ============================================================
     | CALCUL SUPERFICIE
     * ============================================================ */

    private function calculerSuperficieHa(
        array $points
    ): float {
        if (count($points) < 3) {
            return 0.0;
        }

        /*
         * Point de référence :
         * premier point GPS.
         */
        $latitude0 =
            deg2rad(
                (float) $points[0]['latitude']
            );

        $longitude0 =
            deg2rad(
                (float) $points[0]['longitude']
            );

        /*
         * Rayon moyen de la Terre en mètres.
         */
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

            /*
             * Projection locale equirectangulaire.
             */
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

        /*
         * Formule de Shoelace.
         */
        $surfaceM2 = 0.0;

        $nombrePoints = count($xy);

        for ($i = 0; $i < $nombrePoints; $i++) {

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

        /*
         * Conversion m² -> hectares.
         */
        return round(
            $surfaceM2 / 10000,
            4
        );
    }


    /* ============================================================
     | ENREGISTRER LES POINTS GPS
     * ============================================================ */

    private function enregistrerPointsGPS(
        Parcelle $parcelle,
        array $points
    ): void {
        foreach ($points as $index => $point) {

            $pointGPS = new PointGPS();

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
}