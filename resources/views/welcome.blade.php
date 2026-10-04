<x-guest-layout>

<div class="mx-auto max-w-7xl px-4 py-6 font-['Poppins'] sm:px-6 lg:px-8 lg:py-8">


    {{-- =========================================================
         PRÉSENTATION PRINCIPALE
    ========================================================== --}}
    <section class="mb-7">

        <div class="grid items-center gap-6 lg:grid-cols-[1fr_220px]">


            {{-- TEXTE --}}
            <div>

                {{-- Localisation --}}
                <div class="mb-3 flex flex-wrap items-center gap-2">

                    <span
                        class="inline-flex items-center gap-1.5 rounded-md border border-[#ced4da] bg-[#ebeff4] px-2.5 py-1 text-[11px] font-medium text-[#333333]"
                    >

                        <svg
                            class="h-3.5 w-3.5 text-[#006a4f]"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"
                            />
                        </svg>

                        Préfecture de Mô

                    </span>

                    <span class="text-[11px] text-slate-500">
                        République Togolaise
                    </span>

                </div>


                {{-- TITRE --}}
                <h1 class="text-3xl font-bold tracking-tight text-[#006a4f] sm:text-4xl">
                    SIRA-Mô
                </h1>

                <p class="mt-1 text-sm font-medium text-[#333333] sm:text-base">
                    Système d’Information et de Recensement Agricole de Mô
                </p>


                {{-- DESCRIPTION --}}
                <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-600">
                    Une plateforme web destinée au recensement agricole,
                    à la géolocalisation des parcelles et au suivi des
                    intrants agricoles dans la préfecture de Mô.
                </p>


                {{-- THÈME --}}
                <div class="mt-4 max-w-3xl rounded-md border border-[#ced4da] bg-white px-4 py-3">

                    <div class="flex items-start gap-3">

                        <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-[#e5f2ee] text-[#006a4f]">

                            <svg
                                class="h-4 w-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z"
                                />
                            </svg>

                        </div>


                        <div>

                            <p class="text-[10px] font-bold uppercase tracking-wider text-[#006a4f]">
                                Thème du projet
                            </p>

                            <p class="mt-0.5 text-xs leading-5 text-[#333333]">
                                Conception et réalisation d'une application web de recensement,
                                de suivi et de gestion des intrants agricoles :
                                cas de la préfecture de Mô.
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 ICÔNE SIRA-MÔ + CONNEXION
            ================================================== --}}
            <div class="flex flex-col items-center justify-center">

                {{-- Grande icône SVG --}}
                <div class="flex h-28 w-28 items-center justify-center rounded-2xl bg-[#006a4f] shadow-md">

                    <svg
                        viewBox="0 0 64 64"
                        class="h-20 w-20"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >

                        {{-- Cercle extérieur --}}
                        <circle
                            cx="32"
                            cy="32"
                            r="28"
                            stroke="white"
                            stroke-width="2"
                            stroke-opacity="0.25"
                        />

                        {{-- Tige --}}
                        <path
                            d="M32 50V18"
                            stroke="white"
                            stroke-width="4"
                            stroke-linecap="round"
                        />

                        {{-- Feuille gauche --}}
                        <path
                            d="M32 25C25 18 17 20 14 21C16 29 23 34 32 32"
                            stroke="white"
                            stroke-width="4"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />

                        {{-- Feuille droite --}}
                        <path
                            d="M32 32C39 25 47 27 50 28C48 36 41 40 32 39"
                            stroke="white"
                            stroke-width="4"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />

                        {{-- Sommet --}}
                        <circle
                            cx="32"
                            cy="17"
                            r="3.5"
                            fill="#43a842"
                        />

                    </svg>

                </div>


                {{-- Nom sous l'icône --}}
                <div class="mt-3 text-center">

                    <p class="text-sm font-bold text-[#006a4f]">
                        SIRA-Mô
                    </p>

                    <p class="text-[10px] text-slate-500">
                        Recensement Agricole
                    </p>

                </div>


                {{-- Bouton --}}
                <a
                    href="{{ route('login') }}"
                    class="mt-3 inline-flex items-center gap-2 rounded-md bg-[#43a842] px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#378736] focus:outline-none focus:ring-4 focus:ring-[#43a842]/20"
                >

                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3"
                        />
                    </svg>

                    Accéder au système

                </a>

            </div>

        </div>

    </section>



    {{-- =========================================================
         LES 3 GRANDES ÉTAPES
    ========================================================== --}}
    <section class="mb-6">

        <div class="mb-3 flex items-center justify-between">

            <h2 class="flex items-center gap-2 text-base font-bold text-[#333333]">

                <span class="h-5 w-1 rounded-full bg-[#43a842]"></span>

                Fonctionnement du système

            </h2>

            <span class="hidden text-[11px] text-slate-500 sm:block">
                Du terrain au suivi des intrants
            </span>

        </div>


        <div class="grid grid-cols-1 gap-3 md:grid-cols-3">


            {{-- PRÉPARER --}}
            <div class="rounded-lg border border-[#ced4da] bg-white p-4 transition hover:border-[#43a842]">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[#e5f2ee] text-[#006a4f]">

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5H5a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-4M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m0 0h4a2 2 0 012 2v3"
                            />
                        </svg>

                    </div>

                    <div>

                        <p class="text-[10px] font-bold uppercase tracking-wider text-[#006a4f]">
                            01 • Préparer
                        </p>

                        <h3 class="text-sm font-bold text-[#333333]">
                            Organisation
                        </h3>

                    </div>

                </div>

                <p class="mt-3 text-xs leading-5 text-slate-600">
                    Campagne → Planification → Équipes → Affectations → Déploiement
                </p>

            </div>


            {{-- COLLECTER --}}
            <div class="rounded-lg border border-[#ced4da] bg-white p-4 transition hover:border-[#43a842]">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[#e5f2ee] text-[#43a842]">

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17.657 16.657L13.414 21a2 2 0 01-2.828 0l-4.243-4.343a8 8 0 1111.314 0z"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                        </svg>

                    </div>

                    <div>

                        <p class="text-[10px] font-bold uppercase tracking-wider text-[#43a842]">
                            02 • Collecter
                        </p>

                        <h3 class="text-sm font-bold text-[#333333]">
                            Recensement
                        </h3>

                    </div>

                </div>

                <p class="mt-3 text-xs leading-5 text-slate-600">
                    Maison → Ménage → Exploitant → Parcelle GPS → Culture
                </p>

            </div>


            {{-- SUIVRE --}}
            <div class="rounded-lg border border-[#ced4da] bg-white p-4 transition hover:border-[#116e9b]">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-[#116e9b]">

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 3v18h18"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M7 16l4-5 3 3 5-7"
                            />
                        </svg>

                    </div>

                    <div>

                        <p class="text-[10px] font-bold uppercase tracking-wider text-[#116e9b]">
                            03 • Suivre
                        </p>

                        <h3 class="text-sm font-bold text-[#333333]">
                            Gestion
                        </h3>

                    </div>

                </div>

                <p class="mt-3 text-xs leading-5 text-slate-600">
                    Intrants utilisés → Besoins → Allocations → Distributions
                </p>

            </div>

        </div>

    </section>



    {{-- =========================================================
         FONCTIONNALITÉS
    ========================================================== --}}
    <section>

        <div class="mb-3 flex items-center gap-2">

            <span class="h-5 w-1 rounded-full bg-[#006a4f]"></span>

            <h2 class="text-base font-bold text-[#333333]">
                Principales fonctionnalités
            </h2>

        </div>


        <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">


            {{-- CAMPAGNES --}}
            <div class="rounded-lg border border-[#ced4da] bg-white p-3">

                <div class="mb-2 flex h-8 w-8 items-center justify-center rounded-md bg-[#e5f2ee] text-[#006a4f]">

                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                        />
                    </svg>

                </div>

                <h3 class="text-xs font-bold text-[#006a4f]">
                    Campagnes
                </h3>

                <p class="mt-1 text-[11px] leading-4 text-slate-500">
                    Planification et affectation des équipes.
                </p>

            </div>


            {{-- RECENSEMENT --}}
            <div class="rounded-lg border border-[#ced4da] bg-white p-3">

                <div class="mb-2 flex h-8 w-8 items-center justify-center rounded-md bg-[#e5f2ee] text-[#006a4f]">

                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                    </svg>

                </div>

                <h3 class="text-xs font-bold text-[#006a4f]">
                    Recensement
                </h3>

                <p class="mt-1 text-[11px] leading-4 text-slate-500">
                    Ménages, exploitants et exploitations.
                </p>

            </div>


            {{-- GPS --}}
            <div class="rounded-lg border border-[#ced4da] bg-white p-3">

                <div class="mb-2 flex h-8 w-8 items-center justify-center rounded-md bg-[#e5f2ee] text-[#006a4f]">

                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 21s8-4.5 8-11a8 8 0 10-16 0c0 6.5 8 11 8 11z"
                        />

                        <circle
                            cx="12"
                            cy="10"
                            r="2.5"
                            stroke-width="2"
                        />
                    </svg>

                </div>

                <h3 class="text-xs font-bold text-[#006a4f]">
                    Géolocalisation
                </h3>

                <p class="mt-1 text-[11px] leading-4 text-slate-500">
                    Parcelles et coordonnées GPS.
                </p>

            </div>


            {{-- INTRANTS --}}
            <div class="rounded-lg border border-[#ced4da] bg-white p-3">

                <div class="mb-2 flex h-8 w-8 items-center justify-center rounded-md bg-[#e5f2ee] text-[#006a4f]">

                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                        />
                    </svg>

                </div>

                <h3 class="text-xs font-bold text-[#006a4f]">
                    Intrants agricoles
                </h3>

                <p class="mt-1 text-[11px] leading-4 text-slate-500">
                    Besoins, allocations et distributions.
                </p>

            </div>

        </div>

    </section>


    {{-- =========================================================
         SIGNATURE
    ========================================================== --}}
    <div class="mt-6 border-t border-[#ced4da] pt-4 text-center">

        <p class="text-xs font-semibold text-[#006a4f]">
            SIRA-Mô
        </p>

        <p class="mt-0.5 text-[10px] text-slate-500">
            Système d'Information et de Recensement Agricole • Préfecture de Mô
        </p>

    </div>

</div>

</x-guest-layout>