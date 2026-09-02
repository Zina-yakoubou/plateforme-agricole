<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
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

            /*
            |--------------------------------------------------------------------------
            | BESOINS
            |--------------------------------------------------------------------------
            */

            'besoins.array' =>
                'Les besoins transmis sont invalides.',

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

            'besoins.*.unite.string' =>
                'L’unité doit être une chaîne de caractères.',

            'besoins.*.unite.max' =>
                'L’unité ne peut pas dépasser 50 caractères.',

            'besoins.*.observations.string' =>
                'Les observations du besoin doivent être une chaîne de caractères.',

            'besoins.*.observations.max' =>
                'Les observations du besoin ne peuvent pas dépasser 1000 caractères.',
        ];
    }
}