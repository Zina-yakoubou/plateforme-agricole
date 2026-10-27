@extends('layouts.app')

@section('title', 'Barèmes agronomiques')

@section('content')

<div
    class="space-y-6"
    x-data="{ openModal: {{ $errors->any() ? 'true' : 'false' }} }"
    @keydown.escape.window="openModal = false"
>

    {{-- =========================================================
        EN-TÊTE
    ========================================================== --}}
    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-semibold tracking-tight text-[#212529]">
                    Barèmes agronomiques
                </h1>
                <span class="rounded-full bg-[#e5f2ee] px-3 py-1 text-xs font-semibold text-[#006a4f]">
                    SIRA-Mô
                </span>
            </div>

            <p class="mt-1 text-sm text-[#6b7280]">
                Référentiel des doses par culture et par intrant (par hectare) et des plafonds
                par producteur, utilisés pour calculer les besoins et les quotas.
            </p>
        </div>

        <button
            type="button"
            @click="openModal = true"
            class="inline-flex items-center gap-2 rounded-lg bg-[#006a4f] px-4 py-2.5
                   text-sm font-semibold text-white shadow-sm transition
                   hover:bg-[#156c52] focus:outline-none focus:ring-2 focus:ring-[#006a4f]/30"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouveau barème
        </button>

    </div>


    {{-- =========================================================
        MESSAGES
    ========================================================== --}}
    {{-- @if(session('success'))
        <div class="flex items-start gap-3 rounded-lg border border-[#b7dfd2] bg-[#e5f2ee] px-4 py-3 text-sm text-[#006a4f]">
            <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif --}}

    @if(session('error'))
        <div class="flex items-start gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="flex items-start gap-3 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
            </svg>
            <span>Saisie invalide : veuillez vérifier les informations saisies dans la fenêtre d'ajout.</span>
        </div>
    @endif


    {{-- =========================================================
        FILTRES / RECHERCHE
    ========================================================== --}}
    <div class="rounded-lg border border-[#e5e7eb] bg-white p-4 shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

        <form method="GET" action="{{ route('dpa.baremes.index') }}">

            <div class="flex flex-col gap-3 lg:flex-row">

                {{-- RECHERCHE --}}
                <div class="relative flex-1">
                    <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m21 21-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"/>
                    </svg>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search ?? request('search') }}"
                        placeholder="Rechercher par culture ou intrant..."
                        class="w-full rounded-lg border border-[#e5e7eb] bg-white py-3 pl-11 pr-4
                               text-sm text-[#212529] placeholder:text-gray-400
                               focus:border-[#006a4f] focus:outline-none focus:ring-2 focus:ring-[#006a4f]/10"
                    >
                </div>

                {{-- STATUT --}}
                <select
                    name="statut"
                    class="rounded-lg border border-[#e5e7eb] bg-white px-4 py-3 text-sm text-[#212529]
                           focus:border-[#006a4f] focus:outline-none focus:ring-2 focus:ring-[#006a4f]/10"
                >
                    <option value="">Tous les statuts</option>
                    <option value="actif" @selected(request('statut') === 'actif')>Actif</option>
                    <option value="inactif" @selected(request('statut') === 'inactif')>Inactif</option>
                </select>

                {{-- RECHERCHER --}}
                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#006a4f]
                           px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#156c52]"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m21 21-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"/>
                    </svg>
                    Rechercher
                </button>

                {{-- RÉINITIALISER --}}
                @if(request()->filled('search') || request()->filled('statut'))
                    <a
                        href="{{ route('dpa.baremes.index') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-[#e5e7eb]
                               bg-white px-4 py-3 text-sm font-semibold text-gray-600 transition hover:bg-gray-50"
                    >
                        Réinitialiser
                    </a>
                @endif

            </div>

        </form>

    </div>


    {{-- =========================================================
        LISTE DES BARÈMES
    ========================================================== --}}
    <div class="overflow-hidden rounded-lg border border-[#e5e7eb] bg-white shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

        {{-- EN-TÊTE DU TABLEAU --}}
        <div class="flex flex-col gap-3 border-b border-[#e5e7eb] px-6 py-5 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h2 class="text-base font-semibold text-[#212529]">Liste des barèmes</h2>
                <p class="mt-1 text-xs text-gray-500">
                    Barèmes enregistrés dans le référentiel SIRA-Mô
                </p>
            </div>

            <span class="rounded-full bg-[#f8faf9] px-3 py-1.5 text-sm text-gray-500">
                <span class="font-semibold text-[#006a4f]">
                    {{ method_exists($baremes, 'total') ? $baremes->total() : $baremes->count() }}
                </span>
                barème(s)
            </span>

        </div>

        {{-- TABLEAU --}}
        <div class="overflow-x-auto">

            <table class="w-full min-w-[900px] text-sm">

                <thead class="bg-[#f8faf9]">
                    <tr class="border-b border-[#e5e7eb]">
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">N°</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Culture</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Intrant</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Dose recommandée</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Plafond / producteur</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">Statut</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#e5e7eb]">

                    @forelse($baremes as $b)

                        <tr class="group transition hover:bg-[#f8faf9]">

                            {{-- N° --}}
                            <td class="px-6 py-4 text-sm text-gray-400">
                                @if(method_exists($baremes, 'currentPage'))
                                    {{ $loop->iteration + (($baremes->currentPage() - 1) * $baremes->perPage()) }}
                                @else
                                    {{ $loop->iteration }}
                                @endif
                            </td>

                            {{-- CULTURE --}}
                            <td class="px-6 py-4">
                                <div class="font-semibold text-[#212529]">
                                    {{ $b->culture->nomCulture ?? '-' }}
                                </div>
                                @if(!empty($b->culture?->categorie))
                                    <span class="mt-1 inline-flex rounded-full bg-[#e5f2ee] px-2 py-0.5 text-[10px] font-semibold text-[#006a4f]">
                                        {{ $b->culture->categorie }}
                                    </span>
                                @endif
                            </td>

                            {{-- INTRANT --}}
                            <td class="px-6 py-4 text-[#434343]">
                                @if($b->intrant)
                                    {{ $b->intrant->nom }}
                                @else
                                    <span class="inline-flex rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-semibold text-red-700">
                                        Intrant manquant
                                    </span>
                                @endif
                            </td>

                            {{-- DOSE --}}
                            <td class="px-6 py-4 font-bold text-[#006a4f]">
                                {{ rtrim(rtrim(number_format($b->dose_par_hectare, 2, ',', ' '), '0'), ',') }}
                                <span class="font-medium text-gray-500">{{ $b->unite }}</span>
                            </td>

                            {{-- PLAFOND --}}
                            <td class="px-6 py-4 text-[#434343]">
                                @if($b->plafond_max_producteur)
                                    {{ number_format($b->plafond_max_producteur, 2, ',', ' ') }} kg
                                @else
                                    <span class="text-xs text-gray-400">Sans limite</span>
                                @endif
                            </td>

                            {{-- STATUT --}}
                            <td class="px-6 py-4 text-center">
                                @if($b->est_actif)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                        Actif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                        <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                        Inactif
                                    </span>
                                @endif
                            </td>

                            {{-- ACTIONS --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">

                                    {{-- ACTIVER / DÉSACTIVER --}}
                                    <form method="POST" action="{{ route('dpa.baremes.toggle', $b->idBareme) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button
                                            type="submit"
                                            title="{{ $b->est_actif ? 'Désactiver' : 'Activer' }}"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-[#e5e7eb]
                                                   bg-white text-gray-600 transition
                                                   {{ $b->est_actif
                                                        ? 'hover:border-amber-500 hover:bg-amber-50 hover:text-amber-600'
                                                        : 'hover:border-[#006a4f] hover:bg-[#e5f2ee] hover:text-[#006a4f]' }}"
                                        >
                                            @if($b->est_actif)
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            @else
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            @endif
                                        </button>
                                    </form>

                                    {{-- SUPPRIMER --}}
                                    <form
                                        method="POST"
                                        action="{{ route('dpa.baremes.destroy', $b->idBareme) }}"
                                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce barème ?')"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            title="Supprimer"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-[#e5e7eb]
                                                   bg-white text-gray-600 transition
                                                   hover:border-red-500 hover:bg-red-50 hover:text-red-600"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.87 12.14A2 2 0 0116.14 21H7.86a2 2 0 01-1.99-1.86L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">

                                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-[#e5f2ee] text-[#006a4f]">
                                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                    </div>

                                    <p class="font-semibold text-[#212529]">Aucun barème trouvé</p>

                                    <p class="mt-1 text-sm text-gray-500">
                                        @if(request()->filled('search') || request()->filled('statut'))
                                            Aucun barème ne correspond aux critères sélectionnés.
                                        @else
                                            Cliquez sur « Nouveau barème » pour ajouter une règle de dosage.
                                        @endif
                                    </p>

                                    @if(request()->filled('search') || request()->filled('statut'))
                                        <a href="{{ route('dpa.baremes.index') }}"
                                           class="mt-4 text-sm font-semibold text-[#006a4f] hover:underline">
                                            Réinitialiser les filtres
                                        </a>
                                    @endif

                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- PAGINATION --}}
        @if(method_exists($baremes, 'hasPages') && $baremes->hasPages())
            <div class="border-t border-[#e5e7eb] px-6 py-4">
                {{ $baremes->withQueryString()->links() }}
            </div>
        @endif

    </div>


    {{-- =========================================================
        MODALE : NOUVEAU BARÈME
    ========================================================== --}}
    <div
        x-show="openModal"
        x-cloak
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm"
    >
        <div
            @click.outside="openModal = false"
            class="max-h-[90vh] w-full max-w-lg space-y-5 overflow-y-auto rounded-2xl border border-gray-100 bg-white p-6 shadow-2xl"
        >

            {{-- Titre --}}
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Ajouter un barème</h3>
                    <p class="text-xs text-gray-500">
                        Choisissez une culture et un intrant existants, ou saisissez-en de nouveaux.
                    </p>
                </div>
                <button type="button" @click="openModal = false"
                        class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form action="{{ route('dpa.baremes.store') }}" method="POST" class="space-y-4">
                @csrf

                {{-- CULTURE --}}
                <div x-data="{ nouvelleCulture: {{ old('nouvelle_culture') ? 'true' : 'false' }} }" class="space-y-3">
                    <label class="block text-xs font-semibold text-gray-700">Culture</label>

                    {{-- Existante --}}
                    <div x-show="!nouvelleCulture" class="flex gap-2">
                        <select name="culture_id" :disabled="nouvelleCulture"
                                class="w-full rounded-xl border-gray-300 text-sm focus:border-[#006a4f] focus:ring-[#006a4f]">
                            <option value="">Sélectionnez une culture existante...</option>
                            @foreach($cultures as $c)
                                <option value="{{ $c->idCulture }}" @selected(old('culture_id') == $c->idCulture)>
                                    {{ $c->nomCulture }}
                                </option>
                            @endforeach
                        </select>
                        <button type="button" @click="nouvelleCulture = true"
                                class="shrink-0 rounded-xl border border-gray-200 bg-gray-100 px-3.5 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-200">
                            + Autre
                        </button>
                    </div>

                    {{-- Nouvelle --}}
                    <div x-show="nouvelleCulture" x-cloak class="flex gap-2">
                        <input type="text" name="nouvelle_culture" :disabled="!nouvelleCulture"
                               value="{{ old('nouvelle_culture') }}" placeholder="Nom de la nouvelle culture"
                               class="w-full rounded-xl border-gray-300 text-sm focus:border-[#006a4f] focus:ring-[#006a4f]">
                        <button type="button" @click="nouvelleCulture = false"
                                class="shrink-0 rounded-xl border border-gray-200 bg-gray-100 px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-200">
                            Liste
                        </button>
                    </div>

                    {{-- CATÉGORIE DE LA CULTURE (Toujours visible et obligatoire pour éviter l'erreur SQL) --}}
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-600">Catégorie de la culture</label>
                        <select name="categorie" id="categorie" class="w-full rounded-xl border-gray-300 text-sm focus:border-[#006a4f] focus:ring-[#006a4f]" required>
                            <option value="">Sélectionnez une catégorie...</option>
                            <option value="vivriere" @selected(old('categorie') == 'vivriere')>Vivrière</option>
                            <option value="rente" @selected(old('categorie') == 'rente')>Rente</option>
                            <option value="maraichere" @selected(old('categorie') == 'maraichere')>Maraîchère</option>
                            <option value="fruitiere" @selected(old('categorie') == 'fruitiere')>Fruitière</option>
                            <option value="fourragere" @selected(old('categorie') == 'fourragere')>Fourragère</option>
                            <option value="autre" @selected(old('categorie') == 'autre')>Autre</option>
                        </select>
                        @error('categorie')
                            <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    @error('culture_id') <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p> @enderror
                    @error('nouvelle_culture') <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- INTRANT --}}
                <div x-data="{ nouvelIntrant: {{ old('nouvel_intrant') ? 'true' : 'false' }} }" class="space-y-3">
                    <label class="block text-xs font-semibold text-gray-700">Intrant</label>

                    {{-- Existant --}}
                    <div x-show="!nouvelIntrant" class="flex gap-2">
                        <select name="intrant_id" :disabled="nouvelIntrant"
                                class="w-full rounded-xl border-gray-300 text-sm focus:border-[#006a4f] focus:ring-[#006a4f]">
                            <option value="">Sélectionnez un intrant existant...</option>
                            @foreach($intrants as $i)
                                <option value="{{ $i->idIntrant }}" @selected(old('intrant_id') == $i->idIntrant)>
                                    {{ $i->nom }}
                                </option>
                            @endforeach
                        </select>
                        <button type="button" @click="nouvelIntrant = true"
                                class="shrink-0 rounded-xl border border-gray-200 bg-gray-100 px-3.5 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-200">
                            + Autre
                        </button>
                    </div>

                    {{-- Nouveau --}}
                    <div x-show="nouvelIntrant" x-cloak class="flex gap-2">
                        <input type="text" name="nouvel_intrant" :disabled="!nouvelIntrant"
                               value="{{ old('nouvel_intrant') }}" placeholder="Nom du nouvel intrant"
                               class="w-full rounded-xl border-gray-300 text-sm focus:border-[#006a4f] focus:ring-[#006a4f]">
                        <button type="button" @click="nouvelIntrant = false"
                                class="shrink-0 rounded-xl border border-gray-200 bg-gray-100 px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-200">
                            Liste
                        </button>
                    </div>

                    @error('intrant_id') <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p> @enderror
                    @error('nouvel_intrant') <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- DOSE & UNITÉ --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-700">Dose / hectare</label>
                        <input type="number" step="0.01" min="0.01" name="dose_par_hectare"
                               value="{{ old('dose_par_hectare') }}" required placeholder="Ex : 150"
                               class="w-full rounded-xl text-sm focus:border-[#006a4f] focus:ring-[#006a4f] @error('dose_par_hectare') border-red-500 @else border-gray-300 @enderror">
                        @error('dose_par_hectare') <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-700">Unité</label>
                        <input type="text" name="unite" value="{{ old('unite', 'kg/ha') }}" required
                               class="w-full rounded-xl text-sm focus:border-[#006a4f] focus:ring-[#006a4f] @error('unite') border-red-500 @else border-gray-300 @enderror">
                        @error('unite') <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- PLAFOND --}}
                <div>
                    <label class="mb-1 block text-xs font-semibold text-gray-700">Plafond max par producteur (kg)</label>
                    <input type="number" step="0.01" min="0" name="plafond_max_producteur"
                           value="{{ old('plafond_max_producteur') }}" placeholder="Ex : 500 (optionnel)"
                           class="w-full rounded-xl text-sm focus:border-[#006a4f] focus:ring-[#006a4f] @error('plafond_max_producteur') border-red-500 @else border-gray-300 @enderror">
                    @error('plafond_max_producteur') <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- BOUTONS --}}
                <div class="flex justify-end gap-2 border-t border-gray-100 pt-4">
                    <button type="button" @click="openModal = false"
                            class="rounded-xl border border-gray-200 px-4 py-2 text-xs font-semibold text-gray-600 transition hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit"
                            class="rounded-xl bg-[#006a4f] px-5 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-[#156c52]">
                        Enregistrer le barème
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>

@endsection