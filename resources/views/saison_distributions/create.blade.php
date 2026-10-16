@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-slate-50">

    <div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 lg:px-8">

        {{-- ============================================================
            EN-TÊTE
        ================================================================ --}}
        <div class="mb-6 flex items-center justify-between">

            <div class="flex items-center gap-3">

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>

                </div>

                <div>
                    <h1 class="text-2xl font-bold text-slate-800">
                        Nouvelle saison de distribution
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Configurer et planifier une nouvelle saison de distribution d'intrants
                    </p>
                </div>

            </div>

            <a href="{{ route('saison-distributions.index') }}" 
               class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 transition">
                Retour à la liste
            </a>

        </div>

        {{-- ============================================================
            FORMULAIRE
        ================================================================ --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <p class="font-semibold">Veuillez corriger les erreurs ci-dessous :</p>
                    <ul class="mt-1 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('saison-distributions.store') }}" method="POST">
                @include('saison_distributions._form', ['submitButtonText' => 'Enregistrer la saison'])
            </form>

        </div>

    </div>

</div>

@endsection