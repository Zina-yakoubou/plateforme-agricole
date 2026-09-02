@csrf

<div class="grid grid-cols-1 gap-6 md:grid-cols-2">

    {{-- ================================================================
        ÉQUIPE
    ================================================================= --}}
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Équipe
        </label>

        <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-3">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-4a4 4 0 100-8 4 4 0 000 8zm6 0a3 3 0 100-6 3 3 0 000 6zM6 10a3 3 0 100-6 3 3 0 000 6z"/>
                    </svg>
                </div>

                <div>
                    <div class="font-semibold text-gray-800">
                        {{ $equipe->nom }}
                    </div>

                    @if($equipe->superviseur)
                        <div class="text-xs text-gray-500">
                            Superviseur :
                            {{ $equipe->superviseur->name }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <input type="hidden"
               name="equipe_id"
               value="{{ $equipe->idEquipe }}">
    </div>


    {{-- ================================================================
        CAMPAGNE
    ================================================================= --}}
    <div class="md:col-span-2">

        <label for="campagne_search"
               class="block text-sm font-medium text-gray-700 mb-1">
            Campagne
        </label>

        <div class="relative">

            <div class="flex gap-2">

                <input type="text"
                       id="campagne_search"
                       autocomplete="off"
                       placeholder="Rechercher une campagne..."
                       class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm
                              focus:border-emerald-500 focus:ring-emerald-500">

                <button type="button"
                        id="campagne_toggle"
                        class="rounded-lg border border-gray-300 bg-white px-3 text-gray-600
                               hover:bg-gray-50">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M19 9l-7 7-7-7"/>
                    </svg>

                </button>

            </div>

            <input type="hidden"
                   name="campagne_id"
                   id="campagne_id"
                   value="{{ old('campagne_id', $affectation->campagne_id ?? '') }}">

            <div id="campagne_results"
                 class="absolute z-30 mt-1 hidden w-full overflow-hidden rounded-lg
                        border border-gray-200 bg-white shadow-lg">
            </div>

        </div>

        <div id="campagne_selected"
             class="mt-2 hidden rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2">
        </div>

        @error('campagne_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

    </div>


    {{-- ================================================================
        CANTON
    ================================================================= --}}
    <div>

        <label for="canton_search"
               class="block text-sm font-medium text-gray-700 mb-1">
            Canton
        </label>

        <div class="relative">

            <div class="flex gap-2">

                <input type="text"
                       id="canton_search"
                       autocomplete="off"
                       disabled
                       placeholder="Sélectionnez d'abord une campagne..."
                       class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm
                              disabled:bg-gray-100 disabled:text-gray-400
                              focus:border-emerald-500 focus:ring-emerald-500">

                <button type="button"
                        id="canton_toggle"
                        disabled
                        class="rounded-lg border border-gray-300 bg-white px-3 text-gray-600
                               disabled:bg-gray-100 disabled:text-gray-400">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M19 9l-7 7-7-7"/>
                    </svg>

                </button>

            </div>

            <input type="hidden"
                   name="canton_id"
                   id="canton_id"
                   value="{{ old('canton_id', $affectation->canton_id ?? '') }}">

            <div id="canton_results"
                 class="absolute z-20 mt-1 hidden w-full overflow-hidden rounded-lg
                        border border-gray-200 bg-white shadow-lg">
            </div>

        </div>

        <div id="canton_selected"
             class="mt-2 hidden rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2">
        </div>

        @error('canton_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

    </div>


    {{-- ================================================================
        VILLAGES
    ================================================================= --}}
    <div>

        <label for="village_search"
               class="block text-sm font-medium text-gray-700 mb-1">
            Villages concernés
        </label>

        <div class="relative">

            <div class="flex gap-2">

                <input type="text"
                       id="village_search"
                       autocomplete="off"
                       disabled
                       placeholder="Sélectionnez d'abord un canton..."
                       class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm
                              disabled:bg-gray-100 disabled:text-gray-400
                              focus:border-emerald-500 focus:ring-emerald-500">

                <button type="button"
                        id="village_toggle"
                        disabled
                        class="rounded-lg border border-gray-300 bg-white px-3 text-gray-600
                               disabled:bg-gray-100 disabled:text-gray-400">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M19 9l-7 7-7-7"/>
                    </svg>

                </button>

            </div>

            <div id="village_results"
                 class="absolute z-20 mt-1 hidden w-full overflow-hidden rounded-lg
                        border border-gray-200 bg-white shadow-lg">
            </div>

        </div>

        <div id="village_loading"
             class="mt-2 hidden text-xs text-gray-500">
            Recherche des villages concernés...
        </div>

        <div id="village_help"
             class="mt-2 text-xs text-gray-500">
            Les villages proposés correspondent aux zones couvertes par la campagne.
        </div>

        <div id="villages_selected"
             class="mt-3 flex flex-wrap gap-2">
        </div>

        @error('village_ids')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

        @error('village_ids.*')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

    </div>


    {{-- ================================================================
        OBSERVATIONS
    ================================================================= --}}
    <div class="md:col-span-2">

        <label for="observations"
               class="block text-sm font-medium text-gray-700 mb-1">
            Observations
            <span class="font-normal text-gray-400">(facultatif)</span>
        </label>

        <textarea name="observations"
                  id="observations"
                  rows="4"
                  placeholder="Ajouter éventuellement une observation..."
                  class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm
                         focus:border-emerald-500 focus:ring-emerald-500">{{ old('observations', $affectation->observations ?? '') }}</textarea>

        @error('observations')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

    </div>

</div>


{{-- ================================================================
    STATUT
================================================================= --}}
<input type="hidden"
       name="statut"
       value="{{ old('statut', $affectation->statut ?? 'active') }}">


<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ================================================================
       DONNÉES
    ================================================================= */

    const campagnes = @json($campagnesJs ?? []);

    const cantons = @json($cantonsJs ?? []);

    const villagesCampagne = @json(
        $villagesCampagneJs ?? $villagesPlanifiesJs ?? []
    );


    /* ================================================================
       CAMPAGNE
    ================================================================= */

    const campagneSearch = document.getElementById('campagne_search');
    const campagneId = document.getElementById('campagne_id');
    const campagneResults = document.getElementById('campagne_results');
    const campagneSelected = document.getElementById('campagne_selected');
    const campagneToggle = document.getElementById('campagne_toggle');


    /* ================================================================
       CANTON
    ================================================================= */

    const cantonSearch = document.getElementById('canton_search');
    const cantonId = document.getElementById('canton_id');
    const cantonResults = document.getElementById('canton_results');
    const cantonSelected = document.getElementById('canton_selected');
    const cantonToggle = document.getElementById('canton_toggle');


    /* ================================================================
       VILLAGES
    ================================================================= */

    const villageSearch = document.getElementById('village_search');
    const villageResults = document.getElementById('village_results');
    const villageToggle = document.getElementById('village_toggle');
    const villageLoading = document.getElementById('village_loading');
    const villageHelp = document.getElementById('village_help');
    const villagesSelected = document.getElementById('villages_selected');


    /* ================================================================
       VILLAGES SÉLECTIONNÉS
    ================================================================= */

    let villagesSelectionnes = [];


    /* ================================================================
       UTILITAIRES
    ================================================================= */

    function escapeHtml(value) {

        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

    }


    function afficherZone(element) {

        element.classList.remove('hidden');

    }


    function masquerZone(element) {

        element.classList.add('hidden');

    }


    /* ================================================================
       CAMPAGNES
    ================================================================= */

    function afficherCampagnes(recherche = '') {

        const terme = recherche.trim().toLowerCase();

        const resultats = campagnes.filter(campagne => {

            const texte = [
                campagne.idCampagne,
                campagne.codeCampagne,
                campagne.libelle,
                campagne.statut,
                campagne.portee
            ]
            .filter(Boolean)
            .join(' ')
            .toLowerCase();

            return texte.includes(terme);

        });


        campagneResults.innerHTML = '';


        if (resultats.length === 0) {

            campagneResults.innerHTML = `
                <div class="px-4 py-3 text-sm text-gray-500">
                    Aucune campagne trouvée.
                </div>
            `;

            afficherZone(campagneResults);

            return;
        }


        resultats.forEach(campagne => {

            const div = document.createElement('button');

            div.type = 'button';

            div.className =
                'block w-full border-b border-gray-100 px-4 py-3 text-left ' +
                'hover:bg-emerald-50';

            div.innerHTML = `
                <div class="font-medium text-gray-800">
                    ${escapeHtml(campagne.libelle)}
                </div>

                <div class="mt-0.5 text-xs text-gray-500">
                    ${escapeHtml(campagne.codeCampagne ?? '')}
                    ${campagne.statut
                        ? ' • ' + escapeHtml(campagne.statut)
                        : ''}
                </div>
            `;


            div.addEventListener('click', function () {

                selectionnerCampagne(campagne);

            });


            campagneResults.appendChild(div);

        });


        afficherZone(campagneResults);

    }


    function selectionnerCampagne(campagne) {

        campagneId.value = campagne.idCampagne ?? campagne.id;

        campagneSearch.value = campagne.libelle;

        campagneSelected.innerHTML = `
            <div class="flex items-center justify-between gap-3">

                <div>
                    <div class="text-sm font-semibold text-emerald-800">
                        ${escapeHtml(campagne.libelle)}
                    </div>

                    <div class="text-xs text-emerald-700">
                        ${escapeHtml(campagne.codeCampagne ?? '')}
                    </div>
                </div>

                <button type="button"
                        id="campagne_clear"
                        class="text-xs font-medium text-red-600 hover:text-red-800">
                    Modifier
                </button>

            </div>
        `;


        afficherZone(campagneSelected);

        masquerZone(campagneResults);


        document.getElementById('campagne_clear')
            .addEventListener('click', function () {

                campagneId.value = '';
                campagneSearch.value = '';

                masquerZone(campagneSelected);

                resetCanton();
                resetVillages();

                campagneSearch.focus();

            });


        activerCanton();

    }


    campagneSearch.addEventListener('input', function () {

        if (!this.value.trim()) {

            campagneId.value = '';

            masquerZone(campagneSelected);

            resetCanton();
            resetVillages();

        }

        afficherCampagnes(this.value);

    });


    campagneSearch.addEventListener('focus', function () {

        afficherCampagnes(this.value);

    });


    campagneToggle.addEventListener('click', function () {

        if (campagneResults.classList.contains('hidden')) {

            afficherCampagnes(campagneSearch.value);

        } else {

            masquerZone(campagneResults);

        }

    });


    /* ================================================================
       CANTONS
    ================================================================= */

    function activerCanton() {

        cantonSearch.disabled = false;
        cantonToggle.disabled = false;

        cantonSearch.placeholder = 'Rechercher un canton...';

    }


    function afficherCantons(recherche = '') {

        if (!campagneId.value) {

            return;

        }


        const terme = recherche.trim().toLowerCase();

        const resultats = cantons.filter(canton => {

            const texte = [
                canton.nom,
                canton.code,
                canton.commune_nom
            ]
            .filter(Boolean)
            .join(' ')
            .toLowerCase();

            return texte.includes(terme);

        });


        cantonResults.innerHTML = '';


        if (resultats.length === 0) {

            cantonResults.innerHTML = `
                <div class="px-4 py-3 text-sm text-gray-500">
                    Aucun canton trouvé.
                </div>
            `;

            afficherZone(cantonResults);

            return;
        }


        resultats.forEach(canton => {

            const div = document.createElement('button');

            div.type = 'button';

            div.className =
                'block w-full border-b border-gray-100 px-4 py-3 text-left ' +
                'hover:bg-emerald-50';

            div.innerHTML = `
                <div class="font-medium text-gray-800">
                    ${escapeHtml(canton.nom)}
                </div>

                ${
                    canton.commune_nom
                    ? `
                        <div class="text-xs text-gray-500">
                            Commune : ${escapeHtml(canton.commune_nom)}
                        </div>
                      `
                    : ''
                }
            `;


            div.addEventListener('click', function () {

                selectionnerCanton(canton);

            });


            cantonResults.appendChild(div);

        });


        afficherZone(cantonResults);

    }


    function selectionnerCanton(canton) {

        cantonId.value = canton.idCanton ?? canton.id;

        cantonSearch.value = canton.nom;

        cantonSelected.innerHTML = `
            <div class="flex items-center justify-between gap-3">

                <div>
                    <div class="text-sm font-semibold text-emerald-800">
                        ${escapeHtml(canton.nom)}
                    </div>

                    ${
                        canton.commune_nom
                        ? `
                            <div class="text-xs text-emerald-700">
                                ${escapeHtml(canton.commune_nom)}
                            </div>
                          `
                        : ''
                    }
                </div>

                <button type="button"
                        id="canton_clear"
                        class="text-xs font-medium text-red-600 hover:text-red-800">
                    Modifier
                </button>

            </div>
        `;


        afficherZone(cantonSelected);

        masquerZone(cantonResults);


        document.getElementById('canton_clear')
            .addEventListener('click', function () {

                resetCanton();

                cantonSearch.focus();

            });


        chargerVillages(
            campagneId.value,
            cantonId.value
        );

    }


    cantonSearch.addEventListener('input', function () {

        if (!this.value.trim()) {

            cantonId.value = '';

            masquerZone(cantonSelected);

            resetVillages();

        }

        afficherCantons(this.value);

    });


    cantonSearch.addEventListener('focus', function () {

        afficherCantons(this.value);

    });


    cantonToggle.addEventListener('click', function () {

        if (cantonResults.classList.contains('hidden')) {

            afficherCantons(cantonSearch.value);

        } else {

            masquerZone(cantonResults);

        }

    });


    /* ================================================================
       VILLAGES
    ================================================================= */

    function chargerVillages(campagne, canton) {

        resetVillages(false);


        if (!campagne || !canton) {

            return;

        }


        villageLoading.classList.remove('hidden');

        villageHelp.textContent =
            'Recherche des villages concernés par cette campagne...';


        villageSearch.disabled = true;
        villageToggle.disabled = true;


        setTimeout(function () {

            const villages = villagesCampagne.filter(village => {

                return (
                    String(village.campagne_id) === String(campagne)
                    &&
                    String(village.canton_id) === String(canton)
                );

            });


            villageLoading.classList.add('hidden');

            villageSearch.disabled = false;
            villageToggle.disabled = false;


            villageSearch.placeholder =
                'Rechercher un village...';


            villageResults.innerHTML = '';


            if (villages.length === 0) {

                villageHelp.textContent =
                    'Aucun village de ce canton n’est couvert par les zones de cette campagne.';

                villageResults.innerHTML = `
                    <div class="px-4 py-3 text-sm text-gray-500">
                        Aucun village concerné.
                    </div>
                `;

                afficherZone(villageResults);

                return;

            }


            villageHelp.textContent =
                `${villages.length} village${villages.length > 1 ? 's' : ''} couvert${villages.length > 1 ? 's' : ''} par cette campagne.`;


            afficherVillages(villages, '');

        }, 100);

    }


    function afficherVillages(villages, recherche = '') {

        const terme = recherche.trim().toLowerCase();


        const resultats = villages.filter(village => {

            const texte = [
                village.nom,
                village.code
            ]
            .filter(Boolean)
            .join(' ')
            .toLowerCase();

            return texte.includes(terme);

        });


        villageResults.innerHTML = '';


        if (resultats.length === 0) {

            villageResults.innerHTML = `
                <div class="px-4 py-3 text-sm text-gray-500">
                    Aucun village trouvé.
                </div>
            `;

            afficherZone(villageResults);

            return;

        }


        resultats.forEach(village => {

            const villageId = village.idVillage ?? village.id;

            const dejaSelectionne =
                villagesSelectionnes.some(
                    item => String(item.id) === String(villageId)
                );


            const div = document.createElement('button');

            div.type = 'button';

            div.disabled = dejaSelectionne;

            div.className =
                'block w-full border-b border-gray-100 px-4 py-3 text-left ' +
                (dejaSelectionne
                    ? 'cursor-not-allowed bg-gray-50 opacity-50'
                    : 'hover:bg-emerald-50');


            div.innerHTML = `
                <div class="font-medium text-gray-800">
                    ${escapeHtml(village.nom)}
                </div>

                ${
                    village.code
                    ? `
                        <div class="text-xs text-gray-500">
                            ${escapeHtml(village.code)}
                        </div>
                      `
                    : ''
                }
            `;


            if (!dejaSelectionne) {

                div.addEventListener('click', function () {

                    ajouterVillage(village);

                });

            }


            villageResults.appendChild(div);

        });


        afficherZone(villageResults);

    }


    function ajouterVillage(village) {

        const id = village.idVillage ?? village.id;


        if (
            villagesSelectionnes.some(
                item => String(item.id) === String(id)
            )
        ) {

            return;

        }


        villagesSelectionnes.push({

            id: id,

            nom: village.nom

        });


        afficherVillagesSelectionnes();


        villageSearch.value = '';

        masquerZone(villageResults);

    }


    function supprimerVillage(id) {

        villagesSelectionnes =
            villagesSelectionnes.filter(
                village =>
                    String(village.id) !== String(id)
            );


        afficherVillagesSelectionnes();

    }


    function afficherVillagesSelectionnes() {

        villagesSelected.innerHTML = '';


        villagesSelectionnes.forEach(village => {

            const wrapper = document.createElement('div');

            wrapper.className =
                'flex items-center gap-2 rounded-full border border-emerald-200 ' +
                'bg-emerald-50 px-3 py-1.5 text-sm text-emerald-800';


            wrapper.innerHTML = `
                <span>
                    ${escapeHtml(village.nom)}
                </span>

                <button type="button"
                        class="text-emerald-600 hover:text-red-600"
                        title="Retirer">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-4 w-4"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>

                    </svg>

                </button>
            `;


            wrapper
                .querySelector('button')
                .addEventListener('click', function () {

                    supprimerVillage(village.id);

                });


            const input = document.createElement('input');

            input.type = 'hidden';

            input.name = 'village_ids[]';

            input.value = village.id;


            wrapper.appendChild(input);

            villagesSelected.appendChild(wrapper);

        });

    }


    villageSearch.addEventListener('input', function () {

        const campagne = campagneId.value;
        const canton = cantonId.value;


        if (!campagne || !canton) {

            return;

        }


        const villages = villagesCampagne.filter(village => {

            return (
                String(village.campagne_id) === String(campagne)
                &&
                String(village.canton_id) === String(canton)
            );

        });


        afficherVillages(
            villages,
            this.value
        );

    });


    villageSearch.addEventListener('focus', function () {

        const campagne = campagneId.value;
        const canton = cantonId.value;


        if (!campagne || !canton) {

            return;

        }


        const villages = villagesCampagne.filter(village => {

            return (
                String(village.campagne_id) === String(campagne)
                &&
                String(village.canton_id) === String(canton)
            );

        });


        afficherVillages(
            villages,
            this.value
        );

    });


    villageToggle.addEventListener('click', function () {

        const campagne = campagneId.value;
        const canton = cantonId.value;


        if (!campagne || !canton) {

            return;

        }


        if (villageResults.classList.contains('hidden')) {

            const villages = villagesCampagne.filter(village => {

                return (
                    String(village.campagne_id) === String(campagne)
                    &&
                    String(village.canton_id) === String(canton)
                );

            });


            afficherVillages(
                villages,
                villageSearch.value
            );

        } else {

            masquerZone(villageResults);

        }

    });


    /* ================================================================
       RESET CANTON
    ================================================================= */

    function resetCanton() {

        cantonId.value = '';

        cantonSearch.value = '';

        cantonSearch.disabled = !campagneId.value;

        cantonToggle.disabled = !campagneId.value;

        cantonSearch.placeholder =
            campagneId.value
                ? 'Rechercher un canton...'
                : 'Sélectionnez d’abord une campagne...';


        masquerZone(cantonResults);

        masquerZone(cantonSelected);

        resetVillages();

    }


    /* ================================================================
       RESET VILLAGES
    ================================================================= */

    function resetVillages(clearSelection = true) {

        if (clearSelection) {

            villagesSelectionnes = [];

            afficherVillagesSelectionnes();

        }


        villageSearch.value = '';

        villageSearch.disabled = true;

        villageToggle.disabled = true;

        villageSearch.placeholder =
            'Sélectionnez d’abord un canton...';


        masquerZone(villageResults);

        villageLoading.classList.add('hidden');

        villageHelp.textContent =
            'Les villages proposés correspondent aux zones couvertes par la campagne.';

    }


    /* ================================================================
       FERMETURE DES LISTES
    ================================================================= */

    document.addEventListener('click', function (event) {

        if (
            !campagneSearch.contains(event.target) &&
            !campagneResults.contains(event.target) &&
            !campagneToggle.contains(event.target)
        ) {

            masquerZone(campagneResults);

        }


        if (
            !cantonSearch.contains(event.target) &&
            !cantonResults.contains(event.target) &&
            !cantonToggle.contains(event.target)
        ) {

            masquerZone(cantonResults);

        }


        if (
            !villageSearch.contains(event.target) &&
            !villageResults.contains(event.target) &&
            !villageToggle.contains(event.target)
        ) {

            masquerZone(villageResults);

        }

    });


    /* ================================================================
       RESTAURATION DES ANCIENNES VALEURS
    ================================================================= */

    const ancienneCampagneId = campagneId.value;

    const ancienCantonId = cantonId.value;

    /*
     * Ne pas utiliser :
     *
     * $affectation->villages->pluck(...)
     *
     * car la relation villages n'est pas disponible ici.
     */
    const anciensVillages = @json(old('village_ids', []));


    /* ------------------------------------------------
       Restaurer campagne
    ------------------------------------------------ */

    if (ancienneCampagneId) {

        const campagne = campagnes.find(item =>
            String(item.idCampagne) === String(ancienneCampagneId)
        );


        if (campagne) {

            selectionnerCampagne(campagne);

        }

    }


    /* ------------------------------------------------
       Restaurer canton
    ------------------------------------------------ */

    if (ancienCantonId) {

        const canton = cantons.find(item => {

            const id = item.idCanton ?? item.id;

            return String(id) === String(ancienCantonId);

        });


        if (canton) {

            selectionnerCanton(canton);

        }

    }


    /* ------------------------------------------------
       Restaurer villages
    ------------------------------------------------ */

    if (
        Array.isArray(anciensVillages) &&
        anciensVillages.length > 0
    ) {

        anciensVillages.forEach(id => {

            const village = villagesCampagne.find(item => {

                const villageId =
                    item.idVillage ?? item.id;

                return String(villageId) === String(id);

            });


            if (village) {

                ajouterVillage(village);

            }

        });

    }

});
</script>