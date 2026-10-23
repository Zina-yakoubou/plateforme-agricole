<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateBaremeRequest extends FormRequest
{
    public function authorize(): bool
    {
        $bareme = $this->route('bareme');
        return Auth::check() && $bareme && $bareme->prefecture_id === Auth::user()->prefecture_id;
    }

    public function rules(): array
    {
        return [
            'dose_par_hectare'       => ['required', 'numeric', 'min:0.01'],
            'unite'                  => ['required', 'string', 'max:20'],
            'plafond_max_producteur' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'dose_par_hectare.required' => 'La dose par hectare est obligatoire.',
            'dose_par_hectare.numeric'  => 'La dose doit être un nombre valide.',
            'dose_par_hectare.min'      => 'La dose doit être supérieure à 0.',
            'unite.required'            => 'L\'unité de mesure est requise.',
            'plafond_max_producteur.numeric' => 'Le plafond doit être un nombre valide.',
        ];
    }
}