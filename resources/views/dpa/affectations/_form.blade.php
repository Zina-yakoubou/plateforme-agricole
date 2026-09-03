@csrf

<div class="grid grid-cols-1 gap-6">

    {{-- ================================================================
        ÉQUIPE
    ================================================================= --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Équipe
        </label>

        <div class="flex items-center gap-3 rounded-xl border border-gray-200 bg-gray-50 px-4 py-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
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
                        Superviseur : {{ $equipe->superviseur->name }}
                    </div>
                @endif
            </div>
        </div>

        <input type="hidden" name="equipe_id" value="{{ $equipe->idEquipe }}">
    </div>


    {{-- ================================================================
        CAMPAGNE
    ================================================================= --}}
    <div>

        <label for="campagne_search" class="block text-sm font-medium text-gray-700 mb-1">
            Campagne
        </label>

        <div class="flex gap-2">

            <div class="relative flex-1">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>

                <input type="text"
                       id="campagne_search"
                       autocomplete="off"
                       placeholder="Rechercher une campagne..."
                       class="w-full rounded-xl border border-gray-300 py-2.5 pl-10 pr-4 text-sm
                              transition focus:border-emerald-500 focus:ring-emerald-500">
            </div>

            <button type="button"
                    id="campagne_toggle"
                    class="flex items-center justify-center rounded-xl border border-gray-300 bg-white px-3 text-gray-600
                           transition hover:bg-gray-50">

                <svg xmlns="http://www.w3.org/2000/svg"
                     id="campagne_toggle_icon"
                     class="h-5 w-5 transition-transform duration-200"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

        </div>

        <input type="hidden"
               name="campagne_id"
               id="campagne_id"
               value="{{ old('campagne_id', $affectation->campagne_id ?? '') }}">

        {{-- Panneau en flux normal : ne peut plus rester "caché" hors champ visuel --}}
        <div id="campagne_results_wrapper"
             class="grid grid-rows-[0fr] transition-[grid-template-rows] duration-300 ease-out">
            <div class="overflow-hidden">
                <div id="campagne_results"
                     class="mt-2 max-h-80 divide-y divide-gray-100 overflow-y-auto rounded-xl
                            border border-gray-200 bg-white shadow-sm">
                </div>
            </div>
        </div>

        <div id="campagne_selected_wrapper"
             class="grid grid-rows-[0fr] transition-[grid-template-rows] duration-300 ease-out">
            <div class="overflow-hidden">
                <div id="campagne_selected"
                     class="mt-2 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2.5">
                </div>
            </div>
        </div>

        @error('campagne_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

    </div>


    {{-- ================================================================
        VILLAGES — sélection libre à n'importe quel niveau
    ================================================================= --}}
    <div>

        <div class="mb-1 flex items-center justify-between gap-3">

            <label for="village_search" class="block text-sm font-medium text-gray-700">
                Zone d'affectation
            </label>

            <button type="button"
                    id="village_select_all"
                    disabled
                    class="inline-flex shrink-0 items-center gap-1 rounded-full border border-emerald-200
                           bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700
                           transition hover:bg-emerald-100
                           disabled:cursor-not-allowed disabled:border-gray-200 disabled:bg-gray-50 disabled:text-gray-300">
                Toute la préfecture
            </button>

        </div>

        <p class="mb-2 text-xs text-gray-500">
            Cliquez sur une commune, un canton ou un village pour sélectionner tout ce niveau
            d'un coup. Vous pouvez combiner plusieurs niveaux librement.
        </p>

        <div class="flex gap-2">

            <div class="relative flex-1">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>

                <input type="text"
                       id="village_search"
                       autocomplete="off"
                       disabled
                       placeholder="Sélectionnez d'abord une campagne..."
                       class="w-full rounded-xl border border-gray-300 py-2.5 pl-10 pr-4 text-sm
                              transition disabled:bg-gray-100 disabled:text-gray-400
                              focus:border-emerald-500 focus:ring-emerald-500">
            </div>

            <button type="button"
                    id="village_toggle"
                    disabled
                    class="flex items-center justify-center rounded-xl border border-gray-300 bg-white px-3 text-gray-600
                           transition disabled:bg-gray-100 disabled:text-gray-400">

                <svg xmlns="http://www.w3.org/2000/svg"
                     id="village_toggle_icon"
                     class="h-5 w-5 transition-transform duration-200"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

        </div>

        <div id="village_loading" class="mt-2 hidden items-center gap-2 text-xs text-gray-500">
            <svg class="h-3.5 w-3.5 animate-spin text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
            Recherche des zones concernées...
        </div>

        <div id="village_help" class="mt-2 text-xs text-gray-500">
            Sélectionnez d'abord une campagne pour voir les zones qu'elle couvre.
        </div>

        {{-- Panneau en flux normal : plus de positionnement "absolute" qui pouvait
             faire disparaître la liste sous le reste de la page ou d'une modale --}}
        <div id="village_results_wrapper"
             class="grid grid-rows-[0fr] transition-[grid-template-rows] duration-300 ease-out">
            <div class="overflow-hidden">
                <div id="village_results"
                     class="mt-2 max-h-[28rem] overflow-y-auto rounded-xl border border-gray-200 bg-white shadow-sm">
                </div>
            </div>
        </div>

        <div id="villages_selected" class="mt-3 flex flex-wrap gap-2"></div>

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
    <div>

        <label for="observations" class="block text-sm font-medium text-gray-700 mb-1">
            Observations
            <span class="font-normal text-gray-400">(facultatif)</span>
        </label>

        <textarea name="observations"
                  id="observations"
                  rows="4"
                  placeholder="Ajouter éventuellement une observation..."
                  class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm
                         transition focus:border-emerald-500 focus:ring-emerald-500">{{ old('observations', $affectation->observations ?? '') }}</textarea>

        @error('observations')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

    </div>

</div>


{{-- ================================================================
    STATUT
================================================================= --}}
<input type="hidden" name="statut" value="{{ old('statut', $affectation->statut ?? 'active') }}">


<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ================================================================
       DONNÉES
    ================================================================= */

    const campagnes = @json($campagnesJs ?? []);

    const villagesCampagne = @json(
        $villagesCampagneJs ?? $villagesPlanifiesJs ?? []
    );


    /* ================================================================
       CAMPAGNE
    ================================================================= */

    const campagneSearch = document.getElementById('campagne_search');
    const campagneId = document.getElementById('campagne_id');
    const campagneResults = document.getElementById('campagne_results');
    const campagneResultsWrapper = document.getElementById('campagne_results_wrapper');
    const campagneSelected = document.getElementById('campagne_selected');
    const campagneSelectedWrapper = document.getElementById('campagne_selected_wrapper');
    const campagneToggle = document.getElementById('campagne_toggle');
    const campagneToggleIcon = document.getElementById('campagne_toggle_icon');


    /* ================================================================
       VILLAGES / ZONE
    ================================================================= */

    const villageSearch = document.getElementById('village_search');
    const villageResults = document.getElementById('village_results');
    const villageResultsWrapper = document.getElementById('village_results_wrapper');
    const villageToggle = document.getElementById('village_toggle');
    const villageToggleIcon = document.getElementById('village_toggle_icon');
    const villageLoading = document.getElementById('village_loading');
    const villageHelp = document.getElementById('village_help');
    const villagesSelected = document.getElementById('villages_selected');
    const villageSelectAll = document.getElementById('village_select_all');


    let villagesSelectionnes = [];


    /* ================================================================
       UTILITAIRES — ouverture / fermeture des panneaux
    ================================================================= */

    function escapeHtml(value) {

        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

    }


    /**
     * Ouvre un panneau (wrapper en grid-template-rows 0fr -> 1fr).
     * Le panneau vit dans le flux normal de la page : il ne peut donc
     * plus se retrouver visuellement "caché" derrière ou sous un autre
     * élément comme le ferait un dropdown en position absolute.
     */
    function ouvrirPanneau(wrapper, icone, scrollAuBesoin = true) {

        wrapper.classList.remove('grid-rows-[0fr]');
        wrapper.classList.add('grid-rows-[1fr]');

        if (icone) {
            icone.classList.add('rotate-180');
        }

        if (scrollAuBesoin) {

            setTimeout(function () {

                const rect = wrapper.getBoundingClientRect();

                const debordeEnBas = rect.bottom > window.innerHeight;

                if (debordeEnBas) {

                    wrapper.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest'
                    });

                }

            }, 220);

        }

    }


    function fermerPanneau(wrapper, icone) {

        wrapper.classList.remove('grid-rows-[1fr]');
        wrapper.classList.add('grid-rows-[0fr]');

        if (icone) {
            icone.classList.remove('rotate-180');
        }

    }


    function panneauOuvert(wrapper) {

        return wrapper.classList.contains('grid-rows-[1fr]');

    }


    function estSelectionne(village) {

        const id = village.idVillage ?? village.id;

        return villagesSelectionnes.some(
            item => String(item.id) === String(id)
        );

    }


    /**
     * Retourne tous les villages couverts
     * par la campagne actuellement sélectionnée.
     */
    function villagesDeLaCampagneCourante() {

        const campagne = campagneId.value;

        if (!campagne) {
            return [];
        }

        return villagesCampagne.filter(village => {
            return String(village.campagne_id) === String(campagne);
        });

    }


    /**
     * Regroupe commune -> canton -> villages.
     */
    function grouperParCommuneEtCanton(villages) {

        const communes = new Map();

        villages.forEach(village => {

            const communeId = village.commune_id ?? 'sans-commune';
            const communeNom = village.commune_nom || 'Commune non renseignée';

            const cantonId = village.canton_id ?? 'sans-canton';
            const cantonNom = village.canton_nom || 'Canton non renseigné';

            if (!communes.has(communeId)) {
                communes.set(communeId, {
                    commune_id: communeId,
                    commune_nom: communeNom,
                    cantons: new Map()
                });
            }

            const communeGroupe = communes.get(communeId);

            if (!communeGroupe.cantons.has(cantonId)) {
                communeGroupe.cantons.set(cantonId, {
                    canton_id: cantonId,
                    canton_nom: cantonNom,
                    villages: []
                });
            }

            communeGroupe.cantons.get(cantonId).villages.push(village);

        });

        return Array.from(communes.values())
            .sort((a, b) => a.commune_nom.localeCompare(b.commune_nom))
            .map(communeGroupe => {

                const cantons = Array.from(communeGroupe.cantons.values())
                    .sort((a, b) => a.canton_nom.localeCompare(b.canton_nom))
                    .map(cantonGroupe => {

                        cantonGroupe.villages.sort((a, b) =>
                            (a.nom || '').localeCompare(b.nom || '')
                        );

                        return cantonGroupe;

                    });

                return {
                    commune_id: communeGroupe.commune_id,
                    commune_nom: communeGroupe.commune_nom,
                    cantons: cantons
                };

            });

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
                <div class="px-4 py-6 text-center text-sm text-gray-500">
                    Aucune campagne trouvée.
                </div>
            `;

            ouvrirPanneau(campagneResultsWrapper, campagneToggleIcon);

            return;
        }

        resultats.forEach(campagne => {

            const div = document.createElement('button');

            div.type = 'button';

            div.className =
                'block w-full border-b border-gray-100 px-4 py-3 text-left ' +
                'transition last:border-b-0 hover:bg-emerald-50';

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

        ouvrirPanneau(campagneResultsWrapper, campagneToggleIcon);

    }


    function selectionnerCampagne(campagne) {

        campagneId.value = campagne.idCampagne ?? campagne.id;

        campagneSearch.value = campagne.libelle;

        campagneSelected.innerHTML = `
            <div class="flex items-center justify-between gap-3">

                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-emerald-600"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <div>
                        <div class="text-sm font-semibold text-emerald-800">
                            ${escapeHtml(campagne.libelle)}
                        </div>
                        <div class="text-xs text-emerald-700">
                            ${escapeHtml(campagne.codeCampagne ?? '')}
                        </div>
                    </div>
                </div>

                <button type="button"
                        id="campagne_clear"
                        class="shrink-0 text-xs font-medium text-red-600 transition hover:text-red-800">
                    Modifier
                </button>

            </div>
        `;

        ouvrirPanneau(campagneSelectedWrapper, null, false);

        fermerPanneau(campagneResultsWrapper, campagneToggleIcon);

        document.getElementById('campagne_clear')
            .addEventListener('click', function () {

                campagneId.value = '';
                campagneSearch.value = '';

                fermerPanneau(campagneSelectedWrapper, null);

                resetVillages();

                campagneSearch.focus();

            });

        activerVillages();

    }


    campagneSearch.addEventListener('input', function () {

        if (!this.value.trim()) {

            campagneId.value = '';

            fermerPanneau(campagneSelectedWrapper, null);

            resetVillages();

        }

        afficherCampagnes(this.value);

    });


    campagneSearch.addEventListener('focus', function () {
        afficherCampagnes(this.value);
    });


    campagneToggle.addEventListener('click', function () {

        if (!panneauOuvert(campagneResultsWrapper)) {
            afficherCampagnes(campagneSearch.value);
        } else {
            fermerPanneau(campagneResultsWrapper, campagneToggleIcon);
        }

    });


    /* ================================================================
       VILLAGES / ZONE
    ================================================================= */

    function activerVillages() {

        villageLoading.classList.remove('hidden');
        villageLoading.classList.add('flex');

        villageSearch.disabled = true;
        villageToggle.disabled = true;
        villageSelectAll.disabled = true;

        villageHelp.textContent =
            'Recherche des zones concernées par cette campagne...';

        setTimeout(function () {

            const villages = villagesDeLaCampagneCourante();

            villageLoading.classList.add('hidden');
            villageLoading.classList.remove('flex');

            villageSearch.disabled = false;
            villageToggle.disabled = false;

            villageSearch.placeholder =
                'Rechercher une commune, un canton ou un village...';

            villageResults.innerHTML = '';

            if (villages.length === 0) {

                villageHelp.textContent =
                    'Aucun village de votre préfecture n’est couvert par les zones de cette campagne.';

                villageSelectAll.disabled = true;

                return;

            }

            const nbCommunes = new Set(villages.map(v => v.commune_id)).size;
            const nbCantons = new Set(villages.map(v => v.canton_id)).size;

            villageHelp.textContent =
                `${villages.length} village${villages.length > 1 ? 's' : ''} `
                + `répartis sur ${nbCommunes} commune${nbCommunes > 1 ? 's' : ''} `
                + `et ${nbCantons} canton${nbCantons > 1 ? 's' : ''}, `
                + `couverts par cette campagne.`;

            villageSelectAll.disabled = false;

        }, 100);

    }


    /**
     * Affiche les résultats groupés commune -> canton -> villages.
     * Chaque niveau (commune, canton, village) peut être
     * sélectionné en un clic, indépendamment des autres.
     */
    function afficherVillages(villages, recherche = '') {

        const terme = recherche.trim().toLowerCase();

        const resultats = villages.filter(village => {

            const texte = [
                village.nom,
                village.code,
                village.canton_nom,
                village.commune_nom
            ]
            .filter(Boolean)
            .join(' ')
            .toLowerCase();

            return texte.includes(terme);

        });

        villageResults.innerHTML = '';

        if (resultats.length === 0) {

            villageResults.innerHTML = `
                <div class="px-4 py-6 text-center text-sm text-gray-500">
                    Aucun résultat trouvé.
                </div>
            `;

            ouvrirPanneau(villageResultsWrapper, villageToggleIcon);

            return;

        }

        const communes = grouperParCommuneEtCanton(resultats);

        communes.forEach(communeGroupe => {

            const villagesDeLaCommune = communeGroupe.cantons
                .flatMap(c => c.villages);

            const nonSelectionnesCommune = villagesDeLaCommune
                .filter(v => !estSelectionne(v));

            const enteteCommune = document.createElement('div');

            enteteCommune.className =
                'sticky top-0 z-10 flex items-center justify-between gap-3 border-b ' +
                'border-gray-200 bg-gray-100/95 px-4 py-2 backdrop-blur-sm';

            enteteCommune.innerHTML = `
                <div class="text-xs font-bold uppercase tracking-wide text-gray-700">
                    ${escapeHtml(communeGroupe.commune_nom)}
                    <span class="font-normal normal-case tracking-normal text-gray-400">
                        (${villagesDeLaCommune.length} village${villagesDeLaCommune.length > 1 ? 's' : ''})
                    </span>
                </div>
            `;

            const boutonCommune = document.createElement('button');

            boutonCommune.type = 'button';

            const communeEntierementSelectionnee = nonSelectionnesCommune.length === 0;

            boutonCommune.disabled = communeEntierementSelectionnee;

            boutonCommune.className =
                'shrink-0 whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-semibold transition ' +
                (communeEntierementSelectionnee
                    ? 'cursor-not-allowed text-gray-300'
                    : 'text-emerald-800 hover:bg-emerald-100');

            boutonCommune.textContent =
                communeEntierementSelectionnee
                    ? 'Commune sélectionnée'
                    : 'Sélectionner cette commune';

            boutonCommune.addEventListener('click', function (event) {

                event.stopPropagation();

                villagesDeLaCommune.forEach(village => {
                    ajouterVillage(village);
                });

                afficherVillages(villages, recherche);

            });

            enteteCommune.appendChild(boutonCommune);

            villageResults.appendChild(enteteCommune);

            communeGroupe.cantons.forEach(cantonGroupe => {

                const nonSelectionnesCanton = cantonGroupe.villages
                    .filter(v => !estSelectionne(v));

                const enteteCanton = document.createElement('div');

                enteteCanton.className =
                    'flex items-center justify-between gap-3 border-b ' +
                    'border-gray-100 bg-gray-50 px-4 py-1.5 pl-6';

                enteteCanton.innerHTML = `
                    <div class="text-xs font-semibold text-gray-500">
                        ${escapeHtml(cantonGroupe.canton_nom)}
                        <span class="font-normal text-gray-400">
                            (${cantonGroupe.villages.length})
                        </span>
                    </div>
                `;

                const boutonCanton = document.createElement('button');

                boutonCanton.type = 'button';

                const cantonEntierementSelectionne = nonSelectionnesCanton.length === 0;

                boutonCanton.disabled = cantonEntierementSelectionne;

                boutonCanton.className =
                    'shrink-0 whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-medium transition ' +
                    (cantonEntierementSelectionne
                        ? 'cursor-not-allowed text-gray-300'
                        : 'text-emerald-700 hover:bg-emerald-100');

                boutonCanton.textContent =
                    cantonEntierementSelectionne
                        ? 'Sélectionné'
                        : 'Sélectionner ce canton';

                boutonCanton.addEventListener('click', function (event) {

                    event.stopPropagation();

                    cantonGroupe.villages.forEach(village => {
                        ajouterVillage(village);
                    });

                    afficherVillages(villages, recherche);

                });

                enteteCanton.appendChild(boutonCanton);

                villageResults.appendChild(enteteCanton);

                cantonGroupe.villages.forEach(village => {

                    const dejaSelectionne = estSelectionne(village);

                    const div = document.createElement('button');

                    div.type = 'button';

                    div.disabled = dejaSelectionne;

                    div.className =
                        'flex w-full items-center justify-between gap-3 border-b border-gray-100 ' +
                        'px-4 py-2 pl-10 text-left transition last:border-b-0 ' +
                        (dejaSelectionne
                            ? 'cursor-not-allowed bg-gray-50 opacity-50'
                            : 'hover:bg-emerald-50');

                    div.innerHTML = `
                        <div>
                            <div class="text-sm text-gray-800">
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
                        </div>

                        ${
                            dejaSelectionne
                            ? `
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-emerald-500"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                              `
                            : ''
                        }
                    `;

                    if (!dejaSelectionne) {

                        div.addEventListener('click', function () {
                            ajouterVillage(village);
                            afficherVillages(villages, recherche);
                        });

                    }

                    villageResults.appendChild(div);

                });

            });

        });

        ouvrirPanneau(villageResultsWrapper, villageToggleIcon);

    }


    function ajouterVillage(village) {

        const id = village.idVillage ?? village.id;

        if (estSelectionne(village)) {
            return;
        }

        villagesSelectionnes.push({
            id: id,
            nom: village.nom,
            canton_nom: village.canton_nom,
            commune_nom: village.commune_nom
        });

        afficherVillagesSelectionnes();

    }


    function supprimerVillage(id) {

        villagesSelectionnes =
            villagesSelectionnes.filter(
                village => String(village.id) !== String(id)
            );

        afficherVillagesSelectionnes();

        if (panneauOuvert(villageResultsWrapper)) {

            afficherVillages(
                villagesDeLaCampagneCourante(),
                villageSearch.value
            );

        }

    }


    function afficherVillagesSelectionnes() {

        villagesSelected.innerHTML = '';

        villagesSelectionnes.forEach(village => {

            const wrapper = document.createElement('div');

            wrapper.className =
                'flex items-center gap-2 rounded-full border border-emerald-200 ' +
                'bg-emerald-50 px-3 py-1.5 text-sm text-emerald-800';

            const sousTitre = [village.canton_nom, village.commune_nom]
                .filter(Boolean)
                .join(' · ');

            wrapper.innerHTML = `
                <span>
                    ${escapeHtml(village.nom)}
                    ${sousTitre
                        ? '<span class="text-emerald-600"> · ' + escapeHtml(sousTitre) + '</span>'
                        : ''}
                </span>

                <button type="button"
                        class="text-emerald-600 transition hover:text-red-600"
                        title="Retirer">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-4 w-4"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
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

        if (!campagneId.value) {
            return;
        }

        afficherVillages(
            villagesDeLaCampagneCourante(),
            this.value
        );

    });


    villageSearch.addEventListener('focus', function () {

        if (!campagneId.value) {
            return;
        }

        afficherVillages(
            villagesDeLaCampagneCourante(),
            this.value
        );

    });


    villageToggle.addEventListener('click', function () {

        if (!campagneId.value) {
            return;
        }

        if (!panneauOuvert(villageResultsWrapper)) {

            afficherVillages(
                villagesDeLaCampagneCourante(),
                villageSearch.value
            );

        } else {

            fermerPanneau(villageResultsWrapper, villageToggleIcon);

        }

    });


    /**
     * Bouton « Toute la préfecture ».
     */
    villageSelectAll.addEventListener('click', function () {

        const villages = villagesDeLaCampagneCourante();

        if (villages.length === 0) {
            return;
        }

        villages.forEach(village => {
            ajouterVillage(village);
        });

        fermerPanneau(villageResultsWrapper, villageToggleIcon);

    });


    /* ================================================================
       RESET VILLAGES
    ================================================================= */

    function resetVillages() {

        villagesSelectionnes = [];

        afficherVillagesSelectionnes();

        villageSearch.value = '';

        villageSearch.disabled = true;
        villageToggle.disabled = true;
        villageSelectAll.disabled = true;

        villageSearch.placeholder = 'Sélectionnez d\'abord une campagne...';

        fermerPanneau(villageResultsWrapper, villageToggleIcon);

        villageLoading.classList.add('hidden');
        villageLoading.classList.remove('flex');

        villageHelp.textContent =
            'Sélectionnez d\'abord une campagne pour voir les zones qu\'elle couvre.';

    }


    /* ================================================================
       FERMETURE DES PANNEAUX AU CLIC EXTÉRIEUR
    ================================================================= */

    document.addEventListener('click', function (event) {

        if (
            !campagneSearch.contains(event.target) &&
            !campagneResultsWrapper.contains(event.target) &&
            !campagneToggle.contains(event.target)
        ) {

            fermerPanneau(campagneResultsWrapper, campagneToggleIcon);

        }

        if (
            !villageSearch.contains(event.target) &&
            !villageResultsWrapper.contains(event.target) &&
            !villageToggle.contains(event.target)
        ) {

            fermerPanneau(villageResultsWrapper, villageToggleIcon);

        }

    });


    /* ================================================================
       RESTAURATION DES ANCIENNES VALEURS
    ================================================================= */

    const ancienneCampagneId = campagneId.value;

    const anciensVillages = @json(old('village_ids', []));

    if (ancienneCampagneId) {

        const campagne = campagnes.find(item =>
            String(item.idCampagne) === String(ancienneCampagneId)
        );

        if (campagne) {
            selectionnerCampagne(campagne);
        }

    }

    if (
        Array.isArray(anciensVillages) &&
        anciensVillages.length > 0
    ) {

        anciensVillages.forEach(id => {

            const village = villagesCampagne.find(item => {

                const villageId = item.idVillage ?? item.id;

                return String(villageId) === String(id);

            });

            if (village) {
                ajouterVillage(village);
            }

        });

    }

});
</script>