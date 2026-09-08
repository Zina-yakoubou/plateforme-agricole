@extends('layouts.app')

@section('page-title', 'Détail de l’équipe')
@section('page-subtitle', $equipe->libelle)

@section('content')

<div class="mx-auto max-w-7xl space-y-6">

    {{-- Informations --}}
    <div class="rounded-xl border bg-white p-6">

        <h2 class="font-semibold text-lg mb-4">
            Informations générales
        </h2>

        <div class="grid md:grid-cols-2 gap-4">

            <div>
                <p class="text-sm text-gray-500">Référence</p>
                <p>{{ $equipe->reference }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Nom</p>
                <p>{{ $equipe->libelle }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Statut</p>
                <span class="rounded-full bg-green-100 px-3 py-1 text-green-700">
                    {{ ucfirst($equipe->statut) }}
                </span>
            </div>

            <div>
                <p class="text-sm text-gray-500">Superviseur</p>
                <p>{{ $equipe->superviseur->name }}</p>
            </div>

        </div>

    </div>

    {{-- Agents --}}
    <div class="rounded-xl border bg-white p-6">

        <h2 class="font-semibold text-lg mb-4">
            Agents recenseurs
        </h2>

        <table class="w-full">

            <thead>
                <tr class="text-left border-b">
                    <th class="py-2">Nom</th>
                    <th>Téléphone</th>
                    <th>Login</th>
                </tr>
            </thead>

            <tbody>

            @foreach($equipe->membres as $agent)

                <tr class="border-b">

                    <td class="py-3">
                        {{ $agent->name }}
                    </td>

                    <td>
                        {{ $agent->telephone }}
                    </td>

                    <td>
                        {{ $agent->login }}
                    </td>

                </tr>

            @endforeach

            </tbody>

        </table>

    </div>

    {{-- Villages --}}
    <div class="rounded-xl border bg-white p-6">

        <h2 class="font-semibold text-lg mb-4">
            Villages affectés
        </h2>

        <table class="w-full">

            <thead>
                <tr class="border-b text-left">
                    <th class="py-2">Village</th>
                    <th>Canton</th>
                    <th>Commune</th>
                    <th>Campagne</th>
                </tr>
            </thead>

            <tbody>

            @foreach($equipe->affectations as $affectation)

                <tr class="border-b">

                    <td class="py-3">
                        {{ $affectation->village->nom }}
                    </td>

                    <td>
                        {{ $affectation->village->canton->nom }}
                    </td>

                    <td>
                        {{ $affectation->village->canton->commune->nom }}
                    </td>

                    <td>
                        {{ $affectation->campagne->libelle }}
                    </td>

                </tr>

            @endforeach

            </tbody>

        </table>

    </div>

    {{-- Statistiques --}}
    <div class="grid md:grid-cols-4 gap-5">

        <div class="rounded-xl bg-white border p-5 text-center">
            <p class="text-3xl font-bold text-green-700">
                {{ $equipe->membres->count() }}
            </p>
            <p class="text-sm text-gray-500">Agents</p>
        </div>

        <div class="rounded-xl bg-white border p-5 text-center">
            <p class="text-3xl font-bold text-blue-700">
                {{ $equipe->affectations->count() }}
            </p>
            <p class="text-sm text-gray-500">Villages</p>
        </div>

        <div class="rounded-xl bg-white border p-5 text-center">
            <p class="text-3xl font-bold text-orange-600">
                {{ $equipe->affectations->where('statut','active')->count() }}
            </p>
            <p class="text-sm text-gray-500">Affectations actives</p>
        </div>

        <div class="rounded-xl bg-white border p-5 text-center">
            <p class="text-3xl font-bold text-purple-700">
                {{ $equipe->affectations->pluck('campagne_id')->unique()->count() }}
            </p>
            <p class="text-sm text-gray-500">Campagnes</p>
        </div>

    </div>

</div>

@endsection