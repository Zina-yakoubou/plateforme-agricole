<section>
    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div class="grid gap-6 md:grid-cols-2">

            <div>
                <x-input-label
                    for="name"
                    value="Nom complet"
                    class="text-sm font-medium text-slate-700"
                />

                <x-text-input
                    id="name"
                    name="name"
                    type="text"
                    class="mt-2 block w-full"
                    :value="old('name', $user->name)"
                    required
                    autocomplete="name"
                />

                <x-input-error
                    class="mt-2"
                    :messages="$errors->get('name')"
                />
            </div>

            <div>
                <x-input-label
                    for="telephone"
                    value="Téléphone"
                    class="text-sm font-medium text-slate-700"
                />

                <x-text-input
                    id="telephone"
                    name="telephone"
                    type="text"
                    class="mt-2 block w-full"
                    :value="old('telephone', $user->telephone)"
                    autocomplete="tel"
                />

                <x-input-error
                    class="mt-2"
                    :messages="$errors->get('telephone')"
                />
            </div>

        </div>

        <div>
            <x-input-label
                for="email"
                value="Adresse e-mail"
                class="text-sm font-medium text-slate-700"
            />

            <x-text-input
                id="email"
                name="email"
                type="email"
                class="mt-2 block w-full"
                :value="old('email', $user->email)"
                required
                autocomplete="email"
            />

            <x-input-error
                class="mt-2"
                :messages="$errors->get('email')"
            />
        </div>

        <div class="grid gap-6 md:grid-cols-2">

            <div>
                <x-input-label
                    value="Rôle"
                    class="text-sm font-medium text-slate-700"
                />

                <x-text-input
                    :value="$user->role?->nom ?? 'Aucun rôle'"
                    class="mt-2 block w-full bg-slate-50"
                    readonly
                />
            </div>

            <div>
                <x-input-label
                    value="Statut"
                    class="text-sm font-medium text-slate-700"
                />

                <x-text-input
                    :value="$user->statut ? 'Actif' : 'Inactif'"
                    class="mt-2 block w-full bg-slate-50"
                    readonly
                />
            </div>

        </div>

        <div class="flex items-center gap-4 border-t border-slate-100 pt-5">

            <x-primary-button>
                Enregistrer les modifications
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <span class="text-sm font-medium text-green-600">
                    Modifications enregistrées.
                </span>
            @endif

        </div>

    </form>
</section>