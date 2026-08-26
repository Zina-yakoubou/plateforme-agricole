@php
    $user = auth()->user();
@endphp


{{-- =========================================================
     OVERLAY MOBILE
========================================================== --}}

<div
    x-show="sidebarOpen"
    x-transition.opacity
    x-cloak
    @click="sidebarOpen = false"
    class="fixed inset-0 bg-black/50 z-40 lg:hidden"
></div>


{{-- =========================================================
     SIDEBAR
========================================================== --}}

<aside
    class="w-72 h-screen fixed lg:sticky top-0 left-0 z-50 lg:z-auto
           bg-white border-r border-[#e5e7eb] flex flex-col shrink-0
           transition-all duration-300 ease-in-out relative"
    :class="[
        sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
        collapsed ? 'lg:w-20' : 'lg:w-72'
    ]"
>

    {{-- =========================================================
         BOUTON REPLIER (desktop uniquement)
    ========================================================== --}}

    <button
        type="button"
        @click="collapsed = !collapsed"
        class="hidden lg:flex absolute -right-3 top-8 w-6 h-6 rounded-full
               bg-white border border-[#e5e7eb] shadow-[0_1px_2px_rgba(0,0,0,0.05)]
               items-center justify-center text-[#434343] hover:text-[#006a4f]
               hover:border-[#006a4f] transition-colors duration-150 z-10"
        :aria-label="collapsed ? 'Déplier le menu' : 'Replier le menu'"
    >
        <svg
            class="w-3.5 h-3.5 transition-transform duration-300"
            :class="collapsed ? 'rotate-180' : ''"
            fill="none" stroke="currentColor" viewBox="0 0 24 24"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 18l-6-6 6-6" />
        </svg>
    </button>


    {{-- =========================================================
         LOGO
    ========================================================== --}}

    <div
        class="h-20 flex items-center border-b border-[#e5e7eb] shrink-0"
        :class="collapsed ? 'lg:justify-center lg:px-0 px-6' : 'px-6 justify-between'"
    >

        <div class="flex items-center min-w-0" :class="collapsed ? 'lg:justify-center' : ''">
            <img
                src="{{ asset('images/sira-mo.png') }}"
                class="w-11 h-11 rounded-lg object-contain bg-[#e5f2ee] p-1 shrink-0"
                alt="SIRA-Mô"
            >

            <div class="ml-3 min-w-0" x-show="!collapsed" x-cloak.lg>
                <h2 class="font-poppins text-lg font-semibold text-[#212529] truncate">
                    SIRA-Mô
                </h2>

                <p class="font-poppins text-xs text-[#374151] truncate">
                    Recensement Agricole
                </p>
            </div>
        </div>

        {{-- Fermer (mobile) --}}
        <button
            type="button"
            @click="sidebarOpen = false"
            class="lg:hidden w-9 h-9 shrink-0 flex items-center justify-center rounded-lg
                   text-[#434343] hover:bg-[#e5f2ee] hover:text-[#006a4f] transition-colors duration-150"
            aria-label="Fermer le menu"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

    </div>


    {{-- =========================================================
         NAVIGATION
    ========================================================== --}}

    <nav class="flex-1 px-4 py-5 overflow-y-auto overflow-x-hidden">


        {{-- =====================================================
             TABLEAU DE BORD
        ====================================================== --}}

        
            href="{{ route('dashboard') }}"
            title="Tableau de bord"
            class="flex items-center gap-3 px-4 py-3 rounded-lg mb-2 font-poppins text-sm transition-colors duration-150
            {{ request()->routeIs('dashboard')
                ? 'bg-[#e5f2ee] text-[#006a4f] font-semibold'
                : 'text-[#434343] hover:bg-[#e5f2ee] hover:text-[#006a4f]' }}"
            :class="collapsed ? 'lg:justify-center lg:px-0' : ''"
        >

            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10" />
            </svg>

            <span x-show="!collapsed" x-cloak.lg>Tableau de bord</span>

        </a>


        {{-- =====================================================
             ADMINISTRATEUR
        ====================================================== --}}

        @if($user->isAdmin())

            <div class="mt-7">

                <p class="px-4 mb-3 font-poppins text-xs font-semibold uppercase tracking-widest text-[#374151]"
                   x-show="!collapsed" x-cloak.lg>
                    Administration
                </p>


                {{-- Utilisateurs --}}

                
                    href="{{ route('users.index') }}"
                    title="Utilisateurs"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg mb-2 font-poppins text-sm transition-colors duration-150
                    {{ request()->routeIs('users.*')
                        ? 'bg-[#e5f2ee] text-[#006a4f] font-semibold'
                        : 'text-[#434343] hover:bg-[#e5f2ee] hover:text-[#006a4f]' }}"
                    :class="collapsed ? 'lg:justify-center lg:px-0' : ''"
                >

                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2
                                 M9 11a4 4 0 100-8 4 4 0 000 8
                                 M22 21v-2a4 4 0 00-3-3.87
                                 M16 3.13a4 4 0 010 7.75" />
                    </svg>

                    <span x-show="!collapsed" x-cloak.lg>Utilisateurs</span>

                </a>


                {{-- Campagnes --}}

                
                    href="{{ route('campagnes.index') }}"
                    title="Campagnes"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg mb-2 font-poppins text-sm transition-colors duration-150
                    {{ request()->routeIs('campagnes.*')
                        ? 'bg-[#e5f2ee] text-[#006a4f] font-semibold'
                        : 'text-[#434343] hover:bg-[#e5f2ee] hover:text-[#006a4f]' }}"
                    :class="collapsed ? 'lg:justify-center lg:px-0' : ''"
                >

                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M8 2v4
                                 M16 2v4
                                 M3 10h18
                                 M5 4h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z" />
                    </svg>

                    <span x-show="!collapsed" x-cloak.lg>Campagnes</span>

                </a>

            </div>


            {{-- =================================================
                 DONNÉES
            ================================================== --}}

            <div class="mt-7">

                <p class="px-4 mb-3 font-poppins text-xs font-semibold uppercase tracking-widest text-[#374151]"
                   x-show="!collapsed" x-cloak.lg>
                    Données
                </p>


                {{-- Statistiques --}}
                {{-- À activer lorsque la route statistiques.index sera créée --}}

                <div
                    title="Statistiques"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg font-poppins text-sm text-[#9ca3af] cursor-not-allowed"
                    :class="collapsed ? 'lg:justify-center lg:px-0' : ''"
                >

                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M4 19V5
                                 M4 19h16
                                 M8 16v-5
                                 M12 16V8
                                 M16 16v-8" />
                    </svg>

                    <span x-show="!collapsed" x-cloak.lg>Statistiques</span>

                </div>

            </div>

        @endif


        {{-- =====================================================
             DIRECTEUR PRÉFECTORAL
        ====================================================== --}}

        @if($user->isDPA())

            <div class="mt-7">

                <p class="px-4 mb-3 font-poppins text-xs font-semibold uppercase tracking-widest text-[#374151]"
                   x-show="!collapsed" x-cloak.lg>
                    Gestion
                </p>


                {{-- Agents recenseurs --}}

                
                    href="{{ route('agents.index') }}"
                    title="Agents recenseurs"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg mb-2 font-poppins text-sm transition-colors duration-150
                    {{ request()->routeIs('agents.*')
                        ? 'bg-[#e5f2ee] text-[#006a4f] font-semibold'
                        : 'text-[#434343] hover:bg-[#e5f2ee] hover:text-[#006a4f]' }}"
                    :class="collapsed ? 'lg:justify-center lg:px-0' : ''"
                >

                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2
                                 M9 11a4 4 0 100-8 4 4 0 000 8
                                 M22 21v-2a4 4 0 00-3-3.87
                                 M16 3.13a4 4 0 010 7.75" />
                    </svg>

                    <span x-show="!collapsed" x-cloak.lg>Agents recenseurs</span>

                </a>


                {{-- Campagnes --}}

                
                    href="{{ route('campagnes.index') }}"
                    title="Campagnes"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg mb-2 font-poppins text-sm transition-colors duration-150
                    {{ request()->routeIs('campagnes.*')
                        ? 'bg-[#e5f2ee] text-[#006a4f] font-semibold'
                        : 'text-[#434343] hover:bg-[#e5f2ee] hover:text-[#006a4f]' }}"
                    :class="collapsed ? 'lg:justify-center lg:px-0' : ''"
                >

                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M8 2v4
                                 M16 2v4
                                 M3 10h18
                                 M5 4h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z" />
                    </svg>

                    <span x-show="!collapsed" x-cloak.lg>Campagnes</span>

                </a>

            </div>


            {{-- =================================================
                 DONNÉES
            ================================================== --}}

            <div class="mt-7">

                <p class="px-4 mb-3 font-poppins text-xs font-semibold uppercase tracking-widest text-[#374151]"
                   x-show="!collapsed" x-cloak.lg>
                    Données
                </p>


                {{-- Statistiques --}}

                <div
                    title="Statistiques"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg font-poppins text-sm text-[#9ca3af] cursor-not-allowed"
                    :class="collapsed ? 'lg:justify-center lg:px-0' : ''"
                >

                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M4 19V5
                                 M4 19h16
                                 M8 16v-5
                                 M12 16V8
                                 M16 16v-8" />
                    </svg>

                    <span x-show="!collapsed" x-cloak.lg>Statistiques</span>

                </div>

            </div>

        @endif


        {{-- =====================================================
             AGENT RECENSEUR
        ====================================================== --}}

        @if($user->isAgent())

            <div class="mt-7">

                <p class="px-4 mb-3 font-poppins text-xs font-semibold uppercase tracking-widest text-[#374151]"
                   x-show="!collapsed" x-cloak.lg>
                    Recensement
                </p>


                {{-- Mes affectations --}}

                
                    href="{{ route('agent.affectations') }}"
                    title="Mes affectations"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg font-poppins text-sm transition-colors duration-150
                    {{ request()->routeIs('agent.affectations*')
                        ? 'bg-[#e5f2ee] text-[#006a4f] font-semibold'
                        : 'text-[#434343] hover:bg-[#e5f2ee] hover:text-[#006a4f]' }}"
                    :class="collapsed ? 'lg:justify-center lg:px-0' : ''"
                >

                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                                 M9 5a3 3 0 006 0
                                 M9 12h6
                                 M9 16h4" />
                    </svg>

                    <span x-show="!collapsed" x-cloak.lg>Mes affectations</span>

                </a>

            </div>

        @endif

    </nav>


    {{-- =========================================================
         UTILISATEUR CONNECTÉ
    ========================================================== --}}

    <div
        class="border-t border-[#e5e7eb] p-3 shrink-0 relative"
        x-data="{ accountMenu: false }"
        @click.outside="accountMenu = false"
    >

        {{-- Déclencheur --}}

        <button
            type="button"
            @click="accountMenu = !accountMenu"
            class="w-full flex items-center gap-3 p-2 rounded-lg
                   hover:bg-[#e5f2ee] transition-colors duration-150"
            :class="collapsed ? 'lg:justify-center' : ''"
        >

            {{-- Avatar --}}

            <div
                class="w-10 h-10 rounded-full bg-[#006a4f] text-white
                       flex items-center justify-center font-poppins font-semibold text-sm shrink-0"
            >
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>


            {{-- Infos --}}

            <div class="min-w-0 flex-1 text-left" x-show="!collapsed" x-cloak.lg>

                <p class="font-poppins text-sm font-semibold text-[#212529] truncate">
                    {{ $user->name }}
                </p>

                <p class="font-poppins text-xs text-[#374151] truncate">
                    {{ $user->role->nom ?? '' }}
                </p>

            </div>


            {{-- Chevron --}}

            <svg
                class="w-4 h-4 text-[#374151] shrink-0 transition-transform duration-150"
                x-show="!collapsed"
                x-cloak.lg
                :class="accountMenu ? 'rotate-180' : ''"
                fill="none" stroke="currentColor" viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 9l6 6 6-6" />
            </svg>

        </button>


        {{-- Menu déroulant --}}

        <div
            x-show="accountMenu"
            x-transition
            x-cloak
            class="absolute bottom-full mb-2 bg-white rounded-lg border border-[#e5e7eb]
                   shadow-[0_10px_15px_rgba(0,0,0,0.1),0_4px_6px_rgba(0,0,0,0.1)] py-2 z-50"
            :class="collapsed ? 'lg:left-full lg:ml-2 lg:bottom-3 left-3 right-3 lg:right-auto lg:w-56' : 'left-3 right-3'"
        >

            <div class="px-4 py-2 border-b border-[#e5e7eb]">
                <p class="font-poppins text-sm font-semibold text-[#212529] truncate">
                    {{ $user->name }}
                </p>
                <p class="font-poppins text-xs text-[#374151] truncate">
                    {{ $user->role->nom ?? '' }}
                </p>
            </div>

            
                href="{{ route('profile.edit') }}"
                class="flex items-center gap-2 px-4 py-2 font-poppins text-sm text-[#434343]
                       hover:bg-[#e5f2ee] hover:text-[#006a4f] transition-colors duration-150"
            >
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M20 21a8 8 0 00-16 0 M12 13a4 4 0 100-8 4 4 0 000 8z" />
                </svg>
                Modifier mon compte
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="w-full flex items-center gap-2 px-4 py-2 font-poppins text-sm
                           text-[#ab1717] hover:bg-red-50 transition-colors duration-150"
                >
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M15 12H3 M7 8l-4 4 4 4 M21 5v14a2 2 0 01-2 2h-6 M15 7V5a2 2 0 00-2-2H7" />
                    </svg>
                    Déconnexion
                </button>
            </form>

        </div>

    </div>

</aside>