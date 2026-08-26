@extends('layouts.app')

@section('content')


<div class="max-w-7xl mx-auto">


    {{-- En-tête --}}
    <div class="flex items-center justify-between mb-6">


        <div>

            <h1 class="text-2xl font-bold text-slate-800">
                Gestion des régions
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Liste des régions administratives.
            </p>

        </div>



        <a href="{{ route('regions.create') }}"
           class="px-5 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">

            + Nouvelle région

        </a>


    </div>




    {{-- Message succès --}}
    @if(session('success'))

        <div class="mb-5 rounded-lg bg-green-100 text-green-700 px-4 py-3">

            {{ session('success') }}

        </div>

    @endif





    


        {{-- Recherche --}}
        <div class="bg-white rounded-xl shadow p-4 mb-6">


            <form method="GET"
                  action="{{ route('regions.index') }}">


                <div class="flex gap-3">


                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Rechercher une région..."
                        class="flex-1 rounded-lg border-gray-300 
                               focus:border-green-500 focus:ring-green-500">



                    <button
                        class="px-5 py-2 bg-slate-800 text-white rounded-lg">

                        Rechercher

                    </button>


                </div>


            </form>


        </div>





        <div class="bg-white rounded-xl shadow overflow-hidden">


            <table class="w-full text-sm">

                <thead class="bg-slate-100">


                    <tr>


                        <th class="px-6 py-4 text-left">
                            Nom
                        </th>


                        <th class="px-6 py-4 text-left">
                            Code
                        </th>


                        <th class="px-6 py-4 text-center">
                            Préfectures
                        </th>


                        <th class="px-6 py-4 text-center">
                            Actions
                        </th>


                    </tr>


                </thead>




                <tbody class="divide-y">


                @forelse($regions as $region)


                    <tr class="hover:bg-gray-50">


                        <td class="px-6 py-4 font-semibold text-slate-800">

                            {{ $region->nom }}

                        </td>



                        <td class="px-6 py-4">

                            {{ $region->code }}

                        </td>



                        <td class="px-6 py-4 text-center">

                            {{ $region->prefectures_count ?? $region->prefectures->count() }}

                        </td>




                        <td class="px-6 py-4">


                            <div class="flex justify-center gap-2">


                                {{-- Voir --}}
                                {{-- <a href="{{ route('regions.show',$region) }}"
                                   class="px-3 py-1 rounded-lg bg-slate-700 text-white">

                                    Voir

                                </a> --}}

                                <a href="{{ route('regions.show', $region->idRegion) }}"
                                    class="inline-flex px-3 py-1 rounded-lg bg-slate-700 text-white hover:bg-slate-800">

                                    Voir

                                </a>


                                {{-- Modifier --}}
                                {{-- <a href="{{ route('regions.edit',$region) }}"
                                   class="px-3 py-1 rounded-lg bg-blue-600 text-white">

                                    Modifier

                                </a> --}}


                            </div>


                        </td>


                    </tr>


                @empty


                    <tr>

                        <td colspan="4"
                            class="px-6 py-8 text-center text-gray-500">

                            Aucune région trouvée.

                        </td>

                    </tr>


                @endforelse


                </tbody>


            </table>


        </div>





        {{-- Pagination --}}
        <div class="p-5">

            {{ $regions->links() }}

        </div>


    </div>


</div>


@endsection