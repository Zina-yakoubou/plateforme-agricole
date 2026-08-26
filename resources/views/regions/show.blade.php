@extends('layouts.app')

@section('content')


<div class="max-w-5xl mx-auto">


    {{-- En-tête --}}
    <div class="flex items-center justify-between mb-6">


        <div>

            <h1 class="text-2xl font-bold text-slate-800">

                Détail de la région

            </h1>


            <p class="text-sm text-slate-500 mt-1">

                Informations administratives de la région.

            </p>

        </div>



        <div class="flex gap-3">


            {{-- <a href="{{ route('regions.edit',$region) }}"
               class="px-5 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">

                Modifier

            </a> --}}



            <a href="{{ route('regions.index') }}"
               class="px-5 py-2 rounded-lg border border-gray-300 hover:bg-gray-500">

                Retour

            </a>


        </div>


    </div>





    {{-- Informations --}}
    <div class="bg-white rounded-xl shadow p-6">


        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


            <div>

                <p class="text-sm text-gray-500">

                    Nom de la région

                </p>


                <p class="font-semibold text-slate-800 text-lg">

                    {{ $region->nom }}

                </p>


            </div>



            <div>

                <p class="text-sm text-gray-500">

                    Code

                </p>


                <p class="font-semibold text-slate-800 text-lg">

                    {{ $region->code }}

                </p>


            </div>



            <div>

                <p class="text-sm text-gray-500">

                    Nombre de préfectures

                </p>


                <p class="font-semibold text-green-600 text-lg">

                    {{ $region->prefectures->count() }}

                </p>


            </div>


            <div>

                <p class="text-sm text-gray-500">

                    Créée le

                </p>


                <p class="font-semibold text-slate-800">

                    {{ $region->created_at?->format('d/m/Y') }}

                </p>


            </div>


        </div>


    </div>





    {{-- Liste des préfectures --}}
    <div class="bg-white rounded-xl shadow mt-6 p-6">


        <div class="flex justify-between items-center mb-4">


            <h2 class="text-lg font-bold text-slate-800">

                Préfectures rattachées

            </h2>

           <a href="{{ route('prefectures.create', ['region' => $region->idRegion]) }}"
            class="px-5 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">

                + Ajouter une préfecture

            </a>


        </div>




        @if($region->prefectures->count())


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


                        @foreach($region->prefectures as $prefecture)


                        <tr>


                            <td class="px-5 py-3">

                                {{ $prefecture->nom }}

                            </td>



                            <td class="px-5 py-3">

                                {{ $prefecture->code }}

                            </td>


                        </tr>


                        @endforeach


                    </tbody>


                </table>


            </div>


        @else


            <p class="text-gray-500">

                Aucune préfecture associée à cette région.

            </p>


        @endif


    </div>


</div>


@endsection