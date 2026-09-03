<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class StoreDpaAgentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isDpa();
    }

    protected function prepareForValidation(): void
    {
        if ($this->telephone) {

            $telephone = preg_replace(
                '/[\s().-]/',
                '',
                $this->telephone
            );

            if (preg_match('/^[0-9]{8}$/', $telephone)) {
                $telephone = '+228'.$telephone;
            }

            if (preg_match('/^00228[0-9]{8}$/', $telephone)) {
                $telephone = '+228'.substr($telephone, 5);
            }

            $this->merge([
                'telephone' => $telephone,
            ]);
        }
    }

    public function rules(): array
    {
        $rolesAutorises = Role::whereIn('nom', [
            'Superviseur',
            'Agent recenseur',
        ])->pluck('idRole')->toArray();

        return [

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'telephone' => [
                'required',
                'regex:/^\+228[0-9]{8}$/',
                'unique:users,telephone',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'role_id' => [
                'required',
                'in:'.implode(',', $rolesAutorises),
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

        ];
    }

    public function messages(): array
    {
        return [

            'name.required' =>
                'Le nom complet est obligatoire.',

            'telephone.required' =>
                'Le téléphone est obligatoire.',

            'telephone.regex' =>
                'Le téléphone doit être un numéro togolais valide.',

            'telephone.unique' =>
                'Ce numéro existe déjà.',

            'email.email' =>
                'Adresse email invalide.',

            'email.unique' =>
                'Cette adresse email existe déjà.',

            'role_id.required' =>
                'Sélectionnez un rôle.',

            'role_id.in' =>
                'Vous pouvez uniquement créer un superviseur ou un agent recenseur.',

            'password.required' =>
                'Le mot de passe est obligatoire.',

            'password.min' =>
                'Le mot de passe doit contenir au moins 8 caractères.',

            'password.confirmed' =>
                'La confirmation du mot de passe est incorrecte.',

        ];
    }
}