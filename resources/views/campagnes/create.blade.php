@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto">


    {{-- En-tête --}}
    <div class="mb-6">

        <h1 class="text-2xl font-bold text-slate-800">

            Ajouter une campagne

        </h1>


        <p class="mt-1 text-sm text-slate-500">

            Créer une nouvelle campagne de recensement.

        </p>

    </div>




    <div class="bg-white rounded-xl shadow p-6">


        <form method="POST"
              action="{{ route('campagnes.store') }}">

            @include('campagnes._form')


        </form>


    </div>


</div>


@endsection