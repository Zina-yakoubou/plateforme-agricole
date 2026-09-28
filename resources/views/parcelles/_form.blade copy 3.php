{{-- resources/views/parcelles/_form.blade.php --}}

@php
    /*
    |--------------------------------------------------------------------------
    | Mode du formulaire
    |--------------------------------------------------------------------------
    */
    $isEdit = isset($parcelle);

    /*
    |--------------------------------------------------------------------------
    | Points GPS existants
    |--------------------------------------------------------------------------
    */
    $pointsExistants = collect();

    if ($isEdit && isset($parcelle->pointsGPS)) {
        $pointsExistants = $parcelle->pointsGPS
            ->sortBy('ordre')
            ->values();
    }

    /*
    |--------------------------------------------------------------------------
    | Cultures existantes en modification
    |--------------------------------------------------------------------------
    |
    | Structure envoyée au composant Alpine.
    |
    */
    $culturesForm = [];

    if ($isEdit && $parcelle->relationLoaded('cultures')) {
        foreach ($parcelle->cultures as $cultureParcelle) {

            $intrantsForm = [];

            if ($cultureParcelle->relationLoaded('intrants')) {
                foreach ($cultureParcelle->intrants as $cultureIntrant) {
                    $intrantsForm[] = [
                        'intrant_id' => (string) $cultureIntrant->intrant_id,
                        'quantite' => $cultureIntrant->quantite,
                        'nombreApplications' => $cultureIntrant->nombreApplications,
                        'dateApplication' => $cultureIntrant->dateApplication,
                        'observations' => $cultureIntrant->observations,
                    ];
                }
            }

            $culturesForm[] = [
                'culture_id' => (string) $cultureParcelle->culture_id,
                'modeCulture' => $cultureParcelle->modeCulture,
                'superficieCultivee' => $cultureParcelle->superficieCultivee,
                'dateSemis' => $cultureParcelle->dateSemis,
                'dateRecoltePrevue' => $cultureParcelle->dateRecoltePrevue,
                'dateRecolteEffective' => $cultureParcelle->dateRecolteEffective,
                'irriguee' => (bool) $cultureParcelle->irriguee,
                'etatCulture' => $cultureParcelle->etatCulture,
                'observations' => $cultureParcelle->observations,
                'intrants' => $intrantsForm,
            ];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Anciennes données en cas d'erreur de validation
    |--------------------------------------------------------------------------
    */
    if (old('cultures') !== null) {
        $culturesForm = old('cultures');
    }

    /*
    |--------------------------------------------------------------------------
    | Valeurs par défaut
    |--------------------------------------------------------------------------
    */
    if (empty($culturesForm)) {
        $culturesForm = [
            [
                'culture_id' => '',
                'modeCulture' => 'principale',
                'superficieCultivee' => '',
                'dateSemis' => '',
                'dateRecoltePrevue' => '',
                'dateRecolteEffective' => '',
                'irriguee' => false,
                'etatCulture' => 'semis',
                'observations' => '',
                'intrants' => [],
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Référentiel intrants
    |--------------------------------------------------------------------------
    */
    $intrantsReferentiel = collect($intrants ?? [])
        ->map(function ($intrant) {
            return [
                'id' => $intrant->idIntrant,
                'nom' => $intrant->nom,
                'type' => $intrant->type,
                'unite' => $intrant->unite,
            ];
        })
        ->values()
        ->toArray();

    /*
    |--------------------------------------------------------------------------
    | Points GPS pour Alpine
    |--------------------------------------------------------------------------
    */
    $pointsGpsForm = $pointsExistants
        ->map(function ($point) {
            return [
                'latitude' => (float) $point->latitude,
                'longitude' => (float) $point->longitude,
                'ordre' => $point->ordre,
            ];
        })
        ->values()
        ->toArray();
@endphp


{{-- ================================================================
     ERREURS GÉNÉRALES
================================================================ --}}
@if ($errors->any())
    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4">
        <div class="flex items-start gap-3">
            <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-600"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.73-3L13.73 4a2 2 0 00-3.46 0L3.34 16a2 2 0 001.73 3z"/>
            </svg>

            <div>
                <p class="text-sm font-semibold text-red-800">
                    Vérifiez les informations saisies.
                </p>

                <ul class="mt-2 space-y-1 text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif


<div
    x-data="parcelleForm()"
    x-init="initialiser()"
    class="space-y-6"
>

    {{-- ============================================================
         1. IDENTIFICATION DE LA PARCELLE
    ============================================================= --}}
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-100 bg-slate-50 px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-[#006a4f]">
                    <svg class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.8"
                              d="M3 7.5L12 3l9 4.5v9L12 21l-9-4.5v-9z"/>
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.8"
                              d="M12 21V12m9-4.5l-9 4.5L3 7.5"/>
                    </svg>
                </div>

                <div>
                    <h2 class="text-base font-bold text-slate-800">
                        Identification de la parcelle
                    </h2>

                    <p class="text-xs text-slate-500">
                        Informations générales et superficie
                    </p>
                </div>
            </div>
        </div>

        <div class="grid gap-5 p-6 md:grid-cols-2">

            {{-- NUMÉRO --}}
            <div>
                <label for="numeroParcelle"
                       class="mb-2 block text-sm font-semibold text-slate-700">
                    Numéro de la parcelle
                </label>

                <input
                    type="text"
                    name="numeroParcelle"
                    id="numeroParcelle"
                    value="{{ old('numeroParcelle', $parcelle->numeroParcelle ?? $numeroParcelle ?? '') }}"
                    readonly
                    class="w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-600"
                >

                <p class="mt-1.5 text-xs text-slate-400">
                    Numéro généré automatiquement par le système.
                </p>

                @error('numeroParcelle')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>


            {{-- SUPERFICIE --}}
            <div>
                <label for="superficie"
                       class="mb-2 block text-sm font-semibold text-slate-700">
                    Superficie de la parcelle
                    <span class="text-red-500">*</span>
                </label>

                <div class="relative">
                    <input
                        type="number"
                        step="0.01"
                        min="0.01"
                        name="superficie"
                        id="superficie"
                        x-model="superficie"
                        readonly
                        required
                        class="w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-100 px-4 py-3 pr-16 text-sm font-semibold text-slate-700"
                    >

                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-medium text-slate-500">
                        ha
                    </span>
                </div>

                <p class="mt-1.5 text-xs text-slate-400">
                    Calculée automatiquement à partir du contour GPS.
                </p>

                @error('superficie')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

        </div>
    </section>


    {{-- ============================================================
         2. LOCALISATION GPS
    ============================================================= --}}
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-100 bg-slate-50 px-6 py-4">
            <div class="flex items-center gap-3">

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-[#006a4f]">
                    <svg class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.8"
                              d="M12 21s7-5.2 7-11a7 7 0 10-14 0c0 5.8 7 11 7 11z"/>
                        <circle cx="12"
                                cy="10"
                                r="2.5"
                                stroke-width="1.8"/>
                    </svg>
                </div>

                <div>
                    <h2 class="text-base font-bold text-slate-800">
                        Localisation GPS
                    </h2>

                    <p class="text-xs text-slate-500">
                        Relevé du contour de la parcelle
                    </p>
                </div>
            </div>
        </div>


        <div class="space-y-5 p-6">

            {{-- ACTION GPS --}}
            <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-4">

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <p class="text-sm font-semibold text-slate-800">
                            Relevé du contour
                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            Marchez autour de la parcelle pour enregistrer au moins trois points.
                        </p>
                    </div>

                    <button
                        type="button"
                        @click="toggleGPS()"
                        :disabled="gpsChargement"
                        class="inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold text-white transition"
                        :class="gpsActif
                            ? 'bg-red-600 hover:bg-red-700'
                            : 'bg-[#006a4f] hover:bg-[#00583f]'"
                    >
                        <template x-if="!gpsActif">
                            <span class="inline-flex items-center gap-2">
                                <svg class="h-4 w-4"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M12 5v14m-7-7h14"/>
                                </svg>

                                Démarrer le GPS
                            </span>
                        </template>

                        <template x-if="gpsActif">
                            <span class="inline-flex items-center gap-2">
                                <svg class="h-4 w-4"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <rect x="6"
                                          y="6"
                                          width="12"
                                          height="12"
                                          rx="1"
                                          stroke-width="2"/>
                                </svg>

                                Arrêter le GPS
                            </span>
                        </template>
                    </button>

                </div>


                {{-- STATUT GPS --}}
                <div class="mt-4 flex flex-wrap gap-2">

                    <span
                        class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-semibold"
                        :class="gpsActif
                            ? 'bg-emerald-100 text-emerald-700'
                            : 'bg-slate-100 text-slate-600'"
                    >
                        <span
                            class="h-2 w-2 rounded-full"
                            :class="gpsActif ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400'"
                        ></span>

                        <span x-text="gpsActif ? 'GPS actif' : 'GPS arrêté'"></span>
                    </span>

                    <span class="rounded-full bg-white px-3 py-1.5 text-xs font-semibold text-slate-600">
                        Points :
                        <span x-text="pointsGPS.length"></span>
                    </span>

                </div>
            </div>


            {{-- COORDONNÉES --}}
            <div class="grid gap-4 md:grid-cols-3">

                <div>
                    <label for="latitude"
                           class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Latitude
                    </label>

                    <input
                        type="text"
                        name="latitude"
                        id="latitude"
                        x-model="latitude"
                        readonly
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600"
                    >

                    @error('latitude')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <div>
                    <label for="longitude"
                           class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Longitude
                    </label>

                    <input
                        type="text"
                        name="longitude"
                        id="longitude"
                        x-model="longitude"
                        readonly
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600"
                    >

                    @error('longitude')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <div>
                    <label for="precisionGPS"
                           class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Précision GPS
                    </label>

                    <div class="relative">
                        <input
                            type="text"
                            name="precisionGPS"
                            id="precisionGPS"
                            x-model="precisionGPS"
                            readonly
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 pr-12 text-sm text-slate-600"
                        >

                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-400">
                            m
                        </span>
                    </div>

                    @error('precisionGPS')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>


            {{-- POINTS GPS --}}
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Points du contour
                </label>

                <div class="overflow-hidden rounded-xl border border-slate-200">

                    <div class="grid grid-cols-3 border-b border-slate-200 bg-slate-50 px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <span>#</span>
                        <span>Latitude</span>
                        <span>Longitude</span>
                    </div>

                    <div
                        x-show="pointsGPS.length > 0"
                        class="divide-y divide-slate-100"
                    >
                        <template x-for="(point, index) in pointsGPS" :key="index">

                            <div class="grid grid-cols-3 px-4 py-3 text-sm text-slate-600">
                                <span x-text="index + 1"></span>
                                <span x-text="Number(point.latitude).toFixed(6)"></span>
                                <span x-text="Number(point.longitude).toFixed(6)"></span>
                            </div>

                        </template>
                    </div>

                    <div
                        x-show="pointsGPS.length === 0"
                        class="px-4 py-6 text-center text-sm text-slate-400"
                    >
                        Aucun point GPS enregistré.
                    </div>

                </div>
            </div>


            {{-- JSON GPS --}}
            <input
                type="hidden"
                name="points_gps"
                x-model="pointsGpsJson"
            />

        </div>
    </section>


    {{-- ============================================================
         3. CARACTÉRISTIQUES DE LA PARCELLE
    ============================================================= --}}
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-100 bg-slate-50 px-6 py-4">

            <div class="flex items-center gap-3">

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-[#006a4f]">
                    <svg class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.8"
                              d="M4 19h16M5 19V8l7-4 7 4v11M9 19v-6h6v6"/>
                    </svg>
                </div>

                <div>
                    <h2 class="text-base font-bold text-slate-800">
                        Caractéristiques de la parcelle
                    </h2>

                    <p class="text-xs text-slate-500">
                        Informations physiques et agricoles
                    </p>
                </div>

            </div>
        </div>


        <div class="space-y-5 p-6">

            {{-- TYPE SOL --}}
            <div>
                <label for="typeSol"
                       class="mb-2 block text-sm font-semibold text-slate-700">
                    Type de sol
                    <span class="text-red-500">*</span>
                </label>

                <select
                    name="typeSol"
                    id="typeSol"
                    required
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                >
                    <option value="">
                        Sélectionner le type de sol
                    </option>

                    @foreach($typeSols ?? [] as $value => $label)
                        <option
                            value="{{ $value }}"
                            @selected(
                                old(
                                    'typeSol',
                                    $parcelle->typeSol ?? ''
                                ) === $value
                            )
                        >
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                @error('typeSol')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>


            {{-- FAIRE VALOIR + IRRIGATION --}}
            <div class="grid gap-5 md:grid-cols-2">

                <div>
                    <label for="modeFaireValoir"
                           class="mb-2 block text-sm font-semibold text-slate-700">
                        Mode de faire-valoir
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="modeFaireValoir"
                        id="modeFaireValoir"
                        required
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                    >
                        <option value="">
                            Sélectionner
                        </option>

                        @foreach([
                            'proprietaire' => 'Propriétaire',
                            'location' => 'Location',
                            'pret' => 'Prêt',
                            'metayage' => 'Métayage',
                            'autre' => 'Autre',
                        ] as $value => $label)

                            <option
                                value="{{ $value }}"
                                @selected(
                                    old(
                                        'modeFaireValoir',
                                        $parcelle->modeFaireValoir ?? ''
                                    ) === $value
                                )
                            >
                                {{ $label }}
                            </option>

                        @endforeach
                    </select>

                    @error('modeFaireValoir')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                <div>
                    <label for="modeIrrigation"
                           class="mb-2 block text-sm font-semibold text-slate-700">
                        Mode d'irrigation
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="modeIrrigation"
                        id="modeIrrigation"
                        required
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                    >
                        <option value="">
                            Sélectionner
                        </option>

                        @foreach([
                            'pluvial' => 'Pluvial',
                            'gravitaire' => 'Gravitaire',
                            'pompage' => 'Pompage',
                            'aucun' => 'Aucun',
                            'autre' => 'Autre',
                        ] as $value => $label)

                            <option
                                value="{{ $value }}"
                                @selected(
                                    old(
                                        'modeIrrigation',
                                        $parcelle->modeIrrigation ?? ''
                                    ) === $value
                                )
                            >
                                {{ $label }}
                            </option>

                        @endforeach
                    </select>

                    @error('modeIrrigation')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>


            {{-- INDICATEURS --}}
            <div class="grid gap-4 md:grid-cols-3">

                {{-- CULTIVÉE --}}
                <label class="flex cursor-pointer items-center justify-between rounded-xl border border-slate-200 p-4 transition hover:border-emerald-200 hover:bg-emerald-50/40">

                    <div>
                        <p class="text-sm font-semibold text-slate-700">
                            Parcelle cultivée
                        </p>

                        <p class="mt-1 text-xs text-slate-400">
                            La parcelle est actuellement cultivée
                        </p>
                    </div>

                    <input
                        type="checkbox"
                        name="estCultivee"
                        value="1"
                        @checked(
                            old(
                                'estCultivee',
                                $parcelle->estCultivee ?? false
                            )
                        )
                        class="h-5 w-5 rounded border-slate-300 text-[#006a4f] focus:ring-emerald-500"
                    >

                </label>


                {{-- JACHÈRE --}}
                <label class="flex cursor-pointer items-center justify-between rounded-xl border border-slate-200 p-4 transition hover:border-emerald-200 hover:bg-emerald-50/40">

                    <div>
                        <p class="text-sm font-semibold text-slate-700">
                            Jachère
                        </p>

                        <p class="mt-1 text-xs text-slate-400">
                            La parcelle est en jachère
                        </p>
                    </div>

                    <input
                        type="checkbox"
                        name="estJachere"
                        value="1"
                        @checked(
                            old(
                                'estJachere',
                                $parcelle->estJachere ?? false
                            )
                        )
                        class="h-5 w-5 rounded border-slate-300 text-[#006a4f] focus:ring-emerald-500"
                    >

                </label>


                {{-- ARBRES --}}
                <label class="flex cursor-pointer items-center justify-between rounded-xl border border-slate-200 p-4 transition hover:border-emerald-200 hover:bg-emerald-50/40">

                    <div>
                        <p class="text-sm font-semibold text-slate-700">
                            Présence d'arbres
                        </p>

                        <p class="mt-1 text-xs text-slate-400">
                            Arbres présents sur la parcelle
                        </p>
                    </div>

                    <input
                        type="checkbox"
                        name="presenceArbres"
                        value="1"
                        @checked(
                            old(
                                'presenceArbres',
                                $parcelle->presenceArbres ?? false
                            )
                        )
                        class="h-5 w-5 rounded border-slate-300 text-[#006a4f] focus:ring-emerald-500"
                    >

                </label>

            </div>


            {{-- OBSERVATIONS --}}
            <div>
                <label for="observations"
                       class="mb-2 block text-sm font-semibold text-slate-700">
                    Observations
                </label>

                <textarea
                    name="observations"
                    id="observations"
                    rows="3"
                    placeholder="Observation éventuelle sur la parcelle..."
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                >{{ old('observations', $parcelle->observations ?? '') }}</textarea>

                @error('observations')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

        </div>
    </section>


    {{-- ============================================================
         4. CULTURES
    ============================================================= --}}
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-100 bg-slate-50 px-6 py-4">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-[#006a4f]">
                        <svg class="h-5 w-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M12 21V10m0 0c-3.5 0-6-2.2-6-5 3.5 0 6 2.2 6 5zm0 0c3.5 0 6-2.2 6-5-3.5 0-6 2.2-6 5z"/>
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M12 21c0-4 1.5-6.5 4-8"/>
                        </svg>
                    </div>

                    <div>
                        <h2 class="text-base font-bold text-slate-800">
                            Cultures de la parcelle
                        </h2>

                        <p class="text-xs text-slate-500">
                            Déclarez les cultures présentes sur cette parcelle.
                        </p>
                    </div>

                </div>


                <button
                    type="button"
                    @click="ajouterCulture()"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#006a4f] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#00583f]"
                >
                    <svg class="h-4 w-4"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 5v14m-7-7h14"/>
                    </svg>

                    Ajouter une culture
                </button>

            </div>
        </div>


        <div class="divide-y divide-slate-200 p-6">

            <template
                x-for="(culture, cultureIndex) in cultures"
                :key="cultureIndex"
            >

                <div class="py-6 first:pt-0">

                    {{-- EN-TÊTE CULTURE --}}
                    <div class="mb-5 flex items-center justify-between">

                        <div>
                            <p class="text-sm font-bold text-slate-800">
                                Culture
                                <span x-text="cultureIndex + 1"></span>
                            </p>

                            <p class="mt-0.5 text-xs text-slate-400">
                                Informations sur la culture
                            </p>
                        </div>

                        <button
                            type="button"
                            x-show="cultures.length > 1"
                            @click="supprimerCulture(cultureIndex)"
                            class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50"
                        >
                            <svg class="h-4 w-4"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="1.8"
                                      d="M6 7h12M9 7V5h6v2m-7 0l.7 12h6.6L16 7M10 10v6m4-6v6"/>
                            </svg>

                            Supprimer
                        </button>

                    </div>


                    <div class="space-y-5">

                        {{-- CULTURE + MODE --}}
                        <div class="grid gap-5 md:grid-cols-2">

                            {{-- CULTURE --}}
                            <div>
                                <label
                                    :for="`culture_${cultureIndex}`"
                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                >
                                    Culture
                                    <span class="text-red-500">*</span>
                                </label>

                                <select
                                    :name="`cultures[${cultureIndex}][culture_id]`"
                                    :id="`culture_${cultureIndex}`"
                                    x-model="culture.culture_id"
                                    required
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                                >
                                    <option value="">
                                        Sélectionner une culture
                                    </option>

                                    @foreach($cultures ?? [] as $cultureReferentiel)
                                        <option value="{{ $cultureReferentiel->idCulture }}">
                                            {{ $cultureReferentiel->nomCulture }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('cultures.*.culture_id')
                                    <p class="mt-1 text-xs text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>


                            {{-- MODE --}}
                            <div>
                                <label
                                    :for="`modeCulture_${cultureIndex}`"
                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                >
                                    Mode de culture
                                    <span class="text-red-500">*</span>
                                </label>

                                <select
                                    :name="`cultures[${cultureIndex}][modeCulture]`"
                                    :id="`modeCulture_${cultureIndex}`"
                                    x-model="culture.modeCulture"
                                    required
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                                >
                                    <option value="principale">
                                        Principale
                                    </option>

                                    <option value="associee">
                                        Associée
                                    </option>
                                </select>
                            </div>

                        </div>


                        {{-- SUPERFICIE CULTIVÉE --}}
                        <div>
                            <label
                                :for="`superficieCultivee_${cultureIndex}`"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Superficie cultivée
                            </label>

                            <div class="relative">

                                <input
                                    type="number"
                                    step="0.01"
                                    min="0.01"
                                    :name="`cultures[${cultureIndex}][superficieCultivee]`"
                                    :id="`superficieCultivee_${cultureIndex}`"
                                    x-model="culture.superficieCultivee"
                                    placeholder="Ex. 1.50"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 pr-14 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                                >

                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-400">
                                    ha
                                </span>

                            </div>

                            <p class="mt-1.5 text-xs text-slate-400">
                                Facultatif.
                            </p>
                        </div>


                        {{-- DATES --}}
                        <div class="grid gap-5 md:grid-cols-3">

                            <div>
                                <label
                                    :for="`dateSemis_${cultureIndex}`"
                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                >
                                    Date de semis
                                </label>

                                <input
                                    type="date"
                                    :name="`cultures[${cultureIndex}][dateSemis]`"
                                    :id="`dateSemis_${cultureIndex}`"
                                    x-model="culture.dateSemis"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                                >
                            </div>


                            <div>
                                <label
                                    :for="`dateRecoltePrevue_${cultureIndex}`"
                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                >
                                    Récolte prévue
                                </label>

                                <input
                                    type="date"
                                    :name="`cultures[${cultureIndex}][dateRecoltePrevue]`"
                                    :id="`dateRecoltePrevue_${cultureIndex}`"
                                    x-model="culture.dateRecoltePrevue"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                                >
                            </div>


                            <div>
                                <label
                                    :for="`dateRecolteEffective_${cultureIndex}`"
                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                >
                                    Récolte effective
                                </label>

                                <input
                                    type="date"
                                    :name="`cultures[${cultureIndex}][dateRecolteEffective]`"
                                    :id="`dateRecolteEffective_${cultureIndex}`"
                                    x-model="culture.dateRecolteEffective"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                                >
                            </div>

                        </div>


                        {{-- ÉTAT + IRRIGATION --}}
                        <div class="grid gap-5 md:grid-cols-2">

                            <div>
                                <label
                                    :for="`etatCulture_${cultureIndex}`"
                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                >
                                    État de la culture
                                </label>

                                <select
                                    :name="`cultures[${cultureIndex}][etatCulture]`"
                                    :id="`etatCulture_${cultureIndex}`"
                                    x-model="culture.etatCulture"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                                >
                                    <option value="semis">Semis</option>
                                    <option value="croissance">Croissance</option>
                                    <option value="floraison">Floraison</option>
                                    <option value="recolte">Récolte</option>
                                    <option value="terminee">Terminée</option>
                                </select>
                            </div>


                            <label class="flex cursor-pointer items-center justify-between rounded-xl border border-slate-200 px-4 py-3">

                                <div>
                                    <p class="text-sm font-semibold text-slate-700">
                                        Culture irriguée
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        Cette culture bénéficie d'une irrigation.
                                    </p>
                                </div>

                                <input
                                    type="checkbox"
                                    :name="`cultures[${cultureIndex}][irriguee]`"
                                    value="1"
                                    x-model="culture.irriguee"
                                    class="h-5 w-5 rounded border-slate-300 text-[#006a4f] focus:ring-emerald-500"
                                >

                            </label>

                        </div>


                        {{-- OBSERVATIONS CULTURE --}}
                        <div>
                            <label
                                :for="`cultureObservations_${cultureIndex}`"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Observations sur la culture
                            </label>

                            <textarea
                                :name="`cultures[${cultureIndex}][observations]`"
                                :id="`cultureObservations_${cultureIndex}`"
                                x-model="culture.observations"
                                rows="2"
                                placeholder="Observation éventuelle..."
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                            ></textarea>
                        </div>


                        {{-- ====================================================
                             INTRANTS
                             (pas de card imbriquée : simple sous-section avec
                             séparateurs, cohérente avec le reste du bloc culture)
                        ===================================================== --}}
                        <div class="border-t border-slate-100 pt-5">

                            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                                <div>
                                    <p class="text-sm font-bold text-slate-800">
                                        Intrants utilisés
                                    </p>

                                    <p class="mt-1 text-xs text-slate-500">
                                        Déclarez uniquement les intrants réellement utilisés pour cette culture.
                                    </p>
                                </div>

                                <button
                                    type="button"
                                    @click="ajouterIntrant(cultureIndex)"
                                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-emerald-200 bg-white px-3 py-2 text-xs font-semibold text-[#006a4f] transition hover:bg-emerald-50"
                                >
                                    <svg class="h-4 w-4"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M12 5v14m-7-7h14"/>
                                    </svg>

                                    Ajouter un intrant
                                </button>

                            </div>


                            <div
                                x-show="culture.intrants.length > 0"
                                class="divide-y divide-slate-100 border-t border-slate-100"
                            >

                                <template
                                    x-for="(intrant, intrantIndex) in culture.intrants"
                                    :key="intrantIndex"
                                >

                                    <div class="py-5 first:pt-0">

                                        <div class="mb-4 flex items-center justify-between">

                                            <p class="text-xs font-bold uppercase tracking-wide text-slate-500">
                                                Intrant
                                                <span x-text="intrantIndex + 1"></span>
                                            </p>

                                            <button
                                                type="button"
                                                @click="supprimerIntrant(cultureIndex, intrantIndex)"
                                                class="rounded-lg p-1.5 text-red-500 transition hover:bg-red-50"
                                                title="Supprimer l'intrant"
                                            >
                                                <svg class="h-4 w-4"
                                                     fill="none"
                                                     viewBox="0 0 24 24"
                                                     stroke="currentColor">
                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          stroke-width="1.8"
                                                          d="M6 7h12M9 7V5h6v2m-7 0l.7 12h6.6L16 7M10 10v6m4-6v6"/>
                                                </svg>
                                            </button>

                                        </div>


                                        <div class="grid gap-4 md:grid-cols-2">

                                            {{-- INTRANT --}}
                                            <div>
                                                <label
                                                    :for="`intrant_${cultureIndex}_${intrantIndex}`"
                                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                                >
                                                    Intrant
                                                    <span class="text-red-500">*</span>
                                                </label>

                                                <select
                                                    :name="`cultures[${cultureIndex}][intrants][${intrantIndex}][intrant_id]`"
                                                    :id="`intrant_${cultureIndex}_${intrantIndex}`"
                                                    x-model="intrant.intrant_id"
                                                    required
                                                    class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                                                >
                                                    <option value="">
                                                        Sélectionner un intrant
                                                    </option>

                                                    @foreach($intrants ?? [] as $intrantReferentiel)
                                                        <option value="{{ $intrantReferentiel->idIntrant }}">
                                                            {{ $intrantReferentiel->nom }}
                                                            — {{ ucfirst(str_replace('_', ' ', $intrantReferentiel->type)) }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                            </div>


                                            {{-- QUANTITÉ --}}
                                            <div>
                                                <label
                                                    :for="`quantite_${cultureIndex}_${intrantIndex}`"
                                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                                >
                                                    Quantité
                                                    <span class="text-red-500">*</span>
                                                </label>

                                                <div class="relative">

                                                    <input
                                                        type="number"
                                                        step="0.01"
                                                        min="0"
                                                        :name="`cultures[${cultureIndex}][intrants][${intrantIndex}][quantite]`"
                                                        :id="`quantite_${cultureIndex}_${intrantIndex}`"
                                                        x-model="intrant.quantite"
                                                        required
                                                        placeholder="Ex. 50"
                                                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 pr-16 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                                                    >

                                                    <span
                                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-medium text-slate-400"
                                                        x-text="trouverUnite(intrant.intrant_id)"
                                                    ></span>

                                                </div>
                                            </div>


                                            {{-- NOMBRE APPLICATIONS --}}
                                            <div>
                                                <label
                                                    :for="`nombreApplications_${cultureIndex}_${intrantIndex}`"
                                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                                >
                                                    Nombre d'applications
                                                    <span class="text-red-500">*</span>
                                                </label>

                                                <input
                                                    type="number"
                                                    min="1"
                                                    :name="`cultures[${cultureIndex}][intrants][${intrantIndex}][nombreApplications]`"
                                                    :id="`nombreApplications_${cultureIndex}_${intrantIndex}`"
                                                    x-model="intrant.nombreApplications"
                                                    required
                                                    class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                                                >
                                            </div>


                                            {{-- DATE APPLICATION --}}
                                            <div>
                                                <label
                                                    :for="`dateApplication_${cultureIndex}_${intrantIndex}`"
                                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                                >
                                                    Date d'application
                                                </label>

                                                <input
                                                    type="date"
                                                    :name="`cultures[${cultureIndex}][intrants][${intrantIndex}][dateApplication]`"
                                                    :id="`dateApplication_${cultureIndex}_${intrantIndex}`"
                                                    x-model="intrant.dateApplication"
                                                    class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                                                >
                                            </div>

                                        </div>


                                        {{-- OBSERVATIONS INTRANT --}}
                                        <div class="mt-4">

                                            <label
                                                :for="`intrantObservations_${cultureIndex}_${intrantIndex}`"
                                                class="mb-2 block text-sm font-semibold text-slate-700"
                                            >
                                                Observations
                                            </label>

                                            <textarea
                                                :name="`cultures[${cultureIndex}][intrants][${intrantIndex}][observations]`"
                                                :id="`intrantObservations_${cultureIndex}_${intrantIndex}`"
                                                x-model="intrant.observations"
                                                rows="2"
                                                placeholder="Observation éventuelle..."
                                                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                                            ></textarea>

                                        </div>

                                    </div>

                                </template>

                            </div>


                            {{-- AUCUN INTRANT --}}
                            <div
                                x-show="culture.intrants.length === 0"
                                class="border-t border-dashed border-slate-200 px-1 py-6 text-center"
                            >
                                <p class="text-sm font-medium text-slate-500">
                                    Aucun intrant déclaré.
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    Cliquez sur « Ajouter un intrant » si des intrants ont été utilisés.
                                </p>
                            </div>

                        </div>

                    </div>

                </div>

            </template>

        </div>
    </section>


    {{-- ============================================================
         STATUT
    ============================================================= --}}
    @if($isEdit)
        <input
            type="hidden"
            name="statut"
            value="{{ old('statut', $parcelle->statut ?? 'brouillon') }}"
        >
    @endif


    {{-- ============================================================
         ERREUR ALPINE / GPS
    ============================================================= --}}
    <div
        x-show="gpsErreur"
        x-cloak
        class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
    >
        <span x-text="gpsErreur"></span>
    </div>

</div>


@push('scripts')
<script>
function parcelleForm() {
    return {

        /* ============================================================
         * DONNÉES
         * ============================================================
         */

        cultures: @js($culturesForm),

        intrantsReferentiel: @js($intrantsReferentiel),

        pointsGPS: @js($pointsGpsForm),

        latitude: @js(
            old(
                'latitude',
                $parcelle->latitude ?? ''
            )
        ),

        longitude: @js(
            old(
                'longitude',
                $parcelle->longitude ?? ''
            )
        ),

        precisionGPS: @js(
            old(
                'precisionGPS',
                $parcelle->precisionGPS ?? ''
            )
        ),

        superficie: @js(
            old(
                'superficie',
                $parcelle->superficie ?? ''
            )
        ),

        gpsActif: false,

        gpsChargement: false,

        gpsErreur: '',

        watchId: null,


        /* ============================================================
         * INITIALISATION
         * ============================================================
         */

        initialiser() {

            this.mettreAJourJsonGPS();

            this.calculerSuperficie();

            /*
             * Si aucun point GPS n'existe mais que la parcelle
             * possède déjà ses coordonnées centrales, on les conserve.
             */
        },


        /* ============================================================
         * CULTURES
         * ============================================================
         */

        nouvelleCulture() {

            return {
                culture_id: '',
                modeCulture: 'associee',
                superficieCultivee: '',
                dateSemis: '',
                dateRecoltePrevue: '',
                dateRecolteEffective: '',
                irriguee: false,
                etatCulture: 'semis',
                observations: '',
                intrants: [],
            };

        },


        ajouterCulture() {

            this.cultures.push(
                this.nouvelleCulture()
            );

        },


        supprimerCulture(index) {

            if (this.cultures.length <= 1) {
                return;
            }

            this.cultures.splice(index, 1);

            /*
             * La première culture reste la culture principale
             * si aucune autre culture principale n'est présente.
             */
            const principaleExiste = this.cultures.some(
                culture => culture.modeCulture === 'principale'
            );

            if (!principaleExiste && this.cultures.length > 0) {
                this.cultures[0].modeCulture = 'principale';
            }

        },


        /* ============================================================
         * INTRANTS
         * ============================================================
         */

        nouvelIntrant() {

            return {
                intrant_id: '',
                quantite: '',
                nombreApplications: 1,
                dateApplication: '',
                observations: '',
            };

        },


        ajouterIntrant(cultureIndex) {

            if (!this.cultures[cultureIndex]) {
                return;
            }

            this.cultures[cultureIndex].intrants.push(
                this.nouvelIntrant()
            );

        },


        supprimerIntrant(cultureIndex, intrantIndex) {

            if (!this.cultures[cultureIndex]) {
                return;
            }

            this.cultures[cultureIndex].intrants.splice(
                intrantIndex,
                1
            );

        },


        trouverUnite(intrantId) {

            if (!intrantId) {
                return '';
            }

            const intrant = this.intrantsReferentiel.find(
                item => String(item.id) === String(intrantId)
            );

            return intrant
                ? intrant.unite
                : '';

        },


        /* ============================================================
         * GPS
         * ============================================================
         */

        toggleGPS() {

            if (this.gpsActif) {
                this.arreterGPS();
            } else {
                this.demarrerGPS();
            }

        },


        demarrerGPS() {

            this.gpsErreur = '';

            if (!navigator.geolocation) {

                this.gpsErreur =
                    'La géolocalisation n’est pas prise en charge par ce navigateur.';

                return;
            }

            this.gpsChargement = true;

            navigator.geolocation.getCurrentPosition(
                position => {

                    this.gpsChargement = false;

                    this.ajouterPointGPS(position);

                    this.watchId =
                        navigator.geolocation.watchPosition(
                            position => {
                                this.ajouterPointGPS(position);
                            },
                            erreur => {
                                this.gestionErreurGPS(erreur);
                            },
                            {
                                enableHighAccuracy: true,
                                maximumAge: 0,
                                timeout: 15000
                            }
                        );

                    this.gpsActif = true;

                },
                erreur => {

                    this.gpsChargement = false;

                    this.gestionErreurGPS(erreur);

                },
                {
                    enableHighAccuracy: true,
                    maximumAge: 0,
                    timeout: 15000
                }
            );

        },


        arreterGPS() {

            if (
                this.watchId !== null &&
                navigator.geolocation
            ) {

                navigator.geolocation.clearWatch(
                    this.watchId
                );

            }

            this.watchId = null;

            this.gpsActif = false;

        },


        ajouterPointGPS(position) {

            const latitude =
                Number(position.coords.latitude);

            const longitude =
                Number(position.coords.longitude);

            const precision =
                Number(position.coords.accuracy);

            if (
                !Number.isFinite(latitude) ||
                !Number.isFinite(longitude)
            ) {
                return;
            }

            /*
             * Première position :
             * elle devient la position centrale de la parcelle.
             */
            this.latitude =
                latitude.toFixed(7);

            this.longitude =
                longitude.toFixed(7);

            if (Number.isFinite(precision)) {
                this.precisionGPS =
                    precision.toFixed(2);
            }

            /*
             * Éviter d'ajouter plusieurs fois exactement
             * le même point.
             */
            const dernier =
                this.pointsGPS[
                    this.pointsGPS.length - 1
                ];

            if (
                dernier &&
                Math.abs(
                    Number(dernier.latitude) - latitude
                ) < 0.000001 &&
                Math.abs(
                    Number(dernier.longitude) - longitude
                ) < 0.000001
            ) {
                return;
            }

            this.pointsGPS.push({
                latitude: latitude,
                longitude: longitude,
                ordre: this.pointsGPS.length + 1,
            });

            this.mettreAJourOrdres();

            this.mettreAJourJsonGPS();

            this.calculerSuperficie();

        },


        mettreAJourOrdres() {

            this.pointsGPS =
                this.pointsGPS.map(
                    (point, index) => ({
                        ...point,
                        ordre: index + 1,
                    })
                );

        },


        mettreAJourJsonGPS() {

            const points =
                this.pointsGPS.map(
                    (point, index) => ({
                        latitude:
                            Number(point.latitude),

                        longitude:
                            Number(point.longitude),

                        ordre:
                            index + 1,
                    })
                );

            this.pointsGpsJson =
                JSON.stringify(points);

        },


        pointsGpsJson: '[]',


        /* ============================================================
         * CALCUL SUPERFICIE
         * ============================================================
         */

        calculerSuperficie() {

            if (this.pointsGPS.length < 3) {
                return;
            }

            /*
             * Conversion latitude/longitude
             * vers une approximation plane locale.
             */
            const R = 6378137;

            const lat0 =
                Number(this.pointsGPS[0].latitude)
                * Math.PI / 180;

            const points =
                this.pointsGPS.map(point => {

                    const lat =
                        Number(point.latitude)
                        * Math.PI / 180;

                    const lon =
                        Number(point.longitude)
                        * Math.PI / 180;

                    return {
                        x:
                            R *
                            lon *
                            Math.cos(lat0),

                        y:
                            R * lat,
                    };

                });


            let aire = 0;

            for (
                let i = 0;
                i < points.length;
                i++
            ) {

                const j =
                    (i + 1) % points.length;

                aire +=
                    points[i].x *
                    points[j].y;

                aire -=
                    points[j].x *
                    points[i].y;

            }

            aire =
                Math.abs(aire) / 2;


            /*
             * m² → hectares
             */
            const hectares =
                aire / 10000;


            if (
                Number.isFinite(hectares) &&
                hectares > 0
            ) {

                this.superficie =
                    hectares.toFixed(2);

            }

        },


        /* ============================================================
         * ERREURS GPS
         * ============================================================
         */

        gestionErreurGPS(erreur) {

            this.gpsChargement = false;

            switch (erreur.code) {

                case 1:
                    this.gpsErreur =
                        'L’accès à la position a été refusé. Autorisez la géolocalisation dans votre navigateur.';
                    break;

                case 2:
                    this.gpsErreur =
                        'La position GPS n’est pas disponible actuellement.';
                    break;

                case 3:
                    this.gpsErreur =
                        'Le délai de récupération de la position GPS est dépassé.';
                    break;

                default:
                    this.gpsErreur =
                        'Une erreur est survenue lors de la récupération de la position GPS.';
            }

        },


        soumettreFormulaire(event) {

            /*
            * Recalculer une dernière fois la superficie
            * avant l'envoi au serveur.
            */
            this.mettreAJourOrdres();
            this.mettreAJourJsonGPS();
            this.calculerSuperficie();

            /*
            * Vérification côté navigateur.
            */
            if (
                this.pointsGPS.length < 3 ||
                !Number.isFinite(Number(this.superficie)) ||
                Number(this.superficie) <= 0
            ) {
                event.preventDefault();

                this.gpsErreur =
                    'Veuillez enregistrer au moins trois points GPS afin de calculer la superficie de la parcelle.';

                return;
            }
        },

    };
}
</script>
@endpush