@extends('layouts.agent')

@section('content')
<div class="max-w-4xl mx-auto py-6">

    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-xl font-semibold text-gray-800">
                Ménages — {{ $recensement->maison->adresse ?? 'Maison n°'.$recensement->maison->numero }}
            </h1>
            <p class="text-sm text-gray-500">
                Campagne : {{ $recensement->campagne->libelle ?? '—' }}
                · Statut :
                <span class="inline-block px-2 py-0.5 rounded text-xs font-medium
                    @class([
                        'bg-gray-100 text-gray-600' => $recensement->statut === 'brouillon',
                        'bg-blue-100 text-blue-700' => $recensement->statut === 'en_cours',
                        'bg-green-100 text-green-700' => $recensement->statut === 'valide',
                    ])">
                    {{ $recensement->statut }}
                </span>
            </p>
        </div>

        @if($recensement->statut !== 'valide')
            <a href="{{ route('agent.recensements.menages.create', $recensement) }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                + Ajouter un ménage
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-2 rounded bg-green-50 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left font-medium text-gray-500">N°</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-500">Chef de ménage</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-500">Sexe</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-500">Membres</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-500">Exploitation</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-500">Exploitants</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($menages as $menage)
                    <tr>
                        <td class="px-4 py-2">{{ $menage->numeroMenage }}</td>
                        <td class="px-4 py-2">{{ $menage->prenomChef }} {{ $menage->nomChef }}</td>
                        <td class="px-4 py-2">{{ $menage->sexeChef }}</td>
                        <td class="px-4 py-2">
                            {{ $menage->nombreHommes + $menage->nombreFemmes + $menage->nombreGarcons + $menage->nombreFilles }}
                        </td>
                        <td class="px-4 py-2">
                            @if($menage->possedeExploitation)
                                <span class="text-green-600">Oui</span>
                            @else
                                <span class="text-gray-400">Non</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">{{ $menage->exploitants_count }}</td>
                        <td class="px-4 py-2 text-right space-x-2">
                            @if($menage->possedeExploitation)
                                <a href="{{ route('agent.menages.exploitants.create', $menage) }}"
                                   class="text-blue-600 hover:underline">Exploitant</a>
                            @endif
                            @if($recensement->statut !== 'valide')
                                <a href="{{ route('agent.recensements.menages.edit', [$recensement, $menage]) }}"
                                   class="text-gray-600 hover:underline">Modifier</a>
                                <form action="{{ route('agent.recensements.menages.destroy', [$recensement, $menage]) }}"
                                      method="POST" class="inline"
                                      onsubmit="return confirm('Supprimer ce ménage ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Supprimer</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-400">
                            Aucun ménage saisi pour ce recensement.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection