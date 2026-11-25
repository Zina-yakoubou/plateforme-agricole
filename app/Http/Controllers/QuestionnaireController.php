<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestionnaireRequest;
use App\Models\CampagneRecensement;
use App\Models\Questionnaire;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
class QuestionnaireController extends Controller
{
    /**
     * Enregistrer un nouveau questionnaire.
     */
    public function store(
        StoreQuestionnaireRequest $request
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | DONNÉES VALIDÉES
        |--------------------------------------------------------------------------
        */

        $validated = $request->validated();


        /*
        |--------------------------------------------------------------------------
        | TRANSACTION
        |--------------------------------------------------------------------------
        */

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | FICHIER
            |--------------------------------------------------------------------------
            */

            $fichier = $request->file('fichier');

            $chemin = $fichier->store(
                'questionnaires',
                'public'
            );


            /*
            |--------------------------------------------------------------------------
            | CODE QUESTIONNAIRE
            |--------------------------------------------------------------------------
            */

            $codeQuestionnaire = $this->genererCodeQuestionnaire();


            /*
            |--------------------------------------------------------------------------
            | CRÉATION
            |--------------------------------------------------------------------------
            */

            $questionnaire = Questionnaire::create([

                'codeQuestionnaire' => $codeQuestionnaire,

                'titre' => $validated['titre'],

                'version' => $validated['version'] ?? null,

                'description' => $validated['description'] ?? null,

                'type' => $validated['type'],

                'fichier' => $chemin,

                'format' => strtoupper(
                    $fichier->getClientOriginalExtension()
                ),

                'actif' => $validated['actif'] ?? true,

                'created_by' => Auth::id(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | ASSOCIATION À LA CAMPAGNE
            |--------------------------------------------------------------------------
            */

            if (!empty($validated['campagne_id'])) {

                $campagne = CampagneRecensement::findOrFail(
                    $validated['campagne_id']
                );

                /*
                | Un questionnaire peut être associé
                | à une campagne.
                |
                */

                $campagne->questionnaires()->syncWithoutDetaching([
                    $questionnaire->idQuestionnaire,
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | VALIDATION DE LA TRANSACTION
            |--------------------------------------------------------------------------
            */

            DB::commit();


            /*
            |--------------------------------------------------------------------------
            | REDIRECTION
            |--------------------------------------------------------------------------
            */

            if (!empty($validated['campagne_id'])) {

                return redirect()
                    ->route(
                        'campagnes.show',
                        $validated['campagne_id']
                    )
                    ->with(
                        'success',
                        'Le questionnaire a été enregistré et associé à la campagne.'
                    );
            }


            return redirect()
                ->route('questionnaires.index')
                ->with(
                    'success',
                    'Le questionnaire a été enregistré avec succès.'
                );


        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | ANNULATION
            |--------------------------------------------------------------------------
            */

            DB::rollBack();


            /*
            |--------------------------------------------------------------------------
            | SUPPRESSION DU FICHIER SI LA CRÉATION ÉCHOUE
            |--------------------------------------------------------------------------
            */

            if (
                isset($chemin) &&
                Storage::disk('public')->exists($chemin)
            ) {

                Storage::disk('public')->delete($chemin);
            }


            throw $e;
        }
    }


    /**
     * Générer automatiquement le code du questionnaire.
     *
     * Exemple :
     * QST-2026-001
     * QST-2026-002
     */
    private function genererCodeQuestionnaire(): string
    {
        $annee = now()->year;

        $dernier = Questionnaire::query()
            ->where('codeQuestionnaire', 'like', "QST-{$annee}-%")
            ->orderByDesc('idQuestionnaire')
            ->first();


        if (!$dernier) {

            $numero = 1;

        } else {

            $parties = explode(
                '-',
                $dernier->codeQuestionnaire
            );

            $numero = ((int) end($parties)) + 1;
        }


        return sprintf(
            'QST-%d-%03d',
            $annee,
            $numero
        );
    }


        /**
     * Créer un questionnaire depuis le formulaire de campagne (sans quitter la page).
     * Mêmes règles que store() : validation, fichier, code automatique.
     */
    public function storeAjax(StoreQuestionnaireRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $chemin = null;

        try {
            $fichier = $request->file('fichier');

            $chemin = $fichier->store('questionnaires', 'public');

            $questionnaire = Questionnaire::create([
                'codeQuestionnaire' => $this->genererCodeQuestionnaire(),
                'titre'             => $validated['titre'],
                'version'           => $validated['version'] ?? null,
                'description'       => $validated['description'] ?? null,
                'type'              => $validated['type'],
                'fichier'           => $chemin,
                'format'            => strtoupper($fichier->getClientOriginalExtension()),
                'actif'             => $validated['actif'] ?? true,
                'created_by'        => Auth::id(),
            ]);

            return response()->json([
                'id'    => $questionnaire->idQuestionnaire,
                'titre' => $questionnaire->titre,
                'code'  => $questionnaire->codeQuestionnaire,
            ], 201);

        } catch (\Throwable $e) {

            if ($chemin && Storage::disk('public')->exists($chemin)) {
                Storage::disk('public')->delete($chemin);
            }

            report($e);

            return response()->json(['message' => 'Création impossible. Réessayez.'], 500);
        }
    }
}