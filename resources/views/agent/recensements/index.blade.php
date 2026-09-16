@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-slate-50">


{{-- ============================================================
    EN-TÊTE
============================================================ --}}
<div class="mb-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>
            <div class="flex items-center gap-3">

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6.586L19 7.586V19a2 2 0 01-2 2z"/>
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M14 3v5h5"/>
                    </svg>
                </div>

                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-800">
                        Mes recensements
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Reprendre ou consulter vos recensements.
                    </p>
                </div>

            </div>
        </div>

        <a href="{{ route('agent.affectations') }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200
                  bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm
                  transition hover:border-emerald-300 hover:bg-emerald-50 hover:text-emerald-700">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="1.8">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>

            Mes affectations
        </a>

    </div>
</div>


{{-- ============================================================
    RECHERCHE
============================================================ --}}
<div class="mb-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">

    <form method="GET"
          action="{{ route('agent.recensements.index') }}"
          class="flex flex-col gap-3 sm:flex-row">

        <div class="relative flex-1">

            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="m21 21-4.35-4.35m1.35-5.4a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z"/>
                </svg>
            </div>

            <input
                type="search"
                name="search"
                value="{{ $search }}"
                placeholder="Rechercher une maison ou un village..."
                class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4
                       text-sm text-slate-700 outline-none transition
                       placeholder:text-slate-400
                       focus:border-emerald-500 focus:bg-white
                       focus:ring-2 focus:ring-emerald-100"
            >

        </div>

        <button type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-700
                       px-5 py-3 text-sm font-semibold text-white transition
                       hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-300">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="1.8">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="m21 21-4.35-4.35m1.35-5.4a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z"/>
            </svg>

            Rechercher
        </button>

        @if($search !== '')

            <a href="{{ route('agent.recensements.index') }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200
                      bg-white px-5 py-3 text-sm font-semibold text-slate-600
                      transition hover:bg-slate-50">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>

                Effacer
            </a>

        @endif

    </form>

</div>


{{-- ============================================================
    LISTE
============================================================ --}}
<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

    {{-- En-tête --}}
    <div class="border-b border-slate-200 px-5 py-4">

        <div class="flex items-center justify-between gap-4">

            <div>
                <h2 class="text-base font-bold text-slate-800">
                    Recensements
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    {{ $recensements->total() }}
                    {{ $recensements->total() > 1 ? 'recensements' : 'recensement' }}
                </p>
            </div>

            @if($search !== '')

                <span class="hidden rounded-lg bg-slate-100 px-3 py-1.5 text-xs
                             font-medium text-slate-600 sm:inline-flex">
                    Recherche : {{ $search }}
                </span>

            @endif

        </div>

    </div>


    @if($recensements->count() > 0)

        {{-- ====================================================
            VERSION BUREAU
        ==================================================== --}}
        <div class="hidden overflow-x-auto md:block">

            <table class="min-w-full">

                <thead class="bg-slate-50">
                    <tr>

                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Maison
                        </th>

                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Zone
                        </th>

                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Campagne
                        </th>

                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Dernière activité
                        </th>

                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">
                            État
                        </th>

                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Action
                        </th>

                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @foreach($recensements as $recensement)

                        @php
                            $statut = $recensement->statut;

                            $statutLibelle = match ($statut) {
                                'brouillon' => 'Brouillon',
                                'en_cours' => 'En cours',
                                'termine' => 'Terminé',
                                'valide' => 'Validé',
                                default => ucfirst(str_replace('_', ' ', $statut)),
                            };

                            $statutClasses = match ($statut) {
                                'brouillon' => 'bg-slate-100 text-slate-700',
                                'en_cours' => 'bg-amber-100 text-amber-700',
                                'termine' => 'bg-blue-100 text-blue-700',
                                'valide' => 'bg-emerald-100 text-emerald-700',
                                default => 'bg-slate-100 text-slate-700',
                            };
                        @endphp

                        <tr class="transition hover:bg-slate-50">

                            {{-- Maison --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center
                                                rounded-xl bg-emerald-50 text-emerald-700">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-5 w-5"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.8">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M3 10.5L12 3l9 7.5"/>
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M5.5 9.5V21h13V9.5M9 21v-6h6v6"/>
                                        </svg>

                                    </div>

                                    <div>
                                        <div class="font-semibold text-slate-800">
                                            Maison {{ $recensement->maison?->numeroMaison ?? '—' }}
                                        </div>
                                    </div>

                                </div>

                            </td>


                            {{-- Zone --}}
                            <td class="px-5 py-4">

                                <div class="text-sm font-medium text-slate-700">
                                    {{ $recensement->maison?->village?->nomVillage ?? '—' }}
                                </div>

                                @if($recensement->maison?->village?->canton)
                                    <div class="mt-0.5 text-xs text-slate-400">
                                        {{ $recensement->maison->village->canton->nomCanton }}
                                    </div>
                                @endif

                            </td>


                            {{-- Campagne --}}
                            <td class="px-5 py-4">

                                <div class="text-sm font-semibold text-slate-700">
                                    {{ $recensement->campagne?->libelle ?? '—' }}
                                </div>

                            </td>


                            {{-- Dernière activité --}}
                            <td class="px-5 py-4">

                                <div class="text-sm text-slate-700">
                                    {{ $recensement->dateDerniereModification
                                        ? $recensement->dateDerniereModification->format('d/m/Y')
                                        : '—' }}
                                </div>

                                @if($recensement->dateDerniereModification)
                                    <div class="mt-0.5 text-xs text-slate-400">
                                        {{ $recensement->dateDerniereModification->format('H:i') }}
                                    </div>
                                @endif

                            </td>


                            {{-- État --}}
                            <td class="px-5 py-4 text-center">

                                <span class="inline-flex rounded-full px-3 py-1.5 text-xs font-semibold {{ $statutClasses }}">
                                    {{ $statutLibelle }}
                                </span>

                            </td>


                            {{-- Action --}}
                            <td class="px-5 py-4 text-right">

                                <a href="{{ route('agent.recensements.show', $recensement) }}"
                                   class="inline-flex items-center gap-2 rounded-xl border border-slate-200
                                          bg-white px-3.5 py-2 text-sm font-semibold text-slate-700
                                          shadow-sm transition
                                          hover:border-emerald-300 hover:bg-emerald-50
                                          hover:text-emerald-700">

                                    @if(in_array($statut, ['brouillon', 'en_cours']))

                                        Continuer

                                    @else

                                        Consulter

                                    @endif

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-4 w-4"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="1.8">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M5 12h14M13 6l6 6-6 6"/>
                                    </svg>

                                </a>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        {{-- ====================================================
            VERSION MOBILE
        ==================================================== --}}
        <div class="divide-y divide-slate-100 md:hidden">

            @foreach($recensements as $recensement)

                @php
                    $statut = $recensement->statut;

                    $statutLibelle = match ($statut) {
                        'brouillon' => 'Brouillon',
                        'en_cours' => 'En cours',
                        'termine' => 'Terminé',
                        'valide' => 'Validé',
                        default => ucfirst(str_replace('_', ' ', $statut)),
                    };

                    $statutClasses = match ($statut) {
                        'brouillon' => 'bg-slate-100 text-slate-700',
                        'en_cours' => 'bg-amber-100 text-amber-700',
                        'termine' => 'bg-blue-100 text-blue-700',
                        'valide' => 'bg-emerald-100 text-emerald-700',
                        default => 'bg-slate-100 text-slate-700',
                    };
                @endphp

                <a href="{{ route('agent.recensements.show', $recensement) }}"
                   class="block p-4 transition hover:bg-slate-50">

                    <div class="flex items-start justify-between gap-3">

                        <div class="flex min-w-0 items-center gap-3">

                            <div class="flex h-10 w-10 shrink-0 items-center justify-center
                                        rounded-xl bg-emerald-50 text-emerald-700">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M3 10.5L12 3l9 7.5"/>
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M5.5 9.5V21h13V9.5M9 21v-6h6v6"/>
                                </svg>

                            </div>

                            <div class="min-w-0">

                                <div class="font-semibold text-slate-800">
                                    Maison {{ $recensement->maison?->numeroMaison ?? '—' }}
                                </div>

                                <div class="mt-0.5 text-xs text-slate-500">
                                    {{ $recensement->maison?->village?->nomVillage ?? '—' }}
                                </div>

                            </div>

                        </div>

                        <span class="shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold {{ $statutClasses }}">
                            {{ $statutLibelle }}
                        </span>

                    </div>


                    <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3">

                        <div>

                            <div class="text-xs text-slate-400">
                                {{ $recensement->campagne?->libelle ?? '—' }}
                            </div>

                            @if($recensement->dateDerniereModification)
                                <div class="mt-1 text-xs text-slate-500">
                                    Mis à jour le
                                    {{ $recensement->dateDerniereModification->format('d/m/Y à H:i') }}
                                </div>
                            @endif

                        </div>

                        <span class="inline-flex items-center gap-1 text-sm font-semibold text-emerald-700">

                            @if(in_array($statut, ['brouillon', 'en_cours']))
                                Continuer
                            @else
                                Consulter
                            @endif

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-4 w-4"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.8">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M5 12h14M13 6l6 6-6 6"/>
                            </svg>

                        </span>

                    </div>

                </a>

            @endforeach

        </div>


        {{-- Pagination --}}
        <div class="border-t border-slate-200 px-5 py-4">
            {{ $recensements->links() }}
        </div>


    @else

        {{-- ====================================================
            AUCUN RÉSULTAT
        ==================================================== --}}
        <div class="px-6 py-16 text-center">

            <div class="mx-auto flex h-14 w-14 items-center justify-center
                        rounded-2xl bg-slate-100 text-slate-400">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-7 w-7"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.6">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6.586L19 7.586V19a2 2 0 01-2 2z"/>
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M14 3v5h5"/>
                </svg>

            </div>

            <h3 class="mt-5 text-base font-bold text-slate-800">
                Aucun recensement trouvé
            </h3>

            @if($search !== '')

                <p class="mx-auto mt-2 max-w-md text-sm text-slate-500">
                    Aucun recensement ne correspond à
                    « {{ $search }} ».
                </p>

                <a href="{{ route('agent.recensements.index') }}"
                   class="mt-5 inline-flex items-center rounded-xl bg-emerald-700
                          px-4 py-2.5 text-sm font-semibold text-white
                          transition hover:bg-emerald-800">
                    Effacer la recherche
                </a>

            @else

                <p class="mx-auto mt-2 max-w-md text-sm text-slate-500">
                    Vos recensements apparaîtront ici après le début
                    d'une collecte.
                </p>

                <a href="{{ route('agent.affectations') }}"
                   class="mt-5 inline-flex items-center gap-2 rounded-xl bg-emerald-700
                          px-4 py-2.5 text-sm font-semibold text-white
                          transition hover:bg-emerald-800">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                    </svg>

                    Voir mes affectations
                </a>

            @endif

        </div>

    @endif

</div>

</div>

@endsection
