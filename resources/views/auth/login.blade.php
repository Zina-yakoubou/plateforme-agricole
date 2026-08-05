<x-guest-layout>

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">


        <div class="text-center mb-8">

            <img 
                src="{{ asset('images/sira-mo.png') }}"
                class="w-20 h-20 mx-auto mb-4"
                alt="SIRA-MO">


            <h1 class="text-2xl font-bold text-green-800">
                Connexion SIRA-MO
            </h1>


            <p class="text-gray-500 mt-2">
                Système Intégré de Recensement Agricole
            </p>

        </div>



        <x-auth-session-status 
            class="mb-4" 
            :status="session('status')" />



        <form method="POST" action="{{ route('login') }}">

            @csrf


            <!-- Login -->

            <div>

                <x-input-label 
                    for="login" 
                    value="Login" />


                <x-text-input

                    id="login"

                    class="block mt-1 w-full"

                    type="text"

                    name="login"

                    :value="old('login')"

                    required

                    autofocus />



                <x-input-error 
                    :messages="$errors->get('login')" 
                    class="mt-2" />

            </div>




            <!-- Mot de passe -->

            <div class="mt-5">


                <x-input-label 
                    for="password" 
                    value="Mot de passe" />


                <x-text-input

                    id="password"

                    class="block mt-1 w-full"

                    type="password"

                    name="password"

                    required />


                <x-input-error 
                    :messages="$errors->get('password')" 
                    class="mt-2" />

            </div>




            <!-- Bouton -->

            <button

                type="submit"

                class="w-full mt-8 bg-green-700 hover:bg-green-800 text-white py-3 rounded-lg font-semibold transition">


                Se connecter


            </button>


        </form>


    </div>


</x-guest-layout>