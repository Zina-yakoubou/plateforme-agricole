<header class="h-16 bg-white shadow-[0_1px_2px_rgba(0,0,0,0.05)] shrink-0 sticky top-0 z-30">

    <div class="h-full px-4 lg:px-6 flex items-center justify-between gap-4">

        {{-- =====================================================
             GAUCHE — Hamburger (mobile) + Titre / Fil d'Ariane
        ====================================================== --}}

        <div class="flex items-center gap-4 min-w-0">

            {{-- Bouton menu mobile --}}

            <button
                type="button"
                @click="sidebarOpen = true"
                class="lg:hidden shrink-0 w-10 h-10 flex items-center justify-center
                       rounded-lg text-[#434343] hover:bg-[#e5f2ee] hover:text-[#006a4f]
                       transition-colors duration-150"
                aria-label="Ouvrir le menu"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            {{-- Titre de page / fil d'Ariane --}}

            <div class="min-w-0">
                <h1 class="font-poppins text-lg font-semibold text-[#212529] truncate">
                    @yield('page-title', 'Tableau de bord')
                </h1>

                @hasSection('page-subtitle')
                    <p class="font-poppins text-xs text-[#374151] truncate">
                        @yield('page-subtitle')
                    </p>
                @endif
            </div>

        </div>


        {{-- =====================================================
             DROITE — Notifications, Aide, Profil
        ====================================================== --}}

        <div class="flex items-center gap-1 shrink-0">

            {{-- Aide --}}

            <button
                type="button"
                class="w-10 h-10 hidden sm:flex items-center justify-center rounded-lg
                       text-[#434343] hover:bg-[#e5f2ee] hover:text-[#006a4f]
                       transition-colors duration-150"
                aria-label="Aide"
                title="Aide"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M9.09 9a3 3 0 015.83 1c0 2-3 2-3 4M12 17h.01" />
                    <circle cx="12" cy="12" r="9" stroke-width="1.8" />
                </svg>
            </button>

            {{-- Notifications --}}

            <button
                type="button"
                class="relative w-10 h-10 flex items-center justify-center rounded-lg
                       text-[#434343] hover:bg-[#e5f2ee] hover:text-[#006a4f]
                       transition-colors duration-150"
                aria-label="Notifications"
                title="Notifications"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5
                             M9 17a3 3 0 006 0" />
                </svg>

                {{-- Pastille --}}
                <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-[#ab1717]"></span>
            </button>

            {{-- Séparateur --}}
            <div class="w-px h-8 bg-[#e5e7eb] mx-2 hidden sm:block"></div>

            {{-- Profil --}}

            <div class="relative" x-data="{ profileOpen: false }" @click.outside="profileOpen = false">

                <button
                    type="button"
                    @click="profileOpen = !profileOpen"
                    class="flex items-center gap-2 pl-1 pr-2 sm:pr-3 py-1 rounded-lg
                           hover:bg-[#e5f2ee] transition-colors duration-150"
                >
                    <div class="w-9 h-9 rounded-full bg-[#006a4f] text-white flex items-center
                                justify-center font-poppins font-semibold text-sm shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    <div class="hidden sm:block text-left min-w-0">
                        <p class="font-poppins text-sm font-medium text-[#212529] truncate max-w-[120px]">
                            {{ auth()->user()->name }}
                        </p>
                    </div>

                    <svg class="w-4 h-4 text-[#374151] hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 9l6 6 6-6" />
                    </svg>
                </button>

                {{-- Menu déroulant --}}

                <div
                    x-show="profileOpen"
                    x-transition
                    x-cloak
                    class="absolute right-0 mt-2 w-56 bg-white rounded-lg border border-[#e5e7eb]
                           shadow-[0_10px_15px_rgba(0,0,0,0.1),0_4px_6px_rgba(0,0,0,0.1)] py-2 z-40"
                >
                    <div class="px-4 py-2 border-b border-[#e5e7eb]">
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
                                   text-[#ab1717] hover:bg-[#e5f2ee] transition-colors duration-150"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M15 12H3 M7 8l-4 4 4 4 M21 5v14a2 2 0 01-2 2h-6 M15 7V5a2 2 0 00-2-2H7" />
                            </svg>
                            Déconnexion
                        </button>
                    </form>
                </div>

            </div>

        </div>

    </div>

</header>