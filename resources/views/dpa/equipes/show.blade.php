@extends('layouts.app')

@section('content')

@php
    $estActive = $equipe->statut === 'ACTIVE';

    // Affectations de l'équipe, de la plus récente à la plus ancienne
    $affectations = $equipe->affectations()
        ->with(['campagne', 'village'])
        ->latest('idAffectation')
        ->get();
@endphp

<div class="space-y-6">

    {{-- EN-TÊTE --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <div class="mb-2 flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('dpa.equipes.index') }}" class="transition hover:text-[#006a4f]">
                    Équipes
                </a>
                <span class="text-gray-400">›</span>
                <span class="text-gray-700">{{ $equipe->reference }}</span>
            </div>

            <div class="flex flex-wrap items-center gap-3">

                <h1 class="text-2xl font-semibold tracking-tight text-[#212529]">
                    {{ $equipe->nom }}
                </h1>

                @if($estActive)
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                        Active
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                        <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                        Inactive
                    </span>
                @endif

            </div>

            <p class="mt-1 text-sm text-gray-500">
                Composition de l'équipe et travaux qui lui sont confiés.
            </p>

        </div>

        <div class="flex items-center gap-2">

            <a href="{{ route('dpa.equipes.index') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-[#e5e7eb] bg-white px-4 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
                Retour
            </a>

            @if($estActive)
                <a href="{{ route('dpa.equipes.edit', $equipe) }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-[#006a4f] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#156c52]">
                    Modifier
                </a>
            @endif

        </div>

    </div>


    {{-- MESSAGES --}}
    @if(session('success'))
        <div class="rounded-lg border border-[#b7dfd2] bg-[#e5f2ee] px-4 py-3 text-sm text-[#006a4f]">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif


    {{-- IDENTITÉ DE L'ÉQUIPE + SUPERVISEUR --}}
    <div class="overflow-hidden rounded-lg border border-[#e5e7eb] bg-white shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

        <div class="grid grid-cols-1 divide-y divide-[#e5e7eb] lg:grid-cols-2 lg:divide-x lg:divide-y-0">

            {{-- Équipe --}}
            <div class="px-6 py-6">

                <h2 class="text-base font-semibold text-[#212529]">Équipe</h2>

                <dl class="mt-4 grid grid-cols-2 gap-x-6 gap-y-5">

                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Référence</dt>
                        <dd class="mt-1 font-mono text-sm font-semibold text-[#006a4f]">{{ $equipe->reference }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Nom</dt>
                        <dd class="mt-1 text-sm font-semibold text-[#212529]">{{ $equipe->nom }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Créée le</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $equipe->created_at?->format('d/m/Y à H:i') ?? '-' }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Dernière modification</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $equipe->updated_at?->format('d/m/Y à H:i') ?? '-' }}</dd>
                    </div>

                </dl>

            </div>

            {{-- Superviseur --}}
            <div class="px-6 py-6">

                <h2 class="text-base font-semibold text-[#212529]">Superviseur</h2>

                @if($equipe->superviseur)

                    <div class="mt-4 flex items-center gap-4">

                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-[#e5f2ee] text-lg font-semibold text-[#006a4f]">
                            {{ strtoupper(substr($equipe->superviseur->name, 0, 1)) }}
                        </div>

                        <div class="min-w-0">

                            <p class="font-semibold text-[#212529]">
                                {{ $equipe->superviseur->name }}
                            </p>

                            @if($equipe->superviseur->telephone)
                                <p class="mt-1 text-sm text-gray-500">{{ $equipe->superviseur->telephone }}</p>
                            @endif

                            @if($equipe->superviseur->email)
                                <p class="mt-0.5 truncate text-xs text-gray-400">{{ $equipe->superviseur->email }}</p>
                            @endif

                          
                        </div>

                    </div>

                @else

                    <p class="mt-4 text-sm text-gray-500">Aucun superviseur affecté.</p>

                @endif

            </div>

        </div>

    </div>


    {{-- AGENTS RECENSEURS --}}
    <div class="overflow-hidden rounded-lg border border-[#e5e7eb] bg-white shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

        <div class="flex flex-col gap-3 border-b border-[#e5e7eb] px-6 py-5 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h2 class="text-base font-semibold text-[#212529]">Agents recenseurs</h2>
                <p class="mt-1 text-xs text-gray-500">Membres de cette équipe de terrain</p>
            </div>

            <span class="rounded-full bg-[#f8faf9] px-3 py-1.5 text-sm text-gray-500">
                <span class="font-semibold text-[#006a4f]">{{ $equipe->membres->count() }}</span>
                membre{{ $equipe->membres->count() > 1 ? 's' : '' }}
            </span>

        </div>

        @forelse($equipe->membres as $agent)

            @if($loop->first)
                <div class="divide-y divide-[#e5e7eb]">
            @endif

            <div class="flex flex-col gap-3 px-6 py-4 transition hover:bg-[#f8faf9] sm:flex-row sm:items-center sm:justify-between">

                <div class="flex items-center gap-4">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#e5f2ee] text-sm font-semibold text-[#006a4f]">
                        {{ strtoupper(substr($agent->name, 0, 1)) }}
                    </div>

                    <div>
                        <p class="font-semibold text-[#212529]">{{ $agent->name }}</p>
                        <p class="mt-0.5 text-xs text-gray-500">{{ $agent->telephone ?? '—' }}</p>
                    </div>

                </div>

                {{-- <div class="flex items-center gap-4">

                    @if($agent->statut)
                        <span class="rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-semibold text-green-700">Actif</span>
                    @else
                        <span class="rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-semibold text-red-700">Inactif</span>
                    @endif

                    <a href="{{ route('dpa.agents.show', $agent) }}"
                       class="text-xs font-semibold text-[#006a4f] hover:underline">
                        Voir la fiche
                    </a>

                </div> --}}

            </div>

            @if($loop->last)
                </div>
            @endif

        @empty

            <div class="px-6 py-12 text-center">
                <p class="font-semibold text-[#212529]">Aucun agent recenseur</p>
                <p class="mt-1 text-sm text-gray-500">Aucun agent n'est actuellement membre de cette équipe.</p>

                @if($estActive)
                    <a href="{{ route('dpa.equipes.edit', $equipe) }}"
                       class="mt-3 inline-block text-sm font-semibold text-[#006a4f] hover:underline">
                        Modifier l'équipe
                    </a>
                @endif
            </div>

        @endforelse

    </div>


    {{-- AFFECTATIONS --}}
    {{-- <div class="overflow-hidden rounded-lg border border-[#e5e7eb] bg-white shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

        <div class="flex flex-col gap-3 border-b border-[#e5e7eb] px-6 py-5 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h2 class="text-base font-semibold text-[#212529]">Affectations</h2>
                <p class="mt-1 text-xs text-gray-500">Campagnes et villages confiés à cette équipe</p>
            </div>

            @if($estActive)
                <a href="{{ route('dpa.equipes.affectations.create', $equipe) }}"
                   class="inline-flex items-center rounded-lg border border-[#e5e7eb] bg-white px-4 py-2 text-sm font-semibold text-[#006a4f] transition hover:border-[#006a4f] hover:bg-[#e5f2ee]">
                    {{ $affectations->isNotEmpty() ? 'Affecter / Reconduire' : 'Affecter l\'équipe' }}
                </a>
            @endif

        </div>

        @if($affectations->isNotEmpty())

            <div class="overflow-x-auto">

                <table class="w-full min-w-[800px] text-sm">

                    <thead class="bg-[#f8faf9]">
                        <tr class="border-b border-[#e5e7eb]">
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Campagne</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Village</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Période</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">Statut</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#e5e7eb]">

                        @foreach($affectations as $affectation)

                            <tr class="transition hover:bg-[#f8faf9]">

                                <td class="px-6 py-4">
                                    <div class="font-semibold text-[#212529]">
                                        {{ $affectation->campagne?->libelle ?? '—' }}
                                    </div>
                                    <div class="mt-0.5 font-mono text-xs text-gray-400">
                                        {{ $affectation->reference ?? '' }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-[#434343]">
                                    {{ $affectation->village?->nom ?? '—' }}
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ $affectation->dateDebut?->format('d/m/Y') ?? '-' }}
                                    au
                                    {{ $affectation->dateFin?->format('d/m/Y') ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-center">

                                    @if($affectation->statut === 'active')
                                        <span class="inline-flex rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">En cours</span>
                                    @elseif($affectation->statut === 'terminee')
                                        <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">Terminée</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">
                                            {{ ucfirst($affectation->statut ?? 'Annulée') }}
                                        </span>
                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="px-6 py-12 text-center">
                <p class="font-semibold text-[#212529]">Aucune affectation</p>
                <p class="mt-1 text-sm text-gray-500">Cette équipe n'a encore été affectée à aucune campagne.</p>
            </div>

        @endif

    </div> --}}

</div>

@endsection