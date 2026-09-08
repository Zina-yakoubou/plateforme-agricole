@extends('layouts.app')

@section('page-title', 'Affectations')

@section('page-subtitle', 'Suivi des équipes affectées aux territoires de la campagne')

@section('content')

<div class="space-y-6">

    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}
    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <div class="flex items-center gap-2">

                <h1 class="text-2xl font-semibold tracking-tight text-[#212529]">
                    Affectations
                </h1>

                <span class="rounded-full bg-[#e5f2ee] px-3 py-1
                             text-xs font-semibold text-[#006a4f]">
                    SIRA-Mô
                </span>

            </div>

            <p class="mt-1 text-sm text-[#6b7280]">
                Consultez les équipes affectées aux différents territoires
                des campagnes de recensement.
            </p>

        </div>

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
         FILTRES
    ========================================================== --}}

    <div class="rounded-lg
                border border-[#e5e7eb]
                bg-white
                p-4
                shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

        <form
            method="GET"
            action="{{ route('dpa.affectations.index') }}"
        >

            <div class="grid grid-cols-1 gap-3 md:grid-cols-3">

                {{-- =================================================
                     CAMPAGNE
                ================================================== --}}

                <div>

                    <label
                        for="campagne_id"
                        class="mb-1.5 block text-sm font-medium text-[#212529]"
                    >
                        Campagne
                    </label>

                    <select
                        id="campagne_id"
                        name="campagne_id"
                        class="w-full rounded-lg
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
                                @selected(
                                    (string) request('campagne_id')
                                    === (string) $campagne->idCampagne
                                )
                            >
                                {{ $campagne->libelle }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- =================================================
                     STATUT
                ================================================== --}}

                <div>

                    <label
                        for="statut"
                        class="mb-1.5 block text-sm font-medium text-[#212529]"
                    >
                        Statut
                    </label>

                    <select
                        id="statut"
                        name="statut"
                        class="w-full rounded-lg
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


                {{-- =================================================
                     BOUTONS
                ================================================== --}}

                <div class="flex items-end gap-2">

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
                                d="m21 21-4.35-4.35
                                   m2.35-5.65a8 8 0 11-16 0
                                   8 8 0 0116 0z"
                            />
                        </svg>

                        Filtrer

                    </button>


                    @if(
                        request()->filled('campagne_id')
                        || request()->filled('statut')
                    )

                        <a
                            href="{{ route('dpa.affectations.index') }}"
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

            </div>

        </form>

    </div>


    {{-- =========================================================
         TABLEAU DES AFFECTATIONS
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
                    Affectations des équipes
                </h2>

                <p class="mt-1 text-xs text-gray-500">
                    Équipes affectées aux territoires des campagnes.
                </p>

            </div>


            <div class="text-sm text-gray-500">

                <span class="font-semibold text-[#006a4f]">
                    {{ $affectations->total() }}
                </span>

                affectation(s)

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
                            Affectation
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
                            Campagne
                        </th>


                        <th class="px-6 py-4 text-left
                                   text-xs font-semibold
                                   uppercase tracking-wide
                                   text-gray-500">
                            Territoire
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

                    @forelse($affectations as $affectation)

                        <tr class="group transition hover:bg-[#f8faf9]">


                            {{-- =================================================
                                 N°
                            ================================================== --}}

                            <td class="px-6 py-4 text-sm text-gray-400">

                                {{
                                    $loop->iteration
                                    + (
                                        ($affectations->currentPage() - 1)
                                        * $affectations->perPage()
                                    )
                                }}

                            </td>


                            {{-- =================================================
                                 AFFECTATION
                            ================================================== --}}

                            <td class="px-6 py-4">

                                <div class="min-w-0">

                                    <div class="truncate font-semibold text-[#212529]">

                                        {{ $affectation->reference }}

                                    </div>

                                    <div class="mt-1 font-mono text-xs
                                                font-medium text-[#006a4f]">

                                        #{{ $affectation->idAffectation }}

                                    </div>

                                    @if($affectation->dateDebut)

                                        <div class="mt-1 text-xs text-gray-400">

                                            Début :
                                            {{ \Carbon\Carbon::parse($affectation->dateDebut)->format('d/m/Y') }}

                                        </div>

                                    @endif

                                </div>

                            </td>


                            {{-- =================================================
                                 ÉQUIPE
                            ================================================== --}}

                            <td class="px-6 py-4">

                                @if($affectation->equipe)

                                    <div class="min-w-0">

                                        <div class="truncate font-semibold text-[#212529]">

                                            {{ $affectation->equipe->libelle }}

                                        </div>


                                        @if($affectation->equipe->reference)

                                            <div class="mt-1 font-mono text-xs
                                                        font-medium text-[#006a4f]">

                                                {{ $affectation->equipe->nom }}

                                            </div>

                                        @endif


                                        @if($affectation->equipe->superviseur)

                                            <div class="mt-1 text-xs text-gray-500">

                                                Superviseur :
                                                <span class="font-medium text-gray-600">
                                                    {{ $affectation->equipe->superviseur->name }}
                                                </span>

                                            </div>

                                        @endif

                                    </div>

                                @else

                                    <span class="text-sm text-gray-400">
                                        Équipe non renseignée
                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 CAMPAGNE
                            ================================================== --}}

                            <td class="px-6 py-4">

                                @if($affectation->campagne)

                                    <div class="min-w-0">

                                        <div class="truncate font-semibold text-[#212529]">

                                            {{ $affectation->campagne->libelle }}

                                        </div>


                                        {{-- @if($affectation->campagne->codeCampagne)

                                            <div class="mt-1 font-mono text-xs
                                                        font-medium text-[#006a4f]">

                                                {{ $affectation->campagne->codeCampagne }}

                                            </div>

                                        @endif
 --}}
                                    </div>

                                @else

                                    <span class="text-sm text-gray-400">
                                        Campagne non renseignée
                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 TERRITOIRE
                            ================================================== --}}

                            <td class="px-6 py-4">

                                @if($affectation->village)

                                    <div class="min-w-0">

                                        {{-- Village --}}

                                        <div class="font-semibold text-[#212529]">

                                            {{ $affectation->village->nom }}

                                        </div>


                                        {{-- Canton --}}

                                        @if($affectation->village->canton)

                                            <div class="mt-1 text-xs text-gray-500">

                                                Canton :
                                                <span class="font-medium text-gray-600">
                                                    {{ $affectation->village->canton->nom }}
                                                </span>

                                            </div>

                                        @endif


                                        {{-- Commune --}}

                                        @if($affectation->village->canton?->commune)

                                            <div class="mt-0.5 text-xs text-gray-400">

                                                Commune :
                                                {{ $affectation->village->canton->commune->nom }}

                                            </div>

                                        @endif

                                    </div>

                                @else

                                    <span class="text-sm text-gray-400">
                                        Territoire non renseigné
                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 STATUT
                            ================================================== --}}

                            <td class="px-6 py-4 text-center">

                                @switch($affectation->statut)

                                    @case('active')

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

                                        @break


                                    @case('annulee')

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

                                            Annulée

                                        </span>

                                        @break


                                    @case('cloturee')

                                        <span class="inline-flex
                                                     items-center
                                                     gap-1.5
                                                     rounded-full
                                                     bg-gray-100
                                                     px-3 py-1
                                                     text-xs font-semibold
                                                     text-gray-700">

                                            <span class="h-1.5 w-1.5
                                                         rounded-full
                                                         bg-gray-500">
                                            </span>

                                            Clôturée

                                        </span>

                                        @break


                                    @default

                                        <span class="inline-flex
                                                     items-center
                                                     rounded-full
                                                     bg-gray-100
                                                     px-3 py-1
                                                     text-xs font-semibold
                                                     text-gray-600">

                                            {{ ucfirst($affectation->statut) }}

                                        </span>

                                @endswitch

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
                                        href="{{ route('dpa.affectations.show', $affectation) }}"
                                        title="Voir l'affectation"
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


                                    {{-- =================================================
                                         ANNULER
                                    ================================================== --}}

                                    {{-- @if($affectation->statut === 'active')

                                        <form
                                            method="POST"
                                            action="{{ route('dpa.affectations.destroy', $affectation) }}"
                                            onsubmit="return confirm(
                                                'Voulez-vous vraiment annuler cette affectation ?'
                                            )"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                title="Annuler l'affectation"
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


                                    @if($affectation->statut === 'active')

                                    {{-- ANNULER --}}
                                    <form
                                        method="POST"
                                        action="{{ route('dpa.affectations.destroy', $affectation) }}"
                                        onsubmit="return confirm('Voulez-vous vraiment annuler cette affectation ?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            title="Annuler l'affectation"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-red-200 bg-red-50 text-red-600 transition hover:bg-red-100"
                                        >
                                            {{-- icône --}}
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </form>

                                @else

                                    {{-- RÉACTIVER --}}
                                    <form
                                        method="POST"
                                        action="{{ route('dpa.affectations.reactiver', $affectation) }}"
                                        onsubmit="return confirm('Voulez-vous réactiver cette affectation ?')"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            title="Réactiver l'affectation"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-green-200 bg-green-50 text-green-600 transition hover:bg-green-100"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M4 4v5h5M20 20v-5h-5M5.5 9A7 7 0 0117 5.5L20 9M19 15a7 7 0 01-11.5 3.5L4 15"/>
                                            </svg>
                                        </button>
                                    </form>

                                @endif

                                </div>

                            </td>

                        </tr>


                    @empty

                        {{-- =================================================
                             AUCUNE AFFECTATION
                        ================================================== --}}

                        <tr>

                            <td
                                colspan="7"
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
                                                d="M9 5H7a2 2 0 00-2 2v11
                                                   a2 2 0 002 2h10
                                                   a2 2 0 002-2V7
                                                   a2 2 0 00-2-2h-2
                                                   M9 3h6v4H9V3z
                                                   M8 12l2 2 4-4
                                                   M8 17l2 2 4-4"
                                            />

                                        </svg>

                                    </div>


                                    <p class="font-semibold text-[#212529]">
                                        Aucune affectation trouvée
                                    </p>


                                    <p class="mt-1 text-sm text-gray-500">

                                        @if(
                                            request()->filled('campagne_id')
                                            || request()->filled('statut')
                                        )

                                            Aucune affectation ne correspond
                                            aux critères sélectionnés.

                                        @else

                                            Aucune équipe n'a encore été affectée
                                            à une campagne.

                                        @endif

                                    </p>


                                    @if(
                                        request()->filled('campagne_id')
                                        || request()->filled('statut')
                                    )

                                        <a
                                            href="{{ route('dpa.affectations.index') }}"
                                            class="mt-4 text-sm font-semibold
                                                   text-[#006a4f]
                                                   hover:underline"
                                        >
                                            Réinitialiser les filtres
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

        @if($affectations->hasPages())

            <div class="border-t border-[#e5e7eb] px-6 py-4">

                {{ $affectations->withQueryString()->links() }}

            </div>

        @endif

    </div>

</div>

@endsection