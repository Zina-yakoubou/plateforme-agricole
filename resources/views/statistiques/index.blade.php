@extends('layouts.app')

@section('page-title', 'Statistiques')

@section(
    'page-subtitle',
    isset($prefecture)
        ? 'Analyse des données de la préfecture de ' . $prefecture->nom
        : 'Analyse globale des campagnes de recensement'
)

@section('content')

<div class="mx-auto max-w-7xl space-y-6">

    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}

    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">

        <div>

            <h1 class="font-poppins text-2xl font-semibold text-[#212529]">
                Statistiques
            </h1>

            <p class="mt-1 font-poppins text-sm text-[#6b7280]">

                @isset($prefecture)

                    Préfecture de {{ $prefecture->nom }}

                @else

                    Vue globale du recensement agricole

                @endisset

            </p>

        </div>


        {{-- =====================================================
             FILTRE CAMPAGNE
        ====================================================== --}}

        <form
            method="GET"
            action="{{ route('statistiques.index') }}"
            class="w-full lg:w-auto"
        >

            <label class="mb-1 block font-poppins text-xs font-medium text-[#6b7280]">
                Campagne
            </label>

            <select
                name="campagne_id"
                onchange="this.form.submit()"
                class="w-full min-w-[260px] rounded-lg border border-[#d1d5db]
                       bg-white px-4 py-2.5 font-poppins text-sm text-[#212529]
                       focus:border-[#006a4f] focus:ring-[#006a4f]"
            >

                @forelse($campagnes as $item)

                    <option
                        value="{{ $item->idCampagne }}"
                        @selected(
                            $campagne &&
                            (int) $campagne->idCampagne ===
                            (int) $item->idCampagne
                        )
                    >
                        {{ $item->libelle }}
                        — {{ ucfirst($item->statut) }}
                    </option>

                @empty

                    <option value="">
                        Aucune campagne
                    </option>

                @endforelse

            </select>

        </form>

    </div>


    {{-- =========================================================
         CAMPAGNE SÉLECTIONNÉE
    ========================================================== --}}

    @if($campagne)

        <div class="rounded-xl border border-[#d9ebe5] bg-[#f3faf7] p-5">

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <p class="font-poppins text-xs font-semibold uppercase tracking-wider text-[#006a4f]">
                        Campagne analysée
                    </p>

                    <h2 class="mt-1 font-poppins text-lg font-semibold text-[#212529]">
                        {{ $campagne->libelle }}
                    </h2>

                </div>


                <div class="flex items-center gap-3">

                    @if($campagne->statut === 'active')

                        <span class="rounded-full bg-[#dff3eb] px-3 py-1 font-poppins text-xs font-semibold text-[#006a4f]">
                            Active
                        </span>

                    @elseif($campagne->statut === 'cloturee')

                        <span class="rounded-full bg-gray-100 px-3 py-1 font-poppins text-xs font-semibold text-gray-700">
                            Clôturée
                        </span>

                    @elseif($campagne->statut === 'archivee')

                        <span class="rounded-full bg-gray-100 px-3 py-1 font-poppins text-xs font-semibold text-gray-700">
                            Archivée
                        </span>

                    @else

                        <span class="rounded-full bg-amber-50 px-3 py-1 font-poppins text-xs font-semibold text-amber-700">
                            Planifiée
                        </span>

                    @endif

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
         INDICATEURS
    ========================================================== --}}

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">


        {{-- ÉQUIPES --}}

        <div class="rounded-xl border border-[#e5e7eb] bg-white p-5">

            <p class="font-poppins text-sm text-[#6b7280]">
                Équipes
            </p>

            <p class="mt-2 font-poppins text-3xl font-semibold text-[#212529]">
                {{ $nombreEquipes }}
            </p>

        </div>


        {{-- AGENTS --}}

        <div class="rounded-xl border border-[#e5e7eb] bg-white p-5">

            <p class="font-poppins text-sm text-[#6b7280]">
                Agents recenseurs
            </p>

            <p class="mt-2 font-poppins text-3xl font-semibold text-[#212529]">
                {{ $nombreAgents }}
            </p>

        </div>


        {{-- VILLAGES --}}

        <div class="rounded-xl border border-[#e5e7eb] bg-white p-5">

            <p class="font-poppins text-sm text-[#6b7280]">
                Villages
            </p>

            <p class="mt-2 font-poppins text-3xl font-semibold text-[#212529]">
                {{ $nombreVillages }}
            </p>

        </div>


        {{-- AFFECTATIONS --}}

        <div class="rounded-xl border border-[#e5e7eb] bg-white p-5">

            <p class="font-poppins text-sm text-[#6b7280]">
                Affectations
            </p>

            <p class="mt-2 font-poppins text-3xl font-semibold text-[#212529]">
                {{ $nombreAffectations }}
            </p>

        </div>

    </div>


    {{-- =========================================================
         PROGRESSION
    ========================================================== --}}

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        <div class="rounded-xl border border-[#e5e7eb] bg-white p-6 lg:col-span-2">

            <div class="flex items-center justify-between">

                <div>

                    <h2 class="font-poppins text-base font-semibold text-[#212529]">
                        Couverture territoriale
                    </h2>

                    <p class="mt-1 font-poppins text-sm text-[#6b7280]">
                        Villages ayant reçu au moins une affectation
                    </p>

                </div>

                <span class="font-poppins text-2xl font-semibold text-[#006a4f]">
                    {{ $progression }}%
                </span>

            </div>


            <div class="mt-6 h-4 overflow-hidden rounded-full bg-[#e5e7eb]">

                <div
                    class="h-full rounded-full bg-[#006a4f] transition-all duration-500"
                    style="width: {{ $progression }}%"
                ></div>

            </div>


            <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-3">

                <div class="rounded-lg bg-[#f9fafb] p-4">

                    <p class="font-poppins text-xs text-[#6b7280]">
                        Villages affectés
                    </p>

                    <p class="mt-1 font-poppins text-xl font-semibold text-[#212529]">
                        {{ $villagesAffectes }}
                    </p>

                </div>


                @isset($villagesNonAffectes)

                    <div class="rounded-lg bg-[#f9fafb] p-4">

                        <p class="font-poppins text-xs text-[#6b7280]">
                            Villages non affectés
                        </p>

                        <p class="mt-1 font-poppins text-xl font-semibold text-[#212529]">
                            {{ $villagesNonAffectes }}
                        </p>

                    </div>

                @endisset


                <div class="rounded-lg bg-[#f9fafb] p-4">

                    <p class="font-poppins text-xs text-[#6b7280]">
                        Affectations actives
                    </p>

                    <p class="mt-1 font-poppins text-xl font-semibold text-[#006a4f]">
                        {{ $affectationsActives }}
                    </p>

                </div>

            </div>

        </div>


        {{-- ÉTAT AFFECTATIONS --}}

        <div class="rounded-xl border border-[#e5e7eb] bg-white p-6">

            <h2 class="font-poppins text-base font-semibold text-[#212529]">
                État des affectations
            </h2>


            <div class="mt-6 space-y-5">

                <div class="flex items-center justify-between">

                    <span class="font-poppins text-sm text-[#6b7280]">
                        Total
                    </span>

                    <span class="font-poppins font-semibold text-[#212529]">
                        {{ $nombreAffectations }}
                    </span>

                </div>


                <div class="flex items-center justify-between">

                    <span class="font-poppins text-sm text-[#6b7280]">
                        Actives
                    </span>

                    <span class="font-poppins font-semibold text-[#006a4f]">
                        {{ $affectationsActives }}
                    </span>

                </div>


                <div class="flex items-center justify-between">

                    <span class="font-poppins text-sm text-[#6b7280]">
                        Annulées
                    </span>

                    <span class="font-poppins font-semibold text-[#ab1717]">
                        {{ $affectationsAnnulees }}
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         ADMIN : PRÉFECTURES
    ========================================================== --}}

    @isset($prefectures)

        <div class="rounded-xl border border-[#e5e7eb] bg-white">

            <div class="border-b border-[#e5e7eb] px-6 py-5">

                <h2 class="font-poppins text-base font-semibold text-[#212529]">
                    Analyse par préfecture
                </h2>

                <p class="mt-1 font-poppins text-sm text-[#6b7280]">
                    Comparaison de la couverture territoriale
                </p>

            </div>


            <div class="overflow-x-auto">

                <table class="min-w-full">

                    <thead class="bg-[#f9fafb]">

                        <tr>

                            <th class="px-6 py-3 text-left font-poppins text-xs font-semibold uppercase tracking-wider text-[#6b7280]">
                                Préfecture
                            </th>

                            <th class="px-6 py-3 text-left font-poppins text-xs font-semibold uppercase tracking-wider text-[#6b7280]">
                                Équipes
                            </th>

                            <th class="px-6 py-3 text-left font-poppins text-xs font-semibold uppercase tracking-wider text-[#6b7280]">
                                Villages
                            </th>

                            <th class="px-6 py-3 text-left font-poppins text-xs font-semibold uppercase tracking-wider text-[#6b7280]">
                                Affectés
                            </th>

                            <th class="px-6 py-3 text-left font-poppins text-xs font-semibold uppercase tracking-wider text-[#6b7280]">
                                Non affectés
                            </th>

                            <th class="px-6 py-3 text-left font-poppins text-xs font-semibold uppercase tracking-wider text-[#6b7280]">
                                Progression
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-[#e5e7eb]">

                        @forelse($prefectures as $item)

                            <tr class="hover:bg-[#f9fafb]">

                                <td class="px-6 py-4 font-poppins text-sm font-medium text-[#212529]">
                                    {{ $item['nom'] }}
                                </td>

                                <td class="px-6 py-4 font-poppins text-sm text-[#434343]">
                                    {{ $item['equipes'] }}
                                </td>

                                <td class="px-6 py-4 font-poppins text-sm text-[#434343]">
                                    {{ $item['villages'] }}
                                </td>

                                <td class="px-6 py-4 font-poppins text-sm text-[#434343]">
                                    {{ $item['villages_affectes'] }}
                                </td>

                                <td class="px-6 py-4 font-poppins text-sm text-[#434343]">
                                    {{ $item['villages_non_affectes'] }}
                                </td>

                                <td class="px-6 py-4">

                                    <div class="flex items-center gap-3">

                                        <div class="h-2 w-24 overflow-hidden rounded-full bg-[#e5e7eb]">

                                            <div
                                                class="h-full rounded-full bg-[#006a4f]"
                                                style="width: {{ $item['progression'] }}%"
                                            ></div>

                                        </div>

                                        <span class="font-poppins text-sm font-semibold text-[#212529]">
                                            {{ $item['progression'] }}%
                                        </span>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="px-6 py-10 text-center font-poppins text-sm text-[#6b7280]"
                                >
                                    Aucune donnée disponible.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    @endisset


    {{-- =========================================================
         DPA : COMMUNES
    ========================================================== --}}

    @isset($communes)

        <div class="rounded-xl border border-[#e5e7eb] bg-white">

            <div class="border-b border-[#e5e7eb] px-6 py-5">

                <h2 class="font-poppins text-base font-semibold text-[#212529]">
                    Analyse par commune
                </h2>

                <p class="mt-1 font-poppins text-sm text-[#6b7280]">
                    Situation détaillée dans votre préfecture
                </p>

            </div>


            <div class="overflow-x-auto">

                <table class="min-w-full">

                    <thead class="bg-[#f9fafb]">

                        <tr>

                            <th class="px-6 py-3 text-left font-poppins text-xs font-semibold uppercase tracking-wider text-[#6b7280]">
                                Commune
                            </th>

                            <th class="px-6 py-3 text-left font-poppins text-xs font-semibold uppercase tracking-wider text-[#6b7280]">
                                Villages
                            </th>

                            <th class="px-6 py-3 text-left font-poppins text-xs font-semibold uppercase tracking-wider text-[#6b7280]">
                                Affectés
                            </th>

                            <th class="px-6 py-3 text-left font-poppins text-xs font-semibold uppercase tracking-wider text-[#6b7280]">
                                Non affectés
                            </th>

                            <th class="px-6 py-3 text-left font-poppins text-xs font-semibold uppercase tracking-wider text-[#6b7280]">
                                Affectations
                            </th>

                            <th class="px-6 py-3 text-left font-poppins text-xs font-semibold uppercase tracking-wider text-[#6b7280]">
                                Progression
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-[#e5e7eb]">

                        @forelse($communes as $commune)

                            <tr class="hover:bg-[#f9fafb]">

                                <td class="px-6 py-4 font-poppins text-sm font-medium text-[#212529]">
                                    {{ $commune['nom'] }}
                                </td>

                                <td class="px-6 py-4 font-poppins text-sm text-[#434343]">
                                    {{ $commune['villages'] }}
                                </td>

                                <td class="px-6 py-4 font-poppins text-sm text-[#434343]">
                                    {{ $commune['villages_affectes'] }}
                                </td>

                                <td class="px-6 py-4 font-poppins text-sm text-[#434343]">
                                    {{ $commune['villages_non_affectes'] }}
                                </td>

                                <td class="px-6 py-4 font-poppins text-sm text-[#434343]">
                                    {{ $commune['affectations'] }}
                                </td>

                                <td class="px-6 py-4">

                                    <div class="flex items-center gap-3">

                                        <div class="h-2 w-24 overflow-hidden rounded-full bg-[#e5e7eb]">

                                            <div
                                                class="h-full rounded-full bg-[#006a4f]"
                                                style="width: {{ $commune['progression'] }}%"
                                            ></div>

                                        </div>

                                        <span class="font-poppins text-sm font-semibold text-[#212529]">
                                            {{ $commune['progression'] }}%
                                        </span>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="px-6 py-10 text-center font-poppins text-sm text-[#6b7280]"
                                >
                                    Aucune commune disponible.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    @endisset


    {{-- =========================================================
         HISTORIQUE
    ========================================================== --}}

    <div class="rounded-xl border border-[#e5e7eb] bg-white">

        <div class="border-b border-[#e5e7eb] px-6 py-5">

            <h2 class="font-poppins text-base font-semibold text-[#212529]">
                Historique des campagnes
            </h2>

            <p class="mt-1 font-poppins text-sm text-[#6b7280]">
                Comparaison de la couverture territoriale des campagnes
            </p>

        </div>


        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="bg-[#f9fafb]">

                    <tr>

                        <th class="px-6 py-3 text-left font-poppins text-xs font-semibold uppercase tracking-wider text-[#6b7280]">
                            Campagne
                        </th>

                        <th class="px-6 py-3 text-left font-poppins text-xs font-semibold uppercase tracking-wider text-[#6b7280]">
                            Statut
                        </th>

                        <th class="px-6 py-3 text-left font-poppins text-xs font-semibold uppercase tracking-wider text-[#6b7280]">
                            Villages affectés
                        </th>

                        <th class="px-6 py-3 text-left font-poppins text-xs font-semibold uppercase tracking-wider text-[#6b7280]">
                            Progression
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-[#e5e7eb]">

                    @forelse($historique as $item)

                        <tr class="hover:bg-[#f9fafb]">

                            <td class="px-6 py-4 font-poppins text-sm font-medium text-[#212529]">
                                {{ $item['libelle'] }}
                            </td>

                            <td class="px-6 py-4">

                                <span class="font-poppins text-sm text-[#434343]">
                                    {{ ucfirst($item['statut']) }}
                                </span>

                            </td>

                            <td class="px-6 py-4 font-poppins text-sm text-[#434343]">
                                {{ $item['villages_affectes'] }}
                            </td>

                            <td class="px-6 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="h-2 w-28 overflow-hidden rounded-full bg-[#e5e7eb]">

                                        <div
                                            class="h-full rounded-full bg-[#006a4f]"
                                            style="width: {{ $item['progression'] }}%"
                                        ></div>

                                    </div>

                                    <span class="font-poppins text-sm font-semibold text-[#212529]">
                                        {{ $item['progression'] }}%
                                    </span>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="4"
                                class="px-6 py-10 text-center font-poppins text-sm text-[#6b7280]"
                            >
                                Aucune campagne disponible.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- =========================================================
         INFORMATION
    ========================================================== --}}

    <div class="rounded-xl border border-[#e5e7eb] bg-white p-5">

        <div class="flex items-start gap-3">

            <svg
                class="mt-0.5 h-5 w-5 shrink-0 text-[#006a4f]"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.8"
                    d="M12 8v4m0 4h.01
                       M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
            </svg>

            <div>

                <p class="font-poppins text-sm font-semibold text-[#212529]">
                    À propos de ces statistiques
                </p>

                <p class="mt-1 font-poppins text-sm leading-6 text-[#6b7280]">
                    La progression affichée actuellement correspond à la couverture
                    des villages par les affectations de la campagne sélectionnée.
                    Les statistiques de recensement effectif seront intégrées
                    lorsque les données du module de recensement seront disponibles.
                </p>

            </div>

        </div>

    </div>

</div>

@endsection