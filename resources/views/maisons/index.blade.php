@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">


{{-- ================================================= --}}
{{-- EN-TÊTE --}}
{{-- ================================================= --}}

<div class="flex items-center justify-between mb-6">

    <div>

        <div class="flex items-center gap-2 mb-2">

            <a
                href="{{ route('agent.affectations') }}"
                class="text-sm text-slate-500 hover:text-green-600"
            >
                ← Mes affectations
            </a>

        </div>

        <h1 class="text-2xl font-bold text-slate-800">
            Maisons du village
        </h1>

        <p class="text-sm text-slate-500 mt-1">
            Enregistrez et consultez les maisons du village
            <strong class="text-slate-700">
                {{ $village->nom }}
            </strong>.
        </p>

    </div>


    {{-- AJOUTER UNE MAISON --}}

    <a
        href="{{ route('villages.maisons.create', $village->idVillage) }}"
        class="inline-flex items-center gap-2
               px-5 py-2.5 rounded-xl
               bg-green-600 text-white
               font-semibold
               hover:bg-green-700 transition"
    >
        <span class="text-lg">+</span>
        Ajouter une maison
    </a>

</div>


{{-- ================================================= --}}
{{-- INFORMATIONS DU VILLAGE --}}
{{-- ================================================= --}}

<div class="bg-white rounded-2xl shadow-sm
            border border-slate-200 mb-6">

    <div class="p-6">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Village --}}

            <div class="flex items-center gap-4">

                <div class="w-12 h-12 rounded-xl
                            bg-green-100 text-green-700
                            flex items-center justify-center
                            text-xl">
                    🌾
                </div>

                <div>

                    <p class="text-xs uppercase tracking-wider
                              text-slate-400">
                        Village
                    </p>

                    <p class="font-bold text-slate-800">
                        {{ $village->nom }}
                    </p>

                </div>

            </div>


            {{-- Canton --}}

            <div class="flex items-center gap-4">

                <div class="w-12 h-12 rounded-xl
                            bg-slate-100 text-slate-600
                            flex items-center justify-center
                            text-xl">
                    🗺️
                </div>

                <div>

                    <p class="text-xs uppercase tracking-wider
                              text-slate-400">
                        Canton
                    </p>

                    <p class="font-semibold text-slate-800">
                        {{ $village->canton->nom ?? '—' }}
                    </p>

                </div>

            </div>


            {{-- Nombre de maisons --}}

            <div class="flex items-center gap-4">

                <div class="w-12 h-12 rounded-xl
                            bg-blue-100 text-blue-700
                            flex items-center justify-center
                            text-xl">
                    🏠
                </div>

                <div>

                    <p class="text-xs uppercase tracking-wider
                              text-slate-400">
                        Maisons enregistrées
                    </p>

                    <p class="font-bold text-slate-800">
                        {{ $maisons->total() }}
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ================================================= --}}
{{-- MESSAGES --}}
{{-- ================================================= --}}

@if(session('success'))

    <div class="mb-5 rounded-xl
                bg-green-100 text-green-700
                px-5 py-4 border border-green-200">

        {{ session('success') }}

    </div>

@endif


@if($errors->any())

    <div class="mb-5 rounded-xl
                bg-red-100 text-red-700
                px-5 py-4 border border-red-200">

        <ul class="list-disc list-inside">

            @foreach($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

@endif


{{-- ================================================= --}}
{{-- RECHERCHE --}}
{{-- ================================================= --}}

<div class="bg-white rounded-2xl shadow-sm
            border border-slate-200 p-5 mb-6">

    <form
        method="GET"
        action="{{ route('villages.maisons.index', $village->idVillage) }}"
    >

        <div class="flex flex-col md:flex-row gap-3">

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Rechercher par numéro ou adresse..."
                class="flex-1 rounded-xl border-gray-300
                       focus:border-green-500
                       focus:ring-green-500"
            >

            <button
                type="submit"
                class="px-6 py-2.5
                       bg-slate-800 text-white
                       rounded-xl
                       hover:bg-slate-700
                       transition"
            >
                Rechercher
            </button>

        </div>

    </form>

</div>


{{-- ================================================= --}}
{{-- TABLEAU DES MAISONS --}}
{{-- ================================================= --}}

<div class="bg-white rounded-2xl shadow-sm
            border border-slate-200 overflow-hidden">

    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-slate-100">

                <tr>

                    <th class="px-6 py-4 text-left">
                        N°
                    </th>

                    <th class="px-6 py-4 text-left">
                        Numéro de maison
                    </th>

                    <th class="px-6 py-4 text-left">
                        Adresse
                    </th>

                    <th class="px-6 py-4 text-center">
                        Ménages
                    </th>

                    <th class="px-6 py-4 text-center">
                        Actions
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y">

                @forelse($maisons as $maison)

                    <tr class="hover:bg-slate-50 transition">

                        {{-- N° --}}

                        <td class="px-6 py-4 text-slate-500">

                            {{ $loop->iteration + (($maisons->currentPage() - 1) * $maisons->perPage()) }}

                        </td>


                        {{-- NUMÉRO MAISON --}}

                        <td class="px-6 py-4">

                            <div class="flex items-center gap-3">

                                <div class="w-10 h-10 rounded-lg
                                            bg-green-100 text-green-700
                                            flex items-center justify-center
                                            text-lg">
                                    🏠
                                </div>

                                <div>

                                    <p class="font-bold text-slate-800">
                                        {{ $maison->numeroMaison }}
                                    </p>

                                    <p class="text-xs text-slate-400">
                                        UID : {{ $maison->uid }}
                                    </p>

                                </div>

                            </div>

                        </td>


                        {{-- ADRESSE --}}

                        <td class="px-6 py-4 text-slate-600">

                            {{ $maison->adresse ?: '—' }}

                        </td>


                        {{-- MÉNAGES --}}

                        <td class="px-6 py-4 text-center">

                            <span
                                class="inline-flex items-center justify-center
                                       min-w-10 px-3 py-1 rounded-full
                                       text-sm font-semibold
                                       bg-slate-100 text-slate-700"
                            >

                                {{ $maison->menages_count }}

                            </span>

                        </td>


                        {{-- ACTIONS --}}

                        <td class="px-6 py-4">

                            <div class="flex justify-center gap-2">

                                {{-- VOIR --}}

                                <a
                                    href="{{ route('maisons.show', $maison->idMaison) }}"
                                    class="px-3 py-1.5 rounded-lg
                                           bg-slate-700 text-white
                                           hover:bg-slate-800 transition"
                                >
                                    Voir
                                </a>


                                {{-- RECENSER --}}

                                <a
                                    href="{{ route('maisons.show', $maison->idMaison) }}"
                                    class="px-3 py-1.5 rounded-lg
                                           bg-green-600 text-white
                                           hover:bg-green-700 transition"
                                >
                                    Recenser
                                </a>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="px-6 py-12 text-center"
                        >

                            <div class="flex flex-col
                                        items-center justify-center">

                                <div class="w-16 h-16 rounded-full
                                            bg-slate-100
                                            flex items-center justify-center
                                            text-3xl mb-4">
                                    🏠
                                </div>

                                <p class="font-semibold text-slate-700">
                                    Aucune maison enregistrée
                                </p>

                                <p class="text-sm text-slate-500 mt-1">
                                    Commencez par enregistrer la première
                                    maison de ce village.
                                </p>

                                <a
                                    href="{{ route('villages.maisons.create', $village->idVillage) }}"
                                    class="mt-4 px-5 py-2 rounded-xl
                                           bg-green-600 text-white
                                           hover:bg-green-700"
                                >
                                    + Ajouter une maison
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

        <div class="p-5 border-t border-slate-200">

            {{ $maisons->withQueryString()->links() }}

        </div>

    @endif

</div>


</div>

@endsection
