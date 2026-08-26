@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    {{-- En-tête --}}
    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Détail de l'affectation
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Informations relatives à la mission de l'agent.
            </p>
        </div>

        

    </div>


    {{-- Informations générales --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">

            <h2 class="font-semibold text-slate-800">
                Informations générales
            </h2>

        </div>


        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <p class="text-xs text-slate-400 uppercase">
                    Référence
                </p>

                <p class="mt-1 font-semibold text-slate-800">
                    {{ $affectation->reference }}
                </p>
            </div>


            <div>
                <p class="text-xs text-slate-400 uppercase">
                    Statut
                </p>

                @if($affectation->statut === 'ACTIVE')

                    <span class="inline-flex mt-1 px-3 py-1 rounded-full
                                 text-xs font-semibold bg-green-100 text-green-700">

                        Active

                    </span>

                @else

                    <span class="inline-flex mt-1 px-3 py-1 rounded-full
                                 text-xs font-semibold bg-slate-100 text-slate-600">

                        Désactivée

                    </span>

                @endif

            </div>


            <div>
                <p class="text-xs text-slate-400 uppercase">
                    Agent recenseur
                </p>

                <p class="mt-1 font-medium text-slate-800">
                    {{ $affectation->user->name ?? 'Non défini' }}
                </p>

                @if($affectation->user)

                    <p class="text-sm text-slate-500">
                        {{ $affectation->user->login }}
                    </p>

                @endif
            </div>


            <div>
                <p class="text-xs text-slate-400 uppercase">
                    Campagne
                </p>

                <p class="mt-1 font-medium text-slate-800">
                    {{ $affectation->campagne->nom ?? 'Non définie' }}
                </p>

            </div>


            <div>
                <p class="text-xs text-slate-400 uppercase">
                    Préfecture
                </p>

                <p class="mt-1 font-medium text-slate-800">
                    {{ $affectation->prefecture->nom ?? 'Non définie' }}
                </p>

            </div>


            <div>
                <p class="text-xs text-slate-400 uppercase">
                    Canton
                </p>

                <p class="mt-1 font-medium text-slate-800">
                    {{ $affectation->canton->nom ?? 'Non défini' }}
                </p>

            </div>


            <div>
                <p class="text-xs text-slate-400 uppercase">
                    Village
                </p>

                <p class="mt-1 font-semibold text-green-700">
                    {{ $affectation->village->nom ?? 'Non défini' }}
                </p>

            </div>


            {{-- <div>
                <p class="text-xs text-slate-400 uppercase">
                    Date de début
                </p>

                <p class="mt-1 font-medium text-slate-800">
                    {{ $affectation->dateDebut
                        ? \Carbon\Carbon::parse($affectation->dateDebut)->format('d/m/Y')
                        : 'Non définie' }}
                </p>

            </div>


            <div>
                <p class="text-xs text-slate-400 uppercase">
                    Date de fin
                </p>

                <p class="mt-1 font-medium text-slate-800">
                    {{ $affectation->dateFin
                        ? \Carbon\Carbon::parse($affectation->dateFin)->format('d/m/Y')
                        : 'Non définie' }}
                </p>

            </div> --}}

        </div>

    </div>


    {{-- Actions --}}
    <div class="flex justify-end gap-3">
        <a href="{{ route('affectations.index') }}"
           class="px-4 py-2 rounded-lg bg-gray-500 text-slate-700 hover:bg-slate-200">

            Fermé

        </a>

        @if($affectation->statut === 'ACTIVE')

            <a href="{{ route('affectations.edit', $affectation) }}"
               class="px-5 py-2 rounded-lg bg-blue-600 text-white
                      hover:bg-blue-700">

                Modifier

            </a>


            <form method="POST"
                  action="{{ route('affectations.destroy', $affectation) }}"
                  onsubmit="return confirm('Voulez-vous vraiment désactiver cette affectation ?');">

                @csrf
                @method('DELETE')

                <button type="submit"
                        class="px-5 py-2 rounded-lg bg-red-600 text-white
                               hover:bg-red-700">

                    Désactiver

                </button>

            </form>

        @endif

    </div>

</div>

@endsection