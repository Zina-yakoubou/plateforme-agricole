@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">
            Modifier l'affectation
        </h1>

        <p class="text-sm text-slate-500 mt-1">
            Modification de l'affectation {{ $affectation->reference }}.
        </p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

        <form
            action="{{ route('affectations.update', $affectation->idAffectation) }}"
            method="POST"
        >

            @csrf
            @method('PUT')

            {{-- Agent --}}
            <div class="mb-6">

                <label
                    for="user_id"
                    class="block text-sm font-medium text-slate-700 mb-2"
                >
                    Agent recenseur
                </label>

                <select
                    name="user_id"
                    id="user_id"
                    class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                    required
                >

                    <option value="">
                        Sélectionner un agent
                    </option>

                    @foreach($agents as $agent)

                        <option
                            value="{{ $agent->id }}"
                            @selected(
                                old('user_id', $affectation->user_id) == $agent->id
                            )
                        >
                            {{ $agent->name }}
                            @if($agent->login)
                                — {{ $agent->login }}
                            @endif
                        </option>

                    @endforeach

                </select>

                @error('user_id')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Campagne --}}
            <div class="mb-6">

                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Campagne de recensement
                </label>

                <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">

                    @if($affectation->campagne)

                        <p class="font-semibold text-slate-800">
                            {{ $affectation->campagne->libelle }}
                        </p>

                        <p class="text-sm text-slate-500 mt-1">
                            Campagne associée à cette affectation.
                        </p>

                    @else

                        <p class="text-sm text-red-600">
                            Aucune campagne associée.
                        </p>

                    @endif

                </div>

            </div>


            {{-- Village --}}
            <div class="mb-6">

                <label
                    for="village_id"
                    class="block text-sm font-medium text-slate-700 mb-2"
                >
                    Village
                </label>

                <select
                    name="village_id"
                    id="village_id"
                    class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                    required
                >

                    <option value="">
                        Sélectionner un village
                    </option>

                    @foreach($villages as $village)

                        <option
                            value="{{ $village->idVillage }}"
                            @selected(
                                old(
                                    'village_id',
                                    $affectation->village_id
                                ) == $village->idVillage
                            )
                        >
                            {{ $village->nom }}
                        </option>

                    @endforeach

                </select>

                @error('village_id')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Date début --}}
            {{-- <div class="mb-6">

                <label
                    for="dateDebut"
                    class="block text-sm font-medium text-slate-700 mb-2"
                >
                    Date de début
                </label>

                <input
                    type="date"
                    name="dateDebut"
                    id="dateDebut"
                    value="{{ old('dateDebut', $affectation->dateDebut) }}"
                    class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                    required
                >

                @error('dateDebut')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div> --}}


            {{-- Date fin --}}
            {{-- <div class="mb-6">

                <label
                    for="dateFin"
                    class="block text-sm font-medium text-slate-700 mb-2"
                >
                    Date de fin
                </label>

                <input
                    type="date"
                    name="dateFin"
                    id="dateFin"
                    value="{{ old('dateFin', $affectation->dateFin) }}"
                    class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                >

                @error('dateFin')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div> --}}


            {{-- Statut --}}
            <div class="mb-6">

                <label
                    for="statut"
                    class="block text-sm font-medium text-slate-700 mb-2"
                >
                    Statut
                </label>

                <select
                    name="statut"
                    id="statut"
                    class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                    required
                >

                    <option
                        value="En attente"
                        @selected(old('statut', $affectation->statut) === 'En attente')
                    >
                        En attente
                    </option>

                    <option
                        value="Active"
                        @selected(old('statut', $affectation->statut) === 'Active')
                    >
                        Active
                    </option>

                    <option
                        value="Terminée"
                        @selected(old('statut', $affectation->statut) === 'Terminée')
                    >
                        Terminée
                    </option>

                </select>

            </div>


            {{-- Boutons --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">

                <a
                    href="{{ route('affectations.index') }}"
                    class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50"
                >
                    Annuler
                </a>

                <button
                    type="submit"
                    class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700"
                >
                    Enregistrer les modifications
                </button>

            </div>

        </form>

    </div>

</div>

@endsection