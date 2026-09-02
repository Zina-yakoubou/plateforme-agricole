@extends('layouts.app')

@section('page-title', 'Affectation des équipes')

@section(
    'page-subtitle',
    'Suivi des équipes affectées aux territoires de la campagne'
)

@section('content')

<div class="mx-auto max-w-7xl space-y-6">

    {{-- ============================================================
        EN-TÊTE
    ============================================================= --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div class="flex items-center gap-3">

            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-100">
                <svg
                    class="h-6 w-6 text-green-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 5H7a2 2 0 00-2 2v11a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"
                    />

                    <rect
                        x="9"
                        y="3"
                        width="6"
                        height="4"
                        rx="1"
                        stroke-width="1.8"
                    />

                    <path
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M8 12l1.5 1.5L12 11
                           M8 17l1.5 1.5L12 16
                           M14 13h2
                           M14 18h2"
                    />
                </svg>
            </div>

            <div>
                <h1 class="text-2xl font-bold text-slate-800">
                    Affectation des équipes
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Consultez et gérez les affectations de votre préfecture.
                </p>
            </div>

        </div>

    </div>


    {{-- ============================================================
        INDICATEURS
    ============================================================= --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

        {{-- Total --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Total des affectations
                    </p>

                    <p class="mt-2 text-2xl font-bold text-slate-800">
                        {{ $affectations->total() }}
                    </p>
                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100">
                    <svg
                        class="h-5 w-5 text-gray-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 5H7a2 2 0 00-2 2v11a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"
                        />

                        <rect
                            x="9"
                            y="3"
                            width="6"
                            height="4"
                            rx="1"
                            stroke-width="1.8"
                        />
                    </svg>
                </div>

            </div>

        </div>


        {{-- Actives --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Affectations actives
                    </p>

                    <p class="mt-2 text-2xl font-bold text-green-600">
                        {{ $affectations->getCollection()->where('statut', 'active')->count() }}
                    </p>
                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-50">
                    <span class="h-3 w-3 rounded-full bg-green-500"></span>
                </div>

            </div>

        </div>


        {{-- Annulées --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Affectations annulées
                    </p>

                    <p class="mt-2 text-2xl font-bold text-red-600">
                        {{ $affectations->getCollection()->where('statut', 'annulee')->count() }}
                    </p>
                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-50">
                    <svg
                        class="h-5 w-5 text-red-500"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 6l12 12M18 6L6 18"
                        />
                    </svg>
                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        FILTRES
    ============================================================= --}}
    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

        <form
            method="GET"
            action="{{ route('dpa.affectations.index') }}"
            class="grid grid-cols-1 gap-4 md:grid-cols-3"
        >

            {{-- Campagne --}}
            <div>
                <label
                    for="campagne_id"
                    class="mb-1.5 block text-sm font-medium text-gray-700"
                >
                    Campagne
                </label>

                <select
                    name="campagne_id"
                    id="campagne_id"
                    class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-green-500 focus:ring-green-500"
                >

                    <option value="">
                        Toutes les campagnes
                    </option>

                    @php
                        $campagnesFiltrees = $affectations
                            ->getCollection()
                            ->pluck('campagne')
                            ->filter()
                            ->unique('idCampagne')
                            ->sortBy('libelle');
                    @endphp

                    @foreach($campagnesFiltrees as $campagne)
                        <option
                            value="{{ $campagne->idCampagne }}"
                            @selected(request('campagne_id') == $campagne->idCampagne)
                        >
                            {{ $campagne->libelle }}
                        </option>
                    @endforeach

                </select>
            </div>


            {{-- Statut --}}
            <div>
                <label
                    for="statut"
                    class="mb-1.5 block text-sm font-medium text-gray-700"
                >
                    Statut
                </label>

                <select
                    name="statut"
                    id="statut"
                    class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-green-500 focus:ring-green-500"
                >

                    <option value="">
                        Tous les statuts
                    </option>

                    <option
                        value="active"
                        @selected(request('statut') === 'active')
                    >
                        Active
                    </option>

                    <option
                        value="annulee"
                        @selected(request('statut') === 'annulee')
                    >
                        Annulée
                    </option>

                    <option
                        value="cloturee"
                        @selected(request('statut') === 'cloturee')
                    >
                        Clôturée
                    </option>

                </select>
            </div>


            {{-- Boutons --}}
            <div class="flex items-end gap-2">

                <button
                    type="submit"
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-green-600 px-5 text-sm font-semibold text-white transition hover:bg-green-700"
                >
                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"
                        />
                    </svg>

                    Filtrer
                </button>

                @if(request()->filled('campagne_id') || request()->filled('statut'))

                    <a
                        href="{{ route('dpa.affectations.index') }}"
                        class="inline-flex h-10 items-center justify-center rounded-lg border border-gray-300 px-4 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                    >
                        Réinitialiser
                    </a>

                @endif

            </div>

        </form>

    </div>


    {{-- ============================================================
        TABLEAU
    ============================================================= --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 px-5 py-4">

            <div class="flex items-center justify-between">

                <div>
                    <h2 class="text-base font-semibold text-slate-800">
                        Liste des affectations
                    </h2>

                    <p class="mt-1 text-xs text-gray-500">
                        Les affectations de votre préfecture
                    </p>
                </div>

                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600">
                    {{ $affectations->total() }}
                    {{ $affectations->total() > 1 ? 'affectations' : 'affectation' }}
                </span>

            </div>

        </div>


        @if($affectations->count())

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Référence
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Équipe
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Campagne
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Territoire
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Statut
                            </th>

                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100 bg-white">

                        @foreach($affectations as $affectation)

                            <tr class="transition hover:bg-gray-50">

                                {{-- Référence --}}
                                <td class="whitespace-nowrap px-5 py-4">

                                    <div class="text-sm font-semibold text-slate-800">
                                        {{ $affectation->reference }}
                                    </div>

                                    <div class="mt-0.5 text-xs text-gray-400">
                                        #{{ $affectation->idAffectation }}
                                    </div>

                                </td>


                                {{-- Équipe --}}
                                <td class="px-5 py-4">

                                    <div class="text-sm font-semibold text-slate-800">
                                        {{ $affectation->equipe?->nom ?? 'Équipe non renseignée' }}
                                    </div>

                                    @if($affectation->equipe?->superviseur)

                                        <div class="mt-1 text-xs text-gray-500">
                                            Superviseur :
                                            {{ $affectation->equipe->superviseur->name }}
                                        </div>

                                    @endif

                                </td>


                                {{-- Campagne --}}
                                <td class="px-5 py-4">

                                    @if($affectation->campagne)

                                        <div class="text-sm font-medium text-slate-700">
                                            {{ $affectation->campagne->libelle }}
                                        </div>

                                        @if($affectation->campagne->codeCampagne)

                                            <div class="mt-1 text-xs text-gray-400">
                                                {{ $affectation->campagne->codeCampagne }}
                                            </div>

                                        @endif

                                    @else

                                        <span class="text-sm text-gray-400">
                                            Campagne supprimée
                                        </span>

                                    @endif

                                </td>


                                {{-- Territoire --}}
                                <td class="px-5 py-4">

                                    @if($affectation->village)

                                        <div class="text-sm font-semibold text-slate-700">
                                            {{ $affectation->village->nom }}
                                        </div>

                                        @if($affectation->village->canton)

                                            <div class="mt-1 text-xs text-gray-500">
                                                Canton :
                                                {{ $affectation->village->canton->nom }}
                                            </div>

                                        @endif

                                        @if($affectation->village->canton?->commune)

                                            <div class="mt-0.5 text-xs text-gray-400">
                                                Commune :
                                                {{ $affectation->village->canton->commune->nom }}
                                            </div>

                                        @endif

                                    @else

                                        <span class="text-sm text-gray-400">
                                            Territoire non renseigné
                                        </span>

                                    @endif

                                </td>


                                {{-- Statut --}}
                                <td class="px-5 py-4 whitespace-nowrap">

                                    @switch($affectation->statut)

                                        @case('active')

                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">
                                                <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                                Active
                                            </span>

                                            @break

                                        @case('annulee')

                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">
                                                <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                                Annulée
                                            </span>

                                            @break

                                        @case('cloturee')

                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700">
                                                <span class="h-1.5 w-1.5 rounded-full bg-gray-500"></span>
                                                Clôturée
                                            </span>

                                            @break

                                        @default

                                            <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">
                                                {{ ucfirst($affectation->statut) }}
                                            </span>

                                    @endswitch

                                </td>


                                {{-- Actions --}}
                                <td class="whitespace-nowrap px-5 py-4">

                                    <div class="flex items-center justify-end gap-2">

                                        {{-- Voir --}}
                                        <a
                                            href="{{ route(
                                                'dpa.affectations.show',
                                                $affectation
                                            ) }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 transition hover:border-green-200 hover:bg-green-50 hover:text-green-700"
                                            title="Voir l'affectation"
                                        >

                                            <svg
                                                class="h-4 w-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-width="1.8"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M2.5 12s3.5-5 9.5-5 9.5 5 9.5 5-3.5 5-9.5 5-9.5-5-9.5-5z"
                                                />

                                                <circle
                                                    cx="12"
                                                    cy="12"
                                                    r="2.5"
                                                    stroke-width="1.8"
                                                />
                                            </svg>

                                            Voir

                                        </a>


                                        {{-- Annuler --}}
                                        @if($affectation->statut === 'active')

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'dpa.affectations.destroy',
                                                    $affectation
                                                ) }}"
                                                onsubmit="return confirm('Voulez-vous vraiment annuler cette affectation ?');"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50"
                                                    title="Annuler l'affectation"
                                                >

                                                    <svg
                                                        class="h-4 w-4"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-width="1.8"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M6 6l12 12M18 6L6 18"
                                                        />
                                                    </svg>

                                                    Annuler

                                                </button>

                                            </form>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            @if($affectations->hasPages())

                <div class="border-t border-gray-200 px-5 py-4">
                    {{ $affectations->links() }}
                </div>

            @endif

        @else

            {{-- Aucun résultat --}}
            <div class="px-6 py-16 text-center">

                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100">

                    <svg
                        class="h-6 w-6 text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 5H7a2 2 0 00-2 2v11a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"
                        />

                        <rect
                            x="9"
                            y="3"
                            width="6"
                            height="4"
                            rx="1"
                            stroke-width="1.8"
                        />
                    </svg>

                </div>

                <h3 class="mt-4 text-sm font-semibold text-gray-800">
                    Aucune affectation trouvée
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Aucune affectation ne correspond aux critères sélectionnés.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection