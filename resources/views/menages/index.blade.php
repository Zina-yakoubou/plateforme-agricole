@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}
    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <div class="flex flex-wrap items-center gap-2">

                <h1 class="text-2xl font-semibold tracking-tight text-[#212529]">
                    Ménages
                </h1>

                <span class="rounded-full bg-[#e5f2ee] px-3 py-1
                             text-xs font-semibold text-[#006a4f]">
                    SIRA-Mô
                </span>

            </div>

            <p class="mt-1 text-sm text-[#6b7280]">

                Ménages recensés dans la maison

                <span class="font-semibold text-[#212529]">
                    n°{{ $recensement->maison->numeroMaison }}
                </span>

            </p>

        </div>


        {{-- =====================================================
             AJOUTER UN MÉNAGE
        ====================================================== --}}
        <a
            href="{{ route('agent.recensements.menages.create', $recensement) }}"
            class="inline-flex items-center justify-center gap-2
                   rounded-lg bg-[#006a4f]
                   px-4 py-2.5
                   text-sm font-semibold text-white
                   shadow-sm transition
                   hover:bg-[#156c52]
                   focus:outline-none
                   focus:ring-2
                   focus:ring-[#006a4f]/30"
        >

            <svg
                class="h-4 w-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 4v16m8-8H4"
                />
            </svg>

            Ajouter un ménage

        </a>

    </div>


    {{-- =========================================================
         INFORMATIONS DU RECENSEMENT
    ========================================================== --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

        {{-- MAISON --}}
        <div class="rounded-lg border border-[#e5e7eb]
                    bg-white p-5
                    shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-xs font-medium uppercase
                              tracking-wide text-gray-500">
                        Maison
                    </p>

                    <p class="mt-1 text-lg font-bold text-[#212529]">
                        N°{{ $recensement->maison->numeroMaison }}
                    </p>

                </div>

                <div class="flex h-10 w-10 items-center justify-center
                            rounded-lg bg-[#e5f2ee] text-[#006a4f]">

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
                            d="M3 10.5L12 3l9 7.5M5 9.5V21h14V9.5M9 21v-6h6v6"
                        />
                    </svg>

                </div>

            </div>

        </div>


        {{-- VILLAGE --}}
        <div class="rounded-lg border border-[#e5e7eb]
                    bg-white p-5
                    shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-xs font-medium uppercase
                              tracking-wide text-gray-500">
                        Village
                    </p>

                    <p class="mt-1 text-lg font-bold text-[#212529]">
                        {{ $recensement->maison->village->nomVillage ?? '—' }}
                    </p>

                </div>

                <div class="flex h-10 w-10 items-center justify-center
                            rounded-lg bg-blue-50 text-blue-600">

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
                            d="M12 21s7-6.2 7-11a7 7 0 10-14 0c0 4.8 7 11 7 11z"
                        />

                        <circle
                            cx="12"
                            cy="10"
                            r="2.5"
                        />

                    </svg>

                </div>

            </div>

        </div>


        {{-- STATUT --}}
        <div class="rounded-lg border border-[#e5e7eb]
                    bg-white p-5
                    shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-xs font-medium uppercase
                              tracking-wide text-gray-500">
                        Statut
                    </p>

                    <p class="mt-1 text-lg font-bold text-[#006a4f]">
                        {{ ucfirst(str_replace('_', ' ', $recensement->statut)) }}
                    </p>

                </div>

                <div class="flex h-10 w-10 items-center justify-center
                            rounded-lg bg-[#e5f2ee] text-[#006a4f]">

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
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>

                </div>

            </div>

        </div>


        {{-- TOTAL --}}
        <div class="rounded-lg border border-[#e5e7eb]
                    bg-white p-5
                    shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-xs font-medium uppercase
                              tracking-wide text-gray-500">
                        Total ménages
                    </p>

                    <p class="mt-1 text-lg font-bold text-[#212529]">
                        {{ $menages->total() }}
                    </p>

                </div>

                <div class="flex h-10 w-10 items-center justify-center
                            rounded-lg bg-purple-50 text-purple-600">

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
                            d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2
                               M9 11a4 4 0 100-8 4 4 0 000 8z
                               M22 21v-2a4 4 0 00-3-3.87
                               M16 3.13a4 4 0 010 7.75"
                        />
                    </svg>

                </div>

            </div>

        </div>

    </div>

    {{-- =========================================================
        RECHERCHE
    ========================================================== --}}
    <div class="rounded-lg
                border border-[#e5e7eb]
                bg-white
                p-4
                shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

        <form
            method="GET"
            action="{{ route('agent.recensements.menages.index', $recensement) }}"
        >

            <div class="flex flex-col gap-3 lg:flex-row lg:items-center">

                {{-- CHAMP DE RECHERCHE --}}
                <div class="relative flex-1">

                    <div class="pointer-events-none absolute inset-y-0 left-0
                                flex items-center pl-3 text-gray-400">

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
                                d="m21 21-4.35-4.35
                                M10.5 18a7.5 7.5 0 1 1 0-15
                                7.5 7.5 0 0 1 0 15z"
                            />
                        </svg>

                    </div>

                    <input
                        type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Rechercher par numéro, nom ou prénom du chef..."
                        autocomplete="off"
                        class="w-full rounded-lg
                            border border-[#dfe3e1]
                            bg-white
                            py-2.5 pl-10 pr-4
                            text-sm text-[#212529]
                            placeholder:text-gray-400
                            outline-none
                            transition
                            focus:border-[#006a4f]
                            focus:ring-2
                            focus:ring-[#006a4f]/10"
                    >

                </div>


                {{-- BOUTON RECHERCHER --}}
                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2
                        rounded-lg
                        bg-[#006a4f]
                        px-5 py-2.5
                        text-sm font-semibold text-white
                        shadow-sm
                        transition
                        hover:bg-[#156c52]
                        focus:outline-none
                        focus:ring-2
                        focus:ring-[#006a4f]/30"
                >

                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="m21 21-4.35-4.35
                            M10.5 18a7.5 7.5 0 1 1 0-15
                            7.5 7.5 0 0 1 0 15z"
                        />
                    </svg>

                    Rechercher

                </button>


                {{-- RÉINITIALISER --}}
                @if(!empty($search))

                    <a
                        href="{{ route(
                            'agent.recensements.menages.index',
                            $recensement
                        ) }}"
                        class="inline-flex items-center justify-center gap-2
                            rounded-lg
                            border border-[#e5e7eb]
                            bg-white
                            px-5 py-2.5
                            text-sm font-semibold text-gray-600
                            transition
                            hover:border-gray-300
                            hover:bg-gray-50
                            hover:text-[#212529]"
                    >

                        <svg
                            class="h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M6 6l12 12
                                M18 6L6 18"
                            />
                        </svg>

                        Réinitialiser

                    </a>

                @endif

            </div>


            {{-- INDICATION DE RECHERCHE --}}
            @if(!empty($search))

                <div class="mt-3 flex items-center gap-2 text-xs text-gray-500">

                    <svg
                        class="h-4 w-4 text-[#006a4f]"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M9 12l2 2 4-4
                            M21 12a9 9 0 11-18 0
                            9 9 0 0118 0z"
                        />
                    </svg>

                    <span>
                        Résultats pour :
                        <span class="font-semibold text-[#212529]">
                            "{{ $search }}"
                        </span>

                        —
                        {{ $menages->total() }}
                        résultat(s)
                    </span>

                </div>

            @endif

        </form>

    </div>


    {{-- =========================================================
         MESSAGES
    ========================================================== --}}

    @if(session('success'))

        <div class="flex items-start gap-3 rounded-lg
                    border border-[#b7dfd2]
                    bg-[#e5f2ee]
                    px-4 py-3
                    text-sm text-[#006a4f]">

            <svg
                class="mt-0.5 h-5 w-5 shrink-0"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 13l4 4L19 7"
                />
            </svg>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif


    @if(session('error'))

        <div class="flex items-start gap-3 rounded-lg
                    border border-red-200
                    bg-red-50
                    px-4 py-3
                    text-sm text-red-700">

            <svg
                class="mt-0.5 h-5 w-5 shrink-0"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
            </svg>

            <span>
                {{ session('error') }}
            </span>

        </div>

    @endif


    @if(session('info'))

        <div class="flex items-start gap-3 rounded-lg
                    border border-blue-200
                    bg-blue-50
                    px-4 py-3
                    text-sm text-blue-700">

            <svg
                class="mt-0.5 h-5 w-5 shrink-0"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M12 21a9 9 0 100-18"
                />
            </svg>

            <span>
                {{ session('info') }}
            </span>

        </div>

    @endif


    {{-- =========================================================
         ERREURS DE VALIDATION
    ========================================================== --}}

    @if($errors->any())

        <div class="flex items-start gap-3 rounded-lg
                    border border-red-200
                    bg-red-50
                    px-4 py-3">

            <svg
                class="mt-0.5 h-5 w-5 shrink-0 text-red-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 01-18 0z"
                />
            </svg>

            <div>

                <p class="text-sm font-semibold text-red-700">
                    Vérifiez les informations saisies.
                </p>

                <ul class="mt-1 list-disc space-y-1 pl-5 text-sm text-red-700">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        </div>

    @endif


    {{-- =========================================================
         LISTE DES MÉNAGES
    ========================================================== --}}

    <div class="overflow-hidden rounded-lg
                border border-[#e5e7eb]
                bg-white
                shadow-[0_1px_2px_rgba(0,0,0,0.05)]">


        {{-- =====================================================
             EN-TÊTE DU TABLEAU
        ====================================================== --}}

        <div class="flex flex-col gap-3
                    border-b border-[#e5e7eb]
                    px-6 py-5
                    sm:flex-row
                    sm:items-center
                    sm:justify-between">

            <div>

                <h2 class="text-base font-semibold text-[#212529]">
                    Liste des ménages
                </h2>

                <p class="mt-1 text-xs text-gray-500">
                    Ménages enregistrés dans cette maison
                </p>

            </div>


            <div class="flex items-center gap-3">

                <span class="rounded-full bg-[#f8faf9]
                             px-3 py-1.5
                             text-sm text-gray-500">

                    <span class="font-semibold text-[#006a4f]">
                        {{ $menages->total() }}
                    </span>

                    ménage(s)

                </span>

            </div>

        </div>


        {{-- =====================================================
             TABLEAU
        ====================================================== --}}

        <div class="overflow-x-auto">

            <table class="w-full min-w-[1000px] text-sm">

                <thead class="bg-[#f8faf9]">

                    <tr class="border-b border-[#e5e7eb]">

                        <th class="px-6 py-4 text-left text-xs
                                   font-semibold uppercase tracking-wide
                                   text-gray-500">
                            N°
                        </th>

                        <th class="px-6 py-4 text-left text-xs
                                   font-semibold uppercase tracking-wide
                                   text-gray-500">
                            Chef du ménage
                        </th>

                        <th class="px-6 py-4 text-left text-xs
                                   font-semibold uppercase tracking-wide
                                   text-gray-500">
                            Sexe
                        </th>

                        <th class="px-6 py-4 text-center text-xs
                                   font-semibold uppercase tracking-wide
                                   text-gray-500">
                            Membres
                        </th>

                        <th class="px-6 py-4 text-center text-xs
                                   font-semibold uppercase tracking-wide
                                   text-gray-500">
                            Exploitation
                        </th>

                        <th class="px-6 py-4 text-right text-xs
                                   font-semibold uppercase tracking-wide
                                   text-gray-500">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-[#e5e7eb]">

                    @forelse($menages as $menage)

                        <tr class="group transition hover:bg-[#f8faf9]">


                            {{-- =================================================
                                 N°
                            ================================================== --}}

                            <td class="px-6 py-4 text-sm text-gray-400">

                                {{
                                    $loop->iteration
                                    + (($menages->currentPage() - 1)
                                    * $menages->perPage())
                                }}

                            </td>


                            {{-- =================================================
                                 CHEF DU MÉNAGE
                            ================================================== --}}

                            <td class="px-6 py-4">

                                <div>

                                    <div class="font-semibold text-[#212529]">

                                        {{ $menage->nomChef }}
                                        {{ $menage->prenomChef }}

                                    </div>

                                    <div class="mt-1 text-xs text-gray-400">

                                        Ménage
                                        n°{{ $menage->numeroMenage }}

                                    </div>

                                </div>

                            </td>


                            {{-- =================================================
                                 SEXE
                            ================================================== --}}

                            <td class="px-6 py-4">

                                @if($menage->sexeChef === 'M')

                                    <span class="inline-flex rounded-full
                                                 bg-blue-50
                                                 px-3 py-1
                                                 text-xs font-semibold
                                                 text-blue-700">
                                        Masculin
                                    </span>

                                @elseif($menage->sexeChef === 'F')

                                    <span class="inline-flex rounded-full
                                                 bg-purple-50
                                                 px-3 py-1
                                                 text-xs font-semibold
                                                 text-purple-700">
                                        Féminin
                                    </span>

                                @else

                                    <span class="text-xs text-gray-400">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 MEMBRES
                            ================================================== --}}

                            <td class="px-6 py-4 text-center">

                                @php

                                    $nombreMembres =
                                        ($menage->nombreHommes ?? 0)
                                        + ($menage->nombreFemmes ?? 0)
                                        + ($menage->nombreGarcons ?? 0)
                                        + ($menage->nombreFilles ?? 0);

                                @endphp

                                @if($nombreMembres > 0)

                                    <span class="inline-flex items-center
                                                 rounded-full
                                                 bg-slate-100
                                                 px-3 py-1
                                                 text-xs font-semibold
                                                 text-slate-700">

                                        {{ $nombreMembres }}

                                    </span>

                                @else

                                    <span class="text-xs text-gray-400">
                                        Non renseigné
                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 EXPLOITATION
                            ================================================== --}}

                            <td class="px-6 py-4 text-center">

                                @if($menage->possedeExploitation)

                                    <a
                                        href="{{ route(
                                            'agent.recensements.menages.exploitants.index',
                                            [
                                                'recensement' => $recensement,
                                                'menage' => $menage,
                                            ]
                                        ) }}"
                                        class="inline-flex items-center gap-1.5
                                               rounded-full
                                               bg-[#e5f2ee]
                                               px-3 py-1
                                               text-xs font-semibold
                                               text-[#006a4f]
                                               transition
                                               hover:bg-[#d7eee6]"
                                    >

                                        <span class="h-1.5 w-1.5
                                                     rounded-full
                                                     bg-[#006a4f]">
                                        </span>

                                        Exploitant

                                    </a>


                                    @if(isset($menage->exploitants_count))

                                        <div class="mt-1 text-[10px] text-gray-400">

                                            {{ $menage->exploitants_count }}

                                            {{
                                                $menage->exploitants_count > 1
                                                ? 'exploitants'
                                                : 'exploitant'
                                            }}

                                        </div>

                                    @endif

                                @else

                                    <span class="inline-flex items-center gap-1.5
                                                 rounded-full
                                                 bg-gray-50
                                                 px-3 py-1
                                                 text-xs font-medium
                                                 text-gray-500">

                                        <span class="h-1.5 w-1.5
                                                     rounded-full
                                                     bg-gray-400">
                                        </span>

                                        Aucune

                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 ACTIONS
                            ================================================== --}}

                            <td class="px-6 py-4">

                                <div class="flex items-center justify-end gap-2">


                                    {{-- VOIR / EXPLOITANTS --}}
                                    @if($menage->possedeExploitation)

                                        <a
                                            href="{{ route(
                                                'agent.recensements.menages.exploitants.index',
                                                [
                                                    'recensement' => $recensement,
                                                    'menage' => $menage,
                                                ]
                                            ) }}"
                                            title="Voir les exploitants"
                                            class="inline-flex h-9 w-9
                                                   items-center justify-center
                                                   rounded-lg
                                                   border border-[#e5e7eb]
                                                   bg-white
                                                   text-gray-600
                                                   transition
                                                   hover:border-[#006a4f]
                                                   hover:bg-[#e5f2ee]
                                                   hover:text-[#006a4f]"
                                        >

                                            <svg
                                                class="h-4 w-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="1.8"
                                                    d="M15 19a4 4 0 00-8 0
                                                       M11 11a3 3 0 100-6 3 3 0 000 6
                                                       M19 19a4 4 0 00-3-3.87
                                                       M16 5.13a3 3 0 010 5.74"
                                                />
                                            </svg>

                                        </a>

                                    @endif


                                    {{-- MODIFIER --}}

                                    <a
                                        href="{{ route(
                                            'agent.recensements.menages.edit',
                                            [
                                                'recensement' => $recensement,
                                                'menage' => $menage,
                                            ]
                                        ) }}"
                                        title="Modifier"
                                        class="inline-flex h-9 w-9
                                               items-center justify-center
                                               rounded-lg
                                               border border-[#e5e7eb]
                                               bg-white
                                               text-gray-600
                                               transition
                                               hover:border-blue-500
                                               hover:bg-blue-50
                                               hover:text-blue-600"
                                    >

                                        <svg
                                            class="h-4 w-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                                                   m-1.5-9.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 8.5-8.5z"
                                            />
                                        </svg>

                                    </a>


                                    {{-- SUPPRIMER --}}

                                    <form
                                        action="{{ route(
                                            'agent.recensements.menages.destroy',
                                            [
                                                'recensement' => $recensement,
                                                'menage' => $menage,
                                            ]
                                        ) }}"
                                        method="POST"
                                        onsubmit="return confirm(
                                            'Voulez-vous vraiment supprimer ce ménage ?'
                                        );"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            title="Supprimer"
                                            class="inline-flex h-9 w-9
                                                   items-center justify-center
                                                   rounded-lg
                                                   border border-red-200
                                                   bg-white
                                                   text-red-600
                                                   transition
                                                   hover:border-red-300
                                                   hover:bg-red-50"
                                        >

                                            <svg
                                                class="h-4 w-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="1.8"
                                                    d="M6 7h12
                                                       M10 11v6
                                                       M14 11v6
                                                       M9 7V4h6v3
                                                       M19 7l-1 14H6L5 7"
                                                />
                                            </svg>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>


                    @empty

                        {{-- =================================================
                             AUCUN MÉNAGE
                        ================================================== --}}

                        <tr>

                            <td
                                colspan="6"
                                class="px-6 py-16 text-center"
                            >

                                <div class="flex flex-col items-center">

                                    <div class="mb-4 flex h-14 w-14
                                                items-center justify-center
                                                rounded-full
                                                bg-[#e5f2ee]
                                                text-[#006a4f]">

                                        <svg
                                            class="h-7 w-7"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.7"
                                                d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2
                                                   M9 11a4 4 0 100-8 4 4 0 000 8
                                                   M22 21v-2a4 4 0 00-3-3.87
                                                   M16 3.13a4 4 0 010 7.75"
                                            />
                                        </svg>

                                    </div>

                                    <p class="font-semibold text-[#212529]">
                                        Aucun ménage enregistré
                                    </p>

                                    <p class="mt-1 text-sm text-gray-500">
                                        Commencez par ajouter le premier ménage de cette maison.
                                    </p>

                                    <a
                                        href="{{ route(
                                            'agent.recensements.menages.create',
                                            $recensement
                                        ) }}"
                                        class="mt-4 inline-flex items-center gap-2
                                               rounded-lg
                                               bg-[#006a4f]
                                               px-4 py-2
                                               text-sm font-semibold
                                               text-white
                                               transition
                                               hover:bg-[#156c52]"
                                    >

                                        <svg
                                            class="h-4 w-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 4v16m8-8H4"
                                            />
                                        </svg>

                                        Ajouter un ménage

                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- =====================================================
             PAGINATION
        ====================================================== --}}

        @if($menages->hasPages())

            <div class="border-t border-[#e5e7eb] px-6 py-4">

                {{ $menages->withQueryString()->links() }}

            </div>

        @endif

    </div>

</div>

@endsection