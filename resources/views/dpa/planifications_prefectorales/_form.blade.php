@csrf

@php

    /*
    |--------------------------------------------------------------------------
    | PLANIFICATION EXISTANTE
    |--------------------------------------------------------------------------
    */

    $planificationActuelle =
        $planificationPrefectorale
        ?? $planification
        ?? null;


    /*
    |--------------------------------------------------------------------------
    | BESOINS EXISTANTS
    |--------------------------------------------------------------------------
    */

    $besoinsExistants = $planificationActuelle
        ? $planificationActuelle->besoins->map(function ($besoin) {

            return [
                'categorie'    => $besoin->categorie,
                'designation'  => $besoin->designation,
                'quantite'     => $besoin->quantite,
                'unite'        => $besoin->unite ?? '',
                'observations' => $besoin->observations ?? '',
            ];

        })->values()->toArray()
        : [];

@endphp


<div
    x-data="{
        besoins: @js(old('besoins', $besoinsExistants)),


        /*
        |--------------------------------------------------------------------------
        | AJOUTER UN BESOIN
        |--------------------------------------------------------------------------
        */

        ajouterBesoin() {

            this.besoins.push({
                categorie: '',
                designation: '',
                quantite: '',
                unite: '',
                observations: ''
            });

        },


        /*
        |--------------------------------------------------------------------------
        | SUPPRIMER UN BESOIN
        |--------------------------------------------------------------------------
        */

        supprimerBesoin(index) {

            this.besoins.splice(index, 1);

        }

    }"
>


    {{-- ==========================================================
        INFORMATIONS DE LA PLANIFICATION
    =========================================================== --}}

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

                <p class="mt-1 text-xs leading-5 text-green-700">
                    Cette planification concerne l'organisation de la campagne
                    au niveau préfectoral.
                </p>

            </div>

        </div>

    </div>


    {{-- ==========================================================
        BESOINS
    =========================================================== --}}

    <div class="mt-8">

        <div class="mb-4 flex items-start justify-between gap-4">

            <div>

                <h3 class="text-sm font-semibold text-gray-800">
                    Besoins pour la campagne
                </h3>

                <p class="mt-1 text-xs text-gray-500">
                    Indiquez les ressources nécessaires à la réalisation
                    des activités dans la préfecture.
                </p>

            </div>


            {{-- AJOUTER --}}

            <button
                type="button"
                @click="ajouterBesoin()"
                class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-green-700"
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
                        d="M12 4v16m8-8H4"
                    />

                </svg>

                Ajouter un besoin

            </button>

        </div>


        {{-- ======================================================
            AUCUN BESOIN
        ======================================================= --}}

        <template x-if="besoins.length === 0">

            <div
                class="rounded-lg border border-dashed border-gray-300 bg-gray-50 p-6 text-center"
            >

                <p class="text-sm text-gray-500">
                    Aucun besoin ajouté.
                </p>

                <p class="mt-1 text-xs text-gray-400">
                    Ajoutez les ressources nécessaires à la campagne.
                </p>

            </div>

        </template>


        {{-- ======================================================
            LISTE DES BESOINS
        ======================================================= --}}

        <div class="space-y-4">

            <template
                x-for="(besoin, index) in besoins"
                :key="index"
            >

                <div
                    class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm"
                >

                    {{-- EN-TÊTE --}}

                    <div class="mb-4 flex items-center justify-between">

                        <p class="text-sm font-semibold text-gray-800">

                            Besoin
                            <span x-text="index + 1"></span>

                        </p>


                        <button
                            type="button"
                            @click="supprimerBesoin(index)"
                            class="text-sm font-medium text-red-600 hover:text-red-700"
                        >
                            Supprimer
                        </button>

                    </div>


                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">


                        {{-- ==================================================
                            CATÉGORIE
                        =================================================== --}}

                        <div>

                            <label
                                class="block text-sm font-medium text-gray-700"
                            >

                                Catégorie

                                <span class="text-red-500">
                                    *
                                </span>

                            </label>


                            <select
                                :name="`besoins[${index}][categorie]`"
                                x-model="besoin.categorie"
                                required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                            >

                                <option value="">
                                    Sélectionner
                                </option>

                                <option value="Matériel">
                                    Matériel
                                </option>

                                <option value="Transport">
                                    Transport
                                </option>

                                <option value="Personnel">
                                    Personnel
                                </option>

                                <option value="Communication">
                                    Communication
                                </option>

                                <option value="Logistique">
                                    Logistique
                                </option>

                                <option value="Autre">
                                    Autre
                                </option>

                            </select>

                        </div>


                        {{-- ==================================================
                            DÉSIGNATION
                        =================================================== --}}

                        <div>

                            <label
                                class="block text-sm font-medium text-gray-700"
                            >

                                Besoin

                                <span class="text-red-500">
                                    *
                                </span>

                            </label>


                            <input
                                type="text"
                                :name="`besoins[${index}][designation]`"
                                x-model="besoin.designation"
                                required
                                placeholder="Ex. Tablettes, motos, carburant..."
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                            >

                        </div>


                        {{-- ==================================================
                            QUANTITÉ
                        =================================================== --}}

                        <div>

                            <label
                                class="block text-sm font-medium text-gray-700"
                            >

                                Quantité

                                <span class="text-red-500">
                                    *
                                </span>

                            </label>


                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                :name="`besoins[${index}][quantite]`"
                                x-model="besoin.quantite"
                                required
                                placeholder="Ex. 20"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                            >

                        </div>


        


                        {{-- ==================================================
                            OBSERVATIONS DU BESOIN
                        =================================================== --}}

                        <div class="md:col-span-2">

                            <label
                                class="block text-sm font-medium text-gray-700"
                            >
                                Observations
                            </label>


                            <input
                                type="text"
                                :name="`besoins[${index}][observations]`"
                                x-model="besoin.observations"
                                placeholder="Précision ou justification"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                            >

                        </div>

                    </div>

                </div>

            </template>

        </div>


        {{-- ======================================================
            ERREURS BESOINS
        ======================================================= --}}

        @error('besoins')

            <p class="mt-2 text-sm text-red-600">
                {{ $message }}
            </p>

        @enderror


        @error('besoins.*.categorie')

            <p class="mt-2 text-sm text-red-600">
                {{ $message }}
            </p>

        @enderror


        @error('besoins.*.designation')

            <p class="mt-2 text-sm text-red-600">
                {{ $message }}
            </p>

        @enderror


        @error('besoins.*.quantite')

            <p class="mt-2 text-sm text-red-600">
                {{ $message }}
            </p>

        @enderror

    </div>


    {{-- ==========================================================
        PLAN DE TRAVAIL
    =========================================================== --}}

    <div class="mt-8">

        <label
            for="planTravail"
            class="block text-sm font-medium text-gray-700"
        >
            Plan de travail
        </label>


        <p class="mt-1 text-xs text-gray-500">
            Décrivez les principales dispositions prévues pour la mise
            en œuvre de la campagne dans la préfecture.
        </p>


        <textarea
            id="planTravail"
            name="planTravail"
            rows="5"
            class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
        >{{ old(
            'planTravail',
            $planificationActuelle?->planTravail ?? ''
        ) }}</textarea>


        @error('planTravail')

            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>

        @enderror

    </div>


    {{-- ==========================================================
        OBSERVATIONS GÉNÉRALES
    =========================================================== --}}

    <div class="mt-6">

        <label
            for="observations"
            class="block text-sm font-medium text-gray-700"
        >
            Observations générales
        </label>


        <textarea
            id="observations"
            name="observations"
            rows="4"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
        >{{ old(
            'observations',
            $planificationActuelle?->observations ?? ''
        ) }}</textarea>


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
            href="{{ route('dpa.planifications-prefectorales.index') }}"
            class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-100"
        >
            Annuler
        </a>


        <button
            type="submit"
            class="rounded-lg bg-green-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
        >

            {{ $planificationActuelle
                ? 'Mettre à jour la planification'
                : 'Enregistrer la planification'
            }}

        </button>

    </div>

</div>