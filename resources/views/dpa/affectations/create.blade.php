@extends('layouts.app')

@section('content')

<div class="mx-auto max-w-5xl space-y-6">

    {{-- ========================================================= --}}
    {{-- EN-TÊTE --}}
    {{-- ========================================================= --}}

    <div>

        <div class="flex items-center gap-3">

            <a
                href="{{ route('dpa.equipes.show', $equipe) }}"
                class="flex h-9 w-9 items-center justify-center rounded-xl
                       border border-gray-200 bg-white text-gray-500
                       transition hover:bg-gray-50 hover:text-gray-700"
            >

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M15 19l-7-7 7-7"/>

                </svg>

            </a>


            <div>

                <h1 class="text-2xl font-bold text-gray-900">
                    Nouvelle affectation
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Affectez l'équipe à un ou plusieurs villages planifiés.
                </p>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ERREURS --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="flex items-start gap-3 rounded-2xl border border-green-200
                    bg-green-50 px-4 py-4 text-sm text-green-700">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="mt-0.5 h-5 w-5 shrink-0"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 13l4 4L19 7"/>

            </svg>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif


    @if(session('error'))

        <div class="flex items-start gap-3 rounded-2xl border border-red-200
                    bg-red-50 px-4 py-4 text-sm text-red-700">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="mt-0.5 h-5 w-5 shrink-0"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 9v4m0 4h.01M10.29 3.86l-8.18 14A2 2 0 0 0 3.83 21h16.34a2 2 0 0 0 1.72-3.14l-8.18-14a2 2 0 0 0-3.42 0Z"/>

            </svg>

            <span>
                {{ session('error') }}
            </span>

        </div>

    @endif


    @if($errors->any())

        <div class="rounded-2xl border border-red-200 bg-red-50 p-4">

            <div class="flex items-center gap-2 text-sm font-semibold text-red-700">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 9v4m0 4h.01M10.29 3.86l-8.18 14A2 2 0 0 0 3.83 21h16.34a2 2 0 0 0 1.72-3.14l-8.18-14a2 2 0 0 0-3.42 0Z"/>

                </svg>

                Vérifiez les informations saisies.

            </div>


            <ul class="mt-2 list-disc space-y-1 pl-7 text-sm text-red-600">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- FORMULAIRE --}}
    {{-- ========================================================= --}}

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">


        <div class="border-b border-gray-200 bg-gray-50/70 px-6 py-5">

            <div class="flex items-center gap-3">

                <div class="flex h-10 w-10 items-center justify-center rounded-xl
                            bg-[#006a4f]/10 text-[#006a4f]">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.8"
                              d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2
                                 M9 5a3 3 0 0 1 6 0
                                 M9 5h6"/>

                    </svg>

                </div>


                <div>

                    <h2 class="font-semibold text-gray-900">
                        Informations de l'affectation
                    </h2>

                    <p class="mt-0.5 text-xs text-gray-500">
                        Sélectionnez la campagne, le canton puis les villages
                        prévus dans la planification préfectorale.
                    </p>

                </div>

            </div>

        </div>


        <form
            method="POST"
            action="{{ route('dpa.equipes.affectations.store', $equipe) }}"
        >

            @csrf


            <div class="p-6">

                @include('dpa.affectations._form')

            </div>


            <div class="flex items-center justify-end gap-3
                        border-t border-gray-200
                        bg-gray-50 px-6 py-4">

                <a
                    href="{{ route('dpa.equipes.show', $equipe) }}"
                    class="rounded-xl border border-gray-200 bg-white
                           px-5 py-2.5 text-sm font-semibold text-gray-600
                           transition hover:bg-gray-50"
                >
                    Annuler
                </a>


                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-xl
                           bg-[#006a4f] px-5 py-2.5 text-sm font-semibold
                           text-white transition hover:bg-[#00563f]"
                >

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M5 13l4 4L19 7"/>

                    </svg>

                    Enregistrer l'affectation

                </button>

            </div>

        </form>

    </div>

</div>

@endsection