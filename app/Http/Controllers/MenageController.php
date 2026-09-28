<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMenageRequest;
use App\Http\Requests\UpdateMenageRequest;
use App\Models\Menage;
use App\Models\Recensement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MenageController extends Controller
{
    /**
     * Liste des ménages d'un recensement.
     */
    public function index(Recensement $recensement): View
    {
        $this->verifierAccesRecensement($recensement);

        $recensement->load([
            'campagne',
            'maison.village.canton.commune.prefecture',
        ]);

        $menages = $recensement->menages()
            ->latest('idMenage')
            ->paginate(15);

        return view(
            'menages.index',
            compact('recensement', 'menages')
        );
    }

    /**
     * Formulaire d'ajout d'un ménage.
     */
    public function create(Recensement $recensement): View
    {
        $this->verifierAccesRecensement($recensement);

        $recensement->load([
            'campagne',
            'maison.village.canton.commune.prefecture',
        ]);

        /*
         * Déterminer automatiquement le prochain numéro
         * de ménage pour ce recensement (affichage uniquement).
         */
        $dernierNumero = $recensement->menages()
            ->max('numeroMenage');

        $prochainNumero = $dernierNumero
            ? ((int) $dernierNumero + 1)
            : 1;

        return view(
            'menages.create',
            compact(
                'recensement',
                'prochainNumero'
            )
        );
    }

    /**
     * Enregistrer un ménage.
     */
    public function store(
        StoreMenageRequest $request,
        Recensement $recensement
    ): RedirectResponse {

        $this->verifierAccesRecensement($recensement);

        //DB::transaction(function () use ($request, $recensement) {

            /*
             * Le numéro de ménage n'est jamais pris depuis le
             * formulaire : il est calculé côté serveur, à
             * l'intérieur de la transaction, avec un verrou,
             * pour éviter les doublons en cas de créations
             * concurrentes sur le même recensement.
             */
        //     $dernierNumero = $recensement->menages()
        //         ->lockForUpdate()
        //         ->max('numeroMenage');

        //     $nouveauNumero = $dernierNumero
        //         ? ((int) $dernierNumero + 1)
        //         : 1;

        //     Menage::create(array_merge(
        //         $request->validated(),
        //         [
        //             'recensement_id' => $recensement->idRecensement,
        //             'numeroMenage' => $nouveauNumero,
        //             'uid' => (string) Str::uuid(),
        //         ]
        //     ));

        //     $recensement->update([
        //         'dateDerniereModification' => now(),
        //     ]);
        // });

        // return redirect()
            // ->route(
            //     'agent.recensements.show',
            //     $recensement
            // )
            // ->with(
            //     'success',
            //     'Le ménage a été ajouté avec succès.'
            // );

            $menage = DB::transaction(function () use ($request, $recensement) {

            $dernierNumero = $recensement->menages()
                ->lockForUpdate()
                ->max('numeroMenage');

            $nouveauNumero = $dernierNumero
                ? ((int) $dernierNumero + 1)
                : 1;

            $menage = Menage::create(array_merge(
                $request->validated(),
                [
                    'recensement_id' => $recensement->idRecensement,
                    'numeroMenage' => $nouveauNumero,
                    'uid' => (string) Str::uuid(),
                ]
            ));

            $recensement->update([
                'dateDerniereModification' => now(),
            ]);

                return $menage;
            });

            if ($menage->possedeExploitation) {
                return redirect()->route(
                    'agent.recensements.menages.exploitants.create',
                    [
                        'recensement' => $recensement->idRecensement,
                        'menage'      => $menage->idMenage,
                    ]
                )->with(
                    'success',
                    'Le ménage a été ajouté. Vous pouvez maintenant enregistrer l’exploitant.'
                );
            }
           
    }

    /**
     * Formulaire de modification.
     */
    public function edit(
        Recensement $recensement,
        Menage $menage
    ): View {

        $this->verifierAccesRecensement($recensement);

        $this->verifierMenageDuRecensement(
            $recensement,
            $menage
        );

        return view(
            'menages.edit',
            compact('recensement', 'menage')
        );
    }

    /**
     * Modifier un ménage.
     */
    public function update(
        UpdateMenageRequest $request,
        Recensement $recensement,
        Menage $menage
    ): RedirectResponse {

        $this->verifierAccesRecensement($recensement);

        $this->verifierMenageDuRecensement(
            $recensement,
            $menage
        );

        $menage->update($request->validated());

        $recensement->update([
            'dateDerniereModification' => now(),
        ]);

        return redirect()
            ->route(
                'agent.recensements.show',
                $recensement
            )
            ->with(
                'success',
                'Le ménage a été modifié avec succès.'
            );
    }

    /**
     * Supprimer un ménage.
     */
    public function destroy(
        Recensement $recensement,
        Menage $menage
    ): RedirectResponse {

        $this->verifierAccesRecensement($recensement);

        $this->verifierMenageDuRecensement(
            $recensement,
            $menage
        );

        $menage->delete();

        $recensement->update([
            'dateDerniereModification' => now(),
        ]);

        return redirect()
            ->route(
                'agent.recensements.show',
                $recensement
            )
            ->with(
                'success',
                'Le ménage a été supprimé avec succès.'
            );
    }

    /**
     * Vérifie que l'agent connecté appartient bien à l'équipe
     * affectée au recensement.
     */
    private function verifierAccesRecensement(
        Recensement $recensement
    ): void {

        $recensement->loadMissing([
            'affectation.equipe.membres',
        ]);

        $user = Auth::user();

        $affectation = $recensement->affectation;

        abort_unless(
            $affectation,
            403,
            'Aucune affectation associée à ce recensement.'
        );

        abort_unless(
            $affectation->statut === 'active',
            403,
            'Cette affectation n’est plus active.'
        );

        $estMembre = $affectation->equipe
            ->membres
            ->contains('id', $user->id);

        abort_unless(
            $estMembre,
            403,
            'Vous n’êtes pas autorisé à accéder à ce recensement.'
        );
    }

    /**
     * Vérifie que le ménage appartient bien au recensement courant.
     */
    private function verifierMenageDuRecensement(
        Recensement $recensement,
        Menage $menage
    ): void {

        abort_unless(
            (int) $menage->recensement_id === (int) $recensement->idRecensement,
            404,
            'Ce ménage n’appartient pas à ce recensement.'
        );
    }
}