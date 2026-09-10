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

        <p class="mt-1 text-sm text-slate-500">
            Enregistrer une nouvelle maison dans le village
            <strong>{{ $village->nom }}</strong>.
        </p>

    </div>

    {{-- ERREURS --}}
    @if($errors->any())

        <div class="mb-5 rounded-lg bg-red-100 px-4 py-3 text-red-700">

            <ul class="list-disc list-inside">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    {{-- FORMULAIRE --}}
    <div class="rounded-xl bg-white p-6 shadow">

        <form
            method="POST"
            action="{{ route('villages.maisons.store', $village) }}"
        >

            @include('maisons._form', [
                'village' => $village
            ])

        </form>

    </div>

</div>

@endsection