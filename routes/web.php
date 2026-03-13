<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Route de base - redirection vers login
Route::get('/', function () {
    return redirect('/login');
});

// Routes d'authentification (Breeze)
require __DIR__.'/auth.php';

// Routes protégées
Route::middleware(['auth'])->group(function () {
    
    // Dashboard - route simple
    Route::get('/dashboard', function () {
        $user = Auth::user();
        
        if (!$user) {
            return redirect('/login');
        }
        
        // Redirection basique selon le rôle
        if ($user->employe) {
            $role = $user->employe->role;
            
            if ($role === 'admin') {
                return redirect('/admin/dashboard');
            } elseif ($role === 'manager') {
                return redirect('/manager/dashboard');
            } elseif ($role === 'employe') {
                return redirect('/employe/dashboard');
            }
        }
        
        return view('dashboard.default', ['user' => $user]);
    })->name('dashboard');
    
    // Routes employés
    Route::prefix('employe')->middleware('auth')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'employeDashboard'])
            ->name('employe.dashboard');
        
        // Congés
        Route::resource('conges', 'App\Http\Controllers\CongeController');
    });
    
    // Routes managers
    Route::prefix('manager')->middleware('auth')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'managerDashboard'])
            ->name('manager.dashboard');
        
        // Équipe
        Route::get('/equipe', function () {
            $user = Auth::user();
            return view('manager.equipe', ['user' => $user]);
        })->name('manager.equipe');
        
        // Validations des congés
        Route::get('/conges/validation', [App\Http\Controllers\CongeController::class, 'validation'])
            ->name('conges.validation.index');
        
        // Mon équipe
        Route::get('/employes', [App\Http\Controllers\EmployeController::class, 'mesEmployes'])
            ->name('employes.mes-employes');
    });
    
    // Routes administrateurs
    Route::prefix('admin')->middleware('auth')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'adminDashboard'])
            ->name('admin.dashboard');
        
        // Départements
        Route::resource('departements', 'App\Http\Controllers\DepartementController');
        
        // Employés
        Route::resource('employes', 'App\Http\Controllers\EmployeController');
    });
    
    // Profile routes
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

// Route de test pour vérifier l'authentification
Route::get('/test-user', function () {
    $user = Auth::user();
    
    if ($user) {
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'has_employe' => $user->employe ? 'Oui' : 'Non',
            'employe_role' => $user->employe ? $user->employe->role : 'Aucun',
        ]);
    }
    
    return 'Utilisateur non connecté';
});
// Notifications
Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])
    ->name('notifications.index');

Route::post('/notifications/{notification}/read', [App\Http\Controllers\NotificationController::class, 'markRead'])
    ->name('notifications.mark-read');

Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllRead'])
    ->name('notifications.mark-all-read');

Route::delete('/notifications/{notification}', [App\Http\Controllers\NotificationController::class, 'destroy'])
    ->name('notifications.destroy');

// Validations routes
Route::post('/validations/{conge}/approve', [App\Http\Controllers\ValidationController::class, 'approve'])
    ->name('validations.approve')
    ->middleware('auth');

Route::post('/validations/{conge}/reject', [App\Http\Controllers\ValidationController::class, 'reject'])
    ->name('validations.reject')
    ->middleware('auth');

// Dashboard statistiques route
Route::get('/statistiques', [App\Http\Controllers\DashboardController::class, 'statistiques'])
    ->name('dashboard.statistiques')
    ->middleware('auth');

// Validations index route
Route::get('/validations', [App\Http\Controllers\ValidationController::class, 'index'])
    ->name('validations.index')
    ->middleware('auth');
