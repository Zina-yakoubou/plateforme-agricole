@extends('layouts.app')

@section('title', 'Recensements')
@section('page-title', 'Recensements')
@section('page-subtitle', $prefixe === 'dpa' ? 'Recensements de votre préfecture' : 'Tous les recensements')

@section('content')
@php
    $badge = fn ($s) => match ($s) {
        'valide'   => 'bg-emerald-50 text-emerald-700',
        'termine'  => 'bg-green-50 text-green-700',
        'en_cours' => 'bg-blue-50 text-blue-700',
        default    => 'bg-slate-100 text-slate-600',
    };
    $libelle = fn ($s) => match ($s) {
        'valide' => 'Validé', 'termine' => 'Terminé', 'en_cours' => 'En cours',
        'brouillon' => 'Brouillon', 'en_attente' => 'En attente', default => ucfirst($s),
    };
@endphp

<div class="mx-auto max-w-7xl">

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-text-primary">Recensements</h1>
        <p class="mt-1 text-sm text-text-secondary">
            {{ $prefixe === 'dpa'
                ? 'Consultez les recensements de votre préfecture.'
                : 'Consultez tous les recensements, toutes préfectures confondues.' }}
        </p>
    </div>

    {{-- Compteurs --}}
    <div class="mb-6 grid grid-cols-2 gap-3 md:grid-cols-4">
        <div class="rounded-xl border border-slate-200 bg-white p-4">
            <p class="text-xs text-text-secondary">Total</p>
            <p class="text-2xl font-semibold">{{ $stats['total'] }}</p>
        </div>
        <a href="{{ route($prefixe.'.recensements.index', array_merge(request()->query(), ['statut' => 'incomplets'])) }}"
           class="rounded-xl border border-slate-200 bg-white p-4 hover:bg-slate-50">
            <p class="text-xs text-text-secondary">Incomplets</p>
            <p class="text-2xl font-semibold text-blue-600">{{ $stats['incomplets'] }}</p>
        </a>
        <a href="{{ route($prefixe.'.recensements.index', array_merge(request()->query(), ['statut' => 'termine'])) }}"
           class="rounded-xl border border-slate-200 bg-white p-4 hover:bg-slate-50">
            <p class="text-xs text-text-secondary">Terminés</p>
            <p class="text-2xl font-semibold text-green-700">{{ $stats['termines'] }}</p>
        </a>
        <a href="{{ route($prefixe.'.recensements.index', array_merge(request()->query(), ['statut' => 'valide'])) }}"
           class="rounded-xl border border-slate-200 bg-white p-4 hover:bg-slate-50">
            <p class="text-xs text-text-secondary">Validés</p>
            <p class="text-2xl font-semibold text-emerald-700">{{ $stats['valides'] }}</p>
        </a>
    </div>

    {{-- Filtres --}}
    <form method="GET" action="{{ route($prefixe.'.recensements.index') }}"
          class="mb-5 grid gap-3 rounded-xl border border-slate-200 bg-white p-4 md:grid-cols-6">

        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Maison, campagne ou agent"
               class="rounded-md border border-slate-300 px-3 py-2 text-sm md:col-span-2">

        @if($prefixe === 'admin')
            <select name="prefecture" class="rounded-md border border-slate-300 px-3 py-2 text-sm">
                <option value="">Toutes préfectures</option>
                @foreach($prefectures as $p)
                    <option value="{{ $p->idPrefecture }}" @selected(request('prefecture') == $p->idPrefecture)>
                        {{ $p->nom }}
                    </option>
                @endforeach
            </select>
        @endif

        <select name="campagne" class="rounded-md border border-slate-300 px-3 py-2 text-sm">
            <option value="">Toutes campagnes</option>
            @foreach($campagnes as $c)
                <option value="{{ $c->idCampagne }}" @selected(request('campagne') == $c->idCampagne)>
                    {{ $c->codeCampagne }}
                </option>
            @endforeach
        </select>

        <select name="agent" class="rounded-md border border-slate-300 px-3 py-2 text-sm">
            <option value="">Tous agents</option>
            @foreach($agents as $a)
                <option value="{{ $a->id }}" @selected(request('agent') == $a->id)>{{ $a->name }}</option>
            @endforeach
        </select>

        <select name="statut" class="rounded-md border border-slate-300 px-3 py-2 text-sm">
            <option value="">Tous statuts</option>
            @foreach(['incomplets' => 'Incomplets', 'en_cours' => 'En cours', 'termine' => 'Terminés', 'valide' => 'Validés'] as $k => $l)
                <option value="{{ $k }}" @selected(request('statut') === $k)>{{ $l }}</option>
            @endforeach
        </select>

        <div class="flex gap-2 md:col-span-6">
            <button class="rounded-md bg-green-700 px-4 py-2 text-sm font-semibold text-white hover:bg-green-800">
                Filtrer
            </button>
            <a href="{{ route($prefixe.'.recensements.index') }}"
               class="rounded-md border border-slate-300 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                Réinitialiser
            </a>
        </div>
    </form>

    {{-- Tableau --}}
    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-text-secondary">
                <tr>
                    <th class="px-4 py-3">N°</th>
                    <th class="px-4 py-3">Campagne</th>
                    <th class="px-4 py-3">Maison et localisation</th>
                    <th class="px-4 py-3">Agent</th>
                    <th class="px-4 py-3">Dernière modification</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($recensements as $r)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-text-secondary">
                            {{ $recensements->firstItem() + $loop->index }}
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-medium">{{ $r->campagne?->libelle }}</p>
                            <p class="text-xs text-text-secondary">{{ $r->campagne?->codeCampagne }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-medium">Maison n° {{ $r->maison?->numeroMaison }}</p>
                            <p class="text-xs text-text-secondary">
                                Village de {{ $r->maison?->village?->nom }}
                                · {{ $r->maison?->village?->canton?->commune?->nom }}
                            </p>
                        </td>
                        <td class="px-4 py-3">{{ $r->agent?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-text-secondary">
                            {{ $r->dateDerniereModification
                                ? \Carbon\Carbon::parse($r->dateDerniereModification)->format('d/m/Y à H:i')
                                : '—' }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $badge($r->statut) }}">
                                {{ $libelle($r->statut) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route($prefixe.'.recensements.show', $r) }}"
                               class="rounded-md border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                Consulter
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-text-secondary">
                            Aucun recensement ne correspond à ces filtres.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $recensements->links() }}</div>
</div>
@endsection