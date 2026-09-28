<div class="space-y-6">

    {{-- ============================================================
        NUMÉRO PARCELLE
    ============================================================ --}}
    <div>
        <label for="numeroParcelle"
               class="mb-2 block text-sm font-semibold text-slate-700">
            Numéro de la parcelle
        </label>

        <input type="text"
               name="numeroParcelle"
               id="numeroParcelle"
               value="{{ old('numeroParcelle', $parcelle->numeroParcelle ?? '') }}"
               class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
               placeholder="Ex. P-01">

        @error('numeroParcelle')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror
    </div>


    {{-- ============================================================
        SUPERFICIE
    ============================================================ --}}
    <div>
        <label for="superficie"
               class="mb-2 block text-sm font-semibold text-slate-700">
            Superficie (hectares)
        </label>

        <input type="number"
               name="superficie"
               id="superficie"
               step="0.01"
               min="0"
               value="{{ old('superficie', $parcelle->superficie ?? '') }}"
               class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
               placeholder="Ex. 2.50">

        <p class="mt-1 text-xs text-slate-500">
            L'agent peut renseigner la superficie mesurée ou déclarée.
        </p>

        @error('superficie')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror
    </div>


    {{-- ============================================================
        TYPE DE SOL
    ============================================================ --}}
    <div>
        <label for="typeSol"
               class="mb-2 block text-sm font-semibold text-slate-700">
            Type de sol
        </label>

        <input type="text"
               name="typeSol"
               id="typeSol"
               value="{{ old('typeSol', $parcelle->typeSol ?? '') }}"
               class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
               placeholder="Ex. Sol ferrugineux">

        @error('typeSol')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror
    </div>


    {{-- ============================================================
        MODE DE FAIRE-VALOIR
    ============================================================ --}}
    <div>

        <label for="modeFaireValoir"
               class="mb-2 block text-sm font-semibold text-slate-700">
            Mode de faire-valoir
        </label>

        <select name="modeFaireValoir"
                id="modeFaireValoir"
                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">

            @php
                $modeFaireValoir = old(
                    'modeFaireValoir',
                    $parcelle->modeFaireValoir ?? 'proprietaire'
                );
            @endphp

            <option value="proprietaire" {{ $modeFaireValoir === 'proprietaire' ? 'selected' : '' }}>
                Propriétaire
            </option>

            <option value="location" {{ $modeFaireValoir === 'location' ? 'selected' : '' }}>
                Location
            </option>

            <option value="pret" {{ $modeFaireValoir === 'pret' ? 'selected' : '' }}>
                Prêt
            </option>

            <option value="metayage" {{ $modeFaireValoir === 'metayage' ? 'selected' : '' }}>
                Métayage
            </option>

            <option value="autre" {{ $modeFaireValoir === 'autre' ? 'selected' : '' }}>
                Autre
            </option>

        </select>

        @error('modeFaireValoir')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>


    {{-- ============================================================
        MODE D'IRRIGATION
    ============================================================ --}}
    <div>

        <label for="modeIrrigation"
               class="mb-2 block text-sm font-semibold text-slate-700">
            Mode d'irrigation
        </label>

        @php
            $modeIrrigation = old(
                'modeIrrigation',
                $parcelle->modeIrrigation ?? 'pluvial'
            );
        @endphp

        <select name="modeIrrigation"
                id="modeIrrigation"
                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">

            <option value="pluvial" {{ $modeIrrigation === 'pluvial' ? 'selected' : '' }}>
                Pluvial
            </option>

            <option value="gravitaire" {{ $modeIrrigation === 'gravitaire' ? 'selected' : '' }}>
                Gravitaire
            </option>

            <option value="pompage" {{ $modeIrrigation === 'pompage' ? 'selected' : '' }}>
                Pompage
            </option>

            <option value="aucun" {{ $modeIrrigation === 'aucun' ? 'selected' : '' }}>
                Aucun
            </option>

            <option value="autre" {{ $modeIrrigation === 'autre' ? 'selected' : '' }}>
                Autre
            </option>

        </select>

        @error('modeIrrigation')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>


    {{-- ============================================================
        ÉTAT DE LA PARCELLE
    ============================================================ --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

        <label class="flex items-center gap-3 rounded-xl border border-slate-200 p-4">

            <input type="checkbox"
                   name="estCultivee"
                   value="1"
                   {{ old('estCultivee', $parcelle->estCultivee ?? true) ? 'checked' : '' }}
                   class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">

            <span class="text-sm font-medium text-slate-700">
                Parcelle cultivée
            </span>

        </label>


        <label class="flex items-center gap-3 rounded-xl border border-slate-200 p-4">

            <input type="checkbox"
                   name="estJachere"
                   value="1"
                   {{ old('estJachere', $parcelle->estJachere ?? false) ? 'checked' : '' }}
                   class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">

            <span class="text-sm font-medium text-slate-700">
                Jachère
            </span>

        </label>


        <label class="flex items-center gap-3 rounded-xl border border-slate-200 p-4">

            <input type="checkbox"
                   name="presenceArbres"
                   value="1"
                   {{ old('presenceArbres', $parcelle->presenceArbres ?? false) ? 'checked' : '' }}
                   class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">

            <span class="text-sm font-medium text-slate-700">
                Présence d'arbres
            </span>

        </label>

    </div>


    {{-- ============================================================
        OBSERVATIONS
    ============================================================ --}}
    <div>

        <label for="observations"
               class="mb-2 block text-sm font-semibold text-slate-700">
            Observations
        </label>

        <textarea name="observations"
                  id="observations"
                  rows="4"
                  class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                  placeholder="Informations complémentaires...">{{ old('observations', $parcelle->observations ?? '') }}</textarea>

        @error('observations')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>

</div>