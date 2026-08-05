@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto">


    <div class="mb-6">

        <h1 class="text-2xl font-bold text-slate-800">

            Modifier un canton

        </h1>


        <p class="mt-1 text-sm text-slate-500">

            Mettre à jour les informations du canton.

        </p>


    </div>




    <div class="bg-white rounded-xl shadow p-6">


        <form
            method="POST"
            action="{{ route(
                'cantons.update',
                $canton->idCanton
            ) }}">


            @csrf

            @method('PUT')


            @include('cantons._form')


        </form>


    </div>


</div>


@endsection