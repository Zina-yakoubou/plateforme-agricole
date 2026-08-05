@extends('layouts.app')

@section('content')


<div class="max-w-5xl mx-auto">


    {{-- En-tête --}}
    <div class="mb-6">


        <h1 class="text-2xl font-bold text-slate-800">

            Modifier la région

        </h1>


        <p class="mt-1 text-sm text-slate-500">

            Modifier les informations de la région :
            <strong>{{ $region->nom }}</strong>

        </p>


    </div>




    {{-- Formulaire --}}
    <div class="bg-white rounded-xl shadow p-6">


        <form method="POST"
              action="{{ route('regions.update', $region) }}">


            @csrf

            @method('PUT')


            @include('regions._form')


        </form>


    </div>


</div>


@endsection