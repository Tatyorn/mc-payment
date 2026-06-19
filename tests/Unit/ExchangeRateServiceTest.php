<?php

namespace Tests\Unit;

use App\Exceptions\Errors\CurrencyNotSupported;
use App\Services\ExchangeRateService;
use Illuminate\Support\Facades\Http;

it('fetches the correct exchange rate from currency to eur', function () {
    Http::fake([
        'v6.exchangerate-api.com/*' => Http::response([
            'result' => 'success',
            'conversion_rate' => 0.9345,
            'time_last_update_unix' => 1718805600,
        ], 200),
    ]);

    $service = new ExchangeRateService;
    $result = $service->getRate('USD');

    expect($result)->toBeArray()
        ->and($result['rate'])->toBe(0.9345)
        ->and($result['source'])->toBe('ExchangeRate-API');
});

it('throws an exception if currency is not supported', function () {
    Http::fake([
        'v6.exchangerate-api.com/*' => Http::response([
            'result' => 'error',
            'error-type' => 'unsupported-code',
        ], 404),
    ]);

    $service = new ExchangeRateService;

    expect(fn () => $service->getRate('INVALID'))->toThrow(CurrencyNotSupported::class);
});
