<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreMenageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'nomChef' => [
                'required',
                'string',
                'max:255',
            ],

            'prenomChef' => [
                'nullable',
                'string',
                'max:255',
            ],

            'sexeChef' => [
                'required',
                'in:M,F',
            ],

            'nombreHommes' => [
                'required',
                'integer',
                'min:0',
            ],

            'nombreFemmes' => [
                'required',
                'integer',
                'min:0',
            ],

            'nombreGarcons' => [
                'required',
                'integer',
                'min:0',
            ],

            'nombreFilles' => [
                'required',
                'integer',
                'min:0',
            ],

            'possedeExploitation' => [
                'required',
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

    public function messages(): array
    {
        return [
            'nomChef.required' =>
                'Le nom du chef de ménage est obligatoire.',

            'nomChef.string' =>
                'Le nom du chef de ménage doit être une chaîne de caractères.',

            'nomChef.max' =>
                'Le nom du chef de ménage ne peut pas dépasser 255 caractères.',

            'prenomChef.string' =>
                'Le prénom du chef de ménage doit être une chaîne de caractères.',

            'prenomChef.max' =>
                'Le prénom du chef de ménage ne peut pas dépasser 255 caractères.',

            'sexeChef.required' =>
                'Le sexe du chef de ménage est obligatoire.',

            'sexeChef.in' =>
                'Le sexe sélectionné est invalide.',

            'nombreHommes.required' =>
                'Le nombre d’hommes est obligatoire.',

            'nombreHommes.integer' =>
                'Le nombre d’hommes doit être un nombre entier.',

            'nombreHommes.min' =>
                'Le nombre d’hommes ne peut pas être négatif.',

            'nombreFemmes.required' =>
                'Le nombre de femmes est obligatoire.',

            'nombreFemmes.integer' =>
                'Le nombre de femmes doit être un nombre entier.',

            'nombreFemmes.min' =>
                'Le nombre de femmes ne peut pas être négatif.',

            'nombreGarcons.required' =>
                'Le nombre de garçons est obligatoire.',

            'nombreGarcons.integer' =>
                'Le nombre de garçons doit être un nombre entier.',

            'nombreGarcons.min' =>
                'Le nombre de garçons ne peut pas être négatif.',

            'nombreFilles.required' =>
                'Le nombre de filles est obligatoire.',

            'nombreFilles.integer' =>
                'Le nombre de filles doit être un nombre entier.',

            'nombreFilles.min' =>
                'Le nombre de filles ne peut pas être négatif.',

            'possedeExploitation.required' =>
                'Veuillez indiquer si le ménage possède une exploitation agricole.',

            'possedeExploitation.boolean' =>
                'La valeur indiquant la possession d’une exploitation est invalide.',

            'observations.string' =>
                'Les observations doivent être une chaîne de caractères.',

            'statut.in' =>
                'Le statut sélectionné est invalide.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'possedeExploitation' => $this->boolean(
                'possedeExploitation'
            ),
        ]);
    }
}