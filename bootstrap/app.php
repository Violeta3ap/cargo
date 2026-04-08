<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'nocache' => \App\Http\Middleware\NoCacheMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            if (
                $request->expectsJson()
                || $e instanceof \Illuminate\Validation\ValidationException
                || $e instanceof \Illuminate\Auth\AuthenticationException
            ) {
                return null;
            }

            $status = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;

            $titles = [
                403 => 'Piekļuve liegta',
                404 => 'Lapa nav atrasta',
                419 => 'Sesijas termiņš beidzies',
                500 => 'Radās kļūda',
            ];

            $messages = [
                403 => 'Jums nav tiesību veikt šo darbību.',
                404 => 'Pieprasītā lapa nav atrasta. Lūdzu, pārbaudiet saiti vai atgriezieties iepriekšējā lapā.',
                419 => 'Sesijas laiks ir beidzies. Atsvaidziniet lapu un mēģiniet vēlreiz.',
                500 => 'Radās neparedzēta sistēmas kļūda. Mēģiniet vēlreiz vēlāk vai sazinieties ar administratoru.',
            ];

            return response()->view('errors.custom', [
                'status' => $status,
                'title' => $titles[$status] ?? 'Darbību neizdevās izpildīt',
                'message' => $messages[$status] ?? 'Pieprasījumu šobrīd nevar apstrādāt. Lūdzu, mēģiniet vēlreiz.',
            ], $status);
        });
    })->create();
