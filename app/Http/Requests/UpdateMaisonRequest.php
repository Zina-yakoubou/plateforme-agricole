<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMaisonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            /*
            |--------------------------------------------------------------------------
            | Informations de la maison
            |--------------------------------------------------------------------------
            */

            'chefMaison' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
            ],

            'adresse' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | GPS
            |--------------------------------------------------------------------------
            */

            'latitude' => [
                'sometimes',
                'nullable',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'sometimes',
                'nullable',
                'numeric',
                'between:-180,180',
            ],

            'precisionGPS' => [
                'sometimes',
                'nullable',
                'numeric',
                'min:0',
                'max:100000',
            ],

            /*
            |--------------------------------------------------------------------------
            | Photo
            |--------------------------------------------------------------------------
            */

            'photoMaison' => [
                'sometimes',
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            /*
            |--------------------------------------------------------------------------
            | Suivi
            |--------------------------------------------------------------------------
            */

            'statut' => [
                'sometimes',
                'required',
                'in:brouillon,en_cours,terminee,verifiee',
            ],

            'dateIdentification' => [
                'sometimes',
                'nullable',
                'date',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'chefMaison.string' =>
                'Le nom de chef de maison doit être une chaîne de caractères.',

            'chefMaison.max' =>
                'Le nom du chef de maison ne peut pas dépasser 255 caractères.',

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

            'precisionGPS.max' =>
                'La précision GPS est invalide.',

            'photoMaison.image' =>
                'Le fichier sélectionné doit être une image.',

            'photoMaison.mimes' =>
                'La photo doit être au format JPG, JPEG, PNG ou WEBP.',

            'photoMaison.max' =>
                'La photo ne doit pas dépasser 5 Mo.',

            'statut.required' =>
                'Le statut de la maison est obligatoire.',

            'statut.in' =>
                'Le statut sélectionné est invalide.',

            'dateIdentification.date' =>
                'La date d’identification est invalide.',
        ];
    }
}