@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto pb-12">

    {{-- =========================================================
         EN-TÊTE : INFOS DU RECENSEMENT & MAISON
    ========================================================== --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between bg-white p-6 rounded-2xl border border-gray-200 shadow-xs">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-semibold tracking-tight text-[#212529]">
                    Maison n° {{ $recensement->maison->numeroMaison ?? '—' }}
                </h1>
                <span class="rounded-full bg-[#e5f2ee] px-3 py-1 text-xs font-semibold text-[#006a4f]">
                    {{ $recensement->campagne->libelle ?? 'Campagne en cours' }}
                </span>
            </div>
            <p class="mt-1 text-sm text-gray-500">
                Village de {{ $recensement->maison->village->nom ?? '—' }} • Statut : 
                <span class="font-semibold capitalize text-[#006a4f]">{{ $recensement->statut }}</span>
            </p>
        </div>

        <div>
            <a
                href="{{ route('agent.recensements.index', ['maison_id' => $recensement->maison_id]) }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour aux recensements
            </a>
        </div>
    </div>


    {{-- =========================================================
         SECTION : GESTION DES MÉNAGES DE CETTE MAISON
    ========================================================== --}}
    <div class="rounded-2xl border border-gray-200 bg-white shadow-xs overflow-hidden">
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-gray-200 bg-[#f8faf9] px-6 py-4 gap-4">
            <div>
                <h2 class="text-base font-semibold text-[#212529]">
                    Ménages résidant dans cette habitation
                </h2>
                <p class="text-xs text-gray-500">
                    Enregistrez et gérez les ménages, leurs membres et leurs exploitants agricoles.
                </p>
            </div>

            {{-- Bouton d'ajout d'un ménage (affiché uniquement si le recensement n'est pas clôturé/validé) --}}
            @if(!in_array($recensement->statut, ['termine', 'valide']))
                <a
                    href="{{ route('agent.recensements.menages.create', $recensement->idRecensement) }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-[#006a4f] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#156c52]"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Ajouter un ménage
                </a>
            @endif
        </div>

        <div class="p-6">
            @if(isset($recensement->menages) && $recensement->menages->count() > 0)
                <div class="space-y-4">
                    @foreach($recensement->menages as $menage)
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-xl border border-gray-200 p-4 bg-gray-50/50 hover:bg-white transition">
                            <div>
                                <h3 class="font-semibold text-gray-900">
                                    Ménage de {{ $menage->chefMenage ?? 'Chef non renseigné' }}
                                </h3>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    Code / Réf : {{ $menage->codeMenage ?? '—' }} • Personnes composant le ménage : {{ $menage->nombrePersonnes ?? '—' }}
                                </p>
                            </div>

                            <div class="mt-3 sm:mt-0 flex items-center gap-2">
                                {{-- Lien vers la gestion des exploitants / parcelles du ménage --}}
                                <a
                                    href="{{ route('agent.recensements.menages.exploitants.index', $menage) }}"
                                    
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition"
                                >
                                    Gérer les exploitants & parcelles
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#e5f2ee] text-[#006a4f] mb-3">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-800">Aucun ménage enregistré pour l'instant</p>
                    <p class="text-xs text-gray-500 mt-1">Commencez par ajouter le premier ménage résidant dans cette maison.</p>
                </div>
            @endif
        </div>
    </div>


    {{-- =========================================================
         SECTION ACTIONS DE CLÔTURE DU RECENSEMENT
    ========================================================== --}}
    @if(!in_array($recensement->statut, ['termine', 'valide']))
        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
            <p class="text-xs text-gray-500">
                Une fois tous les ménages et parcelles saisis, vous pouvez clôturer ce recensement.
            </p>
            <form action="{{ route('agent.recensements.cloturer', $recensement) }}" method="POST">
                @csrf
                <button
                    type="submit"
                    onclick="return confirm('Voulez-vous vraiment clôturer le recensement de cette maison ?')"
                    class="inline-flex items-center gap-2 rounded-xl bg-orange-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-orange-700"
                >
                    Clôturer le recensement
                </button>
            </form>
        </div>
    @endif

</div>
@endsection