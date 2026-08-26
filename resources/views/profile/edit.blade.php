@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-slate-50 px-6 py-8 lg:px-10">

    {{-- En-tête --}}
    {{-- <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-slate-800">
            Mon profil
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            Gérez vos informations personnelles et la sécurité de votre compte.
        </p>
    </div> --}}

    <div class="mx-auto max-w-6xl space-y-6">

        {{-- =========================================
             INFORMATIONS DU PROFIL
        ========================================== --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            {{-- En-tête carte --}}
            <div class="border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white px-6 py-5">
                <div class="flex items-center gap-4">

                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100 text-lg font-bold text-emerald-700">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>

                    <div>
                        <h2 class="text-lg font-semibold text-slate-800">
                            {{ $user->name }}
                        </h2>

                        <p class="text-sm text-slate-500">
                            {{ $user->role->nom ?? 'Utilisateur' }}
                        </p>
                    </div>

                </div>
            </div>

            {{-- Informations --}}
            <div class="grid gap-6 px-6 py-6 md:grid-cols-2">

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Adresse e-mail
                    </p>

                    <p class="mt-1 font-medium text-slate-700">
                        {{ $user->email }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Téléphone
                    </p>

                    <p class="mt-1 font-medium text-slate-700">
                        {{ $user->telephone ?? 'Non renseigné' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Rôle
                    </p>

                    <span class="mt-1 inline-flex rounded-full bg-emerald-50 px-3 py-1 text-sm font-medium text-emerald-700">
                        {{ $user->role->nom ?? 'Utilisateur' }}
                    </span>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Statut du compte
                    </p>

                    <span class="mt-1 inline-flex rounded-full bg-emerald-50 px-3 py-1 text-sm font-medium text-emerald-700">
                        Actif
                    </span>
                </div>

            </div>

            {{-- Formulaire informations --}}
            <div class="border-t border-slate-100 px-6 py-6">
                @include('profile.partials.update-profile-information-form')
            </div>

        </div>


        {{-- =========================================
             SÉCURITÉ
        ========================================== --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-100 px-6 py-5">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 text-amber-700">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="2">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v2h8z"/>
                        </svg>
                    </div>

                    <div>
                        <h2 class="text-lg font-semibold text-slate-800">
                            Sécurité du compte
                        </h2>

                        <p class="text-sm text-slate-500">
                            Modifiez votre mot de passe pour sécuriser votre compte.
                        </p>
                    </div>

                </div>

            </div>

            <div class="px-6 py-6">
                @include('profile.partials.update-password-form')
            </div>

        </div>

    </div>

</div>

@endsection