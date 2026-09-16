<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRecensementRequest extends FormRequest
{
    /**
     * Autorisation de la requête.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Règles de validation.
     */
    public function rules(): array
    {
        return [
            'observations' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'statut' => [
                'sometimes',
                Rule::in([
                    'brouillon',
                    'en_cours',
                    'termine',
                ]),
            ],
        ];
    }

    /**
     * Messages personnalisés.
     */
    public function messages(): array
    {
        return [
            'observations.max' => 'Les observations ne peuvent pas dépasser 5000 caractères.',

            'statut.in' => 'Le statut sélectionné est invalide.',
        ];
    }
}