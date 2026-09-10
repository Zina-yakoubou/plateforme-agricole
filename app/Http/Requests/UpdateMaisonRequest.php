<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMaisonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /*
        |--------------------------------------------------------------------------
        | Maison actuellement modifiée
        |--------------------------------------------------------------------------
        */
        $maison = $this->route('maison');

        /*
        |--------------------------------------------------------------------------
        | Récupération de la clé primaire
        |--------------------------------------------------------------------------
        */
        $idMaison = is_object($maison)
            ? $maison->idMaison
            : $maison;

        return [
            'numeroMaison' => [
                'required',
                'string',
                'max:100',
                Rule::unique('maisons', 'numeroMaison')
                    ->ignore($idMaison, 'idMaison'),
            ],

            'village_id' => [
                'required',
                'integer',
                'exists:villages,idVillage',
            ],

            'latitude' => [
                'nullable',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'nullable',
                'numeric',
                'between:-180,180',
            ],

            'precisionGPS' => [
                'nullable',
                'numeric',
                'min:0',
                'max:9999.99',
            ],

            'adresse' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'numeroMaison.required' => 'Le numéro de maison est obligatoire.',
            'numeroMaison.string' => 'Le numéro de maison doit être une chaîne de caractères.',
            'numeroMaison.max' => 'Le numéro de maison ne peut pas dépasser 100 caractères.',
            'numeroMaison.unique' => 'Ce numéro de maison est déjà utilisé.',

            'village_id.required' => 'Le village est obligatoire.',
            'village_id.integer' => 'Le village sélectionné est invalide.',
            'village_id.exists' => 'Le village sélectionné n’existe pas.',

            'latitude.numeric' => 'La latitude doit être une valeur numérique.',
            'latitude.between' => 'La latitude doit être comprise entre -90 et 90.',

            'longitude.numeric' => 'La longitude doit être une valeur numérique.',
            'longitude.between' => 'La longitude doit être comprise entre -180 et 180.',

            'precisionGPS.numeric' => 'La précision GPS doit être une valeur numérique.',
            'precisionGPS.min' => 'La précision GPS ne peut pas être négative.',
            'precisionGPS.max' => 'La précision GPS est invalide.',

            'adresse.string' => 'L’adresse doit être une chaîne de caractères.',
            'adresse.max' => 'L’adresse ne peut pas dépasser 255 caractères.',
        ];
    }

    public function attributes(): array
    {
        return [
            'numeroMaison' => 'numéro de maison',
            'village_id' => 'village',
            'latitude' => 'latitude',
            'longitude' => 'longitude',
            'precisionGPS' => 'précision GPS',
            'adresse' => 'adresse',
        ];
    }
}