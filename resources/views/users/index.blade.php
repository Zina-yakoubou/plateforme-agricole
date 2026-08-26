@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}
    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

        <div></div>

        <div class="flex flex-wrap items-center gap-3">

            {{-- DIRECTEUR PRÉFECTORAL --}}
            @if(auth()->user()->isDPA())

                <a
                    href="{{ route('users.create', [
                        'role' => 'agent',
                        'redirect' => 'affectations.create'
                    ]) }}"
                    class="inline-flex items-center gap-2
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

                    Nouvel agent
                </a>

            @endif


            {{-- ADMINISTRATEUR --}}
            @if(auth()->user()->isAdmin())

                <a
                    href="{{ route('users.create') }}"
                    class="inline-flex items-center gap-2
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

                    Nouvel utilisateur
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
         RECHERCHE
    ========================================================== --}}

    <div class="rounded-lg
                border border-[#e5e7eb]
                bg-white
                p-4
                shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

        <form
            method="GET"
            action="{{ route('users.index') }}"
        >

            <div class="flex flex-col gap-3 sm:flex-row">

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
         TABLEAU DES UTILISATEURS
    ========================================================== --}}

    <div class="overflow-hidden
                rounded-lg
                border border-[#e5e7eb]
                bg-white
                shadow-[0_1px_2px_rgba(0,0,0,0.05)]">


        {{-- EN-TÊTE DU TABLEAU --}}
        <div class="flex flex-col gap-1
                    border-b border-[#e5e7eb]
                    px-6 py-5
                    sm:flex-row
                    sm:items-center
                    sm:justify-between">

            <div>

                <h2 class="text-base font-semibold text-[#212529]">
                    Utilisateurs
                </h2>

                <p class="mt-1 text-xs text-gray-500">
                    Comptes enregistrés dans le système.
                </p>

            </div>

            <div class="text-sm text-gray-500">

                <span class="font-semibold text-[#006a4f]">
                    {{ $users->total() }}
                </span>

                utilisateur(s)

            </div>

        </div>


        {{-- TABLE --}}
        <div class="overflow-x-auto">

            <table class="w-full min-w-[1000px] text-sm">

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
                            Utilisateur
                        </th>

                        <th class="px-6 py-4 text-left
                                   text-xs font-semibold
                                   uppercase tracking-wide
                                   text-gray-500">
                            Téléphone
                        </th>

                        <th class="px-6 py-4 text-left
                                   text-xs font-semibold
                                   uppercase tracking-wide
                                   text-gray-500">
                            Rôle
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

                    @forelse($users as $user)

                        <tr class="group transition hover:bg-[#f8faf9]">


                            {{-- N° --}}
                            <td class="px-6 py-4 text-sm text-gray-400">

                                {{ $loop->iteration + (($users->currentPage() - 1) * $users->perPage()) }}

                            </td>


                            {{-- UTILISATEUR --}}
                            <td class="px-6 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-10 w-10 shrink-0
                                                items-center justify-center
                                                rounded-full
                                                bg-[#e5f2ee]
                                                text-sm font-semibold
                                                text-[#006a4f]">

                                        {{ strtoupper(substr($user->name, 0, 1)) }}

                                    </div>


                                    <div class="min-w-0">

                                        <div class="truncate
                                                    font-semibold
                                                    text-[#212529]">

                                            {{ $user->name }}

                                        </div>

                                        <div class="truncate
                                                    text-xs
                                                    text-gray-500">

                                            {{ $user->email }}

                                        </div>

                                    </div>

                                </div>

                            </td>


                            {{-- TÉLÉPHONE --}}
                            <td class="px-6 py-4 text-[#434343]">

                                {{ $user->telephone ?? '—' }}

                            </td>


                            {{-- RÔLE --}}
                            <td class="px-6 py-4">

                                @if($user->role)

                                    <span class="inline-flex
                                                 items-center
                                                 rounded-full
                                                 bg-[#e5f2ee]
                                                 px-3 py-1
                                                 text-xs font-semibold
                                                 text-[#006a4f]">

                                        {{ $user->role->nom }}

                                    </span>

                                @else

                                    <span class="text-gray-400">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- STATUT --}}
                            <td class="px-6 py-4 text-center">

                                @if($user->statut)

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

                                        Actif

                                    </span>

                                @else

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

                                        Inactif

                                    </span>

                                @endif

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
                                         VOIR / HISTORIQUE
                                    ====================================== --}}

                                    <a
                                        href="{{ route('users.show', $user) }}"
                                        title="Voir le profil et l'historique"
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
                                         ACTIONS AGENT RECENSEUR
                                    ====================================== --}}

                                    @if(
                                        auth()->user()->isDPA()
                                        && $user->role?->nom === 'Agent recenseur'
                                    )


                                        {{-- =================================
                                             AGENT ACTIF
                                        ================================== --}}

                                        @if($user->statut)


                                            {{-- =============================
                                                 PREMIÈRE AFFECTATION
                                            ============================== --}}

                                            @if(!$user->affectations()->exists())

                                                <a
                                                    href="{{ route('affectations.create', [
                                                        'user_id' => $user->id
                                                    ]) }}"
                                                    title="Affecter cet agent"
                                                    class="inline-flex
                                                           items-center
                                                           gap-2
                                                           rounded-lg
                                                           bg-[#006a4f]
                                                           px-3 py-2
                                                           text-xs
                                                           font-semibold
                                                           text-white
                                                           shadow-sm
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
                                                            d="M12 4v16
                                                               m8-8H4"
                                                        />
                                                    </svg>

                                                    Affecter

                                                </a>


                                            {{-- =============================
                                                 AGENT DÉJÀ AFFECTÉ
                                            ============================== --}}

                                            @else

                                                <a
                                                    href="{{ route('affectations.create', [
                                                        'user_id' => $user->id,
                                                        'reconduction' => 1
                                                    ]) }}"
                                                    title="Reconduire cet agent"
                                                    class="inline-flex
                                                           items-center
                                                           gap-2
                                                           rounded-lg
                                                           border
                                                           border-[#006a4f]
                                                           bg-white
                                                           px-3 py-2
                                                           text-xs
                                                           font-semibold
                                                           text-[#006a4f]
                                                           shadow-sm
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
                                                            d="M4 12a8 8 0 0113.657-5.657L20 8.686
                                                               M20 8.686V4
                                                               M20 8.686h-4.686
                                                               M20 12a8 8 0 01-13.657 5.657L4 15.314
                                                               M4 15.314V20
                                                               M4 15.314h4.686"
                                                        />
                                                    </svg>

                                                    Reconduire

                                                </a>

                                            @endif

                                        @endif

                                    @endif

                                </div>

                            </td>

                        </tr>


                    @empty

                        {{-- =============================================
                             AUCUN UTILISATEUR
                        ============================================== --}}

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
                                                d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2
                                                   m9-9a4 4 0 100-8 4 4 0 000 8
                                                   m5-3a3 3 0 100-6
                                                   m2 18v-2a4 4 0 00-3-3.87"
                                            />
                                        </svg>

                                    </div>

                                    <p class="font-semibold text-[#212529]">
                                        Aucun utilisateur trouvé
                                    </p>

                                    <p class="mt-1 text-sm text-gray-500">
                                        Aucun compte ne correspond à votre recherche.
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

    @if($users->hasPages())

        <div class="flex justify-center pt-2">

            {{ $users->lainks() }}

        </div>

    @endif

</div>

@endsection