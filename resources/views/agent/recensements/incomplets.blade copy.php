@extends('layouts.app')

@section('title', 'À rappeler / Incomplets')
@section('page-title', 'À rappeler / Incomplets')
@section('page-subtitle', 'Villages que vous avez commencés mais pas terminés')

@section('content')
<div class="mx-auto max-w-5xl">

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-text-primary">À rappeler / Incomplets</h1>
        <p class="mt-1 text-sm text-text-secondary">
            Reprenez les villages commencés. Ceux avec des maisons en cours apparaissent en premier.
        </p>
    </div>

    <form method="GET" action="{{ route('agent.incomplets') }}" class="mb-5 flex gap-2">
        <input
            type="text"
            name="search"
            value="{{ $search }}"
            placeholder="Rechercher un village"
            class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm
                   focus:border-green-600 focus:outline-none focus:ring-1 focus:ring-green-600"
        >
        <button type="submit"
                class="rounded-md bg-green-700 px-4 py-2 text-sm font-semibold text-white hover:bg-green-800">
            Rechercher
        </button>
        @if($search !== '')
            <a href="{{ route('agent.incomplets') }}"
               class="rounded-md border border-slate-300 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                Effacer
            </a>
        @endif
    </form>

    @if($villages->isNotEmpty())
        <p class="mb-4 text-sm text-text-secondary">
            {{ $villages->count() }} village(s) à reprendre
        </p>
    @endif

    @forelse($villages as $v)
        <div class="mb-4 rounded-xl border border-slate-200 bg-white p-4">

            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="font-semibold text-text-primary">
                        Village de {{ $v->village->nom }}
                    </h2>
                    <p class="text-xs text-text-secondary">
                        {{ $v->campagne->codeCampagne }} ·
                        Dernière activité le
                        {{ \Carbon\Carbon::parse($v->activite)->format('d/m/Y à H:i') }}
                    </p>
                </div>

                <a href="{{ route('agent.recensements.index', [
                            'village' => $v->village->idVillage,
                            'statut'  => 'en_attente',
                        ]) }}"
                   class="rounded-md bg-green-50 px-3 py-1.5 text-sm font-semibold text-green-700
                          hover:bg-green-100">
                    Reprendre le village
                </a>
            </div>

            <div class="mt-3 flex h-2 overflow-hidden rounded bg-blue-500">
                <span class="bg-green-600" style="width: {{ $v->pct_term }}%"></span>
            </div>

            <div class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-text-secondary">
                <span>{{ $v->recenses }} maison(s) recensée(s)</span>
                <span class="inline-flex items-center gap-1">
                    <span class="h-2 w-2 rounded-full bg-green-600"></span>
                    {{ $v->termines }} terminée(s)
                </span>
                <span class="inline-flex items-center gap-1">
                    <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                    {{ $v->en_cours }} en cours
                </span>
            </div>
        </div>
    @empty
        <div class="rounded-xl border border-dashed border-slate-300 bg-white py-12 text-center">
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
@endsection