<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRegionRequest extends FormRequest
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
                'max:255',
            ],


            'code' => [
                'required',
                'string',
                'max:50',
                'unique:regions,code',
            ],

        ];
    }



    /**
     * Messages personnalisés
     */
    public function messages(): array
    {
        return [

            'nom.required' => 'Le nom de la région est obligatoire.',

            'code.required' => 'Le code de la région est obligatoire.',

            'code.unique' => 'Ce code existe déjà.',

        ];
    }
}