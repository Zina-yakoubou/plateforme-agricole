@csrf

{{-- ================================================= --}}
{{-- AGENT RECENSEUR --}}
{{-- ================================================= --}}

<div
    x-data="{

        agents: @js($agents),

        selected: @js(
            old(
                'user_id',
                $affectation->user_id
                    ?? $agentSelectionne?->id
                    ?? request()->query('user_id', '')
            )
        ),

        search: '',

        open: false,

        get filteredAgents() {

            if (!this.search) {
                return this.agents;
            }

            const search =
                this.search.toLowerCase();

            return this.agents.filter(agent =>

                (agent.name ?? '')
                    .toLowerCase()
                    .includes(search)

                ||

                (agent.login ?? '')
                    .toLowerCase()
                    .includes(search)

            );
        },

        get selectedAgent() {

            return this.agents.find(
                agent =>
                    String(agent.id)
                    === String(this.selected)
            );
        },

        selectAgent(agent) {

            this.selected = agent.id;

            this.search = agent.name;

            this.open = false;
        }
    }"

    x-init="
        if (selected && selectedAgent) {
            search = selectedAgent.name;
        }
    "

    class="relative mb-6"
>

    <label
        class="block text-sm font-medium
               text-gray-700 mb-2"
    >
        Agent recenseur
        <span class="text-red-500">*</span>
    </label>


    {{-- Recherche --}}

    <input
        type="text"
        x-model="search"
        @focus="open = true"
        @click="open = true"
        @input="open = true"
        @click.outside="open = false"

        placeholder="Rechercher un agent par son nom ou login..."

        autocomplete="off"

        class="w-full rounded-xl
               border-gray-300
               focus:border-green-500
               focus:ring-green-500"
    >


    {{-- ID réel envoyé au serveur --}}

    <input
        type="hidden"
        name="user_id"
        x-model="selected"
    >


    {{-- Liste --}}

    <div
        x-show="open"
        x-cloak

        class="absolute z-50 mt-1 w-full
               bg-white border border-gray-200
               rounded-xl shadow-lg
               max-h-60 overflow-y-auto"
    >

        <template x-if="filteredAgents.length === 0">

            <div class="px-4 py-3 text-sm text-gray-500">

                Aucun agent trouvé.

            </div>

        </template>


        <template
            x-for="agent in filteredAgents"
            :key="agent.id"
        >

            <button
                type="button"

                @click="selectAgent(agent)"

                class="w-full text-left
                       px-4 py-3
                       hover:bg-green-50
                       border-b border-gray-100
                       last:border-0"
            >

                <div
                    class="font-medium text-gray-800"
                    x-text="agent.name"
                ></div>

                <div
                    class="text-xs text-gray-500"

                    x-text="
                        agent.login
                            ? 'Login : ' + agent.login
                            : ''
                    "
                ></div>

            </button>

        </template>

    </div>


    {{-- Agent sélectionné --}}

    <template x-if="selectedAgent">

        <div
            class="mt-3 p-3 rounded-xl
                   bg-green-50
                   border border-green-200"
        >

            <p class="text-xs text-green-600">
                Agent sélectionné
            </p>

            <p
                class="font-semibold text-green-800"
                x-text="selectedAgent.name"
            ></p>

            <p
                class="text-xs text-green-600"
                x-text="
                    selectedAgent.login
                        ? 'Login : ' + selectedAgent.login
                        : ''
                "
            ></p>

        </div>

    </template>


    @error('user_id')

        <p class="mt-1 text-sm text-red-600">
            {{ $message }}
        </p>

    @enderror

</div>


{{-- ================================================= --}}
{{-- CAMPAGNE ACTIVE --}}
{{-- ================================================= --}}

<div class="mb-6">

    <label
        class="block text-sm font-medium
               text-gray-700 mb-2"
    >
        Campagne de recensement
    </label>


    @if($campagneActive)

        <div
            class="flex items-center gap-4
                   p-4 rounded-xl
                   bg-green-50
                   border border-green-200"
        >

            <div
                class="flex-shrink-0
                       w-11 h-11
                       rounded-xl
                       bg-green-100
                       flex items-center justify-center"
            >

                <svg
                    class="w-6 h-6 text-green-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                    />

                </svg>

            </div>


            <div>

                <p
                    class="text-sm font-semibold
                           text-green-800"
                >
                    {{ $campagneActive->libelle }}
                </p>

                <p
                    class="text-xs text-green-600"
                >
                    {{ $campagneActive->codeRNA }}
                    · Campagne active
                </p>

                <p
                    class="mt-1 text-xs
                           text-green-600"
                >
                    Du
                    {{ $campagneActive->dateDebut?->format('d/m/Y') }}
                    au
                    {{ $campagneActive->dateFin?->format('d/m/Y') }}
                </p>

            </div>

        </div>


        <p
            class="mt-2 text-xs text-gray-500"
        >
            La campagne active est automatiquement
            associée à cette affectation.
        </p>

    @else

        <div
            class="p-4 rounded-xl
                   bg-red-50
                   border border-red-200"
        >

            <p
                class="text-sm font-semibold
                       text-red-700"
            >
                Aucune campagne active
            </p>

            <p
                class="mt-1 text-xs text-red-600"
            >
                Une campagne doit être activée avant
                de pouvoir affecter un agent recenseur.
            </p>

        </div>

    @endif


    @error('campagne')

        <p class="mt-1 text-sm text-red-600">
            {{ $message }}
        </p>

    @enderror

</div>


{{-- ================================================= --}}
{{-- VILLAGE --}}
{{-- ================================================= --}}

<div class="mb-6">

    <label
        for="village_id"
        class="block text-sm font-medium
               text-gray-700 mb-2"
    >
        Village
        <span class="text-red-500">*</span>
    </label>


    <select
        name="village_id"
        id="village_id"

        class="w-full rounded-xl
               border-gray-300
               focus:border-green-500
               focus:ring-green-500"
    >

        <option value="">
            Sélectionner un village
        </option>


        @foreach($villages as $item)

            <option
                value="{{ $item->idVillage }}"

                @selected(
                    old(
                        'village_id',
                        $affectation->village_id
                            ?? $village?->idVillage
                            ?? ''
                    )
                    == $item->idVillage
                )
            >

                {{ $item->nom }}

                @if(
                    $item->canton
                    && $item->canton->commune
                    && $item->canton->commune->prefecture
                )

                    —
                    {{ $item->canton->commune->nom }}
                    /
                    {{ $item->canton->nom }}

                @endif

            </option>

        @endforeach

    </select>


    <p class="mt-1 text-xs text-gray-500">
        Sélectionnez le village dans lequel l'agent
        réalisera le recensement.
    </p>


    @error('village_id')

        <p class="mt-1 text-sm text-red-600">
            {{ $message }}
        </p>

    @enderror

</div>


{{-- ================================================= --}}
{{-- STATUT --}}
{{-- ================================================= --}}

{{-- <div class="mb-8">

    <label
        for="statut"
        class="block text-sm font-medium
               text-gray-700 mb-2"
    >
        Statut de l'affectation
    </label>


    <select
        name="statut"
        id="statut"

        class="w-full rounded-xl
               border-gray-300
               focus:border-green-500
               focus:ring-green-500"
    >

        @foreach([
            'ACTIVE' => 'Active',
            'SUSPENDUE' => 'Suspendue',
            'TERMINEE' => 'Terminée'
        ] as $key => $label)

            <option
                value="{{ $key }}"

                @selected(
                    old(
                        'statut',
                        $affectation->statut
                            ?? 'ACTIVE'
                    ) === $key
                )
            >

                {{ $label }}

            </option>

        @endforeach

    </select>


    @error('statut')

        <p class="mt-1 text-sm text-red-600">
            {{ $message }}
        </p>

    @enderror

</div> --}}


{{-- ================================================= --}}
{{-- BOUTONS --}}
{{-- ================================================= --}}

<div
    class="flex items-center justify-end
           gap-3 pt-5
           border-t border-gray-100"
>

    <a
        href="{{ route('affectations.index') }}"

        class="px-5 py-3
               rounded-xl
               bg-gray-100
               text-gray-700
               hover:bg-gray-200
               transition"
    >
        Annuler
    </a>


    <button
        type="submit"

        @disabled(!$campagneActive)

        class="px-5 py-3
               rounded-xl
               bg-green-600
               text-white
               hover:bg-green-700
               disabled:opacity-50
               disabled:cursor-not-allowed
               transition"
    >

        Enregistrer l'affectation

    </button>

</div>