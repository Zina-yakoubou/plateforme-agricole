@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- ============================================================
        EN-TÊTE
    ============================================================ --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

        <div>

            {{-- Retour --}}
            <div class="flex items-center gap-2 mb-2">
                <a
                    href="{{ route('dpa.planification.show', $planification) }}"
                    class="text-slate-500 hover:text-slate-700"
                >
                    ← Retour à la planification
                </a>
            </div>

            {{-- Titre --}}
            <h1 class="text-2xl font-bold text-slate-800">
                Étapes de la planification
            </h1>

            {{-- Campagne --}}
            <p class="text-slate-500 mt-1">
                {{ $planification->campagne->libelle }}
            </p>

        </div>


        {{-- Bouton ajouter --}}
        @if($planification->statut === 'brouillon')

            <a
                href="{{ route('dpa.planification.etapes.create', $planification) }}"
                class="inline-flex items-center px-4 py-2 rounded-lg
                       bg-[#266486] text-white
                       hover:bg-[#1f5570]"
            >
                + Ajouter une étape
            </a>

        @endif

    </div>


    {{-- ============================================================
        RÉSUMÉ DE LA PLANIFICATION
    ============================================================ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Préfecture --}}
        <div class="bg-white border rounded-xl p-4">

            <p class="text-sm text-slate-500">
                Préfecture
            </p>

            <h3 class="font-semibold text-slate-800 mt-1">
                {{ $planification->prefecture->nomPrefecture }}
            </h3>

        </div>


        {{-- Date début --}}
        <div class="bg-white border rounded-xl p-4">

            <p class="text-sm text-slate-500">
                Début planification
            </p>

            <h3 class="font-semibold text-slate-800 mt-1">
                {{ $planification->dateDebut->format('d/m/Y') }}
            </h3>

        </div>


        {{-- Date fin --}}
        <div class="bg-white border rounded-xl p-4">

            <p class="text-sm text-slate-500">
                Fin planification
            </p>

            <h3 class="font-semibold text-slate-800 mt-1">
                {{ $planification->dateFin?->format('d/m/Y') ?? '-' }}
            </h3>

        </div>


        {{-- Nombre d'étapes --}}
        <div class="bg-white border rounded-xl p-4">

            <p class="text-sm text-slate-500">
                Nombre d'étapes
            </p>

            <h3 class="font-semibold text-slate-800 mt-1">
                {{ $planification->etapes->count() }}
            </h3>

        </div>

    </div>


    {{-- ============================================================
        LISTE DES ÉTAPES
    ============================================================ --}}
    <div class="bg-white rounded-xl border shadow-sm overflow-hidden">

        {{-- En-tête du tableau --}}
        <div class="px-5 py-4 border-b">

            <h2 class="font-semibold text-slate-700">
                Liste des étapes opérationnelles
            </h2>

        </div>


        {{-- ========================================================
            ÉTAPES EXISTANTES
        ======================================================== --}}
        @if($planification->etapes->count() > 0)

            <div class="overflow-x-auto">

                <table class="min-w-full text-sm">

                    {{-- En-tête --}}
                    <thead class="bg-slate-50 text-slate-600">

                        <tr>

                            <th class="px-4 py-3 text-left">
                                Ordre
                            </th>

                            <th class="px-4 py-3 text-left">
                                Étape
                            </th>

                            <th class="px-4 py-3 text-left">
                                Période
                            </th>

                            <th class="px-4 py-3 text-left">
                                Statut
                            </th>

                            <th class="px-4 py-3 text-right">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    {{-- Corps --}}
                    <tbody class="divide-y divide-slate-200">

                        @foreach($planification->etapes as $etape)

                            <tr class="hover:bg-slate-50">

                                {{-- Ordre --}}
                                <td class="px-4 py-3">

                                    <span class="font-semibold text-[#266486]">
                                        {{ $etape->ordre }}
                                    </span>

                                </td>


                                {{-- Étape --}}
                                <td class="px-4 py-3">

                                    <div class="font-medium text-slate-700">
                                        {{ $etape->libelle }}
                                    </div>

                                    @if($etape->description)

                                        <div class="text-xs text-slate-500 mt-1">
                                            {{ $etape->description }}
                                        </div>

                                    @endif

                                </td>


                                {{-- Période --}}
                                <td class="px-4 py-3">

                                    <div>
                                        {{ $etape->dateDebut->format('d/m/Y') }}
                                    </div>

                                    <div class="text-xs text-slate-400 my-1">
                                        au
                                    </div>

                                    <div>
                                        {{ $etape->dateFin->format('d/m/Y') }}
                                    </div>

                                </td>


                                {{-- Statut --}}
                                <td class="px-4 py-3">

                                    @php

                                        $badge = [
                                            'planifiee' =>
                                                'bg-yellow-100 text-yellow-800',

                                            'en_cours' =>
                                                'bg-blue-100 text-blue-700',

                                            'terminee' =>
                                                'bg-green-100 text-green-700',
                                        ];

                                    @endphp


                                    <span
                                        class="inline-flex px-3 py-1 rounded-full
                                               text-xs font-semibold
                                               {{ $badge[$etape->statut] ?? 'bg-slate-100 text-slate-700' }}"
                                    >
                                        {{ ucfirst(str_replace('_', ' ', $etape->statut)) }}
                                    </span>

                                </td>


                                {{-- Actions --}}
                                <td class="px-4 py-3">

                                    @if($planification->statut === 'brouillon')

                                        <div class="flex justify-end items-center gap-2">

                                            {{-- Modifier --}}
                                            <a
                                                href="{{ route('dpa.planification.etapes.edit', $etape) }}"
                                                class="px-3 py-1 rounded-lg
                                                       bg-amber-100 text-amber-700
                                                       hover:bg-amber-200"
                                            >
                                                Modifier
                                            </a>


                                            {{-- Supprimer --}}
                                            <form
                                                action="{{ route('dpa.planification.etapes.destroy', $etape) }}"
                                                method="POST"
                                                onsubmit="return confirm('Supprimer cette étape ?')"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="px-3 py-1 rounded-lg
                                                           bg-red-100 text-red-700
                                                           hover:bg-red-200"
                                                >
                                                    Supprimer
                                                </button>

                                            </form>

                                        </div>

                                    @else

                                        <div class="text-right text-xs text-slate-400">
                                            Aucune action
                                        </div>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


        {{-- ========================================================
            AUCUNE ÉTAPE
        ======================================================== --}}
        @else

            <div class="p-12 text-center">

                {{-- Icône --}}
                <div class="text-5xl mb-4">
                    📅
                </div>


                {{-- Message --}}
                <h3 class="text-lg font-semibold text-slate-700 mb-2">
                    Aucune étape enregistrée
                </h3>


                <p class="text-slate-500 mb-6 max-w-xl mx-auto">
                    Commencez par définir les différentes étapes
                    opérationnelles de cette campagne dans votre préfecture.
                </p>


                {{-- Ajouter première étape --}}
                @if($planification->statut === 'brouillon')

                    <a
                        href="{{ route('dpa.planification.etapes.create', $planification) }}"
                        class="inline-flex items-center
                               px-5 py-3 rounded-lg
                               bg-[#266486] text-white
                               hover:bg-[#1f5570]"
                    >
                        Ajouter la première étape
                    </a>

                @endif

            </div>

        @endif

    </div>

</div>

@endsection