@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto">

    {{-- ========================================================= --}}
    {{-- EN-TÊTE --}}
    {{-- ========================================================= --}}

    <div class="flex items-center justify-between mb-6">

        <div>

            <div class="flex items-center gap-2 mb-2">

                <a href="{{ route('agent.affectations') }}"
                   class="text-sm text-slate-500 hover:text-green-600">

                    ← Mes affectations

                </a>

            </div>

            <h1 class="text-2xl font-bold text-slate-800">
                Détail de l'affectation
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Consultez les informations de votre affectation
                avant de commencer le recensement.
            </p>

        </div>


        {{-- Statut --}}
        <div>

            @if(strtoupper($affectation->statut) === 'ACTIVE')

                <span class="inline-flex items-center
                             px-4 py-2 rounded-full
                             text-sm font-semibold
                             bg-green-100 text-green-700">

                    ● Affectation active

                </span>

            @else

                <span class="inline-flex items-center
                             px-4 py-2 rounded-full
                             text-sm font-semibold
                             bg-slate-100 text-slate-600">

                    {{ $affectation->statut }}

                </span>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- INFORMATIONS GÉNÉRALES --}}
    {{-- ========================================================= --}}

    <div class="bg-white rounded-2xl shadow-sm
                border border-slate-200 mb-6">

        <div class="px-6 py-4 border-b border-slate-200">

            <h2 class="font-semibold text-slate-800">
                Informations de l'affectation
            </h2>

        </div>


        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Référence --}}
            <div>

                <p class="text-xs uppercase tracking-wider
                          text-slate-400 mb-1">

                    Référence

                </p>

                <p class="font-semibold text-slate-800">

                    {{ $affectation->reference ?? '—' }}

                </p>

            </div>


            {{-- Campagne --}}
            <div>

                <p class="text-xs uppercase tracking-wider
                          text-slate-400 mb-1">

                    Campagne

                </p>

                <p class="font-semibold text-slate-800">

                    {{ $affectation->campagne->libelle ?? '—' }}

                </p>

            </div>


            {{-- Date début --}}
            <div>

                <p class="text-xs uppercase tracking-wider
                          text-slate-400 mb-1">

                    Date de début

                </p>

                <p class="text-slate-700">

                    {{ $affectation->dateDebut
                        ? \Carbon\Carbon::parse($affectation->dateDebut)->format('d/m/Y')
                        : '—'
                    }}

                </p>

            </div>


            {{-- Date fin --}}
            <div>

                <p class="text-xs uppercase tracking-wider
                          text-slate-400 mb-1">

                    Date de fin

                </p>

                <p class="text-slate-700">

                    {{ $affectation->dateFin
                        ? \Carbon\Carbon::parse($affectation->dateFin)->format('d/m/Y')
                        : 'Non définie'
                    }}

                </p>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- TERRITOIRE --}}
    {{-- ========================================================= --}}

    <div class="bg-white rounded-2xl shadow-sm
                border border-slate-200 mb-6">

        <div class="px-6 py-4 border-b border-slate-200">

            <h2 class="font-semibold text-slate-800">
                Territoire affecté
            </h2>

            <p class="text-sm text-slate-500 mt-1">
                Zone géographique dans laquelle vous devez effectuer
                le recensement.
            </p>

        </div>


        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Commune --}}
            <div class="rounded-xl bg-slate-50 p-5">

               

                <p class="text-xs uppercase tracking-wider
                          text-slate-400">

                    Commune

                </p>

                <p class="mt-1 font-semibold text-slate-800">

                    {{ $affectation->village->canton->commune->nom ?? '—' }}

                </p>

            </div>


            {{-- Canton --}}
            <div class="rounded-xl bg-slate-50 p-5">

              

                <p class="text-xs uppercase tracking-wider
                          text-slate-400">

                    Canton

                </p>

                <p class="mt-1 font-semibold text-slate-800">

                    {{ $affectation->village->canton->nom ?? '—' }}

                </p>

            </div>


            {{-- Village --}}
            <div class="rounded-xl bg-green-50 p-5
                        border border-green-100">

                
                <p class="text-xs uppercase tracking-wider
                          text-green-600">

                    Village à recenser

                </p>

                <p class="mt-1 font-bold text-green-800">

                    {{ $affectation->village->nom ?? '—' }}

                </p>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- AGENT --}}
    {{-- ========================================================= --}}

    <div class="bg-white rounded-2xl shadow-sm
                border border-slate-200 mb-6">

        <div class="px-6 py-4 border-b border-slate-200">

            <h2 class="font-semibold text-slate-800">
                Agent recenseur
            </h2>

        </div>


        <div class="p-6 flex items-center gap-4">

            <div class="w-12 h-12 rounded-full
                        bg-green-100 text-green-700
                        flex items-center justify-center
                        font-bold text-lg">

                {{ strtoupper(substr($affectation->user->name ?? 'A', 0, 1)) }}

            </div>


            <div>

                <p class="font-semibold text-slate-800">

                    {{ $affectation->user->name ?? '—' }}

                </p>

                <p class="text-sm text-slate-500">

                    {{ $affectation->user->login ?? '' }}

                </p>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ACTION --}}
    {{-- ========================================================= --}}

    <div class="bg-white rounded-2xl shadow-sm
                border border-slate-200">

        <div class="p-6">

            @if(strtoupper($affectation->statut) === 'ACTIVE')

                <div class="flex flex-col md:flex-row
                            items-center justify-between gap-4">

                    <div>

                        <h2 class="font-semibold text-slate-800">

                            Prêt pour le recensement ?

                        </h2>

                        <p class="text-sm text-slate-500 mt-1">

                            Vous allez commencer le recensement
                            du village
                            <strong>
                                {{ $affectation->village->nom ?? '—' }}
                            </strong>.

                        </p>

                    </div>


                    {{-- Pour l'instant, on prépare le bouton.
                         La route du recensement sera créée ensuite. --}}

                    {{-- <button
                        type="button"
                        disabled
                        class="inline-flex items-center gap-2
                               px-6 py-3 rounded-xl
                               bg-green-600 text-white
                               font-semibold
                               opacity-50 cursor-not-allowed">

                        

                        Commencer le recensement

                    </button> --}}

                    <a
                        href="{{ route(
                            'villages.maisons.index',
                            $affectation->village
                        ) }}"
                        class="inline-flex items-center gap-2
                            px-6 py-3 rounded-xl
                            bg-green-600 text-white
                            font-semibold
                            hover:bg-green-700
                            transition">

                        

                        Commencer le recensement

                    </a>
                </div>

            @else

                <div class="flex items-center gap-3
                            rounded-xl bg-slate-50
                            border border-slate-200
                            px-5 py-4">

                    <span class="text-xl">
                        
                    </span>

                    <div>

                        <p class="font-semibold text-slate-700">
                            Recensement indisponible
                        </p>

                        <p class="text-sm text-slate-500">
                            Cette affectation n'est pas active.
                        </p>

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection