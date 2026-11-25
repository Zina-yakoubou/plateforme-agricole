<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreBaremeRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        return $user->isAdmin() || ($user->isDpa() && $user->prefectureId() !== null);
    }

    public function rules(): array
    {
        $user = Auth::user();

        return [
            // L'admin choisit la préfecture ; pour un DPA, ce champ est ignoré par le contrôleur
            'prefecture_id'          => [
                Rule::requiredIf(fn () => $user && $user->isAdmin()),
                'nullable', 'integer', 'exists:prefectures,idPrefecture',
            ],

            'culture_id'             => ['nullable', 'integer', 'exists:cultures,idCulture'],
            'nouvelle_culture'       => ['nullable', 'string', 'max:100'],
            'categorie'              => ['nullable', 'required_with:nouvelle_culture',
                                         'in:vivriere,rente,maraichere,fruitiere,fourragere,autre'],

            'intrant_id'             => ['nullable', 'integer', 'exists:intrants,idIntrant'],
            'nouvel_intrant'         => ['nullable', 'string', 'max:100'],

            'dose_par_hectare'       => ['required', 'numeric', 'min:0.01'],
            'unite'                  => ['required', 'string', 'max:20'],
            'plafond_max_producteur' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (empty($this->culture_id) && empty($this->nouvelle_culture)) {
                $validator->errors()->add('culture_id', 'Veuillez sélectionner une culture ou en saisir une nouvelle.');
            }
            if (empty($this->intrant_id) && empty($this->nouvel_intrant)) {
                $validator->errors()->add('intrant_id', 'Veuillez sélectionner un intrant ou en saisir un nouveau.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'prefecture_id.required'    => 'Choisissez la préfecture concernée par ce barème.',
            'dose_par_hectare.required' => 'La dose par hectare est obligatoire.',
            'dose_par_hectare.numeric'  => 'La dose doit être un nombre valide.',
            'dose_par_hectare.min'      => 'La dose doit être strictement supérieure à 0.',
            'unite.required'            => "L'unité de mesure est requise.",
            'categorie.required_with'   => 'Choisissez la catégorie de la nouvelle culture.',
        ];
    }
}