<x-guest-layout>

    <div class="mb-8 text-center">
        <img
            src="{{ asset('images/logo_recensement_agricole_mo.png') }}"
            alt="SIRA-Mô"
            class="mx-auto h-20 w-auto"
        >

        <h1 class="mt-6 text-2xl font-semibold text-slate-800">
            Connexion
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            Accédez à votre espace SIRA-Mô
        </p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div>
            <x-input-label
                for="email"
                value="Adresse e-mail"
            />

            <x-text-input
                id="email"
                class="mt-1 block w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
                placeholder="exemple@siramo.tg"
            />

            <x-input-error
                :messages="$errors->get('email')"
                class="mt-2"
            />
        </div>


        {{-- Mot de passe --}}
        <div class="mt-4">
            <x-input-label
                for="password"
                value="Mot de passe"
            />

            <x-text-input
                id="password"
                class="mt-1 block w-full"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="Votre mot de passe"
            />

            <x-input-error
                :messages="$errors->get('password')"
                class="mt-2"
            />
        </div>


        {{-- Se souvenir --}}
        <div class="mt-4 flex items-center">
            <input
                id="remember"
                type="checkbox"
                name="remember"
                class="rounded border-slate-300 text-[#266486] shadow-sm focus:ring-[#266486]"
            >

            <label
                for="remember"
                class="ms-2 text-sm text-slate-600"
            >
                Se souvenir de moi
            </label>
        </div>


        {{-- Actions --}}
        <div class="mt-6 flex items-center justify-between">

            @if (Route::has('password.request'))
                <a
                    href="{{ route('password.request') }}"
                    class="text-sm text-[#266486] hover:text-[#15384a] hover:underline"
                >
                    Mot de passe oublié ?
                </a>
            @endif

            <button
                type="submit"
                class="rounded-md bg-[#266486] px-7 py-3 text-sm font-semibold text-white transition hover:bg-[#1a4a63] focus:outline-none focus:ring-4 focus:ring-[#266486]/25"
            >
                Se connecter
            </button>

        </div>

    </form>

</x-guest-layout>