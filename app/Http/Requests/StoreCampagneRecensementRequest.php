<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreCampagneRecensementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | IDENTIFICATION
            |--------------------------------------------------------------------------
            */

            'libelle' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | CADRE DU RECENSEMENT
            |--------------------------------------------------------------------------
            */

            'objectifs' => [
                'required',
                'string',
            ],

            'resultatsAttendus' => [
                'nullable',
                'string',
            ],

            'methodologie' => [
                'nullable',
                'string',
            ],

            'instructions' => [
                'nullable',
                'string',
            ],
             'zoneConserner' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | PORTÉE
            |--------------------------------------------------------------------------
            */

            'portee' => [
                'required',
                Rule::in([
                    'nationale',
                    'regionale',
                    'prefectorale',
                ]),
            ],


            /*
            |--------------------------------------------------------------------------
            | PÉRIODE
            |--------------------------------------------------------------------------
            */

            'dateDebut' => [
                'required',
                'date',
                'after_or_equal:now',
            ],

            'dateFin' => [
                'nullable',
                'date',
                'after_or_equal:dateDebut',
            ],


            /*
            |--------------------------------------------------------------------------
            | QUESTIONNAIRES
            |--------------------------------------------------------------------------
            */

            'questionnaire_ids' => [
                'nullable',
                'array',
            ],

            'questionnaire_ids.*' => [
                'integer',
                'distinct',
                'exists:questionnaires,idQuestionnaire',
            ],


            /*
            |--------------------------------------------------------------------------
            | RÉGIONS
            |--------------------------------------------------------------------------
            |
            | Utilisé uniquement pour une campagne régionale.
            |
            */

            'region_ids' => [
                'nullable',
                'array',
            ],

            'region_ids.*' => [
                'integer',
                'distinct',
                'exists:regions,idRegion',
            ],


            /*
            |--------------------------------------------------------------------------
            | PRÉFECTURES
            |--------------------------------------------------------------------------
            |
            | Utilisé uniquement pour une campagne préfectorale.
            |
            */

            'prefecture_ids' => [
                'nullable',
                'array',
            ],

            'prefecture_ids.*' => [
                'integer',
                'distinct',
                'exists:prefectures,idPrefecture',
            ],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATION MÉTIER
    |--------------------------------------------------------------------------
    */

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {

            $portee = $this->input('portee');

            $regionIds = $this->input('region_ids', []);

            $prefectureIds = $this->input('prefecture_ids', []);


            /*
            |--------------------------------------------------------------------------
            | CAMPAGNE NATIONALE
            |--------------------------------------------------------------------------
            |
            | Aucun territoire spécifique ne doit être sélectionné.
            |
            */

            if ($portee === 'nationale') {

                if (!empty($regionIds)) {

                    $validator->errors()->add(
                        'region_ids',
                        'Une campagne nationale ne doit pas contenir de région spécifique.'
                    );
                }

                if (!empty($prefectureIds)) {

                    $validator->errors()->add(
                        'prefecture_ids',
                        'Une campagne nationale ne doit pas contenir de préfecture spécifique.'
                    );
                }

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | CAMPAGNE RÉGIONALE
            |--------------------------------------------------------------------------
            */

            if ($portee === 'regionale') {

                if (empty($regionIds)) {

                    $validator->errors()->add(
                        'region_ids',
                        'Veuillez sélectionner au moins une région.'
                    );

                    return;
                }

                /*
                | Une campagne régionale ne doit pas recevoir
                | directement des préfectures.
                */

                if (!empty($prefectureIds)) {

                    $validator->errors()->add(
                        'prefecture_ids',
                        'Pour une campagne régionale, sélectionnez les régions et non les préfectures.'
                    );
                }

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | CAMPAGNE PRÉFECTORALE
            |--------------------------------------------------------------------------
            */

            if ($portee === 'prefectorale') {

                if (empty($prefectureIds)) {

                    $validator->errors()->add(
                        'prefecture_ids',
                        'Veuillez sélectionner au moins une préfecture.'
                    );

                    return;
                }

                /*
                | Une campagne préfectorale sélectionne directement
                | les préfectures.
                |
                | Leur région est automatiquement connue par la relation
                | administrative Préfecture → Région.
                */

                if (!empty($regionIds)) {

                    $validator->errors()->add(
                        'region_ids',
                        'Pour une campagne préfectorale, sélectionnez directement les préfectures.'
                    );
                }
            }
        });
    }


    /*
    |--------------------------------------------------------------------------
    | MESSAGES
    |--------------------------------------------------------------------------
    */

    public function messages(): array
    {
        return [

            /*
            | Identification
            */

            'libelle.required' =>
                'Le libellé de la campagne est obligatoire.',

            'libelle.max' =>
                'Le libellé de la campagne ne peut pas dépasser 255 caractères.',


            /*
            | Cadre
            */

            'objectifs.required' =>
                'Les objectifs de la campagne sont obligatoires.',


            /*
            | Portée
            */

            'portee.required' =>
                'Veuillez sélectionner la portée de la campagne.',

            'portee.in' =>
                'La portée sélectionnée est invalide.',


            /*
            | Dates
            */

            'dateDebut.required' =>
                'La date et l’heure de début sont obligatoires.',

            'dateDebut.after_or_equal' =>
                'La campagne doit commencer aujourd’hui ou à une date ultérieure.',

            'dateFin.after_or_equal' =>
                'La date de fin doit être postérieure ou égale à la date de début.',


            /*
            | Questionnaires
            */

            'questionnaire_ids.array' =>
                'La liste des questionnaires est invalide.',

            'questionnaire_ids.*.exists' =>
                'Le questionnaire sélectionné n’existe pas.',

            'questionnaire_ids.*.distinct' =>
                'Un même questionnaire ne peut pas être sélectionné plusieurs fois.',


            /*
            | Régions
            */

            'region_ids.array' =>
                'La liste des régions est invalide.',

            'region_ids.*.exists' =>
                'La région sélectionnée n’existe pas.',

            'region_ids.*.distinct' =>
                'Une même région ne peut pas être sélectionnée plusieurs fois.',


            /*
            | Préfectures
            */

            'prefecture_ids.array' =>
                'La liste des préfectures est invalide.',

            'prefecture_ids.*.exists' =>
                'La préfecture sélectionnée n’existe pas.',

            'prefecture_ids.*.distinct' =>
                'Une même préfecture ne peut pas être sélectionnée plusieurs fois.',
        ];
    }
}