@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-slate-50">

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

        {{-- ============================================================
            EN-TÊTE
        ============================================================= --}}
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h1 class="text-2xl font-bold text-slate-800">
                    Ménages
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Ménages recensés dans la maison
                    <span class="font-semibold text-slate-700">
                        n°{{ $recensement->maison->numeroMaison }}
                    </span>
                </p>
            </div>

            <a
                href="{{ route('agent.recensements.menages.create', $recensement) }}"
                class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700"
            >
                + Ajouter un ménage
            </a>

        </div>


        {{-- ============================================================
            INFORMATIONS DU RECENSEMENT
        ============================================================= --}}
        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                    Maison
                </p>

                <p class="mt-1 text-lg font-bold text-slate-800">
                    N°{{ $recensement->maison->numeroMaison }}
                </p>
            </div>


            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                    Village
                </p>

                <p class="mt-1 text-lg font-bold text-slate-800">
                    {{ $recensement->maison->village->nomVillage ?? '—' }}
                </p>
            </div>


            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                    Statut
                </p>

                <p class="mt-1 text-lg font-bold text-emerald-600">
                    {{ ucfirst(str_replace('_', ' ', $recensement->statut)) }}
                </p>
            </div>


            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                    Total ménages
                </p>

                <p class="mt-1 text-lg font-bold text-slate-800">
                    {{ $menages->total() }}
                </p>
            </div>

        </div>


        {{-- ============================================================
            MESSAGES
        ============================================================= --}}
        @if(session('success'))

            <div class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>

        @endif


        @if(session('error'))

            <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>

        @endif


        {{-- ============================================================
            ERREURS DE VALIDATION
        ============================================================= --}}
        @if($errors->any())

            <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3">

                <ul class="list-disc space-y-1 pl-5 text-sm text-red-700">

                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif


        {{-- ============================================================
            TABLEAU DES MÉNAGES
        ============================================================= --}}
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">

                        <tr>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                N°
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Chef du ménage
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Sexe
                            </th>

                            <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Membres
                            </th>

                            <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Exploitation
                            </th>

                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @forelse($menages as $menage)

                            <tr class="transition hover:bg-slate-50">

                                {{-- =================================================
                                    NUMÉRO
                                ================================================== --}}
                                <td class="whitespace-nowrap px-5 py-4 text-sm font-semibold text-slate-700">
                                    {{ $menage->numeroMenage }}
                                </td>


                                {{-- =================================================
                                    CHEF DU MÉNAGE
                                ================================================== --}}
                                <td class="px-5 py-4">

                                    <div class="text-sm font-semibold text-slate-800">
                                        {{ $menage->nomChef }}
                                        {{ $menage->prenomChef }}
                                    </div>

                                </td>


                                {{-- =================================================
                                    SEXE
                                ================================================== --}}
                                <td class="px-5 py-4 text-sm text-slate-600">
                                    {{ $menage->sexeChef }}
                                </td>


                                {{-- =================================================
                                    MEMBRES
                                ================================================== --}}
                                <td class="px-5 py-4 text-center">

                                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                        {{
                                            (int) $menage->nombreHommes
                                            + (int) $menage->nombreFemmes
                                            + (int) $menage->nombreGarcons
                                            + (int) $menage->nombreFilles
                                        }}
                                    </span>

                                </td>


                                {{-- =================================================
                                    EXPLOITATION
                                ================================================== --}}
                                <td class="px-5 py-4 text-center">

                                    @if($menage->possedeExploitation)

                                        <a
                                            href="{{ route(
                                                'agent.recensements.menages.exploitants.index',
                                                [
                                                    'recensement' => $recensement,
                                                    'menage' => $menage,
                                                ]
                                            ) }}"
                                            class="text-sm font-semibold text-blue-600 hover:text-blue-800 hover:underline"
                                        >
                                            Exploitant
                                        </a>

                                        @if(isset($menage->exploitants_count))

                                            <div class="mt-1 text-xs text-slate-400">
                                                {{ $menage->exploitants_count }}
                                                {{ $menage->exploitants_count > 1 ? 'exploitants' : 'exploitant' }}
                                            </div>

                                        @endif

                                    @else

                                        <span class="text-sm text-slate-400">
                                            Aucune
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                    ACTIONS
                                ================================================== --}}
                                <td class="px-5 py-4">

                                    <div class="flex items-center justify-end gap-3 text-sm">

                                        {{-- Modifier --}}
                                        <a
                                            href="{{ route(
                                                'agent.recensements.menages.edit',
                                                [
                                                    'recensement' => $recensement,
                                                    'menage' => $menage,
                                                ]
                                            ) }}"
                                            class="font-medium text-amber-600 hover:text-amber-700 hover:underline"
                                        >
                                            Modifier
                                        </a>


                                        {{-- Supprimer --}}
                                        <form
                                            action="{{ route(
                                                'agent.recensements.menages.destroy',
                                                [
                                                    'recensement' => $recensement,
                                                    'menage' => $menage,
                                                ]
                                            ) }}"
                                            method="POST"
                                            onsubmit="return confirm('Voulez-vous vraiment supprimer ce ménage ?');"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="font-medium text-red-600 hover:text-red-700 hover:underline"
                                            >
                                                Supprimer
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="px-5 py-12 text-center"
                                >

                                    <div class="text-sm font-medium text-slate-600">
                                        Aucun ménage enregistré.
                                    </div>

                                    <div class="mt-1 text-xs text-slate-400">
                                        Commencez par ajouter le premier ménage de cette maison.
                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- ============================================================
                PAGINATION
            ============================================================= --}}
            @if($menages->hasPages())

                <div class="border-t border-slate-200 px-5 py-4">
                    {{ $menages->links() }}
                </div>

            @endif

        </div>

    </div>

</div>

@endsection
