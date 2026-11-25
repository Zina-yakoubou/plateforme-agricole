@extends('layouts.app')

@section('title', 'À rappeler / Incomplets')
@section('page-title', 'À rappeler / Incomplets')
@section('page-subtitle', 'Villages que vous avez commencés mais pas terminés')

@section('content')
<div class="w-full">

    {{-- En-tête --}}
    <div class="mb-5">
        <h1 class="text-[28px] font-semibold leading-tight text-text-primary">À rappeler / Incomplets</h1>
        <p class="mt-1 text-sm text-text-secondary">
            Reprenez les villages commencés. Ceux avec des maisons en cours apparaissent en premier.
        </p>
    </div>

    {{-- Recherche --}}
    <form method="GET" action="{{ route('agent.incomplets') }}"
          class="mb-5 rounded-xl border border-slate-200 bg-white p-4">
        <div class="flex flex-wrap gap-3">
            <div class="relative min-w-[260px] flex-1">
                <svg class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                </svg>
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Rechercher un village…"
                    class="w-full rounded-lg border border-slate-200 py-2.5 pl-10 pr-3 text-sm
                           focus:border-green-700 focus:outline-none focus:ring-1 focus:ring-green-700"
                >
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

            @if($search !== '')
                <a href="{{ route('agent.incomplets') }}"
                   class="inline-flex items-center rounded-lg border border-slate-200 px-4 py-2.5 text-sm
                          font-medium text-slate-700 hover:bg-slate-50">
                    Effacer
                </a>
            @endif
        </div>
    </form>

    {{-- Liste des villages --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">

        <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-5">
            <div>
                <h2 class="text-base font-semibold text-text-primary">Villages à reprendre</h2>
                <p class="mt-0.5 text-xs text-text-secondary">Villages commencés et encore ouverts</p>
            </div>
            <p class="text-sm text-text-secondary">
                <span class="font-semibold text-green-800">{{ $villages->count() }}</span> village(s)
            </p>
        </div>

        @forelse($villages as $v)
            <div class="flex flex-col gap-4 border-t border-slate-100 px-6 py-5 lg:flex-row lg:items-center lg:gap-8">

                {{-- Village --}}
                <div class="lg:w-1/4">
                    <h3 class="font-semibold text-text-primary">Village de {{ $v->village->nom }}</h3>
                    <p class="text-xs text-text-secondary">
                        {{ $v->campagne->codeCampagne }}
                    </p>
                    <p class="text-xs text-text-secondary">
                        Dernière activité le
                        {{ \Carbon\Carbon::parse($v->activite)->format('d/m/Y à H:i') }}
                    </p>
                </div>

                {{-- Progression --}}
                <div class="flex-1">
                    <div class="flex h-2.5 overflow-hidden rounded-full bg-blue-500">
                        <span class="bg-green-600" style="width: {{ $v->pct_term }}%"></span>
                    </div>

                    <div class="mt-2 flex flex-wrap items-center gap-x-5 gap-y-1 text-xs text-text-secondary">
                        <span class="font-medium text-text-primary">{{ $v->recenses }} maison(s) recensée(s)</span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="h-2 w-2 rounded-full bg-green-600"></span>
                            {{ $v->termines }} terminée(s)
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                            {{ $v->en_cours }} en cours
                        </span>
                    </div>
                </div>

                {{-- Action --}}
                <div class="lg:ml-auto">
                    <a href="{{ route('agent.recensements.index', [
                                'village' => $v->village->idVillage,
                                'statut'  => 'en_attente',
                            ]) }}"
                       class="inline-flex items-center justify-center rounded-lg bg-green-50 px-4 py-2 text-sm
                              font-semibold text-green-700 hover:bg-green-100">
                        Reprendre le village
                    </a>
                </div>
            </div>
        @empty
            <div class="border-t border-slate-100 px-6 py-14 text-center">
                <p class="font-medium text-text-primary">Aucun village incomplet</p>
                <p class="mt-1 text-sm text-text-secondary">
                    Vous n'avez aucun village commencé et encore ouvert.
                </p>
                <a href="{{ route('agent.recensements.index') }}"
                   class="mt-4 inline-block text-sm font-semibold text-green-700 hover:underline">
                    Voir tous mes recensements
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection