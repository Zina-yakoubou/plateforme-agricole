@csrf

<div
    x-data="{
        portee: @js(
            old(
                'portee',
                $planification->portee ?? 'prefectorale'
            )
        )
    }"
>

    {{-- ==========================================================
        TERRITOIRE CONCERNÉ
    =========================================================== --}}

    <div class="md:col-span-2">

        <div class="mb-4">

            <h3 class="text-sm font-semibold text-gray-800">
                Territoire concerné
            </h3>

            <p class="mt-1 text-xs text-gray-500">
                Sélectionnez les communes, cantons ou villages
                concernés par la planification préfectorale.
            </p>

        </div>


        {{-- ======================================================
            PRÉFECTURE
        ======================================================= --}}

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
                        Préfecture de {{ $deploiement->prefecture->nom }}
                    </p>

                    <p class="mt-1 text-sm text-green-700">
                        La planification concerne uniquement le territoire
                        de cette préfecture.
                    </p>

                </div>

            </div>

        </div>


        {{-- ======================================================
            COMMUNES
        ======================================================= --}}

        <div class="mt-5 space-y-4">

            @forelse($communes as $commune)

                <div
                    class="overflow-hidden rounded-lg border border-gray-200"
                    x-data="{ ouvert: true }"
                >

                    {{-- COMMUNE --}}
                    <div class="flex items-center justify-between bg-gray-50 p-4">

                        <label class="flex cursor-pointer items-center gap-3">

                            <input
                                type="checkbox"
                                name="commune_ids[]"
                                value="{{ $commune->idCommune }}"
                                class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                            >

                            <div>

                                <p class="text-sm font-semibold text-gray-800">
                                    {{ $commune->nom }}
                                </p>

                                <p class="mt-0.5 text-xs text-gray-500">
                                    {{ $commune->cantons->count() }}
                                    canton(s)
                                </p>

                            </div>

                        </label>


                        <button
                            type="button"
                            @click="ouvert = !ouvert"
                            class="text-gray-500 hover:text-gray-700"
                        >

                            <svg
                                class="h-5 w-5 transition-transform"
                                :class="{ 'rotate-180': ouvert }"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 9l-7 7-7-7"
                                />
                            </svg>

                        </button>

                    </div>


                    {{-- CANTONS --}}
                    <div
                        x-show="ouvert"
                        class="space-y-3 border-t border-gray-100 p-4"
                    >

                        @forelse($commune->cantons as $canton)

                            <div
                                class="rounded-lg border border-gray-200"
                                x-data="{ ouvertCanton: false }"
                            >

                                {{-- CANTON --}}
                                <div class="flex items-center justify-between p-3">

                                    <label class="flex cursor-pointer items-center gap-3">

                                        <input
                                            type="checkbox"
                                            name="canton_ids[]"
                                            value="{{ $canton->idCanton }}"
                                            class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                        >

                                        <div>

                                            <p class="text-sm font-medium text-gray-700">
                                                {{ $canton->nom }}
                                            </p>

                                            <p class="mt-0.5 text-xs text-gray-400">
                                                {{ $canton->villages->count() }}
                                                village(s)
                                            </p>

                                        </div>

                                    </label>


                                    <button
                                        type="button"
                                        @click="ouvertCanton = !ouvertCanton"
                                        class="text-xs font-medium text-gray-500 hover:text-gray-700"
                                    >
                                        Voir les villages
                                    </button>

                                </div>


                                {{-- VILLAGES --}}
                                <div
                                    x-show="ouvertCanton"
                                    class="border-t border-gray-100 bg-gray-50 px-4 py-3"
                                >

                                    <div class="grid grid-cols-1 gap-2 md:grid-cols-2">

                                        @forelse($canton->villages as $village)

                                            <label
                                                class="flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 bg-white p-3 hover:bg-gray-50"
                                            >

                                                <input
                                                    type="checkbox"
                                                    name="village_ids[]"
                                                    value="{{ $village->idVillage }}"
                                                    class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                                >

                                                <span class="text-sm text-gray-700">
                                                    {{ $village->nom }}
                                                </span>

                                            </label>

                                        @empty

                                            <p class="text-xs text-gray-500">
                                                Aucun village enregistré.
                                            </p>

                                        @endforelse

                                    </div>

                                </div>

                            </div>

                        @empty

                            <p class="text-sm text-gray-500">
                                Aucun canton enregistré pour cette commune.
                            </p>

                        @endforelse

                    </div>

                </div>

            @empty

                <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 p-4">

                    <p class="text-sm text-gray-500">
                        Aucune commune enregistrée pour cette préfecture.
                    </p>

                </div>

            @endforelse

        </div>

    </div>


    {{-- ==========================================================
        PLAN DE TRAVAIL
    =========================================================== --}}

    <div class="mt-6">

        <label
            for="planTravail"
            class="block text-sm font-medium text-gray-700"
        >
            Plan de travail
        </label>

        <textarea
            id="planTravail"
            name="planTravail"
            rows="5"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
        >{{ old('planTravail', $planification->planTravail ?? '') }}</textarea>

        @error('planTravail')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>


    {{-- ==========================================================
        OBSERVATIONS
    =========================================================== --}}

    <div class="mt-6">

        <label
            for="observations"
            class="block text-sm font-medium text-gray-700"
        >
            Observations
        </label>

        <textarea
            id="observations"
            name="observations"
            rows="4"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
        >{{ old('observations', $planification->observations ?? '') }}</textarea>

        @error('observations')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>


    {{-- ==========================================================
        BOUTONS
    =========================================================== --}}

    <div class="mt-8 flex justify-end gap-3">

        <a
            href="{{ route('dpa.campagnes.index') }}"
            class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-100"
        >
            Annuler
        </a>

        <button
            type="submit"
            class="rounded-lg bg-green-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
        >
            Enregistrer la planification
        </button>

    </div>

</div>
</div>