@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <div>
        <a href="{{ route('dpa.planification.etapes.index', $planification) }}"
           class="text-slate-500 hover:text-slate-700">
            ← Retour aux étapes
        </a>

        <h1 class="text-2xl font-bold text-slate-800 mt-2">
            Modifier une étape
        </h1>

        <p class="text-slate-500">
            {{ $etape->libelle }}
        </p>
    </div>

    <form method="POST"
          action="{{ route('dpa.planification.etapes.update', $etape) }}">

        @method('PUT')

        @include('dpa.planification.etapes.form')

    </form>

</div>

@endsection