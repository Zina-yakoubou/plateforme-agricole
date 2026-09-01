@csrf

<div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

    {{-- =========================================================
         CAMPAGNE
    ========================================================== --}}

    <div>
        <label
            for="campagne_id"
            class="block text-sm font-medium text-[#212529]"
        >
            Campagne <span class="text-red-500">*</span>
        </label>

        <select
            id="campagne_id"
            name="campagne_id"
            required
            class="mt-2 w-full rounded-lg
                   border border-[#e5e7eb]
                   bg-white px-4 py-3
                   text-sm
                   focus:border-[#006a4f]
                   focus:outline-none
                   focus:ring-2
                   focus:ring-[#006a4f]/10"
        >

            <option value="">
                Sélectionner une campagne
            </option>

            @foreach($campagnes as $campagne)

                <option
                    value="{{ $campagne->idCampagne }}"
                    @selected(
                        old(
                            'campagne_id',
                            $affectation->campagne_id ?? null
                        )
                        == $campagne->idCampagne
                    )
                >
                    {{ $campagne->libelle }}
                    — {{ $campagne->codeCampagne }}
                </option>

            @endforeach

        </select>

        @error('campagne_id')
            <p class="mt-1 text-xs text-red-600">
                {{ $message }}
            </p>
        @enderror
    </div>


    {{-- =========================================================
         ÉQUIPE
    ========================================================== --}}

    <div>
        <label
            for="equipe_id"
            class="block text-sm font-medium text-[#212529]"
        >
            Équipe <span class="text-red-500">*</span>
        </label>

        <select
            id="equipe_id"
            name="equipe_id"
            required
            class="mt-2 w-full rounded-lg
                   border border-[#e5e7eb]
                   bg-white px-4 py-3
                   text-sm
                   focus:border-[#006a4f]
                   focus:outline-none
                   focus:ring-2
                   focus:ring-[#006a4f]/10"
        >

            <option value="">
                Sélectionner une équipe
            </option>

            @foreach($equipes as $equipe)

                <option
                    value="{{ $equipe->idEquipe }}"
                    @selected(
                        old(
                            'equipe_id',
                            $affectation->equipe_id ?? null
                        )
                        == $equipe->idEquipe
                    )
                >
                    {{ $equipe->nom }}
                    — {{ $equipe->reference }}
                </option>

            @endforeach

        </select>

        @error('equipe_id')
            <p class="mt-1 text-xs text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>


    {{-- =========================================================
         VILLAGE
    ========================================================== --}}

    <div class="lg:col-span-2">

        <label
            for="village_id"
            class="block text-sm font-medium text-[#212529]"
        >
            Village d'affectation <span class="text-red-500">*</span>
        </label>

        <select
            id="village_id"
            name="village_id"
            required
            class="mt-2 w-full rounded-lg
                   border border-[#e5e7eb]
                   bg-white px-4 py-3
                   text-sm
                   focus:border-[#006a4f]
                   focus:outline-none
                   focus:ring-2
                   focus:ring-[#006a4f]/10"
        >

            <option value="">
                Sélectionner un village
            </option>

            @foreach($villages as $village)

                <option
                    value="{{ $village->idVillage }}"
                    @selected(
                        old(
                            'village_id',
                            $affectation->village_id ?? null
                        )
                        == $village->idVillage
                    )
                >
                    {{ $village->nom }}

                    @if($village->canton)
                        — Canton : {{ $village->canton->nom }}
                    @endif

                    @if($village->canton?->commune)
                        — Commune : {{ $village->canton->commune->nom }}
                    @endif

                </option>

            @endforeach

        </select>

        <p class="mt-1 text-xs text-gray-500">
            Le canton, la commune et la préfecture sont déduits automatiquement du village.
        </p>

        @error('village_id')
            <p class="mt-1 text-xs text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>


    {{-- =========================================================
         DATE DÉBUT
    ========================================================== --}}

    {{-- <div>

        <label
            for="dateDebut"
            class="block text-sm font-medium text-[#212529]"
        >
            Date de début <span class="text-red-500">*</span>
        </label>

        <input
            type="date"
            id="dateDebut"
            name="dateDebut"
            value="{{ old(
                'dateDebut',
                isset($affectation)
                    ? $affectation->dateDebut?->format('Y-m-d')
                    : now()->format('Y-m-d')
            ) }}"
            required
            class="mt-2 w-full rounded-lg
                   border border-[#e5e7eb]
                   px-4 py-3
                   text-sm
                   focus:border-[#006a4f]
                   focus:outline-none
                   focus:ring-2
                   focus:ring-[#006a4f]/10"
        >

        @error('dateDebut')
            <p class="mt-1 text-xs text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div> --}}


    {{-- =========================================================
         DATE FIN
    ========================================================== --}}

    {{-- <div>

        <label
            for="dateFin"
            class="block text-sm font-medium text-[#212529]"
        >
            Date de fin
        </label>

        <input
            type="date"
            id="dateFin"
            name="dateFin"
            value="{{ old(
                'dateFin',
                isset($affectation)
                    ? $affectation->dateFin?->format('Y-m-d')
                    : null
            ) }}"
            class="mt-2 w-full rounded-lg
                   border border-[#e5e7eb]
                   px-4 py-3
                   text-sm
                   focus:border-[#006a4f]
                   focus:outline-none
                   focus:ring-2
                   focus:ring-[#006a4f]/10"
        >

        @error('dateFin')
            <p class="mt-1 text-xs text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div> --}}


    {{-- =========================================================
         STATUT
    ========================================================== --}}

    <div>

        <label
            for="statut"
            class="block text-sm font-medium text-[#212529]"
        >
            Statut <span class="text-red-500">*</span>
        </label>

        <select
            id="statut"
            name="statut"
            required
            class="mt-2 w-full rounded-lg
                   border border-[#e5e7eb]
                   bg-white px-4 py-3
                   text-sm
                   focus:border-[#006a4f]
                   focus:outline-none
                   focus:ring-2
                   focus:ring-[#006a4f]/10"
        >

            <option
                value="active"
                @selected(
                    old(
                        'statut',
                        $affectation->statut ?? 'active'
                    ) === 'active'
                )
            >
                Active
            </option>

            <option
                value="terminee"
                @selected(
                    old(
                        'statut',
                        $affectation->statut ?? null
                    ) === 'terminee'
                )
            >
                Terminée
            </option>

            <option
                value="annulee"
                @selected(
                    old(
                        'statut',
                        $affectation->statut ?? null
                    ) === 'annulee'
                )
            >
                Annulée
            </option>

        </select>

        @error('statut')
            <p class="mt-1 text-xs text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>


    {{-- =========================================================
         OBSERVATIONS
    ========================================================== --}}

    <div class="lg:col-span-2">

        <label
            for="observations"
            class="block text-sm font-medium text-[#212529]"
        >
            Observations
        </label>

        <textarea
            id="observations"
            name="observations"
            rows="4"
            placeholder="Observations éventuelles..."
            class="mt-2 w-full rounded-lg
                   border border-[#e5e7eb]
                   px-4 py-3
                   text-sm
                   focus:border-[#006a4f]
                   focus:outline-none
                   focus:ring-2
                   focus:ring-[#006a4f]/10"
        >{{ old('observations', $affectation->observations ?? '') }}</textarea>

        @error('observations')
            <p class="mt-1 text-xs text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>

</div>