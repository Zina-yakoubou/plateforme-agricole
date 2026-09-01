@csrf

<div class="space-y-7">

    {{-- ========================================================= --}}
    {{-- ÉQUIPE --}}
    {{-- ========================================================= --}}

    <div>
        <label class="mb-2 block text-sm font-semibold text-gray-700">
            Équipe
        </label>

        <div class="flex items-center gap-4 rounded-2xl border border-gray-200 bg-gray-50 px-4 py-4">

            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#006a4f]/10 text-[#006a4f]">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-6 w-6"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.8"
                          d="M17 20h5v-2a4 4 0 0 0-4-4h-1
                             M9 20H4v-2a4 4 0 0 1 4-4h1
                             M12 12a4 4 0 1 0 0-8
                             4 4 0 0 0 0 8Z"/>
                </svg>
            </div>

            <div class="min-w-0">
                <p class="truncate text-sm font-bold text-gray-800">
                    {{ $equipe->nom }}
                </p>

                @if($equipe->reference)
                    <p class="mt-0.5 text-xs text-gray-500">
                        Référence : {{ $equipe->reference }}
                    </p>
                @endif
            </div>

            <div class="ml-auto">
                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">
                    Équipe
                </span>
            </div>
        </div>

        <input type="hidden"
               name="equipe_id"
               value="{{ $equipe->idEquipe }}">
    </div>


    {{-- ========================================================= --}}
    {{-- CAMPAGNE --}}
    {{-- ========================================================= --}}

    <div>

        <label for="campagne_search"
               class="mb-2 block text-sm font-semibold text-gray-700">
            Campagne de recensement
            <span class="text-red-500">*</span>
        </label>

        <div class="relative">

            <input
                type="text"
                id="campagne_search"
                autocomplete="off"
                placeholder="Sélectionner une campagne..."
                class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 pr-11 text-sm
                       shadow-sm transition
                       focus:border-[#006a4f] focus:ring-[#006a4f]"
            >

            <button
                type="button"
                id="campagne_toggle"
                class="absolute inset-y-0 right-0 flex w-11 items-center justify-center text-gray-400 hover:text-gray-600"
                tabindex="-1"
            >
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="m6 9 6 6 6-6"/>
                </svg>
            </button>

            <div
                id="campagne_results"
                class="absolute left-0 right-0 z-50 mt-2 hidden max-h-72 overflow-y-auto
                       rounded-xl border border-gray-200 bg-white shadow-xl"
            ></div>

        </div>

        <input
            type="hidden"
            name="campagne_id"
            id="campagne_id"
            value="{{ old('campagne_id') }}"
        >

        <div
            id="campagne_selected"
            class="mt-2 hidden items-center gap-2 rounded-xl border border-[#006a4f]/20
                   bg-[#006a4f]/5 px-3 py-2.5 text-sm text-[#006a4f]"
        ></div>

        @error('campagne_id')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>


    {{-- ========================================================= --}}
    {{-- CANTON --}}
    {{-- ========================================================= --}}

    <div>

        <label for="canton_search"
               class="mb-2 block text-sm font-semibold text-gray-700">
            Canton
            <span class="text-red-500">*</span>
        </label>

        <div class="relative">

            <input
                type="text"
                id="canton_search"
                autocomplete="off"
                disabled
                placeholder="Sélectionnez d'abord une campagne..."
                class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 pr-11 text-sm
                       shadow-sm transition
                       focus:border-[#006a4f] focus:ring-[#006a4f]
                       disabled:cursor-not-allowed disabled:bg-gray-100"
            >

            <button
                type="button"
                id="canton_toggle"
                disabled
                class="absolute inset-y-0 right-0 flex w-11 items-center justify-center
                       text-gray-400 hover:text-gray-600 disabled:cursor-not-allowed"
                tabindex="-1"
            >
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="m6 9 6 6 6-6"/>
                </svg>
            </button>

            <div
                id="canton_results"
                class="absolute left-0 right-0 z-40 mt-2 hidden max-h-72 overflow-y-auto
                       rounded-xl border border-gray-200 bg-white shadow-xl"
            ></div>

        </div>

        <input
            type="hidden"
            name="canton_id"
            id="canton_id"
            value="{{ old('canton_id') }}"
        >

        <div
            id="canton_selected"
            class="mt-2 hidden rounded-xl border border-gray-200 bg-gray-50
                   px-3 py-2.5 text-sm font-medium text-gray-700"
        ></div>

        @error('canton_id')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>


    {{-- ========================================================= --}}
    {{-- VILLAGES --}}
    {{-- ========================================================= --}}

    <div>

        <div class="mb-2 flex items-center justify-between">

            <label for="village_search"
                   class="block text-sm font-semibold text-gray-700">
                Villages concernés
                <span class="text-red-500">*</span>
            </label>

            <span id="village_loading"
                  class="hidden items-center gap-2 text-xs text-gray-500">

                <svg class="h-4 w-4 animate-spin"
                     xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24">
                    <circle class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"/>
                    <path class="opacity-75"
                          fill="currentColor"
                          d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4Z"/>
                </svg>

                Chargement...
            </span>

        </div>


        <div class="relative">

            <input
                type="text"
                id="village_search"
                autocomplete="off"
                disabled
                placeholder="Sélectionnez d'abord un canton..."
                class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 pr-11 text-sm
                       shadow-sm transition
                       focus:border-[#006a4f] focus:ring-[#006a4f]
                       disabled:cursor-not-allowed disabled:bg-gray-100"
            >

            <button
                type="button"
                id="village_toggle"
                disabled
                class="absolute inset-y-0 right-0 flex w-11 items-center justify-center
                       text-gray-400 hover:text-gray-600 disabled:cursor-not-allowed"
                tabindex="-1"
            >
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="m6 9 6 6 6-6"/>
                </svg>
            </button>


            <div
                id="village_results"
                class="absolute left-0 right-0 z-30 mt-2 hidden max-h-72 overflow-y-auto
                       rounded-xl border border-gray-200 bg-white shadow-xl"
            ></div>

        </div>


        <p id="village_help"
           class="mt-2 text-xs text-gray-500">
            Sélectionnez d'abord une campagne puis un canton.
        </p>


        @error('village_ids')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

        @error('village_ids.*')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>


    {{-- ========================================================= --}}
    {{-- VILLAGES SÉLECTIONNÉS --}}
    {{-- ========================================================= --}}

    <div
        id="selected_villages_container"
        class="hidden rounded-2xl border border-[#006a4f]/20 bg-[#006a4f]/5 p-4"
    >

        <div class="mb-3 flex items-center justify-between">

            <div>
                <h3 class="text-sm font-bold text-gray-800">
                    Villages sélectionnés
                </h3>

                <p id="selected_count"
                   class="mt-1 text-xs text-gray-500">
                    0 village
                </p>
            </div>

            <span
                class="flex h-8 w-8 items-center justify-center rounded-lg
                       bg-white text-[#006a4f] shadow-sm"
            >
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-4 w-4"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M5 13l4 4L19 7"/>
                </svg>
            </span>

        </div>

        <div id="selected_villages"
             class="flex flex-wrap gap-2">
        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- STATUT --}}
    {{-- ========================================================= --}}

    <input type="hidden"
           name="statut"
           value="active">


    {{-- ========================================================= --}}
    {{-- OBSERVATIONS --}}
    {{-- ========================================================= --}}

    <div>

        <label for="observations"
               class="mb-2 block text-sm font-semibold text-gray-700">
            Observations

            <span class="text-xs font-normal text-gray-400">
                (facultatif)
            </span>
        </label>

        <textarea
            name="observations"
            id="observations"
            rows="4"
            maxlength="2000"
            class="w-full resize-none rounded-xl border-gray-300 px-4 py-3 text-sm
                   shadow-sm transition
                   focus:border-[#006a4f] focus:ring-[#006a4f]"
            placeholder="Ajoutez une observation éventuelle..."
        >{{ old('observations') }}</textarea>

        <div class="mt-1 flex justify-end">
            <span class="text-xs text-gray-400">
                2000 caractères maximum
            </span>
        </div>

        @error('observations')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | DONNÉES ENVOYÉES PAR LE CONTRÔLEUR
    |--------------------------------------------------------------------------
    */

    const campagnes = @json($campagnesJs ?? []);

    const cantons = @json($cantonsJs ?? []);

    const villagesPlanifies = @json($villagesPlanifiesJs ?? []);


    /*
    |--------------------------------------------------------------------------
    | ÉLÉMENTS
    |--------------------------------------------------------------------------
    */

    const campagneSearch =
        document.getElementById('campagne_search');

    const campagneResults =
        document.getElementById('campagne_results');

    const campagneId =
        document.getElementById('campagne_id');

    const campagneSelected =
        document.getElementById('campagne_selected');

    const campagneToggle =
        document.getElementById('campagne_toggle');


    const cantonSearch =
        document.getElementById('canton_search');

    const cantonResults =
        document.getElementById('canton_results');

    const cantonId =
        document.getElementById('canton_id');

    const cantonSelected =
        document.getElementById('canton_selected');

    const cantonToggle =
        document.getElementById('canton_toggle');


    const villageSearch =
        document.getElementById('village_search');

    const villageResults =
        document.getElementById('village_results');

    const villageToggle =
        document.getElementById('village_toggle');

    const villageLoading =
        document.getElementById('village_loading');

    const villageHelp =
        document.getElementById('village_help');


    const selectedContainer =
        document.getElementById('selected_villages_container');

    const selectedVillages =
        document.getElementById('selected_villages');

    const selectedCount =
        document.getElementById('selected_count');


    /*
    |--------------------------------------------------------------------------
    | VARIABLES
    |--------------------------------------------------------------------------
    */

    let villages = [];

    let selectedVillageIds = [];


    /*
    |--------------------------------------------------------------------------
    | CAMPAGNES
    |--------------------------------------------------------------------------
    */

    function afficherCampagnes(recherche = '') {

        campagneResults.innerHTML = '';

        const terme =
            String(recherche)
                .toLowerCase()
                .trim();


        const resultats =
            campagnes.filter(campagne => {

                if (!terme) {
                    return true;
                }

                return (
                    String(campagne.code ?? '')
                        .toLowerCase()
                        .includes(terme)
                    ||
                    String(campagne.libelle ?? '')
                        .toLowerCase()
                        .includes(terme)
                );
            });


        if (resultats.length === 0) {

            campagneResults.innerHTML = `
                <div class="px-4 py-4 text-center text-sm text-gray-500">
                    Aucune campagne trouvée.
                </div>
            `;

            campagneResults.classList.remove('hidden');

            return;
        }


        resultats.forEach(campagne => {

            const button =
                document.createElement('button');

            button.type = 'button';

            button.className =
                'block w-full border-b border-gray-100 px-4 py-3 text-left transition hover:bg-gray-50';


            button.innerHTML = `
                <div class="flex items-center justify-between gap-3">

                    <div class="min-w-0">

                        <div class="truncate text-sm font-semibold text-gray-800">
                            ${escapeHtml(campagne.code)}
                        </div>

                        <div class="mt-0.5 truncate text-xs text-gray-500">
                            ${escapeHtml(campagne.libelle)}
                        </div>

                    </div>

                    <span class="
                        shrink-0 rounded-full px-2 py-1 text-[10px] font-semibold
                        ${
                            campagne.statut === 'active'
                                ? 'bg-green-100 text-green-700'
                                : 'bg-gray-100 text-gray-600'
                        }
                    ">
                        ${escapeHtml(campagne.statut)}
                    </span>

                </div>
            `;


            button.addEventListener('click', function () {

                selectionnerCampagne(campagne);

            });


            campagneResults.appendChild(button);

        });


        campagneResults.classList.remove('hidden');
    }


    function selectionnerCampagne(campagne) {

        campagneId.value =
            campagne.id;


        campagneSearch.value =
            `${campagne.code} — ${campagne.libelle}`;


        campagneSelected.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-4 w-4 shrink-0"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 13l4 4L19 7"/>
            </svg>

            <span>
                ${escapeHtml(campagne.code)}
                —
                ${escapeHtml(campagne.libelle)}
            </span>
        `;


        campagneSelected.classList.remove('hidden');

        campagneSelected.classList.add('flex');

        campagneResults.classList.add('hidden');


        /*
        |--------------------------------------------------------------------------
        | ACTIVER CANTON
        |--------------------------------------------------------------------------
        */

        cantonSearch.disabled = false;

        cantonToggle.disabled = false;

        cantonSearch.placeholder =
            'Sélectionner un canton...';


        /*
        |--------------------------------------------------------------------------
        | RESET CANTON
        |--------------------------------------------------------------------------
        */

        cantonId.value = '';

        cantonSearch.value = '';

        cantonSelected.classList.add('hidden');

        cantonResults.classList.add('hidden');


        /*
        |--------------------------------------------------------------------------
        | RESET VILLAGES
        |--------------------------------------------------------------------------
        */

        villages = [];

        selectedVillageIds = [];

        villageSearch.disabled = true;

        villageToggle.disabled = true;

        villageSearch.value = '';

        villageSearch.placeholder =
            'Sélectionnez d’abord un canton...';

        villageResults.classList.add('hidden');

        villageHelp.textContent =
            'Sélectionnez d’abord un canton.';

        afficherVillagesSelectionnes();
    }


    campagneSearch.addEventListener('focus', function () {

        afficherCampagnes(campagneSearch.value);

    });


    campagneSearch.addEventListener('input', function () {

        /*
        | Si l'utilisateur modifie manuellement la campagne,
        | on invalide l'ancien ID.
        */

        campagneId.value = '';

        campagneSelected.classList.add('hidden');

        cantonId.value = '';

        cantonSearch.value = '';

        cantonSearch.disabled = true;

        cantonToggle.disabled = true;

        villageSearch.disabled = true;

        villageToggle.disabled = true;

        villages = [];

        selectedVillageIds = [];

        afficherVillagesSelectionnes();

        afficherCampagnes(campagneSearch.value);

    });


    campagneToggle.addEventListener('click', function () {

        if (campagneResults.classList.contains('hidden')) {

            afficherCampagnes(campagneSearch.value);

        } else {

            campagneResults.classList.add('hidden');

        }

        campagneSearch.focus();

    });


    /*
    |--------------------------------------------------------------------------
    | CANTONS
    |--------------------------------------------------------------------------
    */

    function afficherCantons(recherche = '') {

        cantonResults.innerHTML = '';

        const terme =
            String(recherche)
                .toLowerCase()
                .trim();


        const resultats =
            cantons.filter(canton => {

                if (!terme) {
                    return true;
                }

                return String(canton.nom ?? '')
                    .toLowerCase()
                    .includes(terme);

            });


        if (resultats.length === 0) {

            cantonResults.innerHTML = `
                <div class="px-4 py-4 text-center text-sm text-gray-500">
                    Aucun canton trouvé.
                </div>
            `;

            cantonResults.classList.remove('hidden');

            return;
        }


        resultats.forEach(canton => {

            const button =
                document.createElement('button');

            button.type = 'button';

            button.className =
                'flex w-full items-center gap-3 border-b border-gray-100 px-4 py-3 text-left text-sm transition hover:bg-gray-50';


            button.innerHTML = `

                <span class="
                    flex h-8 w-8 shrink-0 items-center justify-center
                    rounded-lg bg-gray-100 text-gray-500
                ">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-4 w-4"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.8"
                              d="M12 21s7-5.2 7-11a7 7 0 1 0-14 0c0 5.8 7 11 7 11Z"/>

                        <circle cx="12"
                                cy="10"
                                r="2.5"
                                stroke="currentColor"
                                stroke-width="1.8"/>

                    </svg>

                </span>

                <span class="font-medium text-gray-700">
                    ${escapeHtml(canton.nom)}
                </span>

            `;


            button.addEventListener('click', function () {

                selectionnerCanton(canton);

            });


            cantonResults.appendChild(button);

        });


        cantonResults.classList.remove('hidden');
    }


    function selectionnerCanton(canton) {

        cantonId.value =
            canton.id;


        cantonSearch.value =
            canton.nom;


        cantonSelected.textContent =
            `Canton sélectionné : ${canton.nom}`;


        cantonSelected.classList.remove('hidden');

        cantonResults.classList.add('hidden');


        /*
        |--------------------------------------------------------------------------
        | CHARGEMENT LOCAL DES VILLAGES
        |--------------------------------------------------------------------------
        |
        | IMPORTANT :
        | Aucun fetch().
        |
        | On utilise directement villagesPlanifiesJs envoyé
        | par AffectationController.
        |
        */

        chargerVillages(
            campagneId.value,
            canton.id
        );

    }


    cantonSearch.addEventListener('focus', function () {

        if (!cantonSearch.disabled) {

            afficherCantons(cantonSearch.value);

        }

    });


    cantonSearch.addEventListener('input', function () {

        afficherCantons(cantonSearch.value);

    });


    cantonToggle.addEventListener('click', function () {

        if (cantonResults.classList.contains('hidden')) {

            afficherCantons(cantonSearch.value);

        } else {

            cantonResults.classList.add('hidden');

        }

        cantonSearch.focus();

    });


    /*
    |--------------------------------------------------------------------------
    | VILLAGES
    |--------------------------------------------------------------------------
    */

    function chargerVillages(campagne, canton) {

        villages = [];

        selectedVillageIds = [];


        if (!campagne || !canton) {

            villageSearch.disabled = true;

            villageToggle.disabled = true;

            return;
        }


        villageLoading.classList.remove('hidden');

        villageLoading.classList.add('flex');


        villageSearch.disabled = true;

        villageToggle.disabled = true;

        villageSearch.value = '';

        villageSearch.placeholder =
            'Recherche des villages planifiés...';

        villageResults.classList.add('hidden');


        /*
        |--------------------------------------------------------------------------
        | FILTRE LOCAL
        |--------------------------------------------------------------------------
        */

        villages =
            villagesPlanifies.filter(village => {

                return (
                    String(village.campagne_id) === String(campagne)
                    &&
                    String(village.canton_id) === String(canton)
                );

            });


        villages.sort(function (a, b) {

            return String(a.nom ?? '')
                .localeCompare(
                    String(b.nom ?? ''),
                    'fr'
                );

        });


        villageLoading.classList.add('hidden');

        villageLoading.classList.remove('flex');


        /*
        |--------------------------------------------------------------------------
        | RÉSULTAT
        |--------------------------------------------------------------------------
        */

        if (villages.length === 0) {

            villageSearch.disabled = true;

            villageToggle.disabled = true;

            villageSearch.placeholder =
                'Aucun village planifié pour ce canton';

            villageHelp.textContent =
                'Aucun village de ce canton n’est prévu dans la planification préfectorale pour cette campagne.';

            return;
        }


        villageSearch.disabled = false;

        villageToggle.disabled = false;

        villageSearch.placeholder =
            'Sélectionner un ou plusieurs villages...';

        villageHelp.textContent =
            `${villages.length} village${villages.length > 1 ? 's' : ''} planifié${villages.length > 1 ? 's' : ''} pour ce canton.`;

    }


    /*
    |--------------------------------------------------------------------------
    | AFFICHAGE VILLAGES
    |--------------------------------------------------------------------------
    */

    function afficherVillages(recherche = '') {

        villageResults.innerHTML = '';

        const terme =
            String(recherche)
                .toLowerCase()
                .trim();


        const resultats =
            villages.filter(village => {

                const dejaSelectionne =
                    selectedVillageIds
                        .map(String)
                        .includes(
                            String(village.idVillage)
                        );


                if (dejaSelectionne) {
                    return false;
                }


                if (!terme) {
                    return true;
                }


                return (
                    String(village.nom ?? '')
                        .toLowerCase()
                        .includes(terme)
                    ||
                    String(village.code ?? '')
                        .toLowerCase()
                        .includes(terme)
                );

            });


        if (resultats.length === 0) {

            villageResults.innerHTML = `
                <div class="px-4 py-4 text-center text-sm text-gray-500">
                    ${
                        villages.length === 0
                            ? 'Aucun village planifié.'
                            : 'Aucun village correspondant.'
                    }
                </div>
            `;

            villageResults.classList.remove('hidden');

            return;
        }


        resultats.forEach(village => {

            const button =
                document.createElement('button');

            button.type = 'button';

            button.className =
                'flex w-full items-center justify-between gap-3 border-b border-gray-100 px-4 py-3 text-left transition hover:bg-[#006a4f]/5';


            button.innerHTML = `

                <div class="flex min-w-0 items-center gap-3">

                    <span class="
                        flex h-9 w-9 shrink-0 items-center justify-center
                        rounded-lg bg-[#006a4f]/10 text-[#006a4f]
                    ">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M12 21s7-5.2 7-11a7 7 0 1 0-14 0c0 5.8 7 11 7 11Z"/>

                            <circle cx="12"
                                    cy="10"
                                    r="2.5"
                                    stroke="currentColor"
                                    stroke-width="1.8"/>

                        </svg>

                    </span>


                    <div class="min-w-0">

                        <div class="truncate text-sm font-semibold text-gray-800">
                            ${escapeHtml(village.nom)}
                        </div>

                        ${
                            village.code
                                ? `
                                    <div class="text-xs text-gray-400">
                                        ${escapeHtml(village.code)}
                                    </div>
                                `
                                : ''
                        }

                    </div>

                </div>


                <span class="
                    shrink-0 rounded-lg border border-gray-200
                    px-2 py-1 text-xs font-medium text-gray-500
                ">
                    Ajouter
                </span>

            `;


            button.addEventListener('click', function () {

                const id =
                    String(village.idVillage);


                if (!selectedVillageIds.includes(id)) {

                    selectedVillageIds.push(id);

                }


                villageSearch.value = '';

                villageResults.classList.add('hidden');

                afficherVillagesSelectionnes();

            });


            villageResults.appendChild(button);

        });


        villageResults.classList.remove('hidden');
    }


    villageSearch.addEventListener('focus', function () {

        if (!villageSearch.disabled) {

            afficherVillages(
                villageSearch.value
            );

        }

    });


    villageSearch.addEventListener('input', function () {

        afficherVillages(
            villageSearch.value
        );

    });


    villageToggle.addEventListener('click', function () {

        if (villageResults.classList.contains('hidden')) {

            afficherVillages(
                villageSearch.value
            );

        } else {

            villageResults.classList.add('hidden');

        }

        villageSearch.focus();

    });


    /*
    |--------------------------------------------------------------------------
    | VILLAGES SÉLECTIONNÉS
    |--------------------------------------------------------------------------
    */

    function afficherVillagesSelectionnes() {

        selectedVillages.innerHTML = '';


        document
            .querySelectorAll('.village-hidden-input')
            .forEach(input => input.remove());


        if (selectedVillageIds.length === 0) {

            selectedContainer.classList.add('hidden');

            selectedCount.textContent =
                '0 village';

            return;
        }


        selectedContainer.classList.remove('hidden');


        selectedCount.textContent =
            selectedVillageIds.length +
            (
                selectedVillageIds.length > 1
                    ? ' villages'
                    : ' village'
            );


        selectedVillageIds.forEach(id => {

            const village =
                villages.find(
                    item =>
                        String(item.idVillage) === String(id)
                );


            const badge =
                document.createElement('div');


            badge.className =
                'flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm shadow-sm';


            badge.innerHTML = `

                <span class="
                    h-2 w-2 shrink-0 rounded-full bg-[#006a4f]
                "></span>

                <span class="font-medium text-gray-700">
                    ${escapeHtml(village?.nom ?? 'Village')}
                </span>

                <button
                    type="button"
                    class="ml-1 text-lg leading-none text-gray-400 transition hover:text-red-600"
                    aria-label="Retirer"
                >
                    ×
                </button>

            `;


            badge
                .querySelector('button')
                .addEventListener('click', function () {

                    selectedVillageIds =
                        selectedVillageIds.filter(
                            villageId =>
                                String(villageId) !== String(id)
                        );


                    afficherVillagesSelectionnes();

                });


            selectedVillages.appendChild(badge);


            ajouterInputVillage(id);

        });

    }


    /*
    |--------------------------------------------------------------------------
    | INPUTS CACHÉS
    |--------------------------------------------------------------------------
    */

    function ajouterInputVillage(id) {

        const form =
            villageSearch.closest('form') ||
            document.querySelector('form');


        if (!form) {
            return;
        }


        const input =
            document.createElement('input');


        input.type = 'hidden';

        input.name = 'village_ids[]';

        input.value = id;

        input.className =
            'village-hidden-input';


        form.appendChild(input);

    }


    /*
    |--------------------------------------------------------------------------
    | FERMETURE DES LISTES
    |--------------------------------------------------------------------------
    */

    document.addEventListener('click', function (event) {

        if (
            !campagneSearch.contains(event.target) &&
            !campagneResults.contains(event.target) &&
            !campagneToggle.contains(event.target)
        ) {
            campagneResults.classList.add('hidden');
        }


        if (
            !cantonSearch.contains(event.target) &&
            !cantonResults.contains(event.target) &&
            !cantonToggle.contains(event.target)
        ) {
            cantonResults.classList.add('hidden');
        }


        if (
            !villageSearch.contains(event.target) &&
            !villageResults.contains(event.target) &&
            !villageToggle.contains(event.target)
        ) {
            villageResults.classList.add('hidden');
        }

    });


    /*
    |--------------------------------------------------------------------------
    | ESCAPE HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {

        const div =
            document.createElement('div');

        div.textContent =
            value ?? '';

        return div.innerHTML;

    }


    /*
    |--------------------------------------------------------------------------
    | RESTAURATION APRÈS ERREUR
    |--------------------------------------------------------------------------
    */

    const ancienneCampagne =
        @json(old('campagne_id'));

    const ancienCanton =
        @json(old('canton_id'));

    const anciensVillages =
        @json(old('village_ids', []));


    /*
    |--------------------------------------------------------------------------
    | CAMPAGNE
    |--------------------------------------------------------------------------
    */

    if (ancienneCampagne) {

        const campagne =
            campagnes.find(
                item =>
                    String(item.id) ===
                    String(ancienneCampagne)
            );


        if (campagne) {

            selectionnerCampagne(
                campagne
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | CANTON
    |--------------------------------------------------------------------------
    */

    if (
        ancienCanton &&
        ancienneCampagne
    ) {

        const canton =
            cantons.find(
                item =>
                    String(item.id) ===
                    String(ancienCanton)
            );


        if (canton) {

            selectionnerCanton(
                canton
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | ANCIENS VILLAGES
    |--------------------------------------------------------------------------
    */

    if (Array.isArray(anciensVillages)) {

        selectedVillageIds =
            anciensVillages.map(String);

    }


    /*
    |--------------------------------------------------------------------------
    | AFFICHAGE INITIAL
    |--------------------------------------------------------------------------
    */

    afficherVillagesSelectionnes();

});
</script>