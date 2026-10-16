<x-guest-layout>
    <div class="mx-auto max-w-7xl px-4 py-8 font-['Poppins'] sm:px-6 lg:px-8">

        {{-- =========================================================
            1. PRÉSENTATION PRINCIPALE
        ========================================================= --}}
        <section class="mb-12">
            <div class="grid items-center gap-8 lg:grid-cols-2">
                
                <div class="space-y-4">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-md border border-[#ced4da] bg-[#ebeff4] px-2.5 py-1 text-xs font-medium text-[#333333]">
                            Préfecture de Mô • Togo
                        </span>

                        @if(isset($campagneActive))
                            <span class="inline-flex items-center gap-1.5 rounded-md bg-[#e5f2ee] px-2.5 py-1 text-xs font-semibold text-[#006a4f]">
                                <span class="h-2 w-2 rounded-full bg-[#43a842] animate-pulse"></span>
                                Campagne : {{ $campagneActive->nom ?? $campagneActive->annee }}
                            </span>
                        @endif
                    </div>

                    <h1 class="text-3xl font-extrabold tracking-tight text-[#006a4f] sm:text-4xl lg:text-5xl">
                        SIRA-Mô
                    </h1>
                    
                    <p class="text-base sm:text-lg font-medium text-slate-700">
                        Système d’Information et de Recensement Agricole de Mô
                    </p>

                    <p class="text-sm leading-relaxed text-slate-600 max-w-2xl">
                        Plateforme web dédiée au recensement des ménages agricoles, à la géolocalisation des parcelles par GPS et au suivi de la distribution des intrants dans la préfecture de Mô.
                    </p>

                    <div class="rounded-lg border border-[#ced4da] bg-white p-4 shadow-sm max-w-2xl">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-[#006a4f]">
                            Thème du projet
                        </p>
                        <p class="mt-1 text-xs sm:text-sm leading-relaxed text-slate-800">
                            Conception et réalisation d'une application web de recensement, de suivi et de gestion des intrants agricoles : cas de la préfecture de Mô.
                        </p>
                    </div>

                    <div class="pt-2">
                        <a
                            href="{{ route('login') }}"
                            class="inline-flex items-center gap-2 rounded-lg bg-[#43a842] px-6 py-3 text-sm font-semibold text-white shadow-md hover:bg-[#378736] transition-all"
                        >
                            Accéder au système
                        </a>
                    </div>
                </div>

                <div class="flex flex-col items-center justify-center lg:justify-end">
                    <div class="flex h-32 w-32 items-center justify-center rounded-2xl bg-[#006a4f] shadow-lg">
                        <svg viewBox="0 0 64 64" class="h-24 w-24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="32" cy="32" r="28" stroke="white" stroke-width="2" stroke-opacity="0.25"/>
                            <path d="M32 50V18" stroke="white" stroke-width="4" stroke-linecap="round"/>
                            <path d="M32 25C25 18 17 20 14 21C16 29 23 34 32 32" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M32 32C39 25 47 27 50 28C48 36 41 40 32 39" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="32" cy="17" r="3.5" fill="#43a842"/>
                        </svg>
                    </div>
                    <p class="mt-3 text-sm font-bold text-[#006a4f]">SIRA-Mô</p>
                    <p class="text-xs text-slate-500">Recensement Agricole</p>
                </div>

            </div>
        </section>


        {{-- =========================================================
            2. DONNÉES RÉELLES DU TERRAIN
        ========================================================= --}}
        <section class="mb-12 rounded-xl bg-white p-6 border border-[#ced4da] shadow-sm">
            <div class="mb-6 text-center">
                <h2 class="text-xs font-bold uppercase tracking-wider text-[#006a4f]">Couverture du territoire</h2>
                <p class="text-lg font-bold text-slate-800">Données réelles enregistrées dans la préfecture</p>
            </div>

            <div class="grid grid-cols-2 gap-4 text-center md:grid-cols-3">
                <div class="p-4 rounded-lg bg-[#f8fafc] border border-slate-100">
                    <p class="text-3xl font-extrabold text-[#006a4f]">{{ $nombreCantons }}</p>
                    <p class="mt-1 text-xs font-medium text-slate-600">Cantons enregistrés</p>
                </div>

                <div class="p-4 rounded-lg bg-[#f8fafc] border border-slate-100">
                    <p class="text-3xl font-extrabold text-[#43a842]">{{ $nombreMenages }}</p>
                    <p class="mt-1 text-xs font-medium text-slate-600">Ménages recensés</p>
                </div>

                <div class="p-4 rounded-lg bg-[#f8fafc] border border-slate-100 col-span-2 md:col-span-1">
                    <p class="text-3xl font-extrabold text-[#006a4f]">{{ $nombreParcelles }}</p>
                    <p class="mt-1 text-xs font-medium text-slate-600">Parcelles géolocalisées (GPS)</p>
                </div>
            </div>
        </section>


        {{-- =========================================================
            3. FOOTER
        ========================================================= --}}
        <footer class="border-t border-[#ced4da] pt-6 text-center">
            <p class="text-xs font-semibold text-[#006a4f]">SIRA-Mô</p>
            <p class="mt-0.5 text-[10px] text-slate-500">
                Système d'Information et de Recensement Agricole • Préfecture de Mô • République Togolaise
            </p>
        </footer>

    </div>
</x-guest-layout>