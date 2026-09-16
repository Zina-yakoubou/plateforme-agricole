<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreMaisonRequest extends FormRequest
{
    /**
     * Autoriser la requête.
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
            'adresse' => [
                'nullable',
                'string',
                'max:255',
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
            ],
        ];
    }

    /**
     * Messages personnalisés.
     */
    public function messages(): array
    {
        return [
            'adresse.string' =>
                'L’adresse doit être une chaîne de caractères.',

            'adresse.max' =>
                'L’adresse ne peut pas dépasser 255 caractères.',

            'latitude.numeric' =>
                'La latitude doit être un nombre.',

            'latitude.between' =>
                'La latitude doit être comprise entre -90 et 90.',

            'longitude.numeric' =>
                'La longitude doit être un nombre.',

            'longitude.between' =>
                'La longitude doit être comprise entre -180 et 180.',

            'precisionGPS.numeric' =>
                'La précision GPS doit être un nombre.',

            'precisionGPS.min' =>
                'La précision GPS ne peut pas être négative.',
        ];
    }
}