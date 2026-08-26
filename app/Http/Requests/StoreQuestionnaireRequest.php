<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreQuestionnaireRequest extends FormRequest
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
            | IDENTIFICATION
            |--------------------------------------------------------------------------
            */

            'titre' => [
                'required',
                'string',
                'max:255',
            ],

            'version' => [
                'nullable',
                'string',
                'max:50',
            ],

            'description' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | TYPE
            |--------------------------------------------------------------------------
            */

            'type' => [
                'required',
                Rule::in([
                    'exploitation',
                    'menage',
                    'village',
                    'communaute',
                    'thematique',
                    'autre',
                ]),
            ],


            /*
            |--------------------------------------------------------------------------
            | FICHIER
            |--------------------------------------------------------------------------
            */

            'fichier' => [
                'required',
                'file',
                'max:10240', // 10 Mo
                'mimes:xls,xlsx,csv,xml,json,zip',
            ],


            /*
            |--------------------------------------------------------------------------
            | STATUT
            |--------------------------------------------------------------------------
            */

            'actif' => [
                'nullable',
                'boolean',
            ],


            /*
            |--------------------------------------------------------------------------
            | CAMPAGNE
            |--------------------------------------------------------------------------
            |
            | Facultatif.
            | Le questionnaire peut être créé indépendamment
            | puis associé à une campagne.
            |
            */

            'campagne_id' => [
                'nullable',
                'integer',
                'exists:campagne_recensements,idCampagne',
            ],
        ];
    }

    /**
     * Messages personnalisés.
     */
    public function messages(): array
    {
        return [

            'titre.required' =>
                'Le titre du questionnaire est obligatoire.',

            'titre.max' =>
                'Le titre du questionnaire ne peut pas dépasser 255 caractères.',

            'version.max' =>
                'La version ne peut pas dépasser 50 caractères.',

            'type.required' =>
                'Veuillez sélectionner le type de questionnaire.',

            'type.in' =>
                'Le type de questionnaire sélectionné est invalide.',

            'fichier.required' =>
                'Veuillez sélectionner le fichier du questionnaire.',

            'fichier.file' =>
                'Le fichier sélectionné est invalide.',

            'fichier.max' =>
                'Le fichier ne doit pas dépasser 10 Mo.',

            'fichier.mimes' =>
                'Le fichier doit être au format XLS, XLSX, CSV, XML, JSON ou ZIP.',

            'campagne_id.exists' =>
                'La campagne sélectionnée n’existe pas.',
        ];
    }
}