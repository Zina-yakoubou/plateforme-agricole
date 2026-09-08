@csrf

{{-- ============================================================
    INFORMATIONS ADMINISTRATIVES
============================================================ --}}

<div class="space-y-6">

    <div>
        <h2 class="text-lg font-semibold text-slate-800">
            Informations de la maison
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Renseignez les informations générales de la maison.
        </p>
    </div>

    {{-- ========================================================
        VILLAGE
    ========================================================= --}}

    <div>
        <label class="block text-sm font-medium text-slate-700">
            Village
        </label>

        <div class="mt-1 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
            <div class="font-medium text-slate-800">
                {{ $village->nom ?? $maison->village->nom ?? 'Village non renseigné' }}
            </div>

            @if(isset($village) && $village->canton)
                <div class="mt-1 text-xs text-slate-500">
                    Canton :
                    {{ $village->canton->nom }}

                    @if($village->canton->commune)
                        · Commune :
                        {{ $village->canton->commune->nom }}
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- ========================================================
        NUMÉRO DE MAISON
    ========================================================= --}}

    @if(isset($maison))

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

            <div>
                <label class="block text-sm font-medium text-slate-700">
                    Numéro de maison
                </label>

                <input
                    type="text"
                    value="{{ $maison->numeroMaison }}"
                    readonly
                    class="mt-1 block w-full rounded-lg border-slate-300 bg-slate-100 text-slate-600 shadow-sm"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">
                    Identifiant hors ligne
                </label>

                <input
                    type="text"
                    value="{{ $maison->uid }}"
                    readonly
                    class="mt-1 block w-full rounded-lg border-slate-300 bg-slate-100 text-xs text-slate-600 shadow-sm"
                >
            </div>

        </div>

    @endif

    {{-- ========================================================
        CHEF DE MAISON
    ========================================================= --}}

    <div>
        <label
            for="chefMaison"
            class="block text-sm font-medium text-slate-700"
        >
            Chef de maison
        </label>

        <input
            type="text"
            name="chefMaison"
            id="chefMaison"
            value="{{ old('chefMaison', $maison->chefMaison ?? '') }}"
            maxlength="255"
            class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-green-600 focus:ring-green-600"
            placeholder="Nom complet du chef de maison"
        >

        @error('chefMaison')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror
    </div>

    {{-- ========================================================
        ADRESSE
    ========================================================= --}}

    <div>
        <label
            for="adresse"
            class="block text-sm font-medium text-slate-700"
        >
            Adresse
        </label>

        <textarea
            name="adresse"
            id="adresse"
            rows="3"
            maxlength="255"
            class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-green-600 focus:ring-green-600"
            placeholder="Adresse ou indication permettant d'identifier la maison"
        >{{ old('adresse', $maison->adresse ?? '') }}</textarea>

        @error('adresse')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror
    </div>

    {{-- ============================================================
        LOCALISATION GPS
    ============================================================= --}}

    <div class="border-t border-slate-200 pt-6">

        <div class="mb-4">
            <h2 class="text-lg font-semibold text-slate-800">
                Localisation GPS
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Localisez précisément la maison à l'aide du GPS.
            </p>
        </div>

        {{-- Champs GPS cachés --}}

        <input
            type="hidden"
            name="latitude"
            id="latitude"
            value="{{ old('latitude', $maison->latitude ?? '') }}"
        >

        <input
            type="hidden"
            name="longitude"
            id="longitude"
            value="{{ old('longitude', $maison->longitude ?? '') }}"
        >

        <input
            type="hidden"
            name="precisionGPS"
            id="precisionGPS"
            value="{{ old('precisionGPS', $maison->precisionGPS ?? '') }}"
        >

        <input
            type="hidden"
            name="dateIdentification"
            id="dateIdentification"
            value="{{ old(
                'dateIdentification',
                isset($maison->dateIdentification)
                    ? $maison->dateIdentification->format('Y-m-d H:i:s')
                    : ''
            ) }}"
        >

        {{-- Bouton de localisation --}}

        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center">

            <button
                type="button"
                id="btnLocaliser"
                class="inline-flex items-center justify-center rounded-lg bg-green-700 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-green-800"
            >
                📍 Localiser la maison
            </button>

            <span
                id="gpsStatus"
                class="text-sm text-slate-500"
            >
                Aucune localisation effectuée.
            </span>

        </div>

        {{-- Informations GPS --}}

        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

            <div>
                <label class="block text-xs font-medium uppercase tracking-wide text-slate-500">
                    Latitude
                </label>

                <div
                    id="latitudeDisplay"
                    class="mt-1 rounded-lg bg-slate-50 px-3 py-2 text-sm text-slate-700"
                >
                    {{ $maison->latitude ?? '—' }}
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium uppercase tracking-wide text-slate-500">
                    Longitude
                </label>

                <div
                    id="longitudeDisplay"
                    class="mt-1 rounded-lg bg-slate-50 px-3 py-2 text-sm text-slate-700"
                >
                    {{ $maison->longitude ?? '—' }}
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium uppercase tracking-wide text-slate-500">
                    Précision GPS
                </label>

                <div
                    id="precisionDisplay"
                    class="mt-1 rounded-lg bg-slate-50 px-3 py-2 text-sm text-slate-700"
                >
                    @if(isset($maison) && $maison->precisionGPS !== null)
                        {{ $maison->precisionGPS }} m
                    @else
                        —
                    @endif
                </div>
            </div>

        </div>

        {{-- Carte --}}

        <div class="mt-4 overflow-hidden rounded-xl border border-slate-200">

            <div
                id="map"
                class="h-80 w-full"
            ></div>

        </div>

        @error('latitude')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

        @error('longitude')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

        @error('precisionGPS')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>

    {{-- ============================================================
        PHOTO
    ============================================================= --}}

    <div class="border-t border-slate-200 pt-6">

        <div class="mb-4">
            <h2 class="text-lg font-semibold text-slate-800">
                Photo de la maison
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Vous pouvez joindre une photo permettant d'identifier la maison.
            </p>
        </div>

        <input
            type="file"
            name="photoMaison"
            id="photoMaison"
            accept="image/jpeg,image/png,image/webp"
            class="block w-full rounded-lg border border-slate-300 bg-white text-sm text-slate-700 file:mr-4 file:border-0 file:bg-slate-100 file:px-4 file:py-2.5 file:text-sm file:font-medium hover:file:bg-slate-200"
        >

        @if(isset($maison) && $maison->photoMaison)

            <div class="mt-4">

                <p class="mb-2 text-sm font-medium text-slate-700">
                    Photo actuelle
                </p>

                <img
                    src="{{ Storage::url($maison->photoMaison) }}"
                    alt="Photo de la maison"
                    class="max-h-64 rounded-xl border border-slate-200 object-cover shadow-sm"
                >

            </div>

        @endif

        @error('photoMaison')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>

    {{-- ============================================================
        STATUT
    ============================================================= --}}

    <div class="border-t border-slate-200 pt-6">

        <label
            for="statut"
            class="block text-sm font-medium text-slate-700"
        >
            Statut
        </label>

        <select
            name="statut"
            id="statut"
            class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-green-600 focus:ring-green-600"
        >

            @php
                $statutActuel = old(
                    'statut',
                    $maison->statut ?? 'brouillon'
                );
            @endphp

            <option
                value="brouillon"
                @selected($statutActuel === 'brouillon')
            >
                Brouillon
            </option>

            <option
                value="en_cours"
                @selected($statutActuel === 'en_cours')
            >
                En cours
            </option>

            <option
                value="terminee"
                @selected($statutActuel === 'terminee')
            >
                Terminée
            </option>

            <option
                value="verifiee"
                @selected($statutActuel === 'verifiee')
            >
                Vérifiée
            </option>

        </select>

        @error('statut')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>

    {{-- ============================================================
        BOUTONS
    ============================================================= --}}

    <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:justify-end">

        <a
            href="{{ isset($village)
                ? route('villages.maisons.index', $village)
                : route('maisons.show', $maison) }}"
            class="inline-flex items-center justify-center rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
        >
            Annuler
        </a>

        <button
            type="submit"
            class="inline-flex items-center justify-center rounded-lg bg-green-700 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-green-800"
        >
            {{ isset($maison)
                ? 'Mettre à jour la maison'
                : 'Enregistrer la maison' }}
        </button>

    </div>

</div>

{{-- ================================================================
    LEAFLET
================================================================ --}}

@once

    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    />

    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    ></script>

@endonce

<script>
document.addEventListener('DOMContentLoaded', function () {

    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const precisionInput = document.getElementById('precisionGPS');
    const dateInput = document.getElementById('dateIdentification');

    const latitudeDisplay = document.getElementById('latitudeDisplay');
    const longitudeDisplay = document.getElementById('longitudeDisplay');
    const precisionDisplay = document.getElementById('precisionDisplay');

    const btnLocaliser = document.getElementById('btnLocaliser');
    const gpsStatus = document.getElementById('gpsStatus');

    const latitudeInitiale = parseFloat(latitudeInput.value);
    const longitudeInitiale = parseFloat(longitudeInput.value);

    const latitudeValide = Number.isFinite(latitudeInitiale);
    const longitudeValide = Number.isFinite(longitudeInitiale);

    let latitudeCentre = latitudeValide
        ? latitudeInitiale
        : 8.6195;

    let longitudeCentre = longitudeValide
        ? longitudeInitiale
        : 0.8248;

    const map = L.map('map').setView(
        [latitudeCentre, longitudeCentre],
        latitudeValide && longitudeValide ? 17 : 7
    );

    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }
    ).addTo(map);

    let marqueur = null;

    if (latitudeValide && longitudeValide) {

        marqueur = L.marker([
            latitudeInitiale,
            longitudeInitiale
        ]).addTo(map);
    }

    function mettreAJourAffichage(
        latitude,
        longitude,
        precision
    ) {

        latitudeDisplay.textContent =
            latitude ?? '—';

        longitudeDisplay.textContent =
            longitude ?? '—';

        precisionDisplay.textContent =
            precision !== null && precision !== ''
                ? `${precision} m`
                : '—';
    }

    btnLocaliser.addEventListener('click', function () {

        if (!navigator.geolocation) {

            gpsStatus.textContent =
                'La géolocalisation n’est pas disponible sur cet appareil.';

            gpsStatus.classList.add('text-red-600');

            return;
        }

        btnLocaliser.disabled = true;

        btnLocaliser.textContent =
            'Localisation en cours...';

        gpsStatus.textContent =
            'Recherche de votre position GPS...';

        gpsStatus.classList.remove(
            'text-red-600',
            'text-green-600'
        );

        navigator.geolocation.getCurrentPosition(

            function (position) {

                const latitude =
                    position.coords.latitude;

                const longitude =
                    position.coords.longitude;

                const precision =
                    position.coords.accuracy;

                latitudeInput.value =
                    latitude.toFixed(7);

                longitudeInput.value =
                    longitude.toFixed(7);

                precisionInput.value =
                    precision.toFixed(2);

                const maintenant =
                    new Date();

                const annee =
                    maintenant.getFullYear();

                const mois =
                    String(
                        maintenant.getMonth() + 1
                    ).padStart(2, '0');

                const jour =
                    String(
                        maintenant.getDate()
                    ).padStart(2, '0');

                const heures =
                    String(
                        maintenant.getHours()
                    ).padStart(2, '0');

                const minutes =
                    String(
                        maintenant.getMinutes()
                    ).padStart(2, '0');

                const secondes =
                    String(
                        maintenant.getSeconds()
                    ).padStart(2, '0');

                dateInput.value =
                    `${annee}-${mois}-${jour} ${heures}:${minutes}:${secondes}`;

                mettreAJourAffichage(
                    latitude.toFixed(7),
                    longitude.toFixed(7),
                    precision.toFixed(2)
                );

                if (marqueur) {
                    map.removeLayer(marqueur);
                }

                marqueur = L.marker([
                    latitude,
                    longitude
                ])
                    .addTo(map)
                    .bindPopup(
                        'Position de la maison'
                    )
                    .openPopup();

                map.setView(
                    [latitude, longitude],
                    18
                );

                gpsStatus.textContent =
                    'Maison localisée avec succès.';

                gpsStatus.classList.add(
                    'text-green-600'
                );

                btnLocaliser.disabled = false;

                btnLocaliser.textContent =
                    'Actualiser la localisation';
            },

            function (error) {

                let message =
                    'Impossible de récupérer la position GPS.';

                if (error.code === 1) {
                    message =
                        'L’accès à la localisation a été refusé.';
                }

                if (error.code === 2) {
                    message =
                        'La position GPS est indisponible.';
                }

                if (error.code === 3) {
                    message =
                        'La récupération de la position a expiré.';
                }

                gpsStatus.textContent =
                    message;

                gpsStatus.classList.add(
                    'text-red-600'
                );

                btnLocaliser.disabled = false;

                btnLocaliser.textContent =
                    'Réessayer la localisation';
            },

            {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 0
            }
        );
    });

});
</script>