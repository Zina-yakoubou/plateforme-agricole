@extends('layouts.app')

@section('content')


<div class="max-w-7xl mx-auto">



    {{-- En-tête --}}
    <div class="flex items-center justify-between mb-6">


        <div>

            <h1 class="text-2xl font-bold text-slate-800">

                Gestion des préfectures

            </h1>


            <p class="text-sm text-slate-500 mt-1">

                Liste des préfectures administratives.

            </p>


        </div>




        <a href="{{ route('prefectures.create') }}"
           class="px-5 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">


            + Nouvelle préfecture


        </a>



    </div>





    {{-- Message succès --}}
    @if(session('success'))

        <div class="mb-5 rounded-lg bg-green-100 text-green-700 px-4 py-3">

            {{ session('success') }}

        </div>

    @endif





    {{-- Tableau --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">



        {{-- Recherche --}}
        <div class="p-5 border-b">


            <form method="GET"
                  action="{{ route('prefectures.index') }}">


                <div class="flex gap-3">


                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Rechercher une préfecture..."
                        class="flex-1 rounded-lg border-gray-300
                               focus:border-green-500 focus:ring-green-500">



                    <button
                        class="px-5 py-2 bg-slate-800 text-white rounded-lg">


                        Rechercher


                    </button>


                </div>


            </form>


        </div>






        <div class="overflow-x-auto">


            <table class="w-full text-sm">



                <thead class="bg-slate-100">


                    <tr>


                        <th class="px-6 py-4 text-left">
                            Nom
                        </th>



                        <th class="px-6 py-4 text-left">
                            Code
                        </th>



                        <th class="px-6 py-4 text-left">
                            Région
                        </th>



                        <th class="px-6 py-4 text-center">
                            Communes
                        </th>



                        <th class="px-6 py-4 text-center">
                            Actions
                        </th>


                    </tr>


                </thead>





                <tbody class="divide-y">



                @forelse($prefectures as $prefecture)



                    <tr class="hover:bg-gray-50">



                        <td class="px-6 py-4 font-semibold text-slate-800">


                            {{ $prefecture->nom }}


                        </td>





                        <td class="px-6 py-4">


                            {{ $prefecture->code }}


                        </td>





                        <td class="px-6 py-4">


                            {{ $prefecture->region->nom ?? '-' }}


                        </td>





                        <td class="px-6 py-4 text-center">


                            {{ $prefecture->communes->count() }}


                        </td>





                        <td class="px-6 py-4">


                            <div class="flex justify-center gap-2">



                                <a href="{{ route('prefectures.show',$prefecture) }}"
                                   class="px-3 py-1 rounded-lg bg-slate-700 text-white">


                                    Voir


                                </a>




                                <a href="{{ route('prefectures.edit',$prefecture) }}"
                                   class="px-3 py-1 rounded-lg bg-blue-600 text-white">


                                    Modifier


                                </a>



                            </div>


                        </td>



                    </tr>




                @empty



                    <tr>


                        <td colspan="5"
                            class="px-6 py-8 text-center text-gray-500">


                            Aucune préfecture trouvée.


                        </td>


                    </tr>



                @endforelse



                </tbody>



            </table>



        </div>






        {{-- Pagination --}}
        <div class="p-5">


            {{ $prefectures->links() }}


        </div>



    </div>



</div>


@endsection