@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- EN-TÊTE --}}
    <div class="mb-6">

        <a
            href="{{ route('villages.maisons.index', $village) }}"
            class="text-sm text-slate-500 hover:text-green-600"
        >
            ← Retour aux maisons du village
        </a>

        <h1 class="mt-3 text-2xl font-bold text-slate-800">
            Ajouter une maison
        </h1>

        <p class="text-sm text-slate-500 mt-1">
            Enregistrer une nouvelle maison dans le village
            <strong>{{ $village->nom }}</strong>.
        </p>

    </div>


    {{-- FORMULAIRE --}}

    <div class="bg-white rounded-xl shadow p-6">

        <form
            method="POST"
            action="{{ route('villages.maisons.store', $village) }}"
        >

            @include('maisons._form')

        </form>

    </div>

</div>

@endsection

