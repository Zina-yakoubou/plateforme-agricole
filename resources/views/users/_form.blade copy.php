@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- Nom --}}
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">
            Nom complet <span class="text-red-500">*</span>
        </label>

        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $user->name ?? '') }}"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">

        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>


    {{-- Login --}}
    <div>
        <label for="login" class="block text-sm font-medium text-gray-700">
            Login <span class="text-red-500">*</span>
        </label>

        <input
            type="text"
            id="login"
            name="login"
            value="{{ old('login', $user->login ?? '') }}"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">

        @error('login')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>


    {{-- Email --}}
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">
            Adresse e-mail <span class="text-red-500">*</span>
        </label>

        <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email', $user->email ?? '') }}"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">

        @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>


    {{-- Téléphone --}}
    <div>
        <label for="telephone" class="block text-sm font-medium text-gray-700">
            Téléphone
        </label>

        <input
            type="text"
            id="telephone"
            name="telephone"
            value="{{ old('telephone', $user->telephone ?? '') }}"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">

        @error('telephone')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>


    {{-- Rôle --}}
    <div>

        <label for="role_id" class="block text-sm font-medium text-gray-700">
            Rôle <span class="text-red-500">*</span>
        </label>

        {{-- Cas : création depuis une préfecture --}}
        @if(isset($role))

            <input
                type="hidden"
                name="role_id"
                value="{{ $role->idRole }}">

            <input
                type="text"
                value="{{ $role->nom }}"
                readonly
                class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-100">

        {{-- Cas normal --}}
        @else

            <select
                id="role_id"
                name="role_id"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">

                <option value="">Sélectionner un rôle</option>

                @foreach($roles as $item)

                    <option
                        value="{{ $item->idRole }}"
                        @selected(old('role_id', $user->role_id ?? '') == $item->idRole)>

                        {{ $item->nom }}

                    </option>

                @endforeach

            </select>

        @endif

        @error('role_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

    </div>


    {{-- Préfecture --}}
    <div
        id="bloc-prefecture"
        @if(!isset($prefecture))
            style="display:none;"
        @endif>

        <label for="prefecture_id" class="block text-sm font-medium text-gray-700">
            Préfecture
        </label>

        {{-- Cas : création depuis le détail d'une préfecture --}}
        @if(isset($prefecture))

            <input
                type="hidden"
                name="prefecture_id"
                value="{{ $prefecture->idPrefecture }}">

            <input
                type="text"
                value="{{ $prefecture->nom }}"
                readonly
                class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-100">

        {{-- Cas : création depuis le menu --}}
        @else

            <select
                id="prefecture_id"
                name="prefecture_id"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">

                <option value="">Sélectionner une préfecture</option>

                @foreach($prefectures as $item)

                    <option
                        value="{{ $item->idPrefecture }}"
                        @selected(old('prefecture_id') == $item->idPrefecture)>

                        {{ $item->nom }}

                    </option>

                @endforeach

            </select>

        @endif

        @error('prefecture_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

    </div>


    {{-- Statut --}}
    @isset($user)

    <div>

        <label for="statut" class="block text-sm font-medium text-gray-700">
            Statut
        </label>

        <select
            id="statut"
            name="statut"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">

            <option value="1" @selected(old('statut', $user->statut)==1)>
                Actif
            </option>

            <option value="0" @selected(old('statut', $user->statut)==0)>
                Inactif
            </option>

        </select>

    </div>

    @endisset


    {{-- Mot de passe --}}
    <div>

        <label for="password" class="block text-sm font-medium text-gray-700">

            @isset($user)
                Nouveau mot de passe
            @else
                Mot de passe <span class="text-red-500">*</span>
            @endisset

        </label>

        <input
            type="password"
            id="password"
            name="password"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">

        @error('password')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

    </div>


    {{-- Confirmation --}}
    <div>

        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
            Confirmation du mot de passe
        </label>

        <input
            type="password"
            id="password_confirmation"
            name="password_confirmation"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">

    </div>

</div>


<div class="mt-8 flex justify-end gap-3">

    <a href="{{ route('users.index') }}"
       class="rounded-lg border border-gray-300 px-5 py-2 hover:bg-gray-100">

        Annuler

    </a>

    <button
        type="submit"
        class="rounded-lg bg-green-600 px-6 py-2 font-semibold text-white hover:bg-green-700">

        {{ isset($user) ? 'Mettre à jour' : 'Enregistrer' }}

    </button>

</div>


@if(!isset($role))

<script>

document.addEventListener('DOMContentLoaded', function () {

    const roleSelect = document.getElementById('role_id');
    const blocPrefecture = document.getElementById('bloc-prefecture');

    function togglePrefecture() {

        const texte = roleSelect.options[roleSelect.selectedIndex]?.text.trim();

        if (texte === 'Directeur préfectoral') {

            blocPrefecture.style.display = 'block';

        } else {

            blocPrefecture.style.display = 'none';

            const prefecture = document.getElementById('prefecture_id');

            if(prefecture){
                prefecture.value = '';
            }

        }

    }

    togglePrefecture();

    roleSelect.addEventListener('change', togglePrefecture);

});

</script>

@endif