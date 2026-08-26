@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">
            {{ isset($affectation) ? "Modifier l'affectation" : 'Nouvelle affectation' }}
        </h1>
        <p class="text-sm text-slate-500 mt-1">
            {{ isset($affectation) ? "Modifier les informations de l'affectation." : 'Affecter un agent recenseur à un village pour une campagne.' }}
        </p>
    </div>

    <div class="bg-white rounded-2xl shadow p-8">

        <form
            method="POST"
            action="{{ isset($affectation) ? route('affectations.update', $affectation) : route('affectations.store') }}"
        >

            @csrf

            @if(isset($affectation))
                @method('PUT')
            @endif

            {{-- ================================================= --}}
            {{-- AGENT RECENSEUR --}}
            {{-- ================================================= --}}

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Agent recenseur
                </label>

                <div
                    x-data="{
                        open: false,
                        search: '',
                        selected: @js(old('user_id', $affectation->user_id ?? '')),
                        agents: @js($agents),
                        get filteredAgents() {
                            const search = this.search.toLowerCase().trim();

                            if (!search) {
                                return this.agents;
                            }

                            return this.agents.filter(agent =>
                                (agent.name ?? '').toLowerCase().includes(search) ||
                                (agent.login ?? '').toLowerCase().includes(search)
                            );
                        },
                        get selectedAgent() {
                            return this.agents.find(agent => String(agent.id) === String(this.selected));
                        },
                        selectAgent(agent) {
                            this.selected = agent.id;
                            this.search = agent.name;
                            this.open = false;
                        }
                    }"
                    class="relative"
                >

                    <input
                        type="text"
                        x-model="search"
                        @focus="open = true"
                        @click="open = true"
                        @input="open = true"
                        @click.outside="open = false"
                        placeholder="Rechercher un agent..."
                        autocomplete="off"
                        class="w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500"
                    >

                    <input
                        type="hidden"
                        name="user_id"
                        x-model="selected"
                    >

                    {{-- Liste des agents --}}
                    <div
                        x-show="open"
                        x-cloak
                        class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-xl shadow-lg max-h-60 overflow-y-auto"
                    >

                        <template x-if="filteredAgents.length === 0">
                            <div class="px-4 py-3 text-sm text-gray-500">
                                Aucun agent trouvé.
                            </div>
                        </template>

                        <template x-for="agent in filteredAgents" :key="agent.id">

                            <button
                                type="button"
                                @click="selectAgent(agent)"
                                class="w-full text-left px-4 py-3 hover:bg-green-50 border-b border-gray-100 last:border-0"
                            >

                                <div
                                    class="font-medium text-gray-800"
                                    x-text="agent.name"
                                ></div>

                                <div
                                    class="text-xs text-gray-500"
                                    x-text="agent.login ? 'Login : ' + agent.login : ''"
                                ></div>

                            </button>

                        </template>

                    </div>

                    {{-- Agent sélectionné --}}
                    <template x-if="selectedAgent">
                        <p class="mt-2 text-xs text-green-600">
                            Agent sélectionné :
                            <span
                                class="font-semibold"
                                x-text="selectedAgent.name"
                            ></span>
                        </p>
                    </template>

                </div>

                @error('user_id')
                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            {{-- ================================================= --}}
            {{-- CAMPAGNE --}}
            {{-- ================================================= --}}

            <div class="mt-6">

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Campagne
                </label>

                <select
                    name="campagne_id"
                    class="w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500"
                >

                    <option value="">
                        Sélectionner une campagne
                    </option>

                    @foreach($campagnes as $campagne)

                        <option
                            value="{{ $campagne->idCampagne }}"
                            @selected(
                                old(
                                    'campagne_id',
                                    $affectation->campagne_id ?? ''
                                ) == $campagne->idCampagne
                            )
                        >
                            {{ $campagne->nom }}
                        </option>

                    @endforeach

                </select>

                @error('campagne_id')
                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            {{-- ================================================= --}}
            {{-- VILLAGE --}}
            {{-- ================================================= --}}

            <div class="mt-6">

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Village
                </label>

                <select
                    name="village_id"
                    class="w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500"
                >

                    <option value="">
                        Sélectionner un village
                    </option>

                    @foreach($villages as $village)

                        <option
                            value="{{ $village->idVillage }}"
                            @selected(
                                old(
                                    'village_id',
                                    $affectation->village_id ?? ''
                                ) == $village->idVillage
                            )
                        >
                            {{ $village->nom }}
                        </option>

                    @endforeach

                </select>

                @error('village_id')
                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            {{-- ================================================= --}}
            {{-- STATUT --}}
            {{-- ================================================= --}}

            <div class="mt-6">

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Statut
                </label>

                <select
                    name="statut"
                    class="w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500"
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
                                    $affectation->statut ?? 'ACTIVE'
                                ) == $key
                            )
                        >
                            {{ $label }}
                        </option>

                    @endforeach

                </select>

                @error('statut')
                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            {{-- ================================================= --}}
            {{-- BOUTONS --}}
            {{-- ================================================= --}}

            <div class="mt-8 flex justify-end gap-3">
                <a
                
                    href="{{ route('affectations.index') }}"
                    class="px-5 py-3 rounded-xl bg-gray-200 text-gray-700 hover:bg-gray-300"
                >
                    Annuler
                </a>

                <button
                    type="submit"
                    class="px-5 py-3 rounded-xl bg-green-600 text-white hover:bg-green-700"
                >
                    {{ isset($affectation) ? 'Modifier' : 'Enregistrer' }}
                </button>

            </div>

        </form>

    </div>

</div>

@endsection