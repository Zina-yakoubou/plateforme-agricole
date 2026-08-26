@extends('layouts.app')

@section('content')

<div class="space-y-6">

{{-- ================================================= --}}
{{-- EN-TÊTE --}}
{{-- ================================================= --}}

<div class="flex items-center justify-between">

    <div>
        <h1 class="text-2xl font-bold text-slate-800">
            Gestion des affectations
        </h1>

        <p class="text-sm text-slate-500 mt-1">
            Gestion des missions confiées aux agents recenseurs.
        </p>
    </div>

    <a href="{{ route('affectations.create') }}"
       class="px-5 py-2.5 rounded-xl bg-green-600 text-white
              hover:bg-green-700 transition font-medium">

        + Nouvelle affectation

    </a>

</div>


{{-- ================================================= --}}
{{-- MESSAGE SUCCÈS --}}
{{-- ================================================= --}}

@if(session('success'))

    <div class="p-4 rounded-xl bg-green-50 border border-green-200
                text-green-700">

        {{ session('success') }}

    </div>

@endif


{{-- ================================================= --}}
{{-- RECHERCHE --}}
{{-- ================================================= --}}

<form method="GET"
      action="{{ route('affectations.index') }}"
      class="flex gap-3">

    <input
        type="text"
        name="search"
        value="{{ $search ?? '' }}"
        placeholder="Rechercher une affectation..."
        class="flex-1 rounded-xl border-slate-300
               focus:border-green-500 focus:ring-green-500">

    <button
        type="submit"
        class="px-5 py-2.5 rounded-xl bg-slate-800
               text-white hover:bg-slate-900 transition">

        Rechercher

    </button>

</form>


{{-- ================================================= --}}
{{-- TABLEAU --}}
{{-- ================================================= --}}

<div class="bg-white border border-slate-200 rounded-2xl
            shadow-sm overflow-hidden">

    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-slate-50 border-b border-slate-200">

                <tr>

                    <th class="px-6 py-4 text-left font-semibold text-slate-600">
                        N°
                    </th>

                    <th class="px-6 py-4 text-left font-semibold text-slate-600">
                        Agent recenseur
                    </th>

                    <th class="px-6 py-4 text-left font-semibold text-slate-600">
                        Campagne
                    </th>

                    <th class="px-6 py-4 text-left font-semibold text-slate-600">
                        Village
                    </th>

                    <th class="px-6 py-4 text-left font-semibold text-slate-600">
                        Canton
                    </th>

                    <th class="px-6 py-4 text-left font-semibold text-slate-600">
                        Statut
                    </th>

                    <th class="px-6 py-4 text-right font-semibold text-slate-600">
                        Actions
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-slate-100">

                @forelse($affectations as $affectation)

                    <tr class="hover:bg-slate-50 transition">

                        {{-- Numéro --}}
                        <td class="px-6 py-4 text-slate-500">
                            {{ $loop->iteration + ($affectations->currentPage() - 1) * $affectations->perPage() }}
                        </td>


                        {{-- Agent --}}
                        <td class="px-6 py-4 text-slate-700">

                            {{ $affectation->user->name ?? 'Non défini' }}

                        </td>


                        {{-- Campagne --}}
                        <td class="px-6 py-4 text-slate-700">

                            {{ $affectation->campagne->libelle ?? 'Non définie' }}

                        </td>


                        {{-- Village --}}
                        <td class="px-6 py-4 text-slate-700">

                            {{ $affectation->village->nom ?? 'Non défini' }}

                        </td>


                        {{-- Canton --}}
                        <td class="px-6 py-4 text-slate-700">

                            {{ $affectation->village?->canton?->nom ?? 'Non défini' }}
                        </td>


                        {{-- ================================================= --}}
                        {{-- STATUT --}}
                        {{-- ================================================= --}}

                        <td class="px-6 py-4">

                            @if($affectation->statut === 'ACTIVE')

                                <span class="inline-flex items-center px-3 py-1
                                             rounded-full text-xs font-semibold
                                             bg-green-100 text-green-700">

                                    Active

                                </span>

                            @else

                                <span class="inline-flex items-center px-3 py-1
                                             rounded-full text-xs font-semibold
                                             bg-slate-100 text-slate-600">

                                    Désactivée

                                </span>

                            @endif

                        </td>


                        {{-- ================================================= --}}
                        {{-- ACTIONS --}}
                        {{-- ================================================= --}}

                        <td class="px-6 py-4">

                            <div class="flex items-center justify-end gap-2">

                                {{-- Voir --}}
                                <a href="{{ route('affectations.show', $affectation) }}"
                                   class="px-3 py-2 rounded-lg text-sm
                                          text-slate-600 hover:bg-slate-100">

                                    Voir

                                </a>


                                {{-- Modifier --}}
                                @if($affectation->statut === 'ACTIVE')

                                    <a href="{{ route('affectations.edit', $affectation) }}"
                                       class="px-3 py-2 rounded-lg text-sm
                                              text-blue-600 hover:bg-blue-50">

                                        Modifier

                                    </a>

                                @endif


                                {{-- Désactiver --}}
                                {{-- @if($affectation->statut === 'ACTIVE')

                                    <form method="POST"
                                          action="{{ route('affectations.destroy', $affectation) }}"
                                          onsubmit="return confirm('Voulez-vous vraiment désactiver cette affectation ?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="px-3 py-2 rounded-lg text-sm
                                                       text-red-600 hover:bg-red-50">

                                            Désactiver

                                        </button>

                                    </form>

                                @endif --}}

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7"
                            class="px-6 py-12 text-center text-slate-500">

                            Aucune affectation enregistrée.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- Pagination --}}
    @if($affectations->hasPages())

        <div class="px-6 py-4 border-t border-slate-200">

            {{ $affectations->links() }}

        </div>

    @endif

</div>

</div>

@endsection
