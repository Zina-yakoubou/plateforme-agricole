@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}
    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <div class="flex items-center gap-2">

                <h1 class="text-2xl font-semibold tracking-tight text-[#212529]">
                    Équipes de terrain
                </h1>

                <span class="rounded-full bg-[#e5f2ee] px-3 py-1
                             text-xs font-semibold text-[#006a4f]">
                    SIRA-Mô
                </span>

            </div>

            <p class="mt-1 text-sm text-[#6b7280]">
                Gérez les équipes de terrain et leurs membres affectés
                aux opérations de recensement.
            </p>

        </div>


        {{-- =====================================================
             NOUVELLE ÉQUIPE
        ====================================================== --}}
        <a
            href="{{ route('dpa.equipes.create') }}"
            class="inline-flex items-center gap-2
                   rounded-lg
                   bg-[#006a4f]
                   px-4 py-2.5
                   text-sm font-semibold
                   text-white
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
                    stroke-width="2"
                    d="M12 4v16m8-8H4"
                />
            </svg>

            Nouvelle équipe

        </a>

    </div>


    {{-- =========================================================
         MESSAGES
    ========================================================== --}}

    @if(session('success'))

        <div class="flex items-start gap-3
                    rounded-lg
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

            <span>{{ session('success') }}</span>

        </div>

    @endif


    @if(session('error'))

        <div class="flex items-start gap-3
                    rounded-lg
                    border border-red-200
                    bg-red-50
                    px-4 py-3
                    text-sm text-[#ab1717]">

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
                    d="M12 8v4m0 4h.01
                       M21 12a9 9 0 11-18 0
                       9 9 0 0118 0z"
                />
            </svg>

            <span>{{ session('error') }}</span>

        </div>

    @endif


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
            action="{{ route('dpa.equipes.index') }}"
        >

            <div class="flex flex-col gap-3 sm:flex-row">


                {{-- RECHERCHE --}}

                <div class="relative flex-1">

                    <svg
                        class="pointer-events-none
                               absolute left-4 top-1/2
                               h-5 w-5
                               -translate-y-1/2
                               text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="m21 21-4.35-4.35
                               m2.35-5.65a8 8 0 11-16 0
                               8 8 0 0116 0z"
                        />

                    </svg>


                    <input
                        type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Rechercher par équipe, référence ou superviseur..."
                        class="w-full
                               rounded-lg
                               border border-[#e5e7eb]
                               bg-white
                               py-3 pl-11 pr-4
                               text-sm text-[#212529]
                               placeholder:text-gray-400
                               focus:border-[#006a4f]
                               focus:outline-none
                               focus:ring-2
                               focus:ring-[#006a4f]/10"
                    >

                </div>


                {{-- STATUT --}}

                <select
                    name="statut"
                    class="rounded-lg
                           border border-[#e5e7eb]
                           bg-white
                           px-4 py-3
                           text-sm text-[#212529]
                           focus:border-[#006a4f]
                           focus:outline-none
                           focus:ring-2
                           focus:ring-[#006a4f]/10"
                >

                    <option value="">
                        Tous les statuts
                    </option>

                    <option
                        value="ACTIVE"
                        @selected(request('statut') === 'ACTIVE')
                    >
                        Active
                    </option>

                    <option
                        value="INACTIVE"
                        @selected(request('statut') === 'INACTIVE')
                    >
                        Inactive
                    </option>

                </select>


                {{-- RECHERCHER --}}

                <button
                    type="submit"
                    class="inline-flex
                           items-center
                           justify-center
                           gap-2
                           rounded-lg
                           bg-[#006a4f]
                           px-5 py-3
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
                            d="m21 21-4.35-4.35
                               m2.35-5.65a8 8 0 11-16 0
                               8 8 0 0116 0z"
                        />

                    </svg>

                    Rechercher

                </button>


                {{-- RÉINITIALISER --}}

                @if(
                    request()->filled('search')
                    || request()->filled('statut')
                )

                    <a
                        href="{{ route('dpa.equipes.index') }}"
                        class="inline-flex
                               items-center
                               justify-center
                               rounded-lg
                               border border-[#e5e7eb]
                               bg-white
                               px-4 py-3
                               text-sm font-semibold
                               text-gray-600
                               transition
                               hover:bg-gray-50"
                    >
                        Réinitialiser
                    </a>

                @endif

            </div>

        </form>

    </div>


    {{-- =========================================================
         TABLEAU DES ÉQUIPES
    ========================================================== --}}

    <div class="overflow-hidden
                rounded-lg
                border border-[#e5e7eb]
                bg-white
                shadow-[0_1px_2px_rgba(0,0,0,0.05)]">


        {{-- =====================================================
             EN-TÊTE DU TABLEAU
        ====================================================== --}}

        <div class="flex flex-col gap-1
                    border-b border-[#e5e7eb]
                    px-6 py-5
                    sm:flex-row
                    sm:items-center
                    sm:justify-between">

            <div>

                <h2 class="text-base font-semibold text-[#212529]">
                    Équipes de terrain
                </h2>

                <p class="mt-1 text-xs text-gray-500">
                    Équipes constituées pour les opérations de terrain.
                </p>

            </div>


            <div class="text-sm text-gray-500">

                <span class="font-semibold text-[#006a4f]">
                    {{ $equipes->total() }}
                </span>

                équipe(s)

            </div>

        </div>


        {{-- =====================================================
             TABLE
        ====================================================== --}}

        <div class="overflow-x-auto">

            <table class="w-full min-w-[950px] text-sm">


                {{-- =================================================
                     THEAD
                ================================================== --}}

                <thead class="bg-[#f8faf9]">

                    <tr class="border-b border-[#e5e7eb]">


                        <th class="px-6 py-4 text-left
                                   text-xs font-semibold
                                   uppercase tracking-wide
                                   text-gray-500">
                            N°
                        </th>


                        <th class="px-6 py-4 text-left
                                   text-xs font-semibold
                                   uppercase tracking-wide
                                   text-gray-500">
                            Équipe
                        </th>


                        <th class="px-6 py-4 text-left
                                   text-xs font-semibold
                                   uppercase tracking-wide
                                   text-gray-500">
                            Superviseur
                        </th>


                        <th class="px-6 py-4 text-center
                                   text-xs font-semibold
                                   uppercase tracking-wide
                                   text-gray-500">
                            Membres
                        </th>


                        <th class="px-6 py-4 text-center
                                   text-xs font-semibold
                                   uppercase tracking-wide
                                   text-gray-500">
                            Statut
                        </th>


                        <th class="px-6 py-4 text-right
                                   text-xs font-semibold
                                   uppercase tracking-wide
                                   text-gray-500">
                            Actions
                        </th>

                    </tr>

                </thead>


                {{-- =================================================
                     TBODY
                ================================================== --}}

                <tbody class="divide-y divide-[#e5e7eb]">

                    @forelse($equipes as $equipe)

                        <tr class="group transition hover:bg-[#f8faf9]">


                            {{-- =================================================
                                 N°
                            ================================================== --}}

                            <td class="px-6 py-4 text-sm text-gray-400">

                                {{
                                    $loop->iteration
                                    + (
                                        ($equipes->currentPage() - 1)
                                        * $equipes->perPage()
                                    )
                                }}

                            </td>


                            {{-- =================================================
                                 ÉQUIPE
                            ================================================== --}}

                            <td class="px-6 py-4">

                                <div class="min-w-0">

                                    <div class="truncate font-semibold text-[#212529]">

                                        {{ $equipe->nom }}

                                    </div>


                                    <div class="mt-1 font-mono text-xs
                                                font-medium text-[#006a4f]">

                                        {{ $equipe->reference }}

                                    </div>

                                </div>

                            </td>


                            {{-- =================================================
                                 SUPERVISEUR
                            ================================================== --}}

                            <td class="px-6 py-4">

                                @if($equipe->superviseur)

                                    <div class="flex items-center gap-3">


                                        <div class="flex h-9 w-9 shrink-0
                                                    items-center justify-center
                                                    rounded-full
                                                    bg-[#e5f2ee]
                                                    text-sm font-semibold
                                                    text-[#006a4f]">

                                            {{ strtoupper(
                                                substr(
                                                    $equipe->superviseur->name,
                                                    0,
                                                    1
                                                )
                                            ) }}

                                        </div>


                                        <div class="min-w-0">

                                            <div class="truncate font-medium
                                                        text-[#212529]">

                                                {{ $equipe->superviseur->name }}

                                            </div>


                                            @if($equipe->superviseur->telephone)

                                                <div class="mt-0.5 text-xs
                                                            text-gray-500">

                                                    {{ $equipe->superviseur->telephone }}

                                                </div>

                                            @endif

                                        </div>

                                    </div>

                                @else

                                    <span class="text-sm text-gray-400">
                                        Aucun superviseur
                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 MEMBRES
                            ================================================== --}}

                            <td class="px-6 py-4 text-center">

                                @if($equipe->membres_count === 0)

                                    <span class="text-sm text-gray-400">
                                        Aucun membre
                                    </span>

                                @elseif($equipe->membres_count === 1)

                                    <span class="font-medium text-[#434343]">
                                        1 agent
                                    </span>

                                @else

                                    <span class="font-medium text-[#434343]">
                                        {{ $equipe->membres_count }} agents
                                    </span>

                                @endif


                                {{-- VOIR LES MEMBRES --}}

                                @if($equipe->membres_count > 0)

                                    <a
                                        href="{{ route('dpa.equipes.show', $equipe) }}"
                                        class="ml-2 text-xs
                                               font-semibold
                                               text-[#006a4f]
                                               hover:underline"
                                    >
                                        Voir
                                    </a>

                                @endif

                            </td>


                            {{-- =================================================
                                 STATUT
                            ================================================== --}}

                            <td class="px-6 py-4 text-center">

                                @if($equipe->statut === 'ACTIVE')

                                    <span class="inline-flex
                                                 items-center
                                                 gap-1.5
                                                 rounded-full
                                                 bg-green-50
                                                 px-3 py-1
                                                 text-xs font-semibold
                                                 text-green-700">

                                        <span class="h-1.5 w-1.5
                                                     rounded-full
                                                     bg-green-500">
                                        </span>

                                        Active

                                    </span>

                                @else

                                    <span class="inline-flex
                                                 items-center
                                                 gap-1.5
                                                 rounded-full
                                                 bg-red-50
                                                 px-3 py-1
                                                 text-xs font-semibold
                                                 text-red-700">

                                        <span class="h-1.5 w-1.5
                                                     rounded-full
                                                     bg-red-500">
                                        </span>

                                        Inactive

                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 ACTIONS
                            ================================================== --}}

                            <td class="px-6 py-4">

                                <div class="flex
                                            items-center
                                            justify-end
                                            gap-2">


                                    {{-- VOIR --}}

                                    <a
                                        href="{{ route('dpa.equipes.show', $equipe) }}"
                                        title="Voir l'équipe"
                                        class="inline-flex
                                               h-9 w-9
                                               items-center
                                               justify-center
                                               rounded-lg
                                               border
                                               border-[#e5e7eb]
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
                                                stroke-width="2"
                                                d="M2.458 12C3.732 7.943
                                                   7.523 5 12 5
                                                   c4.478 0 8.268 2.943
                                                   9.542 7
                                                   -1.274 4.057-5.064 7
                                                   -9.542 7
                                                   -4.477 0-8.268-2.943
                                                   -9.542-7z"
                                            />

                                            <circle
                                                cx="12"
                                                cy="12"
                                                r="3"
                                            />

                                        </svg>

                                    </a>

                                  {{-- RECONDUIRE --}}
                               @php
                                $aUneAffectation = $equipe->affectations()->exists();
                                $derniereAffectation = $equipe->affectations()->latest('idAffectation')->first();
                            @endphp

                            @if($aUneAffectation)

                                {{-- AFFECTER / RECONDUIRE --}}
                                <a
                                    href="{{ route('dpa.equipes.affectations.create', $equipe) }}"
                                    title="Affecter / Reconduire"
                                    class="inline-flex
                                        h-9 w-9
                                        items-center
                                        justify-center
                                        rounded-lg
                                        border
                                        border-[#e5e7eb]
                                        bg-white
                                        text-[#006a4f]
                                        transition
                                        hover:border-[#006a4f]
                                        hover:bg-[#e5f2ee]"
                                >
                                    <svg
                                        class="h-4 w-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <circle
                                            cx="9"
                                            cy="8"
                                            r="3"
                                            stroke-width="1.8"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.8"
                                            d="M3 20v-1a6 6 0 0112 0v1"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.8"
                                            d="M15 8h6m0 0l-3-3m3 3l-3 3"
                                        />
                                    </svg>
                                </a>

                            @else

                                {{-- PREMIÈRE AFFECTATION --}}
                                <a
                                    href="{{ route('dpa.equipes.affectations.create', $equipe) }}"
                                    title="Affecter l'équipe"
                                    class="inline-flex
                                        h-9 w-9
                                        items-center
                                        justify-center
                                        rounded-lg
                                        border
                                        border-[#e5e7eb]
                                        bg-white
                                        text-[#006a4f]
                                        transition
                                        hover:border-[#006a4f]
                                        hover:bg-[#e5f2ee]"
                                >
                                    <svg
                                        class="h-4 w-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <circle
                                            cx="9"
                                            cy="8"
                                            r="3"
                                            stroke-width="1.8"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.8"
                                            d="M3 20v-1a6 6 0 0112 0v1"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.8"
                                            d="M15 8h6m0 0l-3-3m3 3l-3 3"
                                        />
                                    </svg>
                                </a>

                            @endif


                            {{-- MODIFIER --}}

                                                                    @if($equipe->statut === 'ACTIVE')

                                                                        <a
                                                                            href="{{ route('dpa.equipes.edit', $equipe) }}"
                                                                            title="Modifier"
                                                                            class="inline-flex
                                                                                h-9 w-9
                                                                                items-center
                                                                                justify-center
                                                                                rounded-lg
                                                                                border
                                                                                border-[#e5e7eb]
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

                                                                    @endif


                                                                    {{-- DÉSACTIVER --}}

                                                                    {{-- @if($equipe->statut === 'ACTIVE')

                                                                        <form
                                                                            method="POST"
                                                                            action="{{ route('dpa.equipes.destroy', $equipe) }}"
                                                                            onsubmit="return confirm(
                                                                                'Voulez-vous désactiver cette équipe ?'
                                                                            )"
                                                                        >

                                                                            @csrf

                                                                            @method('DELETE')


                                                                            <button
                                                                                type="submit"
                                                                                title="Désactiver"
                                                                                class="inline-flex
                                                                                    h-9 w-9
                                                                                    items-center
                                                                                    justify-center
                                                                                    rounded-lg
                                                                                    bg-red-50
                                                                                    text-red-600
                                                                                    transition
                                                                                    hover:bg-red-100
                                                                                    focus:outline-none
                                                                                    focus:ring-2
                                                                                    focus:ring-red-500/20"
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
                                                                                        d="M6 6l12 12M6 18L18 6"
                                                                                    />

                                                                                </svg>

                                                                            </button>

                                                                        </form>

                                                                    @endif --}}


                                                                    {{-- =========================================================
                                                                        ACTIVATION / DÉSACTIVATION
                                                                    ========================================================= --}}

                                                                    @if($equipe->statut === 'ACTIVE')

                                                                        {{-- DÉSACTIVER --}}

                                                                        <form
                                                                            method="POST"
                                                                            action="{{ route('dpa.equipes.destroy', $equipe) }}"
                                                                            onsubmit="return confirm(
                                                                                'Voulez-vous désactiver cette équipe ?'
                                                                            )"
                                                                        >

                                                                            @csrf
                                                                            @method('DELETE')

                                                                            <button
                                                                                type="submit"
                                                                                title="Désactiver"
                                                                                class="inline-flex
                                                                                    h-9 w-9
                                                                                    items-center
                                                                                    justify-center
                                                                                    rounded-lg
                                                                                    bg-red-50
                                                                                    text-red-600
                                                                                    transition
                                                                                    hover:bg-red-100
                                                                                    focus:outline-none
                                                                                    focus:ring-2
                                                                                    focus:ring-red-500/20"
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
                                                                                        d="M6 6l12 12M6 18L18 6"
                                                                                    />

                                                                                </svg>

                                                                            </button>

                                                                        </form>

                                                                    @else

                                                                        {{-- RÉACTIVER --}}

                                                                        <form
                                                                            method="POST"
                                                                            action="{{ route('dpa.equipes.reactiver', $equipe) }}"
                                                                            onsubmit="return confirm(
                                                                                'Voulez-vous réactiver cette équipe ?'
                                                                            )"
                                                                        >

                                                                            @csrf
                                                                            @method('PATCH')

                                                                            <button
                                                                                type="submit"
                                                                                title="Réactiver"
                                                                                class="inline-flex
                                                                                    h-9 w-9
                                                                                    items-center
                                                                                    justify-center
                                                                                    rounded-lg
                                                                                    bg-green-50
                                                                                    text-green-600
                                                                                    transition
                                                                                    hover:bg-green-100
                                                                                    focus:outline-none
                                                                                    focus:ring-2
                                                                                    focus:ring-green-500/20"
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
                                                                                        d="M4 12a8 8 0 018-8
                                                                                        8 8 0 018 8
                                                                                        8 8 0 01-8 8
                                                                                        8 8 0 01-8-8z
                                                                                        M8 12l3 3 5-6"
                                                                                    />

                                                                                </svg>

                                                                            </button>

                                                                        </form>

                                                                    @endif

                                                                </div>

                                                            </td>

                                                        </tr>


                                                    @empty

                                                        {{-- =================================================
                                                            AUCUNE ÉQUIPE
                                                        ================================================== --}}

                                                        <tr>

                                                            <td
                                                                colspan="6"
                                                                class="px-6 py-16 text-center"
                                                            >

                                                                <div class="flex flex-col items-center">


                                                                    <div class="mb-4
                                                                                flex h-14 w-14
                                                                                items-center
                                                                                justify-center
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
                                                                                m9-9a4 4 0 100-8 4 4 0 000 8
                                                                                m5-3a3 3 0 100-6
                                                                                m2 18v-2a4 4 0 00-3-3.87"
                                                                            />

                                                                        </svg>

                                                                    </div>


                                                                    <p class="font-semibold text-[#212529]">
                                                                        Aucune équipe trouvée
                                                                    </p>


                                                                    <p class="mt-1 text-sm text-gray-500">
                                                                        Aucune équipe ne correspond à votre recherche.
                                                                    </p>


                                                                    @if(
                                                                        request()->filled('search')
                                                                        || request()->filled('statut')
                                                                    )

                                                                        <a
                                                                            href="{{ route('dpa.equipes.index') }}"
                                                                            class="mt-4 text-sm font-semibold
                                                                                text-[#006a4f]
                                                                                hover:underline"
                                                                        >
                                                                            Réinitialiser les filtres
                                                                        </a>

                                                                    @else

                                                                        <a
                                                                            href="{{ route('dpa.equipes.create') }}"
                                                                            class="mt-4 inline-flex
                                                                                items-center
                                                                                gap-2
                                                                                text-sm font-semibold
                                                                                text-[#006a4f]
                                                                                hover:underline"
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

                                                                            Enregistrer

                                                                        </a>

                                                                    @endif

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

                                        @if($equipes->hasPages())

                                            <div class="border-t border-[#e5e7eb] px-6 py-4">

                                                {{ $equipes->withQueryString()->links() }}

                                            </div>

                                        @endif

                                    </div>

                                </div>

                            

                                                                    
@endsection