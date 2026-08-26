<section>
    <header>
        <h2 class="text-lg font-semibold text-slate-800">
            Sécurité du compte
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Modifiez votre mot de passe pour sécuriser votre compte.
        </p>
    </header>

    <form
        method="post"
        action="{{ route('password.update') }}"
        class="mt-6 max-w-xl space-y-6"
    >
        @csrf
        @method('put')

        {{-- Ancien mot de passe --}}
        <div>
            <x-input-label
                for="update_password_current_password"
                value="Mot de passe actuel"
            />

            <x-text-input
                id="update_password_current_password"
                name="current_password"
                type="password"
                class="mt-1 block w-full"
                autocomplete="current-password"
            />

            <x-input-error
                :messages="$errors->updatePassword->get('current_password')"
                class="mt-2"
            />
        </div>


        {{-- Nouveau mot de passe --}}
        <div>
            <x-input-label
                for="update_password_password"
                value="Nouveau mot de passe"
            />

            <x-text-input
                id="update_password_password"
                name="password"
                type="password"
                class="mt-1 block w-full"
                autocomplete="new-password"
            />

            <x-input-error
                :messages="$errors->updatePassword->get('password')"
                class="mt-2"
            />
        </div>


        {{-- Confirmation --}}
        <div>
            <x-input-label
                for="update_password_password_confirmation"
                value="Confirmer le nouveau mot de passe"
            />

            <x-text-input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="mt-1 block w-full"
                autocomplete="new-password"
            />

            <x-input-error
                :messages="$errors->updatePassword->get('password_confirmation')"
                class="mt-2"
            />
        </div>


        <div class="flex items-center gap-4">

            <x-primary-button>
                Modifier le mot de passe
            </x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-sm text-green-600"
                >
                    Mot de passe modifié.
                </p>
            @endif

        </div>
    </form>
</section>