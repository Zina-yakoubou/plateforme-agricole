@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-slate-50">

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

        {{-- ============================================================
            EN-TÊTE
        ============================================================ --}}
        <div class="mb-6">

            <div class="mb-3 flex items-center gap-2 text-sm text-slate-500">

                <span>
                    Recensement
                </span>

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
                        d="M9 5l7 7-7 7"
                    />
                </svg>

                <span class="font-medium text-slate-700">
                    Maison {{ $recensement->maison->numeroMaison }}
                </span>

            </div>


            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                <div>

                    <div class="flex flex-wrap items-center gap-3">

                        <h1 class="text-2xl font-bold text-slate-800">
                            Recensement de la maison
                        </h1>

                        @php
                            $statut = $recensement->statut;
                        @endphp

                        @if($statut === 'brouillon')

                            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                Brouillon
                            </span>

                        @elseif($statut === 'en_cours')

                            <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                En cours
                            </span>

                        @elseif($statut === 'termine')

                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                Terminé
                            </span>

                        @elseif($statut === 'valide')

                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                Validé
                            </span>

                        @endif

                    </div>

                    <p class="mt-1 text-sm text-slate-500">
                        Collecte des informations de la maison pour la campagne
                        <span class="font-semibold text-slate-700">
                            {{ $recensement->campagne->libelle }}
                        </span>
                    </p>

                </div>


                {{-- Identifiant recensement --}}
                <div class="rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm">

                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Recensement
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">
                        #{{ $recensement->idRecensement }}
                    </p>

                </div>

            </div>

        </div>


        {{-- ============================================================
            INFORMATIONS DE TERRAIN
        ============================================================ --}}
        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

            {{-- Maison --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="flex items-start gap-3">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">

                        <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 10.5L12 3l9 7.5M5 9.5V21h14V9.5M9 21v-6h6v6"
                            />
                        </svg>

                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Maison
                        </p>

                        <p class="mt-1 font-bold text-slate-800">
                            N° {{ $recensement->maison->numeroMaison }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- Village --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="flex items-start gap-3">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700">

                        <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17.657 16.657L13.414 21a2 2 0 01-2.828 0l-4.243-4.343a8 8 0 1111.314 0z"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                        </svg>

                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Village
                        </p>

                        <p class="mt-1 font-bold text-slate-800">
                            {{ $recensement->maison->village->nom }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- Canton / Commune --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="flex items-start gap-3">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700">

                        <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 21a9 9 0 100-18 9 9 0 000 18z"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 7v5l3 2"
                            />
                        </svg>

                    </div>

                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Localisation
                        </p>

                        <p class="mt-1 font-bold text-slate-800">
                            {{ $recensement->maison->village->canton->nom }}
                        </p>

                        <p class="text-xs text-slate-500">
                            {{ $recensement->maison->village->canton->commune->nom }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- Agent --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="flex items-start gap-3">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-700">

                        <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 7a3 3 0 11-6 0 3 3 0 016 0z"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 21a7 7 0 0114 0"
                            />
                        </svg>

                    </div>

                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Agent recenseur
                        </p>

                        <p class="mt-1 font-bold text-slate-800">
                            {{ $recensement->agent->name ?? 'Agent' }}
                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- ============================================================
            PROGRESSION DU RECENSEMENT
        ============================================================ --}}
        <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <h2 class="text-lg font-bold text-slate-800">
                        Progression du recensement
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Suivez les différentes étapes de la collecte pour cette maison.
                    </p>

                </div>

                <div class="text-sm font-semibold text-slate-600">

                    @if($statut === 'brouillon')
                        Préparation

                    @elseif($statut === 'en_cours')
                        Collecte en cours

                    @elseif($statut === 'termine')
                        Collecte terminée

                    @elseif($statut === 'valide')
                        Recensement validé

                    @endif

                </div>

            </div>


            <div class="grid grid-cols-1 gap-3 md:grid-cols-4">

                {{-- Étape 1 --}}
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4">

                    <div class="flex items-center gap-3">

                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-600 text-white">

                            <svg
                                class="h-5 w-5"
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

                        </div>

                        <div>
                            <p class="text-sm font-semibold text-emerald-800">
                                Maison
                            </p>

                            <p class="text-xs text-emerald-700">
                                Identifiée
                            </p>
                        </div>

                    </div>

                </div>


                {{-- Étape 2 --}}
                <div class="rounded-xl border
                    {{ $statut !== 'brouillon'
                        ? 'border-blue-200 bg-blue-50'
                        : 'border-slate-200 bg-slate-50'
                    }}
                    p-4">

                    <div class="flex items-center gap-3">

                        <div class="flex h-9 w-9 items-center justify-center rounded-full
                            {{ $statut !== 'brouillon'
                                ? 'bg-blue-600 text-white'
                                : 'bg-slate-200 text-slate-500'
                            }}">

                            @if($statut !== 'brouillon')

                                <svg
                                    class="h-5 w-5"
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

                            @else

                                <span class="text-sm font-bold">
                                    2
                                </span>

                            @endif

                        </div>

                        <div>
                            <p class="text-sm font-semibold text-slate-800">
                                Ménages
                            </p>

                            <p class="text-xs text-slate-500">
                                Collecte
                            </p>
                        </div>

                    </div>

                </div>


                {{-- Étape 3 --}}
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">

                    <div class="flex items-center gap-3">

                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-200 text-slate-500">

                            <span class="text-sm font-bold">
                                3
                            </span>

                        </div>

                        <div>
                            <p class="text-sm font-semibold text-slate-700">
                                Exploitants
                            </p>

                            <p class="text-xs text-slate-500">
                                À renseigner
                            </p>
                        </div>

                    </div>

                </div>


                {{-- Étape 4 --}}
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">

                    <div class="flex items-center gap-3">

                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-200 text-slate-500">

                            <span class="text-sm font-bold">
                                4
                            </span>

                        </div>

                        <div>
                            <p class="text-sm font-semibold text-slate-700">
                                Exploitations
                            </p>

                            <p class="text-xs text-slate-500">
                                À renseigner
                            </p>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ============================================================
            BLOC PRINCIPAL : MÉNAGES
        ============================================================ --}}
        <div class="mb-6 rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-6 py-5">

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <h2 class="text-lg font-bold text-slate-800">
                            Ménages de la maison
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Enregistrez les ménages présents dans cette maison.
                        </p>

                    </div>

                    <div class="flex items-center gap-3">

                        <span class="rounded-xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">

                            {{ $recensement->menages->count() }}

                            ménage(s)

                        </span>


                        @if($statut === 'en_cours')

                            <a
                                href="{{ route('agent.recensements.menages.create', $recensement) }}"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700"
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
                                        stroke-width="2"
                                        d="M12 4v16m8-8H4"
                                    />
                                </svg>

                                Ajouter un ménage

                            </a>

                        @endif

                    </div>

                </div>

            </div>


            {{-- Liste des ménages --}}
            <div class="divide-y divide-slate-100">

                @forelse($recensement->menages as $menage)

                    <div class="px-6 py-5 transition hover:bg-slate-50">

                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                            <div class="flex items-start gap-4">

                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">

                                    <svg
                                        class="h-6 w-6"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-5a4 4 0 10-8 0 4 4 0 008 0z"
                                        />
                                    </svg>

                                </div>


                                <div>

                                    <div class="flex flex-wrap items-center gap-2">

                                        <h3 class="font-bold text-slate-800">
                                            Ménage {{ $menage->numeroMenage }}
                                        </h3>

                                        @if($menage->statut)

                                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                                                {{ ucfirst($menage->statut) }}
                                            </span>

                                        @endif

                                    </div>


                                    <p class="mt-1 text-sm text-slate-600">

                                        Chef de ménage :

                                        <span class="font-medium text-slate-800">
                                            {{ $menage->nomChef }}
                                            {{ $menage->prenomChef }}
                                        </span>

                                    </p>


                                    <div class="mt-2 flex flex-wrap gap-x-5 gap-y-1 text-sm text-slate-500">

                                        <span>
                                            Hommes :
                                            <strong class="font-medium text-slate-700">
                                                {{ $menage->nombreHommes }}
                                            </strong>
                                        </span>

                                        <span>
                                            Femmes :
                                            <strong class="font-medium text-slate-700">
                                                {{ $menage->nombreFemmes }}
                                            </strong>
                                        </span>

                                        <span>
                                            Garçons :
                                            <strong class="font-medium text-slate-700">
                                                {{ $menage->nombreGarcons }}
                                            </strong>
                                        </span>

                                        <span>
                                            Filles :
                                            <strong class="font-medium text-slate-700">
                                                {{ $menage->nombreFilles }}
                                            </strong>
                                        </span>

                                    </div>

                                </div>

                            </div>


                            <div class="flex shrink-0 items-center gap-2">

                                <a
                                    href="{{ route('agent.recensements.menages.edit', [
                                        'recensement' => $recensement,
                                        'menage' => $menage,
                                    ]) }}"
                                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
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
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.5-8.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 8.5-8.5z"
                                        />
                                    </svg>

                                    Modifier

                                </a>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="px-6 py-12 text-center">

                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400">

                            <svg
                                class="h-7 w-7"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-5a4 4 0 10-8 0 4 4 0 008 0z"
                                />
                            </svg>

                        </div>

                        <h3 class="mt-4 text-base font-semibold text-slate-800">
                            Aucun ménage enregistré
                        </h3>

                        <p class="mx-auto mt-1 max-w-md text-sm text-slate-500">
                            Commencez la collecte en enregistrant le premier ménage de cette maison.
                        </p>


                        @if($statut === 'en_cours')

                            <a
                                href="{{ route('agent.recensements.menages.create', $recensement) }}"
                                class="mt-5 inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700"
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
                                        stroke-width="2"
                                        d="M12 4v16m8-8H4"
                                    />
                                </svg>

                                Ajouter le premier ménage

                            </a>

                        @endif

                    </div>

                @endforelse

            </div>

        </div>


        {{-- ============================================================
            ACTIONS DU RECENSEMENT
        ============================================================ --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                <div>

                    <h2 class="text-lg font-bold text-slate-800">
                        État du recensement
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">

                        @if($statut === 'brouillon')
                            Le recensement est créé mais la collecte n'a pas encore commencé.

                        @elseif($statut === 'en_cours')
                            La collecte est en cours. Vous pouvez enregistrer et modifier les ménages.

                        @elseif($statut === 'termine')
                            La collecte de cette maison est terminée.

                        @elseif($statut === 'valide')
                            Ce recensement a été validé.

                        @endif

                    </p>

                </div>


                <div class="flex flex-col gap-2 sm:flex-row">

                    {{-- COMMENCER --}}
                    @if($statut === 'brouillon')

                        <form
                            method="POST"
                            action="{{ route('agent.recensements.mettre-en-cours', $recensement) }}"
                        >

                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 sm:w-auto"
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
                                        stroke-width="2"
                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.868v4.264a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>

                                Commencer la collecte

                            </button>

                        </form>

                    @endif


                    {{-- TERMINER --}}
                    @if($statut === 'en_cours')

                        <form
                            method="POST"
                            action="{{ route('agent.recensements.terminer', $recensement) }}"
                        >

                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 sm:w-auto"
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
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>

                                Terminer le recensement

                            </button>

                        </form>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection