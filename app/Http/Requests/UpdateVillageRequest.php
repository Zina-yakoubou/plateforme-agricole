<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVillageRequest extends FormRequest
{
    /**
     * Autorisation.
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
        $village = $this->route('village');

        return [

            'nom' => [
                'required',
                'string',
                'max:255',
            ],

            'code' => [
                'required',
                'string',
                'max:20',

                Rule::unique(
                    'villages',
                    'code'
                )->ignore(
                    $village->idVillage,
                    'idVillage'
                ),
            ],

            'canton_id' => [
                'required',
                'exists:cantons,idCanton',
            ],

        ];
    }

    /**
     * Messages personnalisés.
     */
    public function messages(): array
    {
        return [

            'nom.required' =>
                'Le nom du village est obligatoire.',

            'code.required' =>
                'Le code du village est obligatoire.',

            'code.unique' =>
                'Ce code existe déjà.',

            'canton_id.required' =>
                'Veuillez sélectionner un canton.',

            'canton_id.exists' =>
                'Le canton sélectionné est invalide.',

        ];
    }
}