@extends('layouts.app')

@section('content')

{{-- Empêche le flash des modals avant l'initialisation d'Alpine --}}
<style>
    [x-cloak] {
        display: none !important;
    }
</style>

<div
    x-data="{
        modal: @if($errors->any() && old('_form') === 'edit')
            'edit'
        @elseif($errors->any())
            'create'
        @else
            null
        @endif,

        selectedRegion: @if($errors->any() && old('_form') === 'edit')
            {
                id: {{ (int) old('id') }},
                nom: @js(old('nom')),
                code: @js(old('code'))
            }
        @else
            null
        @endif,

        openModal(type, region = null) {
            this.modal = type;
            this.selectedRegion = region;
        },

        closeModal() {
            this.modal = null;
            this.selectedRegion = null;
        }
    }"

    @keydown.escape.window="closeModal()"
>

    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}

    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <h1 class="text-2xl font-bold text-[#212529]">
                Gestion des régions
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Liste des régions administratives du territoire.
            </p>

        </div>


        {{-- NOUVELLE RÉGION --}}
        <div class="flex flex-wrap items-center gap-3">

            <button
                type="button"
                @click="openModal('create')"
                class="inline-flex
                       items-center
                       gap-2
                       rounded-lg
                       bg-[#006a4f]
                       px-4 py-2.5
                       text-sm font-semibold
                       text-white
                       shadow-sm
                       transition
                       hover:bg-[#156c52]
                       focus:outline-none
                       focus:ring-2
                       focus:ring-[#006a4f]/30"
            >

                <svg
                    class="h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 4v16m8-8H4"
                    />
                </svg>

                Nouvelle région

            </button>

        </div>

    </div>


    {{-- =========================================================
         MESSAGES
    ========================================================== --}}

    @if(session('success'))

        <div class="flex items-start gap-3
                    rounded-lg
                    border border-[#b7dfd2]
                    bg-[#e5f2ee]
                    px-4 py-3
                    text-sm text-[#006a4f]">

            <svg
                class="mt-0.5 h-5 w-5 shrink-0"
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

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif


    @if(session('error'))

        <div class="flex items-start gap-3
                    rounded-lg
                    border border-red-200
                    bg-red-50
                    px-4 py-3
                    text-sm text-[#ab1717]">

            <svg
                class="mt-0.5 h-5 w-5 shrink-0"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8v4m0 4h.01
                       M21 12a9 9 0 11-18 0
                       9 9 0 0118 0z"
                />
            </svg>

            <span>
                {{ session('error') }}
            </span>

        </div>

    @endif


    {{-- =========================================================
         ERREURS DE VALIDATION
    ========================================================== --}}

    @if($errors->any())

        <div class="flex items-start gap-3
                    rounded-lg
                    border border-red-200
                    bg-red-50
                    px-4 py-3
                    text-sm text-red-700">

            <svg
                class="mt-0.5 h-5 w-5 shrink-0"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8v4m0 4h.01
                       M21 12a9 9 0 11-18 0
                       9 9 0 0118 0z"
                />
            </svg>

            <div>

                <p class="font-semibold">
                    Impossible d'enregistrer les informations.
                </p>

                <ul class="mt-1 list-disc list-inside text-xs">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        </div>

    @endif


    {{-- =========================================================
         RECHERCHE
    ========================================================== --}}

    <div class="rounded-lg
                border border-[#e5e7eb]
                bg-white
                p-4
                shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

        <form
            method="GET"
            action="{{ route('regions.index') }}"
        >

            <div class="flex flex-col gap-3 sm:flex-row">

                <div class="relative flex-1">

                    {{-- ICÔNE RECHERCHE --}}
                    <svg
                        class="pointer-events-none
                               absolute left-4 top-1/2
                               h-5 w-5
                               -translate-y-1/2
                               text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="m21 21-4.35-4.35
                               m2.35-5.65a8 8 0 11-16 0
                               8 8 0 0116 0z"
                        />
                    </svg>


                    <input
                        type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Rechercher par nom ou code..."
                        class="w-full
                               rounded-lg
                               border border-[#e5e7eb]
                               bg-white
                               py-3 pl-11 pr-4
                               text-sm text-[#212529]
                               placeholder:text-gray-400
                               focus:border-[#006a4f]
                               focus:outline-none
                               focus:ring-2
                               focus:ring-[#006a4f]/10"
                    >

                </div>


                {{-- BOUTON RECHERCHER --}}
                <button
                    type="submit"
                    class="inline-flex
                           items-center
                           justify-center
                           gap-2
                           rounded-lg
                           bg-[#006a4f]
                           px-5 py-3
                           text-sm font-semibold
                           text-white
                           transition
                           hover:bg-[#156c52]"
                >

                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="m21 21-4.35-4.35
                               m2.35-5.65a8 8 0 11-16 0
                               8 8 0 0116 0z"
                        />
                    </svg>

                    Rechercher

                </button>

            </div>

        </form>

    </div>


    {{-- =========================================================
         TABLEAU DES RÉGIONS
    ========================================================== --}}

    <div class="overflow-hidden
                rounded-lg
                border border-[#e5e7eb]
                bg-white
                shadow-[0_1px_2px_rgba(0,0,0,0.05)]">


        {{-- =====================================================
             EN-TÊTE DU TABLEAU
        ====================================================== --}}

        <div class="flex flex-col gap-1
                    border-b border-[#e5e7eb]
                    px-6 py-5
                    sm:flex-row
                    sm:items-center
                    sm:justify-between">

            <div>

                <h2 class="text-base font-semibold text-[#212529]">
                    Régions
                </h2>

                <p class="mt-1 text-xs text-gray-500">
                    Régions administratives enregistrées dans le système.
                </p>

            </div>


            {{-- COMPTEUR --}}
            <div class="text-sm text-gray-500">

                <span class="font-semibold text-[#006a4f]">
                    {{ $regions->total() }}
                </span>

                région(s)

            </div>

        </div>


        {{-- =====================================================
             TABLE
        ====================================================== --}}

        <div class="overflow-x-auto">

            <table class="w-full min-w-[850px] text-sm">

                {{-- =================================================
                     THEAD
                ================================================== --}}

                <thead class="bg-[#f8faf9]">

                    <tr class="border-b border-[#e5e7eb]">

                        <th
                            class="px-6 py-4 text-left
                                   text-xs font-semibold
                                   uppercase tracking-wide
                                   text-gray-500"
                        >
                            N°
                        </th>


                        <th
                            class="px-6 py-4 text-left
                                   text-xs font-semibold
                                   uppercase tracking-wide
                                   text-gray-500"
                        >
                            Région
                        </th>


                        <th
                            class="px-6 py-4 text-left
                                   text-xs font-semibold
                                   uppercase tracking-wide
                                   text-gray-500"
                        >
                            Code
                        </th>


                        <th
                            class="px-6 py-4 text-center
                                   text-xs font-semibold
                                   uppercase tracking-wide
                                   text-gray-500"
                        >
                            Préfectures
                        </th>


                        <th
                            class="px-6 py-4 text-right
                                   text-xs font-semibold
                                   uppercase tracking-wide
                                   text-gray-500"
                        >
                            Actions
                        </th>

                    </tr>

                </thead>


                {{-- =================================================
                     TBODY
                ================================================== --}}

                <tbody class="divide-y divide-[#e5e7eb]">

                    @forelse($regions as $region)

                        <tr class="group transition hover:bg-[#f8faf9]">


                            {{-- =====================================
                                 N°
                            ====================================== --}}

                            <td class="px-6 py-4 text-sm text-gray-400">

                                {{ $loop->iteration + (($regions->currentPage() - 1) * $regions->perPage()) }}

                            </td>


                            {{-- =====================================
                                 RÉGION
                            ====================================== --}}

                            <td class="px-6 py-4">

                                <div class="flex items-center gap-3">

                                    {{-- AVATAR --}}
                                    <div class="flex h-10 w-10 shrink-0
                                                items-center justify-center
                                                rounded-full
                                                bg-[#e5f2ee]
                                                text-sm font-semibold
                                                text-[#006a4f]">

                                        {{ strtoupper(substr($region->nom, 0, 1)) }}

                                    </div>


                                    <div class="min-w-0">

                                        <div class="truncate
                                                    font-semibold
                                                    text-[#212529]">

                                            {{ $region->nom }}

                                        </div>

                                        <div class="truncate
                                                    text-xs
                                                    text-gray-500">

                                            Région administrative

                                        </div>

                                    </div>

                                </div>

                            </td>


                            {{-- =====================================
                                 CODE
                            ====================================== --}}

                            <td class="px-6 py-4">

                                <span class="inline-flex
                                             items-center
                                             rounded-full
                                             bg-gray-100
                                             px-3 py-1
                                             text-xs
                                             font-semibold
                                             text-gray-700">

                                    {{ $region->code }}

                                </span>

                            </td>


                            {{-- =====================================
                                 PRÉFECTURES
                            ====================================== --}}

                            <td class="px-6 py-4 text-center">

                                <span class="inline-flex
                                             items-center
                                             justify-center
                                             min-w-[2.25rem]
                                             rounded-full
                                             bg-[#e5f2ee]
                                             px-3 py-1
                                             text-xs
                                             font-semibold
                                             text-[#006a4f]">

                                    {{ $region->prefectures_count ?? $region->prefectures->count() }}

                                </span>

                            </td>


                            {{-- =====================================
                                 ACTIONS
                            ====================================== --}}

                            <td class="px-6 py-4">

                                <div class="flex
                                            items-center
                                            justify-end
                                            gap-2">


                                    {{-- =================================
                                         VOIR
                                    ================================== --}}

                                    <a
                                        href="{{ route('regions.show', $region->idRegion) }}"
                                        title="Voir la région"
                                        class="inline-flex
                                               h-9 w-9
                                               items-center
                                               justify-center
                                               rounded-lg
                                               border
                                               border-[#e5e7eb]
                                               bg-white
                                               text-gray-600
                                               transition
                                               hover:border-[#006a4f]
                                               hover:bg-[#e5f2ee]
                                               hover:text-[#006a4f]"
                                    >

                                        <svg
                                            class="h-4 w-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M2.458 12C3.732 7.943
                                                   7.523 5 12 5
                                                   c4.478 0 8.268 2.943
                                                   9.542 7
                                                   -1.274 4.057-5.064 7
                                                   -9.542 7
                                                   -4.477 0-8.268-2.943
                                                   -9.542-7z"
                                            />

                                            <circle
                                                cx="12"
                                                cy="12"
                                                r="3"
                                            />

                                        </svg>

                                    </a>


                                    {{-- =================================
                                         MODIFIER
                                    ================================== --}}

                                    <button
                                        type="button"
                                        title="Modifier la région"
                                        @click="openModal('edit', {
                                            id: {{ $region->idRegion }},
                                            nom: @js($region->nom),
                                            code: @js($region->code)
                                        })"
                                        class="inline-flex
                                               h-9 w-9
                                               items-center
                                               justify-center
                                               rounded-lg
                                               border
                                               border-[#e5e7eb]
                                               bg-white
                                               text-gray-600
                                               transition
                                               hover:border-[#006a4f]
                                               hover:bg-[#e5f2ee]
                                               hover:text-[#006a4f]"
                                    >

                                        <svg
                                            class="h-4 w-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11
                                                   a2 2 0 002 2h11a2 2 0 002-2v-5"
                                            />

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M18.5 2.5a2.121 2.121
                                                   0 013 3L12 15l-4 1 1-4
                                                   10.5-10.5z"
                                            />

                                        </svg>

                                    </button>

                                </div>

                            </td>

                        </tr>


                    @empty

                        {{-- =========================================
                             AUCUNE RÉGION
                        ========================================== --}}

                        <tr>

                            <td
                                colspan="5"
                                class="px-6 py-16 text-center"
                            >

                                <div class="flex flex-col items-center">

                                    <div class="mb-4
                                                flex h-14 w-14
                                                items-center
                                                justify-center
                                                rounded-full
                                                bg-[#e5f2ee]
                                                text-[#006a4f]">

                                        <svg
                                            class="h-7 w-7"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.7"
                                                d="M3 21h18
                                                   M5 21V5
                                                   a2 2 0 012-2h10
                                                   a2 2 0 012 2v16
                                                   M8 7h2
                                                   M14 7h2
                                                   M8 11h2
                                                   M14 11h2
                                                   M8 15h2
                                                   M14 15h2"
                                            />

                                        </svg>

                                    </div>


                                    <p class="font-semibold text-[#212529]">
                                        Aucune région trouvée
                                    </p>

                                    <p class="mt-1 text-sm text-gray-500">
                                        Aucune région ne correspond à votre recherche.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- =========================================================
         PAGINATION
    ========================================================== --}}

    @if($regions->hasPages())

        <div class="flex justify-center pt-2">

            {{ $regions->links() }}

        </div>

    @endif


    {{-- =========================================================
         MODAL : CRÉATION
    ========================================================== --}}

    <div
        x-show="modal === 'create'"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
    >

        {{-- OVERLAY --}}
        <div
            class="absolute inset-0 bg-black/50"
            @click="closeModal()"
        ></div>


        {{-- MODAL --}}
        <div
            class="relative w-full max-w-lg
                   overflow-hidden
                   rounded-2xl
                   bg-white
                   shadow-xl"
            @click.stop
        >

            {{-- EN-TÊTE --}}
            <div class="flex items-center justify-between
                        border-b border-[#e5e7eb]
                        px-6 py-5">

                <div>

                    <h2 class="text-lg font-bold text-[#212529]">
                        Nouvelle région
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Ajouter une nouvelle région administrative.
                    </p>

                </div>


                <button
                    type="button"
                    @click="closeModal()"
                    class="flex h-9 w-9
                           items-center justify-center
                           rounded-lg
                           text-gray-400
                           transition
                           hover:bg-gray-100
                           hover:text-gray-700"
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
                            stroke-width="2"
                            d="M6 6l12 12M18 6L6 18"
                        />

                    </svg>

                </button>

            </div>


            {{-- FORMULAIRE --}}
            <form
                method="POST"
                action="{{ route('regions.store') }}"
                class="p-6"
            >

                @csrf

                <input
                    type="hidden"
                    name="_form"
                    value="create"
                >


                {{-- NOM --}}
                <div class="mb-5">

                    <label
                        for="create_nom"
                        class="mb-2 block text-sm font-medium text-[#212529]"
                    >
                        Nom de la région
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="nom"
                        id="create_nom"
                        value="{{ old('_form') === 'create' ? old('nom') : '' }}"
                        required
                        placeholder="Ex : Région Centrale"
                        class="w-full
                               rounded-lg
                               border border-[#e5e7eb]
                               bg-white
                               px-4 py-3
                               text-sm text-[#212529]
                               placeholder:text-gray-400
                               focus:border-[#006a4f]
                               focus:outline-none
                               focus:ring-2
                               focus:ring-[#006a4f]/10"
                    >

                    @if(old('_form') === 'create')
                        @error('nom')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    @endif

                </div>


                {{-- CODE --}}
                <div class="mb-6">

                    <label
                        for="create_code"
                        class="mb-2 block text-sm font-medium text-[#212529]"
                    >
                        Code
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="code"
                        id="create_code"
                        value="{{ old('_form') === 'create' ? old('code') : '' }}"
                        required
                        placeholder="Ex : RC"
                        class="w-full
                               rounded-lg
                               border border-[#e5e7eb]
                               bg-white
                               px-4 py-3
                               text-sm text-[#212529]
                               placeholder:text-gray-400
                               focus:border-[#006a4f]
                               focus:outline-none
                               focus:ring-2
                               focus:ring-[#006a4f]/10"
                    >

                    @if(old('_form') === 'create')
                        @error('code')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    @endif

                </div>


                {{-- ACTIONS --}}
                <div class="flex justify-end gap-3">

                    <button
                        type="button"
                        @click="closeModal()"
                        class="rounded-lg
                               border border-[#e5e7eb]
                               bg-white
                               px-5 py-2.5
                               text-sm font-semibold
                               text-gray-600
                               transition
                               hover:bg-gray-50"
                    >
                        Annuler
                    </button>


                    <button
                        type="submit"
                        class="inline-flex
                               items-center
                               gap-2
                               rounded-lg
                               bg-[#006a4f]
                               px-5 py-2.5
                               text-sm font-semibold
                               text-white
                               transition
                               hover:bg-[#156c52]"
                    >

                        <svg
                            class="h-4 w-4"
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

                        Enregistrer

                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
         MODAL : MODIFICATION
    ========================================================== --}}

    <div
        x-show="modal === 'edit'"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
    >

        {{-- OVERLAY --}}
        <div
            class="absolute inset-0 bg-black/50"
            @click="closeModal()"
        ></div>


        {{-- MODAL --}}
        <div
            class="relative w-full max-w-lg
                   overflow-hidden
                   rounded-2xl
                   bg-white
                   shadow-xl"
            @click.stop
        >

            {{-- EN-TÊTE --}}
            <div class="flex items-center justify-between
                        border-b border-[#e5e7eb]
                        px-6 py-5">

                <div>

                    <h2 class="text-lg font-bold text-[#212529]">
                        Modifier la région
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Modifier les informations de la région.
                    </p>

                </div>


                <button
                    type="button"
                    @click="closeModal()"
                    class="flex h-9 w-9
                           items-center justify-center
                           rounded-lg
                           text-gray-400
                           transition
                           hover:bg-gray-100
                           hover:text-gray-700"
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
                            stroke-width="2"
                            d="M6 6l12 12M18 6L6 18"
                        />

                    </svg>

                </button>

            </div>


            {{-- FORMULAIRE --}}
            <form
                method="POST"
                class="p-6"
                :action="`{{ url('regions') }}/${selectedRegion?.id}`"
            >

                @csrf

                @method('PUT')

                <input
                    type="hidden"
                    name="_form"
                    value="edit"
                >

                <input
                    type="hidden"
                    name="id"
                    :value="selectedRegion?.id"
                >


                {{-- NOM --}}
                <div class="mb-5">

                    <label
                        for="edit_nom"
                        class="mb-2 block text-sm font-medium text-[#212529]"
                    >
                        Nom de la région
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="nom"
                        id="edit_nom"
                        x-bind:value="selectedRegion?.nom"
                        required
                        class="w-full
                               rounded-lg
                               border border-[#e5e7eb]
                               bg-white
                               px-4 py-3
                               text-sm text-[#212529]
                               focus:border-[#006a4f]
                               focus:outline-none
                               focus:ring-2
                               focus:ring-[#006a4f]/10"
                    >

                    @if(old('_form') === 'edit')
                        @error('nom')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    @endif

                </div>


                {{-- CODE --}}
                <div class="mb-6">

                    <label
                        for="edit_code"
                        class="mb-2 block text-sm font-medium text-[#212529]"
                    >
                        Code
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="code"
                        id="edit_code"
                        x-bind:value="selectedRegion?.code"
                        required
                        class="w-full
                               rounded-lg
                               border border-[#e5e7eb]
                               bg-white
                               px-4 py-3
                               text-sm text-[#212529]
                               focus:border-[#006a4f]
                               focus:outline-none
                               focus:ring-2
                               focus:ring-[#006a4f]/10"
                    >

                    @if(old('_form') === 'edit')
                        @error('code')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    @endif

                </div>


                {{-- ACTIONS --}}
                <div class="flex justify-end gap-3">

                    <button
                        type="button"
                        @click="closeModal()"
                        class="rounded-lg
                               border border-[#e5e7eb]
                               bg-white
                               px-5 py-2.5
                               text-sm font-semibold
                               text-gray-600
                               transition
                               hover:bg-gray-50"
                    >
                        Annuler
                    </button>


                    <button
                        type="submit"
                        class="inline-flex
                               items-center
                               gap-2
                               rounded-lg
                               bg-[#006a4f]
                               px-5 py-2.5
                               text-sm font-semibold
                               text-white
                               transition
                               hover:bg-[#156c52]"
                    >

                        <svg
                            class="h-4 w-4"
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

                        Enregistrer les modifications

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection