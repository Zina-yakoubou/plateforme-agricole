@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    {{-- =========================================================
         PROFIL
    ========================================================== --}}

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">

        <div class="flex items-center justify-between mb-6">

            <div>
                <h1 class="text-2xl font-bold text-slate-800">
                    Profil utilisateur
                </h1>

                <p class="text-sm text-slate-500 mt-1">
                    Informations du compte
                </p>
            </div>

        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            {{-- Nom --}}
            <div>
                <p class="text-sm text-slate-500">
                    Nom
                </p>

                <p class="font-semibold text-slate-800">
                    {{ $user->name }}
                </p>
            </div>


            {{-- Email --}}
            <div>
                <p class="text-sm text-slate-500">
                    Email
                </p>

                <p class="font-semibold text-slate-800">
                    {{ $user->email ?? '-' }}
                </p>
            </div>


            {{-- Téléphone --}}
            <div>
                <p class="text-sm text-slate-500">
                    Téléphone
                </p>

                <p class="font-semibold text-slate-800">
                    {{ $user->telephone ?? '-' }}
                </p>
            </div>


            {{-- Rôle --}}
            <div>
                <p class="text-sm text-slate-500">
                    Rôle
                </p>

                <p class="font-semibold text-slate-800">
                    {{ $user->role?->nom ?? '-' }}
                </p>
            </div>


            {{-- Préfecture actuelle --}}
            <div>
                <p class="text-sm text-slate-500">
                    Préfecture actuelle
                </p>

                @if($user->rattachementPrefectureActif?->prefecture)

                    <p class="font-semibold text-[#006a4f]">
                        {{ $user->rattachementPrefectureActif->prefecture->nom }}
                    </p>

                @else

                    <p class="text-slate-400">
                        Aucune préfecture
                    </p>

                @endif
            </div>


            {{-- Statut --}}
            <div>
                <p class="text-sm text-slate-500">
                    Statut du compte
                </p>

                @if($user->statut)

                    <span class="inline-flex items-center rounded-full
                                 bg-green-100 px-3 py-1
                                 text-sm font-medium text-green-700">

                        Actif

                    </span>

                @else

                    <span class="inline-flex items-center rounded-full
                                 bg-red-100 px-3 py-1
                                 text-sm font-medium text-red-700">

                        Désactivé

                    </span>

                @endif

            </div>

        </div>


        {{-- =====================================================
             ACTIONS
        ====================================================== --}}

        <div class="mt-8 flex flex-wrap gap-3">

            <a href="{{ route('users.edit', $user) }}"
               class="rounded-lg bg-green-600
               px-6 py-2.5 text-sm font-semibold
               text-white shadow-sm transition
               hover:bg-green-700
               focus:outline-none
               focus:ring-2
               focus:ring-green-500
               focus:ring-offset-2">

                Modifier

            </a>


            <form method="POST"
                  action="{{ route('users.toggle-status', $user) }}">

                @csrf

                @method('PATCH')

                <button
                    type="submit"
                    class="px-5 py-2.5 rounded-lg text-white
                    {{ $user->statut
                        ? 'bg-red-600 hover:bg-red-700'
                        : 'bg-green-600 hover:bg-green-700' }}
                    transition">

                    {{ $user->statut
                        ? 'Désactiver le compte'
                        : 'Activer le compte' }}

                </button>

            </form>

        </div>

    </div>


    {{-- =========================================================
         RATTACHEMENT / MUTATION
    ========================================================== --}}

    @if(auth()->user()->isAdmin())

        @if($user->isDpa() || $user->isSuperviseur() || $user->isTechnicien())

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">

                <div class="mb-6">

                    <h2 class="text-lg font-bold text-slate-800">
                        Rattachement à une préfecture
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Modifier la préfecture de rattachement de cet utilisateur.
                        L'ancien rattachement sera conservé dans l'historique.
                    </p>

                </div>


                {{-- Préfecture actuelle --}}

                <div class="mb-6">

                    <p class="text-sm text-slate-500 mb-1">
                        Préfecture actuelle
                    </p>

                    @if($user->prefecture)

                        <div class="inline-flex items-center
                                    rounded-lg bg-slate-100
                                    px-4 py-2 font-semibold text-slate-700">

                            {{ $user->prefecture->nom }}

                        </div>

                    @else

                        <span class="text-slate-400">
                            Aucune préfecture
                        </span>

                    @endif

                </div>


                {{-- Formulaire de mutation --}}

                <form method="POST"
                      action="{{ route('users.rattacher-prefecture', $user) }}">

                    @csrf


                    <div class="max-w-md">

                        <label
                            for="prefecture_id"
                            class="block text-sm font-medium text-slate-700 mb-2">

                            Nouvelle préfecture

                        </label>


                        <select
                            name="prefecture_id"
                            id="prefecture_id"
                            required
                            class="w-full rounded-lg border-slate-300
                                   focus:border-blue-500
                                   focus:ring-blue-500">

                            <option value="">
                                -- Sélectionner une préfecture --
                            </option>


                            @foreach($prefectures as $prefecture)

                                <option
                                    value="{{ $prefecture->idPrefecture }}"
                                    @selected(
                                        $user->prefecture_id == $prefecture->idPrefecture
                                    )>

                                    {{ $prefecture->nom }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="mt-5">

                        <button
                            type="submit"
                            class="px-5 py-2.5 rounded-lg
                                   bg-[#006a4f] text-white
                                   hover:bg-[#00563f]
                                   transition">

                            Modifier le rattachement

                        </button>

                    </div>

                </form>

            </div>

        @endif

    @endif


    {{-- =========================================================
         HISTORIQUE DES RATTACHEMENTS
    ========================================================== --}}

    @if(
        ($user->isDpa() ||
         $user->isSuperviseur() ||
         $user->isTechnicien())
        && $user->rattachementsPrefecture->count()
    )

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">

            <div class="mb-5">

                <h2 class="text-lg font-bold text-slate-800">
                    Historique des rattachements
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Historique des préfectures auxquelles cet utilisateur
                    a été rattaché.
                </p>

            </div>


            <div class="overflow-x-auto">

                <table class="min-w-full">

                    <thead>

                        <tr class="border-b border-slate-200">

                            <th class="px-4 py-3 text-left
                                       text-xs font-semibold
                                       uppercase text-slate-500">

                                Préfecture

                            </th>


                            <th class="px-4 py-3 text-left
                                       text-xs font-semibold
                                       uppercase text-slate-500">

                                Statut

                            </th>


                            <th class="px-4 py-3 text-left
                                       text-xs font-semibold
                                       uppercase text-slate-500">

                                Enregistré le

                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach(
                            $user->rattachementsPrefecture->sortByDesc('created_at')
                            as $rattachement
                        )

                            <tr class="border-b border-slate-100">

                                <td class="px-4 py-3 font-medium text-slate-700">

                                    {{ $rattachement->prefecture?->nom ?? '-' }}

                                </td>


                                <td class="px-4 py-3">

                                    @if($rattachement->statut === 'actif')

                                        <span class="inline-flex rounded-full
                                                     bg-green-100 px-3 py-1
                                                     text-xs font-medium
                                                     text-green-700">

                                            Actuel

                                        </span>

                                    @else

                                        <span class="inline-flex rounded-full
                                                     bg-slate-100 px-3 py-1
                                                     text-xs font-medium
                                                     text-slate-600">

                                            Terminé

                                        </span>

                                    @endif

                                </td>


                                <td class="px-4 py-3 text-sm text-slate-500">

                                    {{ $rattachement->created_at?->format('d/m/Y') }}

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    @endif


    {{-- =========================================================
         RETOUR
    ========================================================== --}}

    <div>

        <a href="{{ route('users.index') }}"
           class="inline-flex items-center rounded-lg
                  border border-slate-300
                  px-5 py-2.5
                  text-slate-700
                  hover:bg-slate-100 transition">

             Retour aux utilisateurs

        </a>

    </div>

</div>

@endsection