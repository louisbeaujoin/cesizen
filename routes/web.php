<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\BreathingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\InformationPageController;
use App\Http\Controllers\Admin\BreathingExerciseController;

// Page d'accueil
Route::get('/', [HomeController::class, 'index'])->name('home');

// Téléchargement de l'application mobile Android
Route::get('/telecharger-app', function () {
    $path = public_path('downloads/cesizen.apk');
    return response()->download($path, 'CESIZen.apk', [
        'Content-Type' => 'application/vnd.android.package-archive',
    ]);
})->name('app.download');

// Routes accessibles uniquement aux visiteurs non connectés
Route::middleware('guest')->group(function () {
    Route::get('/connexion', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/connexion', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/inscription', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/inscription', [AuthController::class, 'register']);
});

// Routes accessibles uniquement aux utilisateurs connectés
Route::middleware('auth')->group(function () {
    Route::post('/deconnexion', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profil', [AuthController::class, 'showProfile'])->name('profile');
    Route::put('/profil', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profil/mot-de-passe', [AuthController::class, 'updatePassword'])->name('profile.password');
});

// Pages d'information publiques
Route::get('/informations', [InformationController::class, 'index'])->name('information.index');
Route::get('/informations/{slug}', [InformationController::class, 'show'])->name('information.show');

// Exercices de respiration (accessibles à tous, sauvegarde réservée aux connectés)
Route::get('/respiration', [BreathingController::class, 'index'])->name('breathing.index');
Route::get('/respiration/exercice/{exercise?}', [BreathingController::class, 'exercise'])->name('breathing.exercise');
Route::post('/respiration/session', [BreathingController::class, 'saveSession'])->name('breathing.session.save')->middleware('auth');

// Routes admin (nécessitent d'être connecté et d'avoir le rôle admin)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Gestion des utilisateurs
    Route::get('/utilisateurs', [UserController::class, 'index'])->name('users.index');
    Route::get('/utilisateurs/creer', [UserController::class, 'create'])->name('users.create');
    Route::post('/utilisateurs', [UserController::class, 'store'])->name('users.store');
    Route::get('/utilisateurs/{user}/modifier', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/utilisateurs/{user}', [UserController::class, 'update'])->name('users.update');
    Route::patch('/utilisateurs/{user}/toggle', [UserController::class, 'toggleActive'])->name('users.toggle');
    Route::delete('/utilisateurs/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Gestion des pages d'information
    Route::get('/informations', [InformationPageController::class, 'index'])->name('information.index');
    Route::get('/informations/creer', [InformationPageController::class, 'create'])->name('information.create');
    Route::post('/informations', [InformationPageController::class, 'store'])->name('information.store');
    Route::get('/informations/{page}/modifier', [InformationPageController::class, 'edit'])->name('information.edit');
    Route::put('/informations/{page}', [InformationPageController::class, 'update'])->name('information.update');
    Route::delete('/informations/{page}', [InformationPageController::class, 'destroy'])->name('information.destroy');

    // Gestion des exercices de respiration
    Route::get('/respiration', [BreathingExerciseController::class, 'index'])->name('breathing.index');
    Route::get('/respiration/creer', [BreathingExerciseController::class, 'create'])->name('breathing.create');
    Route::post('/respiration', [BreathingExerciseController::class, 'store'])->name('breathing.store');
    Route::get('/respiration/{exercise}/modifier', [BreathingExerciseController::class, 'edit'])->name('breathing.edit');
    Route::put('/respiration/{exercise}', [BreathingExerciseController::class, 'update'])->name('breathing.update');
    Route::delete('/respiration/{exercise}', [BreathingExerciseController::class, 'destroy'])->name('breathing.destroy');
});
