<section id="accueil"
    class="relative min-h-screen flex items-center bg-gradient-to-r from-green-900 via-green-800 to-green-700 overflow-hidden">

    <!-- Image de fond -->
    <div class="absolute inset-0">
        <img src="{{ asset('images/agriculture.jpg') }}"
             alt="Agriculture"
             class="w-full h-full object-cover opacity-25">
    </div>

    <!-- Couche sombre -->
    <div class="absolute inset-0 bg-black/40"></div>

    <div class="relative max-w-7xl mx-auto px-6 lg:px-8">

        <div class="grid lg:grid-cols-2 gap-12 items-center">

            <!-- Texte -->
            <div class="text-white">

                <span class="inline-block px-4 py-2 rounded-full bg-green-600/30 border border-green-400 text-green-100 text-sm mb-6">
                    Plateforme nationale de recensement agricole
                </span>

                <h1 class="text-5xl lg:text-7xl font-bold leading-tight">

                    Bienvenue sur

                    <span class="text-yellow-400">
                        SIRA-MO
                    </span>

                </h1>

                <p class="mt-8 text-xl text-gray-200 leading-9">

                    Le <strong>Système Intégré de Recensement Agricole – Module Opérationnel</strong>
                    est une plateforme numérique permettant la collecte,
                    le suivi et la gestion des données agricoles afin
                    d'améliorer la planification et la prise de décision.

                </p>

                <div class="mt-10 flex flex-wrap gap-4">

                    <a href="{{ route('login') }}"
                        class="px-8 py-4 bg-yellow-500 hover:bg-yellow-400 rounded-xl font-semibold text-black transition duration-300">

                        Se connecter

                    </a>

                    <a href="#fonctionnalites"
                        class="px-8 py-4 border-2 border-white rounded-xl hover:bg-white hover:text-green-800 transition duration-300">

                        Découvrir les fonctionnalités

                    </a>

                </div>

            </div>

            <!-- Carte -->
            <div class="hidden lg:flex justify-center">

                <div class="bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20 w-[420px]">

                    <div class="flex justify-center mb-6">

                        <img src="{{ asset('images/sira-mo.png') }}"
                             class="w-32"
                             alt="Logo SIRA-MO">

                    </div>

                    <h2 class="text-2xl font-bold text-white text-center">

                        SIRA-MO

                    </h2>

                    <p class="text-center text-green-100 mt-3">

                        Système Intégré de Recensement Agricole

                    </p>

                    <div class="grid grid-cols-2 gap-4 mt-8">

                        <div class="bg-green-700/40 rounded-xl p-5 text-center">

                            <h3 class="text-3xl font-bold text-yellow-400">
                                3
                            </h3>

                            <p class="text-white text-sm mt-2">
                                Profils utilisateurs
                            </p>

                        </div>

                        <div class="bg-green-700/40 rounded-xl p-5 text-center">

                            <h3 class="text-3xl font-bold text-yellow-400">
                                24/7
                            </h3>

                            <p class="text-white text-sm mt-2">
                                Disponibilité
                            </p>

                        </div>

                        <div class="bg-green-700/40 rounded-xl p-5 text-center">

                            <h3 class="text-3xl font-bold text-yellow-400">
                                100%
                            </h3>

                            <p class="text-white text-sm mt-2">
                                Données sécurisées
                            </p>

                        </div>

                        <div class="bg-green-700/40 rounded-xl p-5 text-center">

                            <h3 class="text-3xl font-bold text-yellow-400">
                                📍
                            </h3>

                            <p class="text-white text-sm mt-2">
                                Géolocalisation
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Vague -->
    <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none">

        <svg viewBox="0 0 1440 120" class="w-full h-28 fill-white">

            <path d="M0,64L80,74.7C160,85,320,107,480,106.7C640,107,800,85,960,80C1120,75,1280,85,1360,90.7L1440,96L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z"></path>

        </svg>

    </div>

</section>
