@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-6">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold text-gray-800">Gestion des Saisons de Distribution</h1>
        <a href="{{ route('saison-distributions.create') }}" class="bg-[#006a4f] hover:bg-opacity-90 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouvelle Saison
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 shadow-sm" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 shadow-sm" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Barre de recherche -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6">
        <form method="GET" action="{{ route('saison-distributions.index') }}" class="flex gap-3">
            <div class="flex-1 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher par libellé ou statut..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#006a4f] focus:border-transparent text-sm">
            </div>
            <button type="submit" class="bg-gray-800 hover:bg-gray-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition shadow-sm">
                Rechercher
            </button>
            @if(request('search'))
                <a href="{{ route('saison-distributions.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition flex items-center">
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Libellé</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campagne de Base</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($saisons as $saison)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                {{ $saison->libelle }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                Du {{ \Carbon\Carbon::parse($saison->dateDebut)->format('d/m/Y') }} 
                                @if($saison->dateFin)
                                    au {{ \Carbon\Carbon::parse($saison->dateFin)->format('d/m/Y') }}
                                @else
                                    (En cours)
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $badgeClasses = [
                                        'planifiee' => 'bg-gray-100 text-gray-800',
                                        'ouverte' => 'bg-green-100 text-green-800',
                                        'cloturee' => 'bg-yellow-100 text-yellow-800',
                                        'archivee' => 'bg-red-100 text-red-800'
                                    ];
                                @endphp
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeClasses[$saison->statut] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($saison->statut) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $saison->campagneBase->libelle ?? 'Aucune (Indépendant)' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center items-center gap-3">
                                    <a href="{{ route('saison-distributions.show', $saison->idSaison) }}" class="text-blue-600 hover:text-blue-900" title="Voir">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    @if(!in_array($saison->statut, ['cloturee', 'archivee']))
                                        <a href="{{ route('saison-distributions.edit', $saison->idSaison) }}" class="text-yellow-600 hover:text-yellow-900" title="Modifier">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                    @endif
                                    <form action="{{ route('saison-distributions.destroy', $saison->idSaison) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette saison ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Supprimer">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 whitespace-nowrap text-center text-sm text-gray-500">
                                Aucun enregistrement trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
            {{ $saisons->links() }}
        </div>
    </div>
</div>
@endsection