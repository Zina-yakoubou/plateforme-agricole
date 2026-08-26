@csrf


{{-- ================================================= --}}
{{-- VILLAGE RATTACHÉ --}}
{{-- ================================================= --}}

<div class="mb-6">

    <label
        for="village_id"
        class="block text-sm font-medium text-gray-700"
    >
        Village
        <span class="text-red-500">*</span>
    </label>


    {{-- Création depuis le détail d'un village --}}
    @if(isset($village))

        <input
            type="hidden"
            name="village_id"
            value="{{ $village->idVillage }}"
        >

        <input
            type="text"
            value="{{ $village->nom }}"
            readonly
            class="mt-1 block w-full rounded-lg
                   border-gray-300 bg-gray-100"
        >

    {{-- Création / modification normale --}}
    @else

        <select
            name="village_id"
            id="village_id"
            required
            class="mt-1 block w-full rounded-lg
                   border-gray-300
                   focus:border-green-500
                   focus:ring-green-500"
        >

            <option value="">
                -- Sélectionner un village --
            </option>

            @foreach($villages as $item)

                <option
                    value="{{ $item->idVillage }}"
                    @selected(
                        old(
                            'village_id',
                            $maison->village_id ?? ''
                        ) == $item->idVillage
                    )
                >

                    {{ $item->nom }}

                    —
                    {{ $item->canton->nom ?? '' }}

                    —
                    {{ $item->canton->commune->nom ?? '' }}

                </option>

            @endforeach

        </select>

    @endif


    @error('village_id')

        <p class="mt-1 text-sm text-red-600">
            {{ $message }}
        </p>

    @enderror

</div>


{{-- ================================================= --}}
{{-- NUMÉRO DE MAISON --}}
{{-- ================================================= --}}

<div class="mb-6">

    <label
        class="block text-sm font-medium text-gray-700"
    >
        Numéro de maison
    </label>


    @if(isset($maison))

        {{-- Modification : numéro existant --}}
        <input
            type="text"
            value="{{ $maison->numeroMaison }}"
            readonly
            class="mt-1 block w-full rounded-lg
                   border-gray-300 bg-gray-100"
        >

        <p class="mt-1 text-xs text-gray-500">
            Le numéro de maison est une référence officielle
            et ne peut pas être modifié.
        </p>

    @else

        {{-- Création : numéro généré automatiquement --}}
        <div
            class="mt-1 flex items-center gap-3
                   rounded-lg border border-green-200
                   bg-green-50 px-4 py-3"
        >

            <div
                class="w-9 h-9 rounded-lg
                       bg-green-100 text-green-700
                       flex items-center justify-center"
            >
                🏠
            </div>

            <div>

                <p class="text-sm font-semibold text-green-800">
                    Généré automatiquement
                </p>

                <p class="text-xs text-green-700">
                    Le système attribuera automatiquement
                    le numéro de la maison.
                </p>

            </div>

        </div>

    @endif

</div>


{{-- ================================================= --}}
{{-- UID TECHNIQUE --}}
{{-- ================================================= --}}

@if(isset($maison))

    <div class="mb-6">

        <label
            class="block text-sm font-medium text-gray-700"
        >
            Identifiant technique
        </label>

        <input
            type="text"
            value="{{ $maison->uid }}"
            readonly
            class="mt-1 block w-full rounded-lg
                   border-gray-300 bg-gray-100
                   text-sm text-gray-500"
        >

        <p class="mt-1 text-xs text-gray-500">
            Identifiant unique utilisé par le système.
        </p>

    </div>

@endif


{{-- ================================================= --}}
{{-- ADRESSE --}}
{{-- ================================================= --}}

<div class="mb-6">

    <label
        for="adresse"
        class="block text-sm font-medium text-gray-700"
    >
        Adresse
    </label>


    <input
        type="text"
        id="adresse"
        name="adresse"
        value="{{ old('adresse', $maison->adresse ?? '') }}"
        maxlength="255"
        class="mt-1 block w-full rounded-lg
               border-gray-300
               focus:border-green-500
               focus:ring-green-500"
        placeholder="Ex : près de l'école"
    >


    <p class="mt-1 text-xs text-gray-500">
        Indication permettant de localiser la maison.
    </p>


    @error('adresse')

        <p class="mt-1 text-sm text-red-600">
            {{ $message }}
        </p>

    @enderror

</div>


{{-- ================================================= --}}
{{-- BOUTONS --}}
{{-- ================================================= --}}

<div class="mt-8 flex justify-end gap-3">

    <a
        href="{{
            isset($village)
                ? route('villages.maisons.index', $village)
                : route('villages.maisons.index', $maison->village)
        }}"
        class="px-5 py-2 rounded-lg
               border border-gray-300
               hover:bg-gray-100"
    >
        Annuler
    </a>


    <button
        type="submit"
        class="px-6 py-2 rounded-lg
               bg-green-600 text-white
               hover:bg-green-700"
    >
        {{ isset($maison) ? 'Mettre à jour' : 'Enregistrer' }}
    </button>

</div>

