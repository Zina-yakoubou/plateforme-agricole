@extends('layouts.app')

@section('page-title', 'Mes zones')

@section(
'page-subtitle',
'Villages et zones actuellement affectés aux équipes que vous supervisez'
)

@section('content')

<div class="mx-auto max-w-7xl space-y-6">


{{-- ============================================================
    EN-TÊTE
============================================================= --}}
<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

    <div>
        <h2 class="text-xl font-semibold text-gray-900">
            Mes zones
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Consultez les villages affectés à vos équipes.
        </p>
    </div>

    <div class="rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
        <span class="font-semibold">
            {{ $affectations->total() }}
        </span>
        zone(s)
    </div>

</div>


{{-- ============================================================
    RECHERCHE
============================================================= --}}
<div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

    <form
        method="GET"
        action="{{ route('superviseur.zones.index') }}"
        class="flex flex-col gap-3 sm:flex-row"
    >

        <div class="relative flex-1">

            <svg
                class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="m21 21-4.35-4.35m1.35-5.65a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"
                />
            </svg>

            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Rechercher un village, une commune, une équipe..."
                class="w-full rounded-lg border border-gray-300 py-2.5 pl-10 pr-4
                       text-sm focus:border-green-600 focus:outline-none
                       focus:ring-2 focus:ring-green-100"
            >

        </div>

        <button
            type="submit"
            class="rounded-lg bg-[#006a4f] px-5 py-2.5 text-sm
                   font-medium text-white transition hover:bg-green-800"
        >
            Rechercher
        </button>

        @if($search !== '')
            <a
                href="{{ route('superviseur.zones.index') }}"
                class="rounded-lg border border-gray-300 px-5 py-2.5
                       text-center text-sm font-medium text-gray-700
                       hover:bg-gray-50"
            >
                Réinitialiser
            </a>
        @endif

    </form>

</div>


{{-- ============================================================
    LISTE DES ZONES
============================================================= --}}
<div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

    @if($affectations->count())

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-6 py-3 text-left text-xs font-semibold
                                   uppercase tracking-wider text-gray-500">
                            Village
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold
                                   uppercase tracking-wider text-gray-500">
                            Localisation
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold
                                   uppercase tracking-wider text-gray-500">
                            Équipe
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold
                                   uppercase tracking-wider text-gray-500">
                            Campagne
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-semibold
                                   uppercase tracking-wider text-gray-500">
                            Action
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-100 bg-white">

                    @foreach($affectations as $affectation)

                        @php
                            $village = $affectation->village;
                            $canton = $village?->canton;
                            $commune = $canton?->commune;
                            $equipe = $affectation->equipe;
                            $campagne = $affectation->campagne;
                        @endphp

                        <tr class="transition hover:bg-gray-50">

                            {{-- VILLAGE --}}
                            <td class="px-6 py-4">

                                <div class="font-medium text-gray-900">
                                    {{ $village?->nom ?? 'Village non renseigné' }}
                                </div>

                                @if($village?->code)
                                    <div class="mt-1 text-xs text-gray-400">
                                        {{ $village->code }}
                                    </div>
                                @endif

                            </td>


                            {{-- LOCALISATION --}}
                            <td class="px-6 py-4">

                                <div class="text-sm text-gray-700">
                                    {{ $commune?->nom ?? '—' }}
                                </div>

                                <div class="mt-1 text-xs text-gray-500">
                                    Canton :
                                    {{ $canton?->nom ?? '—' }}
                                </div>

                            </td>


                            {{-- ÉQUIPE --}}
                            <td class="px-6 py-4">

                                @if($equipe)

                                    <div class="font-medium text-gray-900">
                                        {{ $equipe->libelle ?? $equipe->reference }}
                                    </div>

                                    @if($equipe->reference)
                                        <div class="mt-1 text-xs text-gray-400">
                                            {{ $equipe->reference }}
                                        </div>
                                    @endif

                                @else

                                    <span class="text-sm text-gray-400">
                                        Équipe non renseignée
                                    </span>

                                @endif

                            </td>


                            {{-- CAMPAGNE --}}
                            <td class="px-6 py-4">

                                @if($campagne)

                                    <div class="text-sm font-medium text-gray-800">
                                        {{ $campagne->libelle }}
                                    </div>

                                    @if($campagne->codeCampagne)
                                        <div class="mt-1 text-xs text-gray-400">
                                            {{ $campagne->codeCampagne }}
                                        </div>
                                    @endif

                                @else

                                    <span class="text-sm text-gray-400">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- ACTION --}}
                            <td class="px-6 py-4 text-right">

                                <a
                                    href="{{ route(
                                        'superviseur.zones.show',
                                        $affectation
                                    ) }}"
                                    class="inline-flex items-center gap-2 rounded-lg
                                           bg-green-50 px-3 py-2 text-sm font-medium
                                           text-green-700 transition
                                           hover:bg-green-100"
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
                                            d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"
                                        />

                                        <circle
                                            cx="12"
                                            cy="12"
                                            r="2.5"
                                            stroke-width="1.8"
                                        />
                                    </svg>


                                </a>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        {{-- ====================================================
            PAGINATION
        ===================================================== --}}
        @if($affectations->hasPages())

            <div class="border-t border-gray-200 px-6 py-4">

                {{ $affectations->links() }}

            </div>

        @endif

    @else

        {{-- ====================================================
            AUCUNE ZONE
        ===================================================== --}}
        <div class="px-6 py-16 text-center">

            <div class="mx-auto flex h-14 w-14 items-center justify-center
                        rounded-full bg-gray-100">

                <svg
                    class="h-7 w-7 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 21s7-5.2 7-11a7 7 0 1 0-14 0c0 5.8 7 11 7 11Z"
                    />

                    <circle
                        cx="12"
                        cy="10"
                        r="2.5"
                        stroke-width="1.8"
                    />
                </svg>

            </div>

            @if($search !== '')

                <h3 class="mt-4 text-sm font-semibold text-gray-900">
                    Aucun résultat
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Aucune zone ne correspond à votre recherche.
                </p>

                <a
                    href="{{ route('superviseur.zones.index') }}"
                    class="mt-4 inline-flex rounded-lg bg-[#006a4f]
                           px-4 py-2 text-sm font-medium text-white
                           hover:bg-green-800"
                >
                    Réinitialiser la recherche
                </a>

            @else

                <h3 class="mt-4 text-sm font-semibold text-gray-900">
                    Aucune zone affectée
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Vous ne disposez actuellement d'aucun village
                    affecté à vos équipes.
                </p>

            @endif

        </div>

    @endif

</div>


</div>

@endsection
