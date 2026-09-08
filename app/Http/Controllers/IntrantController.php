<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIntrantRequest;
use App\Http\Requests\UpdateIntrantRequest;
use App\Models\Intrant;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class IntrantController extends Controller
{
    /**
     * Afficher la liste des intrants.
     */
    public function index(): View
    {
        $intrants = Intrant::query()
            ->orderBy('nom')
            ->paginate(15);

        return view(
            'intrants.index',
            compact('intrants')
        );
    }

    /**
     * Afficher le formulaire de création.
     */
    public function create(): View
    {
        return view('intrants.create');
    }

    /**
     * Enregistrer un intrant.
     */
    public function store(
        StoreIntrantRequest $request
    ): RedirectResponse {
        $intrant = Intrant::create([
            'nom' =>
                $request->validated('nom'),

            'type' =>
                $request->validated('type'),

            'unite' =>
                $request->validated('unite'),

            'actif' =>
                $request->boolean('actif'),
        ]);

        return redirect()
            ->route(
                'intrants.index'
            )
            ->with(
                'success',
                "L'intrant {$intrant->nom} a été enregistré avec succès."
            );
    }

    /**
     * Afficher un intrant.
     */
    public function show(Intrant $intrant): View
    {
        $intrant->load([
            'cultures.parcelle',
        ]);

        return view(
            'intrants.show',
            compact('intrant')
        );
    }

    /**
     * Afficher le formulaire de modification.
     */
    public function edit(Intrant $intrant): View
    {
        return view(
            'intrants.edit',
            compact('intrant')
        );
    }

    /**
     * Mettre à jour un intrant.
     */
    public function update(
        UpdateIntrantRequest $request,
        Intrant $intrant
    ): RedirectResponse {
        $intrant->update([
            'nom' =>
                $request->validated('nom'),

            'type' =>
                $request->validated('type'),

            'unite' =>
                $request->validated('unite'),

            'actif' =>
                $request->boolean('actif'),
        ]);

        return redirect()
            ->route(
                'intrants.show',
                $intrant
            )
            ->with(
                'success',
                "L'intrant {$intrant->nom} a été mis à jour avec succès."
            );
    }

    /**
     * Supprimer un intrant.
     */
    public function destroy(
        Intrant $intrant
    ): RedirectResponse {
        $intrant->delete();

        return redirect()
            ->route(
                'intrants.index'
            )
            ->with(
                'success',
                "L'intrant {$intrant->nom} a été supprimé avec succès."
            );
    }
}
