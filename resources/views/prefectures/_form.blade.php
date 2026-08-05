@csrf

{{-- Région rattachée --}}
<div class="mb-6">

    <label for="region_id"
           class="block text-sm font-medium text-gray-700">

        Région <span class="text-red-500">*</span>

    </label>

    {{-- Cas création depuis une région --}}
    @if(isset($region))

        <input type="hidden"
               name="region_id"
               value="{{ $region->idRegion }}">

        <input type="text"
               value="{{ $region->nom }}"
               readonly
               class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-100">

    {{-- Cas création depuis le menu Préfectures ou modification --}}
    @else

        <select
            name="region_id"
            id="region_id"
            class="mt-1 block w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">

            <option value="">
                -- Choisir une région --
            </option>

            @foreach($regions as $item)

                <option value="{{ $item->idRegion }}"
                    @selected(old('region_id', $prefecture->region_id ?? '') == $item->idRegion)>

                    {{ $item->nom }}

                </option>

            @endforeach

        </select>

    @endif

    @error('region_id')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror

</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- Nom préfecture --}}
    <div>

        <label for="nom"
               class="block text-sm font-medium text-gray-700">

            Nom de la préfecture
            <span class="text-red-500">*</span>

        </label>

        <input
            type="text"
            name="nom"
            id="nom"
            value="{{ old('nom', $prefecture->nom ?? '') }}"
            class="mt-1 block w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">

        @error('nom')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

    </div>

    {{-- Code préfecture --}}
    <div>

        <label for="code"
               class="block text-sm font-medium text-gray-700">

            Code préfecture
            <span class="text-red-500">*</span>

        </label>

        <input
            type="text"
            name="code"
            id="code"
            value="{{ old('code', $prefecture->code ?? '') }}"
            class="mt-1 block w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">

        @error('code')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

    </div>

</div>

<div class="mt-8 flex justify-end gap-3">

    <a href="{{ route('prefectures.index') }}"
       class="px-5 py-2 rounded-lg border border-gray-300 hover:bg-gray-100">

        Annuler

    </a>

    <button type="submit"
            class="px-6 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">

        {{ isset($prefecture) ? 'Modifier' : 'Enregistrer' }}

    </button>

</div>