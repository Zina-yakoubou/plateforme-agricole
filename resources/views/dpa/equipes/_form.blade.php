@csrf

@if(isset($equipe))
    @method('PUT')
@endif

@php
    /*
    |--------------------------------------------------------------------------
    | Valeurs sélectionnées
    |--------------------------------------------------------------------------
    */

    $superviseurSelectionne = old(
        'superviseur_id',
        $equipe->superviseur_id ?? null
    );

    $membresSelectionnes = old(
        'membres',
        isset($equipe)
            ? $equipe->membres->pluck('id')->toArray()
            : []
    );

    $membresSelectionnes = array_map(
        'strval',
        $membresSelectionnes ?? []
    );
@endphp


<div
    class="space-y-8"
    x-data="{
        superviseurSearch: '',
        agentSearch: '',

        superviseurOpen: false,
        agentsOpen: false,

        superviseur: '{{ $superviseurSelectionne }}',

        membres: @js($membresSelectionnes),

        agents: @js(
            $agents->map(fn ($agent) => [
                'id' => (string) $agent->id,
                'name' => $agent->name,
                'telephone' => $agent->telephone,
            ])->values()
        ),

        superviseurs: @js(
            $superviseurs->map(fn ($superviseur) => [
                'id' => (string) $superviseur->id,
                'name' => $superviseur->name,
                'telephone' => $superviseur->telephone,
            ])->values()
        ),

        get superviseurSelectionne() {
            return this.superviseurs.find(
                item => item.id === String(this.superviseur)
            );
        },

        get superviseursFiltres() {
            const search = this.superviseurSearch
                .toLowerCase()
                .trim();

            if (!search) {
                return this.superviseurs;
            }

            return this.superviseurs.filter(item =>
                item.name.toLowerCase().includes(search) ||
                (item.telephone ?? '').toLowerCase().includes(search)
            );
        },

        get agentsFiltres() {
            const search = this.agentSearch
                .toLowerCase()
                .trim();

            if (!search) {
                return this.agents;
            }

            return this.agents.filter(item =>
                item.name.toLowerCase().includes(search) ||
                (item.telephone ?? '').toLowerCase().includes(search)
            );
        },

        estMembre(id) {
            return this.membres.includes(String(id));
        },

        ajouterMembre(id) {
            id = String(id);

            if (!this.membres.includes(id)) {
                this.membres.push(id);
            }
        },

        retirerMembre(id) {
            id = String(id);

            this.membres = this.membres.filter(
                membre => membre !== id
            );
        },

        toggleMembre(id) {
            id = String(id);

            if (this.estMembre(id)) {
                this.retirerMembre(id);
            } else {
                this.ajouterMembre(id);
            }
        },

        get membresSelectionnes() {
            return this.agents.filter(agent =>
                this.membres.includes(String(agent.id))
            );
        }
    }"
    @click.outside="
        superviseurOpen = false;
        agentsOpen = false;
    "
>

    {{-- =========================================================
         INFORMATIONS GÉNÉRALES
    ========================================================== --}}

    <div>

        <div class="mb-5">
            <h3 class="text-base font-semibold text-[#212529]">
                Informations de l'équipe
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Identifiez l'équipe et désignez son responsable.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

            {{-- RÉFÉRENCE --}}
            {{-- <div>

                <label
                    for="reference"
                    class="block text-sm font-medium text-gray-700"
                >
                    Référence
                    <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    name="reference"
                    id="reference"
                    value="{{ old('reference', $equipe->reference ?? '') }}"
                    placeholder="Ex. EQ-MO-001"
                    class="mt-2 block w-full rounded-lg
                           border border-gray-300
                           bg-white
                           px-4 py-3
                           text-sm text-gray-800
                           placeholder:text-gray-400
                           focus:border-[#006a4f]
                           focus:outline-none
                           focus:ring-2
                           focus:ring-[#006a4f]/10"
                >

                @error('reference')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div> --}}


            {{-- NOM --}}
            <div>

                <label
                    for="nom"
                    class="block text-sm font-medium text-gray-700"
                >
                    Nom de l'équipe
                    <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    name="nom"
                    id="nom"
                    value="{{ old('nom', $equipe->nom ?? '') }}"
                    placeholder="Ex. Équipe terrain 01"
                    class="mt-2 block w-full rounded-lg
                           border border-gray-300
                           bg-white
                           px-4 py-3
                           text-sm text-gray-800
                           placeholder:text-gray-400
                           focus:border-[#006a4f]
                           focus:outline-none
                           focus:ring-2
                           focus:ring-[#006a4f]/10"
                >

                @error('nom')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>

        </div>

    </div>


    {{-- =========================================================
         SUPERVISEUR
    ========================================================== --}}

    <div class="border-t border-gray-200 pt-7">

        <div class="mb-5">

            <h3 class="text-base font-semibold text-[#212529]">
                Responsable de l'équipe
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Un superviseur peut être responsable de plusieurs équipes.
            </p>

        </div>


        <div class="relative">

            <label
                for="superviseur_search"
                class="block text-sm font-medium text-gray-700"
            >
                Superviseur
                <span class="text-red-500">*</span>
            </label>


            {{-- Valeur réellement envoyée au serveur --}}
            <input
                type="hidden"
                name="superviseur_id"
                :value="superviseur"
            >


            {{-- Champ de recherche --}}
            <div class="relative mt-2">

                <div
                    class="pointer-events-none absolute inset-y-0 left-0
                           flex items-center pl-4 text-gray-400"
                >

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
                            d="M21 21l-4.35-4.35m2.1-5.4a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z"
                        />
                    </svg>

                </div>


                <input
                    type="text"
                    id="superviseur_search"
                    x-model="superviseurSearch"
                    @focus="superviseurOpen = true"
                    @input="superviseurOpen = true"
                    autocomplete="off"
                    placeholder="Rechercher un superviseur..."
                    class="block w-full rounded-lg
                           border border-gray-300
                           bg-white
                           py-3 pl-11 pr-10
                           text-sm text-gray-800
                           placeholder:text-gray-400
                           focus:border-[#006a4f]
                           focus:outline-none
                           focus:ring-2
                           focus:ring-[#006a4f]/10"
                >


                {{-- Icône --}}
                <div
                    class="pointer-events-none absolute inset-y-0 right-0
                           flex items-center pr-4"
                >

                    <svg
                        class="h-4 w-4 text-gray-400"
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

                </div>

            </div>


            {{-- Superviseur sélectionné --}}
            <template x-if="superviseurSelectionne">

                <div class="mt-3 flex items-center justify-between
                            rounded-lg border border-[#cce5dc]
                            bg-[#e5f2ee] px-4 py-3">

                    <div class="flex min-w-0 items-center gap-3">

                        <div
                            class="flex h-9 w-9 shrink-0 items-center
                                   justify-center rounded-full
                                   bg-[#006a4f] text-sm
                                   font-semibold text-white"
                        >
                            <span
                                x-text="superviseurSelectionne.name.charAt(0).toUpperCase()"
                            ></span>
                        </div>

                        <div class="min-w-0">

                            <p
                                class="truncate text-sm font-semibold text-[#006a4f]"
                                x-text="superviseurSelectionne.name"
                            ></p>

                            <p
                                x-show="superviseurSelectionne.telephone"
                                class="text-xs text-gray-500"
                                x-text="superviseurSelectionne.telephone"
                            ></p>

                        </div>

                    </div>


                    <button
                        type="button"
                        @click="
                            superviseur = '';
                            superviseurSearch = '';
                        "
                        class="ml-3 flex h-7 w-7 shrink-0
                               items-center justify-center
                               rounded-full text-gray-500
                               transition hover:bg-white
                               hover:text-red-600"
                        title="Retirer le superviseur"
                    >
                        ×
                    </button>

                </div>

            </template>


            {{-- Liste des superviseurs --}}
            <div
                x-show="superviseurOpen"
                x-transition
                class="absolute z-30 mt-2 w-full overflow-hidden
                       rounded-lg border border-gray-200
                       bg-white shadow-lg"
            >

                <div
                    class="max-h-64 overflow-y-auto p-1"
                >

                    <template
                        x-for="item in superviseursFiltres"
                        :key="item.id"
                    >

                        <button
                            type="button"
                            @click="
                                superviseur = item.id;
                                superviseurSearch = item.name;
                                superviseurOpen = false;
                            "
                            class="flex w-full items-center gap-3
                                   rounded-md px-3 py-3 text-left
                                   transition hover:bg-[#e5f2ee]"
                        >

                            <div
                                class="flex h-9 w-9 shrink-0
                                       items-center justify-center
                                       rounded-full bg-gray-100
                                       text-xs font-semibold
                                       text-gray-600"
                            >
                                <span
                                    x-text="item.name.charAt(0).toUpperCase()"
                                ></span>
                            </div>

                            <div class="min-w-0 flex-1">

                                <p
                                    class="truncate text-sm font-medium text-gray-800"
                                    x-text="item.name"
                                ></p>

                                <p
                                    x-show="item.telephone"
                                    class="truncate text-xs text-gray-400"
                                    x-text="item.telephone"
                                ></p>

                            </div>


                            <svg
                                x-show="superviseur === item.id"
                                class="h-5 w-5 shrink-0 text-[#006a4f]"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>

                        </button>

                    </template>


                    {{-- Aucun résultat --}}
                    <div
                        x-show="superviseursFiltres.length === 0"
                        class="px-4 py-8 text-center"
                    >

                        <svg
                            class="mx-auto h-8 w-8 text-gray-300"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M21 21l-4.35-4.35m2.1-5.4a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z"
                            />
                        </svg>

                        <p class="mt-2 text-sm text-gray-500">
                            Aucun superviseur trouvé.
                        </p>

                    </div>

                </div>

            </div>


            @error('superviseur_id')
                <p class="mt-2 text-xs text-red-600">
                    {{ $message }}
                </p>
            @enderror

        </div>

    </div>


    {{-- =========================================================
         AGENTS RECENSEURS
    ========================================================== --}}

    <div class="border-t border-gray-200 pt-7">

        <div class="mb-5">

            <div class="flex items-center justify-between gap-4">

                <div>

                    <h3 class="text-base font-semibold text-[#212529]">
                        Composition de l'équipe
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Recherchez puis sélectionnez un ou plusieurs agents recenseurs.
                    </p>

                </div>


                {{-- Nombre sélectionné --}}
                <div
                    x-show="membres.length > 0"
                    class="shrink-0 rounded-full bg-[#e5f2ee]
                           px-3 py-1 text-xs font-semibold
                           text-[#006a4f]"
                >
                    <span x-text="membres.length"></span>
                    <span> sélectionné(s)</span>
                </div>

            </div>

        </div>


        <div class="relative">

            {{-- =====================================================
                 CHAMP DE RECHERCHE
            ====================================================== --}}

            <div class="relative">

                <div
                    class="pointer-events-none absolute inset-y-0 left-0
                           flex items-center pl-4 text-gray-400"
                >

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
                            d="M21 21l-4.35-4.35m2.1-5.4a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z"
                        />
                    </svg>

                </div>


                <input
                    type="text"
                    id="agent_search"
                    x-model="agentSearch"
                    @focus="agentsOpen = true"
                    @input="agentsOpen = true"
                    autocomplete="off"
                    placeholder="Rechercher un agent par nom ou téléphone..."
                    class="block w-full rounded-lg
                           border border-gray-300
                           bg-white
                           py-3 pl-11 pr-10
                           text-sm text-gray-800
                           placeholder:text-gray-400
                           focus:border-[#006a4f]
                           focus:outline-none
                           focus:ring-2
                           focus:ring-[#006a4f]/10"
                >

            </div>


            {{-- =====================================================
                 AGENTS SÉLECTIONNÉS
            ====================================================== --}}

            <div
                x-show="membresSelectionnes.length > 0"
                class="mt-3 rounded-lg border border-[#cce5dc]
                       bg-[#f8fcfa] p-3"
            >

                <div class="flex flex-wrap gap-2">

                    <template
                        x-for="agent in membresSelectionnes"
                        :key="agent.id"
                    >

                        <div
                            class="inline-flex items-center gap-2
                                   rounded-full border border-[#cce5dc]
                                   bg-[#e5f2ee]
                                   py-1.5 pl-2.5 pr-1.5"
                        >

                            <div
                                class="flex h-6 w-6 items-center
                                       justify-center rounded-full
                                       bg-[#006a4f] text-[10px]
                                       font-semibold text-white"
                            >
                                <span
                                    x-text="agent.name.charAt(0).toUpperCase()"
                                ></span>
                            </div>

                            <span
                                class="max-w-[180px] truncate text-xs
                                       font-medium text-[#006a4f]"
                                x-text="agent.name"
                            ></span>

                            <button
                                type="button"
                                @click="retirerMembre(agent.id)"
                                class="flex h-5 w-5 items-center
                                       justify-center rounded-full
                                       text-[#006a4f]
                                       transition hover:bg-[#006a4f]
                                       hover:text-white"
                                title="Retirer"
                            >
                                ×
                            </button>

                        </div>

                    </template>

                </div>

            </div>


            {{-- =====================================================
                 LISTE DES AGENTS
            ====================================================== --}}

            <div
                x-show="agentsOpen"
                x-transition
                class="absolute z-30 mt-2 w-full overflow-hidden
                       rounded-lg border border-gray-200
                       bg-white shadow-lg"
            >

                <div class="border-b border-gray-100 px-4 py-2">

                    <p class="text-xs text-gray-400">
                        Cliquez sur un agent pour l'ajouter à l'équipe.
                    </p>

                </div>


                <div class="max-h-72 overflow-y-auto p-1">

                    <template
                        x-for="agent in agentsFiltres"
                        :key="agent.id"
                    >

                        <button
                            type="button"
                            @click="toggleMembre(agent.id)"
                            class="flex w-full items-center gap-3
                                   rounded-md px-3 py-3 text-left
                                   transition hover:bg-[#e5f2ee]"
                        >

                            {{-- Avatar --}}
                            <div
                                class="flex h-9 w-9 shrink-0
                                       items-center justify-center
                                       rounded-full"
                                :class="
                                    estMembre(agent.id)
                                        ? 'bg-[#006a4f] text-white'
                                        : 'bg-gray-100 text-gray-600'
                                "
                            >
                                <span
                                    class="text-xs font-semibold"
                                    x-text="agent.name.charAt(0).toUpperCase()"
                                ></span>
                            </div>


                            {{-- Informations --}}
                            <div class="min-w-0 flex-1">

                                <p
                                    class="truncate text-sm font-medium text-gray-800"
                                    x-text="agent.name"
                                ></p>

                                <p
                                    x-show="agent.telephone"
                                    class="truncate text-xs text-gray-400"
                                    x-text="agent.telephone"
                                ></p>

                            </div>


                            {{-- Checkbox visuelle --}}
                            <div
                                class="flex h-5 w-5 shrink-0
                                       items-center justify-center
                                       rounded border"
                                :class="
                                    estMembre(agent.id)
                                        ? 'border-[#006a4f] bg-[#006a4f]'
                                        : 'border-gray-300 bg-white'
                                "
                            >

                                <svg
                                    x-show="estMembre(agent.id)"
                                    class="h-3.5 w-3.5 text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>

                            </div>

                        </button>

                    </template>


                    {{-- Aucun agent --}}
                    <div
                        x-show="agentsFiltres.length === 0"
                        class="px-4 py-8 text-center"
                    >

                        @if($agents->count())

                            <svg
                                class="mx-auto h-8 w-8 text-gray-300"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M21 21l-4.35-4.35m2.1-5.4a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z"
                                />
                            </svg>

                            <p class="mt-2 text-sm text-gray-500">
                                Aucun agent trouvé.
                            </p>

                            <p class="mt-1 text-xs text-gray-400">
                                Essayez avec un autre nom ou numéro.
                            </p>

                        @else

                            <svg
                                class="mx-auto h-8 w-8 text-gray-300"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m8-10a4 4 0 100-8 4 4 0 000 8zm6 2a4 4 0 00-4 4v2m4-6a3 3 0 100-6"
                                />
                            </svg>

                            <p class="mt-2 text-sm text-gray-500">
                                Aucun agent recenseur disponible.
                            </p>

                        @endif

                    </div>

                </div>

            </div>


            {{-- Inputs cachés pour Laravel --}}
            <template
                x-for="id in membres"
                :key="id"
            >

                <input
                    type="hidden"
                    name="membres[]"
                    :value="id"
                >

            </template>


            <p class="mt-2 text-xs text-gray-500">
                Vous pouvez sélectionner plusieurs agents. Les agents sélectionnés
                apparaissent au-dessus et peuvent être retirés avec « × ».
            </p>


            @error('membres')
                <p class="mt-2 text-xs text-red-600">
                    {{ $message }}
                </p>
            @enderror

            @error('membres.*')
                <p class="mt-2 text-xs text-red-600">
                    {{ $message }}
                </p>
            @enderror

        </div>

    </div>


    {{-- =========================================================
         STATUT — UNIQUEMENT EN MODIFICATION
    ========================================================== --}}

    @if(isset($equipe))

        <div class="border-t border-gray-200 pt-7">

            <div class="mb-5">

                <h3 class="text-base font-semibold text-[#212529]">
                    État de l'équipe
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Activez ou désactivez cette équipe.
                </p>

            </div>


            <div>

                <label
                    for="statut"
                    class="block text-sm font-medium text-gray-700"
                >
                    Statut
                </label>

                <select
                    name="statut"
                    id="statut"
                    class="mt-2 block w-full rounded-lg
                           border border-gray-300
                           bg-white
                           px-4 py-3
                           text-sm text-gray-800
                           focus:border-[#006a4f]
                           focus:outline-none
                           focus:ring-2
                           focus:ring-[#006a4f]/10"
                >

                    <option
                        value="ACTIVE"
                        @selected(
                            old('statut', $equipe->statut) === 'ACTIVE'
                        )
                    >
                        Active
                    </option>

                    <option
                        value="INACTIVE"
                        @selected(
                            old('statut', $equipe->statut) === 'INACTIVE'
                        )
                    >
                        Inactive
                    </option>

                </select>

                @error('statut')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>

        </div>

    @endif

</div>