<?php

use App\Http\Middleware\SecurityHeadersMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Daftarkan Security Headers secara Global
        $middleware->append(SecurityHeadersMiddleware::class);

        // Rate Limiting untuk API
        $middleware->throttleApi('60,1');

        // Daftarkan Alias Middleware
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class, // Sesuaikan dengan nama class middleware milikmu
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Custom Exception Handler jika diperlukan
    })->create();
