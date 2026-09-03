@extends('layouts.app')

@section('page-title', 'Modifier le compte')

@section(
    'page-subtitle',
    'Modifier les informations d’un superviseur ou d’un agent recenseur'
)

@section('content')

<div class="mx-auto max-w-5xl space-y-6">

    {{-- =========================================================
        EN-TÊTE
    ========================================================== --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>

            <div class="flex items-center gap-2">

                <h1 class="text-2xl font-semibold tracking-tight text-slate-800">
                    Modifier le compte
                </h1>

                <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                    {{ $agent->role?->nom }}
                </span>

            </div>

            <p class="mt-1 text-sm text-slate-500">
                {{ $agent->name }}
            </p>

        </div>

        <a
            href="{{ route('dpa.agents.index') }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
        >
            <svg
                class="h-4 w-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M10 19l-7-7m0 0l7-7m-7 7h18"
                />
            </svg>

            Retour
        </a>

    </div>


    {{-- =========================================================
        ERREURS
    ========================================================== --}}
    @if ($errors->any())

        <div class="rounded-xl border border-red-200 bg-red-50 p-4">

            <p class="text-sm font-semibold text-red-800">
                Vérifiez les informations saisies.
            </p>

            <ul class="mt-1 list-disc pl-5 text-sm text-red-700">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
        FORMULAIRE
    ========================================================== --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 bg-slate-50 px-6 py-5">

            <h2 class="text-base font-semibold text-slate-800">
                Informations du compte
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Modifiez uniquement les informations nécessaires.
            </p>

        </div>

        <form
            method="POST"
            action="{{ route('dpa.agents.update', $agent) }}"
            class="p-6"
        >

            @csrf
            @method('PUT')

            @include('dpa.agents._form')

            <div class="mt-8 flex flex-col-reverse gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:justify-end">

                <a
                    href="{{ route('dpa.agents.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
                >
                    Annuler
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#006a4f] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#005a43]"
                >

                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>

                    Enregistrer les modifications

                </button>

            </div>

        </form>

    </div>

</div>

@endsection