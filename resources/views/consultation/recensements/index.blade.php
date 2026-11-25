@extends('layouts.app')

@section('title', 'Recensements')
@section('page-title', 'Recensements')
@section('page-subtitle', $prefixe === 'dpa' ? 'Recensements de votre préfecture' : 'Tous les recensements')

@section('content')
@php
    $statuts = [
        'brouillon'  => ['Brouillon',  'bg-slate-100 text-slate-600',     'bg-slate-400'],
        'en_attente' => ['En attente', 'bg-amber-50 text-amber-700',     'bg-amber-500'],
        'en_cours'   => ['En cours',   'bg-blue-50 text-blue-700',        'bg-blue-500'],
        'termine'    => ['Terminé',    'bg-green-50 text-green-700',      'bg-green-500'],
        'valide'     => ['Validé',     'bg-emerald-50 text-emerald-700',  'bg-emerald-500'],
    ];
    $filtresActifs = request()->hasAny(['search', 'prefecture', 'campagne', 'agent', 'statut']);
    $selectClasse  = 'rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-text-primary
                      focus:border-green-700 focus:outline-none focus:ring-1 focus:ring-green-700';
@endphp

<div class="w-full">

    {{-- En-tête --}}
    <div class="mb-5 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-[28px] font-semibold leading-tight text-text-primary">Recensements</h1>
            <p class="mt-1 text-sm text-text-secondary">
                {{ $prefixe === 'dpa'
                    ? 'Recensements réalisés dans votre préfecture.'
                    : 'Recensements de toutes les préfectures.' }}
            </p>
        </div>

        @if($prefixe === 'dpa' && $prefectureNom)
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

    
    {{-- Tableau --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
        {{-- Recherche et filtres --}}
        <form method="GET" action="{{ route($prefixe.'.recensements.index') }}"
            class="mb-5 rounded-xl border border-slate-200 bg-white p-4">

            <div class="flex flex-wrap gap-3">
                <div class="relative min-w-[260px] flex-1">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Rechercher par maison, campagne ou agent…"
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
                @if($prefixe === 'admin')
                    <select name="prefecture" onchange="this.form.submit()" class="{{ $selectClasse }}">
                        <option value="">Toutes préfectures</option>
                        @foreach($prefectures as $p)
                            <option value="{{ $p->idPrefecture }}" @selected(request('prefecture') == $p->idPrefecture)>
                                {{ $p->nom }}
                            </option>
                        @endforeach
                    </select>
                @endif

                <select name="campagne" onchange="this.form.submit()" class="{{ $selectClasse }}">
                    <option value="">Toutes campagnes</option>
                    @foreach($campagnes as $c)
                        <option value="{{ $c->idCampagne }}" @selected(request('campagne') == $c->idCampagne)>
                            {{ $c->libelle }}
                        </option>
                    @endforeach
                </select>

                <select name="agent" onchange="this.form.submit()" class="{{ $selectClasse }}">
                    <option value="">Tous agents</option>
                    @foreach($agents as $a)
                        <option value="{{ $a->id }}" @selected(request('agent') == $a->id)>{{ $a->name }}</option>
                    @endforeach
                </select>

                <select name="statut" onchange="this.form.submit()" class="{{ $selectClasse }}">
                    <option value="">Tous statuts</option>
                    @foreach(['incomplets' => 'Incomplets', 'en_cours' => 'En cours', 'termine' => 'Terminés', 'valide' => 'Validés'] as $k => $l)
                        <option value="{{ $k }}" @selected(request('statut') === $k)>{{ $l }}</option>
                    @endforeach
                </select>

                @if($filtresActifs)
                    <a href="{{ route($prefixe.'.recensements.index') }}"
                    class="text-sm font-semibold text-green-700 hover:underline">
                        Réinitialiser
                    </a>
                @endif
            </div>
        </form>


        <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-5">
            <div>
                <h2 class="text-base font-semibold text-text-primary">Liste des recensements</h2>
                <p class="mt-0.5 text-xs text-text-secondary">
                    {{ $stats['incomplets'] }} incomplet(s) ·
                    {{ $stats['termines'] }} terminé(s) ·
                    {{ $stats['valides'] }} validé(s)
                </p>
            </div>
            <p class="text-sm text-text-secondary">
                <span class="font-semibold text-green-800">{{ $stats['total'] }}</span> recensement(s)
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="border-y border-slate-200 bg-slate-50">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-700">
                        <th class="w-16 px-6 py-3">N°</th>
                        <th class="px-6 py-3">Campagne</th>
                        <th class="px-6 py-3">Maison et localisation</th>
                        <th class="px-6 py-3">Agent</th>
                        <th class="px-6 py-3">Dernière modification</th>
                        <th class="px-6 py-3">Statut</th>
                        <th class="px-6 py-3 text-right">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($recensements as $r)
                        @php
                            $s = $statuts[$r->statut] ?? [
                                ucfirst(str_replace('_', ' ', (string) $r->statut)),
                                'bg-slate-100 text-slate-600',
                                'bg-slate-400',
                            ];
                            $village = $r->maison?->village;
                        @endphp

                        <tr class="hover:bg-slate-50/60">

                            {{-- Numérotation 1, 2, 3… (continue d'une page à l'autre) --}}
                            <td class="px-6 py-4 text-text-secondary">
                                {{ $recensements->firstItem() + $loop->index }}
                            </td>

                            {{-- Campagne (sans code) --}}
                            <td class="px-6 py-4">
                                <p class="font-semibold text-text-primary">{{ $r->campagne?->libelle ?? '—' }}</p>
                            </td>

                            {{-- Maison et localisation (sans numéro de maison) --}}
                            <td class="px-6 py-4">
                                <p class="font-semibold text-text-primary">
                                    Village de {{ $village?->nom ?? '—' }}
                                </p>
                                {{-- <p class="text-xs text-text-secondary">
                                    {{ $village?->canton?->nom }}
                                    @if($village?->canton?->commune?->nom)
                                        · {{ $village->canton->commune->nom }}
                                    @endif
                                </p> --}}
                            </td>

                            <td class="px-6 py-4 text-text-primary">{{ $r->agent?->name ?? '—' }}</td>

                            <td class="px-6 py-4 text-text-secondary">
                                {{ $r->dateDerniereModification
                                    ? \Carbon\Carbon::parse($r->dateDerniereModification)->format('d/m/Y à H:i')
                                    : '—' }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium {{ $s[1] }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $s[2] }}"></span>
                                    {{ $s[0] }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex justify-end">
                                    <a href="{{ route($prefixe.'.recensements.show', $r) }}"
                                       title="Consulter"
                                       class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200
                                              text-slate-600 transition hover:bg-slate-50">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-14 text-center text-text-secondary">
                                Aucun recensement ne correspond à votre recherche.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($recensements->hasPages())
            <div class="border-t border-slate-100 px-6 py-4">
                {{ $recensements->links() }}
            </div>
        @endif
    </div>
</div>
@endsection