@csrf

@if(isset($affectation))
    @method('PUT')
@endif


<div class="grid grid-cols-1 md:grid-cols-2 gap-6">


    <div>

        <label class="block text-sm font-medium text-gray-700 mb-2">
            Agent recenseur
        </label>


        <select
            name="user_id"
            class="w-full rounded-xl border-gray-300 focus:ring-green-500"
        >

            <option value="">
                Sélectionner un agent
            </option>


            @foreach($agents as $agent)

                <option value="{{ $agent->id }}"
                    @selected(
                        old(
                            'user_id',
                            $affectation->user_id ?? ''
                        ) == $agent->id
                    )
                >

                    {{ $agent->name }}

                </option>

            @endforeach


        </select>


        @error('user_id')
            <p class="text-red-500 text-sm mt-1">
                {{ $message }}
            </p>
        @enderror

    </div>




    <div>

        <label class="block text-sm font-medium text-gray-700 mb-2">
            Campagne
        </label>


        <select
            name="campagne_id"
            class="w-full rounded-xl border-gray-300"
        >

            <option value="">
                Sélectionner une campagne
            </option>


            @foreach($campagnes as $campagne)

                <option value="{{ $campagne->idCampagne }}"
                    @selected(
                        old(
                            'campagne_id',
                            $affectation->campagne_id ?? ''
                        ) == $campagne->idCampagne
                    )
                >

                    {{ $campagne->nom }}

                </option>

            @endforeach


        </select>


        @error('campagne_id')
            <p class="text-red-500 text-sm mt-1">
                {{ $message }}
            </p>
        @enderror


    </div>





    <div>

        <label class="block text-sm font-medium text-gray-700 mb-2">
            Village
        </label>


        <select
            name="village_id"
            class="w-full rounded-xl border-gray-300"
        >

            <option value="">
                Sélectionner un village
            </option>


            @foreach($villages as $village)

                <option value="{{ $village->idVillage }}"
                    @selected(
                        old(
                            'village_id',
                            $affectation->village_id ?? ''
                        ) == $village->idVillage
                    )
                >

                    {{ $village->nom }}

                </option>

            @endforeach


        </select>


        @error('village_id')
            <p class="text-red-500 text-sm mt-1">
                {{ $message }}
            </p>
        @enderror

    </div>





    <div>

        <label class="block text-sm font-medium text-gray-700 mb-2">
            Statut
        </label>


        <select
            name="statut"
            class="w-full rounded-xl border-gray-300"
        >

            @foreach([
                'ACTIVE'=>'Active',
                'SUSPENDUE'=>'Suspendue',
                'TERMINEE'=>'Terminée'
            ] as $key=>$label)

                <option value="{{ $key }}"
                    @selected(
                        old(
                            'statut',
                            $affectation->statut ?? 'ACTIVE'
                        ) == $key
                    )
                >

                    {{ $label }}

                </option>

            @endforeach


        </select>


    </div>


</div>



<div class="mt-8 flex justify-end gap-3">


    <a href="{{ route('affectations.index') }}"
       class="px-5 py-3 rounded-xl bg-gray-200">

        Annuler

    </a>



    <button
        type="submit"
        class="px-5 py-3 rounded-xl bg-green-600 text-white hover:bg-green-700"
    >

        {{ isset($affectation) ? 'Modifier' : 'Enregistrer' }}

    </button>


</div>