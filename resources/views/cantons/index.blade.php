@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">


    {{-- En-tête --}}
    <div class="flex justify-between items-center mb-6">


        <div>

            <h1 class="text-2xl font-bold text-slate-800">

                Gestion des cantons

            </h1>


            <p class="mt-1 text-sm text-slate-500">

                Liste des cantons enregistrés dans les communes.

            </p>

        </div>



        <a href="{{ route('cantons.create') }}"

           class="px-5 py-2 rounded-lg bg-green-600 
                  text-white hover:bg-green-700">

            + Ajouter un canton

        </a>


    </div>





    {{-- Message succès --}}
    @if(session('success'))

        <div class="mb-5 rounded-lg bg-green-100 
                    text-green-700 px-4 py-3">

            {{ session('success') }}

        </div>

    @endif






    {{-- Recherche --}}
    <div class="bg-white rounded-xl shadow p-5 mb-6">


        <form method="GET"
              action="{{ route('cantons.index') }}">


            <div class="flex gap-3">


                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"

                    placeholder="Rechercher un canton ou une commune..."

                    class="flex-1 rounded-lg border-gray-300
                           focus:border-green-500
                           focus:ring-green-500">


                <button
                    type="submit"

                    class="px-5 py-2 rounded-lg bg-slate-700
                           text-white hover:bg-slate-800">


                    Rechercher

                </button>


            </div>


        </form>


    </div>







    {{-- Tableau --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">


        <table class="w-full text-left">


            <thead class="bg-slate-100">


                <tr>


                    <th class="px-6 py-3 text-sm font-semibold text-slate-700">

                        #

                    </th>


                    <th class="px-6 py-3 text-sm font-semibold text-slate-700">

                        Canton

                    </th>


                    <th class="px-6 py-3 text-sm font-semibold text-slate-700">

                        Code

                    </th>


                    <th class="px-6 py-3 text-sm font-semibold text-slate-700">

                        Commune

                    </th>


                    <th class="px-6 py-3 text-sm font-semibold text-slate-700">

                        Actions

                    </th>


                </tr>


            </thead>





            <tbody>


                @forelse($cantons as $canton)


                <tr class="border-b hover:bg-slate-50">


                    <td class="px-6 py-4">

                        {{ $loop->iteration }}

                    </td>



                    <td class="px-6 py-4 font-medium text-slate-800">

                        {{ $canton->nom }}

                    </td>




                    <td class="px-6 py-4">

                        {{ $canton->code }}

                    </td>





                    <td class="px-6 py-4">

                        {{ $canton->commune->nom ?? '-' }}

                    </td>






                    <td class="px-6 py-4">


                        <div class="flex gap-2">


                            {{-- Voir --}}
                            <a href="{{ route(
                                'cantons.show',
                                $canton->idCanton
                            ) }}"

                            class="px-3 py-1 rounded-md 
                                   bg-blue-600 text-white
                                   hover:bg-blue-700">

                                Voir

                            </a>





                            {{-- Modifier --}}
                            {{-- <a href="{{ route(
                                'cantons.edit',
                                $canton->idCanton
                            ) }}"

                            class="px-3 py-1 rounded-md
                                   bg-yellow-500 text-white
                                   hover:bg-yellow-600">

                                Modifier

                            </a> --}}






                            {{-- Supprimer --}}
                            {{-- <form method="POST"

                                  action="{{ route(
                                      'cantons.destroy',
                                      $canton->idCanton
                                  ) }}">


                                @csrf

                                @method('DELETE')



                                <button type="submit"

                                    onclick="return confirm(
                                    'Voulez-vous supprimer ce canton ?'
                                    )"

                                    class="px-3 py-1 rounded-md
                                           bg-red-600 text-white
                                           hover:bg-red-700">


                                    Supprimer


                                </button>


                            </form> --}}



                        </div>


                    </td>


                </tr>



                @empty


                <tr>


                    <td colspan="5"
                        class="px-6 py-5 text-center text-slate-500">


                        Aucun canton trouvé.


                    </td>


                </tr>


                @endforelse



            </tbody>


        </table>


    </div>





    {{-- Pagination --}}
    <div class="mt-6">

        {{ $cantons->links() }}

    </div>



</div>


@endsection