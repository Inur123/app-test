<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ExternalLogger
{
    public static function send(string $logType, array $payload = []): bool
    {
        $url   = config('services.log_api.url');
        $token = config('services.log_api.token');

        if (! $url || ! $token) {
            Log::warning('External log: config belum lengkap', [
                'url'   => $url,
                'token' => $token ? '(di-set)' : '(kosong)',
            ]);
            return false;
        }

        $body = [
            'log_type' => $logType,
            'payload'  => array_merge($payload, [
                'timestamp' => Carbon::now('Asia/Jakarta')
                    ->locale('id')
                    ->translatedFormat('l, d F Y | H.i') . ' WIB',
                'app'       => config('app.name'),
                'env'       => app()->environment(),
            ]),
        ];

        try {
            /** @var Response $response */
            $response = Http::acceptJson()
                ->withHeaders([
                    'X-API-KEY' => $token,
                ])
                ->post($url, $body);

            Log::info('External log: response', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            if (! $response->successful()) {
                Log::warning('External log: not successful', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
            }

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('External log: exception', [
                'message' => $e->getMessage(),
                'event'   => $logType,
                'payload' => $payload,
            ]);

            return false;
        }
    }
}
