@csrf


{{-- Commune rattachée --}}
<div class="mb-6">

    <label for="commune_id"
           class="block text-sm font-medium text-gray-700 mb-2">

        Commune <span class="text-red-500">*</span>

    </label>


    {{-- 
        Cas 1 :
        Création depuis le détail d'une commune
    --}}
    @if(isset($commune))


        <input type="hidden"
               name="commune_id"
               value="{{ $commune->idCommune }}">


        <input type="text"
               value="{{ $commune->nom }}"
               readonly
               class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-100">


    @else


        {{-- 
            Cas 2 et 3 :
            Création normale ou modification
        --}}

        <select
            name="commune_id"
            id="commune_id"
            class="mt-1 block w-full rounded-lg border-gray-300 
                   focus:border-green-500 focus:ring-green-500">


            <option value="">
                -- Choisir une commune --
            </option>


            @foreach($communes as $item)


                <option value="{{ $item->idCommune }}"

                    @selected(
                        old(
                            'commune_id',
                            $canton->commune_id ?? ''
                        )
                        ==
                        $item->idCommune
                    )

                >

                    {{ $item->nom }}

                </option>


            @endforeach


        </select>


    @endif



    @error('commune_id')

        <p class="mt-1 text-sm text-red-600">
            {{ $message }}
        </p>

    @enderror


</div>





{{-- Nom du canton --}}
<div class="mb-6">

    <label for="nom"
           class="block text-sm font-medium text-gray-700 mb-2">

        Nom du canton <span class="text-red-500">*</span>

    </label>


    <input type="text"
           name="nom"
           id="nom"

           value="{{ old(
                'nom',
                $canton->nom ?? ''
           ) }}"

           class="mt-1 block w-full rounded-lg border-gray-300
                  focus:border-green-500 focus:ring-green-500">


    @error('nom')

        <p class="mt-1 text-sm text-red-600">
            {{ $message }}
        </p>

    @enderror

</div>





{{-- Code du canton --}}
<div class="mb-6">

    <label for="code"
           class="block text-sm font-medium text-gray-700 mb-2">

        Code du canton <span class="text-red-500">*</span>

    </label>


    <input type="text"
           name="code"
           id="code"

           value="{{ old(
                'code',
                $canton->code ?? ''
           ) }}"

           class="mt-1 block w-full rounded-lg border-gray-300
                  focus:border-green-500 focus:ring-green-500">


    @error('code')

        <p class="mt-1 text-sm text-red-600">
            {{ $message }}
        </p>

    @enderror

</div>





{{-- Boutons --}}
<div class="flex items-center gap-4">


    <button type="submit"

        class="px-5 py-2 rounded-lg bg-green-600 
               text-white hover:bg-green-700">

        {{ isset($canton)
            ? 'Modifier'
            : 'Enregistrer'
        }}

    </button>



    <a href="{{ route('cantons.index') }}"

       class="px-5 py-2 rounded-lg bg-gray-300 
              text-gray-700 hover:bg-gray-400">

        Annuler

    </a>


</div>