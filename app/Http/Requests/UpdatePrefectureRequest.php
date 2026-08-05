<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UpdatePrefectureRequest extends FormRequest
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

                Rule::unique('prefectures','code')
                    ->ignore(
                        $this->prefecture->idPrefecture,
                        'idPrefecture'
                    )

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
                'Le code est obligatoire.',


            'code.unique' =>
                'Ce code est déjà utilisé.',


            'region_id.required' =>
                'La région est obligatoire.',

        ];
    }
}