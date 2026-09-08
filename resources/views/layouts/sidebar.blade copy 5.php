<aside
    x-cloak
    :class="sidebarCollapsed ? 'w-20' : 'w-72'"
    class="h-screen sticky top-0 bg-white shadow-[1px_0_2px_rgba(0,0,0,0.05)] flex flex-col relative font-sans transition-all duration-200 ease-in-out shrink-0"
>

@php
    $user = auth()->user();
@endphp

{{-- En-tête aligné à la hauteur de la navbar (h-16) --}}
<div class="h-16 px-4 flex items-center justify-between border-b border-border shrink-0">

    <div class="flex items-center gap-3 min-w-0">
        <img src="{{ asset('images/logo_recensement_agricole_mo.png') }}"
             class="w-9 h-9 rounded-md shrink-0"
             alt="SIRA-MO">

        <div class="min-w-0" x-show="!sidebarCollapsed" x-transition.opacity>
            <h2 class="text-sm font-bold text-text-primary leading-tight whitespace-nowrap">SIRA-Mô</h2>
            <p class="text-xs text-text-secondary whitespace-nowrap">Recensement Agricole</p>
        </div>
    </div>

    {{-- Bouton replier / déplier (desktop uniquement) --}}
    <button
        type="button"
        @click="sidebarCollapsed = !sidebarCollapsed"
        class="hidden lg:flex w-8 h-8 items-center justify-center rounded-md
               border border-border text-text-secondary hover:bg-background-muted hover:text-primary
               transition-colors duration-150 ease-in-out shrink-0"
        :aria-label="sidebarCollapsed ? 'Déplier le menu' : 'Replier le menu'"
    >
        <svg class="w-4 h-4 transition-transform duration-200"
             :class="sidebarCollapsed ? 'rotate-180' : ''"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </button>

</div>

<nav class="flex-1 px-3 py-5 overflow-y-auto overflow-x-hidden">

    <a href="{{ route('dashboard') }}"
        :class="sidebarCollapsed ? 'justify-center px-0' : 'px-4'"
        class="flex items-center gap-3 py-3 rounded-md mb-1 transition-colors duration-150 ease-in-out
        {{ request()->routeIs('dashboard')
            ? 'bg-green-50 text-green-700 font-semibold'
            : 'text-text-secondary hover:bg-background-muted' }}"
        title="Tableau de bord">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                  d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h4a1 1 0 001-1V10" />
        </svg>
        <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap">Tableau de bord</span>
    </a>


    <a
        href="{{ route('statistiques.index') }}"
        title="Statistiques"
        class="flex items-center gap-3 px-4 py-3 rounded-lg font-poppins text-sm transition-colors duration-150
        {{ request()->routeIs('statistiques.*')
            ? 'bg-[#e5f2ee] text-[#006a4f] font-semibold'
            : 'text-[#434343] hover:bg-[#e5f2ee] hover:text-[#006a4f]' }}"
        :class="collapsed ? 'lg:justify-center lg:px-0' : ''"
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
                d="M4 19V5
                M4 19h16
                M8 16v-5
                M12 16V8
                M16 16v-8"
            />
        </svg>

        <span x-show="!collapsed" x-cloak.lg>
            Statistiques
        </span>

    </a>

    {{-- @if($user->isAdmin())
    <div class="mt-6">
        <p x-show="!sidebarCollapsed" class="text-xs uppercase tracking-widest text-text-muted mb-3 px-1 whitespace-nowrap">Administration</p>

        <a href="{{ route('users.index') }}"
            :class="sidebarCollapsed ? 'justify-center px-0' : 'px-4'"
            class="flex items-center gap-3 py-3 rounded-md mb-1 transition-colors duration-150 ease-in-out
            {{ request()->routeIs('users.*') ? 'bg-green-50 text-green-700 font-semibold' : 'text-text-secondary hover:bg-background-muted' }}"
            title="Utilisateurs">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m5-9.13a4 4 0 110 8 4 4 0 010-8zm6 4a4 4 0 100 8" />
            </svg>
            <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap">Utilisateurs</span>
        </a>

        <a href="{{ route('campagnes.index') }}"
            :class="sidebarCollapsed ? 'justify-center px-0' : 'px-4'"
            class="flex items-center gap-3 py-3 rounded-md mb-1 transition-colors duration-150 ease-in-out
            {{ request()->routeIs('campagnes.*') ? 'bg-green-50 text-green-700 font-semibold' : 'text-text-secondary hover:bg-background-muted' }}"
            title="Campagnes">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z" />
            </svg>
            <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap">Campagnes</span>
        </a>

        
    </div>
    @endif --}}

    @if($user->isAdmin())

        <div class="mt-6">

            {{-- =========================================================
                ADMINISTRATION
            ========================================================== --}}
            <p
                x-show="!sidebarCollapsed"
                class="text-xs uppercase tracking-widest text-text-muted mb-3 px-1 whitespace-nowrap"
            >
                Administration
            </p>


            {{-- =========================================================
                UTILISATEURS
            ========================================================== --}}
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
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
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


            {{-- =========================================================
                CAMPAGNES
            ========================================================== --}}
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
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M8 7V3
                        m8 4V3
                        M4 11h16
                        M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"
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


            {{-- =========================================================
                TERRITOIRE
                Régions → Préfectures → Communes → Cantons → Villages
            ========================================================== --}}
            <a
            href="{{ route('regions.index') }}"
            :class="sidebarCollapsed ? 'justify-center px-0' : 'px-4'"
            class="flex items-center gap-3 py-3 rounded-md mb-1
                transition-colors duration-150 ease-in-out
                {{ request()->routeIs('regions.*', 'prefectures.*', 'communes.*', 'cantons.*', 'villages.*')
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
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.8"
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

        


            {{-- =========================================================
                CULTURES
            ========================================================== --}}
            {{-- <a
                href="{{ route('cultures.index') }}"
                :class="sidebarCollapsed ? 'justify-center px-0' : 'px-4'"
                class="flex items-center gap-3 py-3 rounded-md mb-1
                    transition-colors duration-150 ease-in-out
                    {{ request()->routeIs('cultures.*')
                            ? 'bg-green-50 text-green-700 font-semibold'
                            : 'text-text-secondary hover:bg-background-muted' }}"
                title="Cultures"
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
                        d="M12 21V10
                        M12 14c-4-1-6-3.5-6-7
                        4 0 6 2 6 7
                        m0 4c4-1 6-3.5 6-7
                        -4 0-6 2-6 7"
                    />
                </svg>

                <span
                    x-show="!sidebarCollapsed"
                    x-transition.opacity
                    class="whitespace-nowrap"
                >
                    Cultures
                </span>
            </a> --}}


            {{-- =========================================================
                INTRANTS
            ========================================================== --}}
            {{-- <a
                href="{{ route('intrants.index') }}"
                :class="sidebarCollapsed ? 'justify-center px-0' : 'px-4'"
                class="flex items-center gap-3 py-3 rounded-md mb-1
                    transition-colors duration-150 ease-in-out
                    {{ request()->routeIs('intrants.*')
                            ? 'bg-green-50 text-green-700 font-semibold'
                            : 'text-text-secondary hover:bg-background-muted' }}"
                title="Intrants"
            >
                <svg
                    class="w-5 h-5 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M8 3h8
                        M9 3v5l-3 5a5 5 0 004 7h4a5 5 0 004-7l-3-5V3
                        M7 14h10"
                    />
                </svg>

                <span
                    x-show="!sidebarCollapsed"
                    x-transition.opacity
                    class="whitespace-nowrap"
                >
                    Intrants
                </span>
            </a> --}}


            {{-- =========================================================
                STATISTIQUES
            ========================================================== --}}
            <a
                 href="{{ route('statistiques.index') }}" --}}
                {{-- href="#" --}}

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
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M4 19V9
                        M10 19V5
                        M16 19v-7
                        M22 19H2"
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

    {{-- @if($user->isDPA())
    <div class="mt-6">
        <p x-show="!sidebarCollapsed" class="text-xs uppercase tracking-widest text-text-muted mb-3 px-1 whitespace-nowrap">Administration</p>

        <a href="{{ route('users.index') }}"
            :class="sidebarCollapsed ? 'justify-center px-0' : 'px-4'"
            class="flex items-center gap-3 py-3 rounded-md mb-1 transition-colors duration-150 ease-in-out
            {{ request()->routeIs('users.*') ? 'bg-green-50 text-green-700 font-semibold' : 'text-text-secondary hover:bg-background-muted' }}"
            title="Utilisateurs">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m5-9.13a4 4 0 110 8 4 4 0 010-8zm6 4a4 4 0 100 8" />
            </svg>
            <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap">Utilisateurs</span>
        </a>

        <a href="{{ route('communes.index') }}"
            :class="sidebarCollapsed ? 'justify-center px-0' : 'px-4'"
            class="flex items-center gap-3 py-3 rounded-md mb-1 transition-colors duration-150 ease-in-out
            {{ request()->routeIs('communes.*') ? 'bg-green-50 text-green-700 font-semibold' : 'text-text-secondary hover:bg-background-muted' }}"
            title="Mes Communes">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4" />
            </svg>
            <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap">Mes Communes</span>
        </a>

        <a href="{{ route('cantons.index') }}"
            :class="sidebarCollapsed ? 'justify-center px-0' : 'px-4'"
            class="flex items-center gap-3 py-3 rounded-md mb-1 transition-colors duration-150 ease-in-out
            {{ request()->routeIs('cantons.*') ? 'bg-green-50 text-green-700 font-semibold' : 'text-text-secondary hover:bg-background-muted' }}"
            title="Mes Cantons">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
            </svg>
            <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap">Mes Cantons</span>
        </a>

        <a href="{{ route('villages.index') }}"
            :class="sidebarCollapsed ? 'justify-center px-0' : 'px-4'"
            class="flex items-center gap-3 py-3 rounded-md mb-1 transition-colors duration-150 ease-in-out
            {{ request()->routeIs('villages.*') ? 'bg-green-50 text-green-700 font-semibold' : 'text-text-secondary hover:bg-background-muted' }}"
            title="Mes Villages">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h4a1 1 0 001-1V10" />
            </svg>
            <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap">Mes Villages</span>
        </a>

    </div>
    @endif --}}

    {{-- @if($user->isDpa())

        <div class="mt-6">

            <p
                x-show="!sidebarCollapsed"
                class="text-xs uppercase tracking-widest text-text-muted mb-3 px-1 whitespace-nowrap"
            >
                Gestion préfectorale
            </p>


            <a
                href="{{ route('dpa.campagnes.index') }}"
                :class="[
                    sidebarCollapsed ? 'justify-center px-0' : 'px-4',
                    request()->routeIs('dpa.campagnes.*')
                        ? 'bg-green-50 text-green-700 font-semibold'
                        : 'text-text-secondary hover:bg-background-muted'
                ]"
                class="flex items-center gap-3 py-3 rounded-md mb-1 transition-colors duration-150 ease-in-out"
                title="Campagnes"
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
                        d="M8 2v4M16 2v4M3 10h18M5 4h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z"
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


       <a
        href="{{ route('dpa.planifications-prefectorales.index') }}"
        class="inline-flex items-center gap-2
            rounded-lg
            border border-[#006a4f]
            bg-white
            px-3 py-2
            text-xs font-semibold
            text-[#006a4f]
            hover:bg-[#e5f2ee]"
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
            stroke-width="2"
            d="M7 3v4m10-4v4M4 9h16M6 5h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2z"
        />
        <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M8 13h2m2 0h2m2 0h2M8 17h2m2 0h2"
        />
    </svg>

        Planifications
    </a> --}}


{{-- 
    @if($user->isDpa())

    <div class="mt-6">

      
        <p
            x-show="!sidebarCollapsed"
            class="text-xs uppercase tracking-widest text-text-muted mb-3 px-1 whitespace-nowrap"
        >
            Gestion préfectorale
        </p>


       
        <a
            href="{{ route('dpa.campagnes.index') }}"
            :class="[
                sidebarCollapsed ? 'justify-center px-0' : 'px-4',
                request()->routeIs('dpa.campagnes.*')
                    ? 'bg-green-50 text-green-700 font-semibold'
                    : 'text-text-secondary hover:bg-background-muted'
            ]"
            class="flex items-center gap-3 py-3 rounded-md mb-1
                   transition-colors duration-150 ease-in-out"
            title="Campagnes"
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


        
         
        <a
            href="{{ route('dpa.planifications-prefectorales.index') }}"
            :class="[
                sidebarCollapsed ? 'justify-center px-0' : 'px-4',
                request()->routeIs('dpa.planifications-prefectorales.*')
                    ? 'bg-green-50 text-green-700 font-semibold'
                    : 'text-text-secondary hover:bg-background-muted'
            ]"
            class="flex items-center gap-3 py-3 rounded-md mb-1
                   transition-colors duration-150 ease-in-out"
            title="Planifications"
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
                    d="M7 3v4
                       M17 3v4
                       M4 9h16
                       M6 5h12a2 2 0 012 2v12
                       a2 2 0 01-2 2H6
                       a2 2 0 01-2-2V7
                       a2 2 0 012-2z"
                />

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.8"
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
        </a> --}}

          


            {{-- <a
                href="{{ route('dpa.planification.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl
                    text-sm font-medium
                    {{ request()->routeIs('dpa.planification.*')
                            ? 'bg-primary/10 text-primary'
                            : 'text-text-secondary hover:bg-background-muted hover:text-text-primary' }}
                    transition-colors"
            >
                <svg
                    class="w-5 h-5 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M8 2v4M16 2v4M3 10h18M5 4h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z"
                    />
                </svg>

                <span>
                    Planification
                </span>
            </a> --}}

        {{-- </div> --}}

    {{-- @endif --}}

    
    @if($user->isDpa())

        <div class="mt-6">

            {{-- =========================================================
                GESTION PRÉFECTORALE
            ========================================================== --}}
            <p
                x-show="!sidebarCollapsed"
                class="mb-3 px-1 text-xs uppercase tracking-widest text-text-muted whitespace-nowrap"
            >
                Gestion préfectorale
            </p>


            {{-- =========================================================
                CAMPAGNES
            ========================================================== --}}
            <a
                href="{{ route('dpa.campagnes.index') }}"
                :class="[
                    sidebarCollapsed ? 'justify-center px-0' : 'px-4',
                    {{ request()->routeIs('dpa.campagnes.*') ? 'true' : 'false' }}
                        ? 'bg-green-50 text-green-700 font-semibold'
                        : 'text-text-secondary hover:bg-background-muted'
                ]"
                class="mb-1 flex items-center gap-3 rounded-md py-3 transition-colors duration-150 ease-in-out"
                title="Campagnes"
            >

                {{-- Icône Campagnes --}}
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


            {{-- =========================================================
                PLANIFICATIONS
            ========================================================== --}}
            <a
                href="{{ route('dpa.planifications-prefectorales.index') }}"
                :class="[
                    sidebarCollapsed ? 'justify-center px-0' : 'px-4',
                    {{ request()->routeIs('dpa.planifications-prefectorales.*') ? 'true' : 'false' }}
                        ? 'bg-green-50 text-green-700 font-semibold'
                        : 'text-text-secondary hover:bg-background-muted'
                ]"
                class="mb-1 flex items-center gap-3 rounded-md py-3 transition-colors duration-150 ease-in-out"
                title="Planifications"
            >

                {{-- Icône Planifications --}}
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


            {{-- AGENTS RECENSEURS --}}
            <a
                href="{{ route('dpa.agents.index') }}"
                :class="[
                    sidebarCollapsed ? 'justify-center px-0' : 'px-4',
                    {{ request()->routeIs('dpa.agents.*') ? 'true' : 'false' }}
                        ? 'bg-green-50 text-green-700 font-semibold'
                        : 'text-text-secondary hover:bg-background-muted'
                ]"
                class="mb-1 flex items-center gap-3 rounded-md py-3 transition-colors duration-150 ease-in-out"
                title="Agents recenseurs"
            >

                {{-- Icône badge + utilisateur --}}
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="7" r="4" stroke-width="1.8"/>
                    <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                        d="M5 21a7 7 0 0114 0"/>
                    <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                        d="M18 4l1 1 2-2"/>
                </svg>

                <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap">
                    Agents & Superviseurs
                </span>

            </a>


            {{-- =========================================================
                ÉQUIPES
            ========================================================== --}}
            <a
                href="{{ route('dpa.equipes.index') }}"
                :class="[
                    sidebarCollapsed ? 'justify-center px-0' : 'px-4',
                    {{ request()->routeIs('dpa.equipes.*') ? 'true' : 'false' }}
                        ? 'bg-green-50 text-green-700 font-semibold'
                        : 'text-text-secondary hover:bg-background-muted'
                ]"
                class="mb-1 flex items-center gap-3 rounded-md py-3 transition-colors duration-150 ease-in-out"
                title="Équipes"
            >

                {{-- Icône Équipes : groupe de personnes --}}
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


            {{-- =========================================================
                DÉPLOIEMENT DES AGENTS
            ========================================================== --}}
            <a
                href="{{ route('dpa.affectations.index') }}"
                :class="[
                    sidebarCollapsed ? 'justify-center px-0' : 'px-4',
                    {{ request()->routeIs('dpa.affectations.*') ? 'true' : 'false' }}
                        ? 'bg-green-50 text-green-700 font-semibold'
                        : 'text-text-secondary hover:bg-background-muted'
                ]"
                class="mb-1 flex items-center gap-3 rounded-md py-3 transition-colors duration-150 ease-in-out"
                title="Déploiement des agents"
            >

                {{-- Icône Déploiement : utilisateurs + flèche --}}
                <svg
                    class="h-5 w-5 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >

                    {{-- Utilisateur --}}
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

                    {{-- Flèche de déploiement --}}
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
                SUIVI & STATISTIQUES
            ========================================================== --}}
            <a
                {{-- href="#" --}}
                href="{{ route('statistiques.index') }}" 

                :class="[
                    sidebarCollapsed ? 'justify-center px-0' : 'px-4',
                    {{ request()->routeIs('dpa.statistiques.*') ? 'true' : 'false' }}
                        ? 'bg-green-50 text-green-700 font-semibold'
                        : 'text-text-secondary hover:bg-background-muted'
                ]"
                class="mb-1 flex items-center gap-3 rounded-md py-3 transition-colors duration-150 ease-in-out"
                title="Suivi & statistiques"
            >

                {{-- Icône Suivi & statistiques --}}
                <svg
                    class="h-5 w-5 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    {{-- Axe --}}
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

                    {{-- Barres --}}
                    <path
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M8 16v-5
                        M12 16V8
                        M16 16v-4
                        M20 16V6"
                    />

                    {{-- Tendance --}}
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

    
    
    {{-- @if($user->isAgent())
        <div class="mt-6">
            <p x-show="!sidebarCollapsed" class="text-xs uppercase tracking-widest text-text-muted mb-3 px-1 whitespace-nowrap">Recensement</p>

            <a href="{{ route('agent.affectations') }}"
                :class="[
                    sidebarCollapsed ? 'justify-center px-0' : 'px-4',
                    request()->routeIs('agent.affectations') ? 'bg-green-50 text-green-700 font-semibold' : 'text-text-secondary hover:bg-background-muted'
                ]"
                class="flex items-center gap-3 py-3 rounded-md transition-colors duration-150 ease-in-out"
                title="Mes affectations">

                <svg class="w-5 h-5 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                            M9 5a3 3 0 006 0
                            M9 12h6
                            M9 16h4"/>

                </svg>

                <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap">Mes affectations</span>

            </a>
        </div>
    @endif --}}

    @if($user->isAgent())

        <div class="mt-6">

            {{-- =========================================================
                RECENSEMENT
            ========================================================== --}}
            <p
                x-show="!sidebarCollapsed"
                class="mb-3 px-1 text-xs uppercase tracking-widest text-text-muted whitespace-nowrap"
            >
                Recensement
            </p>


            {{-- =========================================================
                MA CAMPAGNE
            ========================================================== --}}
            <a
                href="{{ route('agent.campagne') }}"
                :class="[
                    sidebarCollapsed ? 'justify-center px-0' : 'px-4',
                    {{ request()->routeIs('agent.campagne') ? 'true' : 'false' }}
                        ? 'bg-green-50 text-green-700 font-semibold'
                        : 'text-text-secondary hover:bg-background-muted'
                ]"
                class="mb-1 flex items-center gap-3 rounded-md py-3 transition-colors duration-150 ease-in-out"
                title="Ma campagne"
            >

                {{-- Icône campagne --}}
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
                    Ma campagne
                </span>

            </a>


            {{-- =========================================================
                MES AFFECTATIONS
            ========================================================== --}}
            <a
                href="{{ route('agent.affectations') }}"
                :class="[
                    sidebarCollapsed ? 'justify-center px-0' : 'px-4',
                    {{ request()->routeIs('agent.affectations') ? 'true' : 'false' }}
                        ? 'bg-green-50 text-green-700 font-semibold'
                        : 'text-text-secondary hover:bg-background-muted'
                ]"
                class="mb-1 flex items-center gap-3 rounded-md py-3 transition-colors duration-150 ease-in-out"
                title="Mes affectations"
            >

                {{-- Icône affectations --}}
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
                        d="M9 5H7a2 2 0 00-2 2v12
                        a2 2 0 002 2h10
                        a2 2 0 002-2V7
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


            {{-- =========================================================
                IDENTIFICATION
            ========================================================== --}}
            <a
                href="{{ route('agent.identifications.index') }}"
                :class="[
                    sidebarCollapsed ? 'justify-center px-0' : 'px-4',
                    {{ request()->routeIs('agent.identifications.*') ? 'true' : 'false' }}
                        ? 'bg-green-50 text-green-700 font-semibold'
                        : 'text-text-secondary hover:bg-background-muted'
                ]"
                class="mb-1 flex items-center gap-3 rounded-md py-3 transition-colors duration-150 ease-in-out"
                title="Identification"
            >

                {{-- Icône identification --}}
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
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M3 20v-1a6 6 0 0112 0v1"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M16 8h5
                        m0 0l-3-3
                        m3 3l-3 3"
                    />
                </svg>

                <span
                    x-show="!sidebarCollapsed"
                    x-transition.opacity
                    class="whitespace-nowrap"
                >
                    Identification
                </span>

            </a>


            {{-- =========================================================
                RECENSEMENT
            ========================================================== --}}
            <a
                href="{{ route('agent.recensements.index') }}"
                :class="[
                    sidebarCollapsed ? 'justify-center px-0' : 'px-4',
                    {{ request()->routeIs('agent.recensements.*') ? 'true' : 'false' }}
                        ? 'bg-green-50 text-green-700 font-semibold'
                        : 'text-text-secondary hover:bg-background-muted'
                ]"
                class="mb-1 flex items-center gap-3 rounded-md py-3 transition-colors duration-150 ease-in-out"
                title="Recensement"
            >

                {{-- Icône questionnaire --}}
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
                        d="M6 4h12a2 2 0 012 2v12
                        a2 2 0 01-2 2H6
                        a2 2 0 01-2-2V6
                        a2 2 0 012-2z"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M8 8h8
                        M8 12h8
                        M8 16h5"
                    />
                </svg>

                <span
                    x-show="!sidebarCollapsed"
                    x-transition.opacity
                    class="whitespace-nowrap"
                >
                    Recensement
                </span>

            </a>


            {{-- =========================================================
                SYNCHRONISATION
            ========================================================== --}}
            <a
                href="{{ route('agent.synchronisation') }}"
                :class="[
                    sidebarCollapsed ? 'justify-center px-0' : 'px-4',
                    {{ request()->routeIs('agent.synchronisation') ? 'true' : 'false' }}
                        ? 'bg-green-50 text-green-700 font-semibold'
                        : 'text-text-secondary hover:bg-background-muted'
                ]"
                class="mb-1 flex items-center gap-3 rounded-md py-3 transition-colors duration-150 ease-in-out"
                title="Synchronisation"
            >

                {{-- Icône synchronisation --}}
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
                        d="M4 12a8 8 0 0114.9-4
                        M20 12a8 8 0 01-14.9 4"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M19 4v4h-4
                        M5 20v-4h4"
                    />
                </svg>

                <span
                    x-show="!sidebarCollapsed"
                    x-transition.opacity
                    class="whitespace-nowrap"
                >
                    Synchronisation
                </span>

            </a>

        </div>

    @endif



</nav>

<div class="border-t border-border p-3 shrink-0 space-y-1">
    {{-- <a href="#"
        :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3'"
        class="flex items-center gap-3 py-2 rounded-md text-text-secondary hover:bg-background-muted transition-colors duration-150 ease-in-out"
        title="Aide & support">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                  d="M9.09 9a3 3 0 015.83 1c0 2-3 2-3 4M12 17h.01" />
            <circle cx="12" cy="12" r="9" stroke-width="1.8" />
        </svg>
        <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap">Aide & support</span>
    </a> --}}

    <a href="{{ route('profile.edit') }}"
        :class="[
            sidebarCollapsed ? 'justify-center px-0' : 'px-3',
            request()->routeIs('profile.edit') ? 'bg-green-50 text-green-700 font-semibold' : 'text-text-secondary hover:bg-background-muted'
        ]"
        class="flex items-center gap-3 py-2 rounded-md transition-colors duration-150 ease-in-out"
        title="Mon compte">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                  d="M20 21a8 8 0 00-16 0M12 13a4 4 0 100-8 4 4 0 000 8z" />
        </svg>
        <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap">Mon compte</span>
    </a>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
            :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3'"
            class="w-full flex items-center gap-3 py-2 rounded-md text-accent-red hover:bg-red-50 transition-colors duration-150 ease-in-out"
            title="Déconnexion">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M15 12H3M7 8l-4 4 4 4M21 5v14a2 2 0 01-2 2h-6M15 7V5a2 2 0 00-2-2H7" />
            </svg>
            <span x-show="!sidebarCollapsed" x-transition.opacity class="whitespace-nowrap">Déconnexion</span>
        </button>
    </form>
</div>

</aside>