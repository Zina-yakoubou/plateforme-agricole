<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreAffectationRequest extends FormRequest
{
    /**
     * Autorisation
     */
    public function authorize(): bool
    {
        return Auth::check();
    }


    /**
     * Validation
     */
    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Utilisateur
            |--------------------------------------------------------------------------
            */

            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],


            /*
            |--------------------------------------------------------------------------
            | Village
            |--------------------------------------------------------------------------
            |
            | Obligatoire pour un agent recenseur.
            |
            */

            'village_id' => [
                'nullable',
                'integer',
                'exists:villages,idVillage',
            ],


            /*
            |--------------------------------------------------------------------------
            | Préfecture
            |--------------------------------------------------------------------------
            |
            | Obligatoire pour superviseur et technicien.
            |
            */

            'prefecture_id' => [
                'nullable',
                'integer',
                'exists:prefectures,idPrefecture',
            ],


            /*
            |--------------------------------------------------------------------------
            | Dates
            |--------------------------------------------------------------------------
            */

            'dateDebut' => [
                'nullable',
                'date',
            ],

            'dateFin' => [
                'nullable',
                'date',
                'after_or_equal:dateDebut',
            ],
        ];
    }


    /**
     * Messages
     */
    public function messages(): array
    {
        return [

            'user_id.required' =>
                'Veuillez sélectionner un utilisateur.',

            'user_id.exists' =>
                'L\'utilisateur sélectionné n\'existe pas.',

            'village_id.exists' =>
                'Le village sélectionné n\'existe pas.',

            'prefecture_id.exists' =>
                'La préfecture sélectionnée n\'existe pas.',

            'dateDebut.date' =>
                'La date de début est invalide.',

            'dateFin.date' =>
                'La date de fin est invalide.',

            'dateFin.after_or_equal' =>
                'La date de fin doit être postérieure ou égale à la date de début.',
        ];
    }
}