<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StorePlanificationPrefectoraleRequest extends FormRequest
{
    /**
     * Autorisation.
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
            | INFORMATIONS GÉNÉRALES
            |--------------------------------------------------------------------------
            */

            'planTravail' => [
                'nullable',
                'string',
            ],

            'observations' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | COMMUNES
            |--------------------------------------------------------------------------
            |
            | Une commune sélectionnée signifie :
            | "toute la commune est concernée".
            |
            */

            'commune_ids' => [
                'nullable',
                'array',
            ],

            'commune_ids.*' => [
                'integer',
                'distinct',
                'exists:communes,idCommune',
            ],


            /*
            |--------------------------------------------------------------------------
            | CANTONS
            |--------------------------------------------------------------------------
            |
            | Un canton sélectionné signifie :
            | "tout le canton est concerné".
            |
            */

            'canton_ids' => [
                'nullable',
                'array',
            ],

            'canton_ids.*' => [
                'integer',
                'distinct',
                'exists:cantons,idCanton',
            ],


            /*
            |--------------------------------------------------------------------------
            | VILLAGES
            |--------------------------------------------------------------------------
            |
            | Un village sélectionné signifie uniquement
            | que ce village est concerné.
            |
            */

            'village_ids' => [
                'nullable',
                'array',
            ],

            'village_ids.*' => [
                'integer',
                'distinct',
                'exists:villages,idVillage',
            ],


            /*
            |--------------------------------------------------------------------------
            | BESOINS
            |--------------------------------------------------------------------------
            */

            'besoins' => [
                'nullable',
                'array',
            ],

            'besoins.*.categorie' => [
                'required',
                'string',
                'max:100',
            ],

            'besoins.*.designation' => [
                'required',
                'string',
                'max:255',
            ],

            'besoins.*.quantite' => [
                'required',
                'numeric',
                'min:0',
            ],

            'besoins.*.unite' => [
                'nullable',
                'string',
                'max:50',
            ],

            'besoins.*.observations' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    /**
     * Messages personnalisés.
     */
    public function messages(): array
    {
        return [

            'commune_ids.array' =>
                'Les communes sélectionnées sont invalides.',

            'commune_ids.*.exists' =>
                'Une commune sélectionnée n’existe pas.',

            'commune_ids.*.distinct' =>
                'Une commune ne peut pas être sélectionnée plusieurs fois.',


            'canton_ids.array' =>
                'Les cantons sélectionnés sont invalides.',

            'canton_ids.*.exists' =>
                'Un canton sélectionné n’existe pas.',

            'canton_ids.*.distinct' =>
                'Un canton ne peut pas être sélectionné plusieurs fois.',


            'village_ids.array' =>
                'Les villages sélectionnés sont invalides.',

            'village_ids.*.exists' =>
                'Un village sélectionné n’existe pas.',

            'village_ids.*.distinct' =>
                'Un village ne peut pas être sélectionné plusieurs fois.',


            'besoins.*.categorie.required' =>
                'La catégorie du besoin est obligatoire.',

            'besoins.*.designation.required' =>
                'La désignation du besoin est obligatoire.',

            'besoins.*.quantite.required' =>
                'La quantité du besoin est obligatoire.',

            'besoins.*.quantite.numeric' =>
                'La quantité doit être numérique.',

            'besoins.*.quantite.min' =>
                'La quantité ne peut pas être négative.',
        ];
    }

    /**
     * Validation supplémentaire après les règles classiques.
     */
    protected function passedValidation(): void
    {
        /*
        |--------------------------------------------------------------------------
        | NORMALISATION
        |--------------------------------------------------------------------------
        */

        $this->merge([
            'commune_ids' => array_values(
                array_unique(
                    array_map(
                        'intval',
                        $this->input('commune_ids', [])
                    )
                )
            ),

            'canton_ids' => array_values(
                array_unique(
                    array_map(
                        'intval',
                        $this->input('canton_ids', [])
                    )
                )
            ),

            'village_ids' => array_values(
                array_unique(
                    array_map(
                        'intval',
                        $this->input('village_ids', [])
                    )
                )
            ),
        ]);
    }
}
