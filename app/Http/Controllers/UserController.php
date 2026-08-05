<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Afficher la liste des utilisateurs.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $users = User::with('role')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('login', 'like', "%{$search}%")
                        ->orWhere('telephone', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('users.index', compact('users', 'search'));
    }

    /**
     * Formulaire de création.
     */
    public function create(): View
    {
        $roles = Role::orderBy('nom')->get();

        return view('users.create', compact('roles'));
    }

    /**
     * Enregistrer un nouvel utilisateur.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'telephone' => $request->telephone,
            'login'     => $request->login,
            'password'  => Hash::make($request->password),
            'role_id'   => $request->role_id,
            'statut'    => true,
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Afficher les détails d'un utilisateur.
     */
    public function show(User $user): View
    {
        $user->load('role');

        return view('users.show', compact('user'));
    }

    /**
     * Formulaire de modification.
     */
    public function edit(User $user): View
    {
        $roles = Role::orderBy('nom')->get();

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Mettre à jour un utilisateur.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        // Si aucun mot de passe n'est saisi, on ne le modifie pas.
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'Utilisateur modifié avec succès.');
    }

    /**
     * Supprimer un utilisateur.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Empêcher la suppression de son propre compte
        if (auth()->id() === $user->id) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

        public function toggleStatus(User $user)
    {
        $user->statut = !$user->statut;

        $user->save();

        return redirect()
            ->back()
            ->with('success', 'Le statut du compte a été modifié avec succès.');
    }
}