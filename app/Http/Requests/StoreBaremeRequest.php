<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreBaremeRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = Auth::user();
        return $user !== null && ($user->isAdmin() || $user->isDpa() || $user->prefecture_id !== null);
    }

    public function rules(): array
    {
        return [
            // On autorise soit l'un soit l'autre sans bloquer
            'culture_id'             => ['nullable', 'integer', 'exists:cultures,idCulture'],
            'nouvelle_culture'       => ['nullable', 'string', 'max:100'],
            
            'intrant_id'             => ['nullable', 'integer', 'exists:intrants,idIntrant'],
            'nouvel_intrant'         => ['nullable', 'string', 'max:100'],
            
            'dose_par_hectare'       => ['required', 'numeric', 'min:0.01'],
            'unite'                  => ['required', 'string', 'max:20'],
            'plafond_max_producteur' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    // Ajout d'une vérification personnalisée après validation de base
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
            'dose_par_hectare.required' => 'La dose par hectare est obligatoire.',
            'dose_par_hectare.numeric'  => 'La dose doit être un nombre valide.',
            'dose_par_hectare.min'      => 'La dose doit être strictement supérieure à 0.',
            'unite.required'            => 'L\'unité de mesure est requise.',
        ];
    }
}