@extends('layouts.app')

@section('page-title', 'Détail de la zone')

@section(
'page-subtitle',
'Consultation des informations du village et de son équipe'
)

@section('content')

@php
$village = $affectation->village;
$canton = $village?->canton;
$commune = $canton?->commune;
$prefecture = $commune?->prefecture;


$equipe = $affectation->equipe;
$campagne = $affectation->campagne;
$superviseur = $equipe?->superviseur;
$membres = $equipe?->membres ?? collect();


@endphp

<div class="mx-auto max-w-7xl space-y-6">


{{-- ============================================================
    RETOUR
============================================================= --}}
<div>
    <a
        href="{{ route('superviseur.zones.index') }}"
        class="inline-flex items-center gap-2 text-sm font-medium
               text-gray-600 hover:text-[#006a4f]"
    >
        <svg
            class="h-4 w-4"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-width="1.8"
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M19 12H5m6 6-6-6 6-6"
            />
        </svg>

        Retour à mes zones
    </a>
</div>


{{-- ============================================================
    EN-TÊTE DE LA ZONE
============================================================= --}}
<div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

    <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">

        <div>

            <div class="flex flex-wrap items-center gap-3">

                <h2 class="text-2xl font-semibold text-gray-900">
                    {{ $village?->nom ?? 'Village non renseigné' }}
                </h2>

                <span
                    class="inline-flex items-center rounded-full
                           bg-green-50 px-3 py-1 text-xs font-semibold
                           text-green-700"
                >
                    Zone active
                </span>

            </div>

            @if($village?->code)
                <p class="mt-2 text-sm text-gray-500">
                    Code village :
                    <span class="font-medium text-gray-700">
                        {{ $village->code }}
                    </span>
                </p>
            @endif

        </div>

        <div class="rounded-lg bg-gray-50 px-4 py-3">

            <p class="text-xs uppercase tracking-wide text-gray-500">
                Référence affectation
            </p>

            <p class="mt-1 text-sm font-semibold text-gray-900">
                {{ $affectation->reference }}
            </p>

        </div>

    </div>

</div>


{{-- ============================================================
    LOCALISATION
============================================================= --}}
<div class="rounded-xl border border-gray-200 bg-white shadow-sm">

    <div class="border-b border-gray-200 px-6 py-4">

        <h3 class="font-semibold text-gray-900">
            Localisation
        </h3>

        <p class="mt-1 text-sm text-gray-500">
            Situation administrative de la zone.
        </p>

    </div>

    <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2 lg:grid-cols-4">

        {{-- PRÉFECTURE --}}
        <div>
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                Préfecture
            </p>

            <p class="mt-1 text-sm font-semibold text-gray-900">
                {{ $prefecture?->nom ?? '—' }}
            </p>
        </div>

        {{-- COMMUNE --}}
        <div>
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                Commune
            </p>

            <p class="mt-1 text-sm font-semibold text-gray-900">
                {{ $commune?->nom ?? '—' }}
            </p>
        </div>

        {{-- CANTON --}}
        <div>
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                Canton
            </p>

            <p class="mt-1 text-sm font-semibold text-gray-900">
                {{ $canton?->nom ?? '—' }}
            </p>
        </div>

        {{-- VILLAGE --}}
        <div>
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                Village
            </p>

            <p class="mt-1 text-sm font-semibold text-gray-900">
                {{ $village?->nom ?? '—' }}
            </p>
        </div>

    </div>

</div>


{{-- ============================================================
    CAMPAGNE + AFFECTATION
============================================================= --}}
<div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

    {{-- CAMPAGNE --}}
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 px-6 py-4">

            <h3 class="font-semibold text-gray-900">
                Campagne
            </h3>

        </div>

        <div class="space-y-5 p-6">

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Libellé
                </p>

                <p class="mt-1 text-sm font-semibold text-gray-900">
                    {{ $campagne?->libelle ?? '—' }}
                </p>
            </div>

            @if($campagne?->codeCampagne)

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        Code campagne
                    </p>

                    <p class="mt-1 text-sm text-gray-700">
                        {{ $campagne->codeCampagne }}
                    </p>
                </div>

            @endif

            @if($campagne?->dateDebut)

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        Début de la campagne
                    </p>

                    <p class="mt-1 text-sm text-gray-700">
                        {{ $campagne->dateDebut->format('d/m/Y') }}
                    </p>
                </div>

            @endif

        </div>

    </div>


    {{-- AFFECTATION --}}
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 px-6 py-4">

            <h3 class="font-semibold text-gray-900">
                Affectation
            </h3>

        </div>

        <div class="space-y-5 p-6">

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Date de début
                </p>

                <p class="mt-1 text-sm text-gray-700">
                    {{ $affectation->dateDebut?->format('d/m/Y') ?? '—' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Date de fin
                </p>

                <p class="mt-1 text-sm text-gray-700">
                    {{ $affectation->dateFin?->format('d/m/Y') ?? 'Non définie' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Statut
                </p>

                <span
                    class="mt-1 inline-flex rounded-full bg-green-50
                           px-3 py-1 text-xs font-semibold text-green-700"
                >
                    {{ ucfirst($affectation->statut) }}
                </span>
            </div>

        </div>

    </div>

</div>


{{-- ============================================================
    ÉQUIPE
============================================================= --}}
<div class="rounded-xl border border-gray-200 bg-white shadow-sm">

    <div class="border-b border-gray-200 px-6 py-4">

        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <h3 class="font-semibold text-gray-900">
                    Équipe affectée
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Agents chargés du recensement dans cette zone.
                </p>

            </div>

            @if($equipe)

                <span
                    class="inline-flex rounded-full bg-gray-100
                           px-3 py-1 text-xs font-medium text-gray-700"
                >
                    {{ $equipe->reference }}
                </span>

            @endif

        </div>

    </div>


    @if($equipe)

        <div class="p-6">

            {{-- INFORMATIONS ÉQUIPE --}}
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                <div>

                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        Équipe
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $equipe->libelle ?? $equipe->reference }}
                    </p>

                </div>


                <div>

                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        Superviseur
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $superviseur?->name ?? '—' }}
                    </p>

                </div>

            </div>


            {{-- MEMBRES --}}
            <div class="mt-8">

                <div class="mb-4 flex items-center justify-between">

                    <div>

                        <h4 class="text-sm font-semibold text-gray-900">
                            Agents de l'équipe
                        </h4>

                        <p class="mt-1 text-xs text-gray-500">
                            {{ $membres->count() }}
                            membre(s)
                        </p>

                    </div>

                </div>


                @if($membres->count())

                    <div class="overflow-hidden rounded-lg border border-gray-200">

                        <div class="overflow-x-auto">

                            <table class="min-w-full divide-y divide-gray-200">

                                <thead class="bg-gray-50">

                                    <tr>

                                        <th
                                            class="px-5 py-3 text-left text-xs
                                                   font-semibold uppercase
                                                   tracking-wide text-gray-500"
                                        >
                                            Agent
                                        </th>

                                        <th
                                            class="px-5 py-3 text-left text-xs
                                                   font-semibold uppercase
                                                   tracking-wide text-gray-500"
                                        >
                                            Téléphone
                                        </th>

                                    </tr>

                                </thead>

                                <tbody class="divide-y divide-gray-100 bg-white">

                                    @foreach($membres as $membre)

                                        <tr class="hover:bg-gray-50">

                                            <td class="px-5 py-4">

                                                <div class="font-medium text-gray-900">
                                                    {{ $membre->name }}
                                                </div>

                                            </td>

                                           

                                            <td class="px-5 py-4 text-sm text-gray-600">
                                                {{ $membre->telephone ?? '—' }}
                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                @else

                    <div class="rounded-lg border border-dashed border-gray-300
                                px-6 py-8 text-center">

                        <p class="text-sm text-gray-500">
                            Aucun agent n'est actuellement rattaché à cette équipe.
                        </p>

                    </div>

                @endif

            </div>

        </div>

    @else

        <div class="px-6 py-10 text-center">

            <p class="text-sm text-gray-500">
                Aucune équipe n'est associée à cette affectation.
            </p>

        </div>

    @endif

</div>


{{-- ============================================================
    OBSERVATIONS
============================================================= --}}
@if($affectation->observations)

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 px-6 py-4">

            <h3 class="font-semibold text-gray-900">
                Observations
            </h3>

        </div>

        <div class="p-6">

            <p class="whitespace-pre-line text-sm leading-6 text-gray-700">
                {{ $affectation->observations }}
            </p>

        </div>

    </div>

@endif


{{-- ============================================================
    MESSAGE DE CONSULTATION
============================================================= --}}
{{-- <div class="rounded-xl border border-green-100 bg-green-50 px-5 py-4">

    <div class="flex gap-3">

        <svg
            class="mt-0.5 h-5 w-5 shrink-0 text-green-700"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-width="1.8"
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M12 9v4m0 4h.01M10.3 3.6 2.8 17a2 2 0 0 0 1.75 3h14.9a2 2 0 0 0 1.75-3L13.7 3.6a2 2 0 0 0-3.4 0Z"
            />
        </svg>

        <div>

            <p class="text-sm font-medium text-green-800">
                Mode consultation
            </p>

            <p class="mt-1 text-sm text-green-700">
                En tant que superviseur, vous pouvez consulter les
                informations de cette zone et suivre le travail de
                l'équipe. La saisie et la modification des données de
                recensement restent réservées aux agents recenseurs.
            </p>

        </div>

    </div>

</div> --}}


</div>

@endsection
