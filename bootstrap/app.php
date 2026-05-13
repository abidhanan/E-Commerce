<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'admin.activity' => \App\Http\Middleware\RecordAdminActivity::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\SecurityHeaders::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (
            PostTooLargeException $e,
            Request $request
        ) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Total ukuran upload gambar terlalu besar. Maksimal sesuai batas aplikasi.');
        });

    })->create();
