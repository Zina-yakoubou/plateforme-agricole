@csrf


<div class="grid grid-cols-1 md:grid-cols-2 gap-6">



    {{-- Code RNA --}}
    <div>

        <label for="codeRNA"
               class="block text-sm font-medium text-gray-700">

            Code RNA
            <span class="text-red-500">*</span>

        </label>


        <input
            type="text"
            name="codeRNA"
            id="codeRNA"
            value="{{ old('codeRNA', $campagne->codeRNA ?? '') }}"
            class="mt-1 block w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">


        @error('codeRNA')

            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>

        @enderror

    </div>





    {{-- Libellé --}}
    <div>

        <label for="libelle"
               class="block text-sm font-medium text-gray-700">

            Libellé de la campagne
            <span class="text-red-500">*</span>

        </label>


        <input
            type="text"
            name="libelle"
            id="libelle"
            value="{{ old('libelle', $campagne->libelle ?? '') }}"
            class="mt-1 block w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">


        @error('libelle')

            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>

        @enderror


    </div>





    {{-- Date début --}}
    <div>

        <label for="dateDebut"
               class="block text-sm font-medium text-gray-700">

            Date de début
            <span class="text-red-500">*</span>

        </label>


        <input
            type="date"
            name="dateDebut"
            id="dateDebut"
            value="{{ old('dateDebut', isset($campagne) ? $campagne->dateDebut?->format('Y-m-d') : '') }}"
            class="mt-1 block w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">


        @error('dateDebut')

            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>

        @enderror


    </div>





    {{-- Date fin --}}
    <div>

        <label for="dateFin"
               class="block text-sm text-gray-700">

            Date de fin

        </label>


        <input
            type="date"
            name="dateFin"
            id="dateFin"
            value="{{ old('dateFin', isset($campagne) ? $campagne->dateFin?->format('Y-m-d') : '') }}"
            class="mt-1 block w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">


        @error('dateFin')

            <p class="mt-1 text-sm text-red-600">
                {{ $message }}
            </p>

        @enderror


    </div>







    {{-- Statut --}}
    <div>

        <label for="statut"
               class="block text-sm font-medium text-gray-700">

            Statut
            <span class="text-red-500">*</span>

        </label>


        <select
            name="statut"
            id="statut"
            class="mt-1 block w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">


            @foreach([
                'Préparation',
                'Active',
                'Clôturée',
                'Archivée'
            ] as $statut)


                <option value="{{ $statut }}"
                    @selected(old('statut', $campagne->statut ?? 'Préparation') == $statut)>

                    {{ $statut }}

                </option>


            @endforeach


        </select>


    </div>







    {{-- Responsable --}}
    <div>


        <label for="responsable_id"
               class="block text-sm font-medium text-gray-700">

            Responsable de campagne

        </label>


        <select
            name="responsable_id"
            id="responsable_id"
            class="mt-1 block w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">


            <option value="">
                -- Aucun responsable --
            </option>



            @foreach($responsables as $responsable)


                <option
                    value="{{ $responsable->id }}"
                    @selected(old('responsable_id', $campagne->responsable_id ?? '') == $responsable->id)>


                    {{ $responsable->name }}


                </option>


            @endforeach



        </select>


        @error('responsable_id')

            <p class="mt-1 text-sm text-red-600">

                {{ $message }}

            </p>

        @enderror


    </div>




</div>







{{-- Options --}}
<div class="mt-6 space-y-4">


    <div class="flex items-center">


        <input
            type="checkbox"
            name="estOfficielle"
            value="1"
            @checked(old('estOfficielle', $campagne->estOfficielle ?? false))
            class="rounded border-gray-300 text-green-600">


        <label class="ml-2 text-sm text-gray-700">

            Campagne officielle

        </label>


    </div>




    <div class="flex items-center">


        <input
            type="checkbox"
            name="active"
            value="1"
            @checked(old('active', $campagne->active ?? false))
            class="rounded border-gray-300 text-green-600">


        <label class="ml-2 text-sm text-gray-700">

            Définir comme campagne active

        </label>


    </div>


</div>








{{-- Boutons --}}
<div class="mt-8 flex justify-end gap-3">


    <a href="{{ route('campagnes.index') }}"
       class="px-5 py-2 rounded-lg border border-gray-300 hover:bg-gray-100">

        Annuler

    </a>



    <button type="submit"
            class="px-6 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">


        {{ isset($campagne) ? 'Modifier' : 'Enregistrer' }}


    </button>


</div>