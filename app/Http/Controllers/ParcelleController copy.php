<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParcelleRequest;
use App\Http\Requests\UpdateParcelleRequest;
use App\Models\Exploitant;
use App\Models\Exploitation;
use App\Models\Menage;
use App\Models\Parcelle;
use App\Models\Recensement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ParcelleController extends Controller
{
    /**
     * Liste des parcelles de l'exploitant.
     */
    public function index(
        Recensement $recensement,
        Menage $menage,
        Exploitant $exploitant
    ): View {
        $this->verifierContexte(
            $recensement,
            $menage,
            $exploitant
        );

        $exploitations = $exploitant->exploitations()
            ->with('parcelles')
            ->orderBy('idExploitation')
            ->get();

        $parcelles = Parcelle::query()
            ->whereIn(
                'exploitation_id',
                $exploitations->pluck('idExploitation')
            )
            ->with('exploitation')
            ->orderBy('idParcelle')
            ->get();

        return view('parcelles.index', compact(
            'recensement',
            'menage',
            'exploitant',
            'exploitations',
            'parcelles'
        ));
    }


    /**
     * Formulaire d'ajout d'une parcelle.
     */
    public function create(
        Recensement $recensement,
        Menage $menage,
        Exploitant $exploitant
    ): View {
        $this->verifierContexte(
            $recensement,
            $menage,
            $exploitant
        );

        $exploitations = $exploitant->exploitations()
            ->orderBy('idExploitation')
            ->get();

        return view('parcelles.create', compact(
            'recensement',
            'menage',
            'exploitant',
            'exploitations'
        ));
    }


    /**
     * Enregistre une parcelle.
     *
     * L'exploitation est gérée ici :
     * - une exploitation existante peut être sélectionnée ;
     * - si aucune exploitation n'est sélectionnée,
     *   une nouvelle exploitation est créée automatiquement.
     */
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

        $data = $request->validated();

        DB::transaction(function () use (
            $request,
            $data,
            $exploitant
        ) {

            /*
            |--------------------------------------------------------------------------
            | EXPLOITATION
            |--------------------------------------------------------------------------
            */

            if ($request->filled('exploitation_id')) {

                $exploitation = $exploitant->exploitations()
                    ->where(
                        'idExploitation',
                        $request->input('exploitation_id')
                    )
                    ->firstOrFail();

            } else {

                /*
                | Aucune exploitation sélectionnée :
                | on crée automatiquement l'exploitation.
                |
                | Pour l'instant nous ne demandons pas de nom à l'agent.
                */
                $exploitation = Exploitation::create([
                    'uid' => (string) Str::uuid(),
                    'exploitant_id' => $exploitant->idExploitant,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | PARCELLE
            |--------------------------------------------------------------------------
            */

            $data['uid'] = (string) Str::uuid();

            $data['exploitation_id'] = $exploitation->idExploitation;

            $data['estCultivee'] = $request->boolean('estCultivee');
            $data['estJachere'] = $request->boolean('estJachere');
            $data['presenceArbres'] = $request->boolean('presenceArbres');

            /*
            | On ne laisse jamais le formulaire modifier
            | l'exploitation directement.
            */
            unset($data['exploitation_id']);

            $data['exploitation_id'] = $exploitation->idExploitation;

            Parcelle::create($data);
        });

        return redirect()
            ->route(
                'agent.parcelles.index',
                [$recensement, $menage, $exploitant]
            )
            ->with(
                'success',
                'La parcelle a été enregistrée avec succès.'
            );
    }


    /**
     * Formulaire de modification.
     */
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

        /*
        |--------------------------------------------------------------------------
        | Vérifier que la parcelle appartient bien à l'exploitant
        |--------------------------------------------------------------------------
        */
        abort_unless(
            $exploitant->exploitations()
                ->where(
                    'idExploitation',
                    $parcelle->exploitation_id
                )
                ->exists(),
            404
        );

        $exploitations = $exploitant->exploitations()
            ->orderBy('idExploitation')
            ->get();

        return view('parcelles.edit', compact(
            'recensement',
            'menage',
            'exploitant',
            'exploitations',
            'parcelle'
        ));
    }


    /**
     * Met à jour une parcelle.
     */
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

        /*
        |--------------------------------------------------------------------------
        | Sécurité : la parcelle doit appartenir à une exploitation
        | de cet exploitant.
        |--------------------------------------------------------------------------
        */
        abort_unless(
            $exploitant->exploitations()
                ->where(
                    'idExploitation',
                    $parcelle->exploitation_id
                )
                ->exists(),
            404
        );

        $data = $request->validated();

        $data['estCultivee'] = $request->boolean('estCultivee');
        $data['estJachere'] = $request->boolean('estJachere');
        $data['presenceArbres'] = $request->boolean('presenceArbres');

        /*
        |--------------------------------------------------------------------------
        | Changement éventuel d'exploitation
        |--------------------------------------------------------------------------
        */
        if ($request->filled('exploitation_id')) {

            $nouvelleExploitation = $exploitant->exploitations()
                ->where(
                    'idExploitation',
                    $request->input('exploitation_id')
                )
                ->firstOrFail();

            $data['exploitation_id'] =
                $nouvelleExploitation->idExploitation;

        } else {

            $data['exploitation_id'] =
                $parcelle->exploitation_id;
        }

        $parcelle->update($data);

        return redirect()
            ->route(
                'agent.parcelles.index',
                [$recensement, $menage, $exploitant]
            )
            ->with(
                'success',
                'La parcelle a été modifiée avec succès.'
            );
    }


    /**
     * Supprime une parcelle.
     */
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

        abort_unless(
            $exploitant->exploitations()
                ->where(
                    'idExploitation',
                    $parcelle->exploitation_id
                )
                ->exists(),
            404
        );

        $parcelle->delete();

        return redirect()
            ->route(
                'agent.parcelles.index',
                [$recensement, $menage, $exploitant]
            )
            ->with(
                'success',
                'La parcelle a été supprimée avec succès.'
            );
    }


    /**
     * Vérifie que l'agent travaille bien
     * sur le recensement / ménage / exploitant concernés.
     */
    private function verifierContexte(
        Recensement $recensement,
        Menage $menage,
        Exploitant $exploitant
    ): void {
        /*
        |--------------------------------------------------------------------------
        | Recensement appartenant à l'agent connecté
        |--------------------------------------------------------------------------
        */
        abort_unless(
            (int) $recensement->agent_id === (int) Auth::id(),
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Ménage appartenant au recensement
        |--------------------------------------------------------------------------
        */
        abort_unless(
            (int) $menage->recensement_id ===
            (int) $recensement->idRecensement,
            404
        );

        /*
        |--------------------------------------------------------------------------
        | Exploitant appartenant au ménage
        |--------------------------------------------------------------------------
        */
        abort_unless(
            (int) $exploitant->menage_id ===
            (int) $menage->idMenage,
            404
        );
    }
}