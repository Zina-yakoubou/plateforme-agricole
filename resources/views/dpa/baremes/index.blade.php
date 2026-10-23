@extends('layouts.app')

@section('title', 'Gestion des Barèmes Agronomiques')

@section('content')
<div class="space-y-6">

    {{-- En-tête --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-[#006a4f]">Gestion Préfectorale</p>
                <h1 class="text-2xl font-bold text-gray-900">Barèmes Agronomiques</h1>
                <p class="text-sm text-gray-500 mt-1">Fixez les normes de dosage (kg/ha) applicables aux cultures de votre zone.</p>
            </div>
            
            <button onclick="document.getElementById('modalBareme').classList.remove('hidden')" 
                    class="inline-flex items-center gap-2 rounded-lg bg-[#006a4f] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#00543e]">
                + Nouveau Barème
            </button>
        </div>
    </div>

    {{-- Alertes Flash : Succès --}}
    @if(session('success'))
        <div class="p-4 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 text-sm font-medium flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Alertes Flash : Erreur Backend globale --}}
    @if(session('error'))
        <div class="p-4 rounded-xl border border-red-200 bg-red-50 text-red-800 text-sm font-medium flex items-center gap-2">
            <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Alerte d'erreurs de validation des Form Requests --}}
    @if($errors->any())
        <div class="p-4 rounded-xl border border-amber-200 bg-amber-50 text-amber-800 text-sm font-medium space-y-1">
            <div class="flex items-center gap-2 font-bold">
                <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>Saisie invalide : Veuillez corriger les erreurs indiquées dans le formulaire.</span>
            </div>
        </div>
    @endif

    {{-- Tableau des barèmes --}}
    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100">
            <h3 class="font-semibold text-gray-900">Barèmes configurés</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                        <th class="px-5 py-3">Culture Prioritaire</th>
                        <th class="px-5 py-3">Intrant Associé</th>
                        <th class="px-5 py-3">Dose Recommandée</th>
                        <th class="px-5 py-3">Plafond / Producteur</th>
                        <th class="px-5 py-3">Statut</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($baremes as $b)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-5 py-4 font-medium text-gray-900">{{ $b->culture->nom }}</td>
                            <td class="px-5 py-4 text-gray-700">{{ $b->intrant->nomIntrant }}</td>
                            <td class="px-5 py-4 font-semibold text-[#006a4f]">{{ $b->dose_par_hectare }} {{ $b->unite }}</td>
                            <td class="px-5 py-4 text-gray-600">
                                {{ $b->plafond_max_producteur ? number_format($b->plafond_max_producteur, 2, ',', ' ') . ' kg' : 'Sans limite' }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $b->est_actif ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $b->est_actif ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right flex items-center justify-end gap-3">
                                <form action="{{ route('dpa.baremes.toggle', $b->idBareme) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-xs font-semibold hover:underline {{ $b->est_actif ? 'text-amber-600' : 'text-[#006a4f]' }}">
                                        {{ $b->est_actif ? 'Désactiver' : 'Activer' }}
                                    </button>
                                </form>

                                <form action="{{ route('dpa.baremes.destroy', $b->idBareme) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce barème ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-red-600 hover:underline">
                                        Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-gray-500">
                                Aucun barème agronomique configuré pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal d'ajout de Barème (Se réouvre automatiquement en cas d'erreur de validation) --}}
<div id="modalBareme" class="fixed inset-0 z-50 {{ $errors->any() ? '' : 'hidden' }} bg-black/50 flex items-center justify-center p-4">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl space-y-4">
        <div class="flex items-center justify-between border-b pb-3">
            <h3 class="font-bold text-gray-900">Ajouter un Barème</h3>
            <button onclick="document.getElementById('modalBareme').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>

        <form action="{{ route('dpa.baremes.store') }}" method="POST" class="space-y-4">
            @csrf
            
            {{-- Culture --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Culture</label>
                <select name="culture_id" required class="w-full rounded-lg text-sm focus:border-[#006a4f] focus:ring-[#006a4f] @error('culture_id') border-red-500 @else border-gray-300 @enderror">
                    <option value="">Sélectionnez une culture</option>
                    @foreach($cultures as $c)
                        <option value="{{ $c->idCulture }}" @selected(old('culture_id') == $c->idCulture)>
                            {{ $c->nom }}
                        </option>
                    @endforeach
                </select>
                @error('culture_id')
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Intrant --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Intrant</label>
                <select name="intrant_id" required class="w-full rounded-lg text-sm focus:border-[#006a4f] focus:ring-[#006a4f] @error('intrant_id') border-red-500 @else border-gray-300 @enderror">
                    <option value="">Sélectionnez un intrant</option>
                    @foreach($intrants as $i)
                        <option value="{{ $i->idIntrant }}" @selected(old('intrant_id') == $i->idIntrant)>
                            {{ $i->nomIntrant }}
                        </option>
                    @endforeach
                </select>
                @error('intrant_id')
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Dose & Unité --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Dose / Hectare</label>
                    <input type="number" step="0.01" name="dose_par_hectare" value="{{ old('dose_par_hectare') }}" required placeholder="150" class="w-full rounded-lg text-sm focus:border-[#006a4f] focus:ring-[#006a4f] @error('dose_par_hectare') border-red-500 @else border-gray-300 @enderror">
                    @error('dose_par_hectare')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Unité</label>
                    <input type="text" name="unite" value="{{ old('unite', 'kg/ha') }}" required class="w-full rounded-lg text-sm focus:border-[#006a4f] focus:ring-[#006a4f] @error('unite') border-red-500 @else border-gray-300 @enderror">
                    @error('unite')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Plafond --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Plafond Max par Producteur (kg)</label>
                <input type="number" step="0.01" name="plafond_max_producteur" value="{{ old('plafond_max_producteur') }}" placeholder="Ex: 500 (Optionnel)" class="w-full rounded-lg text-sm focus:border-[#006a4f] focus:ring-[#006a4f] @error('plafond_max_producteur') border-red-500 @else border-gray-300 @enderror">
                @error('plafond_max_producteur')
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-3 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modalBareme').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-gray-600 rounded-lg border hover:bg-gray-50">Annuler</button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white rounded-lg bg-[#006a4f] hover:bg-[#00543e]">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endsection