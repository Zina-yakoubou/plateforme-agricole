@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    {{-- EN-TÊTE --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

        <div>

            <div class="flex items-center gap-2 text-sm text-slate-500 mb-2">

                <a
                    href="{{ route('campagnes.show', $campagne) }}"
                    class="hover:text-blue-600"
                >
                    {{ $campagne->libelle }}
                </a>

                <span>/</span>

                <span>Planification</span>

            </div>

            <h1 class="text-2xl font-bold text-slate-800">
                Calendrier de la campagne
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                {{ $campagne->codeCampagne }}
            </p>

        </div>

    </div>


    {{-- INFORMATIONS --}}
    <div class="grid gap-4 md:grid-cols-3">

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="text-sm text-slate-500">
                Campagne
            </div>

            <div class="mt-1 font-semibold text-slate-800">
                {{ $campagne->libelle }}
            </div>

        </div>


        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="text-sm text-slate-500">
                Planifiée par
            </div>

            <div class="mt-1 font-semibold text-slate-800">
                {{ $planification->planifiePar?->name ?? '—' }}
            </div>

        </div>


        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="text-sm text-slate-500">
                Statut
            </div>

            <div class="mt-1 font-semibold text-emerald-600">
                {{ ucfirst(str_replace('_', ' ', $planification->statut)) }}
            </div>

        </div>

    </div>


    {{-- CALENDRIER --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 p-6">

            <h2 class="text-lg font-semibold text-slate-800">
                Calendrier des activités
            </h2>

        </div>


        <div class="divide-y divide-slate-200">

            @forelse($planification->activites as $activite)

                <div class="p-6">

                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                        <div class="flex gap-4">

                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700">
                                {{ $activite->ordre }}
                            </div>

                            <div>

                                <h3 class="font-semibold text-slate-800">
                                    {{ $activite->libelle }}
                                </h3>

                                @if($activite->description)

                                    <p class="mt-1 text-sm text-slate-500">
                                        {{ $activite->description }}
                                    </p>

                                @endif

                            </div>

                        </div>


                        <div class="text-sm text-slate-600 md:text-right">

                            <div>
                                <strong>Début :</strong>
                                {{ $activite->dateDebut->format('d/m/Y H:i') }}
                            </div>

                            <div>
                                <strong>Fin :</strong>
                                {{ $activite->dateFin->format('d/m/Y H:i') }}
                            </div>

                        </div>

                    </div>

                </div>

            @empty

                <div class="p-10 text-center text-slate-500">
                    Aucune activité n'a été définie.
                </div>

            @endforelse

        </div>

    </div>


    @if($planification->observations)

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

            <h2 class="font-semibold text-slate-800">
                Observations
            </h2>

            <p class="mt-2 text-sm text-slate-600">
                {{ $planification->observations }}
            </p>

        </div>

    @endif


    <div>

        <a
            href="{{ route('campagnes.show', $campagne) }}"
            class="inline-flex items-center rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50"
        >
            ← Retour à la campagne
        </a>

    </div>

</div>

@endsection