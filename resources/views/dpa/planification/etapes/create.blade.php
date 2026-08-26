create.blade.php@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <div>
        <a href="{{ route('dpa.planification.etapes.index', $planification) }}"
           class="text-slate-500 hover:text-slate-700">
            ← Retour aux étapes
        </a>

        <h1 class="text-2xl font-bold text-slate-800 mt-2">
            Ajouter une étape de planification
        </h1>

        <p class="text-slate-500">
            {{ $planification->campagne->libelle }}
        </p>
    </div>

    <form method="POST"
          action="{{ route('dpa.planification.etapes.store', $planification) }}">

        @include('dpa.planification.etapes.form')

    </form>

</div>

@endsection