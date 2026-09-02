<?php

namespace App\Http\Requests;

use App\Models\Canton;
use App\Models\Commune;
use App\Models\Prefecture;
use App\Models\Region;
use App\Models\Village;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreCampagneRecensementRequest extends FormRequest
{
    /**
     * Autorisation.
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
            | ZONES BÉNÉFICIAIRES
            |--------------------------------------------------------------------------
            |
            | Les tableaux sont facultatifs individuellement.
            |
            | La cohérence est contrôlée plus bas selon la portée.
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

            'prefecture_ids' => [
                'nullable',
                'array',
            ],

            'prefecture_ids.*' => [
                'integer',
                'distinct',
                'exists:prefectures,idPrefecture',
            ],

            'commune_ids' => [
                'nullable',
                'array',
            ],

            'commune_ids.*' => [
                'integer',
                'distinct',
                'exists:communes,idCommune',
            ],

            'canton_ids' => [
                'nullable',
                'array',
            ],

            'canton_ids.*' => [
                'integer',
                'distinct',
                'exists:cantons,idCanton',
            ],

            'village_ids' => [
                'nullable',
                'array',
            ],

            'village_ids.*' => [
                'integer',
                'distinct',
                'exists:villages,idVillage',
            ],
        ];
    }


    /**
     * Validation métier du périmètre géographique.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {

            $portee = $this->input('portee');

            $regionIds = collect($this->input('region_ids', []))
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values();

            $prefectureIds = collect($this->input('prefecture_ids', []))
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values();

            $communeIds = collect($this->input('commune_ids', []))
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values();

            $cantonIds = collect($this->input('canton_ids', []))
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values();

            $villageIds = collect($this->input('village_ids', []))
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values();


            /*
            |--------------------------------------------------------------------------
            | CAMPAGNE NATIONALE
            |--------------------------------------------------------------------------
            |
            | Toute la couverture nationale est concernée.
            |
            | Il ne faut donc pas définir de zone particulière.
            |
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
                        'Une campagne nationale couvre automatiquement tout le territoire. Aucune zone spécifique ne doit être sélectionnée.'
                    );
                }

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | CAMPAGNE RÉGIONALE
            |--------------------------------------------------------------------------
            |
            | Une campagne régionale doit commencer par au moins une région.
            |
            | Ensuite, l'utilisateur peut préciser des préfectures,
            | communes, cantons ou villages appartenant à ces régions.
            |
            | Les contrôles de cohérence (appartenance à une région
            | sélectionnée) restent pertinents ici, car la région EST
            | le point de départ obligatoire de cette portée.
            |
            */

            if ($portee === 'regionale') {

                if ($regionIds->isEmpty()) {

                    $validator->errors()->add(
                        'region_ids',
                        'Veuillez sélectionner au moins une région concernée par la campagne.'
                    );

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | PRÉFECTURES
                |--------------------------------------------------------------------------
                */

                if ($prefectureIds->isNotEmpty()) {

                    $prefecturesInvalides = Prefecture::query()
                        ->whereIn('idPrefecture', $prefectureIds)
                        ->whereNotIn('region_id', $regionIds)
                        ->exists();

                    if ($prefecturesInvalides) {

                        $validator->errors()->add(
                            'prefecture_ids',
                            'Une ou plusieurs préfectures sélectionnées n’appartiennent pas aux régions choisies.'
                        );
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | COMMUNES
                |--------------------------------------------------------------------------
                */

                if ($communeIds->isNotEmpty()) {

                    $communesInvalides = Commune::query()
                        ->whereIn('idCommune', $communeIds)
                        ->whereDoesntHave('prefecture', function ($query) use ($regionIds) {

                            $query->whereIn('region_id', $regionIds);

                        })
                        ->exists();

                    if ($communesInvalides) {

                        $validator->errors()->add(
                            'commune_ids',
                            'Une ou plusieurs communes sélectionnées ne se trouvent pas dans une région concernée.'
                        );
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | CANTONS
                |--------------------------------------------------------------------------
                */

                if ($cantonIds->isNotEmpty()) {

                    $cantonsInvalides = Canton::query()
                        ->whereIn('idCanton', $cantonIds)
                        ->whereDoesntHave('commune.prefecture', function ($query) use ($regionIds) {

                            $query->whereIn('region_id', $regionIds);

                        })
                        ->exists();

                    if ($cantonsInvalides) {

                        $validator->errors()->add(
                            'canton_ids',
                            'Un ou plusieurs cantons sélectionnés ne se trouvent pas dans une région concernée.'
                        );
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | VILLAGES
                |--------------------------------------------------------------------------
                */

                if ($villageIds->isNotEmpty()) {

                    $villagesInvalides = Village::query()
                        ->whereIn('idVillage', $villageIds)
                        ->whereDoesntHave('canton.commune.prefecture', function ($query) use ($regionIds) {

                            $query->whereIn('region_id', $regionIds);

                        })
                        ->exists();

                    if ($villagesInvalides) {

                        $validator->errors()->add(
                            'village_ids',
                            'Un ou plusieurs villages sélectionnés ne se trouvent pas dans une région concernée.'
                        );
                    }
                }

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | CAMPAGNE PRÉFECTORALE
            |--------------------------------------------------------------------------
            |
            | L'utilisateur peut sélectionner, via l'accordéon, n'importe
            | quel niveau (préfecture entière, commune, canton ou
            | village) indépendamment des autres. Il n'y a donc PAS de
            | contrôle de cohérence entre prefecture_ids et les niveaux
            | inférieurs : chaque identifiant est déjà validé comme
            | existant réellement en base via la règle "exists:...".
            |
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
                        'Veuillez sélectionner au moins une préfecture, une commune, un canton ou un village concerné par la campagne.'
                    );

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Une campagne préfectorale ne sélectionne pas directement
                | une région.
                |--------------------------------------------------------------------------
                */

                if ($regionIds->isNotEmpty()) {

                    $validator->errors()->add(
                        'region_ids',
                        'Pour une campagne préfectorale, sélectionnez directement les préfectures concernées.'
                    );
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
            | CAMPAGNE
            |--------------------------------------------------------------------------
            */

            'libelle.required' =>
                'Le libellé de la campagne est obligatoire.',

            'libelle.max' =>
                'Le libellé de la campagne ne peut pas dépasser 255 caractères.',

            'objectifs.required' =>
                'Les objectifs de la campagne sont obligatoires.',


            /*
            |--------------------------------------------------------------------------
            | PORTÉE
            |--------------------------------------------------------------------------
            */

            'portee.required' =>
                'Veuillez sélectionner la portée de la campagne.',

            'portee.in' =>
                'La portée sélectionnée est invalide.',


            /*
            |--------------------------------------------------------------------------
            | DATES
            |--------------------------------------------------------------------------
            */

            'dateDebut.required' =>
                'La date et l’heure de début sont obligatoires.',

            'dateDebut.after_or_equal' =>
                'La campagne doit commencer aujourd’hui ou à une date ultérieure.',

            'dateFin.after_or_equal' =>
                'La date de fin doit être postérieure ou égale à la date de début.',


            /*
            |--------------------------------------------------------------------------
            | QUESTIONNAIRES
            |--------------------------------------------------------------------------
            */

            'questionnaire_ids.array' =>
                'La liste des questionnaires est invalide.',

            'questionnaire_ids.*.exists' =>
                'Le questionnaire sélectionné n’existe pas.',

            'questionnaire_ids.*.distinct' =>
                'Un même questionnaire ne peut pas être sélectionné plusieurs fois.',


            /*
            |--------------------------------------------------------------------------
            | RÉGIONS
            |--------------------------------------------------------------------------
            */

            'region_ids.array' =>
                'La liste des régions est invalide.',

            'region_ids.*.exists' =>
                'La région sélectionnée n’existe pas.',

            'region_ids.*.distinct' =>
                'Une même région ne peut pas être sélectionnée plusieurs fois.',


            /*
            |--------------------------------------------------------------------------
            | PRÉFECTURES
            |--------------------------------------------------------------------------
            */

            'prefecture_ids.array' =>
                'La liste des préfectures est invalide.',

            'prefecture_ids.*.exists' =>
                'La préfecture sélectionnée n’existe pas.',

            'prefecture_ids.*.distinct' =>
                'Une même préfecture ne peut pas être sélectionnée plusieurs fois.',


            /*
            |--------------------------------------------------------------------------
            | COMMUNES
            |--------------------------------------------------------------------------
            */

            'commune_ids.array' =>
                'La liste des communes est invalide.',

            'commune_ids.*.exists' =>
                'La commune sélectionnée n’existe pas.',

            'commune_ids.*.distinct' =>
                'Une même commune ne peut pas être sélectionnée plusieurs fois.',


            /*
            |--------------------------------------------------------------------------
            | CANTONS
            |--------------------------------------------------------------------------
            */

            'canton_ids.array' =>
                'La liste des cantons est invalide.',

            'canton_ids.*.exists' =>
                'Le canton sélectionné n’existe pas.',

            'canton_ids.*.distinct' =>
                'Un même canton ne peut pas être sélectionné plusieurs fois.',


            /*
            |--------------------------------------------------------------------------
            | VILLAGES
            |--------------------------------------------------------------------------
            */

            'village_ids.array' =>
                'La liste des villages est invalide.',

            'village_ids.*.exists' =>
                'Le village sélectionné n’existe pas.',

            'village_ids.*.distinct' =>
                'Un même village ne peut pas être sélectionné plusieurs fois.',
        ];
    }
}