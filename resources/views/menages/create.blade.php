@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-slate-50 py-6">

    <div class="max-w-4xl mx-auto px-4">

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                Ajouter un ménage
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Recensement de la maison n°{{ $recensement->maison->numeroMaison }}
            </p>
        </div>

        <form
            action="{{ route('agent.recensements.menages.store', $recensement) }}"
            method="POST"
            class="bg-white border border-gray-200 rounded-lg p-6 space-y-5"
        >
            @csrf

            @include('menages._form', [
                'menage' => null,
                'prochainNumero' => $prochainNumero
            ])

            <div class="pt-4 flex justify-end gap-2">

                <a
                    href="{{ route('agent.recensements.show', $recensement) }}"
                    class="px-4 py-2 text-sm text-gray-600 hover:underline"
                >
                    Annuler
                </a>

                <button
                    type="submit"
                    class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700"
                >
                    Enregistrer le ménage
                </button>

            </div>
        </form>

    </div>

</div>

@endsection