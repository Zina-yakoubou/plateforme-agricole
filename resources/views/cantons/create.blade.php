@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto">


    <div class="mb-6">

        <h1 class="text-2xl font-bold text-slate-800">

            Ajouter un canton

        </h1>


        <p class="mt-1 text-sm text-slate-500">

            Enregistrer un nouveau canton dans une commune.

        </p>


    </div>




    <div class="bg-white rounded-xl shadow p-6">


        <form
            method="POST"
            action="{{ route('cantons.store') }}">


            @include('cantons._form')


        </form>


    </div>


</div>


@endsection