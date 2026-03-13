<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Définir les alias pour tes middlewares personnalisés
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        // Optionnel : ajouter au groupe "web"
        $middleware->appendToGroup('web', [
            // Exemple : tes middlewares supplémentaires
            // \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
