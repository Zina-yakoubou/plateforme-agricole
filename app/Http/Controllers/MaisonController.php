<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaisonRequest;
use App\Http\Requests\UpdateMaisonRequest;
use App\Models\Maison;
use App\Models\Village;
use App\Services\ReferenceGeneratorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MaisonController extends Controller
{
    public function __construct(
        protected ReferenceGeneratorService $referenceGenerator
    ) {
    }

    /**
     * Liste des maisons d'un village.
     */
    public function index(Village $village): View
    {
        $maisons = $village->maisons()
            ->withCount('menages')
            ->orderBy('idMaison')
            ->paginate(10);

        return view('maisons.index', compact(
            'village',
            'maisons'
        ));
    }

    /**
     * Formulaire de création d'une maison.
     */
    public function create(Village $village): View
    {
        $village->load([
            'canton.commune',
        ]);

        return view('maisons.create', compact('village'));
    }

    /**
     * Enregistre une nouvelle maison.
     */
    public function store(
        StoreMaisonRequest $request,
        Village $village
    ): RedirectResponse {
        /*
        |--------------------------------------------------------------------------
        | Génération automatique du numéro de maison
        |--------------------------------------------------------------------------
        */

        $numeroMaison = $this->referenceGenerator->generate(
            type: 'maison',
            parentType: 'village',
            parentId: $village->idVillage,
            codeParent: $village->code,
            prefix: 'M',
            padding: 5
        );

        /*
        |--------------------------------------------------------------------------
        | Génération de l'identifiant hors ligne
        |--------------------------------------------------------------------------
        */

        $uid = (string) Str::uuid();

        /*
        |--------------------------------------------------------------------------
        | Photo de la maison
        |--------------------------------------------------------------------------
        */

        $photoMaison = null;

        if ($request->hasFile('photoMaison')) {
            $photoMaison = $request
                ->file('photoMaison')
                ->store('maisons', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Création
        |--------------------------------------------------------------------------
        */

        $maison = $village->maisons()->create([
            'uid' => $uid,

            'numeroMaison' => $numeroMaison,

            'chefMaison' => $request->validated('chefMaison'),

            'adresse' => $request->validated('adresse'),

            'nombreMenages' => 1,

            'latitude' => $request->validated('latitude'),

            'longitude' => $request->validated('longitude'),

            'precisionGPS' => $request->validated('precisionGPS'),

            'photoMaison' => $photoMaison,

            'statut' => $request->validated(
                'statut',
                'brouillon'
            ),

            'agent_id' => auth()->id(),

            'dateIdentification' => $request->validated(
                'dateIdentification'
            ),
        ]);

        return redirect()
            ->route(
                'villages.maisons.index',
                $village
            )
            ->with(
                'success',
                "La maison {$maison->numeroMaison} a été enregistrée avec succès."
            );
    }

    /**
     * Affiche une maison.
     */
    public function show(Maison $maison): View
    {
        $maison->load([
            'village.canton.commune',
            'menages',
            'agent',
        ]);

        return view('maisons.show', compact('maison'));
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
    public function update(
        UpdateMaisonRequest $request,
        Maison $maison
    ): RedirectResponse {
        /*
        |--------------------------------------------------------------------------
        | Données modifiables
        |--------------------------------------------------------------------------
        */

        $donnees = [
            'chefMaison' => $request->validated('chefMaison'),

            'adresse' => $request->validated('adresse'),

            'latitude' => $request->validated('latitude'),

            'longitude' => $request->validated('longitude'),

            'precisionGPS' => $request->validated('precisionGPS'),

            'statut' => $request->validated('statut'),

            'dateIdentification' => $request->validated(
                'dateIdentification'
            ),
        ];

        /*
        |--------------------------------------------------------------------------
        | Nouvelle photo
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('photoMaison')) {

            if (
                $maison->photoMaison &&
                Storage::disk('public')->exists(
                    $maison->photoMaison
                )
            ) {
                Storage::disk('public')->delete(
                    $maison->photoMaison
                );
            }

            $donnees['photoMaison'] = $request
                ->file('photoMaison')
                ->store('maisons', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Mise à jour
        |--------------------------------------------------------------------------
        */

        $maison->update($donnees);

        return redirect()
            ->route(
                'maisons.show',
                $maison
            )
            ->with(
                'success',
                'La maison a été mise à jour avec succès.'
            );
    }

    /**
     * Supprime une maison.
     */
    public function destroy(Maison $maison): RedirectResponse
    {
        $village = $maison->village;

        /*
        |--------------------------------------------------------------------------
        | Suppression de la photo
        |--------------------------------------------------------------------------
        */

        if (
            $maison->photoMaison &&
            Storage::disk('public')->exists(
                $maison->photoMaison
            )
        ) {
            Storage::disk('public')->delete(
                $maison->photoMaison
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Suppression de la maison
        |--------------------------------------------------------------------------
        */

        $maison->delete();

        return redirect()
            ->route(
                'villages.maisons.index',
                $village
            )
            ->with(
                'success',
                'La maison a été supprimée avec succès.'
            );
    }
}