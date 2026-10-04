<x-guest-layout>

<div class="mx-auto w-full max-w-md font-['Poppins']">

    {{-- =========================================================
         EN-TÊTE DE CONNEXION
    ========================================================== --}}
    <div class="mb-6 text-center">

        {{-- Icône SIRA-Mô --}}
        {{-- <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-xl bg-[#006a4f] shadow-sm">

            <svg
                viewBox="0 0 64 64"
                class="h-10 w-10"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
                aria-hidden="true"
            >
                <circle
                    cx="32"
                    cy="32"
                    r="27"
                    stroke="white"
                    stroke-width="2"
                    stroke-opacity="0.25"
                />

                <path
                    d="M32 49V18"
                    stroke="white"
                    stroke-width="4"
                    stroke-linecap="round"
                />

                <path
                    d="M32 25C25 18 17 20 14 21C16 29 23 34 32 32"
                    stroke="white"
                    stroke-width="4"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />

                <path
                    d="M32 32C39 25 47 27 50 28C48 36 41 40 32 39"
                    stroke="white"
                    stroke-width="4"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />

                <circle
                    cx="32"
                    cy="17"
                    r="3.5"
                    fill="#43a842"
                />
            </svg>

        </div> --}}


        <h1 class="text-2xl font-bold leading-tight text-[#333333]">
            Se connecter
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Accédez à votre espace SIRA-Mô
        </p>

        <p class="mt-2 text-[11px] text-[#d11135]">
            <span class="font-bold">*</span>
            Champs obligatoires
        </p>

    </div>


    {{-- =========================================================
         ALERTE NIU
    ========================================================== --}}
    {{-- <div class="mb-5 rounded-lg border border-amber-200 bg-amber-50 p-3.5">

        <div class="flex items-start gap-3">

            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-amber-100 text-amber-700">

                <svg
                    class="h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 9v4m0 4h.01M10.29 3.86l-7.5 13A2 2 0 004.53 20h14.94a2 2 0 001.74-3.14l-7.5-13a2 2 0 00-3.42 0z"
                    />
                </svg>

            </div>


            <div class="text-xs leading-5 text-amber-800">

                <p class="font-semibold">
                    Information sur l'authentification
                </p>

                <p class="mt-0.5">
                    L'authentification NIU est actuellement en maintenance
                    pour les nouveaux utilisateurs.
                    Les utilisateurs déjà connectés peuvent continuer normalement.
                </p>

            </div>

        </div>

    </div> --}}


    {{-- =========================================================
         CONNEXION NIU
    ========================================================== --}}
    {{-- <div class="mb-5 rounded-lg border border-[#ced4da] bg-white p-4">

        <div class="flex items-center gap-3">

            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-[#e5f2ee] text-[#006a4f]">

                <svg
                    class="h-6 w-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <rect
                        x="3"
                        y="5"
                        width="18"
                        height="14"
                        rx="2"
                        stroke-width="2"
                    />

                    <circle
                        cx="8"
                        cy="12"
                        r="2"
                        stroke-width="2"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-width="2"
                        d="M13 10h5M13 14h4"
                    />
                </svg>

            </div>


            <div class="min-w-0">

                <h2 class="text-sm font-semibold text-[#333333]">
                    Connexion avec le NIU
                </h2>

                <p class="mt-0.5 text-[11px] leading-4 text-slate-500">
                    Numéro d'identification unique associé à la carte e-ID.
                </p>

            </div>

        </div>


        <div class="mt-3 flex items-center justify-between border-t border-slate-100 pt-3">

            <span class="text-[11px] text-slate-400">
                Service temporairement indisponible
            </span>

            <span class="rounded-md bg-slate-100 px-2 py-1 text-[10px] font-medium text-slate-500">
                Maintenance
            </span>

        </div>

    </div> --}}


    {{-- =========================================================
         SÉPARATEUR
    ========================================================== --}}
    {{-- <div class="relative my-5">

        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-[#ced4da]"></div>
        </div>

        <div class="relative flex justify-center">

            <span class="bg-slate-50 px-3 text-xs font-medium text-slate-500">
                ou utiliser votre compte
            </span>

        </div>

    </div> --}}


    {{-- =========================================================
         FORMULAIRE
    ========================================================== --}}
    <form
        method="POST"
        action="{{ route('login') }}"
        class="rounded-lg border border-[#ced4da] bg-white p-5 shadow-[0_2px_8px_rgba(0,0,0,0.04)]"
    >

        @csrf


        {{-- =====================================================
             EMAIL
        ====================================================== --}}
        <div>

            <label
                for="email"
                class="mb-1.5 block text-xs font-semibold text-[#333333]"
            >
                Courriel
                <span class="text-[#d11135]">*</span>
            </label>


            <div class="relative">

                {{-- Icône email --}}
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">

                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
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
                    class="w-full rounded-md border border-[#ced4da] bg-white py-2.5 pl-10 pr-3 text-sm text-[#333333] outline-none transition focus:border-[#43a842] focus:ring-2 focus:ring-[#43a842]/20"
                />

            </div>


            <x-input-error
                :messages="$errors->get('email')"
                class="mt-1.5 text-xs text-[#d11135]"
            />

        </div>


        {{-- =====================================================
             MOT DE PASSE
        ====================================================== --}}
        <div
            class="mt-4"
            x-data="{ showPassword: false }"
        >

            <label
                for="password"
                class="mb-1.5 block text-xs font-semibold text-[#333333]"
            >
                Mot de passe
                <span class="text-[#d11135]">*</span>
            </label>


            <div class="relative">

                {{-- Icône cadenas --}}
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">

                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <rect
                            x="5"
                            y="10"
                            width="14"
                            height="11"
                            rx="2"
                            stroke-width="2"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-width="2"
                            d="M8 10V7a4 4 0 018 0v3"
                        />

                    </svg>

                </div>


                <input
                    id="password"
                    :type="showPassword ? 'text' : 'password'"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                    class="w-full rounded-md border border-[#ced4da] bg-white py-2.5 pl-10 pr-10 text-sm text-[#333333] outline-none transition focus:border-[#43a842] focus:ring-2 focus:ring-[#43a842]/20"
                />


                {{-- Afficher / masquer --}}
                <button
                    type="button"
                    @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 transition hover:text-[#006a4f]"
                    :aria-label="showPassword ? 'Masquer le mot de passe' : 'Afficher le mot de passe'"
                >

                    {{-- Œil fermé --}}
                    <svg
                        x-show="!showPassword"
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6z"
                        />

                        <circle
                            cx="12"
                            cy="12"
                            r="2.5"
                            stroke-width="2"
                        />
                    </svg>


                    {{-- Œil barré --}}
                    <svg
                        x-show="showPassword"
                        x-cloak
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 3l18 18"
                        />

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
                class="mt-1.5 text-xs text-[#d11135]"
            />

        </div>


        {{-- =====================================================
             MOT DE PASSE OUBLIÉ
        ====================================================== --}}
        @if (Route::has('password.request'))

            <div class="mt-3 text-right">

                <a
                    href="{{ route('password.request') }}"
                    class="text-xs font-medium text-[#006a4f] transition hover:text-[#43a842] hover:underline"
                >
                    Mot de passe oublié ?
                </a>

            </div>

        @endif


        {{-- =====================================================
             BOUTON
        ====================================================== --}}
        <div class="mt-5">

            <button
                type="submit"
                class="flex w-full items-center justify-center gap-2 rounded-md bg-[#43a842] py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#378736] active:translate-y-[1px] focus:outline-none focus:ring-4 focus:ring-[#43a842]/20"
            >

                <svg
                    class="h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M11 16l-4-4m0 0l4-4m-4 4h14"
                    />
                </svg>

                Se connecter

            </button>

        </div>

    </form>


    {{-- =========================================================
         IDENTITÉ DU SYSTÈME
    ========================================================== --}}
    <div class="mt-5 text-center">

        <div class="flex items-center justify-center gap-2">

            <span class="h-px w-8 bg-slate-200"></span>

            <span class="text-[10px] font-medium uppercase tracking-wider text-slate-400">
                SIRA-Mô
            </span>

            <span class="h-px w-8 bg-slate-200"></span>

        </div>

        <p class="mt-1 text-[10px] text-slate-400">
            Système d'Information et de Recensement Agricole
        </p>

    </div>

</div>

</x-guest-layout>
