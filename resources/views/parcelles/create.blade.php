@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-slate-50">

    <div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 lg:px-8">

        {{-- ============================================================
            EN-TÊTE
        ============================================================ --}}
        <div class="mb-6">

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
                              d="M3 21h18M5 21V8l7-5 7 5v13M9 21v-6h6v6" />
                    </svg>

                </div>

                <div>
                    <h1 class="text-2xl font-bold text-slate-800">
                        Nouvelle parcelle
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Enregistrer une parcelle de l'exploitant
                    </p>
                </div>

            </div>

        </div>


        {{-- ============================================================
            CONTEXTE
        ============================================================ --}}
        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-5">

            <div>
                <div class="text-xs font-semibold uppercase tracking-wide text-emerald-600">
                    Exploitant
                </div>

                <div class="mt-1 font-semibold text-slate-800">
                    {{ $exploitant->nom }} {{ $exploitant->prenom }}
                </div>
            </div>

            {{-- <div class="mt-4 rounded-xl border border-emerald-200 bg-white/70 p-4">

                <div class="flex items-start gap-3">

                    <div class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0-5v-4m0-4h.01" />
                        </svg>

                    </div>

                    <div>
                        <p class="text-sm font-semibold text-slate-800">
                            Exploitation gérée automatiquement
                        </p>

                        <p class="mt-1 text-xs leading-5 text-slate-600">
                            L'exploitation de cet exploitant sera créée automatiquement
                            si elle n'existe pas encore. Vous n'avez rien à sélectionner.
                        </p>
                    </div>

                </div>

            </div> --}}

        </div>


        {{-- ============================================================
            FORMULAIRE
        ============================================================ --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            {{-- Le formulaire est déjà présent dans parcelles._form --}}
            @include('parcelles._form')

        </div>

    </div>

</div>

@endsection