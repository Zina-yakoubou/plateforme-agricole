<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreParcelleRequest extends FormRequest
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
            'numeroParcelle' => [
                'required',
                'string',
                'max:255',
            ],

            'superficie' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'typeSol' => [
                'nullable',
                'string',
                'max:255',
            ],

            'modeFaireValoir' => [
                'required',
                'in:proprietaire,location,pret,metayage,autre',
            ],

            'modeIrrigation' => [
                'required',
                'in:pluvial,gravitaire,pompage,aucun,autre',
            ],

            'estCultivee' => [
                'nullable',
                'boolean',
            ],

            'estJachere' => [
                'nullable',
                'boolean',
            ],

            'presenceArbres' => [
                'nullable',
                'boolean',
            ],

            'observations' => [
                'nullable',
                'string',
            ],

            'statut' => [
                'nullable',
                'in:brouillon,en_cours,terminee',
            ],
        ];
    }

    /**
     * Messages de validation.
     */
    public function messages(): array
    {
        return [
            'numeroParcelle.required' =>
                'Le numéro de la parcelle est obligatoire.',

            'numeroParcelle.string' =>
                'Le numéro de la parcelle doit être une chaîne de caractères.',

            'numeroParcelle.max' =>
                'Le numéro de la parcelle ne peut pas dépasser 255 caractères.',

            'superficie.required' =>
                'La superficie de la parcelle est obligatoire.',

            'superficie.numeric' =>
                'La superficie doit être une valeur numérique.',

            'superficie.min' =>
                'La superficie doit être supérieure à zéro.',

            'typeSol.string' =>
                'Le type de sol doit être une chaîne de caractères.',

            'typeSol.max' =>
                'Le type de sol ne peut pas dépasser 255 caractères.',

            'modeFaireValoir.required' =>
                'Le mode de faire-valoir est obligatoire.',

            'modeFaireValoir.in' =>
                'Le mode de faire-valoir sélectionné est invalide.',

            'modeIrrigation.required' =>
                'Le mode d’irrigation est obligatoire.',

            'modeIrrigation.in' =>
                'Le mode d’irrigation sélectionné est invalide.',

            'estCultivee.boolean' =>
                'La valeur indiquant si la parcelle est cultivée est invalide.',

            'estJachere.boolean' =>
                'La valeur indiquant si la parcelle est en jachère est invalide.',

            'presenceArbres.boolean' =>
                'La valeur indiquant la présence d’arbres est invalide.',

            'observations.string' =>
                'Les observations doivent être une chaîne de caractères.',

            'statut.in' =>
                'Le statut sélectionné est invalide.',
        ];
    }

    /**
     * Préparer les valeurs avant validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'estCultivee' =>
                $this->boolean('estCultivee'),

            'estJachere' =>
                $this->boolean('estJachere'),

            'presenceArbres' =>
                $this->boolean('presenceArbres'),
        ]);
    }
}

