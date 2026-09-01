<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreAffectationRequest extends FormRequest
{
    /**
     * Autorisation de la requête.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Règles de validation.
     */
    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | CAMPAGNE
            |--------------------------------------------------------------------------
            */

            'campagne_id' => [
                'required',
                'integer',
                'exists:campagne_recensements,idCampagne',
            ],

            /*
            |--------------------------------------------------------------------------
            | ÉQUIPE
            |--------------------------------------------------------------------------
            */

            'equipe_id' => [
                'required',
                'integer',
                'exists:equipes,idEquipe',
            ],

            /*
            |--------------------------------------------------------------------------
            | CANTON
            |--------------------------------------------------------------------------
            |
            | Le canton sert à déterminer les villages disponibles.
            | Il n'est pas enregistré directement dans affectations.
            |
            */

            'canton_id' => [
                'required',
                'integer',
                'exists:cantons,idCanton',
            ],

            /*
            |--------------------------------------------------------------------------
            | VILLAGES
            |--------------------------------------------------------------------------
            |
            | Une équipe peut être affectée à plusieurs villages.
            |
            */

            'village_ids' => [
                'required',
                'array',
                'min:1',
            ],

            'village_ids.*' => [
                'required',
                'integer',
                'distinct',
                'exists:villages,idVillage',
            ],

            /*
            |--------------------------------------------------------------------------
            | DATES
            |--------------------------------------------------------------------------
            |
            | Les dates sont volontairement facultatives.
            | Elles peuvent donc être nulles.
            |
            */

            'dateDebut' => [
                'nullable',
                'date',
            ],

            'dateFin' => [
                'nullable',
                'date',
                'after_or_equal:dateDebut',
            ],

            /*
            |--------------------------------------------------------------------------
            | STATUT
            |--------------------------------------------------------------------------
            */

            'statut' => [
                'nullable',
                'in:active,terminee,annulee',
            ],

            /*
            |--------------------------------------------------------------------------
            | OBSERVATIONS
            |--------------------------------------------------------------------------
            */

            'observations' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ];
    }

    /**
     * Messages personnalisés.
     */
    public function messages(): array
    {
        return [

            /*
            |----------------------------------------------------------------------
            | CAMPAGNE
            |----------------------------------------------------------------------
            */

            'campagne_id.required' =>
                'Veuillez sélectionner une campagne.',

            'campagne_id.integer' =>
                'La campagne sélectionnée est invalide.',

            'campagne_id.exists' =>
                'La campagne sélectionnée est invalide.',


            /*
            |----------------------------------------------------------------------
            | ÉQUIPE
            |----------------------------------------------------------------------
            */

            'equipe_id.required' =>
                'L’équipe est obligatoire.',

            'equipe_id.integer' =>
                'L’équipe sélectionnée est invalide.',

            'equipe_id.exists' =>
                'L’équipe sélectionnée est invalide.',


            /*
            |----------------------------------------------------------------------
            | CANTON
            |----------------------------------------------------------------------
            */

            'canton_id.required' =>
                'Veuillez sélectionner un canton.',

            'canton_id.integer' =>
                'Le canton sélectionné est invalide.',

            'canton_id.exists' =>
                'Le canton sélectionné est invalide.',


            /*
            |----------------------------------------------------------------------
            | VILLAGES
            |----------------------------------------------------------------------
            */

            'village_ids.required' =>
                'Veuillez sélectionner au moins un village.',

            'village_ids.array' =>
                'La sélection des villages est invalide.',

            'village_ids.min' =>
                'Veuillez sélectionner au moins un village.',

            'village_ids.*.required' =>
                'Un village sélectionné est invalide.',

            'village_ids.*.integer' =>
                'Un village sélectionné est invalide.',

            'village_ids.*.distinct' =>
                'Un même village ne peut pas être sélectionné plusieurs fois.',

            'village_ids.*.exists' =>
                'Un des villages sélectionnés est invalide.',


            /*
            |----------------------------------------------------------------------
            | DATES
            |----------------------------------------------------------------------
            */

            'dateDebut.date' =>
                'La date de début est invalide.',

            'dateFin.date' =>
                'La date de fin est invalide.',

            'dateFin.after_or_equal' =>
                'La date de fin doit être postérieure ou égale à la date de début.',


            /*
            |----------------------------------------------------------------------
            | STATUT
            |----------------------------------------------------------------------
            */

            'statut.in' =>
                'Le statut sélectionné est invalide.',


            /*
            |----------------------------------------------------------------------
            | OBSERVATIONS
            |----------------------------------------------------------------------
            */

            'observations.string' =>
                'Les observations doivent être du texte.',

            'observations.max' =>
                'Les observations ne peuvent pas dépasser 2000 caractères.',
        ];
    }

    /**
     * Préparation éventuelle des données avant validation.
     */
    protected function prepareForValidation(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Normaliser village_ids
        |--------------------------------------------------------------------------
        |
        | Si un seul village est envoyé sous forme de valeur simple,
        | on le transforme en tableau.
        |
        */

        if (
            $this->has('village_ids')
            && !is_array($this->village_ids)
        ) {
            $this->merge([
                'village_ids' => [
                    $this->village_ids,
                ],
            ]);
        }
    }
}

