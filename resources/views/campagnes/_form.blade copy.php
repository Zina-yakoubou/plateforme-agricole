@csrf
<div
    x-data="campagneForm()"
    class="space-y-6"
>

    {{-- =========================================================
         CODE CAMPAGNE
    ========================================================== --}}

    @if(isset($campagne))

        <div>
            <label
                for="codeCampagne"
                class="block text-sm font-medium text-slate-700"
            >
                Code campagne
            </label>

            <input
                type="text"
                id="codeCampagne"
                value="{{ $campagne->codeCampagne }}"
                readonly
                class="mt-1 block w-full rounded-lg border-slate-300
                       bg-slate-100 text-slate-600"
            >
        </div>

    @endif


    {{-- =========================================================
         LIBELLÉ
    ========================================================== --}}

    <div>
        <label
            for="libelle"
            class="block text-sm font-medium text-slate-700"
        >
            Libellé de la campagne
            <span class="text-red-500">*</span>
        </label>

        <input
            type="text"
            name="libelle"
            id="libelle"
            value="{{ old('libelle', $campagne->libelle ?? '') }}"
            required
            class="mt-1 block w-full rounded-lg border-slate-300
                   focus:border-green-500 focus:ring-green-500
                   @error('libelle') border-red-500 @enderror"
            placeholder="Ex. : Recensement agricole 2026"
        >

        @error('libelle')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror
    </div>


    {{-- =========================================================
         DESCRIPTION
    ========================================================== --}}

    <div>
        <label
            for="description"
            class="block text-sm font-medium text-slate-700"
        >
            Description
        </label>

        <textarea
            name="description"
            id="description"
            rows="4"
            class="mt-1 block w-full rounded-lg border-slate-300
                   focus:border-green-500 focus:ring-green-500
                   @error('description') border-red-500 @enderror"
            placeholder="Description de la campagne"
        >{{ old('description', $campagne->description ?? '') }}</textarea>

        @error('description')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror
    </div>


    {{-- =========================================================
         OBJECTIFS
    ========================================================== --}}

    <div>
        <label
            for="objectifs"
            class="block text-sm font-medium text-slate-700"
        >
            Objectifs du recensement
            <span class="text-red-500">*</span>
        </label>

        <textarea
            name="objectifs"
            id="objectifs"
            rows="5"
            required
            class="mt-1 block w-full rounded-lg border-slate-300
                   focus:border-green-500 focus:ring-green-500
                   @error('objectifs') border-red-500 @enderror"
            placeholder="Objectifs de la campagne"
        >{{ old('objectifs', $campagne->objectifs ?? '') }}</textarea>

        @error('objectifs')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror
    </div>


    {{-- =========================================================
         MÉTHODOLOGIE
    ========================================================== --}}

    <div>
        <label
            for="methodologie"
            class="block text-sm font-medium text-slate-700"
        >
            Méthodologie
        </label>

        <textarea
            name="methodologie"
            id="methodologie"
            rows="5"
            class="mt-1 block w-full rounded-lg border-slate-300
                   focus:border-green-500 focus:ring-green-500
                   @error('methodologie') border-red-500 @enderror"
            placeholder="Méthodologie de la campagne"
        >{{ old('methodologie', $campagne->methodologie ?? '') }}</textarea>

        @error('methodologie')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror
    </div>


    {{-- =========================================================
         INSTRUCTIONS
    ========================================================== --}}

    <div>
        <label
            for="instructions"
            class="block text-sm font-medium text-slate-700"
        >
            Instructions générales
        </label>

        <textarea
            name="instructions"
            id="instructions"
            rows="5"
            class="mt-1 block w-full rounded-lg border-slate-300
                   focus:border-green-500 focus:ring-green-500
                   @error('instructions') border-red-500 @enderror"
            placeholder="Instructions générales"
        >{{ old('instructions', $campagne->instructions ?? '') }}</textarea>

        @error('instructions')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror
    </div>


    {{-- =========================================================
         PORTÉE
    ========================================================== --}}

    @php
        $porteeActuelle = old(
            'portee',
            $campagne->portee ?? ''
        );
    @endphp

    <div>

        <label
            for="portee"
            class="block text-sm font-medium text-slate-700"
        >
            Portée de la campagne
            <span class="text-red-500">*</span>
        </label>

        <select
            name="portee"
            id="portee"
            x-model="portee"
            required
            class="mt-1 block w-full rounded-lg border-slate-300
                   focus:border-green-500 focus:ring-green-500
                   @error('portee') border-red-500 @enderror"
        >
            <option value="">
                -- Sélectionner --
            </option>

            <option value="nationale">
                Nationale
            </option>

            <option value="regionale">
                Régionale
            </option>

            <option value="prefectorale">
                Préfectorale
            </option>
        </select>

        @error('portee')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>


    {{-- =========================================================
         ZONES RÉGIONALES
    ========================================================== --}}

    <div
        x-show="portee === 'regionale'"
        x-cloak
        class="rounded-xl border border-slate-200 bg-white"
    >

        <div class="border-b border-slate-200 px-5 py-4">
            <h3 class="font-semibold text-slate-800">
                Régions couvertes
            </h3>
        </div>

        <div class="p-5">

            @php
                $regionsSelectionnees = collect(
                    old('zones', $campagne->zones ?? [])
                )
                ->filter(function ($zone) {
                    if (is_array($zone)) {
                        return ($zone['zone_type'] ?? null) === 'region';
                    }

                    return ($zone->zone_type ?? null) === 'region';
                })
                ->map(function ($zone) {
                    if (is_array($zone)) {
                        return (int) ($zone['zone_id'] ?? 0);
                    }

                    return (int) $zone->zone_id;
                })
                ->values()
                ->toArray();
            @endphp

            <div class="space-y-2">

                @forelse($regions ?? [] as $region)

                    <label
                        class="flex cursor-pointer items-center gap-3
                               rounded-lg border border-slate-200
                               px-4 py-3 transition
                               hover:bg-slate-50"
                    >

                        <input
                            type="checkbox"
                            name="zones[{{ $loop->index }}][zone_type]"
                            value="region"
                            @checked(
                                in_array(
                                    $region->idRegion,
                                    $regionsSelectionnees
                                )
                            )
                            class="h-4 w-4 rounded border-slate-300
                                   text-green-600
                                   focus:ring-green-500"
                        >

                        <input
                            type="hidden"
                            name="zones[{{ $loop->index }}][zone_id]"
                            value="{{ $region->idRegion }}"
                            @if(!in_array(
                                $region->idRegion,
                                $regionsSelectionnees
                            ))
                                disabled
                            @endif
                        >

                        <span class="text-sm font-medium text-slate-700">
                            {{ $region->nom }}
                        </span>

                    </label>

                @empty

                    <div class="rounded-lg bg-slate-50 p-4 text-sm text-slate-500">
                        Aucune région disponible.
                    </div>

                @endforelse

            </div>

            @error('zones')
                <p class="mt-3 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

        </div>

    </div>


    {{-- =========================================================
         ZONES PRÉFECTORALES
    ========================================================== --}}

    <div
        x-show="portee === 'prefectorale'"
        x-cloak
        class="rounded-xl border border-slate-200 bg-white"
    >

        <div class="border-b border-slate-200 px-5 py-4">
            <h3 class="font-semibold text-slate-800">
                Préfectures couvertes
            </h3>
        </div>

        <div class="p-5">

            @php
                $prefecturesSelectionnees = collect(
                    old('zones', $campagne->zones ?? [])
                )
                ->filter(function ($zone) {
                    if (is_array($zone)) {
                        return ($zone['zone_type'] ?? null) === 'prefecture';
                    }

                    return ($zone->zone_type ?? null) === 'prefecture';
                })
                ->map(function ($zone) {
                    if (is_array($zone)) {
                        return (int) ($zone['zone_id'] ?? 0);
                    }

                    return (int) $zone->zone_id;
                })
                ->values()
                ->toArray();
            @endphp

            <div class="space-y-5">

                @forelse($regions ?? [] as $region)

                    <div>

                        <div class="mb-2 text-xs font-semibold uppercase
                                    tracking-wide text-slate-400">
                            {{ $region->nom }}
                        </div>

                        <div class="space-y-2">

                            @foreach($region->prefectures as $prefecture)

                                @php
                                    $selected = in_array(
                                        $prefecture->idPrefecture,
                                        $prefecturesSelectionnees
                                    );
                                @endphp

                                <label
                                    class="flex cursor-pointer items-center gap-3
                                           rounded-lg border border-slate-200
                                           px-4 py-3 transition
                                           hover:bg-slate-50"
                                >

                                    <input
                                        type="checkbox"
                                        name="zones[{{ $region->idRegion }}_{{ $prefecture->idPrefecture }}][zone_type]"
                                        value="prefecture"
                                        @checked($selected)
                                        class="prefecture-checkbox h-4 w-4
                                               rounded border-slate-300
                                               text-green-600
                                               focus:ring-green-500"
                                    >

                                    <input
                                        type="hidden"
                                        name="zones[{{ $region->idRegion }}_{{ $prefecture->idPrefecture }}][zone_id]"
                                        value="{{ $prefecture->idPrefecture }}"
                                        @if(!$selected)
                                            disabled
                                        @endif
                                    >

                                    <span class="text-sm font-medium text-slate-700">
                                        {{ $prefecture->nom }}
                                    </span>

                                </label>

                            @endforeach

                        </div>

                    </div>

                @empty

                    <div class="rounded-lg bg-slate-50 p-4 text-sm text-slate-500">
                        Aucune préfecture disponible.
                    </div>

                @endforelse

            </div>

            @error('zones')
                <p class="mt-3 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

        </div>

    </div>


    {{-- =========================================================
         CAMPAGNE NATIONALE
    ========================================================== --}}

    <div
        x-show="portee === 'nationale'"
        x-cloak
        class="rounded-xl border border-blue-200 bg-blue-50 p-5"
    >

        <div class="flex items-center gap-3">

            <svg
                class="h-5 w-5 shrink-0 text-blue-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M12 21a9 9 0 100-18 9 9 0 000 18z"
                />
            </svg>

            <p class="font-medium text-blue-800">
                Territoire national
            </p>

        </div>

    </div>


    {{-- =========================================================
         STRUCTURE PORTEUSE
    ========================================================== --}}

    <div>

        <label
            for="structure_id"
            class="block text-sm font-medium text-slate-700"
        >
            Structure porteuse
        </label>

        <select
            name="structure_id"
            id="structure_id"
            class="mt-1 block w-full rounded-lg border-slate-300
                   focus:border-green-500 focus:ring-green-500
                   @error('structure_id') border-red-500 @enderror"
        >

            <option value="">
                -- Sélectionner --
            </option>

            @foreach($structures ?? [] as $structure)

                <option
                    value="{{ $structure->idStructure }}"
                    @selected(
                        old(
                            'structure_id',
                            $campagne->structure_id ?? ''
                        ) == $structure->idStructure
                    )
                >
                    {{ $structure->nom }}

                    @if($structure->niveau)
                        — {{ ucfirst($structure->niveau) }}
                    @endif
                </option>

            @endforeach

        </select>

        @error('structure_id')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>


    {{-- =========================================================
         PÉRIODE
    ========================================================== --}}

    <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

            {{-- DATE DÉBUT --}}

            <div>

                <label
                    for="dateDebut"
                    class="block text-sm font-medium text-slate-700"
                >
                    Date et heure de début
                    <span class="text-red-500">*</span>
                </label>

                @php
                    $dateDebutActuelle = old(
                        'dateDebut',
                        isset($campagne)
                            ? $campagne->dateDebut?->format('Y-m-d\TH:i')
                            : ''
                    );
                @endphp

                <input
                    type="datetime-local"
                    name="dateDebut"
                    id="dateDebut"
                    value="{{ $dateDebutActuelle }}"
                    min="{{ now()->format('Y-m-d\TH:i') }}"
                    required
                    class="mt-1 block w-full rounded-lg border-slate-300
                           focus:border-green-500 focus:ring-green-500
                           @error('dateDebut') border-red-500 @enderror"
                >

                @error('dateDebut')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- DATE FIN --}}

            <div>

                <label
                    for="dateFin"
                    class="block text-sm font-medium text-slate-700"
                >
                    Date et heure de fin
                </label>

                @php
                    $dateFinActuelle = old(
                        'dateFin',
                        isset($campagne)
                            ? $campagne->dateFin?->format('Y-m-d\TH:i')
                            : ''
                    );
                @endphp

                <input
                    type="datetime-local"
                    name="dateFin"
                    id="dateFin"
                    value="{{ $dateFinActuelle }}"
                    min="{{ now()->format('Y-m-d\TH:i') }}"
                    class="mt-1 block w-full rounded-lg border-slate-300
                           focus:border-green-500 focus:ring-green-500
                           @error('dateFin') border-red-500 @enderror"
                >

                @error('dateFin')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>

        </div>

    </div>


    {{-- =========================================================
         CARACTÈRE OFFICIEL
    ========================================================== --}}

    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">

        <div class="flex items-center gap-3">

            <input
                type="hidden"
                name="estOfficielle"
                value="0"
            >

            <input
                type="checkbox"
                name="estOfficielle"
                id="estOfficielle"
                value="1"
                @checked(
                    old(
                        'estOfficielle',
                        $campagne->estOfficielle ?? false
                    )
                )
                class="h-4 w-4 rounded border-slate-300
                       text-green-600
                       focus:ring-green-500"
            >

            <label
                for="estOfficielle"
                class="font-medium text-slate-700"
            >
                Campagne officiellement reconnue
            </label>

        </div>

        @error('estOfficielle')
            <p class="mt-2 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>


    {{-- =========================================================
         STATUT
    ========================================================== --}}

    @if(isset($campagne))

        <div>

            <label class="block text-sm font-medium text-slate-700">
                Statut actuel
            </label>

            <div class="mt-2">

                @switch($campagne->statut)

                    @case('planifiee')

                        <span
                            class="inline-flex items-center gap-2
                                   rounded-full bg-yellow-100
                                   px-3 py-1.5 text-xs font-semibold
                                   text-yellow-700"
                        >
                            <span
                                class="h-1.5 w-1.5 rounded-full bg-yellow-500"
                            ></span>
                            Planifiée
                        </span>

                        @break

                    @case('active')

                        <span
                            class="inline-flex items-center gap-2
                                   rounded-full bg-green-100
                                   px-3 py-1.5 text-xs font-semibold
                                   text-green-700"
                        >
                            <span
                                class="h-1.5 w-1.5 rounded-full bg-green-500"
                            ></span>
                            Active
                        </span>

                        @break

                    @case('cloturee')

                        <span
                            class="inline-flex items-center gap-2
                                   rounded-full bg-gray-100
                                   px-3 py-1.5 text-xs font-semibold
                                   text-gray-700"
                        >
                            <span
                                class="h-1.5 w-1.5 rounded-full bg-gray-500"
                            ></span>
                            Clôturée
                        </span>

                        @break

                    @case('archivee')

                        <span
                            class="inline-flex items-center gap-2
                                   rounded-full bg-slate-100
                                   px-3 py-1.5 text-xs font-semibold
                                   text-slate-600"
                        >
                            <span
                                class="h-1.5 w-1.5 rounded-full bg-slate-500"
                            ></span>
                            Archivée
                        </span>

                        @break

                    @default

                        <span
                            class="inline-flex items-center gap-2
                                   rounded-full bg-slate-100
                                   px-3 py-1.5 text-xs font-semibold
                                   text-slate-600"
                        >
                            Inconnue
                        </span>

                @endswitch

            </div>

        </div>

    @endif


    {{-- =========================================================
         BOUTONS
    ========================================================== --}}

    <div
        class="flex items-center justify-end gap-3
               border-t border-slate-100 pt-5"
    >

        <a
            href="{{ route('campagnes.index') }}"
            class="rounded-lg border border-slate-300
                   px-5 py-2.5 text-slate-700
                   transition hover:bg-slate-50"
        >
            Annuler
        </a>

        <button
            type="submit"
            class="rounded-lg bg-green-600
                   px-6 py-2.5 font-medium text-white
                   transition hover:bg-green-700"
        >
            {{ isset($campagne)
                ? 'Enregistrer les modifications'
                : 'Créer la campagne'
            }}
        </button>

    </div>

</div>


{{-- =============================================================
     ALPINE
============================================================= --}}

<script>
    function campagneForm() {
        return {
            portee: @js($porteeActuelle),

            init() {

                this.$nextTick(() => {

                    document
                        .querySelectorAll(
                            'input[type="checkbox"][name^="zones["]'
                        )
                        .forEach((checkbox) => {

                            checkbox.addEventListener(
                                'change',
                                function () {

                                    const parent =
                                        this.closest('label');

                                    const hiddenInput =
                                        parent?.querySelector(
                                            'input[type="hidden"]'
                                        );

                                    if (hiddenInput) {
                                        hiddenInput.disabled =
                                            !this.checked;
                                    }
                                }
                            );
                        });
                });
            }
        }
    }
</script>