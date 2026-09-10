<?php

namespace App\Http\Requests;

use App\Models\Canton;
use App\Models\Commune;
use App\Models\Prefecture;
use App\Models\Village;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateCampagneRecensementRequest extends FormRequest
{
    /**
     * Autorisation de la requête.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Règles de validation.
     */
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

            'zoneConcerner' => [
                'nullable',
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
            | DATES
            |--------------------------------------------------------------------------
            */

            'dateDebut' => [
                'required',
                'date',
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
                'exists:questionnaires,idQuestionnaire',
            ],

            /*
            |--------------------------------------------------------------------------
            | RÉGIONS
            |--------------------------------------------------------------------------
            */

            'region_ids' => [
                'nullable',
                'array',
            ],

            'region_ids.*' => [
                'integer',
                'exists:regions,idRegion',
            ],

            /*
            |--------------------------------------------------------------------------
            | PRÉFECTURES
            |--------------------------------------------------------------------------
            */

            'prefecture_ids' => [
                'nullable',
                'array',
            ],

            'prefecture_ids.*' => [
                'integer',
                'exists:prefectures,idPrefecture',
            ],

            /*
            |--------------------------------------------------------------------------
            | COMMUNES
            |--------------------------------------------------------------------------
            */

            'commune_ids' => [
                'nullable',
                'array',
            ],

            'commune_ids.*' => [
                'integer',
                'exists:communes,idCommune',
            ],

            /*
            |--------------------------------------------------------------------------
            | CANTONS
            |--------------------------------------------------------------------------
            */

            'canton_ids' => [
                'nullable',
                'array',
            ],

            'canton_ids.*' => [
                'integer',
                'exists:cantons,idCanton',
            ],

            /*
            |--------------------------------------------------------------------------
            | VILLAGES
            |--------------------------------------------------------------------------
            */

            'village_ids' => [
                'nullable',
                'array',
            ],

            'village_ids.*' => [
                'integer',
                'exists:villages,idVillage',
            ],
        ];
    }

    /**
     * Validation métier complémentaire.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {

            $portee = $this->input('portee');

            $regionIds = collect(
                $this->input('region_ids', [])
            )->filter()->unique()->values();

            $prefectureIds = collect(
                $this->input('prefecture_ids', [])
            )->filter()->unique()->values();

            $communeIds = collect(
                $this->input('commune_ids', [])
            )->filter()->unique()->values();

            $cantonIds = collect(
                $this->input('canton_ids', [])
            )->filter()->unique()->values();

            $villageIds = collect(
                $this->input('village_ids', [])
            )->filter()->unique()->values();


            /*
            |--------------------------------------------------------------------------
            | CAMPAGNE NATIONALE
            |--------------------------------------------------------------------------
            */

            if ($portee === 'nationale') {

                if (
                    $regionIds->isNotEmpty() ||
                    $prefectureIds->isNotEmpty() ||
                    $communeIds->isNotEmpty() ||
                    $cantonIds->isNotEmpty() ||
                    $villageIds->isNotEmpty()
                ) {

                    $validator->errors()->add(
                        'portee',
                        'Une campagne nationale couvre tout le territoire et ne doit pas contenir de sélection territoriale.'
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

                if ($regionIds->isEmpty()) {

                    $validator->errors()->add(
                        'region_ids',
                        'Une campagne régionale doit comporter au moins une région.'
                    );

                    return;
                }


                /*
                | Une campagne régionale est définie au niveau des régions.
                | Les niveaux inférieurs ne sont pas nécessaires.
                */

                if (
                    $prefectureIds->isNotEmpty() ||
                    $communeIds->isNotEmpty() ||
                    $cantonIds->isNotEmpty() ||
                    $villageIds->isNotEmpty()
                ) {

                    $validator->errors()->add(
                        'region_ids',
                        'Une campagne régionale doit être définie au niveau régional. Les niveaux inférieurs ne sont pas nécessaires.'
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

                if (
                    $prefectureIds->isEmpty() &&
                    $communeIds->isEmpty() &&
                    $cantonIds->isEmpty() &&
                    $villageIds->isEmpty()
                ) {

                    $validator->errors()->add(
                        'prefecture_ids',
                        'Une campagne préfectorale doit comporter au moins une préfecture ou une zone située dans une préfecture.'
                    );

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | COHÉRENCE COMMUNE → PRÉFECTURE
                |--------------------------------------------------------------------------
                */

                foreach ($communeIds as $communeId) {

                    $commune = Commune::find($communeId);

                    if (!$commune) {
                        continue;
                    }

                    if (
                        $prefectureIds->isNotEmpty() &&
                        !$prefectureIds->contains(
                            $commune->prefecture_id
                        )
                    ) {

                        $validator->errors()->add(
                            'commune_ids',
                            'Une commune sélectionnée n’appartient pas à une préfecture sélectionnée.'
                        );
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | COHÉRENCE CANTON → COMMUNE
                |--------------------------------------------------------------------------
                */

                foreach ($cantonIds as $cantonId) {

                    $canton = Canton::with('commune')
                        ->find($cantonId);

                    if (!$canton || !$canton->commune) {
                        continue;
                    }

                    if (
                        $communeIds->isNotEmpty() &&
                        !$communeIds->contains(
                            $canton->commune_id
                        )
                    ) {

                        $validator->errors()->add(
                            'canton_ids',
                            'Un canton sélectionné n’appartient pas à une commune sélectionnée.'
                        );
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | COHÉRENCE VILLAGE → CANTON
                |--------------------------------------------------------------------------
                */

                foreach ($villageIds as $villageId) {

                    $village = Village::find($villageId);

                    if (!$village) {
                        continue;
                    }

                    if (
                        $cantonIds->isNotEmpty() &&
                        !$cantonIds->contains(
                            $village->canton_id
                        )
                    ) {

                        $validator->errors()->add(
                            'village_ids',
                            'Un village sélectionné n’appartient pas à un canton sélectionné.'
                        );
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | COHÉRENCE PRÉFECTURE → RÉGION
                |--------------------------------------------------------------------------
                */

                foreach ($prefectureIds as $prefectureId) {

                    $prefecture = Prefecture::find(
                        $prefectureId
                    );

                    if (!$prefecture) {
                        continue;
                    }

                    if (
                        $regionIds->isNotEmpty() &&
                        !$regionIds->contains(
                            $prefecture->region_id
                        )
                    ) {

                        $validator->errors()->add(
                            'prefecture_ids',
                            'Une préfecture sélectionnée n’appartient pas à une région sélectionnée.'
                        );
                    }
                }
            }
        });
    }

    /**
     * Messages personnalisés.
     */
    public function messages(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | IDENTIFICATION
            |--------------------------------------------------------------------------
            */

            'libelle.required' =>
                'Le libellé de la campagne est obligatoire.',

            'libelle.string' =>
                'Le libellé de la campagne doit être une chaîne de caractères.',

            'libelle.max' =>
                'Le libellé de la campagne ne peut pas dépasser 255 caractères.',

            'description.string' =>
                'La description doit être une chaîne de caractères.',

            /*
            |--------------------------------------------------------------------------
            | CADRE
            |--------------------------------------------------------------------------
            */

            'objectifs.required' =>
                'Les objectifs de la campagne sont obligatoires.',

            'objectifs.string' =>
                'Les objectifs doivent être une chaîne de caractères.',

            'resultatsAttendus.string' =>
                'Les résultats attendus doivent être une chaîne de caractères.',

            'zoneConcerner.string' =>
                'La zone concernée doit être une chaîne de caractères.',

            'methodologie.string' =>
                'La méthodologie doit être une chaîne de caractères.',

            'instructions.string' =>
                'Les instructions doivent être une chaîne de caractères.',

            /*
            |--------------------------------------------------------------------------
            | PORTÉE
            |--------------------------------------------------------------------------
            */

            'portee.required' =>
                'La portée de la campagne est obligatoire.',

            'portee.in' =>
                'La portée sélectionnée est invalide.',

            /*
            |--------------------------------------------------------------------------
            | DATES
            |--------------------------------------------------------------------------
            */

            'dateDebut.required' =>
                'La date et l’heure de début sont obligatoires.',

            'dateDebut.date' =>
                'La date et l’heure de début sont invalides.',

            'dateFin.date' =>
                'La date et l’heure de fin sont invalides.',

            'dateFin.after_or_equal' =>
                'La date de fin doit être postérieure ou égale à la date de début.',

            /*
            |--------------------------------------------------------------------------
            | QUESTIONNAIRES
            |--------------------------------------------------------------------------
            */

            'questionnaire_ids.array' =>
                'Le format des questionnaires sélectionnés est invalide.',

            'questionnaire_ids.*.integer' =>
                'L’identifiant du questionnaire est invalide.',

            'questionnaire_ids.*.exists' =>
                'Le questionnaire sélectionné n’existe pas.',

            /*
            |--------------------------------------------------------------------------
            | TERRITOIRES
            |--------------------------------------------------------------------------
            */

            'region_ids.array' =>
                'Le format des régions sélectionnées est invalide.',

            'region_ids.*.integer' =>
                'L’identifiant de la région est invalide.',

            'region_ids.*.exists' =>
                'La région sélectionnée n’existe pas.',

            'prefecture_ids.array' =>
                'Le format des préfectures sélectionnées est invalide.',

            'prefecture_ids.*.integer' =>
                'L’identifiant de la préfecture est invalide.',

            'prefecture_ids.*.exists' =>
                'La préfecture sélectionnée n’existe pas.',

            'commune_ids.array' =>
                'Le format des communes sélectionnées est invalide.',

            'commune_ids.*.integer' =>
                'L’identifiant de la commune est invalide.',

            'commune_ids.*.exists' =>
                'La commune sélectionnée n’existe pas.',

            'canton_ids.array' =>
                'Le format des cantons sélectionnés est invalide.',

            'canton_ids.*.integer' =>
                'L’identifiant du canton est invalide.',

            'canton_ids.*.exists' =>
                'Le canton sélectionné n’existe pas.',

            'village_ids.array' =>
                'Le format des villages sélectionnés est invalide.',

            'village_ids.*.integer' =>
                'L’identifiant du village est invalide.',

            'village_ids.*.exists' =>
                'Le village sélectionné n’existe pas.',
        ];
    }
}