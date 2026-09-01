@csrf

<div class="grid grid-cols-1 gap-6 md:grid-cols-2">

    {{-- LIBELLÉ --}}
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

   {{-- DATE DEBUT --}}
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


    {{-- DATE FIN --}}
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

    {{-- PORTÉE + ZONES --}}
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

            changePortee() {
                /*
                * Une campagne nationale n'a besoin
                * d'aucune sélection territoriale.
                */
                if (this.portee === 'nationale') {
                    this.regionIds = [];
                    this.prefectureIds = [];
                }

                /*
                * Quand on passe en régionale,
                * les anciennes préfectures ne sont plus pertinentes.
                */
                if (this.portee === 'regionale') {
                    this.prefectureIds = [];
                }

                /*
                * Quand on passe en préfectorale,
                * les régions sélectionnées ne sont plus utilisées.
                */
                if (this.portee === 'prefectorale') {
                    this.regionIds = [];
                }
            }
        }"
    >

        {{-- PORTÉE --}}
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
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
            >
                {{-- <option value="">
                    Sélectionner la portée
                </option> --}}

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


        {{-- ZONES CONCERNÉES --}}
        <div class="mt-6">

            <div class="mb-4">
                <h3 class="text-sm font-semibold text-gray-800">
                    Territoire concerné
                </h3>

                <p class="mt-1 text-xs text-gray-500">
                    Définissez le territoire administratif couvert
                    par cette campagne.
                </p>
            </div>


            {{-- ==========================================================
                NATIONALE
            =========================================================== --}}
            <template x-if="portee === 'nationale'">

                <div
                    class="rounded-lg border border-green-200 bg-green-50 p-4"
                >

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


            {{-- ==========================================================
                RÉGIONALE
            =========================================================== --}}
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
                                class="flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 p-4 transition hover:bg-gray-50"
                            >

                                <input
                                    type="checkbox"
                                    name="region_ids[]"
                                    value="{{ $region->idRegion }}"
                                    x-model="regionIds"
                                    class="rounded border-gray-300 text-green-600 focus:ring-green-500"
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


            {{-- ==========================================================
                PRÉFECTORALE
            =========================================================== --}}
            <template x-if="portee === 'prefectorale'">

                <div class="rounded-lg border border-gray-200 p-4">

                    <div class="mb-4">

                        <p class="text-sm font-semibold text-gray-800">
                            Préfectures concernées
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Sélectionnez une ou plusieurs préfectures.
                            La région de chaque préfecture est automatiquement
                            déterminée par son rattachement administratif.
                        </p>

                    </div>


                    @foreach($regions as $region)

                        <div class="mb-5 last:mb-0">

                            <div class="mb-2 border-b border-gray-100 pb-2">

                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    {{ $region->nom }}
                                </p>

                            </div>


                            <div class="grid grid-cols-1 gap-2 md:grid-cols-2">

                                @foreach($region->prefectures as $prefecture)

                                    <label
                                        class="flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 p-3 transition hover:bg-gray-50"
                                    >

                                        <input
                                            type="checkbox"
                                            name="prefecture_ids[]"
                                            value="{{ $prefecture->idPrefecture }}"
                                            x-model="prefectureIds"
                                            class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                        >

                                        <div>

                                            <p class="text-sm font-medium text-gray-700">
                                                {{ $prefecture->nom }}
                                            </p>

                                            <p class="mt-0.5 text-xs text-gray-400">
                                                {{ $region->nom }}
                                            </p>

                                        </div>

                                    </label>

                                @endforeach

                            </div>

                        </div>

                    @endforeach

                </div>

            </template>


            {{-- Aucun choix --}}
            <template x-if="portee === ''">

                <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 p-4">

                    <p class="text-sm text-gray-500">
                        Sélectionnez d'abord la portée de la campagne
                        pour définir son territoire.
                    </p>

                </div>

            </template>


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

            @error('zones')
                <p class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

        </div>

    </div>

    {{-- DESCRIPTION --}}
    <div class="md:col-span-2">
        <label for="description" class="block text-sm font-medium text-gray-700">
            Description
        </label>

        <textarea
            id="description"
            name="description"
            rows="3"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
        >{{ old('description', $campagne->description ?? '') }}</textarea>

        @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- OBJECTIFS --}}
    <div class="md:col-span-2">
        <label for="objectifs" class="block text-sm font-medium text-gray-700">
            Objectifs <span class="text-red-500">*</span>
        </label>

        <textarea
            id="objectifs"
            name="objectifs"
            rows="4"
            required
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
        >{{ old('objectifs', $campagne->objectifs ?? '') }}</textarea>

        @error('objectifs')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- RESULTATS ATTENDUS --}}
    <div class="md:col-span-2">
        <label for="resultatsAttendus" class="block text-sm font-medium text-gray-700">
            Résultats attendus <span class="text-red-500">*</span>
        </label>

        <textarea
            id="resultatsAttendus"
            name="resultatsAttendus"
            rows="4"
            required
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
        >{{ old('resultatsAttendus', $campagne->resultatsAttendus ?? '') }}</textarea>

        @error('resultatsAttendus')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
    {{-- RESULTATS ATTENDUS --}}
    <div class="md:col-span-2">
        <label for="resultatsAttendus" class="block text-sm font-medium text-gray-700">
            Villages concernées <span class="text-red-500">*</span>
        </label>

        <textarea
            id="zoneConcerner"
            name="zoneConcerner"
            rows="4"
            required
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
        >{{ old('zoneConcerner', $campagne->zoneConcerner ?? '') }}</textarea>

        @error('zoneConcerner')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- METHODOLOGIE --}}
    <div class="md:col-span-2">
        <label for="methodologie" class="block text-sm font-medium text-gray-700">
            Méthodologie
        </label>

        <textarea
            id="methodologie"
            name="methodologie"
            rows="4"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
        >{{ old('methodologie', $campagne->methodologie ?? '') }}</textarea>

        @error('methodologie')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- INSTRUCTIONS --}}
    <div class="md:col-span-2">
        <label for="instructions" class="block text-sm font-medium text-gray-700">
            Instructions aux agents recenseurs
        </label>

        <textarea
            id="instructions"
            name="instructions"
            rows="4"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
        >{{ old('instructions', $campagne->instructions ?? '') }}</textarea>

        @error('instructions')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- QUESTIONNAIRES --}}
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Questionnaires de collecte
        </label>

        @php
            $selectionnes = old(
                'questionnaire_ids',
                isset($campagne)
                    ? $campagne->questionnaires->pluck('idQuestionnaire')->toArray()
                    : []
            );
        @endphp

        <div class="space-y-2 rounded-lg border border-gray-200 p-4">

            @forelse($questionnaires as $questionnaire)

                <label class="flex items-center gap-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="questionnaire_ids[]"
                        value="{{ $questionnaire->idQuestionnaire }}"
                        @checked(in_array($questionnaire->idQuestionnaire, $selectionnes))
                        class="rounded border-gray-300 text-green-600 focus:ring-green-500"
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
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

</div>

{{-- BOUTONS --}}
<div class="mt-8 flex justify-end gap-3">

    <a
        href="{{ route('campagnes.index') }}"
        class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-100"
    >
        Annuler
    </a>

    <button
        type="submit"
        class="rounded-lg bg-green-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
    >
        {{ isset($campagne) ? 'Mettre à jour' : 'Enregistrer' }}
    </button>

</div>