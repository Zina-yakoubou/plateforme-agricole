@extends('layouts.app')

@section('content')

<div class="space-y-6">

{{-- =========================================================
     EN-TÊTE
========================================================== --}}

<div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

    <div>

        <div class="flex items-center gap-2">

            <h1 class="text-2xl font-semibold tracking-tight text-[#212529]">
                Maisons du village
            </h1>

            <span
                class="rounded-full bg-[#e5f2ee]
                       px-3 py-1
                       text-xs font-semibold
                       text-[#006a4f]"
            >
                SIRA-Mô
            </span>

        </div>

        <p class="mt-1 text-sm text-[#6b7280]">

            Enregistrez et consultez les maisons du village

            <span class="font-semibold text-[#212529]">
                {{ $village->nom }}
            </span>.

        </p>

    </div>


    <a
        href="{{ route('villages.maisons.create', $village->idVillage) }}"
        class="inline-flex items-center justify-center gap-2
               rounded-lg bg-[#006a4f]
               px-4 py-2.5
               text-sm font-semibold text-white
               shadow-sm transition
               hover:bg-[#156c52]
               focus:outline-none
               focus:ring-2
               focus:ring-[#006a4f]/30"
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

        Ajouter une maison

    </a>

</div>


{{-- =========================================================
     INFORMATIONS DU VILLAGE
========================================================== --}}

<div class="grid grid-cols-1 gap-4 md:grid-cols-3">

    {{-- VILLAGE --}}

    <div class="rounded-lg border border-[#e5e7eb]
                bg-white p-5
                shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

        <div class="flex items-center gap-4">

            <div
                class="flex h-11 w-11 shrink-0
                       items-center justify-center
                       rounded-lg bg-[#e5f2ee]
                       text-[#006a4f]"
            >

                <svg
                    class="h-6 w-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.7"
                        d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"
                    />
                </svg>

            </div>

            <div>

                <p class="text-xs uppercase tracking-wide text-gray-400">
                    Village
                </p>

                <p class="mt-1 font-semibold text-[#212529]">
                    {{ $village->nom }}
                </p>

            </div>

        </div>

    </div>


    {{-- CANTON --}}

    <div class="rounded-lg border border-[#e5e7eb]
                bg-white p-5
                shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

        <div class="flex items-center gap-4">

            <div
                class="flex h-11 w-11 shrink-0
                       items-center justify-center
                       rounded-lg bg-gray-100
                       text-gray-600"
            >

                <svg
                    class="h-6 w-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.7"
                        d="M9 20l-5-2V6l5 2 6-2 5 2v12l-5-2-6 2z"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.7"
                        d="M9 8v12m6-14v12"
                    />
                </svg>

            </div>

            <div>

                <p class="text-xs uppercase tracking-wide text-gray-400">
                    Canton
                </p>

                <p class="mt-1 font-semibold text-[#212529]">
                    {{ $village->canton->nom ?? '—' }}
                </p>

            </div>

        </div>

    </div>


    {{-- NOMBRE DE MAISONS --}}

    <div class="rounded-lg border border-[#e5e7eb]
                bg-white p-5
                shadow-[0_1px_2px_rgba(0,0,0,0.05)]">

        <div class="flex items-center gap-4">

            <div
                class="flex h-11 w-11 shrink-0
                       items-center justify-center
                       rounded-lg bg-blue-50
                       text-blue-600"
            >

                <svg
                    class="h-6 w-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.7"
                        d="M3 21h18M5 21V8l7-5 7 5v13M9 21v-7h6v7"
                    />
                </svg>

            </div>

            <div>

                <p class="text-xs uppercase tracking-wide text-gray-400">
                    Maisons enregistrées
                </p>

                <p class="mt-1 text-xl font-bold text-[#212529]">
                    {{ $maisons->total() }}
                </p>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     MESSAGES
========================================================== --}}

@if(session('success'))

    <div
        class="flex items-start gap-3 rounded-lg
               border border-[#b7dfd2]
               bg-[#e5f2ee]
               px-4 py-3
               text-sm text-[#006a4f]"
    >

        <svg
            class="mt-0.5 h-5 w-5 shrink-0"
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

        <span>
            {{ session('success') }}
        </span>

    </div>

@endif


@if(session('error'))

    <div
        class="flex items-start gap-3 rounded-lg
               border border-red-200
               bg-red-50
               px-4 py-3
               text-sm text-red-700"
    >

        <svg
            class="mt-0.5 h-5 w-5 shrink-0"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
            />
        </svg>

        <span>
            {{ session('error') }}
        </span>

    </div>

@endif


@if($errors->any())

    <div
        class="flex items-start gap-3 rounded-lg
               border border-red-200
               bg-red-50
               px-4 py-3
               text-sm text-red-700"
    >

        <svg
            class="mt-0.5 h-5 w-5 shrink-0"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
            />
        </svg>

        <div>

            <p class="font-semibold">
                Une erreur est survenue.
            </p>

            <ul class="mt-1 list-inside list-disc">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    </div>

@endif


{{-- =========================================================
     LISTE DES MAISONS
========================================================== --}}

<div
    class="overflow-hidden rounded-lg
           border border-[#e5e7eb]
           bg-white
           shadow-[0_1px_2px_rgba(0,0,0,0.05)]"
>

    {{-- EN-TÊTE --}}

    <div
        class="flex flex-col gap-3
               border-b border-[#e5e7eb]
               px-6 py-5
               sm:flex-row
               sm:items-center
               sm:justify-between"
    >

        <div>

            <h2 class="text-base font-semibold text-[#212529]">
                Liste des maisons
            </h2>

            <p class="mt-1 text-xs text-gray-500">
                Maisons enregistrées dans le village
                {{ $village->nom }}
            </p>

        </div>

        <span
            class="rounded-full
                   bg-[#f8faf9]
                   px-3 py-1.5
                   text-sm text-gray-500"
        >

            <span class="font-semibold text-[#006a4f]">
                {{ $maisons->total() }}
            </span>

            maison(s)

        </span>

    </div>


    {{-- TABLEAU --}}

    <div class="overflow-x-auto">

        <table class="w-full min-w-[950px] text-sm">

            <thead class="bg-[#f8faf9]">

                <tr class="border-b border-[#e5e7eb]">

                    <th class="px-6 py-4 text-left
                               text-xs font-semibold
                               uppercase tracking-wide
                               text-gray-500">
                        N°
                    </th>

                    <th class="px-6 py-4 text-left
                               text-xs font-semibold
                               uppercase tracking-wide
                               text-gray-500">
                        Maison
                    </th>

                    <th class="px-6 py-4 text-left
                               text-xs font-semibold
                               uppercase tracking-wide
                               text-gray-500">
                        Chef de maison
                    </th>

                    <th class="px-6 py-4 text-left
                               text-xs font-semibold
                               uppercase tracking-wide
                               text-gray-500">
                        Adresse
                    </th>

                    <th class="px-6 py-4 text-center
                               text-xs font-semibold
                               uppercase tracking-wide
                               text-gray-500">
                        Localisation
                    </th>

                    <th class="px-6 py-4 text-center
                               text-xs font-semibold
                               uppercase tracking-wide
                               text-gray-500">
                        Ménages
                    </th>

                    <th class="px-6 py-4 text-right
                               text-xs font-semibold
                               uppercase tracking-wide
                               text-gray-500">
                        Actions
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-[#e5e7eb]">

                @forelse($maisons as $maison)

                    <tr class="group transition hover:bg-[#f8faf9]">

                        {{-- N° --}}

                        <td class="px-6 py-4 text-sm text-gray-400">

                            {{
                                $loop->iteration
                                + (
                                    ($maisons->currentPage() - 1)
                                    * $maisons->perPage()
                                )
                            }}

                        </td>


                        {{-- MAISON --}}

                        <td class="px-6 py-4">

                            <div class="flex items-center gap-3">

                                <div
                                    class="flex h-10 w-10 shrink-0
                                           items-center justify-center
                                           rounded-lg
                                           bg-[#e5f2ee]
                                           text-[#006a4f]"
                                >

                                    <svg
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.7"
                                            d="M3 21h18M5 21V8l7-5 7 5v13M9 21v-7h6v7"
                                        />
                                    </svg>

                                </div>

                                <div>

                                    <p class="font-semibold text-[#212529]">
                                        {{ $maison->numeroMaison }}
                                    </p>

                                    <p class="mt-1 font-mono text-[10px] text-gray-400">
                                        {{ $maison->uid }}
                                    </p>

                                </div>

                            </div>

                        </td>


                        {{-- CHEF DE MAISON --}}

                        <td class="px-6 py-4">

                            @if($maison->chefMaison)

                                <span class="font-medium text-[#434343]">
                                    {{ $maison->chefMaison }}
                                </span>

                            @else

                                <span class="text-gray-400">
                                    Non renseigné
                                </span>

                            @endif

                        </td>


                        {{-- ADRESSE --}}

                        <td class="px-6 py-4">

                            @if($maison->adresse)

                                <p class="max-w-xs truncate text-[#434343]">
                                    {{ $maison->adresse }}
                                </p>

                            @else

                                <span class="text-gray-400">
                                    —
                                </span>

                            @endif

                        </td>


                        {{-- LOCALISATION --}}

                        <td class="px-6 py-4 text-center">

                            @if(
                                $maison->latitude !== null &&
                                $maison->longitude !== null
                            )

                                <span
                                    class="inline-flex items-center gap-1.5
                                           rounded-full
                                           bg-[#e5f2ee]
                                           px-3 py-1
                                           text-xs font-semibold
                                           text-[#006a4f]"
                                >

                                    <span
                                        class="h-1.5 w-1.5
                                               rounded-full
                                               bg-[#006a4f]"
                                    ></span>

                                    Localisée

                                </span>

                            @else

                                <span
                                    class="inline-flex items-center gap-1.5
                                           rounded-full
                                           bg-gray-50
                                           px-3 py-1
                                           text-xs font-medium
                                           text-gray-500"
                                >

                                    <span
                                        class="h-1.5 w-1.5
                                               rounded-full
                                               bg-gray-400"
                                    ></span>

                                    Non localisée

                                </span>

                            @endif

                        </td>


                        {{-- MÉNAGES --}}

                        <td class="px-6 py-4 text-center">

                            <span
                                class="inline-flex min-w-[2.5rem]
                                       items-center justify-center
                                       rounded-full
                                       bg-gray-100
                                       px-3 py-1
                                       text-sm font-semibold
                                       text-gray-700"
                            >
                                {{ $maison->menages_count }}
                            </span>

                        </td>


                        {{-- ACTIONS --}}

                        <td class="px-6 py-4">

                            <div class="flex items-center justify-end gap-2">

                                {{-- VOIR --}}

                                <a
                                    href="{{ route('maisons.show', $maison->idMaison) }}"
                                    title="Voir la maison"
                                    class="inline-flex h-9 w-9
                                           items-center justify-center
                                           rounded-lg
                                           border border-[#e5e7eb]
                                           bg-white
                                           text-gray-600
                                           transition
                                           hover:border-[#006a4f]
                                           hover:bg-[#e5f2ee]
                                           hover:text-[#006a4f]"
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
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                        />

                                        <circle
                                            cx="12"
                                            cy="12"
                                            r="3"
                                        />

                                    </svg>

                                </a>


                                {{-- RECENSER --}}

                                <a
                                    href="{{ route('maisons.show', $maison->idMaison) }}"
                                    title="Recenser cette maison"
                                    class="inline-flex h-9 w-9
                                           items-center justify-center
                                           rounded-lg
                                           bg-[#006a4f]
                                           text-white
                                           transition
                                           hover:bg-[#156c52]
                                           focus:outline-none
                                           focus:ring-2
                                           focus:ring-[#006a4f]/30"
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
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z"
                                        />
                                    </svg>

                                </a>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="7"
                            class="px-6 py-16 text-center"
                        >

                            <div class="flex flex-col items-center">

                                <div
                                    class="mb-4 flex h-14 w-14
                                           items-center justify-center
                                           rounded-full
                                           bg-[#e5f2ee]
                                           text-[#006a4f]"
                                >

                                    <svg
                                        class="h-7 w-7"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.7"
                                            d="M3 21h18M5 21V8l7-5 7 5v13M9 21v-7h6v7"
                                        />
                                    </svg>

                                </div>

                                <p class="font-semibold text-[#212529]">
                                    Aucune maison enregistrée
                                </p>

                                <p class="mt-1 text-sm text-gray-500">
                                    Commencez par enregistrer la première
                                    maison de ce village.
                                </p>

                                <a
                                    href="{{ route('villages.maisons.create', $village->idVillage) }}"
                                    class="mt-4 inline-flex
                                           items-center gap-2
                                           rounded-lg
                                           bg-[#006a4f]
                                           px-4 py-2
                                           text-sm font-semibold
                                           text-white
                                           transition
                                           hover:bg-[#156c52]"
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

                                    Ajouter une maison

                                </a>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- PAGINATION --}}

    @if($maisons->hasPages())

        <div class="border-t border-[#e5e7eb] px-6 py-4">

            {{ $maisons->withQueryString()->links() }}

        </div>

    @endif

</div>

</div>

@endsection

