@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto">

    {{-- En-tête --}}
    <div class="flex justify-between items-center mb-6">

        <div>

            <h1 class="text-2xl font-bold text-slate-800">

                Détail de la commune

            </h1>

            <p class="mt-1 text-sm text-slate-500">

                Informations administratives de la commune.

            </p>

        </div>

        <div class="flex gap-3">

            <a href="{{ route('communes.edit',$commune) }}"
               class="px-5 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">

                Modifier

            </a>

            <a href="{{ route('communes.index') }}"
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

                    Nom

                </p>

                <p class="font-semibold text-lg">

                    {{ $commune->nom }}

                </p>

            </div>

            <div>

                <p class="text-sm text-gray-500">

                    Code

                </p>

                <p class="font-semibold text-lg">

                    {{ $commune->code }}

                </p>

            </div>

            <div>

                <p class="text-sm text-gray-500">

                    Préfecture

                </p>

                <p class="font-semibold">

                    {{ $commune->prefecture->nom }}

                </p>

            </div>

            <div>

                <p class="text-sm text-gray-500">

                    Région

                </p>

                <p class="font-semibold">

                    {{ $commune->prefecture->region->nom }}

                </p>

            </div>

            <div>

                <p class="text-sm text-gray-500">

                    Nombre de cantons

                </p>

                <p class="font-semibold text-green-600 text-lg">

                    {{ $commune->cantons->count() }}

                </p>

            </div>

            <div>

                <p class="text-sm text-gray-500">

                    Créée le

                </p>

                <p class="font-semibold">

                    {{ $commune->created_at?->format('d/m/Y') }}

                </p>

            </div>

        </div>

    </div>




    {{-- Liste des cantons --}}
    <div class="bg-white rounded-xl shadow mt-6 p-6">

        <div class="flex justify-between items-center mb-4">

            <h2 class="text-lg font-bold text-slate-800">

                Cantons rattachés

            </h2>

            <a href="{{ route('cantons.create', ['commune' => $commune->idCommune]) }}"
               class="px-5 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">

                + Ajouter un canton

            </a>

        </div>



        @if($commune->cantons->count())

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

                        </tr>

                    </thead>

                    <tbody class="divide-y">

                    @foreach($commune->cantons as $canton)

                        <tr>

                            <td class="px-5 py-3">

                                {{ $canton->nom }}

                            </td>

                            <td class="px-5 py-3">

                                {{ $canton->code }}

                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <p class="text-gray-500">

                Aucun canton associé à cette commune.

            </p>

        @endif

    </div>

</div>

@endsection