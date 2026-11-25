@extends('layouts.app')

@section('title', 'Besoins en intrants')
@section('page-title', 'Besoins en intrants')
@section('page-subtitle', 'Consolidation des besoins de votre préfecture')

@section('content')
@php
    $filtresActifs = request()->hasAny(['campagne_id', 'culture_id', 'intrant_id', 'village_id', 'producteur']);
    $selectClasse  = 'rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-text-primary
                      focus:border-green-700 focus:outline-none focus:ring-1 focus:ring-green-700';
@endphp

<div class="w-full">

    {{-- En-tête --}}
    <div class="mb-5 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-[28px] font-semibold leading-tight text-text-primary">Besoins en intrants</h1>
            <p class="mt-1 text-sm text-text-secondary">
                Besoins de toutes les cultures de votre préfecture, calculés à partir des barèmes
                et de la dernière campagne clôturée.
            </p>
        </div>

        @if(!empty($prefectureNom))
            <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-50 text-green-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </span>
                <div>
                    <p class="text-xs text-text-secondary">Ma préfecture</p>
                    <p class="text-sm font-semibold text-text-primary">{{ $prefectureNom }}</p>
                </div>
            </div>
        @endif
    </div>

    {{-- Messages de retour --}}
    @if(session('success'))
        <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            @foreach($errors->all() as $erreur)<p>{{ $erreur }}</p>@endforeach
        </div>
    @endif

    {{-- Calcul des besoins --}}
    <div class="mb-5 flex flex-wrap items-center justify-between gap-4 rounded-xl border border-slate-200 bg-white p-4">
        <div>
            <p class="text-sm font-semibold text-text-primary">Calcul des besoins</p>
            @if($campagne)
                <p class="mt-1 inline-flex items-center gap-2 text-xs text-text-secondary">
                    <span class="h-2 w-2 rounded-full bg-green-600"></span>
                    Campagne active : {{ $campagne->libelle }}@isset($campagne->annee) ({{ $campagne->annee }})@endisset
                </p>
            @else
                <p class="mt-1 text-xs font-medium text-amber-700">Aucune campagne disponible pour le calcul.</p>
            @endif
        </div>

        <form action="{{ route('dpa.besoins.calculer') }}" method="POST" class="flex flex-wrap items-center gap-3">
            @csrf

            <input type="hidden" name="campagne_id" value="{{ request('campagne_id') }}">

            <select name="culture_id" aria-label="Culture à calculer" class="{{ $selectClasse }}">
                <option value="">Toutes les cultures</option>
                @foreach($cultures ?? [] as $culture)
                    <option value="{{ $culture->idCulture }}">{{ $culture->nomCulture ?? $culture->libelle }}</option>
                @endforeach
            </select>

            <button type="submit"
                    @disabled(! $campagne)
                    onclick="return confirm(@js('Calculer les besoins' . ($campagne ? ' pour la campagne « '.$campagne->libelle.' »' : '') . ' ? Les besoins déjà calculés seront mis à jour.'))"
                    class="inline-flex items-center gap-2 rounded-lg bg-green-800 px-5 py-2.5 text-sm font-semibold
                           text-white hover:bg-green-900 disabled:cursor-not-allowed disabled:opacity-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                Calculer les besoins
            </button>
        </form>
    </div>

    {{-- Totaux par intrant --}}
    @if(isset($totaux) && $totaux->isNotEmpty())
        <div class="mb-5 rounded-xl border border-slate-200 bg-white p-5">
            <h2 class="text-base font-semibold text-text-primary">Total des besoins par intrant</h2>
            <p class="mt-0.5 text-xs text-text-secondary">Synthèse consolidée selon les filtres sélectionnés.</p>

            <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($totaux as $t)
                    <div class="rounded-lg border border-slate-200 bg-slate-50/60 p-4">
                        <p class="truncate text-sm font-medium text-text-primary">{{ $t->nom }}</p>
                        <p class="mt-1 text-2xl font-semibold text-green-800">
                            {{ number_format($t->total, 2, ',', ' ') }}
                            <span class="text-sm font-medium text-text-secondary">{{ $t->unite }}</span>
                        </p>
                        <p class="text-xs text-text-secondary">{{ $t->parcelles }} parcelle(s) concernée(s)</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Recherche et filtres --}}
    <form method="GET" action="{{ route('dpa.besoins.index') }}"
          class="mb-5 rounded-xl border border-slate-200 bg-white p-4">

        <div class="flex flex-wrap gap-3">
            <div class="relative min-w-[260px] flex-1">
                <svg class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                </svg>
                <input type="text" name="producteur" value="{{ request('producteur') }}"
                       placeholder="Rechercher un producteur (nom ou prénom)…"
                       class="w-full rounded-lg border border-slate-200 py-2.5 pl-10 pr-3 text-sm
                              focus:border-green-700 focus:outline-none focus:ring-1 focus:ring-green-700">
            </div>

            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-green-800 px-5 py-2.5 text-sm font-semibold
                           text-white hover:bg-green-900">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                </svg>
                Rechercher
            </button>
        </div>

        <div class="mt-3 flex flex-wrap items-center gap-3 border-t border-slate-100 pt-3">
            <select name="campagne_id" onchange="this.form.submit()" class="{{ $selectClasse }}">
                <option value="">Dernière campagne (défaut)</option>
                @foreach($campagnes ?? [] as $camp)
                    <option value="{{ $camp->idCampagne }}" @selected(request('campagne_id') == $camp->idCampagne)>
                        {{ $camp->libelle }}@isset($camp->annee) ({{ $camp->annee }})@endisset
                    </option>
                @endforeach
            </select>

            <select name="culture_id" onchange="this.form.submit()" class="{{ $selectClasse }}">
                <option value="">Toutes les cultures</option>
                @foreach($cultures ?? [] as $culture)
                    <option value="{{ $culture->idCulture }}" @selected(request('culture_id') == $culture->idCulture)>
                        {{ $culture->nomCulture ?? $culture->libelle }}
                    </option>
                @endforeach
            </select>

            <select name="intrant_id" onchange="this.form.submit()" class="{{ $selectClasse }}">
                <option value="">Tous les intrants</option>
                @foreach($intrants ?? [] as $intrant)
                    <option value="{{ $intrant->idIntrant }}" @selected(request('intrant_id') == $intrant->idIntrant)>
                        {{ $intrant->nom ?? $intrant->libelle }}
                    </option>
                @endforeach
            </select>

            <select name="village_id" onchange="this.form.submit()" class="{{ $selectClasse }}">
                <option value="">Tous les villages</option>
                @foreach($listeVillages ?? [] as $vil)
                    <option value="{{ $vil->idVillage }}" @selected(request('village_id') == $vil->idVillage)>
                        {{ $vil->nom }}
                    </option>
                @endforeach
            </select>

            @if($filtresActifs)
                <a href="{{ route('dpa.besoins.index') }}"
                   class="text-sm font-semibold text-green-700 hover:underline">
                    Réinitialiser
                </a>
            @endif
        </div>
    </form>

    {{-- Tableau --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">

        <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-5">
            <div>
                <h2 class="text-base font-semibold text-text-primary">Besoins par producteur</h2>
                <p class="mt-0.5 text-xs text-text-secondary">Quantités calculées avec les barèmes agronomiques</p>
            </div>
            <p class="text-sm text-text-secondary">
                <span class="font-semibold text-green-800">{{ $besoins->total() }}</span> besoin(s)
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="border-y border-slate-200 bg-slate-50">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-700">
                        <th class="w-16 px-6 py-3">N°</th>
                        <th class="px-6 py-3">Producteur</th>
                        <th class="px-6 py-3">Culture et superficie</th>
                        <th class="px-6 py-3">Intrant</th>
                        <th class="px-6 py-3 text-right">Quantité calculée</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($besoins as $besoin)
                        @php
                            $cp         = $besoin->cultureParcelle;
                            $parcelle   = $cp?->parcelle;
                            $exploitant = $parcelle?->exploitation?->exploitant;

                            $haParcelle = (float) ($parcelle?->superficie ?? 0);
                            $haCultivee = (float) ($cp?->superficieCultivee ?? 0);
                            $superficie = $haCultivee > 0 ? min($haCultivee, $haParcelle) : $haParcelle;
                        @endphp

                        <tr class="hover:bg-slate-50/60">

                            {{-- Numérotation 1, 2, 3… (continue d'une page à l'autre) --}}
                            <td class="px-6 py-4 text-text-secondary">
                                {{ $besoins->firstItem() + $loop->index }}
                            </td>

                            <td class="px-6 py-4">
                                <p class="font-semibold text-text-primary">
                                    {{ $exploitant->nom ?? '—' }} {{ $exploitant->prenom ?? '' }}
                                </p>
                                <p class="text-xs text-text-secondary">
                                    {{ $villages[$besoin->culture_parcelle_id] ?? '—' }}
                                </p>
                            </td>

                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700">
                                    {{ $cp?->culture?->nomCulture ?? $cp?->culture?->libelle ?? 'Culture' }}
                                </span>
                                <p class="mt-1 text-xs text-text-secondary">
                                    {{ number_format($superficie, 2, ',', ' ') }} ha
                                </p>
                            </td>

                            <td class="px-6 py-4 font-medium text-text-primary">
                                {{ $besoin->intrant->nom ?? $besoin->intrant->libelle ?? 'N/A' }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                <span class="text-base font-semibold text-green-800">
                                    {{ number_format($besoin->quantitePrevue ?? 0, 2, ',', ' ') }}
                                </span>
                                <span class="ml-1 text-xs text-text-secondary">{{ $besoin->unite ?? 'kg' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-14 text-center">
                                <p class="font-medium text-text-primary">Aucun besoin pour cette sélection.</p>
                                <p class="mt-1 text-xs text-text-secondary">
                                    Modifiez les filtres de recherche ou cliquez sur « Calculer les besoins ».
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($besoins->hasPages())
            <div class="border-t border-slate-100 px-6 py-4">
                {{ $besoins->links() }}
            </div>
        @endif
    </div>
</div>
@endsection