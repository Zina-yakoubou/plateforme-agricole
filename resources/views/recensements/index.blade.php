
@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- EN-TÊTE --}}
    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-semibold tracking-tight text-[#212529]">
                    Gestion des recensements
                </h1>
                <span class="rounded-full bg-[#e5f2ee] px-3 py-1 text-xs font-semibold text-[#006a4f]">
                    SIRA-Mô
                </span>
            </div>

            <p class="mt-1 text-sm text-[#6b7280]">
                Consultez les recensements et gérez les ménages de chaque maison.
            </p>
        </div>
    </div>

    {{-- MESSAGES --}}
    @if(session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    

    {{-- LISTE --}}
    <div class="overflow-hidden rounded-lg border border-[#e5e7eb] bg-white shadow-sm">

        <div class="flex flex-col gap-3 border-b border-[#e5e7eb] px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-base font-semibold text-[#212529]">
                    Liste des recensements
                </h2>
                <p class="mt-1 text-xs text-gray-500">
                    Chaque recensement possède ses propres ménages.
                </p>
            </div>

            {{-- <span class="rounded-full bg-[#f8faf9] px-3 py-1.5 text-sm text-gray-500">
                <span class="font-semibold text-[#006a4f]">{{ $recensements->total() }}</span>
                recensement(s)
            </span> --}}
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1100px] text-sm">
                {{-- RECHERCHE --}}
                <div class="rounded-lg border border-[#e5e7eb] bg-white p-4 shadow-sm">
                    <form method="GET" action="{{ route('recensements.index') }}">
                        <div class="flex flex-col gap-3 lg:flex-row">

                            <div class="relative flex-1">
                                <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="m21 21-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"/>
                                </svg>

                                <input type="text"
                                    name="search"
                                    value="{{ $search ?? request('search') }}"
                                    placeholder="Rechercher par campagne, maison, village ou agent..."
                                    class="w-full rounded-lg border border-[#e5e7eb] py-3 pl-11 pr-4 text-sm
                                            focus:border-[#006a4f] focus:outline-none focus:ring-2 focus:ring-[#006a4f]/10">
                            </div>

                            <select name="statut"
                                    class="rounded-lg border border-[#e5e7eb] bg-white px-4 py-3 text-sm
                                        focus:border-[#006a4f] focus:outline-none">
                                <option value="">Tous les statuts des campagnes</option>
                                <option value="planifiee" @selected(request('statut') === 'planifiee')>Planifiée</option>
                                <option value="active" @selected(request('statut') === 'active')>Active</option>
                                <option value="cloturee" @selected(request('statut') === 'cloturee')>Clôturée</option>
                                <option value="archivee" @selected(request('statut') === 'archivee')>Archivée</option>
                            </select>

                            <button type="submit"
                                    class="rounded-lg bg-[#006a4f] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#156c52]">
                                Rechercher
                            </button>

                            @if(request()->filled('search') || request()->filled('statut'))
                                <a href="{{ route('recensements.index') }}"
                                class="rounded-lg border border-[#e5e7eb] bg-white px-4 py-3 text-center text-sm font-semibold text-gray-600 hover:bg-gray-50">
                                    Réinitialiser
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <thead class="bg-[#f8faf9]">
                    <tr class="border-b border-[#e5e7eb]">
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">N°</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Campagne</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Maison / Village</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Agent recenseur</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">Ménages</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">Campagne</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Date</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#e5e7eb]">

                    @forelse($recensements as $rec)

                        @php
                            $campagneCloturee = ($rec->campagne->statut ?? null) === 'cloturee';

                            $nomAgent = $rec->agent
                                ? ($rec->agent->name
                                    ?? (trim(($rec->agent->prenom ?? '') . ' ' . ($rec->agent->nom ?? '')) ?: ($rec->agent->telephone ?? 'Agent')))
                                : '—';
                        @endphp

                        <tr class="transition hover:bg-[#f8faf9]">

                            <td class="px-6 py-4 text-gray-400">
                                {{ $loop->iteration + (($recensements->currentPage() - 1) * $recensements->perPage()) }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="font-semibold text-[#212529]">
                                    {{ $rec->campagne->libelle ?? 'Campagne #' . $rec->campagne_id }}
                                </div>
                                <div class="mt-1 font-mono text-xs text-[#006a4f]">
                                    {{ $rec->campagne->codeCampagne ?? '' }}
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="font-medium text-[#212529]">
                                    Maison n° {{ $rec->maison->numeroMaison ?? '—' }}
                                </div>
                                <div class="mt-1 text-xs text-gray-400">
                                    {{ $rec->maison->village->nom ?? 'Village non renseigné' }}
                                </div>
                            </td>

                            <td class="px-6 py-4 text-[#434343]">
                                {{ $nomAgent }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="rounded-full bg-[#e5f2ee] px-3 py-1 font-semibold text-[#006a4f]">
                                    {{ $rec->menages_count ?? 0 }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($campagneCloturee)
                                    <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                        Clôturée
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                        {{ ucfirst($rec->campagne->statut ?? 'Ouverte') }}
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-[#434343]">
                                {{ $rec->created_at?->format('d/m/Y') ?? '—' }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end">

                                    <a href="{{ route('agent.recensements.menages.index', $rec) }}"
                                       title="{{ $campagneCloturee ? 'Consulter les ménages' : 'Gérer les ménages' }}"
                                       class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold transition
                                       {{ $campagneCloturee
                                            ? 'border border-gray-300 bg-white text-gray-600 hover:bg-gray-50'
                                            : 'bg-[#006a4f] text-white hover:bg-[#156c52]' }}">

                                        @if($campagneCloturee)
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 3.057 9.542 7c-1.274 3.943-5.065 7-9.542 7s-8.268-3.057-9.542-7z"/>
                                            </svg>
                                            Consulter
                                        @else
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M9 5l7 7-7 7"/>
                                            </svg>
                                            Gérer les ménages
                                        @endif
                                    </a>

                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <p class="font-semibold text-gray-700">Aucun recensement trouvé</p>
                                <p class="mt-1 text-sm text-gray-400">
                                    Les recensements apparaîtront ici lorsqu'ils seront enregistrés.
                                </p>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        @if($recensements->hasPages())
            <div class="border-t border-[#e5e7eb] px-6 py-4">
                {{ $recensements->withQueryString()->links() }}
            </div>
        @endif

    </div>
</div>

@endsection
