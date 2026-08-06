@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto">


    {{-- En-tête --}}
    <div class="flex justify-between items-center mb-6">


        <div>

            <h1 class="text-2xl font-bold text-slate-800">

                Campagnes de recensement

            </h1>


            <p class="text-sm text-gray-500 mt-1">

                Gestion des campagnes RNA.

            </p>


        </div>



        <a href="{{ route('campagnes.create') }}"
           class="px-5 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">

            + Nouvelle campagne

        </a>


    </div>





    {{-- Recherche --}}
    <div class="bg-white rounded-xl shadow p-4 mb-6">


        <form method="GET"
              action="{{ route('campagnes.index') }}"
              class="flex gap-3">


            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Rechercher une campagne..."
                class="flex-1 rounded-lg border-gray-300">


            <button
                class="px-5 py-2 bg-slate-800 text-white rounded-lg">

                Rechercher

            </button>


        </form>


    </div>







    {{-- Tableau --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">


        <table class="w-full text-sm">


            <thead class="bg-slate-100">


            <tr>


                <th class="px-5 py-3 text-left">
                    Code RNA
                </th>


                <th class="px-5 py-3 text-left">
                    Libellé
                </th>


                <th class="px-5 py-3 text-left">
                    Période
                </th>


                <th class="px-5 py-3 text-left">
                    Statut
                </th>


                <th class="px-5 py-3 text-left">
                    Responsable
                </th>


                <th class="px-5 py-3 text-right">
                    Actions
                </th>


            </tr>


            </thead>





            <tbody class="divide-y">



            @forelse($campagnes as $campagne)



            <tr>


                <td class="px-5 py-3 font-semibold">

                    {{ $campagne->codeRNA }}

                </td>




                <td class="px-5 py-3">

                    {{ $campagne->libelle }}


                    @if($campagne->estOfficielle)

                        <span class="ml-2 px-2 py-1 text-xs bg-green-100 text-green-700 rounded">

                            Officielle

                        </span>

                    @endif


                </td>





                <td class="px-5 py-3">

                    {{ $campagne->dateDebut?->format('d/m/Y') }}

                    -

                    {{ $campagne->dateFin?->format('d/m/Y') ?? '...' }}

                </td>





                <td class="px-5 py-3">


                    @if($campagne->active)


                        <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-700">

                            Active

                        </span>


                    @else


                        <span class="px-3 py-1 rounded-full text-xs bg-gray-100 text-gray-600">

                            {{ $campagne->statut }}

                        </span>


                    @endif


                </td>





                <td class="px-5 py-3">

                    {{ $campagne->responsable?->name ?? 'Non défini' }}

                </td>






                <td class="px-5 py-3 text-right">


                    <a href="{{ route('campagnes.show',$campagne) }}"
                       class="text-green-600">

                        Voir

                    </a>


                    <a href="{{ route('campagnes.edit',$campagne) }}"
                       class="ml-3 text-blue-600">

                        Modifier

                    </a>




                    @if(!$campagne->active)

                    <form method="POST"
                          action="{{ route('campagnes.activate',$campagne) }}"
                          class="inline ml-3">


                        @csrf

                        @method('PATCH')


                        <button class="text-orange-600">

                            Activer

                        </button>


                    </form>


                    @endif


                </td>


            </tr>



            @empty


            <tr>

                <td colspan="6"
                    class="px-5 py-5 text-center text-gray-500">


                    Aucune campagne trouvée.


                </td>

            </tr>



            @endforelse



            </tbody>



        </table>


    </div>




    <div class="mt-5">

        {{ $campagnes->links() }}

    </div>


</div>

@endsection