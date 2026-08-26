@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- ========================================================= --}}
    {{-- EN-TÊTE --}}
    {{-- ========================================================= --}}

    <div class="mb-8 flex items-center justify-between">

        <div>

            <h1 class="text-2xl font-bold text-slate-800">
                {{ $userSelectionne ? 'Reconduire un utilisateur' : 'Nouvelle affectation' }}
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Créer une nouvelle affectation pour la campagne.
            </p>

        </div>

        <a
            href="{{ route('users.index') }}"
            class="px-4 py-2 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200"
        >
            Retour
        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- ERREURS --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200">

            <p class="font-semibold text-red-700">
                Vérifiez les informations saisies.
            </p>

            <ul class="mt-2 list-disc list-inside text-sm text-red-600">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- CAMPAGNE --}}
    {{-- ========================================================= --}}

    @if($campagne)

        <div class="mb-6 rounded-xl border border-blue-200 bg-blue-50 p-4">

            <p class="text-xs font-semibold uppercase text-blue-600">
                Campagne
            </p>

            <p class="mt-1 font-semibold text-slate-800">
                {{ $campagne->libelle }}
            </p>

            <p class="text-sm text-slate-500">
                {{ $campagne->codeCampagne }}
            </p>

        </div>

    @else

        <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 p-4">

            <p class="font-semibold text-amber-800">
                Aucune campagne planifiée
            </p>

            <p class="mt-1 text-sm text-amber-700">
                Vous devez créer une campagne avant de pouvoir effectuer une affectation.
            </p>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- FORMULAIRE --}}
    {{-- ========================================================= --}}

    <form
        method="POST"
        action="{{ route('affectations.store') }}"
        class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6"
    >

        @csrf


        {{-- ===================================================== --}}
        {{-- UTILISATEUR --}}
        {{-- ===================================================== --}}

        <div class="mb-6">

            <label class="block text-sm font-semibold text-slate-700 mb-2">
                Utilisateur
            </label>

            @if($userSelectionne)

                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">

                    <div class="font-semibold text-slate-800">
                        {{ $userSelectionne->name }}
                    </div>

                    <div class="text-sm text-slate-500">
                        {{ $userSelectionne->login }}
                    </div>

                    <div class="mt-1 text-xs font-medium text-blue-600">
                        {{ $userSelectionne->role->nom }}
                    </div>

                </div>

                <input
                    type="hidden"
                    name="user_id"
                    value="{{ $userSelectionne->id }}"
                >

            @else

                <select
                    name="user_id"
                    id="user_id"
                    class="w-full rounded-xl border-gray-300"
                    required
                >

                    <option value="">
                        Sélectionner un utilisateur
                    </option>

                    @foreach($users as $user)

                        <option
                            value="{{ $user->id }}"
                            data-role="{{ $user->role->nom }}"
                            @selected(old('user_id') == $user->id)
                        >
                            {{ $user->name }}
                            — {{ $user->role->nom }}
                        </option>

                    @endforeach

                </select>

            @endif

        </div>


        {{-- ===================================================== --}}
        {{-- ZONE --}}
        {{-- ===================================================== --}}

        @php
            $role = $userSelectionne?->role?->nom;
        @endphp


        {{-- AGENT RECENSEUR --}}

        <div
            id="zone-village"
            class="mb-6 {{ $role === 'Agent recenseur' ? '' : 'hidden' }}"
        >

            <label class="block text-sm font-semibold text-slate-700 mb-2">
                Village
            </label>

            <select
                name="village_id"
                id="village_id"
                class="w-full rounded-xl border-gray-300"
            >

                <option value="">
                    Sélectionner un village
                </option>

                @foreach($villages as $village)

                    <option
                        value="{{ $village->idVillage }}"
                        @selected(old('village_id') == $village->idVillage)
                    >
                        {{ $village->nom }}
                        —
                        {{ $village->canton->nom ?? '' }}
                    </option>

                @endforeach

            </select>

        </div>


        {{-- SUPERVISEUR / TECHNICIEN --}}

        <div
            id="zone-prefecture"
            class="mb-6 {{ in_array($role, ['Superviseur', 'Technicien']) ? '' : 'hidden' }}"
        >

            <label class="block text-sm font-semibold text-slate-700 mb-2">
                Préfecture
            </label>

            <select
                name="prefecture_id"
                id="prefecture_id"
                class="w-full rounded-xl border-gray-300"
            >

                <option value="">
                    Sélectionner une préfecture
                </option>

                @foreach($prefectures as $prefecture)

                    <option
                        value="{{ $prefecture->idPrefecture }}"
                        @selected(old('prefecture_id') == $prefecture->idPrefecture)
                    >
                        {{ $prefecture->nom }}
                    </option>

                @endforeach

            </select>

        </div>


        {{-- ===================================================== --}}
        {{-- DATES --}}
        {{-- ===================================================== --}}

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">

            <div>

                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Date de début
                </label>

                <input
                    type="date"
                    name="dateDebut"
                    value="{{ old('dateDebut', now()->format('Y-m-d')) }}"
                    class="w-full rounded-xl border-gray-300"
                >

            </div>

            <div>

                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Date de fin
                </label>

                <input
                    type="date"
                    name="dateFin"
                    value="{{ old('dateFin') }}"
                    class="w-full rounded-xl border-gray-300"
                >

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- BOUTONS --}}
        {{-- ===================================================== --}}

        <div class="flex justify-end gap-3">

            <a
                href="{{ route('users.index') }}"
                class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200"
            >
                Annuler
            </a>

            <button
                type="submit"
                @disabled(!$campagne)
                class="px-6 py-2.5 rounded-xl bg-blue-600 text-white
                       hover:bg-blue-700 disabled:opacity-50"
            >
                {{ $userSelectionne ? 'Reconduire' : 'Affecter' }}
            </button>

        </div>

    </form>

</div>


{{-- ============================================================= --}}
{{-- JAVASCRIPT : CHANGEMENT DE RÔLE --}}
{{-- ============================================================= --}}

@if(!$userSelectionne)

<script>

document.addEventListener('DOMContentLoaded', function () {

    const userSelect = document.getElementById('user_id');

    const zoneVillage = document.getElementById('zone-village');

    const zonePrefecture = document.getElementById('zone-prefecture');

    function afficherZone() {

        const option =
            userSelect.options[userSelect.selectedIndex];

        const role =
            option?.dataset.role;

        zoneVillage.classList.add('hidden');

        zonePrefecture.classList.add('hidden');


        if (role === 'Agent recenseur') {

            zoneVillage.classList.remove('hidden');

        }

        if (
            role === 'Superviseur' ||
            role === 'Technicien'
        ) {

            zonePrefecture.classList.remove('hidden');

        }

    }

    userSelect.addEventListener(
        'change',
        afficherZone
    );

});

</script>

@endif

@endsection