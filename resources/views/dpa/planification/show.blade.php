@extends('layouts.app')

@section('content')

<div class="w-full min-w-0 space-y-6">

    {{-- =========================================================
         FIL D'ARIANE + EN-TÊTE
    ========================================================== --}}
    <div class="w-full">

        <div class="mb-3 flex items-center gap-2 text-sm text-slate-500">

            <a
                href="{{ route('dpa.planification.index') }}"
                class="transition hover:text-[#266486]"
            >
                Planifications
            </a>

            <span>/</span>

            <span class="text-slate-700">
                Détails
            </span>

        </div>


        <div class="flex min-w-0 flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">

            {{-- TITRE --}}
            <div class="min-w-0">

                <h1 class="text-2xl font-bold text-slate-800 sm:text-3xl">
                    Planification préfectorale
                </h1>

                <p class="mt-1 break-words text-slate-500">
                    {{ $planification->campagne->libelle ?? 'Campagne non définie' }}

                    @if($planification->campagne?->codeCampagne)
                        <span class="font-medium">
                            ({{ $planification->campagne->codeCampagne }})
                        </span>
                    @endif
                </p>

            </div>


            {{-- ACTIONS RAPIDES --}}
            @if($planification->statut === 'brouillon')

                <div class="flex shrink-0 flex-wrap gap-2">

                    <a
                        href="{{ route('dpa.planification.edit', $planification) }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-amber-500 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-amber-600"
                    >
                        <span>✏️</span>
                        <span>Modifier</span>
                    </a>
                    <a
                        href="{{ route('dpa.planification.index', $planification) }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#266486] px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-[#1d4f69]"
                    >
                        <span>📅</span>
                        <span>Gérer les étapes</span>
                    </a>

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
         STATUT
    ========================================================== --}}
    @php

        $badges = [
            'brouillon' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            'soumise'   => 'bg-blue-100 text-blue-800 border-blue-200',
            'validee'   => 'bg-green-100 text-green-800 border-green-200',
            'rejetee'   => 'bg-red-100 text-red-800 border-red-200',
        ];

        $libellesStatut = [
            'brouillon' => 'Brouillon',
            'soumise'   => 'Soumise',
            'validee'   => 'Validée',
            'rejetee'   => 'Rejetée',
        ];

    @endphp


    <div class="w-full rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            {{-- STATUT --}}
            <div>

                <p class="mb-2 text-sm text-slate-500">
                    Statut de la planification
                </p>

                <span
                    class="inline-flex items-center rounded-full border px-3 py-1 text-sm font-semibold {{ $badges[$planification->statut] ?? 'border-slate-200 bg-slate-100 text-slate-700' }}"
                >
                    {{ $libellesStatut[$planification->statut] ?? ucfirst($planification->statut) }}
                </span>

            </div>


            {{-- DATE DE CREATION --}}
            <div class="text-left text-sm sm:text-right">

                <p class="text-slate-500">
                    Créée le
                </p>

                <p class="font-semibold text-slate-700">

                    {{ $planification->created_at?->format('d/m/Y à H:i') ?? 'Non disponible' }}

                </p>

            </div>

        </div>

    </div>


    {{-- =========================================================
         INFORMATIONS GÉNÉRALES
    ========================================================== --}}
    <div class="grid w-full min-w-0 grid-cols-1 gap-6 xl:grid-cols-2">


        {{-- =====================================================
             INFORMATIONS CAMPAGNE
        ====================================================== --}}
        <div class="min-w-0 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="mb-5 flex items-center gap-3">

                

                <div>

                    <h2 class="text-lg font-semibold text-slate-800">
                        Informations de la campagne
                    </h2>

                    <p class="text-sm text-slate-500">
                        Informations générales de la campagne nationale.
                    </p>

                </div>

            </div>


            <div class="space-y-5">

                {{-- CAMPAGNE --}}
                <div>

                    <p class="text-sm text-slate-500">
                        Campagne
                    </p>

                    <p class="mt-1 break-words font-medium text-slate-700">
                        {{ $planification->campagne->libelle ?? 'Non définie' }}
                    </p>

                </div>


                {{-- CODE --}}
                <div>

                    <p class="text-sm text-slate-500">
                        Code campagne
                    </p>

                    <p class="mt-1 font-medium text-slate-700">
                        {{ $planification->campagne->codeCampagne ?? 'Non défini' }}
                    </p>

                </div>


                {{-- STRUCTURE --}}
                <div>

                    <p class="text-sm text-slate-500">
                        Structure nationale
                    </p>

                    <p class="mt-1 break-words font-medium text-slate-700">
                        {{ $planification->campagne->structure->libelle ?? 'Non définie' }}
                    </p>

                </div>


                {{-- PREFECTURE --}}
                <div>

                    <p class="text-sm text-slate-500">
                        Préfecture concernée
                    </p>

                    <p class="mt-1 font-medium text-slate-700">
                        {{ $planification->prefecture->nomPrefecture ?? 'Non définie' }}
                    </p>

                </div>


                {{-- PERIODE --}}
                <div>

                    <p class="text-sm text-slate-500">
                        Période nationale
                    </p>

                    <p class="mt-1 break-words font-medium text-slate-700">

                        @if($planification->campagne?->dateDebut)

                            {{ $planification->campagne->dateDebut->format('d/m/Y H:i') }}

                        @else

                            Non définie

                        @endif

                        —

                        @if($planification->campagne?->dateFin)

                            {{ $planification->campagne->dateFin->format('d/m/Y H:i') }}

                        @else

                            Non définie

                        @endif

                    </p>

                </div>

            </div>

        </div>


        {{-- =====================================================
             INFORMATIONS PLANIFICATION
        ====================================================== --}}
        <div class="min-w-0 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="mb-5 flex items-center gap-3">

                <div>

                    <h2 class="text-lg font-semibold text-slate-800">
                        Informations de la planification
                    </h2>

                    <p class="text-sm text-slate-500">
                        Calendrier préfectoral de la campagne.
                    </p>

                </div>

            </div>


            <div class="space-y-5">

                {{-- DATE DEBUT --}}
                <div>

                    <p class="text-sm text-slate-500">
                        Date de début
                    </p>

                    <p class="mt-1 font-medium text-slate-700">

                        @if($planification->dateDebut)

                            {{ $planification->dateDebut->format('d/m/Y H:i') }}

                        @else

                            Non définie

                        @endif

                    </p>

                </div>


                {{-- DATE FIN --}}
                <div>

                    <p class="text-sm text-slate-500">
                        Date de fin
                    </p>

                    <p class="mt-1 font-medium text-slate-700">

                        @if($planification->dateFin)

                            {{ $planification->dateFin->format('d/m/Y H:i') }}

                        @else

                            Non définie

                        @endif

                    </p>

                </div>


                {{-- PLANIFIE PAR --}}
                <div>

                    <p class="text-sm text-slate-500">
                        Planifiée par
                    </p>

                    <p class="mt-1 break-words font-medium text-slate-700">
                        {{ $planification->planifiePar->name ?? 'Non défini' }}
                    </p>

                </div>


                {{-- VALIDATION --}}
                <div>

                    <p class="text-sm text-slate-500">
                        Date de validation nationale
                    </p>

                    <p class="mt-1 font-medium text-slate-700">

                        @if($planification->dateValidation)

                            {{ $planification->dateValidation->format('d/m/Y H:i') }}

                        @else

                            <span class="text-slate-400">
                                En attente
                            </span>

                        @endif

                    </p>

                </div>


                {{-- VALIDATEUR --}}
                <div>

                    <p class="text-sm text-slate-500">
                        Validée par
                    </p>

                    <p class="mt-1 break-words font-medium text-slate-700">
                        {{ $planification->validePar->name ?? 'Aucune validation' }}
                    </p>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         OBSERVATIONS
    ========================================================== --}}
    <div class="w-full rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

        <div class="mb-4 flex items-center gap-3">

    

            <div>

                <h2 class="text-lg font-semibold text-slate-800">
                    Observations du Directeur Préfectoral
                </h2>

                <p class="text-sm text-slate-500">
                    Commentaires et observations associés à la planification.
                </p>

            </div>

        </div>


        @if($planification->observations)

            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">

                <p class="whitespace-pre-line break-words text-slate-700">
                    {{ $planification->observations }}
                </p>

            </div>

        @else

            <div class="rounded-lg border border-dashed border-slate-300 bg-slate-50 p-4">

                <p class="italic text-slate-400">
                    Aucune observation enregistrée.
                </p>

            </div>

        @endif

    </div>


    {{-- =========================================================
         ÉTAPES OPÉRATIONNELLES
    ========================================================== --}}
    <div class="w-full min-w-0 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

        {{-- EN-TÊTE --}}
        <div class="mb-6 flex min-w-0 flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

            <div class="min-w-0">

                <div class="flex items-center gap-3">

                    <div>

                        <h2 class="text-lg font-semibold text-slate-800">
                            Étapes opérationnelles
                        </h2>

                        <p class="text-sm text-slate-500">
                            Calendrier opérationnel de la campagne dans la préfecture.
                        </p>

                    </div>

                </div>

            </div>


            {{-- ACTIONS ETAPES --}}
            @if($planification->statut === 'brouillon')

                <div class="flex shrink-0 flex-wrap gap-2">

                    <a
                        href="{{ route('dpa.planification.create', $planification) }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-green-700"
                    >
                        <span>+</span>
                        <span>Ajouter une étape</span>
                    </a>

                    <a
                        href="{{ route('dpa.planification.index', $planification) }}"
                        class="inline-flex items-center justify-center rounded-lg bg-[#266486] px-4 py-2.5 text-sm font-medium text-white transition hover:bg-[#1d4f69]"
                    >
                        Gérer les étapes
                    </a>

                </div>

            @endif

        </div>


        {{-- =====================================================
             LISTE DES ÉTAPES
        ====================================================== --}}
        @if($planification->etapes && $planification->etapes->count())

            <div class="w-full min-w-0 overflow-x-auto">

                <table class="w-full min-w-[760px] text-sm">

                    <thead>

                        <tr class="border-b border-slate-200 bg-slate-50 text-slate-600">

                            <th class="px-4 py-3 text-left font-semibold">
                                Ordre
                            </th>

                            <th class="px-4 py-3 text-left font-semibold">
                                Étape
                            </th>

                            <th class="px-4 py-3 text-left font-semibold">
                                Début
                            </th>

                            <th class="px-4 py-3 text-left font-semibold">
                                Fin
                            </th>

                            <th class="px-4 py-3 text-left font-semibold">
                                Statut
                            </th>

                            @if($planification->statut === 'brouillon')

                                <th class="px-4 py-3 text-right font-semibold">
                                    Actions
                                </th>

                            @endif

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @foreach($planification->etapes->sortBy('ordre') as $etape)

                            @php

                                $etat = [

                                    'planifiee' => [
                                        'label' => 'Planifiée',
                                        'class' => 'bg-yellow-100 text-yellow-700 border-yellow-200'
                                    ],

                                    'en_cours' => [
                                        'label' => 'En cours',
                                        'class' => 'bg-blue-100 text-blue-700 border-blue-200'
                                    ],

                                    'terminee' => [
                                        'label' => 'Terminée',
                                        'class' => 'bg-green-100 text-green-700 border-green-200'
                                    ],

                                ];

                                $statutEtape = $etat[$etape->statut] ?? [
                                    'label' => ucfirst(str_replace('_', ' ', $etape->statut)),
                                    'class' => 'bg-slate-100 text-slate-700 border-slate-200'
                                ];

                            @endphp


                            <tr class="transition hover:bg-slate-50">

                                {{-- ORDRE --}}
                                <td class="px-4 py-4">

                                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-50 font-semibold text-[#266486]">
                                        {{ $etape->ordre }}
                                    </span>

                                </td>


                                {{-- ETAPE --}}
                                <td class="max-w-md px-4 py-4">

                                    <div class="break-words font-semibold text-slate-700">
                                        {{ $etape->libelle }}
                                    </div>

                                    @if($etape->description)

                                        <div class="mt-1 break-words text-xs leading-5 text-slate-500">
                                            {{ $etape->description }}
                                        </div>

                                    @endif

                                </td>


                                {{-- DEBUT --}}
                                <td class="whitespace-nowrap px-4 py-4 text-slate-600">

                                    @if($etape->dateDebut)

                                        {{ $etape->dateDebut->format('d/m/Y') }}

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- FIN --}}
                                <td class="whitespace-nowrap px-4 py-4 text-slate-600">

                                    @if($etape->dateFin)

                                        {{ $etape->dateFin->format('d/m/Y') }}

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- STATUT --}}
                                <td class="px-4 py-4">

                                    <span
                                        class="inline-flex whitespace-nowrap rounded-full border px-3 py-1 text-xs font-semibold {{ $statutEtape['class'] }}"
                                    >
                                        {{ $statutEtape['label'] }}
                                    </span>

                                </td>


                                {{-- ACTIONS --}}
                                @if($planification->statut === 'brouillon')

                                    <td class="px-4 py-4">

                                        <div class="flex justify-end gap-2">

                                            <a
                                                href="{{ route('dpa.planification.etapes.edit', $etape) }}"
                                                class="inline-flex items-center rounded-lg bg-amber-50 px-3 py-2 text-xs font-medium text-amber-700 transition hover:bg-amber-100"
                                            >
                                                Modifier
                                            </a>


                                            <form
                                                method="POST"
                                                action="{{ route('dpa.planification.etapes.destroy', $etape) }}"
                                                onsubmit="return confirm('Voulez-vous vraiment supprimer cette étape ?')"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center rounded-lg bg-red-50 px-3 py-2 text-xs font-medium text-red-700 transition hover:bg-red-100"
                                                >
                                                    Supprimer
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                @endif

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            {{-- AUCUNE ETAPE --}}
            <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-6 py-12 text-center">

                <div class="mb-4 text-5xl">
                    
                </div>

                <h3 class="text-lg font-semibold text-slate-700">
                    Aucune étape de planification
                </h3>

                <p class="mx-auto mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                    Définissez les différentes étapes opérationnelles de la campagne
                    dans la préfecture : sensibilisation, formation, recensement,
                    supervision, contrôle, validation, etc.
                </p>


                @if($planification->statut === 'brouillon')

                    <a
                        href="{{ route('dpa.planification.create', $planification) }}"
                        class="mt-6 inline-flex items-center gap-2 rounded-lg bg-[#266486] px-5 py-3 text-sm font-medium text-white shadow-sm transition hover:bg-[#1d4f69]"
                    >
                        <span>+</span>
                        <span>Ajouter la première étape</span>
                    </a>

                @endif

            </div>

        @endif

    </div>


    {{-- =========================================================
         ACTIONS MÉTIER
    ========================================================== --}}
    <div class="w-full rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

        <div class="mb-5">

            <h2 class="text-lg font-semibold text-slate-800">
                Actions de la planification
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Actions disponibles selon l'état actuel de la planification.
            </p>

        </div>


        <div class="flex flex-wrap items-center gap-3">


            {{-- =================================================
                 BROUILLON
            ================================================== --}}
            @if($planification->statut === 'brouillon')

                <a
                    href="{{ route('dpa.planification.edit', $planification) }}"
                    class="inline-flex items-center rounded-lg bg-amber-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-amber-600"
                >
                    Modifier la planification
                </a>


                <a
                    href="{{ route('dpa.planification.index', $planification) }}"
                    class="inline-flex items-center rounded-lg bg-[#266486] px-4 py-2.5 text-sm font-medium text-white transition hover:bg-[#1d4f69]"
                >
                    Gérer les étapes
                </a>


                {{-- Validation nationale à développer --}}
                <button
                    type="button"
                    disabled
                    class="inline-flex cursor-not-allowed items-center rounded-lg bg-green-600 px-4 py-2.5 text-sm font-medium text-white opacity-50"
                >
                    Soumettre à validation nationale
                </button>


            {{-- =================================================
                 SOUMISE
            ================================================== --}}
            @elseif($planification->statut === 'soumise')

                <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">

                    <div class="font-semibold">
                        Planification soumise
                    </div>

                    <div class="mt-1">
                        La planification a été transmise au niveau national
                        pour validation.
                    </div>

                </div>


            {{-- =================================================
                 VALIDEE
            ================================================== --}}
            @elseif($planification->statut === 'validee')

                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">

                    <div class="font-semibold">
                        Planification validée
                    </div>

                    <div class="mt-1">
                        La planification a été validée par la structure nationale.
                    </div>

                </div>


                {{-- Affectation à développer --}}
                <button
                    type="button"
                    disabled
                    class="inline-flex cursor-not-allowed items-center rounded-lg bg-[#266486] px-4 py-2.5 text-sm font-medium text-white opacity-50"
                >
                    Commencer l'affectation des superviseurs
                </button>


            {{-- =================================================
                 REJETEE
            ================================================== --}}
            @elseif($planification->statut === 'rejetee')

                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">

                    <div class="font-semibold">
                        Planification rejetée
                    </div>

                    <div class="mt-1">
                        Corrigez la planification puis soumettez-la
                        à nouveau pour validation.
                    </div>

                </div>


                <a
                    href="{{ route('dpa.planification.edit', $planification) }}"
                    class="inline-flex items-center rounded-lg bg-amber-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-amber-600"
                >
                    Corriger la planification
                </a>

            @endif

        </div>

    </div>


    {{-- =========================================================
         RETOUR
    ========================================================== --}}
    <div class="flex justify-start pb-4">

        <a
            href="{{ route('dpa.planification.index') }}"
            class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-[#266486]"
        >
            ← Retour aux planifications
        </a>

    </div>

</div>

@endsection