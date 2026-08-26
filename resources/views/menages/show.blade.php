@extends('layouts.app')

@section('content')

{{-- ================================================= --}}
{{-- EN-TÊTE --}}
{{-- ================================================= --}}

<div class="flex items-center justify-between mb-6">

    <div>

        <h1 class="text-2xl font-bold text-slate-800">
            Détail du ménage
        </h1>

        <p class="text-sm text-slate-500 mt-1">
            Informations et rattachement administratif du ménage.
        </p>

    </div>


    <div class="flex gap-3">

        <a
            href="{{ route('maisons.show', $menage->maison) }}"
            class="px-5 py-2 rounded-lg
                   border border-gray-300
                   text-gray-700
                   hover:bg-gray-100"
        >
            Retour
        </a>


        <a
            href="{{ route('menages.edit', $menage) }}"
            class="px-5 py-2 rounded-lg
                   bg-blue-600
                   text-white
                   hover:bg-blue-700"
        >
            Modifier
        </a>

    </div>

</div>


{{-- ================================================= --}}
{{-- MESSAGE DE SUCCÈS --}}
{{-- ================================================= --}}

@if(session('success'))

    <div class="mb-6 rounded-lg
                bg-green-50
                border border-green-200
                px-5 py-4
                text-sm text-green-700">

        {{ session('success') }}

    </div>

@endif


{{-- ================================================= --}}
{{-- INFORMATIONS PRINCIPALES --}}
{{-- ================================================= --}}

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">


    {{-- ================================================= --}}
    {{-- IDENTIFICATION --}}
    {{-- ================================================= --}}

    <div class="lg:col-span-2
                bg-white
                rounded-xl
                shadow
                p-6">

        <h2 class="text-lg font-bold text-slate-800 mb-5">
            Identification du ménage
        </h2>


        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


            {{-- Numéro --}}
            <div>

                <p class="text-sm text-gray-500">
                    Numéro du ménage
                </p>

                <p class="mt-1
                          font-semibold
                          text-slate-800">

                    {{ $menage->numeroMenage }}

                </p>

            </div>


            {{-- Chef de ménage --}}
            <div>

                <p class="text-sm text-gray-500">
                    Chef de ménage
                </p>

                <p class="mt-1
                          font-semibold
                          text-slate-800">

                    {{ $menage->nomChef }}

                </p>

            </div>


            {{-- Nombre de personnes --}}
            <div>

                <p class="text-sm text-gray-500">
                    Nombre de personnes
                </p>

                <p class="mt-1
                          font-semibold
                          text-slate-800">

                    {{ $menage->nombrePersonnes }}

                    <span class="font-normal text-gray-500">
                        personne(s)
                    </span>

                </p>

            </div>


            {{-- Champ --}}
            <div>

                <p class="text-sm text-gray-500">
                    Possède un champ
                </p>

                @if($menage->aChamp)

                    <span class="inline-flex mt-1
                                 px-3 py-1
                                 rounded-full
                                 bg-green-100
                                 text-green-700
                                 text-sm font-medium">

                        Oui

                    </span>

                @else

                    <span class="inline-flex mt-1
                                 px-3 py-1
                                 rounded-full
                                 bg-slate-100
                                 text-slate-600
                                 text-sm font-medium">

                        Non

                    </span>

                @endif

            </div>

        </div>

    </div>


    {{-- ================================================= --}}
    {{-- MAISON --}}
    {{-- ================================================= --}}

    <div class="bg-white
                rounded-xl
                shadow
                p-6">

        <h2 class="text-lg font-bold text-slate-800 mb-5">
            Maison
        </h2>


        <div class="space-y-5">


            {{-- Numéro maison --}}
            <div>

                <p class="text-sm text-gray-500">
                    Numéro de maison
                </p>

                <p class="mt-1
                          font-semibold
                          text-slate-800">

                    {{ $menage->maison->numeroMaison ?? '-' }}

                </p>

            </div>


            {{-- Adresse --}}
            <div>

                <p class="text-sm text-gray-500">
                    Adresse / indication
                </p>

                <p class="mt-1
                          font-semibold
                          text-slate-800">

                    {{ $menage->maison->adresse
                        ?: 'Aucune indication renseignée' }}

                </p>

            </div>


            {{-- Lien maison --}}
            <div class="pt-2">

                <a
                    href="{{ route('maisons.show', $menage->maison) }}"
                    class="inline-flex
                           items-center
                           text-sm
                           font-medium
                           text-blue-600
                           hover:text-blue-800"
                >

                    Voir la maison →

                </a>

            </div>

        </div>

    </div>

</div>


{{-- ================================================= --}}
{{-- RATTACHEMENT ADMINISTRATIF --}}
{{-- ================================================= --}}

<div class="mt-6
            bg-white
            rounded-xl
            shadow
            p-6">

    <h2 class="text-lg font-bold text-slate-800 mb-5">
        Rattachement administratif
    </h2>


    <div class="grid grid-cols-1
                md:grid-cols-4
                gap-6">


        {{-- Commune --}}
        <div>

            <p class="text-sm text-gray-500">
                Commune
            </p>

            <p class="mt-1
                      font-semibold
                      text-slate-800">

                {{ $menage->maison->village->canton->commune->nom ?? '-' }}

            </p>

        </div>


        {{-- Canton --}}
        <div>

            <p class="text-sm text-gray-500">
                Canton
            </p>

            <p class="mt-1
                      font-semibold
                      text-slate-800">

                {{ $menage->maison->village->canton->nom ?? '-' }}

            </p>

        </div>


        {{-- Village --}}
        <div>

            <p class="text-sm text-gray-500">
                Village
            </p>

            <p class="mt-1
                      font-semibold
                      text-slate-800">

                {{ $menage->maison->village->nom ?? '-' }}

            </p>

        </div>


        {{-- Maison --}}
        <div>

            <p class="text-sm text-gray-500">
                Maison
            </p>

            <p class="mt-1
                      font-semibold
                      text-slate-800">

                {{ $menage->maison->numeroMaison ?? '-' }}

            </p>

        </div>

    </div>

</div>


{{-- ================================================= --}}
{{-- INFORMATIONS AGRICOLES --}}
{{-- ================================================= --}}

<div class="mt-6
            bg-white
            rounded-xl
            shadow
            p-6">

    <div class="flex items-center justify-between mb-5">

        <div>

            <h2 class="text-lg font-bold text-slate-800">
                Informations agricoles
            </h2>

            <p class="text-sm text-slate-500 mt-1">
                Situation agricole du ménage.
            </p>

        </div>

    </div>


    @if($menage->aChamp)

        {{-- Ménage agricole --}}
        <div class="p-4 rounded-lg
                    bg-green-50
                    border border-green-200">

            <div class="flex items-center justify-between gap-4">

                <div>

                    <p class="font-semibold text-green-800">
                        Ménage agricole
                    </p>

                    <p class="text-sm text-green-700 mt-1">
                        Ce ménage possède ou exploite un champ.
                        Vous pouvez maintenant enregistrer l'exploitant.
                    </p>

                </div>


                {{-- Enregistrer exploitant --}}
                {{-- <a
                    href="{{ route('menages.exploitants.create', $menage) }}"
                    class="shrink-0
                           px-5 py-2
                           rounded-lg
                           bg-green-600
                           text-white
                           font-medium
                           hover:bg-green-700"
                >
                    + Ajouter un exploitant
                </a> --}}
                <button class="shrink-0
                               px-5 py-2
                               rounded-lg
                               bg-green-600
                               text-white
                               font-medium
                               hover:bg-green-700">
                    + Ajouter un exploitant
                </button>

            </div>

        </div>

    @else

        {{-- Ménage non agricole --}}
        <div class="p-4 rounded-lg
                    bg-slate-50
                    border border-slate-200">

            <div class="flex items-center gap-3">

                <span class="inline-flex
                             px-3 py-1
                             rounded-full
                             bg-slate-200
                             text-slate-700
                             text-sm font-medium">

                    Aucun champ déclaré

                </span>

                <p class="text-sm text-slate-600">
                    Aucun exploitant agricole n'est à enregistrer
                    pour ce ménage.
                </p>

            </div>

        </div>

    @endif

</div>


{{-- ================================================= --}}
{{-- INFORMATIONS D'ENREGISTREMENT --}}
{{-- ================================================= --}}

<div class="mt-6
            bg-slate-50
            border border-slate-200
            rounded-xl
            p-5">

    <h2 class="text-sm
               font-semibold
               text-slate-700
               mb-3">

        Informations d'enregistrement

    </h2>


    <div class="grid grid-cols-1
                md:grid-cols-2
                gap-4
                text-sm">


        <div>

            <span class="text-gray-500">
                Enregistré le :
            </span>

            <span class="font-medium text-slate-700">

                {{ $menage->created_at?->format('d/m/Y H:i') ?? '-' }}

            </span>

        </div>


        <div>

            <span class="text-gray-500">
                Dernière modification :
            </span>

            <span class="font-medium text-slate-700">

                {{ $menage->updated_at?->format('d/m/Y H:i') ?? '-' }}

            </span>

        </div>

    </div>

</div>

@endsection