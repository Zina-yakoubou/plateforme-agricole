@extends('layouts.app')

@section('page-title', 'Planifications préfectorales')

@section('page-subtitle', 'Planifications des campagnes pour votre préfecture')

@section('content')

<div class="space-y-6">

    {{-- ================================================================
        EN-TÊTE
    ================================================================= --}}

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>

            <h1 class="text-xl font-bold text-slate-900">
                Planifications préfectorales
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Consultez les planifications des campagnes au niveau préfectoral.
            </p>

        </div>

    </div>


    {{-- ================================================================
        STATISTIQUES
    ================================================================= --}}

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

        {{-- TOTAL --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Total
                    </p>

                    <p class="mt-1 text-2xl font-bold text-slate-900">
                        {{ $planifications->total() }}
                    </p>

                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#006a4f]/10">

                    <svg
                        class="h-5 w-5 text-[#006a4f]"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                        />
                    </svg>

                </div>

            </div>

        </div>


        {{-- EN COURS --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        En cours
                    </p>

                    <p class="mt-1 text-2xl font-bold text-emerald-600">
                        {{ $planifications->whereIn('statut', ['active', 'en_cours'])->count() }}
                    </p>

                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50">

                    <svg
                        class="h-5 w-5 text-emerald-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>

                </div>

            </div>

        </div>


        {{-- TERMINÉES --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Terminées
                    </p>

                    <p class="mt-1 text-2xl font-bold text-slate-700">
                        {{ $planifications->whereIn('statut', ['cloturee', 'terminee', 'termine'])->count() }}
                    </p>

                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100">

                    <svg
                        class="h-5 w-5 text-slate-600"
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

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================
        TABLEAU
    ================================================================= --}}

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-slate-200">

                <thead class="bg-slate-50">

                    <tr>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            N°
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Campagne
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Préfecture
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Besoins
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Statut
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-200 bg-white">

                    @forelse($planifications as $planification)

                        @php

                            $statut = $planification->statut;

                            $classes = match ($statut) {

                                'brouillon' =>
                                    'bg-amber-100 text-amber-700',

                                'active' =>
                                    'bg-emerald-100 text-emerald-700',

                                'en_cours' =>
                                    'bg-blue-100 text-blue-700',

                                'cloturee' =>
                                    'bg-slate-100 text-slate-700',

                                'terminee' =>
                                    'bg-slate-100 text-slate-700',

                                default =>
                                    'bg-slate-100 text-slate-700',
                            };

                        @endphp


                        <tr class="transition hover:bg-slate-50">

                            {{-- N° --}}
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">

                                {{ $planifications->firstItem() + $loop->index }}

                            </td>


                            {{-- CAMPAGNE --}}
                            <td class="px-6 py-4">

                                <div>

                                    <p class="font-medium text-slate-900">
                                        {{ $planification->deploiement?->campagne?->libelle ?? '—' }}
                                    </p>

                                    @if($planification->deploiement?->campagne?->codeCampagne)

                                        <p class="mt-0.5 text-xs text-slate-500">
                                            {{ $planification->deploiement->campagne->codeCampagne }}
                                        </p>

                                    @endif

                                </div>

                            </td>


                            {{-- PREFECTURE --}}
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">

                                {{ $planification->deploiement?->prefecture?->nom ?? '—' }}

                            </td>


                            {{-- BESOINS --}}
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">

                                @if($planification->relationLoaded('besoins'))

                                    {{ $planification->besoins->count() }}

                                @else

                                    {{ $planification->besoins()->count() }}

                                @endif

                                besoin(s)

                            </td>


                            {{-- STATUT --}}
                            <td class="whitespace-nowrap px-6 py-4">

                                <span
                                    class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium {{ $classes }}"
                                >
                                    {{ ucfirst(str_replace('_', ' ', $statut ?? 'Non défini')) }}
                                </span>

                            </td>


                            {{-- ACTIONS --}}
                            <td class="whitespace-nowrap px-6 py-4 text-right">

                                <div class="flex items-center justify-end gap-2">

                                    <a
                                        href="{{ route('dpa.planifications-prefectorales.show', $planification->idPlanificationPrefectorale) }}"
                                        class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                                    >

                                        <svg
                                            class="mr-1.5 h-4 w-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            />

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                            />
                                        </svg>

                                        Voir

                                    </a>


                                    <a
                                        href="{{ route('dpa.planifications-prefectorales.edit', $planification->idPlanificationPrefectorale) }}"
                                        class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                                    >

                                        <svg
                                            class="mr-1.5 h-4 w-4"
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
                                                d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"
                                            />
                                        </svg>

                                        Modifier

                                    </a>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="px-6 py-12 text-center"
                            >

                                <div class="flex flex-col items-center">

                                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-100">

                                        <svg
                                            class="h-6 w-6 text-slate-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                            />
                                        </svg>

                                    </div>

                                    <p class="mt-3 font-medium text-slate-900">
                                        Aucune planification préfectorale
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Aucune planification n'a encore été créée.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}
        @if($planifications->hasPages())

            <div class="border-t border-slate-200 px-6 py-4">

                {{ $planifications->links() }}

            </div>

        @endif

    </div>

</div>

@endsection