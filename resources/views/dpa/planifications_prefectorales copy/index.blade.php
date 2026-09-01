@extends('layouts.app')

@section('page-title', 'Planifications préfectorales')
@section('page-subtitle', 'Planifications des campagnes pour votre préfecture')

@section('content')

<div class="space-y-6">

    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-[#212529]">
                Planifications préfectorales
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Planifications des campagnes pour la préfecture
                de {{ $prefecture->nom ?? $prefecture->libelle }}
            </p>
        </div>

    </div>


    {{-- =========================================================
         STATISTIQUES
    ========================================================== --}}

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

        {{-- TOTAL --}}
        <div class="rounded-lg
                    border border-[#e5e7eb]
                    bg-white
                    px-5 py-4
                    shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                Total planifications
            </p>

            <p class="mt-2 text-2xl font-bold text-[#212529]">
                {{ $planifications->total() }}
            </p>

        </div>


        {{-- AFFICHÉES --}}
        <div class="rounded-lg
                    border border-[#e5e7eb]
                    bg-white
                    px-5 py-4
                    shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                Planifications affichées
            </p>

            <p class="mt-2 text-2xl font-bold text-[#006a4f]">
                {{ $planifications->count() }}
            </p>

        </div>


        {{-- PRÉFECTURE --}}
        <div class="rounded-lg
                    border border-[#e5e7eb]
                    bg-white
                    px-5 py-4
                    shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                Préfecture
            </p>

            <p class="mt-2 truncate text-lg font-bold text-[#212529]">
                {{ $prefecture->nom ?? $prefecture->libelle }}
            </p>

        </div>

    </div>


    {{-- =========================================================
         TABLEAU
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
                    Planifications
                </h2>

                <p class="mt-1 text-xs text-gray-500">
                    Liste des planifications préfectorales enregistrées.
                </p>

            </div>

            <div class="text-sm text-gray-500">

                <span class="font-semibold text-[#006a4f]">
                    {{ $planifications->total() }}
                </span>

                planification(s)

            </div>

        </div>


        {{-- =====================================================
             TABLE
        ====================================================== --}}

        <div class="overflow-x-auto">

            <table class="w-full min-w-[1050px] text-sm">

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
                            Campagne
                        </th>

                        <th class="px-6 py-4 text-left
                                   text-xs font-semibold
                                   uppercase tracking-wide
                                   text-gray-500">
                            Préfecture
                        </th>

                        <th class="px-6 py-4 text-left
                                   text-xs font-semibold
                                   uppercase tracking-wide
                                   text-gray-500">
                            Territoires
                        </th>

                        <th class="px-6 py-4 text-left
                                   text-xs font-semibold
                                   uppercase tracking-wide
                                   text-gray-500">
                            Besoins
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

                    @forelse($planifications as $planification)

                        @php

                            /*
                            |--------------------------------------------------------------------------
                            | TERRITOIRES
                            |--------------------------------------------------------------------------
                            */

                            $territoires = $planification->territoires;

                            $cantonIds = $territoires
                                ->whereNotNull('canton_id')
                                ->pluck('canton_id');

                            $villageIds = $territoires
                                ->whereNotNull('village_id')
                                ->pluck('village_id');


                            /*
                            |--------------------------------------------------------------------------
                            | COMMUNES DÉDUITES DES CANTONS
                            |--------------------------------------------------------------------------
                            */

                            $communeIdsDepuisCantons = $territoires
                                ->whereNotNull('canton_id')
                                ->filter(fn ($t) => $t->canton)
                                ->map(fn ($t) => $t->canton->commune_id)
                                ->filter();


                            /*
                            |--------------------------------------------------------------------------
                            | COMMUNES DÉDUITES DES VILLAGES
                            |--------------------------------------------------------------------------
                            */

                            $communeIdsDepuisVillages = $territoires
                                ->whereNotNull('village_id')
                                ->filter(fn ($t) => $t->village?->canton)
                                ->map(fn ($t) => $t->village->canton->commune_id)
                                ->filter();


                            $communeCount = $communeIdsDepuisCantons
                                ->merge($communeIdsDepuisVillages)
                                ->unique()
                                ->count();


                            /*
                            |--------------------------------------------------------------------------
                            | STATUT
                            |--------------------------------------------------------------------------
                            */

                            $statut = $planification->statut;

                            $classes = match ($statut) {

                                'brouillon' =>
                                    'bg-gray-100 text-gray-700',

                                'soumise' =>
                                    'bg-yellow-50 text-yellow-700',

                                'validee' =>
                                    'bg-green-50 text-green-700',

                                'rejetee' =>
                                    'bg-red-50 text-red-700',

                                default =>
                                    'bg-gray-100 text-gray-700',
                            };

                        @endphp


                        <tr class="group transition hover:bg-[#f8faf9]">


                            {{-- =================================================
                                 N°
                            ================================================== --}}

                            <td class="px-6 py-4 text-sm text-gray-400">

                                {{ $loop->iteration + (($planifications->currentPage() - 1) * $planifications->perPage()) }}

                            </td>


                            {{-- =================================================
                                 CAMPAGNE
                            ================================================== --}}

                            <td class="px-6 py-4">

                                <div class="min-w-0">

                                    <div class="truncate font-semibold text-[#212529]">

                                        {{ $planification->deploiement?->campagne?->libelle ?? '—' }}

                                    </div>


                                    @if($planification->deploiement?->campagne?->codeCampagne)

                                        <div class="mt-1 text-xs text-gray-500">

                                            {{ $planification->deploiement->campagne->codeCampagne }}

                                        </div>

                                    @endif

                                </div>

                            </td>


                            {{-- =================================================
                                 PRÉFECTURE
                            ================================================== --}}

                            <td class="px-6 py-4 text-[#434343]">

                                {{ $planification->deploiement?->prefecture?->nom
                                    ?? $planification->deploiement?->prefecture?->libelle
                                    ?? '—' }}

                            </td>


                            {{-- =================================================
                                 TERRITOIRES
                            ================================================== --}}

                            <td class="px-6 py-4">

                                <div class="space-y-1 text-sm">

                                    <div class="text-[#434343]">

                                        <span class="font-semibold text-[#212529]">
                                            {{ $communeCount }}
                                        </span>

                                        commune(s)

                                    </div>


                                    <div class="text-gray-500">

                                        <span class="font-semibold text-[#434343]">
                                            {{ $cantonIds->unique()->count() }}
                                        </span>

                                        canton(s)

                                    </div>


                                    <div class="text-gray-500">

                                        <span class="font-semibold text-[#434343]">
                                            {{ $villageIds->unique()->count() }}
                                        </span>

                                        village(s)

                                    </div>

                                </div>

                            </td>


                            {{-- =================================================
                                 BESOINS
                            ================================================== --}}

                            <td class="px-6 py-4 text-[#434343]">

                                <span class="font-semibold text-[#212529]">
                                    {{ $planification->besoins?->count() ?? 0 }}
                                </span>

                                besoin(s)

                            </td>


                            {{-- =================================================
                                 STATUT
                            ================================================== --}}

                            <td class="px-6 py-4 text-center">

                                <span
                                    class="inline-flex
                                           items-center
                                           gap-1.5
                                           rounded-full
                                           px-3 py-1
                                           text-xs
                                           font-semibold
                                           {{ $classes }}"
                                >

                                    <span
                                        class="h-1.5 w-1.5 rounded-full
                                        @switch($statut)
                                            @case('brouillon')
                                                bg-gray-400
                                                @break

                                            @case('soumise')
                                                bg-yellow-500
                                                @break

                                            @case('validee')
                                                bg-green-500
                                                @break

                                            @case('rejetee')
                                                bg-red-500
                                                @break

                                            @default
                                                bg-gray-400
                                        @endswitch
                                        "
                                    ></span>

                                    {{ ucfirst($statut ?? 'inconnu') }}

                                </span>

                            </td>


                            {{-- =================================================
                                 ACTIONS
                            ================================================== --}}

                            <td class="px-6 py-4">

                                <div class="flex
                                            items-center
                                            justify-end
                                            gap-2">


                                    {{-- =====================================
                                         VOIR
                                    ====================================== --}}

                                    <a
                                        href="{{ route(
                                            'dpa.planifications-prefectorales.show',
                                            $planification
                                        ) }}"
                                        title="Voir la planification"
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


                                    {{-- =====================================
                                         MODIFIER
                                    ====================================== --}}

                                    <a
                                        href="{{ route(
                                            'dpa.planifications-prefectorales.edit',
                                            $planification
                                        ) }}"
                                        title="Modifier la planification"
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
                                                d="M11 4H6
                                                   a2 2 0 00-2 2v12
                                                   a2 2 0 002 2h12
                                                   a2 2 0 002-2v-5
                                                   M16.5 3.5
                                                   a2.121 2.121 0 013 3L12 14
                                                   l-4 1 1-4 7.5-7.5z"
                                            />

                                        </svg>

                                    </a>

                                </div>

                            </td>

                        </tr>


                    @empty

                        {{-- =================================================
                             AUCUNE PLANIFICATION
                        ================================================== --}}

                        <tr>

                            <td
                                colspan="7"
                                class="px-6 py-16 text-center"
                            >

                                <div class="flex flex-col items-center">

                                    <div
                                        class="mb-4
                                               flex h-14 w-14
                                               items-center
                                               justify-center
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
                                                d="M8 3v4
                                                   M16 3v4
                                                   M4 9h16
                                                   M6 5h12a2 2 0 012 2v12
                                                   a2 2 0 01-2 2H6
                                                   a2 2 0 01-2-2V7
                                                   a2 2 0 012-2z"
                                            />

                                        </svg>

                                    </div>


                                    <p class="font-semibold text-[#212529]">
                                        Aucune planification préfectorale
                                    </p>

                                    <p class="mt-1 text-sm text-gray-500">
                                        Les planifications créées pour votre
                                        préfecture apparaîtront ici.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- =========================================================
             PAGINATION
        ========================================================== --}}

        @if($planifications->hasPages())

            <div class="flex justify-center border-t border-[#e5e7eb] px-6 py-4">

                {{ $planifications->links() }}

            </div>

        @endif

    </div>

</div>

@endsection