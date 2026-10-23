@php
    $labelClass = $labelClass ?? 'block text-sm font-medium text-gray-700';
    $inputClass = $inputClass ?? 'w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-[#006a4f] focus:outline-none focus:ring-2 focus:ring-[#006a4f]/10';
@endphp

<div class="space-y-6">

    {{-- INFORMATIONS SUR LE CHEF DE MÉNAGE --}}
    <div class="space-y-4">
        <h3 class="text-sm font-semibold uppercase tracking-wider text-[#006a4f]">
            Chef de ménage
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Nom du chef --}}
            <div>
                <label for="nomChef" class="{{ $labelClass }}">
                    Nom du chef de ménage <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="nomChef"
                    name="nomChef" 
                    value="{{ old('nomChef', $menage->nomChef ?? '') }}" 
                    class="{{ $inputClass }}" 
                    placeholder="Nom de famille"
                    required 
                />
                @error('nomChef')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Prénom du chef --}}
            <div>
                <label for="prenomChef" class="{{ $labelClass }}">
                    Prénom du chef de ménage
                </label>
                <input 
                    type="text" 
                    id="prenomChef"
                    name="prenomChef" 
                    value="{{ old('prenomChef', $menage->prenomChef ?? '') }}" 
                    class="{{ $inputClass }}" 
                    placeholder="Prénom(s)"
                />
                @error('prenomChef')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Sexe du chef --}}
        <div>
            <span class="{{ $labelClass }}">
                Sexe du chef de ménage <span class="text-red-500">*</span>
            </span>
            <div class="mt-2 flex items-center gap-6">
                <label for="sexeChefM" class="inline-flex cursor-pointer items-center gap-2 text-sm text-gray-700">
                    <input
                        type="radio"
                        name="sexeChef"
                        id="sexeChefM"
                        value="M"
                        @checked(old('sexeChef', $menage->sexeChef ?? '') === 'M')
                        class="h-4 w-4 border-gray-300 text-[#006a4f] focus:ring-[#006a4f]"
                        required
                    />
                    Masculin
                </label>

                <label for="sexeChefF" class="inline-flex cursor-pointer items-center gap-2 text-sm text-gray-700">
                    <input
                        type="radio"
                        name="sexeChef"
                        id="sexeChefF"
                        value="F"
                        @checked(old('sexeChef', $menage->sexeChef ?? '') === 'F')
                        class="h-4 w-4 border-gray-300 text-[#006a4f] focus:ring-[#006a4f]"
                        required
                    />
                    Féminin
                </label>
            </div>
            @error('sexeChef')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- COMPOSITION DU MÉNAGE --}}
    <div class="space-y-4 pt-4 border-t border-gray-200">
        <h3 class="text-sm font-semibold uppercase tracking-wider text-[#006a4f]">
            Composition du ménage
        </h3>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            {{-- Nombre d'hommes --}}
            <div>
                <label for="nombreHommes" class="{{ $labelClass }}">Hommes</label>
                <input 
                    type="number" 
                    id="nombreHommes"
                    name="nombreHommes" 
                    min="0" 
                    value="{{ old('nombreHommes', $menage->nombreHommes ?? 0) }}" 
                    class="{{ $inputClass }}" 
                />
                @error('nombreHommes')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nombre de femmes --}}
            <div>
                <label for="nombreFemmes" class="{{ $labelClass }}">Femmes</label>
                <input 
                    type="number" 
                    id="nombreFemmes"
                    name="nombreFemmes" 
                    min="0" 
                    value="{{ old('nombreFemmes', $menage->nombreFemmes ?? 0) }}" 
                    class="{{ $inputClass }}" 
                />
                @error('nombreFemmes')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nombre de garçons --}}
            <div>
                <label for="nombreGarcons" class="{{ $labelClass }}">Garçons</label>
                <input 
                    type="number" 
                    id="nombreGarcons"
                    name="nombreGarcons" 
                    min="0" 
                    value="{{ old('nombreGarcons', $menage->nombreGarcons ?? 0) }}" 
                    class="{{ $inputClass }}" 
                />
                @error('nombreGarcons')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nombre de filles --}}
            <div>
                <label for="nombreFilles" class="{{ $labelClass }}">Filles</label>
                <input 
                    type="number" 
                    id="nombreFilles"
                    name="nombreFilles" 
                    min="0" 
                    value="{{ old('nombreFilles', $menage->nombreFilles ?? 0) }}" 
                    class="{{ $inputClass }}" 
                />
                @error('nombreFilles')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- EXPLOITATION AGRICOLE & OBSERVATIONS --}}
    <div class="space-y-4 pt-4 border-t border-gray-200">
        {{-- Possède une exploitation --}}
        <div>
            <label class="flex cursor-pointer items-center gap-3">
                <input 
                    type="hidden" 
                    name="possedeExploitation" 
                    value="0" 
                />
                <input 
                    type="checkbox" 
                    name="possedeExploitation" 
                    id="possedeExploitation"
                    value="1" 
                    @checked(old('possedeExploitation', $menage->possedeExploitation ?? false))
                    class="h-5 w-5 rounded border-gray-300 text-[#006a4f] focus:ring-[#006a4f]"
                />
                <span class="text-sm font-medium text-gray-700">
                    Le ménage possède une exploitation agricole
                </span>
            </label>
            @error('possedeExploitation')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Observations --}}
        <div>
            <label for="observations" class="{{ $labelClass }}">
                Observations
            </label>
            <textarea 
                id="observations"
                name="observations" 
                rows="3" 
                class="{{ $inputClass }}"
                placeholder="Remarques ou observations éventuelles..."
            >{{ old('observations', $menage->observations ?? '') }}</textarea>
            @error('observations')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

</div>