<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StoreCampagnePlanificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'observations' => [
                'nullable',
                'string',
            ],

            'activites' => [
                'required',
                'array',
                'min:1',
            ],

            'activites.*.libelle' => [
                'required',
                'string',
                'max:255',
            ],

            'activites.*.description' => [
                'nullable',
                'string',
            ],

            'activites.*.dateDebut' => [
                'required',
                'date',
            ],

            'activites.*.dateFin' => [
                'required',
                'date',
            ],
        ];
    }

    /**
     * ==========================================================
     * VALIDATION DES DATES
     * ==========================================================
     *
     * Règles :
     *
     * 1. La date de fin doit être >= à la date de début.
     *
     * 2. Une activité suivante doit commencer au moins
     *    le jour suivant la fin de l'activité précédente.
     *
     * Exemple :
     *
     * Activité 1 : 23/08 → 24/08
     * Activité 2 : 25/08 → 27/08
     * Activité 3 : 28/08 → 30/08
     *
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {

            $activites = $this->input('activites', []);

            foreach ($activites as $index => $activite) {

                $dateDebut = $activite['dateDebut'] ?? null;
                $dateFin   = $activite['dateFin'] ?? null;

                /*
                |--------------------------------------------------------------------------
                | On ne fait les comparaisons que si les deux dates existent.
                |--------------------------------------------------------------------------
                */
                if (!$dateDebut || !$dateFin) {
                    continue;
                }

                try {

                    $debut = Carbon::parse($dateDebut);
                    $fin   = Carbon::parse($dateFin);

                    /*
                    |--------------------------------------------------------------------------
                    | 1. LA FIN NE PEUT PAS ÊTRE AVANT LE DÉBUT
                    |--------------------------------------------------------------------------
                    */
                    if ($fin->lt($debut)) {

                        $validator->errors()->add(
                            "activites.$index.dateFin",
                            "La date de fin doit être postérieure ou égale à la date de début."
                        );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | 2. CONTRÔLE PAR RAPPORT À L'ACTIVITÉ PRÉCÉDENTE
                    |--------------------------------------------------------------------------
                    */
                    if ($index > 0) {

                        $activitePrecedente = $activites[$index - 1] ?? null;

                        $dateFinPrecedente =
                            $activitePrecedente['dateFin'] ?? null;

                        if ($dateFinPrecedente) {

                            $finPrecedente = Carbon::parse(
                                $dateFinPrecedente
                            );

                            /*
                            |--------------------------------------------------------------------------
                            | Le jour suivant est obligatoire.
                            |
                            | Si activité précédente finit le 24,
                            | l'activité actuelle doit commencer le 25 minimum.
                            |--------------------------------------------------------------------------
                            */

                            $dateDebutMinimum = $finPrecedente
                                ->copy()
                                ->addDay()
                                ->startOfDay();

                            if (
                                $debut->startOfDay()
                                    ->lt($dateDebutMinimum)
                            ) {

                                $validator->errors()->add(
                                    "activites.$index.dateDebut",
                                    "Cette activité doit commencer au moins le "
                                    . $dateDebutMinimum->format('d/m/Y')
                                    . ", soit le lendemain de la fin de l'activité précédente."
                                );
                            }
                        }
                    }

                } catch (\Throwable $e) {

                    /*
                    |--------------------------------------------------------------------------
                    | Les règles "date" s'occupent déjà des dates invalides.
                    |--------------------------------------------------------------------------
                    */
                }
            }
        });
    }

    public function messages(): array
    {
        return [

            'activites.required' =>
                'Vous devez ajouter au moins une activité.',

            'activites.array' =>
                'Le format des activités est invalide.',

            'activites.min' =>
                'Vous devez ajouter au moins une activité.',

            'activites.*.libelle.required' =>
                'Le nom de chaque activité est obligatoire.',

            'activites.*.libelle.string' =>
                'Le nom de chaque activité doit être du texte.',

            'activites.*.dateDebut.required' =>
                'La date de début est obligatoire.',

            'activites.*.dateDebut.date' =>
                'La date de début est invalide.',

            'activites.*.dateFin.required' =>
                'La date de fin est obligatoire.',

            'activites.*.dateFin.date' =>
                'La date de fin est invalide.',
        ];
    }
}