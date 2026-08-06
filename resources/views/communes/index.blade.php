@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- En-tête --}}
    <div class="flex items-center justify-between mb-6">

        <div>

            <h1 class="text-2xl font-bold text-slate-800">

                Gestion des communes

            </h1>

            <p class="mt-1 text-sm text-slate-500">

                Liste des communes administratives.

            </p>

        </div>

        <a href="{{ route('communes.create') }}"
           class="px-5 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">

            + Nouvelle commune

        </a>

    </div>



    @if(session('success'))

        <div class="mb-5 rounded-lg bg-green-100 text-green-700 px-4 py-3">

            {{ session('success') }}

        </div>

    @endif




    <div class="bg-white rounded-xl shadow overflow-hidden">

        {{-- Recherche --}}
        <div class="p-5 border-b">

            <form method="GET"
                  action="{{ route('communes.index') }}">

                <div class="flex gap-3">

                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Rechercher une commune..."
                        class="flex-1 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">

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
                            Préfecture
                        </th>

                        <th class="px-6 py-4 text-left">
                            Région
                        </th>

                        <th class="px-6 py-4 text-center">
                            Cantons
                        </th>

                        <th class="px-6 py-4 text-center">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y">

                @forelse($communes as $commune)

                    <tr class="hover:bg-gray-50">

                        <td class="px-6 py-4 font-semibold">

                            {{ $commune->nom }}

                        </td>

                        <td class="px-6 py-4">

                            {{ $commune->code }}

                        </td>

                        <td class="px-6 py-4">

                            {{ $commune->prefecture->nom }}

                        </td>

                        <td class="px-6 py-4">

                            {{ $commune->prefecture->region->nom }}

                        </td>

                        <td class="px-6 py-4 text-center">

                            {{ $commune->cantons_count }}
                        </td>

                        <td class="px-6 py-4">

                            <div class="flex justify-center gap-2">

                                <a href="{{ route('communes.show',$commune) }}"
                                   class="px-3 py-1 rounded-lg bg-slate-700 text-white hover:bg-slate-800">

                                    Voir

                                </a>

                                {{-- <a href="{{ route('communes.edit',$commune) }}"
                                   class="px-3 py-1 rounded-lg bg-blue-600 text-white hover:bg-blue-700">

                                    Modifier

                                </a> --}}

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6"
                            class="px-6 py-8 text-center text-gray-500">

                            Aucune commune trouvée.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>



        <div class="p-5">

            {{ $communes->links() }}

        </div>

    </div>

</div>

@endsection