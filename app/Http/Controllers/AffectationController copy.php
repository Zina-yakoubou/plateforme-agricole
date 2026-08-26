<?php

namespace App\Http\Controllers;

use App\Models\Affectation;
use App\Models\User;
use App\Models\Village;
use App\Models\CampagneRecensement;
use App\Http\Requests\StoreAffectationRequest;
use App\Http\Requests\UpdateAffectationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AffectationController extends Controller
{


    /**
     * Liste des affectations
     */
    // public function index()
    // {
    //     $affectations = Affectation::with([
    //             'user.role',
    //             'village',
    //             'prefecture',
    //             'campagne'
    //         ])
    //         ->latest()
    //         ->paginate(10);


    //     return view(
    //         'affectations.index',
    //         compact('affectations')
    //     );
    // }



    
    


    /**
     * Formulaire création
     */
    // public function create(Request $request)
    // {

    //     $village = null;


    //     if($request->has('village'))
    //     {
    //         $village = Village::findOrFail(
    //             $request->village
    //         );
    //     }


    //     // uniquement les agents recenseurs
    //     $agents = User::whereHas(
    //         'role',
    //         function($query){

    //             $query->where(
    //                 'nom',
    //                 'Agent recenseur'
    //             );

    //         }
    //     )->get();



    //     // campagne active
    //     $campagne = CampagneRecensement::where(
    //         'active',
    //         true
    //     )->first();



    //     return view(
    //         'affectations.create',
    //         compact(
    //             'agents',
    //             'campagne',
    //             'village'
    //         )
    //     );
    // }






/**
 * Liste des affectations
 */
public function index(Request $request)
{
    $search = $request->input('search');

    // Liste des affectations
    $affectations = Affectation::with([
        'user',
        'campagne',
        'village',
        'canton',
    ])
    ->when($search, function ($query) use ($search) {

        $query->where(function ($q) use ($search) {

            $q->where('reference', 'like', "%{$search}%")

                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('login', 'like', "%{$search}%");
                })

                ->orWhereHas('campagne', function ($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%");
                })

                ->orWhereHas('village', function ($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%");
                })

                ->orWhereHas('canton', function ($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%");
                });

        });

    })
    ->latest()
    ->paginate(10)
    ->withQueryString();


    /*
    |--------------------------------------------------------------------------
    | Données nécessaires au modal de création
    |--------------------------------------------------------------------------
    */

    // Agents recenseurs
    $agents = User::whereHas('role', function ($query) {
        $query->where('nom', 'Agent recenseur');
    })
    ->where('statut', true)
    ->orderBy('name')
    ->get();


    // Campagnes
    $campagnes = CampagneRecensement::orderBy('nom')->get();


    // Villages
    $villages = Village::orderBy('nom')->get();


    return view('affectations.index', compact(
        'affectations',
        'search',
        'agents',
        'campagnes',
        'villages'
    ));
}




    /**
 * Formulaire création
 */
    public function create(Request $request)
    {

        $village = null;


        if($request->has('village'))
        {
            $village = Village::findOrFail(
                $request->village
            );
        }



        // Agents recenseurs actifs
        $agents = User::whereHas(
            'role',
            function($query){

                $query->where(
                    'nom',
                    'Agent recenseur'
                );

            }
        )
        ->where('statut', true)
        ->get();



        // Campagnes actives
        $campagnes = CampagneRecensement::where(
            'statut',
            'active'
        )->get();



        // Tous les villages disponibles
        $villages = Village::with([
            'canton.commune.prefecture'
        ])
        ->get();



        return view(
            'affectations.create',
            compact(
                'agents',
                'campagnes',
                'villages',
                'village'
            )
        );

    }

    /**
     * Enregistrement
     */
    public function store(StoreAffectationRequest $request)
    {

        $data = $request->validated();



        /*
        |--------------------------------------------------------------------------
        | Génération référence
        |--------------------------------------------------------------------------
        */

        $data['reference'] =
            'AFF-' . now()->format('YmdHis');




        /*
        |--------------------------------------------------------------------------
        | Vérification du rôle
        |--------------------------------------------------------------------------
        */

        $user = User::findOrFail(
            $data['user_id']
        );


        /*
        |--------------------------------------------------------------------------
        | Cas Agent recenseur
        |--------------------------------------------------------------------------
        */

        if(
            $user->role?->nom === 'Agent recenseur'
        )
        {


            if(
                empty($data['campagne_id'])
                ||
                empty($data['village_id'])
            )
            {
                return back()
                    ->withErrors(
                        'Un agent recenseur doit avoir une campagne et un village.'
                    )
                    ->withInput();
            }



            /*
            | Empêcher doublon
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



            if($existe)
            {
                return back()
                    ->withErrors(
                        'Cet agent est déjà affecté à ce village pour cette campagne.'
                    )
                    ->withInput();
            }

        }



        /*
        |--------------------------------------------------------------------------
        | Cas Directeur préfectoral
        |--------------------------------------------------------------------------
        */

        if(
            $user->role?->nom === 'Directeur préfectoral'
        )
        {

            if(
                empty($data['prefecture_id'])
            )
            {
                return back()
                    ->withErrors(
                        'Un directeur préfectoral doit être rattaché à une préfecture.'
                    )
                    ->withInput();
            }


            // pas de campagne/village

            $data['campagne_id'] = null;

            $data['village_id'] = null;

        }




        Affectation::create($data);



        return redirect()
            ->route('affectations.index')
            ->with(
                'success',
                'Affectation créée avec succès.'
            );
    }



    /**
 * Formulaire de modification
 */
public function edit(Affectation $affectation)
{
    $agents = User::whereHas('role', function ($query) {
        $query->where('nom', 'Agent recenseur');
    })
    ->where('statut', true)
    ->orderBy('name')
    ->get();

    $campagnes = CampagneRecensement::orderBy('nom')->get();

    $villages = Village::with([
        'canton.commune.prefecture'
    ])
    ->orderBy('nom')
    ->get();

    return view(
        'affectations.edit',
        compact(
            'affectation',
            'agents',
            'campagnes',
            'villages'
        )
    );
}


    /**
     * Mise à jour d'une affectation
     */
    public function update(Request $request, $id)
    {
        $affectation = Affectation::findOrFail($id);

        $validated = $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
            ],
            'village_id' => [
                'required',
                'exists:villages,id',
            ],
            'dateDebut' => [
                'required',
                'date',
            ],
            'dateFin' => [
                'nullable',
                'date',
                'after_or_equal:dateDebut',
            ],
            'statut' => [
                'required',
                'string',
            ],
        ]);

        $affectation->update([
            'user_id'    => $validated['user_id'],
            'village_id' => $validated['village_id'],
            'dateDebut'  => $validated['dateDebut'],
            'dateFin'    => $validated['dateFin'] ?? null,
            'statut'     => $validated['statut'],
        ]);

        return redirect()
            ->route('affectations.index')
            ->with('success', 'Affectation modifiée avec succès.');
    }




}