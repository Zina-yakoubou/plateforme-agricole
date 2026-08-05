<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePrefectureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


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
                'max:20',
                'unique:prefectures,code'
            ],


            'region_id' => [
                'required',
                'exists:regions,idRegion'
            ],

        ];
    }



    public function messages(): array
    {
        return [

            'nom.required' =>
                'Le nom de la préfecture est obligatoire.',


            'code.required' =>
                'Le code de la préfecture est obligatoire.',


            'code.unique' =>
                'Ce code existe déjà.',


            'region_id.required' =>
                'La région est obligatoire.',


            'region_id.exists' =>
                'La région sélectionnée est invalide.',

        ];
    }
}