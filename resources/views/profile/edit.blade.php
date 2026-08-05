<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">
                    Mon profil
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Gérez vos informations personnelles et la sécurité de votre compte.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 space-y-6">

            <!-- Carte utilisateur -->
            <div class="bg-white rounded-xl shadow border border-slate-200 p-6">

                <div class="flex items-center gap-5">

                    <div class="w-20 h-20 rounded-full bg-green-600 flex items-center justify-center text-white text-3xl font-bold">
                        {{ strtoupper(substr($user->name,0,1)) }}
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold text-slate-800">
                            {{ $user->name }}
                        </h3>

                        <p class="text-green-600 font-medium">
                            {{ $user->role->nom }}
                        </p>

                        <p class="text-slate-500 text-sm">
                            {{ $user->email }}
                        </p>
                    </div>

                </div>

            </div>

            <!-- Informations -->
            <div class="bg-white rounded-xl shadow border border-slate-200 p-6">
                @include('profile.partials.update-profile-information-form')
            </div>

            <!-- Mot de passe -->
            <div class="bg-white rounded-xl shadow border border-slate-200 p-6">
                @include('profile.partials.update-password-form')
            </div>

            <!-- Suppression -->
            <div class="bg-white rounded-xl shadow border border-red-200 p-6">
                @include('profile.partials.delete-user-form')
            </div>

        </div>
    </div>

</x-app-layout>