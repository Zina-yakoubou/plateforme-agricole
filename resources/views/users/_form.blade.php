@csrf

<div class="grid grid-cols-1 gap-6 md:grid-cols-2">

    {{-- =========================================================
         NOM COMPLET
    ========================================================== --}}
    <div>
        <label
            for="name"
            class="block text-sm font-medium text-gray-700"
        >
            Nom complet <span class="text-red-500">*</span>
        </label>

        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $user->name ?? '') }}"
            required
            autocomplete="name"
            class="mt-1 block w-full rounded-lg border-gray-300
                   shadow-sm focus:border-green-500 focus:ring-green-500"
        >

        @error('name')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror
    </div>


    {{-- =========================================================
         TÉLÉPHONE
    ========================================================== --}}
    <div>
        <label
            for="telephone"
            class="block text-sm font-medium text-gray-700"
        >
            Téléphone <span class="text-red-500">*</span>
        </label>

        <input
            type="text"
            id="telephone"
            name="telephone"
            value="{{ old('telephone', $user->telephone ?? '') }}"
            required
            autocomplete="tel"
            placeholder="+228 90 11 22 33"
            class="mt-1 block w-full rounded-lg border-gray-300
                   shadow-sm focus:border-green-500 focus:ring-green-500"
        >

        <p class="mt-1 text-xs text-gray-500">
            Exemple : +228 90 11 22 33
        </p>

        @error('telephone')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror
    </div>


    {{-- =========================================================
         EMAIL
    ========================================================== --}}
    <div>
        <label
            for="email"
            class="block text-sm font-medium text-gray-700"
        >
            Adresse e-mail
        </label>

        <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email', $user->email ?? '') }}"
            autocomplete="email"
            placeholder="exemple@email.com"
            class="mt-1 block w-full rounded-lg border-gray-300
                   shadow-sm focus:border-green-500 focus:ring-green-500"
        >

        <p class="mt-1 text-xs text-gray-500">
            Facultatif
        </p>

        @error('email')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror
    </div>


    {{-- =========================================================
         RÔLE
    ========================================================== --}}
    <div>

        <label
            for="role_id"
            class="block text-sm font-medium text-gray-700"
        >
            Rôle <span class="text-red-500">*</span>
        </label>

        @if($roles->count() === 1)

            {{-- Un seul rôle autorisé --}}
            <input
                type="hidden"
                name="role_id"
                value="{{ $roles->first()->idRole }}"
            >

            <input
                type="text"
                value="{{ $roles->first()->nom }}"
                readonly
                class="mt-1 block w-full rounded-lg
                       border-gray-300 bg-gray-100
                       text-gray-600"
            >

        @else

            <select
                id="role_id"
                name="role_id"
                required
                class="mt-1 block w-full rounded-lg
                       border-gray-300 shadow-sm
                       focus:border-green-500
                       focus:ring-green-500"
            >

                <option value="">
                    Sélectionner un rôle
                </option>

                @foreach($roles as $item)

                    <option
                        value="{{ $item->idRole }}"
                        @selected(
                            old(
                                'role_id',
                                $role->idRole ?? ($user->role_id ?? '')
                            ) == $item->idRole
                        )
                    >
                        {{ $item->nom }}
                    </option>

                @endforeach

            </select>

        @endif

        @error('role_id')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>


    {{-- =========================================================
         MOT DE PASSE
    ========================================================== --}}
    <div>
        <label
            for="password"
            class="block text-sm font-medium text-gray-700"
        >

            @isset($user)
                Nouveau mot de passe
            @else
                Mot de passe <span class="text-red-500">*</span>
            @endisset

        </label>

        <input
            type="password"
            id="password"
            name="password"
            @empty($user) required @endempty
            autocomplete="new-password"
            class="mt-1 block w-full rounded-lg
                   border-gray-300 shadow-sm
                   focus:border-green-500
                   focus:ring-green-500"
        >

        <p class="mt-1 text-xs text-gray-500">
            Minimum 8 caractères.
        </p>

        @error('password')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror
    </div>


    {{-- =========================================================
         CONFIRMATION MOT DE PASSE
    ========================================================== --}}
    <div>
        <label
            for="password_confirmation"
            class="block text-sm font-medium text-gray-700"
        >
            Confirmation du mot de passe
        </label>

        <input
            type="password"
            id="password_confirmation"
            name="password_confirmation"
            @empty($user) required @endempty
            autocomplete="new-password"
            class="mt-1 block w-full rounded-lg
                   border-gray-300 shadow-sm
                   focus:border-green-500
                   focus:ring-green-500"
        >

        @error('password_confirmation')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror
    </div>


    {{-- =========================================================
         STATUT — UNIQUEMENT EN MODIFICATION
    ========================================================== --}}
    @isset($user)

        <div>

            <label
                for="statut"
                class="block text-sm font-medium text-gray-700"
            >
                Statut
            </label>

            <select
                id="statut"
                name="statut"
                class="mt-1 block w-full rounded-lg
                       border-gray-300 shadow-sm
                       focus:border-green-500
                       focus:ring-green-500"
            >

                <option
                    value="1"
                    @selected(old('statut', $user->statut) == 1)
                >
                    Actif
                </option>

                <option
                    value="0"
                    @selected(old('statut', $user->statut) == 0)
                >
                    Inactif
                </option>

            </select>

            @error('statut')
                <p class="mt-1 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

        </div>

    @endisset

</div>


{{-- =========================================================
     ACTIONS
========================================================= --}}
<div class="mt-8 flex justify-end gap-3">

    <a
        href="{{ route('users.index') }}"
        class="rounded-lg border border-gray-300
               px-5 py-2.5 text-sm font-medium
               text-gray-700 transition
               hover:bg-gray-100"
    >
        Annuler
    </a>

    <button
        type="submit"
        class="rounded-lg bg-green-600
               px-6 py-2.5 text-sm font-semibold
               text-white shadow-sm transition
               hover:bg-green-700
               focus:outline-none
               focus:ring-2
               focus:ring-green-500
               focus:ring-offset-2"
    >
        {{ isset($user) ? 'Mettre à jour' : 'Enregistrer' }}
    </button>

</div>