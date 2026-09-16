<?php

namespace App\Http\Requests;

use App\Models\CampagneRecensement;
use App\Models\Maison;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRecensementRequest extends FormRequest
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
            'campagne_id' => [
                'required',
                'integer',
                'exists:campagne_recensements,idCampagne',
            ],

            'affectation_id' => [
                'required',
                'integer',
                'exists:affectations,idAffectation',
            ],

            'maison_id' => [
                'required',
                'integer',
                'exists:maisons,idMaison',
            ],

            'observations' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ];
    }

    /**
     * Messages personnalisés.
     */
    public function messages(): array
    {
        return [
            'campagne_id.required' => 'La campagne est obligatoire.',
            'campagne_id.exists' => 'La campagne sélectionnée est invalide.',

            'affectation_id.required' => 'L’affectation est obligatoire.',
            'affectation_id.exists' => 'L’affectation sélectionnée est invalide.',

            'maison_id.required' => 'La maison est obligatoire.',
            'maison_id.exists' => 'La maison sélectionnée est invalide.',

            'observations.max' => 'Les observations ne peuvent pas dépasser 5000 caractères.',
        ];
    }
}
