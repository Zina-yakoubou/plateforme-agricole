@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6">

    <!-- En-tête et Bouton de Calcul Global -->
    <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-900 tracking-tight">Consolidation des Besoins par Producteur</h1>
            <p class="text-sm text-gray-500 mt-1">
                Campagne active : <span class="font-semibold text-emerald-750">Active</span>
            </p>
        </div>

        <!-- Déclenchement du calcul sans ID dans l'URL -->
        <form action="{{ route('dpa.besoins.calculer') }}" method="POST">
            @csrf
            <button type="submit" 
                    onclick="return confirm('Relancer le calcul automatique pour tous les producteurs ?')"
                    class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-md shadow-sm transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                Lancer le calcul automatique
            </button>
        </form>
    </div>

    <!-- Messages Flash -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-sm rounded-r-md">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 text-sm rounded-r-md">
            {{ session('error') }}
        </div>
    @endif

    <!-- Tableau Synthétique des Besoins par Producteur -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                <thead class="bg-gray-50 text-gray-500 font-medium">
                    <tr>
                        <th scope="col" class="px-6 py-3">Producteur</th>
                        <th scope="col" class="px-6 py-3">Parcelle & Culture</th>
                        <th scope="col" class="px-6 py-3">Intrant Requis</th>
                        <th scope="col" class="px-6 py-3 text-right">Quantité Calculée</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white text-gray-700">
                    @forelse($besoins as $besoin)
                        @php
                            $cp = $besoin->cultureParcelle;
                            $parcelle = $cp?->parcelle;
                            $exploitant = $parcelle?->exploitation?->exploitant;
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <!-- Producteur -->
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">
                                    {{ $exploitant->nom ?? '—' }} {{ $exploitant->prenom ?? '' }}
                                </div>
                                <div class="text-xs text-gray-400">Secteur Mô</div>
                            </td>

                            <!-- Parcelle & Culture -->
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-800">{{ $cp?->culture?->nomCulture ?? $cp?->culture?->libelle ?? 'Culture' }}</span>
                                <span class="text-xs text-gray-500 block">Sup: {{ $parcelle?->superficie ?? 0 }} ha</span>
                            </td>

                            <!-- Intrant -->
                            <td class="px-6 py-4 font-medium text-emerald-700">
                                {{ $besoin->intrant->nom ?? $besoin->intrant->libelle ?? 'N/A' }}
                            </td>

                            <!-- Quantité -->
                            <td class="px-6 py-4 text-right font-bold text-gray-900">
                                {{ number_format($besoin->quantitePrevue ?? 0, 2, ',', ' ') }}
                                <span class="text-xs font-normal text-gray-500">{{ $besoin->unite ?? '' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-400 italic">
                                Aucun besoin calculé. (Cliquez sur "Lancer le calcul automatique" ci-dessus).
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(method_exists($besoins, 'hasPages') && $besoins->hasPages())
            <div class="px-5 py-3 border-t border-gray-200 bg-gray-50">
                {{ $besoins->links() }}
            </div>
        @endif
    </div>

</div>
@endsection