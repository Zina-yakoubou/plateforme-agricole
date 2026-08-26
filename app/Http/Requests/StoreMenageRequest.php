<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreMenageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }


    public function rules(): array
    {
        return [

            'nomChef' => [
                'required',
                'string',
                'max:255',
            ],

            'nombrePersonnes' => [
                'required',
                'integer',
                'min:1',
            ],

            'aChamp' => [
                'nullable',
                'boolean',
            ],

        ];
    }


    public function messages(): array
    {
        return [

            'nomChef.required' =>
                'Le nom du chef de ménage est obligatoire.',

            'nombrePersonnes.required' =>
                'Le nombre de personnes est obligatoire.',

            'nombrePersonnes.integer' =>
                'Le nombre de personnes doit être un nombre entier.',

            'nombrePersonnes.min' =>
                'Le nombre de personnes doit être au moins égal à 1.',

        ];
    }
}