@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto">


    {{-- En-tête --}}
    <div class="flex justify-between items-center mb-6">


        <div>

            <h1 class="text-2xl font-bold text-slate-800">

                Détail du canton : {{ $canton->nom }}

            </h1>


            <p class="mt-1 text-sm text-slate-500">

                Informations administratives du canton.

            </p>


        </div>




        <div class="flex gap-3">


            <a href="{{ route(
                'cantons.edit',
                $canton->idCanton
            ) }}"

            class="px-5 py-2 rounded-lg bg-blue-500 
                   text-white hover:bg-blue-600">


                Modifier

            </a>





            <a href="{{ route('cantons.index') }}"

            class="px-5 py-2 rounded-lg bg-gray-300 
                   text-gray-700 hover:bg-gray-400">


                Retour

            </a>


        </div>


    </div>






    {{-- Informations canton --}}
    <div class="bg-white rounded-xl shadow p-6 mb-8">


        <h2 class="text-lg font-semibold text-slate-800 mb-5">

            Informations du canton

        </h2>



        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">


            <div>

                <p class="text-sm text-gray-500">

                    Nom

                </p>

                <p class="font-semibold text-slate-800">

                    {{ $canton->nom }}

                </p>

            </div>





            <div>

                <p class="text-sm text-gray-500">

                    Code

                </p>


                <p class="font-semibold text-slate-800">

                    {{ $canton->code }}

                </p>


            </div>





            <div>

                <p class="text-sm text-gray-500">

                    Commune

                </p>


                <p class="font-semibold text-slate-800">

                    {{ $canton->commune->nom ?? '-' }}

                </p>


            </div>



        </div>


    </div>








    {{-- Section villages --}}
    <div class="bg-white rounded-xl shadow p-6">


        <div class="flex justify-between items-center mb-5">


            <div>


                <h2 class="text-lg font-semibold text-slate-800">

                    Villages du canton

                </h2>


                <p class="text-sm text-gray-500">

                    Liste des villages rattachés à ce canton.

                </p>


            </div>





            {{-- Ajouter village --}}
            <a href="{{ route(
                'villages.create',
                [
                    'canton' => $canton->idCanton
                ]
            ) }}"

            class="px-5 py-2 rounded-lg bg-green-600 
                   text-white hover:bg-green-700">


                + Ajouter un village


            </a>



        </div>






        <div class="overflow-x-auto">


            <table class="w-full text-left">


                <thead class="bg-slate-100">


                    <tr>


                        <th class="px-5 py-3">

                            #

                        </th>


                        <th class="px-5 py-3">

                            Village

                        </th>


                        <th class="px-5 py-3">

                            Code

                        </th>


                    </tr>


                </thead>





                <tbody>


                    @forelse($canton->villages as $village)


                    <tr class="border-b">


                        <td class="px-5 py-3">

                            {{ $loop->iteration }}

                        </td>


                        <td class="px-5 py-3">

                            {{ $village->nom }}

                        </td>


                        <td class="px-5 py-3">

                            {{ $village->code }}

                        </td>


                    </tr>



                    @empty


                    <tr>


                        <td colspan="3"
                            class="px-5 py-5 text-center text-gray-500">


                            Aucun village enregistré.


                        </td>


                    </tr>


                    @endforelse



                </tbody>


            </table>


        </div>



    </div>


</div>


@endsection