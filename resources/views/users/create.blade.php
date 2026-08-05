@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto">

    <div class="mb-6">

        <h1 class="text-2xl font-bold text-slate-800">
            Ajouter un utilisateur
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Créer un nouveau compte utilisateur dans le système.
        </p>

    </div>


    <div class="bg-white rounded-xl shadow p-6">


        <form method="POST" action="{{ route('users.store') }}">

            @include('users._form')


        </form>


    </div>


</div>

@endsection