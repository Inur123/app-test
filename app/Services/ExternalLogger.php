<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
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

        $finalPayload = array_merge($payload, [
            'timestamp' => Carbon::now('Asia/Jakarta')
                ->locale('id')
                ->translatedFormat('l, d F Y | H.i') . ' WIB',
            'app'       => config('app.name'),
            'env'       => app()->environment(),
        ]);

        // Inject username jika belum ada (wajib untuk Logging API)
        if (! isset($finalPayload['username'])) {
            // Prioritas 1: Ambil dari session Auth (identitas pelaku yang sedang login)
            if (Auth::check()) {
                $user = Auth::user();
                $finalPayload['username'] = $user->name ?: $user->email;
            } 
            // Prioritas 2: Field name, cek, atau email di payload (sebagai fallback request user)
            else {
                $finalPayload['username'] = $finalPayload['name'] ?? 
                                           $finalPayload['cek'] ?? 
                                           $finalPayload['email'] ?? 
                                           null;
            }

            // Fallback terakhir jika benar-benar tidak ada info
            if (! $finalPayload['username']) {
                $finalPayload['username'] = 'System';
            }
        }

        $body = [
            'log_type' => $logType,
            'payload'  => $finalPayload,
        ];

        try {
            /** @var Response $response */
            $response = Http::acceptJson()
                ->timeout(3) // Maksimal tunggu 3 detik
                ->connectTimeout(2) // Maksimal koneksi 2 detik
                ->withOptions([
                    'verify' => false, // Bypass SSL verification
                ])
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
