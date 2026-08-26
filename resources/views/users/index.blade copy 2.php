@extends('layouts.app')

@section('content')

{{-- Empêche le flash des modals avant qu'Alpine ne soit initialisé --}}
<style>[x-cloak] { display: none !important; }</style>

<div
    x-data="{
        modal: @if($errors->any() && old('_form') === 'edit') 'edit' @elseif($errors->any()) 'create' @else null @endif,
        selectedUser: @if($errors->any() && old('_form') === 'edit')
            {
                id: {{ (int) old('id') }},
                name: @js(old('name')),
                email: @js(old('email')),
                login: @js(old('login')),
                telephone: @js(old('telephone')),
                role_id: {{ (int) old('role_id') }},
                statut: {{ old('statut') ? 'true' : 'false' }}
            }
        @else
            null
        @endif,

        openModal(type, user = null) {
            this.modal = type;
            this.selectedUser = user;
        },

        closeModal() {
            this.modal = null;
            this.selectedUser = null;
        }
    }"
    @keydown.escape.window="closeModal()"
>

    {{-- ================================================= --}}
    {{-- EN-TÊTE --}}
    {{-- ================================================= --}}

    <div class="flex items-center justify-between mb-6">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Gestion des utilisateurs
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Administration des comptes utilisateurs.
            </p>
        </div>

        {{-- OUVRIR MODAL CRÉATION --}}
        <button
            type="button"
            @click="openModal('create')"
            class="px-5 py-2 rounded-lg bg-green-600 text-white
                   hover:bg-green-700 transition"
        >
            + Nouvel utilisateur
        </button>

    </div>

    {{-- ================================================= --}}
    {{-- MESSAGE SUCCÈS --}}
    {{-- ================================================= --}}

    @if(session('success'))
        <div class="mb-5 rounded-lg bg-green-100 border border-green-300 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- ================================================= --}}
    {{-- ERREURS --}}
    {{-- ================================================= --}}

    @if($errors->any())
        <div class="mb-5 rounded-lg bg-red-100 text-red-700 px-4 py-3">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ================================================= --}}
    {{-- RECHERCHE --}}
    {{-- ================================================= --}}

    <div class="bg-white rounded-xl shadow p-4 mb-6">
        <form method="GET" action="{{ route('users.index') }}">
            <div class="flex gap-3">
                <input
                    type="text"
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Rechercher par nom, email, login..."
                    class="flex-1 rounded-lg border-gray-300
                           focus:border-green-500
                           focus:ring-green-500"
                >
                <button
                    type="submit"
                    class="px-5 py-2 rounded-lg bg-slate-800 text-white hover:bg-slate-900"
                >
                    Rechercher
                </button>
            </div>
        </form>
    </div>

    {{-- ================================================= --}}
    {{-- TABLEAU --}}
    {{-- ================================================= --}}

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-left">Utilisateur</th>
                        <th class="px-6 py-4 text-left">Login</th>
                        <th class="px-6 py-4 text-left">Téléphone</th>
                        <th class="px-6 py-4 text-left">Rôle</th>
                        <th class="px-6 py-4 text-center">Statut</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50">

                            {{-- UTILISATEUR --}}
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-800">
                                    {{ $user->name }}
                                </div>
                                <div class="text-gray-500 text-xs">
                                    {{ $user->email }}
                                </div>
                            </td>

                            {{-- LOGIN --}}
                            <td class="px-6 py-4">
                                {{ $user->login }}
                            </td>

                            {{-- TÉLÉPHONE --}}
                            <td class="px-6 py-4">
                                {{ $user->telephone ?? '-' }}
                            </td>

                            {{-- RÔLE --}}
                            <td class="px-6 py-4">
                                {{ $user->role->nom ?? '-' }}
                            </td>

                            {{-- STATUT --}}
                            <td class="px-6 py-4 text-center">
                                @if($user->statut)
                                    <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-700">
                                        Actif
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs bg-red-100 text-red-700">
                                        Inactif
                                    </span>
                                @endif
                            </td>

                            {{-- ACTIONS --}}
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">

                                    {{-- VOIR --}}
                                    <a
                                        href="{{ route('users.show', $user) }}"
                                        class="px-3 py-1 rounded-lg
                                               bg-slate-700 text-white
                                               hover:bg-slate-800"
                                    >
                                        Voir
                                    </a>

                                    {{-- MODIFIER --}}
                                    <button
                                        type="button"
                                        @click="openModal('edit', {
                                            id: {{ $user->id }},
                                            name: @js($user->name),
                                            email: @js($user->email),
                                            login: @js($user->login),
                                            telephone: @js($user->telephone),
                                            role_id: {{ $user->role_id ?? 'null' }},
                                            statut: {{ $user->statut ? 'true' : 'false' }}
                                        })"
                                        class="px-3 py-1 rounded-lg
                                               bg-blue-600 text-white
                                               hover:bg-blue-700"
                                    >
                                        Modifier
                                    </button>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                Aucun utilisateur trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-5">
            {{ $users->links() }}
        </div>
    </div>

    {{-- ================================================= --}}
    {{-- MODAL CRÉATION --}}
    {{-- ================================================= --}}

    <div
        x-show="modal === 'create'"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
    >
        <div class="absolute inset-0 bg-black/50" @click="closeModal()"></div>

        <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl max-h-[90vh] overflow-y-auto" @click.stop>

            <div class="flex items-center justify-between px-6 py-4 border-b">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Nouvel utilisateur</h2>
                    <p class="text-sm text-slate-500">Créer un nouveau compte utilisateur.</p>
                </div>
                <button type="button" @click="closeModal()" class="text-gray-400 hover:text-gray-700 text-xl">
                    &times;
                </button>
            </div>

            <form method="POST" action="{{ route('users.store') }}" class="p-6">
                @csrf
                <input type="hidden" name="_form" value="create">

                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nom complet
                    </label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('_form') === 'create' ? old('name') : '' }}"
                        required
                        class="w-full rounded-xl border-gray-300
                               focus:border-green-500
                               focus:ring-green-500"
                        placeholder="Ex : Ama Koffi"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('_form') === 'create' ? old('email') : '' }}"
                        required
                        class="w-full rounded-xl border-gray-300
                               focus:border-green-500
                               focus:ring-green-500"
                        placeholder="Ex : ama.koffi@example.com"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Login
                    </label>
                    <input
                        type="text"
                        name="login"
                        value="{{ old('_form') === 'create' ? old('login') : '' }}"
                        required
                        class="w-full rounded-xl border-gray-300
                               focus:border-green-500
                               focus:ring-green-500"
                        placeholder="Ex : akoffi"
                    >
                    @error('login')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Téléphone
                    </label>
                    <input
                        type="text"
                        name="telephone"
                        value="{{ old('_form') === 'create' ? old('telephone') : '' }}"
                        class="w-full rounded-xl border-gray-300
                               focus:border-green-500
                               focus:ring-green-500"
                        placeholder="Ex : 90 00 00 00"
                    >
                    @error('telephone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Mot de passe
                    </label>
                    <input
                        type="password"
                        name="password"
                        required
                        class="w-full rounded-xl border-gray-300
                               focus:border-green-500
                               focus:ring-green-500"
                        placeholder="Minimum 8 caractères"
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Confirmer le mot de passe
                    </label>
                    <input
                        type="password"
                        name="password_confirmation"
                        required
                        class="w-full rounded-xl border-gray-300
                               focus:border-green-500
                               focus:ring-green-500"
                    >
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Rôle
                    </label>
                    <select
                        name="role_id"
                        required
                        class="w-full rounded-xl border-gray-300
                               focus:border-green-500
                               focus:ring-green-500"
                    >
                        <option value="">Sélectionner un rôle</option>
                        @foreach($roles as $role)
                            <option
                                value="{{ $role->id }}"
                                @selected(old('_form') === 'create' && old('role_id') == $role->id)
                            >
                                {{ $role->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="inline-flex items-center gap-2">
                        <input
                            type="checkbox"
                            name="statut"
                            value="1"
                            @checked(old('_form') === 'create' ? old('statut', true) : true)
                            class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                        >
                        <span class="text-sm font-medium text-gray-700">Compte actif</span>
                    </label>
                </div>

                <div class="flex justify-end gap-3">
                    <button
                        type="button"
                        @click="closeModal()"
                        class="px-5 py-2 rounded-xl bg-gray-200 text-gray-700 hover:bg-gray-300"
                    >
                        Annuler
                    </button>
                    <button
                        type="submit"
                        class="px-5 py-2 rounded-xl bg-green-600 text-white hover:bg-green-700"
                    >
                        Enregistrer
                    </button>
                </div>
            </form>

        </div>
    </div>

    {{-- ================================================= --}}
    {{-- MODAL MODIFICATION --}}
    {{-- ================================================= --}}

    <div
        x-show="modal === 'edit'"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
    >
        <div class="absolute inset-0 bg-black/50" @click="closeModal()"></div>

        <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl max-h-[90vh] overflow-y-auto" @click.stop>

            <div class="flex items-center justify-between px-6 py-4 border-b">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Modifier l'utilisateur</h2>
                    <p class="text-sm text-slate-500">Modifier les informations du compte.</p>
                </div>
                <button type="button" @click="closeModal()" class="text-gray-400 hover:text-gray-700 text-xl">
                    &times;
                </button>
            </div>

            <form
                method="POST"
                class="p-6"
                :action="`{{ url('users') }}/${selectedUser?.id}`"
            >
                @csrf
                @method('PUT')
                <input type="hidden" name="_form" value="edit">
                <input type="hidden" name="id" :value="selectedUser?.id">

                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nom complet
                    </label>
                    <input
                        type="text"
                        name="name"
                        x-bind:value="selectedUser?.name ?? ''"
                        required
                        class="w-full rounded-xl border-gray-300
                               focus:border-green-500
                               focus:ring-green-500"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input
                        type="email"
                        name="email"
                        x-bind:value="selectedUser?.email ?? ''"
                        required
                        class="w-full rounded-xl border-gray-300
                               focus:border-green-500
                               focus:ring-green-500"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Login
                    </label>
                    <input
                        type="text"
                        name="login"
                        x-bind:value="selectedUser?.login ?? ''"
                        required
                        class="w-full rounded-xl border-gray-300
                               focus:border-green-500
                               focus:ring-green-500"
                    >
                    @error('login')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Téléphone
                    </label>
                    <input
                        type="text"
                        name="telephone"
                        x-bind:value="selectedUser?.telephone ?? ''"
                        class="w-full rounded-xl border-gray-300
                               focus:border-green-500
                               focus:ring-green-500"
                    >
                    @error('telephone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nouveau mot de passe <span class="text-gray-400 font-normal">(laisser vide pour ne pas changer)</span>
                    </label>
                    <input
                        type="password"
                        name="password"
                        class="w-full rounded-xl border-gray-300
                               focus:border-green-500
                               focus:ring-green-500"
                        placeholder="••••••••"
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Confirmer le nouveau mot de passe
                    </label>
                    <input
                        type="password"
                        name="password_confirmation"
                        class="w-full rounded-xl border-gray-300
                               focus:border-green-500
                               focus:ring-green-500"
                    >
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Rôle
                    </label>
                    <select
                        name="role_id"
                        required
                        x-bind:value="selectedUser?.role_id ?? ''"
                        class="w-full rounded-xl border-gray-300
                               focus:border-green-500
                               focus:ring-green-500"
                    >
                        <option value="">Sélectionner un rôle</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">
                                {{ $role->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="inline-flex items-center gap-2">
                        <input
                            type="checkbox"
                            name="statut"
                            value="1"
                            x-bind:checked="selectedUser?.statut"
                            class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                        >
                        <span class="text-sm font-medium text-gray-700">Compte actif</span>
                    </label>
                </div>

                <div class="flex justify-end gap-3">
                    <button
                        type="button"
                        @click="closeModal()"
                        class="px-5 py-2 rounded-xl bg-gray-200 text-gray-700 hover:bg-gray-300"
                    >
                        Annuler
                    </button>
                    <button
                        type="submit"
                        class="px-5 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700"
                    >
                        Enregistrer les modifications
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>

@endsection