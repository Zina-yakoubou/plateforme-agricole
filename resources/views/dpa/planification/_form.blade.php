{{-- ============================================================
     FORMULAIRE PLANIFICATION
     Utilisé par create.blade.php et edit.blade.php
============================================================ --}}

@php
    $isEdit = isset($planification);

    $dateDebutValue = old(
        'dateDebut',
        isset($planification?->dateDebut)
            ? $planification->dateDebut->format('Y-m-d\TH:i')
            : ''
    );

    $dateFinValue = old(
        'dateFin',
        isset($planification?->dateFin)
            ? $planification->dateFin->format('Y-m-d\TH:i')
            : ''
    );

    $observationsValue = old(
        'observations',
        $planification->observations ?? ''
    );
@endphp


{{-- ============================================================
     ERREURS DE VALIDATION
============================================================ --}}

@if ($errors->any())
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">

        <div class="flex items-start gap-3">

            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600">
                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 8v4m0 4h.01M10.29 3.86l-7.82 13.5A1 1 0 003.33 21h17.34a1 1 0 001.86-3.64l-7.82-13.5a1 1 0 00-4.42 0z"
                    />
                </svg>
            </div>

            <div>
                <h3 class="font-semibold text-red-800">
                    Impossible d'enregistrer la planification
                </h3>

                <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

        </div>

    </div>
@endif


{{-- ============================================================
     INFORMATIONS DE LA CAMPAGNE
============================================================ --}}

@if(isset($deploiement))
    <div class="mb-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

        <div class="mb-5 flex items-center gap-3">

            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#266486]/10 text-[#266486]">
                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z"
                    />
                </svg>
            </div>

            <div>
                <h2 class="text-lg font-semibold text-gray-900">
                    Campagne réceptionnée
                </h2>

                <p class="text-sm text-gray-500">
                    Informations de la campagne à planifier
                </p>
            </div>

        </div>


        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

            {{-- Code --}}
            <div>
                <label class="block text-sm font-medium text-gray-600">
                    Code campagne
                </label>

                <div class="mt-1 rounded-lg bg-gray-50 px-4 py-3 font-semibold text-gray-900">
                    {{ $deploiement->campagne->codeCampagne ?? '—' }}
                </div>
            </div>


            {{-- Libellé --}}
            <div>
                <label class="block text-sm font-medium text-gray-600">
                    Libellé
                </label>

                <div class="mt-1 rounded-lg bg-gray-50 px-4 py-3 text-gray-900">
                    {{ $deploiement->campagne->libelle ?? '—' }}
                </div>
            </div>


            {{-- Structure --}}
            <div>
                <label class="block text-sm font-medium text-gray-600">
                    Structure
                </label>

                <div class="mt-1 rounded-lg bg-gray-50 px-4 py-3 text-gray-900">
                    {{ $deploiement->campagne->structure->nomStructure ?? '—' }}
                </div>
            </div>


            {{-- Préfecture --}}
            <div>
                <label class="block text-sm font-medium text-gray-600">
                    Préfecture
                </label>

                <div class="mt-1 rounded-lg bg-gray-50 px-4 py-3 text-gray-900">
                    {{ $deploiement->prefecture->nomPrefecture ?? '—' }}
                </div>
            </div>

        </div>

    </div>
@endif


{{-- ============================================================
     FORMULAIRE
============================================================ --}}

<div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

    <div class="mb-6">

        <h2 class="text-lg font-semibold text-gray-900">
            {{ $isEdit ? 'Modifier la planification' : 'Paramètres de planification' }}
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Définissez la période prévue pour l'exécution de la campagne.
        </p>

    </div>


    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">


        {{-- ====================================================
             DATE DE DÉBUT
        ===================================================== --}}

        <div>

            <label
                for="dateDebut"
                class="mb-2 block text-sm font-medium text-gray-700"
            >
                Date de début
            </label>

            <input
                type="datetime-local"
                id="dateDebut"
                name="dateDebut"
                value="{{ $dateDebutValue }}"
                class="block w-full rounded-xl border-gray-300 px-4 py-3 text-sm shadow-sm
                       focus:border-[#266486] focus:ring-[#266486]
                       @error('dateDebut')
                           border-red-500 ring-1 ring-red-500
                       @enderror"
            >

            <p class="mt-1.5 text-xs text-gray-500">
                La date doit être comprise dans la période de la campagne.
            </p>

            @error('dateDebut')
                <p class="mt-1 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

        </div>


        {{-- ====================================================
             DATE DE FIN
        ===================================================== --}}

        {{-- <div>

            <label
                for="dateFin"
                class="mb-2 block text-sm font-medium text-gray-700"
            >
                Date de fin
            </label>

            <input
                type="datetime-local"
                id="dateFin"
                name="dateFin"
                value="{{ $dateFinValue }}"
                class="block w-full rounded-xl border-gray-300 px-4 py-3 text-sm shadow-sm
                       focus:border-[#266486] focus:ring-[#266486]
                       @error('dateFin')
                           border-red-500 ring-1 ring-red-500
                       @enderror"
            >

            <p class="mt-1.5 text-xs text-gray-500">
                La date de fin doit être postérieure ou égale à la date de début.
            </p>

            @error('dateFin')
                <p class="mt-1 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

        </div> --}}

        {{-- ====================================================
            DATE DE DÉBUT
        ===================================================== --}}

        <div>

            <label
                for="dateDebut"
                class="mb-2 block text-sm font-medium text-gray-700"
            >
                Date de début de la planification
            </label>

            <input
                type="datetime-local"
                id="dateDebut"
                name="dateDebut"
                value="{{ $deploiement->campagne->dateDebut
                    ? $deploiement->campagne->dateDebut->format('Y-m-d\TH:i')
                    : old('dateDebut', $planification->dateDebut ?? '') }}"
                readonly
                class="block w-full cursor-not-allowed rounded-xl border-gray-300 bg-gray-100 px-4 py-3 text-sm text-gray-700 shadow-sm"
            >

            <p class="mt-1.5 text-xs text-gray-500">
                Cette date est définie par la structure responsable de la campagne
                et ne peut pas être modifiée au niveau préfectoral.
            </p>

            @error('dateDebut')
                <p class="mt-1 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

        </div>


        {{-- ====================================================
             OBSERVATIONS
        ===================================================== --}}

        <div class="md:col-span-2">

            <label
                for="observations"
                class="mb-2 block text-sm font-medium text-gray-700"
            >
                Observations
                <span class="font-normal text-gray-400">
                    (facultatif)
                </span>
            </label>

            <textarea
                id="observations"
                name="observations"
                rows="5"
                maxlength="5000"
                placeholder="Ajoutez ici les observations ou précisions relatives à la planification..."
                class="block w-full rounded-xl border-gray-300 px-4 py-3 text-sm shadow-sm
                       focus:border-[#266486] focus:ring-[#266486]
                       @error('observations')
                           border-red-500 ring-1 ring-red-500
                       @enderror"
            >{{ $observationsValue }}</textarea>

            <div class="mt-1 flex items-center justify-between">

                <p class="text-xs text-gray-500">
                    Maximum 5000 caractères.
                </p>

                <span
                    id="observations-count"
                    class="text-xs text-gray-400"
                >
                    {{ strlen($observationsValue) }}/5000
                </span>

            </div>

            @error('observations')
                <p class="mt-1 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

        </div>

    </div>


    {{-- ========================================================
         ACTIONS
    ========================================================= --}}

    <div class="mt-8 flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 sm:flex-row sm:justify-end">

        <a
            href="{{ $isEdit
                ? route('dpa.planification.show', $planification)
                : route('dpa.planification.index')
            }}"
            class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-5 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
        >
            Annuler
        </a>


        <button
            type="submit"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#266486] px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#1f526d] focus:outline-none focus:ring-2 focus:ring-[#266486] focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60"
            id="submit-button"
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
                    stroke-width="2"
                    d="M5 13l4 4L19 7"
                />
            </svg>

            <span id="submit-text">
                {{ $isEdit ? 'Enregistrer les modifications' : 'Créer la planification' }}
            </span>

        </button>

    </div>

</div>


{{-- ============================================================
     CONTRÔLE CLIENT
============================================================ --}}

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.querySelector('form');

    const dateDebut = document.getElementById('dateDebut');
    const dateFin = document.getElementById('dateFin');

    const observations = document.getElementById('observations');
    const observationsCount = document.getElementById('observations-count');

    const submitButton = document.getElementById('submit-button');
    const submitText = document.getElementById('submit-text');


    /*
    |--------------------------------------------------------------------------
    | COMPTEUR OBSERVATIONS
    |--------------------------------------------------------------------------
    */

    if (observations && observationsCount) {

        observations.addEventListener('input', function () {

            observationsCount.textContent =
                `${this.value.length}/5000`;

        });

    }


    /*
    |--------------------------------------------------------------------------
    | DATE DE FIN
    |--------------------------------------------------------------------------
    */

    function verifierDates() {

        if (!dateDebut || !dateFin) {
            return true;
        }

        dateFin.setCustomValidity('');

        if (
            dateDebut.value &&
            dateFin.value &&
            dateFin.value < dateDebut.value
        ) {

            dateFin.setCustomValidity(
                'La date de fin doit être postérieure ou égale à la date de début.'
            );

            return false;
        }

        return true;
    }


    if (dateDebut) {
        dateDebut.addEventListener('change', verifierDates);
    }

    if (dateFin) {
        dateFin.addEventListener('change', verifierDates);
    }


    /*
    |--------------------------------------------------------------------------
    | SOUMISSION
    |--------------------------------------------------------------------------
    */

    if (form) {

        form.addEventListener('submit', function (event) {

            if (!verifierDates()) {

                event.preventDefault();

                dateFin.reportValidity();

                return;
            }


            /*
            |--------------------------------------------------------------
            | PROTECTION DOUBLE CLIC
            |--------------------------------------------------------------
            */

            if (submitButton) {

                submitButton.disabled = true;

                if (submitText) {

                    submitText.textContent =
                        '{{ $isEdit ? "Enregistrement..." : "Création en cours..." }}';

                }

            }

        });

    }

});
</script>