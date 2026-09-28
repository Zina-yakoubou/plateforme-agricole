@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-slate-50">

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

        {{-- ============================================================
            EN-TÊTE
        ============================================================ --}}
        <div class="mb-6">

            <div class="mb-2 flex items-center gap-2 text-sm text-slate-500">

                <span>
                    Mes affectations
                </span>

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
                        d="M9 5l7 7-7 7"
                    />
                </svg>

                <span>
                    {{ $affectation->village->nom }}
                </span>

            </div>

            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                <div>
                    <h1 class="text-2xl font-bold text-slate-800">
                        Recensement du village
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ $affectation->village->nom }}
                    </p>
                </div>

                <div class="rounded-xl bg-emerald-50 px-4 py-3">
                    <p class="text-xs font-medium uppercase tracking-wide text-emerald-700">
                        Statut de l'affectation
                    </p>

                    <p class="mt-1 text-sm font-semibold text-emerald-800">
                        {{ ucfirst($affectation->statut) }}
                    </p>
                </div>

            </div>

        </div>


        {{-- ============================================================
            INFORMATIONS DE L'AFFECTATION
        ============================================================ --}}
        <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

            {{-- Campagne --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="flex items-center gap-3">

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">

                        <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                            />
                        </svg>

                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Campagne
                        </p>

                        <p class="font-semibold text-slate-800">
                            {{ $affectation->campagne->libelle }}
                        </p>
                    </div>

                </div>

            </div>


            {{-- Équipe --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="flex items-center gap-3">

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-700">

                        <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-5a4 4 0 10-8 0 4 4 0 008 0zm-9 0a3 3 0 10-6 0 3 3 0 006 0z"
                            />
                        </svg>

                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Équipe
                        </p>

                        <p class="font-semibold text-slate-800">
                            {{ $affectation->equipe->nom ?? 'Équipe' }}
                        </p>
                    </div>

                </div>

            </div>


            {{-- Canton --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="flex items-center gap-3">

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-100 text-amber-700">

                        <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17.657 16.657L13.414 21a2 2 0 01-2.828 0l-4.243-4.343a8 8 0 1111.314 0z"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                        </svg>

                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Canton
                        </p>

                        <p class="font-semibold text-slate-800">
                            {{ $affectation->village->canton->nom }}
                        </p>
                    </div>

                </div>

            </div>


            {{-- Commune --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="flex items-center gap-3">

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100 text-slate-700">

                        <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 21h18M5 21V9l7-4 7 4v12M9 21v-6h6v6"
                            />
                        </svg>

                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Commune
                        </p>

                        <p class="font-semibold text-slate-800">
                            {{ $affectation->village->canton->commune->nom }}
                        </p>
                    </div>

                </div>

            </div>

        </div>


        {{-- ============================================================
            MAISONS DU VILLAGE
        ============================================================ --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

            {{-- En-tête --}}
            <div class="border-b border-slate-200 px-6 py-5">

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <h2 class="text-lg font-bold text-slate-800">
                            Maisons à recenser
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Sélectionnez une maison pour commencer ou reprendre son recensement.
                        </p>

                    </div>

                    <div class="rounded-xl bg-slate-100 px-4 py-2">

                        <span class="text-sm font-semibold text-slate-700">
                            {{ $maisons->count() }}
                        </span>

                        <span class="text-sm text-slate-500">
                            maison(s)
                        </span>

                    </div>

                </div>

            </div>


            {{-- Liste des maisons --}}
            <div class="divide-y divide-slate-100">

                @forelse($maisons as $maison)

                    @php
                        $recensement = $maison->recensements->first();
                    @endphp

                    <div class="px-6 py-5 transition hover:bg-slate-50">

                        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                            {{-- Informations maison --}}
                            <div class="flex items-start gap-4">

                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">

                                    <svg
                                        class="h-6 w-6"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 10.5L12 3l9 7.5M5 9.5V21h14V9.5M9 21v-6h6v6"
                                        />
                                    </svg>

                                </div>

                                <div>

                                    <div class="flex flex-wrap items-center gap-2">

                                        <h3 class="font-bold text-slate-800">
                                            Maison {{ $maison->numeroMaison }}
                                        </h3>

                                        @if($recensement)

                                            @if($recensement->statut === 'brouillon')

                                                <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                                    Brouillon
                                                </span>

                                            @elseif($recensement->statut === 'en_cours')

                                                <span class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700">
                                                    En cours
                                                </span>

                                            @elseif($recensement->statut === 'termine')

                                                <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                                    Terminé
                                                </span>

                                            @elseif($recensement->statut === 'valide')

                                                <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">
                                                    Validé
                                                </span>

                                            @endif

                                        @else

                                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                                Non commencé
                                            </span>

                                        @endif

                                    </div>


                                    <div class="mt-2 flex flex-wrap gap-x-5 gap-y-1 text-sm text-slate-500">

                                        <span>
                                            Village :
                                            <strong class="font-medium text-slate-700">
                                                {{ $affectation->village->nom }}
                                            </strong>
                                        </span>

                                        @if($maison->adresse)

                                            <span>
                                                {{ $maison->adresse }}
                                            </span>

                                        @endif

                                    </div>


                                    @if($recensement)

                                        <div class="mt-2 flex items-center gap-2 text-sm text-slate-500">

                                            <svg
                                                class="h-4 w-4 text-slate-400"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-5a4 4 0 10-8 0 4 4 0 008 0z"
                                                />
                                            </svg>

                                            <span>
                                                {{ $recensement->menages_count ?? 0 }}
                                                ménage(s)
                                            </span>

                                        </div>

                                    @endif

                                </div>

                            </div>


                            {{-- Action --}}
                            <div class="shrink-0">

                                @if($recensement)

                                    <a
                                        href="{{ route('agent.recensements.show', $recensement) }}"
                                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 sm:w-auto"
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
                                                stroke-width="2"
                                                d="M13 7l5 5m0 0l-5 5m5-5H6"
                                            />
                                        </svg>

                                        Reprendre

                                    </a>

                                @else

                                    <a
                                        href="{{ route('agent.recensements.commencer', [
                                            'affectation' => $affectation->idAffectation,
                                            'maison' => $maison->idMaison,
                                        ]) }}"
                                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 sm:w-auto"
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
                                                stroke-width="2"
                                                d="M12 4v16m8-8H4"
                                            />
                                        </svg>

                                        Commencer

                                    </a>

                                @endif

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="px-6 py-12 text-center">

                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400">

                            <svg
                                class="h-7 w-7"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 10.5L12 3l9 7.5M5 9.5V21h14V9.5"
                                />
                            </svg>

                        </div>

                        <h3 class="mt-4 text-base font-semibold text-slate-800">
                            Aucune maison trouvée
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Aucune maison n'est actuellement enregistrée dans ce village.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </div>

</div>

@endsection