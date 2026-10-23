{{-- resources/views/menages/_form.blade.php --}}

<div class="grid grid-cols-2 gap-4">

    {{-- Nom du chef --}}
    <div>
        <label for="nomChef" class="block text-sm font-medium text-gray-700 mb-1">
            Nom 
        </label>

        <input
            type="text"
            id="nomChef"
            name="nomChef"
            value="{{ old('nomChef', $menage->nomChef ?? '') }}"
            class="w-full border-gray-300 rounded-lg text-sm @error('nomChef') border-red-500 @enderror"
        >

        @error('nomChef')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>


    {{-- Prénom du chef --}}
    <div>
        <label for="prenomChef" class="block text-sm font-medium text-gray-700 mb-1">
            Prénom 
        </label>

        <input
            type="text"
            id="prenomChef"
            name="prenomChef"
            value="{{ old('prenomChef', $menage->prenomChef ?? '') }}"
            class="w-full border-gray-300 rounded-lg text-sm @error('prenomChef') border-red-500 @enderror"
        >

        @error('prenomChef')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

</div>


        {{-- Sexe --}}
                <div>
                    <span class="{{ $labelClass }}">
                        Sexe <span class="text-red-500">*</span>
                    </span>

                    <div class="mt-2 flex items-center gap-6">

                        <label for="sexeM" class="inline-flex cursor-pointer items-center gap-2 text-sm text-gray-700">
                            <input
                                type="radio"
                                name="sexe"
                                id="sexeM"
                                value="M"
                                class="h-4 w-4 border-gray-300 text-[#006a4f] focus:ring-[#006a4f]"
                                @checked(old('sexe', $exploitant->sexe ?? '') === 'M')
                                required
                            >
                            Masculin
                        </label>

                        <label for="sexeF" class="inline-flex cursor-pointer items-center gap-2 text-sm text-gray-700">
                            <input
                                type="radio"
                                name="sexe"
                                id="sexeF"
                                value="F"
                                class="h-4 w-4 border-gray-300 text-[#006a4f] focus:ring-[#006a4f]"
                                @checked(old('sexe', $exploitant->sexe ?? '') === 'F')
                            >
                            Féminin
                        </label>

                    </div>

                    @error('sexe')
                        <p class="{{ $errorClass }}">{{ $message }}</p>
                    @enderror
                </div>

    {{-- Composition du ménage --}}
    <div>

    <p class="block text-sm font-medium text-gray-700 mb-2">
        Composition du ménage
    </p>

    <div class="grid grid-cols-4 gap-3">

        @foreach([
            'nombreHommes' => 'Hommes',
            'nombreFemmes' => 'Femmes',
            'nombreGarcons' => 'Garçons',
            'nombreFilles' => 'Filles'
        ] as $field => $label)

            <div>

                <label
                    for="{{ $field }}"
                    class="block text-xs text-gray-500 mb-1"
                >
                    {{ $label }}
                </label>

                <input
                    type="number"
                    id="{{ $field }}"
                    name="{{ $field }}"
                    min="0"
                    value="{{ old($field, $menage->$field ?? 0) }}"
                    class="w-full border-gray-300 rounded-lg text-sm @error($field) border-red-500 @enderror"
                >

                @error($field)
                    <p class="text-red-600 text-xs mt-1">
                        {{ $message }}
                    </p>
                @enderror

            </div>

        @endforeach

    </div>

</div>


{{-- Possession d'une exploitation --}}
<div class="rounded-lg border border-gray-200 bg-gray-50 p-4">

    <div class="flex items-center gap-3">

        {{-- Important :
             Si la case est décochée, Laravel reçoit 0.
             Si elle est cochée, Laravel reçoit 1.
        --}}
        <input
            type="hidden"
            name="possedeExploitation"
            value="0"
        >

        <input
            type="checkbox"
            id="possedeExploitation"
            name="possedeExploitation"
            value="1"
            @checked(old(
                'possedeExploitation',
                $menage->possedeExploitation ?? false
            ))
            class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
        >

        <label
            for="possedeExploitation"
            class="text-sm font-medium text-gray-700 cursor-pointer"
        >
            Ce ménage possède une exploitation agricole
        </label>

    </div>

    @error('possedeExploitation')
        <p class="text-red-600 text-xs mt-2">
            {{ $message }}
        </p>
    @enderror

</div>


{{-- Observations --}}
<div>

    <label
        for="observations"
        class="block text-sm font-medium text-gray-700 mb-1"
    >
        Observations
    </label>

    <textarea
        id="observations"
        name="observations"
        rows="3"
        class="w-full border-gray-300 rounded-lg text-sm @error('observations') border-red-500 @enderror"
    >{{ old('observations', $menage->observations ?? '') }}</textarea>

    @error('observations')
        <p class="text-red-600 text-xs mt-1">
            {{ $message }}
        </p>
    @enderror

</div>
