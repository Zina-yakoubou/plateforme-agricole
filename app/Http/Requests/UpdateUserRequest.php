<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Autoriser la modification.
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
             * Supprimer espaces, tirets, points et parenthèses.
             */
            $telephone = preg_replace(
                '/[\s().-]/',
                '',
                $telephone
            );

            /*
             * 90112233
             * ↓
             * +22890112233
             */
            if (preg_match('/^[0-9]{8}$/', $telephone)) {
                $telephone = '+228' . $telephone;
            }

            /*
             * 0022890112233
             * ↓
             * +22890112233
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
        /*
         * L'utilisateur modifié.
         */
        $user = $this->route('user');

        return [

            /*
             * Nom complet.
             */
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            /*
             * Téléphone.
             *
             * Le téléphone reste unique mais
             * l'utilisateur actuel est ignoré.
             */
            'telephone' => [
                'required',
                'string',
                'regex:/^\+228[0-9]{8}$/',
                Rule::unique('users', 'telephone')
                    ->ignore($user?->id),
            ],

            /*
             * Email facultatif.
             */
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user?->id),
            ],

            /*
             * Rôle.
             *
             * Le rôle est géré séparément selon
             * les autorisations du contrôleur.
             */
            'role_id' => [
                'required',
                'string',
                'exists:roles,idRole',
            ],

            /*
             * Statut du compte.
             */
            'statut' => [
                'required',
                'boolean',
            ],

            /*
             * Mot de passe facultatif lors d'une modification.
             */
            'password' => [
                'nullable',
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

            'telephone.required' =>
                'Le numéro de téléphone est obligatoire.',

            'telephone.regex' =>
                'Le numéro doit être un numéro togolais valide au format +228 suivi de 8 chiffres.',

            'telephone.unique' =>
                'Ce numéro de téléphone est déjà associé à un autre compte.',

            'email.email' =>
                'Veuillez saisir une adresse e-mail valide.',

            'email.unique' =>
                'Cette adresse e-mail est déjà utilisée.',

            'role_id.required' =>
                'Le rôle est obligatoire.',

            'role_id.exists' =>
                'Le rôle sélectionné n’existe pas.',

            'statut.required' =>
                'Le statut du compte est obligatoire.',

            'statut.boolean' =>
                'Le statut du compte est invalide.',

            'password.min' =>
                'Le mot de passe doit contenir au moins 8 caractères.',

            'password.confirmed' =>
                'La confirmation du mot de passe ne correspond pas.',
        ];
    }
}