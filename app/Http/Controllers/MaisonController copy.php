<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaisonRequest;
use App\Http\Requests\UpdateMaisonRequest;
use App\Models\Maison;
use App\Models\Village;
use App\Services\ReferenceGeneratorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Models\Affectation;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Storage;

class MaisonController extends Controller
{
    public function __construct(
        protected ReferenceGeneratorService $referenceGenerator
    ) {
    }

    /**
     * Liste des maisons d'un village.
     */
    // public function index(Village $village): View
    // {
    //     $maisons = $village->maisons()
    //         ->withCount('menages')
    //         ->orderBy('idMaison')
    //         ->paginate(10);

    //     return view('maisons.index', compact(
    //         'village',
    //         'maisons'
    //     ));
    // }


        public function index(Request $request, Village $village): View
    {
        $affectation = null;

        if ($request->filled('affectation_id')) {
            $affectation = Affectation::with(['campagne', 'equipe', 'village.canton.commune'])
                ->findOrFail($request->integer('affectation_id'));

            abort_unless($affectation->statut === 'active', 403, 'Cette affectation n’est plus active.');

            $estMembre = $affectation->equipe()
                ->whereHas('membres', fn ($q) => $q->where('users.id', auth()->id()))
                ->exists();

            abort_unless($estMembre, 403, 'Vous n’êtes pas autorisé à accéder à cette affectation.');

            abort_unless(
                $affectation->village_id === $village->idVillage,
                403,
                'Ce village ne correspond pas à votre affectation.'
            );
        }

        $maisons = $village->maisons()
            ->with(['village.canton.commune'])
            ->with(['recensements' => function ($query) {
                $query->with('campagne:idCampagne,libelle')
                    ->withCount('menages')
                    ->orderByDesc('campagne_id');
            }])
            ->when($request->filled('q'), function ($query) use ($request) {
                $terme = $request->string('q');
                $query->where(function ($q) use ($terme) {
                    $q->where('numeroMaison', 'like', "%{$terme}%")
                    ->orWhere('adresse', 'like', "%{$terme}%");
                });
            })
            ->orderBy('idMaison')
            ->paginate(10)
            ->withQueryString();

        return view('maisons.index', compact('village', 'maisons', 'affectation'));
    }

   
    public function create(Village $village): View
    {
        $village->load([
            'canton.commune',
        ]);

        return view('maisons.create', compact('village'));
    }

   
    // public function store(
    //     StoreMaisonRequest $request,
    //     Village $village
    // ): RedirectResponse {
        
    //     $numeroMaison = $this->referenceGenerator->generate(
    //         type: 'maison',
    //         parentType: 'village',
    //         parentId: $village->idVillage,
    //         codeParent: $village->code,
    //         prefix: 'M',
    //         padding: 5
    //     );

        
    //     $uid = (string) Str::uuid();

        

    //     $photoMaison = null;

    //     if ($request->hasFile('photoMaison')) {
    //         $photoMaison = $request
    //             ->file('photoMaison')
    //             ->store('maisons', 'public');
    //     }

        

    //     $maison = $village->maisons()->create([
    //         'uid' => $uid,

    //         'numeroMaison' => $numeroMaison,

    //         'chefMaison' => $request->validated('chefMaison'),

    //         'adresse' => $request->validated('adresse'),

    //         'nombreMenages' => 1,

    //         'latitude' => $request->validated('latitude'),

    //         'longitude' => $request->validated('longitude'),

    //         'precisionGPS' => $request->validated('precisionGPS'),

    //         'photoMaison' => $photoMaison,

    //         'statut' => $request->validated(
    //             'statut',
    //             'brouillon'
    //         ),

    //         'agent_id' => auth()->id(),

    //         'dateIdentification' => $request->validated(
    //             'dateIdentification'
    //         ),
    //     ]);

    //     return redirect()
    //         ->route(
    //             'villages.maisons.index',
    //             $village
    //         )
    //         ->with(
    //             'success',
    //             "La maison {$maison->numeroMaison} a été enregistrée avec succès."
    //         );
    // }

    public function store(
            StoreMaisonRequest $request,
            Village $village
        ): RedirectResponse {

        $numeroMaison = $this->referenceGenerator->generate(
            type: 'maison',
            parentType: 'village',
            parentId: $village->idVillage,
            codeParent: $village->code,
            prefix: 'M',
            padding: 5
        );

        $maison = $village->maisons()->create([
            'uid' => (string) Str::uuid(),

            'numeroMaison' => $numeroMaison,

            'repere' => $request->validated('repere'),

            'adresse' => $request->validated('adresse'),

            'latitude' => $request->validated('latitude'),

            'longitude' => $request->validated('longitude'),

            'precisionGPS' => $request->validated('precisionGPS'),
        ]);

        return redirect()
            ->route('villages.maisons.index', $village)
            ->with(
                'success',
                "La maison {$maison->numeroMaison} a été enregistrée avec succès."
            );
    }

    
    //     public function show(Maison $maison): View
    // {
    //     $maison->load([
    //         'village.canton.commune',
    //         'recensements.agent',
    //         'recensements.campagne',
    //         'recensements.affectation.equipe',
    //     ]);

    //     return view('maisons.show', compact('maison'));
    // }




        public function show(Maison $maison): View
    {
        $maison->load([
            'village.canton.commune',
            'recensements.campagne',
            'recensements.agent',
            'recensements.affectation.equipe',
        ]);

        $user = Auth::user();

        // Affectation active de l'agent connecté couvrant ce village
        $affectation = Affectation::query()
            ->where('village_id', $maison->village_id)
            ->where('statut', 'active')
            ->whereHas('equipe.membres', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->latest('idAffectation')
            ->first();

        // Recensement de cette maison pour la campagne de l'affectation
        $recensement = $affectation
            ? $maison->recensements->firstWhere('campagne_id', $affectation->campagne_id)
            : null;

        return view('maisons.show', compact('maison', 'affectation', 'recensement'));
    }
    /**
     * Formulaire de modification.
     */
    public function edit(Maison $maison): View
    {
        $maison->load([
            'village.canton.commune',
        ]);

        return view('maisons.edit', compact('maison'));
    }

    /**
     * Met à jour une maison.
     */
    // public function update(
    //     UpdateMaisonRequest $request,
    //     Maison $maison
    // ): RedirectResponse {
       

    //     $donnees = [
    //         'chefMaison' => $request->validated('chefMaison'),

    //         'adresse' => $request->validated('adresse'),

    //         'latitude' => $request->validated('latitude'),

    //         'longitude' => $request->validated('longitude'),

    //         'precisionGPS' => $request->validated('precisionGPS'),

    //         'statut' => $request->validated('statut'),

    //         'dateIdentification' => $request->validated(
    //             'dateIdentification'
    //         ),
    //     ];

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Nouvelle photo
    //     |--------------------------------------------------------------------------
    //     */

    //     if ($request->hasFile('photoMaison')) {

    //         if (
    //             $maison->photoMaison &&
    //             Storage::disk('public')->exists(
    //                 $maison->photoMaison
    //             )
    //         ) {
    //             Storage::disk('public')->delete(
    //                 $maison->photoMaison
    //             );
    //         }

    //         $donnees['photoMaison'] = $request
    //             ->file('photoMaison')
    //             ->store('maisons', 'public');
    //     }

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Mise à jour
    //     |--------------------------------------------------------------------------
    //     */

    //     $maison->update($donnees);

    //     return redirect()
    //         ->route(
    //             'maisons.show',
    //             $maison
    //         )
    //         ->with(
    //             'success',
    //             'La maison a été mise à jour avec succès.'
    //         );
    // }


    public function update(
            UpdateMaisonRequest $request,
            Maison $maison
        ): RedirectResponse {

        $maison->update([
            'numeroMaison' => $request->validated('numeroMaison'),

            'repere' => $request->validated('repere'),

            'adresse' => $request->validated('adresse'),

            'latitude' => $request->validated('latitude'),

            'longitude' => $request->validated('longitude'),

            'precisionGPS' => $request->validated('precisionGPS'),
        ]);

        return redirect()
            ->route('maisons.show', $maison)
            ->with('success', 'La maison a été mise à jour avec succès.');
    }

    /**
     * Supprime une maison.
     */
    // public function destroy(Maison $maison): RedirectResponse
    // {
    //     $village = $maison->village;

       

    //     if (
    //         $maison->photoMaison &&
    //         Storage::disk('public')->exists(
    //             $maison->photoMaison
    //         )
    //     ) {
    //         Storage::disk('public')->delete(
    //             $maison->photoMaison
    //         );
    //     }

        

    //     $maison->delete();

    //     return redirect()
    //         ->route(
    //             'villages.maisons.index',
    //             $village
    //         )
    //         ->with(
    //             'success',
    //             'La maison a été supprimée avec succès.'
    //         );
    // }


        public function destroy(Maison $maison): RedirectResponse
    {
        $village = $maison->village;

        $maison->delete();

        return redirect()
            ->route('villages.maisons.index', $village)
            ->with('success', 'La maison a été supprimée avec succès.');
    }
}