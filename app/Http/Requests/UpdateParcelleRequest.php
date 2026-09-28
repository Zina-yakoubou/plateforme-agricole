<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateParcelleRequest extends FormRequest
{
    /**
     * Autorisation de la requête.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Règles de validation.
     */
    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | EXPLOITATION
            |--------------------------------------------------------------------------
            */

            'typeExploitation' => [
                'nullable',
                Rule::in([
                    'agricole',
                    'elevage',
                    'mixte',
                    'aquacole',
                    'autre',
                ]),
            ],

            'statutJuridique' => [
                'nullable',
                Rule::in([
                    'individuelle',
                    'familiale',
                    'cooperative',
                    'gie',
                    'societe',
                    'autre',
                ]),
            ],

            'nombreTravailleursPermanents' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'nombreTravailleursSaisonniers' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'recoursMainOeuvreFamiliale' => [
                'nullable',
                'boolean',
            ],

            'possedeTracteur' => [
                'nullable',
                'boolean',
            ],

            'possedeMotopompe' => [
                'nullable',
                'boolean',
            ],

            'possedeCharrue' => [
                'nullable',
                'boolean',
            ],

            'activitePrincipale' => [
                'nullable',
                Rule::in([
                    'culture_vivriere',
                    'culture_rente',
                    'elevage',
                    'mixte',
                ]),
            ],

            'destinationProduction' => [
                'nullable',
                Rule::in([
                    'autoconsommation',
                    'vente',
                    'mixte',
                ]),
            ],

            'accesCredit' => [
                'nullable',
                'boolean',
            ],

            'accesEncadrementTechnique' => [
                'nullable',
                'boolean',
            ],

            'exploitation_observations' => [
                'nullable',
                'string',
                'max:5000',
            ],


            /*
            |--------------------------------------------------------------------------
            | GPS
            |--------------------------------------------------------------------------
            */

            'points_gps' => [
                'required',
            ],


            /*
            |--------------------------------------------------------------------------
            | PARCELLE
            |--------------------------------------------------------------------------
            */

            'statutParcelle' => [
                'required',
                Rule::in([
                    'exploitee',
                    'jachere',
                    'non_exploitee',
                ]),
            ],

            'typeSol' => [
                'nullable',
                'string',
                'max:100',
            ],

            'modeFaireValoir' => [
                'required',
                Rule::in([
                    'proprietaire',
                    'location',
                    'pret',
                    'metayage',
                    'autre',
                ]),
            ],

            'modeIrrigation' => [
                'required',
                Rule::in([
                    'pluvial',
                    'gravitaire',
                    'pompage',
                    'aucun',
                    'autre',
                ]),
            ],

            'presenceArbres' => [
                'nullable',
                'boolean',
            ],

            'observations' => [
                'nullable',
                'string',
                'max:5000',
            ],


            /*
            |--------------------------------------------------------------------------
            | CULTURES
            |--------------------------------------------------------------------------
            */

            'cultures' => [
                'nullable',
                'array',
            ],

            'cultures.*' => [
                'array',
            ],

            'cultures.*.culture_id' => [
                'required',
                'integer',
                'exists:cultures,idCulture',
            ],

            'cultures.*.campagneAgricole' => [
                'nullable',
                'string',
                'max:100',
            ],

            'cultures.*.modeCulture' => [
                'required',
                Rule::in([
                    'principale',
                    'associee',
                ]),
            ],

            'cultures.*.superficieCultivee' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'cultures.*.dateSemis' => [
                'nullable',
                'date',
            ],

            'cultures.*.dateRecoltePrevue' => [
                'nullable',
                'date',
            ],

            'cultures.*.dateRecolteEffective' => [
                'nullable',
                'date',
            ],

            'cultures.*.irriguee' => [
                'nullable',
                'boolean',
            ],

            'cultures.*.etatCulture' => [
                'nullable',
                Rule::in([
                    'semis',
                    'croissance',
                    'floraison',
                    'recolte',
                    'terminee',
                ]),
            ],

            'cultures.*.observations' => [
                'nullable',
                'string',
                'max:5000',
            ],


            /*
            |--------------------------------------------------------------------------
            | INTRANTS
            |--------------------------------------------------------------------------
            */

            'cultures.*.intrants' => [
                'nullable',
                'array',
            ],

            'cultures.*.intrants.*' => [
                'array',
            ],

            'cultures.*.intrants.*.intrant_id' => [
                'required',
                'integer',
                'exists:intrants,idIntrant',
            ],

            'cultures.*.intrants.*.quantite' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'cultures.*.intrants.*.nombreApplications' => [
                'required',
                'integer',
                'min:1',
            ],

            'cultures.*.intrants.*.dateApplication' => [
                'nullable',
                'date',
            ],

            'cultures.*.intrants.*.observations' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ];
    }

    /**
     * Messages personnalisés.
     */
    public function messages(): array
    {
        return [

            'points_gps.required' =>
                'Le contour GPS de la parcelle est obligatoire.',

            'statutParcelle.required' =>
                'Le statut de la parcelle est obligatoire.',

            'statutParcelle.in' =>
                'Le statut de la parcelle sélectionné est invalide.',

            'modeFaireValoir.required' =>
                'Le mode de faire-valoir est obligatoire.',

            'modeFaireValoir.in' =>
                'Le mode de faire-valoir sélectionné est invalide.',

            'modeIrrigation.required' =>
                'Le mode d’irrigation est obligatoire.',

            'modeIrrigation.in' =>
                'Le mode d’irrigation sélectionné est invalide.',

            'cultures.*.culture_id.required' =>
                'La culture est obligatoire.',

            'cultures.*.culture_id.exists' =>
                'La culture sélectionnée n’existe pas.',

            'cultures.*.modeCulture.required' =>
                'Le mode de culture est obligatoire.',

            'cultures.*.modeCulture.in' =>
                'Le mode de culture sélectionné est invalide.',

            'cultures.*.superficieCultivee.numeric' =>
                'La superficie cultivée doit être un nombre.',

            'cultures.*.superficieCultivee.min' =>
                'La superficie cultivée ne peut pas être négative.',

            'cultures.*.dateSemis.date' =>
                'La date de semis est invalide.',

            'cultures.*.dateRecoltePrevue.date' =>
                'La date de récolte prévue est invalide.',

            'cultures.*.dateRecolteEffective.date' =>
                'La date de récolte effective est invalide.',

            'cultures.*.etatCulture.in' =>
                'L’état de la culture sélectionné est invalide.',

            'cultures.*.intrants.*.intrant_id.required' =>
                'L’intrant est obligatoire.',

            'cultures.*.intrants.*.intrant_id.exists' =>
                'L’intrant sélectionné n’existe pas.',

            'cultures.*.intrants.*.quantite.required' =>
                'La quantité d’intrant est obligatoire.',

            'cultures.*.intrants.*.quantite.numeric' =>
                'La quantité d’intrant doit être un nombre.',

            'cultures.*.intrants.*.quantite.min' =>
                'La quantité d’intrant doit être supérieure à zéro.',

            'cultures.*.intrants.*.nombreApplications.required' =>
                'Le nombre d’applications est obligatoire.',

            'cultures.*.intrants.*.nombreApplications.integer' =>
                'Le nombre d’applications doit être un nombre entier.',

            'cultures.*.intrants.*.nombreApplications.min' =>
                'Le nombre d’applications doit être au moins égal à 1.',
        ];
    }

    /**
     * Attributs lisibles.
     */
    public function attributes(): array
    {
        return [
            'points_gps' =>
                'contour GPS',

            'typeExploitation' =>
                'type d’exploitation',

            'statutJuridique' =>
                'statut juridique',

            'statutParcelle' =>
                'statut de la parcelle',

            'typeSol' =>
                'type de sol',

            'modeFaireValoir' =>
                'mode de faire-valoir',

            'modeIrrigation' =>
                'mode d’irrigation',

            'presenceArbres' =>
                'présence d’arbres',

            'cultures.*.culture_id' =>
                'culture',

            'cultures.*.modeCulture' =>
                'mode de culture',

            'cultures.*.superficieCultivee' =>
                'superficie cultivée',

            'cultures.*.dateSemis' =>
                'date de semis',

            'cultures.*.dateRecoltePrevue' =>
                'date de récolte prévue',

            'cultures.*.dateRecolteEffective' =>
                'date de récolte effective',

            'cultures.*.etatCulture' =>
                'état de la culture',

            'cultures.*.intrants.*.intrant_id' =>
                'intrant',

            'cultures.*.intrants.*.quantite' =>
                'quantité d’intrant',

            'cultures.*.intrants.*.nombreApplications' =>
                'nombre d’applications',

            'cultures.*.intrants.*.dateApplication' =>
                'date d’application',
        ];
    }
}