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

    :class="[
        sidebarCollapsed ? 'lg:w-20' : 'lg:w-72',

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
                src="{{ asset('images/logo_recensement_agricole_mo.png') }}"
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


        {{-- FERMER MOBILE --}}
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


        {{-- REPLIER / DÉPLIER DESKTOP --}}
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
                   transition-colors duration-150
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
                    d="M3 12l9-9 9 9
                       M5 10v10a1 1 0 001 1h4a1 1 0 001-1v-4
                       a1 1 0 011-1h2a1 1 0 011 1v4
                       a1 1 0 001 1h4a1 1 0 001-1V10"
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
                    class="px-1 mb-3 text-xs uppercase tracking-widest
                           text-text-muted whitespace-nowrap"
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


                {{-- TERRITOIRE --}}
                <a
                    href="{{ route('regions.index') }}"
                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                    class="flex items-center gap-3 py-3 rounded-md mb-1
                           transition-colors duration-150
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


            {{-- STATISTIQUES ADMIN --}}
            <a
                href="{{ route('statistiques.index') }}"
                @click="sidebarOpen = false"
                :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                class="mt-6 flex items-center gap-3 py-3 rounded-md mb-1
                       transition-colors duration-150
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


        {{-- =================================================================
            DPA
        ================================================================== --}}
        @if($user->isDpa())

            <div class="mt-6">

                <p
                    x-show="!sidebarCollapsed"
                    class="px-1 mb-3 text-xs uppercase tracking-widest
                           text-text-muted whitespace-nowrap"
                >
                    Gestion préfectorale
                </p>


                {{-- CAMPAGNES --}}
                <a
                    href="{{ route('campagnes-deployees.index') }}"
                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                    class="flex items-center gap-3 py-3 rounded-md mb-1
                           transition-colors duration-150
                           {{ request()->routeIs('dpa.campagnes.*')
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


                {{-- PLANIFICATIONS --}}
                <a
                    href="{{ route('planifications-prefectorales.index') }}"
                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                    class="flex items-center gap-3 py-3 rounded-md mb-1
                           transition-colors duration-150
                           {{ request()->routeIs('dpa.planifications-prefectorales.*')
                                ? 'bg-green-50 text-green-700 font-semibold'
                                : 'text-text-secondary hover:bg-background-muted' }}"
                    title="Planifications"
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
                    href="{{ route('agents.index') }}"
                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                    class="flex items-center gap-3 py-3 rounded-md mb-1
                           transition-colors duration-150
                           {{ request()->routeIs('dpa.agents.*')
                                ? 'bg-green-50 text-green-700 font-semibold'
                                : 'text-text-secondary hover:bg-background-muted' }}"
                    title="Agents et superviseurs"
                >
                    <svg
                        class="w-5 h-5 shrink-0"
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
                        Agents & superviseurs
                    </span>
                </a>


                {{-- ÉQUIPES --}}
                <a
                    href="{{ route('equipes.index') }}"
                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                    class="flex items-center gap-3 py-3 rounded-md mb-1
                           transition-colors duration-150
                           {{ request()->routeIs('dpa.equipes.*')
                                ? 'bg-green-50 text-green-700 font-semibold'
                                : 'text-text-secondary hover:bg-background-muted' }}"
                    title="Équipes"
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


                {{-- AFFECTATIONS & DÉPLOIEMENT --}}
                <a
                    href="{{ route('affectations.index') }}"
                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                    class="flex items-center gap-3 py-3 rounded-md mb-1
                           transition-colors duration-150
                           {{ request()->routeIs('dpa.affectations.*')
                                ? 'bg-green-50 text-green-700 font-semibold'
                                : 'text-text-secondary hover:bg-background-muted' }}"
                    title="Affectations et déploiement"
                >
                    <svg
                        class="w-5 h-5 shrink-0"
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
                        Affectations & déploiement
                    </span>
                </a>


                {{-- SUIVI & STATISTIQUES --}}
                <a
                    href="{{ route('statistiques.index') }}"
                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                    class="flex items-center gap-3 py-3 rounded-md mb-1
                           transition-colors duration-150
                           {{ request()->routeIs('statistiques.*')
                                ? 'bg-green-50 text-green-700 font-semibold'
                                : 'text-text-secondary hover:bg-background-muted' }}"
                    title="Suivi et statistiques"
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
                    class="px-1 mb-3 text-xs uppercase tracking-widest
                           text-text-muted whitespace-nowrap"
                >
                    Collecte
                </p>


                {{-- MES AFFECTATIONS --}}
                
                {{-- MES AFFECTATIONS --}}
                <a
                    href="{{ route('mes-affectations.index') }}"
                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                    class="flex items-center gap-3 py-3 rounded-md mb-1
                        transition-colors duration-150
                        {{ request()->routeIs('mes-affectations.*')
                                ? 'bg-green-50 text-green-700 font-semibold'
                                : 'text-text-secondary hover:bg-background-muted' }}"
                    title="Mes affectations"
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
                            d="M9 5H7a2 2 0 00-2 2v12
                               a2 2 0 002 2h10a2 2 0 002-2V7
                               a2 2 0 00-2-2h-2
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
                {{-- MES AFFECTATIONS --}}
                <a
                    href="{{ route('mes-affectations.index') }}"
                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                    class="flex items-center gap-3 py-3 rounded-md mb-1
                        transition-colors duration-150
                        {{ request()->routeIs('mes-affectations.*')
                                ? 'bg-green-50 text-green-700 font-semibold'
                                : 'text-text-secondary hover:bg-background-muted' }}"
                    title="Mes affectations"
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

            </div>

        @endif


        {{-- =================================================================
            SUPERVISEUR
        ================================================================== --}}
        @if($user->isSuperviseur())

            <div class="mt-6">

                <p
                    x-show="!sidebarCollapsed"
                    class="px-1 mb-3 text-xs uppercase tracking-widest
                           text-text-muted whitespace-nowrap"
                >
                    Supervision
                </p>


                {{-- MES ÉQUIPES --}}
                <a
                    href="{{ route('superviseur.equipes.index') }}"
                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                    class="flex items-center gap-3 py-3 rounded-md mb-1
                           transition-colors duration-150
                           {{ request()->routeIs('superviseur.equipes.*')
                                ? 'bg-green-50 text-green-700 font-semibold'
                                : 'text-text-secondary hover:bg-background-muted' }}"
                    title="Mes équipes"
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


                {{-- VILLAGES SUIVIS --}}
                <a
                    href="{{ route('superviseur.zones.index') }}"
                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                    class="flex items-center gap-3 py-3 rounded-md mb-1
                           transition-colors duration-150
                           {{ request()->routeIs('superviseur.zones.*')
                                ? 'bg-green-50 text-green-700 font-semibold'
                                : 'text-text-secondary hover:bg-background-muted' }}"
                    title="Villages suivis"
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
                            d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618
                               a1 1 0 011.553-.832L9 7
                               m0 13l6-3
                               m-6 3V7
                               m6 10l5.447 2.724A1 1 0 0021 18.382V7.618
                               a1 1 0 00-.553-.894L15 4
                               m0 13V4
                               m0 0L9 7"
                        />
                    </svg>

                    <span
                        x-show="!sidebarCollapsed"
                        x-transition.opacity
                        class="whitespace-nowrap"
                    >
                        Villages suivis
                    </span>
                </a>


                {{-- SUIVI DU RECENSEMENT --}}
                <a
                    href="{{ route('superviseur.suivi.index') }}"
                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                    class="flex items-center gap-3 py-3 rounded-md mb-1
                           transition-colors duration-150
                           {{ request()->routeIs('superviseur.suivi.*')
                                ? 'bg-green-50 text-green-700 font-semibold'
                                : 'text-text-secondary hover:bg-background-muted' }}"
                    title="Suivi du recensement"
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


                {{-- VALIDATION --}}
                <a
                    href="{{ route('superviseur.controle-qualite.index') }}"
                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                    class="flex items-center gap-3 py-3 rounded-md mb-1
                           transition-colors duration-150
                           {{ request()->routeIs('superviseur.controle-qualite.*')
                                ? 'bg-green-50 text-green-700 font-semibold'
                                : 'text-text-secondary hover:bg-background-muted' }}"
                    title="Validation"
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
                        class="whitespace-nowrap"
                    >
                        Validation
                    </span>
                </a>


                {{-- STATISTIQUES --}}
                <a
                    href="{{ route('statistiques.index') }}"
                    @click="sidebarOpen = false"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-4'"
                    class="flex items-center gap-3 py-3 rounded-md mb-1
                           transition-colors duration-150
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

            </div>

        @endif

    </nav>


    {{-- ====================================================================
        PIED DE SIDEBAR
    ===================================================================== --}}
    <div
        class="border-t border-border
               p-3
               shrink-0
               space-y-1
               bg-white"
    >

        {{-- MON COMPTE --}}
        <a
            href="{{ route('profile.edit') }}"
            @click="sidebarOpen = false"
            :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-3'"
            class="flex items-center gap-3 py-2 rounded-md
                   transition-colors duration-150
                   {{ request()->routeIs('profile.edit')
                        ? 'bg-green-50 text-green-700 font-semibold'
                        : 'text-text-secondary hover:bg-background-muted' }}"
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


        {{-- DÉCONNEXION --}}
        <form
            method="POST"
            action="{{ route('logout') }}"
        >
            @csrf

            <button
                type="submit"
                :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-3'"
                class="w-full flex items-center gap-3 py-2 rounded-md
                       text-accent-red
                       hover:bg-red-50
                       transition-colors duration-150"
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