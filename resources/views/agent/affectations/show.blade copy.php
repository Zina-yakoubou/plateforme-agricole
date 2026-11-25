@extends('layouts.app')

@section('content')

@php
    $campagne = $affectation->campagne;
    // On récupère le statut réel dynamique calculé par le modèle (à la seconde près)
    $campagneStatut = $campagne?->statut_reel ?? $campagne?->statut ?? 'planifiee';
    $estBloquee = in_array($campagneStatut, ['planifiee', 'cloturee', 'planifiée', 'clôturée']);
@endphp

<div class="space-y-6">

    {{-- ============================================================
        INFORMATIONS DE L'AFFECTATION & COMPTEUR TEMPOREL
    ============================================================ --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

        {{-- Campagne & Compteur en temps réel --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"
             x-data="compteurCampagne('{{ $campagne?->dateFin }}', '{{ $campagne?->dateDebut }}', '{{ $campagneStatut }}')"
             x-init="init()">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Campagne</p>
                    <p class="font-semibold text-slate-800 truncate" title="{{ $campagne?->libelle }}">{{ $campagne?->libelle }}</p>
                    
                    <div class="mt-1 flex items-center justify-between">
                        <span class="rounded-full px-2 py-0.5 text-[10px] font-semibold uppercase {{ $estBloquee ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700' }}">
                            {{ $campagneStatut }}
                        </span>
                    </div>
                    
                    {{-- Affichage du compte à rebours précis en secondes/minutes --}}
                    <div class="mt-2 text-[11px] font-medium text-slate-500 flex items-center gap-1">
                        <svg class="h-3.5 w-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span x-text="texteRestant">Chargement du temps...</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Équipe --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-5a4 4 0 10-8 0 4 4 0 008 0zm-9 0a3 3 0 10-6 0 3 3 0 006 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Équipe</p>
                    <p class="font-semibold text-slate-800">{{ $affectation->equipe->nom ?? 'Équipe' }}</p>
                </div>
            </div>
        </div>

        {{-- Canton --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 21a2 2 0 01-2.828 0l-4.243-4.343a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Canton</p>
                    <p class="font-semibold text-slate-800">{{ $affectation->village->canton->nom }}</p>
                </div>
            </div>
        </div>

        {{-- Commune --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100 text-slate-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M5 21V9l7-4 7 4v12M9 21v-6h6v6"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Commune</p>
                    <p class="font-semibold text-slate-800">{{ $affectation->village->canton->commune->nom }}</p>
                </div>
            </div>
        </div>

    </div>


    {{-- Alerte si la campagne est bloquée --}}
    @if($estBloquee)
        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-amber-800 flex items-center gap-3">
            <svg class="h-5 w-5 shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
            </svg>
            <p class="text-sm font-medium">
                Cette campagne est actuellement <strong>{{ $campagneStatut }}</strong>. Les actions de modification ou de recensement sur les maisons sont verrouillées.
            </p>
        </div>
    @endif


    {{-- ============================================================
        MAISONS DU VILLAGE & FILTRES
    ============================================================ --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

        {{-- En-tête avec Recherche et Filtres --}}
        <div class="border-b border-slate-200 px-6 py-5">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">
                        Maisons à recenser
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Sélectionnez une maison pour commencer, reprendre ou consulter son dossier complet.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="rounded-xl bg-slate-100 px-4 py-2">
                        <span class="text-sm font-semibold text-slate-700">{{ $maisons->total() }}</span>
                        <span class="text-sm text-slate-500">maison(s)</span>
                    </div>

                    {{-- Bouton d'ajout protégé --}}
                    @if(!$estBloquee)
                        <a
                            href="{{ route('villages.maisons.create', $affectation->village) }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Ajouter une maison
                        </a>
                    @else
                        <button
                            disabled
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-500 cursor-not-allowed"
                            title="Action impossible : campagne planifiée ou clôturée"
                        >
                            Ajouter une maison
                        </button>
                    @endif
                </div>
            </div>

            {{-- Formulaire de recherche et de filtre par statut --}}
            <form method="GET" action="{{ route('agent.affectations.show', $affectation) }}" class="mt-5 flex flex-col gap-3 sm:flex-row">
                
                {{-- Barre de recherche --}}
                <div class="relative flex-1">
                    <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"/>
                    </svg>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Rechercher par numéro de maison ou adresse..."
                        class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-11 pr-4 text-sm text-slate-800 placeholder:text-slate-400 focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600"
                    >
                </div>

                {{-- Filtre par statut --}}
                <div class="sm:w-64">
                    <select
                        name="statut"
                        onchange="this.form.submit()"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600"
                    >
                        <option value="">Tous les statuts</option>
                        <option value="non_commence" @selected(($statutFiltre ?? '') === 'non_commence')>Non commencé</option>
                        <option value="brouillon" @selected(($statutFiltre ?? '') === 'brouillon')>Brouillon</option>
                        <option value="en_cours" @selected(($statutFiltre ?? '') === 'en_cours')>En cours</option>
                        <option value="termine" @selected(($statutFiltre ?? '') === 'termine')>Terminé</option>
                        <option value="valide" @selected(($statutFiltre ?? '') === 'valide')>Validé</option>
                    </select>
                </div>

                {{-- Bouton de soumission / réinitialisation --}}
                <div class="flex items-center gap-2">
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-slate-800 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700"
                    >
                        Filtrer
                    </button>

                    @if(($search ?? '') !== '' || ($statutFiltre ?? '') !== '')
                        <a
                            href="{{ route('agent.affectations.show', $affectation) }}"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
                        >
                            Réinitialiser
                        </a>
                    @endif
                </div>

            </form>
        </div>


        {{-- Liste des maisons --}}
        <div class="divide-y divide-slate-100">

            @forelse($maisons as $maison)

                @php
                    $recensement = $maison->recensements->first();
                @endphp

                <div class="px-6 py-5 transition hover:bg-slate-50">

                    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                        {{-- Informations maison --}}
                        <div class="flex items-start gap-4">

                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">

                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10.5L12 3l9 7.5M5 9.5V21h14V9.5M9 21v-6h6v6"/>
                                </svg>

                            </div>

                            <div>

                                <div class="flex flex-wrap items-center gap-2">

                                    <h3 class="font-bold text-slate-800">
                                        Maison {{ $maison->numeroMaison }}
                                    </h3>

                                    @if($recensement)

                                        @if($recensement->statut === 'brouillon')

                                            <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                                Brouillon
                                            </span>

                                        @elseif($recensement->statut === 'en_cours')

                                            <span class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700">
                                                En cours
                                            </span>

                                        @elseif($recensement->statut === 'termine')

                                            <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                                Terminé
                                            </span>

                                        @elseif($recensement->statut === 'valide')

                                            <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">
                                                Validé
                                            </span>

                                        @endif

                                    @else

                                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                            Non commencé
                                        </span>

                                    @endif

                                </div>


                                <div class="mt-2 flex flex-wrap gap-x-5 gap-y-1 text-sm text-slate-500">

                                    <span>
                                        Village :
                                        <strong class="font-medium text-slate-700">
                                            {{ $affectation->village->nom }}
                                        </strong>
                                    </span>

                                    @if($maison->adresse)

                                        <span>
                                            {{ $maison->adresse }}
                                        </span>

                                    @endif

                                </div>


                                @if($recensement)

                                    <div class="mt-2 flex items-center gap-2 text-sm text-slate-500">

                                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-5a4 4 0 10-8 0 4 4 0 008 0z"/>
                                        </svg>

                                        <span>
                                            {{ $recensement->menages_count ?? 0 }}
                                            ménage(s)
                                        </span>

                                    </div>

                                @endif

                            </div>

                        </div>


                        {{-- Actions (Reprise/Commencer + Dossier Complet A-Z) --}}
                        <div class="flex flex-wrap items-center gap-2 shrink-0">

                            {{-- Bouton d'accès au Dossier Complet A-Z si le recensement existe --}}
                            @if($recensement)
                                <a
                                    href="{{ route('agent.recensements.dossier_complet', $recensement->idRecensement) }}"
                                    class="inline-flex items-center gap-1.5 rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
                                    title="Consulter le dossier complet de la chaîne (Maison -> Ménages -> Exploitant -> Parcelles -> Cultures -> Intrants)"
                                >
                                    <svg class="h-4 w-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z"/>
                                    </svg>
                                    Dossier A-Z
                                </a>
                            @endif

                            @if($estBloquee)
                                <span class="inline-flex items-center gap-1.5 rounded-xl bg-slate-100 px-4 py-3 text-xs font-semibold text-slate-400 cursor-not-allowed">
                                    Verrouillé (Campagne {{ $campagneStatut }})
                                </span>
                            @else
                                @if($recensement)

                                    <a
                                        href="{{ route('agent.recensements.show', $recensement) }}"
                                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700"
                                    >
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
                                        Reprendre
                                    </a>

                                @else

                                    <form action="{{ route('agent.recensements.commencer', ['affectation' => $affectation->idAffectation, 'maison' => $maison->idMaison]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Commencer
                                        </button>
                                    </form>

                                @endif
                            @endif

                        </div>

                    </div>

                </div>

            @empty

                <div class="px-6 py-12 text-center">

                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10.5L12 3l9 7.5M5 9.5V21h14V9.5"/>
                        </svg>
                    </div>

                    <h3 class="mt-4 text-base font-semibold text-slate-800">
                        Aucune maison trouvée
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Aucune maison ne correspond à vos critères de recherche.
                    </p>

                </div>

            @endforelse

        </div>

        {{-- Pagination --}}
        @if($maisons->hasPages())
            <div class="border-t border-slate-200 px-6 py-4">
                {{ $maisons->withQueryString()->links() }}
            </div>
        @endif

    </div>

</div>

{{-- Script Alpine.js pour gérer le compte à rebours de la campagne à la seconde près --}}
@push('scripts')
<script>
function compteurCampagne(dateFinStr, dateDebutStr, statutActuel) {
    return {
        texteRestant: 'Calcul...',
        init() {
            const updateTimer = () => {
                const maintenant = new Date().getTime();
                const debut = dateDebutStr ? new Date(dateDebutStr).getTime() : null;
                const fin = dateFinStr ? new Date(dateFinStr).getTime() : null;

                if (statutActuel === 'cloturee' || (fin && maintenant > fin)) {
                    this.texteRestant = 'Campagne clôturée';
                    return;
                }

                if (debut && maintenant < debut) {
                    const diff = debut - maintenant;
                    this.texteRestant = 'Début dans : ' + this.formaterDifference(diff);
                    return;
                }

                if (fin) {
                    const diff = fin - maintenant;
                    this.texteRestant = 'Clôture dans : ' + this.formaterDifference(diff);
                } else {
                    this.texteRestant = 'Campagne active';
                }
            };

            updateTimer();
            setInterval(updateTimer, 1000);
        },
        formaterDifference(ms) {
            const jours = Math.floor(ms / (1000 * 60 * 60 * 24));
            const heures = Math.floor((ms % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((ms % (1000 * 60 * 60)) / (1000 * 60));
            const secondes = Math.floor((ms % (1000 * 60)) / 1000);

            let txt = '';
            if (jours > 0) txt += jours + 'j ';
            txt += String(heures).padStart(2, '0') + 'h ';
            txt += String(minutes).padStart(2, '0') + 'm ';
            txt += String(secondes).padStart(2, '0') + 's';
            return txt;
        }
    }
}
</script>
@endpush

@endsection