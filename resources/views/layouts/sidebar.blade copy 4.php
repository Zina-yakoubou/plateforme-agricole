<aside class="w-72 h-screen sticky top-0 bg-white border-r border-slate-200 flex flex-col relative">

@php
    $user = auth()->user();
@endphp

{{-- ================================================= --}}
{{-- LOGO --}}
{{-- ================================================= --}}

<div class="h-20 px-6 flex items-center border-b border-slate-200 shrink-0">
    <img src="{{ asset('images/sira-mo.png') }}"
         class="w-12 h-12 rounded-lg"
         alt="SIRA-MO">
    <div class="ml-3">
        <h2 class="text-lg font-bold text-slate-800">SIRA-MO</h2>
        <p class="text-xs text-slate-400">Recensement Agricole</p>
    </div>
</div>


{{-- ================================================= --}}
{{-- MENU --}}
{{-- ================================================= --}}

<nav class="flex-1 px-4 py-5 overflow-y-auto">

    <a href="{{ route('dashboard') }}"
        class="flex items-center gap-3 px-4 py-3 rounded-xl mb-2 transition
        {{ request()->routeIs('dashboard')
            ? 'bg-green-600 text-white'
            : 'text-slate-700 hover:bg-green-50' }}">
        📊
        <span>Tableau de bord</span>
    </a>


    {{-- ================================================= --}}
    {{-- ADMINISTRATEUR --}}
    {{-- ================================================= --}}

    @if($user->isAdmin())

    <div class="mt-6">

        <p class="text-xs uppercase tracking-widest text-slate-400 mb-3">Administration</p>

        <details open class="border border-slate-200 rounded-xl mb-3">
            <summary class="cursor-pointer flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-green-50">
                🌍
                <span>Référentiel territorial</span>
            </summary>

            <div class="ml-4 mt-2 space-y-1">
                <a href="{{ route('regions.index') }}"
                    class="block px-3 py-2 rounded-lg hover:bg-green-50
                    {{ request()->routeIs('regions.*') ? 'text-green-600 font-semibold' : 'text-slate-700' }}">
                    🌍 Régions
                </a>

                <a href="{{ route('prefectures.index') }}"
                    class="block px-3 py-2 rounded-lg hover:bg-green-50
                    {{ request()->routeIs('prefectures.*') ? 'text-green-600 font-semibold' : 'text-slate-700' }}">
                    🏛️ Préfectures
                </a>

                <a href="{{ route('communes.index') }}"
                    class="block px-3 py-2 rounded-lg hover:bg-green-50
                    {{ request()->routeIs('communes.*') ? 'text-green-600 font-semibold' : 'text-slate-700' }}">
                    🏘️ Communes
                </a>

                <a href="{{ route('cantons.index') }}"
                    class="block px-3 py-2 rounded-lg hover:bg-green-50
                    {{ request()->routeIs('cantons.*') ? 'text-green-600 font-semibold' : 'text-slate-700' }}">
                    🗺️ Cantons
                </a>

                <a href="{{ route('villages.index') }}"
                    class="block px-3 py-2 rounded-lg hover:bg-green-50
                    {{ request()->routeIs('villages.*') ? 'text-green-600 font-semibold' : 'text-slate-700' }}">
                    🌾 Villages
                </a>
            </div>
        </details>

        <a href="{{ route('users.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl mb-2
            {{ request()->routeIs('users.*') ? 'bg-green-50 text-green-700' : 'hover:bg-green-50 text-slate-700' }}">
            👥
            <span>Utilisateurs</span>
        </a>

        <a href="{{ route('campagnes.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl mb-2
            {{ request()->routeIs('campagnes.*') ? 'bg-green-50 text-green-700' : 'hover:bg-green-50 text-slate-700' }}">
            📅
            <span>Campagnes</span>
        </a>

        <a href="{{ route('affectations.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl mb-2
            {{ request()->routeIs('affectations.*') ? 'bg-green-50 text-green-700' : 'hover:bg-green-50 text-slate-700' }}">
            📍
            <span>Affectations</span>
        </a>

    </div>

    @endif


    {{-- ================================================= --}}
    {{-- DIRECTEUR PREFECTORAL --}}
    {{-- ================================================= --}}

    @if($user->isDirecteur())

    <div class="mt-6">

        <a href="{{ route('communes.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-green-50">
            🏘️ Mes Communes
        </a>

        <a href="{{ route('cantons.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-green-50">
            🗺️ Mes Cantons
        </a>

        <a href="{{ route('villages.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-green-50">
            🌾 Mes Villages
        </a>

        <a href="{{ route('affectations.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-green-50">
            📍 Affectations
        </a>

    </div>

    @endif



{{-- ================================================= --}}
{{-- AGENT RECENSEUR --}}
{{-- ================================================= --}}

@if($user->isAgent())

    {{-- ================= RECENSEMENT ================= --}}
    <div class="mt-6">

        <p class="px-4 mb-3 text-xs font-semibold uppercase tracking-widest text-slate-400">
            Recensement
        </p>


        {{-- Mes affectations --}}
        <a href="{{ route('agent.affectations') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl mb-1
                  transition
                  {{ request()->routeIs('agent.affectations')
                      ? 'bg-green-600 text-white font-semibold shadow-sm'
                      : 'text-slate-700 hover:bg-green-50 hover:text-green-700' }}">

            <svg class="w-5 h-5 flex-shrink-0"
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

            <span>Mes affectations</span>

        </a>


        {{-- Nouveau recensement --}}
        {{-- <a href="{{ route('recensements.create') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl mb-1
                  transition
                  {{ request()->routeIs('recensements.create')
                      ? 'bg-green-600 text-white font-semibold shadow-sm'
                      : 'text-slate-700 hover:bg-green-50 hover:text-green-700' }}">

            <svg class="w-5 h-5 flex-shrink-0"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1.8"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                         M9 5a3 3 0 006 0
                         M9 12h6
                         M9 16h6
                         M12 19v-3
                         M10.5 17.5h3"/>

            </svg>

            <span>Nouveau recensement</span>

        </a> --}}


        {{-- Ménages --}}
        {{-- <a href="{{ route('menages.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl mb-1
                  transition
                  {{ request()->routeIs('menages.*')
                      ? 'bg-green-600 text-white font-semibold shadow-sm'
                      : 'text-slate-700 hover:bg-green-50 hover:text-green-700' }}">

            <svg class="w-5 h-5 flex-shrink-0"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1.8"
                      d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2
                         M9 11a4 4 0 100-8 4 4 0 000 8
                         M22 21v-2a4 4 0 00-3-3.87
                         M16 3.13a4 4 0 010 7.75"/>

            </svg>

            <span>Ménages</span>

        </a> --}}


        {{-- Cultures --}}
        {{-- <a href="{{ route('cultures.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl mb-1
                  transition
                  {{ request()->routeIs('cultures.*')
                      ? 'bg-green-600 text-white font-semibold shadow-sm'
                      : 'text-slate-700 hover:bg-green-50 hover:text-green-700' }}">

            <svg class="w-5 h-5 flex-shrink-0"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1.8"
                      d="M12 21V10
                         M12 14c-4-1-6-3-6-7 4 0 6 2 6 7
                         M12 16c4-1 6-3 6-7-4 0-6 2-6 7"/>

            </svg>

            <span>Cultures</span>

        </a> --}}


        {{-- Besoins en intrants --}}
        {{-- <a href="{{ route('besoins-intrants.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl mb-1
                  transition
                  {{ request()->routeIs('besoins-intrants.*')
                      ? 'bg-green-600 text-white font-semibold shadow-sm'
                      : 'text-slate-700 hover:bg-green-50 hover:text-green-700' }}">

            <svg class="w-5 h-5 flex-shrink-0"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1.8"
                      d="M9 3h6
                         M10 3v3
                         M14 3v3
                         M6 8h12
                         M7 8v10a3 3 0 003 3h4a3 3 0 003-3V8
                         M9 12h6
                         M9 16h6"/>

            </svg>

            <span>Besoins en intrants</span>

        </a> --}}

    </div>


    {{-- ================= DONNÉES LOCALES ================= --}}
    <div class="mt-6">

        <p class="px-4 mb-3 text-xs font-semibold uppercase tracking-widest text-slate-400">
            Données locales
        </p>


        {{-- Historique local --}}
        {{-- <a href="{{ route('recensements.historique') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl mb-1
                  transition
                  {{ request()->routeIs('recensements.historique')
                      ? 'bg-green-600 text-white font-semibold shadow-sm'
                      : 'text-slate-700 hover:bg-green-50 hover:text-green-700' }}">

            <svg class="w-5 h-5 flex-shrink-0"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1.8"
                      d="M12 8v4l3 2
                         M21 12a9 9 0 11-3-6.7
                         M21 4v6h-6"/>

            </svg>

            <span>Historique local</span>

        </a>


        {{-- Synchronisation --}}
        {{-- <a href="{{ route('recensements.synchroniser') }}"
           class="flex items-center justify-between gap-3
                  px-4 py-3 rounded-xl mb-1
                  transition
                  {{ request()->routeIs('recensements.synchroniser')
                      ? 'bg-green-600 text-white font-semibold shadow-sm'
                      : 'text-slate-700 hover:bg-green-50 hover:text-green-700' }}">

            <span class="flex items-center gap-3">

                <svg class="w-5 h-5 flex-shrink-0"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.8"
                          d="M4 4v5h5
                             M20 20v-5h-5
                             M5.5 9A7 7 0 0118 6.5L20 9
                             M18.5 15A7 7 0 016 17.5L4 15"/>

                </svg>

                <span>Synchroniser</span>

            </span>


            @if(isset($enAttenteSync) && $enAttenteSync > 0)

                <span class="inline-flex items-center justify-center
                             min-w-[20px] h-5 px-1.5 rounded-full
                             bg-orange-500 text-white text-xs font-bold">

                    {{ $enAttenteSync }}

                </span>

            @endif

        </a> --}}

    </div>

@endif


{{-- ================================================= --}}
{{-- PROFIL UTILISATEUR --}}
{{-- ================================================= --}}

<div class="mt-6 pt-5 border-t border-slate-200">

    {{-- Identité --}}
    <div class="flex items-center gap-3 px-3">

        <div class="w-11 h-11 rounded-full
                    bg-green-600 text-white
                    flex items-center justify-center
                    font-bold flex-shrink-0">

            {{ strtoupper(substr($user->name, 0, 1)) }}

        </div>

        <div class="min-w-0">

            <p class="font-semibold text-slate-800 truncate">
                {{ $user->name }}
            </p>

            <p class="text-xs text-slate-400 truncate">
                {{ $user->role->nom ?? '' }}
            </p>

        </div>

    </div>


    {{-- Modifier le compte --}}
    <a href="{{ route('profile.edit') }}"
       class="mt-3 flex items-center gap-3
              px-3 py-2.5 rounded-lg
              text-sm text-slate-700
              hover:bg-green-50 hover:text-green-700
              transition">

        <svg class="w-5 h-5 text-slate-400"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">

            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="1.8"
                  d="M15.75 6a3.75 3.75 0 11-7.5 0
                     3.75 3.75 0 017.5 0z
                     M4.5 20.25a8.25 8.25 0 0115 0"/>

        </svg>

        <span>Modifier mon compte</span>

    </a>


    {{-- Déconnexion --}}
    <form method="POST" action="{{ route('logout') }}">

        @csrf

        <button type="submit"
                class="mt-1 w-full flex items-center gap-3
                       px-3 py-2.5 rounded-lg
                       text-sm text-red-600
                       hover:bg-red-50
                       transition">

            <svg class="w-5 h-5"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1.8"
                      d="M15.75 9V5.25A2.25 2.25 0 0013.5 3
                         h-6a2.25 2.25 0 00-2.25 2.25v13.5
                         A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15
                         M18 15l3-3m0 0l-3-3m3 3H9"/>

            </svg>

            <span>Déconnexion</span>

        </button>

    </form>

</div>

