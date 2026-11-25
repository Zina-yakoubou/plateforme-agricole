@extends('layouts.app')

@section('title', 'Détail du village')

@section('content')

@php
    $campagne   = $affectation->campagne;
    $statut     = $campagne->statut ?? '';
    $estBloquee = in_array($statut, ['planifiee', 'planifiée', 'cloturee', 'clôturée']);
    $village    = $affectation->village;
    $equipe     = $affectation->equipe;
    $membres    = $equipe?->membres ?? collect();
    
    $statsSafe  = $stats ?? [];
    $total      = $statsSafe['total'] ?? 0;
    $termine    = $statsSafe['termine'] ?? 0;
    $enCours    = $statsSafe['en_cours'] ?? 0;
    $nonComm    = $statsSafe['non_commence'] ?? 0;

    $pct        = $total > 0 ? round(($termine / $total) * 100) : 0;
    $nomMembre  = fn ($m) => $m->name ?? trim(($m->prenom ?? '') . ' ' . ($m->nom ?? '')) ?: ($m->telephone ?? 'Agent');
    $date       = fn ($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('d/m/Y') : '—';
@endphp

<div class="mx-auto max-w-5xl space-y-6">

    {{-- Fil d'Ariane --}}
    <div>
        <a href="{{ route('agent.affectations') }}" class="text-sm text-slate-500 transition hover:text-[#006a4f]">
            Retour 
        </a>
        <div class="mt-2 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Village de {{ $village->nom ?? '—' }}</h1>
                <p class="mt-1 text-sm text-slate-500">
                    {{ $village->canton->commune->nom ?? '—' }} · Canton de {{ $village->canton->nom ?? '—' }}
                </p>
            </div>
            <span class="inline-flex w-fit rounded-full px-3 py-1 text-xs font-semibold {{ $estBloquee ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                Campagne : {{ ucfirst($statut) }}
            </span>
        </div>
    </div>

    {{-- Cartes d'informations --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Campagne</p>
            <p class="mt-1 font-semibold text-slate-800 truncate">{{ $campagne->libelle ?? '—' }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Période</p>
            <p class="mt-1 font-semibold text-slate-800">{{ $date($affectation->dateDebut) }} → {{ $date($affectation->dateFin) }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Canton</p>
            <p class="mt-1 font-semibold text-slate-800">{{ $village->canton->nom ?? '—' }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Code village</p>
            <p class="mt-1 font-semibold text-slate-800">{{ $village->code ?? '—' }}</p>
        </div>
    </div>

    {{-- Avancement et BOUTON DE NAVIGATION vers la page dédiée des maisons --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-5">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-800">Avancement du recensement</h2>
            <span class="text-2xl font-bold text-[#006a4f]">{{ $pct }}%</span>
        </div>

        <div class="h-2.5 w-full overflow-hidden rounded-full bg-slate-100">
            <div class="h-full rounded-full bg-[#006a4f] transition-all duration-500" style="width: {{ $pct }}%"></div>
        </div>

        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
            <div class="rounded-xl bg-slate-50 p-3"><p class="text-xs text-slate-500">Maisons</p><p class="text-xl font-bold text-slate-800">{{ $total }}</p></div>
            <div class="rounded-xl bg-emerald-50 p-3"><p class="text-xs text-emerald-700">Terminées</p><p class="text-xl font-bold text-emerald-700">{{ $termine }}</p></div>
            <div class="rounded-xl bg-blue-50 p-3"><p class="text-xs text-blue-700">En cours</p><p class="text-xl font-bold text-blue-700">{{ $enCours }}</p></div>
            <div class="rounded-xl bg-slate-50 p-3"><p class="text-xs text-slate-500">Non commencées</p><p class="text-xl font-bold text-slate-800">{{ $nonComm }}</p></div>
        </div>

        {{-- Lien direct unique et sécurisé vers la liste des maisons du village --}}
        <a href="{{ isset($village) ? route('villages.maisons.index', $village) : '#' }}"
           class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#006a4f] px-5 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#00563f]">
            <span>Accéder à la liste des maisons du village ({{ $total }})</span>
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
            </svg>
        </a>
    </div>

    {{-- Équipe --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold text-slate-800">Équipe : {{ $equipe->nom ?? 'Non définie' }}</h2>
        <ul class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($membres as $m)
                <li class="flex items-center gap-3 rounded-xl bg-slate-50 p-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#e5f2ee] text-sm font-bold text-[#006a4f]">
                        {{ mb_strtoupper(mb_substr($nomMembre($m), 0, 1)) }}
                    </span>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-medium text-slate-800">{{ $nomMembre($m) }}</p>
                        @if (!empty($m->telephone)) <p class="text-xs text-slate-500">{{ $m->telephone }}</p> @endif
                    </div>
                </li>
            @empty
                <li class="text-sm text-slate-500 col-span-full">Aucun agent dans cette équipe.</li>
            @endforelse
        </ul>
    </div>

</div>

@endsection