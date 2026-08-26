@extends('layouts.app')

@section('content')

<div class="mx-auto max-w-3xl">

    <div class="mb-6">

        <h1 class="text-2xl font-bold text-slate-800">
            Modifier la campagne
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Mise à jour des informations de la campagne.
        </p>

    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">

        <form
            method="POST"
            action="{{ route('campagnes.update', $campagne) }}"
            class="space-y-5"
        >

            @method('PUT')

            @include('campagnes._form')

        </form>

    </div>

</div>

@endsection