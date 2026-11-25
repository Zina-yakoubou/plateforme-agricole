@extends('layouts.app')

@section('title', 'Recensement de la maison')
@section('page-title', 'Recensement')
@section('page-subtitle', 'Ménages, exploitants et parcelles de la maison')

@section('content')
@php
    $maison     = $recensement->maison;
    $menages    = $recensement->menages ?? collect();
    $cloture    = in_array($recensement->statut, ['termine', 'valide'], true);

    $statuts = [
        'brouillon'  => ['Brouillon',  'bg-slate-100 text-slate-600',    'bg-slate-400'],
        'en_attente' => ['En attente', 'bg-amber-50 text-amber-700',     'bg-amber-500'],
        'en_cours'   => ['En cours',   'bg-blue-50 text-blue-700',       'bg-blue-500'],
        'termine'    => ['Terminé',    'bg-green-50 text-green-700',     'bg-green-500'],
        'valide'     => ['Validé',     'bg-emerald-50 text-emerald-700', 'bg-emerald-500'],
    ];
    $s = $statuts[$recensement->statut] ?? [
        ucfirst(str_replace('_', ' ', (string) $recensement->statut)), 'bg-slate-100 text-slate-600', 'bg-slate-400',
    ];

    // Bouton principal : vert plein
    $boutonVert = 'inline-flex items-center justify-center gap-2 rounded-lg bg-[#006a4f] px-5 py-2.5 text-sm
                   font-semibold text-white shadow-sm transition hover:bg-[#156c52]
                   focus:outline-none focus:ring-2 focus:ring-[#006a4f]/30
                   disabled:cursor-not-allowed disabled:opacity-50';

    // Bouton secondaire : contour vert
    $boutonContour = 'inline-flex items-center justify-center gap-1.5 rounded-lg border border-[#006a4f]/30
                      bg-[#e5f2ee] px-3.5 py-2 text-xs font-semibold text-[#006a4f] transition
                      hover:bg-[#006a4f] hover:text-white';
@endphp

<div class="w-full space-y-5">

    {{-- Messages --}}
    {{-- @if(session('success'))
        <div class="flex items-start gap-3 rounded-lg border border-[#b7dfd2] bg-[#e5f2ee] px-4 py-3 text-sm text-[#006a4f]">
            <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>co
    @endif --}}

    @if(session('info'))
        <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">
            {{ session('info') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            @foreach($errors->all() as $erreur)<p>{{ $erreur }}</p>@endforeach
        </div>
    @endif

    {{-- Bandeau recensement clôturé --}}
    @if($cloture)
        <div class="flex items-center gap-3 rounded-lg border border-[#b7dfd2] bg-[#e5f2ee] px-4 py-3 text-sm font-medium text-[#006a4f]">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Recensement clôturé : consultation en lecture seule.
        </div>
    @endif

    {{-- En-tête --}}
    <div class="flex flex-wrap items-start justify-between gap-4 rounded-xl border border-slate-200 bg-white p-6 shadow-[0_1px_2px_rgba(0,0,0,0.05)]">
        <div>
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-[28px] font-semibold leading-tight text-[#212529]">
                    Maison n° {{ $maison->numeroMaison ?? '—' }}
                </h1>
                <span class="rounded-full bg-[#e5f2ee] px-3 py-1 text-xs font-semibold text-[#006a4f]">
                    {{ $recensement->campagne->libelle ?? 'Campagne en cours' }}
                </span>
            </div>

            <div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-gray-500">
                <span>Village de {{ $maison->village->nom ?? '—' }}</span>
                <span class="text-slate-300">•</span>
                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium {{ $s[1] }}">
                    <span class="h-1.5 w-1.5 rounded-full {{ $s[2] }}"></span>
                    {{ $s[0] }}
                </span>
            </div>
        </div>

        <a href="{{ route('agent.recensements.index', ['maison_id' => $recensement->maison_id]) }}"
           class="inline-flex items-center gap-2 rounded-lg border border-[#006a4f]/30 bg-white px-4 py-2.5
                  text-sm font-semibold text-[#006a4f] transition hover:bg-[#e5f2ee]">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Retour aux recensements
        </a>
    </div>

    {{-- Ménages de la maison --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-6 py-5">
            <div>
                <h2 class="text-base font-semibold text-[#212529]">Ménages résidant dans cette habitation</h2>
                <p class="mt-0.5 text-xs text-gray-500">
                    Enregistrez et gérez les ménages, leurs membres et leurs exploitants agricoles.
                </p>
            </div>

            <div class="flex items-center gap-4">
                <span class="rounded-full bg-[#f8faf9] px-3 py-1.5 text-sm text-gray-500">
                    <span class="font-semibold text-[#006a4f]">{{ $menages->count() }}</span> ménage(s)
                </span>

                @unless($cloture)
                    <a href="{{ route('agent.recensements.menages.create', $recensement) }}" class="{{ $boutonVert }}">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Ajouter un ménage
                    </a>
                @endunless
            </div>
        </div>

        @forelse($menages as $menage)
            @php
                $chef = trim(($menage->prenomChef ?? '') . ' ' . ($menage->nomChef ?? ''));
                $personnes = (int) ($menage->nombreHommes ?? 0) + (int) ($menage->nombreFemmes ?? 0)
                           + (int) ($menage->nombreGarcons ?? 0) + (int) ($menage->nombreFilles ?? 0);
            @endphp

            <div class="flex flex-col gap-3 px-6 py-4 transition hover:bg-[#f8faf9]
                        sm:flex-row sm:items-center sm:justify-between
                        {{ ! $loop->first ? 'border-t border-slate-100' : '' }}">

                <div class="flex min-w-0 items-center gap-4">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#e5f2ee] text-sm font-semibold text-[#006a4f]">
                        {{ $loop->iteration }}
                    </div>

                    <div class="min-w-0">
                        <h3 class="truncate font-semibold text-[#212529]">
                            Ménage de {{ $chef !== '' ? $chef : 'Chef non renseigné' }}
                        </h3>
                        <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-gray-500">
                            <span class="rounded-full bg-[#f8faf9] px-2.5 py-0.5 font-mono">
                                Réf : {{ $menage->numeroMenage ?? '—' }}
                            </span>
                            <span class="rounded-full bg-[#f8faf9] px-2.5 py-0.5">
                                {{ $personnes > 0 ? $personnes : '—' }} personne(s)
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    @unless($cloture)
                        <a href="{{ route('agent.recensements.menages.edit', $menage) }}" class="{{ $boutonContour }}">
                            Modifier
                        </a>
                    @endunless

                    <a href="{{ route('agent.recensements.menages.exploitants.index', $menage) }}" class="{{ $boutonContour }}">
                        Gérer les exploitants &amp; parcelles
                    </a>
                </div>
            </div>
        @empty
            <div class="px-6 py-14 text-center">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-[#e5f2ee] text-[#006a4f]">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M3 12l9-9 9 9M5 10v10h14V10"/>
                    </svg>
                </div>
                <p class="font-semibold text-[#212529]">Aucun ménage enregistré</p>
                <p class="mt-1 text-sm text-gray-500">
                    Ajoutez le premier ménage de cette habitation pour commencer le recensement.
                </p>
            </div>
        @endforelse
    </div>

    {{-- Clôture --}}
    <div class="flex flex-wrap items-center justify-between gap-4 rounded-xl border border-slate-200 bg-white px-6 py-4 shadow-[0_1px_2px_rgba(0,0,0,0.05)]">
        <p class="text-sm text-gray-500">
            @if($cloture)
                Ce recensement est clôturé : il n'est plus modifiable.
            @elseif($menages->isEmpty())
                Ajoutez au moins un ménage pour pouvoir clôturer ce recensement.
            @else
                Une fois tous les ménages et parcelles saisis, vous pouvez clôturer ce recensement.
            @endif
        </p>

        @unless($cloture)
            <form method="POST"
                  action="{{ route('agent.recensements.cloturer', $recensement) }}"
                  onsubmit="if (!confirm('Clôturer ce recensement ? Il ne pourra plus être modifié.')) return false; this.querySelector('button').disabled = true;">
                @csrf
                <button type="submit"
                        @disabled($menages->isEmpty())
                        class="{{ $boutonVert }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Clôturer le recensement
                </button>
            </form>
        @endunless
    </div>
</div>
@endsection