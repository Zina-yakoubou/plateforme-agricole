@extends('layouts.app')

@section('title', 'Paramètres')
@section('page-title', 'Paramètres')
@section('page-subtitle', "Réglages généraux de l'application")

@section('content')
<div class="mx-auto max-w-4xl">

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-text-primary">Paramètres</h1>
        <p class="mt-1 text-sm text-text-secondary">
            Réglez le comportement général de l'application.
        </p>
    </div>

    @if(session('success'))
        <div class="mb-5 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-5 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            Certains champs sont invalides. Corrigez-les puis enregistrez.
        </div>
    @endif

    <form method="POST" action="{{ route('admin.parametres.update') }}">
        @csrf
        @method('PUT')

        @foreach($groupes as $g => $groupe)
            <section class="mb-6 rounded-xl border border-slate-200 bg-white">
                <div class="border-b border-slate-200 px-5 py-3">
                    <h2 class="font-semibold text-text-primary">{{ $groupe['label'] }}</h2>
                    <p class="text-xs text-text-secondary">{{ $groupe['description'] }}</p>
                </div>

                <div class="grid gap-5 p-5 md:grid-cols-2">
                    @foreach($groupe['champs'] as $c => $champ)
                        @php
                            $id      = "p_{$g}_{$c}";
                            $valeur  = old("parametres.$g.$c", $valeurs["$g.$c"]);
                            $integer = $champ['type'] === 'integer';
                        @endphp

                        <div>
                            <label for="{{ $id }}" class="mb-1 block text-sm font-medium text-text-primary">
                                {{ $champ['label'] }}
                            </label>

                            <div class="flex items-center gap-2">
                                <input
                                    id="{{ $id }}"
                                    name="parametres[{{ $g }}][{{ $c }}]"
                                    type="{{ $integer ? 'number' : 'text' }}"
                                    value="{{ $valeur }}"
                                    @if($integer) min="{{ $champ['min'] ?? 0 }}" max="{{ $champ['max'] ?? 100000 }}" @endif
                                    class="w-full rounded-md border px-3 py-2 text-sm
                                           focus:border-green-600 focus:outline-none focus:ring-1 focus:ring-green-600
                                           {{ $errors->has("parametres.$g.$c") ? 'border-red-400' : 'border-slate-300' }}"
                                >
                                @if(!empty($champ['unite']))
                                    <span class="whitespace-nowrap text-xs text-text-secondary">{{ $champ['unite'] }}</span>
                                @endif
                            </div>

                            @if(!empty($champ['aide']))
                                <p class="mt-1 text-xs text-text-secondary">{{ $champ['aide'] }}</p>
                            @endif

                            @error("parametres.$g.$c")
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach

        <div class="flex justify-end">
            <button type="submit"
                    class="rounded-md bg-green-700 px-5 py-2 text-sm font-semibold text-white hover:bg-green-800">
                Enregistrer les paramètres
            </button>
        </div>
    </form>
</div>
@endsection