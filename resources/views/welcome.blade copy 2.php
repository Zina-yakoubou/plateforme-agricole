@extends('layouts.accueil')

@section('content')

{{-- =========================================================
     NAVBAR
========================================================= --}}
{{-- <nav class="sticky top-0 z-50 border-b border-slate-200 bg-white">
    <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-6 lg:px-8">

        <a href="{{ url('/') }}" class="flex items-center gap-3">

            <img
                src="{{ asset('images/logo_recensement_agricole.png') }}"
                alt="logo_recensement"
                class="h-14 w-14 object-contain"
            >

            <div class="leading-tight">
                <div class="text-xl font-extrabold tracking-tight text-[#163042]">
                    SIRA-Mô
                </div>

                <div class="mt-0.5 text-[10px] font-semibold uppercase tracking-[0.16em] text-slate-500">
                    Recensement agricole
                </div>
            </div>

        </a>


        <a
            href="{{ route('login') }}"
            class="inline-flex items-center justify-center rounded-md bg-[#266486] px-6 py-3 text-sm font-semibold text-white shadow-sm transition duration-200 hover:bg-[#1A4A63]"
        >
            Se connecter
        </a>

    </div>
</nav> --}}


{{-- =========================================================
     NAVBAR
========================================================= --}}
<nav class="sticky top-0 z-50 border-b border-[#dee2e6] bg-white shadow-sm">

    <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-6 lg:px-8">

        {{-- Logo --}}
        <a href="{{ url('/') }}" class="flex items-center gap-3">

            <img
                src="{{ asset('images/logo_recensement_agricole_mo.png') }}"
                alt="logo_recensement"
                class="h-14 w-14 object-contain"
            >

            <div class="leading-tight">
                <div class="text-xl font-bold text-[#212529]">
                    SIRA-Mô
                </div>

                <div class="text-[10px] font-medium uppercase tracking-[0.14em] text-[#363636]">
                    Recensement agricole
                </div>
            </div>

        </a>


        {{-- Bouton connexion --}}
        <a
            href="{{ route('login') }}"
            class="inline-flex items-center justify-center rounded-[5px] border border-[#43a842] bg-[#43a842] px-4 py-2.5 text-sm font-semibold text-white transition-colors duration-150 ease-in-out hover:border-[#026b58] hover:bg-[#026b58]"
        >
            Se connecter
        </a>

    </div>
    

</nav>


{{-- =========================================================
     HERO
========================================================= --}}
<main>

    <section class="relative overflow-hidden bg-white-900">

        {{-- Décorations très légères --}}
        <div class="pointer-events-none absolute -right-40 top-20 h-96 w-96 rounded-full bg-[#266486]/5 blur-3xl"></div>
        <div class="pointer-events-none absolute -left-40 bottom-0 h-96 w-96 rounded-full bg-emerald-100/30 blur-3xl"></div>


        <div class="relative mx-auto max-w-7xl px-6 py-16 lg:px-8 lg:py-24">

            <div class="grid items-center gap-12 lg:grid-cols-[1fr_0.9fr] lg:gap-16">


                {{-- =================================================
                     TEXTE
                ================================================= --}}
                <div class="max-w-2xl">

                    {{-- Petit label --}}
                    <div class="mb-6 flex items-center gap-3">

                        <span class="h-px w-10 bg-[#266486]"></span>

                        <span class="text-sm font-bold uppercase tracking-[0.16em] text-[#266486]">
                            Préfecture de Mô
                        </span>

                    </div>


                    {{-- Titre --}}
                    <h1 class="text-4xl font-extrabold leading-[1.1] tracking-tight text-[#163042] sm:text-5xl lg:text-6xl">

                        Système d'Information
                        <span class="block text-[#266486]">
                            et de Recensement
                        </span>

                        <span class="block">
                            Agricole de Mô
                        </span>

                    </h1>


                    {{-- Description --}}
                    <p class="mt-7 max-w-xl text-lg leading-8 text-slate-600">

                        SIRA-Mô est une plateforme numérique dédiée à
                        l'identification, au recensement et au suivi des
                        producteurs et des exploitations agricoles dans
                        la préfecture de Mô.

                    </p>


                    {{-- Petite phrase --}}
                    <div class="mt-6 flex flex-wrap items-center gap-x-6 gap-y-3 text-sm font-medium text-slate-500">

                        <span class="flex items-center gap-2">

                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[#266486]/10 text-[#266486]">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-3.5 w-3.5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                            </span>

                            Identifier

                        </span>


                        <span class="flex items-center gap-2">

                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[#266486]/10 text-[#266486]">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-3.5 w-3.5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                            </span>

                            Recenser

                        </span>


                        <span class="flex items-center gap-2">

                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[#266486]/10 text-[#266486]">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-3.5 w-3.5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                            </span>

                            Suivre

                        </span>

                    </div>


                    {{-- Bouton --}}
                    <div class="mt-9">

                        <a
                            href="{{ route('login') }}"
                            class="inline-flex items-center gap-3 rounded-md bg-[#266486] px-7 py-3.5 text-sm font-bold text-white shadow-md shadow-[#266486]/15 transition duration-200 hover:bg-[#1A4A63]"
                        >

                            Accéder à la plateforme

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M13 7l5 5m0 0-5 5m5-5H6"
                                />
                            </svg>

                        </a>

                    </div>

                </div>


                {{-- =================================================
                     IMAGE AGENT RECENSEUR
                     PAS DE CARD
                ================================================= --}}
                <div class="relative flex items-center justify-center lg:justify-end">

                    {{-- Forme décorative derrière l'image --}}
                    <div class="absolute h-[360px] w-[360px] rounded-full bg-[#266486]/5 sm:h-[440px] sm:w-[440px]"></div>

                    <div class="absolute h-[290px] w-[290px] rounded-full border border-[#266486]/10 sm:h-[370px] sm:w-[370px]"></div>


                    {{-- Image --}}
                    <img
                        src="{{ asset('images/image-agentRencenseur.png') }}"
                        alt="Agent recenseur agricole"
                        class="relative z-10 w-full max-w-[520px] object-contain"
                    >

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
         CONTACT
    ========================================================= --}}
    <section class="border-t border-slate-200 bg-slate-50">

        <div class="mx-auto max-w-7xl px-6 py-14 lg:px-8">

            <div class="grid gap-10 md:grid-cols-3 md:items-center">

                {{-- Titre --}}
                <div>

                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#266486]">
                        Contact
                    </p>

                    <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-[#163042]">
                        Besoin d'informations ?
                    </h2>

                </div>


                {{-- Institution --}}
                <div class="flex items-start gap-4">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-[#266486]/10 text-[#266486]">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 21h18M5 21V10l7-6 7 6v11M9 21v-6h6v6"
                            />
                        </svg>

                    </div>

                    <div>

                        <p class="font-bold text-slate-900">
                            Préfecture de Mô
                        </p>

                        <p class="mt-1 text-sm leading-6 text-slate-500">
                            Service chargé du recensement
                            et du suivi des données agricoles.
                        </p>

                    </div>

                </div>


                {{-- Coordonnées --}}
                <div class="space-y-3 text-sm text-slate-600">

                    <div class="flex items-center gap-3">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 text-[#266486]"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 8l9 6 9-6M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2Z"
                            />
                        </svg>

                        <span>
                            Contact institutionnel
                        </span>

                    </div>


                    <div class="flex items-center gap-3">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 text-[#266486]"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 5h2l2 5-2.5 1.5A15 15 0 0 0 12.5 19L14 16.5l5 2v2a1 1 0 0 1-1 1C9.7 21.5 2.5 14.3 2.5 6a1 1 0 0 1 1-1Z"
                            />
                        </svg>

                        <span>
                            Préfecture de Mô
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </section>

</main>


{{-- =========================================================
     FOOTER
========================================================= --}}
<footer class="border-t border-slate-200 bg-white">

    <div class="mx-auto flex max-w-7xl flex-col gap-4 px-6 py-7 sm:flex-row sm:items-center sm:justify-between lg:px-8">

        {{-- Identité --}}
        <div class="flex items-center gap-3">

            <img
                src="{{ asset('images/logo_recensement_agricole_mo.png') }}"
                alt="Logo SIRA-Mô"
                class="h-9 w-9 object-contain"
            >

            <div>

                <p class="text-sm font-bold text-[#163042]">
                    SIRA-Mô
                </p>

                <p class="text-xs text-slate-500">
                    Système d'Information et de Recensement Agricole
                </p>

            </div>

        </div>


        {{-- Copyright --}}
        <p class="text-xs text-slate-500">
            © {{ date('Y') }} SIRA-Mô. Tous droits réservés.
        </p>

    </div>

</footer>

@endsection