@csrf

<div class="space-y-6">

    {{-- LIBELLÉ --}}
    <div>
        <label for="libelle" class="block text-sm font-medium text-slate-700 mb-1">
            Libellé de la Saison <span class="text-red-500">*</span>
        </label>
        <input type="text" 
               id="libelle" 
               name="libelle" 
               value="{{ old('libelle', $saisonDistribution->libelle ?? '') }}" 
               required 
               placeholder="Ex: Saison Hivernage 2026"
               class="w-full rounded-xl border {{ $errors->has('libelle') ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-slate-300 focus:border-emerald-500 focus:ring-emerald-500' }} bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm focus:outline-none focus:ring-1">
        @error('libelle')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- DATES DE DÉBUT ET FIN --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label for="dateDebut" class="block text-sm font-medium text-slate-700 mb-1">
                Date de Début <span class="text-red-500">*</span>
            </label>
            <input type="date" 
                   id="dateDebut" 
                   name="dateDebut" 
                   value="{{ old('dateDebut', $saisonDistribution->dateDebut ?? '') }}" 
                   required 
                   class="w-full rounded-xl border {{ $errors->has('dateDebut') ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-slate-300 focus:border-emerald-500 focus:ring-emerald-500' }} bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm focus:outline-none focus:ring-1">
            @error('dateDebut')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="dateFin" class="block text-sm font-medium text-slate-700 mb-1">
                Date de Fin
            </label>
            <input type="date" 
                   id="dateFin" 
                   name="dateFin" 
                   value="{{ old('dateFin', $saisonDistribution->dateFin ?? '') }}" 
                   class="w-full rounded-xl border {{ $errors->has('dateFin') ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-slate-300 focus:border-emerald-500 focus:ring-emerald-500' }} bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm focus:outline-none focus:ring-1">
            @error('dateFin')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- STATUT --}}
    <div>
        <label for="statut" class="block text-sm font-medium text-slate-700 mb-1">
            Statut initial <span class="text-red-500">*</span>
        </label>
        @php $currentStatut = old('statut', $saisonDistribution->statut ?? 'planifiee'); @endphp
        <select id="statut" 
                name="statut" 
                required 
                class="w-full rounded-xl border {{ $errors->has('statut') ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-slate-300 focus:border-emerald-500 focus:ring-emerald-500' }} bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm focus:outline-none focus:ring-1">
            <option value="planifiee" {{ $currentStatut == 'planifiee' ? 'selected' : '' }}>Planifiée</option>
            <option value="ouverte" {{ $currentStatut == 'ouverte' ? 'selected' : '' }}>Ouverte</option>
            <option value="cloturee" {{ $currentStatut == 'cloturee' ? 'selected' : '' }}>Clôturée</option>
            <option value="archivee" {{ $currentStatut == 'archivee' ? 'selected' : '' }}>Archivée</option>
        </select>
        @error('statut')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- CAMPAGNE DE RECENSEMENT DE BASE --}}
    <div>
        <label for="campagneBase_id" class="block text-sm font-medium text-slate-700 mb-1">
            Campagne de Recensement de Base (Quotas)
        </label>
        @php $currentCampagneId = old('campagneBase_id', $saisonDistribution->campagneBase_id ?? ''); @endphp
        <select id="campagneBase_id" 
                name="campagneBase_id" 
                class="w-full rounded-xl border {{ $errors->has('campagneBase_id') ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-slate-300 focus:border-emerald-500 focus:ring-emerald-500' }} bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm focus:outline-none focus:ring-1">
            <option value="">-- Aucune (Indépendant) --</option>
            @foreach($campagnesRecensement as $campagne)
                <option value="{{ $campagne->idCampagne }}" {{ $currentCampagneId == $campagne->idCampagne ? 'selected' : '' }}>
                    {{ $campagne->libelle }}
                </option>
            @endforeach
        </select>
        @error('campagneBase_id')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
        <p class="mt-1 text-xs text-slate-500">Sélectionnez la campagne de recensement qui servira de référence pour les allocations.</p>
    </div>

    {{-- CASE À COCHER --}}
    <div class="flex items-center gap-3 pt-2">
        <input type="checkbox" 
               id="baseSurDerniereAnnee" 
               name="baseSurDerniereAnnee" 
               value="1" 
               {{ old('baseSurDerniereAnnee', $saisonDistribution->baseSurDerniereAnnee ?? false) ? 'checked' : '' }}
               class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
        <label for="baseSurDerniereAnnee" class="text-sm font-medium text-slate-700">
            Baser les allocations sur les données de la dernière année
        </label>
    </div>

    {{-- BOUTON DE SOUMISSION --}}
    <div class="pt-4">
        <button type="submit" 
                class="w-full rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition">
            {{ $submitButtonText ?? 'Enregistrer' }}
        </button>
    </div>

</div>