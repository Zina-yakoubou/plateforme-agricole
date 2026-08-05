<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCantonRequest extends FormRequest
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

            'nom' => [
                'required',
                'string',
                'max:255'
            ],


            'code' => [
                'required',
                'string',
                'max:50',
                'unique:cantons,code'
            ],


            'commune_id' => [
                'required',
                'exists:communes,idCommune'
            ],

        ];
    }


    /**
     * Messages personnalisés
     */
    public function messages(): array
    {
        return [

            'nom.required' =>
                'Le nom du canton est obligatoire.',


            'code.required' =>
                'Le code du canton est obligatoire.',


            'code.unique' =>
                'Ce code existe déjà.',


            'commune_id.required' =>
                'La commune est obligatoire.',


            'commune_id.exists' =>
                'La commune sélectionnée est invalide.',

        ];
    }
}