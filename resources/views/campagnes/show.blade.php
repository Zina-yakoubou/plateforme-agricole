@extends('layouts.app')

@section('content')

<div class="mx-auto max-w-7xl space-y-6">

    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <div class="flex flex-wrap items-center gap-3">

                <h1 class="text-2xl font-bold text-slate-800">
                    {{ $campagne->libelle }}
                </h1>

                {{-- STATUT --}}
                @switch($campagne->statut)

                    @case('planifiee')

                        <span class="inline-flex items-center gap-1.5 rounded-full
                                     bg-yellow-50 px-3 py-1
                                     text-xs font-semibold text-yellow-700">

                            <span class="h-1.5 w-1.5 rounded-full bg-yellow-500"></span>

                            Planifiée
                        </span>

                        @break

                    @case('active')

                        <span class="inline-flex items-center gap-1.5 rounded-full
                                     bg-green-50 px-3 py-1
                                     text-xs font-semibold text-green-700">

                            <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>

                            Active
                        </span>

                        @break

                    @case('cloturee')

                        <span class="inline-flex items-center gap-1.5 rounded-full
                                     bg-gray-100 px-3 py-1
                                     text-xs font-semibold text-gray-600">

                            <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>

                            Clôturée
                        </span>

                        @break

                    @case('archivee')

                        <span class="inline-flex items-center gap-1.5 rounded-full
                                     bg-slate-100 px-3 py-1
                                     text-xs font-semibold text-slate-600">

                            <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>

                            Archivée
                        </span>

                        @break

                    @default

                        <span class="inline-flex rounded-full
                                     bg-red-50 px-3 py-1
                                     text-xs font-semibold text-red-600">

                            Statut inconnu

                        </span>

                @endswitch

            </div>

            <p class="mt-1 text-sm text-gray-500">
                Détails et suivi de la campagne de recensement.
            </p>

        </div>


        {{-- =====================================================
             ACTIONS ADMIN
        ====================================================== --}}
        <div class="flex flex-wrap gap-2">

            {{-- MODIFIER --}}
            @if(in_array($campagne->statut, ['planifiee', 'active']))

                <a
                    href="{{ route('campagnes.edit', $campagne) }}"
                    class="inline-flex items-center gap-2 rounded-lg
                            bg-green-600 px-4 py-2.5
                           text-sm font-semibold text-white
                           transition hover:bg-blue-700"
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
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.5-9.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 8.5-8.5z"
                        />
                    </svg>

                    Modifier

                </a>

            @endif


            {{-- DÉPLOYER --}}
            @if($campagne->statut === 'planifiee')

                <form
                    method="POST"
                    action="{{ route('campagnes.deploy', $campagne) }}"
                    onsubmit="return confirm(
                        'Voulez-vous déployer cette campagne vers les préfectures concernées ?'
                    )"
                >

                    @csrf

                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-lg
                               bg-[#006a4f] px-4 py-2.5
                               text-sm font-semibold text-white
                               transition hover:bg-[#156c52]"
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
                                d="M5 12h14M13 6l6 6-6 6"
                            />
                        </svg>

                        Déployer

                    </button>

                </form>

            @endif
@if(!$campagne->planification)

    <a href="{{ route('campagnes.planification.create', $campagne) }}"
       class="inline-flex items-center gap-2 rounded-lg  bg-green-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
        Planifier la campagne
    </a>

@elseif($campagne->statut === 'planifiee')

    <a href="{{ route('campagnes.planification.show', $campagne) }}"
       class="inline-flex items-center gap-2 rounded-lg  bg-green-500 px-4 py-2.5 text-sm font-semibold text-white hover:bg-sky-700">
        Voir la planification
    </a>

@endif

            {{-- CLÔTURER --}}
            @if($campagne->statut === 'active')

                <form
                    method="POST"
                    action="{{ route('campagnes.close', $campagne) }}"
                    onsubmit="return confirm(
                        'Voulez-vous vraiment clôturer cette campagne ?'
                    )"
                >

                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-lg
                               bg-orange-500 px-4 py-2.5
                               text-sm font-semibold text-white
                               transition hover:bg-orange-600"
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
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>

                        Clôturer

                    </button>

                </form>

            @endif


            {{-- ARCHIVER --}}
            @if($campagne->statut === 'cloturee')

                <form
                    method="POST"
                    action="{{ route('campagnes.archive', $campagne) }}"
                    onsubmit="return confirm(
                        'Voulez-vous vraiment archiver cette campagne ?'
                    )"
                >

                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-lg
                               bg-gray-600 px-4 py-2.5
                               text-sm font-semibold text-white
                               transition hover:bg-gray-700"
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
                                d="M5 8h14M10 12h4m-6 4h8"
                            />
                        </svg>

                        Archiver

                    </button>

                </form>

            @endif


            {{-- RETOUR --}}
            <a
                href="{{ route('campagnes.index') }}"
                class="inline-flex items-center gap-2 rounded-lg
                       border border-gray-300 bg-white
                       px-4 py-2.5 text-sm font-semibold
                       text-gray-700 transition hover:bg-gray-50"
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
                        d="M15 19l-7-7 7-7"
                    />
                </svg>

                Retour

            </a>

        </div>

    </div>


    {{-- =========================================================
         INFORMATIONS GÉNÉRALES
    ========================================================== --}}
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 px-6 py-5">

            <h2 class="text-lg font-semibold text-slate-800">
                Informations générales
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Informations administratives et caractéristiques de la campagne.
            </p>

        </div>


        <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-2 lg:grid-cols-3">

            {{-- CODE --}}
            <div>

                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Code campagne
                </p>

                <p class="mt-1 font-semibold text-[#006a4f]">
                    {{ $campagne->codeCampagne ?? '-' }}
                </p>

            </div>


            {{-- LIBELLÉ --}}
            <div>

                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Libellé
                </p>

                <p class="mt-1 font-semibold text-slate-800">
                    {{ $campagne->libelle ?? '-' }}
                </p>

            </div>


            {{-- PORTÉE --}}
            <div>

                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Portée
                </p>

                <div class="mt-1">

                    @switch($campagne->portee)

                        @case('nationale')

                            <span class="inline-flex rounded-full
                                         bg-blue-50 px-3 py-1
                                         text-xs font-semibold text-blue-700">
                                Nationale
                            </span>

                            @break

                        @case('regionale')

                            <span class="inline-flex rounded-full
                                         bg-purple-50 px-3 py-1
                                         text-xs font-semibold text-purple-700">
                                Régionale
                            </span>

                            @break

                        @case('prefectorale')

                            <span class="inline-flex rounded-full
                                         bg-[#e5f2ee] px-3 py-1
                                         text-xs font-semibold text-[#006a4f]">
                                Préfectorale
                            </span>

                            @break

                        @default

                            <span class="text-sm text-gray-400">
                                Non définie
                            </span>

                    @endswitch

                </div>

            </div>


            {{-- STRUCTURE --}}
            <div>

                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Structure porteuse
                </p>

                <p class="mt-1 font-semibold text-slate-800">
                    {{ $campagne->structure?->nom ?? 'Non définie' }}
                </p>

                @if($campagne->structure?->niveau)

                    <p class="mt-1 text-xs text-gray-400">
                        Niveau :
                        {{ ucfirst($campagne->structure->niveau) }}
                    </p>

                @endif

            </div>


            {{-- CRÉATEUR --}}
            <div>

                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Créée par
                </p>

                <p class="mt-1 font-semibold text-slate-800">
                    {{ $campagne->createur?->name ?? 'Non défini' }}
                </p>

            </div>


            {{-- DATE CRÉATION --}}
            <div>

                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Date de création
                </p>

                <p class="mt-1 font-semibold text-slate-800">
                    {{ $campagne->created_at?->format('d/m/Y à H:i') ?? '-' }}
                </p>

            </div>


            {{-- DATE DÉBUT --}}
            <div>

                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Date de début
                </p>

                <p class="mt-1 font-semibold text-slate-800">
                    {{ $campagne->dateDebut?->format('d/m/Y à H:i') ?? '-' }}
                </p>

            </div>


            {{-- DATE FIN --}}
            <div>

                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Date de fin
                </p>

                <p class="mt-1 font-semibold text-slate-800">

                    @if($campagne->dateFin)

                        {{ $campagne->dateFin->format('d/m/Y à H:i') }}

                    @else

                        <span class="text-gray-400">
                            Non définie
                        </span>

                    @endif

                </p>

            </div>


            {{-- OFFICIELLE --}}
            <div>

                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Campagne officielle
                </p>

                @if($campagne->estOfficielle)

                    <span class="mt-1 inline-flex items-center rounded-full
                                 bg-green-50 px-3 py-1
                                 text-xs font-semibold text-green-700">
                        Oui
                    </span>

                @else

                    <span class="mt-1 inline-flex items-center rounded-full
                                 bg-gray-100 px-3 py-1
                                 text-xs font-semibold text-gray-600">
                        Non
                    </span>

                @endif

            </div>


            {{-- DESCRIPTION --}}
            <div class="md:col-span-2 lg:col-span-3">

                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Description
                </p>

                <div class="mt-2 rounded-lg bg-gray-50 p-4 text-sm
                            leading-relaxed text-gray-700">

                    @if($campagne->description)

                        {{ $campagne->description }}

                    @else

                        <span class="italic text-gray-400">
                            Aucune description renseignée.
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         OBJECTIFS
    ========================================================== --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

        {{-- OBJECTIFS --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

            <div class="border-b border-gray-200 px-6 py-5">

                <h2 class="text-lg font-semibold text-slate-800">
                    Objectifs
                </h2>

            </div>

            <div class="p-6">

                @if($campagne->objectifs)

                    <div class="whitespace-pre-line text-sm
                                leading-relaxed text-gray-700">
                        {{ $campagne->objectifs }}
                    </div>

                @else

                    <p class="text-sm italic text-gray-400">
                        Aucun objectif renseigné.
                    </p>

                @endif

            </div>

        </div>


        {{-- RÉSULTATS ATTENDUS --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

            <div class="border-b border-gray-200 px-6 py-5">

                <h2 class="text-lg font-semibold text-slate-800">
                    Résultats attendus
                </h2>

            </div>

            <div class="p-6">

                @if($campagne->resultatsAttendus)

                    <div class="whitespace-pre-line text-sm
                                leading-relaxed text-gray-700">
                        {{ $campagne->resultatsAttendus }}
                    </div>

                @else

                    <p class="text-sm italic text-gray-400">
                        Aucun résultat attendu renseigné.
                    </p>

                @endif

            </div>

        </div>

    </div>


    {{-- =========================================================
         MÉTHODOLOGIE ET INSTRUCTIONS
    ========================================================== --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

        {{-- MÉTHODOLOGIE --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

            <div class="border-b border-gray-200 px-6 py-5">

                <h2 class="text-lg font-semibold text-slate-800">
                    Méthodologie
                </h2>

            </div>

            <div class="p-6">

                @if($campagne->methodologie)

                    <div class="whitespace-pre-line text-sm
                                leading-relaxed text-gray-700">
                        {{ $campagne->methodologie }}
                    </div>

                @else

                    <p class="text-sm italic text-gray-400">
                        Aucune méthodologie renseignée.
                    </p>

                @endif

            </div>

        </div>


        {{-- INSTRUCTIONS --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

            <div class="border-b border-gray-200 px-6 py-5">

                <h2 class="text-lg font-semibold text-slate-800">
                    Instructions générales
                </h2>

            </div>

            <div class="p-6">

                @if($campagne->instructions)

                    <div class="whitespace-pre-line text-sm
                                leading-relaxed text-gray-700">
                        {{ $campagne->instructions }}
                    </div>

                @else

                    <p class="text-sm italic text-gray-400">
                        Aucune instruction renseignée.
                    </p>

                @endif

            </div>

        </div>

    </div>


    {{-- =========================================================
         ZONES DE LA CAMPAGNE
    ========================================================== --}}
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="flex flex-col gap-3 border-b border-gray-200
                    px-6 py-5 sm:flex-row sm:items-center
                    sm:justify-between">

            <div>

                <h2 class="text-lg font-semibold text-slate-800">
                    Zones concernées
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Territoires couverts par la campagne.
                </p>

            </div>

            <div class="rounded-lg bg-slate-100 px-4 py-2 text-sm text-slate-700">

                <strong>
                    {{ $campagne->zones->count() }}
                </strong>

                zone(s)

            </div>

        </div>


        <div class="p-6">

            @if($campagne->zones->count())

                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead>

                            <tr class="border-b border-gray-200">

                                <th class="px-4 py-3 text-left text-xs
                                           font-semibold uppercase tracking-wide
                                           text-gray-500">
                                    #
                                </th>

                                <th class="px-4 py-3 text-left text-xs
                                           font-semibold uppercase tracking-wide
                                           text-gray-500">
                                    Type
                                </th>

                                <th class="px-4 py-3 text-left text-xs
                                           font-semibold uppercase tracking-wide
                                           text-gray-500">
                                    Zone
                                </th>

                                <th class="px-4 py-3 text-left text-xs
                                           font-semibold uppercase tracking-wide
                                           text-gray-500">
                                    Statut
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100">

                            @foreach($campagne->zones as $zone)

                                <tr class="transition hover:bg-gray-50">

                                    <td class="px-4 py-3 text-gray-400">
                                        {{ $loop->iteration }}
                                    </td>


                                   <td class="px-4 py-3">

                                        @switch($zone->zone_type)

                                            @case('region')

                                                <span class="inline-flex rounded-full
                                                            bg-purple-50 px-3 py-1
                                                            text-xs font-semibold
                                                            text-purple-700">
                                                    Région
                                                </span>

                                                @break

                                            @case('prefecture')

                                                <span class="inline-flex rounded-full
                                                            bg-blue-50 px-3 py-1
                                                            text-xs font-semibold
                                                            text-blue-700">
                                                    Préfecture
                                                </span>

                                                @break

                                            @case('commune')

                                                <span class="inline-flex rounded-full
                                                            bg-[#e5f2ee] px-3 py-1
                                                            text-xs font-semibold
                                                            text-[#006a4f]">
                                                    Commune
                                                </span>

                                                @break

                                            @case('canton')

                                                <span class="inline-flex rounded-full
                                                            bg-amber-50 px-3 py-1
                                                            text-xs font-semibold
                                                            text-amber-700">
                                                    Canton
                                                </span>

                                                @break

                                            @case('village')

                                                <span class="inline-flex rounded-full
                                                            bg-gray-100 px-3 py-1
                                                            text-xs font-semibold
                                                            text-gray-600">
                                                    Village
                                                </span>

                                                @break

                                            @default

                                                <span class="inline-flex rounded-full
                                                            bg-gray-100 px-3 py-1
                                                            text-xs font-semibold
                                                            text-gray-600">
                                                    Non défini
                                                </span>

                                        @endswitch

                                    </td>


                                    <td class="px-4 py-3 font-medium text-slate-700">

                                        {{ $zone->nom_zone ?? $zone->zone_id ?? '-' }}

                                    </td>


                                    <td class="px-4 py-3">

                                        @switch($zone->statut)

                                            @case('planifiee')

                                                <span class="text-xs font-semibold text-yellow-700">
                                                    Planifiée
                                                </span>

                                                @break

                                            @case('active')

                                                <span class="text-xs font-semibold text-green-700">
                                                    Active
                                                </span>

                                                @break

                                            @case('cloturee')

                                                <span class="text-xs font-semibold text-gray-600">
                                                    Clôturée
                                                </span>

                                                @break

                                            @default

                                                <span class="text-xs text-gray-400">
                                                    {{ $zone->statut ?? '-' }}
                                                </span>

                                        @endswitch

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="rounded-lg border border-dashed border-gray-300
                            py-10 text-center text-sm text-gray-500">

                    Aucune zone n'est associée à cette campagne.

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
         DÉPLOIEMENTS
    ========================================================== --}}
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="flex flex-col gap-3 border-b border-gray-200
                    px-6 py-5 sm:flex-row sm:items-center
                    sm:justify-between">

            <div>

                <h2 class="text-lg font-semibold text-slate-800">
                    Déploiement de la campagne
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Suivi de la transmission de la campagne aux préfectures concernées.
                </p>

            </div>

            <div class="rounded-lg bg-[#e5f2ee] px-4 py-2
                        text-sm text-[#006a4f]">

                <strong>
                    {{ $campagne->deploiements->count() }}
                </strong>

                préfecture(s)

            </div>

        </div>


        <div class="p-6">

            @if($campagne->deploiements->count())

                <div class="overflow-x-auto">

                    <table class="w-full min-w-[800px] text-sm">

                        <thead>

                            <tr class="border-b border-gray-200">

                                <th class="px-4 py-3 text-left text-xs
                                           font-semibold uppercase tracking-wide
                                           text-gray-500">
                                    #
                                </th>

                                <th class="px-4 py-3 text-left text-xs
                                           font-semibold uppercase tracking-wide
                                           text-gray-500">
                                    Préfecture
                                </th>

                                <th class="px-4 py-3 text-center text-xs
                                           font-semibold uppercase tracking-wide
                                           text-gray-500">
                                    État
                                </th>

                                <th class="px-4 py-3 text-left text-xs
                                           font-semibold uppercase tracking-wide
                                           text-gray-500">
                                    Réception
                                </th>

                                <th class="px-4 py-3 text-left text-xs
                                           font-semibold uppercase tracking-wide
                                           text-gray-500">
                                    Prise en charge
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100">

                            @foreach($campagne->deploiements as $deploiement)

                                <tr class="hover:bg-gray-50">

                                    <td class="px-4 py-4 text-gray-400">
                                        {{ $loop->iteration }}
                                    </td>


                                    <td class="px-4 py-4">

                                        <div class="font-semibold text-slate-800">

                                            {{ $deploiement->prefecture?->nom
                                                ?? 'Préfecture non définie'
                                            }}

                                        </div>

                                    </td>


                                    <td class="px-4 py-4 text-center">

                                        @switch($deploiement->statut)

                                            @case('notifiee')

                                                <span class="inline-flex items-center gap-1.5
                                                             rounded-full bg-yellow-50
                                                             px-3 py-1 text-xs
                                                             font-semibold text-yellow-700">

                                                    <span class="h-1.5 w-1.5 rounded-full
                                                                 bg-yellow-500"></span>

                                                    Notifiée

                                                </span>

                                                @break

                                            @case('recu')

                                                <span class="inline-flex items-center gap-1.5
                                                             rounded-full bg-blue-50
                                                             px-3 py-1 text-xs
                                                             font-semibold text-blue-700">

                                                    <span class="h-1.5 w-1.5 rounded-full
                                                                 bg-blue-500"></span>

                                                    Reçue

                                                </span>

                                                @break

                                            @case('prise_en_charge')

                                                <span class="inline-flex items-center gap-1.5
                                                             rounded-full bg-green-50
                                                             px-3 py-1 text-xs
                                                             font-semibold text-green-700">

                                                    <span class="h-1.5 w-1.5 rounded-full
                                                                 bg-green-500"></span>

                                                    Prise en charge

                                                </span>

                                                @break

                                            @default

                                                <span class="inline-flex rounded-full
                                                             bg-gray-100 px-3 py-1
                                                             text-xs font-semibold
                                                             text-gray-600">

                                                    {{ $deploiement->statut ?? '-' }}

                                                </span>

                                        @endswitch

                                    </td>


                                    <td class="px-4 py-4 text-gray-600">

                                        @if($deploiement->dateReception)

                                            {{ $deploiement->dateReception->format('d/m/Y à H:i') }}

                                        @else

                                            <span class="text-gray-400">
                                                En attente
                                            </span>

                                        @endif

                                    </td>


                                    <td class="px-4 py-4">

                                        @if($deploiement->datePriseEnCharge)

                                            <div class="text-gray-600">
                                                {{ $deploiement->datePriseEnCharge->format('d/m/Y à H:i') }}
                                            </div>

                                            @if($deploiement->prisEnChargePar)

                                                <div class="mt-1 text-xs text-gray-400">
                                                    Par :
                                                    {{ $deploiement->prisEnChargePar->name }}
                                                </div>

                                            @endif

                                        @else

                                            <span class="text-gray-400">
                                                En attente
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="rounded-lg border border-dashed border-gray-300
                            py-10 text-center">

                    <p class="font-semibold text-slate-700">
                        Campagne non déployée
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Aucune préfecture n'a encore été notifiée.
                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
         QUESTIONNAIRES
    ========================================================== --}}
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="flex flex-col gap-3 border-b border-gray-200
                    px-6 py-5 sm:flex-row sm:items-center
                    sm:justify-between">

            <div>

                <h2 class="text-lg font-semibold text-slate-800">
                    Questionnaires associés
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Questionnaires utilisés dans le cadre de cette campagne.
                </p>

            </div>

            <div class="rounded-lg bg-slate-100 px-4 py-2
                        text-sm text-slate-700">

                <strong>
                    {{ $campagne->questionnaires->count() }}
                </strong>

                questionnaire(s)

            </div>

        </div>


        <div class="p-6">

            @if($campagne->questionnaires->count())

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">

                    @foreach($campagne->questionnaires as $questionnaire)

                        <div class="rounded-xl border border-gray-200
                                    bg-gray-50 p-5">

                            <div class="flex items-start justify-between gap-3">

                                <div>

                                    <h3 class="font-semibold text-slate-800">
                                        {{ $questionnaire->libelle
                                            ?? $questionnaire->nom
                                            ?? 'Questionnaire'
                                        }}
                                    </h3>

                                    @if(isset($questionnaire->code))

                                        <p class="mt-1 text-xs text-gray-400">
                                            {{ $questionnaire->code }}
                                        </p>

                                    @endif

                                </div>

                                <span class="rounded-full bg-white
                                             px-2.5 py-1 text-xs
                                             font-medium text-gray-500">
                                    Questionnaire
                                </span>

                            </div>

                            @if(isset($questionnaire->description) && $questionnaire->description)

                                <p class="mt-3 text-sm text-gray-600">
                                    {{ $questionnaire->description }}
                                </p>

                            @endif

                        </div>

                    @endforeach

                </div>

            @else

                <div class="rounded-lg border border-dashed border-gray-300
                            py-10 text-center text-sm text-gray-500">

                    Aucun questionnaire n'est encore associé à cette campagne.

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
         AFFECTATIONS
         INFORMATION SEULEMENT
         La gestion appartient au DPA.
    ========================================================== --}}
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="flex flex-col gap-3 border-b border-gray-200
                    px-6 py-5 sm:flex-row sm:items-center
                    sm:justify-between">

            <div>

                <h2 class="text-lg font-semibold text-slate-800">
                    Affectations
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Agents actuellement affectés à cette campagne.
                </p>

            </div>

            <div class="rounded-lg bg-slate-100 px-4 py-2
                        text-sm text-slate-700">

                <strong>
                    {{ $campagne->affectations->count() }}
                </strong>

                affectation(s)

            </div>

        </div>


        <div class="p-6">

            @if($campagne->affectations->count())

                <div class="overflow-x-auto">

                    <table class="w-full min-w-[800px] text-sm">

                        <thead>

                            <tr class="border-b border-gray-200">

                                <th class="px-4 py-3 text-left text-xs
                                           font-semibold uppercase tracking-wide
                                           text-gray-500">
                                    #
                                </th>

                                <th class="px-4 py-3 text-left text-xs
                                           font-semibold uppercase tracking-wide
                                           text-gray-500">
                                    Agent
                                </th>

                                <th class="px-4 py-3 text-left text-xs
                                           font-semibold uppercase tracking-wide
                                           text-gray-500">
                                    Référence
                                </th>

                                <th class="px-4 py-3 text-left text-xs
                                           font-semibold uppercase tracking-wide
                                           text-gray-500">
                                    Début
                                </th>

                                <th class="px-4 py-3 text-left text-xs
                                           font-semibold uppercase tracking-wide
                                           text-gray-500">
                                    Fin
                                </th>

                                <th class="px-4 py-3 text-center text-xs
                                           font-semibold uppercase tracking-wide
                                           text-gray-500">
                                    Statut
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100">

                            @foreach($campagne->affectations as $affectation)

                                <tr class="hover:bg-gray-50">

                                    <td class="px-4 py-4 text-gray-400">
                                        {{ $loop->iteration }}
                                    </td>


                                    <td class="px-4 py-4">

                                        <div class="font-semibold text-slate-800">
                                            {{ $affectation->user?->name
                                                ?? 'Agent non défini'
                                            }}
                                        </div>

                                    </td>


                                    <td class="px-4 py-4 font-medium text-[#006a4f]">
                                        {{ $affectation->reference ?? '-' }}
                                    </td>


                                    <td class="px-4 py-4 text-gray-600">
                                        {{ $affectation->dateDebut?->format('d/m/Y')
                                            ?? '-'
                                        }}
                                    </td>


                                    <td class="px-4 py-4 text-gray-600">
                                        {{ $affectation->dateFin?->format('d/m/Y')
                                            ?? '-'
                                        }}
                                    </td>


                                    <td class="px-4 py-4 text-center">

                                        @if($affectation->statut === 'active')

                                            <span class="inline-flex items-center gap-1.5
                                                         rounded-full bg-green-50
                                                         px-3 py-1 text-xs
                                                         font-semibold text-green-700">

                                                <span class="h-1.5 w-1.5 rounded-full
                                                             bg-green-500"></span>

                                                Active

                                            </span>

                                        @elseif($affectation->statut === 'terminee')

                                            <span class="inline-flex items-center gap-1.5
                                                         rounded-full bg-gray-100
                                                         px-3 py-1 text-xs
                                                         font-semibold text-gray-600">

                                                <span class="h-1.5 w-1.5 rounded-full
                                                             bg-gray-400"></span>

                                                Terminée

                                            </span>

                                        @else

                                            <span class="inline-flex rounded-full
                                                         bg-slate-100 px-3 py-1
                                                         text-xs font-semibold
                                                         text-slate-600">

                                                {{ $affectation->statut ?? '-' }}

                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="rounded-lg border border-dashed border-gray-300
                            py-10 text-center text-sm text-gray-500">

                    Aucun agent n'est encore affecté à cette campagne.

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
         NOTE MÉTIER
         La planification n'est PAS gérée ici.
    ========================================================== --}}
    @if($campagne->deploiements->count())

        <div class="rounded-xl border border-blue-200 bg-blue-50 p-5">

            <div class="flex gap-3">

                <svg
                    class="mt-0.5 h-5 w-5 shrink-0 text-blue-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M12 21a9 9 0 100-18 9 9 0 000 18z"
                    />
                </svg>

                <div>

                    <h3 class="font-semibold text-blue-800">
                        Étape suivante
                    </h3>

                    <p class="mt-1 text-sm leading-relaxed text-blue-700">
                        Après le déploiement et la réception de la campagne,
                        chaque DPA concerné peut procéder à la
                        <strong>planification préfectorale</strong>,
                        à l'organisation des étapes de terrain et à
                        l'affectation des agents recenseurs.
                    </p>

                </div>

            </div>

        </div>

    @endif
    

</div>

@endsection