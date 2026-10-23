<nav class="fixed top-0 left-0 w-full z-50 bg-white shadow-sm border-b border-gray-200">

    <div class="max-w-7xl mx-auto px-6 lg:px-8">

        <div class="flex items-center justify-between h-20">

            <!-- Logo Amélioré Style Application -->
            <a href="#accueil" class="flex items-center gap-3 decoration-none group">

                {{-- Boîte / Conteneur du logo --}}
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-green-50/80 p-1.5 shadow-sm border border-green-100 group-hover:border-green-200 transition duration-300">
                    <img
                        src="{{ asset('images/sira-mo.png') }}"
                        alt="SIRA-MÔ"
                        class="h-full w-full object-contain rounded-lg">
                </div>

                {{-- Textes nom + sous-titre --}}
                <div class="flex flex-col">
                    <h1 class="text-xl font-extrabold leading-tight text-green-900 tracking-tight">
                        SIRA-Mô
                    </h1>

                    <p class="text-xs font-medium text-gray-500">
                        Recensement Agricole
                    </p>
                </div>

            </a>

            <!-- Navigation Desktop -->
            <div class="hidden lg:flex items-center space-x-10">

                <a href="#accueil"
                    class="font-medium text-gray-700 hover:text-green-700 transition duration-300">
                    Accueil
                </a>

                <a href="#apropos"
                    class="font-medium text-gray-700 hover:text-green-700 transition duration-300">
                    À propos
                </a>

                <a href="#fonctionnalites"
                    class="font-medium text-gray-700 hover:text-green-700 transition duration-300">
                    Fonctionnalités
                </a>

                <a href="#processus"
                    class="font-medium text-gray-700 hover:text-green-700 transition duration-300">
                    Processus
                </a>

                <a href="#contact"
                    class="font-medium text-gray-700 hover:text-green-700 transition duration-300">
                    Contact
                </a>

            </div>

            <!-- Bouton Connexion -->
            <div class="hidden lg:block">

                <a href="{{ route('login') }}"
                class="bg-green-700 hover:bg-green-800 text-white px-6 py-3 rounded-lg font-semibold transition duration-300 shadow">

                    Connexion

                </a>

            </div>

            <!-- Bouton Mobile -->
            <button
                id="menuButton"
                class="lg:hidden text-green-700">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-8 h-8"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"/>

                </svg>

            </button>

        </div>

    </div>

    <!-- Menu Mobile -->
    <div id="mobileMenu" class="hidden lg:hidden bg-white border-t">

        <a href="#accueil"
            class="block px-6 py-4 hover:bg-green-50">
            Accueil
        </a>

        <a href="#apropos"
            class="block px-6 py-4 hover:bg-green-50">
            À propos
        </a>

        <a href="#fonctionnalites"
            class="block px-6 py-4 hover:bg-green-50">
            Fonctionnalités
        </a>

        <a href="#processus"
            class="block px-6 py-4 hover:bg-green-50">
            Processus
        </a>

        <a href="#contact"
            class="block px-6 py-4 hover:bg-green-50">
            Contact
        </a>

        <div class="p-6">

            <a href="{{ route('login') }}"
                class="block text-center bg-green-700 hover:bg-green-800 text-white py-3 rounded-lg font-semibold transition duration-300">
                Connexion
            </a>

        </div>

    </div>

</nav>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const btn = document.getElementById('menuButton');
    const menu = document.getElementById('mobileMenu');

    btn.addEventListener('click', function () {
        menu.classList.toggle('hidden');
    });

});
</script>