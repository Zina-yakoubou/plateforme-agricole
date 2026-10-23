@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- EN-TÊTE --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>
            <div class="mb-2 flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('superviseur.zones.index') }}" class="hover:text-[#006a4f]">Mes zones</a>
                <span class="text-gray-400">›</span>
                <a href="{{ route('superviseur.zones.show', $affectation) }}" class="hover:text-[#006a4f]">
                    {{ $affectation->village?->nom }}
                </a>
                <span class="text-gray-400">›</span>
                <span class="text-gray-700">Vérification</span>
            </div>

            <h1 class="text-2xl font-semibold tracking-tight text-[#212529]">
                Vérification sur le terrain
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Village de {{ $affectation->village?->nom ?? '—' }}
                · {{ $affectation->campagne?->libelle ?? '—' }}
                · {{ $affectation->equipe?->nom ?? '—' }}
            </p>
        </div>

        <a href="{{ route('superviseur.zones.show', $affectation) }}"
           class="inline-flex items-center rounded-lg border border-[#e5e7eb] bg-white px-4 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
            Retour à la zone
        </a>

    </div>


    {{-- MESSAGES --}}
    @if(session('success'))
        <div class="rounded-lg border border-[#b7dfd2] bg-[#e5f2ee] px-4 py-3 text-sm text-[#006a4f]">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            @foreach($errors->all() as $erreur)
                <div>{{ $erreur }}</div>
            @endforeach
        </div>
    @endif


    {{-- BILAN DU VILLAGE --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

        <div class="rounded-lg border border-[#e5e7eb] bg-white px-5 py-4">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Maisons recensées</p>
            <p class="mt-1 text-2xl font-semibold text-[#212529]">{{ $total }}</p>
        </div>

        <div class="rounded-lg border border-[#e5e7eb] bg-white px-5 py-4">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Déjà vérifiées</p>
            <p class="mt-1 text-2xl font-semibold text-[#212529]">
                {{ $verifiees }} <span class="text-sm font-normal text-gray-400">sur {{ $total }}</span>
            </p>
        </div>

        <div class="rounded-lg border border-[#e5e7eb] bg-white px-5 py-4">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Conformes</p>
            <p class="mt-1 text-2xl font-semibold text-[#006a4f]">
                {{ $verifiees > 0 ? round(($conformes / $verifiees) * 100) : 0 }} %
            </p>
        </div>

    </div>


    {{-- TIRAGE AU HASARD --}}
    <div class="flex flex-wrap items-center gap-3">

        <a href="{{ route('superviseur.verifications.index', [$affectation, 'hasard' => 3]) }}"
           class="inline-flex items-center rounded-lg bg-[#006a4f] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#156c52]">
            Tirer 3 maisons au hasard
        </a>

        @if($modeHasard)
            <a href="{{ route('superviseur.verifications.index', $affectation) }}"
               class="inline-flex items-center rounded-lg border border-[#e5e7eb] bg-white px-4 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
                Voir toutes les maisons
            </a>

            <span class="text-sm text-gray-500">
                {{ $recensements->count() }} maison(s) tirée(s) parmi celles non vérifiées.
            </span>
        @endif

    </div>


    {{-- MAISONS --}}
    <div class="space-y-4">

        @forelse($recensements as $recensement)

            @php
                $verification = $verifications->get($recensement->getKey());
            @endphp

            <div class="rounded-lg border border-[#e5e7eb] bg-white shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

                {{-- Identité de la maison --}}
                <div class="flex flex-col gap-3 border-b border-[#e5e7eb] px-6 py-4 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <p class="text-base font-semibold text-[#212529]">
                            Maison {{ $recensement->maison?->numeroMaison ?? '—' }}
                        </p>

                        <p class="mt-0.5 text-xs text-gray-500">
                            @if($recensement->maison?->repere)
                                Repère : {{ $recensement->maison->repere }} ·
                            @endif
                            @if($recensement->maison?->adresse)
                                {{ $recensement->maison->adresse }} ·
                            @endif
                            Recensée par {{ $recensement->agent?->name ?? '—' }}
                        </p>
                    </div>

                    @if($verification)

                        @php
                            $classe = match($verification->resultat) {
                                'conforme'     => 'bg-green-50 text-green-700',
                                'non_conforme' => 'bg-red-50 text-red-700',
                                default        => 'bg-amber-50 text-amber-700',
                            };

                            $libelle = match($verification->resultat) {
                                'conforme'     => 'Conforme',
                                'non_conforme' => 'Non conforme',
                                default        => 'Introuvable',
                            };
                        @endphp

                        <div class="text-right">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $classe }}">
                                {{ $libelle }}
                            </span>
                            <p class="mt-1 text-xs text-gray-400">
                                Visitée le {{ $verification->dateVisite?->format('d/m/Y à H:i') }}
                            </p>
                        </div>

                    @else

                        <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                            À vérifier
                        </span>

                    @endif

                </div>

                {{-- Formulaire de la visite --}}
                <form method="POST"
                      action="{{ route('superviseur.verifications.store', $affectation) }}"
                      class="grid grid-cols-1 gap-3 px-6 py-4 lg:grid-cols-[220px_1fr_auto]">

                    @csrf

                    <input type="hidden" name="recensement_id" value="{{ $recensement->getKey() }}">

                    <select name="resultat"
                            class="rounded-lg border border-[#e5e7eb] bg-white px-4 py-2.5 text-sm text-[#212529] focus:border-[#006a4f] focus:outline-none focus:ring-2 focus:ring-[#006a4f]/10">

                        <option value="">Résultat de la visite…</option>

                        <option value="conforme" @selected($verification?->resultat === 'conforme')>
                            Conforme
                        </option>

                        <option value="non_conforme" @selected($verification?->resultat === 'non_conforme')>
                            Non conforme
                        </option>

                        <option value="introuvable" @selected($verification?->resultat === 'introuvable')>
                            Maison introuvable ou fermée
                        </option>

                    </select>

                    <input type="text"
                           name="remarque"
                           value="{{ $verification?->remarque }}"
                           placeholder="Remarque (obligatoire si non conforme ou introuvable)"
                           class="rounded-lg border border-[#e5e7eb] bg-white px-4 py-2.5 text-sm text-[#212529] placeholder:text-gray-400 focus:border-[#006a4f] focus:outline-none focus:ring-2 focus:ring-[#006a4f]/10">

                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-[#006a4f] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#156c52]">
                        {{ $verification ? 'Modifier' : 'Enregistrer' }}
                    </button>

                </form>

            </div>

        @empty

            <div class="rounded-lg border border-[#e5e7eb] bg-white px-6 py-14 text-center">
                <p class="font-semibold text-[#212529]">
                    {{ $modeHasard ? 'Toutes les maisons sont déjà vérifiées' : 'Aucune maison à vérifier' }}
                </p>
                <p class="mt-1 text-sm text-gray-500">
                    Seules les maisons dont le recensement est terminé apparaissent ici.
                </p>
            </div>

        @endforelse

    </div>

</div>

@endsection