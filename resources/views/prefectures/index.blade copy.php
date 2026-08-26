@extends('layouts.app')

@section('content')

{{-- ================================================= --}}
{{-- EN-TÊTE --}}
{{-- ================================================= --}}

<div class="flex items-center justify-between mb-6">

    <div>

        <h1 class="text-2xl font-bold text-slate-800">
            Gestion des préfectures
        </h1>

        <p class="text-sm text-slate-500 mt-1">
            Liste des préfectures administratives.
        </p>

    </div>

    <a href="{{ route('prefectures.create') }}"
       class="px-5 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">

        + Nouvelle préfecture

    </a>

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
{{-- TABLEAU --}}
{{-- ================================================= --}}

<div class="bg-white rounded-xl shadow overflow-hidden">

    {{-- Recherche --}}
    <div class="p-5 border-b">

        <form method="GET"
              action="{{ route('prefectures.index') }}">

            <div class="flex gap-3">

                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Rechercher une préfecture..."
                    class="flex-1 rounded-lg border-gray-300
                           focus:border-green-500 focus:ring-green-500">

                <button
                    type="submit"
                    class="px-5 py-2 bg-slate-800 text-white rounded-lg
                           hover:bg-slate-700">

                    Rechercher

                </button>

            </div>

        </form>

    </div>


    {{-- Tableau --}}
    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-slate-100">

                <tr>

                    <th class="px-6 py-4 text-left">
                        Nom
                    </th>

                    <th class="px-6 py-4 text-left">
                        Code
                    </th>

                    <th class="px-6 py-4 text-left">
                        Région
                    </th>

                    <th class="px-6 py-4 text-center">
                        Communes
                    </th>

                    <th class="px-6 py-4 text-center">
                        Actions
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y">

                @forelse($prefectures as $prefecture)

                    <tr class="hover:bg-gray-50">

                        {{-- Nom --}}
                        <td class="px-6 py-4 font-semibold text-slate-800">

                            {{ $prefecture->nom }}

                        </td>


                        {{-- Code --}}
                        <td class="px-6 py-4">

                            {{ $prefecture->code }}

                        </td>


                        {{-- Région --}}
                        <td class="px-6 py-4">

                            {{ $prefecture->region->nom ?? '-' }}

                        </td>


                        {{-- Communes --}}
                        <td class="px-6 py-4 text-center">

                            {{ $prefecture->communes->count() }}

                        </td>


                        {{-- Actions --}}
                        <td class="px-6 py-4">

                            <div class="flex justify-center gap-2">

                                {{-- Voir --}}
                                <a href="{{ route(
                                    'prefectures.show',
                                    $prefecture->idPrefecture
                                ) }}"
                                   class="px-3 py-1 rounded-lg
                                          bg-slate-700 text-white
                                          hover:bg-slate-800">

                                    Voir

                                </a>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5"
                            class="px-6 py-8 text-center text-gray-500">

                            Aucune préfecture trouvée.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- Pagination --}}
    <div class="p-5">

        {{ $prefectures->links() }}

    </div>

</div>



{{-- ========================================================= --}}
{{-- MODAL : AJOUTER UNE PRÉFECTURE --}}
{{-- ========================================================= --}}

<div
    id="createPrefectureModal"
    class="hidden fixed inset-0 z-50 flex items-center justify-center"
>

    {{-- Fond sombre --}}
    <div
        class="absolute inset-0 bg-black/50"
        onclick="closeCreatePrefectureModal()"
    ></div>


    {{-- Fenêtre --}}
    <div
        class="relative w-full max-w-lg mx-4 bg-white rounded-2xl shadow-2xl"
    >

        {{-- En-tête --}}
        <div class="flex items-center justify-between px-6 py-4 border-b">

            <div>

                <h2 class="text-xl font-bold text-slate-800">
                    Nouvelle préfecture
                </h2>

                <p class="text-sm text-slate-500">
                    Ajouter une préfecture administrative.
                </p>

            </div>

            <button
                type="button"
                onclick="closeCreatePrefectureModal()"
                class="text-gray-400 hover:text-gray-700 text-2xl"
            >
                &times;
            </button>

        </div>


        {{-- Formulaire --}}
        <form
            method="POST"
            action="{{ route('prefectures.store') }}"
            class="p-6 space-y-5"
        >

            @csrf


            {{-- Région --}}
            <div>

                <label
                    for="create_region_id"
                    class="block text-sm font-medium text-gray-700 mb-2"
                >
                    Région
                </label>

                <select
                    id="create_region_id"
                    name="region_id"
                    class="w-full rounded-xl border-gray-300
                           focus:border-green-500 focus:ring-green-500"
                >

                    <option value="">
                        Sélectionner une région
                    </option>

                    @foreach($regions as $region)

                        <option
                            value="{{ $region->idRegion }}"
                            @selected(
                                old(
                                    'region_id',
                                    $regionSelectionnee?->idRegion
                                ) == $region->idRegion
                            )
                        >
                            {{ $region->nom }}
                        </option>

                    @endforeach

                </select>

                @error('region_id')
                    <p class="text-sm text-red-600 mt-1">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Nom --}}
            <div>

                <label
                    for="create_nom"
                    class="block text-sm font-medium text-gray-700 mb-2"
                >
                    Nom de la préfecture
                </label>

                <input
                    type="text"
                    id="create_nom"
                    name="nom"
                    value="{{ old('nom') }}"
                    class="w-full rounded-xl border-gray-300
                           focus:border-green-500 focus:ring-green-500"
                    placeholder="Exemple : Mô"
                >

                @error('nom')
                    <p class="text-sm text-red-600 mt-1">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Code --}}
            <div>

                <label
                    for="create_code"
                    class="block text-sm font-medium text-gray-700 mb-2"
                >
                    Code
                </label>

                <input
                    type="text"
                    id="create_code"
                    name="code"
                    value="{{ old('code') }}"
                    class="w-full rounded-xl border-gray-300
                           focus:border-green-500 focus:ring-green-500"
                    placeholder="Exemple : MO"
                >

                @error('code')
                    <p class="text-sm text-red-600 mt-1">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Boutons --}}
            <div class="flex justify-end gap-3 pt-4 border-t">

                <button
                    type="button"
                    onclick="closeCreatePrefectureModal()"
                    class="px-5 py-2 rounded-xl bg-gray-200
                           text-gray-700 hover:bg-gray-300"
                >
                    Annuler
                </button>

                <button
                    type="submit"
                    class="px-5 py-2 rounded-xl bg-green-600
                           text-white hover:bg-green-700"
                >
                    Enregistrer
                </button>

            </div>

        </form>

    </div>

</div>





{{-- ========================================================= --}}
{{-- MODAL : MODIFIER UNE PRÉFECTURE --}}
{{-- ========================================================= --}}

<div
    id="editPrefectureModal"
    class="hidden fixed inset-0 z-50 flex items-center justify-center"
>

    {{-- Fond --}}
    <div
        class="absolute inset-0 bg-black/50"
        onclick="closeEditPrefectureModal()"
    ></div>


    {{-- Fenêtre --}}
    <div
        class="relative w-full max-w-lg mx-4 bg-white rounded-2xl shadow-2xl"
    >

        {{-- En-tête --}}
        <div class="flex items-center justify-between px-6 py-4 border-b">

            <div>

                <h2 class="text-xl font-bold text-slate-800">
                    Modifier la préfecture
                </h2>

                <p class="text-sm text-slate-500">
                    Modifier les informations administratives.
                </p>

            </div>

            <button
                type="button"
                onclick="closeEditPrefectureModal()"
                class="text-gray-400 hover:text-gray-700 text-2xl"
            >
                &times;
            </button>

        </div>


        {{-- Formulaire --}}
        <form
            id="editPrefectureForm"
            method="POST"
            class="p-6 space-y-5"
        >

            @csrf

            @method('PUT')


            {{-- Région --}}
            <div>

                <label
                    for="edit_region_id"
                    class="block text-sm font-medium text-gray-700 mb-2"
                >
                    Région
                </label>

                <select
                    id="edit_region_id"
                    name="region_id"
                    class="w-full rounded-xl border-gray-300
                           focus:border-green-500 focus:ring-green-500"
                >

                    @foreach($regions as $region)

                        <option
                            value="{{ $region->idRegion }}"
                        >
                            {{ $region->nom }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Nom --}}
            <div>

                <label
                    for="edit_nom"
                    class="block text-sm font-medium text-gray-700 mb-2"
                >
                    Nom de la préfecture
                </label>

                <input
                    type="text"
                    id="edit_nom"
                    name="nom"
                    class="w-full rounded-xl border-gray-300
                           focus:border-green-500 focus:ring-green-500"
                >

            </div>


            {{-- Code --}}
            <div>

                <label
                    for="edit_code"
                    class="block text-sm font-medium text-gray-700 mb-2"
                >
                    Code
                </label>

                <input
                    type="text"
                    id="edit_code"
                    name="code"
                    class="w-full rounded-xl border-gray-300
                           focus:border-green-500 focus:ring-green-500"
                >

            </div>


            {{-- Boutons --}}
            <div class="flex justify-end gap-3 pt-4 border-t">

                <button
                    type="button"
                    onclick="closeEditPrefectureModal()"
                    class="px-5 py-2 rounded-xl bg-gray-200
                           text-gray-700 hover:bg-gray-300"
                >
                    Annuler
                </button>

                <button
                    type="submit"
                    class="px-5 py-2 rounded-xl bg-blue-600
                           text-white hover:bg-blue-700"
                >
                    Enregistrer les modifications
                </button>

            </div>

        </form>

    </div>

</div>


{{-- ========================================================= --}}
{{-- JAVASCRIPT DES MODALS --}}
{{-- ========================================================= --}}

<script>

function openCreatePrefectureModal()
{
    document
        .getElementById('createPrefectureModal')
        .classList.remove('hidden');
}


function closeCreatePrefectureModal()
{
    document
        .getElementById('createPrefectureModal')
        .classList.add('hidden');
}


function openEditPrefectureModal(
    id,
    nom,
    code,
    regionId
)
{
    const modal =
        document.getElementById('editPrefectureModal');

    const form =
        document.getElementById('editPrefectureForm');

    /*
    |--------------------------------------------------------------------------
    | Route update
    |--------------------------------------------------------------------------
    */

    form.action =
        "{{ url('prefectures') }}/" + id;


    /*
    |--------------------------------------------------------------------------
    | Préremplir
    |--------------------------------------------------------------------------
    */

    document.getElementById('edit_nom').value = nom;

    document.getElementById('edit_code').value = code;

    document.getElementById('edit_region_id').value =
        regionId;


    /*
    |--------------------------------------------------------------------------
    | Afficher
    |--------------------------------------------------------------------------
    */

    modal.classList.remove('hidden');
}


function closeEditPrefectureModal()
{
    document
        .getElementById('editPrefectureModal')
        .classList.add('hidden');
}


/*
|--------------------------------------------------------------------------
| Si validation Laravel échoue
|--------------------------------------------------------------------------
*/

@if($errors->any())

    openCreatePrefectureModal();

@endif

</script>



@endsection