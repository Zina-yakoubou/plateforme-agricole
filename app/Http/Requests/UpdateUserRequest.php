<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    /**
     * Déterminer si l'utilisateur est autorisé.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation.
     */
    public function rules(): array
    {
        return [

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($this->route('user')->id),
            ],

            'telephone' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('users', 'telephone')
                    ->ignore($this->route('user')->id),
            ],

            'login' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'login')
                    ->ignore($this->route('user')->id),
            ],

            'password' => [
                'nullable',
                'confirmed',
                Password::defaults(),
            ],

            'role_id' => [
                'required',
                Rule::exists('roles', 'idRole'),
            ],

            'statut' => [
                'required',
                'boolean',
            ],

        ];
    }
}