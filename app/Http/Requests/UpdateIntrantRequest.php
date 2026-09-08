<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIntrantRequest extends FormRequest
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
            'nom' => [
                'required',
                'string',
                'max:255',
            ],

            'type' => [
                'required',
                'in:semence,engrais,herbicide,insecticide,fongicide,fumure_organique,autre',
            ],

            'unite' => [
                'required',
                'string',
                'max:255',
            ],

            'actif' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    /**
     * Messages de validation.
     */
    public function messages(): array
    {
        return [
            'nom.required' =>
                'Le nom de l’intrant est obligatoire.',

            'nom.string' =>
                'Le nom de l’intrant doit être une chaîne de caractères.',

            'nom.max' =>
                'Le nom de l’intrant ne peut pas dépasser 255 caractères.',

            'type.required' =>
                'Le type d’intrant est obligatoire.',

            'type.in' =>
                'Le type d’intrant sélectionné est invalide.',

            'unite.required' =>
                'L’unité de mesure est obligatoire.',

            'unite.string' =>
                'L’unité de mesure doit être une chaîne de caractères.',

            'unite.max' =>
                'L’unité de mesure ne peut pas dépasser 255 caractères.',

            'actif.boolean' =>
                'La valeur indiquant si l’intrant est actif est invalide.',
        ];
    }

    /**
     * Préparer les valeurs avant validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'actif' => $this->boolean('actif'),
        ]);
    }
}

