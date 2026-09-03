<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDpaAgentRequest extends FormRequest
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
                Rule::unique('users', 'telephone')
                    ->ignore($this->agent),
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($this->agent),
            ],

            'role_id' => [
                'required',
                'in:'.implode(',', $rolesAutorises),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

        ];
    }

    public function messages(): array
    {
        return [

            'telephone.unique' =>
                'Ce numéro est déjà utilisé.',

            'email.unique' =>
                'Cette adresse email est déjà utilisée.',

            'password.confirmed' =>
                'La confirmation du mot de passe est incorrecte.',

        ];
    }
}