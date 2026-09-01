@csrf

@php

    /*
    |--------------------------------------------------------------------------
    | ÉQUIPE
    |--------------------------------------------------------------------------
    */

    $equipeActuelle = $equipe;

    /*
    |--------------------------------------------------------------------------
    | CAMPAGNE SÉLECTIONNÉE
    |--------------------------------------------------------------------------
    */

    $campagneSelectionnee = old(
        'campagne_id',
        $affectation->campagne_id ?? ''
    );

    /*
    |--------------------------------------------------------------------------
    | DONNÉES CANTONS
    |--------------------------------------------------------------------------
    */

    $cantonsData = $cantons->map(function ($canton) {

        return [
            'id' => $canton->idCanton,
            'nom' => $canton->nom,
        ];

    })->values()->toArray();


    /*
    |--------------------------------------------------------------------------
    | DONNÉES VILLAGES
    |--------------------------------------------------------------------------
    */

    $villagesData = $villages->map(function ($village) {

        return [
            'id' => $village->idVillage,
            'nom' => $village->nom,
            'canton_id' => $village->canton_id,
        ];

    })->values()->toArray();


    /*
    |--------------------------------------------------------------------------
    | ANCIENS VILLAGES
    |--------------------------------------------------------------------------
    */

    $anciensVillages = old(
        'village_ids',
        $affectation->village_ids ?? []
    );

    if (!is_array($anciensVillages)) {
        $anciensVillages = [];
    }

@endphp


<div class="space-y-6">


    {{-- =========================================================
         ÉQUIPE
    ========================================================== --}}

    <div>

        <label class="block text-sm font-semibold text-gray-700 mb-2">
            Équipe
        </label>

        <div
            class="flex items-center gap-3 rounded-xl
                   border border-gray-200 bg-gray-50
                   px-4 py-3"
        >

            <div
                class="flex h-10 w-10 items-center justify-center
                       rounded-lg bg-[#006a4f]/10
                       text-[#006a4f]"
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M17 20h5v-2a4 4 0 0 0-4-4h-1
                           M9 20H4v-2a4 4 0 0 1 4-4h1
                           M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"
                    />
                </svg>

            </div>

            <div>

                <p class="text-sm font-semibold text-gray-800">
                    {{ $equipeActuelle->nom }}
                </p>

                @if($equipeActuelle->reference)

                    <p class="text-xs text-gray-500">
                        {{ $equipeActuelle->reference }}
                    </p>

                @endif

            </div>

        </div>

        <input
            type="hidden"
            name="equipe_id"
            value="{{ $equipeActuelle->idEquipe }}"
        >

    </div>


    {{-- =========================================================
         CAMPAGNE
    ========================================================== --}}

    <div>

        <label
            class="block text-sm font-semibold text-gray-700 mb-2"
        >
            Campagne de recensement
            <span class="text-red-500">*</span>
        </label>

        <div class="relative">

            <input
                type="text"
                id="campagne_search"
                autocomplete="off"
                placeholder="Rechercher une campagne..."
                class="w-full rounded-xl border-gray-300
                       px-4 py-3 pr-10 text-sm
                       focus:border-[#006a4f]
                       focus:ring-[#006a4f]"
            >

            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="absolute right-3 top-3.5 h-5 w-5 text-gray-400"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="m21 21-4.35-4.35
                       m2.35-5.65a8 8 0 1 1-16 0
                       8 8 0 0 1 16 0Z"
                />
            </svg>

            <div
                id="campagne_results"
                class="absolute z-30 mt-1 hidden
                       max-h-60 w-full overflow-y-auto
                       rounded-xl border border-gray-200
                       bg-white shadow-lg"
            ></div>

        </div>

        <input
            type="hidden"
            name="campagne_id"
            id="campagne_id"
            value="{{ $campagneSelectionnee }}"
        >

        <div
            id="campagne_selected"
            class="mt-2 hidden rounded-lg
                   bg-[#006a4f]/10 px-3 py-2
                   text-sm text-[#006a4f]"
        ></div>

        @error('campagne_id')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>


    {{-- =========================================================
         CANTON
    ========================================================== --}}

    <div>

        <label
            class="block text-sm font-semibold text-gray-700 mb-2"
        >
            Canton
            <span class="text-red-500">*</span>
        </label>

        <div class="relative">

            <input
                type="text"
                id="canton_search"
                autocomplete="off"
                placeholder="Rechercher un canton..."
                disabled
                class="w-full rounded-xl border-gray-300
                       px-4 py-3 pr-10 text-sm
                       focus:border-[#006a4f]
                       focus:ring-[#006a4f]
                       disabled:bg-gray-100"
            >

            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="absolute right-3 top-3.5 h-5 w-5 text-gray-400"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="m21 21-4.35-4.35
                       m2.35-5.65a8 8 0 1 1-16 0
                       8 8 0 0 1 16 0Z"
                />
            </svg>

            <div
                id="canton_results"
                class="absolute z-20 mt-1 hidden
                       max-h-60 w-full overflow-y-auto
                       rounded-xl border border-gray-200
                       bg-white shadow-lg"
            ></div>

        </div>

        <input
            type="hidden"
            id="canton_id"
            name="canton_id"
        >

        <div
            id="canton_selected"
            class="mt-2 hidden rounded-lg
                   bg-gray-100 px-3 py-2
                   text-sm font-medium text-gray-700"
        ></div>

    </div>


    {{-- =========================================================
         VILLAGES
    ========================================================== --}}

    <div>

        <label
            class="block text-sm font-semibold text-gray-700 mb-2"
        >
            Villages
            <span class="text-red-500">*</span>
        </label>

        <div class="relative">

            <input
                type="text"
                id="village_search"
                autocomplete="off"
                placeholder="Rechercher un village..."
                disabled
                class="w-full rounded-xl border-gray-300
                       px-4 py-3 pr-10 text-sm
                       focus:border-[#006a4f]
                       focus:ring-[#006a4f]
                       disabled:bg-gray-100"
            >

            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="absolute right-3 top-3.5 h-5 w-5 text-gray-400"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="m21 21-4.35-4.35
                       m2.35-5.65a8 8 0 1 1-16 0
                       8 8 0 0 1 16 0Z"
                />
            </svg>

            <div
                id="village_results"
                class="absolute z-20 mt-1 hidden
                       max-h-60 w-full overflow-y-auto
                       rounded-xl border border-gray-200
                       bg-white shadow-lg"
            ></div>

        </div>

        @error('village_ids')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>


    {{-- =========================================================
         VILLAGES SÉLECTIONNÉS
    ========================================================== --}}

    <div
        id="selected_villages_container"
        class="hidden rounded-2xl border border-gray-200
               bg-gray-50 p-4"
    >

        <div class="mb-3 flex items-center justify-between">

            <div>

                <h3 class="text-sm font-bold text-gray-800">
                    Villages sélectionnés
                </h3>

                <p
                    id="selected_count"
                    class="text-xs text-gray-500"
                >
                    0 village
                </p>

            </div>

        </div>

        <div
            id="selected_villages"
            class="flex flex-wrap gap-2"
        ></div>

    </div>


    {{-- =========================================================
         OBSERVATIONS
    ========================================================== --}}

    <div>

        <label
            for="observations"
            class="block text-sm font-semibold text-gray-700 mb-2"
        >
            Observations
        </label>

        <textarea
            name="observations"
            id="observations"
            rows="4"
            class="w-full rounded-xl border-gray-300
                   px-4 py-3 text-sm
                   focus:border-[#006a4f]
                   focus:ring-[#006a4f]"
            placeholder="Observations éventuelles..."
        >{{ old('observations', $affectation->observations ?? '') }}</textarea>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | DONNÉES
    |--------------------------------------------------------------------------
    */

    const cantons =
        @json($cantonsData);

    const villages =
        @json($villagesData);

    const villagesDejaAffectes =
        @json($villagesDejaAffectes);


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


    const cantonSearch =
        document.getElementById('canton_search');

    const cantonResults =
        document.getElementById('canton_results');

    const cantonId =
        document.getElementById('canton_id');

    const cantonSelected =
        document.getElementById('canton_selected');


    const villageSearch =
        document.getElementById('village_search');

    const villageResults =
        document.getElementById('village_results');


    const selectedContainer =
        document.getElementById(
            'selected_villages_container'
        );

    const selectedVillages =
        document.getElementById(
            'selected_villages'
        );

    const selectedCount =
        document.getElementById(
            'selected_count'
        );


    /*
    |--------------------------------------------------------------------------
    | VILLAGES SÉLECTIONNÉS
    |--------------------------------------------------------------------------
    */

    let selectedVillageIds = [];


    /*
    |--------------------------------------------------------------------------
    | CAMPAGNES
    |--------------------------------------------------------------------------
    */

    const campagnes = [];

        @foreach($campagnes as $campagne)
            campagnes.push({
                id: @js($campagne->idCampagne),
                code: @js($campagne->codeCampagne),
                libelle: @js($campagne->libelle),
                statut: @js($campagne->statut)
            });
        @endforeach
    /*
    |--------------------------------------------------------------------------
    | RECHERCHE CAMPAGNE
    |--------------------------------------------------------------------------
    */

    campagneSearch.addEventListener(
        'input',
        function () {

            const search =
                this.value
                    .toLowerCase()
                    .trim();

            campagneResults.innerHTML = '';

            if (!search) {

                campagneResults.classList.add('hidden');

                return;
            }


            const resultats =
                campagnes.filter(function (campagne) {

                    return (
                        campagne.code
                            .toLowerCase()
                            .includes(search)
                        ||
                        campagne.libelle
                            .toLowerCase()
                            .includes(search)
                    );

                });


            resultats.forEach(function (campagne) {

                const button =
                    document.createElement('button');

                button.type = 'button';

                button.className =
                    'block w-full px-4 py-3 '
                    + 'text-left hover:bg-gray-50';

                button.innerHTML = `
                    <div class="text-sm font-semibold text-gray-800">
                        ${escapeHtml(campagne.code)}
                    </div>

                    <div class="text-xs text-gray-500">
                        ${escapeHtml(campagne.libelle)}
                        — ${escapeHtml(campagne.statut)}
                    </div>
                `;

                button.addEventListener(
                    'click',
                    function () {

                        campagneId.value =
                            campagne.id;

                        campagneSearch.value =
                            campagne.code
                            + ' — '
                            + campagne.libelle;

                        campagneSelected.textContent =
                            campagne.code
                            + ' — '
                            + campagne.libelle;

                        campagneSelected.classList.remove(
                            'hidden'
                        );

                        campagneResults.classList.add(
                            'hidden'
                        );

                        cantonSearch.disabled =
                            false;

                        /*
                        | Réinitialiser le canton
                        */

                        cantonId.value = '';

                        cantonSearch.value = '';

                        cantonSelected.classList.add(
                            'hidden'
                        );

                        villageSearch.disabled =
                            true;

                        villageSearch.value = '';

                        villageResults.innerHTML = '';

                        villageResults.classList.add(
                            'hidden'
                        );

                        selectedVillageIds = [];

                        afficherVillagesSelectionnes();

                    }
                );

                campagneResults.appendChild(
                    button
                );

            });


            campagneResults.classList.toggle(
                'hidden',
                resultats.length === 0
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | RECHERCHE CANTON
    |--------------------------------------------------------------------------
    */

    cantonSearch.addEventListener(
        'input',
        function () {

            const search =
                this.value
                    .toLowerCase()
                    .trim();

            cantonResults.innerHTML = '';

            if (!search) {

                cantonResults.classList.add('hidden');

                return;

            }


            const resultats =
                cantons.filter(function (canton) {

                    return canton.nom
                        .toLowerCase()
                        .includes(search);

                });


            resultats.forEach(function (canton) {

                const button =
                    document.createElement('button');

                button.type = 'button';

                button.className =
                    'block w-full px-4 py-3 '
                    + 'text-left text-sm '
                    + 'hover:bg-gray-50';

                button.textContent =
                    canton.nom;


                button.addEventListener(
                    'click',
                    function () {

                        cantonId.value =
                            canton.id;

                        cantonSearch.value =
                            canton.nom;

                        cantonSelected.textContent =
                            'Canton sélectionné : '
                            + canton.nom;

                        cantonSelected.classList.remove(
                            'hidden'
                        );

                        cantonResults.classList.add(
                            'hidden'
                        );


                        /*
                        | Activation village
                        */

                        villageSearch.disabled =
                            false;

                        villageSearch.value = '';

                        villageResults.innerHTML = '';

                        villageResults.classList.add(
                            'hidden'
                        );


                        /*
                        | Nouvelle sélection de villages
                        */

                        selectedVillageIds = [];

                        afficherVillagesSelectionnes();

                    }
                );


                cantonResults.appendChild(
                    button
                );

            });


            cantonResults.classList.toggle(
                'hidden',
                resultats.length === 0
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | RECHERCHE VILLAGE
    |--------------------------------------------------------------------------
    */

    villageSearch.addEventListener(
        'input',
        function () {

            const search =
                this.value
                    .toLowerCase()
                    .trim();

            villageResults.innerHTML = '';


            if (
                !search
                || !cantonId.value
            ) {

                villageResults.classList.add(
                    'hidden'
                );

                return;

            }


            const resultats =
                villages.filter(function (village) {

                    return (
                        String(village.canton_id)
                            === String(cantonId.value)
                        &&
                        village.nom
                            .toLowerCase()
                            .includes(search)
                    );

                });


            resultats.forEach(function (village) {

                const dejaSelectionne =
                    selectedVillageIds
                        .map(String)
                        .includes(
                            String(village.id)
                        );


                const dejaAffecte =
                    villagesDejaAffectes
                        .map(String)
                        .includes(
                            String(village.id)
                        );


                const button =
                    document.createElement('button');

                button.type = 'button';

                button.className =
                    'block w-full px-4 py-3 '
                    + 'text-left hover:bg-gray-50 '
                    + 'border-b border-gray-100';


                if (dejaAffecte) {

                    button.disabled = true;

                    button.className =
                        'block w-full px-4 py-3 '
                        + 'text-left bg-gray-100 '
                        + 'text-gray-400 '
                        + 'cursor-not-allowed';

                    button.innerHTML = `
                        <div class="flex justify-between">

                            <span>
                                ${escapeHtml(village.nom)}
                            </span>

                            <span class="text-xs">
                                Déjà affecté
                            </span>

                        </div>
                    `;

                } else if (dejaSelectionne) {

                    button.disabled = true;

                    button.className =
                        'block w-full px-4 py-3 '
                        + 'text-left bg-green-50 '
                        + 'text-[#006a4f]';

                    button.textContent =
                        village.nom
                        + ' — sélectionné';

                } else {

                    button.textContent =
                        village.nom;


                    button.addEventListener(
                        'click',
                        function () {

                            if (
                                !selectedVillageIds
                                    .map(String)
                                    .includes(
                                        String(village.id)
                                    )
                            ) {

                                selectedVillageIds.push(
                                    village.id
                                );

                            }


                            villageSearch.value = '';

                            villageResults.classList.add(
                                'hidden'
                            );


                            afficherVillagesSelectionnes();

                        }
                    );

                }


                villageResults.appendChild(
                    button
                );

            });


            villageResults.classList.toggle(
                'hidden',
                resultats.length === 0
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | AFFICHER LES VILLAGES SÉLECTIONNÉS
    |--------------------------------------------------------------------------
    */

    function afficherVillagesSelectionnes() {

        selectedVillages.innerHTML = '';

        /*
        | Supprimer les anciens hidden inputs
        */

        document
            .querySelectorAll(
                '.village-hidden-input'
            )
            .forEach(function (input) {

                input.remove();

            });


        if (
            selectedVillageIds.length === 0
        ) {

            selectedContainer.classList.add(
                'hidden'
            );

            selectedCount.textContent =
                '0 village';

            return;

        }


        selectedContainer.classList.remove(
            'hidden'
        );


        selectedCount.textContent =
            selectedVillageIds.length
            + (
                selectedVillageIds.length > 1
                    ? ' villages'
                    : ' village'
            );


        selectedVillageIds.forEach(
            function (id) {

                const village =
                    villages.find(function (item) {

                        return String(item.id)
                            === String(id);

                    });


                if (!village) {
                    return;
                }


                const badge =
                    document.createElement('div');


                badge.className =
                    'flex items-center gap-2 '
                    + 'rounded-lg bg-white '
                    + 'border border-gray-200 '
                    + 'px-3 py-2 text-sm';


                badge.innerHTML = `

                    <span
                        class="h-2 w-2 rounded-full
                               bg-[#006a4f]"
                    ></span>

                    <span>
                        ${escapeHtml(village.nom)}
                    </span>

                    <button
                        type="button"
                        class="ml-1 text-gray-400
                               hover:text-red-600"
                    >
                        ×
                    </button>

                `;


                badge
                    .querySelector('button')
                    .addEventListener(
                        'click',
                        function () {

                            selectedVillageIds =
                                selectedVillageIds.filter(
                                    function (villageId) {

                                        return String(villageId)
                                            !== String(id);

                                    }
                                );


                            afficherVillagesSelectionnes();

                        }
                    );


                selectedVillages.appendChild(
                    badge
                );


                /*
                | Hidden input envoyé au serveur
                */

                const input =
                    document.createElement('input');


                input.type = 'hidden';

                input.name =
                    'village_ids[]';

                input.value =
                    id;

                input.className =
                    'village-hidden-input';


                document
                    .querySelector('form')
                    .appendChild(input);

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | FERMER LES RÉSULTATS EN CLIQUANT AILLEURS
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'click',
        function (event) {

            if (
                !campagneSearch.contains(event.target)
                &&
                !campagneResults.contains(event.target)
            ) {

                campagneResults.classList.add(
                    'hidden'
                );

            }


            if (
                !cantonSearch.contains(event.target)
                &&
                !cantonResults.contains(event.target)
            ) {

                cantonResults.classList.add(
                    'hidden'
                );

            }


            if (
                !villageSearch.contains(event.target)
                &&
                !villageResults.contains(event.target)
            ) {

                villageResults.classList.add(
                    'hidden'
                );

            }

        }
    );


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
    | ANCIENS VILLAGES
    |--------------------------------------------------------------------------
    */

    @if(count($anciensVillages) > 0)

        selectedVillageIds =
            @json($anciensVillages);

        afficherVillagesSelectionnes();

    @endif

});
</script>