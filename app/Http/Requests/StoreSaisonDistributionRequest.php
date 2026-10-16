<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaisonDistributionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Adapte selon tes règles d'authentification/rôles si besoin
    }

    public function rules(): array
    {
        return [
            'libelle' => ['required', 'string', 'max:255'],
            'dateDebut' => ['required', 'date'],
            'dateFin' => ['nullable', 'date', 'after_or_equal:dateDebut'],
            'statut' => ['required', 'in:planifiee,ouverte,cloturee,archivee'],
            'baseSurDerniereAnnee' => ['nullable', 'boolean'],
            'campagneBase_id' => ['nullable', 'exists:campagne_recensements,idCampagne'],
        ];
    }
}