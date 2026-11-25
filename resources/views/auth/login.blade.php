<x-guest-layout>

<div class="mx-auto max-w-screen-2xl px-4 py-10 font-['Poppins'] sm:px-8 lg:px-12">

    <div class="grid overflow-hidden rounded-2xl border border-[#ced4da] bg-white shadow-xl lg:grid-cols-2">

        {{-- ===== COLONNE GAUCHE : présentation (cachée sur mobile) ===== --}}
        <div class="relative hidden min-h-[36rem] lg:block">

            @if(file_exists(public_path('images/agent-recenseur.jpg')))
                <img
                    src="{{ asset('images/agent-recenseur.jpg') }}"
                    alt="Agent recenseur avec une tablette dans un champ de maïs"
                    class="absolute inset-0 h-full w-full object-cover object-[75%_30%]"
                >
            @else
                <div class="absolute inset-0 bg-[#006a4f]"></div>
            @endif

            <div class="absolute inset-0 bg-gradient-to-t from-[#006a4f]/90 via-[#006a4f]/30 to-transparent"></div>

            <div class="absolute inset-x-0 bottom-0 p-8">
                <p class="text-3xl font-extrabold text-white">SIRA-Mô</p>
                <p class="mt-2 max-w-md text-base leading-relaxed text-white/90">
                    Recensement agricole et gestion des données de référence
                    de la préfecture de Mô.
                </p>
            </div>

        </div>

        {{-- ===== COLONNE DROITE : formulaire ===== --}}
        <div class="flex items-center justify-center p-6 sm:p-10 lg:p-14">

            <div class="w-full max-w-md">

                <div class="mb-6 text-center lg:text-left">

                    <h1 class="text-3xl font-bold leading-tight text-[#333333]">
                        Se connecter
                    </h1>

                    <p class="mt-1 text-base text-slate-500">
                        Accédez à votre espace SIRA-Mô
                    </p>

                    <p class="mt-2 text-xs text-[#d11135]">
                        <span class="font-bold">*</span>
                        Champs obligatoires
                    </p>

                </div>

                {{-- Message de session (ex. mot de passe réinitialisé) --}}
                @if(session('status'))
                    <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                        {{ session('status') }}
                    </div>
                @endif

                {{-- novalidate : les messages s'affichent en français, via le serveur --}}
                <form
                    method="POST"
                    action="{{ route('login') }}"
                    novalidate
                >

                    @csrf

                    {{-- COURRIEL --}}
                    <div>

                        <label
                            for="email"
                            class="mb-1.5 block text-sm font-semibold text-[#333333]"
                        >
                            Courriel
                            <span class="text-[#d11135]">*</span>
                        </label>

                        <div class="relative">

                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                    />
                                </svg>
                            </div>

                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="exemple@siramo.tg"
                                class="w-full rounded-md border bg-white py-3 pl-11 pr-3 text-base text-[#333333] outline-none transition focus:border-[#43a842] focus:ring-2 focus:ring-[#43a842]/20 @error('email') border-[#d11135] @else border-[#ced4da] @enderror"
                            />

                        </div>

                        <x-input-error
                            :messages="$errors->get('email')"
                            class="mt-1.5 text-sm text-[#d11135]"
                        />

                    </div>

                    {{-- MOT DE PASSE --}}
                    <div class="mt-5" x-data="{ showPassword: false }">

                        <label
                            for="password"
                            class="mb-1.5 block text-sm font-semibold text-[#333333]"
                        >
                            Mot de passe
                            <span class="text-[#d11135]">*</span>
                        </label>

                        <div class="relative">

                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <rect x="5" y="10" width="14" height="11" rx="2" stroke-width="2"/>
                                    <path stroke-linecap="round" stroke-width="2" d="M8 10V7a4 4 0 018 0v3"/>
                                </svg>
                            </div>

                            <input
                                id="password"
                                :type="showPassword ? 'text' : 'password'"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="w-full rounded-md border bg-white py-3 pl-11 pr-11 text-base text-[#333333] outline-none transition focus:border-[#43a842] focus:ring-2 focus:ring-[#43a842]/20 @error('password') border-[#d11135] @else border-[#ced4da] @enderror"
                            />

                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 flex items-center px-3.5 text-slate-400 transition hover:text-[#006a4f]"
                                :aria-label="showPassword ? 'Masquer le mot de passe' : 'Afficher le mot de passe'"
                            >

                                <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6z"
                                    />
                                    <circle cx="12" cy="12" r="2.5" stroke-width="2"/>
                                </svg>

                                <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"/>
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M10.6 6.2A9.6 9.6 0 0112 6c6 0 9.5 6 9.5 6a16 16 0 01-3.1 3.8M6.1 6.1C3.8 7.9 2.5 10.1 2.5 12c0 0 3.5 6 9.5 6 1.3 0 2.5-.3 3.5-.8"
                                    />
                                </svg>

                            </button>

                        </div>

                        <x-input-error
                            :messages="$errors->get('password')"
                            class="mt-1.5 text-sm text-[#d11135]"
                        />

                    </div>

                    @if (Route::has('password.request'))
                        <div class="mt-3 text-right">
                            <a
                                href="{{ route('password.request') }}"
                                class="text-sm font-medium text-[#006a4f] transition hover:text-[#43a842] hover:underline"
                            >
                                Mot de passe oublié ?
                            </a>
                        </div>
                    @endif

                    {{-- BOUTON --}}
                    <div class="mt-6">

                        <button
                            type="submit"
                            class="flex w-full items-center justify-center gap-2 rounded-md bg-[#43a842] py-3.5 text-base font-semibold text-white shadow-sm transition hover:bg-[#378736] active:translate-y-[1px] focus:outline-none focus:ring-4 focus:ring-[#43a842]/20"
                        >
                            Se connecter

                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6"
                                />
                            </svg>
                        </button>

                    </div>

                </form>

                {{-- AIDE ET RETOUR --}}
                <div class="mt-8 border-t border-slate-100 pt-5 text-center">

                    <p class="text-sm text-slate-500">
                        Pas de compte ? Contactez votre direction préfectorale.
                    </p>

                    <a
                        href="{{ url('/') }}"
                        class="mt-3 inline-flex items-center gap-1 text-sm font-semibold text-[#006a4f] hover:underline"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                        </svg>
                        Retour à l'accueil
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

</x-guest-layout>