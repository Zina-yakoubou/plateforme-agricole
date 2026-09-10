<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMaisonRequest extends FormRequest
{
    /**
     * Autoriser la requête.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation.
     */
    public function rules(): array
    {
        return [
            'uid' => [
                'required',
                'uuid',
                'unique:maisons,uid',
            ],

            'numeroMaison' => [
                'required',
                'string',
                'max:100',
                'unique:maisons,numeroMaison',
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

    /**
     * Messages personnalisés.
     */
    public function messages(): array
    {
        return [
            'uid.required' => 'L’identifiant technique de la maison est obligatoire.',
            'uid.uuid' => 'L’identifiant technique de la maison doit être un UUID valide.',
            'uid.unique' => 'Cette maison existe déjà.',

            'numeroMaison.required' => 'Le numéro de la maison est obligatoire.',
            'numeroMaison.unique' => 'Ce numéro de maison existe déjà.',
            'numeroMaison.max' => 'Le numéro de maison ne peut pas dépasser 100 caractères.',

            'village_id.required' => 'Le village est obligatoire.',
            'village_id.exists' => 'Le village sélectionné n’existe pas.',

            'latitude.numeric' => 'La latitude doit être une valeur numérique.',
            'latitude.between' => 'La latitude doit être comprise entre -90 et 90.',

            'longitude.numeric' => 'La longitude doit être une valeur numérique.',
            'longitude.between' => 'La longitude doit être comprise entre -180 et 180.',

            'precisionGPS.numeric' => 'La précision GPS doit être une valeur numérique.',
            'precisionGPS.min' => 'La précision GPS ne peut pas être négative.',

            'adresse.max' => 'L’adresse ne peut pas dépasser 255 caractères.',
        ];
    }

    /**
     * Noms lisibles des champs.
     */
    public function attributes(): array
    {
        return [
            'uid' => 'identifiant technique',
            'numeroMaison' => 'numéro de maison',
            'village_id' => 'village',
            'latitude' => 'latitude',
            'longitude' => 'longitude',
            'precisionGPS' => 'précision GPS',
            'adresse' => 'adresse',
        ];
    }
}