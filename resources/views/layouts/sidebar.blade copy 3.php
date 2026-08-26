<aside class="w-72 h-screen sticky top-0 bg-white border-r border-slate-200 flex flex-col relative">

@php
    $user = auth()->user();
@endphp

<div class="h-20 px-6 flex items-center border-b border-slate-200 shrink-0">
    <img src="{{ asset('images/sira-mo.png') }}"
         class="w-12 h-12 rounded-lg"
         alt="SIRA-MO">
    <div class="ml-3">
        <h2 class="text-lg font-bold text-slate-800">SIRA-MO</h2>
        <p class="text-xs text-slate-400">Recensement Agricole</p>
    </div>
</div>

<nav class="flex-1 px-4 py-5 overflow-y-auto">

    <a href="{{ route('dashboard') }}"
        class="flex items-center gap-3 px-4 py-3 rounded-xl mb-2 transition
        {{ request()->routeIs('dashboard')
            ? 'bg-green-600 text-white'
            : 'text-slate-700 hover:bg-green-50' }}">
        📊
        <span>Tableau de bord</span>
    </a>

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

    @if($user->isAgent())
        <div class="mt-6">
            <p class="text-xs uppercase tracking-widest text-slate-400 mb-3">Recensement</p>

            {{-- <a href="{{ route('affectations.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-green-50">
                📌 Mes affectations
            </a> --}}

            <a href="{{ route('agent.affectations') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl
                    text-slate-700 hover:bg-green-50 hover:text-green-700
                    transition">

                <svg class="w-5 h-5"
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
        </div>
    @endif

</nav>

<div class="border-t border-slate-200 p-4 shrink-0">
    <div class="flex items-center gap-3">
        <div class="w-11 h-11 rounded-full bg-green-600 text-white flex items-center justify-center font-bold">
            {{ strtoupper(substr($user->name,0,1)) }}
        </div>
        <div>
            <p class="font-semibold text-slate-800">{{ $user->name }}</p>
            <p class="text-xs text-slate-400">{{ $user->role->nom ?? '' }}</p>
        </div>
    </div>

    <a href="{{ route('profile.edit') }}"
        class="mt-3 block px-3 py-2 rounded-lg hover:bg-green-50">
        👤 Modifier mon compte
    </a>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
            class="mt-2 w-full text-left px-3 py-2 rounded-lg text-red-600 hover:bg-red-50">
            🚪 Déconnexion
        </button>
    </form>
</div>

</aside>