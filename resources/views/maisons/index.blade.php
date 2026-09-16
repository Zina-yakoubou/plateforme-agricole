@extends('layouts.app')

@section('title', 'Maisons du village')

@section('content')

<div class="space-y-6">

    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>

            {{-- FIL D'ARIANE --}}
            <div class="mb-2 flex items-center gap-2 text-sm text-gray-500">
                <span>{{ $village->canton->nomCanton ?? 'Canton inconnu' }}</span>
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7" />
                </svg>
                <span class="text-gray-700">{{ $village->nomVillage }}</span>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-2xl font-semibold tracking-tight text-[#212529]">
                    Maisons du village
                </h1>

                <span class="rounded-full bg-[#e5f2ee] px-3 py-1 text-xs font-semibold text-[#006a4f]">
                    SIRA-Mô
                </span>

                @if($affectation)
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                        <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                        Campagne {{ $affectation->campagne->libelle ?? 'en cours' }}
                    </span>
                @endif
            </div>

            <p class="mt-1 text-sm text-gray-500">
                Référentiel permanent des maisons et suivi de leur recensement pour la campagne active.
            </p>

        </div>


        {{-- ACTIONS --}}
        <div class="flex items-center gap-2">
            <a href="{{ $affectation ? route('agent.recensements.maison.create', $affectation->idAffectation) : route('villages.maisons.create', $village->idVillage) }}"
               class="inline-flex items-center gap-2 rounded-lg bg-[#006a4f] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#156c52]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Ajouter une maison
            </a>
        </div>

    </div>


    {{-- =========================================================
         STATISTIQUES RAPIDES
    ========================================================== --}}
    @php
        $total = $maisons->total();
        $recensees = $affectation
            ? $maisons->filter(fn($m) => $m->recensements->firstWhere('campagne_id', $affectation->campagne_id))->count()
            : null;
        $localisees = $maisons->filter(fn($m) => $m->latitude !== null)->count();
    @endphp

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

        <div class="rounded-lg border border-[#e5e7eb] bg-white p-5 shadow-[0_1px_2px_rgba(0,0,0,0.05)]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-2xl font-semibold text-[#212529]">{{ $total }}</p>
                    <p class="mt-1 text-sm text-gray-500">Maison{{ $total > 1 ? 's' : '' }} enregistrée{{ $total > 1 ? 's' : '' }}</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-[#e5f2ee] text-[#006a4f]">
                    <svg class="h-5.5 w-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-5h6v5" />
                    </svg>
                </div>
            </div>
        </div>

        @if($affectation)
            <div class="rounded-lg border border-[#e5e7eb] bg-white p-5 shadow-[0_1px_2px_rgba(0,0,0,0.05)]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-2xl font-semibold text-[#212529]">{{ $recensees }} / {{ $total }}</p>
                        <p class="mt-1 text-sm text-gray-500">Recensées cette campagne</p>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                        <svg class="h-5.5 w-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>
        @endif

        <div class="rounded-lg border border-[#e5e7eb] bg-white p-5 shadow-[0_1px_2px_rgba(0,0,0,0.05)]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-2xl font-semibold text-[#212529]">{{ $localisees }} / {{ $total }}</p>
                    <p class="mt-1 text-sm text-gray-500">Localisées GPS</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-purple-50 text-purple-600">
                    <svg class="h-5.5 w-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s8-4.35 8-10a8 8 0 10-16 0c0 5.65 8 10 8 10z" />
                        <circle cx="12" cy="11" r="2.5" />
                    </svg>
                </div>
            </div>
        </div>

    </div>


    {{-- =========================================================
         RECHERCHE
    ========================================================== --}}
    <div class="rounded-lg border border-[#e5e7eb] bg-white p-4 shadow-[0_1px_2px_rgba(0,0,0,0.05)]">
        <form method="GET">
            <div class="flex flex-col gap-3 sm:flex-row">

                <div class="relative flex-1">
                    <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z" />
                    </svg>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Numéro ou adresse..."
                           class="w-full rounded-lg border border-[#e5e7eb] bg-white py-3 pl-11 pr-4 text-sm text-[#212529] placeholder:text-gray-400 focus:border-[#006a4f] focus:outline-none focus:ring-2 focus:ring-[#006a4f]/10">
                    @if($affectation)
                        <input type="hidden" name="affectation_id" value="{{ $affectation->idAffectation }}">
                    @endif
                </div>

                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#006a4f] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#156c52]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z" />
                    </svg>
                    Rechercher
                </button>

                @if(request('q'))
                    <a href="{{ request()->fullUrlWithQuery(['q' => null]) }}"
                       class="inline-flex items-center justify-center rounded-lg border border-[#e5e7eb] bg-white px-4 py-3 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
                        Réinitialiser
                    </a>
                @endif

            </div>
        </form>
    </div>


    {{-- =========================================================
         TABLEAU DES MAISONS
    ========================================================== --}}
    <div class="overflow-hidden rounded-lg border border-[#e5e7eb] bg-white shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

        <div class="flex flex-col gap-1 border-b border-[#e5e7eb] px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-base font-semibold text-[#212529]">Liste des maisons</h2>
                <p class="mt-1 text-xs text-gray-500">
                    Rattachement des maisons à leurs recensements
                    @if(request('q')) · résultats pour « {{ request('q') }} » @endif
                </p>
            </div>
            <div class="text-sm text-gray-500">
                <span class="font-semibold text-[#006a4f]">{{ $maisons->total() }}</span> maison(s)
            </div>
        </div>

        @if($maisons->count())

            <div class="overflow-x-auto">
                <table class="w-full min-w-[950px] text-sm">
                    <thead class="bg-[#f8faf9]">
                        <tr class="border-b border-[#e5e7eb]">
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">N°</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Maison</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Adresse</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">GPS</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">
                                {{ $affectation ? 'Statut campagne' : 'Statut' }}
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">Ménages</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#e5e7eb]">
                        @foreach($maisons as $maison)

                            @php
                                $recensement = $affectation
                                    ? $maison->recensements->firstWhere('campagne_id', $affectation->campagne_id)
                                    : $maison->recensements->sortByDesc('created_at')->first();

                                $nombreMenages = $recensement?->menages_count;
                                $termine = in_array($recensement?->statut, ['termine', 'valide'], true);
                            @endphp

                            <tr class="group transition hover:bg-[#f8faf9]">

                                <td class="px-6 py-4 text-sm text-gray-400">
                                    {{ $maisons->firstItem() + $loop->index }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#e5f2ee] text-sm font-semibold text-[#006a4f]">
                                            {{ strtoupper(substr($maison->numeroMaison ?? 'M', -2)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <a href="{{ route('maisons.show', $maison->idMaison) }}"
                                               class="truncate font-semibold text-[#212529] hover:text-[#006a4f] hover:underline">
                                                {{ $maison->numeroMaison }}
                                            </a>
                                            <p class="mt-0.5 truncate font-mono text-xs text-gray-400">{{ $maison->uid }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="max-w-xs px-6 py-4">
                                    <span class="block truncate {{ $maison->adresse ? 'text-gray-700' : 'text-gray-400' }}">
                                        {{ $maison->adresse ?: 'Non renseignée' }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($maison->latitude !== null && $maison->longitude !== null)
                                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-[#006a4f]">
                                            <span class="h-1.5 w-1.5 rounded-full bg-[#006a4f]"></span>
                                            Localisée
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($recensement && $termine)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-[#e5f2ee] px-2.5 py-1 text-xs font-semibold text-[#00503b]">
                                            <span class="h-1.5 w-1.5 rounded-full bg-[#006a4f]"></span>
                                            Terminé
                                        </span>
                                    @elseif($recensement)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-800">
                                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                            En cours
                                        </span>
                                    @elseif($affectation)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-500">
                                            <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                            À recenser
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">Jamais recensée</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($recensement)
                                        <span class="font-semibold text-[#212529]">{{ $nombreMenages ?? 0 }}</span>
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">

                                        <a href="{{ route('maisons.show', $maison->idMaison) }}"
                                           title="Voir la fiche"
                                           class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-[#e5e7eb] bg-white text-gray-600 transition hover:border-[#006a4f] hover:bg-[#e5f2ee] hover:text-[#006a4f]">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </a>

                                        @if($affectation)
                                            @if($recensement)
                                                <a href="{{ route('agent.recensements.menages.index', $recensement) }}"
                                                   class="rounded-lg bg-[#006a4f] px-3 py-2 text-xs font-semibold text-white transition hover:bg-[#156c52]">
                                                    {{ $termine ? 'Consulter' : 'Continuer' }}
                                                </a>
                                            @else
                                                <form method="POST" action="{{ route('agent.recensements.commencer', [
                                                    'affectation' => $affectation->idAffectation,
                                                    'maison' => $maison->idMaison,
                                                ]) }}">
                                                    @csrf
                                                    <button type="submit"
                                                            class="rounded-lg bg-[#006a4f] px-3 py-2 text-xs font-semibold text-white transition hover:bg-[#156c52]">
                                                        Recenser
                                                    </button>
                                                </form>
                                            @endif
                                        @endif

                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @else

            <div class="px-6 py-16 text-center">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-[#e5f2ee] text-[#006a4f]">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-5h6v5" />
                    </svg>
                </div>

                <p class="font-semibold text-[#212529]">
                    @if(request('q')) Aucune maison ne correspond à « {{ request('q') }} » @else Aucune maison enregistrée @endif
                </p>

                <p class="mt-1 text-sm text-gray-500">
                    @if(request('q'))
                        Vérifiez le numéro ou l'adresse recherchée.
                    @else
                        Enregistrez les maisons du village : elles serviront de base à toutes les campagnes à venir.
                    @endif
                </p>

                <div class="mt-4">
                    @if(request('q'))
                        <a href="{{ request()->fullUrlWithQuery(['q' => null]) }}" class="text-sm font-semibold text-[#006a4f] hover:underline">
                            Réinitialiser la recherche
                        </a>
                    @else
                        <a href="{{ $affectation ? route('agent.recensements.maison.create', $affectation->idAffectation) : route('villages.maisons.create', $village->idVillage) }}"
                           class="inline-flex items-center gap-2 text-sm font-semibold text-[#006a4f] hover:underline">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Ajouter une maison
                        </a>
                    @endif
                </div>
            </div>

        @endif

        @if($maisons->hasPages())
            <div class="border-t border-[#e5e7eb] px-6 py-4">
                {{ $maisons->withQueryString()->links() }}
            </div>
        @endif

    </div>


    {{-- =========================================================
         RAPPEL
    ========================================================== --}}
    @if($affectation)
        <div class="rounded-lg border border-blue-200 bg-blue-50 px-5 py-4">
            <div class="flex items-start gap-3">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 16h-1v-4h-1m1-4h.01M12 21a9 9 0 100-18" />
                </svg>
                <div>
                    <p class="text-sm font-semibold text-blue-800">Rattachement à la campagne</p>
                    <p class="mt-1 text-sm leading-6 text-blue-700">
                        Les recensements créés depuis cette page sont automatiquement rattachés à la campagne
                        <strong>{{ $affectation->campagne->libelle ?? '—' }}</strong>. Le référentiel des maisons, lui, est permanent
                        et reste valable pour toutes les campagnes futures.
                    </p>
                </div>
            </div>
        </div>
    @endif

</div>

@endsection