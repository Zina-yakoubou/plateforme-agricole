@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- ================= EN-TÊTE ================= --}}
    <div class="flex items-center justify-between mb-6">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Mes affectations
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Liste des villages qui vous ont été affectés pour le recensement.
            </p>
        </div>

    </div>


    {{-- ================= MESSAGE SUCCÈS ================= --}}
    @if(session('success'))

        <div class="mb-6 rounded-xl bg-green-50 border border-green-200
                    px-4 py-3 text-sm text-green-700">

            {{ session('success') }}

        </div>

    @endif


    {{-- ================= RECHERCHE ================= --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 mb-6">

        <form method="GET"
              action="{{ route('agent.affectations') }}"
              class="flex gap-3">

            <div class="flex-1">

                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Rechercher une affectation, un village, un canton..."
                    class="w-full rounded-xl border-slate-300
                           focus:border-green-500
                           focus:ring-green-500">

            </div>

            <button
                type="submit"
                class="px-5 py-3 rounded-xl
                       bg-green-600 text-white
                       hover:bg-green-700 transition">

                Rechercher

            </button>

            @if($search)

                <a href="{{ route('agent.affectations') }}"
                   class="px-5 py-3 rounded-xl
                          bg-slate-100 text-slate-700
                          hover:bg-slate-200 transition">

                    Réinitialiser

                </a>

            @endif

        </form>

    </div>


    {{-- ================= AFFECTATIONS ================= --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

        <div class="px-6 py-4 border-b border-slate-200">

            <h2 class="font-semibold text-slate-800">
                Mes affectations
            </h2>

        </div>


        @if($affectations->count())

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-slate-50">

                        <tr>

                            <th class="px-6 py-4 text-left font-semibold text-slate-600">
                                N°
                            </th>

                            <th class="px-6 py-4 text-left font-semibold text-slate-600">
                                Campagne
                            </th>

                            <th class="px-6 py-4 text-left font-semibold text-slate-600">
                                Commune
                            </th>

                            <th class="px-6 py-4 text-left font-semibold text-slate-600">
                                Canton
                            </th>

                            <th class="px-6 py-4 text-left font-semibold text-slate-600">
                                Village
                            </th>

                            <th class="px-6 py-4 text-left font-semibold text-slate-600">
                                Statut
                            </th>

                           <th class="px-6 py-4 text-right font-semibold text-slate-600">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @foreach($affectations as $affectation)

                            <tr class="hover:bg-slate-50 transition">

                                {{-- Numéro --}}
                                <td class="px-6 py-4">

                                    <span class="font-semibold text-slate-800">
                                        {{ $loop->iteration + ($affectations->currentPage() - 1) * $affectations->perPage() }}
                                    </span>

                                </td>


                                {{-- Campagne --}}
                                <td class="px-6 py-4 text-slate-600">

                                    {{ $affectation->campagne->libelle ?? '—' }}

                                </td>


                                {{-- Préfecture --}}
                                <td class="px-6 py-4 text-slate-600">

                                    {{ $affectation->village->canton->commune->nom ?? '—' }}

                                </td>


                                {{-- Canton --}}
                                <td class="px-6 py-4 text-slate-600">

                                    {{ $affectation->village->canton->nom ?? '—' }}

                                </td>


                                <td class="px-6 py-4">

                                    <span class="font-medium text-slate-800">

                                        {{ $affectation->village->nom ?? '—' }}

                                    </span>

                                </td>


                                {{-- Statut --}}
                                <td class="px-6 py-4">

                                    @if(strtoupper($affectation->statut) === 'ACTIVE')

                                        <span class="inline-flex items-center
                                                     px-2.5 py-1 rounded-full
                                                     text-xs font-semibold
                                                     bg-green-100 text-green-700">

                                            Active

                                        </span>

                                    @else

                                        <span class="inline-flex items-center
                                                     px-2.5 py-1 rounded-full
                                                     text-xs font-semibold
                                                     bg-slate-100 text-slate-600">

                                            {{ $affectation->statut }}

                                        </span>

                                    @endif

                                </td>
                                <td class="px-6 py-4 text-right">

                                    <a href="{{ route('agent.affectations.show', $affectation) }}"
                                   title="Voir la campagne"
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

                                </td>


                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            <div class="px-6 py-4 border-t border-slate-200">

                {{ $affectations->links() }}

            </div>

        @else

            {{-- Aucun résultat --}}
            <div class="px-6 py-16 text-center">

               
                <h3 class="text-lg font-semibold text-slate-800">
                    Aucune affectation
                </h3>

                <p class="text-sm text-slate-500 mt-2">

                    Vous n'avez actuellement aucune affectation.

                </p>

            </div>

        @endif

    </div>

</div>

@endsection