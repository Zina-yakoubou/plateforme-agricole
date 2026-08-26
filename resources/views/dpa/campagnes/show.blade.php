@extends('layouts.app')

@section('page-title', 'Campagne de recensement')
@section('page-subtitle', 'Informations et activités planifiées pour votre préfecture')

@section('content')

@php

    $campagne = $deploiement->campagne;

    /*
    |--------------------------------------------------------------------------
    | STATUT CAMPAGNE
    |--------------------------------------------------------------------------
    */

    $statutCampagne = match ($campagne->statut) {
        'planifiee' => 'Planifiée',
        'active'    => 'Active',
        'cloturee'  => 'Clôturée',
        'archivee'  => 'Archivée',
        default     => ucfirst($campagne->statut),
    };

    $statutCampagneClass = match ($campagne->statut) {
        'planifiee' => 'bg-blue-50 text-blue-700 border-blue-100',
        'active'    => 'bg-green-50 text-green-700 border-green-100',
        'cloturee'  => 'bg-orange-50 text-orange-700 border-orange-100',
        'archivee'  => 'bg-gray-100 text-gray-600 border-gray-200',
        default     => 'bg-gray-100 text-gray-600 border-gray-200',
    };

    /*
    |--------------------------------------------------------------------------
    | RÉCEPTION
    |--------------------------------------------------------------------------
    */

    $receptionne = $deploiement->statut === 'recue';

    /*
    |--------------------------------------------------------------------------
    | PORTÉE
    |--------------------------------------------------------------------------
    */

    $porteeLabel = match ($campagne->portee) {
        'nationale'    => 'Nationale',
        'regionale'    => 'Régionale',
        'prefectorale' => 'Préfectorale',
        default        => ucfirst($campagne->portee),
    };

    /*
    |--------------------------------------------------------------------------
    | PLANIFICATION PRÉFECTORALE
    |--------------------------------------------------------------------------
    | Géré par PlanificationPrefectoraleController.
    | Relation hasOne sur le déploiement : $deploiement->planificationPrefectorale
    */

    $planificationPrefectorale = $deploiement->planificationPrefectorale;

    /*
    |--------------------------------------------------------------------------
    | ACTIVITÉS (planning global de la campagne)
    |--------------------------------------------------------------------------
    */

    $activites = $campagne->planification?->activites ?? collect();

@endphp


<div class="space-y-6">


    {{-- =========================================================
         RETOUR
    ========================================================== --}}

    <div>
        <a
            href="{{ route('dpa.campagnes.index') }}"
            class="inline-flex items-center gap-2 text-sm font-medium
                   text-text-secondary hover:text-primary transition"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                    d="M15 19l-7-7 7-7" />
            </svg>
            Retour aux campagnes
        </a>
    </div>


    {{-- =========================================================
         MESSAGES
    ========================================================== --}}

    @if(session('success'))
        <div class="flex items-start gap-3 p-4 rounded-xl bg-green-50 border border-green-200">
            <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <p class="text-sm text-green-700">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="flex items-start gap-3 p-4 rounded-xl bg-red-50 border border-red-200">
            <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <p class="text-sm text-red-700">{{ session('error') }}</p>
        </div>
    @endif

    @if(session('info'))
        <div class="flex items-start gap-3 p-4 rounded-xl bg-blue-50 border border-blue-200">
            <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01" />
            </svg>
            <p class="text-sm text-blue-700">{{ session('info') }}</p>
        </div>
    @endif


    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}

    <div class="bg-white border border-border rounded-2xl overflow-hidden">

        <div class="p-6">

            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">

                {{-- =================================================
                     INFORMATIONS CAMPAGNE
                ================================================== --}}

                <div class="flex items-start gap-4">

                    <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M8 2v4M16 2v4M3 10h18M5 4h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z" />
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <p class="text-xs text-text-muted mb-1">{{ $campagne->codeCampagne }}</p>
                        <h1 class="text-xl lg:text-2xl font-bold text-text-primary">{{ $campagne->libelle }}</h1>

                        @if($campagne->description)
                            <p class="text-sm text-text-secondary mt-2">{{ $campagne->description }}</p>
                        @endif
                    </div>

                </div>


                {{-- =================================================
                     ACTIONS (bouton unique Planifier / Modifier)
                ================================================== --}}

                <div class="flex items-center gap-3 flex-wrap">

                    @if($receptionne && !$planificationPrefectorale)

    <a
        href="{{ route('dpa.planifications-prefectorales.create', $deploiement) }}"
        class="inline-flex items-center gap-2
               px-4 py-2.5 rounded-xl
               bg-primary text-white
               text-sm font-semibold
               hover:opacity-90 transition"
    >
        <svg
            class="w-4 h-4"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 4v16m8-8H4"
            />
        </svg>

        Planifier
    </a>

@elseif($planificationPrefectorale)

    <a
        href="{{ route(
            'dpa.planifications-prefectorales.edit',
            $planificationPrefectorale
        ) }}"
        class="inline-flex items-center gap-2
               px-4 py-2.5 rounded-xl
               border border-border
               bg-white text-text-primary
               text-sm font-semibold
               hover:bg-background-muted transition"
    >
        <svg
            class="w-4 h-4"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="1.8"
                d="M11 5h2M5 19h2l10-10-2-2L5 17v2z"
            />
        </svg>

        Modifier le planning
    </a>

@endif

                    <span
                        class="inline-flex items-center gap-2 px-3 py-1.5
                               rounded-full border text-xs font-semibold
                               {{ $statutCampagneClass }}"
                    >
                        <span class="w-2 h-2 rounded-full bg-current"></span>
                        {{ $statutCampagne }}
                    </span>

                </div>

            </div>

        </div>


        {{-- =========================================================
             RÉSUMÉ
        ========================================================== --}}

        <div class="border-t border-border bg-background-muted/40">
            <div class="grid grid-cols-2 lg:grid-cols-4 divide-x divide-border">

                <div class="p-4">
                    <p class="text-xs text-text-muted">Portée</p>
                    <p class="text-sm font-semibold text-text-primary mt-1">{{ $porteeLabel }}</p>
                </div>

                <div class="p-4">
                    <p class="text-xs text-text-muted">Début</p>
                    <p class="text-sm font-semibold text-text-primary mt-1">
                        {{ $campagne->dateDebut?->format('d/m/Y à H:i') }}
                    </p>
                </div>

                <div class="p-4">
                    <p class="text-xs text-text-muted">Fin</p>
                    <p class="text-sm font-semibold text-text-primary mt-1">
                        {{ $campagne->dateFin?->format('d/m/Y à H:i') ?? 'Non définie' }}
                    </p>
                </div>

                <div class="p-4">
                    <p class="text-xs text-text-muted">Préfecture</p>
                    <p class="text-sm font-semibold text-text-primary mt-1">
                        {{ $deploiement->prefecture?->nom ?? $prefecture->nom }}
                    </p>
                </div>

            </div>
        </div>

    </div>


    {{-- =========================================================
         RÉCEPTION
    ========================================================== --}}

    <div
        class="rounded-2xl border p-5
        {{ $receptionne ? 'bg-green-50 border-green-200' : 'bg-orange-50 border-orange-200' }}"
    >
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

            <div class="flex items-start gap-3">

                <div
                    class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0
                    {{ $receptionne ? 'bg-green-100' : 'bg-orange-100' }}"
                >
                    @if($receptionne)
                        <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    @else
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M12 9v4m0 4h.01M10.29 3.86l-7.82 13.5A2 2 0 004.2 20h15.6a2 2 0 001.73-2.64l-7.82-13.5a2 2 0 00-3.42 0z" />
                        </svg>
                    @endif
                </div>

                <div>
                    @if($receptionne)
                        <h2 class="text-sm font-semibold text-green-800">Campagne réceptionnée</h2>
                        <p class="text-xs text-green-700 mt-1">
                            @if($deploiement->dateReception)
                                Réceptionnée le {{ $deploiement->dateReception->format('d/m/Y à H:i') }}
                                @if($deploiement->recuPar) par {{ $deploiement->recuPar->name }} @endif
                            @else
                                Réception confirmée.
                            @endif
                        </p>
                    @else
                        <h2 class="text-sm font-semibold text-orange-800">Réception en attente</h2>
                        <p class="text-xs text-orange-700 mt-1">
                            Cette campagne a été envoyée à votre préfecture.
                        </p>
                    @endif
                </div>

            </div>

            @unless($receptionne)
                <form
                    method="POST"
                    action="{{ route('dpa.campagnes.reception', $deploiement) }}"
                    onsubmit="return confirm('Confirmez-vous la réception de cette campagne ?');"
                >
                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        class="w-full lg:w-auto inline-flex items-center justify-center gap-2
                               px-5 py-2.5 rounded-xl bg-primary text-white
                               text-sm font-semibold hover:opacity-90 transition"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Accuser réception
                    </button>
                </form>
            @endunless

        </div>
    </div>


    {{-- =========================================================
         CONTENU PRINCIPAL
    ========================================================== --}}

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">


        {{-- =====================================================
             COLONNE PRINCIPALE
        ====================================================== --}}

        <div class="lg:col-span-2 space-y-6">

            {{-- OBJECTIFS --}}
            <div class="bg-white border border-border rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14l-5-4.87 6.91-1.01L12 2z" />
                        </svg>
                    </div>
                    <h2 class="text-base font-semibold text-text-primary">Objectifs de la campagne</h2>
                </div>

                @if($campagne->objectifs)
                    <div class="text-sm text-text-secondary leading-7 whitespace-pre-line">{{ $campagne->objectifs }}</div>
                @else
                    <p class="text-sm text-text-muted italic">Aucun objectif n'a été renseigné pour cette campagne.</p>
                @endif
            </div>


            {{-- ACTIVITÉS PLANIFIÉES --}}
            <div class="bg-white border border-border rounded-2xl p-6">

                <div class="flex items-center justify-between gap-4 mb-5">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-text-primary">Planning des activités</h2>
                            <p class="text-xs text-text-secondary">Activités prévues dans votre préfecture</p>
                        </div>
                    </div>

                    @if($activites->isNotEmpty())
                        <span class="px-2.5 py-1 rounded-full bg-background-muted text-text-secondary text-xs font-semibold">
                            {{ $activites->count() }} activité{{ $activites->count() > 1 ? 's' : '' }}
                        </span>
                    @endif
                </div>

                @if($activites->isEmpty())

                    <div class="rounded-xl border border-dashed border-border p-8 text-center">
                        <svg class="w-10 h-10 mx-auto text-text-muted mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="1.5" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                        </svg>

                        <p class="text-sm font-medium text-text-secondary">Aucune activité planifiée</p>
                        <p class="text-xs text-text-muted mt-1">
                            Le planning de cette campagne n'a pas encore été défini.
                        </p>

                        @if($receptionne && !$planificationPrefectorale)
                            <p class="text-xs text-text-muted mt-3">
                                Utilisez le bouton « Planifier » en haut de page pour le définir.
                            </p>
                        @endif
                    </div>

                @else

                    <div class="space-y-3">
                        @foreach($activites->sortBy('ordre') as $activite)

                            @php
                                $statutActivite = match ($activite->statut ?? null) {
                                    'planifiee' => 'Planifiée',
                                    'en_cours'  => 'En cours',
                                    'terminee'  => 'Terminée',
                                    'annulee'   => 'Annulée',
                                    default     => 'Planifiée',
                                };

                                $couleurStatut = match ($activite->statut ?? null) {
                                    'en_cours' => 'bg-blue-50 text-blue-700',
                                    'terminee' => 'bg-green-50 text-green-700',
                                    'annulee'  => 'bg-red-50 text-red-700',
                                    default    => 'bg-gray-100 text-gray-600',
                                };
                            @endphp

                            <div class="rounded-xl border border-border bg-background-muted/30 p-4">
                                <div class="flex items-start gap-4">

                                    <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-sm font-bold shrink-0">
                                        {{ $loop->iteration }}
                                    </div>

                                    <div class="flex-1 min-w-0">

                                        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                            <div>
                                                <h3 class="text-sm font-semibold text-text-primary">{{ $activite->libelle }}</h3>
                                                @if($activite->description)
                                                    <p class="text-xs text-text-secondary mt-1 leading-5">{{ $activite->description }}</p>
                                                @endif
                                            </div>

                                            <span class="inline-flex shrink-0 px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $couleurStatut }}">
                                                {{ $statutActivite }}
                                            </span>
                                        </div>

                                        <div class="flex flex-wrap gap-x-5 gap-y-2 mt-3 text-xs text-text-secondary">

                                            @if($activite->dateDebut)
                                                <div class="flex items-center gap-1.5">
                                                    <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                            d="M8 2v4M16 2v4M3 10h18M5 4h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z" />
                                                    </svg>
                                                    <span>{{ $activite->dateDebut->format('d/m/Y à H:i') }}</span>
                                                </div>
                                            @endif

                                            @if($activite->dateFin)
                                                <div class="flex items-center gap-1.5">
                                                    <svg class="w-3.5 h-3.5 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <circle cx="12" cy="12" r="9" stroke-width="1.8" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 7v5l3 2" />
                                                    </svg>
                                                    <span>{{ $activite->dateFin->format('d/m/Y à H:i') }}</span>
                                                </div>
                                            @endif

                                        </div>

                                    </div>

                                </div>
                            </div>

                        @endforeach
                    </div>

                @endif

            </div>


            {{-- MÉTHODOLOGIE --}}
            @if($campagne->methodologie)
                <div class="bg-white border border-border rounded-2xl p-6">
                    <h2 class="text-base font-semibold text-text-primary mb-3">Méthodologie de mise en œuvre</h2>
                    <div class="text-sm text-text-secondary leading-7 whitespace-pre-line">{{ $campagne->methodologie }}</div>
                </div>
            @endif


            {{-- INSTRUCTIONS --}}
            @if($campagne->instructions)
                <div class="bg-white border border-border rounded-2xl p-6">
                    <h2 class="text-base font-semibold text-text-primary mb-3">Instructions officielles</h2>
                    <div class="text-sm text-text-secondary leading-7 whitespace-pre-line">{{ $campagne->instructions }}</div>
                </div>
            @endif


            {{-- PÉRIMÈTRE TERRITORIAL --}}
            <div class="bg-white border border-border rounded-2xl p-6">

                <div class="flex items-center justify-between gap-4 mb-5">
                    <div>
                        <h2 class="text-base font-semibold text-text-primary">Périmètre territorial</h2>
                        <p class="text-xs text-text-secondary mt-1">Territoire concerné par la campagne</p>
                    </div>

                    @if($campagne->portee === 'nationale')
                        <span class="px-2.5 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                            Tout le territoire
                        </span>
                    @else
                        <span class="px-2.5 py-1 rounded-full bg-background-muted text-text-secondary text-xs font-semibold">
                            {{ $campagne->zones->count() }} zone(s)
                        </span>
                    @endif
                </div>

                @if($campagne->portee === 'nationale')

                    <div class="rounded-xl border border-green-200 bg-green-50 p-4">
                        <p class="text-sm font-semibold text-green-800">Campagne nationale</p>
                        <p class="text-xs text-green-700 mt-1">
                            La campagne couvre l'ensemble du territoire national.
                        </p>
                    </div>

                @elseif($campagne->zones->isNotEmpty())

                    <div class="space-y-2">
                        @foreach($campagne->zones as $zone)

                            @php
                                $typeZone = match ($zone->zone_type) {
                                    'region'     => 'Région',
                                    'prefecture' => 'Préfecture',
                                    'commune'    => 'Commune',
                                    'canton'     => 'Canton',
                                    'village'    => 'Village',
                                    default      => ucfirst($zone->zone_type ?? 'Zone'),
                                };
                            @endphp

                            <div class="flex items-center gap-3 p-3 rounded-xl border border-border bg-background-muted/30">
                                <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                        <circle cx="12" cy="11" r="3" stroke-width="1.8" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-text-primary">{{ $zone->nom_zone }}</p>
                                    <p class="text-xs text-text-secondary">{{ $typeZone }}</p>
                                </div>
                            </div>

                        @endforeach
                    </div>

                @else
                    <p class="text-sm text-text-muted italic">Aucun périmètre détaillé.</p>
                @endif

            </div>

        </div>


        {{-- =====================================================
             COLONNE LATÉRALE
        ====================================================== --}}

        <div class="space-y-6">

            {{-- INFORMATIONS ESSENTIELLES --}}
            <div class="bg-white border border-border rounded-2xl p-5">
                <h2 class="text-base font-semibold text-text-primary mb-4">Informations essentielles</h2>

                <div class="space-y-4">

                    <div>
                        <p class="text-xs text-text-muted">Code campagne</p>
                        <p class="text-sm font-semibold text-text-primary mt-1">{{ $campagne->codeCampagne }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-text-muted">Portée</p>
                        <p class="text-sm font-semibold text-text-primary mt-1">{{ $porteeLabel }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-text-muted">Structure porteuse</p>
                        <p class="text-sm font-semibold text-text-primary mt-1">
                            {{ $campagne->structure?->nom ?? 'Non renseignée' }}
                        </p>
                    </div>

                    @if($campagne->estOfficielle)
                        <div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-green-50 text-green-700 text-xs font-semibold">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Campagne officielle
                            </span>
                        </div>
                    @endif

                </div>
            </div>


            {{-- RÉCEPTION --}}
            <div class="bg-white border border-border rounded-2xl p-5">
                <h2 class="text-base font-semibold text-text-primary mb-4">Réception</h2>

                <div class="space-y-4">

                    <div>
                        <p class="text-xs text-text-muted">Préfecture</p>
                        <p class="text-sm font-semibold text-text-primary mt-1">
                            {{ $deploiement->prefecture?->nom ?? $prefecture->nom }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-text-muted mb-1">État</p>
                        @if($receptionne)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-green-50 text-green-700 text-xs font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                Réceptionnée
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-orange-50 text-orange-700 text-xs font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                En attente
                            </span>
                        @endif
                    </div>

                    @if($deploiement->dateReception)
                        <div>
                            <p class="text-xs text-text-muted">Date de réception</p>
                            <p class="text-sm font-semibold text-text-primary mt-1">
                                {{ $deploiement->dateReception->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                    @endif

                    @if($deploiement->recuPar)
                        <div>
                            <p class="text-xs text-text-muted">Réceptionné par</p>
                            <p class="text-sm font-semibold text-text-primary mt-1">{{ $deploiement->recuPar->name }}</p>
                        </div>
                    @endif

                </div>
            </div>


            {{-- ACTION RÉCEPTION (rappel, uniquement si non réceptionnée) --}}
            @unless($receptionne)

                <div class="bg-primary rounded-2xl p-5 text-white">
                    <h2 class="text-sm font-semibold">Confirmer la réception</h2>

                    <p class="text-xs text-white/70 mt-2 leading-5">
                        Confirmez que votre préfecture a bien reçu la campagne et son planning.
                    </p>

                    <form
                        method="POST"
                        action="{{ route('dpa.campagnes.reception', $deploiement) }}"
                        class="mt-4"
                        onsubmit="return confirm('Confirmez-vous officiellement la réception de cette campagne ?');"
                    >
                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            class="w-full bg-white text-primary px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-50 transition"
                        >
                            Accuser réception
                        </button>
                    </form>
                </div>

            @endunless


            {{-- ÉTAT DU PLANNING (information uniquement — aucun bouton dupliqué ici) --}}
            @if($planificationPrefectorale)

                <div class="bg-white border border-border rounded-2xl p-5">
                    <div class="flex items-start gap-3">

                        <div class="w-9 h-9 rounded-lg bg-green-50 text-green-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>

                        <div>
                            <h2 class="text-sm font-semibold text-text-primary">Planning défini</h2>
                            <p class="text-xs text-text-secondary mt-1">
                                {{ $activites->count() }} activité{{ $activites->count() > 1 ? 's' : '' }}
                                enregistrée{{ $activites->count() > 1 ? 's' : '' }}.
                            </p>
                        </div>

                    </div>
                </div>

            @elseif($receptionne)

                <div class="bg-primary rounded-2xl p-5 text-white">
                    <div class="flex items-start gap-3">

                        <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>

                        <div>
                            <h2 class="text-sm font-semibold">Campagne prête à planifier</h2>
                            <p class="text-xs text-white/70 mt-1 leading-5">
                                Utilisez le bouton « Planifier » en haut de page pour définir
                                les activités et les périodes nécessaires à l'exécution de la campagne.
                            </p>
                        </div>

                    </div>
                </div>

            @endif

        </div>

    </div>

</div>

@endsection