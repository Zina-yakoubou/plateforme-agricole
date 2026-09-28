@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-slate-50">
    
<div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}
    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

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
                              d="M3 21h18M5 21V8l7-5 7 5v13M9 21v-6h6v6"/>
                    </svg>

                </div>

                <div>

                    <div class="flex items-center gap-2">

                        <h1 class="text-2xl font-semibold tracking-tight text-[#212529]">
                            Gestion des parcelles
                        </h1>

                        <span class="rounded-full bg-[#e5f2ee] px-3 py-1 text-xs font-semibold text-[#006a4f]">
                            SIRA-Mô
                        </span>

                    </div>

                    <p class="mt-1 text-sm text-gray-500">
                        Parcelles recensées pour cet exploitant agricole.
                    </p>

                </div>

            </div>

        </div>


        {{-- ACTIONS --}}
        <div class="flex flex-wrap items-center gap-2">

            <a
                href="{{ route(
                    'agent.recensements.menages.exploitants.index',
                    [$recensement, $menage]
                ) }}"
                class="inline-flex items-center justify-center gap-2
                       rounded-lg border border-[#e5e7eb]
                       bg-white px-4 py-2.5
                       text-sm font-semibold text-gray-600
                       transition hover:bg-gray-50
                       hover:text-[#006a4f]"
            >

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-4 w-4"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M15 19l-7-7 7-7"/>
                </svg>

                Retour aux exploitants

            </a>


            {{-- AJOUT PARCELLE --}}
            <a
                href="{{ route(
                    'agent.parcelles.create',
                    [$recensement, $menage, $exploitant]
                ) }}"
                class="inline-flex items-center justify-center gap-2
                       rounded-lg bg-[#006a4f]
                       px-4 py-2.5
                       text-sm font-semibold text-white
                       transition hover:bg-[#156c52]"
            >

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-4 w-4"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 4v16m8-8H4"/>
                </svg>

                Ajouter une parcelle

            </a>

        </div>

    </div>


    {{-- =========================================================
         MESSAGES
    ========================================================== --}}
    @if(session('success'))

        <div class="mb-6 rounded-lg border border-[#b7dfd2]
                    bg-[#e5f2ee] px-4 py-3
                    text-sm text-[#006a4f]">

            {{ session('success') }}

        </div>

    @endif


    @if(session('error'))

        <div class="mb-6 rounded-lg border border-red-200
                    bg-red-50 px-4 py-3
                    text-sm text-red-700">

            {{ session('error') }}

        </div>

    @endif


    {{-- =========================================================
         CONTEXTE
    ========================================================== --}}
    <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">

        {{-- Campagne --}}
        <div class="rounded-xl border border-[#e5e7eb] bg-white p-5 shadow-sm">

            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                Campagne
            </p>

            <p class="mt-2 font-semibold text-[#212529]">
                {{ $recensement->campagne->libelle ?? '—' }}
            </p>

        </div>


        {{-- Ménage --}}
        <div class="rounded-xl border border-[#e5e7eb] bg-white p-5 shadow-sm">

            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                Ménage
            </p>

            <p class="mt-2 font-semibold text-[#212529]">
                {{ $menage->numeroMenage ?? '—' }}
            </p>

        </div>


        {{-- Exploitant --}}
        <div class="rounded-xl border border-[#e5e7eb] bg-white p-5 shadow-sm">

            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                Exploitant
            </p>

            <p class="mt-2 font-semibold text-[#212529]">
                {{ $exploitant->nom }} {{ $exploitant->prenom }}
            </p>

            <p class="mt-1 text-xs text-gray-400">
                {{ $exploitant->uid }}
            </p>

        </div>


        {{-- Parcelles --}}
        <div class="rounded-xl border border-[#b7dfd2]
                    bg-[#e5f2ee] p-5 shadow-sm">

            <p class="text-xs font-semibold uppercase tracking-wide text-[#006a4f]">
                Parcelles recensées
            </p>

            <p class="mt-2 text-3xl font-bold text-[#006a4f]">
                {{ $parcelles->count() }}
            </p>

        </div>

    </div>


    {{-- =========================================================
         EXPLOITATION
    ========================================================== --}}
    <div class="mb-6 rounded-xl border border-[#e5e7eb] bg-white shadow-sm">

        <div class="border-b border-[#e5e7eb] px-6 py-5">

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <h2 class="text-base font-semibold text-[#212529]">
                        Exploitation
                    </h2>

                    <p class="mt-1 text-xs text-gray-500">
                        Exploitation agricole associée à cet exploitant.
                    </p>

                </div>

                <span class="rounded-full bg-gray-50 px-3 py-1.5 text-sm text-gray-500">

                    <span class="font-semibold text-[#006a4f]">
                        {{ $exploitations->count() }}
                    </span>

                    exploitation(s)

                </span>

            </div>

        </div>


        @if($exploitations->count())

            <div class="divide-y divide-slate-100">

                @foreach($exploitations as $exploitation)

                    <div class="flex flex-col gap-4 p-6 sm:flex-row sm:items-center sm:justify-between">

                        <div class="flex items-center gap-3">

                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 text-[#006a4f]">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M3 21h18M5 21V8l7-5 7 5v13M9 21v-6h6v6"/>
                                </svg>

                            </div>

                            <div>

                                <p class="font-semibold text-[#212529]">
                                    {{ $exploitation->nomExploitation ?? 'Exploitation agricole' }}
                                </p>

                                <p class="mt-1 text-xs text-gray-400">
                                    {{ $exploitation->codeExploitation
                                        ?? $exploitation->uid
                                        ?? '—' }}
                                </p>

                            </div>

                        </div>

                        <span class="text-sm text-gray-500">

                            <span class="font-semibold text-[#006a4f]">
                                {{ $exploitation->parcelles->count() }}
                            </span>

                            parcelle(s)

                        </span>

                    </div>

                @endforeach

            </div>

        @else

            <div class="px-6 py-6">

                <div class="rounded-xl border border-dashed border-emerald-200 bg-emerald-50/50 p-5">

                    <div class="flex items-start gap-3">

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.8">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M12 9v3m0 4h.01M10.29 3.86l-7.4 12.82A2 2 0 004.62 19.7h14.76a2 2 0 001.73-3.02l-7.4-12.82a2 2 0 00-3.42 0z"/>
                            </svg>

                        </div>

                        <div>

                            <p class="text-sm font-semibold text-[#212529]">
                                Aucune exploitation enregistrée pour le moment
                            </p>

                            <p class="mt-1 text-sm text-gray-500">
                                L'exploitation doit être créée depuis le module
                                de gestion des exploitations avant l'enregistrement
                                des parcelles.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        @endif

    </div>


    {{-- =========================================================
         PARCELLES
    ========================================================== --}}
    <div class="overflow-hidden rounded-xl
                border border-[#e5e7eb]
                bg-white shadow-sm">

        <div class="border-b border-[#e5e7eb] px-6 py-5">

            <h2 class="text-base font-semibold text-[#212529]">
                Liste des parcelles
            </h2>

            <p class="mt-1 text-xs text-gray-500">
                Toutes les parcelles recensées pour cet exploitant.
            </p>

        </div>


        @if($parcelles->count())

            <div class="overflow-x-auto">

                <table class="w-full min-w-[950px] text-sm">

                    <thead class="bg-[#f8faf9]">

                        <tr class="border-b border-[#e5e7eb]">

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                N°
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Parcelle
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Exploitation
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Superficie
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Faire-valoir
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Irrigation
                            </th>

                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @foreach($parcelles as $parcelle)

                            <tr class="transition hover:bg-slate-50">

                                <td class="px-6 py-4 text-gray-500">
                                    {{ $loop->iteration }}
                                </td>


                                <td class="px-6 py-4">

                                    <p class="font-semibold text-[#212529]">
                                        {{ $parcelle->numeroParcelle }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-400">
                                        {{ $parcelle->uid }}
                                    </p>

                                </td>


                                <td class="px-6 py-4">

                                    <p class="font-medium text-[#212529]">
                                        {{ $parcelle->exploitation->nomExploitation ?? 'Exploitation agricole' }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-400">
                                        {{ $parcelle->exploitation->codeExploitation
                                            ?? $parcelle->exploitation->uid
                                            ?? '—' }}
                                    </p>

                                </td>


                                <td class="px-6 py-4 font-medium text-[#212529]">
                                    {{ number_format($parcelle->superficie, 2, ',', ' ') }}
                                    ha
                                </td>


                                <td class="px-6 py-4 text-gray-600">
                                    {{ ucfirst(str_replace('_', ' ', $parcelle->modeFaireValoir)) }}
                                </td>


                                <td class="px-6 py-4 text-gray-600">
                                    {{ ucfirst(str_replace('_', ' ', $parcelle->modeIrrigation)) }}
                                </td>


                                <td class="px-6 py-4">

                                    <div class="flex justify-end gap-2">

                                        {{-- MODIFIER --}}
                                        <a
                                            href="{{ route(
                                                'agent.parcelles.edit',
                                                [
                                                    $recensement,
                                                    $menage,
                                                    $exploitant,
                                                    $parcelle
                                                ]
                                            ) }}"
                                            title="Modifier"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100"
                                        >

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-4 w-4"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke="currentColor"
                                                 stroke-width="1.8">
                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      d="M16.862 3.487a2.25 2.25 0 113.182 3.182L8.25 18.465 4 19.5l1.035-4.25L16.862 3.487z"/>
                                            </svg>

                                        </a>


                                        {{-- SUPPRIMER --}}
                                        <form
                                            action="{{ route(
                                                'agent.parcelles.destroy',
                                                [
                                                    $recensement,
                                                    $menage,
                                                    $exploitant,
                                                    $parcelle
                                                ]
                                            ) }}"
                                            method="POST"
                                            onsubmit="return confirm('Voulez-vous vraiment supprimer cette parcelle ?');"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                title="Supprimer"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-red-200 bg-red-50 text-red-600 hover:bg-red-100"
                                            >

                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="h-4 w-4"
                                                     fill="none"
                                                     viewBox="0 0 24 24"
                                                     stroke="currentColor"
                                                     stroke-width="1.8">
                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          d="M6 7h12M9 7V5h6v2m-8 0l.75 12h8.5L17 7M10 11v5m4-5v5"/>
                                                </svg>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="px-6 py-14 text-center">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-50 text-slate-400">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-7 w-7"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M3 21h18M5 21V8l7-5 7 5v13M9 21v-6h6v6"/>
                    </svg>

                </div>

                <h3 class="mt-4 text-sm font-semibold text-[#212529]">
                    Aucune parcelle enregistrée
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Commencez par enregistrer la première parcelle de cette exploitation.
                </p>

                <a
                    href="{{ route(
                        'agent.parcelles.create',
                        [$recensement, $menage, $exploitant]
                    ) }}"
                    class="mt-5 inline-flex items-center gap-2 rounded-lg bg-[#006a4f] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#156c52]"
                >

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-4 w-4"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 4v16m8-8H4"/>
                    </svg>

                    Ajouter une parcelle

                </a>

            </div>

        @endif

    </div>

</div>


</div>

@endsection