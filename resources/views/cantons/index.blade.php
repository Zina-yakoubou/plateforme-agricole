@extends('layouts.app')

@section('content')

{{-- Empêche le flash des modals avant qu'Alpine ne soit initialisé --}}
<style>[x-cloak] { display: none !important; }</style>

<div
    x-data="{
        modal: @if($errors->any() && old('_form') === 'edit') 'edit' @elseif($errors->any()) 'create' @else null @endif,
        selectedCanton: @if($errors->any() && old('_form') === 'edit')
            { id: {{ (int) old('id') }}, nom: @js(old('nom')), code: @js(old('code')), commune_id: {{ (int) old('commune_id') }} }
        @else
            null
        @endif,

        openModal(type, canton = null) {
            this.modal = type;
            this.selectedCanton = canton;
        },

        closeModal() {
            this.modal = null;
            this.selectedCanton = null;
        }
    }"
    @keydown.escape.window="closeModal()"
>

    {{-- ================================================= --}}
    {{-- EN-TÊTE --}}
    {{-- ================================================= --}}

    <div class="flex items-center justify-between mb-6">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Gestion des cantons
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Liste des cantons enregistrés dans les communes.
            </p>
        </div>

        {{-- OUVRIR MODAL CRÉATION --}}
        {{-- <button
            type="button"
            @click="openModal('create')"
            class="px-5 py-2 rounded-lg bg-green-600 text-white
                   hover:bg-green-700 transition"
        >
            + Ajouter un canton
        </button> --}}

    </div>

    {{-- ================================================= --}}
    {{-- MESSAGE SUCCÈS --}}
    {{-- ================================================= --}}

    @if(session('success'))
        <div class="mb-5 rounded-lg bg-green-100 text-green-700 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    {{-- ================================================= --}}
    {{-- ERREURS --}}
    {{-- ================================================= --}}

    @if($errors->any())
        <div class="mb-5 rounded-lg bg-red-100 text-red-700 px-4 py-3">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ================================================= --}}
    {{-- RECHERCHE --}}
    {{-- ================================================= --}}

    <div class="bg-white rounded-xl shadow p-5 mb-6">
        <form method="GET" action="{{ route('cantons.index') }}">
            <div class="flex gap-3">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Rechercher un canton ou une commune..."
                    class="flex-1 rounded-lg border-gray-300
                           focus:border-green-500
                           focus:ring-green-500"
                >
                <button
                    type="submit"
                    class="px-5 py-2 bg-slate-800 text-white
                           rounded-lg hover:bg-slate-700"
                >
                    Rechercher
                </button>
            </div>
        </form>
    </div>

    {{-- ================================================= --}}
    {{-- TABLEAU --}}
    {{-- ================================================= --}}

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-left"> N°</th>
                        <th class="px-6 py-4 text-left">Canton</th>
                        <th class="px-6 py-4 text-left">Code</th>
                        <th class="px-6 py-4 text-left">Commune</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse($cantons as $canton)
                        <tr class="hover:bg-gray-50">

                            <td class="px-6 py-4 text-slate-500">
                                {{ $loop->iteration + ($cantons->currentPage() - 1) * $cantons->perPage() }}
                            </td>

                            <td class="px-6 py-4 font-semibold text-slate-800">
                                {{ $canton->nom }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $canton->code }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $canton->commune->nom ?? '-' }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">

                                    {{-- VOIR --}}
                                    <a
                                        href="{{ route('cantons.show', $canton->idCanton) }}"
                                        class="px-3 py-1 rounded-lg
                                               bg-slate-700 text-white
                                               hover:bg-slate-800"
                                    >
                                        Voir
                                    </a>

                                    {{-- MODIFIER --}}
                                    <button
                                        type="button"
                                        @click="openModal('edit', {
                                            id: {{ $canton->idCanton }},
                                            nom: @js($canton->nom),
                                            code: @js($canton->code),
                                            commune_id: {{ $canton->commune_id }}
                                        })"
                                        class="px-3 py-1 rounded-lg
                                               bg-blue-600 text-white
                                               hover:bg-blue-700"
                                    >
                                        Modifier
                                    </button>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                Aucun canton trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-5">
            {{ $cantons->links() }}
        </div>
    </div>

    {{-- ================================================= --}}
    {{-- MODAL CRÉATION --}}
    {{-- ================================================= --}}

    <div
        x-show="modal === 'create'"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
    >
        <div class="absolute inset-0 bg-black/50" @click="closeModal()"></div>

        <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl" @click.stop>

            <div class="flex items-center justify-between px-6 py-4 border-b">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Nouveau canton</h2>
                    <p class="text-sm text-slate-500">Ajouter un nouveau canton.</p>
                </div>
                <button type="button" @click="closeModal()" class="text-gray-400 hover:text-gray-700 text-xl">
                    &times;
                </button>
            </div>

            <form method="POST" action="{{ route('cantons.store') }}" class="p-6">
                @csrf
                <input type="hidden" name="_form" value="create">

                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nom du canton
                    </label>
                    <input
                        type="text"
                        name="nom"
                        value="{{ old('_form') === 'create' ? old('nom') : '' }}"
                        required
                        class="w-full rounded-xl border-gray-300
                               focus:border-green-500
                               focus:ring-green-500"
                        placeholder="Ex : Canton d'Agoè"
                    >
                    @error('nom')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Code
                    </label>
                    <input
                        type="text"
                        name="code"
                        value="{{ old('_form') === 'create' ? old('code') : '' }}"
                        required
                        class="w-full rounded-xl border-gray-300
                               focus:border-green-500
                               focus:ring-green-500"
                        placeholder="Ex : AGO"
                    >
                    @error('code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Commune
                    </label>
                    <select
                        name="commune_id"
                        required
                        class="w-full rounded-xl border-gray-300
                               focus:border-green-500
                               focus:ring-green-500"
                    >
                        <option value="">Sélectionner une commune</option>
                        @foreach($communes as $commune)
                            <option
                                value="{{ $commune->idCommune }}"
                                @selected(old('_form') === 'create' && old('commune_id') == $commune->idCommune)
                            >
                                {{ $commune->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('commune_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3">
                    <button
                        type="button"
                        @click="closeModal()"
                        class="px-5 py-2 rounded-xl bg-gray-200 text-gray-700 hover:bg-gray-300"
                    >
                        Annuler
                    </button>
                    <button
                        type="submit"
                        class="px-5 py-2 rounded-xl bg-green-600 text-white hover:bg-green-700"
                    >
                        Enregistrer
                    </button>
                </div>
            </form>

        </div>
    </div>

    {{-- ================================================= --}}
    {{-- MODAL MODIFICATION --}}
    {{-- ================================================= --}}

    <div
        x-show="modal === 'edit'"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
    >
        <div class="absolute inset-0 bg-black/50" @click="closeModal()"></div>

        <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl" @click.stop>

            <div class="flex items-center justify-between px-6 py-4 border-b">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Modifier le canton</h2>
                    <p class="text-sm text-slate-500">Modifier les informations du canton.</p>
                </div>
                <button type="button" @click="closeModal()" class="text-gray-400 hover:text-gray-700 text-xl">
                    &times;
                </button>
            </div>

            <form
                method="POST"
                class="p-6"
                :action="`{{ url('cantons') }}/${selectedCanton?.id}`"
            >
                @csrf
                @method('PUT')
                <input type="hidden" name="_form" value="edit">
                <input type="hidden" name="id" :value="selectedCanton?.id">

                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nom du canton
                    </label>
                    <input
                        type="text"
                        name="nom"
                        x-bind:value="selectedCanton?.nom ?? ''"
                        required
                        class="w-full rounded-xl border-gray-300
                               focus:border-green-500
                               focus:ring-green-500"
                    >
                    @error('nom')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Code
                    </label>
                    <input
                        type="text"
                        name="code"
                        x-bind:value="selectedCanton?.code ?? ''"
                        required
                        class="w-full rounded-xl border-gray-300
                               focus:border-green-500
                               focus:ring-green-500"
                    >
                    @error('code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Commune
                    </label>
                    <select
                        name="commune_id"
                        required
                        x-bind:value="selectedCanton?.commune_id ?? ''"
                        class="w-full rounded-xl border-gray-300
                               focus:border-green-500
                               focus:ring-green-500"
                    >
                        <option value="">Sélectionner une commune</option>
                        @foreach($communes as $commune)
                            <option value="{{ $commune->idCommune }}">
                                {{ $commune->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('commune_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3">
                    <button
                        type="button"
                        @click="closeModal()"
                        class="px-5 py-2 rounded-xl bg-gray-200 text-gray-700 hover:bg-gray-300"
                    >
                        Annuler
                    </button>
                    <button
                        type="submit"
                        class="px-5 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700"
                    >
                        Enregistrer les modifications
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>

@endsection