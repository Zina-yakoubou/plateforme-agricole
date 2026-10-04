@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

{{-- =========================================================
     EN-TÊTE
========================================================== --}}
<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

    <div>

        <div class="flex items-center gap-2">

            <h1 class="text-2xl font-semibold tracking-tight text-[#212529]">
                Mes affectations
            </h1>

            <span class="rounded-full bg-[#e5f2ee] px-3 py-1
                         text-xs font-semibold text-[#006a4f]">
                SIRA-Mô
            </span>

        </div>

        <p class="mt-1 text-sm text-gray-500">
            Consultez les affectations qui vous ont été attribuées
            pour les campagnes de recensement.
        </p>

    </div>

</div>


{{-- =========================================================
     MESSAGE DE SUCCÈS
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


{{-- =========================================================
     MESSAGE D'ERREUR
========================================================== --}}
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
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
            />
        </svg>

        <span>{{ session('error') }}</span>

    </div>

@endif


{{-- =========================================================
     RECHERCHE
========================================================== --}}
<div class="rounded-lg border border-[#e5e7eb]
            bg-white p-4
            shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

    <form
        method="GET"
        action="{{ route('mes-affectations.index') }}"
    >

        <div class="flex flex-col gap-3 sm:flex-row">

            {{-- Recherche --}}
            <div class="relative flex-1">

                <svg
                    class="pointer-events-none absolute left-4 top-1/2
                           h-5 w-5 -translate-y-1/2 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="m21 21-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"
                    />
                </svg>

                <input
                    type="text"
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Rechercher une campagne, un village, un canton..."
                    class="w-full rounded-lg
                           border border-[#e5e7eb]
                           bg-white py-3 pl-11 pr-4
                           text-sm text-[#212529]
                           placeholder:text-gray-400
                           focus:border-[#006a4f]
                           focus:outline-none
                           focus:ring-2
                           focus:ring-[#006a4f]/10"
                >

            </div>


            {{-- Rechercher --}}
            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2
                       rounded-lg
                       bg-[#006a4f]
                       px-5 py-3
                       text-sm font-semibold text-white
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
                        d="m21 21-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"
                    />
                </svg>

                Rechercher

            </button>


            {{-- Réinitialiser --}}
            @if($search)

                <a
                    href="{{ route('agent.affectations') }}"
                    class="inline-flex items-center justify-center
                           rounded-lg
                           border border-[#e5e7eb]
                           bg-white
                           px-4 py-3
                           text-sm font-semibold text-gray-600
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
     LISTE DES AFFECTATIONS
========================================================== --}}
<div class="overflow-hidden rounded-lg
            border border-[#e5e7eb]
            bg-white
            shadow-[0_1px_2px_rgba(0,0,0,0.05)]">


    {{-- En-tête --}}
    <div class="flex flex-col gap-2
                border-b border-[#e5e7eb]
                px-6 py-5
                sm:flex-row sm:items-center
                sm:justify-between">

        <div>

            <h2 class="text-base font-semibold text-[#212529]">
                Affectations qui me sont attribuées
            </h2>

            <p class="mt-1 text-xs text-gray-500">
                Sélectionnez une affectation pour consulter les détails
                de votre zone de travail.
            </p>

        </div>


        <span class="rounded-full bg-[#f8faf9]
                     px-3 py-1.5 text-sm text-gray-500">

            <span class="font-semibold text-[#006a4f]">
                {{ $affectations->total() }}
            </span>

            affectation(s)

        </span>

    </div>


    @if($affectations->count())


        {{-- =====================================================
             TABLEAU
        ====================================================== --}}
        <div class="overflow-x-auto">

            <table class="w-full min-w-[950px] text-sm">

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
                            Campagne
                        </th>

                        <th class="px-6 py-4 text-left text-xs
                                   font-semibold uppercase tracking-wide
                                   text-gray-500">
                            Commune
                        </th>

                        <th class="px-6 py-4 text-left text-xs
                                   font-semibold uppercase tracking-wide
                                   text-gray-500">
                            Canton
                        </th>

                        <th class="px-6 py-4 text-left text-xs
                                   font-semibold uppercase tracking-wide
                                   text-gray-500">
                            Village
                        </th>

                        <th class="px-6 py-4 text-center text-xs
                                   font-semibold uppercase tracking-wide
                                   text-gray-500">
                            Statut
                        </th>

                        <th class="px-6 py-4 text-right text-xs
                                   font-semibold uppercase tracking-wide
                                   text-gray-500">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-[#e5e7eb]">

                    @foreach($affectations as $affectation)

                        <tr class="group transition hover:bg-[#f8faf9]">

                            {{-- =================================================
                                 N°
                            ================================================== --}}
                            <td class="px-6 py-4 text-sm text-gray-400">

                                {{
                                    $loop->iteration
                                    + (($affectations->currentPage() - 1)
                                    * $affectations->perPage())
                                }}

                            </td>


                            {{-- =================================================
                                 CAMPAGNE
                            ================================================== --}}
                            <td class="px-6 py-4">

                                <div>

                                    <div class="font-semibold text-[#212529]">

                                        {{ $affectation->campagne->libelle ?? '—' }}

                                    </div>

                                    @if($affectation->campagne?->codeCampagne)

                                        <div class="mt-1 font-mono text-xs
                                                    font-medium text-[#006a4f]">

                                            {{ $affectation->campagne->codeCampagne }}

                                        </div>

                                    @endif

                                </div>

                            </td>


                            {{-- =================================================
                                 COMMUNE
                            ================================================== --}}
                            <td class="px-6 py-4 text-gray-600">

                                {{ $affectation->village->canton->commune->nom ?? '—' }}

                            </td>


                            {{-- =================================================
                                 CANTON
                            ================================================== --}}
                            <td class="px-6 py-4 text-gray-600">

                                {{ $affectation->village->canton->nom ?? '—' }}

                            </td>


                            {{-- =================================================
                                 VILLAGE
                            ================================================== --}}
                            <td class="px-6 py-4">

                                <span class="font-medium text-[#212529]">

                                    {{ $affectation->village->nom ?? '—' }}

                                </span>

                            </td>


                            {{-- =================================================
                                 STATUT
                            ================================================== --}}
                            <td class="px-6 py-4 text-center">

                                @if(strtolower($affectation->statut) === 'active')

                                    <span class="inline-flex items-center gap-1.5
                                                 rounded-full
                                                 bg-green-50
                                                 px-3 py-1
                                                 text-xs font-semibold
                                                 text-green-700">

                                        <span class="h-1.5 w-1.5 rounded-full
                                                     bg-green-500">
                                        </span>

                                        Active

                                    </span>

                                @else

                                    <span class="inline-flex items-center gap-1.5
                                                 rounded-full
                                                 bg-gray-100
                                                 px-3 py-1
                                                 text-xs font-semibold
                                                 text-gray-600">

                                        <span class="h-1.5 w-1.5 rounded-full
                                                     bg-gray-400">
                                        </span>

                                        {{ ucfirst($affectation->statut) }}

                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 ACTION
                            ================================================== --}}
                            <td class="px-6 py-4 text-right">

                                <div class="flex items-center justify-end gap-2">

                                    {{-- DÉTAIL DE L'AFFECTATION --}}
                                    <a
                                        href="{{ route('mes-affectations.show', $affectation) }}"
                                        title="Voir l'affectation"
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
                                                stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-9.542-7z"
                                            />

                                            <circle
                                                cx="12"
                                                cy="12"
                                                r="3"
                                            />

                                        </svg>

                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        {{-- =====================================================
             PAGINATION
        ====================================================== --}}
        @if($affectations->hasPages())

            <div class="border-t border-[#e5e7eb] px-6 py-4">

                {{ $affectations->withQueryString()->links() }}

            </div>

        @endif


    @else


        {{-- =====================================================
             AUCUNE AFFECTATION
        ====================================================== --}}
        <div class="px-6 py-16 text-center">

            <div class="mx-auto mb-4 flex h-14 w-14
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
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z"
                    />
                </svg>

            </div>

            <h3 class="font-semibold text-[#212529]">
                Aucune affectation
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Vous n'avez actuellement aucune affectation
                pour une campagne de recensement.
            </p>

            @if($search)

                <a
                    href="{{ route('agent.affectations') }}"
                    class="mt-4 inline-block text-sm font-semibold
                           text-[#006a4f]
                           hover:underline"
                >
                    Réinitialiser la recherche
                </a>

            @endif

        </div>

    @endif

</div>


</div>

@endsection
