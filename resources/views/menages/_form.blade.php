@csrf


{{-- ================================================= --}}
{{-- MAISON RATTACHÉE --}}
{{-- ================================================= --}}

<div class="mb-6">

    <label
        for="maison"
        class="block text-sm font-medium text-gray-700"
    >
        Maison
        <span class="text-red-500">*</span>
    </label>

    <input
        type="text"
        id="maison"
        value="{{ $menage->maison->numeroMaison ?? $maison->numeroMaison }}"
        readonly
        class="mt-1 block w-full rounded-lg
               border-gray-300 bg-gray-100"
    >

    <p class="mt-1 text-xs text-gray-500">
        Le ménage est automatiquement rattaché à cette maison.
    </p>

</div>


{{-- ================================================= --}}
{{-- INFORMATIONS DU MÉNAGE --}}
{{-- ================================================= --}}

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">


    {{-- Numéro ménage --}}
    <div>

        <label
            for="numeroMenage"
            class="block text-sm font-medium text-gray-700"
        >
            Numéro du ménage
        </label>

        <input
            type="text"
            id="numeroMenage"
            value="{{ old(
                'numeroMenage',
                $menage->numeroMenage ?? 'Généré automatiquement'
            ) }}"
            readonly
            class="mt-1 block w-full rounded-lg
                   border-gray-300 bg-gray-100"
        >

        <p class="mt-1 text-xs text-gray-500">
            Ce numéro est généré automatiquement par le système.
        </p>

    </div>


    {{-- Chef de ménage --}}
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
            class="mt-1 block w-full rounded-lg
                   border-gray-300
                   focus:border-green-500
                   focus:ring-green-500"
            placeholder="Ex : Koffi Komlan"
        >

        @error('nomChef')
            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>


</div>


{{-- ================================================= --}}
{{-- NOMBRE DE PERSONNES --}}
{{-- ================================================= --}}

<div class="mt-6">

    <label
        for="nombrePersonnes"
        class="block text-sm font-medium text-gray-700"
    >
        Nombre de personnes dans le ménage
        <span class="text-red-500">*</span>
    </label>

    <input
        type="number"
        id="nombrePersonnes"
        name="nombrePersonnes"
        min="1"
        value="{{ old(
            'nombrePersonnes',
            $menage->nombrePersonnes ?? 1
        ) }}"
        required
        class="mt-1 block w-full md:w-1/2 rounded-lg
               border-gray-300
               focus:border-green-500
               focus:ring-green-500"
    >

    @error('nombrePersonnes')
        <p class="mt-1 text-sm text-red-600">
            {{ $message }}
        </p>
    @enderror

</div>


{{-- ================================================= --}}
{{-- ACTIVITÉ AGRICOLE --}}
{{-- ================================================= --}}

<div class="mt-6">

    <label class="block text-sm font-medium text-gray-700 mb-3">
        Le ménage possède-t-il un champ ?
    </label>

    <div class="flex items-center gap-6">

        <label class="inline-flex items-center">

            <input
                type="radio"
                name="aChamp"
                value="1"
                class="text-green-600
                       border-gray-300
                       focus:ring-green-500"
                @checked(
                    old(
                        'aChamp',
                        $menage->aChamp ?? false
                    )
                )
            >

            <span class="ml-2 text-sm text-gray-700">
                Oui
            </span>

        </label>


        <label class="inline-flex items-center">

            <input
                type="radio"
                name="aChamp"
                value="0"
                class="text-green-600
                       border-gray-300
                       focus:ring-green-500"
                @checked(
                    !old(
                        'aChamp',
                        $menage->aChamp ?? false
                    )
                )
            >

            <span class="ml-2 text-sm text-gray-700">
                Non
            </span>

        </label>

    </div>

    @error('aChamp')
        <p class="mt-1 text-sm text-red-600">
            {{ $message }}
        </p>
    @enderror

</div>


{{-- ================================================= --}}
{{-- BOUTONS --}}
{{-- ================================================= --}}

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
               hover:bg-green-700"
    >

        {{ isset($menage) ? 'Mettre à jour' : 'Enregistrer le ménage' }}

    </button>

</div>