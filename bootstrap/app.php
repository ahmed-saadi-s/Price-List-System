<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request; // Add this import for the Request type hint
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
     // Redirect guests to the login page when
     $middleware->redirectGuestsTo(fn (Request $request) => route('login.form'));
     // Redirect authenticated users to the dashboard home page
     $middleware->redirectUsersTo(fn (Request $request) => route('dashboard.home'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
