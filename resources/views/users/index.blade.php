@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">


    {{-- En-tête --}}
    <div class="flex items-center justify-between mb-6">

        <div>

            <h1 class="text-2xl font-bold text-slate-800">
                Gestion des utilisateurs
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Administration des comptes utilisateurs.
            </p>

        </div>


        <a href="{{ route('users.create') }}"
           class="px-5 py-2 rounded-lg bg-green-600 text-white font-semibold hover:bg-green-700">

            + Nouvel utilisateur

        </a>


    </div>



    {{-- Message succès --}}
    @if(session('success'))

        <div class="mb-5 rounded-lg bg-green-100 border border-green-300 px-4 py-3 text-green-700">

            {{ session('success') }}

        </div>

    @endif



    {{-- Carte principale --}}
    <div class="bg-white rounded-xl shadow">


        {{-- Recherche --}}
        <div class="p-5 border-b">


            <form method="GET" action="{{ route('users.index') }}">


                <div class="flex gap-3">


                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Rechercher par nom, email, login..."
                        class="flex-1 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">


                    <button
                        type="submit"
                        class="px-5 py-2 rounded-lg bg-slate-800 text-white hover:bg-slate-900">

                        Rechercher

                    </button>


                </div>


            </form>


        </div>



        {{-- Tableau --}}
        <div class="overflow-x-auto">


            <table class="w-full text-sm">


                <thead class="bg-slate-100">


                    <tr>


                        <th class="px-6 py-4 text-left">
                            Utilisateur
                        </th>


                        <th class="px-6 py-4 text-left">
                            Login
                        </th>


                        <th class="px-6 py-4 text-left">
                            Téléphone
                        </th>


                        <th class="px-6 py-4 text-left">
                            Rôle
                        </th>


                        <th class="px-6 py-4 text-center">
                            Statut
                        </th>


                        <th class="px-6 py-4 text-center">
                            Actions
                        </th>


                    </tr>


                </thead>



                <tbody class="divide-y">


                    @forelse($users as $user)


                    <tr class="hover:bg-gray-50">


                        {{-- utilisateur --}}
                        <td class="px-6 py-4">


                            <div class="font-semibold text-slate-800">

                                {{ $user->name }}

                            </div>


                            <div class="text-gray-500 text-xs">

                                {{ $user->email }}

                            </div>


                        </td>



                        {{-- login --}}
                        <td class="px-6 py-4">

                            {{ $user->login }}

                        </td>



                        {{-- téléphone --}}
                        <td class="px-6 py-4">

                            {{ $user->telephone ?? '-' }}

                        </td>



                        {{-- rôle --}}
                        <td class="px-6 py-4">

                            {{ $user->role->nom ?? '-' }}

                        </td>



                        {{-- statut --}}
                        <td class="px-6 py-4 text-center">


                            @if($user->statut)


                                <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-700">

                                    Actif

                                </span>


                            @else


                                <span class="px-3 py-1 rounded-full text-xs bg-red-100 text-red-700">

                                    Inactif

                                </span>


                            @endif


                        </td>



                        {{-- actions --}}
                        <td class="px-6 py-4">


                            <div class="flex justify-center gap-2">


                                <a href="{{ route('users.show',$user) }}"
                                   class="px-3 py-1 rounded-lg bg-blue-500 text-white hover:bg-blue-600">

                                    Voir

                                </a>



                                {{-- <form method="POST"
                                      action="{{ route('users.destroy',$user) }}"
                                      onsubmit="return confirm('Voulez-vous supprimer cet utilisateur ?')">


                                    @csrf

                                    @method('DELETE')


                                    <button
                                        class="px-3 py-1 rounded-lg bg-red-600 text-white hover:bg-red-700">

                                        Supprimer

                                    </button>


                                </form> --}}


                            </div>


                        </td>



                    </tr>


                    @empty


                    <tr>

                        <td colspan="6"
                            class="px-6 py-8 text-center text-gray-500">

                            Aucun utilisateur trouvé.

                        </td>

                    </tr>


                    @endforelse


                </tbody>


            </table>


        </div>



        {{-- Pagination --}}
        <div class="p-5">

            {{ $users->links() }}

        </div>



    </div>


</div>


@endsection