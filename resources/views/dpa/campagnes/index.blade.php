@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}

    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <h1 class="text-2xl font-semibold tracking-tight text-[#212529]">
                Campagnes de recensement
            </h1>

            <p class="mt-1 text-sm text-[#6b7280]">
                Campagnes de recensement déployées dans votre préfecture.
            </p>

        </div>


        {{-- PRÉFECTURE DU DPA --}}

        @if($prefecture)

            <div class="inline-flex items-center gap-3 rounded-lg
                        border border-[#e5e7eb]
                        bg-white
                        px-4 py-3
                        shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

                <div class="flex h-9 w-9 items-center justify-center
                            rounded-lg bg-[#e5f2ee] text-[#006a4f]">

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"
                        />

                        <circle
                            cx="12"
                            cy="11"
                            r="3"
                            stroke-width="1.8"
                        />
                    </svg>

                </div>

                <div>

                    <p class="text-[11px] text-gray-400">
                        Ma préfecture
                    </p>

                    <p class="text-sm font-semibold text-[#212529]">
                        {{ $prefecture->nom }}
                    </p>

                </div>

            </div>

        @endif

    </div>


    {{-- =========================================================
         MESSAGES
    ========================================================== --}}

    @if(session('success'))

        <div class="flex items-start gap-3 rounded-lg
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

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif


    @if(session('error'))

        <div class="flex items-start gap-3 rounded-lg
                    border border-red-200
                    bg-red-50
                    px-4 py-3
                    text-sm text-red-700">

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
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0"
                />
            </svg>

            <span>
                {{ session('error') }}
            </span>

        </div>

    @endif


    @if(session('info'))

        <div class="flex items-start gap-3 rounded-lg
                    border border-blue-200
                    bg-blue-50
                    px-4 py-3
                    text-sm text-blue-700">

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
                    d="M13 16h-1v-4h-1m1-4h.01M12 21a9 9 0 100-18"
                />
            </svg>

            <span>
                {{ session('info') }}
            </span>

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
            action="{{ route('dpa.campagnes.index') }}"
        >

            <div class="flex flex-col gap-3 sm:flex-row">

                <div class="relative flex-1">

                    <svg
                        class="pointer-events-none absolute left-4 top-1/2
                               h-5 w-5 -translate-y-1/2
                               text-gray-400"
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
                        placeholder="Rechercher une campagne..."
                        class="w-full rounded-lg
                               border border-[#e5e7eb]
                               bg-white
                               py-3 pl-11 pr-4
                               text-sm text-[#212529]
                               placeholder:text-gray-400
                               focus:border-[#006a4f]
                               focus:outline-none
                               focus:ring-2
                               focus:ring-[#006a4f]/10"
                    >

                </div>


                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2
                           rounded-lg
                           bg-[#006a4f]
                           px-5 py-3
                           text-sm font-semibold text-white
                           transition
                           hover:bg-[#156c52]"
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

            </div>

        </form>

    </div>


    {{-- =========================================================
         TABLEAU
    ========================================================== --}}

    <div class="overflow-hidden rounded-lg
                border border-[#e5e7eb]
                bg-white
                shadow-[0_1px_2px_rgba(0,0,0,0.05)]">


        {{-- EN-TÊTE DU TABLEAU --}}

        <div class="flex flex-col gap-1
                    border-b border-[#e5e7eb]
                    px-6 py-5
                    sm:flex-row sm:items-center sm:justify-between">

            <div>

                <h2 class="text-base font-semibold text-[#212529]">
                    Campagnes déployées
                </h2>

                <p class="mt-1 text-xs text-gray-500">
                    Campagnes transmises à votre préfecture
                </p>

            </div>


            {{-- CORRIGÉ : $deploiements et non $planifications --}}

            <div class="text-sm text-gray-500">

                <span class="font-semibold text-[#006a4f]">
                    {{ $deploiements->total() }}
                </span>

                campagne(s)

            </div>

        </div>


        {{-- =====================================================
             TABLE
        ====================================================== --}}

        <div class="overflow-x-auto">

            <table class="w-full min-w-[1100px] text-sm">

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
                            Code
                        </th>

                        <th class="px-6 py-4 text-left text-xs
                                   font-semibold uppercase tracking-wide
                                   text-gray-500">
                            Campagne
                        </th>

                        <th class="px-6 py-4 text-left text-xs
                                   font-semibold uppercase tracking-wide
                                   text-gray-500">
                            Portée
                        </th>

                        <th class="px-6 py-4 text-left text-xs
                                   font-semibold uppercase tracking-wide
                                   text-gray-500">
                            Période
                        </th>

                        <th class="px-6 py-4 text-center text-xs
                                   font-semibold uppercase tracking-wide
                                   text-gray-500">
                            Campagne
                        </th>

                        <th class="px-6 py-4 text-center text-xs
                                   font-semibold uppercase tracking-wide
                                   text-gray-500">
                            Réception
                        </th>

                        <th class="px-6 py-4 text-right text-xs
                                   font-semibold uppercase tracking-wide
                                   text-gray-500">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-[#e5e7eb]">

                    @forelse($deploiements as $deploiement)

                        @php

                            $campagne = $deploiement->campagne;

                            /*
                            |------------------------------------------------------------------
                            | STATUT CAMPAGNE
                            |------------------------------------------------------------------
                            */

                            $statutLabel = match($campagne?->statut) {

                                'planifiee' => 'Planifiée',

                                'active' => 'Active',

                                'cloturee' => 'Clôturée',

                                'archivee' => 'Archivée',

                                default => ucfirst($campagne?->statut ?? 'Inconnu'),

                            };


                            $statutClass = match($campagne?->statut) {

                                'planifiee'
                                    => 'bg-yellow-50 text-yellow-700',

                                'active'
                                    => 'bg-green-50 text-green-700',

                                'cloturee'
                                    => 'bg-gray-100 text-gray-600',

                                'archivee'
                                    => 'bg-slate-100 text-slate-600',

                                default
                                    => 'bg-gray-100 text-gray-600',

                            };


                            /*
                            |------------------------------------------------------------------
                            | PORTÉE
                            |------------------------------------------------------------------
                            */

                            $porteeLabel = match($campagne?->portee) {

                                'nationale' => 'Nationale',

                                'regionale' => 'Régionale',

                                'prefectorale' => 'Préfectorale',

                                default => 'Non définie',

                            };


                            $porteeClass = match($campagne?->portee) {

                                'nationale'
                                    => 'bg-blue-50 text-blue-700',

                                'regionale'
                                    => 'bg-purple-50 text-purple-700',

                                'prefectorale'
                                    => 'bg-[#e5f2ee] text-[#006a4f]',

                                default
                                    => 'bg-gray-100 text-gray-500',

                            };


                            /*
                            |------------------------------------------------------------------
                            | STATUT DÉPLOIEMENT
                            |------------------------------------------------------------------
                            */

                            $receptionnee =
                                $deploiement->statut === 'recue';

                            $enAttenteReception =
                                $deploiement->statut === 'notifiee';

                        @endphp


                        <tr class="group transition hover:bg-[#f8faf9]">


                            {{-- =================================================
                                 N°
                            ================================================== --}}

                            <td class="px-6 py-4 text-sm text-gray-400">

                                {{
                                    $loop->iteration
                                    + (($deploiements->currentPage() - 1)
                                    * $deploiements->perPage())
                                }}

                            </td>


                            {{-- =================================================
                                 CODE
                            ================================================== --}}

                            <td class="px-6 py-4">

                                <span class="font-semibold text-[#006a4f]">
                                    {{ $campagne?->codeCampagne ?? '—' }}
                                </span>

                            </td>


                            {{-- =================================================
                                 CAMPAGNE
                            ================================================== --}}

                            <td class="px-6 py-4">

                                <div class="font-semibold text-[#212529]">
                                    {{ $campagne?->libelle ?? 'Campagne inconnue' }}
                                </div>

                                @if($campagne?->description)

                                    <div class="mt-1 max-w-xs truncate text-xs text-gray-400">
                                        {{ $campagne->description }}
                                    </div>

                                @endif

                            </td>


                            {{-- =================================================
                                 PORTÉE
                            ================================================== --}}

                            <td class="px-6 py-4">

                                <span
                                    class="inline-flex rounded-full
                                           px-3 py-1
                                           text-xs font-semibold
                                           {{ $porteeClass }}"
                                >
                                    {{ $porteeLabel }}
                                </span>

                            </td>


                            {{-- =================================================
                                 PÉRIODE
                            ================================================== --}}

                            <td class="px-6 py-4 text-[#434343]">

                                <div class="font-medium">

                                    {{ $campagne?->dateDebut?->format('d/m/Y') ?? '—' }}

                                </div>

                                <div class="mt-1 text-xs text-gray-400">

                                    jusqu'au

                                    {{ $campagne?->dateFin?->format('d/m/Y') ?? 'Non définie' }}

                                </div>

                            </td>


                            {{-- =================================================
                                 STATUT CAMPAGNE
                            ================================================== --}}

                            <td class="px-6 py-4 text-center">

                                <span
                                    class="inline-flex items-center gap-1.5
                                           rounded-full
                                           px-3 py-1
                                           text-xs font-semibold
                                           {{ $statutClass }}"
                                >

                                    <span
                                        class="h-1.5 w-1.5 rounded-full bg-current"
                                    ></span>

                                    {{ $statutLabel }}

                                </span>

                            </td>


                            {{-- =================================================
                                 RÉCEPTION
                            ================================================== --}}

                            <td class="px-6 py-4 text-center">

                                @if($receptionnee)

                                    <div class="inline-flex flex-col items-center">

                                        <span
                                            class="inline-flex items-center gap-1.5
                                                   rounded-full
                                                   bg-[#e5f2ee]
                                                   px-3 py-1
                                                   text-xs font-semibold
                                                   text-[#006a4f]"
                                        >

                                            <span
                                                class="h-1.5 w-1.5
                                                       rounded-full
                                                       bg-[#006a4f]"
                                            ></span>

                                            Réceptionnée

                                        </span>


                                        @if($deploiement->dateReception)

                                            <div class="mt-1 text-[11px] text-gray-400">

                                                {{ $deploiement->dateReception->format('d/m/Y H:i') }}

                                            </div>

                                        @endif

                                    </div>

                                @else

                                    <span
                                        class="inline-flex items-center gap-1.5
                                               rounded-full
                                               bg-orange-50
                                               px-3 py-1
                                               text-xs font-semibold
                                               text-orange-700"
                                    >

                                        <span
                                            class="h-1.5 w-1.5
                                                   rounded-full
                                                   bg-orange-500"
                                        ></span>

                                        En attente

                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 ACTIONS
                            ================================================== --}}

                            <td class="px-6 py-4">

                                <div class="flex items-center justify-end gap-2">


                                    {{-- =================================================
                                         VOIR
                                    ================================================== --}}

                                    <a
                                        href="{{ route(
                                            'dpa.campagnes.show',
                                            $deploiement
                                        ) }}"
                                        title="Voir les détails"
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
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                            />

                                            <circle
                                                cx="12"
                                                cy="12"
                                                r="3"
                                            />

                                        </svg>

                                    </a>


                                    {{-- =================================================
                                         RÉCEPTION
                                    ================================================== --}}

                                    @if($enAttenteReception)

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'dpa.campagnes.receive',
                                                $deploiement
                                            ) }}"
                                            class="inline-flex"
                                        >

                                            @csrf

                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                title="Accuser réception"
                                                class="inline-flex h-9
                                                       items-center justify-center
                                                       gap-2
                                                       rounded-lg
                                                       bg-[#006a4f]
                                                       px-3
                                                       text-xs font-semibold
                                                       text-white
                                                       transition
                                                       hover:bg-[#156c52]
                                                       focus:outline-none
                                                       focus:ring-2
                                                       focus:ring-[#006a4f]/20"
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
                                                        d="M5 13l4 4L19 7"
                                                    />

                                                </svg>

                                                Réceptionner

                                            </button>

                                        </form>


                                    @elseif($receptionnee)

                                        <span
                                            title="Campagne réceptionnée"
                                            class="inline-flex h-9 w-9
                                                   items-center justify-center
                                                   rounded-lg
                                                   border border-[#b7dfd2]
                                                   bg-[#e5f2ee]
                                                   text-[#006a4f]"
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
                                                    d="M5 13l4 4L19 7"
                                                />

                                            </svg>

                                        </span>

                                    @endif


                                    {{-- =================================================
                                         PLANIFICATION PRÉFECTORALE
                                    ================================================== --}}

                                    @if($receptionnee)

                                        @if(!$deploiement->planificationPrefectorale)

                                            {{-- PLANIFIER --}}

                                            <a
                                                href="{{ route(
                                                    'dpa.planifications-prefectorales.create',
                                                    $deploiement
                                                ) }}"
                                                title="Planifier la campagne"
                                                class="inline-flex h-9 w-9
                                                       items-center justify-center
                                                       rounded-lg
                                                       bg-[#006a4f]
                                                       text-white
                                                       transition
                                                       hover:bg-[#156c52]
                                                       focus:outline-none
                                                       focus:ring-2
                                                       focus:ring-[#006a4f]/20"
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
                                                        d="M12 4v16m8-8H4"
                                                    />

                                                </svg>

                                            </a>

                                        @else

                                            {{-- MODIFIER LA PLANIFICATION --}}

                                            <a
                                                href="{{ route(
                                                    'dpa.planifications-prefectorales.edit',
                                                    $deploiement->planificationPrefectorale
                                                ) }}"
                                                title="Modifier la planification"
                                                class="inline-flex h-9 w-9
                                                       items-center justify-center
                                                       rounded-lg
                                                       border border-[#006a4f]
                                                       bg-white
                                                       text-[#006a4f]
                                                       transition
                                                       hover:bg-[#e5f2ee]"
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
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                                                        M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"
                                                    />
                                                </svg>

                                            </a>

                                        @endif

                                    @endif

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="px-6 py-16 text-center"
                            >

                                <div class="flex flex-col items-center">

                                    <div
                                        class="mb-4 flex h-14 w-14
                                               items-center justify-center
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
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z"
                                            />

                                        </svg>

                                    </div>


                                    <p class="font-semibold text-[#212529]">
                                        Aucune campagne trouvée
                                    </p>


                                    @if($search)

                                        <p class="mt-1 text-sm text-gray-500">
                                            Aucune campagne ne correspond à votre recherche.
                                        </p>

                                    @else

                                        <p class="mt-1 text-sm text-gray-500">
                                            Aucune campagne n'a encore été déployée
                                            auprès de votre préfecture.
                                        </p>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- =========================================================
         PAGINATION
    ========================================================== --}}

    @if($deploiements->hasPages())

        <div class="flex justify-center pt-2">

            {{ $deploiements->links() }}

        </div>

    @endif

</div>

@endsection