<?php

namespace App\Http\Controllers;

use App\Models\ExchangeRate;
use App\Services\TcmbExchangeRateService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ExchangeRateController extends Controller
{
    public function today(Request $request, TcmbExchangeRateService $service)
    {
        $data = $request->validate([
            'currency_code' => ['required', 'string', 'in:USD,EUR'],
        ]);

        $code = (string) $data['currency_code'];
        $selling = $service->getTodayForexSelling($code);

        if ($selling === null) {
            return response()->json([
                'ok' => false,
                'message' => 'Kur verisi alınamadı.',
            ], 422);
        }

        return response()->json([
            'ok' => true,
            'currency_code' => $code,
            'tarih' => now()->toDateString(),
            'forex_selling' => $selling,
        ]);
    }

    public function byDate(Request $request, TcmbExchangeRateService $service)
    {
        $data = $request->validate([
            'currency_code' => ['required', 'string', 'in:TL,USD,EUR'],
            'tarih' => ['required', 'date'],
        ]);

        $code = strtoupper(trim((string) $data['currency_code']));
        $date = Carbon::parse($data['tarih'])->toDateString();

        if ($code === 'TL') {
            return response()->json([
                'ok' => true,
                'currency_code' => $code,
                'tarih' => $date,
                'forex_buying' => 1,
                'forex_selling' => 1,
                'banknote_buying' => 1,
                'banknote_selling' => 1,
            ]);
        }

        if ($date === now()->toDateString()) {
            $service->ensureTodayRates([$code]);
        }

        $rate = ExchangeRate::query()
            ->where('currency_code', $code)
            ->whereDate('tarih', '<=', $date)
            ->orderByDesc('tarih')
            ->first();

        if (!$rate) {
            return response()->json([
                'ok' => false,
                'message' => 'Kur verisi bulunamadı.',
            ], 422);
        }

        return response()->json([
            'ok' => true,
            'currency_code' => $code,
            'tarih' => $rate->tarih?->toDateString() ?? $date,
            'forex_buying' => $rate->forex_buying,
            'forex_selling' => $rate->forex_selling,
            'banknote_buying' => $rate->banknote_buying,
            'banknote_selling' => $rate->banknote_selling,
        ]);
    }
}
