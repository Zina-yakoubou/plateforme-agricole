@extends('layouts.app')

@section('page-title', 'Maisons du village')

@section(
    'page-subtitle',
    'Marquez votre passage sur chaque maison vérifiée.'
)

@section('content')

@php
    $campagneActive = $campagneActive ?? null;
    $visites        = $visites ?? collect();
    $menages        = $menages ?? collect();

    $statutFiltre   = request('statut', 'tous');
    $searchQuery    = request('search', '');

    $couleurs = [
        'conforme'   => 'bg-emerald-100 text-emerald-800 border-emerald-200',
        'a_corriger' => 'bg-amber-100 text-amber-800 border-amber-200',
        'probleme'   => 'bg-rose-100 text-rose-800 border-rose-200',
    ];

    $libellesStatut = [
        'conforme'   => 'Conforme',
        'a_corriger' => 'À corriger',
        'probleme'   => 'Problème',
    ];
@endphp

<div class="space-y-6">

    {{-- ===========================================================
        EN-TÊTE DU VILLAGE & BOUTON RETOUR
    ============================================================ --}}
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">

        <div>
            <h2 class="text-2xl font-bold text-slate-800">
                Village : {{ $village->nom }}
            </h2>

            <p class="text-sm text-slate-500">
                Préfecture de {{ $village->canton?->commune?->prefecture?->nom ?? 'Mô' }} · Canton de {{ $village->canton?->nom ?? '-' }}
            </p>
        </div>

        <a
            href="{{ route('superviseur.suivi.index') }}"
            class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition shadow-sm"
        >
            ← Retour au suivi
        </a>

    </div>

    @if(! $campagneActive)
        <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 shadow-sm">
            La campagne de recensement est actuellement clôturée ou inactive : la consultation des visites est disponible mais aucun marquage ne peut être enregistré.
        </div>
    @endif

    {{-- ===========================================================
        SYNTHÈSE DU VILLAGE (KPIs)
    ============================================================ --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total maisons recensées</p>
            <h3 class="mt-1 text-2xl font-bold text-slate-800">
                {{ method_exists($maisons, 'total') ? $maisons->total() : $maisons->count() }}
            </h3>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Maisons contrôlées par vous</p>
            <h3 class="mt-1 text-2xl font-bold text-emerald-700">
                {{ $statsVisites['visitees'] ?? $visites->count() }}
            </h3>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Anomalies / À corriger</p>
            <h3 class="mt-1 text-2xl font-bold text-amber-600">
                {{ $statsVisites['a_corriger_ou_probleme'] ?? 0 }}
            </h3>
        </div>
    </div>

    {{-- ===========================================================
        BARRE DE RECHERCHE ET DE FILTRES
    ============================================================ --}}
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('superviseur.suivi.maisons', $village->idVillage) }}" class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            
            {{-- Recherche rapide --}}
            <div class="relative flex-1">
                <input
                    type="text"
                    name="search"
                    value="{{ $searchQuery }}"
                    placeholder="Chercher un n° de maison ou nom d'un chef..."
                    class="w-full rounded-lg border border-slate-300 pl-9 pr-4 py-2 text-sm focus:border-emerald-500 focus:outline-none"
                >
                <svg class="absolute left-3 top-2.5 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            {{-- Filtres par statut --}}
            <div class="flex flex-wrap items-center gap-1.5">
                <a
                    href="{{ route('superviseur.suivi.maisons', ['villageId' => $village->idVillage, 'search' => $searchQuery, 'statut' => 'tous']) }}"
                    class="rounded-lg px-3 py-1.5 text-xs font-medium transition {{ $statutFiltre === 'tous' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                >
                    Toutes
                </a>

                <a
                    href="{{ route('superviseur.suivi.maisons', ['villageId' => $village->idVillage, 'search' => $searchQuery, 'statut' => 'non_visitees']) }}"
                    class="rounded-lg px-3 py-1.5 text-xs font-medium transition {{ $statutFiltre === 'non_visitees' ? 'bg-slate-700 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                >
                    Non visitées
                </a>

                <a
                    href="{{ route('superviseur.suivi.maisons', ['villageId' => $village->idVillage, 'search' => $searchQuery, 'statut' => 'conforme']) }}"
                    class="rounded-lg px-3 py-1.5 text-xs font-medium transition {{ $statutFiltre === 'conforme' ? 'bg-emerald-700 text-white' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}"
                >
                    Conformes
                </a>

                <a
                    href="{{ route('superviseur.suivi.maisons', ['villageId' => $village->idVillage, 'search' => $searchQuery, 'statut' => 'a_corriger']) }}"
                    class="rounded-lg px-3 py-1.5 text-xs font-medium transition {{ $statutFiltre === 'a_corriger' ? 'bg-amber-600 text-white' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}"
                >
                    À corriger
                </a>

                <a
                    href="{{ route('superviseur.suivi.maisons', ['villageId' => $village->idVillage, 'search' => $searchQuery, 'statut' => 'probleme']) }}"
                    class="rounded-lg px-3 py-1.5 text-xs font-medium transition {{ $statutFiltre === 'probleme' ? 'bg-rose-600 text-white' : 'bg-rose-50 text-rose-700 hover:bg-rose-100' }}"
                >
                    Problème
                </a>
            </div>

        </form>
    </div>

    {{-- ===========================================================
        LISTE DES MAISONS
    ============================================================ --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">

        @if($maisons->count())

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">

                        <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-500">

                            <th class="px-6 py-3">N° maison</th>
                            <th class="px-6 py-3">Chef(s) de ménage</th>
                            <th class="px-6 py-3">Adresse / Repère</th>
                            <th class="px-6 py-3">Dernière visite</th>
                            <th class="px-6 py-3 text-center">Action</th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">

                        @foreach($maisons as $maison)

                            @php
                                $visite = $visites->get($maison->idMaison);
                                $chefs  = $menages->get($maison->idMaison, collect());

                                $dateVisiteFormatee = null;
                                if ($visite && ! empty($visite->date_visite)) {
                                    $dateVisiteFormatee = $visite->date_visite instanceof \Carbon\CarbonInterface
                                        ? $visite->date_visite->format('d/m/Y H:i')
                                        : \Carbon\Carbon::parse($visite->date_visite)->format('d/m/Y H:i');
                                }
                            @endphp

                            <tr class="hover:bg-slate-50 transition">

                                {{-- N° maison --}}
                                <td class="px-6 py-4 font-semibold text-slate-800 whitespace-nowrap">
                                    {{ $maison->numeroMaison }}
                                </td>

                                {{-- Chef de ménage --}}
                                <td class="px-6 py-4 text-sm text-slate-700">

                                    @forelse($chefs as $chef)
                                        <div class="font-medium text-slate-800">
                                            {{ trim(($chef->prenomChef ?? '') . ' ' . ($chef->nomChef ?? '')) ?: 'Chef sans nom' }}
                                        </div>
                                    @empty
                                        <span class="text-xs text-slate-400">Non recensée</span>
                                    @endforelse

                                </td>

                                {{-- Adresse --}}
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    <div>{{ $maison->adresse ?? '-' }}</div>

                                    @if(! empty($maison->repere))
                                        <div class="text-xs text-slate-500">
                                            Repère : {{ $maison->repere }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Dernière visite --}}
                                <td class="px-6 py-4 text-sm text-slate-700">

                                    @if($visite)

                                        <span class="inline-block rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $couleurs[$visite->statut] ?? 'bg-slate-200 text-slate-600 border-slate-300' }}">
                                            {{ $libellesStatut[$visite->statut] ?? str_replace('_', ' ', (string) $visite->statut) }}
                                        </span>

                                        @if($dateVisiteFormatee)
                                            <div class="mt-1 text-xs text-slate-500">
                                                Le {{ $dateVisiteFormatee }}
                                            </div>
                                        @endif

                                        @if(! empty($visite->observation))
                                            <div class="mt-1 text-xs italic text-slate-600 bg-slate-50 p-1.5 rounded border border-slate-100">
                                                « {{ $visite->observation }} »
                                            </div>
                                        @endif

                                    @else

                                        <span class="text-xs text-slate-400">Jamais visitée</span>

                                    @endif

                                </td>

                                {{-- Action --}}
                                <td class="px-6 py-4 text-center whitespace-nowrap">

                                    @if($campagneActive)

                                        <button
                                            type="button"
                                            onclick='ouvrirVisite(
                                                {{ (int) $maison->idMaison }},
                                                @js($maison->numeroMaison),
                                                @js($visite->statut ?? "conforme"),
                                                @js($visite->observation ?? "")
                                            )'
                                            class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700 transition shadow-sm"
                                        >
                                            {{ $visite ? 'Modifier' : 'Marquer' }}
                                        </button>

                                    @else

                                        <span class="text-xs text-slate-400">Clôturée</span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            {{-- ===========================================================
                PAGINATION DES MAISONS (Si > 15 maisons)
            ============================================================ --}}
            @if(method_exists($maisons, 'hasPages') && $maisons->hasPages())
                <div class="border-t border-slate-200 px-6 py-4">
                    {{ $maisons->appends(request()->query())->links() }}
                </div>
            @endif

        @else

            <div class="px-6 py-12 text-center">
                <p class="text-sm font-medium text-slate-600">
                    Aucune maison ne correspond à votre filtre.
                </p>
                <p class="mt-1 text-xs text-slate-400">
                    Essayez de réinitialiser la recherche ou de changer le filtre de statut.
                </p>
            </div>

        @endif

    </div>

</div>

{{-- ===========================================================
    MESSAGES FLASH & ERREURS
============================================================ --}}
@if(session('success'))
    <div class="fixed right-5 top-5 z-50 rounded-lg border border-emerald-200 bg-emerald-100 px-4 py-3 text-sm font-semibold text-emerald-800 shadow-lg">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="fixed right-5 top-5 z-50 rounded-lg border border-rose-200 bg-rose-100 px-4 py-3 text-sm font-semibold text-rose-800 shadow-lg">
        {{ $errors->first() }}
    </div>
@endif

{{-- ===========================================================
    MODALE : MARQUER / ENREGISTRER UNE VISITE
============================================================ --}}
<div
    id="modalVisite"
    onclick="if (event.target === this) fermerVisite()"
    class="fixed inset-0 z-40 hidden items-center justify-center bg-black/50 p-4 backdrop-blur-sm"
>

    <form
        method="POST"
        action="{{ route('superviseur.suivi.visite') }}"
        class="w-full max-w-md rounded-xl bg-white p-6 shadow-2xl"
    >
        @csrf

        <h3 class="mb-4 text-lg font-bold text-slate-800">
            Visite de la Maison n° <span id="visiteMaisonNumero" class="text-emerald-700"></span>
        </h3>

        <input type="hidden" name="village_id" value="{{ $village->idVillage }}">
        <input type="hidden" name="maison_id" id="visiteMaison">

        <div class="mb-4">
            <label for="visiteStatut" class="block text-sm font-medium text-slate-700 mb-1">Constat de supervision</label>
            <select
                id="visiteStatut"
                name="statut"
                required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none"
            >
                <option value="conforme">Conforme</option>
                <option value="a_corriger">À corriger</option>
                <option value="probleme">Problème majeur</option>
            </select>
        </div>

        <div class="mb-5">
            <label for="visiteObservation" class="block text-sm font-medium text-slate-700 mb-1">Observation / Remarque (facultatif)</label>
            <textarea
                id="visiteObservation"
                name="observation"
                rows="3"
                placeholder="Ex: Numéro de téléphone du chef de ménage incomplet..."
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none"
            ></textarea>
        </div>

        <div class="flex justify-end gap-2">
            <button
                type="button"
                onclick="fermerVisite()"
                class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition"
            >
                Annuler
            </button>

            <button
                type="submit"
                class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700 transition shadow-sm"
            >
                Enregistrer la visite
            </button>
        </div>

    </form>

</div>

<script>
    function ouvrirVisite(maisonId, numero, statut = 'conforme', observation = '') {
        document.getElementById('visiteMaison').value = maisonId;
        document.getElementById('visiteMaisonNumero').textContent = numero;
        document.getElementById('visiteStatut').value = statut || 'conforme';
        document.getElementById('visiteObservation').value = observation || '';

        const modal = document.getElementById('modalVisite');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function fermerVisite() {
        const modal = document.getElementById('modalVisite');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            fermerVisite();
        }
    });
</script>

@endsection