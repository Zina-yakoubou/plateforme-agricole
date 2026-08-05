@extends('layouts.app')


@section('content')


<div class="space-y-6">


    {{-- En-tête --}}
    <div>

        <h1 class="text-3xl font-bold text-gray-800">
            Tableau de bord
        </h1>

        <p class="text-gray-500">
            Vue générale du système de recensement agricole
        </p>

    </div>



    {{-- Cartes statistiques --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">


        <div class="bg-white rounded-xl shadow p-6">

            <div class="flex justify-between">

                <div>
                    <p class="text-gray-500">
                        Régions
                    </p>

                    <h2 class="text-3xl font-bold">
                        5
                    </h2>
                </div>

                <div class="text-3xl">
                    🏛️
                </div>

            </div>

        </div>



        <div class="bg-white rounded-xl shadow p-6">

            <div class="flex justify-between">

                <div>
                    <p class="text-gray-500">
                        Préfectures
                    </p>

                    <h2 class="text-3xl font-bold">
                        39
                    </h2>
                </div>

                <div class="text-3xl">
                    📍
                </div>

            </div>

        </div>



        <div class="bg-white rounded-xl shadow p-6">

            <div class="flex justify-between">

                <div>
                    <p class="text-gray-500">
                        Agents
                    </p>

                    <h2 class="text-3xl font-bold">
                        0
                    </h2>
                </div>

                <div class="text-3xl">
                    👨‍🌾
                </div>

            </div>

        </div>



        <div class="bg-white rounded-xl shadow p-6">

            <div class="flex justify-between">

                <div>
                    <p class="text-gray-500">
                        Campagne
                    </p>

                    <h2 class="text-xl font-bold">
                        Active
                    </h2>
                </div>

                <div class="text-3xl">
                    🌱
                </div>

            </div>

        </div>


    </div>




    {{-- Section inférieure --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">



        {{-- Activités --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow p-6">

            <h2 class="text-xl font-bold mb-4">
                Activités récentes
            </h2>


            <ul class="space-y-3">

                <li class="border-b pb-3">
                    Aucun événement récent
                </li>

            </ul>


        </div>




        {{-- Actions rapides --}}
        <div class="bg-white rounded-xl shadow p-6">

            <h2 class="text-xl font-bold mb-4">
                Actions rapides
            </h2>


            <div class="space-y-3">


                <button class="w-full bg-green-600 text-white p-3 rounded-lg">
                    Ajouter une région
                </button>


                <button class="w-full bg-blue-600 text-white p-3 rounded-lg">
                    Ajouter un utilisateur
                </button>


                <button class="w-full bg-gray-800 text-white p-3 rounded-lg">
                    Voir les rapports
                </button>


            </div>

        </div>


    </div>


</div>


@endsection