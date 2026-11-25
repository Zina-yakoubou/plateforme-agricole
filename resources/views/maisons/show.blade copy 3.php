@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto pb-12">

    {{-- =========================================================
         EN-TÊTE DE LA PAGE
    ========================================================== --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-semibold tracking-tight text-[#212529]">
                    Maison n° {{ $maison->numeroMaison ?? '1' }}
                </h1>
                <span class="rounded-full bg-[#e5f2ee] px-3 py-1 text-xs font-semibold text-[#006a4f]">
                    SIRA-Mô
                </span>
            </div>
            <p class="mt-1 text-sm text-gray-500">
                Village de {{ $maison->village->nom ?? 'Solide' }} • {{ $maison->adresse ?? 'Aucune adresse' }}
            </p>
        </div>

        <div>
            <a
                href="{{ url()->previous() }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour
            </a>
        </div>
    </div>


    {{-- =========================================================
         FICHE DE L'HABITATION (CARTE PRINCIPALE)
    ========================================================== --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-xs">
        
        <div class="border-b border-gray-200 bg-[#f8faf9] px-6 py-4">
            <h2 class="text-base font-semibold text-[#212529]">
                Fiche de l'habitation
            </h2>
            <p class="text-xs text-gray-500">
                Données d'identification administrative et géolocalisation.
            </p>
        </div>

        <div class="p-6 grid gap-6 sm:grid-cols-3">
            
            <div>
                <span class="block text-xs font-medium uppercase tracking-wider text-gray-400">Numéro de maison</span>
                <span class="mt-1 block text-base font-semibold text-[#212529]">
                    {{ $maison->numeroMaison ?? '1' }}
                </span>
            </div>

            <div>
                <span class="block text-xs font-medium uppercase tracking-wider text-gray-400">Chef de maison / Référent</span>
                <span class="mt-1 block text-base font-semibold text-[#212529]">
                    {{ $maison->chefMaison ?? 'Non renseigné' }}
                </span>
            </div>

            <div>
                <span class="block text-xs font-medium uppercase tracking-wider text-gray-400">Village</span>
                <span class="mt-1 block text-base font-semibold text-[#212529]">
                    {{ $maison->village->nom ?? 'Solide' }}
                </span>
            </div>

            <div>
                <span class="block text-xs font-medium uppercase tracking-wider text-gray-400">Coordonnées GPS</span>
                <span class="mt-1 inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                    <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                    {{ $maison->coordonneesGPS ?? 'Non géolocalisée' }}
                </span>
            </div>

            <div>
                <span class="block text-xs font-medium uppercase tracking-wider text-gray-400">Repère de localisation</span>
                <span class="mt-1 block text-base font-medium text-gray-700">
                    {{ $maison->repere ?? 'Aucun' }}
                </span>
            </div>

            <div>
                <span class="block text-xs font-medium uppercase tracking-wider text-gray-400">Recensements effectués</span>
                <span class="mt-1 block text-base font-semibold text-[#006a4f]">
                    {{ $maison->recensements_count ?? '6' }} passage(s)
                </span>
            </div>

            <div class="sm:col-span-3 pt-2 border-t border-gray-100">
                <span class="block text-xs font-medium uppercase tracking-wider text-gray-400">Adresse complète</span>
                <p class="mt-1 text-sm text-gray-700">
                    {{ $maison->adresseComplete ?? 'Aucune adresse renseignée.' }}
                </p>
            </div>

        </div>
    </div>


    {{-- =========================================================
         LE BOUTON UNIQUE DEPLACÉ EN BAS ET EN VERT
    ========================================================== --}}
    <div class="flex items-center justify-end pt-4 border-t border-gray-200">
        <a
            href="{{ route('agent.recensements.index', ['maison_id' => $maison->id ?? 1]) }}"
            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-[#006a4f] px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#00583f] focus:outline-none focus:ring-2 focus:ring-[#006a4f]/30"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
            </svg>
            Liste des recensements ({{ $maison->recensements_count ?? '6' }})
        </a>
    </div>

</div>
@endsection
@endsection