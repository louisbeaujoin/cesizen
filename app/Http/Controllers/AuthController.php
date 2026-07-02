<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

// Gère la connexion, l'inscription, la déconnexion et le profil utilisateur
class AuthController extends Controller
{
    // Affiche le formulaire de connexion
    public function showLogin()
    {
        return view('auth.login');
    }

    // Vérifie les identifiants et connecte l'utilisateur
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            // Refuse la connexion si le compte est désactivé
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Votre compte a été désactivé.']);
            }

            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors(['email' => 'Identifiants incorrects.'])->onlyInput('email');
    }

    // Affiche le formulaire d'inscription
    public function showRegister()
    {
        return view('auth.register');
    }

    // Crée un nouveau compte et connecte l'utilisateur
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        return redirect('/');
    }

    // Déconnecte l'utilisateur et invalide la session
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // Affiche la page de profil de l'utilisateur connecté
    public function showProfile()
    {
        return view('auth.profile', ['user' => auth()->user()]);
    }

    // Met à jour le nom et l'email du profil
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return back()->with('success', 'Profil mis à jour.');
    }

    // Change le mot de passe après vérification de l'ancien
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        // Vérifie que l'ancien mot de passe est correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Mot de passe mis à jour.');
    }
}
