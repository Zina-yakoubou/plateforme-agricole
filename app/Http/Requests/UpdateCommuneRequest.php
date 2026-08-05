<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCommuneRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
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

                Rule::unique('communes', 'code')
                    ->ignore(
                        $this->commune->idCommune,
                        'idCommune'
                    ),

            ],

            'prefecture_id' => [

                'required',

                'exists:prefectures,idPrefecture',

            ],

        ];
    }

    public function messages(): array
    {
        return [

            'nom.required' => 'Le nom de la commune est obligatoire.',

            'code.required' => 'Le code est obligatoire.',

            'code.unique' => 'Ce code existe déjà.',

            'prefecture_id.required' => 'Veuillez choisir une préfecture.',

            'prefecture_id.exists' => 'La préfecture sélectionnée est invalide.',

        ];
    }
}