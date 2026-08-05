@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="bg-white rounded-xl shadow p-6">


        <h1 class="text-2xl font-bold text-slate-800 mb-6">

            Profil utilisateur

        </h1>


        <div class="space-y-4">


            <p>
                <strong>Nom :</strong>
                {{ $user->name }}
            </p>


            <p>
                <strong>Email :</strong>
                {{ $user->email }}
            </p>


            <p>
                <strong>Téléphone :</strong>
                {{ $user->telephone ?? '-' }}
            </p>


            <p>
                <strong>Login :</strong>
                {{ $user->login }}
            </p>


            <p>
                <strong>Rôle :</strong>
                {{ $user->role->nom }}
            </p>


            <p>
                <strong>Statut :</strong>

                @if($user->statut)

                    <span class="text-green-600">
                        Actif
                    </span>

                @else

                    <span class="text-red-600">
                        Désactivé
                    </span>

                @endif

            </p>


        </div>



        <div class="mt-8 flex gap-3">


            <a href="{{ route('users.edit',$user) }}"
               class="px-5 py-2 bg-blue-600 text-white rounded-lg">

                Modifier

            </a>



            <form method="POST"
                  action="{{ route('users.toggle-status',$user) }}">

                @csrf
                @method('PATCH')


                <button
                    class="px-5 py-2 rounded-lg 
                    {{ $user->statut 
                    ? 'bg-red-600' 
                    : 'bg-green-600' }} 
                    text-white">

                    {{ $user->statut 
                        ? 'Désactiver le compte' 
                        : 'Activer le compte' }}

                </button>


            </form>

            <a href="{{ route('users.index') }}"
                class="rounded-lg border border-gray-300 px-5 py-2 hover:bg-gray-100">

                Annuler

            </a>


        </div>


    </div>

</div>

@endsection