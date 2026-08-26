<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCampagnePlanificationRequest;
use App\Models\CampagneDeploiement;
use App\Models\CampagnePlanification;
use App\Models\RattachementPrefecture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlanificationController extends Controller
{
    /**
     * --------------------------------------------------------------------------
     * LISTE DES CAMPAGNES À PLANIFIER
     * --------------------------------------------------------------------------
     *
     * Seules les campagnes :
     * - destinées à la préfecture de l'utilisateur ;
     * - réceptionnées ;
     * sont affichées.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | RATTACHEMENT PRÉFECTORAL ACTIF
        |--------------------------------------------------------------------------
        */

        $rattachement = $this->rattachementActif($user->id);

        if (!$rattachement) {
            return view('dpa.planification.index', [
                'deploiements' => collect(),
                'prefecture'   => null,
                'search'       => $request->search,
            ])->with(
                'error',
                'Aucun rattachement préfectoral actif n’est associé à votre compte.'
            );
        }

        $prefecture = $rattachement->prefecture;

        $search = $request->search;

        /*
        |--------------------------------------------------------------------------
        | CAMPAGNES RÉCEPTIONNÉES
        |--------------------------------------------------------------------------
        */

        $deploiements = CampagneDeploiement::query()
            ->with([
                'campagne.structure',
                'campagne.createur',
                'campagne.planifications',
                'prefecture',
                'recuPar',
            ])

            ->where(
                'prefecture_id',
                $prefecture->idPrefecture
            )

            ->where(
                'statut',
                'recue'
            )

            ->when(
                $search,
                function ($query) use ($search) {

                    $query->whereHas(
                        'campagne',
                        function ($q) use ($search) {

                            $q->where(function ($subQuery) use ($search) {

                                $subQuery
                                    ->where(
                                        'libelle',
                                        'like',
                                        "%{$search}%"
                                    )
                                    ->orWhere(
                                        'codeCampagne',
                                        'like',
                                        "%{$search}%"
                                    );

                            });
                        }
                    );
                }
            )

            ->latest('dateReception')

            ->paginate(10)

            ->withQueryString();

        return view(
            'dpa.planification.index',
            compact(
                'deploiements',
                'prefecture',
                'search'
            )
        );
    }


    /**
     * --------------------------------------------------------------------------
     * FORMULAIRE DE CRÉATION
     * --------------------------------------------------------------------------
     */
    public function create(CampagneDeploiement $deploiement)
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | RATTACHEMENT
        |--------------------------------------------------------------------------
        */

        $rattachement = $this->rattachementActif($user->id);

        if (!$rattachement) {
            abort(
                403,
                'Aucun rattachement préfectoral actif n’est associé à votre compte.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SÉCURITÉ TERRITORIALE
        |--------------------------------------------------------------------------
        */

        $this->verifierDeploiement(
            $deploiement,
            $rattachement->prefecture_id
        );

        /*
        |--------------------------------------------------------------------------
        | LA CAMPAGNE DOIT ÊTRE RÉCEPTIONNÉE
        |--------------------------------------------------------------------------
        */

        if ($deploiement->statut !== 'recue') {

            return redirect()
                ->route('dpa.planification.index')
                ->with(
                    'error',
                    'Cette campagne doit être réceptionnée avant de pouvoir être planifiée.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | UNE SEULE PLANIFICATION PAR CAMPAGNE / PRÉFECTURE
        |--------------------------------------------------------------------------
        */

        $planification = CampagnePlanification::query()
            ->where(
                'campagne_id',
                $deploiement->campagne_id
            )
            ->where(
                'prefecture_id',
                $deploiement->prefecture_id
            )
            ->first();

        if ($planification) {

            return redirect()
                ->route(
                    'dpa.planification.show',
                    $planification
                )
                ->with(
                    'info',
                    'Une planification existe déjà pour cette campagne dans votre préfecture.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | CHARGEMENT DES DONNÉES
        |--------------------------------------------------------------------------
        */

        $deploiement->load([
            'campagne.structure',
            'campagne.createur',
            'campagne.zones',
            'prefecture',
            'recuPar',
        ]);

        return view(
            'dpa.planification.create',
            compact('deploiement')
        );
    }


    /**
     * --------------------------------------------------------------------------
     * ENREGISTRER UNE PLANIFICATION
     * --------------------------------------------------------------------------
     */
    // public function store(
    //     Request $request,
    //     CampagneDeploiement $deploiement
    // ) {
    //     $user = auth()->user();

    //     /*
    //     |--------------------------------------------------------------------------
    //     | RATTACHEMENT
    //     |--------------------------------------------------------------------------
    //     */

    //     $rattachement = $this->rattachementActif($user->id);

    //     if (!$rattachement) {
    //         abort(
    //             403,
    //             'Aucun rattachement préfectoral actif n’est associé à votre compte.'
    //         );
    //     }

    //     /*
    //     |--------------------------------------------------------------------------
    //     | SÉCURITÉ TERRITORIALE
    //     |--------------------------------------------------------------------------
    //     */

    //     $this->verifierDeploiement(
    //         $deploiement,
    //         $rattachement->prefecture_id
    //     );

    //     /*
    //     |--------------------------------------------------------------------------
    //     | CAMPAGNE RÉCEPTIONNÉE
    //     |--------------------------------------------------------------------------
    //     */

    //     if ($deploiement->statut !== 'recue') {

    //         return back()
    //             ->withInput()
    //             ->with(
    //                 'error',
    //                 'La campagne doit être réceptionnée avant sa planification.'
    //             );
    //     }

    //     /*
    //     |--------------------------------------------------------------------------
    //     | VALIDATION
    //     |--------------------------------------------------------------------------
    //     */

    //     $validated = $request->validate([

    //         'dateDebut' => [
    //             'nullable',
    //             'date',
    //         ],

    //         'dateFin' => [
    //             'nullable',
    //             'date',
    //             'after_or_equal:dateDebut',
    //         ],

    //         'observations' => [
    //             'nullable',
    //             'string',
    //             'max:5000',
    //         ],

    //     ]);

    //     /*
    //     |--------------------------------------------------------------------------
    //     | CAMPAGNE
    //     |--------------------------------------------------------------------------
    //     */

    //     $campagne = $deploiement->campagne;

    //     /*
    //     |--------------------------------------------------------------------------
    //     | VÉRIFICATION DATE DE DÉBUT
    //     |--------------------------------------------------------------------------
    //     */

    //     if (
    //         !empty($validated['dateDebut']) &&
    //         $campagne->dateDebut &&
    //         strtotime($validated['dateDebut']) <
    //         strtotime($campagne->dateDebut)
    //     ) {

    //         return back()
    //             ->withInput()
    //             ->with(
    //                 'error',
    //                 'La date de début de la planification ne peut pas être antérieure au début de la campagne.'
    //             );
    //     }

    //     /*
    //     |--------------------------------------------------------------------------
    //     | VÉRIFICATION DATE DE FIN
    //     |--------------------------------------------------------------------------
    //     */

    //     if (
    //         !empty($validated['dateFin']) &&
    //         $campagne->dateFin &&
    //         strtotime($validated['dateFin']) >
    //         strtotime($campagne->dateFin)
    //     ) {

    //         return back()
    //             ->withInput()
    //             ->with(
    //                 'error',
    //                 'La date de fin de la planification ne peut pas dépasser la fin de la campagne.'
    //             );
    //     }

    //     /*
    //     |--------------------------------------------------------------------------
    //     | PROTECTION CONTRE LE DOUBLE ENREGISTREMENT
    //     |--------------------------------------------------------------------------
    //     */

    //     $existe = CampagnePlanification::query()
    //         ->where(
    //             'campagne_id',
    //             $deploiement->campagne_id
    //         )
    //         ->where(
    //             'prefecture_id',
    //             $deploiement->prefecture_id
    //         )
    //         ->exists();

    //     if ($existe) {

    //         return redirect()
    //             ->route('dpa.planification.index')
    //             ->with(
    //                 'error',
    //                 'Une planification existe déjà pour cette campagne dans votre préfecture.'
    //             );
    //     }

    //     /*
    //     |--------------------------------------------------------------------------
    //     | CRÉATION
    //     |--------------------------------------------------------------------------
    //     */

    //     $planification = DB::transaction(
    //         function () use (
    //             $validated,
    //             $deploiement,
    //             $user
    //         ) {

    //             return CampagnePlanification::create([

    //                 'campagne_id' => $deploiement->campagne_id,

    //                 'prefecture_id' => $deploiement->prefecture_id,

    //                 'planifie_par' => $user->id,

    //                 'dateDebut' =>
    //                     $validated['dateDebut'] ?? null,

    //                 'dateFin' =>
    //                     $validated['dateFin'] ?? null,

    //                 'statut' => 'brouillon',

    //                 'observations' =>
    //                     $validated['observations'] ?? null,
    //             ]);
    //         }
    //     );

    //     /*
    //     |--------------------------------------------------------------------------
    //     | REDIRECTION
    //     |--------------------------------------------------------------------------
    //     */

    //     return redirect()
    //         ->route(
    //             'dpa.planification.show',
    //             $planification
    //         )
    //         ->with(
    //             'success',
    //             'La planification a été créée avec succès.'
    //         );
    // }



    /**
     * Enregistrer une nouvelle planification.
     */
    public function store(
        StoreCampagnePlanificationRequest $request,
        CampagneDeploiement $deploiement
    ) {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | RATTACHEMENT PRÉFECTORAL
        |--------------------------------------------------------------------------
        */

        $rattachement = $this->rattachementActif($user->id);

        if (!$rattachement) {
            abort(
                403,
                'Aucun rattachement préfectoral actif n’est associé à votre compte.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SÉCURITÉ TERRITORIALE
        |--------------------------------------------------------------------------
        */

        $this->verifierDeploiement(
            $deploiement,
            $rattachement->prefecture_id
        );

        /*
        |--------------------------------------------------------------------------
        | LA CAMPAGNE DOIT ÊTRE RÉCEPTIONNÉE
        |--------------------------------------------------------------------------
        */

        if ($deploiement->statut !== 'recue') {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'La campagne doit être réceptionnée avant sa planification.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | DONNÉES VALIDÉES
        |--------------------------------------------------------------------------
        */

        $validated = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | CAMPAGNE
        |--------------------------------------------------------------------------
        */

        $campagne = $deploiement->campagne;

        /*
        |--------------------------------------------------------------------------
        | CONVERSION DES DATES
        |--------------------------------------------------------------------------
        */

        $dateDebut = !empty($validated['dateDebut'])
            ? \Carbon\Carbon::createFromFormat(
                'Y-m-d\TH:i',
                $validated['dateDebut']
            )
            : null;

        $dateFin = !empty($validated['dateFin'])
            ? \Carbon\Carbon::createFromFormat(
                'Y-m-d\TH:i',
                $validated['dateFin']
            )
            : null;

        /*
        |--------------------------------------------------------------------------
        | DATE DE DÉBUT PAR RAPPORT À LA CAMPAGNE
        |--------------------------------------------------------------------------
        */

        if (
            $dateDebut &&
            $campagne->dateDebut &&
            $dateDebut->lt($campagne->dateDebut)
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'La date de début de la planification ne peut pas être antérieure au début de la campagne.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | DATE DE FIN PAR RAPPORT À LA CAMPAGNE
        |--------------------------------------------------------------------------
        */

        if (
            $dateFin &&
            $campagne->dateFin &&
            $dateFin->gt($campagne->dateFin)
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'La date de fin de la planification ne peut pas dépasser la fin de la campagne.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | UNE SEULE PLANIFICATION PAR CAMPAGNE / PRÉFECTURE
        |--------------------------------------------------------------------------
        */

        $existe = CampagnePlanification::query()
            ->where(
                'campagne_id',
                $deploiement->campagne_id
            )
            ->where(
                'prefecture_id',
                $deploiement->prefecture_id
            )
            ->exists();

        if ($existe) {
            return redirect()
                ->route('dpa.planification.index')
                ->with(
                    'error',
                    'Une planification existe déjà pour cette campagne dans votre préfecture.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | CRÉATION
        |--------------------------------------------------------------------------
        */

        $planification = DB::transaction(function () use (
            $validated,
            $deploiement,
            $user,
            $dateDebut,
            $dateFin
        ) {

            return CampagnePlanification::create([

                'campagne_id' => $deploiement->campagne_id,

                'prefecture_id' => $deploiement->prefecture_id,

                'planifie_par' => $user->id,

                'dateDebut' => $dateDebut,

                'dateFin' => $dateFin,

                'statut' => 'brouillon',

                'observations' =>
                    $validated['observations'] ?? null,
            ]);
        });

        /*
        |--------------------------------------------------------------------------
        | REDIRECTION
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'dpa.planification.show',
                $planification
            )
            ->with(
                'success',
                'La planification a été créée avec succès.'
            );
    }


    /**
     * --------------------------------------------------------------------------
     * AFFICHER UNE PLANIFICATION
     * --------------------------------------------------------------------------
     */
    public function show(CampagnePlanification $planification)
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | RATTACHEMENT
        |--------------------------------------------------------------------------
        */

        $rattachement = $this->rattachementActif($user->id);

        if (!$rattachement) {
            abort(
                403,
                'Aucun rattachement préfectoral actif n’est associé à votre compte.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SÉCURITÉ TERRITORIALE
        |--------------------------------------------------------------------------
        */

        $this->verifierPlanification(
            $planification,
            $rattachement->prefecture_id
        );

        /*
        |--------------------------------------------------------------------------
        | CHARGEMENT
        |--------------------------------------------------------------------------
        */

        $planification->load([
            'campagne.structure',
            'campagne.createur',
            'prefecture',
            'planifiePar',
            'validePar',

            'etapes' => function ($query) {

                $query->orderBy('ordre');

            },
        ]);

        return view(
            'dpa.planification.show',
            compact('planification')
        );
    }


    /**
     * --------------------------------------------------------------------------
     * FORMULAIRE DE MODIFICATION
     * --------------------------------------------------------------------------
     */
    public function edit(CampagnePlanification $planification)
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | RATTACHEMENT
        |--------------------------------------------------------------------------
        */

        $rattachement = $this->rattachementActif($user->id);

        if (!$rattachement) {
            abort(
                403,
                'Aucun rattachement préfectoral actif n’est associé à votre compte.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SÉCURITÉ
        |--------------------------------------------------------------------------
        */

        $this->verifierPlanification(
            $planification,
            $rattachement->prefecture_id
        );

        /*
        |--------------------------------------------------------------------------
        | SEUL UN BROUILLON EST MODIFIABLE
        |--------------------------------------------------------------------------
        */

        if ($planification->statut !== 'brouillon') {

            return redirect()
                ->route(
                    'dpa.planification.show',
                    $planification
                )
                ->with(
                    'info',
                    'Cette planification n’est plus au stade brouillon et ne peut plus être modifiée.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | CHARGEMENT
        |--------------------------------------------------------------------------
        */

        $planification->load([
            'campagne',
            'prefecture',

            'etapes' => function ($query) {

                $query->orderBy('ordre');

            },
        ]);

        return view(
            'dpa.planification.edit',
            compact('planification')
        );
    }


    /**
     * --------------------------------------------------------------------------
     * MODIFIER UNE PLANIFICATION
     * --------------------------------------------------------------------------
     */
    public function update(
        Request $request,
        CampagnePlanification $planification
    ) {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | RATTACHEMENT
        |--------------------------------------------------------------------------
        */

        $rattachement = $this->rattachementActif($user->id);

        if (!$rattachement) {
            abort(
                403,
                'Aucun rattachement préfectoral actif n’est associé à votre compte.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SÉCURITÉ
        |--------------------------------------------------------------------------
        */

        $this->verifierPlanification(
            $planification,
            $rattachement->prefecture_id
        );

        /*
        |--------------------------------------------------------------------------
        | BROUILLON UNIQUEMENT
        |--------------------------------------------------------------------------
        */

        if ($planification->statut !== 'brouillon') {

            return back()
                ->with(
                    'error',
                    'Cette planification ne peut plus être modifiée.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'dateDebut' => [
                'nullable',
                'date',
            ],

            'dateFin' => [
                'nullable',
                'date',
                'after_or_equal:dateDebut',
            ],

            'observations' => [
                'nullable',
                'string',
                'max:5000',
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | CAMPAGNE
        |--------------------------------------------------------------------------
        */

        $campagne = $planification->campagne;

        /*
        |--------------------------------------------------------------------------
        | DATE DE DÉBUT
        |--------------------------------------------------------------------------
        */

        if (
            !empty($validated['dateDebut']) &&
            $campagne->dateDebut &&
            strtotime($validated['dateDebut']) <
            strtotime($campagne->dateDebut)
        ) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'La date de début de la planification ne peut pas être antérieure au début de la campagne.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | DATE DE FIN
        |--------------------------------------------------------------------------
        */

        if (
            !empty($validated['dateFin']) &&
            $campagne->dateFin &&
            strtotime($validated['dateFin']) >
            strtotime($campagne->dateFin)
        ) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'La date de fin de la planification ne peut pas dépasser la fin de la campagne.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | MISE À JOUR
        |--------------------------------------------------------------------------
        */

        $planification->update([

            'dateDebut' =>
                $validated['dateDebut'] ?? null,

            'dateFin' =>
                $validated['dateFin'] ?? null,

            'observations' =>
                $validated['observations'] ?? null,

        ]);

        /*
        |--------------------------------------------------------------------------
        | REDIRECTION
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'dpa.planification.show',
                $planification
            )
            ->with(
                'success',
                'La planification a été mise à jour avec succès.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */


    /**
     * Récupérer le rattachement préfectoral actif.
     */
    private function rattachementActif(
        int $userId
    ): ?RattachementPrefecture {

        return RattachementPrefecture::query()

            ->where(
                'user_id',
                $userId
            )

            ->where(
                'statut',
                'actif'
            )

            ->whereNull(
                'dateFin'
            )

            ->with(
                'prefecture'
            )

            ->first();
    }


    /**
     * Vérifier qu'un déploiement appartient
     * à la préfecture de l'utilisateur.
     */
    private function verifierDeploiement(
        CampagneDeploiement $deploiement,
        int $prefectureId
    ): void {

        if (
            (int) $deploiement->prefecture_id
            !==
            (int) $prefectureId
        ) {

            abort(
                403,
                'Cette campagne ne concerne pas votre préfecture.'
            );
        }
    }


    /**
     * Vérifier qu'une planification appartient
     * à la préfecture de l'utilisateur.
     */
    private function verifierPlanification(
        CampagnePlanification $planification,
        int $prefectureId
    ): void {

        if (
            (int) $planification->prefecture_id
            !==
            (int) $prefectureId
        ) {

            abort(
                403,
                'Cette planification ne concerne pas votre préfecture.'
            );
        }
    }




    
}