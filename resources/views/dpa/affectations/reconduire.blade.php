@extends('layouts.app')

@section('page-title', 'Reconduire une équipe')
@section('page-subtitle', 'Reprendre les affectations de cette équipe pour une nouvelle campagne')

@section('content')

<div class="mx-auto max-w-6xl space-y-6">

{{-- =========================================================
     EN-TÊTE
========================================================== --}}
<div class="flex flex-col gap-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm md:flex-row md:items-center md:justify-between">

    <div>
        <div class="mb-2 flex items-center gap-2">
            <a
                href="{{ route('dpa.equipes.show', $equipe) }}"
                class="text-sm font-medium text-gray-500 transition hover:text-[#266486]"
            >
                Équipes
            </a>

            <span class="text-gray-300">/</span>

            <span class="text-sm text-gray-500">
                Reconduction
            </span>
        </div>

        <h1 class="text-2xl font-bold tracking-tight text-gray-900">
            Reconduire l’équipe
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Créez de nouvelles affectations à partir des affectations existantes de cette équipe.
        </p>
    </div>

    <a
        href="{{ route('dpa.equipes.show', $equipe) }}"
        class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
    >
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15 19l-7-7 7-7"
            />
        </svg>

        Retour à l'équipe
    </a>

</div>


{{-- =========================================================
     MESSAGES
========================================================== --}}
@if(session('success'))
    <div class="flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 p-4 text-green-800">
        <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M5 13l4 4L19 7"
            />
        </svg>

        <div class="text-sm font-medium">
            {{ session('success') }}
        </div>
    </div>
@endif

@if(session('error'))
    <div class="flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">
        <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 8v4m0 4h.01M10.29 3.86l-7.5 13A2 2 0 004.53 20h14.94a2 2 0 001.74-3.14l-7.5-13a2 2 0 00-3.42 0z"
            />
        </svg>

        <div class="text-sm font-medium">
            {{ session('error') }}
        </div>
    </div>
@endif

@if($errors->any())
    <div class="rounded-xl border border-red-200 bg-red-50 p-4">
        <div class="flex items-start gap-3">
            <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8v4m0 4h.01M10.29 3.86l-7.5 13A2 2 0 004.53 20h14.94a2 2 0 001.74-3.14l-7.5-13a2 2 0 00-3.42 0z"
                />
            </svg>

            <div>
                <p class="text-sm font-semibold text-red-800">
                    Vérifiez les informations saisies.
                </p>

                <ul class="mt-2 space-y-1 text-sm text-red-700">
                    @foreach($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif


{{-- =========================================================
     INFORMATIONS ÉQUIPE
========================================================== --}}
<div class="grid grid-cols-1 gap-4 md:grid-cols-3">

    {{-- Équipe --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
        <div class="flex items-center gap-3">

            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#266486]/10 text-[#266486]">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                    />
                </svg>
            </div>

            <div class="min-w-0">
                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Équipe
                </p>

                <p class="truncate text-base font-bold text-gray-900">
                    {{ $equipe->nom }}
                </p>

                @if($equipe->reference)
                    <p class="mt-0.5 text-xs text-gray-500">
                        {{ $equipe->reference }}
                    </p>
                @endif
            </div>

        </div>
    </div>


    {{-- Superviseur --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
        <div class="flex items-center gap-3">

            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                    />
                </svg>
            </div>

            <div class="min-w-0">
                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Superviseur
                </p>

                <p class="truncate text-base font-bold text-gray-900">
                    {{ $equipe->superviseur?->name ?? 'Non affecté' }}
                </p>
            </div>

        </div>
    </div>


    {{-- Membres --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
        <div class="flex items-center gap-3">

            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-6-6m3-3a4 4 0 10-3.464-6"
                    />
                </svg>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Membres
                </p>

                <p class="text-base font-bold text-gray-900">
                    {{ $equipe->membres?->count() ?? 0 }}
                </p>
            </div>

        </div>
    </div>

</div>


{{-- =========================================================
     FORMULAIRE
========================================================== --}}
<form
    method="POST"
    action="{{ route('dpa.equipes.reconduire.store', $equipe) }}"
    x-data="{
        selectedVillages: @js(old('villages', [])),

        toggleVillage(id) {
            id = String(id);

            if (this.selectedVillages.includes(id)) {
                this.selectedVillages =
                    this.selectedVillages.filter(v => v !== id);
            } else {
                this.selectedVillages.push(id);
            }
        },

        isSelected(id) {
            return this.selectedVillages.includes(String(id));
        },

        selectAll() {
            this.selectedVillages = @js($villages->pluck('idVillage')->map(fn($id) => (string) $id));
        },

        deselectAll() {
            this.selectedVillages = [];
        }
    }"
>
    @csrf

    {{-- reste du formulaire --}}
</form>

</div>

@endsection
