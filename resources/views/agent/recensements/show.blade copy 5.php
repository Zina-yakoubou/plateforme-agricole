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
        'en_attente' => ['En attente', 'bg-amber-50 text-amber-700',    'bg-amber-500'],
        'en_cours'   => ['En cours',   'bg-blue-50 text-blue-700',       'bg-blue-500'],
        'termine'    => ['Terminé',    'bg-green-50 text-green-700',     'bg-green-500'],
        'valide'     => ['Validé',     'bg-emerald-50 text-emerald-700', 'bg-emerald-500'],
    ];
    $s = $statuts[$recensement->statut] ?? [
        ucfirst(str_replace('_', ' ', (string) $recensement->statut)), 'bg-slate-100 text-slate-600', 'bg-slate-400',
    ];

    $boutonClair = 'inline-flex items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-white
                    px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50';
@endphp

<div class="w-full">

    {{-- Messages --}}
    @if(session('success'))
        <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            @foreach($errors->all() as $erreur)<p>{{ $erreur }}</p>@endforeach
        </div>
    @endif

    {{-- En-tête --}}
    <div class="mb-5 flex flex-wrap items-start justify-between gap-4 rounded-xl border border-slate-200 bg-white p-6">
        <div>
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-[28px] font-semibold leading-tight text-text-primary">
                    Maison n° {{ $maison->numeroMaison ?? '—' }}
                </h1>
                <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                    {{ $recensement->campagne->libelle ?? 'Campagne en cours' }}
                </span>
            </div>

            <p class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-text-secondary">
                <span>Village de {{ $maison->village->nom ?? '—' }}</span>
                <span class="text-slate-300">•</span>
                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium {{ $s[1] }}">
                    <span class="h-1.5 w-1.5 rounded-full {{ $s[2] }}"></span>
                    {{ $s[0] }}
                </span>
            </p>
        </div>

        <a href="{{ route('agent.recensements.index', ['maison_id' => $recensement->maison_id]) }}"
           class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm
                  font-semibold text-slate-700 transition hover:bg-slate-50">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Retour aux recensements
        </a>
    </div>

    {{-- Ménages de la maison --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">

        <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-5">
            <div>
                <h2 class="text-base font-semibold text-text-primary">Ménages résidant dans cette habitation</h2>
                <p class="mt-0.5 text-xs text-text-secondary">
                    Enregistrez et gérez les ménages, leurs membres et leurs exploitants agricoles.
                </p>
            </div>

            <div class="flex items-center gap-4">
                <p class="text-sm text-text-secondary">
                    <span class="font-semibold text-green-800">{{ $menages->count() }}</span> ménage(s)
                </p>

                @unless($cloture)
                    <a href="{{ route('agent.recensements.menages.create', $recensement) }}"
                       class="inline-flex items-center gap-2 rounded-lg bg-green-800 px-5 py-2.5 text-sm font-semibold
                              text-white hover:bg-green-900">
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

            <div class="flex flex-col gap-3 border-t border-slate-100 px-6 py-4 transition hover:bg-slate-50/60 sm:flex-row sm:items-center sm:justify-between">
                <div class="min-w-0">
                    <h3 class="font-semibold text-text-primary">
                        Ménage de {{ $chef !== '' ? $chef : 'Chef non renseigné' }}
                    </h3>
                    <p class="mt-0.5 text-xs text-text-secondary">
                        Code / Réf : {{ $menage->numeroMenage ?? '—' }}
                        <span class="mx-1 text-slate-300">•</span>
                        Personnes composant le ménage : {{ $personnes > 0 ? $personnes : '—' }}
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    @unless($cloture)
                        <a href="{{ route('agent.recensements.menages.edit', $menage) }}" class="{{ $boutonClair }}">
                            Modifier
                        </a>
                    @endunless

                    <a href="{{ route('agent.recensements.menages.exploitants.index', $menage) }}" class="{{ $boutonClair }}">
                        Gérer les exploitants &amp; parcelles
                    </a>
                </div>
            </div>
        @empty
            <div class="border-t border-slate-100 px-6 py-14 text-center">
                <p class="font-medium text-text-primary">Aucun ménage enregistré</p>
                <p class="mt-1 text-sm text-text-secondary">
                    Ajoutez le premier ménage de cette habitation pour commencer le recensement.
                </p>
            </div>
        @endforelse
    </div>

    {{-- Clôture --}}
    <div class="mt-5 flex flex-wrap items-center justify-between gap-4 rounded-xl border border-slate-200 bg-white px-6 py-4">
        <p class="text-sm text-text-secondary">
            @if($cloture)
                Ce recensement est clôturé : il n'est plus modifiable.
            @else
                Une fois tous les ménages et parcelles saisis, vous pouvez clôturer ce recensement.
            @endif
        </p>

        @unless($cloture)
            <form method="POST"
                  action="{{ route('agent.recensements.cloturer', $recensement) }}"
                  onsubmit="return confirm('Clôturer ce recensement ? Il ne pourra plus être modifié.')">
                @csrf
                <button type="submit"
                        @disabled($menages->isEmpty())
                        class="inline-flex items-center gap-2 rounded-lg bg-orange-600 px-5 py-2.5 text-sm font-semibold
                               text-white hover:bg-orange-700 disabled:cursor-not-allowed disabled:opacity-50">
                    Clôturer le recensement
                </button>
            </form>
        @endunless
    </div>
</div>
@endsection