<div class="space-y-6">

    {{-- ============================================================
        NUMÉRO PARCELLE AUTOMATIQUE
    ============================================================ --}}
    <div>

        <label
            for="numeroParcelle"
            class="mb-2 block text-sm font-semibold text-slate-700"
        >
            Numéro de la parcelle
        </label>

        <input
            type="text"
            name="numeroParcelle"
            id="numeroParcelle"
            value="{{ old('numeroParcelle', $numeroParcelle ?? ($parcelle->numeroParcelle ?? '')) }}"
            readonly
            class="w-full rounded-xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm font-semibold text-emerald-700 shadow-sm"
        >

        <p class="mt-1 text-xs text-slate-500">
            Ce numéro est généré automatiquement par SIRA-Mô.
        </p>

        @error('numeroParcelle')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>


    {{-- ============================================================
        SUPERFICIE / MESURE GPS
    ============================================================ --}}
    <div>

        <label
            for="superficie"
            class="mb-2 block text-sm font-semibold text-slate-700"
        >
            Superficie de la parcelle
        </label>

        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <p class="text-sm font-semibold text-slate-800">
                        Mesure automatique par GPS
                    </p>

                    <p class="mt-1 text-xs leading-5 text-slate-600">
                        Faites le tour de la parcelle avec le téléphone ou la tablette.
                        L'application enregistrera automatiquement les points GPS.
                    </p>

                </div>

                <button
                    type="button"
                    id="btnDemarrerGPS"
                    class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-[#006a4f] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#00583f]"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0-12v3m0 0 2 2m-2-2-2 2"
                        />
                    </svg>

                    <span id="texteBoutonGPS">
                        Démarrer la mesure
                    </span>

                </button>

            </div>


            {{-- ====================================================
                INFORMATIONS GPS
            ===================================================== --}}
            <div
                id="gpsInformations"
                class="mt-4 hidden rounded-xl border border-slate-200 bg-white p-4"
            >

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">

                    <div>
                        <div class="text-xs font-medium text-slate-500">
                            Points enregistrés
                        </div>

                        <div
                            id="nombrePointsGPS"
                            class="mt-1 text-lg font-bold text-slate-800"
                        >
                            0
                        </div>
                    </div>


                    <div>
                        <div class="text-xs font-medium text-slate-500">
                            Précision GPS
                        </div>

                        <div
                            id="precisionGPS"
                            class="mt-1 text-lg font-bold text-slate-800"
                        >
                            —
                        </div>
                    </div>


                    <div>
                        <div class="text-xs font-medium text-slate-500">
                            Superficie calculée
                        </div>

                        <div class="mt-1">

                            <span
                                id="superficieAffichee"
                                class="text-lg font-bold text-emerald-700"
                            >
                                —
                            </span>

                            <span class="text-sm text-slate-500">
                                ha
                            </span>

                        </div>
                    </div>

                </div>


                <p
                    id="messageGPS"
                    class="mt-4 text-xs leading-5 text-slate-600"
                >
                    En attente du GPS...
                </p>

            </div>

        </div>


        {{-- ====================================================
            SUPERFICIE CALCULÉE
        ===================================================== --}}
        <input
            type="number"
            name="superficie"
            id="superficie"
            step="0.01"
            min="0"
            value="{{ old('superficie', $parcelle->superficie ?? '') }}"
            readonly
            class="mt-4 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm"
        >

        <p class="mt-1 text-xs text-slate-500">
            La superficie est calculée automatiquement à partir du contour GPS.
        </p>


        {{-- ====================================================
            POINTS GPS
        ===================================================== --}}
        <input
            type="hidden"
            name="points_gps"
            id="points_gps"
            value="{{ old('points_gps') }}"
        >


        @error('superficie')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

        @error('points_gps')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>


    {{-- ============================================================
        TYPE DE SOL
    ============================================================ --}}
    <div>

        <label
            for="typeSol"
            class="mb-2 block text-sm font-semibold text-slate-700"
        >
            Type de sol
        </label>

        <input
            type="text"
            name="typeSol"
            id="typeSol"
            value="{{ old('typeSol', $parcelle->typeSol ?? '') }}"
            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
            placeholder="Ex. Sol ferrugineux"
        >

        @error('typeSol')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>


    {{-- ============================================================
        MODE DE FAIRE-VALOIR
    ============================================================ --}}
    <div>

        <label
            for="modeFaireValoir"
            class="mb-2 block text-sm font-semibold text-slate-700"
        >
            Mode de faire-valoir
        </label>

        @php
            $modeFaireValoir = old(
                'modeFaireValoir',
                $parcelle->modeFaireValoir ?? 'proprietaire'
            );
        @endphp

        <select
            name="modeFaireValoir"
            id="modeFaireValoir"
            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
        >

            <option value="proprietaire" {{ $modeFaireValoir === 'proprietaire' ? 'selected' : '' }}>
                Propriétaire
            </option>

            <option value="location" {{ $modeFaireValoir === 'location' ? 'selected' : '' }}>
                Location
            </option>

            <option value="pret" {{ $modeFaireValoir === 'pret' ? 'selected' : '' }}>
                Prêt
            </option>

            <option value="metayage" {{ $modeFaireValoir === 'metayage' ? 'selected' : '' }}>
                Métayage
            </option>

            <option value="autre" {{ $modeFaireValoir === 'autre' ? 'selected' : '' }}>
                Autre
            </option>

        </select>

        @error('modeFaireValoir')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>


    {{-- ============================================================
        MODE D'IRRIGATION
    ============================================================ --}}
    <div>

        <label
            for="modeIrrigation"
            class="mb-2 block text-sm font-semibold text-slate-700"
        >
            Mode d'irrigation
        </label>

        @php
            $modeIrrigation = old(
                'modeIrrigation',
                $parcelle->modeIrrigation ?? 'pluvial'
            );
        @endphp

        <select
            name="modeIrrigation"
            id="modeIrrigation"
            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
        >

            <option value="pluvial" {{ $modeIrrigation === 'pluvial' ? 'selected' : '' }}>
                Pluvial
            </option>

            <option value="gravitaire" {{ $modeIrrigation === 'gravitaire' ? 'selected' : '' }}>
                Gravitaire
            </option>

            <option value="pompage" {{ $modeIrrigation === 'pompage' ? 'selected' : '' }}>
                Pompage
            </option>

            <option value="aucun" {{ $modeIrrigation === 'aucun' ? 'selected' : '' }}>
                Aucun
            </option>

            <option value="autre" {{ $modeIrrigation === 'autre' ? 'selected' : '' }}>
                Autre
            </option>

        </select>

        @error('modeIrrigation')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>


    {{-- ============================================================
        ÉTAT DE LA PARCELLE
    ============================================================ --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

        <label class="flex items-center gap-3 rounded-xl border border-slate-200 p-4">

            <input
                type="checkbox"
                name="estCultivee"
                value="1"
                {{ old('estCultivee', $parcelle->estCultivee ?? true) ? 'checked' : '' }}
                class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
            >

            <span class="text-sm font-medium text-slate-700">
                Parcelle cultivée
            </span>

        </label>


        <label class="flex items-center gap-3 rounded-xl border border-slate-200 p-4">

            <input
                type="checkbox"
                name="estJachere"
                value="1"
                {{ old('estJachere', $parcelle->estJachere ?? false) ? 'checked' : '' }}
                class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
            >

            <span class="text-sm font-medium text-slate-700">
                Jachère
            </span>

        </label>


        <label class="flex items-center gap-3 rounded-xl border border-slate-200 p-4">

            <input
                type="checkbox"
                name="presenceArbres"
                value="1"
                {{ old('presenceArbres', $parcelle->presenceArbres ?? false) ? 'checked' : '' }}
                class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
            >

            <span class="text-sm font-medium text-slate-700">
                Présence d'arbres
            </span>

        </label>

    </div>


    {{-- ============================================================
        OBSERVATIONS
    ============================================================ --}}
    <div>

        <label
            for="observations"
            class="mb-2 block text-sm font-semibold text-slate-700"
        >
            Observations
        </label>

        <textarea
            name="observations"
            id="observations"
            rows="4"
            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
            placeholder="Informations complémentaires..."
        >{{ old('observations', $parcelle->observations ?? '') }}</textarea>

        @error('observations')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>

</div>


{{-- ================================================================
    JAVASCRIPT MESURE GPS
================================================================ --}}
@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const bouton = document.getElementById('btnDemarrerGPS');
    const texteBouton = document.getElementById('texteBoutonGPS');

    const informations = document.getElementById('gpsInformations');
    const nombrePoints = document.getElementById('nombrePointsGPS');
    const precision = document.getElementById('precisionGPS');
    const superficieAffichee = document.getElementById('superficieAffichee');
    const message = document.getElementById('messageGPS');

    const champSuperficie = document.getElementById('superficie');
    const champPoints = document.getElementById('points_gps');

    if (!bouton) {
        return;
    }

    let points = [];
    let watchId = null;
    let mesureActive = false;


    /*
    |--------------------------------------------------------------------------
    | Distance entre deux coordonnées GPS
    |--------------------------------------------------------------------------
    */
    function distanceMetres(point1, point2) {

        const R = 6371000;

        const lat1 =
            point1.latitude * Math.PI / 180;

        const lat2 =
            point2.latitude * Math.PI / 180;

        const deltaLat =
            (point2.latitude - point1.latitude) *
            Math.PI / 180;

        const deltaLon =
            (point2.longitude - point1.longitude) *
            Math.PI / 180;

        const a =
            Math.sin(deltaLat / 2) ** 2 +
            Math.cos(lat1) *
            Math.cos(lat2) *
            Math.sin(deltaLon / 2) ** 2;

        const c =
            2 *
            Math.atan2(
                Math.sqrt(a),
                Math.sqrt(1 - a)
            );

        return R * c;
    }


    /*
    |--------------------------------------------------------------------------
    | Calcul de superficie
    |--------------------------------------------------------------------------
    */
    function calculerSuperficie(points) {

        if (points.length < 3) {
            return 0;
        }

        const latitudeMoyenne =
            points.reduce(
                (total, point) => total + point.latitude,
                0
            ) / points.length;

        const latRad =
            latitudeMoyenne * Math.PI / 180;

        const metresParDegreLatitude = 111320;

        const metresParDegreLongitude =
            111320 * Math.cos(latRad);

        const origineLat =
            points[0].latitude;

        const origineLon =
            points[0].longitude;

        const coordonnees =
            points.map(point => {

                return {
                    x:
                        (point.longitude - origineLon) *
                        metresParDegreLongitude,

                    y:
                        (point.latitude - origineLat) *
                        metresParDegreLatitude
                };

            });


        let aire = 0;

        for (
            let i = 0;
            i < coordonnees.length;
            i++
        ) {

            const suivant =
                (i + 1) % coordonnees.length;

            aire +=
                coordonnees[i].x *
                coordonnees[suivant].y
                -
                coordonnees[suivant].x *
                coordonnees[i].y;
        }

        return Math.abs(aire) / 2;
    }


    /*
    |--------------------------------------------------------------------------
    | Mise à jour affichage
    |--------------------------------------------------------------------------
    */
    function actualiserAffichage() {

        nombrePoints.textContent =
            points.length;

        champPoints.value =
            JSON.stringify(points);


        if (points.length >= 3) {

            const superficieM2 =
                calculerSuperficie(points);

            const superficieHa =
                superficieM2 / 10000;

            superficieAffichee.textContent =
                superficieHa.toFixed(2);

            champSuperficie.value =
                superficieHa.toFixed(2);

        } else {

            superficieAffichee.textContent =
                '—';

            champSuperficie.value =
                '';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Réception d'un nouveau point GPS
    |--------------------------------------------------------------------------
    */
    function recevoirPosition(position) {

        const latitude =
            position.coords.latitude;

        const longitude =
            position.coords.longitude;

        const altitude =
            position.coords.altitude;

        const precisionGPS =
            position.coords.accuracy;


        precision.textContent =
            precisionGPS.toFixed(1) + ' m';


        /*
        | Éviter les points trop proches.
        */
        if (points.length > 0) {

            const dernierPoint =
                points[points.length - 1];

            const distance =
                distanceMetres(
                    dernierPoint,
                    {
                        latitude: latitude,
                        longitude: longitude
                    }
                );

            if (distance < 2) {
                return;
            }
        }


        points.push({
            latitude: latitude,
            longitude: longitude,
            altitude: altitude,
            precisionGPS: precisionGPS,
            ordre: points.length + 1
        });


        actualiserAffichage();


        if (precisionGPS <= 5) {

            message.textContent =
                'GPS précis. Continuez à faire le tour de la parcelle.';

        } else if (precisionGPS <= 10) {

            message.textContent =
                'Précision GPS moyenne. Continuez doucement.';

        } else {

            message.textContent =
                'Précision GPS faible. Attendez une meilleure précision avant de continuer.';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Erreur GPS
    |--------------------------------------------------------------------------
    */
    function erreurGPS(error) {

        if (error.code === 1) {

            message.textContent =
                'L’autorisation de localisation a été refusée.';

        } else if (error.code === 2) {

            message.textContent =
                'Position GPS indisponible.';

        } else if (error.code === 3) {

            message.textContent =
                'Le GPS met trop de temps à répondre.';

        } else {

            message.textContent =
                'Impossible d’obtenir la position GPS.';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Démarrer / arrêter
    |--------------------------------------------------------------------------
    */
    bouton.addEventListener('click', function () {

        if (!navigator.geolocation) {

            informations.classList.remove('hidden');

            message.textContent =
                'La géolocalisation n’est pas disponible sur cet appareil.';

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | DÉMARRER
        |--------------------------------------------------------------------------
        */
        if (!mesureActive) {

            points = [];

            actualiserAffichage();

            informations.classList.remove('hidden');

            mesureActive = true;

            texteBouton.textContent =
                'Arrêter la mesure';


            bouton.classList.remove(
                'bg-[#006a4f]',
                'hover:bg-[#00583f]'
            );

            bouton.classList.add(
                'bg-red-600',
                'hover:bg-red-700'
            );


            message.textContent =
                'Recherche de votre position GPS...';


            watchId =
                navigator.geolocation.watchPosition(
                    recevoirPosition,
                    erreurGPS,
                    {
                        enableHighAccuracy: true,
                        maximumAge: 0,
                        timeout: 15000
                    }
                );

        }


        /*
        |--------------------------------------------------------------------------
        | ARRÊTER
        |--------------------------------------------------------------------------
        */
        else {

            if (watchId !== null) {

                navigator.geolocation.clearWatch(
                    watchId
                );

                watchId = null;
            }


            mesureActive = false;


            texteBouton.textContent =
                'Reprendre la mesure';


            bouton.classList.remove(
                'bg-red-600',
                'hover:bg-red-700'
            );

            bouton.classList.add(
                'bg-[#006a4f]',
                'hover:bg-[#00583f]'
            );


            if (points.length < 3) {

                message.textContent =
                    'Il faut au moins 3 points GPS pour calculer la superficie.';

            } else {

                const superficieM2 =
                    calculerSuperficie(points);

                const superficieHa =
                    superficieM2 / 10000;

                message.textContent =
                    'Mesure terminée : ' +
                    superficieM2.toFixed(2) +
                    ' m² (' +
                    superficieHa.toFixed(2) +
                    ' ha).';
            }
        }

    });


    /*
    |--------------------------------------------------------------------------
    | Vérification avant enregistrement
    |--------------------------------------------------------------------------
    */
    const formulaire =
        bouton.closest('form');

    if (formulaire) {

        formulaire.addEventListener(
            'submit',
            function (event) {

                if (points.length < 3) {

                    event.preventDefault();

                    alert(
                        'Veuillez mesurer la parcelle avec le GPS avant de l’enregistrer.'
                    );

                    return;
                }


                champPoints.value =
                    JSON.stringify(points);

                champSuperficie.value =
                    (
                        calculerSuperficie(points) / 10000
                    ).toFixed(2);
            }
        );
    }

});
</script>

@endpush
