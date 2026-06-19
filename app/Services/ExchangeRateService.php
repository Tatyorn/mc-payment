<?php

namespace App\Services;

use App\Exceptions\Errors\CurrencyNotSupported;
use App\Exceptions\Errors\ExchangeRateFailed;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ExchangeRateService
{
    private string $apiKey;

    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.exchangerate.key');
        $this->baseUrl = "https://v6.exchangerate-api.com/v6/{$this->apiKey}";
    }

    /**
     * Obtém a taxa de câmbio de uma Moeda Local para o Euro (EUR).
     *
     * @param  string  $currency  Código ISO da moeda (ex: USD, BRL)
     *
     * @throws CurrencyNotSupported
     * @throws ExchangeRateFailed
     */
    public function getRate(string $currency): array
    {
        $currency = strtoupper($currency);

        if ($currency === 'EUR') {
            return [
                'rate' => 1.0,
                'source' => 'ExchangeRate-API',
                'timestamp' => now()->toIso8601String(),
            ];
        }

        return Cache::remember("exchange_rate_{$currency}_to_eur", 86400, function () use ($currency) {
            $response = Http::timeout(10)->get("{$this->baseUrl}/pair/{$currency}/EUR");

            if ($response->status() === 404 || ($response->json()['error-type'] ?? '') === 'unsupported-code') {
                throw new CurrencyNotSupported($currency);
            }

            if ($response->failed() || ($response->json()['result'] ?? '') !== 'success') {
                throw new ExchangeRateFailed;
            }

            $data = $response->json();

            return [
                'rate' => (float) $data['conversion_rate'],
                'source' => 'ExchangeRate-API',
                'timestamp' => isset($data['time_last_update_unix'])
                    ? now()->setTimestamp($data['time_last_update_unix'])->toIso8601String()
                    : now()->toIso8601String(),
            ];
        });
    }
}
