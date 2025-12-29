<?php

namespace App\Services;

use App\Models\ExchangeRate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class TcmbExchangeRateService
{
    public function ensureTodayRates(array $currencyCodes = ['USD', 'EUR']): void
    {
        $today = now()->toDateString();

        $missing = [];
        foreach ($currencyCodes as $code) {
            $exists = ExchangeRate::query()
                ->whereDate('tarih', $today)
                ->where('currency_code', $code)
                ->exists();
            if (!$exists) {
                $missing[] = $code;
            }
        }

        if (count($missing) === 0) {
            return;
        }

        $this->fetchAndStoreToday();
    }

    public function getTodayForexSelling(string $currencyCode): ?string
    {
        $today = now()->toDateString();

        $rate = ExchangeRate::query()
            ->where('currency_code', $currencyCode)
            ->whereDate('tarih', '<=', $today)
            ->orderByDesc('tarih')
            ->first();

        if (!$rate) {
            $this->fetchAndStoreToday();
            $rate = ExchangeRate::query()
                ->where('currency_code', $currencyCode)
                ->whereDate('tarih', '<=', $today)
                ->orderByDesc('tarih')
                ->first();
        }

        return $rate?->forex_selling;
    }

    private function fetchAndStoreToday(): void
    {
        $response = Http::timeout(8)->get('https://www.tcmb.gov.tr/kurlar/today.xml');
        if (!$response->ok()) {
            return;
        }

        $xml = @simplexml_load_string($response->body());
        if (!$xml) {
            return;
        }

        $dateAttr = (string) ($xml['Tarih'] ?? '');
        $date = null;
        if ($dateAttr) {
            try {
                $date = Carbon::createFromFormat('d.m.Y', $dateAttr)->toDateString();
            } catch (\Throwable $e) {
                $date = null;
            }
        }
        $date = $date ?: now()->toDateString();

        foreach ($xml->Currency as $currency) {
            $code = (string) ($currency['CurrencyCode'] ?? '');
            if (!in_array($code, ['USD', 'EUR'], true)) {
                continue;
            }

            $payload = [
                'tarih' => $date,
                'currency_code' => $code,
                'forex_buying' => $this->toDecimalOrNull((string) ($currency->ForexBuying ?? '')),
                'forex_selling' => $this->toDecimalOrNull((string) ($currency->ForexSelling ?? '')),
                'banknote_buying' => $this->toDecimalOrNull((string) ($currency->BanknoteBuying ?? '')),
                'banknote_selling' => $this->toDecimalOrNull((string) ($currency->BanknoteSelling ?? '')),
            ];

            ExchangeRate::updateOrCreate(
                ['tarih' => $date, 'currency_code' => $code],
                $payload
            );
        }
    }

    private function toDecimalOrNull(string $value): ?string
    {
        $v = trim($value);
        if ($v === '') {
            return null;
        }
        $v = str_replace(',', '.', $v);
        return is_numeric($v) ? $v : null;
    }
}
