@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}

    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <h1 class="text-xl font-semibold text-[#212529]">
                Superviseurs et agents recenseurs
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Gestion des comptes rattachés à votre préfecture.
            </p>

        </div>

        <div class="flex flex-wrap items-center gap-3">

            {{-- =====================================================
                 NOUVEAU COMPTE
            ====================================================== --}}

            @if(auth()->user()->isDPA())

                <a
                    href="{{ route('dpa.agents.create') }}"
                    class="inline-flex
                           items-center
                           gap-2
                           rounded-lg
                           bg-[#006a4f]
                           px-4 py-2.5
                           text-sm font-semibold
                           text-white
                           shadow-sm
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
                            d="M12 4v16m8-8H4"
                        />
                    </svg>

                    Nouveau compte

                </a>

            @endif

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

            <span>
                {{ session('success') }}
            </span>

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

            <span>
                {{ session('error') }}
            </span>

        </div>

    @endif


    {{-- =========================================================
         RECHERCHE ET FILTRES
    ========================================================== --}}

    <div class="rounded-lg
                border border-[#e5e7eb]
                bg-white
                p-4
                shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

        <form
            method="GET"
            action="{{ route('dpa.agents.index') }}"
        >

            <div class="flex flex-col gap-3 lg:flex-row">

                {{-- =================================================
                     RECHERCHE
                ================================================== --}}

                <div class="relative flex-1">

                    <svg
                        class="pointer-events-none
                               absolute left-4 top-1/2
                               h-5 w-5
                               -translate-y-1/2
                               text-gray-400"
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

                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Rechercher par nom, email, login ou téléphone..."
                        class="w-full
                               rounded-lg
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


                {{-- =================================================
                     FILTRE RÔLE
                ================================================== --}}

                <select
                    name="role"
                    class="rounded-lg
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
                        Tous les rôles
                    </option>

                    @foreach($roles as $item)

                        <option
                            value="{{ $item->nom }}"
                            @selected($role === $item->nom)
                        >
                            {{ $item->nom }}
                        </option>

                    @endforeach

                </select>


                {{-- =================================================
                     FILTRE STATUT
                ================================================== --}}

                <select
                    name="statut"
                    class="rounded-lg
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
                        value="1"
                        @selected((string) $statut === '1')
                    >
                        Actif
                    </option>

                    <option
                        value="0"
                        @selected((string) $statut === '0')
                    >
                        Inactif
                    </option>

                </select>


                {{-- =================================================
                     RECHERCHER
                ================================================== --}}

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

                    Rechercher

                </button>

            </div>

        </form>

    </div>


    {{-- =========================================================
         TABLEAU DES COMPTES
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
                    Superviseurs et agents recenseurs
                </h2>

                <p class="mt-1 text-xs text-gray-500">
                    Comptes rattachés à votre préfecture.
                </p>

            </div>

            <div class="text-sm text-gray-500">

                <span class="font-semibold text-[#006a4f]">
                    {{ $agents->total() }}
                </span>

                compte(s)

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

                        <th
                            class="px-6 py-4 text-left
                                   text-xs font-semibold
                                   uppercase tracking-wide
                                   text-gray-500"
                        >
                            N°
                        </th>

                        <th
                            class="px-6 py-4 text-left
                                   text-xs font-semibold
                                   uppercase tracking-wide
                                   text-gray-500"
                        >
                            Utilisateur
                        </th>

                        <th
                            class="px-6 py-4 text-left
                                   text-xs font-semibold
                                   uppercase tracking-wide
                                   text-gray-500"
                        >
                            Téléphone
                        </th>

                        <th
                            class="px-6 py-4 text-left
                                   text-xs font-semibold
                                   uppercase tracking-wide
                                   text-gray-500"
                        >
                            Rôle
                        </th>

                        <th
                            class="px-6 py-4 text-left
                                   text-xs font-semibold
                                   uppercase tracking-wide
                                   text-gray-500"
                        >
                            Préfecture
                        </th>

                        <th
                            class="px-6 py-4 text-center
                                   text-xs font-semibold
                                   uppercase tracking-wide
                                   text-gray-500"
                        >
                            Statut
                        </th>

                        <th
                            class="px-6 py-4 text-right
                                   text-xs font-semibold
                                   uppercase tracking-wide
                                   text-gray-500"
                        >
                            Actions
                        </th>

                    </tr>

                </thead>


                {{-- =================================================
                     TBODY
                ================================================== --}}

                <tbody class="divide-y divide-[#e5e7eb]">

                    @forelse($agents as $agent)

                        <tr class="group transition hover:bg-[#f8faf9]">


                            {{-- =================================================
                                 N°
                            ================================================== --}}

                            <td class="px-6 py-4 text-sm text-gray-400">

                                {{ $loop->iteration + (($agents->currentPage() - 1) * $agents->perPage()) }}

                            </td>


                            {{-- =================================================
                                 UTILISATEUR
                            ================================================== --}}

                            <td class="px-6 py-4">

                                <div class="flex items-center gap-3">

                                    {{-- AVATAR --}}

                                    <div
                                        class="flex h-10 w-10 shrink-0
                                               items-center justify-center
                                               rounded-full
                                               bg-[#e5f2ee]
                                               text-sm font-semibold
                                               text-[#006a4f]"
                                    >

                                        {{ strtoupper(substr($agent->name, 0, 1)) }}

                                    </div>


                                    {{-- INFORMATIONS --}}

                                    <div class="min-w-0">

                                        <div class="flex items-center gap-2">

                                            <div
                                                class="truncate
                                                       font-semibold
                                                       text-[#212529]"
                                            >

                                                {{ $agent->name }}

                                            </div>


                                            {{-- DPA CONNECTÉ
                                                 Normalement absent de la liste,
                                                 mais conservé par sécurité.
                                            --}}

                                            @if($agent->id === auth()->id())

                                                <span
                                                    class="inline-flex
                                                           shrink-0
                                                           rounded-full
                                                           bg-[#006a4f]
                                                           px-2 py-0.5
                                                           text-[10px]
                                                           font-semibold
                                                           text-white"
                                                >
                                                    Vous
                                                </span>

                                            @endif

                                        </div>


                                        <div
                                            class="truncate
                                                   text-xs
                                                   text-gray-500"
                                        >

                                            {{ $agent->email ?? '—' }}

                                        </div>

                                    </div>

                                </div>

                            </td>


                            {{-- =================================================
                                 TÉLÉPHONE
                            ================================================== --}}

                            <td class="px-6 py-4 text-[#434343]">

                                {{ $agent->telephone ?? '—' }}

                            </td>


                            {{-- =================================================
                                 RÔLE
                            ================================================== --}}

                            <td class="px-6 py-4">

                                @if($agent->role)

                                    @php

                                        $nomRole = $agent->role->nom;

                                        $classeRole = match($nomRole) {

                                            'Superviseur' =>
                                                'bg-blue-50 text-blue-700',

                                            'Agent recenseur' =>
                                                'bg-[#e5f2ee] text-[#006a4f]',

                                            default =>
                                                'bg-gray-100 text-gray-600',

                                        };

                                    @endphp

                                    <span
                                        class="inline-flex
                                               items-center
                                               rounded-full
                                               px-3 py-1
                                               text-xs
                                               font-semibold
                                               {{ $classeRole }}"
                                    >

                                        {{ $nomRole }}

                                    </span>

                                @else

                                    <span class="text-gray-400">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 PRÉFECTURE
                            ================================================== --}}

                            <td class="px-6 py-4 text-[#434343]">

                                {{ $agent->rattachementPrefectureActif?->prefecture?->nom ?? '—' }}

                            </td>


                            {{-- =================================================
                                 STATUT
                            ================================================== --}}

                            <td class="px-6 py-4 text-center">

                                @if($agent->statut)

                                    <span
                                        class="inline-flex
                                               items-center
                                               gap-1.5
                                               rounded-full
                                               bg-green-50
                                               px-3 py-1
                                               text-xs
                                               font-semibold
                                               text-green-700"
                                    >

                                        <span
                                            class="h-1.5 w-1.5
                                                   rounded-full
                                                   bg-green-500"
                                        ></span>

                                        Actif

                                    </span>

                                @else

                                    <span
                                        class="inline-flex
                                               items-center
                                               gap-1.5
                                               rounded-full
                                               bg-red-50
                                               px-3 py-1
                                               text-xs
                                               font-semibold
                                               text-red-700"
                                    >

                                        <span
                                            class="h-1.5 w-1.5
                                                   rounded-full
                                                   bg-red-500"
                                        ></span>

                                        Inactif

                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 ACTIONS
                            ================================================== --}}

                            <td class="px-6 py-4">

                                <div
                                    class="flex
                                           items-center
                                           justify-end
                                           gap-2"
                                >

                                    {{-- =================================================
                                         VOIR
                                    ================================================== --}}

                                    <a
                                        href="{{ route('dpa.agents.show', $agent) }}"
                                        title="Voir le profil"
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
                                               hover:text-[#006a4f]
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
                                         MODIFIER
                                    ================================================== --}}

                                    @if(
                                        $agent->id !== auth()->id()
                                        && in_array(
                                            $agent->role?->nom,
                                            [
                                                'Superviseur',
                                                'Agent recenseur'
                                            ],
                                            true
                                        )
                                    )

                                        <a
                                            href="{{ route('dpa.agents.edit', $agent) }}"
                                            title="Modifier"
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
                                                   hover:text-[#006a4f]
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
                                                    d="M11 5H6a2 2 0 00-2 2v11
                                                       a2 2 0 002 2h11
                                                       a2 2 0 002-2v-5
                                                       M18.5 2.5a2.121 2.121
                                                       0 013 3L12 15l-4 1
                                                       1-4 9.5-9.5z"
                                                />

                                            </svg>

                                        </a>


                                        {{-- =================================================
                                             ACTIVER / DÉSACTIVER
                                        ================================================== --}}

                                        <form
                                            method="POST"
                                            action="{{ route('dpa.agents.toggle-status', $agent) }}"
                                            class="inline"
                                        >

                                            @csrf

                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                title="{{ $agent->statut ? 'Désactiver' : 'Activer' }}"
                                                class="inline-flex
                                                       h-9 w-9
                                                       items-center
                                                       justify-center
                                                       rounded-lg
                                                       border
                                                       border-[#e5e7eb]
                                                       bg-white
                                                       transition
                                                       focus:outline-none
                                                       focus:ring-2
                                                       focus:ring-[#006a4f]/20
                                                       {{ $agent->statut
                                                           ? 'text-red-600 hover:border-red-200 hover:bg-red-50'
                                                           : 'text-green-600 hover:border-green-200 hover:bg-green-50' }}"
                                            >

                                                @if($agent->statut)

                                                    {{-- DÉSACTIVER --}}

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
                                                            d="M18 6L6 18M6 6l12 12"
                                                        />

                                                    </svg>

                                                @else

                                                    {{-- ACTIVER --}}

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

                                                @endif

                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </td>

                        </tr>


                    @empty

                        {{-- =================================================
                             AUCUN COMPTE
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
                                                d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2
                                                   m9-9a4 4 0 100-8 4 4 0 000 8
                                                   m5-3a3 3 0 100-6
                                                   m2 18v-2a4 4 0 00-3-3.87"
                                            />

                                        </svg>

                                    </div>

                                    <p class="font-semibold text-[#212529]">
                                        Aucun compte trouvé
                                    </p>

                                    <p class="mt-1 text-sm text-gray-500">
                                        Aucun compte ne correspond à votre recherche.
                                    </p>

                                    @if(auth()->user()->isDPA())

                                        <a
                                            href="{{ route('dpa.agents.create') }}"
                                            class="mt-4
                                                   inline-flex
                                                   items-center
                                                   gap-2
                                                   rounded-lg
                                                   bg-[#006a4f]
                                                   px-4 py-2
                                                   text-sm
                                                   font-semibold
                                                   text-white
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
                                                    d="M12 4v16m8-8H4"
                                                />

                                            </svg>

                                            Créer un compte

                                        </a>

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

    @if($agents->hasPages())

        <div class="flex justify-center pt-2">

            {{ $agents->links() }}

        </div>

    @endif

</div>

@endsection