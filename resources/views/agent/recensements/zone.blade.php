@extends('layouts.app')

@section('title', 'Maisons du village')

@section('content')

<div class="space-y-6">

    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>

            <div class="flex items-center gap-2">

                <span class="inline-flex items-center rounded-full bg-[#e5f2ee] px-3 py-1 text-xs font-semibold text-[#006a4f]">
                    SIRA-Mô
                </span>

                <span class="text-xs font-medium text-gray-400">
                    Collecte
                </span>

            </div>

            <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900">
                Maisons du village
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Sélectionnez une maison pour poursuivre ou commencer son recensement.
            </p>

        </div>

        {{-- Retour aux zones --}}
        <div>

            <a
                href="{{ route('agent.recensements.index') }}"
                class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:border-[#006a4f] hover:bg-[#e5f2ee] hover:text-[#006a4f]"
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M10 19l-7-7 7-7M3 12h18"
                    />
                </svg>

                Mes recensements

            </a>

        </div>

    </div>


    {{-- =========================================================
         CONTEXTE DE L'AFFECTATION
    ========================================================== --}}
    @if($affectation)

        <div class="rounded-2xl border border-[#cfe7df] bg-[#e5f2ee] p-5">

            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                <div>

                    <div class="flex items-center gap-3">

                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-[#006a4f]">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z"
                                />
                            </svg>

                        </span>

                        <div>

                            <h2 class="font-semibold text-[#006a4f]">
                                Zone de collecte
                            </h2>

                            <p class="text-sm text-gray-600">
                                {{ $village->nomVillage }}
                            </p>

                        </div>

                    </div>

                    <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-3">

                        <div>

                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                Campagne
                            </p>

                            <p class="mt-1 font-semibold text-gray-800">
                                {{ $affectation->campagne->libelle ?? '—' }}
                            </p>

                        </div>

                        <div>

                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                Équipe
                            </p>

                            <p class="mt-1 font-semibold text-gray-800">
                                {{ $affectation->equipe->nomEquipe ?? '—' }}
                            </p>

                        </div>

                        <div>

                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                Village
                            </p>

                            <p class="mt-1 font-semibold text-gray-800">
                                {{ $village->nomVillage }}
                            </p>

                        </div>

                    </div>

                </div>


                <div class="shrink-0">

                    <span class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1.5 text-xs font-semibold text-[#006a4f]">

                        <span class="h-2 w-2 rounded-full bg-[#006a4f]"></span>

                        Affectation active

                    </span>

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
         INFORMATIONS DU VILLAGE
    ========================================================== --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

        {{-- Village --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        Village
                    </p>

                    <p class="mt-1 text-lg font-bold text-gray-900">
                        {{ $village->nomVillage }}
                    </p>

                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#e5f2ee] text-[#006a4f]">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 21s8-4.35 8-10a8 8 0 10-16 0c0 5.65 8 10 8 10z"
                        />

                        <circle
                            cx="12"
                            cy="11"
                            r="2.5"
                        />

                    </svg>

                </div>

            </div>

        </div>


        {{-- Canton --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        Canton
                    </p>

                    <p class="mt-1 text-lg font-bold text-gray-900">
                        {{ $village->canton->nomCanton ?? '—' }}
                    </p>

                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#e5f2ee] text-[#006a4f]">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-5h6v5M8 10h.01M12 10h.01M16 10h.01"
                        />

                    </svg>

                </div>

            </div>

        </div>


        {{-- Nombre de maisons --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        Maisons
                    </p>

                    <p class="mt-1 text-2xl font-bold text-gray-900">
                        {{ $maisons->total() }}
                    </p>

                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#e5f2ee] text-[#006a4f]">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-5h6v5"
                        />

                    </svg>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         PROGRESSION DE LA COLLECTE
    ========================================================== --}}
    @if($affectation)

        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <h2 class="text-base font-semibold text-gray-900">
                        Progression de la collecte
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        État des recensements des maisons de ce village pour cette campagne.
                    </p>

                </div>

                <div class="text-left sm:text-right">

                    <p class="text-2xl font-bold text-[#006a4f]">
                        {{ $pourcentage ?? 0 }}%
                    </p>

                    <p class="text-xs text-gray-500">
                        {{ $recensees ?? 0 }} / {{ $total ?? $maisons->total() }} maisons
                    </p>

                </div>

            </div>


            <div class="mt-4 h-2 overflow-hidden rounded-full bg-gray-100">

                <div
                    class="h-full rounded-full bg-[#006a4f] transition-all"
                    style="width: {{ min(100, max(0, $pourcentage ?? 0)) }}%"
                ></div>

            </div>


            <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">

                <div class="rounded-xl bg-gray-50 p-3">

                    <p class="text-xs text-gray-500">
                        Total
                    </p>

                    <p class="mt-1 text-lg font-bold text-gray-900">
                        {{ $total ?? $maisons->total() }}
                    </p>

                </div>


                <div class="rounded-xl bg-emerald-50 p-3">

                    <p class="text-xs text-gray-500">
                        Recensées
                    </p>

                    <p class="mt-1 text-lg font-bold text-emerald-700">
                        {{ $recensees ?? 0 }}
                    </p>

                </div>


                <div class="rounded-xl bg-amber-50 p-3">

                    <p class="text-xs text-gray-500">
                        En cours
                    </p>

                    <p class="mt-1 text-lg font-bold text-amber-600">
                        {{ $enCours ?? 0 }}
                    </p>

                </div>


                <div class="rounded-xl bg-slate-50 p-3">

                    <p class="text-xs text-gray-500">
                        À recenser
                    </p>

                    <p class="mt-1 text-lg font-bold text-slate-700">
                        {{ $aRecenser ?? 0 }}
                    </p>

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
         LISTE DES MAISONS
    ========================================================== --}}
    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

        <div class="border-b border-gray-100 px-5 py-4">

            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <h2 class="text-base font-semibold text-gray-900">
                        Maisons à recenser
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Chaque maison est permanente. Le recensement est créé pour la campagne courante.
                    </p>

                </div>

                <span class="text-sm text-gray-500">
                    {{ $maisons->total() }}
                    maison{{ $maisons->total() > 1 ? 's' : '' }}
                </span>

            </div>

        </div>


        @if($maisons->count())

            {{-- =================================================
                 VERSION DESKTOP
            ================================================== --}}
            <div class="hidden overflow-x-auto md:block">

                <table class="min-w-full divide-y divide-gray-100">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                N°
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Maison
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Adresse
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Localisation
                            </th>

                            <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Ménages
                            </th>

                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Collecte
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100 bg-white">

                        @foreach($maisons as $maison)

                            @php
                                /*
                                 * Le contrôleur charge uniquement
                                 * le recensement de la campagne courante.
                                 */
                                $recensement = $maison->recensements->first();

                                $nombreMenages = $recensement
                                    ? ($recensement->menages_count ?? 0)
                                    : 0;
                            @endphp

                            <tr class="transition hover:bg-gray-50">

                                {{-- Numéro --}}
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-500">
                                    {{ $maisons->firstItem() + $loop->index }}
                                </td>


                                {{-- Maison --}}
                                <td class="whitespace-nowrap px-5 py-4">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#e5f2ee] text-[#006a4f]">

                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-5h6v5"
                                                />
                                            </svg>

                                        </div>

                                        <div>

                                            <p class="font-semibold text-gray-900">
                                                {{ $maison->numeroMaison }}
                                            </p>

                                            <p class="text-xs text-gray-400">
                                                Maison permanente
                                            </p>

                                        </div>

                                    </div>

                                </td>


                                {{-- Adresse --}}
                                <td class="px-5 py-4">

                                    <span class="text-sm text-gray-600">
                                        {{ $maison->adresse ?: 'Non renseignée' }}
                                    </span>

                                </td>


                                {{-- Localisation --}}
                                <td class="whitespace-nowrap px-5 py-4">

                                    @if($maison->latitude !== null && $maison->longitude !== null)

                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-[#e5f2ee] px-2.5 py-1 text-xs font-medium text-[#006a4f]">

                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-3.5 w-3.5"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                                stroke-width="2"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M12 21s8-4.35 8-10a8 8 0 10-16 0c0 5.65 8 10 8 10z"
                                                />
                                            </svg>

                                            Localisée

                                        </span>

                                    @else

                                        <span class="text-xs text-gray-400">
                                            Non localisée
                                        </span>

                                    @endif

                                </td>


                                {{-- Ménages --}}
                                <td class="whitespace-nowrap px-5 py-4 text-center">

                                    @if($recensement)

                                        <span class="inline-flex min-w-8 items-center justify-center rounded-full bg-[#e5f2ee] px-2.5 py-1 text-xs font-semibold text-[#006a4f]">
                                            {{ $nombreMenages }}
                                        </span>

                                    @else

                                        <span class="text-sm text-gray-400">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Action de collecte --}}
                                <td class="whitespace-nowrap px-5 py-4">

                                    <div class="flex items-center justify-end">

                                        @if($recensement)

                                            {{-- Recensement déjà créé --}}
                                            <a
                                                href="{{ route(
                                                    'agent.recensements.show',
                                                    $recensement
                                                ) }}"
                                                title="Ouvrir le recensement"
                                                class="inline-flex items-center gap-2 rounded-lg bg-[#006a4f] px-3 py-2 text-xs font-semibold text-white transition hover:bg-[#00563f]"
                                            >

                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    stroke-width="1.8"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a3 3 0 016 0M9 5h6"
                                                    />

                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M9 13h6M9 17h4"
                                                    />

                                                </svg>

                                                Ouvrir

                                            </a>

                                        @else

                                            {{-- Aucun recensement pour cette campagne --}}
                                            <a
                                                href="{{ route(
                                                    'agent.recensements.commencer',
                                                    [
                                                        'affectation' => $affectation->idAffectation,
                                                        'maison' => $maison->idMaison,
                                                    ]
                                                ) }}"
                                                title="Commencer le recensement"
                                                class="inline-flex items-center gap-2 rounded-lg bg-[#006a4f] px-3 py-2 text-xs font-semibold text-white transition hover:bg-[#00563f]"
                                            >

                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    stroke-width="1.8"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M12 6v12M6 12h12"
                                                    />

                                                </svg>

                                                Commencer

                                            </a>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- =================================================
                 VERSION MOBILE
            ================================================== --}}
            <div class="divide-y divide-gray-100 md:hidden">

                @foreach($maisons as $maison)

                    @php
                        $recensement = $maison->recensements->first();

                        $nombreMenages = $recensement
                            ? ($recensement->menages_count ?? 0)
                            : 0;
                    @endphp

                    <div class="p-5">

                        <div class="flex items-start justify-between gap-4">

                            <div class="flex items-center gap-3">

                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#e5f2ee] text-[#006a4f]">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-5h6v5"
                                        />

                                    </svg>

                                </div>

                                <div>

                                    <p class="font-semibold text-gray-900">
                                        {{ $maison->numeroMaison }}
                                    </p>

                                    <p class="text-xs text-gray-400">
                                        Maison permanente
                                    </p>

                                </div>

                            </div>

                            <span class="text-xs text-gray-400">
                                #{{ $maisons->firstItem() + $loop->index }}
                            </span>

                        </div>


                        <div class="mt-4 space-y-2 text-sm">

                            <div class="flex justify-between gap-4">

                                <span class="text-gray-500">
                                    Adresse
                                </span>

                                <span class="text-right font-medium text-gray-700">
                                    {{ $maison->adresse ?: 'Non renseignée' }}
                                </span>

                            </div>


                            <div class="flex justify-between gap-4">

                                <span class="text-gray-500">
                                    Localisation
                                </span>

                                @if($maison->latitude !== null && $maison->longitude !== null)

                                    <span class="font-medium text-[#006a4f]">
                                        Localisée
                                    </span>

                                @else

                                    <span class="text-gray-400">
                                        Non localisée
                                    </span>

                                @endif

                            </div>


                            <div class="flex justify-between gap-4">

                                <span class="text-gray-500">
                                    Ménages
                                </span>

                                @if($recensement)

                                    <span class="font-semibold text-[#006a4f]">
                                        {{ $nombreMenages }}
                                    </span>

                                @else

                                    <span class="text-gray-400">
                                        —
                                    </span>

                                @endif

                            </div>

                        </div>


                        <div class="mt-5 flex items-center justify-end">

                            @if($recensement)

                                <a
                                    href="{{ route(
                                        'agent.recensements.show',
                                        $recensement
                                    ) }}"
                                    class="inline-flex items-center gap-2 rounded-lg bg-[#006a4f] px-3 py-2 text-xs font-semibold text-white transition hover:bg-[#00563f]"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a3 3 0 016 0M9 5h6"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M9 13h6M9 17h4"
                                        />

                                    </svg>

                                    Ouvrir

                                </a>

                            @else

                                <a
                                    href="{{ route(
                                        'agent.recensements.commencer',
                                        [
                                            'affectation' => $affectation->idAffectation,
                                            'maison' => $maison->idMaison,
                                        ]
                                    ) }}"
                                    class="inline-flex items-center gap-2 rounded-lg bg-[#006a4f] px-3 py-2 text-xs font-semibold text-white transition hover:bg-[#00563f]"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 6v12M6 12h12"
                                        />

                                    </svg>

                                    Commencer

                                </a>

                            @endif

                        </div>

                    </div>

                @endforeach

            </div>


        @else

            {{-- =================================================
                 ÉTAT VIDE
            ================================================== --}}
            <div class="px-6 py-16 text-center">

                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#e5f2ee] text-[#006a4f]">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-8 w-8"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-5h6v5"
                        />

                    </svg>

                </div>


                <h3 class="mt-4 text-base font-semibold text-gray-900">
                    Aucune maison dans ce village
                </h3>


                <p class="mx-auto mt-1 max-w-md text-sm text-gray-500">
                    Aucune maison permanente n'est actuellement enregistrée
                    dans ce village.
                </p>

            </div>

        @endif


        {{-- Pagination --}}
        @if($maisons->hasPages())

            <div class="border-t border-gray-100 px-5 py-4">

                {{ $maisons->withQueryString()->links() }}

            </div>

        @endif

    </div>

</div>

@endsection
