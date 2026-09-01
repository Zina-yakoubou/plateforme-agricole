@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>

            {{-- FIL D'ARIANE --}}
            <div class="mb-2 flex items-center gap-2 text-sm text-gray-500">

                <a
                    href="{{ route('dpa.equipes.index') }}"
                    class="transition hover:text-[#006a4f]"
                >
                    Équipes
                </a>

                <svg
                    class="h-4 w-4 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="m9 5 7 7-7 7"
                    />
                </svg>

                <span class="text-gray-700">
                    {{ $equipe->reference }}
                </span>

            </div>


            <div class="flex flex-wrap items-center gap-3">

                <h1 class="text-2xl font-semibold tracking-tight text-[#212529]">
                    {{ $equipe->nom }}
                </h1>


                {{-- STATUT --}}
                @if($equipe->statut === 'ACTIVE')

                    <span
                        class="inline-flex items-center gap-1.5
                               rounded-full bg-green-50
                               px-3 py-1
                               text-xs font-semibold text-green-700"
                    >

                        <span
                            class="h-1.5 w-1.5 rounded-full bg-green-500"
                        ></span>

                        Active

                    </span>

                @else

                    <span
                        class="inline-flex items-center gap-1.5
                               rounded-full bg-gray-100
                               px-3 py-1
                               text-xs font-semibold text-gray-600"
                    >

                        <span
                            class="h-1.5 w-1.5 rounded-full bg-gray-400"
                        ></span>

                        Inactive

                    </span>

                @endif

            </div>


            <p class="mt-1 text-sm text-gray-500">
                Détails de l'équipe et composition des agents recenseurs.
            </p>

        </div>


        {{-- =====================================================
             ACTIONS
        ====================================================== --}}
        <div class="flex items-center gap-2">

            <a
                href="{{ route('dpa.equipes.index') }}"
                class="inline-flex items-center gap-2
                       rounded-lg border border-[#e5e7eb]
                       bg-white px-4 py-2.5
                       text-sm font-semibold text-gray-600
                       transition hover:bg-gray-50"
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
                        d="M15 19l-7-7 7-7"
                    />
                </svg>

                Retour

            </a>


            @if($equipe->statut === 'ACTIVE')

                <a
                    href="{{ route('dpa.equipes.edit', $equipe) }}"
                    class="inline-flex items-center gap-2
                           rounded-lg bg-[#006a4f]
                           px-4 py-2.5
                           text-sm font-semibold text-white
                           shadow-sm transition
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
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.5-9.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 8.5-8.5z"
                        />
                    </svg>

                    Modifier

                </a>

            @endif

        </div>

    </div>


    {{-- =========================================================
         MESSAGES
    ========================================================== --}}

    @if(session('success'))

        <div class="flex items-start gap-3 rounded-lg
                    border border-[#b7dfd2]
                    bg-[#e5f2ee]
                    px-4 py-3 text-sm text-[#006a4f]">

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

        <div class="flex items-start gap-3 rounded-lg
                    border border-red-200
                    bg-red-50
                    px-4 py-3 text-sm text-red-700">

            <svg
                class="mt-0.5 h-5 w-5 shrink-0"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    class="mt-0.5 h-5 w-5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0"
                />
            </svg>

            <span>{{ session('error') }}</span>

        </div>

    @endif


    {{-- =========================================================
         INFORMATIONS GÉNÉRALES
    ========================================================== --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">


        {{-- =====================================================
             CARTE ÉQUIPE
        ====================================================== --}}
        <div
            class="rounded-lg border border-[#e5e7eb]
                   bg-white shadow-[0_1px_2px_rgba(0,0,0,0.05)]"
        >

            <div class="border-b border-[#e5e7eb] px-6 py-5">

                <div class="flex items-center gap-3">

                    <div
                        class="flex h-10 w-10 items-center justify-center
                               rounded-lg bg-[#e5f2ee]
                               text-[#006a4f]"
                    >

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
                                d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m7-5a4 4 0 11-8 0 4 4 0 018 0zm5 1a3 3 0 10-6 0"
                            />
                        </svg>

                    </div>

                    <div>

                        <h2 class="font-semibold text-[#212529]">
                            Informations de l'équipe
                        </h2>

                        <p class="text-xs text-gray-500">
                            Identification de l'équipe
                        </p>

                    </div>

                </div>

            </div>


            <div class="space-y-5 px-6 py-5">

                {{-- RÉFÉRENCE --}}
                <div>

                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Référence
                    </p>

                    <p class="mt-1 font-mono text-sm font-semibold text-[#006a4f]">
                        {{ $equipe->reference }}
                    </p>

                </div>


                {{-- NOM --}}
                <div>

                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Nom de l'équipe
                    </p>

                    <p class="mt-1 text-sm font-semibold text-[#212529]">
                        {{ $equipe->nom }}
                    </p>

                </div>


                {{-- CRÉATION --}}
                <div>

                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Créée le
                    </p>

                    <p class="mt-1 text-sm text-gray-700">
                        {{ $equipe->created_at?->format('d/m/Y à H:i') ?? '-' }}
                    </p>

                </div>


                {{-- DERNIÈRE MODIFICATION --}}
                <div>

                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Dernière modification
                    </p>

                    <p class="mt-1 text-sm text-gray-700">
                        {{ $equipe->updated_at?->format('d/m/Y à H:i') ?? '-' }}
                    </p>

                </div>

            </div>

        </div>


        {{-- =====================================================
             CARTE SUPERVISEUR
        ====================================================== --}}
        <div
            class="rounded-lg border border-[#e5e7eb]
                   bg-white shadow-[0_1px_2px_rgba(0,0,0,0.05)]"
        >

            <div class="border-b border-[#e5e7eb] px-6 py-5">

                <div class="flex items-center gap-3">

                    <div
                        class="flex h-10 w-10 items-center justify-center
                               rounded-lg bg-blue-50
                               text-blue-600"
                    >

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
                                d="M12 14l9-5-9-5-9 5 9 5z"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M12 14l6.16-3.42A12.08 12.08 0 0118 15c0 2.21-2.69 4-6 4s-6-1.79-6-4c0-1.51.68-2.86 1.84-3.78L12 14z"
                            />
                        </svg>

                    </div>

                    <div>

                        <h2 class="font-semibold text-[#212529]">
                            Superviseur
                        </h2>

                        <p class="text-xs text-gray-500">
                            Responsable de l'équipe
                        </p>

                    </div>

                </div>

            </div>


            <div class="px-6 py-6">

                @if($equipe->superviseur)

                    <div class="flex items-center gap-4">

                        <div
                            class="flex h-14 w-14 shrink-0 items-center
                                   justify-center rounded-full
                                   bg-[#e5f2ee]
                                   text-lg font-semibold
                                   text-[#006a4f]"
                        >
                            {{ strtoupper(
                                substr(
                                    $equipe->superviseur->name,
                                    0,
                                    1
                                )
                            ) }}
                        </div>


                        <div class="min-w-0">

                            <p class="font-semibold text-[#212529]">
                                {{ $equipe->superviseur->name }}
                            </p>

                            @if($equipe->superviseur->telephone)

                                <p class="mt-1 text-sm text-gray-500">
                                    {{ $equipe->superviseur->telephone }}
                                </p>

                            @endif

                            @if($equipe->superviseur->email)

                                <p class="mt-1 truncate text-xs text-gray-400">
                                    {{ $equipe->superviseur->email }}
                                </p>

                            @endif

                        </div>

                    </div>

                @else

                    <div class="py-4 text-center">

                        <p class="text-sm font-medium text-gray-500">
                            Aucun superviseur affecté
                        </p>

                    </div>

                @endif

            </div>

        </div>


        {{-- =====================================================
             CARTE EFFECTIF
        ====================================================== --}}
        <div
            class="rounded-lg border border-[#e5e7eb]
                   bg-white shadow-[0_1px_2px_rgba(0,0,0,0.05)]"
        >

            <div class="border-b border-[#e5e7eb] px-6 py-5">

                <div class="flex items-center gap-3">

                    <div
                        class="flex h-10 w-10 items-center justify-center
                               rounded-lg bg-purple-50
                               text-purple-600"
                    >

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
                                d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m7-5a4 4 0 11-8 0 4 4 0 018 0zm5 1a3 3 0 10-6 0"
                            />
                        </svg>

                    </div>

                    <div>

                        <h2 class="font-semibold text-[#212529]">
                            Effectif
                        </h2>

                        <p class="text-xs text-gray-500">
                            Composition de l'équipe
                        </p>

                    </div>

                </div>

            </div>


            <div class="px-6 py-6">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-3xl font-semibold text-[#212529]">
                            {{ $equipe->membres->count() }}
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            agent{{ $equipe->membres->count() > 1 ? 's' : '' }}
                            recenseur{{ $equipe->membres->count() > 1 ? 's' : '' }}
                        </p>

                    </div>


                    <div
                        class="flex h-14 w-14 items-center justify-center
                               rounded-full bg-[#e5f2ee]
                               text-[#006a4f]"
                    >

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
                                d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m7-5a4 4 0 11-8 0 4 4 0 018 0zm5 1a3 3 0 10-6 0"
                            />
                        </svg>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         MEMBRES DE L'ÉQUIPE
    ========================================================== --}}
    <div
        class="overflow-hidden rounded-lg
               border border-[#e5e7eb]
               bg-white
               shadow-[0_1px_2px_rgba(0,0,0,0.05)]"
    >

        {{-- EN-TÊTE --}}
        <div
            class="flex flex-col gap-3
                   border-b border-[#e5e7eb]
                   px-6 py-5
                   sm:flex-row sm:items-center sm:justify-between"
        >

            <div>

                <h2 class="text-base font-semibold text-[#212529]">
                    Agents recenseurs
                </h2>

                <p class="mt-1 text-xs text-gray-500">
                    Membres composant cette équipe de terrain
                </p>

            </div>


            <span
                class="rounded-full bg-[#f8faf9]
                       px-3 py-1.5 text-sm text-gray-500"
            >

                <span class="font-semibold text-[#006a4f]">
                    {{ $equipe->membres->count() }}
                </span>

                membre{{ $equipe->membres->count() > 1 ? 's' : '' }}

            </span>

        </div>


        {{-- LISTE --}}
        @if($equipe->membres->count() > 0)

            <div class="divide-y divide-[#e5e7eb]">

                @foreach($equipe->membres as $agent)

                    <div
                        class="flex flex-col gap-4 px-6 py-5
                               transition hover:bg-[#f8faf9]
                               sm:flex-row sm:items-center
                               sm:justify-between"
                    >

                        {{-- IDENTITÉ --}}
                        <div class="flex items-center gap-4">

                            <div
                                class="flex h-11 w-11 shrink-0
                                       items-center justify-center
                                       rounded-full
                                       bg-[#e5f2ee]
                                       text-sm font-semibold
                                       text-[#006a4f]"
                            >

                                {{ strtoupper(
                                    substr(
                                        $agent->name,
                                        0,
                                        1
                                    )
                                ) }}

                            </div>


                            <div>

                                <p class="font-semibold text-[#212529]">
                                    {{ $agent->name }}
                                </p>

                                <div class="mt-1 flex flex-wrap items-center gap-x-4 gap-y-1">

                                    @if($agent->telephone)

                                        <span class="text-xs text-gray-500">
                                            {{ $agent->telephone }}
                                        </span>

                                    @endif

                                    @if($agent->email)

                                        <span class="text-xs text-gray-400">
                                            {{ $agent->email }}
                                        </span>

                                    @endif

                                </div>

                            </div>

                        </div>


                        {{-- RÔLE --}}
                        <div>

                            <span
                                class="inline-flex items-center gap-1.5
                                       rounded-full
                                       bg-blue-50 px-3 py-1
                                       text-xs font-semibold
                                       text-blue-700"
                            >

                                <span
                                    class="h-1.5 w-1.5 rounded-full
                                           bg-blue-500"
                                ></span>

                                Agent recenseur

                            </span>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            {{-- AUCUN MEMBRE --}}
            <div class="px-6 py-16 text-center">

                <div
                    class="mx-auto mb-4 flex h-14 w-14
                           items-center justify-center
                           rounded-full
                           bg-[#e5f2ee]
                           text-[#006a4f]"
                >

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
                            d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m7-5a4 4 0 11-8 0 4 4 0 018 0zm5 1a3 3 0 10-6 0"
                        />
                    </svg>

                </div>


                <p class="font-semibold text-[#212529]">
                    Aucun agent recenseur
                </p>

                <p class="mt-1 text-sm text-gray-500">
                    Aucun agent n'est actuellement membre de cette équipe.
                </p>


                @if($equipe->statut === 'ACTIVE')

                    <a
                        href="{{ route('dpa.equipes.edit', $equipe) }}"
                        class="mt-4 inline-flex items-center gap-2
                               text-sm font-semibold
                               text-[#006a4f]
                               hover:underline"
                    >

                        Modifier l'équipe

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
                                d="m9 18 6-6-6-6"
                            />
                        </svg>

                    </a>

                @endif

            </div>

        @endif

    </div>


    {{-- =========================================================
         RAPPEL ARCHITECTURE
    ========================================================== --}}
    <div
        class="rounded-lg border border-blue-200
               bg-blue-50 px-5 py-4"
    >

        <div class="flex items-start gap-3">

            <svg
                class="mt-0.5 h-5 w-5 shrink-0 text-blue-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.8"
                    d="M13 16h-1v-4h-1m1-4h.01M12 21a9 9 0 100-18"
                />
            </svg>

            <div>

                <p class="text-sm font-semibold text-blue-800">
                    Organisation de l'équipe
                </p>

                <p class="mt-1 text-sm leading-6 text-blue-700">
                    Cette équipe est constituée d'un superviseur et
                    d'un ou plusieurs agents recenseurs.
                    Les zones de travail seront définies lors de
                    l'affectation de l'équipe à une campagne.
                </p>

            </div>

        </div>

    </div>

</div>

@endsection