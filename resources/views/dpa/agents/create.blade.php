@extends('layouts.app')

@section('page-title', 'Nouveau compte')

@section(
    'page-subtitle',
    'Créer un nouveau superviseur ou agent recenseur'
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
                    Nouveau compte
                </h1>

                <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                    DPA
                </span>

            </div>

            <p class="mt-1 text-sm text-slate-500">
                Créez le compte d'un superviseur ou d'un agent recenseur.
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

            <div class="flex gap-3">

                <svg
                    class="mt-0.5 h-5 w-5 shrink-0 text-red-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 9v4m0 4h.01M10.29 3.86l-7.82 14A2 2 0 004.21 21h15.58a2 2 0 001.74-3.14l-7.82-14a2 2 0 00-3.42 0z"
                    />
                </svg>

                <div>

                    <p class="text-sm font-semibold text-red-800">
                        Impossible d'enregistrer le compte
                    </p>

                    <ul class="mt-1 list-disc pl-5 text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>

            </div>

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
                Les informations saisies permettront à l'utilisateur de se connecter à SIRA-Mô.
            </p>

        </div>

        <form
            method="POST"
            action="{{ route('dpa.agents.store') }}"
            class="p-6"
        >

            @csrf

            @include('dpa.agents._form')

            {{-- =================================================
                ACTIONS
            ================================================== --}}
            <div class="mt-8 flex flex-col-reverse gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:justify-end">

                <a
                    href="{{ route('dpa.agents.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
                >
                    Annuler
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#006a4f] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#005a43] focus:outline-none focus:ring-2 focus:ring-[#006a4f] focus:ring-offset-2"
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
                            d="M12 4v16m8-8H4"
                        />
                    </svg>

                    Créer le compte

                </button>

            </div>

        </form>

    </div>

</div>

@endsection