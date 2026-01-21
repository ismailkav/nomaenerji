<?php

namespace App\Http\Controllers;

use App\Models\StockInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockInventoryController extends Controller
{
    public function index(Request $request)
    {
        $openOrderTotalsByStockCode = DB::table('siparis_detaylari as sd')
            ->join('siparisler as s', 's.id', '=', 'sd.siparis_id')
            ->join('urunler as u2', 'u2.id', '=', 'sd.urun_id')
            ->where('s.siparis_turu', 'satis')
            ->where('sd.durum', 'A')
            ->groupBy('u2.kod')
            ->selectRaw(
                "u2.kod as stokkod,
                 SUM(
                    CASE
                        WHEN (COALESCE(sd.miktar,0) - COALESCE(sd.gelen,0)) > 0
                            THEN (COALESCE(sd.miktar,0) - COALESCE(sd.gelen,0))
                        ELSE 0
                    END
                 ) as siparis_miktar"
            );

        $items = StockInventory::query()
            ->leftJoin('depolar', 'depolar.id', '=', 'stokenvanter.depo_id')
            ->leftJoin('urunler', 'urunler.kod', '=', 'stokenvanter.stokkod')
            ->leftJoinSub($openOrderTotalsByStockCode, 'oot', function ($join) {
                $join->on('oot.stokkod', '=', 'stokenvanter.stokkod');
            })
            ->select([
                'stokenvanter.id',
                'stokenvanter.depo_id',
                'depolar.kod as depo_kod',
                'stokenvanter.stokkod',
                'urunler.aciklama as stokaciklama',
                'stokenvanter.stokmiktar',
                DB::raw('COALESCE(oot.siparis_miktar,0) as siparis_miktar'),
            ])
            ->selectSub(
                DB::table('stokrevize as sr')
                    ->selectRaw('COALESCE(SUM(sr.miktar),0)')
                    ->whereColumn('sr.depo_id', 'stokenvanter.depo_id')
                    ->whereColumn('sr.stokkod', 'stokenvanter.stokkod')
                    ->whereIn('sr.durum', ['A', 'Açık', 'AÇõŽñk']),
                'revize_miktar'
            )
            ->orderBy('depolar.kod')
            ->orderBy('stokenvanter.stokkod')
            ->get();

        return view('stock.inventory.index', [
            'active' => 'stock-inventory',
            'items' => $items,
        ]);
    }

    public function reserveDetails(Request $request)
    {
        $data = $request->validate([
            'depo_id' => ['required', 'integer', 'min:1'],
            'stokkod' => ['required', 'string', 'max:100'],
        ]);

        $depoId = (int) $data['depo_id'];
        $stokkod = (string) $data['stokkod'];
        $openDurumlar = ['A', 'Açık', 'AÇõŽñk'];

        $rows = DB::table('stokrevize as sr')
            ->join('siparis_detaylari as sd', 'sd.id', '=', 'sr.siparissatirid')
            ->join('siparisler as s', 's.id', '=', 'sd.siparis_id')
            ->where('sr.depo_id', $depoId)
            ->where('sr.stokkod', $stokkod)
            ->whereIn('sr.durum', $openDurumlar)
            ->orderByDesc('sr.created_at')
            ->get([
                's.siparis_no',
                's.tarih',
                's.carikod',
                's.siparis_durum',
                'sr.miktar as rezerve_miktar',
            ]);

        return response()->json([
            'ok' => true,
            'rows' => $rows,
        ]);
    }
}
