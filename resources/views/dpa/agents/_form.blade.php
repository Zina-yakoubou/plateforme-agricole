<div class="grid grid-cols-1 gap-6 md:grid-cols-2">

    {{-- =========================================================
        PRÉFECTURE
    ========================================================== --}}
    <div class="md:col-span-2">

        <label class="mb-1.5 block text-sm font-medium text-slate-700">
            Préfecture de rattachement
        </label>

        <div class="flex items-center gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3">

            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white text-[#006a4f]">

                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M12 21s8-4.5 8-11a8 8 0 10-16 0c0 6.5 8 11 8 11z"
                    />

                    <circle
                        cx="12"
                        cy="10"
                        r="2.5"
                        stroke-width="1.8"
                    />
                </svg>

            </div>

            <div>

                <p class="text-sm font-semibold text-[#006a4f]">
                    {{ $prefecture?->nom ?? 'Préfecture non définie' }}
                </p>

                <p class="mt-0.5 text-xs text-green-700">
                    Le compte sera automatiquement rattaché à votre préfecture.
                </p>

            </div>

        </div>

    </div>


    {{-- =========================================================
        NOM COMPLET
    ========================================================== --}}
    <div>

        <label
            for="name"
            class="mb-1.5 block text-sm font-medium text-slate-700"
        >
            Nom complet
            <span class="text-red-500">*</span>
        </label>

        <input
            id="name"
            type="text"
            name="name"
            value="{{ old('name', $agent->name ?? '') }}"
            required
            autocomplete="name"
            placeholder="Nom et prénoms"
            class="block w-full rounded-lg border-slate-300 px-4 py-2.5 text-sm shadow-sm transition focus:border-[#006a4f] focus:ring-[#006a4f]"
        >

        @error('name')
            <p class="mt-1.5 text-xs text-red-600">
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
            class="mb-1.5 block text-sm font-medium text-slate-700"
        >
            Téléphone
            <span class="text-red-500">*</span>
        </label>

        <input
            id="telephone"
            type="text"
            name="telephone"
            value="{{ old('telephone', $agent->telephone ?? '') }}"
            required
            autocomplete="tel"
            placeholder="+228 XX XX XX XX"
            class="block w-full rounded-lg border-slate-300 px-4 py-2.5 text-sm shadow-sm transition focus:border-[#006a4f] focus:ring-[#006a4f]"
        >

        <p class="mt-1.5 text-xs text-slate-500">
            Exemple : +22890123456
        </p>

        @error('telephone')
            <p class="mt-1.5 text-xs text-red-600">
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
            class="mb-1.5 block text-sm font-medium text-slate-700"
        >
            Adresse e-mail
            <span class="text-xs font-normal text-slate-400">
                (facultatif)
            </span>
        </label>

        <input
            id="email"
            type="email"
            name="email"
            value="{{ old('email', $agent->email ?? '') }}"
            autocomplete="email"
            placeholder="exemple@email.com"
            class="block w-full rounded-lg border-slate-300 px-4 py-2.5 text-sm shadow-sm transition focus:border-[#006a4f] focus:ring-[#006a4f]"
        >

        @error('email')
            <p class="mt-1.5 text-xs text-red-600">
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
            class="mb-1.5 block text-sm font-medium text-slate-700"
        >
            Type de compte
            <span class="text-red-500">*</span>
        </label>

        <select
            id="role_id"
            name="role_id"
            required
            class="block w-full rounded-lg border-slate-300 px-4 py-2.5 text-sm shadow-sm transition focus:border-[#006a4f] focus:ring-[#006a4f]"
        >

            <option value="">
                Sélectionner un rôle
            </option>

            @foreach($roles as $role)

                <option
                    value="{{ $role->idRole }}"
                    @selected(old('role_id', $agent->role_id ?? '') == $role->idRole)
                >
                    {{ $role->nom }}
                </option>

            @endforeach

        </select>

        <p class="mt-1.5 text-xs text-slate-500">
            Le DPA peut créer un superviseur ou un agent recenseur.
        </p>

        @error('role_id')
            <p class="mt-1.5 text-xs text-red-600">
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
            class="mb-1.5 block text-sm font-medium text-slate-700"
        >
            Mot de passe
            <span class="text-red-500">*</span>
        </label>

        <input
            id="password"
            type="password"
            name="password"
            required
            autocomplete="new-password"
            class="block w-full rounded-lg border-slate-300 px-4 py-2.5 text-sm shadow-sm transition focus:border-[#006a4f] focus:ring-[#006a4f]"
        >

        <p class="mt-1.5 text-xs text-slate-500">
            Minimum 8 caractères.
        </p>

        @error('password')
            <p class="mt-1.5 text-xs text-red-600">
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
            class="mb-1.5 block text-sm font-medium text-slate-700"
        >
            Confirmation du mot de passe
            <span class="text-red-500">*</span>
        </label>

        <input
            id="password_confirmation"
            type="password"
            name="password_confirmation"
            required
            autocomplete="new-password"
            class="block w-full rounded-lg border-slate-300 px-4 py-2.5 text-sm shadow-sm transition focus:border-[#006a4f] focus:ring-[#006a4f]"
        >

    </div>

</div>