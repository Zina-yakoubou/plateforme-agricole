@csrf

{{-- Préfecture rattachée --}}
<div class="mb-6">

    <label for="prefecture_id"
           class="block text-sm font-medium text-gray-700">

        Préfecture <span class="text-red-500">*</span>

    </label>

    {{-- Cas création depuis une préfecture --}}
    @if(isset($prefecture))

        <input type="hidden"
               name="prefecture_id"
               value="{{ $prefecture->idPrefecture }}">

        <input type="text"
               value="{{ $prefecture->nom }}"
               readonly
               class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-100">

    {{-- Cas création depuis le menu Communes ou modification --}}
    @else

        <select name="prefecture_id"
                id="prefecture_id"
                class="mt-1 block w-full rounded-lg border-gray-300
                       focus:border-green-500 focus:ring-green-500">

            <option value="">
                -- Choisir une préfecture --
            </option>

            @foreach($prefectures as $item)

                <option value="{{ $item->idPrefecture }}"
                    @selected(
                        old(
                            'prefecture_id',
                            $commune->prefecture_id ?? ''
                        ) == $item->idPrefecture
                    )>

                    {{ $item->nom }}

                </option>

            @endforeach

        </select>

    @endif

    @error('prefecture_id')

        <p class="mt-1 text-sm text-red-600">

            {{ $message }}

        </p>

    @enderror

</div>



<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- Nom commune --}}
    <div>

        <label for="nom"
               class="block text-sm font-medium text-gray-700">

            Nom de la commune
            <span class="text-red-500">*</span>

        </label>

        <input
            type="text"
            name="nom"
            id="nom"
            value="{{ old('nom', $commune->nom ?? '') }}"
            class="mt-1 block w-full rounded-lg border-gray-300
                   focus:border-green-500 focus:ring-green-500">

        @error('nom')

            <p class="mt-1 text-sm text-red-600">

                {{ $message }}

            </p>

        @enderror

    </div>



    {{-- Code commune --}}
    <div>

        <label for="code"
               class="block text-sm font-medium text-gray-700">

            Code commune
            <span class="text-red-500">*</span>

        </label>

        <input
            type="text"
            name="code"
            id="code"
            value="{{ old('code', $commune->code ?? '') }}"
            class="mt-1 block w-full rounded-lg border-gray-300
                   focus:border-green-500 focus:ring-green-500">

        @error('code')

            <p class="mt-1 text-sm text-red-600">

                {{ $message }}

            </p>

        @enderror

    </div>

</div>



{{-- Boutons --}}
<div class="mt-8 flex justify-end gap-3">

    <a href="{{ route('communes.index') }}"
       class="px-5 py-2 rounded-lg border border-gray-300 hover:bg-gray-100">

        Annuler

    </a>

    <button type="submit"
            class="px-6 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">

        {{ isset($commune) ? 'Modifier' : 'Enregistrer' }}

    </button>

</div>