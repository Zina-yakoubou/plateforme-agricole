@extends('layouts.app')

@section('page-title', 'Tableau de bord')

@section(
    'page-subtitle',
    $typeDashboard === 'dpa'
        ? 'Pilotage de la campagne dans votre préfecture'
        : 'Vue générale du recensement agricole'
)

@section('content')

<div class="mx-auto max-w-7xl space-y-6">

    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>

            @if($typeDashboard === 'dpa')

                <h1 class="font-poppins text-2xl font-semibold text-[#212529]">
                    Tableau de bord
                </h1>

                <p class="mt-1 font-poppins text-sm text-[#6b7280]">
                    Préfecture de {{ $prefecture->nom ?? 'Non définie' }}
                </p>

            @elseif($typeDashboard === 'admin')

                <h1 class="font-poppins text-2xl font-semibold text-[#212529]">
                    Tableau de bord
                </h1>

                <p class="mt-1 font-poppins text-sm text-[#6b7280]">
                    Vue globale de la campagne de recensement
                </p>

            @else

                <h1 class="font-poppins text-2xl font-semibold text-[#212529]">
                    Tableau de bord
                </h1>

            @endif

        </div>


        {{-- =====================================================
             CAMPAGNE ACTUELLE
        ====================================================== --}}

        @if($campagne)

            <div class="rounded-xl border border-[#e5e7eb] bg-white px-5 py-3">

                <p class="font-poppins text-xs font-medium uppercase tracking-wide text-[#6b7280]">
                    Campagne actuelle
                </p>

                <div class="mt-1 flex items-center gap-3">

                    <span class="font-poppins text-sm font-semibold text-[#212529]">
                        {{ $campagne->libelle }}
                    </span>

                    @if($campagne->statut === 'active')

                        <span class="rounded-full bg-[#e5f2ee] px-2.5 py-1 font-poppins text-xs font-semibold text-[#006a4f]">
                            Active
                        </span>

                    @elseif($campagne->statut === 'planifiee')

                        <span class="rounded-full bg-amber-50 px-2.5 py-1 font-poppins text-xs font-semibold text-amber-700">
                            Planifiée
                        </span>

                    @endif

                </div>

            </div>

        @endif

    </div>


    {{-- =========================================================
         MESSAGE SI AUCUNE CAMPAGNE
    ========================================================== --}}

    @if(!$campagne)

        <div class="rounded-xl border border-amber-200 bg-amber-50 p-5">

            <div class="flex items-start gap-3">

                <svg
                    class="mt-0.5 h-5 w-5 shrink-0 text-amber-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 9v3m0 4h.01M10.29 3.86l-8.2 14A2 2 0 003.82 21h16.36a2 2 0 001.73-3.14l-8.2-14a2 2 0 00-3.42 0z"
                    />
                </svg>

                <div>

                    <p class="font-poppins text-sm font-semibold text-amber-800">
                        Aucune campagne en cours
                    </p>

                    <p class="mt-1 font-poppins text-sm text-amber-700">
                        Aucune campagne active ou planifiée n'est actuellement disponible.
                    </p>

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
         DASHBOARD DPA
    ========================================================== --}}

    @if($typeDashboard === 'dpa')

        {{-- =====================================================
             INDICATEURS
        ====================================================== --}}

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            {{-- Équipes --}}

            <div class="rounded-xl border border-[#e5e7eb] bg-white p-5">

                <div class="flex items-center justify-between">

                    <div>
                        <p class="font-poppins text-sm text-[#6b7280]">
                            Équipes
                        </p>

                        <p class="mt-2 font-poppins text-3xl font-semibold text-[#212529]">
                            {{ $nombreEquipes }}
                        </p>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-[#e5f2ee] text-[#006a4f]">

                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M17 20h5v-2a4 4 0 00-4-4h-1
                                   M9 20H4v-2a4 4 0 014-4h1
                                   M12 14a4 4 0 100-8 4 4 0 000 8
                                   M16 3.13a4 4 0 010 7.75
                                   M8 3.13a4 4 0 000 7.75"
                            />
                        </svg>

                    </div>

                </div>

            </div>


            {{-- Agents --}}

            <div class="rounded-xl border border-[#e5e7eb] bg-white p-5">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="font-poppins text-sm text-[#6b7280]">
                            Agents recenseurs
                        </p>

                        <p class="mt-2 font-poppins text-3xl font-semibold text-[#212529]">
                            {{ $nombreAgents }}
                        </p>

                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-[#e5f2ee] text-[#006a4f]">

                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2
                                   M9 11a4 4 0 100-8 4 4 0 000 8
                                   M22 21v-2a4 4 0 00-3-3.87
                                   M16 3.13a4 4 0 010 7.75"
                            />

                        </svg>

                    </div>

                </div>

            </div>


            {{-- Superviseurs --}}

            <div class="rounded-xl border border-[#e5e7eb] bg-white p-5">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="font-poppins text-sm text-[#6b7280]">
                            Superviseurs
                        </p>

                        <p class="mt-2 font-poppins text-3xl font-semibold text-[#212529]">
                            {{ $nombreSuperviseurs }}
                        </p>

                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-[#e5f2ee] text-[#006a4f]">

                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M12 14a4 4 0 100-8 4 4 0 000 8
                                   M4 21a8 8 0 0116 0"
                            />

                        </svg>

                    </div>

                </div>

            </div>


            {{-- Villages --}}

            <div class="rounded-xl border border-[#e5e7eb] bg-white p-5">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="font-poppins text-sm text-[#6b7280]">
                            Villages
                        </p>

                        <p class="mt-2 font-poppins text-3xl font-semibold text-[#212529]">
                            {{ $nombreVillages }}
                        </p>

                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-[#e5f2ee] text-[#006a4f]">

                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M12 21s7-5.2 7-12a7 7 0 10-14 0c0 6.8 7 12 7 12z
                                   M12 11a2.5 2.5 0 100-5 2.5 2.5 0 000 5z"
                            />

                        </svg>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             PROGRESSION + AFFECTATIONS
        ====================================================== --}}

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Progression --}}

            <div class="rounded-xl border border-[#e5e7eb] bg-white p-6 lg:col-span-2">

                <div class="flex items-center justify-between">

                    <div>

                        <h2 class="font-poppins text-base font-semibold text-[#212529]">
                            Progression de la campagne
                        </h2>

                        <p class="mt-1 font-poppins text-sm text-[#6b7280]">
                            Couverture des villages par les affectations
                        </p>

                    </div>

                    <span class="font-poppins text-2xl font-semibold text-[#006a4f]">
                        {{ $progression }}%
                    </span>

                </div>


                <div class="mt-6 h-3 overflow-hidden rounded-full bg-[#e5e7eb]">

                    <div
                        class="h-full rounded-full bg-[#006a4f] transition-all duration-500"
                        style="width: {{ $progression }}%"
                    ></div>

                </div>


                <div class="mt-4 grid grid-cols-2 gap-4">

                    <div class="rounded-lg bg-[#f9fafb] p-4">

                        <p class="font-poppins text-xs text-[#6b7280]">
                            Villages affectés
                        </p>

                        <p class="mt-1 font-poppins text-xl font-semibold text-[#212529]">
                            {{ $villagesAffectes }}
                        </p>

                    </div>


                    <div class="rounded-lg bg-[#f9fafb] p-4">

                        <p class="font-poppins text-xs text-[#6b7280]">
                            Villages non affectés
                        </p>

                        <p class="mt-1 font-poppins text-xl font-semibold text-[#212529]">
                            {{ $villagesNonAffectes }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- Affectations --}}

            <div class="rounded-xl border border-[#e5e7eb] bg-white p-6">

                <h2 class="font-poppins text-base font-semibold text-[#212529]">
                    Affectations
                </h2>

                <div class="mt-6 space-y-5">

                    <div class="flex items-center justify-between">

                        <span class="font-poppins text-sm text-[#6b7280]">
                            Total
                        </span>

                        <span class="font-poppins text-lg font-semibold text-[#212529]">
                            {{ $nombreAffectations }}
                        </span>

                    </div>


                    <div class="flex items-center justify-between">

                        <span class="font-poppins text-sm text-[#6b7280]">
                            Actives
                        </span>

                        <span class="font-poppins text-lg font-semibold text-[#006a4f]">
                            {{ $affectationsActives }}
                        </span>

                    </div>


                    <div class="border-t border-[#e5e7eb] pt-5">

                        <a
                            href="{{ route('dpa.affectations.index') }}"
                            class="flex items-center justify-center rounded-lg bg-[#006a4f] px-4 py-2.5 font-poppins text-sm font-medium text-white transition hover:bg-[#005a43]"
                        >
                            Voir les affectations
                        </a>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             PROGRESSION PAR COMMUNE
        ====================================================== --}}

        <div class="rounded-xl border border-[#e5e7eb] bg-white">

            <div class="border-b border-[#e5e7eb] px-6 py-5">

                <h2 class="font-poppins text-base font-semibold text-[#212529]">
                    Progression par commune
                </h2>

                <p class="mt-1 font-poppins text-sm text-[#6b7280]">
                    Suivi territorial de la campagne
                </p>

            </div>


            <div class="overflow-x-auto">

                <table class="min-w-full">

                    <thead class="bg-[#f9fafb]">

                        <tr>

                            <th class="px-6 py-3 text-left font-poppins text-xs font-semibold uppercase tracking-wider text-[#6b7280]">
                                Commune
                            </th>

                            <th class="px-6 py-3 text-left font-poppins text-xs font-semibold uppercase tracking-wider text-[#6b7280]">
                                Villages
                            </th>

                            <th class="px-6 py-3 text-left font-poppins text-xs font-semibold uppercase tracking-wider text-[#6b7280]">
                                Affectés
                            </th>

                            <th class="px-6 py-3 text-left font-poppins text-xs font-semibold uppercase tracking-wider text-[#6b7280]">
                                Progression
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-[#e5e7eb]">

                        @forelse($communes as $commune)

                            <tr class="hover:bg-[#f9fafb]">

                                <td class="px-6 py-4 font-poppins text-sm font-medium text-[#212529]">
                                    {{ $commune['nom'] }}
                                </td>

                                <td class="px-6 py-4 font-poppins text-sm text-[#434343]">
                                    {{ $commune['villages'] }}
                                </td>

                                <td class="px-6 py-4 font-poppins text-sm text-[#434343]">
                                    {{ $commune['villages_affectes'] }}
                                </td>

                                <td class="px-6 py-4">

                                    <div class="flex items-center gap-3">

                                        <div class="h-2 w-28 overflow-hidden rounded-full bg-[#e5e7eb]">

                                            <div
                                                class="h-full rounded-full bg-[#006a4f]"
                                                style="width: {{ $commune['progression'] }}%"
                                            ></div>

                                        </div>

                                        <span class="font-poppins text-sm font-semibold text-[#212529]">
                                            {{ $commune['progression'] }}%
                                        </span>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="4"
                                    class="px-6 py-10 text-center font-poppins text-sm text-[#6b7280]"
                                >
                                    Aucune donnée territoriale disponible.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


    {{-- =========================================================
         DASHBOARD ADMINISTRATEUR
    ========================================================== --}}

    @elseif($typeDashboard === 'admin')

        {{-- =====================================================
             INDICATEURS
        ====================================================== --}}

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            <div class="rounded-xl border border-[#e5e7eb] bg-white p-5">

                <p class="font-poppins text-sm text-[#6b7280]">
                    Campagnes
                </p>

                <p class="mt-2 font-poppins text-3xl font-semibold text-[#212529]">
                    {{ $nombreCampagnes }}
                </p>

            </div>


            <div class="rounded-xl border border-[#e5e7eb] bg-white p-5">

                <p class="font-poppins text-sm text-[#6b7280]">
                    Équipes
                </p>

                <p class="mt-2 font-poppins text-3xl font-semibold text-[#212529]">
                    {{ $nombreEquipes }}
                </p>

            </div>


            <div class="rounded-xl border border-[#e5e7eb] bg-white p-5">

                <p class="font-poppins text-sm text-[#6b7280]">
                    Agents recenseurs
                </p>

                <p class="mt-2 font-poppins text-3xl font-semibold text-[#212529]">
                    {{ $nombreAgents }}
                </p>

            </div>


            <div class="rounded-xl border border-[#e5e7eb] bg-white p-5">

                <p class="font-poppins text-sm text-[#6b7280]">
                    Villages
                </p>

                <p class="mt-2 font-poppins text-3xl font-semibold text-[#212529]">
                    {{ $nombreVillages }}
                </p>

            </div>

        </div>


        {{-- =====================================================
             CAMPAGNE
        ====================================================== --}}

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            <div class="rounded-xl border border-[#e5e7eb] bg-white p-6 lg:col-span-2">

                <div class="flex items-center justify-between">

                    <div>

                        <h2 class="font-poppins text-base font-semibold text-[#212529]">
                            Campagne actuelle
                        </h2>

                        @if($campagne)

                            <p class="mt-1 font-poppins text-sm text-[#6b7280]">
                                {{ $campagne->libelle }}
                            </p>

                        @else

                            <p class="mt-1 font-poppins text-sm text-[#6b7280]">
                                Aucune campagne active
                            </p>

                        @endif

                    </div>


                    @if($campagne)

                        <span class="rounded-full bg-[#e5f2ee] px-3 py-1 font-poppins text-xs font-semibold text-[#006a4f]">
                            {{ ucfirst($campagne->statut) }}
                        </span>

                    @endif

                </div>


                <div class="mt-6 h-3 overflow-hidden rounded-full bg-[#e5e7eb]">

                    <div
                        class="h-full rounded-full bg-[#006a4f]"
                        style="width: {{ $progression }}%"
                    ></div>

                </div>


                <div class="mt-3 flex items-center justify-between">

                    <span class="font-poppins text-sm text-[#6b7280]">
                        Couverture des villages
                    </span>

                    <span class="font-poppins text-sm font-semibold text-[#006a4f]">
                        {{ $progression }}%
                    </span>

                </div>

            </div>


            <div class="rounded-xl border border-[#e5e7eb] bg-white p-6">

                <h2 class="font-poppins text-base font-semibold text-[#212529]">
                    Affectations
                </h2>

                <div class="mt-5 space-y-4">

                    <div class="flex items-center justify-between">

                        <span class="font-poppins text-sm text-[#6b7280]">
                            Total
                        </span>

                        <span class="font-poppins text-xl font-semibold text-[#212529]">
                            {{ $nombreAffectations }}
                        </span>

                    </div>

                    <div class="flex items-center justify-between">

                        <span class="font-poppins text-sm text-[#6b7280]">
                            Actives
                        </span>

                        <span class="font-poppins text-xl font-semibold text-[#006a4f]">
                            {{ $affectationsActives }}
                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             PRÉFECTURES
        ====================================================== --}}

        <div class="rounded-xl border border-[#e5e7eb] bg-white">

            <div class="border-b border-[#e5e7eb] px-6 py-5">

                <h2 class="font-poppins text-base font-semibold text-[#212529]">
                    Situation par préfecture
                </h2>

                <p class="mt-1 font-poppins text-sm text-[#6b7280]">
                    Vue globale de la campagne actuelle
                </p>

            </div>


            <div class="overflow-x-auto">

                <table class="min-w-full">

                    <thead class="bg-[#f9fafb]">

                        <tr>

                            <th class="px-6 py-3 text-left font-poppins text-xs font-semibold uppercase tracking-wider text-[#6b7280]">
                                Préfecture
                            </th>

                            <th class="px-6 py-3 text-left font-poppins text-xs font-semibold uppercase tracking-wider text-[#6b7280]">
                                Équipes
                            </th>

                            <th class="px-6 py-3 text-left font-poppins text-xs font-semibold uppercase tracking-wider text-[#6b7280]">
                                Villages
                            </th>

                            <th class="px-6 py-3 text-left font-poppins text-xs font-semibold uppercase tracking-wider text-[#6b7280]">
                                Affectés
                            </th>

                            <th class="px-6 py-3 text-left font-poppins text-xs font-semibold uppercase tracking-wider text-[#6b7280]">
                                Progression
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-[#e5e7eb]">

                        @forelse($prefectures as $prefecture)

                            <tr class="hover:bg-[#f9fafb]">

                                <td class="px-6 py-4 font-poppins text-sm font-medium text-[#212529]">
                                    {{ $prefecture['nom'] }}
                                </td>

                                <td class="px-6 py-4 font-poppins text-sm text-[#434343]">
                                    {{ $prefecture['equipes'] }}
                                </td>

                                <td class="px-6 py-4 font-poppins text-sm text-[#434343]">
                                    {{ $prefecture['villages'] }}
                                </td>

                                <td class="px-6 py-4 font-poppins text-sm text-[#434343]">
                                    {{ $prefecture['villages_affectes'] }}
                                </td>

                                <td class="px-6 py-4">

                                    <div class="flex items-center gap-3">

                                        <div class="h-2 w-28 overflow-hidden rounded-full bg-[#e5e7eb]">

                                            <div
                                                class="h-full rounded-full bg-[#006a4f]"
                                                style="width: {{ $prefecture['progression'] }}%"
                                            ></div>

                                        </div>

                                        <span class="font-poppins text-sm font-semibold text-[#212529]">
                                            {{ $prefecture['progression'] }}%
                                        </span>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="px-6 py-10 text-center font-poppins text-sm text-[#6b7280]"
                                >
                                    Aucune donnée disponible.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
        @elseif($typeDashboard === 'agent')

    {{-- =====================================================
         DASHBOARD AGENT RECENSEUR
    ====================================================== --}}

    {{-- =====================================================
         INFORMATIONS DE L'AGENT
    ====================================================== --}}

    <div class="rounded-xl border border-[#e5e7eb] bg-white p-6">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <p class="font-poppins text-sm text-[#6b7280]">
                    Espace agent recenseur
                </p>

                <h2 class="mt-1 font-poppins text-xl font-semibold text-[#212529]">
                    Bonjour, {{ auth()->user()->name }}
                </h2>

                <p class="mt-1 font-poppins text-sm text-[#6b7280]">
                    Suivi de votre mission de recensement sur le terrain.
                </p>

            </div>

            @if($campagne)

                <span class="inline-flex w-fit rounded-full bg-[#e5f2ee] px-3 py-1.5 font-poppins text-xs font-semibold text-[#006a4f]">
                    Campagne active
                </span>

            @endif

        </div>

    </div>


    {{-- =====================================================
         CAMPAGNE ACTUELLE
    ====================================================== --}}

    @if($campagne)

        <div class="rounded-xl border border-[#e5e7eb] bg-white p-6">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

                <div>

                    <p class="font-poppins text-xs font-medium uppercase tracking-wide text-[#6b7280]">
                        Campagne actuelle
                    </p>

                    <h2 class="mt-2 font-poppins text-lg font-semibold text-[#212529]">
                        {{ $campagne->libelle }}
                    </h2>

                    @if($campagne->codeCampagne)

                        <p class="mt-1 font-poppins text-sm text-[#6b7280]">
                            {{ $campagne->codeCampagne }}
                        </p>

                    @endif

                </div>

                @if($campagne->statut === 'active')

                    <span class="rounded-full bg-[#e5f2ee] px-3 py-1 font-poppins text-xs font-semibold text-[#006a4f]">
                        Active
                    </span>

                @elseif($campagne->statut === 'cloturee')

                    <span class="rounded-full bg-gray-100 px-3 py-1 font-poppins text-xs font-semibold text-gray-600">
                        Clôturée
                    </span>

                @endif

            </div>

        </div>

    @else

        <div class="rounded-xl border border-amber-200 bg-amber-50 p-5">

            <div class="flex items-start gap-3">

                <svg
                    class="mt-0.5 h-5 w-5 shrink-0 text-amber-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 9v3m0 4h.01M10.29 3.86l-8.2 14A2 2 0 003.82 21h16.36a2 2 0 001.73-3.14l-8.2-14a2 2 0 00-3.42 0z"
                    />
                </svg>

                <div>

                    <p class="font-poppins text-sm font-semibold text-amber-800">
                        Aucune campagne active
                    </p>

                    <p class="mt-1 font-poppins text-sm text-amber-700">
                        Vous n'avez actuellement aucune campagne active.
                    </p>

                </div>

            </div>

        </div>

    @endif


    {{-- =====================================================
         MON ÉQUIPE
    ====================================================== --}}

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Équipe --}}

        <div class="rounded-xl border border-[#e5e7eb] bg-white p-6 lg:col-span-2">

            <div class="flex items-center justify-between">

                <div>

                    <h2 class="font-poppins text-base font-semibold text-[#212529]">
                        Mon équipe
                    </h2>

                    <p class="mt-1 font-poppins text-sm text-[#6b7280]">
                        Équipe avec laquelle vous intervenez sur le terrain.
                    </p>

                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#e5f2ee] text-[#006a4f]">

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M17 20h5v-2a4 4 0 00-4-4h-1
                               M9 20H4v-2a4 4 0 014-4h1
                               M12 14a4 4 0 100-8 4 4 0 000 8"
                        />
                    </svg>

                </div>

            </div>


            @if($equipe)

                <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2">

                    <div class="rounded-lg bg-[#f9fafb] p-4">

                        <p class="font-poppins text-xs text-[#6b7280]">
                            Équipe
                        </p>

                        <p class="mt-1 font-poppins text-base font-semibold text-[#212529]">
                            {{ $equipe->nom }}
                        </p>

                        @if($equipe->reference)

                            <p class="mt-1 font-poppins text-xs text-[#6b7280]">
                                {{ $equipe->reference }}
                            </p>

                        @endif

                    </div>


                    <div class="rounded-lg bg-[#f9fafb] p-4">

                        <p class="font-poppins text-xs text-[#6b7280]">
                            Superviseur
                        </p>

                        <p class="mt-1 font-poppins text-base font-semibold text-[#212529]">

                            {{ $equipe->superviseur?->name ?? 'Non défini' }}

                        </p>

                    </div>


                    <div class="rounded-lg bg-[#f9fafb] p-4">

                        <p class="font-poppins text-xs text-[#6b7280]">
                            Membres de l'équipe
                        </p>

                        <p class="mt-1 font-poppins text-xl font-semibold text-[#212529]">
                            {{ $equipe->membres->count() }}
                        </p>

                    </div>


                    <div class="rounded-lg bg-[#f9fafb] p-4">

                        <p class="font-poppins text-xs text-[#6b7280]">
                            Statut
                        </p>

                        <p class="mt-1 font-poppins text-sm font-semibold text-[#006a4f]">
                            {{ ucfirst($equipe->statut ?? 'Non défini') }}
                        </p>

                    </div>

                </div>

            @else

                <div class="mt-6 rounded-lg border border-amber-200 bg-amber-50 p-4">

                    <p class="font-poppins text-sm font-semibold text-amber-800">
                        Aucune équipe affectée
                    </p>

                    <p class="mt-1 font-poppins text-sm text-amber-700">
                        Vous n'êtes actuellement membre d'aucune équipe.
                    </p>

                </div>

            @endif

        </div>


        {{-- Statistiques terrain --}}

        <div class="rounded-xl border border-[#e5e7eb] bg-white p-6">

            <h2 class="font-poppins text-base font-semibold text-[#212529]">
                Mon terrain
            </h2>

            <div class="mt-6 space-y-5">

                <div class="flex items-center justify-between">

                    <span class="font-poppins text-sm text-[#6b7280]">
                        Affectations
                    </span>

                    <span class="font-poppins text-xl font-semibold text-[#212529]">
                        {{ $nombreAffectations }}
                    </span>

                </div>


                <div class="flex items-center justify-between">

                    <span class="font-poppins text-sm text-[#6b7280]">
                        Affectations actives
                    </span>

                    <span class="font-poppins text-xl font-semibold text-[#006a4f]">
                        {{ $affectationsActives }}
                    </span>

                </div>


                <div class="flex items-center justify-between">

                    <span class="font-poppins text-sm text-[#6b7280]">
                        Villages
                    </span>

                    <span class="font-poppins text-xl font-semibold text-[#212529]">
                        {{ $nombreVillages }}
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         MES ZONES DE TRAVAIL
    ====================================================== --}}

    <div class="rounded-xl border border-[#e5e7eb] bg-white">

        <div class="border-b border-[#e5e7eb] px-6 py-5">

            <h2 class="font-poppins text-base font-semibold text-[#212529]">
                Mes zones de travail
            </h2>

            <p class="mt-1 font-poppins text-sm text-[#6b7280]">
                Villages affectés à votre équipe pour la campagne actuelle.
            </p>

        </div>


        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="bg-[#f9fafb]">

                    <tr>

                        <th class="px-6 py-3 text-left font-poppins text-xs font-semibold uppercase tracking-wider text-[#6b7280]">
                            Village
                        </th>

                        <th class="px-6 py-3 text-left font-poppins text-xs font-semibold uppercase tracking-wider text-[#6b7280]">
                            Canton
                        </th>

                        <th class="px-6 py-3 text-left font-poppins text-xs font-semibold uppercase tracking-wider text-[#6b7280]">
                            Commune
                        </th>

                        <th class="px-6 py-3 text-left font-poppins text-xs font-semibold uppercase tracking-wider text-[#6b7280]">
                            Statut
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-[#e5e7eb]">

                    @forelse($mesAffectations as $affectation)

                        <tr class="hover:bg-[#f9fafb]">

                            <td class="px-6 py-4">

                                <p class="font-poppins text-sm font-medium text-[#212529]">
                                    {{ $affectation->village?->nom ?? 'Village non défini' }}
                                </p>

                                @if($affectation->village?->code)

                                    <p class="mt-1 font-poppins text-xs text-[#6b7280]">
                                        {{ $affectation->village->code }}
                                    </p>

                                @endif

                            </td>


                            <td class="px-6 py-4 font-poppins text-sm text-[#434343]">

                                {{ $affectation->village?->canton?->nom ?? '—' }}

                            </td>


                            <td class="px-6 py-4 font-poppins text-sm text-[#434343]">

                                {{ $affectation->village?->canton?->commune?->nom ?? '—' }}

                            </td>


                            <td class="px-6 py-4">

                                @if($affectation->statut === 'active')

                                    <span class="rounded-full bg-[#e5f2ee] px-2.5 py-1 font-poppins text-xs font-semibold text-[#006a4f]">
                                        Active
                                    </span>

                                @elseif($affectation->statut === 'terminee')

                                    <span class="rounded-full bg-gray-100 px-2.5 py-1 font-poppins text-xs font-semibold text-gray-600">
                                        Terminée
                                    </span>

                                @elseif($affectation->statut === 'annulee')

                                    <span class="rounded-full bg-red-50 px-2.5 py-1 font-poppins text-xs font-semibold text-red-600">
                                        Annulée
                                    </span>

                                @else

                                    <span class="rounded-full bg-gray-100 px-2.5 py-1 font-poppins text-xs font-semibold text-gray-600">
                                        {{ ucfirst($affectation->statut ?? 'Non défini') }}
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="4"
                                class="px-6 py-10 text-center font-poppins text-sm text-[#6b7280]"
                            >
                                Aucune zone de travail ne vous est actuellement affectée.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- =====================================================
         HISTORIQUE DES CAMPAGNES
    ====================================================== --}}

    <div class="rounded-xl border border-[#e5e7eb] bg-white">

        <div class="border-b border-[#e5e7eb] px-6 py-5">

            <h2 class="font-poppins text-base font-semibold text-[#212529]">
                Mes campagnes
            </h2>

            <p class="mt-1 font-poppins text-sm text-[#6b7280]">
                Historique des campagnes auxquelles vous avez participé.
            </p>

        </div>


        <div class="divide-y divide-[#e5e7eb]">

            @forelse($mesCampagnes as $campagneAgent)

                <div class="flex flex-col gap-4 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <p class="font-poppins text-sm font-semibold text-[#212529]">
                            {{ $campagneAgent->libelle }}
                        </p>

                        @if($campagneAgent->codeCampagne)

                            <p class="mt-1 font-poppins text-xs text-[#6b7280]">
                                {{ $campagneAgent->codeCampagne }}
                            </p>

                        @endif

                    </div>


                    <div>

                        @if($campagneAgent->statut === 'active')

                            <span class="rounded-full bg-[#e5f2ee] px-2.5 py-1 font-poppins text-xs font-semibold text-[#006a4f]">
                                Active
                            </span>

                        @elseif($campagneAgent->statut === 'cloturee')

                            <span class="rounded-full bg-gray-100 px-2.5 py-1 font-poppins text-xs font-semibold text-gray-600">
                                Clôturée
                            </span>

                        @elseif($campagneAgent->statut === 'archivee')

                            <span class="rounded-full bg-gray-100 px-2.5 py-1 font-poppins text-xs font-semibold text-gray-500">
                                Archivée
                            </span>

                        @else

                            <span class="rounded-full bg-amber-50 px-2.5 py-1 font-poppins text-xs font-semibold text-amber-700">
                                {{ ucfirst($campagneAgent->statut ?? 'Non défini') }}
                            </span>

                        @endif

                    </div>

                </div>

            @empty

                <div class="px-6 py-10 text-center">

                    <p class="font-poppins text-sm text-[#6b7280]">
                        Aucun historique de campagne disponible.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

    @else

        {{-- =====================================================
             DASHBOARD STANDARD
        ====================================================== --}}

        <div class="rounded-xl border border-[#e5e7eb] bg-white p-8">

            <h2 class="font-poppins text-lg font-semibold text-[#212529]">
                Bienvenue sur SIRA-Mô
            </h2>

            <p class="mt-2 font-poppins text-sm text-[#6b7280]">
                Votre tableau de bord est prêt.
            </p>

        </div>

    @endif

</div>

@endsection