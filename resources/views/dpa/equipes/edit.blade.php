@extends('layouts.app')

@section('content')

<div class="mx-auto max-w-5xl space-y-6">

    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}

    <div>

        <div class="flex items-center gap-3">

            <a
                href="{{ route('dpa.equipes.index') }}"
                class="inline-flex h-9 w-9 items-center justify-center
                       rounded-lg border border-gray-200
                       bg-white text-gray-500
                       transition hover:bg-gray-50
                       hover:text-gray-700"
                title="Retour"
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
                        stroke-width="1.8"
                        d="M15 19l-7-7 7-7"
                    />
                </svg>

            </a>


            <div>

                <h1 class="text-2xl font-semibold tracking-tight text-[#212529]">
                    Modifier l'équipe
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Modifiez le responsable ou la composition de l'équipe.
                </p>

            </div>

        </div>

    </div>


    {{-- =========================================================
         MESSAGES
    ========================================================== --}}

    @if(session('success'))

        <div class="rounded-lg border border-[#b7dfd2]
                    bg-[#e5f2ee] px-4 py-3
                    text-sm text-[#006a4f]">

            {{ session('success') }}

        </div>

    @endif


    @if($errors->any())

        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3">

            <p class="text-sm font-semibold text-red-700">
                Certaines informations sont incorrectes.
            </p>

            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-600">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
         FORMULAIRE
    ========================================================== --}}

    <form
        method="POST"
        action="{{ route('dpa.equipes.update', $equipe) }}"
    >

        <div class="overflow-hidden rounded-xl
                    border border-gray-200
                    bg-white shadow-sm">

            {{-- En-tête --}}

            <div class="border-b border-gray-200 px-6 py-5">

                <div class="flex items-center justify-between gap-4">

                    <div>

                        <h2 class="text-base font-semibold text-[#212529]">
                            {{ $equipe->nom }}
                        </h2>

                        <p class="mt-1 text-xs text-gray-500">
                            {{ $equipe->reference }}
                        </p>

                    </div>


                    <span
                        class="inline-flex rounded-full
                               px-3 py-1 text-xs font-semibold
                               {{ $equipe->statut === 'ACTIVE'
                                    ? 'bg-[#e5f2ee] text-[#006a4f]'
                                    : 'bg-gray-100 text-gray-600' }}"
                    >
                        {{ $equipe->statut === 'ACTIVE' ? 'Active' : 'Inactive' }}
                    </span>

                </div>

            </div>


            {{-- Formulaire partagé --}}

            <div class="px-6 py-7">

                @include('dpa.equipes._form', [
                    'equipe' => $equipe,
                    'superviseurs' => $superviseurs,
                    'agents' => $agents,
                ])

            </div>


            {{-- Actions --}}

            <div class="flex items-center justify-end gap-3
                        border-t border-gray-200
                        bg-gray-50
                        px-6 py-4">

                <a
                    href="{{ route('dpa.equipes.show', $equipe) }}"
                    class="rounded-lg border border-gray-300
                           bg-white px-4 py-2.5
                           text-sm font-medium text-gray-700
                           transition hover:bg-gray-100"
                >
                    Annuler
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center gap-2
                           rounded-lg
                           bg-[#006a4f]
                           px-5 py-2.5
                           text-sm font-semibold text-white
                           transition hover:bg-[#156c52]"
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
                            stroke-width="1.8"
                            d="M11 5h2M5 19h2l10-10-2-2L5 17v2z"
                        />
                    </svg>

                    Enregistrer

                </button>

            </div>

        </div>

    </form>

</div>

@endsection