@csrf

<div class="space-y-6">

    <div class="bg-white rounded-xl border p-6 space-y-5">

        <h2 class="text-lg font-semibold text-slate-700">
            Informations sur l'étape
        </h2>

        <div>
            <label class="block text-sm font-medium mb-2">
                Libellé de l'étape
            </label>

            <input
                type="text"
                name="libelle"
                value="{{ old('libelle', $etape->libelle ?? '') }}"
                class="w-full rounded-lg border-slate-300 focus:border-[#266486] focus:ring-[#266486]"
                placeholder="Ex : Formation des agents recenseurs">

            @error('libelle')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">
                Description (optionnelle)
            </label>

            <textarea
                name="description"
                rows="4"
                class="w-full rounded-lg border-slate-300 focus:border-[#266486] focus:ring-[#266486]">{{ old('description', $etape->description ?? '') }}</textarea>

            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

            <div>
                <label class="block text-sm font-medium mb-2">
                    Ordre
                </label>

                <input
                    type="number"
                    min="1"
                    name="ordre"
                    value="{{ old('ordre', $etape->ordre ?? '') }}"
                    class="w-full rounded-lg border-slate-300 focus:border-[#266486] focus:ring-[#266486]">

                @error('ordre')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">
                    Date de début
                </label>

                <input
                    type="datetime-local"
                    name="dateDebut"
                    min="{{ $planification->dateDebut->format('Y-m-d\TH:i') }}"
                    max="{{ optional($planification->dateFin)->format('Y-m-d\TH:i') }}"
                    value="{{ old('dateDebut', isset($etape) ? $etape->dateDebut->format('Y-m-d\TH:i') : '') }}"
                    class="w-full rounded-lg border-slate-300 focus:border-[#266486] focus:ring-[#266486]">

                @error('dateDebut')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">
                    Date de fin
                </label>

                <input
                    type="datetime-local"
                    name="dateFin"
                    min="{{ $planification->dateDebut->format('Y-m-d\TH:i') }}"
                    max="{{ optional($planification->dateFin)->format('Y-m-d\TH:i') }}"
                    value="{{ old('dateFin', isset($etape) ? $etape->dateFin->format('Y-m-d\TH:i') : '') }}"
                    class="w-full rounded-lg border-slate-300 focus:border-[#266486] focus:ring-[#266486]">

                @error('dateFin')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>

    </div>

    <div class="flex justify-end gap-3">

        <a href="{{ route('dpa.planification.etapes.index', $planification) }}"
           class="px-5 py-2 rounded-lg border">
            Annuler
        </a>

        <button
            class="px-6 py-2 rounded-lg bg-[#266486] text-white hover:bg-[#1f5570]">
            Enregistrer l'étape
        </button>

    </div>

</div>