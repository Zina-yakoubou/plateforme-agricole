<?php

namespace App\Http\Controllers;

use App\Models\Affectation;
use App\Models\User;
use App\Models\Village;
use App\Models\CampagneRecensement;
use App\Http\Requests\StoreAffectationRequest;
use Illuminate\Http\Request;

class AffectationController extends Controller
{
    /**
     * Liste des affectations.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $affectations = Affectation::with([
            'user.role',
            'campagne',
            'village',
            'canton',
        ])
        ->when($search, function ($query) use ($search) {

            $query->where(function ($q) use ($search) {

                /*
                | Référence
                */

                $q->where(
                    'reference',
                    'like',
                    "%{$search}%"
                )

                /*
                | Agent
                */

                ->orWhereHas(
                    'user',
                    function ($q) use ($search) {

                        $q->where(
                            'name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'login',
                            'like',
                            "%{$search}%"
                        );
                    }
                )

                /*
                | Campagne
                */

                ->orWhereHas(
                    'campagne',
                    function ($q) use ($search) {

                        $q->where(
                            'libelle',
                            'like',
                            "%{$search}%"
                        );
                    }
                )

                /*
                | Village
                */

                ->orWhereHas(
                    'village',
                    function ($q) use ($search) {

                        $q->where(
                            'nom',
                            'like',
                            "%{$search}%"
                        );
                    }
                )

                /*
                | Canton
                */

                ->orWhereHas(
                    'canton',
                    function ($q) use ($search) {

                        $q->where(
                            'nom',
                            'like',
                            "%{$search}%"
                        );
                    }
                );

            });

        })
        ->latest()
        ->paginate(10)
        ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Agents recenseurs actifs
        |--------------------------------------------------------------------------
        */

        $agents = User::whereHas(
            'role',
            function ($query) {

                $query->where(
                    'nom',
                    'Agent recenseur'
                );
            }
        )
        ->where(
            'statut',
            true
        )
        ->orderBy('name')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Campagne active
        |--------------------------------------------------------------------------
        */

        $campagneActive = CampagneRecensement::where(
            'statut',
            'active'
        )->first();


        /*
        |--------------------------------------------------------------------------
        | Villages
        |--------------------------------------------------------------------------
        */

        $villages = Village::with([
            'canton.commune.prefecture'
        ])
        ->orderBy('nom')
        ->get();


        return view(
            'affectations.index',
            compact(
                'affectations',
                'search',
                'agents',
                'campagneActive',
                'villages'
            )
        );
    }


    /**
     * Formulaire de création.
     */
    public function create(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Agent pré-sélectionné
        |--------------------------------------------------------------------------
        */

        $agentSelectionne = null;

        if ($request->filled('user_id')) {

            $agentSelectionne = User::whereHas(
                'role',
                function ($query) {

                    $query->where(
                        'nom',
                        'Agent recenseur'
                    );
                }
            )
            ->where(
                'id',
                $request->user_id
            )
            ->where(
                'statut',
                true
            )
            ->firstOrFail();
        }


        /*
        |--------------------------------------------------------------------------
        | Agents recenseurs actifs
        |--------------------------------------------------------------------------
        */

        $agents = User::whereHas(
            'role',
            function ($query) {

                $query->where(
                    'nom',
                    'Agent recenseur'
                );
            }
        )
        ->where(
            'statut',
            true
        )
        ->orderBy('name')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Campagne active
        |--------------------------------------------------------------------------
        */

        $campagneActive = CampagneRecensement::where(
            'statut',
            'active'
        )->first();


        /*
        |--------------------------------------------------------------------------
        | Villages
        |--------------------------------------------------------------------------
        */

        $villages = Village::with([
            'canton.commune.prefecture'
        ])
        ->orderBy('nom')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Village éventuellement pré-sélectionné
        |--------------------------------------------------------------------------
        */

        $village = null;

        if ($request->filled('village')) {

            $village = Village::findOrFail(
                $request->village
            );
        }


        return view(
            'affectations.create',
            compact(
                'agents',
                'campagneActive',
                'villages',
                'village',
                'agentSelectionne'
            )
        );
    }


    /**
     * Enregistrement d'une affectation.
     */
    public function store(
        StoreAffectationRequest $request
    ) {

        $data = $request->validated();


        /*
        |--------------------------------------------------------------------------
        | Agent
        |--------------------------------------------------------------------------
        */

        $user = User::findOrFail(
            $data['user_id']
        );


        /*
        |--------------------------------------------------------------------------
        | Vérifier que l'utilisateur est agent recenseur
        |--------------------------------------------------------------------------
        */

        if (
            $user->role?->nom !== 'Agent recenseur'
        ) {

            return back()
                ->withErrors([
                    'user_id' =>
                        'Seul un agent recenseur peut recevoir une affectation.'
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier que l'agent est actif
        |--------------------------------------------------------------------------
        */

        if (!$user->statut) {

            return back()
                ->withErrors([
                    'user_id' =>
                        'Ce compte agent est désactivé.'
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Campagne active
        |--------------------------------------------------------------------------
        */

        $campagneActive = CampagneRecensement::where(
            'statut',
            'active'
        )->first();


        if (!$campagneActive) {

            return back()
                ->withErrors([
                    'campagne' =>
                        'Aucune campagne de recensement active n\'est disponible.'
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Ajouter automatiquement la campagne
        |--------------------------------------------------------------------------
        */

        $data['campagne_id'] =
            $campagneActive->idCampagne;


        /*
        |--------------------------------------------------------------------------
        | Génération référence
        |--------------------------------------------------------------------------
        */

        $data['reference'] =
            'AFF-' . now()->format('YmdHis');


        /*
        |--------------------------------------------------------------------------
        | Statut par défaut
        |--------------------------------------------------------------------------
        */

        $data['statut'] =
            $data['statut'] ?? 'ACTIVE';


        /*
        |--------------------------------------------------------------------------
        | Vérifier doublon
        |--------------------------------------------------------------------------
        */

        $existe = Affectation::where(
            'user_id',
            $data['user_id']
        )
        ->where(
            'campagne_id',
            $data['campagne_id']
        )
        ->where(
            'village_id',
            $data['village_id']
        )
        ->where(
            'statut',
            'ACTIVE'
        )
        ->exists();


        if ($existe) {

            return back()
                ->withErrors([
                    'user_id' =>
                        'Cet agent est déjà affecté à ce village pour la campagne active.'
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Création
        |--------------------------------------------------------------------------
        */

        Affectation::create($data);


        /*
        |--------------------------------------------------------------------------
        | Retour
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('affectations.index')
            ->with(
                'success',
                'Agent affecté au village avec succès.'
            );
    }


    /**
 * Afficher le détail d'une affectation
 */
    public function show(Affectation $affectation)
    {
        $affectation->load([
            'user.role',
            'campagne',
            'prefecture',
            'canton',
            'village',
        ]);

        return view(
            'affectations.show',
            compact('affectation')
        );
    }


        public function destroy(Affectation $affectation)
    {
        $affectation->update([
            'statut' => 'DESACTIVEE',
        ]);

        return redirect()
            ->route('affectations.index')
            ->with('success', 'Affectation désactivée avec succès.');
    }
}