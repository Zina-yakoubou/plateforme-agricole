@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Planification des campagnes
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                @if($prefecture)
                    Campagnes réceptionnées de la préfecture de
                    <span class="font-semibold text-gray-700">
                        {{ $prefecture->nomPrefecture ?? $prefecture->libelle ?? 'Préfecture' }}
                    </span>
                @else
                    Gestion des campagnes à planifier
                @endif
            </p>
        </div>

    </div>


    {{-- =========================================================
         MESSAGES
    ========================================================== --}}

    @if(session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    @if(session('info'))
        <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">
            {{ session('info') }}
        </div>
    @endif


    {{-- =========================================================
         RECHERCHE
    ========================================================== --}}

    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">

        <form
            method="GET"
            action="{{ route('dpa.planification.index') }}"
            class="flex flex-col gap-3 md:flex-row"
        >

            <div class="flex-1">

                <label
                    for="search"
                    class="mb-1 block text-sm font-medium text-gray-700"
                >
                    Rechercher une campagne
                </label>

                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Code ou libellé de la campagne..."
                    class="w-full rounded-lg border-gray-300 px-4 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                >

            </div>

            <div class="flex items-end gap-2">

                <button
                    type="submit"
                    class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700"
                >
                    Rechercher
                </button>

                @if($search)
                    <a
                        href="{{ route('dpa.planification.index') }}"
                        class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                    >
                        Réinitialiser
                    </a>
                @endif

            </div>

        </form>

    </div>


    {{-- =========================================================
         LISTE
    ========================================================== --}}

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        {{-- En-tête tableau --}}

        <div class="border-b border-gray-200 px-5 py-4">

            <div class="flex items-center justify-between">

                <div>
                    <h2 class="font-semibold text-gray-800">
                        Campagnes réceptionnées
                    </h2>

                    <p class="mt-1 text-xs text-gray-500">
                        {{ $deploiements->total() }}
                        campagne(s)
                    </p>
                </div>

            </div>

        </div>


        {{-- =====================================================
             TABLEAU
        ====================================================== --}}

        @if($deploiements->count())

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Campagne
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Réception
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Planification
                            </th>

                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-200 bg-white">

                        @foreach($deploiements as $deploiement)

                            @php
                                $campagne = $deploiement->campagne;

                                $planification = $campagne?->planifications
                                    ?->firstWhere('prefecture_id', $deploiement->prefecture_id);
                            @endphp

                            <tr class="transition hover:bg-gray-50">

                                {{-- ===============================
                                     CAMPAGNE
                                ================================ --}}

                                <td class="px-5 py-4">

                                    <div class="font-semibold text-gray-800">
                                        {{ $campagne->libelle ?? 'Campagne sans libellé' }}
                                    </div>

                                    @if($campagne?->codeCampagne)

                                        <div class="mt-1 text-xs text-gray-500">
                                            {{ $campagne->codeCampagne }}
                                        </div>

                                    @endif

                                </td>


                                {{-- ===============================
                                     RÉCEPTION
                                ================================ --}}

                                <td class="px-5 py-4">

                                    <div class="text-sm text-gray-700">

                                        @if($deploiement->dateReception)

                                            {{ \Carbon\Carbon::parse($deploiement->dateReception)->format('d/m/Y H:i') }}

                                        @else

                                            —

                                        @endif

                                    </div>

                                    @if($deploiement->recuPar)

                                        <div class="mt-1 text-xs text-gray-500">
                                            Reçue par
                                            {{ $deploiement->recuPar->name }}
                                        </div>

                                    @endif

                                </td>


                                {{-- ===============================
                                     STATUT PLANIFICATION
                                ================================ --}}

                                <td class="px-5 py-4">

                                    @if($planification)

                                        @switch($planification->statut)

                                            @case('brouillon')

                                                <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                                    Brouillon
                                                </span>

                                                @break

                                            @case('planifiee')

                                                <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                                    Planifiée
                                                </span>

                                                @break

                                            @case('validee')

                                                <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                                    Validée
                                                </span>

                                                @break

                                            @case('executee')

                                                <span class="inline-flex rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700">
                                                    Exécutée
                                                </span>

                                                @break

                                            @default

                                                <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                                                    {{ ucfirst($planification->statut) }}
                                                </span>

                                        @endswitch

                                    @else

                                        <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                            Non planifiée
                                        </span>

                                    @endif

                                </td>


                                {{-- ===============================
                                     ACTION
                                ================================ --}}

                                <td class="px-5 py-4 text-right">

                                    @if($planification)

                                        <a
                                            href="{{ route('dpa.planification.show', $planification) }}"
                                            class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                                        >
                                            Voir
                                        </a>

                                    @else

                                        <a
                                            href="{{ route('dpa.planification.create', $deploiement) }}"
                                            class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700"
                                        >
                                            Planifier
                                        </a>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- =================================================
                 PAGINATION
            ================================================== --}}

            @if($deploiements->hasPages())

                <div class="border-t border-gray-200 px-5 py-4">

                    {{ $deploiements->links() }}

                </div>

            @endif


        @else

            {{-- =================================================
                 AUCUNE CAMPAGNE
            ================================================== --}}

            <div class="px-5 py-16 text-center">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100">

                    <svg
                        class="h-7 w-7 text-gray-400"
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

                <h3 class="mt-4 text-sm font-semibold text-gray-800">
                    Aucune campagne à planifier
                </h3>

                <p class="mx-auto mt-1 max-w-md text-sm text-gray-500">

                    @if($search)

                        Aucune campagne ne correspond à votre recherche.

                    @else

                        Aucune campagne réceptionnée n'est actuellement disponible pour la planification.

                    @endif

                </p>

                @if($search)

                    <a
                        href="{{ route('dpa.planification.index') }}"
                        class="mt-4 inline-flex rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                    >
                        Voir toutes les campagnes
                    </a>

                @endif

            </div>

        @endif

    </div>

</div>

@endsection