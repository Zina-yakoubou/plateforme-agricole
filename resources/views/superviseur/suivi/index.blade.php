@extends('layouts.app')

@section('page-title', 'Suivi du recensement')

@section(
    'page-subtitle',
    'Suivi de la progression des agents recenseurs de vos équipes.'
)

@section('content')

<div class="mx-auto max-w-7xl space-y-6">

    {{-- ===========================================================
        CAMPAGNE ACTIVE
    ============================================================ --}}
    <div class="rounded-2xl border border-emerald-100 bg-gradient-to-r from-emerald-600 to-green-700 p-6 text-white shadow-sm">

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

            <div>

                <p class="text-xs uppercase tracking-widest text-emerald-100">
                    Campagne en cours
                </p>

                <h2 class="mt-1 text-2xl font-bold">
                    {{ $campagneActive?->libelle ?? 'Aucune campagne active' }}
                </h2>

                @if($campagneActive)
                    <p class="mt-1 text-sm text-emerald-100">
                        Code : {{ $campagneActive->codeCampagne }}
                    </p>
                @endif

            </div>

            @if($campagneActive)
                <span class="rounded-full bg-white/20 px-4 py-2 text-sm font-semibold">
                    Active
                </span>
            @endif

        </div>

    </div>

    {{-- ===========================================================
        STATISTIQUES
    ============================================================ --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Agents supervisés</p>

            <h3 class="mt-2 text-3xl font-bold text-emerald-700">
                {{ $stats['agents'] }}
            </h3>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Villages affectés</p>

            <h3 class="mt-2 text-3xl font-bold text-blue-700">
                {{ $stats['villages'] }}
            </h3>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Maisons recensées</p>

            <h3 class="mt-2 text-3xl font-bold text-orange-600">
                {{ $stats['maisons'] }}
            </h3>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Exploitants recensés</p>

            <h3 class="mt-2 text-3xl font-bold text-purple-700">
                {{ $stats['exploitants'] }}
            </h3>
        </div>

    </div>

    {{-- ===========================================================
        PROGRESSION GLOBALE
    ============================================================ --}}
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-slate-800">
                Progression globale de la supervision
            </h3>

            <span class="text-lg font-bold text-emerald-700">
                {{ $stats['progression'] }} %
            </span>
        </div>

        <div class="h-4 w-full overflow-hidden rounded-full bg-slate-200">
            <div
                class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-green-600"
                style="width: {{ $stats['progression'] }}%"
            ></div>
        </div>

        <p class="mt-3 text-sm text-slate-500">
            Cette progression représente l'avancement moyen des villages affectés à vos équipes.
        </p>

    </div>

    {{-- ===========================================================
        TABLEAU DES AGENTS
    ============================================================ --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">

        <div class="border-b border-slate-200 px-6 py-4">
            <h3 class="text-lg font-semibold text-slate-800">
                Progression des agents recenseurs
            </h3>

            <p class="text-sm text-slate-500">
                Suivi individuel des performances des agents de vos équipes.
            </p>
        </div>

        @if($agents->count())

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">

                        <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-500">

                            <th class="px-6 py-3">Agent</th>

                            <th class="px-6 py-3">Équipe</th>

                            <th class="px-6 py-3">Village</th>

                            <th class="px-6 py-3 text-center">Maisons</th>

                            <th class="px-6 py-3 text-center">Exploitants</th>

                            <th class="px-6 py-3">Progression</th>

                            <th class="px-6 py-3 text-center">Statut</th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">

                        @foreach($agents as $agent)

                            <tr class="hover:bg-slate-50 transition">

                                {{-- Agent --}}
                                <td class="px-6 py-4">

                                    <div class="font-medium text-slate-800">
                                        {{ $agent->name }}
                                    </div>

                                    <div class="text-xs text-slate-500">
                                        {{ $agent->telephone }}
                                    </div>

                                </td>

                                {{-- Équipe --}}
                                <td class="px-6 py-4 text-slate-700">
                                    {{ $agent->equipe?->libelle ?? '-' }}
                                </td>

                                {{-- Village --}}
                                <td class="px-6 py-4 text-slate-700">
                                    {{ $agent->village?->nom ?? '-' }}
                                </td>

                                {{-- Maisons --}}
                                <td class="px-6 py-4 text-center font-semibold text-slate-800">
                                    {{ $agent->maisons_recensees }}
                                </td>

                                {{-- Exploitants --}}
                                <td class="px-6 py-4 text-center font-semibold text-slate-800">
                                    {{ $agent->exploitants_recenses }}
                                </td>

                                {{-- Progression --}}
                                <td class="px-6 py-4 w-64">

                                    <div class="flex items-center gap-3">

                                        <div class="flex-1 h-2 rounded-full bg-slate-200 overflow-hidden">

                                            <div
                                                class="h-full rounded-full bg-emerald-500"
                                                style="width: {{ $agent->progression }}%"
                                            ></div>

                                        </div>

                                        <span class="text-sm font-semibold text-emerald-700 w-12 text-right">
                                            {{ $agent->progression }}%
                                        </span>

                                    </div>

                                </td>

                                {{-- Statut --}}
                                <td class="px-6 py-4 text-center">

                                    @if($agent->progression >= 100)

                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                            Terminé
                                        </span>

                                    @elseif($agent->progression >= 60)

                                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                            En bonne voie
                                        </span>

                                    @elseif($agent->progression > 0)

                                        <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                            En cours
                                        </span>

                                    @else

                                        <span class="rounded-full bg-slate-200 px-3 py-1 text-xs font-semibold text-slate-600">
                                            Non démarré
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="px-6 py-12 text-center">

                <svg class="mx-auto h-14 w-14 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        d="M9 17v-6m3 6V7m3 10v-3m4 7H5a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2z"/>
                </svg>

                <p class="mt-4 text-sm text-slate-500">
                    Aucun agent n'est encore affecté à vos équipes.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection