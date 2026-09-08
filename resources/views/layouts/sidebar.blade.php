<aside
    x-cloak
    :class="sidebarCollapsed ? 'w-20' : 'w-72'"
    class="h-screen sticky top-0 bg-white shadow-[1px_0_2px_rgba(0,0,0,0.05)]
           flex flex-col relative font-sans transition-all duration-200
           ease-in-out shrink-0"
>

@php
    $user = auth()->user();
@endphp


{{-- ========================================================================
    EN-TÊTE
========================================================================= --}}
<div class="h-16 px-4 flex items-center justify-between border-b border-border shrink-0">

    <div class="flex items-center gap-3 min-w-0">

        <img
            src="{{ asset('images/logo_recensement_agricole_mo.png') }}"
            class="w-9 h-9 rounded-md shrink-0"
            alt="SIRA-MO"
        >

        <div
            class="min-w-0"
            x-show="!sidebarCollapsed"
            x-transition.opacity
        >
            <h2 class="text-sm font-bold text-text-primary leading-tight whitespace-nowrap">
                SIRA-Mô
            </h2>

            <p class="text-xs text-text-secondary whitespace-nowrap">
                Recensement Agricole
            </p>
        </div>

    </div>


    {{-- Bouton replier / déplier --}}
    <button
        type="button"
        @click="sidebarCollapsed = !sidebarCollapsed"
        class="hidden lg:flex w-8 h-8 items-center justify-center rounded-md
               border border-border text-text-secondary
               hover:bg-background-muted hover:text-primary
               transition-colors duration-150 ease-in-out shrink-0"
        :aria-label="sidebarCollapsed ? 'Déplier le menu' : 'Replier le menu'"
    >

        <svg
            class="w-4 h-4 transition-transform duration-200"
            :class="sidebarCollapsed ? 'rotate-180' : ''"
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

    </button>

</div>



{{-- ========================================================================
    NAVIGATION
========================================================================= --}}
<nav class="flex-1 px-3 py-5 overflow-y-auto overflow-x-hidden">


    {{-- ====================================================================
        TABLEAU DE BORD
    ===================================================================== --}}
    <a
        href="{{ route('dashboard') }}"
        :class="sidebarCollapsed ? 'justify-center px-0' : 'px-4'"
        class="flex items-center gap-3 py-3 rounded-md mb-1
               transition-colors duration-150 ease-in-out
               {{ request()->routeIs('dashboard')
                    ? 'bg-green-50 text-green-700 font-semibold'
                    : 'text-text-secondary hover:bg-background-muted' }}"
        title="Tableau de bord"
    >

        <svg
            class="w-5 h-5 shrink-0"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="1.8"
                d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h4a1 1 0 001-1V10"
            />
        </svg>

        <span
            x-show="!sidebarCollapsed"
            x-transition.opacity
            class="whitespace-nowrap"
        >
            Tableau de bord
        </span>

    </a>



    {{-- ====================================================================
        STATISTIQUES
        Accessible à Admin et DPA
    ===================================================================== --}}
    @if($user->isAdmin() || $user->isDpa())

        <a
            href="{{ route('statistiques.index') }}"
            :class="sidebarCollapsed ? 'justify-center px-0' : 'px-4'"
            class="flex items-center gap-3 py-3 rounded-md mb-1
                   transition-colors duration-150 ease-in-out
                   {{ request()->routeIs('statistiques.*')
                        ? 'bg-green-50 text-green-700 font-semibold'
                        : 'text-text-secondary hover:bg-background-muted' }}"
            title="Statistiques"
        >

            <svg
                class="w-5 h-5 shrink-0"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M4 19V5
                       M4 19h16
                       M8 16v-5
                       M12 16V8
                       M16 16v-8"
                />
            </svg>

            <span
                x-show="!sidebarCollapsed"
                x-transition.opacity
                class="whitespace-nowrap"
            >
                Statistiques
            </span>

        </a>

    @endif



    {{-- ====================================================================
        ADMINISTRATEUR
    ===================================================================== --}}
    @if($user->isAdmin())

        <div class="mt-6">

            <p
                x-show="!sidebarCollapsed"
                class="text-xs uppercase tracking-widest
                       text-text-muted mb-3 px-1 whitespace-nowrap"
            >
                Administration
            </p>


            {{-- UTILISATEURS --}}
            <a
                href="{{ route('users.index') }}"
                :class="sidebarCollapsed ? 'justify-center px-0' : 'px-4'"
                class="flex items-center gap-3 py-3 rounded-md mb-1
                       transition-colors duration-150 ease-in-out
                       {{ request()->routeIs('users.*')
                            ? 'bg-green-50 text-green-700 font-semibold'
                            : 'text-text-secondary hover:bg-background-muted' }}"
                title="Utilisateurs"
            >

                <svg
                    class="w-5 h-5 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M17 20h5v-2a4 4 0 00-3-3.87
                           M9 20H4v-2a4 4 0 013-3.87
                           m5-9.13a4 4 0 110 8 4 4 0 010-8
                           m6 4a4 4 0 100 8"
                    />
                </svg>

                <span
                    x-show="!sidebarCollapsed"
                    x-transition.opacity
                    class="whitespace-nowrap"
                >
                    Utilisateurs
                </span>

            </a>


            {{-- CAMPAGNES --}}
            <a
                href="{{ route('campagnes.index') }}"
                :class="sidebarCollapsed ? 'justify-center px-0' : 'px-4'"
                class="flex items-center gap-3 py-3 rounded-md mb-1
                       transition-colors duration-150 ease-in-out
                       {{ request()->routeIs('campagnes.*')
                            ? 'bg-green-50 text-green-700 font-semibold'
                            : 'text-text-secondary hover:bg-background-muted' }}"
                title="Campagnes"
            >

                <svg
                    class="w-5 h-5 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M8 7V3
                           m8 4V3
                           M4 11h16
                           M5 5h14a1 1 0 011 1v13
                           a1 1 0 01-1 1H5
                           a1 1 0 01-1-1V6
                           a1 1 0 011-1z"
                    />
                </svg>

                <span
                    x-show="!sidebarCollapsed"
                    x-transition.opacity
                    class="whitespace-nowrap"
                >
                    Campagnes
                </span>

            </a>


            {{-- TERRITOIRE --}}
            <a
                href="{{ route('regions.index') }}"
                :class="sidebarCollapsed ? 'justify-center px-0' : 'px-4'"
                class="flex items-center gap-3 py-3 rounded-md mb-1
                       transition-colors duration-150 ease-in-out
                       {{ request()->routeIs(
                            'regions.*',
                            'prefectures.*',
                            'communes.*',
                            'cantons.*',
                            'villages.*'
                       )
                            ? 'bg-green-50 text-green-700 font-semibold'
                            : 'text-text-secondary hover:bg-background-muted' }}"
                title="Territoire"
            >

                <svg
                    class="w-5 h-5 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 21s7-5.2 7-11a7 7 0 10-14 0c0 5.8 7 11 7 11z"
                    />

                    <circle
                        cx="12"
                        cy="10"
                        r="2.5"
                        stroke-width="1.8"
                    />
                </svg>

                <span
                    x-show="!sidebarCollapsed"
                    x-transition.opacity
                    class="whitespace-nowrap"
                >
                    Territoire
                </span>

            </a>

        </div>

    @endif



    {{-- ====================================================================
        DPA
    ===================================================================== --}}
    @if($user->isDpa())

        <div class="mt-6">

            <p
                x-show="!sidebarCollapsed"
                class="mb-3 px-1 text-xs uppercase tracking-widest
                       text-text-muted whitespace-nowrap"
            >
                Gestion préfectorale
            </p>


            {{-- ============================================================
                CAMPAGNES
            ============================================================= --}}
            <a
                href="{{ route('dpa.campagnes.index') }}"
                :class="[
                    sidebarCollapsed ? 'justify-center px-0' : 'px-4',
                    {{ request()->routeIs('dpa.campagnes.*') ? 'true' : 'false' }}
                        ? 'bg-green-50 text-green-700 font-semibold'
                        : 'text-text-secondary hover:bg-background-muted'
                ]"
                class="mb-1 flex items-center gap-3 rounded-md py-3
                       transition-colors duration-150 ease-in-out"
                title="Campagnes"
            >

                <svg
                    class="h-5 w-5 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M8 7V3
                           m8 4V3
                           M4 11h16
                           M5 5h14a1 1 0 011 1v13
                           a1 1 0 01-1 1H5
                           a1 1 0 01-1-1V6
                           a1 1 0 011-1z"
                    />
                </svg>

                <span
                    x-show="!sidebarCollapsed"
                    x-transition.opacity
                    class="whitespace-nowrap"
                >
                    Campagnes
                </span>

            </a>


            {{-- ============================================================
                PLANIFICATIONS
            ============================================================= --}}
            <a
                href="{{ route('dpa.planifications-prefectorales.index') }}"
                :class="[
                    sidebarCollapsed ? 'justify-center px-0' : 'px-4',
                    {{ request()->routeIs('dpa.planifications-prefectorales.*') ? 'true' : 'false' }}
                        ? 'bg-green-50 text-green-700 font-semibold'
                        : 'text-text-secondary hover:bg-background-muted'
                ]"
                class="mb-1 flex items-center gap-3 rounded-md py-3
                       transition-colors duration-150 ease-in-out"
                title="Planifications"
            >

                <svg
                    class="h-5 w-5 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M7 3v4
                           M17 3v4
                           M4 9h16
                           M6 5h12a2 2 0 012 2v12
                           a2 2 0 01-2 2H6
                           a2 2 0 01-2-2V7
                           a2 2 0 012-2z"
                    />

                    <path
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M8 13h2
                           M12 13h2
                           M16 13h.01
                           M8 17h2
                           M12 17h2"
                    />
                </svg>

                <span
                    x-show="!sidebarCollapsed"
                    x-transition.opacity
                    class="whitespace-nowrap"
                >
                    Planifications
                </span>

            </a>


            {{-- ============================================================
                AGENTS & SUPERVISEURS
            ============================================================= --}}
            <a
                href="{{ route('dpa.agents.index') }}"
                :class="[
                    sidebarCollapsed ? 'justify-center px-0' : 'px-4',
                    {{ request()->routeIs('dpa.agents.*') ? 'true' : 'false' }}
                        ? 'bg-green-50 text-green-700 font-semibold'
                        : 'text-text-secondary hover:bg-background-muted'
                ]"
                class="mb-1 flex items-center gap-3 rounded-md py-3
                       transition-colors duration-150 ease-in-out"
                title="Agents recenseurs"
            >

                <svg
                    class="h-5 w-5 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <circle
                        cx="12"
                        cy="7"
                        r="4"
                        stroke-width="1.8"
                    />

                    <path
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M5 21a7 7 0 0114 0"
                    />

                    <path
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M18 4l1 1 2-2"
                    />
                </svg>

                <span
                    x-show="!sidebarCollapsed"
                    x-transition.opacity
                    class="whitespace-nowrap"
                >
                    Agents & Superviseurs
                </span>

            </a>


            {{-- ============================================================
                ÉQUIPES
            ============================================================= --}}
            <a
                href="{{ route('dpa.equipes.index') }}"
                :class="[
                    sidebarCollapsed ? 'justify-center px-0' : 'px-4',
                    {{ request()->routeIs('dpa.equipes.*') ? 'true' : 'false' }}
                        ? 'bg-green-50 text-green-700 font-semibold'
                        : 'text-text-secondary hover:bg-background-muted'
                ]"
                class="mb-1 flex items-center gap-3 rounded-md py-3
                       transition-colors duration-150 ease-in-out"
                title="Équipes"
            >

                <svg
                    class="h-5 w-5 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"
                    />

                    <circle
                        cx="9"
                        cy="7"
                        r="4"
                        stroke-width="1.8"
                    />

                    <path
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M22 21v-2a4 4 0 00-3-3.87
                           M16 3.13a4 4 0 010 7.75"
                    />
                </svg>

                <span
                    x-show="!sidebarCollapsed"
                    x-transition.opacity
                    class="whitespace-nowrap"
                >
                    Équipes
                </span>

            </a>


            {{-- ============================================================
                DÉPLOIEMENT DES AGENTS
            ============================================================= --}}
            <a
                href="{{ route('dpa.affectations.index') }}"
                :class="[
                    sidebarCollapsed ? 'justify-center px-0' : 'px-4',
                    {{ request()->routeIs('dpa.affectations.*') ? 'true' : 'false' }}
                        ? 'bg-green-50 text-green-700 font-semibold'
                        : 'text-text-secondary hover:bg-background-muted'
                ]"
                class="mb-1 flex items-center gap-3 rounded-md py-3
                       transition-colors duration-150 ease-in-out"
                title="Déploiement des agents"
            >

                <svg
                    class="h-5 w-5 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >

                    <circle
                        cx="9"
                        cy="8"
                        r="3"
                        stroke-width="1.8"
                    />

                    <path
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 20v-1a6 6 0 0112 0v1"
                    />

                    <path
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M16 8h5
                           m0 0l-3-3
                           m3 3l-3 3"
                    />

                    <path
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M16 14h5
                           m0 0l-3-3
                           m3 3l-3 3"
                    />

                </svg>

                <span
                    x-show="!sidebarCollapsed"
                    x-transition.opacity
                    class="whitespace-nowrap"
                >
                    Déploiement des agents
                </span>

            </a>


            {{-- ============================================================
                SUIVI & STATISTIQUES
            ============================================================= --}}
            <a
                href="{{ route('statistiques.index') }}"
                :class="[
                    sidebarCollapsed ? 'justify-center px-0' : 'px-4',
                    {{ request()->routeIs('statistiques.*') ? 'true' : 'false' }}
                        ? 'bg-green-50 text-green-700 font-semibold'
                        : 'text-text-secondary hover:bg-background-muted'
                ]"
                class="mb-1 flex items-center gap-3 rounded-md py-3
                       transition-colors duration-150 ease-in-out"
                title="Suivi & statistiques"
            >

                <svg
                    class="h-5 w-5 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >

                    <path
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M4 19V5"
                    />

                    <path
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M4 19h17"
                    />

                    <path
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M8 16v-5
                           M12 16V8
                           M16 16v-4
                           M20 16V6"
                    />

                    <path
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M8 9l3-3 3 2 5-5"
                    />

                </svg>

                <span
                    x-show="!sidebarCollapsed"
                    x-transition.opacity
                    class="whitespace-nowrap"
                >
                    Suivi & statistiques
                </span>

            </a>

        </div>

    @endif



    {{-- ====================================================================
        AGENT RECENSEUR
    ===================================================================== --}}
    @if($user->isAgent())

        <div class="mt-6">

            <p
                x-show="!sidebarCollapsed"
                class="mb-3 px-1 text-xs uppercase tracking-widest text-text-muted whitespace-nowrap"
            >
                Recensement
            </p>

            {{-- Tableau de bord / Ma campagne --}}
            {{-- <a
                href="{{ route('dashboard') }}"
                :class="sidebarCollapsed ? 'justify-center px-0' : 'px-4'"
                class="mb-1 flex items-center gap-3 rounded-md py-3 transition-colors duration-150 ease-in-out
                    {{ request()->routeIs('dashboard')
                        ? 'bg-green-50 text-green-700 font-semibold'
                        : 'text-text-secondary hover:bg-background-muted' }}"
                title="Ma campagne"
            >
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                        d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/>
                </svg>

                <span x-show="!sidebarCollapsed" x-transition.opacity>
                    Ma campagne
                </span>
            </a> --}}

            {{-- Mes affectations --}}
            <a
                href="{{ route('agent.affectations') }}"
                :class="sidebarCollapsed ? 'justify-center px-0' : 'px-4'"
                class="mb-1 flex items-center gap-3 rounded-md py-3 transition-colors duration-150 ease-in-out
                    {{ request()->routeIs('agent.affectations')
                        ? 'bg-green-50 text-green-700 font-semibold'
                        : 'text-text-secondary hover:bg-background-muted' }}"
                title="Mes affectations"
            >
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a3 3 0 006 0M9 12h6M9 16h4"/>
                </svg>

                <span x-show="!sidebarCollapsed" x-transition.opacity>
                    Mes affectations
                </span>
            </a>

            {{-- Identification (désactivé pour le moment) --}}
            <a
                href="#"
                onclick="return false"
                :class="sidebarCollapsed ? 'justify-center px-0' : 'px-4'"
                class="mb-1 flex items-center gap-3 rounded-md py-3 text-gray-400 cursor-not-allowed"
                title="Bientôt disponible"
            >
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="9" cy="8" r="3" stroke-width="1.8"/>
                    <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                        d="M3 20v-1a6 6 0 0112 0v1"/>
                </svg>

                <span x-show="!sidebarCollapsed" x-transition.opacity>
                    Identification
                </span>

                <span x-show="!sidebarCollapsed" class="ml-auto text-[10px] px-2 py-0.5 rounded-full bg-gray-100">
                    Bientôt
                </span>
            </a>

            {{-- Recensement (désactivé) --}}
            <a
                href="#"
                onclick="return false"
                :class="sidebarCollapsed ? 'justify-center px-0' : 'px-4'"
                class="mb-1 flex items-center gap-3 rounded-md py-3 text-gray-400 cursor-not-allowed"
                title="Bientôt disponible"
            >
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                        d="M6 4h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                    <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                        d="M8 8h8M8 12h8M8 16h5"/>
                </svg>

                <span x-show="!sidebarCollapsed" x-transition.opacity>
                    Recensement
                </span>

                <span x-show="!sidebarCollapsed" class="ml-auto text-[10px] px-2 py-0.5 rounded-full bg-gray-100">
                    Bientôt
                </span>
            </a>

            {{-- Synchronisation (désactivé) --}}
            <a
                href="#"
                onclick="return false"
                :class="sidebarCollapsed ? 'justify-center px-0' : 'px-4'"
                class="mb-1 flex items-center gap-3 rounded-md py-3 text-gray-400 cursor-not-allowed"
                title="Bientôt disponible"
            >
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                        d="M4 12a8 8 0 0114.9-4M20 12a8 8 0 01-14.9 4"/>
                    <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                        d="M19 4v4h-4M5 20v-4h4"/>
                </svg>

                <span x-show="!sidebarCollapsed" x-transition.opacity>
                    Synchronisation
                </span>

                <span x-show="!sidebarCollapsed" class="ml-auto text-[10px] px-2 py-0.5 rounded-full bg-gray-100">
                    Bientôt
                </span>
            </a>

        </div>

    @endif


    {{-- ====================================================================
        SUPERVISEUR
    ==================================================================== --}}
    @if($user->isSuperviseur())

    <div class="mt-6">

        <p
            x-show="!sidebarCollapsed"
            class="mb-3 px-1 text-xs uppercase tracking-widest text-text-muted whitespace-nowrap"
        >
            Supervision
        </p>

        {{-- ============================================================
            MES ÉQUIPES
        ============================================================= --}}
        <a
            href="{{ route('superviseur.equipes.index') }}"
            :class="sidebarCollapsed ? 'justify-center px-0' : 'px-4'"
            class="mb-1 flex items-center gap-3 rounded-md py-3 transition-colors duration-150 ease-in-out
                {{ request()->routeIs('superviseur.equipes.*')
                    ? 'bg-green-50 text-green-700 font-semibold'
                    : 'text-text-secondary hover:bg-background-muted' }}"
            title="Mes équipes"
        >

            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                    d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/>
                <circle cx="9" cy="7" r="4" stroke-width="1.8"/>
                <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                    d="M22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
            </svg>

            <span x-show="!sidebarCollapsed" x-transition.opacity>
                Mes équipes
            </span>

        </a>

        {{-- ============================================================
            MES ZONES
        ============================================================= --}}
        <a
            href="{{ route('superviseur.zones.index') }}"
            :class="sidebarCollapsed ? 'justify-center px-0' : 'px-4'"
            class="mb-1 flex items-center gap-3 rounded-md py-3 transition-colors duration-150 ease-in-out
                {{ request()->routeIs('superviseur.zones.*')
                    ? 'bg-green-50 text-green-700 font-semibold'
                    : 'text-text-secondary hover:bg-background-muted' }}"
            title="Mes zones"
        >

            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.553-.832L9 7m0 13l6-3m-6 3V7m6 10l5.447 2.724A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
            </svg>

            <span x-show="!sidebarCollapsed" x-transition.opacity>
                Mes zones
            </span>

        </a>

        {{-- ============================================================
            SUIVI DU RECENSEMENT
        ============================================================= --}}
        <a
            href="{{ route('superviseur.suivi.index') }}"
            :class="sidebarCollapsed ? 'justify-center px-0' : 'px-4'"
            class="mb-1 flex items-center gap-3 rounded-md py-3 transition-colors duration-150 ease-in-out
                {{ request()->routeIs('superviseur.suivi.*')
                    ? 'bg-green-50 text-green-700 font-semibold'
                    : 'text-text-secondary hover:bg-background-muted' }}"
            title="Suivi du recensement"
        >

            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                    d="M4 19V5"/>
                <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                    d="M4 19h16"/>
                <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                    d="M8 16v-5M12 16V8M16 16v-3M20 16V6"/>
            </svg>

            <span x-show="!sidebarCollapsed" x-transition.opacity>
                Suivi du recensement
            </span>

        </a>

        {{-- CONTRÔLE QUALITÉ --}}
        <a
            href="{{ route('superviseur.controle-qualite.index') }}"
            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                {{ request()->routeIs('superviseur.controle-qualite.*')
                    ? 'bg-[#006a4f] text-white'
                    : 'text-gray-700 hover:bg-gray-100' }}"
        >
            <svg
                class="h-5 w-5 shrink-0"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.8"
                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622C17.176 19.29 21 14.591 21 9c0-1.07-.14-2.107-.402-3.016z"
                />
            </svg>

            <span>Contrôle qualité</span>
        </a>

    </div>

    @endif
</nav>



{{-- ========================================================================
    PIED DE SIDEBAR
========================================================================= --}}
<div class="border-t border-border p-3 shrink-0 space-y-1">


    {{-- ====================================================================
        MON COMPTE
    ===================================================================== --}}
    <a
        href="{{ route('profile.edit') }}"
        :class="[
            sidebarCollapsed ? 'justify-center px-0' : 'px-3',
            {{ request()->routeIs('profile.edit') ? 'true' : 'false' }}
                ? 'bg-green-50 text-green-700 font-semibold'
                : 'text-text-secondary hover:bg-background-muted'
        ]"
        class="flex items-center gap-3 py-2 rounded-md
               transition-colors duration-150 ease-in-out"
        title="Mon compte"
    >

        <svg
            class="w-5 h-5 shrink-0"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="1.8"
                d="M20 21a8 8 0 00-16 0
                   M12 13a4 4 0 100-8
                   4 4 0 000 8z"
            />
        </svg>

        <span
            x-show="!sidebarCollapsed"
            x-transition.opacity
            class="whitespace-nowrap"
        >
            Mon compte
        </span>

    </a>



    {{-- ====================================================================
        DÉCONNEXION
    ===================================================================== --}}
    <form
        method="POST"
        action="{{ route('logout') }}"
    >
        @csrf

        <button
            type="submit"
            :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3'"
            class="w-full flex items-center gap-3 py-2 rounded-md
                   text-accent-red hover:bg-red-50
                   transition-colors duration-150 ease-in-out"
            title="Déconnexion"
        >

            <svg
                class="w-5 h-5 shrink-0"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.8"
                    d="M15 12H3
                       M7 8l-4 4 4 4
                       M21 5v14a2 2 0 01-2 2h-6
                       M15 7V5a2 2 0 00-2-2H7"
                />
            </svg>

            <span
                x-show="!sidebarCollapsed"
                x-transition.opacity
                class="whitespace-nowrap"
            >
                Déconnexion
            </span>

        </button>

    </form>

</div>

</aside>