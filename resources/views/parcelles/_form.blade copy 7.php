@php
    use Carbon\Carbon;

    $isEdit = isset($parcelle) && $parcelle;

    /*
    |--------------------------------------------------------------------------
    | POINTS GPS
    |--------------------------------------------------------------------------
    */

    $pointsGpsForm = old('points_gps', []);

    if (is_string($pointsGpsForm)) {
        $pointsGpsForm = json_decode($pointsGpsForm, true) ?? [];
    }

    if (
        $isEdit &&
        empty($pointsGpsForm) &&
        method_exists($parcelle, 'pointsGPS')
    ) {
        $pointsGpsForm = $parcelle->pointsGPS()
            ->orderBy('ordre')
            ->get()
            ->map(function ($point) {
                return [
                    'latitude'     => $point->latitude,
                    'longitude'    => $point->longitude,
                    'precisionGPS' => $point->precisionGPS,
                    'altitude'     => $point->altitude,
                    'ordre'        => $point->ordre,
                ];
            })
            ->values()
            ->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | CULTURES
    |--------------------------------------------------------------------------
    */

    $culturesForm = old('cultures', null);

    if (
        $culturesForm === null &&
        $isEdit &&
        isset($parcelle->cultures)
    ) {
        $culturesForm = $parcelle->cultures
            ->map(function ($culture) {
                return [
                    'culture_id'            => $culture->culture_id,
                    'modeCulture'           => $culture->modeCulture ?? 'principale',
                    'superficieCultivee'    => $culture->superficieCultivee,
                    'dateSemis'             => $culture->dateSemis,
                    'dateRecoltePrevue'     => $culture->dateRecoltePrevue,
                    'dateRecolteEffective'  => $culture->dateRecolteEffective,
                    'etatCulture'           => $culture->etatCulture ?? 'semis',
                    'irriguee'              => (bool) $culture->irriguee,
                    'observations'          => $culture->observations,
                    'intrants'              => isset($culture->intrants)
                        ? $culture->intrants
                            ->map(function ($intrant) {
                                return [
                                    'intrant_id'         => $intrant->intrant_id,
                                    'quantite'           => $intrant->quantite,
                                    'nombreApplications' => $intrant->nombreApplications ?? 1,
                                    'dateApplication'    => $intrant->dateApplication,
                                    'observations'       => $intrant->observations,
                                ];
                            })
                            ->values()
                            ->toArray()
                        : [],
                ];
            })
            ->values()
            ->toArray();
    }

    if (!is_array($culturesForm)) {
        $culturesForm = [];
    }

    if (empty($culturesForm)) {
        $culturesForm = [
            [
                'culture_id'           => '',
                'modeCulture'          => 'principale',
                'superficieCultivee'   => '',
                'dateSemis'            => '',
                'dateRecoltePrevue'    => '',
                'dateRecolteEffective' => '',
                'etatCulture'          => 'semis',
                'irriguee'             => false,
                'observations'         => '',
                'intrants'             => [],
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RÉFÉRENTIELS
    |--------------------------------------------------------------------------
    */

    $culturesReferentiel = $cultures ?? [];
    $intrantsReferentiel = $intrantsReferentiel ?? [];

    /*
    |--------------------------------------------------------------------------
    | AUTRES VALEURS
    |--------------------------------------------------------------------------
    */

    $numeroParcelleForm = old(
        'numeroParcelle',
        $isEdit ? ($parcelle->numeroParcelle ?? '') : ($numeroParcelle ?? '')
    );

    $superficieForm = old(
        'superficie',
        $isEdit ? ($parcelle->superficie ?? '') : ''
    );

    $statutParcelleForm = old(
        'statutParcelle',
        $isEdit ? ($parcelle->statutParcelle ?? 'exploitee') : 'exploitee'
    );

    $typeSolForm = old(
        'typeSol',
        $isEdit ? ($parcelle->typeSol ?? '') : ''
    );

    $modeFaireValoirForm = old(
        'modeFaireValoir',
        $isEdit ? ($parcelle->modeFaireValoir ?? '') : ''
    );

    $modeIrrigationForm = old(
        'modeIrrigation',
        $isEdit ? ($parcelle->modeIrrigation ?? 'pluvial') : 'pluvial'
    );

    $presenceArbresForm = old(
        'presenceArbres',
        $isEdit ? (int) ($parcelle->presenceArbres ?? 0) : 0
    );

    $observationsForm = old(
        'observations',
        $isEdit ? ($parcelle->observations ?? '') : ''
    );
@endphp


<form
    method="POST"
    action="{{ $isEdit
        ? route('agent.parcelles.update', $parcelle)
        : route('agent.parcelles.store', $exploitant) }}"
    x-data="parcelleForm()"
    x-init="initialiser()"
    @submit="soumettreFormulaire($event)"
    class="space-y-8"
>

    @csrf

    @if($isEdit)
        @method('PUT')
    @endif


    {{-- =========================================================
         ERREURS DE VALIDATION
    ========================================================== --}}

    @if($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 p-4">
            <div class="flex items-start gap-3">
                <svg
                    class="mt-0.5 h-5 w-5 shrink-0 text-red-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 8v4m0 4h.01M10.29 3.86l-7.82 13.5A2 2 0 004.2 20h15.6a2 2 0 001.73-2.64l-7.82-13.5a2 2 0 00-3.42 0z"
                    />
                </svg>

                <div class="flex-1">
                    <h3 class="font-semibold text-red-800">
                        Vérifiez les informations saisies.
                    </h3>
                    <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif


    {{-- =========================================================
         EN-TÊTE / IDENTIFICATION
    ========================================================== --}}

    <div class="space-y-4">
        <div class="flex items-center gap-3 border-b border-gray-200 pb-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#e5f2ee] text-[#006a4f]">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M5 7v12h14V7M8 7V4h8v3M9 11h6M9 15h4"/>
                </svg>
            </div>
            <div>
                <h2 class="text-base font-semibold text-gray-900">
                    Identification de la parcelle
                </h2>
                <p class="text-sm text-gray-500">
                    Les informations d'identification sont gérées automatiquement par le système.
                </p>
            </div>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">
                    Superficie
                </label>

                <div class="relative">
                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="superficie"
                        x-model="superficie"
                        readonly
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 pr-14 text-sm text-gray-700 outline-none"
                    />

                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-medium text-gray-500">
                        ha
                    </span>
                </div>

                <p class="mt-1.5 text-xs text-gray-500">
                    Calculée automatiquement à partir des points GPS.
                </p>

                @error('superficie')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>


    {{-- =========================================================
         LOCALISATION GPS
    ========================================================== --}}

    <div class="space-y-4 pt-6 border-t border-gray-200">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between border-b border-gray-200 pb-3">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#e5f2ee] text-[#006a4f]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21s7-4.35 7-10a7 7 0 10-14 0c0 5.65 7 10 7 10z"/>
                        <circle cx="12" cy="11" r="2.5"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-gray-900">
                        Localisation GPS
                    </h2>
                    <p class="text-sm text-gray-500">
                        Enregistrez la position de la parcelle.
                    </p>
                </div>
            </div>

            <div
                x-show="gpsMessage"
                x-cloak
                class="rounded-lg bg-emerald-50 px-3 py-2 text-xs font-medium text-emerald-700"
                x-text="gpsMessage"
            ></div>
        </div>

        <div class="space-y-5">
            <div
                x-show="gpsErreur"
                x-cloak
                class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
                x-text="gpsErreur"
            ></div>

            <div class="grid gap-4 sm:grid-cols-3">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Latitude</label>
                    <input type="text" x-model="latitude" readonly class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700"/>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Longitude</label>
                    <input type="text" x-model="longitude" readonly class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700"/>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Précision</label>
                    <div class="relative">
                        <input type="text" x-model="precisionGPS" readonly class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 pr-12 text-sm text-gray-700"/>
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-gray-500">m</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <button
                    type="button"
                    @click="obtenirPosition()"
                    :disabled="gpsRecherche"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#006a4f] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#00583f] disabled:cursor-not-allowed disabled:opacity-60"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.48 8.48l2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.48-8.48l2.83-2.83"/>
                        <circle cx="12" cy="12" r="4"/>
                    </svg>
                    <span x-text="gpsRecherche ? 'Recherche...' : 'Obtenir ma position'"></span>
                </button>

                <button
                    type="button"
                    @click="marquerPoint()"
                    :disabled="!positionDisponible"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-[#006a4f] bg-white px-4 py-3 text-sm font-semibold text-[#006a4f] transition hover:bg-[#e5f2ee] disabled:cursor-not-allowed disabled:opacity-50"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21s7-4.35 7-10a7 7 0 10-14 0c0 5.65 7 10 7 10z"/>
                        <circle cx="12" cy="11" r="2"/>
                    </svg>
                    Marquer le point
                </button>

                <button
                    type="button"
                    @click="chargerPointsGPSTest()"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-amber-300 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-700 transition hover:bg-amber-100"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.5-2A7.5 7.5 0 1112 4.5"/>
                    </svg>
                    Utiliser des points GPS de test
                </button>

                <button
                    type="button"
                    @click="effacerPoints()"
                    :disabled="pointsGPS.length === 0"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4h6v3m-8 0h10"/>
                    </svg>
                    Effacer les points
                </button>
            </div>

            <div
                x-show="pointsGPS.length > 0"
                x-cloak
                class="overflow-hidden rounded-xl border border-gray-200"
            >
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Point</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Latitude</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Longitude</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Précision</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-600">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            <template x-for="(point, index) in pointsGPS" :key="index">
                                <tr>
                                    <td class="px-4 py-3 font-medium text-gray-900" x-text="index + 1"></td>
                                    <td class="px-4 py-3 text-gray-600" x-text="point.latitude"></td>
                                    <td class="px-4 py-3 text-gray-600" x-text="point.longitude"></td>
                                    <td class="px-4 py-3 text-gray-600">
                                        <span x-text="point.precisionGPS ?? '-'"></span>
                                        <span x-show="point.precisionGPS" class="text-xs">m</span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <button
                                            type="button"
                                            @click="supprimerPoint(index)"
                                            class="inline-flex items-center justify-center rounded-lg p-2 text-red-600 transition hover:bg-red-50"
                                            title="Supprimer ce point"
                                        >
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6V7m-2-3h4M9 4h6"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <div
                x-show="pointsGPS.length === 0"
                x-cloak
                class="rounded-xl border border-dashed border-gray-300 bg-gray-50 px-4 py-6 text-center"
            >
                <p class="text-sm text-gray-500">Aucun point GPS enregistré.</p>
            </div>

            <input type="hidden" name="points_gps" x-ref="pointsGpsInput" value=""/>
        </div>
    </div>


    {{-- =========================================================
         CARACTÉRISTIQUES DE LA PARCELLE
    ========================================================== --}}

    <div class="space-y-4 pt-6 border-t border-gray-200">
        <div class="flex items-center gap-3 border-b border-gray-200 pb-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#e5f2ee] text-[#006a4f]">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </div>
            <div>
                <h2 class="text-base font-semibold text-gray-900">
                    Caractéristiques de la parcelle
                </h2>
                <p class="text-sm text-gray-500">
                    Renseignez les caractéristiques observées sur la parcelle.
                </p>
            </div>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label for="statutParcelle" class="mb-2 block text-sm font-medium text-gray-700">
                    Statut de la parcelle <span class="text-red-500">*</span>
                </label>
                <select
                    id="statutParcelle"
                    name="statutParcelle"
                    required
                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#006a4f] focus:ring-2 focus:ring-[#006a4f]/10"
                >
                    <option value="">Sélectionner</option>
                    <option value="exploitee" @selected($statutParcelleForm === 'exploitee')>Exploitée</option>
                    <option value="jachere" @selected($statutParcelleForm === 'jachere')>Jachère</option>
                    <option value="non_exploitee" @selected($statutParcelleForm === 'non_exploitee')>Non exploitée</option>
                </select>
                @error('statutParcelle')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="typeSol" class="mb-2 block text-sm font-medium text-gray-700">Type de sol</label>
                <select
                    id="typeSol"
                    name="typeSol"
                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#006a4f] focus:ring-2 focus:ring-[#006a4f]/10"
                >
                    <option value="">Sélectionner</option>
                    <option value="argileux" @selected($typeSolForm === 'argileux')>Argileux</option>
                    <option value="sableux" @selected($typeSolForm === 'sableux')>Sableux</option>
                    <option value="limoneux" @selected($typeSolForm === 'limoneux')>Limoneux</option>
                    <option value="argilo_sableux" @selected($typeSolForm === 'argilo_sableux')>Argilo-sableux</option>
                    <option value="autre" @selected($typeSolForm === 'autre')>Autre</option>
                </select>
                @error('typeSol')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="modeFaireValoir" class="mb-2 block text-sm font-medium text-gray-700">
                    Mode de faire-valoir <span class="text-red-500">*</span>
                </label>
                <select
                    id="modeFaireValoir"
                    name="modeFaireValoir"
                    required
                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#006a4f] focus:ring-2 focus:ring-[#006a4f]/10"
                >
                    <option value="">Sélectionner</option>
                    <option value="proprietaire" @selected($modeFaireValoirForm === 'proprietaire')>Propriétaire</option>
                    <option value="location" @selected($modeFaireValoirForm === 'location')>Location</option>
                    <option value="pret" @selected($modeFaireValoirForm === 'pret')>Prêt</option>
                    <option value="metayage" @selected($modeFaireValoirForm === 'metayage')>Métayage</option>
                    <option value="autre" @selected($modeFaireValoirForm === 'autre')>Autre</option>
                </select>
                @error('modeFaireValoir')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="modeIrrigation" class="mb-2 block text-sm font-medium text-gray-700">
                    Mode d'irrigation <span class="text-red-500">*</span>
                </label>
                <select
                    id="modeIrrigation"
                    name="modeIrrigation"
                    required
                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#006a4f] focus:ring-2 focus:ring-[#006a4f]/10"
                >
                    <option value="pluvial" @selected($modeIrrigationForm === 'pluvial')>Pluvial</option>
                    <option value="gravitaire" @selected($modeIrrigationForm === 'gravitaire')>Gravitaire</option>
                    <option value="pompage" @selected($modeIrrigationForm === 'pompage')>Pompage</option>
                    <option value="aucun" @selected($modeIrrigationForm === 'aucun')>Aucun</option>
                    <option value="autre" @selected($modeIrrigationForm === 'autre')>Autre</option>
                </select>
                @error('modeIrrigation')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <input type="hidden" name="presenceArbres" value="0"/>
                <label class="flex cursor-pointer items-center gap-3">
                    <input
                        type="checkbox"
                        name="presenceArbres"
                        value="1"
                        @checked((int) $presenceArbresForm === 1)
                        class="h-5 w-5 rounded border-gray-300 text-[#006a4f] focus:ring-[#006a4f]"
                    />
                    <span class="text-sm font-medium text-gray-700">
                        Présence d'arbres sur la parcelle
                    </span>
                </label>
            </div>

            <div class="md:col-span-2">
                <label for="observations" class="mb-2 block text-sm font-medium text-gray-700">Observations</label>
                <textarea
                    id="observations"
                    name="observations"
                    rows="4"
                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#006a4f] focus:ring-2 focus:ring-[#006a4f]/10"
                    placeholder="Observations éventuelles..."
                >{{ $observationsForm }}</textarea>
                @error('observations')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>


    {{-- =========================================================
         CULTURES
    ========================================================== --}}

    <div class="space-y-4 pt-6 border-t border-gray-200">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-gray-200 pb-3">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#e5f2ee] text-[#006a4f]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21V9m0 0c-3.5 0-6-2.5-6-6 3.5 0 6 2.5 6 6zm0 4V9m0 0c3.5 0 6-2.5 6-6-3.5 0-6 2.5-6 6z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-gray-900">
                        Cultures
                    </h2>
                    <p class="text-sm text-gray-500">
                        Ajoutez les cultures présentes sur cette parcelle.
                    </p>
                </div>
            </div>

            <button
                type="button"
                @click="ajouterCulture()"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#006a4f] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#00583f]"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14M5 12h14"/>
                </svg>
                Ajouter une culture
            </button>
        </div>

        <div class="space-y-6">
            <template x-for="(culture, cultureIndex) in cultures" :key="cultureIndex">
                <div class="space-y-5 border-b border-gray-200 pb-6 last:border-b-0">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <h3 class="font-semibold text-gray-900">
                                Culture <span x-text="cultureIndex + 1"></span>
                            </h3>
                            <p class="text-xs text-gray-500">
                                Informations relatives à cette culture.
                            </p>
                        </div>

                        <button
                            type="button"
                            @click="supprimerCulture(cultureIndex)"
                            x-show="cultures.length > 1"
                            class="inline-flex items-center justify-center rounded-lg p-2 text-red-600 transition hover:bg-red-50"
                            title="Supprimer la culture"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6V7m-2-3h4M9 4h6"/>
                            </svg>
                        </button>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Culture <span class="text-red-500">*</span>
                            </label>
                            
                            {{-- Champ de recherche pour filtrer les cultures dynamiquement --}}
                            <input
                                type="text"
                                x-model="culture.searchCulture"
                                placeholder="🔍 Filtrer une culture..."
                                class="mb-2 w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-700 outline-none focus:border-[#006a4f]"
                            />

                            <select
                                :name="`cultures[${cultureIndex}][culture_id]`"
                                x-model="culture.culture_id"
                                required
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#006a4f] focus:ring-2 focus:ring-[#006a4f]/10"
                            >
                                <option value="">Sélectionner une culture</option>
                                <template x-for="item in filtrerCultures(culture.searchCulture)" :key="item.idCulture">
                                    <option :value="item.idCulture" x-text="item.nomCulture"></option>
                                </template>
                            </select>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Mode de culture <span class="text-red-500">*</span>
                            </label>
                            <select
                                :name="`cultures[${cultureIndex}][modeCulture]`"
                                x-model="culture.modeCulture"
                                required
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#006a4f] focus:ring-2 focus:ring-[#006a4f]/10"
                            >
                                <option value="principale">Principale</option>
                                <option value="associee">Associée</option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">Superficie cultivée</label>
                            <div class="relative">
                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    :name="`cultures[${cultureIndex}][superficieCultivee]`"
                                    x-model="culture.superficieCultivee"
                                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 pr-14 text-sm text-gray-700 outline-none transition focus:border-[#006a4f] focus:ring-2 focus:ring-[#006a4f]/10"
                                />
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-gray-500">ha</span>
                            </div>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                État de la culture <span class="text-red-500">*</span>
                            </label>
                            <select
                                :name="`cultures[${cultureIndex}][etatCulture]`"
                                x-model="culture.etatCulture"
                                required
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#006a4f] focus:ring-2 focus:ring-[#006a4f]/10"
                            >
                                <option value="semis">Semis</option>
                                <option value="croissance">Croissance</option>
                                <option value="floraison">Floraison</option>
                                <option value="recolte">Récolte</option>
                                <option value="terminee">Terminée</option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">Date de semis</label>
                            <input
                                type="date"
                                :name="`cultures[${cultureIndex}][dateSemis]`"
                                x-model="culture.dateSemis"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#006a4f] focus:ring-2 focus:ring-[#006a4f]/10"
                            />
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">Date de récolte prévue</label>
                            <input
                                type="date"
                                :name="`cultures[${cultureIndex}][dateRecoltePrevue]`"
                                x-model="culture.dateRecoltePrevue"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#006a4f] focus:ring-2 focus:ring-[#006a4f]/10"
                            />
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">Date de récolte effective</label>
                            <input
                                type="date"
                                :name="`cultures[${cultureIndex}][dateRecolteEffective]`"
                                x-model="culture.dateRecolteEffective"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#006a4f] focus:ring-2 focus:ring-[#006a4f]/10"
                            />
                        </div>

                        <div class="flex items-center pt-8">
                            <label class="flex cursor-pointer items-center gap-3">
                                <input type="hidden" :name="`cultures[${cultureIndex}][irriguee]`" value="0"/>
                                <input
                                    type="checkbox"
                                    :name="`cultures[${cultureIndex}][irriguee]`"
                                    value="1"
                                    x-model="culture.irriguee"
                                    class="h-5 w-5 rounded border-gray-300 text-[#006a4f] focus:ring-[#006a4f]"
                                />
                                <span class="text-sm font-medium text-gray-700">Culture irriguée</span>
                            </label>
                        </div>

                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm font-medium text-gray-700">Observations</label>
                            <textarea
                                rows="3"
                                :name="`cultures[${cultureIndex}][observations]`"
                                x-model="culture.observations"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#006a4f] focus:ring-2 focus:ring-[#006a4f]/10"
                                placeholder="Observations sur cette culture..."
                            ></textarea>
                        </div>
                    </div>


                    {{-- INTRANTS --}}
                    <div class="mt-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">Intrants utilisés</h4>
                                <p class="text-xs text-gray-500">Ajoutez les intrants associés à cette culture.</p>
                            </div>

                            <button
                                type="button"
                                @click="ajouterIntrant(cultureIndex)"
                                class="inline-flex items-center justify-center gap-2 rounded-lg border border-[#006a4f] px-3 py-2 text-xs font-semibold text-[#006a4f] transition hover:bg-[#e5f2ee]"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14M5 12h14"/>
                                </svg>
                                Ajouter un intrant
                            </button>
                        </div>

                        <div class="space-y-4">
                            <template x-for="(intrant, intrantIndex) in culture.intrants" :key="intrantIndex">
                                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-xs">
                                    <div class="mb-3 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-gray-800">
                                            Intrant <span x-text="intrantIndex + 1"></span>
                                        </span>
                                        <button
                                            type="button"
                                            @click="supprimerIntrant(cultureIndex, intrantIndex)"
                                            class="rounded-lg p-2 text-red-600 transition hover:bg-red-50"
                                            title="Supprimer l'intrant"
                                        >
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6V7m-2-3h4M9 4h6"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="grid gap-4 md:grid-cols-2">
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-gray-700">Intrant</label>
                                            
                                            {{-- Champ de recherche pour filtrer les intrants dynamiquement --}}
                                            <input
                                                type="text"
                                                x-model="intrant.searchIntrant"
                                                placeholder="🔍 Filtrer un intrant..."
                                                class="mb-2 w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-700 outline-none focus:border-[#006a4f]"
                                            />

                                            <select
                                                :name="`cultures[${cultureIndex}][intrants][${intrantIndex}][intrant_id]`"
                                                x-model="intrant.intrant_id"
                                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#006a4f] focus:ring-2 focus:ring-[#006a4f]/10"
                                            >
                                                <option value="">Sélectionner un intrant</option>
                                                <template x-for="item in filtrerIntrants(intrant.searchIntrant)" :key="item.idIntrant">
                                                    <option :value="item.idIntrant" x-text="item.nom"></option>
                                                </template>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-gray-700">Quantité</label>
                                            <input
                                                type="number"
                                                step="0.01"
                                                min="0"
                                                :name="`cultures[${cultureIndex}][intrants][${intrantIndex}][quantite]`"
                                                x-model="intrant.quantite"
                                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#006a4f] focus:ring-2 focus:ring-[#006a4f]/10"
                                            />
                                        </div>

                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-gray-700">Nombre d'applications</label>
                                            <input
                                                type="number"
                                                min="1"
                                                :name="`cultures[${cultureIndex}][intrants][${intrantIndex}][nombreApplications]`"
                                                x-model="intrant.nombreApplications"
                                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#006a4f] focus:ring-2 focus:ring-[#006a4f]/10"
                                            />
                                        </div>

                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-gray-700">Date d'application</label>
                                            <input
                                                type="date"
                                                :name="`cultures[${cultureIndex}][intrants][${intrantIndex}][dateApplication]`"
                                                x-model="intrant.dateApplication"
                                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#006a4f] focus:ring-2 focus:ring-[#006a4f]/10"
                                            />
                                        </div>

                                        <div class="md:col-span-2">
                                            <label class="mb-2 block text-sm font-medium text-gray-700">Observations</label>
                                            <textarea
                                                rows="2"
                                                :name="`cultures[${cultureIndex}][intrants][${intrantIndex}][observations]`"
                                                x-model="intrant.observations"
                                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#006a4f] focus:ring-2 focus:ring-[#006a4f]/10"
                                                placeholder="Observations sur l'intrant..."
                                            ></textarea>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <div
                                x-show="culture.intrants.length === 0"
                                x-cloak
                                class="rounded-xl border border-dashed border-gray-300 bg-gray-50 px-4 py-5 text-center"
                            >
                                <p class="text-xs text-gray-500">Aucun intrant ajouté à cette culture.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>


    {{-- =========================================================
         ACTIONS
    ========================================================== --}}

    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end pt-6 border-t border-gray-200">
        <a
            href="{{ route('agent.parcelles.index', $exploitant) }}"
            class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
        >
            Annuler
        </a>

        <button
            type="submit"
            :disabled="envoiEnCours"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#006a4f] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#00583f] disabled:cursor-not-allowed disabled:opacity-60"
        >
            <svg
                x-show="!envoiEnCours"
                class="h-5 w-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>

            <svg
                x-show="envoiEnCours"
                x-cloak
                class="h-5 w-5 animate-spin"
                fill="none"
                viewBox="0 0 24 24"
            >
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>

            <span x-text="envoiEnCours ? 'Enregistrement...' : '{{ $isEdit ? 'Mettre à jour la parcelle' : 'Enregistrer la parcelle' }}'"></span>
        </button>
    </div>

</form>


@push('scripts')
<script>
(function () {
    window.parcelleForm = function () {
        return {
            gpsRecherche: false,
            gpsErreur: '',
            gpsMessage: '',
            latitude: '',
            longitude: '',
            precisionGPS: '',
            positionDisponible: false,
            pointsGPS: @json($pointsGpsForm),

            envoiEnCours: false,
            superficie: @json($superficieForm),
            cultures: @json($culturesForm),
            culturesReferentiel: @json($culturesReferentiel),
            intrantsReferentiel: @json($intrantsReferentiel),

            initialiser() {
                if (!Array.isArray(this.pointsGPS)) {
                    this.pointsGPS = [];
                }

                if (!Array.isArray(this.cultures)) {
                    this.cultures = [];
                }

                this.cultures = this.cultures.map(
                    culture => this.normaliserCulture(culture)
                );

                if (this.cultures.length === 0) {
                    this.cultures.push(this.nouvelleCulture());
                }

                this.mettreAJourOrdres();
                this.mettreAJourJsonGPS();

                if (this.pointsGPS.length > 0) {
                    const dernierPoint = this.pointsGPS[this.pointsGPS.length - 1];
                    this.latitude = dernierPoint.latitude ?? '';
                    this.longitude = dernierPoint.longitude ?? '';
                    this.precisionGPS = dernierPoint.precisionGPS ?? '';
                    this.positionDisponible = true;
                    this.gpsMessage = this.pointsGPS.length + ' point(s) GPS enregistré(s).';
                }
            },

            nouvelleCulture() {
                return {
                    searchCulture: '',
                    culture_id: '',
                    modeCulture: 'principale',
                    superficieCultivee: '',
                    dateSemis: '',
                    dateRecoltePrevue: '',
                    dateRecolteEffective: '',
                    etatCulture: 'semis',
                    irriguee: false,
                    observations: '',
                    intrants: []
                };
            },

            filtrerCultures(recherche) {
                if (!recherche) return this.culturesReferentiel;
                const term = recherche.toLowerCase();
                return this.culturesReferentiel.filter(c => 
                    c.nomCulture.toLowerCase().includes(term)
                );
            },

            filtrerIntrants(recherche) {
                if (!recherche) return this.intrantsReferentiel;
                const term = recherche.toLowerCase();
                return this.intrantsReferentiel.filter(i => 
                    i.nom.toLowerCase().includes(term)
                );
            },

            normaliserCulture(culture) {
                culture = culture || {};
                return {
                    searchCulture: '',
                    culture_id: culture.culture_id ?? '',
                    modeCulture: culture.modeCulture ?? 'principale',
                    superficieCultivee: culture.superficieCultivee ?? '',
                    dateSemis: culture.dateSemis ?? '',
                    dateRecoltePrevue: culture.dateRecoltePrevue ?? '',
                    dateRecolteEffective: culture.dateRecolteEffective ?? '',
                    etatCulture: culture.etatCulture ?? 'semis',
                    irriguee: Boolean(culture.irriguee),
                    observations: culture.observations ?? '',
                    intrants: Array.isArray(culture.intrants)
                        ? culture.intrants.map(intrant => this.normaliserIntrant(intrant))
                        : []
                };
            },

            normaliserIntrant(intrant) {
                intrant = intrant || {};
                return {
                    searchIntrant: '',
                    intrant_id: intrant.intrant_id ?? '',
                    quantite: intrant.quantite ?? '',
                    nombreApplications: intrant.nombreApplications ?? 1,
                    dateApplication: intrant.dateApplication ?? '',
                    observations: intrant.observations ?? ''
                };
            },

            ajouterCulture() {
                this.cultures.push(this.nouvelleCulture());
                this.mettreAJourOrdres();
            },

            supprimerCulture(index) {
                if (this.cultures.length <= 1) {
                    return;
                }
                this.cultures.splice(index, 1);
                this.mettreAJourOrdres();
            },

            ajouterIntrant(cultureIndex) {
                if (!this.cultures[cultureIndex]) {
                    return;
                }
                if (!Array.isArray(this.cultures[cultureIndex].intrants)) {
                    this.cultures[cultureIndex].intrants = [];
                }
                this.cultures[cultureIndex].intrants.push(this.normaliserIntrant({}));
            },

            supprimerIntrant(cultureIndex, intrantIndex) {
                if (!this.cultures[cultureIndex] || !Array.isArray(this.cultures[cultureIndex].intrants)) {
                    return;
                }
                this.cultures[cultureIndex].intrants.splice(intrantIndex, 1);
            },

            obtenirPosition() {
                this.gpsErreur = '';
                this.gpsMessage = '';

                if (!navigator.geolocation) {
                    this.gpsErreur = 'La géolocalisation n’est pas disponible sur cet appareil.';
                    return;
                }

                this.gpsRecherche = true;

                navigator.geolocation.getCurrentPosition(
                    position => {
                        this.majPosition(position);
                        this.gpsRecherche = false;
                        this.gpsMessage = 'Position GPS obtenue avec succès.';
                    },
                    erreur => {
                        this.gpsRecherche = false;
                        this.traiterErreurGPS(erreur);
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 15000,
                        maximumAge: 0
                    }
                );
            },

            majPosition(position) {
                if (!position || !position.coords) {
                    return;
                }

                this.latitude = Number(position.coords.latitude).toFixed(7);
                this.longitude = Number(position.coords.longitude).toFixed(7);
                this.precisionGPS = position.coords.accuracy !== null && position.coords.accuracy !== undefined
                    ? Number(position.coords.accuracy).toFixed(2)
                    : '';

                this.positionDisponible = true;
            },

            chargerPointsGPSTest() {
                const pointsTest = [
                    { latitude: 8.1234560, longitude: 0.1234560, precisionGPS: 5, altitude: null, ordre: 1 },
                    { latitude: 8.1238000, longitude: 0.1237000, precisionGPS: 5, altitude: null, ordre: 2 },
                    { latitude: 8.1236000, longitude: 0.1240000, precisionGPS: 5, altitude: null, ordre: 3 },
                    { latitude: 8.1233000, longitude: 0.1238000, precisionGPS: 5, altitude: null, ordre: 4 }
                ];

                this.pointsGPS = pointsTest;
                this.mettreAJourOrdres();

                const dernierPoint = this.pointsGPS[this.pointsGPS.length - 1];
                this.latitude = dernierPoint.latitude;
                this.longitude = dernierPoint.longitude;
                this.precisionGPS = dernierPoint.precisionGPS;

                this.positionDisponible = true;
                this.mettreAJourJsonGPS();
                this.gpsErreur = '';
                this.gpsMessage = this.pointsGPS.length + ' points GPS de test chargés.';
            },

            marquerPoint() {
                if (this.latitude === '' || this.longitude === '') {
                    this.gpsErreur = 'Veuillez d’abord obtenir votre position GPS.';
                    return;
                }

                const point = {
                    latitude: Number(this.latitude),
                    longitude: Number(this.longitude),
                    precisionGPS: this.precisionGPS !== '' ? Number(this.precisionGPS) : null,
                    altitude: null,
                    ordre: this.pointsGPS.length + 1
                };

                this.pointsGPS.push(point);
                this.mettreAJourOrdres();
                this.mettreAJourJsonGPS();
                this.gpsErreur = '';
                this.gpsMessage = 'Point GPS ' + this.pointsGPS.length + ' ajouté.';
            },

            supprimerPoint(index) {
                if (index < 0 || index >= this.pointsGPS.length) {
                    return;
                }

                this.pointsGPS.splice(index, 1);
                this.mettreAJourOrdres();
                this.mettreAJourJsonGPS();

                if (this.pointsGPS.length === 0) {
                    this.latitude = '';
                    this.longitude = '';
                    this.precisionGPS = '';
                    this.positionDisponible = false;
                    this.gpsMessage = '';
                } else {
                    const dernierPoint = this.pointsGPS[this.pointsGPS.length - 1];
                    this.latitude = dernierPoint.latitude ?? '';
                    this.longitude = dernierPoint.longitude ?? '';
                    this.precisionGPS = dernierPoint.precisionGPS ?? '';
                }
            },

            effacerPoints() {
                this.pointsGPS = [];
                this.latitude = '';
                this.longitude = '';
                this.precisionGPS = '';
                this.positionDisponible = false;
                this.gpsErreur = '';
                this.gpsMessage = '';
                this.mettreAJourJsonGPS();
            },

            mettreAJourOrdres() {
                this.pointsGPS = this.pointsGPS.map((point, index) => {
                    return {
                        ...point,
                        ordre: index + 1
                    };
                });
            },

            mettreAJourJsonGPS() {
                if (this.$refs.pointsGpsInput) {
                    this.$refs.pointsGpsInput.value = JSON.stringify(this.pointsGPS);
                }
            },

            traiterErreurGPS(erreur) {
                switch (erreur.code) {
                    case 1:
                        this.gpsErreur = 'L’autorisation d’accès à la position a été refusée.';
                        break;
                    case 2:
                        this.gpsErreur = 'La position GPS n’a pas pu être déterminée.';
                        break;
                    case 3:
                        this.gpsErreur = 'La recherche de la position GPS a expiré.';
                        break;
                    default:
                        this.gpsErreur = 'Une erreur est survenue lors de la géolocalisation.';
                        break;
                }
            },

            soumettreFormulaire(event) {
                this.mettreAJourOrdres();
                this.mettreAJourJsonGPS();
                this.envoiEnCours = true;
            }
        };
    };
})();
</script>
@endpush