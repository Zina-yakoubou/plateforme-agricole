@extends('layouts.app')

@section('title', 'Besoins en intrants')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">

    {{-- =========================================================
         EN-TÊTE & LANCEMENT DU CALCUL
    ========================================================== --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center rounded-md bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                    SIRA-Mô • DPA
                </span>
                <span class="text-xs text-slate-500 font-medium">Consolidation des besoins</span>
            </div>

            <h1 class="text-2xl font-bold text-slate-900 tracking-tight mt-1">Besoins en intrants</h1>

            <p class="text-sm text-slate-500 mt-0.5">
                Besoins de toutes les cultures de votre préfecture, calculés à partir des barèmes
                et de la dernière campagne clôturée.
            </p>

            @if($campagne)
                <p class="mt-2 inline-flex items-center gap-2 rounded-lg bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-700 ring-1 ring-slate-200">
                    <span class="h-2 w-2 rounded-full bg-[#43a842]"></span>
                    Campagne utilisée : {{ $campagne->libelle }}@isset($campagne->annee) ({{ $campagne->annee }})@endisset
                </p>
            @else
                <p class="mt-2 rounded-lg bg-amber-50 px-3 py-1.5 text-xs font-medium text-amber-800 ring-1 ring-amber-200">
                    Aucune campagne clôturée : il n'y a pas encore de données à exploiter.
                </p>
            @endif
        </div>

        <form action="{{ route('dpa.besoins.calculer') }}" method="POST" class="flex flex-col sm:flex-row items-center gap-3">
            @csrf

            <select name="culture_id" aria-label="Culture à calculer"
                    class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-[#006a4f] focus:outline-none focus:ring-2 focus:ring-[#006a4f]/10">
                <option value="">Toutes les cultures</option>
                @foreach($cultures ?? [] as $culture)
                    <option value="{{ $culture->idCulture }}">{{ $culture->nomCulture ?? $culture->libelle }}</option>
                @endforeach
            </select>

            <button type="submit"
                    @disabled(! $campagne)
                    onclick="return confirm(@js('Calculer les besoins' . ($campagne ? ' pour la campagne « '.$campagne->libelle.' »' : '') . ' ? Les besoins déjà calculés seront mis à jour.'))"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#006a4f] hover:bg-[#00583f] text-white text-sm font-semibold rounded-xl shadow-sm transition disabled:cursor-not-allowed disabled:opacity-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                Calculer les besoins
            </button>
        </form>
    </div>

    {{-- =========================================================
         MESSAGES
    ========================================================== --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border-l-4 border-[#006a4f] text-emerald-900 text-sm rounded-r-xl">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-900 text-sm rounded-r-xl">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-900 text-sm rounded-r-xl">
            @foreach($errors->all() as $erreur)<p>{{ $erreur }}</p>@endforeach
        </div>
    @endif

    {{-- =========================================================
         TOTAUX PAR INTRANT (consolidation)
    ========================================================== --}}
    @if($totaux->isNotEmpty())
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-slate-800 text-base">Total des besoins par intrant</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Selon les filtres choisis ci-dessous.</p>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($totaux as $t)
                    <div class="rounded-xl bg-slate-50 p-4 ring-1 ring-slate-100">
                        <p class="truncate text-sm font-medium text-slate-700">{{ $t->nom }}</p>
                        <p class="mt-1 text-2xl font-bold text-[#006a4f]">
                            {{ number_format($t->total, 2, ',', ' ') }}
                            <span class="text-sm font-medium text-slate-500">{{ $t->unite }}</span>
                        </p>
                        <p class="text-xs text-slate-400">{{ $t->parcelles }} parcelle(s)</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- =========================================================
         FILTRES D'AFFICHAGE
    ========================================================== --}}
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('dpa.besoins.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">

            <div class="md:col-span-5">
                <label for="filtre_culture" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Culture</label>
                <select name="culture_id" id="filtre_culture"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-[#006a4f] focus:outline-none focus:ring-2 focus:ring-[#006a4f]/10 transition">
                    <option value="">Toutes les cultures</option>
                    @foreach($cultures ?? [] as $culture)
                        <option value="{{ $culture->idCulture }}" @selected(request('culture_id') == $culture->idCulture)>
                            {{ $culture->nomCulture ?? $culture->libelle }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-4">
                <label for="filtre_intrant" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Intrant</label>
                <select name="intrant_id" id="filtre_intrant"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-[#006a4f] focus:outline-none focus:ring-2 focus:ring-[#006a4f]/10 transition">
                    <option value="">Tous les intrants</option>
                    @foreach($intrants ?? [] as $intrant)
                        <option value="{{ $intrant->idIntrant }}" @selected(request('intrant_id') == $intrant->idIntrant)>
                            {{ $intrant->nom ?? $intrant->libelle }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-3 flex items-center gap-2">
                <button type="submit"
                        class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-xl transition">
                    Filtrer
                </button>

                @if(request()->hasAny(['culture_id', 'intrant_id']))
                    <a href="{{ route('dpa.besoins.index') }}" title="Réinitialiser"
                       class="p-2.5 text-slate-400 hover:text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">✕</a>
                @endif
            </div>

        </form>
    </div>

    {{-- =========================================================
         TABLEAU DES BESOINS
    ========================================================== --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h2 class="font-semibold text-slate-800 text-base">Besoins par producteur</h2>
            <span class="text-xs text-slate-500 font-medium bg-white px-3 py-1 rounded-full border border-slate-200">
                {{ $besoins->total() }} besoin(s)
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-xs tracking-wider">
                    <tr>
                        <th scope="col" class="px-6 py-3.5">Producteur</th>
                        <th scope="col" class="px-6 py-3.5">Culture et parcelle</th>
                        <th scope="col" class="px-6 py-3.5">Intrant</th>
                        <th scope="col" class="px-6 py-3.5 text-right">Quantité calculée</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white text-slate-700">
                    @forelse($besoins as $besoin)
                        @php
                            $cp         = $besoin->cultureParcelle;
                            $parcelle   = $cp?->parcelle;
                            $exploitant = $parcelle?->exploitation?->exploitant;

                            // Même règle que le calcul : surface cultivée, sans dépasser la parcelle
                            $haParcelle = (float) ($parcelle?->superficie ?? 0);
                            $haCultivee = (float) ($cp?->superficieCultivee ?? 0);
                            $superficie = $haCultivee > 0 ? min($haCultivee, $haParcelle) : $haParcelle;
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900">
                                    {{ $exploitant->nom ?? '—' }} {{ $exploitant->prenom ?? '' }}
                                </div>
                                <div class="text-xs text-slate-400 mt-0.5">
                                    {{ $villages[$besoin->culture_parcelle_id] ?? '—' }}
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-emerald-50 text-[#006a4f] text-xs font-semibold">
                                    {{ $cp?->culture?->nomCulture ?? $cp?->culture?->libelle ?? 'Culture' }}
                                </span>
                                <div class="text-xs text-slate-500 mt-1">
                                    {{ $parcelle?->numeroParcelle }} —
                                    <span class="font-medium text-slate-700">{{ number_format($superficie, 2, ',', ' ') }} ha</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 font-semibold text-slate-800">
                                {{ $besoin->intrant->nom ?? $besoin->intrant->libelle ?? 'N/A' }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                <span class="text-base font-bold text-[#006a4f] font-mono">
                                    {{ number_format($besoin->quantitePrevue ?? 0, 2, ',', ' ') }}
                                </span>
                                <span class="text-xs font-medium text-slate-500 ml-1">{{ $besoin->unite ?? 'kg' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                <p class="text-base font-medium text-slate-600">Aucun besoin pour cette sélection.</p>
                                <p class="text-xs text-slate-400 mt-1">Modifiez les filtres ou cliquez sur « Calculer les besoins ».</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($besoins->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">
                {{ $besoins->links() }}
            </div>
        @endif
    </div>

</div>
@endsection