@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')

<div class="space-y-6">
    {{-- =========================================================
        EN-TÊTE
    ========================================================== --}}
    <div class="mb-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

            <div>
                <p class="text-sm font-medium text-[#006a4f]">
                    SIRA-Mô
                </p>

                <h1 class="mt-1 text-2xl font-bold text-gray-900">
                    Tableau de bord
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    {{ $role }}
                </p>
            </div>

            @if($campagneActive)

                <div class="rounded-xl border border-[#d7ebe4] bg-[#f2faf7] px-5 py-4">

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#006a4f] text-white">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                />
                            </svg>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                Campagne sélectionnée
                            </p>

                            <p class="font-semibold text-gray-900">
                                {{ $campagneActive->libelle }}
                            </p>

                            <p class="text-xs text-gray-500">
                                {{ $campagneActive->annee }}
                                —
                                {{ ucfirst($campagneActive->statut) }}
                            </p>
                        </div>

                    </div>

                </div>

            @else

                <div class="rounded-xl border border-amber-200 bg-amber-50 px-5 py-4">
                    <p class="text-sm font-medium text-amber-800">
                        Aucune campagne disponible.
                    </p>
                </div>

            @endif

        </div>
    </div>


    {{-- =========================================================
        FILTRES
    ========================================================== --}}
    @if($role !== 'Agent recenseur')

        <div class="mb-6">

            <form
                method="GET"
                action="{{ route('dashboard') }}"
                class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm"
            >

                <div class="mb-4">
                    <h2 class="text-base font-semibold text-gray-900">
                        Filtres du tableau de bord
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Les indicateurs affichés sont recalculés selon les critères sélectionnés.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                    {{-- Campagne --}}
                    <div>
                        <label
                            for="campagne_id"
                            class="mb-1.5 block text-sm font-medium text-gray-700"
                        >
                            Campagne
                        </label>

                        <select
                            id="campagne_id"
                            name="campagne_id"
                            class="w-full rounded-lg border-gray-300 text-sm focus:border-[#006a4f] focus:ring-[#006a4f]"
                        >
                            <option value="">
                                Toutes les campagnes
                            </option>

                            @foreach($campagnes as $item)
                                <option
                                    value="{{ $item->idCampagne }}"
                                    @selected($campagneId == $item->idCampagne)
                                >
                                    {{ $item->libelle }}
                                    @if(isset($item->annee))
                                        — {{ $item->annee }}
                                    @endif
                                </option>
                            @endforeach

                        </select>
                    </div>


                    {{-- Canton --}}
                    <div>
                        <label
                            for="canton_id"
                            class="mb-1.5 block text-sm font-medium text-gray-700"
                        >
                            Canton
                        </label>

                        <select
                            id="canton_id"
                            name="canton_id"
                            class="w-full rounded-lg border-gray-300 text-sm focus:border-[#006a4f] focus:ring-[#006a4f]"
                        >

                            <option value="">
                                Tous les cantons
                            </option>

                            @foreach($cantons as $canton)
                                <option
                                    value="{{ $canton->idCanton }}"
                                    @selected($cantonId == $canton->idCanton)
                                >
                                    {{ $canton->nom }}
                                </option>
                            @endforeach

                        </select>
                    </div>


                    {{-- Village --}}
                    <div>
                        <label
                            for="village_id"
                            class="mb-1.5 block text-sm font-medium text-gray-700"
                        >
                            Village
                        </label>

                        <select
                            id="village_id"
                            name="village_id"
                            class="w-full rounded-lg border-gray-300 text-sm focus:border-[#006a4f] focus:ring-[#006a4f]"
                        >

                            <option value="">
                                Tous les villages
                            </option>

                            @foreach($villages as $village)
                                <option
                                    value="{{ $village->idVillage }}"
                                    @selected($villageId == $village->idVillage)
                                >
                                    {{ $village->nom }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                </div>


                <div class="mt-5 flex flex-wrap gap-3">

                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-[#006a4f] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#00543e]"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 4h18M6 8h12M10 12h4M11 16h2M12 20v-4"
                            />
                        </svg>

                        Appliquer les filtres
                    </button>

                    <a
                        href="{{ route('dashboard') }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                    >
                        Réinitialiser
                    </a>

                </div>

            </form>

        </div>

    @endif


    {{-- =========================================================
        CONTENU
    ========================================================== --}}
    <div class="w-full space-y-6 pb-10">


        {{-- =====================================================
            ADMINISTRATEUR
        ====================================================== --}}
        @if($role === 'Administrateur')

            <div>
                <h2 class="text-lg font-bold text-gray-900">
                    Vue générale
                </h2>

                <p class="text-sm text-gray-500">
                    Suivi global de la plateforme et du recensement agricole.
                </p>
            </div>


            {{-- Indicateurs --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                {{-- Utilisateurs --}}
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                    <p class="text-sm text-gray-500">
                        Utilisateurs
                    </p>

                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $utilisateursTotal }}
                    </p>

                    <p class="mt-1 text-xs text-gray-500">
                        {{ $utilisateursActifs }} actifs
                    </p>
                </div>


                {{-- Campagnes --}}
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                    <p class="text-sm text-gray-500">
                        Campagnes
                    </p>

                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $campagnes->count() }}
                    </p>

                    <p class="mt-1 text-xs text-gray-500">
                        campagnes d'identification
                    </p>
                </div>


                {{-- Recensements --}}
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                    <p class="text-sm text-gray-500">
                        Recensements
                    </p>

                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $nombreRecensements }}
                    </p>

                    <p class="mt-1 text-xs text-gray-500">
                        dans le périmètre sélectionné
                    </p>
                </div>


                {{-- Villages --}}
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                    <p class="text-sm text-gray-500">
                        Villages couverts
                    </p>

                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $villagesCouverts }}
                    </p>

                    <p class="mt-1 text-xs text-gray-500">
                        {{ $prefecturesCouvertes }} préfecture(s)
                    </p>
                </div>

            </div>


            {{-- Données de recensement --}}
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">

                    <h3 class="font-semibold text-gray-900">
                        Recensement sélectionné
                    </h3>

                    <div class="mt-5 grid grid-cols-2 gap-4">

                        <div class="rounded-xl bg-gray-50 p-4">
                            <p class="text-xs text-gray-500">
                                Maisons recensées
                            </p>
                            <p class="mt-1 text-2xl font-bold text-gray-900">
                                {{ $maisonsRecensees }}
                            </p>
                        </div>

                        <div class="rounded-xl bg-gray-50 p-4">
                            <p class="text-xs text-gray-500">
                                Ménages
                            </p>
                            <p class="mt-1 text-2xl font-bold text-gray-900">
                                {{ $menages }}
                            </p>
                        </div>

                        <div class="rounded-xl bg-gray-50 p-4">
                            <p class="text-xs text-gray-500">
                                Exploitants
                            </p>
                            <p class="mt-1 text-2xl font-bold text-gray-900">
                                {{ $exploitants }}
                            </p>
                        </div>

                        <div class="rounded-xl bg-gray-50 p-4">
                            <p class="text-xs text-gray-500">
                                Exploitations
                            </p>
                            <p class="mt-1 text-2xl font-bold text-gray-900">
                                {{ $exploitations }}
                            </p>
                        </div>

                        <div class="rounded-xl bg-gray-50 p-4">
                            <p class="text-xs text-gray-500">
                                Parcelles
                            </p>
                            <p class="mt-1 text-2xl font-bold text-gray-900">
                                {{ $parcelles }}
                            </p>
                        </div>

                        <div class="rounded-xl bg-gray-50 p-4">
                            <p class="text-xs text-gray-500">
                                Superficie
                            </p>
                            <p class="mt-1 text-2xl font-bold text-gray-900">
                                {{ number_format($superficieTotale, 2, ',', ' ') }}
                            </p>
                            <p class="text-xs text-gray-500">
                                ha
                            </p>
                        </div>

                    </div>

                </div>


                {{-- Répartition des statuts --}}
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">

                    <h3 class="font-semibold text-gray-900">
                        État des recensements
                    </h3>

                    <div class="mt-5 space-y-4">

                        <div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">
                                    Brouillons
                                </span>
                                <strong>
                                    {{ $dossiersBrouillon }}
                                </strong>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">
                                    En cours
                                </span>
                                <strong>
                                    {{ $dossiersEnCours }}
                                </strong>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">
                                    Terminés
                                </span>
                                <strong>
                                    {{ $dossiersTermines }}
                                </strong>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">
                                    Validés
                                </span>
                                <strong>
                                    {{ $dossiersValides }}
                                </strong>
                            </div>
                        </div>


                        <div class="border-t border-gray-100 pt-4">

                            <div class="flex items-center justify-between">

                                <span class="font-medium text-gray-700">
                                    Progression des dossiers
                                </span>

                                <span class="font-bold text-[#006a4f]">
                                    {{ $progression }} %
                                </span>

                            </div>

                            <div class="mt-2 h-2.5 overflow-hidden rounded-full bg-gray-100">

                                <div
                                    class="h-full rounded-full bg-[#006a4f]"
                                    style="width: {{ min(100, max(0, $progression)) }}%"
                                ></div>

                            </div>

                            <p class="mt-2 text-xs text-gray-500">
                                {{ $dossiersTraites }}
                                dossier(s) traité(s) sur
                                {{ $nombreRecensements }}
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Campagnes --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">

                <h3 class="font-semibold text-gray-900">
                    Recensements par campagne
                </h3>

                <div class="mt-5 overflow-x-auto">

                    <table class="min-w-full text-sm">

                        <thead>
                            <tr class="border-b border-gray-200 text-left text-xs uppercase tracking-wide text-gray-500">
                                <th class="px-3 py-3">
                                    Campagne
                                </th>

                                <th class="px-3 py-3">
                                    Année
                                </th>

                                <th class="px-3 py-3">
                                    Statut
                                </th>

                                <th class="px-3 py-3 text-right">
                                    Recensements
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">

                            @forelse($recensementsParCampagne as $item)

                                <tr>

                                    <td class="px-3 py-3 font-medium text-gray-900">
                                        {{ $item['campagne']->libelle }}
                                    </td>

                                    <td class="px-3 py-3 text-gray-600">
                                        {{ $item['campagne']->annee }}
                                    </td>

                                    <td class="px-3 py-3 text-gray-600">
                                        {{ ucfirst($item['campagne']->statut) }}
                                    </td>

                                    <td class="px-3 py-3 text-right font-semibold text-gray-900">
                                        {{ $item['total'] }}
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td
                                        colspan="4"
                                        class="px-3 py-6 text-center text-gray-500"
                                    >
                                        Aucune campagne disponible.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- Rôles --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">

                <h3 class="font-semibold text-gray-900">
                    Utilisateurs par rôle
                </h3>

                <div class="mt-5 grid grid-cols-2 gap-4 md:grid-cols-4">

                    @foreach($utilisateursParRole as $nomRole => $nombre)

                        <div class="rounded-xl border border-gray-100 p-4">

                            <p class="text-sm text-gray-500">
                                {{ $nomRole }}
                            </p>

                            <p class="mt-1 text-2xl font-bold text-gray-900">
                                {{ $nombre }}
                            </p>

                        </div>

                    @endforeach

                </div>

            </div>

        @endif


        {{-- =====================================================
            DPA
        ====================================================== --}}
        @if($role === 'DPA')

            <div>
                <h2 class="text-lg font-bold text-gray-900">
                    Suivi préfectoral
                </h2>

                <p class="text-sm text-gray-500">
                    État du déploiement et progression du recensement.
                </p>
            </div>


            {{-- Indicateurs --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                    <p class="text-sm text-gray-500">
                        Équipes constituées
                    </p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $equipesConstituees }}
                    </p>
                </div>

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                    <p class="text-sm text-gray-500">
                        Équipes affectées
                    </p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $equipesAffectees }}
                    </p>
                </div>

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                    <p class="text-sm text-gray-500">
                        Villages déployés
                    </p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $villagesDeployes }}
                    </p>
                </div>

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                    <p class="text-sm text-gray-500">
                        Agents concernés
                    </p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $nombreAgents }}
                    </p>
                </div>

            </div>


            {{-- Recensement --}}
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 lg:col-span-2">

                    <h3 class="font-semibold text-gray-900">
                        Progression du recensement
                    </h3>

                    <div class="mt-5">

                        <div class="flex items-end justify-between">

                            <div>
                                <p class="text-4xl font-bold text-[#006a4f]">
                                    {{ $progression }}%
                                </p>

                                <p class="mt-1 text-sm text-gray-500">
                                    dossiers traités
                                </p>
                            </div>

                            <div class="text-right text-sm text-gray-500">
                                <p>
                                    {{ $dossiersTraites }} traités
                                </p>
                                <p>
                                    {{ $nombreRecensements }} total
                                </p>
                            </div>

                        </div>

                        <div class="mt-4 h-3 overflow-hidden rounded-full bg-gray-100">

                            <div
                                class="h-full rounded-full bg-[#006a4f]"
                                style="width: {{ min(100, max(0, $progression)) }}%"
                            ></div>

                        </div>

                        <p class="mt-3 text-xs text-gray-500">
                            Cette progression est calculée à partir des recensements
                            terminés ou validés. Elle ne représente pas un objectif
                            de maisons planifiées lorsqu'aucun objectif de planification
                            n'est enregistré.
                        </p>

                    </div>

                </div>


                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">

                    <h3 class="font-semibold text-gray-900">
                        Villages
                    </h3>

                    <div class="mt-5">

                        <p class="text-3xl font-bold text-gray-900">
                            {{ $villagesDeployes }}
                        </p>

                        <p class="text-sm text-gray-500">
                            villages déployés
                        </p>

                        <div class="mt-5 border-t border-gray-100 pt-5">

                            <p class="text-2xl font-bold text-gray-900">
                                {{ $villagesRestants }}
                            </p>

                            <p class="text-sm text-gray-500">
                                villages restant à déployer
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Données agricoles --}}
            <div class="grid grid-cols-2 gap-4 md:grid-cols-5">

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                    <p class="text-xs text-gray-500">Ménages</p>
                    <p class="mt-2 text-2xl font-bold">{{ $menages }}</p>
                </div>

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                    <p class="text-xs text-gray-500">Exploitants</p>
                    <p class="mt-2 text-2xl font-bold">{{ $exploitants }}</p>
                </div>

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                    <p class="text-xs text-gray-500">Exploitations</p>
                    <p class="mt-2 text-2xl font-bold">{{ $exploitations }}</p>
                </div>

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                    <p class="text-xs text-gray-500">Parcelles</p>
                    <p class="mt-2 text-2xl font-bold">{{ $parcelles }}</p>
                </div>

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                    <p class="text-xs text-gray-500">Superficie</p>
                    <p class="mt-2 text-2xl font-bold">
                        {{ number_format($superficieTotale, 2, ',', ' ') }}
                    </p>
                    <p class="text-xs text-gray-500">ha</p>
                </div>

            </div>


            {{-- Progression villages --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">

                <h3 class="font-semibold text-gray-900">
                    Progression par village
                </h3>

                <div class="mt-5 space-y-5">

                    @forelse($progressionVillages as $item)

                        <div>

                            <div class="flex items-center justify-between gap-4">

                                <div class="min-w-0">
                                    <p class="truncate text-sm font-medium text-gray-900">
                                        {{ $item['village']->nom }}
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        {{ $item['traites'] }}
                                        traité(s) /
                                        {{ $item['total'] }}
                                        recensement(s)
                                    </p>
                                </div>

                                <span class="shrink-0 text-sm font-semibold text-[#006a4f]">
                                    {{ $item['progression'] }}%
                                </span>

                            </div>

                            <div class="mt-2 h-2 overflow-hidden rounded-full bg-gray-100">

                                <div
                                    class="h-full rounded-full bg-[#006a4f]"
                                    style="width: {{ min(100, max(0, $item['progression'])) }}%"
                                ></div>

                            </div>

                        </div>

                    @empty

                        <p class="py-6 text-center text-sm text-gray-500">
                            Aucun village à afficher pour les critères sélectionnés.
                        </p>

                    @endforelse

                </div>

            </div>


            {{-- Équipes --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">

                <h3 class="font-semibold text-gray-900">
                    Suivi des équipes
                </h3>

                <div class="mt-5 space-y-5">

                    @forelse($suiviEquipes as $item)

                        <div>

                            <div class="flex items-center justify-between">

                                <div>
                                    <p class="font-medium text-gray-900">
                                        {{ $item['equipe']->nom
                                            ?? $item['equipe']->reference
                                            ?? 'Équipe' }}
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        {{ $item['traites'] }}
                                        traité(s) /
                                        {{ $item['total'] }}
                                    </p>
                                </div>

                                <span class="font-semibold text-[#006a4f]">
                                    {{ $item['progression'] }}%
                                </span>

                            </div>

                            <div class="mt-2 h-2 overflow-hidden rounded-full bg-gray-100">

                                <div
                                    class="h-full rounded-full bg-[#006a4f]"
                                    style="width: {{ min(100, max(0, $item['progression'])) }}%"
                                ></div>

                            </div>

                        </div>

                    @empty

                        <p class="py-6 text-center text-sm text-gray-500">
                            Aucune équipe à afficher.
                        </p>

                    @endforelse

                </div>

            </div>


            {{-- File de contrôle --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">

                <div class="flex items-center justify-between">

                    <div>
                        <h3 class="font-semibold text-gray-900">
                            File de contrôle
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Recensements terminés en attente de validation.
                        </p>
                    </div>

                    <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                        {{ $fileControle->count() }}
                    </span>

                </div>

                <div class="mt-5 divide-y divide-gray-100">

                    @forelse($fileControle as $recensement)

                        <div class="flex items-center justify-between gap-4 py-4">

                            <div>
                                <p class="font-medium text-gray-900">
                                    Recensement #{{ $recensement->idRecensement }}
                                </p>

                                <p class="text-xs text-gray-500">

                                    @if($recensement->affectation?->village)
                                        {{ $recensement->affectation->village->nom }}
                                    @endif

                                    @if($recensement->agent)
                                        —
                                        {{ $recensement->agent->nom
                                            ?? $recensement->agent->name
                                            ?? 'Agent' }}
                                    @endif

                                </p>
                            </div>

                            <span class="text-xs font-medium text-amber-700">
                                À contrôler
                            </span>

                        </div>

                    @empty

                        <p class="py-6 text-center text-sm text-gray-500">
                            Aucun recensement en attente de contrôle.
                        </p>

                    @endforelse

                </div>

            </div>

        @endif


        {{-- =====================================================
            SUPERVISEUR
        ====================================================== --}}
        @if($role === 'Superviseur')

            <div>
                <h2 class="text-lg font-bold text-gray-900">
                    Suivi des équipes
                </h2>

                <p class="text-sm text-gray-500">
                    Contrôle de la progression des agents et des villages.
                </p>
            </div>


            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                    <p class="text-sm text-gray-500">
                        Équipes supervisées
                    </p>

                    <p class="mt-2 text-3xl font-bold">
                        {{ $equipes->count() }}
                    </p>
                </div>

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                    <p class="text-sm text-gray-500">
                        Agents actifs
                    </p>

                    <p class="mt-2 text-3xl font-bold">
                        {{ $agents }}
                    </p>
                </div>

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                    <p class="text-sm text-gray-500">
                        Villages suivis
                    </p>

                    <p class="mt-2 text-3xl font-bold">
                        {{ $villagesSuivis }}
                    </p>
                </div>

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                    <p class="text-sm text-gray-500">
                        Dossiers traités
                    </p>

                    <p class="mt-2 text-3xl font-bold">
                        {{ $dossiersTraites }}
                    </p>
                </div>

            </div>


            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 lg:col-span-2">

                    <h3 class="font-semibold text-gray-900">
                        Progression globale
                    </h3>

                    <div class="mt-5">

                        <div class="flex items-center justify-between">

                            <span class="text-sm text-gray-600">
                                Recensements traités
                            </span>

                            <span class="font-bold text-[#006a4f]">
                                {{ $progression }}%
                            </span>

                        </div>

                        <div class="mt-2 h-3 overflow-hidden rounded-full bg-gray-100">

                            <div
                                class="h-full rounded-full bg-[#006a4f]"
                                style="width: {{ min(100, max(0, $progression)) }}%"
                            ></div>

                        </div>

                        <p class="mt-2 text-xs text-gray-500">
                            {{ $dossiersTraites }}
                            traités sur
                            {{ $nombreRecensements }}
                            recensements.
                        </p>

                    </div>

                </div>


                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">

                    <h3 class="font-semibold text-gray-900">
                        État des dossiers
                    </h3>

                    <div class="mt-5 space-y-3">

                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">
                                Brouillons
                            </span>
                            <strong>
                                {{ $dossiersBrouillon }}
                            </strong>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">
                                En cours
                            </span>
                            <strong>
                                {{ $dossiersEnCours }}
                            </strong>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">
                                Terminés
                            </span>
                            <strong>
                                {{ $dossiersTermines }}
                            </strong>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">
                                Validés
                            </span>
                            <strong>
                                {{ $dossiersValides }}
                            </strong>
                        </div>

                    </div>

                </div>

            </div>


            {{-- Progression agents --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">

                <h3 class="font-semibold text-gray-900">
                    Progression des agents
                </h3>

                <div class="mt-5 space-y-5">

                    @forelse($progressionAgents as $item)

                        <div>

                            <div class="flex items-center justify-between">

                                <div>

                                    <p class="font-medium text-gray-900">
                                        {{ $item['agent']->nom
                                            ?? $item['agent']->name
                                            ?? 'Agent' }}
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        {{ $item['traites'] }}
                                        traité(s) /
                                        {{ $item['total'] }}
                                    </p>

                                </div>

                                <span class="font-semibold text-[#006a4f]">
                                    {{ $item['progression'] }}%
                                </span>

                            </div>

                            <div class="mt-2 h-2 overflow-hidden rounded-full bg-gray-100">

                                <div
                                    class="h-full rounded-full bg-[#006a4f]"
                                    style="width: {{ min(100, max(0, $item['progression'])) }}%"
                                ></div>

                            </div>

                        </div>

                    @empty

                        <p class="py-6 text-center text-sm text-gray-500">
                            Aucun agent avec des recensements dans ce périmètre.
                        </p>

                    @endforelse

                </div>

            </div>


            {{-- Contrôle --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">

                <h3 class="font-semibold text-gray-900">
                    Recensements à contrôler
                </h3>

                <div class="mt-5 divide-y divide-gray-100">

                    @forelse($fileControle as $recensement)

                        <div class="flex items-center justify-between py-4">

                            <div>

                                <p class="font-medium text-gray-900">
                                    Recensement #{{ $recensement->idRecensement }}
                                </p>

                                <p class="text-xs text-gray-500">

                                    @if($recensement->affectation?->village)
                                        {{ $recensement->affectation->village->nom }}
                                    @endif

                                </p>

                            </div>

                            <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                                Terminé
                            </span>

                        </div>

                    @empty

                        <p class="py-6 text-center text-sm text-gray-500">
                            Aucun dossier à contrôler.
                        </p>

                    @endforelse

                </div>

            </div>


            {{-- Villages --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">

                <h3 class="font-semibold text-gray-900">
                    Progression par village
                </h3>

                <div class="mt-5 space-y-5">

                    @forelse($progressionVillages as $item)

                        <div>

                            <div class="flex justify-between">

                                <div>
                                    <p class="font-medium text-gray-900">
                                        {{ $item['village']->nom }}
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        {{ $item['traites'] }}/{{ $item['total'] }}
                                        traités
                                    </p>
                                </div>

                                <span class="font-semibold text-[#006a4f]">
                                    {{ $item['progression'] }}%
                                </span>

                            </div>

                            <div class="mt-2 h-2 overflow-hidden rounded-full bg-gray-100">

                                <div
                                    class="h-full rounded-full bg-[#006a4f]"
                                    style="width: {{ min(100, max(0, $item['progression'])) }}%"
                                ></div>

                            </div>

                        </div>

                    @empty

                        <p class="py-6 text-center text-sm text-gray-500">
                            Aucun village disponible.
                        </p>

                    @endforelse

                </div>

            </div>

        @endif


        {{-- =====================================================
            AGENT RECENSEUR
        ====================================================== --}}
        @if($role === 'Agent recenseur')

            <div>
                <h2 class="text-lg font-bold text-gray-900">
                    Mon activité de recensement
                </h2>

                <p class="text-sm text-gray-500">
                    Suivi de vos recensements et des dossiers en cours.
                </p>
            </div>


            {{-- Indicateurs --}}
            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">

                    <p class="text-sm text-gray-500">
                        Maisons recensées
                    </p>

                    <p class="mt-2 text-3xl font-bold">
                        {{ $maisonsRecensees }}
                    </p>

                </div>


                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">

                    <p class="text-sm text-gray-500">
                        Dossiers traités
                    </p>

                    <p class="mt-2 text-3xl font-bold">
                        {{ $dossiersTraites }}
                    </p>

                </div>


                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">

                    <p class="text-sm text-gray-500">
                        En cours
                    </p>

                    <p class="mt-2 text-3xl font-bold">
                        {{ $dossiersEnCours }}
                    </p>

                </div>


                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">

                    <p class="text-sm text-gray-500">
                        Brouillons
                    </p>

                    <p class="mt-2 text-3xl font-bold">
                        {{ $dossiersBrouillon }}
                    </p>

                </div>

            </div>


            {{-- Progression --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">

                <div class="flex items-center justify-between">

                    <div>
                        <h3 class="font-semibold text-gray-900">
                            Ma progression
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Recensements terminés ou validés.
                        </p>
                    </div>

                    <span class="text-2xl font-bold text-[#006a4f]">
                        {{ $progression }}%
                    </span>

                </div>

                <div class="mt-5 h-3 overflow-hidden rounded-full bg-gray-100">

                    <div
                        class="h-full rounded-full bg-[#006a4f]"
                        style="width: {{ min(100, max(0, $progression)) }}%"
                    ></div>

                </div>

                <div class="mt-3 flex justify-between text-xs text-gray-500">

                    <span>
                        {{ $dossiersTraites }} traités
                    </span>

                    <span>
                        {{ $nombreRecensements }} total
                    </span>

                </div>

            </div>


            {{-- À faire --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">

                <div class="flex items-center justify-between">

                    <div>
                        <h3 class="font-semibold text-gray-900">
                            Mes dossiers à poursuivre
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Brouillons et recensements actuellement en cours.
                        </p>
                    </div>

                    <span class="rounded-full bg-[#e5f2ee] px-3 py-1 text-xs font-semibold text-[#006a4f]">
                        {{ $aFaire->count() }}
                    </span>

                </div>


                <div class="mt-5 divide-y divide-gray-100">

                    @forelse($aFaire as $recensement)

                        <div class="flex items-center justify-between gap-4 py-4">

                            <div>

                                <p class="font-medium text-gray-900">
                                    Recensement #{{ $recensement->idRecensement }}
                                </p>

                                <p class="mt-1 text-xs text-gray-500">

                                    @if($recensement->affectation?->village)
                                        Village :
                                        {{ $recensement->affectation->village->nom }}
                                    @endif

                                    —
                                    {{ ucfirst($recensement->statut) }}

                                </p>

                            </div>

                            <span
                                class="
                                    rounded-full px-3 py-1 text-xs font-semibold
                                    {{ $recensement->statut === 'en_cours'
                                        ? 'bg-blue-50 text-blue-700'
                                        : 'bg-gray-100 text-gray-700' }}
                                "
                            >
                                {{ ucfirst(str_replace('_', ' ', $recensement->statut)) }}
                            </span>

                        </div>

                    @empty

                        <div class="py-8 text-center">

                            <p class="font-medium text-gray-900">
                                Aucun dossier en attente.
                            </p>

                            <p class="mt-1 text-sm text-gray-500">
                                Tous vos recensements sont actuellement traités.
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>


            {{-- Progression villages --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">

                <h3 class="font-semibold text-gray-900">
                    Progression par village
                </h3>

                <div class="mt-5 space-y-5">

                    @forelse($progressionVillages as $item)

                        <div>

                            <div class="flex items-center justify-between">

                                <div>

                                    <p class="font-medium text-gray-900">
                                        {{ $item['village']->nom }}
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        {{ $item['traites'] }}
                                        traité(s) /
                                        {{ $item['total'] }}
                                    </p>

                                </div>

                                <span class="font-semibold text-[#006a4f]">
                                    {{ $item['progression'] }}%
                                </span>

                            </div>

                            <div class="mt-2 h-2 overflow-hidden rounded-full bg-gray-100">

                                <div
                                    class="h-full rounded-full bg-[#006a4f]"
                                    style="width: {{ min(100, max(0, $item['progression'])) }}%"
                                ></div>

                            </div>

                        </div>

                    @empty

                        <p class="py-6 text-center text-sm text-gray-500">
                            Aucun recensement enregistré pour le moment.
                        </p>

                    @endforelse

                </div>

            </div>


            {{-- Données agricoles --}}
            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                    <p class="text-xs text-gray-500">
                        Ménages
                    </p>
                    <p class="mt-2 text-2xl font-bold">
                        {{ $menages }}
                    </p>
                </div>

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                    <p class="text-xs text-gray-500">
                        Exploitants
                    </p>
                    <p class="mt-2 text-2xl font-bold">
                        {{ $exploitants }}
                    </p>
                </div>

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                    <p class="text-xs text-gray-500">
                        Exploitations
                    </p>
                    <p class="mt-2 text-2xl font-bold">
                        {{ $exploitations }}
                    </p>
                </div>

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                    <p class="text-xs text-gray-500">
                        Parcelles
                    </p>
                    <p class="mt-2 text-2xl font-bold">
                        {{ $parcelles }}
                    </p>
                </div>

            </div>

        @endif


        {{-- =====================================================
            MESSAGE SI CAMPAGNE ABSENTE
        ====================================================== --}}
        @if(!$campagneActive)

            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6">

                <div class="flex gap-4">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 shrink-0 text-amber-600"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 9v3.75m0 3.75h.008v.008H12v-.008z"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"
                        />
                    </svg>

                    <div>

                        <h3 class="font-semibold text-amber-900">
                            Aucune campagne sélectionnée
                        </h3>

                        <p class="mt-1 text-sm text-amber-800">
                            Sélectionnez une campagne pour afficher les données
                            correspondantes.
                        </p>

                    </div>

                </div>

            </div>

        @endif

    </div>

</div>

@endsection