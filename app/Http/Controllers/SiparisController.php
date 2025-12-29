<?php

namespace App\Http\Controllers;

use App\Models\Firm;
use App\Models\Depot;
use App\Models\IslemTuru;
use App\Models\Product;
use App\Models\Project;
use App\Models\Siparis;
use App\Models\SiparisDetay;
use App\Models\StockRevision;
use App\Models\Teklif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SiparisController extends Controller
{
    public function index(Request $request)
    {
        $tur = $this->normalizeTur($request->query('tur'));

        $query = Siparis::query()
            ->with(['islemTuru', 'proje'])
            ->where('siparis_turu', $tur);

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q2) use ($search) {
                $q2->where('siparis_no', 'like', '%' . $search . '%')
                    ->orWhere('carikod', 'like', '%' . $search . '%')
                    ->orWhere('cariaciklama', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('tarih_baslangic')) {
            $query->whereDate('tarih', '>=', $request->input('tarih_baslangic'));
        }

        if ($request->filled('tarih_bitis')) {
            $query->whereDate('tarih', '<=', $request->input('tarih_bitis'));
        }

        if ($request->filled('siparis_durum') && $request->input('siparis_durum') !== 'hepsi') {
            $query->where('siparis_durum', $request->input('siparis_durum'));
        }

        if ($request->filled('islem_turu_id')) {
            $query->where('islem_turu_id', $request->integer('islem_turu_id'));
        }

        if ($request->filled('proje_id')) {
            $query->where('proje_id', $request->integer('proje_id'));
        }

        $siparisler = $query
            ->orderByDesc('tarih')
            ->orderByDesc('id')
            ->paginate(15)
            ->appends($request->query());

        $durumlar = $this->durumlar();
        $islemTurleri = IslemTuru::orderBy('ad')->get();
        $projects = Project::where('pasif', false)->orderBy('kod')->get();

        $active = $tur === 'alim' ? 'purchase-orders' : 'sales-orders';

        return view('orders.index', compact('siparisler', 'durumlar', 'islemTurleri', 'projects', 'tur', 'active'));
    }

    public function planning(Request $request)
    {
        $query = DB::table('siparis_detaylari as sd')
            ->join('siparisler as s', 'sd.siparis_id', '=', 's.id')
            ->leftJoin('urunler as u', 'sd.urun_id', '=', 'u.id')
            ->where('s.siparis_turu', 'satis')
            ->where('sd.durum', 'A');

        if ($request->filled('tarih_baslangic')) {
            $query->whereDate('s.tarih', '>=', $request->input('tarih_baslangic'));
        }

        if ($request->filled('tarih_bitis')) {
            $query->whereDate('s.tarih', '<=', $request->input('tarih_bitis'));
        }

        $query->select([
            'sd.id as siparis_detay_id',
            's.id as siparis_id',
            's.siparis_no',
            's.tarih',
            's.carikod',
            'sd.urun_id',
            'u.kod as stok_kod',
            'u.aciklama as stok_aciklama',
            'sd.miktar as miktar',
            's.planlanan_miktar as planlanan_miktar',
        ]);

        $query->selectSub(
            DB::table('stokenvanter as se')
                ->selectRaw('COALESCE(SUM(se.stokmiktar),0)')
                ->whereColumn('se.stokkod', 'u.kod'),
            'stok_miktar'
        );

        $query->selectSub(
            DB::table('stokrevize as sr2')
                ->selectRaw('COALESCE(SUM(sr2.miktar),0)')
                ->whereColumn('sr2.stokkod', 'u.kod')
                ->whereIn('sr2.durum', ['A', 'Açık', 'AÇõŽñk']),
            'revize_toplam'
        );

        $query->selectSub(
            DB::table('stokrevize as sr3')
                ->selectRaw('COALESCE(SUM(sr3.miktar),0)')
                ->whereColumn('sr3.siparissatirid', 'sd.id')
                ->whereIn('sr3.durum', ['A', 'Açık', 'AÇõŽñk']),
            'revize_satir_miktar'
        );

        $query->selectSub(
            DB::table('siparis_detaylari as sd2')
                ->selectRaw('COALESCE(SUM(sd2.miktar),0)')
                ->whereColumn('sd2.siparis_id', 's.id')
                ->where('sd2.durum', 'A'),
            'siparis_miktar'
        );

        $rows = $query
            ->orderByDesc('s.tarih')
            ->orderByDesc('s.id')
            ->orderByDesc('sd.id')
            ->paginate(25)
            ->appends($request->query());

        $firms = Firm::query()
            ->orderBy('carikod')
            ->get(['carikod', 'cariaciklama', 'adres1', 'adres2', 'il', 'ilce']);

        return view('orders.planning', [
            'active' => 'order-planning',
            'rows' => $rows,
            'firms' => $firms,
            'filters' => [
                'tarih_baslangic' => $request->input('tarih_baslangic'),
                'tarih_bitis' => $request->input('tarih_bitis'),
                'q' => $request->input('q'),
            ],
        ]);
    }

    public function planningCreatePurchase(Request $request)
    {
        $data = $request->validate([
            'carikod' => ['required', 'string', 'max:50'],
            'selected_rows' => ['required', 'string'],
        ]);

        $firm = Firm::query()
            ->where('carikod', $data['carikod'])
            ->first();

        if (!$firm) {
            return back()->withErrors(['carikod' => 'Cari bulunamadı.']);
        }

        $selectedRows = json_decode($data['selected_rows'], true);
        if (!is_array($selectedRows) || count($selectedRows) === 0) {
            return back()->withErrors(['selected_rows' => 'Seçili satır bulunamadı.']);
        }

        $wanted = [];
        foreach ($selectedRows as $row) {
            $detailId = isset($row['satis_detay_id']) ? (int) $row['satis_detay_id'] : 0;
            $qty = isset($row['siparis_miktar']) ? (float) $row['siparis_miktar'] : 0.0;
            if ($detailId <= 0 || $qty <= 0) {
                continue;
            }
            $wanted[$detailId] = $qty;
        }

        if (count($wanted) === 0) {
            return back()->withErrors(['selected_rows' => 'Geçerli miktar içeren satır bulunamadı.']);
        }

        $details = SiparisDetay::query()
            ->with(['urun', 'siparis'])
            ->whereIn('id', array_keys($wanted))
            ->where('durum', 'A')
            ->get()
            ->filter(function (SiparisDetay $d) {
                return ($d->siparis->siparis_turu ?? 'alim') === 'satis';
            })
            ->values();

        if ($details->isEmpty()) {
            return back()->withErrors(['selected_rows' => 'Satış sipariş satırı bulunamadı.']);
        }

        $revizeMap = StockRevision::query()
            ->whereIn('siparissatirid', array_keys($wanted))
            ->whereIn('durum', ['A', 'Açık', 'AÇõŽñk'])
            ->selectRaw('siparissatirid, COALESCE(SUM(miktar),0) as toplam')
            ->groupBy('siparissatirid')
            ->pluck('toplam', 'siparissatirid')
            ->all();

        DB::transaction(function () use ($details, $wanted) {
            $totalsBySiparis = [];
            foreach ($details as $detail) {
                $siparisId = (int) ($detail->siparis_id ?? 0);
                $qty = (float) ($wanted[$detail->id] ?? 0);
                if ($siparisId <= 0 || $qty <= 0) {
                    continue;
                }
                $totalsBySiparis[$siparisId] = ($totalsBySiparis[$siparisId] ?? 0) + $qty;
            }

            foreach ($totalsBySiparis as $siparisId => $qty) {
                $siparis = Siparis::query()
                    ->where('id', $siparisId)
                    ->where('siparis_turu', 'satis')
                    ->lockForUpdate()
                    ->first();

                if (!$siparis) {
                    continue;
                }

                $current = (float) ($siparis->planlanan_miktar ?? 0);
                $siparis->planlanan_miktar = $current + $qty;
                $siparis->save();
            }
        });

        $grouped = [];
        foreach ($details as $detail) {
            $urunId = (int) ($detail->urun_id ?? 0);
            if ($urunId <= 0) {
                continue;
            }

            $qty = (float) ($wanted[$detail->id] ?? 0.0);
            $qty += isset($revizeMap[$detail->id]) ? (float) $revizeMap[$detail->id] : 0.0;
            if ($qty <= 0) {
                continue;
            }

            if (!isset($grouped[$urunId])) {
                $grouped[$urunId] = [
                    'urun_id' => $urunId,
                    'urun' => [
                        'kod' => $detail->urun->kod ?? null,
                        'aciklama' => $detail->urun->aciklama ?? null,
                    ],
                    'satir_aciklama' => $detail->urun->aciklama ?? ($detail->satir_aciklama ?? ''),
                    'durum' => 'A',
                    'miktar' => 0.0,
                    'birim_fiyat' => (float) ($detail->urun->satis_fiyat ?? 0),
                    'kdv_orani' => (float) ($detail->urun->kdv_oran ?? 0),
                    'doviz' => 'TL',
                    'kur' => 1,
                    'iskonto1' => 0,
                    'iskonto2' => 0,
                    'iskonto3' => 0,
                    'iskonto4' => 0,
                    'iskonto5' => 0,
                    'iskonto6' => 0,
                    'satis_detay_ids' => [],
                    'sales_links' => [],
                ];
            }

            $grouped[$urunId]['miktar'] += $qty;
            $grouped[$urunId]['satis_detay_ids'][] = $detail->id;
            $grouped[$urunId]['sales_links'][] = [
                'carikod' => $detail->siparis->carikod ?? null,
                'siparis_no' => $detail->siparis->siparis_no ?? null,
                'tarih' => $detail->siparis->tarih ? $detail->siparis->tarih->toDateString() : null,
                'miktar' => $qty,
            ];
        }

        $lines = array_values($grouped);
        if (count($lines) === 0) {
            return back()->withErrors(['selected_rows' => 'Satıra eklenecek ürün bulunamadı.']);
        }

        session()->put('planning_purchase_prefill', [
            'header' => [
                'carikod' => $firm->carikod,
                'cariaciklama' => $firm->cariaciklama,
                'tarih' => now()->toDateString(),
            ],
            'lines' => $lines,
        ]);

        return redirect()->route('orders.create', ['tur' => 'alim']);
    }

    public function planningSaveRevision(Request $request)
    {
        $data = $request->validate([
            'siparissatirid' => ['required', 'integer', 'min:1'],
            'miktar' => ['nullable', 'numeric', 'min:0'],
        ]);

        $detailId = (int) $data['siparissatirid'];
        $qty = $data['miktar'] !== null ? (float) $data['miktar'] : null;

        $detail = DB::table('siparis_detaylari as sd')
            ->join('siparisler as s', 'sd.siparis_id', '=', 's.id')
            ->leftJoin('urunler as u', 'sd.urun_id', '=', 'u.id')
            ->where('sd.id', $detailId)
            ->where('s.siparis_turu', 'satis')
            ->where('sd.durum', 'A')
            ->select([
                'sd.id',
                'u.kod as stokkod',
            ])
            ->first();

        if (!$detail || empty($detail->stokkod)) {
            return response()->json(['ok' => false, 'message' => 'Satır bulunamadı.'], 404);
        }

        $stokkod = (string) $detail->stokkod;
        $openDurumlar = ['A', 'Açık', 'AÇõŽñk'];

        $result = DB::transaction(function () use ($detailId, $stokkod, $qty, $openDurumlar) {
            $stokMiktar = (float) DB::table('stokenvanter')
                ->where('stokkod', $stokkod)
                ->sum('stokmiktar');

            $otherTotal = (float) StockRevision::query()
                ->where('stokkod', $stokkod)
                ->whereIn('durum', $openDurumlar)
                ->where('siparissatirid', '!=', $detailId)
                ->lockForUpdate()
                ->sum('miktar');

            if ($qty === null || $qty <= 0) {
                StockRevision::where('siparissatirid', $detailId)->delete();
                $thisQty = 0.0;
            } else {
                $thisQty = (float) (int) round($qty);
                $maxForThis = max(0.0, $stokMiktar - $otherTotal);

                if ($thisQty > $maxForThis) {
                    abort(response()->json([
                        'ok' => false,
                        'message' => 'Revize miktar gerçek stoktan fazla olamaz.',
                        'max' => (int) round($maxForThis),
                    ], 422));
                }

                StockRevision::updateOrCreate(
                    ['siparissatirid' => $detailId],
                    [
                        'stokkod' => $stokkod,
                        'miktar' => $thisQty,
                        'durum' => 'Açık',
                    ]
                );
            }

            $revizeToplam = (float) StockRevision::query()
                ->where('stokkod', $stokkod)
                ->whereIn('durum', $openDurumlar)
                ->sum('miktar');

            $gercekStok = $stokMiktar - $revizeToplam;

            return [
                'stokkod' => $stokkod,
                'stok_miktar' => (int) round($stokMiktar),
                'revize_toplam' => (int) round($revizeToplam),
                'gercek_stok' => (int) round($gercekStok),
                'this_revize' => (int) round($thisQty ?? 0),
            ];
        });

        return response()->json(array_merge(['ok' => true], $result));
    }

    public function planningRevisionDepotData(Request $request)
    {
        $detailId = (int) $request->query('siparissatirid', 0);
        if ($detailId <= 0) {
            return response()->json(['ok' => false, 'message' => 'Satır seçilmedi.'], 422);
        }

        $detail = DB::table('siparis_detaylari as sd')
            ->join('siparisler as s', 'sd.siparis_id', '=', 's.id')
            ->leftJoin('urunler as u', 'sd.urun_id', '=', 'u.id')
            ->where('sd.id', $detailId)
            ->where('s.siparis_turu', 'satis')
            ->where('sd.durum', 'A')
            ->select([
                'sd.id',
                'u.kod as stokkod',
                'u.aciklama as stokaciklama',
            ])
            ->first();

        if (!$detail || empty($detail->stokkod)) {
            return response()->json(['ok' => false, 'message' => 'Satır bulunamadı.'], 404);
        }

        $stokkod = (string) $detail->stokkod;
        $openDurumlar = ['A', 'Açık', 'AÇõŽñk'];

        $depots = Depot::query()
            ->where('pasif', false)
            ->orderBy('kod')
            ->get(['id', 'kod']);

        $stokByDepot = DB::table('stokenvanter')
            ->selectRaw('depo_id, COALESCE(SUM(stokmiktar),0) as stokmiktar')
            ->where('stokkod', $stokkod)
            ->groupBy('depo_id')
            ->pluck('stokmiktar', 'depo_id')
            ->all();

        $revizeByDepot = DB::table('stokrevize')
            ->selectRaw('depo_id, COALESCE(SUM(miktar),0) as miktar')
            ->where('stokkod', $stokkod)
            ->whereIn('durum', $openDurumlar)
            ->whereNotNull('depo_id')
            ->groupBy('depo_id')
            ->pluck('miktar', 'depo_id')
            ->all();

        $rows = [];
        foreach ($depots as $depot) {
            $stok = (float) ($stokByDepot[$depot->id] ?? 0);
            $rev = (float) ($revizeByDepot[$depot->id] ?? 0);
            $avail = $stok - $rev;
            $rows[] = [
                'depo_id' => (int) $depot->id,
                'depo' => (string) $depot->kod,
                'stokkod' => $stokkod,
                'stokmiktar' => (int) round($stok),
                'revizemiktar' => (int) round($rev),
                'kullanilabilir' => (int) round($avail),
            ];
        }

        return response()->json([
            'ok' => true,
            'siparissatirid' => $detailId,
            'stokkod' => $stokkod,
            'stokaciklama' => (string) ($detail->stokaciklama ?? ''),
            'rows' => $rows,
        ]);
    }

    public function planningTransferRevision(Request $request)
    {
        $data = $request->validate([
            'siparissatirid' => ['required', 'integer', 'min:1'],
            'items' => ['required', 'array'],
            'items.*.depo_id' => ['required', 'integer', 'min:1'],
            'items.*.miktar' => ['required', 'numeric', 'min:0'],
        ]);

        $detailId = (int) $data['siparissatirid'];
        $items = $data['items'];

        $detail = DB::table('siparis_detaylari as sd')
            ->join('siparisler as s', 'sd.siparis_id', '=', 's.id')
            ->leftJoin('urunler as u', 'sd.urun_id', '=', 'u.id')
            ->where('sd.id', $detailId)
            ->where('s.siparis_turu', 'satis')
            ->where('sd.durum', 'A')
            ->select(['sd.id', 'u.kod as stokkod'])
            ->first();

        if (!$detail || empty($detail->stokkod)) {
            return response()->json(['ok' => false, 'message' => 'Satır bulunamadı.'], 404);
        }

        $stokkod = (string) $detail->stokkod;
        $openDurumlar = ['A', 'Açık', 'AÇõŽñk'];

        $result = DB::transaction(function () use ($detailId, $stokkod, $items, $openDurumlar) {
            foreach ($items as $item) {
                $depoId = (int) ($item['depo_id'] ?? 0);
                $qty = (float) ($item['miktar'] ?? 0);
                $qty = (float) (int) round($qty);

                if ($depoId <= 0 || $qty <= 0) {
                    continue;
                }

                $stokMiktarDepot = (float) DB::table('stokenvanter')
                    ->where('depo_id', $depoId)
                    ->where('stokkod', $stokkod)
                    ->sum('stokmiktar');

                $revizeDepot = (float) DB::table('stokrevize')
                    ->where('depo_id', $depoId)
                    ->where('stokkod', $stokkod)
                    ->whereIn('durum', $openDurumlar)
                    ->lockForUpdate()
                    ->sum('miktar');

                $available = $stokMiktarDepot - $revizeDepot;
                if ($qty > $available) {
                    abort(response()->json([
                        'ok' => false,
                        'message' => 'Aktarım miktarı kullanılabilir stoktan fazla olamaz.',
                        'depo_id' => $depoId,
                        'max' => (int) round(max(0, $available)),
                    ], 422));
                }

                $existing = StockRevision::query()
                    ->where('siparissatirid', $detailId)
                    ->where('depo_id', $depoId)
                    ->whereIn('durum', $openDurumlar)
                    ->lockForUpdate()
                    ->first();

                if ($existing) {
                    $existingQty = (float) $existing->miktar;
                    $existing->miktar = $existingQty + $qty;
                    $existing->save();
                } else {
                    StockRevision::create([
                        'siparissatirid' => $detailId,
                        'depo_id' => $depoId,
                        'stokkod' => $stokkod,
                        'miktar' => $qty,
                        'durum' => 'Açık',
                    ]);
                }
            }

            $siparisRevize = (float) StockRevision::query()
                ->where('siparissatirid', $detailId)
                ->whereIn('durum', $openDurumlar)
                ->sum('miktar');

            $stokMiktarTotal = (float) DB::table('stokenvanter')
                ->where('stokkod', $stokkod)
                ->sum('stokmiktar');

            $revizeToplam = (float) StockRevision::query()
                ->where('stokkod', $stokkod)
                ->whereIn('durum', $openDurumlar)
                ->sum('miktar');

            $gercekStok = $stokMiktarTotal - $revizeToplam;

            return [
                'siparis_revize' => (int) round($siparisRevize),
                'stok_miktar' => (int) round($stokMiktarTotal),
                'revize_toplam' => (int) round($revizeToplam),
                'gercek_stok' => (int) round($gercekStok),
            ];
        });

        return response()->json(array_merge([
            'ok' => true,
            'siparissatirid' => $detailId,
            'stokkod' => $stokkod,
        ], $result));
    }

    public function planningRevisionListData(Request $request)
    {
        $detailId = (int) $request->query('siparissatirid', 0);
        if ($detailId <= 0) {
            return response()->json(['ok' => false, 'message' => 'Satır seçilmedi.'], 422);
        }

        $detail = DB::table('siparis_detaylari as sd')
            ->join('siparisler as s', 'sd.siparis_id', '=', 's.id')
            ->leftJoin('urunler as u', 'sd.urun_id', '=', 'u.id')
            ->where('sd.id', $detailId)
            ->where('s.siparis_turu', 'satis')
            ->where('sd.durum', 'A')
            ->select([
                'sd.id',
                'u.kod as stokkod',
                'u.aciklama as stokaciklama',
            ])
            ->first();

        if (!$detail || empty($detail->stokkod)) {
            return response()->json(['ok' => false, 'message' => 'Satır bulunamadı.'], 404);
        }

        $stokkod = (string) $detail->stokkod;
        $openDurumlar = ['A', 'Açık', 'AÇõŽñk'];

        $revisions = DB::table('stokrevize as sr')
            ->leftJoin('depolar as d', 'd.id', '=', 'sr.depo_id')
            ->where('sr.siparissatirid', $detailId)
            ->whereIn('sr.durum', $openDurumlar)
            ->orderByDesc('sr.created_at')
            ->get([
                'sr.id',
                'sr.depo_id',
                'd.kod as depo_kod',
                'sr.miktar',
                'sr.created_at',
            ]);

        $rows = [];
        foreach ($revisions as $rev) {
            $depoId = $rev->depo_id !== null ? (int) $rev->depo_id : null;
            $currentQty = (float) ($rev->miktar ?? 0);

            if ($depoId) {
                $stokDepot = (float) DB::table('stokenvanter')
                    ->where('depo_id', $depoId)
                    ->where('stokkod', $stokkod)
                    ->sum('stokmiktar');

                $totalDepot = (float) DB::table('stokrevize')
                    ->where('depo_id', $depoId)
                    ->where('stokkod', $stokkod)
                    ->whereIn('durum', $openDurumlar)
                    ->sum('miktar');

                $max = $stokDepot - ($totalDepot - $currentQty);
            } else {
                $stokTotal = (float) DB::table('stokenvanter')
                    ->where('stokkod', $stokkod)
                    ->sum('stokmiktar');

                $totalAll = (float) DB::table('stokrevize')
                    ->where('stokkod', $stokkod)
                    ->whereIn('durum', $openDurumlar)
                    ->sum('miktar');

                $max = $stokTotal - ($totalAll - $currentQty);
            }

            $rows[] = [
                'id' => (int) $rev->id,
                'depo_id' => $depoId,
                'depo_kod' => (string) ($rev->depo_kod ?? '-'),
                'miktar' => (int) round($currentQty),
                'created_at' => $rev->created_at ? (string) $rev->created_at : null,
                'max' => (int) round(max(0.0, $max)),
            ];
        }

        return response()->json([
            'ok' => true,
            'siparissatirid' => $detailId,
            'stokkod' => $stokkod,
            'stokaciklama' => (string) ($detail->stokaciklama ?? ''),
            'rows' => $rows,
        ]);
    }

    public function planningRevisionListSave(Request $request)
    {
        $data = $request->validate([
            'siparissatirid' => ['required', 'integer', 'min:1'],
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'integer', 'min:1'],
            'items.*.miktar' => ['required', 'numeric', 'min:0'],
        ]);

        $detailId = (int) $data['siparissatirid'];
        $items = $data['items'];

        $detail = DB::table('siparis_detaylari as sd')
            ->join('siparisler as s', 'sd.siparis_id', '=', 's.id')
            ->leftJoin('urunler as u', 'sd.urun_id', '=', 'u.id')
            ->where('sd.id', $detailId)
            ->where('s.siparis_turu', 'satis')
            ->where('sd.durum', 'A')
            ->select(['sd.id', 'u.kod as stokkod'])
            ->first();

        if (!$detail || empty($detail->stokkod)) {
            return response()->json(['ok' => false, 'message' => 'Satır bulunamadı.'], 404);
        }

        $stokkod = (string) $detail->stokkod;
        $openDurumlar = ['A', 'Açık', 'AÇõŽñk'];

        $result = DB::transaction(function () use ($detailId, $stokkod, $items, $openDurumlar) {
            foreach ($items as $item) {
                $id = (int) ($item['id'] ?? 0);
                $newQty = (float) ($item['miktar'] ?? 0);
                $newQty = (float) (int) round($newQty);

                $rev = StockRevision::query()
                    ->where('id', $id)
                    ->where('siparissatirid', $detailId)
                    ->whereIn('durum', $openDurumlar)
                    ->lockForUpdate()
                    ->first();

                if (!$rev) {
                    continue;
                }

                $depoId = $rev->depo_id ? (int) $rev->depo_id : null;
                $currentQty = (float) $rev->miktar;

                if ($depoId) {
                    $stokDepot = (float) DB::table('stokenvanter')
                        ->where('depo_id', $depoId)
                        ->where('stokkod', $stokkod)
                        ->sum('stokmiktar');

                    $totalDepot = (float) DB::table('stokrevize')
                        ->where('depo_id', $depoId)
                        ->where('stokkod', $stokkod)
                        ->whereIn('durum', $openDurumlar)
                        ->lockForUpdate()
                        ->sum('miktar');

                    $max = $stokDepot - ($totalDepot - $currentQty);
                } else {
                    $stokTotal = (float) DB::table('stokenvanter')
                        ->where('stokkod', $stokkod)
                        ->sum('stokmiktar');

                    $totalAll = (float) DB::table('stokrevize')
                        ->where('stokkod', $stokkod)
                        ->whereIn('durum', $openDurumlar)
                        ->lockForUpdate()
                        ->sum('miktar');

                    $max = $stokTotal - ($totalAll - $currentQty);
                }

                $max = max(0.0, $max);
                if ($newQty > $max) {
                    abort(response()->json([
                        'ok' => false,
                        'message' => 'Revize miktarı kullanılabilir stoktan fazla olamaz.',
                        'id' => $id,
                        'max' => (int) round($max),
                    ], 422));
                }

                if ($newQty <= 0) {
                    $rev->delete();
                } else {
                    $rev->miktar = $newQty;
                    $rev->save();
                }
            }

            $siparisRevize = (float) StockRevision::query()
                ->where('siparissatirid', $detailId)
                ->whereIn('durum', $openDurumlar)
                ->sum('miktar');

            $stokMiktarTotal = (float) DB::table('stokenvanter')
                ->where('stokkod', $stokkod)
                ->sum('stokmiktar');

            $revizeToplam = (float) StockRevision::query()
                ->where('stokkod', $stokkod)
                ->whereIn('durum', $openDurumlar)
                ->sum('miktar');

            $gercekStok = $stokMiktarTotal - $revizeToplam;

            return [
                'siparis_revize' => (int) round($siparisRevize),
                'stok_miktar' => (int) round($stokMiktarTotal),
                'revize_toplam' => (int) round($revizeToplam),
                'gercek_stok' => (int) round($gercekStok),
            ];
        });

        return response()->json(array_merge([
            'ok' => true,
            'siparissatirid' => $detailId,
            'stokkod' => $stokkod,
        ], $result));
    }

    public function planningRevisionListDelete(Request $request)
    {
        $data = $request->validate([
            'siparissatirid' => ['required', 'integer', 'min:1'],
            'id' => ['required', 'integer', 'min:1'],
        ]);

        $detailId = (int) $data['siparissatirid'];
        $id = (int) $data['id'];

        $rev = StockRevision::query()
            ->where('id', $id)
            ->where('siparissatirid', $detailId)
            ->first();

        if (!$rev) {
            return response()->json(['ok' => false, 'message' => 'Revize satırı bulunamadı.'], 404);
        }

        $stokkod = (string) $rev->stokkod;
        $openDurumlar = ['A', 'Açık', 'AÇõŽñk'];

        $result = DB::transaction(function () use ($detailId, $stokkod, $id, $openDurumlar) {
            StockRevision::query()
                ->where('id', $id)
                ->where('siparissatirid', $detailId)
                ->whereIn('durum', $openDurumlar)
                ->delete();

            $siparisRevize = (float) StockRevision::query()
                ->where('siparissatirid', $detailId)
                ->whereIn('durum', $openDurumlar)
                ->sum('miktar');

            $stokMiktarTotal = (float) DB::table('stokenvanter')
                ->where('stokkod', $stokkod)
                ->sum('stokmiktar');

            $revizeToplam = (float) StockRevision::query()
                ->where('stokkod', $stokkod)
                ->whereIn('durum', $openDurumlar)
                ->sum('miktar');

            $gercekStok = $stokMiktarTotal - $revizeToplam;

            return [
                'siparis_revize' => (int) round($siparisRevize),
                'stok_miktar' => (int) round($stokMiktarTotal),
                'revize_toplam' => (int) round($revizeToplam),
                'gercek_stok' => (int) round($gercekStok),
            ];
        });

        return response()->json(array_merge([
            'ok' => true,
            'siparissatirid' => $detailId,
            'stokkod' => $stokkod,
        ], $result));
    }

    public function updatePlanning(Request $request, Siparis $siparis)
    {
        if (($siparis->siparis_turu ?? 'alim') !== 'satis') {
            abort(404);
        }

        $data = $request->validate([
            'planlama_durum' => ['nullable', 'in:beklemede,kismi_yapildi,yapildi'],
            'planlanan_miktar' => ['nullable', 'numeric', 'min:0'],
        ]);

        $siparis->update($data);

        return back();
    }

    public function create(Request $request)
    {
        $tur = $this->normalizeTur($request->query('tur'));
        $active = $tur === 'alim' ? 'purchase-orders' : 'sales-orders';

        $durumlar = $this->durumlar();

        $firms = Firm::with('authorities')
            ->orderBy('carikod')
            ->get();

        $products = Product::where('pasif', false)
            ->orderBy('kod')
            ->get();

        $islemTurleri = IslemTuru::orderBy('ad')->get();
        $projects = Project::where('pasif', false)->orderBy('kod')->get();

        $start = $tur === 'satis' ? 20000001 : 10000001;

        $maxSiparisNo = Siparis::query()
            ->where('siparis_turu', $tur)
            ->select('siparis_no')
            ->get()
            ->pluck('siparis_no')
            ->filter(fn ($v) => is_numeric($v))
            ->map(fn ($v) => (int) $v)
            ->max();

        $nextSiparisNo = $maxSiparisNo ? (string) ($maxSiparisNo + 1) : (string) $start;

        $prefillLines = null;
        $selectedFirm = null;

        if ($tur === 'alim') {
            $prefill = session()->pull('planning_purchase_prefill');
            if (is_array($prefill)) {
                $header = (array) ($prefill['header'] ?? []);
                $prefillLines = $prefill['lines'] ?? null;

                $flash = [];
                if (isset($header['carikod'])) $flash['carikod'] = $header['carikod'];
                if (isset($header['cariaciklama'])) $flash['cariaciklama'] = $header['cariaciklama'];
                if (isset($header['tarih'])) $flash['tarih'] = $header['tarih'];
                if (count($flash) > 0) {
                    $request->session()->flashInput($flash);
                }

                if (!empty($flash['carikod'])) {
                    $selectedFirm = Firm::where('carikod', $flash['carikod'])->first();
                }
            }
        }

        return view('orders.create', [
            'durumlar'     => $durumlar,
            'firms'        => $firms,
            'products'     => $products,
            'islemTurleri' => $islemTurleri,
            'projects'     => $projects,
            'nextSiparisNo' => $nextSiparisNo,
            'tur'          => $tur,
            'active'       => $active,
            'selectedFirm' => $selectedFirm,
            'prefillLines' => $prefillLines,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedHeader($request);
        $data['siparis_turu'] = $this->normalizeTur($data['siparis_turu'] ?? $request->query('tur'));

        if (Auth::check()) {
            $user = Auth::user();
            $fullName = trim(($user->ad ?? '') . ' ' . ($user->soyad ?? ''));
            $data['hazirlayan'] = $fullName !== '' ? $fullName : ($user->mail ?? null);
        }

        $lines = $request->input('lines', []);

        DB::transaction(function () use ($data, $lines) {
            $start = ($data['siparis_turu'] ?? 'alim') === 'satis' ? 20000001 : 10000001;

            $maxSiparisNo = Siparis::query()
                ->where('siparis_turu', $data['siparis_turu'] ?? 'alim')
                ->select('siparis_no')
                ->lockForUpdate()
                ->get()
                ->pluck('siparis_no')
                ->filter(fn ($v) => is_numeric($v))
                ->map(fn ($v) => (int) $v)
                ->max();

            $data['siparis_no'] = $maxSiparisNo ? (string) ($maxSiparisNo + 1) : (string) $start;

            $siparis = Siparis::create($data);

            $toplam = 0;
            $iskontoToplam = 0;
            $kdvToplam = 0;
            $headerDoviz = strtoupper(trim((string) ($data['siparis_doviz'] ?? 'TL')));
            $headerKur = (float) ($data['siparis_kur'] ?? 0);

            foreach ($lines as $line) {
                $satirAciklama = trim($line['satir_aciklama'] ?? '');
                $urunId = $line['urun_id'] ?? null;
                $miktar = (float) ($line['miktar'] ?? 0);
                $birimFiyat = (float) ($line['birim_fiyat'] ?? 0);

                if ($satirAciklama === '' && $miktar <= 0 && $birimFiyat <= 0) {
                    continue;
                }

                $birim = $line['birim'] ?? null;
                $doviz = strtoupper(trim((string) ($line['doviz'] ?? 'TL')));
                $kur = isset($line['kur']) ? (float) $line['kur'] : 0.0;
                if ($doviz === 'TL') {
                    $kur = 1.0;
                }

                $iskontolar = [];
                for ($i = 1; $i <= 6; $i++) {
                    $iskontolar[$i] = isset($line["iskonto{$i}"]) ? (float) $line["iskonto{$i}"] : 0.0;
                }

                $durum = strtoupper(trim((string) ($line['durum'] ?? 'A')));
                if (!in_array($durum, ['A', 'K'], true)) {
                    $durum = 'A';
                }

                $kdvOrani = isset($line['kdv_orani']) ? (float) $line['kdv_orani'] : 0.0;
                $kdvDurum = $line['kdv_durum'] ?? 'H';

                $lineRate = 1.0;
                if ($doviz !== 'TL') {
                    $lineRate = $kur;
                    if ($lineRate <= 0 && $headerDoviz === $doviz && $headerKur > 0) {
                        $lineRate = $headerKur;
                    }
                    if ($lineRate <= 0) {
                        $lineRate = 0.0;
                    }
                }

                $brut = ($miktar * $birimFiyat) * $lineRate;
                $net = $brut;

                foreach ($iskontolar as $oran) {
                    if ($oran > 0) {
                        $net -= $net * ($oran / 100);
                    }
                }

                $iskontoTutar = $brut - $net;

                if ($kdvOrani > 0 && $net > 0) {
                    if ($kdvDurum === 'H') {
                        $kdvTutar = $net * ($kdvOrani / 100);
                        $satirToplam = $net + $kdvTutar;
                    } elseif ($kdvDurum === 'E' || $kdvDurum === 'D') {
                        $oran = $kdvOrani / 100;
                        $kdvTutar = $net - ($net / (1 + $oran));
                        $satirToplam = $net;
                    } else {
                        $kdvTutar = 0.0;
                        $satirToplam = $net;
                    }
                } else {
                    $kdvTutar = 0.0;
                    $satirToplam = $net;
                }

                $detay = SiparisDetay::create([
                    'siparis_id'     => $siparis->id,
                    'urun_id'        => $urunId,
                    'satir_aciklama' => $satirAciklama,
                    'durum'          => $durum,
                    'miktar'         => $miktar,
                    'gelen'          => (float) ($line['gelen'] ?? 0),
                    'birim'          => $birim,
                    'birim_fiyat'    => $birimFiyat,
                    'doviz'          => $doviz,
                    'kur'            => $kur,
                    'iskonto1'       => $iskontolar[1],
                    'iskonto2'       => $iskontolar[2],
                    'iskonto3'       => $iskontolar[3],
                    'iskonto4'       => $iskontolar[4],
                    'iskonto5'       => $iskontolar[5],
                    'iskonto6'       => $iskontolar[6],
                    'iskonto_tutar'  => $iskontoTutar,
                    'kdv_orani'      => $kdvOrani,
                    'kdv_tutar'      => $kdvTutar,
                    'satir_toplam'   => $satirToplam,
                ]);

                if (($data['siparis_turu'] ?? 'alim') === 'alim') {
                    $raw = $line['satis_detay_ids'] ?? null;
                    if (is_string($raw)) {
                        $decoded = json_decode($raw, true);
                        $raw = is_array($decoded) ? $decoded : null;
                    }

                    if (is_array($raw) && count($raw) > 0) {
                        $rowsToInsert = [];
                        foreach ($raw as $satisId) {
                            $sid = (int) $satisId;
                            if ($sid <= 0) continue;
                            $rowsToInsert[] = [
                                'alim_detay_id' => $detay->id,
                                'satis_detay_id' => $sid,
                                'miktar' => null,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                        if (count($rowsToInsert) > 0) {
                            DB::table('siparis_satir_eslestirmeleri')->insertOrIgnore($rowsToInsert);
                        }
                    }
                }

                $toplam += $brut;
                $iskontoToplam += $iskontoTutar;
                $kdvToplam += $kdvTutar;
            }

            $genelToplam = $toplam - $iskontoToplam + $kdvToplam;

            $siparis->update([
                'toplam'        => $toplam,
                'iskonto_tutar' => $iskontoToplam,
                'kdv'           => $kdvToplam,
                'genel_toplam'  => $genelToplam,
            ]);
        });

        $tur = $this->normalizeTur($data['siparis_turu'] ?? null);

        return redirect()->route('orders.index', ['tur' => $tur])
            ->with('status', 'Sipariş oluşturuldu.');
    }

    public function edit(Request $request, Siparis $siparis)
    {
        $tur = $this->normalizeTur($siparis->siparis_turu);
        $active = $tur === 'alim' ? 'purchase-orders' : 'sales-orders';

        $durumlar = $this->durumlar();

        $firms = Firm::with('authorities')
            ->orderBy('carikod')
            ->get();

        $products = Product::where('pasif', false)
            ->orderBy('kod')
            ->get();

        $islemTurleri = IslemTuru::orderBy('ad')->get();
        $projects = Project::where('pasif', false)->orderBy('kod')->get();

        $siparis->load(['detaylar.urun', 'detaylar.alimEslestirmeleri.satisDetay.siparis', 'islemTuru', 'proje']);

        if ($tur === 'alim') {
            foreach ($siparis->detaylar as $detay) {
                $links = [];
                $ids = [];
                foreach ($detay->alimEslestirmeleri as $eslestirme) {
                    $satisDetay = $eslestirme->satisDetay;
                    $satisSiparis = $satisDetay?->siparis;
                    if (!$satisDetay || !$satisSiparis) {
                        continue;
                    }
                    $ids[] = $satisDetay->id;
                    $links[] = [
                        'carikod' => $satisSiparis->carikod ?? null,
                        'siparis_no' => $satisSiparis->siparis_no ?? null,
                        'tarih' => $satisSiparis->tarih ? $satisSiparis->tarih->toDateString() : null,
                        'miktar' => $eslestirme->miktar ?? $satisDetay->miktar ?? null,
                    ];
                }
                $detay->setAttribute('satis_detay_ids', $ids);
                $detay->setAttribute('sales_links', $links);
            }
        }

        $selectedFirm = null;
        if ($siparis->carikod) {
            $selectedFirm = Firm::where('carikod', $siparis->carikod)->first();
        }

        return view('orders.create', [
            'durumlar'      => $durumlar,
            'firms'         => $firms,
            'products'      => $products,
            'islemTurleri'  => $islemTurleri,
            'projects'      => $projects,
            'nextSiparisNo' => $siparis->siparis_no,
            'siparis'       => $siparis,
            'selectedFirm'  => $selectedFirm,
            'tur'           => $tur,
            'active'        => $active,
        ]);
    }

    public function update(Request $request, Siparis $siparis)
    {
        $data = $this->validatedHeader($request);
        $data['siparis_turu'] = $this->normalizeTur($data['siparis_turu'] ?? $siparis->siparis_turu);

        if (Auth::check()) {
            $user = Auth::user();
            $fullName = trim(($user->ad ?? '') . ' ' . ($user->soyad ?? ''));
            $data['hazirlayan'] = $fullName !== '' ? $fullName : ($user->mail ?? null);
        }

        $lines = $request->input('lines', []);

        DB::transaction(function () use ($data, $lines, $siparis) {
            $siparis->update($data);

            $siparis->detaylar()->delete();

            $toplam = 0;
            $iskontoToplam = 0;
            $kdvToplam = 0;
            $headerDoviz = strtoupper(trim((string) ($data['siparis_doviz'] ?? 'TL')));
            $headerKur = (float) ($data['siparis_kur'] ?? 0);

            foreach ($lines as $line) {
                $satirAciklama = trim($line['satir_aciklama'] ?? '');
                $urunId = $line['urun_id'] ?? null;
                $miktar = (float) ($line['miktar'] ?? 0);
                $birimFiyat = (float) ($line['birim_fiyat'] ?? 0);

                if ($satirAciklama === '' && $miktar <= 0 && $birimFiyat <= 0) {
                    continue;
                }

                $birim = $line['birim'] ?? null;
                $doviz = strtoupper(trim((string) ($line['doviz'] ?? 'TL')));
                $kur = isset($line['kur']) ? (float) $line['kur'] : 0.0;
                if ($doviz === 'TL') {
                    $kur = 1.0;
                }

                $iskontolar = [];
                for ($i = 1; $i <= 6; $i++) {
                    $iskontolar[$i] = isset($line["iskonto{$i}"]) ? (float) $line["iskonto{$i}"] : 0.0;
                }

                $durum = strtoupper(trim((string) ($line['durum'] ?? 'A')));
                if (!in_array($durum, ['A', 'K'], true)) {
                    $durum = 'A';
                }

                $kdvOrani = isset($line['kdv_orani']) ? (float) $line['kdv_orani'] : 0.0;
                $kdvDurum = $line['kdv_durum'] ?? 'H';

                $lineRate = 1.0;
                if ($doviz !== 'TL') {
                    $lineRate = $kur;
                    if ($lineRate <= 0 && $headerDoviz === $doviz && $headerKur > 0) {
                        $lineRate = $headerKur;
                    }
                    if ($lineRate <= 0) {
                        $lineRate = 0.0;
                    }
                }

                $brut = ($miktar * $birimFiyat) * $lineRate;
                $net = $brut;

                foreach ($iskontolar as $oran) {
                    if ($oran > 0) {
                        $net -= $net * ($oran / 100);
                    }
                }

                $iskontoTutar = $brut - $net;

                if ($kdvOrani > 0 && $net > 0) {
                    if ($kdvDurum === 'H') {
                        $kdvTutar = $net * ($kdvOrani / 100);
                        $satirToplam = $net + $kdvTutar;
                    } elseif ($kdvDurum === 'E' || $kdvDurum === 'D') {
                        $oran = $kdvOrani / 100;
                        $kdvTutar = $net - ($net / (1 + $oran));
                        $satirToplam = $net;
                    } else {
                        $kdvTutar = 0.0;
                        $satirToplam = $net;
                    }
                } else {
                    $kdvTutar = 0.0;
                    $satirToplam = $net;
                }

                $detay = SiparisDetay::create([
                    'siparis_id'     => $siparis->id,
                    'urun_id'        => $urunId,
                    'satir_aciklama' => $satirAciklama,
                    'durum'          => $durum,
                    'miktar'         => $miktar,
                    'gelen'          => (float) ($line['gelen'] ?? 0),
                    'birim'          => $birim,
                    'birim_fiyat'    => $birimFiyat,
                    'doviz'          => $doviz,
                    'kur'            => $kur,
                    'iskonto1'       => $iskontolar[1],
                    'iskonto2'       => $iskontolar[2],
                    'iskonto3'       => $iskontolar[3],
                    'iskonto4'       => $iskontolar[4],
                    'iskonto5'       => $iskontolar[5],
                    'iskonto6'       => $iskontolar[6],
                    'iskonto_tutar'  => $iskontoTutar,
                    'kdv_orani'      => $kdvOrani,
                    'kdv_tutar'      => $kdvTutar,
                    'satir_toplam'   => $satirToplam,
                ]);

                if (($data['siparis_turu'] ?? 'alim') === 'alim') {
                    $raw = $line['satis_detay_ids'] ?? null;
                    if (is_string($raw)) {
                        $decoded = json_decode($raw, true);
                        $raw = is_array($decoded) ? $decoded : null;
                    }

                    if (is_array($raw) && count($raw) > 0) {
                        $rowsToInsert = [];
                        foreach ($raw as $satisId) {
                            $sid = (int) $satisId;
                            if ($sid <= 0) continue;
                            $rowsToInsert[] = [
                                'alim_detay_id' => $detay->id,
                                'satis_detay_id' => $sid,
                                'miktar' => null,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                        if (count($rowsToInsert) > 0) {
                            DB::table('siparis_satir_eslestirmeleri')->insertOrIgnore($rowsToInsert);
                        }
                    }
                }

                $toplam += $brut;
                $iskontoToplam += $iskontoTutar;
                $kdvToplam += $kdvTutar;
            }

            $genelToplam = $toplam - $iskontoToplam + $kdvToplam;

            $siparis->update([
                'toplam'        => $toplam,
                'iskonto_tutar' => $iskontoToplam,
                'kdv'           => $kdvToplam,
                'genel_toplam'  => $genelToplam,
            ]);
        });

        return redirect()->route('orders.index', ['tur' => $data['siparis_turu']])
            ->with('status', 'Sipariş güncellendi.');
    }

    public function destroy(Request $request, Siparis $siparis)
    {
        $tur = $this->normalizeTur($siparis->siparis_turu);
        $teklifNo = trim((string) ($siparis->teklif_no ?? ''));

        DB::transaction(function () use ($siparis, $teklifNo) {
            $siparis->delete();

            if ($teklifNo === '') {
                return;
            }

            $hasOtherOrders = Siparis::query()
                ->where('teklif_no', $teklifNo)
                ->exists();

            if ($hasOtherOrders) {
                return;
            }

            $teklif = Teklif::query()
                ->where('teklif_no', $teklifNo)
                ->orderByDesc('id')
                ->first();

            if ($teklif) {
                $teklif->update(['teklif_durum' => 'Taslak']);
            }
        });

        return redirect()->route('orders.index', ['tur' => $tur])
            ->with('status', 'Sipariş silindi.');
    }

    protected function validatedHeader(Request $request): array
    {
        return $request->validate([
            'siparis_turu'      => ['nullable', 'string', 'in:alim,satis'],
            'carikod'           => ['required', 'string', 'max:50'],
            'cariaciklama'      => ['required', 'string', 'max:255'],
            'tarih'             => ['required', 'date'],
            'gecerlilik_tarihi' => ['nullable', 'date'],
            'siparis_no'        => ['required', 'string', 'max:50'],
            'teklif_no'         => ['nullable', 'string', 'max:50'],
            'aciklama'          => ['nullable', 'string'],
            'siparis_durum'     => ['nullable', 'string', 'max:50'],
            'planlama_durum'    => ['nullable', 'string', 'in:beklemede,kismi_yapildi,yapildi'],
            'planlanan_miktar'  => ['nullable', 'numeric', 'min:0'],
            'onay_durum'        => ['nullable', 'string', 'max:50'],
            'onay_tarihi'       => ['nullable', 'date'],
            'yetkili_personel'  => ['nullable', 'string', 'max:150'],
            'hazirlayan'        => ['nullable', 'string', 'max:150'],
            'islem_turu_id'     => ['nullable', 'integer', 'exists:islem_turleri,id'],
            'proje_id'          => ['nullable', 'integer', 'exists:projeler,id'],
            'siparis_doviz'     => ['nullable', 'string', 'max:3', 'in:TL,USD,EUR'],
            'siparis_kur'       => ['nullable', 'numeric', 'min:0'],
        ]);
    }

    protected function durumlar(): array
    {
        return [
            'hepsi'      => 'Hepsi',
            'Taslak'     => 'Taslak',
            'Gönderildi' => 'Gönderildi',
            'Kabul'      => 'Kabul',
            'Reddedildi' => 'Reddedildi',
        ];
    }

    protected function normalizeTur(?string $tur): string
    {
        $t = strtolower(trim((string) $tur));
        return $t === 'satis' ? 'satis' : 'alim';
    }
}
