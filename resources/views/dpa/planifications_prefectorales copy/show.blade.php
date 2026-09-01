@extends('layouts.app')

@section('page-title', 'Planification préfectorale')

@section('page-subtitle')
    Consultation de la planification de la campagne
@endsection

@section('content')

<div class="mx-auto max-w-5xl space-y-6">

    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>

            <div class="flex items-center gap-3">

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-100">

                    <svg
                        class="h-6 w-6 text-green-700"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a3 3 0 016 0M9 5a3 3 0 006 0M9 5h6"
                        />
                    </svg>

                </div>

                <div>

                    <h1 class="text-2xl font-bold text-slate-800">
                        Planification préfectorale
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Organisation de la campagne dans la préfecture
                    </p>

                </div>

            </div>

        </div>


        {{-- ACTION MODIFIER --}}

        {{-- <a
            href="{{ route(
                'dpa.planifications-prefectorales.edit',
                $planificationPrefectorale
            ) }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
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

        </a> --}}

    </div>


    {{-- =========================================================
         INFORMATIONS DE LA PLANIFICATION
    ========================================================== --}}

    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

        <div class="grid grid-cols-1 divide-y divide-slate-100 sm:grid-cols-3 sm:divide-x sm:divide-y-0">

            {{-- CAMPAGNE --}}

            <div class="px-3 py-2 sm:px-5">

                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Campagne
                </p>

                <p class="mt-1 font-semibold text-slate-800">
                    {{ $planificationPrefectorale->deploiement?->campagne?->libelle ?? '-' }}
                </p>

            </div>


            {{-- PRÉFECTURE --}}

            <div class="px-3 py-3 sm:px-5">

                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Préfecture
                </p>

                <p class="mt-1 font-semibold text-slate-800">
                    {{ $planificationPrefectorale->deploiement?->prefecture?->nom ?? '-' }}
                </p>

            </div>


            {{-- STATUT --}}

            <div class="px-3 py-3 sm:px-5">

                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Statut
                </p>

                @php
                    $statut = $planificationPrefectorale->statut;
                @endphp

                @if($statut === 'brouillon')

                    <span class="mt-1 inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                        Brouillon
                    </span>

                @elseif($statut === 'soumise')

                    <span class="mt-1 inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                        Soumise
                    </span>

                @elseif($statut === 'validee')

                    <span class="mt-1 inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                        Validée
                    </span>

                @else

                    <span class="mt-1 inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                        {{ ucfirst($statut ?? 'Inconnu') }}
                    </span>

                @endif

            </div>

        </div>


        {{-- DATE --}}

        <div class="mt-5 border-t border-slate-100 pt-4">

            <div class="flex flex-wrap items-center gap-x-8 gap-y-2">

                <div>

                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Créée le
                    </p>

                    <p class="mt-1 text-sm text-slate-700">
                        {{ $planificationPrefectorale->created_at?->format('d/m/Y à H:i') ?? '-' }}
                    </p>

                </div>


                @if($planificationPrefectorale->updated_at)

                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Dernière modification
                        </p>

                        <p class="mt-1 text-sm text-slate-700">
                            {{ $planificationPrefectorale->updated_at->format('d/m/Y à H:i') }}
                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- =========================================================
         TERRITOIRE
    ========================================================== --}}

    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

        <div class="mb-5 flex items-start gap-3">

            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-green-50">

                <svg
                    class="h-5 w-5 text-green-700"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 21s8-4.5 8-10a8 8 0 10-16 0c0 5.5 8 10 8 10z"
                    />

                    <circle
                        cx="12"
                        cy="11"
                        r="2.5"
                    />
                </svg>

            </div>

            <div>

                <h2 class="text-base font-bold text-slate-800">
                    Territoire concerné
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Territoires retenus pour la réalisation de la campagne.
                </p>

            </div>

        </div>


        @if($planificationPrefectorale->territoires->count())

            <div class="flex flex-wrap gap-2">

                @foreach($planificationPrefectorale->territoires as $territoire)

                    @php

                        $nom = null;
                        $type = null;

                        if ($territoire->canton) {
                            $nom = $territoire->canton->nom;
                            $type = 'Canton';
                        }

                        if ($territoire->village) {
                            $nom = $territoire->village->nom;
                            $type = 'Village';
                        }

                    @endphp


                    <div class="inline-flex items-center gap-2 rounded-lg border border-green-100 bg-green-50 px-3 py-2">

                        <span class="h-2 w-2 rounded-full bg-green-600"></span>

                        <span class="text-sm font-medium text-green-800">
                            {{ $nom ?? $territoire->nom ?? '-' }}
                        </span>

                        @if($type)

                            <span class="text-xs text-green-600">
                                {{ $type }}
                            </span>

                        @endif

                    </div>

                @endforeach

            </div>

        @else

            <div class="rounded-lg border border-dashed border-slate-200 bg-slate-50 p-5 text-center">

                <p class="text-sm text-slate-400">
                    Aucun territoire spécifique enregistré.
                </p>

            </div>

        @endif

    </div>


    {{-- =========================================================
         BESOINS
    ========================================================== --}}

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-100 p-5">

            <div class="flex items-start gap-3">

                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-50">

                    <svg
                        class="h-5 w-5 text-blue-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                        />
                    </svg>

                </div>

                <div>

                    <h2 class="text-base font-bold text-slate-800">
                        Besoins pour la campagne
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Ressources nécessaires à la réalisation des activités.
                    </p>

                </div>

            </div>

        </div>


        @if($planificationPrefectorale->besoins->count())

            <div class="overflow-x-auto">

                <table class="min-w-full">

                    <thead class="bg-slate-50">

                        <tr>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Catégorie
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Besoin
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Quantité
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Observation
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @foreach($planificationPrefectorale->besoins as $besoin)

                            <tr class="transition hover:bg-slate-50">

                                <td class="px-5 py-4">

                                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700">
                                        {{ $besoin->categorie }}
                                    </span>

                                </td>


                                <td class="px-5 py-4">

                                    <p class="font-medium text-slate-800">
                                        {{ $besoin->designation }}
                                    </p>

                                </td>


                                <td class="px-5 py-4 text-sm text-slate-600">

                                    {{ $besoin->quantite }}

                                    @if($besoin->unite)
                                        {{ $besoin->unite }}
                                    @endif

                                </td>


                                <td class="px-5 py-4 text-sm text-slate-500">

                                    {{ $besoin->observations ?: '-' }}

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="p-6 text-center">

                <p class="text-sm text-slate-400">
                    Aucun besoin enregistré.
                </p>

            </div>

        @endif

    </div>


    {{-- =========================================================
         PLAN DE TRAVAIL
    ========================================================== --}}

    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

        <div class="flex items-start gap-3">

            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-indigo-50">

                <svg
                    class="h-5 w-5 text-indigo-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12h6m-6 4h6M9 8h6M5 4h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V5a1 1 0 011-1z"
                    />
                </svg>

            </div>

            <div class="min-w-0 flex-1">

                <h2 class="text-base font-bold text-slate-800">
                    Plan de travail
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Organisation prévue pour la mise en œuvre de la campagne.
                </p>

            </div>

        </div>


        @if($planificationPrefectorale->planTravail)

            <div class="mt-5 rounded-lg border border-slate-100 bg-slate-50 p-4">

                <p class="whitespace-pre-line text-sm leading-6 text-slate-700">
                    {{ $planificationPrefectorale->planTravail }}
                </p>

            </div>

        @else

            <p class="mt-5 text-sm text-slate-400">
                Aucun plan de travail renseigné.
            </p>

        @endif

    </div>


    {{-- =========================================================
         OBSERVATIONS
    ========================================================== --}}

    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

        <div class="flex items-start gap-3">

            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-50">

                <svg
                    class="h-5 w-5 text-amber-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 10h8M8 14h5m-9 6h14a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                    />
                </svg>

            </div>

            <div class="min-w-0 flex-1">

                <h2 class="text-base font-bold text-slate-800">
                    Observations générales
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Informations complémentaires relatives à la planification.
                </p>

            </div>

        </div>


        @if($planificationPrefectorale->observations)

            <div class="mt-5 rounded-lg border border-amber-100 bg-amber-50 p-4">

                <p class="whitespace-pre-line text-sm leading-6 text-slate-700">
                    {{ $planificationPrefectorale->observations }}
                </p>

            </div>

        @else

            <p class="mt-5 text-sm text-slate-400">
                Aucune observation générale.
            </p>

        @endif

    </div>


    {{-- =========================================================
         ACTIONS
    ========================================================== --}}

    <div class="flex flex-wrap items-center justify-between gap-3">

        <a
            href="{{ route('dpa.planifications-prefectorales.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
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

            Retour aux planifications

        </a>


        <a
            href="{{ route(
                'dpa.planifications-prefectorales.edit',
                $planificationPrefectorale
            ) }}"
            class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-green-700"
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
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"
                />

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 10.5-10.5z"
                />
            </svg>

            Modifier la planification

        </a>

    </div>

</div>

@endsection