@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="mb-6">

        <h1 class="text-2xl font-bold text-slate-800">
            Modifier le ménage
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Modification des informations du ménage
            {{ $menage->numeroMenage }}.
        </p>

    </div>


    <div class="bg-white rounded-xl shadow p-6">

        <form
            method="POST"
            action="{{ route('menages.update', $menage) }}"
        >

            @method('PUT')

            @include('menages._form')

        </form>

    </div>

</div>

@endsection