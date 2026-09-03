<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreAffectationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * canton_id n'est plus un champ du formulaire : chaque
     * village porte déjà son canton en base, la vérification
     * se fait directement sur les villages dans le contrôleur.
     */
    public function rules(): array
    {
        return [

            'campagne_id' => [
                'required',
                'integer',
                'exists:campagne_recensements,idCampagne',
            ],

            'equipe_id' => [
                'required',
                'integer',
                'exists:equipes,idEquipe',
            ],

            'village_ids' => [
                'required',
                'array',
                'min:1',
            ],

            'village_ids.*' => [
                'required',
                'integer',
                'distinct',
                'exists:villages,idVillage',
            ],

            'dateDebut' => [
                'nullable',
                'date',
            ],

            'dateFin' => [
                'nullable',
                'date',
                'after_or_equal:dateDebut',
            ],

            'statut' => [
                'nullable',
                'in:active,terminee,annulee',
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

            'campagne_id.integer' =>
                'La campagne sélectionnée est invalide.',

            'campagne_id.exists' =>
                'La campagne sélectionnée est invalide.',

            'equipe_id.required' =>
                'L’équipe est obligatoire.',

            'equipe_id.integer' =>
                'L’équipe sélectionnée est invalide.',

            'equipe_id.exists' =>
                'L’équipe sélectionnée est invalide.',

            'village_ids.required' =>
                'Veuillez sélectionner au moins un village.',

            'village_ids.array' =>
                'La sélection des villages est invalide.',

            'village_ids.min' =>
                'Veuillez sélectionner au moins un village.',

            'village_ids.*.required' =>
                'Un village sélectionné est invalide.',

            'village_ids.*.integer' =>
                'Un village sélectionné est invalide.',

            'village_ids.*.distinct' =>
                'Un même village ne peut pas être sélectionné plusieurs fois.',

            'village_ids.*.exists' =>
                'Un des villages sélectionnés est invalide.',

            'dateDebut.date' =>
                'La date de début est invalide.',

            'dateFin.date' =>
                'La date de fin est invalide.',

            'dateFin.after_or_equal' =>
                'La date de fin doit être postérieure ou égale à la date de début.',

            'statut.in' =>
                'Le statut sélectionné est invalide.',

            'observations.string' =>
                'Les observations doivent être du texte.',

            'observations.max' =>
                'Les observations ne peuvent pas dépasser 2000 caractères.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if (
            $this->has('village_ids')
            && !is_array($this->village_ids)
        ) {
            $this->merge([
                'village_ids' => [
                    $this->village_ids,
                ],
            ]);
        }
    }
}