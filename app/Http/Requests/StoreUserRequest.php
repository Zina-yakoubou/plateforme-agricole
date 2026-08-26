<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Autoriser la requête.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Préparer les données avant validation.
     */
    protected function prepareForValidation(): void
    {
        $telephone = $this->telephone;

        if ($telephone) {

            /*
            |--------------------------------------------------------------------------
            | Normalisation du téléphone
            |--------------------------------------------------------------------------
            |
            | Supprimer espaces, tirets, points et parenthèses.
            |
            */

            $telephone = preg_replace(
                '/[\s().-]/',
                '',
                $telephone
            );

            /*
            |--------------------------------------------------------------------------
            | Numéro local
            |--------------------------------------------------------------------------
            |
            | 90112233
            | ↓
            | +22890112233
            |
            */

            if (preg_match('/^[0-9]{8}$/', $telephone)) {
                $telephone = '+228' . $telephone;
            }

            /*
            |--------------------------------------------------------------------------
            | Format international avec 00
            |--------------------------------------------------------------------------
            |
            | 0022890112233
            | ↓
            | +22890112233
            |
            */

            if (preg_match('/^00228[0-9]{8}$/', $telephone)) {
                $telephone = '+228' . substr($telephone, 5);
            }
        }

        $this->merge([
            'telephone' => $telephone,
        ]);
    }

    /**
     * Règles de validation.
     */
    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Nom complet
            |--------------------------------------------------------------------------
            */

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | Téléphone
            |--------------------------------------------------------------------------
            |
            | Le téléphone est l'identifiant de connexion.
            |
            */

            'telephone' => [
                'required',
                'string',
                'regex:/^\+228[0-9]{8}$/',
                'unique:users,telephone',
            ],

            /*
            |--------------------------------------------------------------------------
            | Email
            |--------------------------------------------------------------------------
            |
            | L'email reste facultatif.
            |
            */

            'email' => [
                'nullable',
                'email',
                'max:255',
                'unique:users,email',
            ],

            /*
            |--------------------------------------------------------------------------
            | Rôle
            |--------------------------------------------------------------------------
            */

            'role_id' => [
                'required',
                'string',
                'exists:roles,idRole',
            ],

            /*
            |--------------------------------------------------------------------------
            | Mot de passe
            |--------------------------------------------------------------------------
            |
            | Obligatoire lors de la création du compte.
            |
            */

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ];
    }

    /**
     * Messages personnalisés.
     */
    public function messages(): array
    {
        return [

            'name.required' =>
                'Le nom complet est obligatoire.',

            'name.string' =>
                'Le nom complet doit être une chaîne de caractères.',

            'telephone.required' =>
                'Le numéro de téléphone est obligatoire.',

            'telephone.regex' =>
                'Le numéro doit être un numéro togolais valide au format +228 suivi de 8 chiffres.',

            'telephone.unique' =>
                'Ce numéro de téléphone est déjà associé à un compte.',

            'email.email' =>
                'Veuillez saisir une adresse e-mail valide.',

            'email.unique' =>
                'Cette adresse e-mail est déjà utilisée.',

            'role_id.required' =>
                'Le rôle est obligatoire.',

            'role_id.exists' =>
                'Le rôle sélectionné n’existe pas.',

            'password.required' =>
                'Le mot de passe est obligatoire.',

            'password.min' =>
                'Le mot de passe doit contenir au moins 8 caractères.',

            'password.confirmed' =>
                'La confirmation du mot de passe ne correspond pas.',
        ];
    }
}