<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreBaremeRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = Auth::user();

        return $user !== null && $user->prefecture_id !== null;
    }

    public function rules(): array
    {
        return [
            'culture_id'             => ['required', 'integer', 'exists:cultures,idCulture'],
            'intrant_id'             => ['required', 'integer', 'exists:intrants,idIntrant'],
            'dose_par_hectare'       => ['required', 'numeric', 'min:0.01'],
            'unite'                  => ['required', 'string', 'max:20'],
            'plafond_max_producteur' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'culture_id.required'       => 'Veuillez sélectionner une culture.',
            'culture_id.exists'         => 'La culture sélectionnée est invalide.',
            'intrant_id.required'       => 'Veuillez sélectionner un intrant.',
            'intrant_id.exists'         => 'L\'intrant sélectionné est invalide.',
            'dose_par_hectare.required' => 'La dose par hectare est obligatoire.',
            'dose_par_hectare.numeric'  => 'La dose doit être un nombre valide.',
            'dose_par_hectare.min'      => 'La dose doit être strictement supérieure à 0.',
            'unite.required'            => 'L\'unité de mesure est requise.',
            'plafond_max_producteur.numeric' => 'Le plafond doit être un nombre valide.',
            'plafond_max_producteur.min' => 'Le plafond ne peut pas être négatif.',
        ];
    }
}