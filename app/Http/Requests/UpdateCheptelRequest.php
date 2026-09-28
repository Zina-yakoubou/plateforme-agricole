<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCheptelRequest extends FormRequest
{
    /**
     * Autorisation.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Règles.
     */
    public function rules(): array
    {
        return [

            'typeAnimal' => [
                'required',
                Rule::in([
                    'bovin',
                    'ovin',
                    'caprin',
                    'porcin',
                    'volaille',
                    'lapin',
                    'asin',
                    'equin',
                    'autre',
                ]),
            ],

            'effectif' => [
                'required',
                'integer',
                'min:1',
            ],

            'modeElevage' => [
                'nullable',
                Rule::in([
                    'extensif',
                    'semi_intensif',
                    'intensif',
                ]),
            ],

            'observations' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ];
    }

    /**
     * Messages.
     */
    public function messages(): array
    {
        return [

            'typeAnimal.required' =>
                'Le type d’animal est obligatoire.',

            'typeAnimal.in' =>
                'Le type d’animal sélectionné est invalide.',

            'effectif.required' =>
                'L’effectif est obligatoire.',

            'effectif.integer' =>
                'L’effectif doit être un nombre entier.',

            'effectif.min' =>
                'L’effectif doit être supérieur ou égal à 1.',

            'modeElevage.in' =>
                'Le mode d’élevage sélectionné est invalide.',

            'observations.string' =>
                'Les observations doivent être du texte.',
        ];
    }

    /**
     * Attributs.
     */
    public function attributes(): array
    {
        return [
            'typeAnimal' =>
                'type d’animal',

            'effectif' =>
                'effectif',

            'modeElevage' =>
                'mode d’élevage',

            'observations' =>
                'observations',
        ];
    }
}