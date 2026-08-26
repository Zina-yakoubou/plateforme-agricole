@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto">

    {{-- En-tête --}}
    <div class="flex items-center justify-between mb-6">

        <div>

            <h1 class="text-2xl font-bold text-slate-800">

                Détail du village

            </h1>

            <p class="text-sm text-slate-500 mt-1">

                Informations administratives du village.

            </p>

        </div>

        <div class="flex gap-3">

            <a href="{{ route('villages.edit',$village) }}"
               class="px-5 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">

                Modifier

            </a>

            {{-- <a href="{{ route('maisons.create',[
                    'village'=>$village->idVillage
                ]) }}"
               class="px-5 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">

                + Ajouter une maison

            </a> --}}

            <a href="{{ route('villages.index') }}"
               class="px-5 py-2 rounded-lg border border-gray-300 hover:bg-gray-100">

                Retour

            </a>

        </div>

    </div>





    {{-- Informations --}}
    <div class="bg-white rounded-xl shadow p-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>

                <p class="text-sm text-gray-500">

                    Nom du village

                </p>

                <p class="font-semibold text-slate-800 text-lg">

                    {{ $village->nom }}

                </p>

            </div>





            <div>

                <p class="text-sm text-gray-500">

                    Code

                </p>

                <p class="font-semibold text-slate-800 text-lg">

                    {{ $village->code }}

                </p>

            </div>





            <div>

                <p class="text-sm text-gray-500">

                    Canton

                </p>

                <p class="font-semibold text-green-700">

                    {{ $village->canton->nom }}

                </p>

            </div>





            <div>

                <p class="text-sm text-gray-500">

                    Commune

                </p>

                <p class="font-semibold">

                    {{ $village->canton->commune->nom }}

                </p>

            </div>





            <div>

                <p class="text-sm text-gray-500">

                    Préfecture

                </p>

                <p class="font-semibold">

                    {{ $village->canton->commune->prefecture->nom }}

                </p>

            </div>





            <div>

                <p class="text-sm text-gray-500">

                    Créé le

                </p>

                <p class="font-semibold">

                    {{ $village->created_at?->format('d/m/Y') }}

                </p>

            </div>

        </div>

    </div>







    {{-- Maisons --}}
    <div class="bg-white rounded-xl shadow mt-6 p-6">

        <div class="flex justify-between items-center mb-4">

            <h2 class="text-lg font-bold text-slate-800">

                Maisons rattachées

            </h2>

            {{-- <a href="{{ route('maisons.create',[
                    'village'=>$village->idVillage
                ]) }}"
               class="px-5 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">

                + Ajouter une maison

            </a> --}}

        </div>





        @if($village->maisons->count())

            <table class="w-full text-sm">

                <thead class="bg-slate-100">

                    <tr>

                        <th class="px-5 py-3 text-left">

                            Numéro

                        </th>

                        <th class="px-5 py-3 text-left">

                            Code

                        </th>

                        <th class="px-5 py-3 text-right">

                            Actions

                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y">

                    @foreach($village->maisons as $maison)

                        <tr>

                            <td class="px-5 py-3">

                                {{ $maison->numero }}

                            </td>

                            <td class="px-5 py-3">

                                {{ $maison->code }}

                            </td>

                            <td class="px-5 py-3 text-right">

                                <a href="{{ route('maisons.show',$maison) }}"
                                   class="text-green-600">

                                    Voir

                                </a>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @else

            <div class="text-center py-10">

                <p class="text-gray-500">

                    Aucune maison n'est encore enregistrée pour ce village.

                </p>

            </div>

        @endif

    </div>

</div>

@endsection