<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePointGPSRequest extends FormRequest
{
    /**
     * Autoriser la requête.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Règles de validation.
     */
    public function rules(): array
    {
        return [
            'latitude' => [
                'required',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'required',
                'numeric',
                'between:-180,180',
            ],

            'altitude' => [
                'nullable',
                'numeric',
            ],

            'precisionGPS' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'ordre' => [
                'required',
                'integer',
                'min:1',
            ],
        ];
    }

    /**
     * Messages de validation.
     */
    public function messages(): array
    {
        return [
            'latitude.required' =>
                'La latitude est obligatoire.',

            'latitude.numeric' =>
                'La latitude doit être une valeur numérique.',

            'latitude.between' =>
                'La latitude doit être comprise entre -90 et 90.',

            'longitude.required' =>
                'La longitude est obligatoire.',

            'longitude.numeric' =>
                'La longitude doit être une valeur numérique.',

            'longitude.between' =>
                'La longitude doit être comprise entre -180 et 180.',

            'altitude.numeric' =>
                'L’altitude doit être une valeur numérique.',

            'precisionGPS.numeric' =>
                'La précision GPS doit être une valeur numérique.',

            'precisionGPS.min' =>
                'La précision GPS ne peut pas être négative.',

            'ordre.required' =>
                'L’ordre du point GPS est obligatoire.',

            'ordre.integer' =>
                'L’ordre du point GPS doit être un nombre entier.',

            'ordre.min' =>
                'L’ordre du point GPS doit être supérieur ou égal à 1.',
        ];
    }
}
