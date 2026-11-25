@extends('layouts.app')

@section('content')

@php
    $exploitant = optional($parcelle->exploitation)->exploitant;
    $exploitantId = $exploitant ? $exploitant->idExploitant : null;
@endphp

<div class="max-w-5xl mx-auto space-y-6">

    {{-- =========================================================
         INFORMATIONS GÉNÉRALES DE LA PARCELLE
    ========================================================== --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">
                    Parcelle : {{ $parcelle->numeroParcelle }}
                </h1>
                <p class="text-sm text-slate-500 mt-1">
                    Détails complets de la parcelle, géolocalisation et caractéristiques techniques.
                </p>
            </div>

            <div>
                <span class="inline-flex items-center rounded-full bg-[#e5f2ee] px-3.5 py-1 text-sm font-semibold text-[#006a4f]">
                    {{ number_format($parcelle->superficie, 2, ',', ' ') }} ha
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            {{-- Statut de la parcelle --}}
            <div>
                <p class="text-sm text-slate-500">Statut de la parcelle</p>
                <p class="font-semibold text-slate-800 capitalize">
                    {{ str_replace('_', ' ', $parcelle->statutParcelle ?? 'Non renseigné') }}
                </p>
            </div>

            {{-- Type de sol --}}
            <div>
                <p class="text-sm text-slate-500">Type de sol</p>
                <p class="font-semibold text-slate-800 capitalize">
                    {{ str_replace('_', ' ', $parcelle->typeSol ?? 'Non renseigné') }}
                </p>
            </div>

            {{-- Mode de faire-valoir --}}
            <div>
                <p class="text-sm text-slate-500">Mode de faire-valoir</p>
                <p class="font-semibold text-slate-800 capitalize">
                    {{ str_replace('_', ' ', $parcelle->modeFaireValoir ?? 'Non renseigné') }}
                </p>
            </div>

            {{-- Mode d'irrigation --}}
            <div>
                <p class="text-sm text-slate-500">Mode d'irrigation</p>
                <p class="font-semibold text-slate-800 capitalize">
                    {{ str_replace('_', ' ', $parcelle->modeIrrigation ?? 'Non renseigné') }}
                </p>
            </div>

            {{-- Présence d'arbres --}}
            <div>
                <p class="text-sm text-slate-500">Présence d'arbres</p>
                <p class="font-semibold text-slate-800">
                    @if($parcelle->presenceArbres)
                        <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-0.5 text-xs font-medium text-green-700">Oui</span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-0.5 text-xs font-medium text-slate-600">Non</span>
                    @endif
                </p>
            </div>

        </div>

        @if($parcelle->observations)
            <div class="mt-6 pt-4 border-t border-slate-100">
                <p class="text-sm text-slate-500 mb-1">Observations</p>
                <p class="text-sm text-slate-700 bg-slate-50 p-3 rounded-lg">{{ $parcelle->observations }}</p>
            </div>
        @endif

        {{-- =====================================================
             ACTIONS
        ====================================================== --}}
        <div class="mt-8 flex flex-wrap gap-3">
            @if($exploitantId)
                <a href="{{ route('agent.parcelles.index', $exploitantId) }}"
                   class="inline-flex items-center rounded-lg border border-slate-300 px-5 py-2.5 text-slate-700 hover:bg-slate-100 transition">
                    Retour aux parcelles
                </a>
            @endif

            <a href="{{ route('agent.parcelles.edit', $parcelle) }}"
               class="rounded-lg bg-[#006a4f] px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#00563f] focus:outline-none focus:ring-2 focus:ring-[#006a4f] focus:ring-offset-2">
                Modifier
            </a>
        </div>

    </div>


    {{-- =========================================================
         GÉOLOCALISATION & POINTS GPS
    ========================================================== --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">

        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-lg font-bold text-slate-800">
                    Géolocalisation & Polygone
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Liste séquentielle des points GPS enregistrés pour le tracé de la parcelle.
                </p>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 rounded-lg text-slate-600">
                {{ $parcelle->pointsGPS->count() }} point(s)
            </span>
        </div>

        @if($parcelle->pointsGPS->count() > 0)
            <div class="overflow-x-auto rounded-lg border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-xs">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600">Ordre</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600">Latitude</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600">Longitude</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-600">Précision</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($parcelle->pointsGPS as $point)
                            <tr>
                                <td class="px-4 py-3 font-medium text-slate-800">#{{ $point->ordre }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $point->latitude }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $point->longitude }}</td>
                                <td class="px-4 py-3 text-right text-slate-600">{{ $point->precisionGPS ? $point->precisionGPS.' m' : '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="rounded-lg border border-dashed border-slate-200 bg-slate-50 p-6 text-center text-sm text-slate-500">
                Aucun point GPS enregistré pour cette parcelle.
            </div>
        @endif

    </div>


    {{-- =========================================================
         CULTURES PRATIQUÉES ET INTRANTS
    ========================================================== --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-6">

        <div>
            <h2 class="text-lg font-bold text-slate-800">
                Cultures pratiquées et Intrants
            </h2>
            <p class="text-sm text-slate-500 mt-1">
                Suivi des cultures installées sur cette parcelle et des apports d'intrants associés.
            </p>
        </div>

        @forelse($parcelle->cultures as $cultureParcelle)
            <div class="rounded-xl border border-slate-200 p-5 space-y-4 bg-slate-50/50">
                
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-slate-200 pb-4 gap-2">
                    <div>
                        <h3 class="text-base font-semibold text-slate-800 flex items-center gap-2">
                            <span>{{ optional($cultureParcelle->culture)->nomCulture ?? 'Culture non spécifiée' }}</span>
                            <span class="rounded-full bg-slate-200 px-2.5 py-0.5 text-xs font-medium text-slate-700 capitalize">
                                {{ $cultureParcelle->modeCulture }}
                            </span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">État : <span class="capitalize font-medium text-slate-700">{{ $cultureParcelle->etatCulture }}</span></p>
                    </div>

                    <div class="flex items-center gap-4 text-sm">
                        @if($cultureParcelle->superficieCultivee)
                            <div class="text-right">
                                <span class="text-xs text-slate-500 block">Superficie cultivée</span>
                                <span class="font-semibold text-slate-800">{{ number_format($cultureParcelle->superficieCultivee, 2, ',', ' ') }} ha</span>
                            </div>
                        @endif
                        <div>
                            @if($cultureParcelle->irriguee)
                                <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">Irriguée</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-slate-200 px-3 py-1 text-xs font-medium text-slate-600">Non irriguée</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs text-slate-600">
                    <div><span class="font-medium text-slate-700">Date de semis :</span> {{ $cultureParcelle->dateSemis ? \Carbon\Carbon::parse($cultureParcelle->dateSemis)->format('d/m/Y') : '-' }}</div>
                    <div><span class="font-medium text-slate-700">Récolte prévue :</span> {{ $cultureParcelle->dateRecoltePrevue ? \Carbon\Carbon::parse($cultureParcelle->dateRecoltePrevue)->format('d/m/Y') : '-' }}</div>
                    <div><span class="font-medium text-slate-700">Récolte effective :</span> {{ $cultureParcelle->dateRecolteEffective ? \Carbon\Carbon::parse($cultureParcelle->dateRecolteEffective)->format('d/m/Y') : '-' }}</div>
                </div>

                {{-- Tableau des intrants --}}
                @if($cultureParcelle->intrants->count() > 0)
                    <div class="mt-4 pt-4 border-t border-slate-200 space-y-3">
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-slate-500">Intrants associés</h4>
                        <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white">
                            <table class="min-w-full divide-y divide-slate-200 text-xs">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left font-semibold text-slate-600">Intrant</th>
                                        <th class="px-3 py-2 text-left font-semibold text-slate-600">Quantité</th>
                                        <th class="px-3 py-2 text-left font-semibold text-slate-600">Applications</th>
                                        <th class="px-3 py-2 text-left font-semibold text-slate-600">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    @foreach($cultureParcelle->intrants as $cultureIntrant)
                                        <tr>
                                            <td class="px-3 py-2 font-medium text-slate-800">{{ optional($cultureIntrant->intrant)->nom ?? '-' }}</td>
                                            <td class="px-3 py-2 text-slate-600">{{ $cultureIntrant->quantite }}</td>
                                            <td class="px-3 py-2 text-slate-600">{{ $cultureIntrant->nombreApplications }} fois</td>
                                            <td class="px-3 py-2 text-slate-600">{{ $cultureIntrant->dateApplication ? \Carbon\Carbon::parse($cultureIntrant->dateApplication)->format('d/m/Y') : '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

            </div>
        @empty
            <div class="rounded-lg border border-dashed border-slate-200 bg-slate-50 p-6 text-center text-sm text-slate-500">
                Aucune culture enregistrée pour cette parcelle.
            </div>
        @endforelse

    </div>

</div>

@endsection