@extends('layouts.accueil')

@section('content')

{{-- =========================================================
     NAVIGATION
========================================================= --}}
<nav class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur">

    <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-6 lg:px-8">

        {{-- Logo --}}
        <a href="{{ url('/') }}" class="flex items-center gap-3">

            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-700 text-white shadow-sm">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-6 w-6"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 3v18M5 8c2.5-2.2 4.7-2.7 7-2v8c-2.3-.7-4.5-.2-7 2V8Zm14 0c-2.5-2.2-4.7-2.7-7-2v8c2.3-.7 4.5-.2 7 2V8Z"/>

                </svg>

            </div>


            <div>

                <div class="text-lg font-extrabold tracking-tight text-slate-900">
                    SIRA-Mô
                </div>

                <div class="text-[10px] font-semibold uppercase tracking-[0.18em] text-slate-500">
                    Données agricoles
                </div>

            </div>

        </a>


        {{-- Navigation desktop --}}
        <div class="hidden items-center gap-8 md:flex">

            <a href="#accueil"
               class="text-sm font-semibold text-emerald-700 transition hover:text-emerald-800">
                Accueil
            </a>

            <a href="#campagnes"
               class="text-sm font-semibold text-slate-700 transition hover:text-emerald-700">
                Campagnes
            </a>

            <a href="#contact"
               class="text-sm font-semibold text-slate-700 transition hover:text-emerald-700">
                Contact
            </a>

        </div>


        {{-- Connexion desktop --}}
        <a href="{{ route('login') }}"
           class="hidden rounded-xl bg-slate-900 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-700 md:inline-flex">

            Se connecter

        </a>


        {{-- Connexion mobile --}}
        <a href="{{ route('login') }}"
           class="inline-flex rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-emerald-700 md:hidden">

            Connexion

        </a>

    </div>

</nav>



{{-- =========================================================
     HERO
========================================================= --}}
<section id="accueil"
         class="relative min-h-[calc(100vh-5rem)] overflow-hidden bg-slate-50">


    {{-- Décoration --}}
    <div class="pointer-events-none absolute -right-40 -top-40 h-[500px] w-[500px] rounded-full bg-emerald-100/60 blur-3xl"></div>

    <div class="pointer-events-none absolute -bottom-40 -left-40 h-[500px] w-[500px] rounded-full bg-blue-100/40 blur-3xl"></div>


    <div class="relative mx-auto flex min-h-[calc(100vh-5rem)] max-w-7xl items-center px-6 py-16 lg:px-8 lg:py-20">

        <div class="grid w-full items-center gap-14 lg:grid-cols-2 lg:gap-20">


            {{-- =================================================
                 TEXTE HERO
            ================================================== --}}
            <div>

                {{-- Badge --}}
                <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-white px-4 py-2 text-xs font-bold uppercase tracking-wider text-emerald-700 shadow-sm">

                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>

                    Préfecture de Mô

                </div>


                {{-- Titre --}}
                <h1 class="max-w-2xl text-4xl font-black leading-[1.08] tracking-tight text-slate-950 sm:text-5xl lg:text-6xl">

                    Système d'Information et de

                    <span class="text-emerald-700">
                        Recensement Agricole
                    </span>

                    de Mô

                </h1>


                {{-- Description --}}
                <p class="mt-6 max-w-xl text-lg leading-8 text-slate-600">

                    Une plateforme numérique dédiée à l'identification,
                    au recensement et au suivi des producteurs agricoles
                    de la préfecture de Mô.

                </p>


                {{-- Slogan --}}
                <div class="mt-6 flex flex-wrap items-center gap-3 text-sm font-semibold text-slate-600">

                    <span>
                        Identifier
                    </span>

                    <span class="text-emerald-600">
                        •
                    </span>

                    <span>
                        Recenser
                    </span>

                    <span class="text-emerald-600">
                        •
                    </span>

                    <span>
                        Suivre
                    </span>

                </div>


                {{-- Bouton connexion --}}
                <div class="mt-9">

                    <a href="{{ route('login') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-700 px-7 py-3.5 text-sm font-bold text-white shadow-lg shadow-emerald-700/20 transition duration-200 hover:bg-emerald-800 hover:shadow-xl">

                        Se connecter

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="2">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M13 7l5 5m0 0-5 5m5-5H6"/>

                        </svg>

                    </a>

                </div>


                {{-- Information --}}
                <div class="mt-10 flex items-center gap-3 text-sm text-slate-500">

                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 3v18M5 8c2.5-2.2 4.7-2.7 7-2v8c-2.3-.7-4.5-.2-7 2V8Zm14 0c-2.5-2.2-4.7-2.7-7-2v8c2.3-.7 4.5-.2 7 2V8Z"/>

                        </svg>

                    </div>

                    <span>
                        Système dédié au suivi des données agricoles
                    </span>

                </div>

            </div>



            {{-- =================================================
                 IMAGE À DROITE
            ================================================== --}}
            <div class="relative">


                {{-- Décoration --}}
                <div class="absolute -right-5 -top-5 h-24 w-24 rounded-2xl border-2 border-emerald-200"></div>

                <div class="absolute -bottom-5 -left-5 h-24 w-24 rounded-2xl border-2 border-blue-200"></div>


                {{-- Cadre --}}
                <div class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white p-2 shadow-2xl shadow-slate-900/10">

                    <div class="relative overflow-hidden rounded-2xl">

                        <img
                            src="{{ asset('images/image-agentRencenseur.png') }}"
                            alt="Agent recenseur agricole"
                            class="h-[430px] w-full object-cover transition duration-700 hover:scale-105"
                        >


                        {{-- Dégradé --}}
                        <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-slate-950/40 via-transparent to-transparent"></div>


                        {{-- Informations sur image --}}
                        <div class="absolute bottom-5 left-5 right-5">

                            <div class="rounded-2xl border border-white/30 bg-white/90 p-4 shadow-xl backdrop-blur">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-700 text-white">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-5 w-5"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.8">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M5 13l4 4L19 7"/>

                                        </svg>

                                    </div>


                                    <div>

                                        <p class="text-xs font-medium text-slate-500">
                                            Recensement agricole
                                        </p>

                                        <p class="font-bold text-slate-900">
                                            Préfecture de Mô
                                        </p>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>



{{-- =========================================================
     CAMPAGNES
========================================================= --}}
<section id="campagnes"
         class="border-t border-slate-200 bg-white py-20">

    <div class="mx-auto max-w-7xl px-6 lg:px-8">

        <div class="mx-auto max-w-2xl text-center">

            <p class="text-sm font-bold uppercase tracking-[0.18em] text-emerald-700">
                Campagnes
            </p>

            <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">

                Campagnes de recensement agricole

            </h2>

            <p class="mt-4 leading-7 text-slate-600">

                Les campagnes de recensement sont organisées et suivies
                dans l'espace sécurisé de SIRA-Mô.

            </p>

        </div>


        {{-- Carte campagne --}}
        <div class="mx-auto mt-10 max-w-3xl">

            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6 shadow-sm sm:p-8">

                <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                    <div class="flex items-center gap-4">

                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-6 w-6"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.8">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z"/>

                            </svg>

                        </div>


                        <div>

                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Gestion des campagnes
                            </p>

                            <p class="mt-1 font-bold text-slate-900">
                                Espace réservé aux utilisateurs autorisés
                            </p>

                        </div>

                    </div>


                    <a href="{{ route('login') }}"
                       class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-3 text-sm font-bold text-white transition hover:bg-emerald-700">

                        Accéder

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="2">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M13 7l5 5m0 0-5 5m5-5H6"/>

                        </svg>

                    </a>

                </div>

            </div>

        </div>

    </div>

</section>



{{-- =========================================================
     CONTACT
========================================================= --}}
<section id="contact"
         class="border-t border-slate-200 bg-slate-50 py-20">

    <div class="mx-auto max-w-7xl px-6 lg:px-8">

        <div class="grid gap-12 lg:grid-cols-2 lg:items-center">


            {{-- Texte --}}
            <div>

                <p class="text-sm font-bold uppercase tracking-[0.18em] text-emerald-700">
                    Contact
                </p>

                <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">

                    Besoin d'informations ?

                </h2>

                <p class="mt-5 max-w-xl leading-8 text-slate-600">

                    Pour toute question concernant SIRA-Mô, le recensement
                    agricole ou l'utilisation de la plateforme, veuillez
                    vous rapprocher du service compétent de la préfecture de Mô.

                </p>

            </div>


            {{-- Informations --}}
            <div class="grid gap-4 sm:grid-cols-2">


                {{-- Localisation --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 21s7-5.1 7-11a7 7 0 1 0-14 0c0 5.9 7 11 7 11Z"/>

                            <circle cx="12" cy="10" r="2.5"/>

                        </svg>

                    </div>

                    <p class="mt-4 text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Localisation
                    </p>

                    <p class="mt-1 font-bold text-slate-900">
                        Préfecture de Mô
                    </p>

                </div>


                {{-- Téléphone --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-700">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.09l-4.423-1.106a1.125 1.125 0 0 0-1.173.417l-.97 1.293a1.125 1.125 0 0 1-1.21.38 12.035 12.035 0 0 1-7.21-7.21 1.125 1.125 0 0 1 .38-1.21l1.293-.97c.363-.272.52-.74.417-1.173L6.896 2.934A1.125 1.125 0 0 0 5.806 2.25H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/>

                        </svg>

                    </div>

                    <p class="mt-4 text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Téléphone
                    </p>

                    <p class="mt-1 font-bold text-slate-900">
                        À renseigner
                    </p>

                </div>


                {{-- Email --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-100 text-amber-700">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M3 7.5 12 13l9-5.5M4.5 19.5h15A1.5 1.5 0 0 0 21 18V6a1.5 1.5 0 0 0-1.5-1.5h-15A1.5 1.5 0 0 0 3 6v12a1.5 1.5 0 0 0 1.5 1.5Z"/>

                        </svg>

                    </div>

                    <p class="mt-4 text-xs font-semibold uppercase tracking-wider text-slate-500">
                        E-mail
                    </p>

                    <p class="mt-1 font-bold text-slate-900">
                        À renseigner
                    </p>

                </div>


                {{-- Disponibilité --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-100 text-violet-700">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">

                            <circle cx="12" cy="12" r="9"/>

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 7v5l3 2"/>

                        </svg>

                    </div>

                    <p class="mt-4 text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Disponibilité
                    </p>

                    <p class="mt-1 font-bold text-slate-900">
                        Heures administratives
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>



{{-- =========================================================
     FOOTER
========================================================= --}}
<footer class="border-t border-slate-200 bg-white">

    <div class="mx-auto max-w-7xl px-6 py-8 lg:px-8">

        <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">


            {{-- Logo --}}
            <div class="flex items-center gap-3">

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-700 text-white">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 3v18M5 8c2.5-2.2 4.7-2.7 7-2v8c-2.3-.7-4.5-.2-7 2V8Zm14 0c-2.5-2.2-4.7-2.7-7-2v8c2.3-.7 4.5-.2 7 2V8Z"/>

                    </svg>

                </div>


                <div>

                    <p class="font-extrabold text-slate-900">
                        SIRA-Mô
                    </p>

                    <p class="text-xs text-slate-500">
                        Système d'Information et de Recensement Agricole
                    </p>

                </div>

            </div>


            {{-- Copyright --}}
            <div class="text-sm text-slate-500">

                © {{ date('Y') }} SIRA-Mô.
                Tous droits réservés.

            </div>

        </div>

    </div>

</footer>

@endsection