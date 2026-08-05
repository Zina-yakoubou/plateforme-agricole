<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCantonRequest extends FormRequest
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

        $canton = $this->route('canton');


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

                Rule::unique('cantons', 'code')
                    ->ignore(
                        $canton->idCanton,
                        'idCanton'
                    ),

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