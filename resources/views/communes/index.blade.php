@extends('layouts.app')

@section('content')

{{-- Empêche le flash des modals avant qu'Alpine ne soit initialisé --}}
<style>[x-cloak] { display: none !important; }</style>

<div
    x-data="{
        modal: @if($errors->any() && old('_form') === 'edit') 'edit' @elseif($errors->any()) 'create' @else null @endif,
        selectedCommune: @if($errors->any() && old('_form') === 'edit')
            { id: {{ (int) old('id') }}, nom: @js(old('nom')), code: @js(old('code')), prefecture_id: {{ (int) old('prefecture_id') }} }
        @else
            null
        @endif,

        openModal(type, commune = null) {
            this.modal = type;
            this.selectedCommune = commune;
        },

        closeModal() {
            this.modal = null;
            this.selectedCommune = null;
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
                Gestion des communes
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Liste des communes administratives.
            </p>
        </div>

        {{-- OUVRIR MODAL CRÉATION --}}
        {{-- <button
            type="button"
            @click="openModal('create')"
            class="px-5 py-2 rounded-lg bg-green-600 text-white
                   hover:bg-green-700 transition"
        >
            + Nouvelle commune
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

    <div class="bg-white rounded-xl shadow p-4 mb-6">
        <form method="GET" action="{{ route('communes.index') }}">
            <div class="flex gap-3">
                <input
                    type="text"
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Rechercher une commune..."
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
                        <th class="px-6 py-4 text-left">Nom</th>
                        <th class="px-6 py-4 text-left">Code</th>
                        <th class="px-6 py-4 text-left">Préfecture</th>
                        <th class="px-6 py-4 text-left">Région</th>
                        <th class="px-6 py-4 text-center">Cantons</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse($communes as $commune)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-slate-500">
                                {{ $loop->iteration + ($communes->currentPage() - 1) * $communes->perPage() }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-800">
                                {{ $commune->nom }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $commune->code }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $commune->prefecture->nom ?? '-' }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $commune->prefecture->region->nom ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                {{ $commune->cantons_count ?? $commune->cantons->count() }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">

                                    {{-- VOIR --}}
                                    <a
                                        href="{{ route('communes.show', $commune) }}"
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
                                            id: {{ $commune->id }},
                                            nom: @js($commune->nom),
                                            code: @js($commune->code),
                                            prefecture_id: {{ $commune->prefecture_id }}
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
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                Aucune commune trouvée.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-5">
            {{ $communes->links() }}
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
                    <h2 class="text-lg font-bold text-slate-800">Nouvelle commune</h2>
                    <p class="text-sm text-slate-500">Ajouter une nouvelle commune administrative.</p>
                </div>
                <button type="button" @click="closeModal()" class="text-gray-400 hover:text-gray-700 text-2xl">
                    &times;
                </button>
            </div>

            <form method="POST" action="{{ route('communes.store') }}" class="p-6">
                @csrf
                <input type="hidden" name="_form" value="create">

                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nom de la commune
                    </label>
                    <input
                        type="text"
                        name="nom"
                        value="{{ old('_form') === 'create' ? old('nom') : '' }}"
                        required
                        class="w-full rounded-xl border-gray-300
                               focus:border-green-500
                               focus:ring-green-500"
                        placeholder="Ex : Commune de Lomé"
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
                        placeholder="Ex : LOM"
                    >
                    @error('code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Préfecture
                    </label>
                    <select
                        name="prefecture_id"
                        required
                        class="w-full rounded-xl border-gray-300
                               focus:border-green-500
                               focus:ring-green-500"
                    >
                        <option value="">Sélectionner une préfecture</option>
                        @foreach($prefectures as $prefecture)
                            <option
                                value="{{ $prefecture->idPrefecture }}"
                                @selected(old('_form') === 'create' && old('prefecture_id') == $prefecture->idPrefecture)
                            >
                                {{ $prefecture->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('prefecture_id')
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
                    <h2 class="text-lg font-bold text-slate-800">Modifier la commune</h2>
                    <p class="text-sm text-slate-500">Modifier les informations de la commune.</p>
                </div>
                <button type="button" @click="closeModal()" class="text-gray-400 hover:text-gray-700 text-2xl">
                    &times;
                </button>
            </div>

            <form
                method="POST"
                class="p-6"
                :action="`{{ url('communes') }}/${selectedCommune?.id}`"
            >
                @csrf
                @method('PUT')
                <input type="hidden" name="_form" value="edit">
                <input type="hidden" name="id" :value="selectedCommune?.id">

                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nom de la commune
                    </label>
                    <input
                        type="text"
                        name="nom"
                        x-bind:value="selectedCommune?.nom ?? ''"
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
                        x-bind:value="selectedCommune?.code ?? ''"
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
                        Préfecture
                    </label>
                    <select
                        name="prefecture_id"
                        required
                        x-bind:value="selectedCommune?.prefecture_id ?? ''"
                        class="w-full rounded-xl border-gray-300
                               focus:border-green-500
                               focus:ring-green-500"
                    >
                        <option value="">Sélectionner une préfecture</option>
                        @foreach($prefectures as $prefecture)
                            <option value="{{ $prefecture->idPrefecture }}">
                                {{ $prefecture->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('prefecture_id')
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