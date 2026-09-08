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
            Informations et rattachement du ménage.
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

    <div
        class="mb-6 rounded-lg
               bg-green-50
               border border-green-200
               px-5 py-4
               text-sm text-green-700"
    >
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

    <div
        class="lg:col-span-2
               bg-white
               rounded-xl
               shadow
               p-6"
    >

        <h2 class="text-lg font-bold text-slate-800 mb-5">
            Identification du ménage
        </h2>


        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


            {{-- Numéro --}}
            <div>

                <p class="text-sm text-gray-500">
                    Numéro du ménage
                </p>

                <p class="mt-1 font-semibold text-slate-800">
                    {{ $menage->numeroMenage }}
                </p>

            </div>


            {{-- Statut --}}
            <div>

                <p class="text-sm text-gray-500">
                    Statut
                </p>

                @switch($menage->statut)

                    @case('brouillon')

                        <span
                            class="inline-flex mt-1
                                   px-3 py-1
                                   rounded-full
                                   bg-gray-100
                                   text-gray-700
                                   text-sm font-medium"
                        >
                            Brouillon
                        </span>

                        @break

                    @case('en_cours')

                        <span
                            class="inline-flex mt-1
                                   px-3 py-1
                                   rounded-full
                                   bg-blue-100
                                   text-blue-700
                                   text-sm font-medium"
                        >
                            En cours
                        </span>

                        @break

                    @case('terminee')

                        <span
                            class="inline-flex mt-1
                                   px-3 py-1
                                   rounded-full
                                   bg-green-100
                                   text-green-700
                                   text-sm font-medium"
                        >
                            Terminée
                        </span>

                        @break

                    @default

                        <span
                            class="inline-flex mt-1
                                   px-3 py-1
                                   rounded-full
                                   bg-gray-100
                                   text-gray-700
                                   text-sm font-medium"
                        >
                            {{ $menage->statut }}
                        </span>

                @endswitch

            </div>


            {{-- Nom --}}
            <div>

                <p class="text-sm text-gray-500">
                    Nom du chef de ménage
                </p>

                <p class="mt-1 font-semibold text-slate-800">
                    {{ $menage->nomChef }}
                </p>

            </div>


            {{-- Prénom --}}
            <div>

                <p class="text-sm text-gray-500">
                    Prénom du chef de ménage
                </p>

                <p class="mt-1 font-semibold text-slate-800">
                    {{ $menage->prenomChef ?: '-' }}
                </p>

            </div>


            {{-- Sexe --}}
            <div>

                <p class="text-sm text-gray-500">
                    Sexe du chef de ménage
                </p>

                <p class="mt-1 font-semibold text-slate-800">

                    @if($menage->sexeChef === 'M')
                        Homme
                    @elseif($menage->sexeChef === 'F')
                        Femme
                    @else
                        -
                    @endif

                </p>

            </div>


            {{-- Nombre total --}}
            <div>

                <p class="text-sm text-gray-500">
                    Nombre total de personnes
                </p>

                <p class="mt-1 font-semibold text-slate-800">

                    {{ $menage->nombrePersonnes }}

                    <span class="font-normal text-gray-500">
                        personne(s)
                    </span>

                </p>

            </div>

        </div>

    </div>


    {{-- ================================================= --}}
    {{-- MAISON --}}
    {{-- ================================================= --}}

    <div
        class="bg-white
               rounded-xl
               shadow
               p-6"
    >

        <h2 class="text-lg font-bold text-slate-800 mb-5">
            Maison
        </h2>


        <div class="space-y-5">


            {{-- Numéro maison --}}
            <div>

                <p class="text-sm text-gray-500">
                    Numéro de maison
                </p>

                <p class="mt-1 font-semibold text-slate-800">

                    {{ $menage->maison->numeroMaison ?? '-' }}

                </p>

            </div>


            {{-- Adresse --}}
            <div>

                <p class="text-sm text-gray-500">
                    Adresse / indication
                </p>

                <p class="mt-1 font-semibold text-slate-800">

                    {{ $menage->maison->adresse
                        ?: 'Aucune indication renseignée'
                    }}

                </p>

            </div>


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
{{-- COMPOSITION DU MÉNAGE --}}
{{-- ================================================= --}}

<div
    class="mt-6
           bg-white
           rounded-xl
           shadow
           p-6"
>

    <div class="mb-5">

        <h2 class="text-lg font-bold text-slate-800">
            Composition du ménage
        </h2>

        <p class="text-sm text-slate-500 mt-1">
            Répartition des personnes composant le ménage.
        </p>

    </div>


    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">


        {{-- Hommes --}}
        <div
            class="rounded-lg
                   bg-slate-50
                   border border-slate-200
                   p-4"
        >

            <p class="text-sm text-gray-500">
                Hommes
            </p>

            <p class="mt-1 text-2xl font-bold text-slate-800">
                {{ $menage->nombreHommes }}
            </p>

        </div>


        {{-- Femmes --}}
        <div
            class="rounded-lg
                   bg-slate-50
                   border border-slate-200
                   p-4"
        >

            <p class="text-sm text-gray-500">
                Femmes
            </p>

            <p class="mt-1 text-2xl font-bold text-slate-800">
                {{ $menage->nombreFemmes }}
            </p>

        </div>


        {{-- Garçons --}}
        <div
            class="rounded-lg
                   bg-slate-50
                   border border-slate-200
                   p-4"
        >

            <p class="text-sm text-gray-500">
                Garçons
            </p>

            <p class="mt-1 text-2xl font-bold text-slate-800">
                {{ $menage->nombreGarcons }}
            </p>

        </div>


        {{-- Filles --}}
        <div
            class="rounded-lg
                   bg-slate-50
                   border border-slate-200
                   p-4"
        >

            <p class="text-sm text-gray-500">
                Filles
            </p>

            <p class="mt-1 text-2xl font-bold text-slate-800">
                {{ $menage->nombreFilles }}
            </p>

        </div>

    </div>


    {{-- Total --}}
    <div
        class="mt-5
               rounded-lg
               bg-green-50
               border border-green-200
               px-5 py-4"
    >

        <div class="flex items-center justify-between">

            <p class="font-semibold text-green-800">
                Total du ménage
            </p>

            <p class="text-xl font-bold text-green-800">

                {{ $menage->nombrePersonnes }}

                <span class="text-sm font-normal">
                    personne(s)
                </span>

            </p>

        </div>

    </div>

</div>


{{-- ================================================= --}}
{{-- INFORMATIONS AGRICOLES --}}
{{-- ================================================= --}}

<div
    class="mt-6
           bg-white
           rounded-xl
           shadow
           p-6"
>

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


    @if($menage->possedeExploitation)

        <div
            class="p-4 rounded-lg
                   bg-green-50
                   border border-green-200"
        >

            <div
                class="flex flex-col
                       sm:flex-row
                       sm:items-center
                       sm:justify-between
                       gap-4"
            >

                <div>

                    <p class="font-semibold text-green-800">
                        Exploitation agricole déclarée
                    </p>

                    <p class="text-sm text-green-700 mt-1">
                        Ce ménage a déclaré posséder une exploitation agricole.
                        Vous pouvez enregistrer son ou ses exploitants.
                    </p>

                </div>


                @if(Route::has('menages.exploitants.create'))

                    <a
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
                    </a>

                @endif

            </div>

        </div>

    @else

        <div
            class="p-4 rounded-lg
                   bg-slate-50
                   border border-slate-200"
        >

            <div class="flex items-center gap-3">

                <span
                    class="inline-flex
                           px-3 py-1
                           rounded-full
                           bg-slate-200
                           text-slate-700
                           text-sm font-medium"
                >
                    Aucune exploitation déclarée
                </span>

                <p class="text-sm text-slate-600">
                    Aucune exploitation agricole n'a été déclarée
                    pour ce ménage.
                </p>

            </div>

        </div>

    @endif

</div>


{{-- ================================================= --}}
{{-- OBSERVATIONS --}}
{{-- ================================================= --}}

@if($menage->observations)

    <div
        class="mt-6
               bg-white
               rounded-xl
               shadow
               p-6"
    >

        <h2 class="text-lg font-bold text-slate-800 mb-4">
            Observations
        </h2>

        <p class="text-sm text-slate-700 whitespace-pre-line">
            {{ $menage->observations }}
        </p>

    </div>

@endif


{{-- ================================================= --}}
{{-- RATTACHEMENT --}}
{{-- ================================================= --}}

<div
    class="mt-6
           bg-white
           rounded-xl
           shadow
           p-6"
>

    <h2 class="text-lg font-bold text-slate-800 mb-5">
        Rattachement administratif
    </h2>


    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">


        {{-- Commune --}}
        <div>

            <p class="text-sm text-gray-500">
                Commune
            </p>

            <p class="mt-1 font-semibold text-slate-800">

                {{ $menage->maison->village->canton->commune->nom ?? '-' }}

            </p>

        </div>


        {{-- Canton --}}
        <div>

            <p class="text-sm text-gray-500">
                Canton
            </p>

            <p class="mt-1 font-semibold text-slate-800">

                {{ $menage->maison->village->canton->nom ?? '-' }}

            </p>

        </div>


        {{-- Village --}}
        <div>

            <p class="text-sm text-gray-500">
                Village
            </p>

            <p class="mt-1 font-semibold text-slate-800">

                {{ $menage->maison->village->nom ?? '-' }}

            </p>

        </div>


        {{-- Maison --}}
        <div>

            <p class="text-sm text-gray-500">
                Maison
            </p>

            <p class="mt-1 font-semibold text-slate-800">

                {{ $menage->maison->numeroMaison ?? '-' }}

            </p>

        </div>

    </div>

</div>


{{-- ================================================= --}}
{{-- INFORMATIONS D'ENREGISTREMENT --}}
{{-- ================================================= --}}

<div
    class="mt-6
           bg-slate-50
           border border-slate-200
           rounded-xl
           p-5"
>

    <h2
        class="text-sm
               font-semibold
               text-slate-700
               mb-3"
    >
        Informations d'enregistrement
    </h2>


    <div
        class="grid grid-cols-1
               md:grid-cols-2
               gap-4
               text-sm"
    >

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
