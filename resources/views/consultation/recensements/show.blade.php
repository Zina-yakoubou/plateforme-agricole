@extends('layouts.app')

@section('title', 'Recensement')
@section('page-title', 'Recensement')
@section('page-subtitle', 'Consultation')

@section('content')
@php
    $date = fn ($d) => $d ? \Carbon\Carbon::parse($d)->format('d/m/Y à H:i') : '—';
    $m    = $recensement->maison;
@endphp

<div class="mx-auto max-w-4xl">

    <a href="{{ route($prefixe.'.recensements.index') }}"
       class="mb-4 inline-block text-sm text-green-700 hover:underline">← Retour à la liste</a>

    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-semibold text-text-primary">Maison n° {{ $m?->numeroMaison }}</h1>
            <p class="text-sm text-text-secondary">
                Village de {{ $m?->village?->nom }} · {{ $m?->village?->canton?->commune?->nom }}
            </p>
        </div>
        <span class="rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-700">
            {{ ucfirst(str_replace('_', ' ', $recensement->statut)) }}
        </span>
    </div>

    <div class="grid gap-4 md:grid-cols-2">

        <section class="rounded-xl border border-slate-200 bg-white p-5">
            <h2 class="mb-3 font-semibold">Campagne</h2>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between"><dt class="text-text-secondary">Code</dt><dd>{{ $recensement->campagne?->codeCampagne }}</dd></div>
                <div class="flex justify-between"><dt class="text-text-secondary">Libellé</dt><dd>{{ $recensement->campagne?->libelle }}</dd></div>
            </dl>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white p-5">
            <h2 class="mb-3 font-semibold">Agent</h2>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between"><dt class="text-text-secondary">Nom</dt><dd>{{ $recensement->agent?->name ?? '—' }}</dd></div>
                <div class="flex justify-between"><dt class="text-text-secondary">E-mail</dt><dd>{{ $recensement->agent?->email ?? '—' }}</dd></div>
            </dl>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white p-5">
            <h2 class="mb-3 font-semibold">Maison</h2>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between"><dt class="text-text-secondary">Identifiant</dt><dd>{{ $m?->uid }}</dd></div>
                <div class="flex justify-between"><dt class="text-text-secondary">Adresse</dt><dd>{{ $m?->adresse ?? '—' }}</dd></div>
                <div class="flex justify-between"><dt class="text-text-secondary">Chef de ménage</dt><dd>{{ $m?->nomChef ?? '—' }}</dd></div>
                <div class="flex justify-between"><dt class="text-text-secondary">GPS</dt>
                    <dd>{{ $m?->latitude && $m?->longitude ? $m->latitude.', '.$m->longitude : '—' }}</dd></div>
            </dl>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white p-5">
            <h2 class="mb-3 font-semibold">Suivi</h2>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between"><dt class="text-text-secondary">Début</dt><dd>{{ $date($recensement->dateDebut) }}</dd></div>
                <div class="flex justify-between"><dt class="text-text-secondary">Dernière modification</dt><dd>{{ $date($recensement->dateDerniereModification) }}</dd></div>
                <div class="flex justify-between"><dt class="text-text-secondary">Terminaison</dt><dd>{{ $date($recensement->dateTerminaison) }}</dd></div>
                <div class="flex justify-between"><dt class="text-text-secondary">Validation</dt><dd>{{ $date($recensement->dateValidation) }}</dd></div>
            </dl>
        </section>
    </div>

    @if($recensement->observations)
        <section class="mt-4 rounded-xl border border-slate-200 bg-white p-5">
            <h2 class="mb-2 font-semibold">Observations</h2>
            <p class="whitespace-pre-line text-sm text-text-secondary">{{ $recensement->observations }}</p>
        </section>
    @endif
</div>
@endsection