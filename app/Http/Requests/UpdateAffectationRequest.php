<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateAffectationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = Auth::user();
    }

    public function rules(): array
    {
        return [

            'campagne_id' => [
                'required',
                'exists:campagne_recensements,idCampagne',
            ],

            'equipe_id' => [
                'required',
                'exists:equipes,idEquipe',
            ],

            'village_id' => [
                'required',
                'exists:villages,idVillage',
            ],

            'dateDebut' => [
                'required',
                'date',
            ],

            'dateFin' => [
                'nullable',
                'date',
                'after_or_equal:dateDebut',
            ],

            'statut' => [
                'required',
                Rule::in([
                    'active',
                    'terminee',
                    'annulee',
                ]),
            ],

            'observations' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ];
    }

    public function messages(): array
    {
        return [

            'campagne_id.required' =>
                'Veuillez sélectionner une campagne.',

            'equipe_id.required' =>
                'Veuillez sélectionner une équipe.',

            'village_id.required' =>
                'Veuillez sélectionner un village.',

            'dateDebut.required' =>
                'La date de début est obligatoire.',

            'dateFin.after_or_equal' =>
                'La date de fin doit être postérieure ou égale à la date de début.',

            'statut.required' =>
                'Veuillez sélectionner un statut.',

        ];
    }
}