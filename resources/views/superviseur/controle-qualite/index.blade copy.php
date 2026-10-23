@extends('layouts.app')

@section('page-title', 'Contrôle qualité')

@section(
    'page-subtitle',
    'Vérification de la qualité des données collectées par vos équipes'
)

@section('content')

<div class="mx-auto max-w-7xl space-y-6">

    {{-- ============================================================
        EN-TÊTE
    ============================================================= --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-semibold text-gray-900">
                Contrôle qualité
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Vérifiez la qualité et la complétude des données collectées
                par les agents que vous supervisez.
            </p>
        </div>

        @if($campagneActive)
            <div
                class="inline-flex items-center gap-2 rounded-full
                       border border-green-200 bg-green-50
                       px-4 py-2 text-sm font-medium text-green-700"
            >
                <span class="h-2 w-2 rounded-full bg-green-500"></span>

                Campagne active :
                <span class="font-semibold">
                    {{ $campagneActive->libelle }}
                </span>
            </div>
        @else
            <div
                class="inline-flex items-center gap-2 rounded-full
                       border border-gray-200 bg-gray-50
                       px-4 py-2 text-sm font-medium text-gray-600"
            >
                Aucune campagne active
            </div>
        @endif

    </div>


    {{-- ============================================================
        INFORMATION
    ============================================================= --}}
    <div
        class="rounded-xl border border-[#006a4f]/20
               bg-[#006a4f]/5 p-5"
    >

        <div class="flex items-start gap-4">

            <div
                class="flex h-10 w-10 shrink-0 items-center justify-center
                       rounded-lg bg-[#006a4f] text-white"
            >
                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622C17.176 19.29 21 14.591 21 9c0-1.07-.14-2.107-.402-3.016z"
                    />
                </svg>
            </div>

            <div>
                <h2 class="font-semibold text-gray-900">
                    Mission du contrôle qualité
                </h2>

                <p class="mt-1 text-sm leading-6 text-gray-600">
                    Cette rubrique permet au superviseur de vérifier que les
                    questionnaires sont correctement renseignés, d'identifier
                    les omissions ou incohérences et de suivre les corrections
                    demandées aux agents.
                </p>
            </div>

        </div>

    </div>


    {{-- ============================================================
        STATISTIQUES
    ============================================================= --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-5">

        {{-- Questionnaires --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <span class="text-sm font-medium text-gray-500">
                    Questionnaires
                </span>

                <div class="rounded-lg bg-gray-100 p-2">

                    <svg
                        class="h-5 w-5 text-gray-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z"
                        />
                    </svg>

                </div>

            </div>

            <p class="mt-3 text-2xl font-semibold text-gray-900">
                {{ $stats['questionnaires'] }}
            </p>

        </div>


        {{-- Incomplets --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <span class="text-sm font-medium text-gray-500">
                    Incomplets
                </span>

                <div class="rounded-lg bg-yellow-50 p-2">

                    <svg
                        class="h-5 w-5 text-yellow-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M12 9v4m0 4h.01M10.29 3.86l-8.1 14a2 2 0 001.73 3h16.16a2 2 0 001.73-3l-8.1-14a2 2 0 00-3.42 0z"
                        />
                    </svg>

                </div>

            </div>

            <p class="mt-3 text-2xl font-semibold text-gray-900">
                {{ $stats['incomplets'] }}
            </p>

        </div>


        {{-- Incohérences --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <span class="text-sm font-medium text-gray-500">
                    Incohérences
                </span>

                <div class="rounded-lg bg-red-50 p-2">

                    <svg
                        class="h-5 w-5 text-red-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>

                </div>

            </div>

            <p class="mt-3 text-2xl font-semibold text-gray-900">
                {{ $stats['incoherences'] }}
            </p>

        </div>


        {{-- Corrections --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <span class="text-sm font-medium text-gray-500">
                    Corrections
                </span>

                <div class="rounded-lg bg-blue-50 p-2">

                    <svg
                        class="h-5 w-5 text-blue-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.5-7.5a2.121 2.121 0 113 3L12 16l-4 1 1-4 7.5-7.5z"
                        />
                    </svg>

                </div>

            </div>

            <p class="mt-3 text-2xl font-semibold text-gray-900">
                {{ $stats['corrections'] }}
            </p>

        </div>


        {{-- Vérifications --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <span class="text-sm font-medium text-gray-500">
                    Vérifications
                </span>

                <div class="rounded-lg bg-purple-50 p-2">

                    <svg
                        class="h-5 w-5 text-purple-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                        />
                    </svg>

                </div>

            </div>

            <p class="mt-3 text-2xl font-semibold text-gray-900">
                {{ $stats['verifications'] }}
            </p>

        </div>

    </div>


    {{-- ============================================================
        TYPES DE CONTRÔLE
    ============================================================= --}}
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 px-6 py-5">

            <h2 class="text-lg font-semibold text-gray-900">
                Contrôles à effectuer
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Accédez aux différents contrôles de qualité de la collecte.
            </p>

        </div>


        <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2 lg:grid-cols-4">

            {{-- Questionnaires incomplets --}}
            <div
                class="rounded-xl border border-gray-200 p-5
                       transition hover:border-yellow-300
                       hover:bg-yellow-50/40"
            >

                <div
                    class="mb-4 flex h-10 w-10 items-center justify-center
                           rounded-lg bg-yellow-50"
                >
                    <svg
                        class="h-5 w-5 text-yellow-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M12 9v4m0 4h.01M10.29 3.86l-8.1 14a2 2 0 001.73 3h16.16a2 2 0 001.73-3l-8.1-14a2 2 0 00-3.42 0z"
                        />
                    </svg>
                </div>

                <h3 class="font-semibold text-gray-900">
                    Questionnaires incomplets
                </h3>

                <p class="mt-2 text-sm leading-5 text-gray-500">
                    Identifier les questionnaires comportant des informations
                    manquantes.
                </p>

                <button
                    type="button"
                    disabled
                    class="mt-4 text-sm font-medium text-gray-400"
                >
                    Bientôt disponible
                </button>

            </div>


            {{-- Incohérences --}}
            <div
                class="rounded-xl border border-gray-200 p-5
                       transition hover:border-red-300
                       hover:bg-red-50/40"
            >

                <div
                    class="mb-4 flex h-10 w-10 items-center justify-center
                           rounded-lg bg-red-50"
                >
                    <svg
                        class="h-5 w-5 text-red-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </div>

                <h3 class="font-semibold text-gray-900">
                    Incohérences
                </h3>

                <p class="mt-2 text-sm leading-5 text-gray-500">
                    Repérer les réponses qui ne respectent pas les règles
                    de cohérence définies.
                </p>

                <button
                    type="button"
                    disabled
                    class="mt-4 text-sm font-medium text-gray-400"
                >
                    Bientôt disponible
                </button>

            </div>


            {{-- Corrections --}}
            <div
                class="rounded-xl border border-gray-200 p-5
                       transition hover:border-blue-300
                       hover:bg-blue-50/40"
            >

                <div
                    class="mb-4 flex h-10 w-10 items-center justify-center
                           rounded-lg bg-blue-50"
                >
                    <svg
                        class="h-5 w-5 text-blue-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.5-7.5a2.121 2.121 0 113 3L12 16l-4 1 1-4 7.5-7.5z"
                        />
                    </svg>
                </div>

                <h3 class="font-semibold text-gray-900">
                    Corrections demandées
                </h3>

                <p class="mt-2 text-sm leading-5 text-gray-500">
                    Suivre les observations transmises aux agents et les
                    corrections à effectuer.
                </p>

                <button
                    type="button"
                    disabled
                    class="mt-4 text-sm font-medium text-gray-400"
                >
                    Bientôt disponible
                </button>

            </div>


            {{-- Visites --}}
            <div
                class="rounded-xl border border-gray-200 p-5
                       transition hover:border-purple-300
                       hover:bg-purple-50/40"
            >

                <div
                    class="mb-4 flex h-10 w-10 items-center justify-center
                           rounded-lg bg-purple-50"
                >
                    <svg
                        class="h-5 w-5 text-purple-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M17.657 16.657L13.414 21a2 2 0 01-2.828 0l-4.243-4.343a8 8 0 1111.314 0z"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                    </svg>
                </div>

                <h3 class="font-semibold text-gray-900">
                    Visites de vérification
                </h3>

                <p class="mt-2 text-sm leading-5 text-gray-500">
                    Préparer et suivre les visites de contrôle effectuées
                    auprès des ménages ou exploitations.
                </p>

                <button
                    type="button"
                    disabled
                    class="mt-4 text-sm font-medium text-gray-400"
                >
                    Bientôt disponible
                </button>

            </div>

        </div>

    </div>


    {{-- ============================================================
        ZONES SUPERVISÉES
    ============================================================= --}}
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 px-6 py-5">

            <h2 class="text-lg font-semibold text-gray-900">
                Zones sous supervision
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Villages actuellement affectés à vos équipes.
            </p>

        </div>


        @if($affectations->isEmpty())

            <div class="px-6 py-12 text-center">

                <p class="text-sm font-medium text-gray-600">
                    Aucune zone affectée
                </p>

                <p class="mt-1 text-sm text-gray-400">
                    Les villages affectés à vos équipes apparaîtront ici.
                </p>

            </div>

        @else

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-gray-50">

                        <tr>

                            <th
                                class="px-6 py-3 text-left text-xs
                                       font-semibold uppercase tracking-wider
                                       text-gray-500"
                            >
                                Village
                            </th>

                            <th
                                class="px-6 py-3 text-left text-xs
                                       font-semibold uppercase tracking-wider
                                       text-gray-500"
                            >
                                Équipe
                            </th>

                            <th
                                class="px-6 py-3 text-left text-xs
                                       font-semibold uppercase tracking-wider
                                       text-gray-500"
                            >
                                Agents
                            </th>

                            <th
                                class="px-6 py-3 text-left text-xs
                                       font-semibold uppercase tracking-wider
                                       text-gray-500"
                            >
                                État du contrôle
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100 bg-white">

                        @foreach($affectations as $affectation)

                            <tr class="hover:bg-gray-50">

                                <td class="px-6 py-4">

                                    <div class="font-medium text-gray-900">
                                        {{ $affectation->village?->nom ?? 'Village non renseigné' }}
                                    </div>

                                </td>


                                <td class="px-6 py-4 text-sm text-gray-600">

                                    {{ $affectation->equipe?->libelle
                                        ?? $affectation->equipe?->reference
                                        ?? 'Équipe non renseignée' }}

                                </td>


                                <td class="px-6 py-4 text-sm text-gray-600">

                                    {{ $affectation->equipe?->membres?->count() ?? 0 }}

                                    agent(s)

                                </td>


                                <td class="px-6 py-4">

                                    <span
                                        class="inline-flex items-center rounded-full
                                               bg-gray-100 px-3 py-1
                                               text-xs font-medium text-gray-600"
                                    >
                                        Contrôle à effectuer
                                    </span>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif

    </div>

</div>

@endsection