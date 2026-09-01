<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAffectationRequest;
use App\Models\Affectation;
use App\Models\CampagneRecensement;
use App\Models\CampagneDeploiement;
use App\Models\Canton;
use App\Models\Equipe;
use App\Models\Village;
use App\Models\RattachementPrefecture;
use App\Models\PlanificationPrefectorale;
use App\Models\PlanificationTerritoire;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AffectationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {
        $user = Auth::user();

        $prefectureId = $this->prefectureIdUtilisateur($user);

        if (!$prefectureId) {
            abort(
                403,
                'Aucune préfecture active n’est associée à votre compte.'
            );
        }

        $affectations = Affectation::query()
            ->with([
                'campagne',
                'equipe',
                'village.canton.commune',
            ])

            ->whereHas('equipe', function ($query) use ($prefectureId) {

                $query->whereHas(
                    'superviseur',
                    function ($q) use ($prefectureId) {

                        $q->whereHas(
                            'rattachementPrefecture',
                            function ($r) use ($prefectureId) {

                                $r->where(
                                    'prefecture_id',
                                    $prefectureId
                                )
                                ->where(
                                    'statut',
                                    'actif'
                                )
                                ->whereNull(
                                    'dateFin'
                                );
                            }
                        );
                    }
                );
            })

            ->when(
                $request->filled('campagne_id'),
                fn ($query) =>
                    $query->where(
                        'campagne_id',
                        $request->campagne_id
                    )
            )

            ->when(
                $request->filled('statut'),
                fn ($query) =>
                    $query->where(
                        'statut',
                        $request->statut
                    )
            )

            ->latest('idAffectation')
            ->paginate(15)
            ->withQueryString();

        return view(
            'dpa.affectations.index',
            compact('affectations')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(Equipe $equipe): View
{
    $user = Auth::user();

    /*
    |--------------------------------------------------------------------------
    | PRÉFECTURE DU DPA
    |--------------------------------------------------------------------------
    */

    $prefectureId = $this->prefectureIdUtilisateur($user);

    if (!$prefectureId) {
        abort(
            403,
            'Aucune préfecture active n’est associée à votre compte.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | VÉRIFIER L'ÉQUIPE
    |--------------------------------------------------------------------------
    */

    $this->verifierEquipePrefecture(
        $equipe,
        $prefectureId
    );

    /*
    |--------------------------------------------------------------------------
    | CAMPAGNES DÉPLOYÉES DANS LA PRÉFECTURE
    |--------------------------------------------------------------------------
    */

    $campagnes = CampagneRecensement::query()
        ->whereIn('statut', [
            'planifiee',
            'active',
        ])
        ->whereHas('deploiements', function ($query) use ($prefectureId) {
            $query->where('prefecture_id', $prefectureId);
        })
        ->with([
            'deploiements' => function ($query) use ($prefectureId) {
                $query->where('prefecture_id', $prefectureId);
            },
        ])
        ->orderByDesc('dateDebut')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | CANTONS DE LA PRÉFECTURE
    |--------------------------------------------------------------------------
    */

    $cantons = Canton::query()
        ->whereHas('commune', function ($query) use ($prefectureId) {
            $query->where('prefecture_id', $prefectureId);
        })
        ->with('commune')
        ->orderBy('nom')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | VILLAGES PLANIFIÉS
    |--------------------------------------------------------------------------
    |
    | On récupère directement toutes les planifications
    | de la préfecture.
    |
    | Aucun fetch().
    | Aucun appel API.
    |
    */

    $villagesPlanifies = PlanificationTerritoire::query()
        ->whereHas(
            'planificationPrefectorale',
            function ($query) use ($prefectureId) {

                $query->whereHas(
                    'deploiement',
                    function ($q) use ($prefectureId) {

                        $q->where(
                            'prefecture_id',
                            $prefectureId
                        );
                    }
                );
            }
        )
        ->whereNotNull('village_id')
        ->with([
            'village.canton.commune',
            'planificationPrefectorale.deploiement',
        ])
        ->get();

    /*
    |--------------------------------------------------------------------------
    | FORMAT POUR JAVASCRIPT
    |--------------------------------------------------------------------------
    */

    $campagnesJs = $campagnes
        ->map(function ($campagne) {

            return [
                'id' => $campagne->idCampagne,
                'code' => $campagne->codeCampagne,
                'libelle' => $campagne->libelle,
                'statut' => $campagne->statut,
            ];

        })
        ->values()
        ->all();

    $cantonsJs = $cantons
        ->map(function ($canton) {

            return [
                'id' => $canton->idCanton,
                'nom' => $canton->nom,
                'commune_id' => $canton->commune_id ?? null,
                'commune_nom' => optional($canton->commune)->nom,
            ];

        })
        ->values()
        ->all();

    /*
    |--------------------------------------------------------------------------
    | VILLAGES PLANIFIÉS POUR JS
    |--------------------------------------------------------------------------
    |
    | Structure :
    |
    | campagne_id
    | canton_id
    | village
    |
    */

    $villagesPlanifiesJs = $villagesPlanifies
        ->filter(function ($territoire) {

            return $territoire->village !== null;
        })
        ->map(function ($territoire) {

            $village = $territoire->village;

            /*
            | On récupère la campagne via le déploiement.
            */

            $campagneId = optional(
                optional(
                    $territoire->planificationPrefectorale
                )->deploiement
            )->campagne_id;

            return [
                'campagne_id' => $campagneId,
                'canton_id' => $village->canton_id,
                'idVillage' => $village->idVillage,
                'nom' => $village->nom,
                'code' => $village->code,
            ];

        })
        ->filter(function ($village) {

            return $village['campagne_id'] !== null
                && $village['canton_id'] !== null;
        })
        ->unique(function ($village) {

            return
                $village['campagne_id'] . '-' .
                $village['canton_id'] . '-' .
                $village['idVillage'];

        })
        ->sortBy('nom')
        ->values()
        ->all();

    /*
    |--------------------------------------------------------------------------
    | VUE
    |--------------------------------------------------------------------------
    */

    return view(
        'dpa.affectations.create',
        [
            'equipe' => $equipe,

            'campagnes' => $campagnes,

            'cantons' => $cantons,

            'campagnesJs' => $campagnesJs,

            'cantonsJs' => $cantonsJs,

            'villagesPlanifiesJs' => $villagesPlanifiesJs,

            'prefectureId' => $prefectureId,

            'affectation' => new Affectation(),
        ]
    );
}


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    // public function store(
    //     StoreAffectationRequest $request,
    //     Equipe $equipe
    //     ): RedirectResponse {

    //         $user = Auth::user();

    //         $prefectureId =
    //             $this->prefectureIdUtilisateur($user);

    //         if (!$prefectureId) {
    //             abort(
    //                 403,
    //                 'Aucune préfecture active n’est associée à votre compte.'
    //             );
    //         }


            
    //         $this->verifierEquipePrefecture(
    //             $equipe,
    //             $prefectureId
    //         );


    //         $validated =
    //             $request->validated();


        

    //         $campagne = CampagneRecensement::query()
    //             ->whereKey(
    //                 $validated['campagne_id']
    //             )
    //             ->whereIn(
    //                 'statut',
    //                 [
    //                     'planifiee',
    //                     'active',
    //                 ]
    //             )
    //             ->whereHas(
    //                 'deploiements',
    //                 function ($query) use ($prefectureId) {

    //                     $query->where(
    //                         'prefecture_id',
    //                         $prefectureId
    //                     );
    //                 }
    //             )
    //             ->first();


    //         if (!$campagne) {

    //             return back()
    //                 ->withInput()
    //                 ->withErrors([
    //                     'campagne_id' =>
    //                         'La campagne sélectionnée n’est pas déployée dans votre préfecture.',
    //                 ]);
    //         }


        

    //         $canton = Canton::query()
    //             ->whereKey(
    //                 $validated['canton_id']
    //             )
    //             ->whereHas(
    //                 'commune',
    //                 function ($query) use ($prefectureId) {

    //                     $query->where(
    //                         'prefecture_id',
    //                         $prefectureId
    //                     );
    //                 }
    //             )
    //             ->first();


    //         if (!$canton) {

    //             return back()
    //                 ->withInput()
    //                 ->withErrors([
    //                     'canton_id' =>
    //                         'Le canton sélectionné n’appartient pas à votre préfecture.',
    //                 ]);
    //         }


        

    //         $villageIds = array_values(
    //             array_unique(
    //                 $validated['village_ids']
    //             )
    //         );


        

    //         $villages = Village::query()
    //             ->whereIn(
    //                 'idVillage',
    //                 $villageIds
    //             )
    //             ->where(
    //                 'canton_id',
    //                 $canton->idCanton
    //             )
    //             ->get();


    //         if (
    //             $villages->count() !==
    //             count($villageIds)
    //         ) {

    //             return back()
    //                 ->withInput()
    //                 ->withErrors([
    //                     'village_ids' =>
    //                         'Un ou plusieurs villages ne correspondent pas au canton sélectionné.',
    //                 ]);
    //         }


            

    //         $deploiement = CampagneDeploiement::query()
    //             ->where(
    //                 'campagne_id',
    //                 $campagne->idCampagne
    //             )
    //             ->where(
    //                 'prefecture_id',
    //                 $prefectureId
    //             )
    //             ->first();


    //         if (!$deploiement) {

    //             return back()
    //                 ->withInput()
    //                 ->withErrors([
    //                     'campagne_id' =>
    //                         'La campagne n’est pas déployée dans votre préfecture.',
    //                 ]);
    //         }


    //         $planification =
    //             PlanificationPrefectorale::query()
    //                 ->where(
    //                     'deploiement_id',
    //                     $deploiement->idDeploiement
    //                 )
    //                 ->first();


    //         if (!$planification) {

    //             return back()
    //                 ->withInput()
    //                 ->withErrors([
    //                     'village_ids' =>
    //                         'Aucune planification préfectorale n’est disponible pour cette campagne.',
    //                 ]);
    //         }


    //     $villagesPlanifiesIds =
    //     PlanificationTerritoire::query()
    //     ->where(
    //         'planification_prefectorale_id',
    //         $planification->idPlanificationPrefectorale
    //     )
    //     ->whereNotNull('village_id')
    //     ->whereHas('village', function ($query) use ($canton) {

    //         $query->where(
    //             'canton_id',
    //             $canton->idCanton
    //         );

    //     })
    //     ->pluck('village_id')
    //     ->map(fn ($id) => (int) $id)
    //     ->unique()
    //     ->values()
    //     ->all();


    //     $villagesNonPlanifies =
    //         array_diff(
    //             $villageIds,
    //             $villagesPlanifiesIds
    //         );


    //     if (!empty($villagesNonPlanifies)) {

    //         return back()
    //             ->withInput()
    //             ->withErrors([
    //                 'village_ids' =>
    //                     'Un ou plusieurs villages sélectionnés ne sont pas prévus dans la planification préfectorale.',
    //             ]);
    //     }


       

    //     DB::transaction(function () use (
    //         $validated,
    //         $villages,
    //         $equipe
    //     ) {

    //         foreach ($villages as $village) {

    //             $dejaAffectee =
    //                 Affectation::query()
    //                     ->where(
    //                         'campagne_id',
    //                         $validated['campagne_id']
    //                     )
    //                     ->where(
    //                         'equipe_id',
    //                         $equipe->idEquipe
    //                     )
    //                     ->where(
    //                         'village_id',
    //                         $village->idVillage
    //                     )
    //                     ->where(
    //                         'statut',
    //                         'active'
    //                     )
    //                     ->exists();


    //             if ($dejaAffectee) {
    //                 continue;
    //             }


    //             Affectation::create([

    //                 'reference' =>
    //                     $this->genererReference(),

    //                 'campagne_id' =>
    //                     $validated['campagne_id'],

    //                 'equipe_id' =>
    //                     $equipe->idEquipe,

    //                 'village_id' =>
    //                     $village->idVillage,

    //                 'dateDebut' =>
    //                     $validated['dateDebut'] ?? null,

    //                 'dateFin' =>
    //                     $validated['dateFin'] ?? null,

    //                 'statut' =>
    //                     $validated['statut'] ?? 'active',

    //                 'observations' =>
    //                     $validated['observations'] ?? null,
    //             ]);
    //         }
    //     });


    //     return redirect()
    //         ->route(
    //             'dpa.equipes.show',
    //             $equipe
    //         )
    //         ->with(
    //             'success',
    //             'Les villages ont été affectés à l’équipe avec succès.'
    //         );
    // }



    public function store(
        StoreAffectationRequest $request,
        Equipe $equipe
        ): RedirectResponse {

        $user = Auth::user();

        $prefectureId = $this->prefectureIdUtilisateur($user);

        if (!$prefectureId) {
            abort(403, 'Aucune préfecture active n’est associée à votre compte.');
        }

        /*
        |--------------------------------------------------------------------------
        | VÉRIFIER L'ÉQUIPE
        |--------------------------------------------------------------------------
        */

        $this->verifierEquipePrefecture($equipe, $prefectureId);

        $validated = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | CAMPAGNE
        |--------------------------------------------------------------------------
        */

        $campagne = CampagneRecensement::query()
            ->whereKey($validated['campagne_id'])
            ->whereIn('statut', ['planifiee', 'active'])
            ->whereHas('deploiements', function ($query) use ($prefectureId) {
                $query->where('prefecture_id', $prefectureId);
            })
            ->first();

        if (!$campagne) {
            return back()
                ->withInput()
                ->withErrors([
                    'campagne_id' =>
                        'La campagne sélectionnée n’est pas déployée dans votre préfecture.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | UNE ÉQUIPE NE PEUT AVOIR QU'UNE AFFECTATION ACTIVE SUR CETTE CAMPAGNE
        |--------------------------------------------------------------------------
        */

        $affectationActive = Affectation::query()
            ->where('equipe_id', $equipe->idEquipe)
            ->where('campagne_id', $campagne->idCampagne)
            ->where('statut', 'active')
            ->exists();

        if ($affectationActive) {
            return back()
                ->withInput()
                ->withErrors([
                    'campagne_id' =>
                        "Cette équipe est déjà affectée à cette campagne. Désactivez son affectation avant de la reconduire.",
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | CANTON
        |--------------------------------------------------------------------------
        */

        $canton = Canton::query()
            ->whereKey($validated['canton_id'])
            ->whereHas('commune', function ($query) use ($prefectureId) {
                $query->where('prefecture_id', $prefectureId);
            })
            ->first();

        if (!$canton) {
            return back()
                ->withInput()
                ->withErrors([
                    'canton_id' =>
                        'Le canton sélectionné n’appartient pas à votre préfecture.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | VILLAGES SÉLECTIONNÉS
        |--------------------------------------------------------------------------
        */

        $villageIds = collect($validated['village_ids'])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $villages = Village::query()
            ->whereIn('idVillage', $villageIds)
            ->where('canton_id', $canton->idCanton)
            ->get();

        if ($villages->count() !== count($villageIds)) {
            return back()
                ->withInput()
                ->withErrors([
                    'village_ids' =>
                        'Un ou plusieurs villages ne correspondent pas au canton sélectionné.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | PLANIFICATION PRÉFECTORALE
        |--------------------------------------------------------------------------
        */

        $deploiement = CampagneDeploiement::query()
            ->where('campagne_id', $campagne->idCampagne)
            ->where('prefecture_id', $prefectureId)
            ->first();

        if (!$deploiement) {
            return back()
                ->withInput()
                ->withErrors([
                    'campagne_id' =>
                        'La campagne n’est pas déployée dans votre préfecture.',
                ]);
        }

        $planification = PlanificationPrefectorale::query()
            ->where('deploiement_id', $deploiement->idDeploiement)
            ->first();

        if (!$planification) {
            return back()
                ->withInput()
                ->withErrors([
                    'village_ids' =>
                        'Aucune planification préfectorale n’est disponible pour cette campagne.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | VILLAGES AUTORISÉS PAR LA PLANIFICATION
        |--------------------------------------------------------------------------
        */

        $villagesPlanifiesIds = PlanificationTerritoire::query()
            ->where('planification_prefectorale_id', $planification->idPlanificationPrefectorale)
            ->whereNotNull('village_id')
            ->whereHas('village', function ($query) use ($canton) {
                $query->where('canton_id', $canton->idCanton);
            })
            ->pluck('village_id')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $villagesNonPlanifies = array_diff($villageIds, $villagesPlanifiesIds);

        if (!empty($villagesNonPlanifies)) {
            return back()
                ->withInput()
                ->withErrors([
                    'village_ids' =>
                        'Un ou plusieurs villages sélectionnés ne sont pas prévus dans la planification préfectorale.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | LES MEMBRES DE L'ÉQUIPE DOIVENT ÊTRE DISPONIBLES
        |--------------------------------------------------------------------------
        */

        $membresEquipe = $equipe->membres()->pluck('users.id');

        $agentsOccupes = Affectation::query()
            ->where('campagne_id', $campagne->idCampagne)
            ->where('statut', 'active')
            ->whereHas('equipe.membres', function ($query) use ($membresEquipe) {
                $query->whereIn('users.id', $membresEquipe);
            })
            ->with('equipe')
            ->get();

        if ($agentsOccupes->isNotEmpty()) {

            $equipeOccupee = $agentsOccupes->first()->equipe;

            return back()
                ->withInput()
                ->withErrors([
                    'campagne_id' =>
                        "Un ou plusieurs agents de cette équipe sont déjà affectés dans l'équipe « {$equipeOccupee->nom} » pour cette campagne.",
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | ENREGISTREMENT DES AFFECTATIONS
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($validated, $villages, $equipe) {

            foreach ($villages as $village) {

                Affectation::create([

                    'reference' => $this->genererReference(),

                    'campagne_id' => $validated['campagne_id'],

                    'equipe_id' => $equipe->idEquipe,

                    'village_id' => $village->idVillage,

                    'dateDebut' => $validated['dateDebut'] ?? now(),

                    'dateFin' => $validated['dateFin'] ?? null,

                    'statut' => 'active',

                    'observations' => $validated['observations'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('dpa.equipes.show', $equipe)
            ->with(
                'success',
                "L'équipe {$equipe->nom} a été affectée avec succès à la campagne {$campagne->libelle}."
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Affectation $affectation
    ): View {

        $affectation->load([
            'campagne',
            'equipe',
            'village.canton.commune',
        ]);


        $prefectureId =
            $this->prefectureIdUtilisateur(
                Auth::user()
            );


        if (!$prefectureId) {
            abort(403);
        }


        $villagePrefecture = optional(
            $affectation->village
                ?->canton
                ?->commune
        )->prefecture_id;


        if (
            $villagePrefecture !== null &&
            (int) $villagePrefecture !== (int) $prefectureId
        ) {
            abort(
                403,
                'Cette affectation n’appartient pas à votre préfecture.'
            );
        }


        return view(
            'dpa.affectations.show',
            compact('affectation')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    // public function destroy(
    //     Affectation $affectation
    // ): RedirectResponse {

    //     $user = Auth::user();

    //     $prefectureId =
    //         $this->prefectureIdUtilisateur($user);


    //     if (!$prefectureId) {
    //         abort(403);
    //     }


    //     $affectation->load(
    //         'village.canton.commune'
    //     );


    //     $villagePrefecture =
    //         optional(
    //             $affectation->village
    //                 ?->canton
    //                 ?->commune
    //         )->prefecture_id;


    //     if (
    //         $villagePrefecture !== null &&
    //         (int) $villagePrefecture !== (int) $prefectureId
    //     ) {

    //         abort(
    //             403,
    //             'Cette affectation n’appartient pas à votre préfecture.'
    //         );
    //     }


    //     if ($affectation->statut === 'annulee') {

    //         return back()
    //             ->with(
    //                 'info',
    //                 'Cette affectation est déjà annulée.'
    //             );
    //     }


    //     $affectation->update([
    //         'statut' => 'annulee',
    //     ]);


    //     return back()
    //         ->with(
    //             'success',
    //             'L’affectation a été annulée avec succès.'
    //         );
    // }



    public function destroy(Affectation $affectation): RedirectResponse
{
    $user = Auth::user();

    $prefectureId = $this->prefectureIdUtilisateur($user);

    if (!$prefectureId) {
        abort(403);
    }

    $affectation->load([
        'equipe',
        'campagne',
        'village.canton.commune'
    ]);

    /*
    |--------------------------------------------------------------------------
    | VÉRIFIER QUE L'AFFECTATION APPARTIENT À LA PRÉFECTURE
    |--------------------------------------------------------------------------
    */

    $villagePrefecture = optional(
        $affectation->village?->canton?->commune
    )->prefecture_id;

    if (
        $villagePrefecture !== null &&
        (int) $villagePrefecture !== (int) $prefectureId
    ) {
        abort(
            403,
            "Cette affectation n'appartient pas à votre préfecture."
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SI DÉJÀ ANNULÉE
    |--------------------------------------------------------------------------
    */

    if ($affectation->statut === 'annulee') {
        return back()->with(
            'info',
            "Cette affectation est déjà désactivée."
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DÉSACTIVATION (LIBÉRATION DE L'ÉQUIPE)
    |--------------------------------------------------------------------------
    */

    $affectation->update([
        'statut'  => 'annulee',
        'dateFin' => now(),
    ]);

    return redirect()
        ->route('dpa.equipes.show', $affectation->equipe)
        ->with(
            'success',
            "L'affectation de l'équipe {$affectation->equipe->nom} a été désactivée. L'équipe est maintenant disponible pour une nouvelle affectation."
        );
}


    /*
    |--------------------------------------------------------------------------
    | PRÉFECTURE UTILISATEUR
    |--------------------------------------------------------------------------
    */

    private function prefectureIdUtilisateur(
        $user
    ): ?int {

        if (!empty($user->prefecture_id)) {
            return (int) $user->prefecture_id;
        }


        $rattachement =
            RattachementPrefecture::query()
                ->where(
                    'user_id',
                    $user->id
                )
                ->where(
                    'statut',
                    'actif'
                )
                ->whereNull(
                    'dateFin'
                )
                ->first();


        return $rattachement
            ? (int) $rattachement->prefecture_id
            : null;
    }


    /*
    |--------------------------------------------------------------------------
    | VÉRIFIER ÉQUIPE
    |--------------------------------------------------------------------------
    */

    private function verifierEquipePrefecture(
        Equipe $equipe,
        int $prefectureId
    ): void {

        if (
            isset($equipe->prefecture_id) &&
            $equipe->prefecture_id !== null
        ) {

            if (
                (int) $equipe->prefecture_id !==
                (int) $prefectureId
            ) {

                abort(
                    403,
                    'Cette équipe n’appartient pas à votre préfecture.'
                );
            }

            return;
        }


        if (method_exists($equipe, 'superviseur')) {

            $superviseur =
                $equipe
                    ->load('superviseur')
                    ->superviseur;


            if ($superviseur) {

                $prefectureSuperviseur =
                    $this->prefectureIdUtilisateur(
                        $superviseur
                    );


                if (
                    $prefectureSuperviseur &&
                    (int) $prefectureSuperviseur !==
                    (int) $prefectureId
                ) {

                    abort(
                        403,
                        'Cette équipe n’appartient pas à votre préfecture.'
                    );
                }
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | RÉFÉRENCE
    |--------------------------------------------------------------------------
    */

    private function genererReference(): string
    {
        do {

            $reference =
                'AFF-' .
                now()->format('Y') .
                '-' .
                strtoupper(
                    Str::random(6)
                );

        } while (
            Affectation::where(
                'reference',
                $reference
            )->exists()
        );


        return $reference;
    }
}