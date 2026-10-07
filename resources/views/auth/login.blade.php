<x-guest-layout>

<div class="mx-auto w-full max-w-md font-['Poppins']">

    <div class="mb-6 text-center">

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

    <form
        method="POST"
        action="{{ route('login') }}"
        class="rounded-lg border border-[#ced4da] bg-white p-5 shadow-[0_2px_8px_rgba(0,0,0,0.04)]"
    >

        @csrf

        <div>

            <label
                for="email"
                class="mb-1.5 block text-xs font-semibold text-[#333333]"
            >
                Courriel
                <span class="text-[#d11135]">*</span>
            </label>

            <div class="relative">

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

                <button
                    type="button"
                    @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 transition hover:text-[#006a4f]"
                    :aria-label="showPassword ? 'Masquer le mot de passe' : 'Afficher le mot de passe'"
                >

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

</div>

</x-guest-layout>