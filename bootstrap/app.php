<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use App\Services\ExternalLogger;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        /**
         *  Kirim semua exception/error ke external log API sebagai SYSTEM_ERROR
         */
        $exceptions->reportable(function (\Throwable $e) {

            // 🚫 Hindari loop: jangan log error dari endpoint log API itu sendiri
            if (request()?->is('api/logs*') || request()?->is('api/log*')) {
                return;
            }

            // ⏱️ Rate limit error yang sama (maks 10x / menit)
            $rateKey = 'system_error:' . md5($e->getMessage());

            if (RateLimiter::tooManyAttempts($rateKey, 10)) {
                return;
            }

            RateLimiter::hit($rateKey, 60);

            //  Kirim ke log API
            ExternalLogger::send('SYSTEM_ERROR', [
                'message' => $e->getMessage(),
                'code'    => class_basename($e),
                'context' => [
                    'url'     => request()?->fullUrl(),
                    'method'  => request()?->method(),
                    'ip'      => request()?->ip(),
                    'user_id' => Auth::id(),

                    // detail stack penting
                    'file'    => $e->getFile(),
                    'line'    => $e->getLine(),
                    'trace'   => collect($e->getTrace())->take(10)->toArray(),
                ],
            ]);
        });

    })->create();
