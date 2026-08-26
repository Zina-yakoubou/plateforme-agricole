@extends('layouts.app')

@section('page-title', 'Tableau de bord')
@section('page-subtitle', 'Vue générale du système de recensement agricole')

@section('content')

{{-- ================================================= --}}
{{-- APERÇU — Statistiques + Progression --}}
{{-- ================================================= --}}

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">

    {{-- Colonne gauche : statistiques --}}
    <div class="lg:col-span-2">

        <div class="mb-4">
            <h2 class="text-lg font-semibold text-text-primary">Aperçu du recensement</h2>
            <p class="text-sm text-text-secondary">Chiffres clés de la campagne en cours</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

            {{-- Régions --}}
            <div class="bg-white rounded-2xl border border-border p-5">
                <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4" />
                    </svg>
                </div>
                <p class="text-2xl font-bold text-text-primary">{{ $regionsCount ?? 0 }}</p>
                <p class="text-sm text-text-secondary mt-0.5">Régions couvertes</p>
            </div>

            {{-- Préfectures --}}
            <div class="bg-white rounded-2xl border border-border p-5">
                <div class="w-11 h-11 rounded-xl bg-orange-50 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                        <circle cx="12" cy="11" r="3" stroke-width="1.8" />
                    </svg>
                </div>
                <p class="text-2xl font-bold text-text-primary">{{ $prefecturesCount ?? 0 }}</p>
                <p class="text-sm text-text-secondary mt-0.5">Préfectures</p>
            </div>

            {{-- Agents actifs — carte pleine, façon "Next session" --}}
            <div class="bg-primary rounded-2xl p-5 flex flex-col justify-center">
                <p class="text-white/70 text-xs mb-1">Agents actifs</p>
                <p class="text-white text-2xl font-bold">{{ $agentsActifs ?? 0 }} / {{ $agentsCount ?? 0 }}</p>
            </div>

        </div>

    </div>

    {{-- Colonne droite : graphique --}}
    <div class="bg-white rounded-2xl border border-border p-6">

        <h3 class="text-base font-semibold text-text-primary mb-1">Progression du recensement</h3>
        <p class="text-xs text-text-secondary mb-4">Ménages et exploitants par mois</p>

        <canvas id="activityChart" height="180"></canvas>

        <div class="flex items-center gap-4 mt-4 pt-4 border-t border-border text-xs text-text-secondary">
            <span class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-primary"></span> Ménages
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-accent-yellow"></span> Exploitants
            </span>
        </div>

    </div>

</div>


{{-- ================================================= --}}
{{-- MÉNAGES RECENSÉS — carte pleine largeur --}}
{{-- ================================================= --}}

<div class="bg-white rounded-2xl border border-border p-5 mb-6 flex items-center justify-between flex-wrap gap-3">
    <div class="flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-background-muted flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h4a1 1 0 001-1V10" />
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-text-primary">{{ number_format($menagesCount ?? 0, 0, ',', ' ') }}</p>
            <p class="text-sm text-text-secondary">Ménages recensés</p>
        </div>
    </div>

    <span class="text-xs font-medium text-accent-red bg-red-50 px-3 py-1.5 rounded-full shrink-0">
        Objectif {{ $objectifMenages ?? '—' }}
    </span>
</div>


{{-- ================================================= --}}
{{-- CAMPAGNES --}}
{{-- ================================================= --}}

<div class="mb-6">

    <div class="flex items-center justify-between mb-4 flex-wrap gap-3">
        <div>
            <h2 class="text-lg font-semibold text-text-primary">Campagnes</h2>
            <p class="text-sm text-text-secondary">État des campagnes de recensement en cours et passées</p>
        </div>

        <a href="{{ route('campagnes.index') }}"
            class="px-4 py-2 rounded-lg border border-border text-sm font-medium text-text-secondary
                   hover:bg-background-muted hover:text-primary hover:border-primary
                   transition-colors duration-150 shrink-0">
            Voir tout
        </a>
    </div>

    @if(isset($campagnes) && $campagnes->count())

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

            @foreach($campagnes as $campagne)
                <div class="bg-white rounded-2xl border border-border p-5 flex flex-col">

                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 rounded-xl bg-background-muted flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M8 2v4M16 2v4M3 10h18M5 4h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z" />
                            </svg>
                        </div>

                        <span class="inline-block px-3 py-1 rounded-full text-xs font-medium
                            {{ $campagne->active ? 'bg-background-muted text-primary' : 'bg-gray-100 text-text-secondary' }}">
                            {{ $campagne->active ? 'Active' : 'Terminée' }}
                        </span>
                    </div>

                    <h4 class="font-semibold text-text-primary mb-1">{{ $campagne->nom }}</h4>

                    <p class="text-xs text-text-secondary mb-4">
                        {{ $campagne->date_debut?->format('d/m/Y') }} — {{ $campagne->date_fin?->format('d/m/Y') ?? 'en cours' }}
                    </p>

                    <div class="mt-auto">
                        <div class="flex items-center justify-between text-xs text-text-secondary mb-1.5">
                            <span>Progression</span>
                            <span class="font-medium text-text-primary">{{ $campagne->progression ?? 0 }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 mb-4">
                            <div class="bg-primary h-2 rounded-full" style="width: {{ $campagne->progression ?? 0 }}%"></div>
                        </div>

                        @if(Route::has('campagnes.show'))
                            <a href="{{ route('campagnes.show', $campagne) }}"
                               class="block text-center w-full bg-background-muted text-primary font-medium
                                      text-sm py-2 rounded-lg hover:bg-primary hover:text-white
                                      transition-colors duration-150">
                                Voir la campagne
                            </a>
                        @endif
                    </div>

                </div>
            @endforeach

        </div>

    @else

        <div class="bg-white rounded-2xl border border-border p-10 text-center">
            <div class="w-14 h-14 rounded-full bg-background-muted flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <p class="text-sm text-text-secondary">Aucune campagne pour le moment</p>
        </div>

    @endif

</div>


{{-- ================================================= --}}
{{-- ACTIVITÉS RÉCENTES --}}
{{-- ================================================= --}}

<div class="bg-white rounded-2xl border border-border p-6">

    <h3 class="text-base font-semibold text-text-primary mb-4">Activités récentes</h3>

    @if(isset($recentActivities) && $recentActivities->count())

        <ul class="space-y-4">
            @foreach($recentActivities as $activity)
                <li class="flex gap-3">
                    <div class="w-8 h-8 rounded-full bg-background-muted flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm text-text-primary leading-snug">{{ $activity->description }}</p>
                        <p class="text-xs text-text-muted mt-0.5">{{ $activity->created_at->diffForHumans() }}</p>
                    </div>
                </li>
            @endforeach
        </ul>

    @else

        <div class="text-center py-10">
            <div class="w-14 h-14 rounded-full bg-background-muted flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <p class="text-sm text-text-secondary">Aucune activité récente</p>
        </div>

    @endif

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('activityChart'), {
        type: 'line',
        data: {
            labels: ['Jan','Fév','Mar','Avr','Mai','Juin','Juil','Août','Sep','Oct','Nov','Déc'],
            datasets: [
                {
                    label: 'Ménages recensés',
                    data: {{ Js::from($menagesParMois ?? array_fill(0, 12, 0)) }},
                    borderColor: '#006a4f',
                    backgroundColor: 'rgba(0,106,79,0.08)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 0
                },
                {
                    label: 'Exploitants',
                    data: {{ Js::from($exploitantsParMois ?? array_fill(0, 12, 0)) }},
                    borderColor: '#ffcf2f',
                    backgroundColor: 'rgba(255,207,47,0.08)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 0
                }
            ]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f1f1' } },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endpush

@endsection