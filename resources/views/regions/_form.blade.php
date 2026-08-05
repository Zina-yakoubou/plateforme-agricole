@csrf


<div class="grid grid-cols-1 md:grid-cols-2 gap-6">


    {{-- Nom région --}}
    <div>

        <label for="nom" class="block text-sm font-medium text-gray-700">

            Nom de la région <span class="text-red-500">*</span>

        </label>


        <input
            type="text"
            name="nom"
            id="nom"
            value="{{ old('nom', $region->nom ?? '') }}"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm 
                   focus:border-green-500 focus:ring-green-500">


        @error('nom')

            <p class="mt-1 text-sm text-red-600">

                {{ $message }}

            </p>

        @enderror


    </div>



    {{-- Code région --}}
    <div>

        <label for="code" class="block text-sm font-medium text-gray-700">

            Code région <span class="text-red-500">*</span>

        </label>


        <input
            type="text"
            name="code"
            id="code"
            value="{{ old('code', $region->code ?? '') }}"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm 
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


    <a href="{{ route('regions.index') }}"
       class="px-5 py-2 rounded-lg border border-gray-300 hover:bg-gray-100">

        Annuler

    </a>



    <button type="submit"
            class="px-6 py-2 rounded-lg bg-green-600 text-white 
                   hover:bg-green-700">


        {{ isset($region) ? 'Modifier' : 'Enregistrer' }}


    </button>


</div>