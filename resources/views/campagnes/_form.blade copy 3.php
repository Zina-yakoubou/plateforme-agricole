@csrf
<div class="grid grid-cols-1 gap-6 md:grid-cols-2">

    {{-- ================================================================
        LIBELLÉ
    ================================================================= --}}
    <div class="md:col-span-2">
        <label for="libelle" class="block text-sm font-medium text-gray-700">
            Libellé de la campagne <span class="text-red-500">*</span>
        </label>

        <input
            type="text"
            id="libelle"
            name="libelle"
            value="{{ old('libelle', $campagne->libelle ?? '') }}"
            required
            placeholder="Ex : Campagne nationale de recensement agricole 2026"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
        >

        @error('libelle')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>


    {{-- ================================================================
        DATE DEBUT
    ================================================================= --}}
    <div>
        <label for="dateDebut" class="block text-sm font-medium text-gray-700">
            Date de début <span class="text-red-500">*</span>
        </label>

        <input
            type="datetime-local"
            id="dateDebut"
            name="dateDebut"
            value="{{ old(
                'dateDebut',
                isset($campagne) && $campagne->dateDebut
                    ? $campagne->dateDebut->format('Y-m-d\TH:i')
                    : ''
            ) }}"
            min="{{ now()->format('Y-m-d\TH:i') }}"
            required
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm
                focus:border-green-500 focus:ring-green-500"
        >

        <p class="mt-1 text-xs text-gray-500">
            La campagne doit commencer aujourd’hui ou à une date ultérieure.
        </p>

        @error('dateDebut')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>


    {{-- ================================================================
        DATE FIN
    ================================================================= --}}
    <div>
        <label for="dateFin" class="block text-sm font-medium text-gray-700">
            Date de fin
        </label>

        <input
            type="datetime-local"
            id="dateFin"
            name="dateFin"
            value="{{ old(
                'dateFin',
                isset($campagne) && $campagne->dateFin
                    ? $campagne->dateFin->format('Y-m-d\TH:i')
                    : ''
            ) }}"
            min="{{ old(
                'dateDebut',
                isset($campagne) && $campagne->dateDebut
                    ? $campagne->dateDebut->format('Y-m-d\TH:i')
                    : now()->format('Y-m-d\TH:i')
            ) }}"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm
                focus:border-green-500 focus:ring-green-500"
        >

        <p class="mt-1 text-xs text-gray-500">
            La date de fin doit être postérieure ou égale à la date de début.
        </p>

        @error('dateFin')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>


    {{-- ================================================================
        PORTÉE + ZONES
    ================================================================= --}}
    <div
        class="md:col-span-2"
        x-data="{
            portee: @js(old('portee', $campagne->portee ?? '')),

            regionIds: @js(
                old(
                    'region_ids',
                    isset($campagne)
                        ? $campagne->zones
                            ->pluck('region_id')
                            ->filter()
                            ->values()
                            ->toArray()
                        : []
                )
            ),

            prefectureIds: @js(
                old(
                    'prefecture_ids',
                    isset($campagne)
                        ? $campagne->zones
                            ->pluck('prefecture_id')
                            ->filter()
                            ->values()
                            ->toArray()
                        : []
                )
            ),

            communeIds: @js(
                old(
                    'commune_ids',
                    isset($campagne)
                        ? $campagne->zones
                            ->pluck('commune_id')
                            ->filter()
                            ->values()
                            ->toArray()
                        : []
                )
            ),

            cantonIds: @js(
                old(
                    'canton_ids',
                    isset($campagne)
                        ? $campagne->zones
                            ->pluck('canton_id')
                            ->filter()
                            ->values()
                            ->toArray()
                        : []
                )
            ),

            villageIds: @js(
                old(
                    'village_ids',
                    isset($campagne)
                        ? $campagne->zones
                            ->pluck('village_id')
                            ->filter()
                            ->values()
                            ->toArray()
                        : []
                )
            ),

            changePortee() {

                if (this.portee === 'nationale') {
                    this.regionIds = [];
                    this.prefectureIds = [];
                    this.communeIds = [];
                    this.cantonIds = [];
                    this.villageIds = [];
                }

                if (this.portee === 'regionale') {
                    this.prefectureIds = [];
                    this.communeIds = [];
                    this.cantonIds = [];
                    this.villageIds = [];
                }

                if (this.portee === 'prefectorale') {
                    this.regionIds = [];
                }
            },

            togglePrefecture(id) {

                id = String(id);

                if (this.prefectureIds.map(String).includes(id)) {

                    /*
                     * Une préfecture entière est sélectionnée.
                     * Les niveaux inférieurs deviennent inutiles.
                     */
                    this.removeChildrenOfPrefecture(id);
                }
            },

            removeChildrenOfPrefecture(prefectureId) {

                const prefecture = document.querySelector(
                    '[data-prefecture-id="' + prefectureId + '"]'
                );

                if (!prefecture) {
                    return;
                }

                prefecture
                    .querySelectorAll('input[data-child-type="commune"]')
                    .forEach(input => {

                        this.communeIds = this.communeIds.filter(
                            id => String(id) !== String(input.value)
                        );

                    });

                prefecture
                    .querySelectorAll('input[data-child-type="canton"]')
                    .forEach(input => {

                        this.cantonIds = this.cantonIds.filter(
                            id => String(id) !== String(input.value)
                        );

                    });

                prefecture
                    .querySelectorAll('input[data-child-type="village"]')
                    .forEach(input => {

                        this.villageIds = this.villageIds.filter(
                            id => String(id) !== String(input.value)
                        );

                    });
            },

            toggleCommune(id) {

                id = String(id);

                if (this.communeIds.map(String).includes(id)) {

                    this.removeChildrenOfCommune(id);
                }
            },

            removeChildrenOfCommune(communeId) {

                const commune = document.querySelector(
                    '[data-commune-id="' + communeId + '"]'
                );

                if (!commune) {
                    return;
                }

                commune
                    .querySelectorAll('input[data-child-type="canton"]')
                    .forEach(input => {

                        this.cantonIds = this.cantonIds.filter(
                            id => String(id) !== String(input.value)
                        );

                    });

                commune
                    .querySelectorAll('input[data-child-type="village"]')
                    .forEach(input => {

                        this.villageIds = this.villageIds.filter(
                            id => String(id) !== String(input.value)
                        );

                    });
            },

            toggleCanton(id) {

                id = String(id);

                if (this.cantonIds.map(String).includes(id)) {

                    this.removeChildrenOfCanton(id);
                }
            },

            removeChildrenOfCanton(cantonId) {

                const canton = document.querySelector(
                    '[data-canton-id="' + cantonId + '"]'
                );

                if (!canton) {
                    return;
                }

                canton
                    .querySelectorAll('input[data-child-type="village"]')
                    .forEach(input => {

                        this.villageIds = this.villageIds.filter(
                            id => String(id) !== String(input.value)
                        );

                    });
            },

            isPrefectureSelected(id) {
                return this.prefectureIds
                    .map(String)
                    .includes(String(id));
            },

            isCommuneSelected(id) {
                return this.communeIds
                    .map(String)
                    .includes(String(id));
            },

            isCantonSelected(id) {
                return this.cantonIds
                    .map(String)
                    .includes(String(id));
            }
        }"
    >

        {{-- ============================================================
            PORTÉE
        ============================================================= --}}
        <div>
            <label
                for="portee"
                class="block text-sm font-medium text-gray-700"
            >
                Portée de la campagne
                <span class="text-red-500">*</span>
            </label>

            <select
                id="portee"
                name="portee"
                x-model="portee"
                @change="changePortee()"
                required
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm
                    focus:border-green-500 focus:ring-green-500"
            >
                <option value="nationale">
                    Nationale
                </option>

                <option value="regionale">
                    Régionale
                </option>

                <option value="prefectorale">
                    Préfectorale
                </option>
            </select>

            @error('portee')
                <p class="mt-1 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror
        </div>


        {{-- ============================================================
            TERRITOIRE CONCERNÉ
        ============================================================= --}}
        <div class="mt-6">

            <div class="mb-4">
                <h3 class="text-sm font-semibold text-gray-800">
                    Territoire concerné
                </h3>

                <p class="mt-1 text-xs text-gray-500">
                    Définissez précisément le territoire administratif
                    couvert par cette campagne.
                </p>
            </div>


            {{-- ========================================================
                NATIONALE
            ========================================================= --}}
            <template x-if="portee === 'nationale'">

                <div class="rounded-lg border border-green-200 bg-green-50 p-4">

                    <div class="flex items-start gap-3">

                        <svg
                            class="mt-0.5 h-5 w-5 text-green-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11v10a1 1 0 01-1 1h-3m-6 0h6"
                            />
                        </svg>

                        <div>

                            <p class="text-sm font-semibold text-green-800">
                                Campagne nationale
                            </p>

                            <p class="mt-1 text-sm text-green-700">
                                Cette campagne couvre automatiquement
                                l'ensemble du territoire national.
                            </p>

                        </div>

                    </div>

                </div>

            </template>


            {{-- ========================================================
                RÉGIONALE
            ========================================================= --}}
            <template x-if="portee === 'regionale'">

                <div class="rounded-lg border border-gray-200 p-4">

                    <div class="mb-4">

                        <p class="text-sm font-semibold text-gray-800">
                            Régions concernées
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Sélectionnez une ou plusieurs régions.
                            Toutes les préfectures appartenant à ces régions
                            seront concernées.
                        </p>

                    </div>


                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">

                        @foreach($regions as $region)

                            <label
                                class="flex cursor-pointer items-center gap-3
                                    rounded-lg border border-gray-200 p-4
                                    transition hover:bg-gray-50"
                            >

                                <input
                                    type="checkbox"
                                    name="region_ids[]"
                                    value="{{ $region->idRegion }}"
                                    x-model="regionIds"
                                    class="rounded border-gray-300 text-green-600
                                        focus:ring-green-500"
                                >

                                <div class="min-w-0">

                                    <p class="text-sm font-medium text-gray-700">
                                        {{ $region->nom }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ $region->prefectures->count() }}
                                        préfecture(s)
                                    </p>

                                </div>

                            </label>

                        @endforeach

                    </div>

                </div>

            </template>


            {{-- ========================================================
                PRÉFECTORALE
            ========================================================= --}}
            <template x-if="portee === 'prefectorale'">

                <div class="rounded-lg border border-gray-200 bg-white p-4">

                    <div class="mb-5">

                        <p class="text-sm font-semibold text-gray-800">
                            Zones bénéficiaires
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Sélectionnez une préfecture entière ou descendez
                            jusqu'à la commune, au canton ou au village.
                            Lorsqu'un niveau supérieur est sélectionné,
                            ses niveaux inférieurs sont masqués.
                        </p>

                    </div>


                    <div class="space-y-4">

                        @foreach($regions as $region)

                            @if($region->prefectures->count())

                                <div>

                                    {{-- RÉGION --}}
                                    <div class="mb-3 flex items-center gap-2
                                        border-b border-gray-100 pb-2">

                                        <div
                                            class="flex h-7 w-7 items-center justify-center
                                                rounded-lg bg-gray-100 text-gray-500"
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
                                                    d="M3 6h18M3 12h18M3 18h18"
                                                />
                                            </svg>
                                        </div>

                                        <div>
                                            <p class="text-xs font-semibold uppercase
                                                tracking-wide text-gray-500">
                                                {{ $region->nom }}
                                            </p>
                                        </div>

                                    </div>


                                    <div class="space-y-3">

                                        @foreach($region->prefectures as $prefecture)

                                            {{-- =================================================
                                                PRÉFECTURE
                                            ================================================== --}}
                                            <div
                                                data-prefecture-id="{{ $prefecture->idPrefecture }}"
                                                class="rounded-xl border border-gray-200
                                                    overflow-hidden"
                                            >

                                                <div
                                                    class="flex items-center gap-3
                                                        bg-gray-50 px-4 py-3"
                                                >

                                                    <input
                                                        type="checkbox"
                                                        name="prefecture_ids[]"
                                                        value="{{ $prefecture->idPrefecture }}"
                                                        x-model="prefectureIds"
                                                        @change="togglePrefecture('{{ $prefecture->idPrefecture }}')"
                                                        class="rounded border-gray-300
                                                            text-green-600 focus:ring-green-500"
                                                    >

                                                    <div class="flex-1">

                                                        <p class="text-sm font-semibold
                                                            text-gray-800">
                                                            {{ $prefecture->nom }}
                                                        </p>

                                                        <p class="mt-0.5 text-xs text-gray-500">
                                                            Toute la préfecture
                                                        </p>

                                                    </div>

                                                    <template
                                                        x-if="isPrefectureSelected('{{ $prefecture->idPrefecture }}')"
                                                    >
                                                        <span
                                                            class="rounded-full bg-green-100 px-2.5 py-1
                                                                text-xs font-medium text-green-700"
                                                        >
                                                            Préfecture entière
                                                        </span>
                                                    </template>

                                                </div>


                                                {{-- =================================================
                                                    COMMUNES
                                                ================================================== --}}
                                                <div
                                                    x-show="!isPrefectureSelected('{{ $prefecture->idPrefecture }}')"
                                                    x-transition
                                                    class="space-y-2 p-3"
                                                >

                                                    @forelse($prefecture->communes as $commune)

                                                        <div
                                                            data-commune-id="{{ $commune->idCommune }}"
                                                            class="rounded-lg border border-gray-100"
                                                        >

                                                            {{-- COMMUNE --}}
                                                            <div
                                                                class="flex items-center gap-3
                                                                    px-3 py-2.5 hover:bg-gray-50"
                                                            >

                                                                <input
                                                                    type="checkbox"
                                                                    name="commune_ids[]"
                                                                    value="{{ $commune->idCommune }}"
                                                                    data-child-type="commune"
                                                                    x-model="communeIds"
                                                                    @change="toggleCommune('{{ $commune->idCommune }}')"
                                                                    class="rounded border-gray-300
                                                                        text-green-600
                                                                        focus:ring-green-500"
                                                                >

                                                                <div class="flex-1">

                                                                    <p class="text-sm font-medium
                                                                        text-gray-700">
                                                                        {{ $commune->nom }}
                                                                    </p>

                                                                    <p class="text-xs text-gray-400">
                                                                        Toute la commune
                                                                    </p>

                                                                </div>

                                                                <template
                                                                    x-if="isCommuneSelected('{{ $commune->idCommune }}')"
                                                                >
                                                                    <span
                                                                        class="rounded-full bg-blue-50 px-2 py-1
                                                                            text-xs font-medium text-blue-700"
                                                                    >
                                                                        Commune entière
                                                                    </span>
                                                                </template>

                                                            </div>


                                                            {{-- =================================================
                                                                CANTONS
                                                            ================================================== --}}
                                                            <div
                                                                x-show="!isCommuneSelected('{{ $commune->idCommune }}')"
                                                                x-transition
                                                                class="ml-7 space-y-2 border-l
                                                                    border-gray-200 py-2 pl-3"
                                                            >

                                                                @forelse($commune->cantons as $canton)

                                                                    <div
                                                                        data-canton-id="{{ $canton->idCanton }}"
                                                                        class="rounded-lg border border-gray-100"
                                                                    >

                                                                        {{-- CANTON --}}
                                                                        <div
                                                                            class="flex items-center gap-3
                                                                                px-3 py-2.5 hover:bg-gray-50"
                                                                        >

                                                                            <input
                                                                                type="checkbox"
                                                                                name="canton_ids[]"
                                                                                value="{{ $canton->idCanton }}"
                                                                                data-child-type="canton"
                                                                                x-model="cantonIds"
                                                                                @change="toggleCanton('{{ $canton->idCanton }}')"
                                                                                class="rounded border-gray-300
                                                                                    text-green-600
                                                                                    focus:ring-green-500"
                                                                            >

                                                                            <div class="flex-1">

                                                                                <p class="text-sm font-medium
                                                                                    text-gray-700">
                                                                                    {{ $canton->nom }}
                                                                                </p>

                                                                                <p class="text-xs text-gray-400">
                                                                                    Tout le canton
                                                                                </p>

                                                                            </div>

                                                                            <template
                                                                                x-if="isCantonSelected('{{ $canton->idCanton }}')"
                                                                            >
                                                                                <span
                                                                                    class="rounded-full bg-purple-50 px-2 py-1
                                                                                        text-xs font-medium text-purple-700"
                                                                                >
                                                                                    Canton entier
                                                                                </span>
                                                                            </template>

                                                                        </div>


                                                                        {{-- =================================================
                                                                            VILLAGES
                                                                        ================================================== --}}
                                                                        <div
                                                                            x-show="!isCantonSelected('{{ $canton->idCanton }}')"
                                                                            x-transition
                                                                            class="ml-7 space-y-1 border-l
                                                                                border-gray-200 py-2 pl-3"
                                                                        >

                                                                            @forelse($canton->villages as $village)

                                                                                <label
                                                                                    class="flex cursor-pointer
                                                                                        items-center gap-3 rounded-lg
                                                                                        px-3 py-2 transition
                                                                                        hover:bg-gray-50"
                                                                                >

                                                                                    <input
                                                                                        type="checkbox"
                                                                                        name="village_ids[]"
                                                                                        value="{{ $village->idVillage }}"
                                                                                        data-child-type="village"
                                                                                        x-model="villageIds"
                                                                                        class="rounded border-gray-300
                                                                                            text-green-600
                                                                                            focus:ring-green-500"
                                                                                    >

                                                                                    <span class="text-sm text-gray-600">
                                                                                        {{ $village->nom }}
                                                                                    </span>

                                                                                </label>

                                                                            @empty

                                                                                <p class="px-3 py-2 text-xs
                                                                                    text-gray-400">
                                                                                    Aucun village disponible.
                                                                                </p>

                                                                            @endforelse

                                                                        </div>

                                                                    </div>

                                                                @empty

                                                                    <p class="px-3 py-2 text-xs text-gray-400">
                                                                        Aucun canton disponible.
                                                                    </p>

                                                                @endforelse

                                                            </div>

                                                        </div>

                                                    @empty

                                                        <p class="px-3 py-2 text-xs text-gray-400">
                                                            Aucune commune disponible.
                                                        </p>

                                                    @endforelse

                                                </div>

                                            </div>

                                        @endforeach

                                    </div>

                                </div>

                            @endif

                        @endforeach

                    </div>

                </div>

            </template>


            {{-- ========================================================
                ERREURS
            ========================================================= --}}
            @error('region_ids')
                <p class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

            @error('prefecture_ids')
                <p class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

            @error('commune_ids')
                <p class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

            @error('canton_ids')
                <p class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

            @error('village_ids')
                <p class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

            @error('zones')
                <p class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

        </div>

    </div>


    {{-- ================================================================
        DESCRIPTION
    ================================================================= --}}
    <div class="md:col-span-2">

        <label
            for="description"
            class="block text-sm font-medium text-gray-700"
        >
            Description
        </label>

        <textarea
            id="description"
            name="description"
            rows="3"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm
                focus:border-green-500 focus:ring-green-500"
        >{{ old('description', $campagne->description ?? '') }}</textarea>

        @error('description')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>


    {{-- ================================================================
        OBJECTIFS
    ================================================================= --}}
    <div class="md:col-span-2">

        <label
            for="objectifs"
            class="block text-sm font-medium text-gray-700"
        >
            Objectifs <span class="text-red-500">*</span>
        </label>

        <textarea
            id="objectifs"
            name="objectifs"
            rows="4"
            required
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm
                focus:border-green-500 focus:ring-green-500"
        >{{ old('objectifs', $campagne->objectifs ?? '') }}</textarea>

        @error('objectifs')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>


    {{-- ================================================================
        RESULTATS ATTENDUS
    ================================================================= --}}
    <div class="md:col-span-2">

        <label
            for="resultatsAttendus"
            class="block text-sm font-medium text-gray-700"
        >
            Résultats attendus <span class="text-red-500">*</span>
        </label>

        <textarea
            id="resultatsAttendus"
            name="resultatsAttendus"
            rows="4"
            required
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm
                focus:border-green-500 focus:ring-green-500"
        >{{ old('resultatsAttendus', $campagne->resultatsAttendus ?? '') }}</textarea>

        @error('resultatsAttendus')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>


    {{-- ================================================================
        METHODOLOGIE
    ================================================================= --}}
    <div class="md:col-span-2">

        <label
            for="methodologie"
            class="block text-sm font-medium text-gray-700"
        >
            Méthodologie
        </label>

        <textarea
            id="methodologie"
            name="methodologie"
            rows="4"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm
                focus:border-green-500 focus:ring-green-500"
        >{{ old('methodologie', $campagne->methodologie ?? '') }}</textarea>

        @error('methodologie')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>


    {{-- ================================================================
        INSTRUCTIONS
    ================================================================= --}}
    <div class="md:col-span-2">

        <label
            for="instructions"
            class="block text-sm font-medium text-gray-700"
        >
            Instructions aux agents recenseurs
        </label>

        <textarea
            id="instructions"
            name="instructions"
            rows="4"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm
                focus:border-green-500 focus:ring-green-500"
        >{{ old('instructions', $campagne->instructions ?? '') }}</textarea>

        @error('instructions')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>


    {{-- ================================================================
        QUESTIONNAIRES
    ================================================================= --}}
    <div class="md:col-span-2">

        <label class="mb-2 block text-sm font-medium text-gray-700">
            Questionnaires de collecte
        </label>

        @php

            $selectionnes = old(
                'questionnaire_ids',
                isset($campagne)
                    ? $campagne->questionnaires
                        ->pluck('idQuestionnaire')
                        ->toArray()
                    : []
            );

        @endphp

        <div class="space-y-2 rounded-lg border border-gray-200 p-4">

            @forelse($questionnaires as $questionnaire)

                <label class="flex cursor-pointer items-center gap-3">

                    <input
                        type="checkbox"
                        name="questionnaire_ids[]"
                        value="{{ $questionnaire->idQuestionnaire }}"
                        @checked(
                            in_array(
                                $questionnaire->idQuestionnaire,
                                $selectionnes
                            )
                        )
                        class="rounded border-gray-300 text-green-600
                            focus:ring-green-500"
                    >

                    <span class="text-sm text-gray-700">
                        {{ $questionnaire->titre }}
                    </span>

                </label>

            @empty

                <p class="text-sm text-amber-600">
                    Aucun questionnaire disponible.
                </p>

            @endforelse

        </div>

        @error('questionnaire_ids')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>

</div>


{{-- ================================================================
    BOUTONS
================================================================= --}}
<div class="mt-8 flex justify-end gap-3">

    <a
        href="{{ route('campagnes.index') }}"
        class="rounded-lg border border-gray-300 px-5 py-2.5
            text-sm font-medium text-gray-700 transition
            hover:bg-gray-100"
    >
        Annuler
    </a>

    <button
        type="submit"
        class="rounded-lg bg-green-600 px-6 py-2.5
            text-sm font-semibold text-white shadow-sm transition
            hover:bg-green-700 focus:outline-none focus:ring-2
            focus:ring-green-500 focus:ring-offset-2"
    >
        {{ isset($campagne) ? 'Mettre à jour' : 'Enregistrer' }}
    </button>

</div>
