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
                Gérez les campagnes de recensement agricole du système SIRA-Mô.
            </p>
        </div>

        {{-- ACTION --}}
        <a
            href="{{ route('campagnes.create') }}"
            class="inline-flex items-center gap-2 rounded-lg
                   bg-[#006a4f] px-4 py-2.5
                   text-sm font-semibold text-white
                   shadow-sm transition
                   hover:bg-[#156c52]
                   focus:outline-none focus:ring-2
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
                    d="M12 4v16m8-8H4"
                />
            </svg>

            Nouvelle campagne
        </a>

    </div>


    {{-- =========================================================
         MESSAGES
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


    @if(session('info'))
        <div class="flex items-start gap-3 rounded-lg
                    border border-blue-200
                    bg-blue-50
                    px-4 py-3 text-sm text-blue-700">

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
                    d="M13 16h-1v-4h-1m1-4h.01M12 21a9 9 0 100-18 9 9 0 000-18z"
                />
            </svg>

            <span>{{ session('info') }}</span>
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
            action="{{ route('campagnes.index') }}"
        >

            <div class="flex flex-col gap-3 sm:flex-row">

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
                        placeholder="Rechercher une campagne..."
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

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2
                           rounded-lg bg-[#006a4f]
                           px-5 py-3
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

        {{-- EN-TÊTE --}}
        <div class="flex flex-col gap-1
                    border-b border-[#e5e7eb]
                    px-6 py-5
                    sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h2 class="text-base font-semibold text-[#212529]">
                    Liste des campagnes
                </h2>

                <p class="mt-1 text-xs text-gray-500">
                    Campagnes enregistrées dans SIRA-Mô
                </p>
            </div>

            <div class="text-sm text-gray-500">

                <span class="font-semibold text-[#006a4f]">
                    {{ $campagnes->total() }}
                </span>

                campagne(s)

            </div>

        </div>


        {{-- TABLE --}}
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
                            Code
                        </th>

                        <th class="px-6 py-4 text-left text-xs
                                   font-semibold uppercase tracking-wide
                                   text-gray-500">
                            Libellé
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
                            Statut
                        </th>

                        <th class="px-6 py-4 text-right text-xs
                                   font-semibold uppercase tracking-wide
                                   text-gray-500">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-[#e5e7eb]">

                    @forelse($campagnes as $campagne)

                        <tr class="group transition hover:bg-[#f8faf9]">

                            {{-- N° --}}
                            <td class="px-6 py-4 text-sm text-gray-400">

                                {{
                                    $loop->iteration
                                    + (($campagnes->currentPage() - 1)
                                    * $campagnes->perPage())
                                }}

                            </td>


                            {{-- CODE --}}
                            <td class="px-6 py-4">

                                <span class="font-semibold text-[#006a4f]">
                                    {{ $campagne->codeCampagne }}
                                </span>

                            </td>


                            {{-- LIBELLÉ --}}
                            <td class="px-6 py-4">

                                <div class="font-semibold text-[#212529]">
                                    {{ $campagne->libelle }}
                                </div>

                                @if($campagne->description)
                                    <div class="mt-1 max-w-xs truncate text-xs text-gray-400">
                                        {{ $campagne->description }}
                                    </div>
                                @endif

                            </td>


                            {{-- PORTÉE --}}
                            <td class="px-6 py-4">

                                @if($campagne->portee === 'nationale')

                                    <span class="inline-flex rounded-full
                                                 bg-blue-50 px-3 py-1
                                                 text-xs font-semibold text-blue-700">
                                        Nationale
                                    </span>

                                @elseif($campagne->portee === 'regionale')

                                    <span class="inline-flex rounded-full
                                                 bg-purple-50 px-3 py-1
                                                 text-xs font-semibold text-purple-700">
                                        Régionale
                                    </span>

                                @elseif($campagne->portee === 'prefectorale')

                                    <span class="inline-flex rounded-full
                                                 bg-[#e5f2ee] px-3 py-1
                                                 text-xs font-semibold text-[#006a4f]">
                                        Préfectorale
                                    </span>

                                @else

                                    <span class="text-gray-400">
                                        Non définie
                                    </span>

                                @endif

                            </td>


                            {{-- PÉRIODE --}}
                            <td class="px-6 py-4 text-[#434343]">

                                <div class="font-medium">
                                    {{ $campagne->dateDebut?->format('d/m/Y') }}
                                </div>

                                <div class="mt-1 text-xs text-gray-400">
                                    jusqu'au
                                    {{ $campagne->dateFin?->format('d/m/Y') ?? 'Non définie' }}
                                </div>

                            </td>


                            {{-- STATUT --}}
                            <td class="px-6 py-4 text-center">

                                @switch($campagne->statut)

                                    @case('planifiee')

                                        <span class="inline-flex items-center gap-1.5
                                                     rounded-full bg-yellow-50
                                                     px-3 py-1
                                                     text-xs font-semibold
                                                     text-yellow-700">

                                            <span class="h-1.5 w-1.5 rounded-full bg-yellow-500"></span>

                                            Planifiée

                                        </span>

                                        @break


                                    @case('active')

                                        <span class="inline-flex items-center gap-1.5
                                                     rounded-full bg-green-50
                                                     px-3 py-1
                                                     text-xs font-semibold
                                                     text-green-700">

                                            <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>

                                            Active

                                        </span>

                                        @break


                                    @case('cloturee')

                                        <span class="inline-flex items-center gap-1.5
                                                     rounded-full bg-gray-100
                                                     px-3 py-1
                                                     text-xs font-semibold
                                                     text-gray-600">

                                            <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>

                                            Clôturée

                                        </span>

                                        @break


                                    @case('archivee')

                                        <span class="inline-flex items-center gap-1.5
                                                     rounded-full bg-slate-100
                                                     px-3 py-1
                                                     text-xs font-semibold
                                                     text-slate-600">

                                            <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>

                                            Archivée

                                        </span>

                                        @break


                                    @default

                                        <span class="text-xs text-gray-400">
                                            Inconnu
                                        </span>

                                @endswitch

                            </td>


                            {{-- ACTIONS --}}
                            <td class="px-6 py-4">

                                <div class="flex items-center justify-end gap-2">

                                    {{-- VOIR --}}
                                    <a
                                        href="{{ route('campagnes.show', $campagne) }}"
                                        title="Voir la campagne"
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


                                    {{-- MODIFIER --}}
                                    @if(
                                        $campagne->statut === 'planifiee'
                                        || $campagne->statut === 'active'
                                    )

                                        <a
                                            href="{{ route('campagnes.edit', $campagne) }}"
                                            title="Modifier"
                                            class="inline-flex h-9 w-9
                                                   items-center justify-center
                                                   rounded-lg
                                                   border border-[#e5e7eb]
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
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.5-9.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 8.5-8.5z"
                                                />
                                            </svg>

                                        </a>

                                    @endif


                                    {{-- CLÔTURER --}}
                                    @if($campagne->statut === 'active')

                                        <form
                                            method="POST"
                                            action="{{ route('campagnes.close', $campagne) }}"
                                        >

                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                title="Clôturer"
                                                onclick="return confirm('Voulez-vous vraiment clôturer cette campagne ?')"
                                                class="inline-flex h-9 w-9
                                                       items-center justify-center
                                                       rounded-lg
                                                       bg-orange-500
                                                       text-white
                                                       transition
                                                       hover:bg-orange-600"
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

                                            </button>

                                        </form>

                                    @endif


                                    {{-- ARCHIVER --}}
                                    @if($campagne->statut === 'cloturee')

                                        <form
                                            method="POST"
                                            action="{{ route('campagnes.archive', $campagne) }}"
                                        >

                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                title="Archiver"
                                                onclick="return confirm('Voulez-vous vraiment archiver cette campagne ?')"
                                                class="inline-flex h-9 w-9
                                                       items-center justify-center
                                                       rounded-lg
                                                       bg-gray-600
                                                       text-white
                                                       transition
                                                       hover:bg-gray-700"
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

                                            </button>

                                        </form>

                                    @endif

                                    {{-- DÉPLOYER --}}
                                    {{-- =========================================================
                                        DÉPLOIEMENT
                                    ========================================================= --}}

                                    @if($campagne->statut === 'planifiee')

                                        @php
                                            $nombreDeploiements = $campagne->deploiements->count();

                                            $nombreRecus = $campagne->deploiements
                                                ->where('statut', 'recue')
                                                ->count();
                                        @endphp


                                        {{-- =====================================================
                                            PAS ENCORE DÉPLOYÉE
                                        ====================================================== --}}

                                        @if($nombreDeploiements === 0)

                                            <form
                                                method="POST"
                                                action="{{ route('campagnes.deploy', $campagne) }}"
                                                onsubmit="return confirm(
                                                    'Voulez-vous déployer cette campagne ? Les préfectures concernées seront notifiées.'
                                                )"
                                            >

                                                @csrf

                                                <button
                                                    type="submit"
                                                    title="Déployer la campagne"
                                                    class="inline-flex h-9 w-9
                                                        items-center justify-center
                                                        rounded-lg
                                                        bg-[#006a4f]
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
                                                            d="M5 12h14M13 6l6 6-6 6"
                                                        />
                                                    </svg>

                                                </button>

                                            </form>


                                        {{-- =====================================================
                                            DÉJÀ DÉPLOYÉE
                                        ====================================================== --}}

                                        @else

                                            <div
                                                title="Campagne déployée : {{ $nombreRecus }} préfecture(s) ont accusé réception"
                                                class="inline-flex items-center gap-2
                                                    rounded-lg
                                                    border border-[#b7dfd2]
                                                    bg-[#e5f2ee]
                                                    px-3 py-2
                                                    text-xs font-semibold
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

                                                Déployée

                                            </div>

                                        @endif

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="px-6 py-16 text-center"
                            >

                                <div class="flex flex-col items-center">

                                    <div class="mb-4 flex h-14 w-14
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

                                    <p class="font-semibold text-[#212529]">
                                        Aucune campagne trouvée
                                    </p>

                                    <p class="mt-1 text-sm text-gray-500">
                                        Aucune campagne ne correspond à votre recherche.
                                    </p>

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
    @if($campagnes->hasPages())

        <div class="flex justify-center pt-2">
            {{ $campagnes->links() }}
        </div>

    @endif

</div>

@endsection


