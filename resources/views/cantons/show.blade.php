@extends('layouts.app')

@section('content')

{{-- ================================================= --}}
{{-- EN-TÊTE --}}
{{-- ================================================= --}}

<div class="flex justify-between items-center mb-6">


<div>

    <h1 class="text-2xl font-bold text-slate-800">
        Détail du canton
    </h1>

    <p class="mt-1 text-sm text-slate-500">
        Informations administratives du canton.
    </p>

</div>


<div class="flex gap-3">

    <a
        href="{{ route('cantons.edit', $canton) }}"
        class="px-5 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700"
    >
        Modifier
    </a>

    <a
        href="{{ route('cantons.index') }}"
        class="px-5 py-2 rounded-lg border border-gray-300 hover:bg-gray-100"
    >
        Retour
    </a>

</div>


</div>

{{-- ================================================= --}}
{{-- INFORMATIONS DU CANTON --}}
{{-- ================================================= --}}

<div class="bg-white rounded-xl shadow p-6">

```
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- Nom --}}
    <div>

        <p class="text-sm text-gray-500">
            Nom
        </p>

        <p class="font-semibold text-lg">
            {{ $canton->nom }}
        </p>

    </div>


    {{-- Code --}}
    <div>

        <p class="text-sm text-gray-500">
            Code
        </p>

        <p class="font-semibold text-lg">
            {{ $canton->code }}
        </p>

    </div>


    {{-- Commune --}}
    <div>

        <p class="text-sm text-gray-500">
            Commune
        </p>

        <p class="font-semibold">
            {{ $canton->commune->nom ?? '-' }}
        </p>

    </div>


    {{-- Région --}}
    <div>

        <p class="text-sm text-gray-500">
            Région
        </p>

        <p class="font-semibold">
            {{ $canton->commune->prefecture->region->nom ?? '-' }}
        </p>

    </div>


    {{-- Préfecture --}}
    <div>

        <p class="text-sm text-gray-500">
            Préfecture
        </p>

        <p class="font-semibold">
            {{ $canton->commune->prefecture->nom ?? '-' }}
        </p>

    </div>


    {{-- Nombre de villages --}}
    <div>

        <p class="text-sm text-gray-500">
            Nombre de villages
        </p>

        <p class="font-semibold text-green-600 text-lg">
            {{ $canton->villages->count() }}
        </p>

    </div>


    {{-- Date de création --}}
    <div>

        <p class="text-sm text-gray-500">
            Créé le
        </p>

        <p class="font-semibold">
            {{ $canton->created_at?->format('d/m/Y') ?? '-' }}
        </p>

    </div>

</div>

</div>

{{-- ================================================= --}}
{{-- LISTE DES VILLAGES --}}
{{-- ================================================= --}}

<div class="bg-white rounded-xl shadow mt-6 p-6">

```
<div class="flex justify-between items-center mb-4">

    <div>

        <h2 class="text-lg font-bold text-slate-800">
            Villages rattachés
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Villages appartenant à ce canton.
        </p>

    </div>


    <a
        href="{{ route('villages.create', ['canton' => $canton->idCanton]) }}"
        class="px-5 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700"
    >
        + Ajouter un village
    </a>

</div>


@if($canton->villages->count())

    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-slate-100">

                <tr>

                    <th class="px-5 py-3 text-left">
                        Nom
                    </th>

                    <th class="px-5 py-3 text-left">
                        Code
                    </th>

                    <th class="px-5 py-3 text-left">
                        Créé le
                    </th>

                    <th class="px-5 py-3 text-right">
                        Actions
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y">

                @foreach($canton->villages as $village)

                    <tr class="hover:bg-slate-50">

                        <td class="px-5 py-3 font-medium">
                            {{ $village->nom }}
                        </td>

                        <td class="px-5 py-3">
                            {{ $village->code ?? '-' }}
                        </td>

                        <td class="px-5 py-3">
                            {{ $village->created_at?->format('d/m/Y') ?? '-' }}
                        </td>

                        <td class="px-5 py-3 text-right">

                            <div class="flex justify-end gap-2">

                                @if(Route::has('villages.show'))

                                    <a
                                        href="{{ route('villages.show', $village) }}"
                                        class="px-3 py-1.5 rounded-lg
                                               bg-slate-100 text-slate-700
                                               hover:bg-slate-200"
                                    >
                                        Voir
                                    </a>

                                @endif

                                @if(Route::has('villages.edit'))

                                    <a
                                        href="{{ route('villages.edit', $village) }}"
                                        class="px-3 py-1.5 rounded-lg
                                               bg-blue-50 text-blue-600
                                               hover:bg-blue-100"
                                    >
                                        Modifier
                                    </a>

                                @endif

                            </div>

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

@else

    <div class="py-8 text-center">

        <p class="text-gray-500">
            Aucun village associé à ce canton.
        </p>

        <a
            href="{{ route('villages.create', ['canton' => $canton->idCanton]) }}"
            class="inline-block mt-3 text-green-600 hover:text-green-700 font-medium"
        >
            + Ajouter le premier village
        </a>

    </div>

@endif


</div>

@endsection
