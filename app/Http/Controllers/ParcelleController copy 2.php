<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreParcelleRequest;
use App\Http\Requests\UpdateParcelleRequest;
use App\Models\Culture;
use App\Models\CultureIntrant;
use App\Models\CultureParcelle;
use App\Models\Exploitation;
use App\Models\Exploitant;
use App\Models\Intrant;
use App\Models\Menage;
use App\Models\Parcelle;
use App\Models\Recensement;
use App\Models\PointGPS;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class ParcelleController extends Controller
{
    /**
     * Liste des parcelles d'une exploitation.
     */
    public function index(
        Recensement $recensement,
        Menage $menage,
        Exploitant $exploitant,
        Exploitation $exploitation
    ): View {
        $this->verifierContexte(
            $recensement,
            $menage,
            $exploitant,
            $exploitation
        );

        $parcelles = $exploitation->parcelles()
            ->with([
                'culturesParcelles.culture',
                'pointsGPS',
            ])
            ->latest('idParcelle')
            ->paginate(10)
            ->withQueryString();

        return view('agent.recensements.menages.exploitants.parcelles.index', [
            'recensement' => $recensement,
            'menage' => $menage,
            'exploitant' => $exploitant,
            'exploitation' => $exploitation,
            'parcelles' => $parcelles,
        ]);
    }

    /**
     * Formulaire de création.
     */
    public function create(
        Recensement $recensement,
        Menage $menage,
        Exploitant $exploitant,
        Exploitation $exploitation
    ): View {
        $this->verifierContexte(
            $recensement,
            $menage,
            $exploitant,
            $exploitation
        );

        $numeroParcelle = $this->genererNumeroParcelle($exploitation);

        $cultures = Culture::query()
            ->orderBy('nomCulture')
            ->get();

        $intrants = Intrant::query()
            ->orderBy('nomIntrant')
            ->get();

        return view('agent.recensements.menages.exploitants.parcelles.create', [
            'recensement' => $recensement,
            'menage' => $menage,
            'exploitant' => $exploitant,
            'exploitation' => $exploitation,
            'numeroParcelle' => $numeroParcelle,
            'cultures' => $cultures,
            'intrants' => $intrants,
        ]);
    }

    /**
     * Enregistre une nouvelle parcelle.
     */
    public function store(
        StoreParcelleRequest $request,
        Recensement $recensement,
        Menage $menage,
        Exploitant $exploitant,
        Exploitation $exploitation
    ): RedirectResponse {
        $this->verifierContexte(
            $recensement,
            $menage,
            $exploitant,
            $exploitation
        );

        $data = $request->validated();

        try {
            DB::transaction(function () use (
                $data,
                $recensement,
                $exploitation
            ) {
                /*
                 * ---------------------------------------------------------
                 * 1. Numéro automatique de la parcelle
                 * ---------------------------------------------------------
                 */
                $numeroParcelle = $this->genererNumeroParcelle($exploitation);

                /*
                 * ---------------------------------------------------------
                 * 2. Vérification du GPS
                 * ---------------------------------------------------------
                 */
                $pointsGPS = [];

                if (!empty($data['points_gps'])) {
                    $pointsGPS = is_string($data['points_gps'])
                        ? json_decode($data['points_gps'], true)
                        : $data['points_gps'];
                }

                if (!is_array($pointsGPS) || count($pointsGPS) < 3) {
                    throw new \RuntimeException(
                        'Le contour GPS de la parcelle doit contenir au moins 3 points.'
                    );
                }

                /*
                 * ---------------------------------------------------------
                 * 3. Création de la parcelle
                 * ---------------------------------------------------------
                 */
                $parcelle = Parcelle::create([
                    'uid' => (string) Str::uuid(),
                    'exploitation_id' => $exploitation->idExploitation,
                    'numeroParcelle' => $numeroParcelle,

                    'superficie' => $data['superficie'] ?? 0,

                    'statutParcelle' => $data['statutParcelle'] ?? 'exploitee',

                    'typeSol' => $data['typeSol'] ?? null,

                    'modeFaireValoir' =>
                        $data['modeFaireValoir'] ?? null,

                    'modeIrrigation' =>
                        $data['modeIrrigation'] ?? 'pluvial',

                    'presenceArbres' =>
                        $data['presenceArbres'] ?? false,

                    'observations' =>
                        $data['observations'] ?? null,
                ]);

                /*
                 * ---------------------------------------------------------
                 * 4. Enregistrement des points GPS
                 * ---------------------------------------------------------
                 */
                foreach ($pointsGPS as $index => $point) {

                    if (
                        !isset($point['latitude']) ||
                        !isset($point['longitude'])
                    ) {
                        continue;
                    }

                    PointGPS::create([
                        'uid' => (string) Str::uuid(),
                        'parcelle_id' => $parcelle->idParcelle,
                        'latitude' => $point['latitude'],
                        'longitude' => $point['longitude'],
                        'precisionGPS' =>
                            $point['precisionGPS']
                            ?? $point['accuracy']
                            ?? null,
                        'ordre' => $index + 1,
                    ]);
                }

                /*
                 * ---------------------------------------------------------
                 * 5. Les cultures
                 * ---------------------------------------------------------
                 *
                 * Une parcelle non exploitée ou en jachère ne reçoit
                 * normalement aucune culture.
                 */
                if (
                    ($data['statutParcelle'] ?? 'exploitee') === 'exploitee'
                    && !empty($data['cultures'])
                ) {
                    foreach ($data['cultures'] as $cultureData) {

                        /*
                         * Création de la culture de la parcelle
                         */
                        $cultureParcelle = CultureParcelle::create([
                            'uid' => (string) Str::uuid(),

                            'parcelle_id' =>
                                $parcelle->idParcelle,

                            'culture_id' =>
                                $cultureData['culture_id'],

                            /*
                             * La campagne agricole est conservée ici
                             * parce que ta table cultures_parcelles
                             * l'utilise actuellement.
                             */
                            'campagneAgricole' =>
                                $cultureData['campagneAgricole'],

                            'modeCulture' =>
                                $cultureData['modeCulture'],

                            'superficieCulture' =>
                                $cultureData['superficieCulture']
                                ?? null,

                            'rendementEstime' =>
                                $cultureData['rendementEstime']
                                ?? null,

                            'productionEstimee' =>
                                $cultureData['productionEstimee']
                                ?? null,

                            'estPrincipale' =>
                                $cultureData['estPrincipale']
                                ?? false,

                            'observations' =>
                                $cultureData['observations']
                                ?? null,
                        ]);

                        /*
                         * -------------------------------------------------
                         * 6. Intrants utilisés pour cette culture
                         * -------------------------------------------------
                         */
                        if (!empty($cultureData['intrants'])) {

                            foreach (
                                $cultureData['intrants']
                                as $intrantData
                            ) {

                                CultureIntrant::create([
                                    'culture_parcelle_id' =>
                                        $cultureParcelle
                                            ->idCultureParcelle,

                                    'intrant_id' =>
                                        $intrantData['intrant_id'],

                                    'quantite' =>
                                        $intrantData['quantite'],

                                    'nombreApplications' =>
                                        $intrantData['nombreApplications']
                                        ?? 1,

                                    'dateApplication' =>
                                        $intrantData['dateApplication']
                                        ?? null,

                                    'observations' =>
                                        $intrantData['observations']
                                        ?? null,
                                ]);
                            }
                        }
                    }
                }
            });

            return redirect()
                ->route(
                    'agent.recensements.menages.exploitants.parcelles.index',
                    [
                        'recensement' => $recensement->idRecensement,
                        'menage' => $menage->idMenage,
                        'exploitant' => $exploitant->idExploitant,
                        'exploitation' => $exploitation->idExploitation,
                    ]
                )
                ->with(
                    'success',
                    'La parcelle a été enregistrée avec succès.'
                );

        } catch (Throwable $e) {

            return back()
                ->withInput()
                ->withErrors([
                    'error' =>
                        'Impossible d’enregistrer la parcelle : '
                        . $e->getMessage(),
                ]);
        }
    }

    /**
     * Affiche une parcelle.
     */
    public function show(
        Recensement $recensement,
        Menage $menage,
        Exploitant $exploitant,
        Exploitation $exploitation,
        Parcelle $parcelle
    ): View {
        $this->verifierContexte(
            $recensement,
            $menage,
            $exploitant,
            $exploitation
        );

        $this->verifierParcelle(
            $parcelle,
            $exploitation
        );

        $parcelle->load([
            'exploitation.exploitant',
            'pointsGPS',
            'culturesParcelles.culture',
            'culturesParcelles.intrants.intrant',
        ]);

        return view('agent.recensements.menages.exploitants.parcelles.show', [
            'recensement' => $recensement,
            'menage' => $menage,
            'exploitant' => $exploitant,
            'exploitation' => $exploitation,
            'parcelle' => $parcelle,
        ]);
    }

    /**
     * Formulaire de modification.
     */
    public function edit(
        Recensement $recensement,
        Menage $menage,
        Exploitant $exploitant,
        Exploitation $exploitation,
        Parcelle $parcelle
    ): View {
        $this->verifierContexte(
            $recensement,
            $menage,
            $exploitant,
            $exploitation
        );

        $this->verifierParcelle(
            $parcelle,
            $exploitation
        );

        $parcelle->load([
            'pointsGPS',
            'culturesParcelles.culture',
            'culturesParcelles.intrants.intrant',
        ]);

        $cultures = Culture::query()
            ->orderBy('nomCulture')
            ->get();

        $intrants = Intrant::query()
            ->orderBy('nomIntrant')
            ->get();

        return view('agent.recensements.menages.exploitants.parcelles.edit', [
            'recensement' => $recensement,
            'menage' => $menage,
            'exploitant' => $exploitant,
            'exploitation' => $exploitation,
            'parcelle' => $parcelle,
            'cultures' => $cultures,
            'intrants' => $intrants,
        ]);
    }

    /**
     * Met à jour une parcelle.
     */
    public function update(
        UpdateParcelleRequest $request,
        Recensement $recensement,
        Menage $menage,
        Exploitant $exploitant,
        Exploitation $exploitation,
        Parcelle $parcelle
    ): RedirectResponse {
        $this->verifierContexte(
            $recensement,
            $menage,
            $exploitant,
            $exploitation
        );

        $this->verifierParcelle(
            $parcelle,
            $exploitation
        );

        $data = $request->validated();

        try {
            DB::transaction(function () use (
                $data,
                $parcelle
            ) {

                /*
                 * ---------------------------------------------------------
                 * 1. GPS
                 * ---------------------------------------------------------
                 */
                $pointsGPS = null;

                if (!empty($data['points_gps'])) {
                    $pointsGPS = is_string($data['points_gps'])
                        ? json_decode($data['points_gps'], true)
                        : $data['points_gps'];

                    if (
                        !is_array($pointsGPS) ||
                        count($pointsGPS) < 3
                    ) {
                        throw new \RuntimeException(
                            'Le contour GPS doit contenir au moins 3 points.'
                        );
                    }
                }

                /*
                 * ---------------------------------------------------------
                 * 2. Mise à jour de la parcelle
                 * ---------------------------------------------------------
                 *
                 * Le numéro de parcelle n'est jamais modifié manuellement.
                 */
                $parcelle->update([
                    'superficie' =>
                        $data['superficie']
                        ?? $parcelle->superficie,

                    'statutParcelle' =>
                        $data['statutParcelle']
                        ?? $parcelle->statutParcelle,

                    'typeSol' =>
                        $data['typeSol']
                        ?? null,

                    'modeFaireValoir' =>
                        $data['modeFaireValoir']
                        ?? null,

                    'modeIrrigation' =>
                        $data['modeIrrigation']
                        ?? 'pluvial',

                    'presenceArbres' =>
                        $data['presenceArbres']
                        ?? false,

                    'observations' =>
                        $data['observations']
                        ?? null,
                ]);

                /*
                 * ---------------------------------------------------------
                 * 3. Remplacement des points GPS
                 * ---------------------------------------------------------
                 */
                if ($pointsGPS !== null) {

                    $parcelle->pointsGPS()->delete();

                    foreach ($pointsGPS as $index => $point) {

                        if (
                            !isset($point['latitude']) ||
                            !isset($point['longitude'])
                        ) {
                            continue;
                        }

                        PointGPS::create([
                            'uid' => (string) Str::uuid(),
                            'parcelle_id' =>
                                $parcelle->idParcelle,

                            'latitude' =>
                                $point['latitude'],

                            'longitude' =>
                                $point['longitude'],

                            'precisionGPS' =>
                                $point['precisionGPS']
                                ?? $point['accuracy']
                                ?? null,

                            'ordre' => $index + 1,
                        ]);
                    }
                }

                /*
                 * ---------------------------------------------------------
                 * 4. Cultures
                 * ---------------------------------------------------------
                 *
                 * On remplace les anciennes cultures par les nouvelles.
                 */
                $parcelle->culturesParcelles()
                    ->each(function ($cultureParcelle) {
                        $cultureParcelle->intrants()->delete();
                    });

                $parcelle->culturesParcelles()->delete();

                if (
                    ($data['statutParcelle'] ?? $parcelle->statutParcelle)
                    === 'exploitee'
                    && !empty($data['cultures'])
                ) {

                    foreach ($data['cultures'] as $cultureData) {

                        $cultureParcelle =
                            CultureParcelle::create([
                                'uid' => (string) Str::uuid(),

                                'parcelle_id' =>
                                    $parcelle->idParcelle,

                                'culture_id' =>
                                    $cultureData['culture_id'],

                                'campagneAgricole' =>
                                    $cultureData['campagneAgricole'],

                                'modeCulture' =>
                                    $cultureData['modeCulture'],

                                'superficieCulture' =>
                                    $cultureData['superficieCulture']
                                    ?? null,

                                'rendementEstime' =>
                                    $cultureData['rendementEstime']
                                    ?? null,

                                'productionEstimee' =>
                                    $cultureData['productionEstimee']
                                    ?? null,

                                'estPrincipale' =>
                                    $cultureData['estPrincipale']
                                    ?? false,

                                'observations' =>
                                    $cultureData['observations']
                                    ?? null,
                            ]);

                        if (!empty($cultureData['intrants'])) {

                            foreach (
                                $cultureData['intrants']
                                as $intrantData
                            ) {

                                CultureIntrant::create([
                                    'culture_parcelle_id' =>
                                        $cultureParcelle
                                            ->idCultureParcelle,

                                    'intrant_id' =>
                                        $intrantData['intrant_id'],

                                    'quantite' =>
                                        $intrantData['quantite'],

                                    'nombreApplications' =>
                                        $intrantData['nombreApplications']
                                        ?? 1,

                                    'dateApplication' =>
                                        $intrantData['dateApplication']
                                        ?? null,

                                    'observations' =>
                                        $intrantData['observations']
                                        ?? null,
                                ]);
                            }
                        }
                    }
                }
            });

            return redirect()
                ->route(
                    'agent.recensements.menages.exploitants.parcelles.show',
                    [
                        'recensement' => $recensement->idRecensement,
                        'menage' => $menage->idMenage,
                        'exploitant' => $exploitant->idExploitant,
                        'exploitation' => $exploitation->idExploitation,
                        'parcelle' => $parcelle->idParcelle,
                    ]
                )
                ->with(
                    'success',
                    'La parcelle a été modifiée avec succès.'
                );

        } catch (Throwable $e) {

            return back()
                ->withInput()
                ->withErrors([
                    'error' =>
                        'Impossible de modifier la parcelle : '
                        . $e->getMessage(),
                ]);
        }
    }

    /**
     * Supprime une parcelle.
     */
    public function destroy(
        Recensement $recensement,
        Menage $menage,
        Exploitant $exploitant,
        Exploitation $exploitation,
        Parcelle $parcelle
    ): RedirectResponse {
        $this->verifierContexte(
            $recensement,
            $menage,
            $exploitant,
            $exploitation
        );

        $this->verifierParcelle(
            $parcelle,
            $exploitation
        );

        try {

            DB::transaction(function () use ($parcelle) {

                /*
                 * Les relations dépendantes sont supprimées
                 * avant la parcelle.
                 */
                $parcelle->culturesParcelles()
                    ->each(function ($cultureParcelle) {
                        $cultureParcelle->intrants()->delete();
                    });

                $parcelle->culturesParcelles()->delete();

                $parcelle->pointsGPS()->delete();

                $parcelle->delete();
            });

            return redirect()
                ->route(
                    'agent.recensements.menages.exploitants.parcelles.index',
                    [
                        'recensement' => $recensement->idRecensement,
                        'menage' => $menage->idMenage,
                        'exploitant' => $exploitant->idExploitant,
                        'exploitation' => $exploitation->idExploitation,
                    ]
                )
                ->with(
                    'success',
                    'La parcelle a été supprimée avec succès.'
                );

        } catch (Throwable $e) {

            return back()
                ->withErrors([
                    'error' =>
                        'Impossible de supprimer la parcelle : '
                        . $e->getMessage(),
                ]);
        }
    }

    /**
     * Génère automatiquement le numéro de parcelle.
     *
     * Exemple :
     * P-001
     * P-002
     * P-003
     */
    private function genererNumeroParcelle(
        Exploitation $exploitation
    ): string {

        $dernierNumero = $exploitation->parcelles()
            ->where('numeroParcelle', 'like', 'P-%')
            ->get(['numeroParcelle'])
            ->map(function ($parcelle) {

                if (
                    preg_match(
                        '/^P-(\d+)$/',
                        $parcelle->numeroParcelle,
                        $matches
                    )
                ) {
                    return (int) $matches[1];
                }

                return 0;
            })
            ->max();

        $numero = ($dernierNumero ?? 0) + 1;

        return 'P-' . str_pad(
            $numero,
            3,
            '0',
            STR_PAD_LEFT
        );
    }

    /**
     * Vérifie que l'exploitation appartient bien
     * à l'exploitant et au recensement courant.
     */
    private function verifierContexte(
        Recensement $recensement,
        Menage $menage,
        Exploitant $exploitant,
        Exploitation $exploitation
    ): void {

        /*
         * Le ménage doit appartenir au recensement.
         */
        if (
            (int) $menage->recensement_id
            !== (int) $recensement->idRecensement
        ) {
            abort(403, 'Ce ménage n’appartient pas à ce recensement.');
        }

        /*
         * L'exploitant doit appartenir au ménage.
         */
        if (
            (int) $exploitant->menage_id
            !== (int) $menage->idMenage
        ) {
            abort(403, 'Cet exploitant n’appartient pas à ce ménage.');
        }

        /*
         * L'exploitation doit appartenir à l'exploitant.
         */
        if (
            (int) $exploitation->exploitant_id
            !== (int) $exploitant->idExploitant
        ) {
            abort(
                403,
                'Cette exploitation n’appartient pas à cet exploitant.'
            );
        }

        /*
         * Vérification supplémentaire du recensement.
         */
        if (
            isset($exploitation->recensement_id)
            && (int) $exploitation->recensement_id
            !== (int) $recensement->idRecensement
        ) {
            abort(
                403,
                'Cette exploitation n’appartient pas au recensement courant.'
            );
        }
    }

    /**
     * Vérifie que la parcelle appartient bien
     * à l'exploitation demandée.
     */
    private function verifierParcelle(
        Parcelle $parcelle,
        Exploitation $exploitation
    ): void {

        if (
            (int) $parcelle->exploitation_id
            !== (int) $exploitation->idExploitation
        ) {
            abort(
                403,
                'Cette parcelle n’appartient pas à cette exploitation.'
            );
        }
    }
}