<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCampagneRecensementRequest extends FormRequest
{


    /**
     * Autorisation
     */
    public function authorize(): bool
    {
        return true;
    }



    /**
     * Règles de validation
     */
    public function rules(): array
    {
        return [

            'codeRNA' => [
                'required',
                'string',
                'max:50',
                'unique:campagne_recensements,codeRNA'
            ],


            'libelle' => [
                'required',
                'string',
                'max:255'
            ],


            'dateDebut' => [
                'required',
                'date'
            ],


            'dateFin' => [
                'nullable',
                'date',
                'after_or_equal:dateDebut'
            ],


            'statut' => [
                'required',
                Rule::in([
                    'Préparation',
                    'Active',
                    'Clôturée',
                    'Archivée'
                ])
            ],


            'active' => [
                'boolean'
            ],


            'estOfficielle' => [
                'boolean'
            ],


            'responsable_id' => [
                'nullable',
                'exists:users,id'
            ],


        ];
    }




    /**
     * Messages personnalisés
     */
    public function messages(): array
    {
        return [

            'codeRNA.unique' =>
                'Ce code RNA existe déjà.',


            'dateFin.after_or_equal' =>
                'La date de fin doit être après la date de début.',


            'responsable_id.exists' =>
                'Le responsable sélectionné est invalide.',

        ];
    }

}