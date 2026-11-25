@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}
    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

        <div>
            <div class="flex items-center gap-2">

                <h1 class="text-2xl font-semibold tracking-tight text-[#212529]">
                    Recensements de la maison
                </h1>

                <span class="rounded-full bg-[#e5f2ee] px-3 py-1
                             text-xs font-semibold text-[#006a4f]">
                    SIRA-Mô
                </span>

            </div>

            {{-- <p class="mt-1 text-sm text-[#6b7280]">
                Consultez l'historique des campagnes de recensement de cette habitation ou initialisez un nouveau passage.
            </p> --}}
            <p class="mt-1 text-sm text-[#6b7280]">
                @if(isset($maison) && $maison)
                    Maison n° <strong>{{ $maison->numeroMaison }}</strong>
                    @if($maison->village) , village de {{ $maison->village->nom }} @endif
                    . Consultez son historique ou initialisez un nouveau passage.
                @else
                    Consultez l'historique des campagnes de recensement.
                @endif
            </p>
        </div>


        {{-- =====================================================
             NOUVEAU RECENSEMENT (ASSOCIÉ AU VILLAGE DE LA MAISON)
        ====================================================== --}}
        <div>
            @if(request('maison_id'))
                @php
                    $maisonCourante = \App\Models\Maison::find(request('maison_id'));
                    
                    // Recherche de l'affectation active de l'agent qui correspond au village de cette maison
                    $affectationCorrespondante = null;
                    if ($maisonCourante) {
                        $affectationCorrespondante = \App\Models\Affectation::where('statut', 'active')
                            ->where('village_id', $maisonCourante->village_id)
                            ->whereHas('equipe.membres', function($q) {
                                $q->where('users.id', auth()->id());
                            })
                            ->first();
                    }
                @endphp

                @if($affectationCorrespondante && $maisonCourante)
                    <form action="{{ route('agent.recensements.commencer', ['affectation' => $affectationCorrespondante->idAffectation, 'maison' => $maisonCourante->idMaison]) }}" method="POST">
                        @csrf
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 rounded-lg
                                   bg-[#006a4f] px-4 py-2.5
                                   text-sm font-semibold text-white
                                   shadow-sm transition
                                   hover:bg-[#156c52]
                                   focus:outline-none focus:ring-2
                                   focus:ring-[#006a4f]/30"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Créer un nouveau recensement
                        </button>
                    </form>
                @else
                    <span class="text-xs text-amber-700 bg-amber-50 px-3 py-2 rounded-lg border border-amber-200 inline-block font-medium">
                        ⚠️ Aucune affectation active pour le village de cette maison.
                    </span>
                @endif
            @endif
        </div>

    </div>


    {{-- =========================================================
         MESSAGES
    ========================================================== --}}
    @if(session('success'))

        <div class="flex items-start gap-3 rounded-lg
                    border border-[#b7dfd2]
                    bg-[#e5f2ee]
                    px-4 py-3 text-sm text-[#006a4f]">

            <svg
                class="mt-0.5 h-5 w-5 shrink-0"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 13l4 4L19 7"
                />
            </svg>

            <span>{{ session('success') }}</span>

        </div>

    @endif


    @if(session('error'))

        <div class="flex items-start gap-3 rounded-lg
                    border border-red-200
                    bg-red-50
                    px-4 py-3 text-sm text-red-700">

            <svg
                class="mt-0.5 h-5 w-5 shrink-0"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
            </svg>

            <span>{{ session('error') }}</span>

        </div>

    @endif


    @if(session('info'))

        <div class="flex items-start gap-3 rounded-lg
                    border border-blue-200
                    bg-blue-50
                    px-4 py-3 text-sm text-blue-700">

            <svg
                class="mt-0.5 h-5 w-5 shrink-0"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M12 21a9 9 0 100-18"
                />
            </svg>

            <span>{{ session('info') }}</span>

        </div>

    @endif


    {{-- =========================================================
         FILTRES / RECHERCHE
    ========================================================== --}}
    <div class="rounded-lg border border-[#e5e7eb]
                bg-white p-4
                shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

        <form
            method="GET"
            action="{{ route('agent.recensements.index') }}"
        >
            {{-- Conserver le filtre maison_id s'il est présent --}}
            @if(request('maison_id'))
                <input type="hidden" name="maison_id" value="{{ request('maison_id') }}">
            @endif

            <div class="flex flex-col gap-3 lg:flex-row">

                {{-- RECHERCHE --}}
                <div class="relative flex-1">

                    <svg
                        class="pointer-events-none absolute left-4 top-1/2
                               h-5 w-5 -translate-y-1/2 text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="m21 21-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"
                        />
                    </svg>

                    <input
                        type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Rechercher par campagne, numéro de maison ou adresse..."
                        class="w-full rounded-lg
                               border border-[#e5e7eb]
                               bg-white py-3 pl-11 pr-4
                               text-sm text-[#212529]
                               placeholder:text-gray-400
                               focus:border-[#006a4f]
                               focus:outline-none
                               focus:ring-2
                               focus:ring-[#006a4f]/10"
                    >

                </div>


                {{-- RECHERCHER --}}
                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2
                           rounded-lg bg-[#006a4f]
                           px-5 py-3
                           text-sm font-semibold text-white
                           transition hover:bg-[#156c52]"
                >

                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="m21 21-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"
                        />
                    </svg>

                    Rechercher

                </button>


                {{-- RÉINITIALISER --}}
                @if(request()->filled('search'))

                    <a
                        href="{{ route('agent.recensements.index', request('maison_id') ? ['maison_id' => request('maison_id')] : []) }}"
                        class="inline-flex items-center justify-center
                               rounded-lg border border-[#e5e7eb]
                               bg-white px-4 py-3
                               text-sm font-semibold text-gray-600
                               transition hover:bg-gray-50"
                    >
                        Réinitialiser
                    </a>

                @endif

            </div>

        </form>

    </div>


    {{-- =========================================================
         LISTE DES RECENSEMENTS
    ========================================================== --}}
    <div class="overflow-hidden rounded-lg
                border border-[#e5e7eb]
                bg-white
                shadow-[0_1px_2px_rgba(0,0,0,0.05)]">


        {{-- =====================================================
             EN-TÊTE DU TABLEAU
        ====================================================== --}}
        <div class="flex flex-col gap-3
                    border-b border-[#e5e7eb]
                    px-6 py-5
                    sm:flex-row sm:items-center sm:justify-between">

            <div>

                <h2 class="text-base font-semibold text-[#212529]">
                    Historique des recensements
                </h2>

                <p class="mt-1 text-xs text-gray-500">
                    Passages enregistrés pour cette habitation
                </p>

            </div>


            <div class="flex items-center gap-3">

                <span class="rounded-full bg-[#f8faf9]
                             px-3 py-1.5 text-sm text-gray-500">

                    <span class="font-semibold text-[#006a4f]">
                        {{ $recensements->total() }}
                    </span>

                    recensement(s)

                </span>

            </div>

        </div>


        {{-- =====================================================
             TABLEAU
        ====================================================== --}}
        <div class="overflow-x-auto">

            <table class="w-full min-w-[1000px] text-sm">

                <thead class="bg-[#f8faf9]">

                    <tr class="border-b border-[#e5e7eb]">

                        <th class="px-6 py-4 text-left text-xs
                                   font-semibold uppercase tracking-wide
                                   text-gray-500">
                            N°
                        </th>

                        <th class="px-6 py-4 text-left text-xs
                                   font-semibold uppercase tracking-wide
                                   text-gray-500">
                            Campagne
                        </th>

                        <th class="px-6 py-4 text-left text-xs
                                   font-semibold uppercase tracking-wide
                                   text-gray-500">
                            Maison & Localisation
                        </th>

                        <th class="px-6 py-4 text-left text-xs
                                   font-semibold uppercase tracking-wide
                                   text-gray-500">
                            Dernière modification
                        </th>

                        <th class="px-6 py-4 text-center text-xs
                                   font-semibold uppercase tracking-wide
                                   text-gray-500">
                            Statut
                        </th>

                        <th class="px-6 py-4 text-right text-xs
                                   font-semibold uppercase tracking-wide
                                   text-gray-500">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-[#e5e7eb]">

                    @forelse($recensements as $recensement)

                        <tr class="group transition hover:bg-[#f8faf9]">

                            {{-- N° --}}
                            <td class="px-6 py-4 text-sm text-gray-400">
                                {{
                                    $loop->iteration
                                    + (($recensements->currentPage() - 1)
                                    * $recensements->perPage())
                                }}
                            </td>

                            {{-- CAMPAGNE --}}
                            <td class="px-6 py-4">
                                <div>
                                    <span class="font-semibold text-[#212529]">
                                        {{ $recensement->campagne->libelle ?? 'Campagne Agricole' }}
                                    </span>
                                    <div class="mt-0.5 font-mono text-xs text-[#006a4f]">
                                        {{ $recensement->campagne->codeCampagne ?? '—' }}
                                    </div>
                                </div>
                            </td>

                            {{-- MAISON & LOCALISATION --}}
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800">
                                    Maison n° {{ $recensement->maison->numeroMaison ?? '—' }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    Village de {{ $recensement->maison->village->nom ?? '—' }}
                                </div>
                            </td>

                            {{-- DERNIÈRE MODIFICATION --}}
                            <td class="px-6 py-4 text-xs text-gray-500">
                                {{ $recensement->dateDerniereModification ? \Illuminate\Support\Carbon::parse($recensement->dateDerniereModification)->format('d/m/Y à H:i') : '—' }}
                            </td>

                            {{-- STATUT --}}
                            <td class="px-6 py-4 text-center">
                                @if(in_array($recensement->statut, ['termine', 'valide']))
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                        Terminé
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                        En cours
                                    </span>
                                @endif
                            </td>

                            {{-- ACTIONS : CONSULTER vs CONTINUER --}}
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">

                                    @if(in_array($recensement->statut, ['termine', 'valide']))
                                        {{-- Campagne clôturée / terminée : Consulter en lecture seule --}}
                                        <a
                                            href="{{ route('agent.recensements.show', $recensement->idRecensement) }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-[#e5e7eb] bg-white px-3.5 py-2 text-xs font-semibold text-gray-700 shadow-sm transition hover:border-[#006a4f] hover:bg-[#e5f2ee] hover:text-[#006a4f]"
                                        >
                                            <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Consulter
                                        </a>
                                    @else
                                        {{-- Campagne en cours / brouillon : Continuer la saisie --}}
                                        <a
                                            href="{{ route('agent.recensements.show', $recensement->idRecensement) }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-[#006a4f]/30 bg-[#e5f2ee] px-3.5 py-2 text-xs font-semibold text-[#006a4f] shadow-sm transition hover:bg-[#006a4f] hover:text-white"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Continuer
                                        </a>
                                    @endif

                                </div>
                            </td>

                        </tr>

                    @empty

                        {{-- AUCUN RECENSEMENT --}}
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">

                                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-[#e5f2ee] text-[#006a4f]">
                                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>

                                    <p class="font-semibold text-[#212529]">
                                        Aucun recensement trouvé
                                    </p>

                                    <p class="mt-1 text-sm text-gray-500">
                                        Aucun historique de recensement n'est enregistré pour cette habitation.
                                    </p>

                                    @if(request()->filled('search'))
                                        <a
                                            href="{{ route('agent.recensements.index', request('maison_id') ? ['maison_id' => request('maison_id')] : []) }}"
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


        {{-- =====================================================
             PAGINATION
        ====================================================== --}}
        @if($recensements->hasPages())

            <div class="border-t border-[#e5e7eb] px-6 py-4">
                {{ $recensements->withQueryString()->links() }}
            </div>

        @endif

    </div>

</div>

@endsection