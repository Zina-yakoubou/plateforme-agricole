<?php

namespace App\Http\Requests;

use App\Models\Equipe;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateEquipeRequest extends FormRequest
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
            | NOM DE L'ÉQUIPE
            |--------------------------------------------------------------------------
            */

            'nom' => [
                'required',
                'string',
                'max:255',
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

            /*
            |--------------------------------------------------------------------------
            | STATUT
            |--------------------------------------------------------------------------
            */

            'statut' => [
                'nullable',
                Rule::in([
                    'ACTIVE',
                    'INACTIVE',
                ]),
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {

            /*
            |--------------------------------------------------------------------------
            | ÉQUIPE ACTUELLE
            |--------------------------------------------------------------------------
            */

            $equipe = $this->route('equipe');

            if (!$equipe instanceof Equipe) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | SUPERVISEUR ≠ MEMBRE
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
            | VÉRIFICATION DU SUPERVISEUR
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
            | VÉRIFICATION DES AGENTS RECENSEURS
            |--------------------------------------------------------------------------
            */

            if (is_array($this->membres)) {

                $membres = User::whereIn('id', $this->membres)->get();

                foreach ($membres as $membre) {

                    if (!$membre->isAgent()) {

                        $validator->errors()->add(
                            'membres',
                            "L'utilisateur {$membre->name} ne possède pas le rôle d'agent recenseur."
                        );
                    }
                }
            }
        });
    }

    public function messages(): array
    {
        return [

            'nom.required' =>
                'Le nom de l’équipe est obligatoire.',

            'superviseur_id.required' =>
                'Le superviseur est obligatoire.',

            'superviseur_id.exists' =>
                'Le superviseur sélectionné est invalide.',

            'membres.required' =>
                'Veuillez sélectionner au moins un agent recenseur.',

            'membres.min' =>
                'Une équipe doit avoir au moins un agent recenseur.',

            'membres.*.distinct' =>
                'Un agent recenseur ne peut pas être sélectionné plusieurs fois.',

            'membres.*.exists' =>
                'Un des agents recenseurs sélectionnés est invalide.',

            'statut.in' =>
                'Le statut sélectionné est invalide.',
        ];
    }
}