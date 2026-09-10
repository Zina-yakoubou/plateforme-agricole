@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- EN-TÊTE --}}
    <div class="mb-6">

        <a
            href="{{ route('villages.maisons.index', $maison->village) }}"
            class="text-sm text-slate-500 hover:text-green-600"
        >
            ← Retour aux maisons
        </a>

        <h1 class="mt-3 text-2xl font-bold text-slate-800">
            Modifier la maison
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Modification de la maison
            <strong>{{ $maison->numeroMaison }}</strong>.
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
    <div class="rounded-xl bg-white shadow">

        <div class="border-b px-6 py-4">

            <h2 class="font-semibold text-slate-800">
                Informations de la maison
            </h2>

        </div>

        <form
            method="POST"
            action="{{ route('maisons.update', $maison) }}"
            class="p-6"
        >

            @method('PUT')

            @include('maisons._form', [
                'maison' => $maison,
                'village' => $maison->village
            ])

        </form>

    </div>

</div>

@endsection