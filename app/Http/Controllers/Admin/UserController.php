<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

// Gère le CRUD des utilisateurs dans l'espace admin
class UserController extends Controller
{
    // Liste tous les utilisateurs, du plus récent au plus ancien
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    // Affiche le formulaire de création d'un utilisateur
    public function create()
    {
        return view('admin.users.create');
    }

    // Enregistre un nouvel utilisateur avec mot de passe hashé
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:user,admin',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé.');
    }

    // Affiche le formulaire de modification d'un utilisateur
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // Met à jour le nom, l'email et le rôle d'un utilisateur
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:user,admin',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour.');
    }

    // Active ou désactive un compte utilisateur
    public function toggleActive(User $user)
    {
        // Empêche l'admin de désactiver son propre compte
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Vous ne pouvez pas désactiver votre propre compte.']);
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activé' : 'désactivé';
        return back()->with('success', "Compte {$status}.");
    }

    // Supprime un utilisateur
    public function destroy(User $user)
    {
        // Empêche l'admin de supprimer son propre compte
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Vous ne pouvez pas supprimer votre propre compte.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé.');
    }
}
