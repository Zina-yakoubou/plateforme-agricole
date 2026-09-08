<?php

namespace App\Http\Controllers\Dpa;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlanificationPrefectoraleRequest;
use App\Models\CampagneDeploiement;
use App\Models\PlanificationPrefectorale;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PlanificationPrefectoraleController extends Controller
{
    /**
     * ================================================================
     * LISTE DES PLANIFICATIONS PRÉFECTORALES
     * ================================================================
     *
     * Un DPA ne voit que les planifications de sa propre préfecture.
     */
    public function index(): View
    {
        $user = Auth::user();

        $this->verifierDpa($user);

        $prefecture = $this->getPrefectureUtilisateur($user);

        /*
         * La planification appartient à la préfecture
         * du déploiement.
         */
        $planifications = PlanificationPrefectorale::with([
                'deploiement.campagne',
                'deploiement.prefecture',
                'besoins',
            ])
            ->whereHas('deploiement', function ($query) use ($prefecture) {
                $query->where(
                    'prefecture_id',
                    $prefecture->idPrefecture
                );
            })
            ->latest()
            ->paginate(15);

        return view(
            'dpa.planifications_prefectorales.index',
            compact(
                'planifications',
                'prefecture'
            )
        );
    }


    /**
     * ================================================================
     * FORMULAIRE DE CRÉATION
     * ================================================================
     */
    public function create(
        CampagneDeploiement $deploiement
    ): View {

        $user = Auth::user();

        $this->verifierDpa($user);

        $prefectureUtilisateur =
            $this->getPrefectureUtilisateur($user);

        /*
         * Le DPA ne peut travailler que sur un déploiement
         * de sa propre préfecture.
         */
        $this->verifierDeploiementPrefecture(
            $deploiement,
            $prefectureUtilisateur
        );

        $deploiement->load([
            'campagne',
            'prefecture',
            'planificationPrefectorale',
        ]);

        $prefecture = $deploiement->prefecture;

        if (!$prefecture) {
            abort(
                404,
                'La préfecture du déploiement est introuvable.'
            );
        }

        /*
         * Si une planification existe déjà, on la charge.
         */
        $planification =
            $deploiement->planificationPrefectorale;

        if ($planification) {
            $planification->load([
                'besoins',
                'adaptationsActivites',
            ]);
        }

        return view(
            'dpa.planifications_prefectorales.create',
            [
                'deploiement' => $deploiement,

                'planification' => $planification,

                'besoins' =>
                    $planification?->besoins ?? collect(),
            ]
        );
    }


    /**
     * ================================================================
     * ENREGISTRER UNE PLANIFICATION
     * ================================================================
     */
    public function store(
        StorePlanificationPrefectoraleRequest $request,
        CampagneDeploiement $deploiement
    ): RedirectResponse {

        $user = Auth::user();

        $this->verifierDpa($user);

        $prefecture =
            $this->getPrefectureUtilisateur($user);

        /*
         * Vérification du périmètre préfectoral.
         */
        $this->verifierDeploiementPrefecture(
            $deploiement,
            $prefecture
        );

        /*
         * Une seule planification par déploiement.
         */
        if ($deploiement->planificationPrefectorale) {

            return redirect()
                ->route(
                    'dpa.planifications-prefectorales.show',
                    $deploiement->planificationPrefectorale
                )
                ->with(
                    'info',
                    'Cette planification existe déjà.'
                );
        }

        $validated = $request->validated();

        DB::transaction(function () use (
            $validated,
            $deploiement,
            $user
        ) {

            $planification =
                PlanificationPrefectorale::create([
                    'deploiement_id' =>
                        $deploiement->idDeploiement,

                    'planifie_par' =>
                        $user->id,

                    'planTravail' =>
                        $validated['planTravail'] ?? null,

                    'observations' =>
                        $validated['observations'] ?? null,

                    'statut' =>
                        'planifier',
                ]);

            /*
             * Enregistrement des besoins.
             */
            $this->enregistrerBesoins(
                $planification,
                $validated['besoins'] ?? []
            );
        });

        return redirect()
            ->route(
                'dpa.planifications-prefectorales.index'
            )
            ->with(
                'success',
                'La planification préfectorale a été créée avec succès.'
            );
    }


    /**
     * ================================================================
     * AFFICHER UNE PLANIFICATION
     * ================================================================
     *
     * Le DPA peut uniquement consulter une planification
     * appartenant à sa préfecture.
     */
    public function show(
        PlanificationPrefectorale $planificationPrefectorale
    ): View {

        $user = Auth::user();

        $this->verifierDpa($user);

        $prefecture =
            $this->getPrefectureUtilisateur($user);

        $planificationPrefectorale->load([
            'deploiement.campagne',
            'deploiement.prefecture',
            'besoins',
            'adaptationsActivites',
        ]);

        /*
         * Vérification de la préfecture.
         */
        if (
            !$planificationPrefectorale->deploiement
        ) {
            abort(
                404,
                'Le déploiement associé à cette planification est introuvable.'
            );
        }

        $this->verifierDeploiementPrefecture(
            $planificationPrefectorale->deploiement,
            $prefecture
        );

        return view(
            'dpa.planifications_prefectorales.show',
            compact(
                'planificationPrefectorale'
            )
        );
    }


    /**
     * ================================================================
     * FORMULAIRE DE MODIFICATION
     * ================================================================
     */
    public function edit(
        PlanificationPrefectorale $planificationPrefectorale
    ): View {

        $user = Auth::user();

        $this->verifierDpa($user);

        $prefecture =
            $this->getPrefectureUtilisateur($user);

        $planificationPrefectorale->load([
            'deploiement',
            'besoins',
            'adaptationsActivites',
        ]);

        $deploiement =
            $planificationPrefectorale->deploiement;

        if (!$deploiement) {
            abort(
                404,
                'Le déploiement associé à cette planification est introuvable.'
            );
        }

        /*
         * Seul le DPA de la préfecture concernée
         * peut modifier.
         */
        $this->verifierDeploiementPrefecture(
            $deploiement,
            $prefecture
        );

        $deploiement->load([
            'campagne',
            'prefecture',
        ]);

        return view(
            'dpa.planifications_prefectorales.edit',
            [
                'planification' =>
                    $planificationPrefectorale,

                'planificationPrefectorale' =>
                    $planificationPrefectorale,

                'deploiement' =>
                    $deploiement,

                'besoins' =>
                    $planificationPrefectorale->besoins,
            ]
        );
    }


    /**
     * ================================================================
     * METTRE À JOUR
     * ================================================================
     */
    public function update(
        StorePlanificationPrefectoraleRequest $request,
        PlanificationPrefectorale $planificationPrefectorale
    ): RedirectResponse {

        $user = Auth::user();

        $this->verifierDpa($user);

        $prefecture =
            $this->getPrefectureUtilisateur($user);

        $planificationPrefectorale->load([
            'deploiement',
            'besoins',
        ]);

        $deploiement =
            $planificationPrefectorale->deploiement;

        if (!$deploiement) {
            abort(
                404,
                'Le déploiement associé à cette planification est introuvable.'
            );
        }

        /*
         * Vérification du périmètre.
         */
        $this->verifierDeploiementPrefecture(
            $deploiement,
            $prefecture
        );

        $validated = $request->validated();

        DB::transaction(function () use (
            $validated,
            $planificationPrefectorale
        ) {

            $planificationPrefectorale->update([
                'planTravail' =>
                    $validated['planTravail'] ?? null,

                'observations' =>
                    $validated['observations'] ?? null,
            ]);

            /*
             * On supprime les anciens besoins.
             */
            $planificationPrefectorale
                ->besoins()
                ->delete();

            /*
             * Puis on recrée ceux du formulaire.
             */
            $this->enregistrerBesoins(
                $planificationPrefectorale,
                $validated['besoins'] ?? []
            );
        });

        return redirect()
            ->route(
                'dpa.planifications-prefectorales.index'
            )
            ->with(
                'success',
                'La planification préfectorale a été mise à jour avec succès.'
            );
    }


    /**
     * ================================================================
     * SUPPRIMER
     * ================================================================
     */
    public function destroy(
        PlanificationPrefectorale $planificationPrefectorale
    ): RedirectResponse {

        $user = Auth::user();

        $this->verifierDpa($user);

        $prefecture =
            $this->getPrefectureUtilisateur($user);

        $planificationPrefectorale->load(
            'deploiement'
        );

        $deploiement =
            $planificationPrefectorale->deploiement;

        if (!$deploiement) {
            abort(
                404,
                'Le déploiement associé à cette planification est introuvable.'
            );
        }

        /*
         * Seul le DPA de la préfecture peut supprimer.
         */
        $this->verifierDeploiementPrefecture(
            $deploiement,
            $prefecture
        );

        DB::transaction(function () use (
            $planificationPrefectorale
        ) {

            /*
             * Suppression des besoins.
             */
            $planificationPrefectorale
                ->besoins()
                ->delete();

            /*
             * Suppression de la planification.
             */
            $planificationPrefectorale->delete();
        });

        return redirect()
            ->route(
                'dpa.planifications-prefectorales.index'
            )
            ->with(
                'success',
                'La planification préfectorale a été supprimée avec succès.'
            );
    }


    /**
     * ================================================================
     * ENREGISTRER LES BESOINS
     * ================================================================
     */
    private function enregistrerBesoins(
        PlanificationPrefectorale $planification,
        array $besoins
    ): void {

        foreach ($besoins as $besoin) {

            /*
             * On ignore une ligne totalement vide.
             */
            if (
                empty($besoin['categorie'] ?? null) &&
                empty($besoin['designation'] ?? null)
            ) {
                continue;
            }

            $planification->besoins()->create([
                'categorie' =>
                    $besoin['categorie'] ?? null,

                'designation' =>
                    $besoin['designation'] ?? null,

                'quantite' =>
                    $besoin['quantite'] ?? 0,

                'unite' =>
                    $besoin['unite'] ?? null,

                'observations' =>
                    $besoin['observations'] ?? null,
            ]);
        }
    }


    /**
     * ================================================================
     * VÉRIFIER DPA
     * ================================================================
     */
    private function verifierDpa($user): void
    {
        if (
            !$user ||
            !is_callable([$user, 'isDpa']) ||
            !call_user_func([$user, 'isDpa'])
        ) {
            abort(
                403,
                'Accès réservé aux Directeurs préfectoraux.'
            );
        }
    }


    /**
     * ================================================================
     * RÉCUPÉRER LA PRÉFECTURE DU DPA
     * ================================================================
     */
    private function getPrefectureUtilisateur($user)
    {
        $prefecture = $user->prefectureActuelle;

        if (!$prefecture) {
            abort(
                403,
                'Aucune préfecture n’est rattachée à cet utilisateur.'
            );
        }

        return $prefecture;
    }


    /**
     * ================================================================
     * VÉRIFIER LE PÉRIMÈTRE PRÉFECTORAL
     * ================================================================
     *
     * Un déploiement appartient à une préfecture.
     *
     * La planification étant liée au déploiement,
     * elle appartient donc automatiquement à cette préfecture.
     */
    private function verifierDeploiementPrefecture(
        CampagneDeploiement $deploiement,
        $prefecture
    ): void {

        if (
            !$deploiement->prefecture_id ||
            (int) $deploiement->prefecture_id !==
                (int) $prefecture->idPrefecture
        ) {
            abort(
                403,
                'Cette planification appartient à une autre préfecture.'
            );
        }
    }
}