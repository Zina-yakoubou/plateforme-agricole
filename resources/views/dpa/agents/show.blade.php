@extends('layouts.app')

@section('content')

@php
    $nomRole = $agent->role?->nom;

    $classeRole = match($nomRole) {
        'Superviseur'     => 'bg-blue-50 text-blue-700',
        'Agent recenseur' => 'bg-[#e5f2ee] text-[#006a4f]',
        default           => 'bg-gray-100 text-gray-600',
    };

    $peutModifier = $agent->id !== auth()->id()
        && in_array($nomRole, ['Superviseur', 'Agent recenseur'], true);

    $prefecture = $agent->rattachementPrefectureActif?->prefecture?->nom;
@endphp

<div class="space-y-6">

    {{-- EN-TÊTE --}}
    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

        <div>
            <h1 class="text-xl font-semibold text-[#212529]">
                Profil du compte
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Informations du superviseur ou de l'agent recenseur.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">

            <a href="{{ route('dpa.agents.index') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-[#e5e7eb] bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-[#f8faf9]">
                Retour
            </a>

            @if($peutModifier)
                <a href="{{ route('dpa.agents.edit', $agent) }}"
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
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-[#ab1717]">
            {{ session('error') }}
        </div>
    @endif

    {{-- CARTE PROFIL --}}
    <div class="overflow-hidden rounded-lg border border-[#e5e7eb] bg-white shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

        {{-- Identité --}}
        <div class="flex flex-col gap-4 border-b border-[#e5e7eb] px-6 py-6 sm:flex-row sm:items-center sm:justify-between">

            <div class="flex items-center gap-4">

                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-[#e5f2ee] text-2xl font-semibold text-[#006a4f]">
                    {{ strtoupper(substr($agent->name, 0, 1)) }}
                </div>

                <div class="min-w-0">

                    <div class="flex items-center gap-2">
                        <h2 class="truncate text-lg font-semibold text-[#212529]">
                            {{ $agent->name }}
                        </h2>

                        @if($agent->id === auth()->id())
                            <span class="inline-flex rounded-full bg-[#006a4f] px-2 py-0.5 text-[10px] font-semibold text-white">
                                Vous
                            </span>
                        @endif
                    </div>

                    <div class="mt-1 flex flex-wrap items-center gap-2">

                        @if($nomRole)
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $classeRole }}">
                                {{ $nomRole }}
                            </span>
                        @endif

                        @if($agent->statut)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                Actif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                Inactif
                            </span>
                        @endif

                    </div>
                </div>
            </div>

            @if($peutModifier)
                <form method="POST" action="{{ route('dpa.agents.toggle-status', $agent) }}">
                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            class="inline-flex items-center rounded-lg border border-[#e5e7eb] bg-white px-4 py-2.5 text-sm font-semibold transition
                                   {{ $agent->statut
                                       ? 'text-red-600 hover:border-red-200 hover:bg-red-50'
                                       : 'text-green-600 hover:border-green-200 hover:bg-green-50' }}">
                        {{ $agent->statut ? 'Désactiver le compte' : 'Activer le compte' }}
                    </button>
                </form>
            @endif

        </div>

        {{-- Détails --}}
        <dl class="grid grid-cols-1 gap-x-8 gap-y-6 px-6 py-6 sm:grid-cols-2">

            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Nom complet</dt>
                <dd class="mt-1 text-sm text-[#212529]">{{ $agent->name }}</dd>
            </div>

            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Téléphone</dt>
                <dd class="mt-1 text-sm text-[#212529]">{{ $agent->telephone ?? '—' }}</dd>
            </div>

            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Adresse e-mail</dt>
                <dd class="mt-1 break-all text-sm text-[#212529]">{{ $agent->email ?? '—' }}</dd>
            </div>

            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Rôle</dt>
                <dd class="mt-1 text-sm text-[#212529]">{{ $nomRole ?? '—' }}</dd>
            </div>

            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Préfecture de rattachement</dt>
                <dd class="mt-1 text-sm text-[#212529]">{{ $prefecture ?? '—' }}</dd>
            </div>

            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Téléphone vérifié</dt>
                <dd class="mt-1 text-sm text-[#212529]">
                    {{ $agent->telephone_verified_at
                        ? $agent->telephone_verified_at->format('d/m/Y à H:i')
                        : 'Non vérifié' }}
                </dd>
            </div>

            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Compte créé le</dt>
                <dd class="mt-1 text-sm text-[#212529]">
                    {{ $agent->created_at?->format('d/m/Y à H:i') ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Dernière modification</dt>
                <dd class="mt-1 text-sm text-[#212529]">
                    {{ $agent->updated_at?->format('d/m/Y à H:i') ?? '—' }}
                </dd>
            </div>

        </dl>

    </div>

</div>

@endsection