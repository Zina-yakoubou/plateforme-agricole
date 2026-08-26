@csrf

{{-- Canton rattaché --}}
<div class="mb-6">

    <label for="canton_id"
           class="block text-sm font-medium text-gray-700">

        Canton
        <span class="text-red-500">*</span>

    </label>

    {{-- Création depuis le détail d'un canton --}}
    @if(isset($canton))

        <input
            type="hidden"
            name="canton_id"
            value="{{ $canton->idCanton }}">

        <input
            type="text"
            value="{{ $canton->nom }}"
            readonly
            class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-100">

    {{-- Création normale --}}
    @else

        <select
            name="canton_id"
            id="canton_id"
            class="mt-1 block w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">

            <option value="">
                -- Sélectionner un canton --
            </option>

            @foreach($cantons as $item)

                <option
                    value="{{ $item->idCanton }}"
                    @selected(old('canton_id', $village->canton_id ?? '') == $item->idCanton)>

                    {{ $item->nom }}
                    —
                    {{ $item->commune->nom }}
                    —
                    {{ $item->commune->prefecture->nom }}

                </option>

            @endforeach

        </select>

    @endif

    @error('canton_id')

        <p class="mt-1 text-sm text-red-600">

            {{ $message }}

        </p>

    @enderror

</div>




<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- Nom --}}
    <div>

        <label
            for="nom"
            class="block text-sm font-medium text-gray-700">

            Nom du village
            <span class="text-red-500">*</span>

        </label>

        <input
            type="text"
            id="nom"
            name="nom"
            value="{{ old('nom', $village->nom ?? '') }}"
            class="mt-1 block w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">

        @error('nom')

            <p class="mt-1 text-sm text-red-600">

                {{ $message }}

            </p>

        @enderror

    </div>




    {{-- Code --}}
    <div>

        <label
            for="code"
            class="block text-sm font-medium text-gray-700">

            Code du village
            <span class="text-red-500">*</span>

        </label>

        <input
            type="text"
            id="code"
            name="code"
            value="{{ old('code', $village->code ?? '') }}"
            class="mt-1 block w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">

        <p class="mt-1 text-xs text-gray-500">

            Exemple : VIL-001

        </p>

        @error('code')

            <p class="mt-1 text-sm text-red-600">

                {{ $message }}

            </p>

        @enderror

    </div>

</div>





<div class="mt-8 flex justify-end gap-3">

    <a
        href="{{ route('villages.index') }}"
        class="px-5 py-2 rounded-lg border border-gray-300 hover:bg-gray-100">

        Annuler

    </a>

    <button
        type="submit"
        class="px-6 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">

        {{ isset($village) ? 'Mettre à jour' : 'Enregistrer' }}

    </button>

</div>