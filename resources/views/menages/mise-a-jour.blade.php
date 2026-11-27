@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-[#212529]">
                Mise à jour du ménage n° {{ $menage->numeroMenage ?? $menage->idMenage }}
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Campagne : <strong>{{ $recensement->campagne->libelle ?? 'Campagne active' }}</strong>
                · Chef : <strong>{{ $menage->prenomChef }} {{ $menage->nomChef }}</strong>
            </p>
        </div>

        <a
            href="{{ route('agent.recensements.show', $recensement->idRecensement) }}"
            class="inline-flex items-center rounded-lg border border-[#e5e7eb] bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
        >
            ← Retour au recensement
        </a>
    </div>

    <form
        method="POST"
        action="{{ route('agent.recensements.menages.mise-a-jour.store', [$recensement->idRecensement, $menage->idMenage]) }}"
        class="space-y-6"
    >
        @csrf

        {{-- État du ménage --}}
        <div class="rounded-lg border border-[#e5e7eb] bg-white p-5 shadow-[0_1px_2px_rgba(0,0,0,0.05)] space-y-4">
            <h2 class="text-base font-semibold text-[#212529]">1. Présence du ménage sur la campagne</h2>

            <div class="flex flex-wrap gap-6">
                <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                    <input type="radio" name="statut_menage" value="present" checked>
                    <span>Ménage présent</span>
                </label>

                <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                    <input type="radio" name="statut_menage" value="absent">
                    <span>Ménage absent</span>
                </label>
            </div>

            <div>
                <label for="motif_absence_menage" class="block text-xs font-medium text-gray-600 mb-1">
                    Motif (si absent)
                </label>
                <input
                    type="text"
                    id="motif_absence_menage"
                    name="motif_absence_menage"
                    placeholder="Ex : Déplacement temporaire, déménagement..."
                    class="w-full rounded-lg border border-[#e5e7eb] px-3 py-2 text-sm"
                >
            </div>
        </div>

        {{-- Exploitants existants --}}
        <div class="rounded-lg border border-[#e5e7eb] bg-white p-5 shadow-[0_1px_2px_rgba(0,0,0,0.05)] space-y-3">
            <h2 class="text-base font-semibold text-[#212529]">2. Exploitants du ménage (Présent / Absent)</h2>

            @forelse($menage->exploitants as $exp)
                @php $expId = $exp->idExploitant ?? $exp->id; @endphp
                <div class="rounded-lg border border-gray-200 p-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <div class="font-medium text-gray-800">
                            {{ $exp->prenom ?? $exp->prenomExploitant ?? '' }} {{ $exp->nom ?? $exp->nomExploitant ?? '' }}
                        </div>
                        <div class="text-xs text-gray-500">
                            Statut actuel : {{ $exp->statut_presence ?? 'present' }}
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-4">
                        <label class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700">
                            <input
                                type="radio"
                                name="exploitants_statuts[{{ $expId }}]"
                                value="present"
                                {{ ($exp->statut_presence ?? 'present') !== 'absent' ? 'checked' : '' }}
                            >
                            Présent
                        </label>

                        <label class="inline-flex items-center gap-1.5 text-xs font-medium text-rose-700">
                            <input
                                type="radio"
                                name="exploitants_statuts[{{ $expId }}]"
                                value="absent"
                                {{ ($exp->statut_presence ?? '') === 'absent' ? 'checked' : '' }}
                            >
                            Absent
                        </label>

                        <input
                            type="text"
                            name="exploitants_motifs[{{ $expId }}]"
                            value="{{ $exp->motif_absence ?? '' }}"
                            placeholder="Motif si absent"
                            class="rounded-lg border border-[#e5e7eb] px-2.5 py-1.5 text-xs"
                        >
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">Aucun exploitant rattaché à ce ménage pour le moment.</p>
            @endforelse
        </div>

        {{-- Nouveaux exploitants --}}
        <div class="rounded-lg border border-[#e5e7eb] bg-white p-5 shadow-[0_1px_2px_rgba(0,0,0,0.05)] space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-semibold text-[#212529]">3. Ajouter un ou plusieurs exploitants</h2>
                <button
                    type="button"
                    onclick="ajouterExploitant()"
                    class="rounded-lg bg-[#006a4f] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[#156c52]"
                >
                    + Ajouter une ligne
                </button>
            </div>

            <div id="listeNouveauxExploitants" class="space-y-3">
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-4 rounded-lg bg-[#f8faf9] p-3 border border-gray-100">
                    <input type="text" name="nouveaux_exploitants[0][nom]" placeholder="Nom" class="rounded-lg border border-[#e5e7eb] bg-white px-3 py-2 text-sm">
                    <input type="text" name="nouveaux_exploitants[0][prenom]" placeholder="Prénom(s)" class="rounded-lg border border-[#e5e7eb] bg-white px-3 py-2 text-sm">
                    <input type="text" name="nouveaux_exploitants[0][cooperative]" placeholder="Coopérative (optionnel)" class="rounded-lg border border-[#e5e7eb] bg-white px-3 py-2 text-sm">
                    <input type="text" name="nouveaux_exploitants[0][numero_piece]" placeholder="N° pièce (optionnel)" class="rounded-lg border border-[#e5e7eb] bg-white px-3 py-2 text-sm">
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button
                type="submit"
                class="rounded-lg bg-[#006a4f] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#156c52]"
            >
                Enregistrer la mise à jour
            </button>
        </div>
    </form>
</div>

<script>
    let idxExp = 1;
    function ajouterExploitant() {
        const container = document.getElementById('listeNouveauxExploitants');
        const div = document.createElement('div');
        div.className = 'grid grid-cols-1 gap-3 sm:grid-cols-4 rounded-lg bg-[#f8faf9] p-3 border border-gray-100';
        div.innerHTML = `
            <input type="text" name="nouveaux_exploitants[${idxExp}][nom]" placeholder="Nom" class="rounded-lg border border-[#e5e7eb] bg-white px-3 py-2 text-sm">
            <input type="text" name="nouveaux_exploitants[${idxExp}][prenom]" placeholder="Prénom(s)" class="rounded-lg border border-[#e5e7eb] bg-white px-3 py-2 text-sm">
            <input type="text" name="nouveaux_exploitants[${idxExp}][cooperative]" placeholder="Coopérative (optionnel)" class="rounded-lg border border-[#e5e7eb] bg-white px-3 py-2 text-sm">
            <input type="text" name="nouveaux_exploitants[${idxExp}][numero_piece]" placeholder="N° pièce (optionnel)" class="rounded-lg border border-[#e5e7eb] bg-white px-3 py-2 text-sm">
        `;
        container.appendChild(div);
        idxExp++;
    }
</script>
@endsection