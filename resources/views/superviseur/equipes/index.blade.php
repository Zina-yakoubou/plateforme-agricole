@extends('layouts.app')

@section('page-title', 'Mes équipes')
@section('page-subtitle', 'Toutes les équipes sous votre supervision.')

@section('content')

<div class="mx-auto max-w-7xl space-y-6">

    <form method="GET" class="flex gap-3">
        <input
            type="text"
            name="search"
            value="{{ $search }}"
            placeholder="Rechercher une équipe..."
            class="w-full rounded-lg border-gray-300"
        >

        <button class="rounded-lg bg-green-700 px-5 text-white">
            Rechercher
        </button>
    </form>

    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-5">

        @forelse($equipes as $equipe)

            <div class="rounded-xl border bg-white p-5 shadow-sm">

                <div class="flex justify-between items-start">

                    <div>
                        <p class="text-xs text-gray-500">
                            {{ $equipe->reference }}
                        </p>

                        <h2 class="font-bold text-lg">
                            {{ $equipe->libelle }}
                        </h2>
                    </div>

                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs text-green-700">
                        {{ ucfirst($equipe->statut) }}
                    </span>

                </div>

                <div class="mt-4 text-sm space-y-2 text-gray-600">

                    <p>
                        👥 {{ $equipe->membres->count() }} agents
                    </p>

                    <p>
                        📍 {{ $equipe->affectations->count() }} villages affectés
                    </p>

                    <p>
                        🌾
                        {{ optional($equipe->affectations->first()?->campagne)->libelle ?? 'Aucune campagne' }}
                    </p>

                </div>

                <a
                    href="{{ route('superviseur.equipes.show', $equipe) }}"
                    class="mt-5 block rounded-lg bg-green-700 py-2 text-center text-white"
                >
                    Voir l'équipe
                </a>

            </div>

        @empty

            <div class="col-span-full rounded-xl border bg-white p-8 text-center text-gray-500">
                Aucune équipe ne vous est affectée.
            </div>

        @endforelse

    </div>

    <div>
        {{ $equipes->links() }}
    </div>

</div>

@endsection