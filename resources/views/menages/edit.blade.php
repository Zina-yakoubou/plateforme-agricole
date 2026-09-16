@extends('layouts.agent')

@section('content')
<div class="max-w-2xl mx-auto py-6">

    <h1 class="text-xl font-semibold text-gray-800 mb-4">
        Modifier le ménage n°{{ $menage->numeroMenage }}
    </h1>

    <form action="{{ route('agent.recensements.menages.update', [$recensement, $menage]) }}" method="POST"
          class="bg-white border border-gray-200 rounded-lg p-6 space-y-5">
        @csrf
        @method('PUT')

        @include('agent.menages._form', ['menage' => $menage, 'prochainNumero' => null])

        <div class="pt-4 flex justify-end gap-2">
            <a href="{{ route('agent.recensements.menages.index', $recensement) }}"
               class="px-4 py-2 text-sm text-gray-600 hover:underline">Annuler</a>
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                Mettre à jour
            </button>
        </div>
    </form>

</div>
@endsection