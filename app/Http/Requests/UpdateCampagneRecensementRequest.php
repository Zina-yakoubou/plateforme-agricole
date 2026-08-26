<?php

namespace App\Http\Requests;

use App\Models\Canton;
use App\Models\Commune;
use App\Models\Prefecture;
use App\Models\Region;
use Illuminate\Support\Facades\Auth;
use App\Models\Village;
use Illuminate\Foundation\Http\FormRequest;
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

            'resultatsAttendus' => [
                'required',
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
            | PORTÉE TERRITORIALE
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
            | PÉRIODE GÉNÉRALE
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
            | CARACTÈRE OFFICIEL
            |--------------------------------------------------------------------------
            */

            // 'estOfficielle' => [
            //     'required',
            //     'boolean',
            // ],


            /*
            |--------------------------------------------------------------------------
            | STRUCTURE PORTEUSE
            |--------------------------------------------------------------------------
            */

            // 'structure_id' => [
            //     'nullable',
            //     'integer',
            //     'exists:structures,idStructure',
            // ],


            /*
            |--------------------------------------------------------------------------
            | ZONES
            |--------------------------------------------------------------------------
            |
            | Structure attendue :
            |
            | zones[0][region_id]
            | zones[0][prefecture_id]
            | zones[0][commune_id]
            | zones[0][canton_id]
            | zones[0][village_id]
            |
            | Les niveaux inférieurs sont facultatifs.
            |
            */

            'zones' => [
                'nullable',
                'array',
            ],

            'zones.*' => [
                'array',
            ],

            'zones.*.region_id' => [
                'nullable',
                'integer',
                'exists:regions,idRegion',
            ],

            'zones.*.prefecture_id' => [
                'nullable',
                'integer',
                'exists:prefectures,idPrefecture',
            ],

            'zones.*.commune_id' => [
                'nullable',
                'integer',
                'exists:communes,idCommune',
            ],

            'zones.*.canton_id' => [
                'nullable',
                'integer',
                'exists:cantons,idCanton',
            ],

            'zones.*.village_id' => [
                'nullable',
                'integer',
                'exists:villages,idVillage',
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

            $zones = $this->input('zones', []);


            /*
            |--------------------------------------------------------------------------
            | CAMPAGNE NATIONALE
            |--------------------------------------------------------------------------
            |
            | Une campagne nationale couvre automatiquement
            | toutes les régions et toutes les préfectures.
            |
            | Il n'est donc pas nécessaire de sélectionner
            | des zones manuellement.
            |
            */

            if ($portee === 'nationale') {

                if (!empty($zones)) {

                    $validator->errors()->add(
                        'zones',
                        'Une campagne nationale couvre tout le territoire et ne doit pas contenir de zones spécifiques.'
                    );
                }

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | CAMPAGNE RÉGIONALE
            |--------------------------------------------------------------------------
            |
            | Une campagne régionale doit comporter au moins
            | une région.
            |
            */

            if ($portee === 'regionale') {

                if (empty($zones)) {

                    $validator->errors()->add(
                        'zones',
                        'Une campagne régionale doit comporter au moins une région.'
                    );

                    return;
                }

                foreach ($zones as $index => $zone) {

                    $regionId = $zone['region_id'] ?? null;

                    /*
                    |------------------------------------------------------------------
                    | Une région est obligatoire
                    |------------------------------------------------------------------
                    */

                    if (!$regionId) {

                        $validator->errors()->add(
                            "zones.$index.region_id",
                            'Une région doit être sélectionnée.'
                        );

                        continue;
                    }


                    /*
                    |------------------------------------------------------------------
                    | Une campagne régionale commence au niveau région
                    |------------------------------------------------------------------
                    */

                    if (
                        !empty($zone['prefecture_id']) ||
                        !empty($zone['commune_id']) ||
                        !empty($zone['canton_id']) ||
                        !empty($zone['village_id'])
                    ) {

                        $validator->errors()->add(
                            "zones.$index.region_id",
                            'Une campagne régionale doit être définie au niveau régional. Les niveaux inférieurs ne sont pas nécessaires lors de la définition de la portée.'
                        );
                    }
                }
            }


            /*
            |--------------------------------------------------------------------------
            | CAMPAGNE PRÉFECTORALE
            |--------------------------------------------------------------------------
            |
            | Une campagne préfectorale doit comporter au moins
            | une préfecture.
            |
            */

            if ($portee === 'prefectorale') {

                if (empty($zones)) {

                    $validator->errors()->add(
                        'zones',
                        'Une campagne préfectorale doit comporter au moins une préfecture.'
                    );

                    return;
                }

                foreach ($zones as $index => $zone) {

                    $prefectureId = $zone['prefecture_id'] ?? null;


                    /*
                    |------------------------------------------------------------------
                    | Préfecture obligatoire
                    |------------------------------------------------------------------
                    */

                    if (!$prefectureId) {

                        $validator->errors()->add(
                            "zones.$index.prefecture_id",
                            'Une préfecture doit être sélectionnée.'
                        );

                        continue;
                    }


                    /*
                    |------------------------------------------------------------------
                    | Vérification de la cohérence région / préfecture
                    |------------------------------------------------------------------
                    */

                    $prefecture = Prefecture::find(
                        $prefectureId
                    );

                    if (!$prefecture) {
                        continue;
                    }


                    if (
                        !empty($zone['region_id']) &&
                        (int) $zone['region_id'] !== (int) $prefecture->region_id
                    ) {

                        $validator->errors()->add(
                            "zones.$index.prefecture_id",
                            'La préfecture sélectionnée n’appartient pas à la région indiquée.'
                        );
                    }


                    /*
                    |------------------------------------------------------------------
                    | Cohérence commune / préfecture
                    |------------------------------------------------------------------
                    */

                    if (!empty($zone['commune_id'])) {

                        $commune = Commune::find(
                            $zone['commune_id']
                        );

                        if (
                            $commune &&
                            (int) $commune->prefecture_id !== (int) $prefectureId
                        ) {

                            $validator->errors()->add(
                                "zones.$index.commune_id",
                                'La commune sélectionnée n’appartient pas à la préfecture indiquée.'
                            );
                        }
                    }
                }
            }


            /*
            |--------------------------------------------------------------------------
            | COHÉRENCE CANTON / COMMUNE
            |--------------------------------------------------------------------------
            */

            foreach ($zones as $index => $zone) {

                if (
                    empty($zone['canton_id']) ||
                    empty($zone['commune_id'])
                ) {
                    continue;
                }

                $canton = Canton::find(
                    $zone['canton_id']
                );

                if (
                    $canton &&
                    (int) $canton->commune_id !==
                    (int) $zone['commune_id']
                ) {

                    $validator->errors()->add(
                        "zones.$index.canton_id",
                        'Le canton sélectionné n’appartient pas à la commune indiquée.'
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | COHÉRENCE VILLAGE / CANTON
            |--------------------------------------------------------------------------
            */

            foreach ($zones as $index => $zone) {

                if (
                    empty($zone['village_id']) ||
                    empty($zone['canton_id'])
                ) {
                    continue;
                }

                $village = Village::find(
                    $zone['village_id']
                );

                if (
                    $village &&
                    (int) $village->canton_id !==
                    (int) $zone['canton_id']
                ) {

                    $validator->errors()->add(
                        "zones.$index.village_id",
                        'Le village sélectionné n’appartient pas au canton indiqué.'
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | DÉTECTION DES DOUBLONS
            |--------------------------------------------------------------------------
            |
            | Deux lignes décrivant exactement le même périmètre
            | ne doivent pas être enregistrées deux fois.
            |
            */

            $zonesUniques = [];

            foreach ($zones as $index => $zone) {

                $cle = implode(':', [
                    $zone['region_id'] ?? 'null',
                    $zone['prefecture_id'] ?? 'null',
                    $zone['commune_id'] ?? 'null',
                    $zone['canton_id'] ?? 'null',
                    $zone['village_id'] ?? 'null',
                ]);


                if (isset($zonesUniques[$cle])) {

                    $validator->errors()->add(
                        "zones.$index",
                        'Cette zone a déjà été sélectionnée pour cette campagne.'
                    );

                    continue;
                }

                $zonesUniques[$cle] = true;
            }
        });
    }


    /*
    |--------------------------------------------------------------------------
    | MESSAGES PERSONNALISÉS
    |--------------------------------------------------------------------------
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

            'resultatsAttendus.required' =>
                'Les résultats attendus sont obligatoires.',

            'resultatsAttendus.string' =>
                'Les résultats attendus doivent être une chaîne de caractères.',

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
            | OFFICIELLE
            |--------------------------------------------------------------------------
            */

            // 'estOfficielle.required' =>
            //     'Veuillez préciser si la campagne est officielle.',

            // 'estOfficielle.boolean' =>
            //     'La valeur du caractère officiel est invalide.',


            
            /*
            |--------------------------------------------------------------------------
            | ZONES
            |--------------------------------------------------------------------------
            */

            'zones.array' =>
                'Le format des zones sélectionnées est invalide.',

            'zones.*.array' =>
                'Le format de la zone sélectionnée est invalide.',

            'zones.*.region_id.integer' =>
                'L’identifiant de la région est invalide.',

            'zones.*.region_id.exists' =>
                'La région sélectionnée n’existe pas.',

            'zones.*.prefecture_id.integer' =>
                'L’identifiant de la préfecture est invalide.',

            'zones.*.prefecture_id.exists' =>
                'La préfecture sélectionnée n’existe pas.',

            'zones.*.commune_id.integer' =>
                'L’identifiant de la commune est invalide.',

            'zones.*.commune_id.exists' =>
                'La commune sélectionnée n’existe pas.',

            'zones.*.canton_id.integer' =>
                'L’identifiant du canton est invalide.',

            'zones.*.canton_id.exists' =>
                'Le canton sélectionné n’existe pas.',

            'zones.*.village_id.integer' =>
                'L’identifiant du village est invalide.',

            'zones.*.village_id.exists' =>
                'Le village sélectionné n’existe pas.',
        ];
    }
}