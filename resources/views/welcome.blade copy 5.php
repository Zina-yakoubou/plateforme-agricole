<x-guest-layout>
    <div class="mx-auto max-w-screen-2xl px-4 py-10 font-['Poppins'] sm:px-8 lg:px-12">

        {{-- =========================================================
            1. PRÉSENTATION
        ========================================================= --}}
        <section class="mb-20">
            <div class="grid items-center gap-10 lg:grid-cols-2">

                <div class="space-y-5">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center rounded-md border border-[#ced4da] bg-[#ebeff4] px-3 py-1.5 text-sm font-medium text-[#333333]">
                            Préfecture de Mô • Togo
                        </span>

                        @if(isset($campagneActive))
                            <span class="inline-flex items-center gap-2 rounded-md bg-[#e5f2ee] px-3 py-1.5 text-sm font-semibold text-[#006a4f]">
                                <span class="h-2.5 w-2.5 animate-pulse rounded-full bg-[#43a842]"></span>
                                Campagne : {{ $campagneActive->nom ?? $campagneActive->annee }}
                            </span>
                        @endif
                    </div>

                    <h1 class="text-5xl font-extrabold tracking-tight text-[#006a4f] sm:text-6xl lg:text-7xl">
                        SIRA-Mô
                    </h1>

                    <p class="text-xl font-semibold text-slate-800 sm:text-2xl">
                        Le recensement agricole de la préfecture de Mô, simple et suivi sur le terrain.
                    </p>

                    <p class="max-w-2xl text-base leading-relaxed text-slate-600 sm:text-lg">
                        SIRA-Mô permet d'enregistrer les ménages agricoles, de localiser leurs parcelles
                        par GPS, de connaître les cultures pratiquées et de suivre les intrants
                        (engrais, semences, produits de traitement) utilisés ou distribués.
                    </p>

                    <div class="max-w-2xl rounded-lg border border-[#ced4da] bg-white p-5 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-wider text-[#006a4f]">Thème du projet</p>
                        <p class="mt-1 text-sm leading-relaxed text-slate-800 sm:text-base">
                            Conception et réalisation d'une application web de recensement, de suivi et de gestion des intrants agricoles : cas de la préfecture de Mô.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center rounded-lg bg-[#43a842] px-8 py-4 text-base font-semibold text-white shadow-md transition-all hover:bg-[#378736]">
                            Accéder au système
                        </a>
                        <a href="#fonctionnement"
                           class="inline-flex items-center rounded-lg border-2 border-[#006a4f] px-7 py-4 text-base font-semibold text-[#006a4f] transition hover:bg-[#e5f2ee]">
                            Comment ça marche ?
                        </a>
                    </div>
                </div>

                {{-- Image : agent recenseur sur le terrain --}}
                <div class="w-full">
                    @if(file_exists(public_path('images/agent-recenseur.jpg')))
                        <div class="relative overflow-hidden rounded-2xl shadow-2xl">
                            <img src="{{ asset('images/agent-recenseur.jpg') }}"
                                 alt="Agent recenseur avec une tablette dans un champ de maïs"
                                 class="h-80 w-full object-cover object-[75%_30%] sm:h-[28rem] lg:h-[36rem]">
                            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent p-5">
                                <p class="text-sm font-semibold text-white sm:text-base">
                                    Les agents recensent les exploitations directement sur le terrain.
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="flex h-64 items-center justify-center rounded-2xl bg-[#006a4f] shadow-lg">
                            <p class="text-2xl font-bold text-white">SIRA-Mô</p>
                        </div>
                    @endif
                </div>

            </div>
        </section>


        {{-- =========================================================
            2. COMMENT ÇA MARCHE
        ========================================================= --}}
        <section id="fonctionnement" class="mb-20 scroll-mt-8">
            <div class="mb-8 text-center">
                <h2 class="text-sm font-bold uppercase tracking-wider text-[#006a4f]">Comment ça marche</h2>
                <p class="mt-1 text-2xl font-bold text-slate-800 sm:text-3xl">Du terrain à la distribution, en 4 étapes</p>
            </div>

            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach([
                    ['1', 'Recensement', 'Les agents de terrain visitent les ménages et enregistrent les exploitants, leurs parcelles et leurs cultures.'],
                    ['2', 'Localisation', 'Chaque parcelle est repérée par GPS et rattachée à sa culture et à son exploitant.'],
                    ['3', 'Besoins en intrants', 'À partir des cultures et des superficies, le système calcule les besoins en engrais et semences, selon les barèmes agronomiques.'],
                    ['4', 'Distribution et suivi', 'Les intrants sont alloués puis distribués. Les préfectures suivent les quantités reçues et distribuées.'],
                ] as [$num, $titre, $texte])
                    <div class="rounded-xl border border-[#ced4da] bg-white p-6 shadow-sm">
                        <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-full bg-[#006a4f] text-base font-bold text-white">
                            {{ $num }}
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">{{ $titre }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600 sm:text-base">{{ $texte }}</p>
                    </div>
                @endforeach
            </div>
        </section>


        {{-- =========================================================
            3. QUI UTILISE SIRA-MÔ
        ========================================================= --}}
        <section class="mb-20">
            <div class="mb-8 text-center">
                <h2 class="text-sm font-bold uppercase tracking-wider text-[#006a4f]">Utilisateurs</h2>
                <p class="mt-1 text-2xl font-bold text-slate-800 sm:text-3xl">Qui utilise SIRA-Mô ?</p>
            </div>

            <div class="grid gap-5 md:grid-cols-3">
                <div class="rounded-xl border border-slate-100 bg-[#f8fafc] p-6">
                    <h3 class="text-lg font-bold text-[#006a4f]">Agents et superviseurs</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600 sm:text-base">
                        Réalisent le recensement dans les villages qui leur sont affectés et suivent l'avancement de leur équipe.
                    </p>
                </div>
                <div class="rounded-xl border border-slate-100 bg-[#f8fafc] p-6">
                    <h3 class="text-lg font-bold text-[#006a4f]">Direction préfectorale (DPA)</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600 sm:text-base">
                        Reçoit les campagnes, organise les équipes, définit les barèmes et suit les distributions dans la préfecture.
                    </p>
                </div>
                <div class="rounded-xl border border-slate-100 bg-[#f8fafc] p-6">
                    <h3 class="text-lg font-bold text-[#006a4f]">Administration et gestion</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600 sm:text-base">
                        Lance les campagnes, gère les cultures et les intrants, et consulte les statistiques nationales.
                    </p>
                </div>
            </div>

            <p class="mt-5 text-center text-sm text-slate-500">
                L'accès se fait avec un compte fourni par l'administration. Pas de compte ? Contactez votre direction préfectorale.
            </p>
        </section>


        {{-- =========================================================
            4. DONNÉES RÉELLES DU TERRAIN
        ========================================================= --}}
        <section class="mb-20 rounded-xl border border-[#ced4da] bg-white p-8 shadow-sm">
            <div class="mb-8 text-center">
                <h2 class="text-sm font-bold uppercase tracking-wider text-[#006a4f]">Couverture du territoire</h2>
                <p class="mt-1 text-2xl font-bold text-slate-800 sm:text-3xl">Données réelles enregistrées dans la préfecture</p>
            </div>

            <div class="grid grid-cols-2 gap-5 text-center md:grid-cols-3">
                <div class="rounded-lg border border-slate-100 bg-[#f8fafc] p-6">
                    <p class="text-5xl font-extrabold text-[#006a4f]">{{ $nombreCantons }}</p>
                    <p class="mt-2 text-sm font-medium text-slate-600 sm:text-base">Cantons enregistrés</p>
                </div>

                <div class="rounded-lg border border-slate-100 bg-[#f8fafc] p-6">
                    <p class="text-5xl font-extrabold text-[#43a842]">{{ $nombreMenages }}</p>
                    <p class="mt-2 text-sm font-medium text-slate-600 sm:text-base">Ménages recensés</p>
                </div>

                <div class="col-span-2 rounded-lg border border-slate-100 bg-[#f8fafc] p-6 md:col-span-1">
                    <p class="text-5xl font-extrabold text-[#006a4f]">{{ $nombreParcelles }}</p>
                    <p class="mt-2 text-sm font-medium text-slate-600 sm:text-base">Parcelles géolocalisées (GPS)</p>
                </div>
            </div>
        </section>


        {{-- =========================================================
            5. APPEL FINAL
        ========================================================= --}}
        <section class="mb-16 rounded-xl bg-[#006a4f] p-10 text-center">
            <p class="text-2xl font-bold text-white">Prêt à commencer ?</p>
            <a href="{{ route('login') }}"
               class="mt-5 inline-flex rounded-lg bg-white px-8 py-4 text-base font-semibold text-[#006a4f] transition hover:bg-[#e5f2ee]">
                Se connecter
            </a>
        </section>


        {{-- =========================================================
            6. FOOTER
        ========================================================= --}}
        <footer class="border-t border-[#ced4da] pt-6 text-center">
            <p class="text-sm font-semibold text-[#006a4f]">SIRA-Mô</p>
            <p class="mt-1 text-xs text-slate-500">
                Système d'Information et de Recensement Agricole • Préfecture de Mô • République Togolaise
            </p>
        </footer>
don 
    </div>
</x-guest-layout>