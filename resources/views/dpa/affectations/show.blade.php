@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- EN-TÊTE --}}

    <div class="flex flex-col gap-4
                sm:flex-row sm:items-center
                sm:justify-between">

        <div>

            <h1 class="text-2xl font-semibold text-[#212529]">
                Détail de l'affectation
            </h1>

            <p class="mt-1 font-mono text-sm text-[#006a4f]">
                {{ $affectation->reference }}
            </p>

        </div>


        <div class="flex items-center gap-2">

            {{-- <a
                href="{{ route(
                    'affectations.edit',
                    $affectation
                ) }}"
                class="rounded-lg bg-[#006a4f]
                       px-4 py-2.5
                       text-sm font-semibold
                       text-white
                       hover:bg-[#156c52]"
            >
                Modifier
            </a> --}}

            <a
                href="{{ route('affectations.index') }}"
                class="rounded-lg border border-[#e5e7eb]
                       bg-white px-4 py-2.5
                       text-sm font-semibold
                       text-gray-600
                       hover:bg-gray-50"
            >
                Retour
            </a>

        </div>

    </div>


    @if(session('success'))

        <div class="rounded-lg border border-[#b7dfd2]
                    bg-[#e5f2ee]
                    px-4 py-3
                    text-sm text-[#006a4f]">

            {{ session('success') }}

        </div>

    @endif


    {{-- INFORMATIONS --}}

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">


        {{-- ÉQUIPE --}}

        <div class="rounded-lg border border-[#e5e7eb]
                    bg-white p-6">

            <h2 class="text-base font-semibold text-[#212529]">
                Équipe affectée
            </h2>

            <div class="mt-5 space-y-4">

                <div>

                    <p class="text-xs text-gray-400">
                        Équipe
                    </p>

                    <p class="mt-1 font-semibold text-[#212529]">
                        {{ $affectation->equipe?->nom ?? '—' }}
                    </p>

                    <p class="text-xs text-[#006a4f]">
                        {{ $affectation->equipe?->reference ?? '—' }}
                    </p>

                </div>


                <div>

                    <p class="text-xs text-gray-400">
                        Superviseur
                    </p>

                    <p class="mt-1 text-sm text-[#434343]">

                        {{ $affectation->equipe?->superviseur?->name ?? '—' }}

                    </p>

                </div>


                <div>

                    <p class="text-xs text-gray-400">
                        Membres
                    </p>

                    <p class="mt-1 text-sm text-[#434343]">

                        {{ $affectation->equipe?->membres?->count() ?? 0 }}
                        agent(s)

                    </p>

                </div>

            </div>

        </div>


        {{-- CAMPAGNE --}}

        <div class="rounded-lg border border-[#e5e7eb]
                    bg-white p-6">

            <h2 class="text-base font-semibold text-[#212529]">
                Campagne
            </h2>

            <div class="mt-5">

                <p class="font-semibold text-[#212529]">

                    {{ $affectation->campagne?->libelle ?? '—' }}

                </p>

                <p class="mt-1 font-mono text-xs text-[#006a4f]">

                    {{ $affectation->campagne?->codeCampagne ?? '—' }}

                </p>

            </div>

        </div>


        {{-- TERRITOIRE --}}

        <div class="rounded-lg border border-[#e5e7eb]
                    bg-white p-6">

            <h2 class="text-base font-semibold text-[#212529]">
                Territoire
            </h2>

            <div class="mt-5 space-y-3">

                <div>

                    <p class="text-xs text-gray-400">
                        Préfecture
                    </p>

                    <p class="font-semibold text-[#212529]">

                        {{
                            $affectation
                                ->village
                                ?->canton
                                ?->commune
                                ?->prefecture
                                ?->nom
                            ?? '—'
                        }}

                    </p>

                </div>


                <div>

                    <p class="text-xs text-gray-400">
                        Commune
                    </p>

                    <p class="text-sm text-[#434343]">

                        {{
                            $affectation
                                ->village
                                ?->canton
                                ?->commune
                                ?->nom
                            ?? '—'
                        }}

                    </p>

                </div>


                <div>

                    <p class="text-xs text-gray-400">
                        Canton
                    </p>

                    <p class="text-sm text-[#434343]">

                        {{
                            $affectation
                                ->village
                                ?->canton
                                ?->nom
                            ?? '—'
                        }}

                    </p>

                </div>


                <div>

                    <p class="text-xs text-gray-400">
                        Village
                    </p>

                    <p class="font-semibold text-[#006a4f]">

                        {{ $affectation->village?->nom ?? '—' }}

                    </p>

                </div>

            </div>

        </div>


        {{-- PÉRIODE / STATUT --}}

        <div class="rounded-lg border border-[#e5e7eb]
                    bg-white p-6">

            <h2 class="text-base font-semibold text-[#212529]">
                Période et statut
            </h2>

            <div class="mt-5 space-y-4">

                <div>

                    <p class="text-xs text-gray-400">
                        Début
                    </p>

                    <p class="mt-1 text-sm">
                        {{ $affectation->dateDebut?->format('d/m/Y') }}
                    </p>

                </div>


                <div>

                    <p class="text-xs text-gray-400">
                        Fin
                    </p>

                    <p class="mt-1 text-sm">

                        {{
                            $affectation->dateFin
                                ? $affectation->dateFin->format('d/m/Y')
                                : 'Sans date de fin'
                        }}

                    </p>

                </div>


                <div>

                    <p class="text-xs text-gray-400">
                        Statut
                    </p>

                    <div class="mt-2">

                        @if($affectation->statut === 'active')

                            <span class="rounded-full bg-green-50
                                         px-3 py-1 text-xs
                                         font-semibold text-green-700">
                                Active
                            </span>

                        @elseif($affectation->statut === 'terminee')

                            <span class="rounded-full bg-gray-100
                                         px-3 py-1 text-xs
                                         font-semibold text-gray-600">
                                Terminée
                            </span>

                        @else

                            <span class="rounded-full bg-red-50
                                         px-3 py-1 text-xs
                                         font-semibold text-red-700">
                                Annulée
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- OBSERVATIONS --}}

    @if($affectation->observations)

        <div class="rounded-lg border border-[#e5e7eb]
                    bg-white p-6">

            <h2 class="text-base font-semibold text-[#212529]">
                Observations
            </h2>

            <p class="mt-3 text-sm leading-6 text-gray-600">
                {{ $affectation->observations }}
            </p>

        </div>

    @endif

</div>

@endsection