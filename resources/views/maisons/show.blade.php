@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>

            {{-- FIL D'ARIANE --}}
            <div class="mb-2 flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('villages.maisons.index', $maison->village->idVillage) }}" class="transition hover:text-[#006a4f]">
                    {{ $maison->village->nomVillage ?? 'Maisons' }}
                </a>
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7" />
                </svg>
                <span class="text-gray-700">{{ $maison->numeroMaison }}</span>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-2xl font-semibold tracking-tight text-[#212529]">
                    Maison {{ $maison->numeroMaison }}
                </h1>

                @if($recensement)
                    @php
                        $s = match($recensement->statut) {
                            'brouillon' => ['bg-gray-100 text-gray-600', 'Brouillon'],
                            'en_cours'  => ['bg-blue-50 text-blue-700', 'En cours'],
                            'termine'   => ['bg-amber-50 text-amber-800', 'Terminé'],
                            'valide'    => ['bg-green-50 text-green-700', 'Validé'],
                            default     => ['bg-gray-100 text-gray-600', 'Inconnu'],
                        };
                    @endphp
                    <span class="inline-flex items-center gap-1.5 rounded-full {{ $s[0] }} px-3 py-1 text-xs font-semibold">
                        <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                        {{ $s[1] }}
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                        <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                        Non recensée
                    </span>
                @endif
            </div>

            <p class="mt-1 text-sm text-gray-500">
                Référentiel permanent et suivi des recensements de cette maison.
            </p>

        </div>


        {{-- ACTIONS --}}
        <div class="flex items-center gap-2">
            <a href="{{ route('villages.maisons.index', $maison->village->idVillage) }}"
               class="inline-flex items-center gap-2 rounded-lg border border-[#e5e7eb] bg-white px-4 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Retour
            </a>

            <a href="{{ route('maisons.edit', $maison->idMaison) }}"
               class="inline-flex items-center gap-2 rounded-lg bg-[#006a4f] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#156c52]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.5-9.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 8.5-8.5z" />
                </svg>
                Modifier
            </a>
        </div>

    </div>


    {{-- =========================================================
         INFORMATIONS GÉNÉRALES
    ========================================================== --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- CARTE MAISON --}}
        <div class="rounded-lg border border-[#e5e7eb] bg-white shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

            <div class="border-b border-[#e5e7eb] px-6 py-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#e5f2ee] text-[#006a4f]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-5h6v5" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-[#212529]">Informations de la maison</h2>
                        <p class="text-xs text-gray-500">Données permanentes du référentiel</p>
                    </div>
                </div>
            </div>

            <div class="space-y-5 px-6 py-5">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Numéro de maison</p>
                    <p class="mt-1 font-mono text-sm font-semibold text-[#006a4f]">{{ $maison->numeroMaison ?? '—' }}</p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Identifiant unique</p>
                    <p class="mt-1 break-all font-mono text-xs text-gray-600">{{ $maison->uid ?? '—' }}</p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Adresse</p>
                    <p class="mt-1 text-sm text-gray-700">{{ $maison->adresse ?: 'Aucune adresse renseignée' }}</p>
                </div>
            </div>
        </div>


        {{-- CARTE GPS --}}
        <div class="rounded-lg border border-[#e5e7eb] bg-white shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

            <div class="border-b border-[#e5e7eb] px-6 py-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-50 text-purple-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s8-4.35 8-10a8 8 0 10-16 0c0 5.65 8 10 8 10z" />
                            <circle cx="12" cy="11" r="2.5" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-[#212529]">Localisation GPS</h2>
                        <p class="text-xs text-gray-500">Coordonnées enregistrées</p>
                    </div>
                </div>
            </div>

            <div class="space-y-5 px-6 py-5">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Latitude</p>
                    <p class="mt-1 font-mono text-sm font-semibold text-gray-800">{{ $maison->latitude ?? 'Non renseignée' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Longitude</p>
                    <p class="mt-1 font-mono text-sm font-semibold text-gray-800">{{ $maison->longitude ?? 'Non renseignée' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Précision</p>
                    <p class="mt-1 font-mono text-sm font-semibold text-gray-800">
                        {{ $maison->precisionGPS !== null ? $maison->precisionGPS . ' m' : 'Non renseignée' }}
                    </p>
                </div>
            </div>
        </div>


        {{-- CARTE CAMPAGNE ACTIVE --}}
        <div class="rounded-lg border border-[#e5e7eb] bg-white shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

            <div class="border-b border-[#e5e7eb] px-6 py-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-[#212529]">Campagne active</h2>
                        <p class="text-xs text-gray-500">Recensement en cours</p>
                    </div>
                </div>
            </div>

            <div class="px-6 py-6">
                @if($affectation)
                    <p class="text-sm font-semibold text-[#212529]">
                        {{ $affectation->campagne->libelle ?? '—' }}
                    </p>

                    <div class="mt-4">
                        @if($recensement)
                            @if($recensement->statut === 'valide')
                                <span class="inline-flex items-center rounded-lg bg-green-50 px-3.5 py-2 text-sm font-semibold text-green-700">
                                    Recensement validé
                                </span>
                            @else
                                <a href="{{ route('agent.recensements.menages.index', $recensement) }}"
                                   class="inline-flex items-center gap-2 rounded-lg bg-[#006a4f] px-3.5 py-2 text-sm font-semibold text-white transition hover:bg-[#156c52]">
                                    Ouvrir le recensement
                                </a>
                            @endif
                        @else
                            <form method="POST" action="{{ route('agent.recensements.commencer', [
                                'affectation' => $affectation->idAffectation,
                                'maison' => $maison->idMaison,
                            ]) }}">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center gap-2 rounded-lg bg-[#006a4f] px-3.5 py-2 text-sm font-semibold text-white transition hover:bg-[#156c52]">
                                    Commencer le recensement
                                </button>
                            </form>
                        @endif
                    </div>
                @else
                    <div class="py-4 text-center">
                        <p class="text-sm font-medium text-gray-500">Aucune affectation active</p>
                    </div>
                @endif
            </div>
        </div>

    </div>


    {{-- =========================================================
         HISTORIQUE DES RECENSEMENTS
    ========================================================== --}}
    <div class="overflow-hidden rounded-lg border border-[#e5e7eb] bg-white shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

        <div class="flex flex-col gap-3 border-b border-[#e5e7eb] px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-base font-semibold text-[#212529]">Historique des recensements</h2>
                <p class="mt-1 text-xs text-gray-500">Suivi de cette maison au fil des campagnes</p>
            </div>

            <span class="rounded-full bg-[#f8faf9] px-3 py-1.5 text-sm text-gray-500">
                <span class="font-semibold text-[#006a4f]">{{ $maison->recensements->count() }}</span>
                {{ $maison->recensements->count() > 1 ? 'recensements' : 'recensement' }}
            </span>
        </div>

        @if($maison->recensements->isNotEmpty())

            <div class="divide-y divide-[#e5e7eb]">
                @foreach($maison->recensements->sortByDesc('created_at') as $rec)
                    @php
                        $s = match($rec->statut) {
                            'brouillon' => ['bg-gray-100 text-gray-700', 'Brouillon'],
                            'en_cours'  => ['bg-blue-50 text-blue-700', 'En cours'],
                            'termine'   => ['bg-amber-50 text-amber-800', 'Terminé'],
                            'valide'    => ['bg-green-50 text-green-700', 'Validé'],
                            default     => ['bg-gray-100 text-gray-600', ucfirst($rec->statut ?? 'Inconnu')],
                        };
                    @endphp

                    <div class="flex flex-col gap-4 px-6 py-5 transition hover:bg-[#f8faf9] sm:flex-row sm:items-center sm:justify-between">

                        <div class="flex items-center gap-4">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#e5f2ee] text-sm font-semibold text-[#006a4f]">
                                {{ strtoupper(substr($rec->agent->name ?? 'A', 0, 1)) }}
                            </div>

                            <div>
                                <p class="font-semibold text-[#212529]">{{ $rec->campagne->libelle ?? 'Campagne non renseignée' }}</p>
                                <div class="mt-1 flex flex-wrap items-center gap-x-4 gap-y-1">
                                    <span class="text-xs text-gray-500">{{ $rec->agent->name ?? 'Agent non renseigné' }}</span>
                                    <span class="text-xs text-gray-400">{{ $rec->affectation->equipe->nomEquipe ?? '—' }}</span>
                                    <span class="text-xs text-gray-400">
                                        {{ optional($rec->dateDerniereModification ?? $rec->created_at)?->format('d/m/Y H:i') ?? '—' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center gap-1.5 rounded-full {{ $s[0] }} px-3 py-1 text-xs font-semibold">
                                <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                {{ $s[1] }}
                            </span>

                            <a href="{{ route('agent.recensements.menages.index', $rec) }}"
                               class="inline-flex items-center gap-1 text-xs font-semibold text-[#006a4f] hover:underline">
                                Voir les ménages
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 18 6-6-6-6" />
                                </svg>
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>

        @else

            <div class="px-6 py-16 text-center">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-[#e5f2ee] text-[#006a4f]">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3M16 7V3M4 11h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z" />
                    </svg>
                </div>

                <p class="font-semibold text-[#212529]">Aucun recensement</p>
                <p class="mt-1 text-sm text-gray-500">
                    Cette maison existe dans le référentiel permanent, mais aucun recensement n'a encore été enregistré pour elle.
                </p>

                @if($affectation && !$recensement)
                    <form method="POST" class="mt-4" action="{{ route('agent.recensements.commencer', [
                        'affectation' => $affectation->idAffectation,
                        'maison' => $maison->idMaison,
                    ]) }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 text-sm font-semibold text-[#006a4f] hover:underline">
                            Commencer le recensement
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 18 6-6-6-6" />
                            </svg>
                        </button>
                    </form>
                @endif
            </div>

        @endif

    </div>


    {{-- =========================================================
         RAPPEL ARCHITECTURE
    ========================================================== --}}
    <div class="rounded-lg border border-blue-200 bg-blue-50 px-5 py-4">
        <div class="flex items-start gap-3">
            <svg class="mt-0.5 h-5 w-5 shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 16h-1v-4h-1m1-4h.01M12 21a9 9 0 100-18" />
            </svg>
            <div>
                <p class="text-sm font-semibold text-blue-800">Référentiel vs recensement</p>
                <p class="mt-1 text-sm leading-6 text-blue-700">
                    Cette maison est une entité permanente du référentiel. Elle peut être recensée
                    à plusieurs reprises au fil des campagnes — chaque ligne de l'historique ci-dessus
                    correspond à une campagne distincte, avec ses propres ménages.
                </p>
            </div>
        </div>
    </div>

    {{-- Métadonnées techniques --}}
    <div class="rounded-lg border border-gray-100 bg-gray-50 px-5 py-4">
        <dl class="grid grid-cols-1 gap-4 text-sm sm:grid-cols-3">
            <div>
                <dt class="text-xs text-gray-400">Créée le</dt>
                <dd class="mt-0.5 font-medium text-gray-600">{{ $maison->created_at?->format('d/m/Y H:i') ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-400">Dernière mise à jour</dt>
                <dd class="mt-0.5 font-medium text-gray-600">{{ $maison->updated_at?->format('d/m/Y H:i') ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-400">Identifiant technique</dt>
                <dd class="mt-0.5 font-mono text-xs font-medium text-gray-600">#{{ $maison->idMaison }}</dd>
            </div>
        </dl>
    </div>

</div>

@endsection