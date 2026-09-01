@extends('layouts.app')

@section('page-title', 'Planification préfectorale')

@section(
    'page-subtitle',
    'Adapter la planification de la campagne à votre préfecture'
)

@section('content')

<div class="mx-auto max-w-6xl">


    {{-- ==========================================================
        EN-TÊTE
    =========================================================== --}}

    <div class="mb-6">

        <div class="flex items-center gap-3">

            <div
                class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-100"
            >

                <svg
                    class="h-5 w-5 text-green-600"
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

                <h1 class="text-2xl font-bold text-slate-800">
                    Planification préfectorale
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Définissez les territoires et les besoins de la campagne.
                </p>

            </div>

        </div>

    </div>


    {{-- ==========================================================
        INFORMATIONS DU DÉPLOIEMENT
    =========================================================== --}}

    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">


            {{-- CAMPAGNE --}}

            <div>

                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Campagne
                </p>

                <p class="mt-1 text-sm font-semibold text-gray-800">
                    {{ $deploiement->campagne->libelle }}
                </p>

                @if($deploiement->campagne->codeCampagne)

                    <p class="mt-1 text-xs text-gray-400">
                        {{ $deploiement->campagne->codeCampagne }}
                    </p>

                @endif

            </div>


            {{-- PRÉFECTURE --}}

            <div>

                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Préfecture
                </p>

                <p class="mt-1 text-sm font-semibold text-gray-800">
                    {{ $deploiement->prefecture->nom }}
                </p>

            </div>


            {{-- STATUT --}}

            <div>

                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Statut du déploiement
                </p>

                <div class="mt-1">

                    @php

                        $statutClasses = match ($deploiement->statut) {

                            'actif', 'active' =>
                                'bg-green-100 text-green-700',

                            'planifie', 'planifiee' =>
                                'bg-blue-100 text-blue-700',

                            'termine', 'cloture' =>
                                'bg-gray-100 text-gray-700',

                            default =>
                                'bg-gray-100 text-gray-600',

                        };

                    @endphp

                    <span
                        class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $statutClasses }}"
                    >
                        {{ ucfirst($deploiement->statut) }}
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- ==========================================================
        FORMULAIRE
    =========================================================== --}}

    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

        <form
            method="POST"
            action="{{ route(
                'dpa.planifications-prefectorales.store',
                [
                    'deploiement' =>
                        $deploiement->idDeploiement
                ]
            ) }}"
        >

            @include(
                'dpa.planifications_prefectorales._form',
                [
                    'deploiement' => $deploiement,

                    'planification' =>
                        $planification ?? null,

                    'planificationPrefectorale' =>
                        $planificationPrefectorale ?? null,

                    'communes' =>
                        $communes ?? collect(),

                    'cantons' =>
                        $cantons ?? collect(),

                    'villages' =>
                        $villages ?? collect(),

                    'besoins' =>
                        $besoins ?? collect(),
                ]
            )

        </form>

    </div>

</div>

@endsection