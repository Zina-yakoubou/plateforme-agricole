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
// À ajouter en haut de MenageController.php :
use App\Models\CampagneRecensement;
use Illuminate\Http\Request;

class MenageController extends Controller
{
    /**
     * Liste des ménages d'un recensement.
     */
    // public function index(Recensement $recensement): View
    // {
    //     $this->verifierAccesRecensement($recensement);

    //     $recensement->load([
    //         'campagne',
    //         'maison.village.canton.commune.prefecture',
    //     ]);

    //     $menages = $recensement->menages()
    //         ->latest('idMenage')
    //         ->paginate(15);

    //     return view(
    //         'menages.index',
    //         compact('recensement', 'menages')
    //     );
    // }



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

        // Terme de recherche
        $search = trim((string) request('search'));

        $menages = $recensement->menages()
            ->when($search !== '', function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where(
                        'numeroMenage',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'nomChef',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'prenomChef',
                        'like',
                        '%' . $search . '%'
                    );

                });
            })
            ->latest('idMenage')
            ->paginate(15)
            ->withQueryString();

        return view(
            'menages.index',
            compact(
                'recensement',
                'menages',
                'search'
            )
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
    // public function store(
        
    //     StoreMenageRequest $request,
    //     Recensement $recensement
    // ): RedirectResponse {

    //     // 🔒 SÉCURITÉ : Bloquer si la campagne est fermée ou si le recensement est terminé
    //     if (!$recensement->campagne->estModifiable() || $recensement->statut === 'termine') {
    //         return back()->with('error', 'Action impossible : les données de cette campagne sont en lecture seule.');
    //     }

    //     $this->verifierAccesRecensement($recensement);

    //     $menage = DB::transaction(function () use ($request, $recensement) {

    //         $dernierNumero = $recensement->menages()
    //             ->lockForUpdate()
    //             ->max('numeroMenage');

    //         $nouveauNumero = $dernierNumero
    //             ? ((int) $dernierNumero + 1)
    //             : 1;

    //         $menage = Menage::create(array_merge(
    //             $request->validated(),
    //             [
    //                 'recensement_id' => $recensement->idRecensement,
    //                 'numeroMenage' => $nouveauNumero,
    //                 'uid' => (string) Str::uuid(),
    //             ]
    //         ));

    //         $recensement->update([
    //             'dateDerniereModification' => now(),
    //         ]);

    //         return $menage;
    //     });

    //     /*
    //     * Si le ménage possède une exploitation,
    //     * on poursuit directement vers l'enregistrement de l'exploitant.
    //     */
    //     if ($menage->possedeExploitation) {
    //         return redirect()
    //             ->route(
    //                 'agent.recensements.menages.exploitants.create',
    //                 [
    //                     'recensement' => $recensement->idRecensement,
    //                     'menage' => $menage->idMenage,
    //                 ]
    //             )
    //             ->with(
    //                 'success',
    //                 'Le ménage a été ajouté. Vous pouvez maintenant enregistrer l’exploitant.'
    //             );
    //     }

    //     /*
    //     * Si le ménage ne possède pas d'exploitation,
    //     * on retourne simplement à la fiche du recensement.
    //     */
    //     return redirect()
    //         ->route(
    //             'agent.recensements.show',
    //             $recensement
    //         )
    //         ->with(
    //             'success',
    //             'Le ménage a été ajouté avec succès.'
    //         );
    // }



    public function store(
        StoreMenageRequest $request, // On garde uniquement la Form Request validée
        Recensement $recensement
    ): RedirectResponse {

        // 🔒 SÉCURITÉ : Bloquer si la campagne est fermée ou si le recensement est terminé
        if (!$recensement->campagne->estModifiable() || $recensement->statut === 'termine') {
            return back()->with('error', 'Action impossible : les données de cette campagne sont en lecture seule.');
        }

        $this->verifierAccesRecensement($recensement);

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

        /*
         * Si le ménage possède une exploitation,
         * on poursuit directement vers l'enregistrement de l'exploitant.
         */
        if ($menage->possedeExploitation) {
            return redirect()
                ->route(
                    'agent.recensements.menages.exploitants.create',
                    [
                        'recensement' => $recensement->idRecensement,
                        'menage' => $menage->idMenage,
                    ]
                )
                ->with(
                    'success',
                    'Le ménage a été ajouté. Vous pouvez maintenant enregistrer l’exploitant.'
                );
        }

        /*
         * Si le ménage ne possède pas d'exploitation,
         * on retourne simplement à la fiche du recensement.
         */
        return redirect()
            ->route(
                'agent.recensements.show',
                $recensement
            )
            ->with(
                'success',
                'Le ménage a été ajouté avec succès.'
            );
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




// ... dans la classe MenageController :

/**
 * Formulaire de mise à jour d'un ménage existant
 * dans le cadre d'un recensement / campagne active.
 */
public function miseAJour(Recensement $recensement, Menage $menage): View
{
    $this->verifierAccesRecensement($recensement);
    $this->verifierMenageDuRecensement($recensement, $menage);

    $recensement->load([
        'campagne',
        'maison.village.canton.commune.prefecture',
    ]);

    $menage->load(['exploitants']);

    // Ménages déjà connus sur cette même maison dans d'autres campagnes / recensements
    $autresMenagesMaison = Menage::query()
        ->where('recensement_id', '!=', $recensement->idRecensement)
        ->whereHas('recensement', function ($q) use ($recensement) {
            $q->where('maison_id', $recensement->maison_id);
        })
        ->with('exploitants')
        ->latest('idMenage')
        ->get();

    return view('menages.mise-a-jour', compact(
        'recensement',
        'menage',
        'autresMenagesMaison'
    ));
}

/**
 * Enregistre la mise à jour du ménage sur la campagne active
 * (ajout ou absence d'exploitants et mise à jour de la fiche).
 */
public function enregistrerMiseAJour(
    Request $request,
    Recensement $recensement,
    Menage $menage
): RedirectResponse {
    if (! $recensement->campagne || ! $recensement->campagne->estModifiable() || $recensement->statut === 'termine') {
        return back()->with('error', 'Action impossible : les données de cette campagne sont en lecture seule.');
    }

    $this->verifierAccesRecensement($recensement);
    $this->verifierMenageDuRecensement($recensement, $menage);

    $validated = $request->validate([
        'statut_menage'                  => ['required', 'in:present,absent'],
        'motif_absence_menage'           => ['nullable', 'string', 'max:255'],
        'exploitants_statuts'            => ['nullable', 'array'],
        'exploitants_statuts.*'          => ['in:present,absent'],
        'exploitants_motifs'             => ['nullable', 'array'],
        'exploitants_motifs.*'           => ['nullable', 'string', 'max:255'],
        'nouveaux_exploitants'           => ['nullable', 'array'],
        'nouveaux_exploitants.*.nom'     => ['nullable', 'string', 'max:120'],
        'nouveaux_exploitants.*.prenom'  => ['nullable', 'string', 'max:120'],
        'nouveaux_exploitants.*.cooperative' => ['nullable', 'string', 'max:150'],
        'nouveaux_exploitants.*.numero_piece' => ['nullable', 'string', 'max:100'],
    ]);

    DB::transaction(function () use ($validated, $recensement, $menage) {
        // 1. Met à jour les colonnes si présentes sur la table menages
        $menageUpdate = [];
        if (\Illuminate\Support\Facades\Schema::hasColumn('menages', 'statut_campagne')) {
            $menageUpdate['statut_campagne'] = $validated['statut_menage'];
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('menages', 'motif_absence')) {
            $menageUpdate['motif_absence'] = $validated['statut_menage'] === 'absent'
                ? ($validated['motif_absence_menage'] ?? 'Absent lors de la campagne')
                : null;
        }
        if (! empty($menageUpdate)) {
            $menage->update($menageUpdate);
        }

        // 2. Met à jour la présence / absence des exploitants existants
        $hasStatutPresence = \Illuminate\Support\Facades\Schema::hasColumn('exploitants', 'statut_presence');
        $hasMotifAbsence   = \Illuminate\Support\Facades\Schema::hasColumn('exploitants', 'motif_absence');

        foreach (($validated['exploitants_statuts'] ?? []) as $exploitantId => $etat) {
            $expUpdate = ['updated_at' => now()];

            if ($hasStatutPresence) {
                $expUpdate['statut_presence'] = $etat;
            }
            if ($hasMotifAbsence) {
                $expUpdate['motif_absence'] = $etat === 'absent'
                    ? ($validated['exploitants_motifs'][$exploitantId] ?? 'Absent / Retiré')
                    : null;
            }

            if (count($expUpdate) > 1) {
                DB::table('exploitants')
                    ->where('idExploitant', $exploitantId)
                    ->where('menage_id', $menage->idMenage)
                    ->update($expUpdate);
            }
        }

        // 3. Ajoute les nouveaux exploitants rattachés à ce ménage
        $hasCooperative = \Illuminate\Support\Facades\Schema::hasColumn('exploitants', 'cooperative');
        $hasNumeroPiece = \Illuminate\Support\Facades\Schema::hasColumn('exploitants', 'numero_piece');

        foreach (($validated['nouveaux_exploitants'] ?? []) as $nouveau) {
            $nom = trim((string) ($nouveau['nom'] ?? ''));
            $prenom = trim((string) ($nouveau['prenom'] ?? ''));

            if ($nom === '' && $prenom === '') {
                continue;
            }

            $insertData = [
                'menage_id'  => $menage->idMenage,
                'nom'        => $nom,
                'prenom'     => $prenom,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($hasStatutPresence) {
                $insertData['statut_presence'] = 'ajoute';
            }
            if ($hasCooperative && ! empty($nouveau['cooperative'])) {
                $insertData['cooperative'] = trim((string) $nouveau['cooperative']);
            }
            if ($hasNumeroPiece && ! empty($nouveau['numero_piece'])) {
                $insertData['numero_piece'] = trim((string) $nouveau['numero_piece']);
            }

            DB::table('exploitants')->insert($insertData);
        }

        // 4. Met à jour l'horodatage du recensement
        $recensement->update([
            'dateDerniereModification' => now(),
        ]);
    });

    return redirect()
        ->route('agent.recensements.show', $recensement->idRecensement)
        ->with('success', 'La mise à jour du ménage a bien été enregistrée pour la campagne.');
}
}