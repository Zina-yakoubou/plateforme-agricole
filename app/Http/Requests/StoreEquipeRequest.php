<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEquipeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | ÉQUIPE
            |--------------------------------------------------------------------------
            */

            'nom' => [
                'required',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | CAMPAGNE
            |--------------------------------------------------------------------------
            */

            'campagne_id' => [
                'required',
                'integer',
                'exists:campagne_recensements,idCampagne',
            ],

            /*
            |--------------------------------------------------------------------------
            | PRÉFECTURE
            |--------------------------------------------------------------------------
            */

            'prefecture_id' => [
                'required',
                'integer',
                'exists:prefectures,idPrefecture',
            ],

            /*
            |--------------------------------------------------------------------------
            | SUPERVISEUR
            |--------------------------------------------------------------------------
            */

            'superviseur_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],

            /*
            |--------------------------------------------------------------------------
            | MODE
            |--------------------------------------------------------------------------
            */

            'mode' => [
                'required',
                Rule::in([
                    'individuel',
                    'groupe',
                ]),
            ],

            /*
            |--------------------------------------------------------------------------
            | MEMBRES
            |--------------------------------------------------------------------------
            */

            'membres' => [
                'required',
                'array',
                'min:1',
            ],

            'membres.*' => [
                'integer',
                'distinct',
                'exists:users,id',
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {

            /*
            |--------------------------------------------------------------------------
            | LE SUPERVISEUR NE DOIT PAS ÊTRE MEMBRE
            |--------------------------------------------------------------------------
            */

            if (
                $this->filled('superviseur_id')
                && is_array($this->membres)
                && in_array(
                    (int) $this->superviseur_id,
                    array_map('intval', $this->membres),
                    true
                )
            ) {
                $validator->errors()->add(
                    'membres',
                    'Le superviseur ne peut pas être membre de sa propre équipe.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | MODE INDIVIDUEL
            |--------------------------------------------------------------------------
            */

            if (
                $this->mode === 'individuel'
                && is_array($this->membres)
                && count($this->membres) !== 1
            ) {
                $validator->errors()->add(
                    'membres',
                    'Une équipe individuelle doit avoir exactement un enquêteur.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | VÉRIFICATION DES RÔLES
            |--------------------------------------------------------------------------
            */

            if ($this->filled('superviseur_id')) {

                $superviseur = User::find($this->superviseur_id);

                if ($superviseur && !$superviseur->isSuperviseur()) {

                    $validator->errors()->add(
                        'superviseur_id',
                        'L’utilisateur sélectionné doit avoir le rôle de superviseur.'
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | LES MEMBRES DOIVENT ÊTRE DES ENQUÊTEURS
            |--------------------------------------------------------------------------
            */

            if (is_array($this->membres)) {

                $membres = User::whereIn('id', $this->membres)->get();

                foreach ($membres as $membre) {

                    if (!$membre->isEnqueteur()) {

                        $validator->errors()->add(
                            'membres',
                            "L'utilisateur {$membre->name} ne possède pas le rôle d'enquêteur."
                        );
                    }
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom de l’équipe est obligatoire.',
            'campagne_id.required' => 'La campagne est obligatoire.',
            'campagne_id.exists' => 'La campagne sélectionnée est invalide.',
            'prefecture_id.required' => 'La préfecture est obligatoire.',
            'superviseur_id.required' => 'Le superviseur est obligatoire.',
            'mode.required' => 'Le mode de travail est obligatoire.',
            'mode.in' => 'Le mode de travail sélectionné est invalide.',
            'membres.required' => 'Veuillez sélectionner au moins un enquêteur.',
            'membres.min' => 'Une équipe doit avoir au moins un enquêteur.',
            'membres.*.distinct' => 'Un enquêteur ne peut pas être sélectionné plusieurs fois.',
            'membres.*.exists' => 'Un des enquêteurs sélectionnés est invalide.',
        ];
    }
}