@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-slate-50">
    <div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 lg:px-8">

        {{-- EN-TÊTE --}}
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 113.182 3.182L8.25 18.465 4 19.5l1.035-4.25L16.862 3.487z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">
                        Modifier la parcelle
                    </h1>
                    <p class="mt-1 text-sm text-slate-500">
                        Parcelle {{ $parcelle->numeroParcelle }}
                    </p>
                </div>
            </div>

            <a href="{{ route('agent.exploitations.index', $exploitant) }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                Retour à l'exploitation
            </a>
        </div>

        {{-- CONTEXTE --}}
        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-5">
            <div class="text-xs font-semibold uppercase tracking-wide text-emerald-600">
                Exploitant
            </div>
            <div class="mt-1 font-semibold text-slate-800">
                {{ $exploitant->nom }} {{ $exploitant->prenom }}
            </div>
        </div>

        {{-- INCLUSION DU FORMULAIRE SEUL (Sans balise <form> englobante ici) --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            @include('parcelles._form', [
                'parcelle' => $parcelle,
                'isEdit' => true
            ])
        </div>

    </div>
</div>

@endsection