@extends('layouts.app')

@section('page-title', 'Planification préfectorale')
@section('page-subtitle', 'Modifier la planification de la campagne pour votre préfecture')

@section('content')

<div class="mx-auto max-w-6xl">


{{-- =========================================================
     EN-TÊTE
========================================================== --}}

<div class="mb-6">

    <h1 class="text-2xl font-bold text-slate-800">
        Modifier la planification préfectorale
    </h1>

    <p class="mt-1 text-sm text-slate-500">
        {{ $deploiement->campagne->libelle }}
    </p>

</div>


{{-- =========================================================
     INFORMATIONS DU DÉPLOIEMENT
========================================================== --}}

<div class="mb-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

        {{-- CAMPAGNE --}}
        <div>

            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                Campagne
            </p>

            <p class="mt-1 text-sm font-semibold text-gray-800">
                {{ $deploiement->campagne->libelle }}
            </p>

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
                Statut
            </p>

            <p class="mt-1 text-sm font-semibold text-gray-800">
                {{ ucfirst($deploiement->statut) }}
            </p>

        </div>

    </div>

</div>


{{-- =========================================================
     FORMULAIRE
========================================================== --}}

<div class="rounded-xl bg-white p-6 shadow-sm">

    <form
        method="POST"
        action="{{ route(
            'dpa.planifications-prefectorales.update',
            ['planificationPrefectorale' => $planificationPrefectorale->idPlanificationPrefectorale]
        ) }}"
    >

        @csrf
        @method('PUT')

        @include(
            'dpa.planifications_prefectorales._form',
            [
                'deploiement' => $deploiement,

                'planification' => $planificationPrefectorale,

                'planificationPrefectorale' => $planificationPrefectorale,

                'communes' => $communes ?? collect(),

                'cantons' => $cantons ?? collect(),

                'villages' => $villages ?? collect(),

                'besoins' => $besoins ?? collect(),
            ]
        )

    </form>

</div>


</div>

@endsection
