<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        
        // Vérifier si l'utilisateur a un profil employé
        // Utilisez une vérification directe au lieu de hasEmployeProfile()
        if (!$user->employe) {
            return redirect('/dashboard')->with('error', 'Profil employé non trouvé.');
        }

        // Vérifier si le rôle est autorisé
        if (!in_array($user->employe->role, $roles)) {
            return redirect('/dashboard')->with('error', 'Accès non autorisé. Rôle requis: ' . implode(', ', $roles));
        }

        return $next($request);
    }
}