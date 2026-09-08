@csrf

{{-- ========================================================= --}}
{{-- MAISON RATTACHÉE --}}
{{-- ========================================================= --}}

<div class="mb-6">

    <label
        for="maison"
        class="block text-sm font-medium text-gray-700"
    >
        Maison rattachée
    </label>

    <input
        type="text"
        id="maison"
        value="{{ $maison->numeroMaison ?? $menage->maison->numeroMaison ?? 'Maison non définie' }}"
        readonly
        class="mt-1 block w-full rounded-lg
               border-gray-300
               bg-gray-100
               text-gray-700"
    >

    <p class="mt-1 text-xs text-gray-500">
        Le ménage est automatiquement rattaché à cette maison.
    </p>

</div>


{{-- ========================================================= --}}
{{-- NUMÉRO DU MÉNAGE --}}
{{-- ========================================================= --}}

@if(isset($menage))

    <div class="mb-6">

        <label
            for="numeroMenage"
            class="block text-sm font-medium text-gray-700"
        >
            Numéro du ménage
        </label>

        <input
            type="text"
            id="numeroMenage"
            value="{{ $menage->numeroMenage }}"
            readonly
            class="mt-1 block w-full rounded-lg
                   border-gray-300
                   bg-gray-100
                   text-gray-700"
        >

        <p class="mt-1 text-xs text-gray-500">
            Ce numéro est attribué automatiquement par le système.
        </p>

    </div>

@endif


{{-- ========================================================= --}}
{{-- CHEF DE MÉNAGE --}}
{{-- ========================================================= --}}

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- Nom --}}
    <div>

        <label
            for="nomChef"
            class="block text-sm font-medium text-gray-700"
        >
            Nom du chef de ménage
            <span class="text-red-500">*</span>
        </label>

        <input
            type="text"
            id="nomChef"
            name="nomChef"
            value="{{ old('nomChef', $menage->nomChef ?? '') }}"
            required
            maxlength="255"
            class="mt-1 block w-full rounded-lg
                   border-gray-300
                   focus:border-green-500
                   focus:ring-green-500"
            placeholder="Ex. : KOFFI"
        >

        @error('nomChef')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>


    {{-- Prénom --}}
    <div>

        <label
            for="prenomChef"
            class="block text-sm font-medium text-gray-700"
        >
            Prénom du chef de ménage
        </label>

        <input
            type="text"
            id="prenomChef"
            name="prenomChef"
            value="{{ old('prenomChef', $menage->prenomChef ?? '') }}"
            maxlength="255"
            class="mt-1 block w-full rounded-lg
                   border-gray-300
                   focus:border-green-500
                   focus:ring-green-500"
            placeholder="Ex. : Komlan"
        >

        @error('prenomChef')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>

</div>


{{-- ========================================================= --}}
{{-- SEXE DU CHEF DE MÉNAGE --}}
{{-- ========================================================= --}}

<div class="mt-6">

    <label class="block text-sm font-medium text-gray-700 mb-3">

        Sexe du chef de ménage

        <span class="text-red-500">*</span>

    </label>

    <div class="flex items-center gap-8">

        {{-- Homme --}}
        <label class="inline-flex items-center">

            <input
                type="radio"
                name="sexeChef"
                value="M"
                required
                class="text-green-600
                       border-gray-300
                       focus:ring-green-500"
                @checked(
                    old(
                        'sexeChef',
                        $menage->sexeChef ?? ''
                    ) === 'M'
                )
            >

            <span class="ml-2 text-sm text-gray-700">
                Homme
            </span>

        </label>


        {{-- Femme --}}
        <label class="inline-flex items-center">

            <input
                type="radio"
                name="sexeChef"
                value="F"
                class="text-green-600
                       border-gray-300
                       focus:ring-green-500"
                @checked(
                    old(
                        'sexeChef',
                        $menage->sexeChef ?? ''
                    ) === 'F'
                )
            >

            <span class="ml-2 text-sm text-gray-700">
                Femme
            </span>

        </label>

    </div>

    @error('sexeChef')
        <p class="mt-1 text-sm text-red-600">
            {{ $message }}
        </p>
    @enderror

</div>


{{-- ========================================================= --}}
{{-- COMPOSITION DU MÉNAGE --}}
{{-- ========================================================= --}}

<div class="mt-8">

    <h2 class="text-lg font-semibold text-slate-800">
        Composition du ménage
    </h2>

    <p class="mt-1 text-sm text-slate-500">
        Indiquez le nombre de personnes composant le ménage.
    </p>

</div>


<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-5">

    {{-- Hommes --}}
    <div>

        <label
            for="nombreHommes"
            class="block text-sm font-medium text-gray-700"
        >
            Hommes
            <span class="text-red-500">*</span>
        </label>

        <input
            type="number"
            id="nombreHommes"
            name="nombreHommes"
            min="0"
            value="{{ old('nombreHommes', $menage->nombreHommes ?? 0) }}"
            required
            class="mt-1 block w-full rounded-lg
                   border-gray-300
                   focus:border-green-500
                   focus:ring-green-500"
        >

        @error('nombreHommes')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>


    {{-- Femmes --}}
    <div>

        <label
            for="nombreFemmes"
            class="block text-sm font-medium text-gray-700"
        >
            Femmes
            <span class="text-red-500">*</span>
        </label>

        <input
            type="number"
            id="nombreFemmes"
            name="nombreFemmes"
            min="0"
            value="{{ old('nombreFemmes', $menage->nombreFemmes ?? 0) }}"
            required
            class="mt-1 block w-full rounded-lg
                   border-gray-300
                   focus:border-green-500
                   focus:ring-green-500"
        >

        @error('nombreFemmes')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>


    {{-- Garçons --}}
    <div>

        <label
            for="nombreGarcons"
            class="block text-sm font-medium text-gray-700"
        >
            Garçons
            <span class="text-red-500">*</span>
        </label>

        <input
            type="number"
            id="nombreGarcons"
            name="nombreGarcons"
            min="0"
            value="{{ old('nombreGarcons', $menage->nombreGarcons ?? 0) }}"
            required
            class="mt-1 block w-full rounded-lg
                   border-gray-300
                   focus:border-green-500
                   focus:ring-green-500"
        >

        @error('nombreGarcons')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>


    {{-- Filles --}}
    <div>

        <label
            for="nombreFilles"
            class="block text-sm font-medium text-gray-700"
        >
            Filles
            <span class="text-red-500">*</span>
        </label>

        <input
            type="number"
            id="nombreFilles"
            name="nombreFilles"
            min="0"
            value="{{ old('nombreFilles', $menage->nombreFilles ?? 0) }}"
            required
            class="mt-1 block w-full rounded-lg
                   border-gray-300
                   focus:border-green-500
                   focus:ring-green-500"
        >

        @error('nombreFilles')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>

</div>


{{-- ========================================================= --}}
{{-- RÉCAPITULATIF DU NOMBRE DE PERSONNES --}}
{{-- ========================================================= --}}

<div
    class="mt-5
           rounded-lg
           bg-slate-50
           border border-slate-200
           px-5 py-4"
>

    <div class="flex items-center justify-between">

        <div>

            <p class="text-sm font-medium text-slate-700">
                Nombre total de personnes
            </p>

            <p class="text-xs text-slate-500 mt-1">
                Calculé automatiquement à partir de la composition du ménage.
            </p>

        </div>

        <span
            id="nombreTotalPersonnes"
            class="text-xl font-bold text-slate-800"
        >
            {{ $menage->nombrePersonnes ?? 0 }}
        </span>

    </div>

</div>


{{-- ========================================================= --}}
{{-- EXPLOITATION AGRICOLE --}}
{{-- ========================================================= --}}

<div class="mt-8">

    <label class="block text-sm font-medium text-gray-700 mb-3">

        Le ménage possède-t-il une exploitation agricole ?

        <span class="text-red-500">*</span>

    </label>

    <div class="flex items-center gap-8">

        {{-- Oui --}}
        <label class="inline-flex items-center">

            <input
                type="radio"
                name="possedeExploitation"
                value="1"
                required
                class="text-green-600
                       border-gray-300
                       focus:ring-green-500"
                @checked(
                    old(
                        'possedeExploitation',
                        $menage->possedeExploitation ?? false
                    ) == true
                )
            >

            <span class="ml-2 text-sm text-gray-700">
                Oui
            </span>

        </label>


        {{-- Non --}}
        <label class="inline-flex items-center">

            <input
                type="radio"
                name="possedeExploitation"
                value="0"
                class="text-green-600
                       border-gray-300
                       focus:ring-green-500"
                @checked(
                    old(
                        'possedeExploitation',
                        $menage->possedeExploitation ?? false
                    ) == false
                )
            >

            <span class="ml-2 text-sm text-gray-700">
                Non
            </span>

        </label>

    </div>

    @error('possedeExploitation')
        <p class="mt-1 text-sm text-red-600">
            {{ $message }}
        </p>
    @enderror

</div>


{{-- ========================================================= --}}
{{-- OBSERVATIONS --}}
{{-- ========================================================= --}}

<div class="mt-6">

    <label
        for="observations"
        class="block text-sm font-medium text-gray-700"
    >
        Observations
    </label>

    <textarea
        id="observations"
        name="observations"
        rows="4"
        class="mt-1 block w-full rounded-lg
               border-gray-300
               focus:border-green-500
               focus:ring-green-500"
        placeholder="Observations éventuelles sur le ménage..."
    >{{ old('observations', $menage->observations ?? '') }}</textarea>

    @error('observations')
        <p class="mt-1 text-sm text-red-600">
            {{ $message }}
        </p>
    @enderror

</div>


{{-- ========================================================= --}}
{{-- STATUT --}}
{{-- ========================================================= --}}

<div class="mt-6">

    <label
        for="statut"
        class="block text-sm font-medium text-gray-700"
    >
        Statut du ménage
        <span class="text-red-500">*</span>
    </label>

    <select
        id="statut"
        name="statut"
        required
        class="mt-1 block w-full rounded-lg
               border-gray-300
               focus:border-green-500
               focus:ring-green-500"
    >

        @php
            $statut = old(
                'statut',
                $menage->statut ?? 'brouillon'
            );
        @endphp

        <option
            value="brouillon"
            @selected($statut === 'brouillon')
        >
            Brouillon
        </option>

        <option
            value="en_cours"
            @selected($statut === 'en_cours')
        >
            En cours
        </option>

        <option
            value="terminee"
            @selected($statut === 'terminee')
        >
            Terminée
        </option>

    </select>

    @error('statut')
        <p class="mt-1 text-sm text-red-600">
            {{ $message }}
        </p>
    @enderror

</div>


{{-- ========================================================= --}}
{{-- BOUTONS --}}
{{-- ========================================================= --}}

<div class="mt-8 flex justify-end gap-3">

    <a
        href="{{
            isset($maison)
                ? route('maisons.show', $maison)
                : route('menages.show', $menage)
        }}"
        class="px-5 py-2 rounded-lg
               border border-gray-300
               text-gray-700
               hover:bg-gray-100"
    >
        Annuler
    </a>

    <button
        type="submit"
        class="px-6 py-2 rounded-lg
               bg-green-600
               text-white
               font-medium
               hover:bg-green-700"
    >
        {{ isset($menage)
            ? 'Mettre à jour'
            : 'Enregistrer le ménage'
        }}
    </button>

</div>


{{-- ========================================================= --}}
{{-- CALCUL AUTOMATIQUE DU TOTAL --}}
{{-- ========================================================= --}}

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const champs = [
        'nombreHommes',
        'nombreFemmes',
        'nombreGarcons',
        'nombreFilles'
    ];

    const total = document.getElementById(
        'nombreTotalPersonnes'
    );

    function calculerTotal() {

        let somme = 0;

        champs.forEach(function (nom) {

            const champ = document.getElementById(nom);

            if (champ) {
                somme += parseInt(champ.value || 0, 10);
            }

        });

        total.textContent = somme;
    }

    champs.forEach(function (nom) {

        const champ = document.getElementById(nom);

        if (champ) {
            champ.addEventListener(
                'input',
                calculerTotal
            );
        }

    });

    calculerTotal();

});

</script>

@endpush
