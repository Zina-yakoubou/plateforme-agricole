@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-slate-50">

    <div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 lg:px-8">

        {{-- EN-TÊTE --}}
        <div class="mb-6">

            <div class="flex items-center gap-3">

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-100 text-amber-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M16.862 3.487a2.25 2.25 0 113.182 3.182L8.25 18.465 4 19.5l1.035-4.25L16.862 3.487z" />
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

        </div>


        {{-- FORMULAIRE --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            <form action="{{ route(
                'agent.parcelles.update',
                [$recensement, $menage, $exploitant, $exploitation, $parcelle]
            ) }}"
                  method="POST">

                @csrf
                @method('PUT')

                @include('parcelles._form', ['parcelle' => $parcelle])

                <div class="mt-6 flex items-center justify-end gap-3">

                    <a href="{{ route(
                        'agent.parcelles.index',
                        [$recensement, $menage, $exploitant]
                    ) }}"
                       class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Annuler
                    </a>

                    <button type="submit"
                            class="rounded-xl bg-[#006a4f] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#00583f]">
                        Enregistrer les modifications
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-slate-50">


<div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 lg:px-8">

    {{-- ============================================================
        EN-TÊTE
    ============================================================ --}}
    <div class="mb-6">

        <div class="flex items-center gap-3">

            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-100 text-amber-700">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-6 w-6"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M16.862 3.487a2.25 2.25 0 113.182 3.182L8.25 18.465 4 19.5l1.035-4.25L16.862 3.487z" />
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

    </div>


    {{-- ============================================================
        CONTEXTE
    ============================================================ --}}
    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-5">

        <div class="text-xs font-semibold uppercase tracking-wide text-emerald-600">
            Exploitant
        </div>

        <div class="mt-1 font-semibold text-slate-800">
            {{ $exploitant->nom }} {{ $exploitant->prenom }}
        </div>

        <div class="mt-4 rounded-xl border border-emerald-200 bg-white/70 p-4">

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
                        Exploitation automatiquement associée
                    </p>

                    <p class="mt-1 text-xs leading-5 text-slate-600">
                        L'exploitation de l'exploitant est gérée automatiquement.
                        Elle n'est pas modifiable depuis cette interface.
                    </p>
                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        FORMULAIRE
    ============================================================ --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

        <form action="{{ route(
            'agent.parcelles.update',
            [$recensement, $menage, $exploitant, $parcelle]
        ) }}"
              method="POST">

            @csrf
            @method('PUT')

            @include('parcelles._form', [
                'parcelle' => $parcelle
            ])

            <div class="mt-6 flex items-center justify-end gap-3">

                <a href="{{ route(
                    'agent.parcelles.index',
                    [$recensement, $menage, $exploitant]
                ) }}"
                   class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Annuler
                </a>

                <button type="submit"
                        class="rounded-xl bg-[#006a4f] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#00583f]">
                    Enregistrer les modifications
                </button>

            </div>

        </form>

    </div>

</div>


</div>

@endsection
