@extends('layouts.app')

@section('title', 'Référentiel cultures et intrants')

@section('content')

@php
    $erreursCulture = $errors->getBag('culture');
    $erreursIntrant = $errors->getBag('intrant');
    $erreursLiens   = $errors->getBag('liens');
@endphp

<div
    class="space-y-6"
    x-data="{
        onglet: @js($onglet),
        rechC: '',
        rechI: '',
        rechLien: '',

        modalCulture: {{ $erreursCulture->any() ? 'true' : 'false' }},
        modalIntrant: {{ $erreursIntrant->any() ? 'true' : 'false' }},
        modalLiens: {{ $erreursLiens->any() ? 'true' : 'false' }},

        c: { id: @js(old('culture_id', '')), nom: @js(old('nomCulture', '')), categorie: @js(old('categorie', '')) },
        i: { id: @js(old('intrant_id', '')), nom: @js(old('nomIntrant', '')), type: @js(old('type', '')), unite: @js(old('unite', 'kg')) },
        lien: { id: '', nom: '', ids: [] },

        liens: @js($liens),
        tousIntrants: @js($intrants->where('actif', true)->values()),

        normaliser(t) {
            return (t ?? '').toString().normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
        },
        correspond(texte, q) {
            return this.normaliser(texte).includes(this.normaliser(q));
        },

        nouvelleCulture() {
            this.c = { id: '', nom: '', categorie: '' };
            this.modalCulture = true;
        },
        modifierCulture(x) {
            this.c = { id: x.idCulture, nom: x.nomCulture, categorie: x.categorie };
            this.modalCulture = true;
        },
        nouvelIntrant() {
            this.i = { id: '', nom: '', type: '', unite: 'kg' };
            this.modalIntrant = true;
        },
        modifierIntrant(x) {
            this.i = { id: x.idIntrant, nom: x.nom, type: x.type, unite: x.unite };
            this.modalIntrant = true;
        },
        ouvrirLiens(x) {
            this.lien = {
                id: x.idCulture,
                nom: x.nomCulture,
                ids: (this.liens[x.idCulture] ?? []).map(String)
            };
            this.rechLien = '';
            this.modalLiens = true;
        }
    }"
    @keydown.escape.window="modalCulture = false; modalIntrant = false; modalLiens = false"
>

    {{-- EN-TÊTE --}}
    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-semibold tracking-tight text-[#212529]">Référentiel</h1>
                <span class="rounded-full bg-[#e5f2ee] px-3 py-1 text-xs font-semibold text-[#006a4f]">SIRA-Mô</span>
            </div>
            <p class="mt-1 text-sm text-[#6b7280]">
                Cultures, intrants et intrants possibles par culture. Les DPA choisissent uniquement
                dans ce référentiel lorsqu'ils définissent un barème.
            </p>
        </div>

        <button type="button" x-show="onglet === 'cultures'" @click="nouvelleCulture()"
                class="inline-flex items-center gap-2 rounded-lg bg-[#006a4f] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#156c52]">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouvelle culture
        </button>

        <button type="button" x-show="onglet === 'intrants'" x-cloak @click="nouvelIntrant()"
                class="inline-flex items-center gap-2 rounded-lg bg-[#006a4f] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#156c52]">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouvel intrant
        </button>
    </div>

    {{-- ONGLETS --}}
    <div class="flex gap-1 border-b border-[#e5e7eb]">
        <button type="button" @click="onglet = 'cultures'"
                :class="onglet === 'cultures' ? 'border-[#006a4f] text-[#006a4f]' : 'border-transparent text-gray-500 hover:text-gray-700'"
                class="-mb-px border-b-2 px-4 py-2.5 text-sm font-semibold transition">
            Cultures
            <span class="ml-1 rounded-full bg-[#e5f2ee] px-2 py-0.5 text-xs text-[#006a4f]">{{ $cultures->count() }}</span>
        </button>
        <button type="button" @click="onglet = 'intrants'"
                :class="onglet === 'intrants' ? 'border-[#006a4f] text-[#006a4f]' : 'border-transparent text-gray-500 hover:text-gray-700'"
                class="-mb-px border-b-2 px-4 py-2.5 text-sm font-semibold transition">
            Intrants
            <span class="ml-1 rounded-full bg-[#e5f2ee] px-2 py-0.5 text-xs text-[#006a4f]">{{ $intrants->count() }}</span>
        </button>
    </div>


    {{-- =========================== ONGLET CULTURES =========================== --}}
    <div x-show="onglet === 'cultures'" class="space-y-4">

        <div class="overflow-hidden rounded-lg border border-[#e5e7eb] bg-white shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

            {{-- Barre de recherche dans le tableau --}}
            <div class="flex flex-col gap-3 border-b border-[#e5e7eb] px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-base font-semibold text-[#212529]">Liste des cultures</h2>

                <div class="relative w-full sm:max-w-xs">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m21 21-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"/>
                    </svg>
                    <input type="text" x-model="rechC" placeholder="Rechercher une culture..."
                           class="w-full rounded-lg border border-[#e5e7eb] bg-white py-2 pl-9 pr-3 text-sm text-[#212529]
                                  placeholder:text-gray-400 focus:border-[#006a4f] focus:outline-none focus:ring-2 focus:ring-[#006a4f]/10">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px] text-sm">
                    <thead class="bg-[#f8faf9]">
                        <tr class="border-b border-[#e5e7eb]">
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Culture</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Catégorie</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Intrants possibles</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Barèmes</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">Statut</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e5e7eb]">
                        @forelse($cultures as $c)
                            @php $nbLiens = $liens->get($c->idCulture, collect())->count(); @endphp
                            <tr class="transition hover:bg-[#f8faf9]"
                                data-nom="{{ $c->nomCulture }}"
                                x-show="correspond($el.dataset.nom, rechC)">

                                <td class="px-6 py-4 font-semibold text-[#212529]">{{ $c->nomCulture }}</td>

                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-full bg-[#e5f2ee] px-2.5 py-0.5 text-xs font-semibold text-[#006a4f]">
                                        {{ $categories[$c->categorie] ?? $c->categorie }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <button type="button" @click="ouvrirLiens(@js($c))"
                                            class="inline-flex items-center gap-2 rounded-lg border border-[#e5e7eb] bg-white px-3 py-1.5 text-xs font-semibold transition hover:border-[#006a4f] hover:bg-[#e5f2ee]
                                                   {{ $nbLiens ? 'text-[#006a4f]' : 'text-amber-700' }}">
                                        @if($nbLiens)
                                            {{ $nbLiens }} intrant(s) · Gérer
                                        @else
                                            Aucun intrant · Définir
                                        @endif
                                    </button>
                                </td>

                                <td class="px-6 py-4 text-[#434343]">{{ $nbBaremesParCulture->get($c->idCulture, 0) }}</td>

                                <td class="px-6 py-4 text-center">
                                    @if($c->active)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span> Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                            <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span> Inactive
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" title="Modifier" @click="modifierCulture(@js($c))"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-[#e5e7eb] bg-white text-gray-600 transition hover:border-[#006a4f] hover:bg-[#e5f2ee] hover:text-[#006a4f]">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.5-9.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 8.5-8.5z"/>
                                            </svg>
                                        </button>

                                        <form method="POST" action="{{ route('admin.referentiel.cultures.toggle') }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="culture_id" value="{{ $c->idCulture }}">
                                            <button type="submit" title="{{ $c->active ? 'Désactiver' : 'Activer' }}"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-[#e5e7eb] bg-white text-gray-600 transition
                                                           {{ $c->active ? 'hover:border-amber-500 hover:bg-amber-50 hover:text-amber-600' : 'hover:border-[#006a4f] hover:bg-[#e5f2ee] hover:text-[#006a4f]' }}">
                                                @if($c->active)
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
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-14 text-center text-sm text-gray-500">
                                    Aucune culture. Cliquez sur « Nouvelle culture » pour commencer.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    {{-- =========================== ONGLET INTRANTS =========================== --}}
    <div x-show="onglet === 'intrants'" x-cloak class="space-y-4">

        <div class="overflow-hidden rounded-lg border border-[#e5e7eb] bg-white shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

            {{-- Barre de recherche dans le tableau --}}
            <div class="flex flex-col gap-3 border-b border-[#e5e7eb] px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-base font-semibold text-[#212529]">Liste des intrants</h2>

                <div class="relative w-full sm:max-w-xs">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m21 21-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"/>
                    </svg>
                    <input type="text" x-model="rechI" placeholder="Rechercher un intrant..."
                           class="w-full rounded-lg border border-[#e5e7eb] bg-white py-2 pl-9 pr-3 text-sm text-[#212529]
                                  placeholder:text-gray-400 focus:border-[#006a4f] focus:outline-none focus:ring-2 focus:ring-[#006a4f]/10">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px] text-sm">
                    <thead class="bg-[#f8faf9]">
                        <tr class="border-b border-[#e5e7eb]">
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Intrant</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Type</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Unité</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Cultures liées</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Barèmes</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">Statut</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e5e7eb]">
                        @forelse($intrants as $in)
                            <tr class="transition hover:bg-[#f8faf9]"
                                data-nom="{{ $in->nom }}"
                                x-show="correspond($el.dataset.nom, rechI)">

                                <td class="px-6 py-4 font-semibold text-[#212529]">{{ $in->nom }}</td>
                                <td class="px-6 py-4 text-[#434343]">{{ ucfirst((string) $in->type) }}</td>
                                <td class="px-6 py-4 text-[#434343]">{{ $in->unite }}</td>
                                <td class="px-6 py-4 text-[#434343]">{{ $nbCulturesParIntrant->get($in->idIntrant, 0) }}</td>
                                <td class="px-6 py-4 text-[#434343]">{{ $nbBaremesParIntrant->get($in->idIntrant, 0) }}</td>

                                <td class="px-6 py-4 text-center">
                                    @if($in->actif)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span> Actif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                            <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span> Inactif
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" title="Modifier" @click="modifierIntrant(@js($in))"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-[#e5e7eb] bg-white text-gray-600 transition hover:border-[#006a4f] hover:bg-[#e5f2ee] hover:text-[#006a4f]">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.5-9.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 8.5-8.5z"/>
                                            </svg>
                                        </button>

                                        <form method="POST" action="{{ route('admin.referentiel.intrants.toggle') }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="intrant_id" value="{{ $in->idIntrant }}">
                                            <button type="submit" title="{{ $in->actif ? 'Désactiver' : 'Activer' }}"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-[#e5e7eb] bg-white text-gray-600 transition
                                                           {{ $in->actif ? 'hover:border-amber-500 hover:bg-amber-50 hover:text-amber-600' : 'hover:border-[#006a4f] hover:bg-[#e5f2ee] hover:text-[#006a4f]' }}">
                                                @if($in->actif)
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
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-14 text-center text-sm text-gray-500">
                                    Aucun intrant. Cliquez sur « Nouvel intrant » pour commencer.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    {{-- =========================== MODALE : CULTURE =========================== --}}
    <div x-show="modalCulture" x-cloak x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm">
        <div @click.outside="modalCulture = false"
             class="w-full max-w-md space-y-5 rounded-2xl border border-gray-100 bg-white p-6 shadow-2xl">

            <div>
                <h3 class="text-lg font-bold text-gray-900" x-text="c.id ? 'Modifier la culture' : 'Nouvelle culture'"></h3>
                <p class="text-xs text-gray-500">Les doublons (accents, majuscules) sont refusés automatiquement.</p>
            </div>

            @if($erreursCulture->any())
                <div class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-800">
                    @foreach($erreursCulture->all() as $e) <p>{{ $e }}</p> @endforeach
                </div>
            @endif

            <form method="POST"
                  :action="c.id ? '{{ route('admin.referentiel.cultures.update') }}' : '{{ route('admin.referentiel.cultures.store') }}'"
                  class="space-y-4">
                @csrf
                <template x-if="c.id"><input type="hidden" name="_method" value="PUT"></template>
                <input type="hidden" name="culture_id" :value="c.id">

                <div>
                    <label class="mb-1 block text-xs font-semibold text-gray-700">Nom de la culture</label>
                    <input type="text" name="nomCulture" x-model="c.nom" required maxlength="100" placeholder="Ex : Maïs"
                           class="w-full rounded-xl border-gray-300 text-sm focus:border-[#006a4f] focus:ring-[#006a4f]">
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold text-gray-700">Catégorie</label>
                    <select name="categorie" x-model="c.categorie" required
                            class="w-full rounded-xl border-gray-300 text-sm focus:border-[#006a4f] focus:ring-[#006a4f]">
                        <option value="">Sélectionnez une catégorie...</option>
                        @foreach($categories as $val => $lib)
                            <option value="{{ $val }}">{{ $lib }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end gap-2 border-t border-gray-100 pt-4">
                    <button type="button" @click="modalCulture = false"
                            class="rounded-xl border border-gray-200 px-4 py-2 text-xs font-semibold text-gray-600 transition hover:bg-gray-50">Annuler</button>
                    <button type="submit"
                            class="rounded-xl bg-[#006a4f] px-5 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-[#156c52]">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>


    {{-- =========================== MODALE : INTRANT =========================== --}}
    <div x-show="modalIntrant" x-cloak x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm">
        <div @click.outside="modalIntrant = false"
             class="w-full max-w-md space-y-5 rounded-2xl border border-gray-100 bg-white p-6 shadow-2xl">

            <div>
                <h3 class="text-lg font-bold text-gray-900" x-text="i.id ? 'Modifier l\'intrant' : 'Nouvel intrant'"></h3>
                <p class="text-xs text-gray-500">Les doublons (accents, majuscules) sont refusés automatiquement.</p>
            </div>

            @if($erreursIntrant->any())
                <div class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-800">
                    @foreach($erreursIntrant->all() as $e) <p>{{ $e }}</p> @endforeach
                </div>
            @endif

            <form method="POST"
                  :action="i.id ? '{{ route('admin.referentiel.intrants.update') }}' : '{{ route('admin.referentiel.intrants.store') }}'"
                  class="space-y-4">
                @csrf
                <template x-if="i.id"><input type="hidden" name="_method" value="PUT"></template>
                <input type="hidden" name="intrant_id" :value="i.id">

                <div>
                    <label class="mb-1 block text-xs font-semibold text-gray-700">Nom de l'intrant</label>
                    <input type="text" name="nomIntrant" x-model="i.nom" required maxlength="150" placeholder="Ex : NPK 15-15-15"
                           class="w-full rounded-xl border-gray-300 text-sm focus:border-[#006a4f] focus:ring-[#006a4f]">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-700">Type</label>
                        <select name="type" x-model="i.type" required
                                class="w-full rounded-xl border-gray-300 text-sm focus:border-[#006a4f] focus:ring-[#006a4f]">
                            <option value="">Sélectionnez...</option>
                            @foreach($types as $t)
                                <option value="{{ $t }}">{{ ucfirst($t) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-700">Unité</label>
                        <select name="unite" x-model="i.unite" required
                                class="w-full rounded-xl border-gray-300 text-sm focus:border-[#006a4f] focus:ring-[#006a4f]">
                            @foreach($unites as $u)
                                <option value="{{ $u }}">{{ $u }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-2 border-t border-gray-100 pt-4">
                    <button type="button" @click="modalIntrant = false"
                            class="rounded-xl border border-gray-200 px-4 py-2 text-xs font-semibold text-gray-600 transition hover:bg-gray-50">Annuler</button>
                    <button type="submit"
                            class="rounded-xl bg-[#006a4f] px-5 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-[#156c52]">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>


    {{-- =========================== MODALE : INTRANTS POSSIBLES =========================== --}}
    <div x-show="modalLiens" x-cloak x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm">
        <div @click.outside="modalLiens = false"
             class="w-full max-w-lg space-y-4 rounded-2xl border border-gray-100 bg-white p-6 shadow-2xl">

            <div>
                <h3 class="text-lg font-bold text-gray-900">Intrants possibles</h3>
                <p class="text-xs text-gray-500">
                    Culture : <span class="font-semibold text-gray-700" x-text="lien.nom"></span>.
                    Cochez les intrants que les DPA pourront utiliser pour cette culture.
                </p>
            </div>

            @if($erreursLiens->any())
                <div class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-800">
                    @foreach($erreursLiens->all() as $e) <p>{{ $e }}</p> @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.referentiel.cultures.intrants') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="culture_id" :value="lien.id">

                <input type="text" x-model="rechLien" placeholder="Rechercher un intrant..."
                       class="w-full rounded-lg border-gray-300 text-sm focus:border-[#006a4f] focus:ring-[#006a4f]">

                <div class="max-h-72 space-y-1 overflow-y-auto rounded-xl border border-gray-200 p-2">
                    <template x-for="x in tousIntrants" :key="x.idIntrant">
                        <label x-show="correspond(x.nom, rechLien)"
                               class="flex cursor-pointer items-center gap-3 rounded-lg px-3 py-2 text-sm hover:bg-gray-50">
                            <input type="checkbox" name="intrants[]" :value="x.idIntrant" x-model="lien.ids"
                                   class="rounded border-gray-300 text-[#006a4f] focus:ring-[#006a4f]">
                            <span class="flex-1 text-gray-800" x-text="x.nom"></span>
                            <span class="text-xs text-gray-400" x-text="x.unite"></span>
                        </label>
                    </template>
                    <p x-show="!tousIntrants.length" class="px-3 py-4 text-center text-xs text-gray-400">
                        Aucun intrant actif. Ajoutez-en d'abord dans l'onglet « Intrants ».
                    </p>
                </div>

                <p class="text-xs text-gray-500">
                    <span class="font-semibold text-[#006a4f]" x-text="lien.ids.length"></span> intrant(s) sélectionné(s).
                    Retirer un intrant ne supprime pas les barèmes déjà définis.
                </p>

                <div class="flex justify-end gap-2 border-t border-gray-100 pt-4">
                    <button type="button" @click="modalLiens = false"
                            class="rounded-xl border border-gray-200 px-4 py-2 text-xs font-semibold text-gray-600 transition hover:bg-gray-50">Annuler</button>
                    <button type="submit"
                            class="rounded-xl bg-[#006a4f] px-5 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-[#156c52]">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection