<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UpdateCampagneRecensementRequest extends FormRequest
{


    public function authorize(): bool
    {
        return true;
    }




    public function rules(): array
    {


        $campagne = $this->route('campagne');



        return [

            'codeRNA' => [

                'required',

                'string',

                'max:50',

                Rule::unique(
                    'campagne_recensements',
                    'codeRNA'
                )
                ->ignore(
                    $campagne->idCampagne,
                    'idCampagne'
                )

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




    public function messages(): array
    {

        return [

            'codeRNA.unique' =>
                'Ce code RNA existe déjà.',


            'dateFin.after_or_equal' =>
                'La date de fin doit être après la date de début.',

        ];

    }

}