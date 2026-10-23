@php
    $user = auth()->user();
@endphp

{{-- ========================================================================
    OVERLAY MOBILE
========================================================================= --}}
<div
    x-cloak
    x-show="sidebarOpen"
    x-transition.opacity
    @click="sidebarOpen = false"
    class="fixed inset-0 z-40 bg-black/50 lg:hidden"
    aria-hidden="true"
></div>


{{-- ========================================================================
    SIDEBAR
========================================================================= --}}
<aside
    x-cloak

    {{-- Desktop : repliable --}}
    :class="[
        sidebarCollapsed ? 'lg:w-20' : 'lg:w-72',

        {{-- Mobile : ouverte / fermée --}}
        sidebarOpen
            ? 'translate-x-0'
            : '-translate-x-full lg:translate-x-0'
    ]"

    class="fixed inset-y-0 left-0 z-50
           w-72
           bg-white
           shadow-[1px_0_4px_rgba(0,0,0,0.08)]
           flex flex-col
           font-sans
           transition-all duration-200 ease-in-out
           shrink-0
           lg:sticky lg:top-0 lg:h-screen
           lg:translate-x-0"
>


    {{-- ====================================================================
        EN-TÊTE
    ===================================================================== --}}
    <div
        class="h-16 px-4 flex items-center justify-between
               border-b border-border shrink-0"
    >

        <div class="flex items-center gap-3 min-w-0">

            <img
                src="{{ asset('images/im.png') }}"
                class="w-9 h-9 rounded-md shrink-0"
                alt="SIRA-Mô"
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


        {{-- ================================================================
            FERMER MOBILE
        ================================================================= --}}
        <button
            type="button"
            @click="sidebarOpen = false"
            class="lg:hidden
                   w-8 h-8
                   flex items-center justify-center
                   rounded-md
                   border border-border
                   text-text-secondary
                   hover:bg-background-muted
                   hover:text-primary
                   transition-colors
                   shrink-0"
            aria-label="Fermer le menu"
        >
            <svg
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 6l12 12M18 6L6 18"
                />
            </svg>
        </button>


        {{-- ================================================================
            REPLIER / DÉPLIER DESKTOP
        ================================================================= --}}
        <button
            type="button"
            @click="sidebarCollapsed = !sidebarCollapsed"
            class="hidden lg:flex
                   w-8 h-8
                   items-center justify-center
                   rounded-md
                   border border-border
                   text-text-secondary
                   hover:bg-background-muted
                   hover:text-primary
                   transition-colors
                   duration-150
                   shrink-0"
            :aria-label="
                sidebarCollapsed
                    ? 'Déplier le menu'
                    : 'Replier le menu'
            "
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



    {{-- ====================================================================
        NAVIGATION
    ===================================================================== --}}
    <nav
        class="flex-1
               px-3
               py-5
               overflow-y-auto
               overflow-x-hidden
               overscroll-contain"
    >


        {{-- =================================================================
            TABLEAU DE BORD
        ================================================================== --}}
        <a
            href="{{ route('dashboard') }}"
            @click="sidebarOpen = false"
            :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
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
                    d="M3 12l9-9 9-9M5 10v10a1 1 0 001 1h4a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h4a1 1 0 001-1V10"
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


        {{-- =================================================================
            ADMINISTRATEUR
        ================================================================== --}}
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
                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                    class="flex items-center gap-3 py-3 rounded-md mb-1
                           transition-colors duration-150
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
                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                    class="flex items-center gap-3 py-3 rounded-md mb-1
                           transition-colors duration-150
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


                {{-- SAISONS DE DISTRIBUTION --}}
                <a
                    href="{{ route('saison-distributions.index') }}"
                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                    class="flex items-center gap-3 py-3 rounded-md mb-1
                           transition-colors duration-150
                           {{ request()->routeIs('saison-distributions.*')
                                ? 'bg-green-50 text-green-700 font-semibold'
                                : 'text-text-secondary hover:bg-background-muted' }}"
                    title="Saisons de Distribution"
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
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                        />
                    </svg>

                    <span
                        x-show="!sidebarCollapsed"
                        x-transition.opacity
                        class="whitespace-nowrap"
                    >
                        Saisons de Distribution
                    </span>
                </a>


                {{-- CONSULTATION GLOBAL INTRANTS & BESOINS (ADMIN) --}}
                <a
                    href="#"
                    {{-- href="{{ route('admin.intrants.index') }}" --}}

                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                    class="flex items-center gap-3 py-3 rounded-md mb-1
                           transition-colors duration-150
                           {{ request()->routeIs('admin.intrants.*')
                                ? 'bg-green-50 text-green-700 font-semibold'
                                : 'text-text-secondary hover:bg-background-muted' }}"
                    title="Consultation Intrants & Stocks"
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
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                        />
                    </svg>

                    <span
                        x-show="!sidebarCollapsed"
                        x-transition.opacity
                        class="whitespace-nowrap"
                    >
                        Consultation Intrants
                    </span>
                </a>


                {{-- STATISTIQUES --}}
                <a
                    href="{{ route('statistiques.index') }}"
                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
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


                {{-- JOURNAL D'ACTIVITÉS --}}
                <a
                    href="#"
                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                    class="flex items-center gap-3 py-3 rounded-md mb-1
                           transition-colors duration-150
                           {{ request()->routeIs('activites.*')
                                ? 'bg-green-50 text-green-700 font-semibold'
                                : 'text-text-secondary hover:bg-background-muted' }}"
                    title="Journal d'activités"
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
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"
                        />
                    </svg>

                    <span
                        x-show="!sidebarCollapsed"
                        x-transition.opacity
                        class="whitespace-nowrap"
                    >
                        Journal d'activités
                    </span>

                </a>

            </div>

        @endif



        {{-- =================================================================
            DPA (DIRECTION PRÉFECTORALE DE L'AGRICULTURE)
        ================================================================== --}}
        @if($user->isDpa())

            <div class="mt-6">

                <p
                    x-show="!sidebarCollapsed"
                    class="mb-3 px-1 text-xs uppercase tracking-widest
                           text-text-muted whitespace-nowrap"
                >
                    Gestion préfectorale
                </p>


                {{-- CAMPAGNES --}}
                <a
                    href="{{ route('dpa.campagnes.index') }}"
                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                    class="mb-1 flex items-center gap-3 rounded-md py-3
                           transition-colors duration-150
                           {{ request()->routeIs('dpa.campagnes.*')
                                ? 'bg-green-50 text-green-700 font-semibold'
                                : 'text-text-secondary hover:bg-background-muted' }}"
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


                {{-- PLANIFICATIONS --}}
                <a
                    href="{{ route('dpa.planifications-prefectorales.index') }}"
                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                    class="mb-1 flex items-center gap-3 rounded-md py-3
                           transition-colors duration-150
                           {{ request()->routeIs('dpa.planifications-prefectorales.*')
                                ? 'bg-green-50 text-green-700 font-semibold'
                                : 'text-text-secondary hover:bg-background-muted' }}"
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


                {{-- AGENTS & SUPERVISEURS --}}
                <a
                    href="{{ route('dpa.agents.index') }}"
                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                    class="mb-1 flex items-center gap-3 rounded-md py-3
                           transition-colors duration-150
                           {{ request()->routeIs('dpa.agents.*')
                                ? 'bg-green-50 text-green-700 font-semibold'
                                : 'text-text-secondary hover:bg-background-muted' }}"
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


                {{-- ÉQUIPES --}}
                <a
                    href="{{ route('dpa.equipes.index') }}"
                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                    class="mb-1 flex items-center gap-3 rounded-md py-3
                           transition-colors duration-150
                           {{ request()->routeIs('dpa.equipes.*')
                                ? 'bg-green-50 text-green-700 font-semibold'
                                : 'text-text-secondary hover:bg-background-muted' }}"
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


                {{-- DÉPLOIEMENT --}}
                <a
                    href="{{ route('dpa.affectations.index') }}"
                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                    class="mb-1 flex items-center gap-3 rounded-md py-3
                           transition-colors duration-150
                           {{ request()->routeIs('dpa.affectations.*')
                                ? 'bg-green-50 text-green-700 font-semibold'
                                : 'text-text-secondary hover:bg-background-muted' }}"
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


                {{-- =========================================================
                    SECTION MODULE INTRANTS (GESTION DPA)
                ========================================================= --}}
                <div class="mt-4 pt-3 border-t border-slate-100">
                    <p
                        x-show="!sidebarCollapsed"
                        class="mb-2 px-1 text-[10px] uppercase tracking-wider text-slate-400 font-semibold whitespace-nowrap"
                    >
                        Gestion des Intrants
                    </p>

                    {{-- 1. BARÈMES AGRONOMIQUES --}}
                    <a
                        href="#"
                        {{-- href="{{ route('dpa.baremes.index') }}" --}}

                        @click="sidebarOpen = false"
                        :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                        class="mb-1 flex items-center gap-3 rounded-md py-2.5 transition-colors duration-150
                               {{ request()->routeIs('dpa.baremes.*')
                                    ? 'bg-green-50 text-green-700 font-semibold'
                                    : 'text-text-secondary hover:bg-background-muted' }}"
                        title="Barèmes agronomiques (Dose/ha)"
                    >
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap">
                            Barèmes Agronomiques
                        </span>
                    </a>

                    {{-- 2. CONSOLIDATION DES BESOINS --}}
                    <a
                        href="#"

                        {{-- href="{{ route('dpa.besoins.index') }}" --}}
                        @click="sidebarOpen = false"
                        :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                        class="mb-1 flex items-center gap-3 rounded-md py-2.5 transition-colors duration-150
                               {{ request()->routeIs('dpa.besoins.*')
                                    ? 'bg-green-50 text-green-700 font-semibold'
                                    : 'text-text-secondary hover:bg-background-muted' }}"
                        title="Consolidation des besoins"
                    >
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap">
                            Consolidation Besoins
                        </span>
                    </a>

                    {{-- 3. ALLOCATIONS & QUOTAS --}}
                    <a
                        href="#"

                        {{-- href="{{ route('dpa.allocations.index') }}" --}}
                        @click="sidebarOpen = false"
                        :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                        class="mb-1 flex items-center gap-3 rounded-md py-2.5 transition-colors duration-150
                               {{ request()->routeIs('dpa.allocations.*')
                                    ? 'bg-green-50 text-green-700 font-semibold'
                                    : 'text-text-secondary hover:bg-background-muted' }}"
                        title="Allocations et plafonnements"
                    >
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap">
                            Allocations & Quotas
                        </span>
                    </a>

                    {{-- 4. DISTRIBUTIONS --}}
                    <a
                        href="#"

                        {{-- href="{{ route('dpa.distributions.index') }}" --}}
                        @click="sidebarOpen = false"
                        :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                        class="mb-1 flex items-center gap-3 rounded-md py-2.5 transition-colors duration-150
                               {{ request()->routeIs('dpa.distributions.*')
                                    ? 'bg-green-50 text-green-700 font-semibold'
                                    : 'text-text-secondary hover:bg-background-muted' }}"
                        title="Suivi des distributions"
                    >
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                        </svg>
                        <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap">
                            Suivi Distributions
                        </span>
                    </a>
                </div>


                {{-- SUIVI & STATISTIQUES --}}
                <a
                    href="{{ route('statistiques.index') }}"
                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                    class="mt-3 mb-1 flex items-center gap-3 rounded-md py-3
                           transition-colors duration-150
                           {{ request()->routeIs('statistiques.*')
                                ? 'bg-green-50 text-green-700 font-semibold'
                                : 'text-text-secondary hover:bg-background-muted' }}"
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
                            d="M4 19V5
                               M4 19h17
                               M8 16v-5
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



        {{-- =================================================================
            AGENT RECENSEUR
        ================================================================== --}}
        @if($user->isAgent())

            <div class="mt-6">

                <p
                    x-show="!sidebarCollapsed"
                    class="mb-3 px-1 text-xs uppercase tracking-widest
                           text-text-muted whitespace-nowrap"
                >
                    Recensement
                </p>


                {{-- MES AFFECTATIONS --}}
                <a
                    href="{{ route('agent.affectations') }}"
                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                    class="mb-1 flex items-center gap-3 rounded-md py-3
                           transition-colors duration-150
                           {{ request()->routeIs('agent.affectations*')
                                ? 'bg-green-50 text-green-700 font-semibold'
                                : 'text-text-secondary hover:bg-background-muted' }}"
                    title="Mes affectations"
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
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                               M9 5a3 3 0 006 0
                               M9 12h6
                               M9 16h4"
                        />
                    </svg>

                    <span
                        x-show="!sidebarCollapsed"
                        x-transition.opacity
                        class="whitespace-nowrap"
                    >
                        Mes affectations
                    </span>

                </a>


                {{-- MES RECENSEMENTS --}}
                <a
                    href="{{ route('agent.recensements.index') }}"
                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                    class="mb-1 flex items-center gap-3 rounded-md py-3
                           transition-colors duration-150
                           {{ request()->routeIs('agent.recensements*') && !request()->has('statut')
                                ? 'bg-green-50 text-green-700 font-semibold'
                                : 'text-text-secondary hover:bg-background-muted' }}"
                    title="Mes recensements"
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
                            d="M8 6h11
                               M8 12h11
                               M8 18h11"
                        />

                        <circle cx="4" cy="6" r="1"/>
                        <circle cx="4" cy="12" r="1"/>
                        <circle cx="4" cy="18" r="1"/>
                    </svg>

                    <span
                        x-show="!sidebarCollapsed"
                        x-transition.opacity
                        class="whitespace-nowrap"
                    >
                        Mes recensements
                    </span>

                </a>


                {{-- À RAPPELER / INCOMPLETS --}}
                <a
                    href="{{ route('agent.recensements.index', ['statut' => 'en_attente']) }}"
                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                    class="mb-1 flex items-center gap-3 rounded-md py-3
                           transition-colors duration-150
                           {{ request()->routeIs('agent.recensements*') && request('statut') === 'en_attente'
                                ? 'bg-amber-50 text-amber-700 font-semibold'
                                : 'text-text-secondary hover:bg-background-muted' }}"
                    title="Dossiers à rappeler ou incomplets"
                >

                    <svg
                        class="h-5 w-5 shrink-0 text-amber-500"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>

                    <span
                        x-show="!sidebarCollapsed"
                        x-transition.opacity
                        class="whitespace-nowrap"
                    >
                        À rappeler / Incomplets
                    </span>

                </a>

            </div>

        @endif



        {{-- =================================================================
            SUPERVISEUR
        ================================================================== --}}
        @if($user->isSuperviseur())

            <div class="mt-6">

                <p
                    x-show="!sidebarCollapsed"
                    class="mb-3 px-1 text-xs uppercase tracking-widest
                           text-text-muted whitespace-nowrap"
                >
                    Supervision
                </p>


                {{-- MES ÉQUIPES --}}
                <a
                    href="{{ route('superviseur.equipes.index') }}"
                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                    class="mb-1 flex items-center gap-3 rounded-md py-3
                           transition-colors duration-150
                           {{ request()->routeIs('superviseur.equipes.*')
                                ? 'bg-green-50 text-green-700 font-semibold'
                                : 'text-text-secondary hover:bg-background-muted' }}"
                    title="Mes équipes"
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
                        Mes équipes
                    </span>

                </a>


                {{-- MES ZONES --}}
                <a
                    href="{{ route('superviseur.zones.index') }}"
                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                    class="mb-1 flex items-center gap-3 rounded-md py-3
                           transition-colors duration-150
                           {{ request()->routeIs('superviseur.zones.*')
                                ? 'bg-green-50 text-green-700 font-semibold'
                                : 'text-text-secondary hover:bg-background-muted' }}"
                    title="Mes zones"
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
                            d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.553-.832L9 7
                               m0 13l6-3
                               m-6 3V7
                               m6 10l5.447 2.724A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4
                               m0 13V4
                               m0 0L9 7"
                        />
                    </svg>

                    <span
                        x-show="!sidebarCollapsed"
                        x-transition.opacity
                        class="whitespace-nowrap"
                    >
                        Mes zones
                    </span>

                </a>


                {{-- CONSULTATION BESOIN INTRANTS (SUPERVISEUR) --}}
                <a
                    href="{{ route('superviseur.besoins.index') }}"
                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                    class="mb-1 flex items-center gap-3 rounded-md py-3
                           transition-colors duration-150
                           {{ request()->routeIs('superviseur.besoins.*')
                                ? 'bg-green-50 text-green-700 font-semibold'
                                : 'text-text-secondary hover:bg-background-muted' }}"
                    title="Consultation des Besoins"
                >
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>

                    <span
                        x-show="!sidebarCollapsed"
                        x-transition.opacity
                        class="whitespace-nowrap"
                    >
                        Consultation Besoins
                    </span>
                </a>


                {{-- SUIVI --}}
                <a
                    href="{{ route('superviseur.suivi.index') }}"
                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                    class="mb-1 flex items-center gap-3 rounded-md py-3
                           transition-colors duration-150
                           {{ request()->routeIs('superviseur.suivi.*')
                                ? 'bg-green-50 text-green-700 font-semibold'
                                : 'text-text-secondary hover:bg-background-muted' }}"
                    title="Suivi du recensement"
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
                            d="M4 19V5
                               M4 19h16
                               M8 16v-5
                               M12 16V8
                               M16 16v-3
                               M20 16V6"
                        />
                    </svg>

                    <span
                        x-show="!sidebarCollapsed"
                        x-transition.opacity
                        class="whitespace-nowrap"
                    >
                        Suivi du recensement
                    </span>

                </a>


                {{-- CONTRÔLE QUALITÉ --}}
                <a
                    href="{{ route('superviseur.controle-qualite.index') }}"
                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-3'"
                    class="flex items-center gap-3 rounded-lg py-2.5
                           text-sm font-medium transition
                           {{ request()->routeIs('superviseur.controle-qualite.*')
                                ? 'bg-[#006a4f] text-white'
                                : 'text-gray-700 hover:bg-gray-100' }}"
                    title="Contrôle qualité"
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
                            d="M9 12l2 2 4-4
                               m5.618-4.016A11.955 11.955 0 0112 2.944
                               a11.955 11.955 0 01-8.618 3.04
                               A12.02 12.02 0 003 9
                               c0 5.591 3.824 10.29 9 11.622
                               C17.176 19.29 21 14.591 21 9
                               c0-1.07-.14-2.107-.402-3.016z"
                        />
                    </svg>

                    <span
                        x-show="!sidebarCollapsed"
                        x-transition.opacity
                    >
                        Contrôle qualité
                    </span>

                </a>

            </div>

        @endif

    </nav>



    {{-- ====================================================================
        PIED DE SIDEBAR (PROFIL & DÉCONNEXION)
    ===================================================================== --}}
    <div class="relative border-t border-border p-3 shrink-0 bg-white" x-data="{ profileOpen: false }" @click.outside="profileOpen = false">

        <button
            type="button"
            @click="profileOpen = !profileOpen"
            class="w-full flex items-center justify-between gap-2 p-2 rounded-lg hover:bg-[#e5f2ee] transition-colors duration-150"
        >
            <div class="flex items-center gap-2 min-w-0">
                <div class="w-9 h-9 rounded-full bg-[#006a4f] text-white flex items-center
                            justify-center font-poppins font-semibold text-sm shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                <div class="text-left min-w-0" x-show="!sidebarCollapsed" x-transition.opacity>
                    <p class="font-poppins text-sm font-medium text-[#212529] truncate">
                        {{ auth()->user()->name }}
                    </p>
                    <p class="font-poppins text-xs text-[#374151] truncate">
                        {{ auth()->user()->role->nom ?? '' }}
                    </p>
                </div>
            </div>

            <svg class="w-4 h-4 text-[#374151] shrink-0" x-show="!sidebarCollapsed" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.8"
                    d="M6 9l6 6 6-6"
                />
            </svg>
        </button>

        {{-- Menu déroulant vers le haut --}}
        <div
            x-show="profileOpen"
            x-transition
            x-cloak
            class="absolute left-0 right-0 bottom-full mb-2 bg-white rounded-lg border border-[#e5e7eb]
                   shadow-[0_10px_15px_rgba(0,0,0,0.1),0_4px_6px_rgba(0,0,0,0.1)]
                   py-2 z-40"
        >
            <div class="px-4 py-2 border-b border-[#e5e7eb]" x-show="sidebarCollapsed">
                <p class="font-poppins text-sm font-semibold text-[#212529] truncate">
                    {{ auth()->user()->name }}
                </p>

                <p class="font-poppins text-xs text-[#374151] truncate">
                    {{ auth()->user()->role->nom ?? '' }}
                </p>
            </div>

            <a
                href="{{ route('profile.edit') }}"
                class="flex items-center gap-2 px-4 py-2 font-poppins text-sm text-[#434343]
                       hover:bg-[#e5f2ee] hover:text-[#006a4f] transition-colors duration-150"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M20 21a8 8 0 00-16 0 M12 13a4 4 0 100-8 4 4 0 000 8z"
                    />
                </svg>

                Modifier mon compte
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button
                    type="submit"
                    class="w-full flex items-center gap-2 px-4 py-2 font-poppins text-sm
                           text-[#ab1717] hover:bg-[#e5f2ee] transition-colors duration-150"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M15 12H3 M7 8l-4 4 4 4 M21 5v14a2 2 0 01-2 2h-6 M15 7V5a2 2 0 00-2-2H7"
                        />
                    </svg>

                    Déconnexion
                </button>
            </form>
        </div>

    </div>

</aside>