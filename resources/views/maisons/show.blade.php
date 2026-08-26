@extends('layouts.app')

@section('content')

{{-- ================================================= --}}
{{-- EN-TÊTE --}}
{{-- ================================================= --}}

<div class="flex items-center justify-between mb-6">


<div>
    <h1 class="text-2xl font-bold text-slate-800">
        Détail de la maison
    </h1>

    <p class="text-sm text-slate-500 mt-1">
        Informations et rattachement administratif de la maison.
    </p>
</div>

<div class="flex gap-3">

    <a
        href="{{ route('villages.maisons.index', $maison->village) }}"
        class="px-5 py-2 rounded-lg border border-gray-300
               text-gray-700 hover:bg-gray-100"
    >
        Retour
    </a>

    <a
        href="{{ route('maisons.edit', $maison->idMaison) }}"
        class="px-5 py-2 rounded-lg bg-blue-600 text-white
               hover:bg-blue-700"
    >
        Modifier
    </a>

</div>


</div>

{{-- ================================================= --}}
{{-- INFORMATIONS PRINCIPALES --}}
{{-- ================================================= --}}

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">


{{-- Identification --}}
<div class="lg:col-span-2 bg-white rounded-xl shadow p-6">

    <h2 class="text-lg font-bold text-slate-800 mb-5">
        Identification de la maison
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- UID --}}
        <div>
            <p class="text-sm text-gray-500">
                Identifiant unique
            </p>

            <p class="mt-1 font-semibold text-slate-800 break-all">
                {{ $maison->uid }}
            </p>
        </div>

        {{-- Numéro --}}
        <div>
            <p class="text-sm text-gray-500">
                Numéro de maison
            </p>

            <p class="mt-1 font-semibold text-slate-800">
                {{ $maison->numeroMaison }}
            </p>
        </div>

        {{-- Adresse --}}
        <div class="md:col-span-2">

            <p class="text-sm text-gray-500">
                Adresse / indication
            </p>

            <p class="mt-1 font-semibold text-slate-800">
                {{ $maison->adresse ?: 'Aucune indication renseignée' }}
            </p>

        </div>

    </div>

</div>


{{-- Rattachement --}}
<div class="bg-white rounded-xl shadow p-6">

    <h2 class="text-lg font-bold text-slate-800 mb-5">
        Rattachement
    </h2>

    <div class="space-y-5">

        <div>
            <p class="text-sm text-gray-500">
                Village
            </p>

            <p class="mt-1 font-semibold text-slate-800">
                {{ $maison->village->nom ?? '-' }}
            </p>
        </div>

        <div>
            <p class="text-sm text-gray-500">
                Canton
            </p>

            <p class="mt-1 font-semibold text-slate-800">
                {{ $maison->village->canton->nom ?? '-' }}
            </p>
        </div>

        <div>
            <p class="text-sm text-gray-500">
                Commune
            </p>

            <p class="mt-1 font-semibold text-slate-800">
                {{ $maison->village->canton->commune->nom ?? '-' }}
            </p>
        </div>

    </div>

</div>


</div>

{{-- ================================================= --}}
{{-- MÉNAGES --}}
{{-- ================================================= --}}

<div class="mt-6 bg-white rounded-xl shadow overflow-hidden">


{{-- <div class="px-6 py-5 border-b flex items-center justify-between">

    <div>
        <h2 class="text-lg font-bold text-slate-800">
            Ménages de la maison
        </h2>

        <p class="text-sm text-slate-500 mt-1">
            Ménages actuellement enregistrés dans cette maison.
        </p>
    </div>

    <span class="px-3 py-1 rounded-full bg-slate-100
                 text-slate-700 text-sm font-medium">
        {{ $maison->menages->count() }}
        ménage(s)
    </span>

</div> --}}

<div class="px-6 py-5 border-b flex flex-col md:flex-row
            md:items-center md:justify-between gap-4">

    <div>
        <h2 class="text-lg font-bold text-slate-800">
            Ménages de la maison
        </h2>

        <p class="text-sm text-slate-500 mt-1">
            Ménages actuellement enregistrés dans cette maison.
        </p>
    </div>


    <div class="flex items-center gap-3">

        <span class="px-3 py-1 rounded-full bg-slate-100
                     text-slate-700 text-sm font-medium">

            {{ $maison->menages->count() }}
            ménage(s)

        </span>


        {{-- Ajouter un ménage --}}
        <a
            href="{{ route('maisons.menages.create', $maison) }}"
            class="inline-flex items-center gap-2
                   px-4 py-2 rounded-lg
                   bg-green-600 text-white
                   text-sm font-medium
                   hover:bg-green-700"
        >

            <svg
                class="w-4 h-4"
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

            <thead class="bg-slate-100">

                <tr>

                    <th class="px-6 py-4 text-left">
                        N°
                    </th>

                    <th class="px-6 py-4 text-left">
                        Ménage
                    </th>

                    <th class="px-6 py-4 text-left">
                        Référence
                    </th>

                    <th class="px-6 py-4 text-center">
                        Actions
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y">

                @foreach($maison->menages as $menage)

                    <tr class="hover:bg-gray-50">

                        <td class="px-6 py-4 text-slate-500">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-6 py-4 font-semibold text-slate-800">
                            {{ $menage->nom ?? 'Ménage '.$loop->iteration }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $menage->uid ?? $menage->reference ?? '-' }}
                        </td>

                        <td class="px-6 py-4 text-center">

                            @if(Route::has('menages.show'))

                                <a
                                    href="{{ route('menages.show', $menage->idMenage) }}"
                                    class="px-3 py-1 rounded-lg
                                           bg-slate-700 text-white
                                           hover:bg-slate-800"
                                >
                                    Voir
                                </a>

                            @else

                                <span class="text-gray-400">
                                    —
                                </span>

                            @endif

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

@else

    <div class="px-6 py-10 text-center text-gray-500">

        <p class="text-sm">
            Aucun ménage n'est encore enregistré dans cette maison.
        </p>

    </div>

@endif


</div>

{{-- ================================================= --}}
{{-- INFORMATIONS TECHNIQUES --}}
{{-- ================================================= --}}

<div class="mt-6 bg-slate-50 border border-slate-200
            rounded-xl p-5">


<h2 class="text-sm font-semibold text-slate-700 mb-3">
    Informations d'enregistrement
</h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

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

@endsection
