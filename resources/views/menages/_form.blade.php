<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Numéro du ménage</label>
    <input type="number" name="numeroMenage" min="1"
           value="{{ old('numeroMenage', $menage->numeroMenage ?? $prochainNumero) }}"
           class="w-full border-gray-300 rounded-lg text-sm @error('numeroMenage') border-red-500 @enderror">
    @error('numeroMenage') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nom du chef</label>
        <input type="text" name="nomChef" value="{{ old('nomChef', $menage->nomChef ?? '') }}"
               class="w-full border-gray-300 rounded-lg text-sm @error('nomChef') border-red-500 @enderror">
        @error('nomChef') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Prénom du chef</label>
        <input type="text" name="prenomChef" value="{{ old('prenomChef', $menage->prenomChef ?? '') }}"
               class="w-full border-gray-300 rounded-lg text-sm @error('prenomChef') border-red-500 @enderror">
        @error('prenomChef') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Sexe du chef</label>
    <select name="sexeChef" class="w-full border-gray-300 rounded-lg text-sm @error('sexeChef') border-red-500 @enderror">
        <option value="">—</option>
        <option value="M" @selected(old('sexeChef', $menage->sexeChef ?? '') === 'M')>Masculin</option>
        <option value="F" @selected(old('sexeChef', $menage->sexeChef ?? '') === 'F')>Féminin</option>
    </select>
    @error('sexeChef') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <p class="block text-sm font-medium text-gray-700 mb-2">Composition du ménage</p>
    <div class="grid grid-cols-4 gap-3">
        @foreach(['nombreHommes' => 'Hommes', 'nombreFemmes' => 'Femmes', 'nombreGarcons' => 'Garçons', 'nombreFilles' => 'Filles'] as $field => $label)
            <div>
                <label class="block text-xs text-gray-500 mb-1">{{ $label }}</label>
                <input type="number" name="{{ $field }}" min="0"
                       value="{{ old($field, $menage->$field ?? 0) }}"
                       class="w-full border-gray-300 rounded-lg text-sm">
            </div>
        @endforeach
    </div>
</div>

<div class="flex items-center gap-2">
    <input type="checkbox" id="possedeExploitation" name="possedeExploitation" value="1"
           @checked(old('possedeExploitation', $menage->possedeExploitation ?? false))
           class="rounded border-gray-300">
    <label for="possedeExploitation" class="text-sm text-gray-700">
        Ce ménage possède une exploitation agricole
    </label>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Observations</label>
    <textarea name="observations" rows="3"
              class="w-full border-gray-300 rounded-lg text-sm @error('observations') border-red-500 @enderror">{{ old('observations', $menage->observations ?? '') }}</textarea>
    @error('observations') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
</div>