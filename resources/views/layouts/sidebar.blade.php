<aside class="w-72 min-h-screen bg-slate-900 text-slate-200 flex flex-col shadow-2xl">

@php
    $user = auth()->user();
@endphp

    {{-- ================= LOGO ================= --}}
    <div class="h-20 px-6 flex items-center">

        <img src="{{ asset('images/sira-mo.png') }}"
             class="w-12 h-12 rounded-lg"
             alt="Logo">

        <div class="ml-3">

            <h2 class="text-lg font-bold text-white">
                SIRA-MO
            </h2>

            <p class="text-xs text-slate-400">
                Recensement Agricole
            </p>

        </div>

    </div>

    {{-- ================= MENU ================= --}}
    <nav class="flex-1 overflow-y-auto px-4 py-4 space-y-7">

        {{-- GENERAL --}}
        <div>

            <p class="text-[11px] uppercase tracking-widest text-slate-500 mb-3">

                Général

            </p>

            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition
               {{ request()->routeIs('dashboard') ? 'bg-green-600 text-white' : 'hover:bg-slate-800' }}">

                📊

                Tableau de bord

            </a>

        </div>


        {{-- ===================================================== --}}
        {{-- ADMINISTRATEUR --}}
        {{-- ===================================================== --}}
        @if($user->isAdmin())

        <div>

            <p class="text-[11px] uppercase tracking-widest text-slate-500 mb-3">

                Administration

            </p>

            <details class="group">

                <summary class="cursor-pointer px-4 py-3 rounded-xl hover:bg-slate-800">

                    🌍 Localisation

                </summary>

                {{-- <div class="ml-6 mt-2 space-y-1">

                    <a href="#" class="block py-2 hover:text-green-400">Régions</a>

                    <a href="#" class="block py-2 hover:text-green-400">Préfectures</a>

                    <a href="#" class="block py-2 hover:text-green-400">Communes</a>

                    <a href="#" class="block py-2 hover:text-green-400">Cantons</a>

                    <a href="#" class="block py-2 hover:text-green-400">Villages</a>

                </div> --}}
                <div class="ml-6 mt-2 space-y-1">


                    <a href="{{ route('regions.index') }}"
                    class="block py-2 hover:text-green-400">

                        Régions

                    </a>


                    <a href="{{ route('prefectures.index') }}"
                    class="block py-2 hover:text-green-400">

                        Préfectures

                    </a>


                    <a href="{{ route('communes.index') }}"
                    class="block py-2 hover:text-green-400">

                        Communes

                    </a>


                    <a href="{{ route('cantons.index') }}"
                    class="block py-2 hover:text-green-400">

                        Cantons

                    </a>


                    <a href="{{ route('villages.index') }}"
                    class="block py-2 hover:text-green-400">

                        Villages

                    </a>


                </div>

            </details>

            {{-- <details class="group mt-2">

                <summary class="cursor-pointer px-4 py-3 rounded-xl hover:bg-slate-800">

                    👥 Utilisateurs

                </summary>

                <div class="ml-6 mt-2 space-y-1">

                    <a href="#" class="block py-2 hover:text-green-400">

                        Directeurs préfectoraux

                    </a>

                    <a href="#" class="block py-2 hover:text-green-400">

                        Agents recenseurs

                    </a>

                    <a href="#" class="block py-2 hover:text-green-400">

                        Rôles

                    </a>

                </div>

            </details> --}}
            <details class="group mt-2">

                <summary class="cursor-pointer px-4 py-3 rounded-xl hover:bg-slate-800">

                    👥 Utilisateurs

                </summary>

                <div class="ml-6 mt-2 space-y-1">

                    <a href="{{ route('users.index') }}"
                    class="block py-2 hover:text-green-400
                    {{ request()->routeIs('users.*') ? 'text-green-400' : '' }}">

                        Gestion des utilisateurs

                    </a>

                </div>

            </details>

            <details class="group mt-2">

                <summary class="cursor-pointer px-4 py-3 rounded-xl hover:bg-slate-800">

                    📅 Campagnes

                </summary>

                <div class="ml-6 mt-2 space-y-1">

                    <a href="#" class="block py-2 hover:text-green-400">

                        Campagnes agricoles

                    </a>

                </div>

            </details>

        </div>

        @endif


        {{-- ===================================================== --}}
        {{-- DIRECTEUR --}}
        {{-- ===================================================== --}}
        @if($user->isDirecteur())

        <div>

            <p class="text-[11px] uppercase tracking-widest text-slate-500 mb-3">

                Gestion préfectorale

            </p>

            <details>
                <a href="#" class="block py-2 hover:text-green-400">Cantons</a>

                <a href="#" class="block py-2 hover:text-green-400">Villages</a>

                <summary class="cursor-pointer px-4 py-3 rounded-xl hover:bg-slate-800">

                    👨‍🌾 Agents

                </summary>

                <div class="ml-6 mt-2 space-y-1">

                    <a href="#" class="block py-2 hover:text-green-400">

                        Agents recenseurs

                    </a>

                    <a href="#" class="block py-2 hover:text-green-400">

                        Affectations

                    </a>

                </div>

            </details>


            <details class="mt-2">

                <summary class="cursor-pointer px-4 py-3 rounded-xl hover:bg-slate-800">

                    📅 Campagnes

                </summary>

                <div class="ml-6 mt-2 space-y-1">

                    <a href="#" class="block py-2 hover:text-green-400">

                        Campagnes

                    </a>

                </div>

            </details>

            <details class="mt-2">

                <summary class="cursor-pointer px-4 py-3 rounded-xl hover:bg-slate-800">

                    📈 Rapports

                </summary>

                <div class="ml-6 mt-2 space-y-1">

                    <a href="#" class="block py-2 hover:text-green-400">

                        Statistiques

                    </a>

                    <a href="#" class="block py-2 hover:text-green-400">

                        Rapports PDF

                    </a>

                </div>

            </details>

        </div>

        @endif


        {{-- ===================================================== --}}
        {{-- AGENT --}}
        {{-- ===================================================== --}}
        @if($user->isAgent())

        <div>

            <p class="text-[11px] uppercase tracking-widest text-slate-500 mb-3">

                Terrain

            </p>

            <a href="#"
               class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800">

                📌

                Mes affectations

            </a>

            <details class="mt-2">

                <summary class="cursor-pointer px-4 py-3 rounded-xl hover:bg-slate-800">

                    🌾 Recensement

                </summary>

                <div class="ml-6 mt-2 space-y-1">

                    <a href="#" class="block py-2 hover:text-green-400">

                        Maisons

                    </a>

                    <a href="#" class="block py-2 hover:text-green-400">

                        Ménages

                    </a>

                    <a href="#" class="block py-2 hover:text-green-400">

                        Exploitants

                    </a>

                    <a href="#" class="block py-2 hover:text-green-400">

                        Parcelles

                    </a>

                    <a href="#" class="block py-2 hover:text-green-400">

                        Cultures

                    </a>

                    <a href="#" class="block py-2 hover:text-green-400">

                        Récoltes

                    </a>

                    <a href="#" class="block py-2 hover:text-green-400">

                        Intrants utilisés

                    </a>

                </div>

            </details>

            <details class="mt-2">

                <summary class="cursor-pointer px-4 py-3 rounded-xl hover:bg-slate-800">

                    🌱 Besoins

                </summary>

                <div class="ml-6 mt-2">

                    <a href="#" class="block py-2 hover:text-green-400">

                        Évaluation des besoins

                    </a>

                </div>

            </details>

        </div>

        @endif

    </nav>

    {{-- ================= PROFIL ================= --}}
    {{-- <div class="p-4">

        <div class="rounded-xl bg-slate-800 p-4">

            <div class="flex items-center">

                <div class="w-11 h-11 rounded-full bg-green-600 flex items-center justify-center text-white font-bold">

                    {{ strtoupper(substr($user->name,0,1)) }}

                </div>

                <div class="ml-3">

                    <p class="font-semibold text-white">

                        {{ $user->name }}

                    </p>

                    <p class="text-xs text-slate-400">

                        {{ $user->role->nom }}

                    </p>

                </div>

            </div>

            <a href="{{ route('profile.edit') }}"
               class="block mt-5 text-green-400 hover:text-green-300">

                👤 Mon profil

            </a>

            <form method="POST" action="{{ route('logout') }}" class="mt-3">

                @csrf

                <button class="text-red-400 hover:text-red-300">

                    🚪 Déconnexion

                </button>

            </form>

        </div>

    </div> --}}
   <div class="p-4" x-data="{ open: false }">

        <div class="relative">

            <!-- Bouton profil (icône seulement) -->
            <button 
                @click="open = !open"
                class="w-12 h-12 rounded-full bg-green-600 flex items-center justify-center text-white font-bold text-lg hover:bg-green-700 transition">

                {{ strtoupper(substr($user->name,0,1)) }}

            </button>


            <!-- Menu déroulant -->
            <div 
                x-show="open"
                @click.outside="open = false"
                x-transition
                class="absolute left-14 top-0 w-56 bg-slate-800 rounded-xl shadow-xl border border-slate-700 p-3 z-50">


                <!-- Nom utilisateur -->
                <div class="pb-3 border-b border-slate-700">

                    <p class="text-white font-semibold">
                        {{ $user->name }}
                    </p>

                    <p class="text-xs text-green-400">
                        {{ $user->role->nom }}
                    </p>

                </div>


                <!-- Modifier compte -->
    <a href="/profile"
                class="flex items-center gap-3 mt-3 px-3 py-2 rounded-lg text-slate-300 hover:bg-slate-700 hover:text-white">

                    👤

                    <span>
                        Modifier le compte
                    </span>

                </a>



                <!-- Déconnexion -->
                <form method="POST" action="{{ route('logout') }}" class="mt-1">

                    @csrf

                    <button
                        class="flex w-full items-center gap-3 px-3 py-2 rounded-lg text-red-400 hover:bg-red-500/10">

                        🚪

                        <span>
                            Déconnexion
                        </span>

                    </button>

                </form>


            </div>

        </div>

    </div>

</aside>