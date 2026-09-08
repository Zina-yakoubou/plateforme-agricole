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
                Détail de la maison
            </h1>

            <span
                class="rounded-full bg-[#e5f2ee]
                       px-3 py-1
                       text-xs font-semibold
                       text-[#006a4f]"
            >
                SIRA-Mô
            </span>

        </div>

        <p class="mt-1 text-sm text-[#6b7280]">
            Informations et données de recensement de la maison.
        </p>

    </div>


    <div class="flex gap-3">

        <a
            href="{{ route('villages.maisons.index', $maison->village) }}"
            class="rounded-lg border border-gray-300
                   px-5 py-2
                   text-gray-700
                   hover:bg-gray-100"
        >
            Retour
        </a>

        <a
            href="{{ route('maisons.edit', $maison->idMaison) }}"
            class="rounded-lg bg-[#006a4f]
                   px-5 py-2
                   text-white
                   hover:bg-[#156c52]"
        >
            Modifier
        </a>

    </div>

</div>


{{-- =========================================================
     INFORMATIONS PRINCIPALES
========================================================== --}}

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">


{{-- =========================================================
     IDENTIFICATION
========================================================== --}}

<div class="rounded-xl border border-[#e5e7eb]
            bg-white p-6
            shadow-[0_1px_2px_rgba(0,0,0,0.05)]
            lg:col-span-2">

    <h2 class="mb-5 text-lg font-semibold text-[#212529]">
        Identification de la maison
    </h2>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

        {{-- NUMÉRO --}}

        <div>

            <p class="text-sm text-gray-500">
                Numéro de maison
            </p>

            <p class="mt-1 font-semibold text-[#212529]">
                {{ $maison->numeroMaison }}
            </p>

        </div>


        {{-- CHEF --}}

        <div>

            <p class="text-sm text-gray-500">
                Chef de maison
            </p>

            <p class="mt-1 font-semibold text-[#212529]">
                {{ $maison->chefMaison ?: 'Non renseigné' }}
            </p>

        </div>


        {{-- UID --}}

        <div class="md:col-span-2">

            <p class="text-sm text-gray-500">
                Identifiant technique
            </p>

            <p class="mt-1 break-all font-mono text-sm text-gray-600">
                {{ $maison->uid }}
            </p>

        </div>


        {{-- ADRESSE --}}

        <div class="md:col-span-2">

            <p class="text-sm text-gray-500">
                Adresse / indication
            </p>

            <p class="mt-1 font-semibold text-[#212529]">
                {{ $maison->adresse ?: 'Aucune indication renseignée' }}
            </p>

        </div>

    </div>

</div>


{{-- =========================================================
     RATTACHEMENT
========================================================== --}}

<div
    class="rounded-xl border border-[#e5e7eb]
           bg-white p-6
           shadow-[0_1px_2px_rgba(0,0,0,0.05)]"
>

    <h2 class="mb-5 text-lg font-semibold text-[#212529]">
        Rattachement
    </h2>

    <div class="space-y-5">

        <div>

            <p class="text-sm text-gray-500">
                Village
            </p>

            <p class="mt-1 font-semibold text-[#212529]">
                {{ $maison->village->nom ?? '—' }}
            </p>

        </div>


        <div>

            <p class="text-sm text-gray-500">
                Canton
            </p>

            <p class="mt-1 font-semibold text-[#212529]">
                {{ $maison->village->canton->nom ?? '—' }}
            </p>

        </div>


        <div>

            <p class="text-sm text-gray-500">
                Commune
            </p>

            <p class="mt-1 font-semibold text-[#212529]">
                {{ $maison->village->canton->commune->nom ?? '—' }}
            </p>

        </div>

    </div>

</div>

</div>


{{-- =========================================================
     LOCALISATION GPS
========================================================== --}}

<div
    class="overflow-hidden rounded-xl
           border border-[#e5e7eb]
           bg-white
           shadow-[0_1px_2px_rgba(0,0,0,0.05)]"
>

    <div class="border-b border-[#e5e7eb] px-6 py-5">

        <h2 class="text-lg font-semibold text-[#212529]">
            Localisation GPS
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Position géographique enregistrée pour cette maison.
        </p>

    </div>


    @if(
        $maison->latitude !== null &&
        $maison->longitude !== null
    )

        <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-3">

            {{-- LATITUDE --}}

            <div>

                <p class="text-sm text-gray-500">
                    Latitude
                </p>

                <p class="mt-1 font-mono font-semibold text-[#212529]">
                    {{ $maison->latitude }}
                </p>

            </div>


            {{-- LONGITUDE --}}

            <div>

                <p class="text-sm text-gray-500">
                    Longitude
                </p>

                <p class="mt-1 font-mono font-semibold text-[#212529]">
                    {{ $maison->longitude }}
                </p>

            </div>


            {{-- PRÉCISION --}}

            <div>

                <p class="text-sm text-gray-500">
                    Précision GPS
                </p>

                <p class="mt-1 font-semibold text-[#212529]">

                    @if($maison->precision_gps !== null)

                        {{ $maison->precision_gps }} m

                    @else

                        Non renseignée

                    @endif

                </p>

            </div>

        </div>

        @if($maison->date_localisation)

            <div class="border-t border-[#e5e7eb] bg-[#f8faf9] px-6 py-4">

                <p class="text-sm text-gray-500">
                    Date de localisation
                </p>

                <p class="mt-1 text-sm font-medium text-[#212529]">
                    {{ $maison->date_localisation->format('d/m/Y H:i:s') }}
                </p>

            </div>

        @endif

    @else

        <div class="px-6 py-10 text-center">

            <div
                class="mx-auto flex h-12 w-12
                       items-center justify-center
                       rounded-full
                       bg-gray-100
                       text-gray-500"
            >
                📍
            </div>

            <p class="mt-3 font-semibold text-[#212529]">
                Maison non localisée
            </p>

            <p class="mt-1 text-sm text-gray-500">
                Aucune position GPS n'a encore été enregistrée.
            </p>

        </div>

    @endif

</div>


{{-- =========================================================
     REPÈRE
========================================================== --}}

@if(
    $maison->type_repere ||
    $maison->repere_description
)

<div
    class="rounded-xl border border-[#e5e7eb]
           bg-white p-6
           shadow-[0_1px_2px_rgba(0,0,0,0.05)]"
>

    <h2 class="mb-5 text-lg font-semibold text-[#212529]">
        Repère proche
    </h2>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

        <div>

            <p class="text-sm text-gray-500">
                Type de repère
            </p>

            <p class="mt-1 font-semibold text-[#212529]">
                {{ $maison->type_repere ?: '—' }}
            </p>

        </div>


        <div>

            <p class="text-sm text-gray-500">
                Description
            </p>

            <p class="mt-1 font-semibold text-[#212529]">
                {{ $maison->repere_description ?: '—' }}
            </p>

        </div>

    </div>

</div>

@endif


{{-- =========================================================
     MÉNAGES
========================================================== --}}

<div
    class="overflow-hidden rounded-xl
           border border-[#e5e7eb]
           bg-white
           shadow-[0_1px_2px_rgba(0,0,0,0.05)]"
>

    <div
        class="flex flex-col gap-4
               border-b border-[#e5e7eb]
               px-6 py-5
               md:flex-row
               md:items-center
               md:justify-between"
    >

        <div>

            <h2 class="text-lg font-semibold text-[#212529]">
                Ménages de la maison
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Ménages actuellement enregistrés dans cette maison.
            </p>

        </div>


        <div class="flex items-center gap-3">

            <span
                class="rounded-full
                       bg-gray-100
                       px-3 py-1
                       text-sm font-medium
                       text-gray-700"
            >

                {{ $maison->menages->count() }}

                ménage(s)

            </span>


            <a
                href="{{ route('maisons.menages.create', $maison) }}"
                class="inline-flex items-center gap-2
                       rounded-lg
                       bg-[#006a4f]
                       px-4 py-2
                       text-sm font-medium
                       text-white
                       hover:bg-[#156c52]"
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
                        d="M12 4v16m8-8H4"
                    />
                </svg>

                Ajouter un ménage

            </a>

        </div>

    </div>


    @if($maison->menages->count())

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-[#f8faf9]">

                    <tr class="border-b border-[#e5e7eb]">

                        <th class="px-6 py-4 text-left
                                   text-xs font-semibold
                                   uppercase tracking-wide
                                   text-gray-500">
                            N°
                        </th>

                        <th class="px-6 py-4 text-left
                                   text-xs font-semibold
                                   uppercase tracking-wide
                                   text-gray-500">
                            Numéro du ménage
                        </th>

                        <th class="px-6 py-4 text-left
                                   text-xs font-semibold
                                   uppercase tracking-wide
                                   text-gray-500">
                            Chef de ménage
                        </th>

                        <th class="px-6 py-4 text-center
                                   text-xs font-semibold
                                   uppercase tracking-wide
                                   text-gray-500">
                            Personnes
                        </th>

                        <th class="px-6 py-4 text-center
                                   text-xs font-semibold
                                   uppercase tracking-wide
                                   text-gray-500">
                            Exploitation
                        </th>

                        <th class="px-6 py-4 text-right
                                   text-xs font-semibold
                                   uppercase tracking-wide
                                   text-gray-500">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-[#e5e7eb]">

                    @foreach($maison->menages as $menage)

                        <tr class="transition hover:bg-[#f8faf9]">

                            {{-- N° --}}

                            <td class="px-6 py-4 text-gray-400">
                                {{ $loop->iteration }}
                            </td>


                            {{-- NUMÉRO MÉNAGE --}}

                            <td class="px-6 py-4">

                                <span class="font-semibold text-[#212529]">
                                    {{ $menage->numeroMenage }}
                                </span>

                            </td>


                            {{-- CHEF --}}

                            <td class="px-6 py-4">

                                <div>

                                    <p class="font-medium text-[#212529]">

                                        {{ $menage->nomChef }}

                                        @if($menage->prenomChef)
                                            {{ $menage->prenomChef }}
                                        @endif

                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        Sexe : {{ $menage->sexeChef }}
                                    </p>

                                </div>

                            </td>


                            {{-- PERSONNES --}}

                            <td class="px-6 py-4 text-center">

                                <span
                                    class="inline-flex min-w-[2.5rem]
                                           items-center justify-center
                                           rounded-full
                                           bg-gray-100
                                           px-3 py-1
                                           text-sm font-semibold
                                           text-gray-700"
                                >
                                    {{
                                        $menage->nombreHommes
                                        + $menage->nombreFemmes
                                        + $menage->nombreGarcons
                                        + $menage->nombreFilles
                                    }}
                                </span>

                            </td>


                            {{-- EXPLOITATION --}}

                            <td class="px-6 py-4 text-center">

                                @if($menage->possedeExploitation)

                                    <span
                                        class="inline-flex items-center gap-1.5
                                               rounded-full
                                               bg-[#e5f2ee]
                                               px-3 py-1
                                               text-xs font-semibold
                                               text-[#006a4f]"
                                    >

                                        <span
                                            class="h-1.5 w-1.5
                                                   rounded-full
                                                   bg-[#006a4f]"
                                        ></span>

                                        Oui

                                    </span>

                                @else

                                    <span
                                        class="inline-flex items-center gap-1.5
                                               rounded-full
                                               bg-gray-50
                                               px-3 py-1
                                               text-xs font-medium
                                               text-gray-500"
                                    >

                                        Non

                                    </span>

                                @endif

                            </td>


                            {{-- ACTIONS --}}

                            <td class="px-6 py-4 text-right">

                                <a
                                    href="{{ route('menages.show', $menage->idMenage) }}"
                                    class="inline-flex items-center gap-2
                                           rounded-lg
                                           border border-[#e5e7eb]
                                           bg-white
                                           px-3 py-2
                                           text-sm font-medium
                                           text-gray-600
                                           transition
                                           hover:border-[#006a4f]
                                           hover:bg-[#e5f2ee]
                                           hover:text-[#006a4f]"
                                >

                                    Voir

                                </a>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @else

        <div class="px-6 py-10 text-center">

            <p class="text-sm text-gray-500">
                Aucun ménage n'est encore enregistré dans cette maison.
            </p>

            <a
                href="{{ route('maisons.menages.create', $maison) }}"
                class="mt-4 inline-flex items-center gap-2
                       rounded-lg
                       bg-[#006a4f]
                       px-4 py-2
                       text-sm font-semibold
                       text-white
                       hover:bg-[#156c52]"
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
                        d="M12 4v16m8-8H4"
                    />
                </svg>

                Ajouter le premier ménage

            </a>

        </div>

    @endif

</div>


{{-- =========================================================
     INFORMATIONS D'ENREGISTREMENT
========================================================== --}}

<div
    class="rounded-xl
           border border-slate-200
           bg-slate-50
           p-5"
>

    <h2 class="mb-3 text-sm font-semibold text-slate-700">
        Informations d'enregistrement
    </h2>

    <div class="grid grid-cols-1 gap-4 text-sm md:grid-cols-2">

        <div>

            <span class="text-gray-500">
                Enregistrée le :
            </span>

            <span class="font-medium text-slate-700">
                {{ $maison->created_at?->format('d/m/Y H:i') ?? '-' }}
            </span>

        </div>


        <div>

            <span class="text-gray-500">
                Dernière modification :
            </span>

            <span class="font-medium text-slate-700">
                {{ $maison->updated_at?->format('d/m/Y H:i') ?? '-' }}
            </span>

        </div>

    </div>

</div>

</div>

@endsection
