@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- En-tête --}}
    <div class="mb-6">

        <h1 class="text-2xl font-bold text-slate-800">
            Nouveau village
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Enregistrer un nouveau village dans le système.
        </p>

    </div>

    {{-- Carte --}}
    <div class="bg-white rounded-xl shadow p-6">

        <form action="{{ route('villages.store') }}"
              method="POST">

            @include('villages._form')

        </form>

    </div>

</div>

@endsection