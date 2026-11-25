@extends('layouts.app')

@section('page-title', 'Suivi du recensement')
@section('page-subtitle', 'Suivi opérationnel de l\'activité terrain de vos équipes sur la campagne active.')

@section('content')

@php
    $campagne = $campagneActive ?? null;
@endphp

<div class="space-y-6">

    {{-- ===========================================================
        CAMPAGNE ACTIVE
    ============================================================ --}}
    <div class="rounded-2xl border border-emerald-100 bg-gradient-to-r from-emerald-600 to-green-700 p-6 text-white shadow-sm">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs uppercase tracking-widest text-emerald-100">Campagne en cours</p>
                <h2 class="mt-1 text-2xl font-bold">
                    {{ $campagne?->libelle ?? 'Aucune campagne active' }}
                </h2>
                @if($campagne)
                    <p class="mt-1 text-sm text-emerald-100">
                        Périmètre : activités de vos équipes sur les villages affectés pour cette campagne.
                    </p>
                @endif
            </div>

            <span class="rounded-full bg-white/20 px-4 py-2 text-sm font-semibold">
                {{ $campagne ? 'Active' : 'Inactive' }}
            </span>
        </div>
    </div>

    @if(! $campagne)

        <div class="rounded-xl border border-amber-200 bg-amber-50 p-5 text-sm text-amber-900 shadow-sm">
            <h3 class="font-semibold">Aucune campagne de recensement active</h3>
            <p class="mt-1">Le suivi terrain est désactivé tant qu'aucune campagne n'est en cours.</p>
        </div>

    @else

        {{-- ===========================================================
            INDICATEURS TERRAIN RÉELS
        ============================================================ --}}
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Membres d'équipes</p>
                <h3 class="mt-2 text-3xl font-bold text-emerald-700">{{ $stats['agents'] }}</h3>
                <p class="mt-1 text-xs text-slate-400">Agents sous votre supervision</p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Villages affectés</p>
                <h3 class="mt-2 text-3xl font-bold text-blue-700">
                    {{ $stats['villages_actifs'] }} <span class="text-base font-normal text-slate-400">/ {{ $stats['villages'] }}</span>
                </h3>
                <p class="mt-1 text-xs text-slate-400">Villages avec activité ou visite</p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Maisons recensées</p>
                <h3 class="mt-2 text-3xl font-bold text-orange-600">{{ $stats['maisons'] }}</h3>
                <p class="mt-1 text-xs text-slate-400">{{ $stats['maisons_visitees'] }} maison(s) contrôlée(s)</p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Exploitants recensés</p>
                <h3 class="mt-2 text-3xl font-bold text-purple-700">{{ $stats['exploitants'] }}</h3>
                <p class="mt-1 text-xs text-slate-400">Enregistrés sur la campagne active</p>
            </div>
        </div>

        {{-- ===========================================================
            TABLEAU DE SUIVI DES AGENTS
        ============================================================ --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="border-b border-slate-200 px-6 py-4">
                <h3 class="text-lg font-semibold text-slate-800">Activité des membres de vos équipes</h3>
                <p class="text-sm text-slate-500">
                    Volume de saisie, date de dernière activité et visites de supervision effectuées.
                </p>
            </div>

            @if($lignesSuivi->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-6 py-3">Agent</th>
                                <th class="px-6 py-3">Équipe</th>
                                <th class="px-6 py-3">Village(s) affecté(s)</th>
                                <th class="px-6 py-3 text-center">Maisons</th>
                                <th class="px-6 py-3 text-center">Exploitants</th>
                                <th class="px-6 py-3 text-center">État d'activité</th>
                                <th class="px-6 py-3">Visites terrain</th>
                                <th class="px-6 py-3 text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach($lignesSuivi as $ligne)
                                <tr class="hover:bg-slate-50 transition">
                                    {{-- Nom de l'agent --}}
                                    <td class="px-6 py-4 font-medium text-slate-800">
                                        {{ $ligne->agent_nom }}
                                    </td>

                                    {{-- Équipe --}}
                                    <td class="px-6 py-4 text-slate-700">
                                        {{ $ligne->equipe_libelle }}
                                    </td>

                                    {{-- Village(s) --}}
                                    <td class="px-6 py-4 text-slate-700">
                                        @if($ligne->villages->isNotEmpty())
                                            <div class="font-medium text-slate-800">
                                                {{ $ligne->villages->pluck('nom')->join(', ') }}
                                            </div>
                                            @php
                                                $prefectureNom = $ligne->village_principal?->canton?->commune?->prefecture?->nom ?? null;
                                            @endphp
                                            @if($prefectureNom)
                                                <div class="text-xs text-slate-500">
                                                    Préfecture : {{ $prefectureNom }}
                                                </div>
                                            @endif
                                        @else
                                            <span class="text-xs text-slate-400">Aucune affectation</span>
                                        @endif
                                    </td>

                                    {{-- Maisons recensées --}}
                                    <td class="px-6 py-4 text-center font-semibold text-slate-800">
                                        {{ $ligne->maisons_recensees }}
                                    </td>

                                    {{-- Exploitants recensés --}}
                                    <td class="px-6 py-4 text-center font-semibold text-slate-800">
                                        {{ $ligne->exploitants_recenses }}
                                    </td>

                                    {{-- État d'activité & Dernière saisie --}}
                                    <td class="px-6 py-4 text-center">
                                        @if($ligne->maisons_recensees > 0)
                                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                                Actif
                                            </span>
                                        @else
                                            <span class="rounded-full bg-slate-200 px-3 py-1 text-xs font-semibold text-slate-600">
                                                Aucune saisie
                                            </span>
                                        @endif

                                        @if($ligne->derniere_activite)
                                            <div class="mt-1 text-xs text-slate-500">
                                                Dernière : {{ \Carbon\Carbon::parse($ligne->derniere_activite)->format('d/m/Y H:i') }}
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Visites terrain du superviseur --}}
                                    <td class="px-6 py-4 text-xs text-slate-700">
                                        <div class="font-semibold">
                                            {{ $ligne->maisons_visitees }} maison(s) visitée(s)
                                        </div>
                                        @if($ligne->derniere_visite)
                                            <div class="text-slate-500">
                                                Dernière : {{ \Carbon\Carbon::parse($ligne->derniere_visite->date_visite)->format('d/m/Y H:i') }}
                                            </div>
                                        @else
                                            <div class="text-slate-400">
                                                Aucune visite
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Action : Voir les maisons --}}
                                    <td class="px-6 py-4 text-center">
                                        @if($ligne->village_principal)
                                            <a
                                                href="{{ route('superviseur.suivi.maisons', $ligne->village_principal->idVillage) }}"
                                                class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700 transition"
                                            >
                                                Voir les maisons
                                            </a>
                                        @else
                                            <span class="text-xs text-slate-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-12 text-center text-sm text-slate-500">
                    Aucun membre d'équipe ou aucune affectation active pour cette campagne.
                </div>
            @endif
        </div>

    @endif

</div>

@endsection