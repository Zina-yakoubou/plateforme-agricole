@extends('layouts.app')

@section('content')

@php
    $village = $maison->village;
@endphp

<div class="space-y-6">

    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}
    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

        <div>
            <div class="flex items-center gap-2">

                <h1 class="text-2xl font-semibold tracking-tight text-[#212529]">
                    Recensements - Maison n° {{ $maison->numeroMaison ?? '-' }}
                </h1>

                <span class="rounded-full bg-[#e5f2ee] px-3 py-1 text-xs font-semibold text-[#006a4f]">
                    SIRA-Mô
                </span>

            </div>

            <p class="mt-1 text-sm text-[#6b7280]">
                Village de {{ $village->nom ?? '—' }} · {{ $maison->adresse ?? 'Aucune adresse renseignée' }}
            </p>
        </div>

        {{-- =====================================================
             BOUTONS D'ACTION (NOUVEAU RECENSEMENT / RETOUR)
        ====================================================== --}}
        <div class="flex items-center gap-3">

            @if(isset($affectation))
                <form action="{{ route('agent.recensements.commencer', ['affectation' => $affectation->idAffectation, 'maison' => $maison->idMaison]) }}" method="POST" class="inline">
                    @csrf
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-[#006a4f] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#156c52] focus:outline-none focus:ring-2 focus:ring-[#006a4f]/30"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Nouveau recensement
                    </button>
                </form>
            @endif

            <a
                href="{{ route('maisons.show', $maison) }}"
                class="inline-flex items-center gap-2 rounded-lg border border-[#e5e7eb] bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50"
            >
                <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour à la maison
            </a>

        </div>

    </div>


    {{-- =========================================================
         MESSAGES DE NOTIFICATION
    ========================================================== --}}
    @if(session('error'))
        <div class="flex items-start gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if(session('info'))
        <div class="flex items-start gap-3 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">
            <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 21a9 9 0 100-18"/>
            </svg>
            <span>{{ session('info') }}</span>
        </div>
    @endif


    {{-- =========================================================
         FILTRES / RECHERCHE
    ========================================================== --}}
    <div class="rounded-lg border border-[#e5e7eb] bg-white p-4 shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

        <form method="GET" action="{{ route('agent.maisons.recensements.index', $maison) }}">

            <div class="flex flex-col gap-3 lg:flex-row">

                {{-- RECHERCHE PAR MOT-CLÉ --}}
                <div class="relative flex-1">
                    <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"/>
                    </svg>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Rechercher par campagne, agent..."
                        class="w-full rounded-lg border border-[#e5e7eb] bg-white py-3 pl-11 pr-4 text-sm text-[#212529] placeholder:text-gray-400 focus:border-[#006a4f] focus:outline-none focus:ring-2 focus:ring-[#006a4f]/10"
                    >
                </div>

                {{-- FILTRE STATUT --}}
                <select
                    name="statut"
                    class="rounded-lg border border-[#e5e7eb] bg-white px-4 py-3 text-sm text-[#212529] focus:border-[#006a4f] focus:outline-none focus:ring-2 focus:ring-[#006a4f]/10"
                >
                    <option value="">Tous les statuts</option>
                    <option value="en_cours" @selected(request('statut') === 'en_cours')>En cours</option>
                    <option value="termine" @selected(request('statut') === 'termine')>Terminé</option>
                    <option value="cloture" @selected(request('statut') === 'cloture')>Clôturé</option>
                </select>

                {{-- BOUTON RECHERCHER --}}
                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#006a4f] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#156c52]"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"/>
                    </svg>
                    Rechercher
                </button>

                {{-- BOUTON RÉINITIALISER --}}
                @if(request()->filled('search') || request()->filled('statut'))
                    <a
                        href="{{ route('agent.maisons.recensements.index', $maison) }}"
                        class="inline-flex items-center justify-center rounded-lg border border-[#e5e7eb] bg-white px-4 py-3 text-sm font-semibold text-gray-600 transition hover:bg-gray-50"
                    >
                        Réinitialiser
                    </a>
                @endif

            </div>

        </form>

    </div>


    {{-- =========================================================
         LISTE DES RECENSEMENTS DE LA MAISON
    ========================================================== --}}
    <div class="overflow-hidden rounded-lg border border-[#e5e7eb] bg-white shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

        {{-- EN-TÊTE DU TABLEAU --}}
        <div class="flex flex-col gap-3 border-b border-[#e5e7eb] px-6 py-5 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h2 class="text-base font-semibold text-[#212529]">
                    Historique des recensements
                </h2>
                <p class="mt-1 text-xs text-gray-500">
                    Recensements effectués dans cette maison et gestion de leurs ménages
                </p>
            </div>

            <div class="flex items-center gap-3">
                <span class="rounded-full bg-[#f8faf9] px-3 py-1.5 text-sm text-gray-500">
                    <span class="font-semibold text-[#006a4f]">
                        {{ method_exists($recensements, 'total') ? $recensements->total() : $recensements->count() }}
                    </span>
                    passage(s)
                </span>
            </div>

        </div>

        {{-- TABLEAU --}}
        <div class="overflow-x-auto">

            <table class="w-full min-w-[1000px] text-sm">

                <thead class="bg-[#f8faf9]">
                    <tr class="border-b border-[#e5e7eb]">
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            N°
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Campagne
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Agent recenseur
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Ménages
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Statut
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Date de création
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#e5e7eb]">

                    @forelse($recensements as $recensement)

                        <tr class="group transition hover:bg-[#f8faf9]">

                            {{-- N° --}}
                            <td class="px-6 py-4 text-sm text-gray-400">
                                {{ method_exists($recensements, 'currentPage') ? ($loop->iteration + (($recensements->currentPage() - 1) * $recensements->perPage())) : $loop->iteration }}
                            </td>

                            {{-- CAMPAGNE --}}
                            <td class="px-6 py-4">
                                <div>
                                    <span class="font-semibold text-[#212529]">
                                        {{ $recensement->campagne->libelle ?? 'Campagne n°'.$recensement->campagne_id }}
                                    </span>
                                    <div class="mt-0.5 font-mono text-xs text-[#006a4f]">
                                        {{ $recensement->campagne->codeCampagne ?? '-' }}
                                    </div>
                                </div>
                            </td>

                            {{-- AGENT --}}
                            <td class="px-6 py-4 text-[#434343]">
                                <span class="font-medium text-[#212529]">
                                    {{ $recensement->agent->name ?? $recensement->agent->nom ?? '—' }}
                                </span>
                            </td>

                            {{-- NOMBRE DE MÉNAGES --}}
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#e5f2ee] px-3 py-1 text-xs font-semibold text-[#006a4f]">
                                    {{ $recensement->menages_count ?? $recensement->menages->count() }} ménage(s)
                                </span>
                            </td>

                            {{-- STATUT --}}
                            <td class="px-6 py-4 text-center">
                                @switch($recensement->statut)

                                    @case('en_cours')
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-yellow-50 px-3 py-1 text-xs font-semibold text-yellow-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-yellow-500"></span>
                                            En cours
                                        </span>
                                        @break

                                    @case('termine')
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                            Terminé
                                        </span>
                                        @break

                                    @case('cloture')
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                            <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                            Clôturé
                                        </span>
                                        @break

                                    @default
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                            {{ ucfirst($recensement->statut ?? 'Actif') }}
                                        </span>

                                @endswitch
                            </td>

                            {{-- DATE --}}
                            <td class="px-6 py-4 text-[#434343]">
                                <div class="font-medium">
                                    {{ $recensement->created_at?->format('d/m/Y') ?? '-' }}
                                </div>
                                <div class="mt-0.5 text-xs text-gray-400">
                                    {{ $recensement->created_at?->format('H:i') }}
                                </div>
                            </td>

                            {{-- ACTIONS --}}
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">

                                    {{-- GÉRER LES MÉNAGES --}}
                                    <a
                                        href="{{ route('agent.recensements.menages.index', $recensement) }}"
                                        title="Gérer les ménages"
                                        class="inline-flex items-center gap-2 rounded-lg bg-[#006a4f] px-3.5 py-2 text-xs font-semibold text-white transition hover:bg-[#156c52]"
                                    >
                                        <span>Gérer les ménages</span>
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                        </svg>
                                    </a>

                                </div>
                            </td>

                        </tr>

                    @empty

                        {{-- AUCUN RECENSEMENT --}}
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-[#e5e7eb] text-[#006a4f]">
                                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <p class="font-semibold text-[#212529]">
                                        Aucun recensement trouvé
                                    </p>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Aucun recensement ne correspond aux critères sélectionnés pour cette maison.
                                    </p>

                                    @if(request()->filled('search') || request()->filled('statut'))
                                        <a
                                            href="{{ route('agent.maisons.recensements.index', $maison) }}"
                                            class="mt-4 text-sm font-semibold text-[#006a4f] hover:underline"
                                        >
                                            Réinitialiser les filtres
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- PAGINATION --}}
        @if(method_exists($recensements, 'hasPages') && $recensements->hasPages())
            <div class="border-t border-[#e5e7eb] px-6 py-4">
                {{ $recensements->withQueryString()->links() }}
            </div>
        @endif

    </div>

</div>

@endsection