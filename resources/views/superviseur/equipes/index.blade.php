@extends('layouts.app')

@section('page-title', 'Mes Équipes')
@section('page-subtitle', 'Gestion et suivi des équipes sous votre supervision.')

@section('content')

@php
    $campagne = $campagneActive ?? null;
@endphp

<div class="space-y-6">

    {{-- ===========================================================
        CAMPAGNE ACTIVE
    ============================================================ --}}
    <div class="rounded-2xl border border-emerald-100 bg-gradient-to-r from-emerald-600 to-green-700 p-6 text-white shadow-sm">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs uppercase tracking-widest text-emerald-100">
                    Campagne en cours
                </p>

                <h2 class="mt-1 text-2xl font-bold">
                    {{ $campagne?->libelle ?? 'Aucune campagne active' }}
                </h2>

                @if($campagne)
                    <p class="mt-1 text-sm text-emerald-100">
                        Équipes affectées pour la campagne active.
                    </p>
                @endif
            </div>

            <span class="rounded-full bg-white/20 px-4 py-2 text-sm font-semibold">
                {{ $campagne ? 'Active' : 'Inactive' }}
            </span>
        </div>
    </div>

    {{-- ===========================================================
        RECHERCHE & BARRE D'OUTILS
    ============================================================ --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" action="{{ route('superviseur.equipes.index') }}" class="flex w-full max-w-md gap-2">
            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Rechercher par libellé ou référence..."
                class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"
            />
            <button
                type="submit"
                class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700"
            >
                Filtrer
            </button>
            @if($search)
                <a
                    href="{{ route('superviseur.equipes.index') }}"
                    class="rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-600 hover:bg-slate-50"
                >
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>

    {{-- ===========================================================
        LISTE DES ÉQUIPES
    ============================================================ --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        @if($equipes->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-6 py-3">Équipe</th>
                            <th class="px-6 py-3">Référence</th>
                            <th class="px-6 py-3 text-center">Membres</th>
                            <th class="px-6 py-3">Villages / Zones affectés</th>
                            <th class="px-6 py-3 text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($equipes as $equipe)
                            <tr class="hover:bg-slate-50 transition">
                                {{-- Libellé équipe --}}
                                <td class="px-6 py-4 font-semibold text-slate-800">
                                    {{ $equipe->libelle ?? $equipe->nom ?? 'Sans nom' }}
                                </td>

                                {{-- Référence --}}
                                <td class="px-6 py-4 font-mono text-xs text-slate-600">
                                    {{ $equipe->reference ?? '-' }}
                                </td>

                                {{-- Membres --}}
                                <td class="px-6 py-4 text-center font-medium text-slate-700">
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-700">
                                        {{ $equipe->membres->count() }} membre(s)
                                    </span>
                                </td>

                                {{-- Villages affectés --}}
                                <td class="px-6 py-4 text-slate-700">
                                    @php
                                        $villages = $equipe->affectations
                                            ->where('statut', 'active')
                                            ->pluck('village.nom')
                                            ->filter()
                                            ->unique();
                                    @endphp

                                    @if($villages->isNotEmpty())
                                        <div class="text-xs text-slate-700">
                                            {{ $villages->join(', ') }}
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400">Aucune affectation active</span>
                                    @endif
                                </td>

                                {{-- Action --}}
                                <td class="px-6 py-4 text-center">
                                    <a
                                        href="{{ route('superviseur.equipes.show', $equipe->reference ?? $equipe->idEquipe ?? $equipe->id) }}"
                                        class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700 transition"
                                    >
                                        Détails
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($equipes->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $equipes->links() }}
                </div>
            @endif
        @else
            <div class="px-6 py-12 text-center text-sm text-slate-500">
                Aucune équipe trouvée sous votre supervision.
            </div>
        @endif
    </div>

</div>

@endsection