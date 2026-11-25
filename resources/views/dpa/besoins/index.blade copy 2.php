@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">

    {{-- =========================================================
         1. EN-TÊTE DE LA PAGE
    ========================================================== --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center rounded-md bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                    SIRA-Mô • DPA
                </span>
                <span class="text-xs text-slate-400">•</span>
                <span class="text-xs text-slate-500 font-medium">Campagne Agricole 2025-2026</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight mt-1">Consolidation des Besoins en Intrants</h1>
            <p class="text-sm text-slate-500 mt-0.5">
                Vue d'ensemble et estimation des intrants requis par culture et par producteur dans le canton.
            </p>
        </div>

        <!-- Action de calcul -->
        <form action="{{ route('dpa.besoins.calculer') }}" method="POST" class="shrink-0">
            @csrf
            <button type="submit" 
                    onclick="return confirm('Relancer le calcul automatique pour tous les producteurs ?')"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#006a4f] hover:bg-[#00583f] text-white text-sm font-semibold rounded-xl shadow-sm transition-all focus:ring-2 focus:ring-[#006a4f]/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Lancer le calcul automatique
            </button>
        </form>
    </div>

    {{-- =========================================================
         2. MESSAGES FLASH
    ========================================================== --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border-l-4 border-[#006a4f] text-emerald-900 text-sm rounded-r-xl flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-[#006a4f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-900 text-sm rounded-r-xl flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    {{-- =========================================================
         3. BARRE DE FILTRES PROFESSIONNELLE (PAR CULTURE & INTRANT)
    ========================================================== --}}
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('dpa.besoins.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            
            <!-- Filtre Culture -->
            <div class="md:col-span-5">
                <label for="culture_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                    Filtrer par Culture
                </label>
                <select name="culture_id" id="culture_id" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-[#006a4f] focus:outline-none focus:ring-2 focus:ring-[#006a4f]/10 transition">
                    <option value="">Toutes les cultures</option>
                    @foreach($cultures ?? [] as $culture)
                        <option value="{{ $culture->idCulture }}" {{ request('culture_id') == $culture->idCulture ? 'selected' : '' }}>
                            {{ $culture->nomCulture ?? $culture->libelle }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filtre Intrant -->
            <div class="md:col-span-4">
                <label for="intrant_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                    Filtrer par Intrant
                </label>
                <select name="intrant_id" id="intrant_id" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-[#006a4f] focus:outline-none focus:ring-2 focus:ring-[#006a4f]/10 transition">
                    <option value="">Tous les intrants</option>
                    @foreach($intrants ?? [] as $intrant)
                        <option value="{{ $intrant->idIntrant }}" {{ request('intrant_id') == $intrant->idIntrant ? 'selected' : '' }}>
                            {{ $intrant->nom ?? $intrant->libelle }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Boutons d'action -->
            <div class="md:col-span-3 flex items-center gap-2">
                <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filtrer
                </button>

                @if(request()->hasAny(['culture_id', 'intrant_id']))
                    <a href="{{ route('dpa.besoins.index') }}" class="p-2.5 text-slate-400 hover:text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition" title="Réinitialiser les filtres">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                @endif
            </div>

        </form>
    </div>

    {{-- =========================================================
         4. TABLEAU DE SYNTHÈSE DES BESOINS
    ========================================================== --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h2 class="font-semibold text-slate-800 text-base">Résultats des évaluations</h2>
            <span class="text-xs text-slate-500 font-medium bg-white px-3 py-1 rounded-full border border-slate-200">
                {{ $besoins->total() }} besoin(s) trouvé(s)
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-xs tracking-wider">
                    <tr>
                        <th scope="col" class="px-6 py-3.5">Producteur</th>
                        <th scope="col" class="px-6 py-3.5">Culture & Parcelle</th>
                        <th scope="col" class="px-6 py-3.5">Intrant Requis</th>
                        <th scope="col" class="px-6 py-3.5 text-right">Quantité Calculée</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white text-slate-700">
                    @forelse($besoins as $besoin)
                        @php
                            $cp = $besoin->cultureParcelle;
                            $parcelle = $cp?->parcelle;
                            $exploitant = $parcelle?->exploitation?->exploitant;
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            
                            <!-- Producteur -->
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900">
                                    {{ $exploitant->nom ?? '—' }} {{ $exploitant->prenom ?? '' }}
                                </div>
                                <div class="text-xs text-slate-400 mt-0.5 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    </svg>
                                    {{ $exploitant->village->nomVillage ?? 'Secteur Mô' }}
                                </div>
                            </td>

                            <!-- Culture & Parcelle -->
                            <td class="px-6 py-4">
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-[#006a4f] text-xs font-semibold">
                                    🌱 {{ $cp?->culture?->nomCulture ?? $cp?->culture?->libelle ?? 'Culture' }}
                                </div>
                                <div class="text-xs text-slate-500 mt-1">
                                    Parcelle #{{ $parcelle?->numeroParcelle ?? '—' }} • Superficie: <span class="font-medium text-slate-700">{{ $parcelle?->superficie ?? 0 }} ha</span>
                                </div>
                            </td>

                            <!-- Intrant -->
                            <td class="px-6 py-4">
                                <span class="font-semibold text-slate-800">
                                    {{ $besoin->intrant->nom ?? $besoin->intrant->libelle ?? 'N/A' }}
                                </span>
                            </td>

                            <!-- Quantité Calculée -->
                            <td class="px-6 py-4 text-right">
                                <span class="text-base font-bold text-[#006a4f] font-mono">
                                    {{ number_format($besoin->quantitePrevue ?? 0, 2, ',', ' ') }}
                                </span>
                                <span class="text-xs font-medium text-slate-500 ml-1">
                                    {{ $besoin->unite ?? 'kg' }}
                                </span>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                <svg class="mx-auto h-12 w-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-base font-medium text-slate-600">Aucun besoin ne correspond à votre sélection.</p>
                                <p class="text-xs text-slate-400 mt-1">Modifiez vos filtres ou lancez un calcul automatique si ce n'est pas encore fait.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(method_exists($besoins, 'hasPages') && $besoins->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">
                {{ $besoins->links() }}
            </div>
        @endif
    </div>

</div>
@endsection