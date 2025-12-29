<?php

namespace App\Http\Controllers;

use App\Services\TcmbExchangeRateService;
use Illuminate\Http\Request;

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
}
