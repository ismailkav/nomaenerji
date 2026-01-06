<?php

namespace App\Http\Controllers;

use App\Models\Firm;
use App\Models\Depot;
use App\Models\Fatura;
use App\Models\FaturaDetay;
use App\Models\IslemTuru;
use App\Models\Product;
use App\Models\Project;
use App\Models\SiparisDetay;
use App\Models\StockInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InvoiceController extends Controller
{
    private function stockMultiplierForInvoiceTur(string $tur): float
    {
        return in_array($tur, ['satis', 'alim-iade'], true) ? -1.0 : 1.0;
    }

    private function applyStockInventoryDelta(int $depoId, string $stokKod, float $delta): void
    {
        $stokKod = trim($stokKod);
        if ($depoId <= 0 || $stokKod === '' || abs($delta) < 0.0000001) {
            return;
        }

        $inventory = StockInventory::where('depo_id', $depoId)
            ->where('stokkod', $stokKod)
            ->lockForUpdate()
            ->first();

        if ($inventory) {
            $current = (float) ($inventory->stokmiktar ?? 0);
            $inventory->stokmiktar = $current + $delta;
            $inventory->save();
            return;
        }

        StockInventory::create([
            'depo_id' => $depoId,
            'stokkod' => $stokKod,
            'stokmiktar' => $delta,
        ]);
    }

    public function index(Request $request)
    {
        $tur = $this->normalizeTur($request->query('tur'));

        $query = Fatura::query()
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

        return view('orders.index', [
            'siparisler' => $siparisler,
            'durumlar' => $durumlar,
            'islemTurleri' => $islemTurleri,
            'projects' => $projects,
            'tur' => $tur,
            'active' => $this->activeKey($tur),
            'resource' => 'invoices',
            'pageTitle' => 'Faturalar',
            'pageHeading' => $this->heading($tur),
            'newButtonText' => 'Yeni Fatura',
            'enablePlanningActions' => false,
        ]);
    }

    public function create(Request $request)
    {
        $tur = $this->normalizeTur($request->query('tur'));

        $durumlar = $this->durumlar();

        $firms = Firm::with('authorities')
            ->orderBy('carikod')
            ->get();

        $products = Product::where('pasif', false)
            ->orderBy('kod')
            ->get();

        $islemTurleri = IslemTuru::orderBy('ad')->get();
        $projects = Project::where('pasif', false)->orderBy('kod')->get();
        $depots = Depot::where('pasif', false)->orderBy('kod')->get();

        return view('orders.create', [
            'durumlar' => $durumlar,
            'firms' => $firms,
            'products' => $products,
            'islemTurleri' => $islemTurleri,
            'projects' => $projects,
            'depots' => $depots,
            'nextSiparisNo' => $this->nextNo($tur),
            'tur' => $tur,
            'active' => $this->activeKey($tur),
            'selectedFirm' => null,
            'selectedDepot' => null,
            'prefillLines' => null,
            'resource' => 'invoices',
            'pageTitle' => 'Fatura',
            'pageHeading' => $this->heading($tur),
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
        $tur = $data['siparis_turu'];
        $orderTur = $this->orderTurFromInvoiceTur($tur);
        $depoId = (int) ($data['depo_id'] ?? 0);
        $stockMultiplier = $this->stockMultiplierForInvoiceTur($tur);

        $fatura = DB::transaction(function () use ($data, $lines, $tur, $orderTur, $depoId, $stockMultiplier) {
            $start = $this->startNo($tur);

            $maxNo = Fatura::query()
                ->where('siparis_turu', $tur)
                ->select('siparis_no')
                ->lockForUpdate()
                ->get()
                ->pluck('siparis_no')
                ->filter(fn ($v) => is_numeric($v))
                ->map(fn ($v) => (int) $v)
                ->max();

            $data['siparis_no'] = $maxNo ? (string) ($maxNo + 1) : (string) $start;

            $siparis = Fatura::create($data);

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
                $stokKod = trim((string) ($line['stok_kod'] ?? ''));
                if ($stokKod === '' && $urunId) {
                    $stokKod = trim((string) (Product::whereKey($urunId)->value('kod') ?? ''));
                }
                $siparisDetayId = $line['siparis_detay_id'] ?? null;
                $siparisDetayId = is_numeric($siparisDetayId) ? (int) $siparisDetayId : null;
                $siparisDetay = null;

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

                if ($siparisDetayId) {
                    $siparisDetay = SiparisDetay::query()
                        ->with('siparis')
                        ->lockForUpdate()
                        ->whereKey($siparisDetayId)
                        ->first();

                    if (!$siparisDetay || !$siparisDetay->siparis) {
                        throw ValidationException::withMessages(['lines' => 'Geçersiz sipariş satırı seçildi.']);
                    }

                    if ((string) $siparisDetay->siparis->carikod !== (string) ($data['carikod'] ?? '')) {
                        throw ValidationException::withMessages(['lines' => 'Sipariş satırı seçili firmaya ait değil.']);
                    }

                    if ((string) $siparisDetay->siparis->siparis_turu !== $orderTur) {
                        throw ValidationException::withMessages(['lines' => 'Sipariş satırı, fatura türü ile uyumlu değil.']);
                    }

                    if ((string) $siparisDetay->durum !== 'A') {
                        throw ValidationException::withMessages(['lines' => 'Sipariş satırı açık değil.']);
                    }

                    if ($siparisDetay->urun_id && $urunId && (int) $siparisDetay->urun_id !== (int) $urunId) {
                        throw ValidationException::withMessages(['lines' => 'Sipariş satırı ürünü ile fatura satırı ürünü uyuşmuyor.']);
                    }

                    $kalan = max(0.0, (float) $siparisDetay->miktar - (float) $siparisDetay->gelen);
                    if ($miktar - $kalan > 0.0001) {
                        throw ValidationException::withMessages(['lines' => 'Aktarım miktarı, sipariş kalan miktarını aşıyor.']);
                    }
                }

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

                $projeKodu = trim((string) ($line['proje_kodu'] ?? ''));
                if ($projeKodu === '' && $siparisDetay) {
                    $projeKodu = trim((string) ($siparisDetay->proje_kodu ?? ($siparisDetay->siparis?->proje?->kod ?? '')));
                }
                $projeKodu = $projeKodu !== '' ? $projeKodu : null;

                $createdDetay = FaturaDetay::create([
                    'fatura_id' => $siparis->id,
                    'urun_id' => $urunId,
                    'proje_kodu' => $projeKodu,
                    'stokkod' => $stokKod !== '' ? $stokKod : null,
                    'satir_aciklama' => $satirAciklama,
                    'durum' => $durum,
                    'miktar' => $miktar,
                    'birim' => $birim,
                    'birim_fiyat' => $birimFiyat,
                    'doviz' => $doviz,
                    'kur' => $kur,
                    'iskonto1' => $iskontolar[1],
                    'iskonto2' => $iskontolar[2],
                    'iskonto3' => $iskontolar[3],
                    'iskonto4' => $iskontolar[4],
                    'iskonto5' => $iskontolar[5],
                    'iskonto6' => $iskontolar[6],
                    'iskonto_tutar' => $iskontoTutar,
                    'kdv_orani' => $kdvOrani,
                    'kdv_tutar' => $kdvTutar,
                    'satir_toplam' => $satirToplam,
                    'siparis_detay_id' => $siparisDetayId,
                ]);

                if ($siparisDetayId) {
                    DB::table('fatura_siparis_satir_eslestirmeleri')->insert([
                        'fatura_detay_id' => $createdDetay->id,
                        'siparis_detay_id' => $siparisDetayId,
                        'miktar' => $miktar,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    if ($siparisDetay) {
                        $newGelen = (float) $siparisDetay->gelen + $miktar;
                        $closeLine = $newGelen + 0.0001 >= (float) $siparisDetay->miktar;
                        $siparisDetay->update([
                            'gelen' => $newGelen,
                            'durum' => $closeLine ? 'K' : 'A',
                        ]);
                    }
                }

                if ($depoId > 0) {
                    if ($stokKod !== '') {
                        $this->applyStockInventoryDelta($depoId, $stokKod, $stockMultiplier * $miktar);
                    }
                }

                $toplam += $brut;
                $iskontoToplam += $iskontoTutar;
                $kdvToplam += $kdvTutar;
            }

            $genelToplam = $toplam - $iskontoToplam + $kdvToplam;

            $siparis->update([
                'toplam' => $toplam,
                'iskonto_tutar' => $iskontoToplam,
                'kdv' => $kdvToplam,
                'genel_toplam' => $genelToplam,
            ]);

            return $siparis;
        });

        return redirect()->route('invoices.edit', $fatura)
            ->with('status', 'Fatura oluşturuldu.');
    }

    public function edit(Request $request, Fatura $fatura)
    {
        $tur = $this->normalizeTur($fatura->siparis_turu);

        $durumlar = $this->durumlar();

        $firms = Firm::with('authorities')
            ->orderBy('carikod')
            ->get();

        $products = Product::where('pasif', false)
            ->orderBy('kod')
            ->get();

        $islemTurleri = IslemTuru::orderBy('ad')->get();
        $projects = Project::where('pasif', false)->orderBy('kod')->get();
        $depots = Depot::where('pasif', false)->orderBy('kod')->get();

        $fatura->load(['detaylar.urun', 'islemTuru', 'proje']);

        $selectedFirm = null;
        if ($fatura->carikod) {
            $selectedFirm = Firm::where('carikod', $fatura->carikod)->first();
        }

        $selectedDepot = null;
        if ($fatura->depo_id) {
            $selectedDepot = Depot::whereKey($fatura->depo_id)->first();
        }

        return view('orders.create', [
            'durumlar' => $durumlar,
            'firms' => $firms,
            'products' => $products,
            'islemTurleri' => $islemTurleri,
            'projects' => $projects,
            'depots' => $depots,
            'nextSiparisNo' => $fatura->siparis_no,
            'siparis' => $fatura,
            'selectedFirm' => $selectedFirm,
            'selectedDepot' => $selectedDepot,
            'tur' => $tur,
            'active' => $this->activeKey($tur),
            'resource' => 'invoices',
            'pageTitle' => 'Fatura',
            'pageHeading' => $this->heading($tur),
        ]);
    }

    public function update(Request $request, Fatura $fatura)
    {
        $data = $this->validatedHeader($request);
        $data['siparis_turu'] = $this->normalizeTur($data['siparis_turu'] ?? $fatura->siparis_turu);
        $orderTur = $this->orderTurFromInvoiceTur($data['siparis_turu']);
        $newDepotId = (int) ($data['depo_id'] ?? 0);
        $newStockMultiplier = $this->stockMultiplierForInvoiceTur($data['siparis_turu']);

        if (Auth::check()) {
            $user = Auth::user();
            $fullName = trim(($user->ad ?? '') . ' ' . ($user->soyad ?? ''));
            $data['hazirlayan'] = $fullName !== '' ? $fullName : ($user->mail ?? null);
        }

        $lines = $request->input('lines', []);

        DB::transaction(function () use ($data, $lines, $fatura, $orderTur, $newDepotId, $newStockMultiplier) {
            $oldDepotId = (int) ($fatura->depo_id ?? 0);
            $oldStockMultiplier = $this->stockMultiplierForInvoiceTur($this->normalizeTur($fatura->siparis_turu));
            $oldDetails = $fatura->detaylar()->with('urun:id,kod')->get();

            $oldTotals = [];
            foreach ($oldDetails as $oldDetail) {
                $stokKod = trim((string) ($oldDetail->stokkod ?? ''));
                if ($stokKod === '') {
                    $stokKod = trim((string) ($oldDetail->urun?->kod ?? ''));
                }
                if ($stokKod === '') {
                    continue;
                }
                $oldTotals[$stokKod] = ($oldTotals[$stokKod] ?? 0.0) + (float) ($oldDetail->miktar ?? 0);
            }

            $newTotals = [];
            foreach ($lines as $line) {
                $qty = (float) ($line['miktar'] ?? 0);
                if ($qty <= 0) {
                    continue;
                }
                $stokKod = trim((string) ($line['stok_kod'] ?? ''));
                if ($stokKod === '') {
                    $urunId = $line['urun_id'] ?? null;
                    if ($urunId) {
                        $stokKod = trim((string) (Product::whereKey($urunId)->value('kod') ?? ''));
                    }
                }
                if ($stokKod === '') {
                    continue;
                }
                $newTotals[$stokKod] = ($newTotals[$stokKod] ?? 0.0) + $qty;
            }

            $allStockCodes = array_unique(array_merge(array_keys($oldTotals), array_keys($newTotals)));
            foreach ($allStockCodes as $stokKod) {
                $oldQty = (float) ($oldTotals[$stokKod] ?? 0);
                $newQty = (float) ($newTotals[$stokKod] ?? 0);

                if ($oldDepotId > 0 && $newDepotId > 0 && $oldDepotId === $newDepotId && abs($oldStockMultiplier - $newStockMultiplier) < 0.0000001) {
                    $delta = ($newQty - $oldQty) * $newStockMultiplier;
                    $this->applyStockInventoryDelta($newDepotId, $stokKod, $delta);
                    continue;
                }

                if ($oldDepotId > 0 && $oldQty > 0) {
                    $this->applyStockInventoryDelta($oldDepotId, $stokKod, $oldStockMultiplier * (-$oldQty));
                }

                if ($newDepotId > 0 && $newQty > 0) {
                    $this->applyStockInventoryDelta($newDepotId, $stokKod, $newStockMultiplier * $newQty);
                }
            }

            $existingTransfers = DB::table('fatura_siparis_satir_eslestirmeleri as e')
                ->join('fatura_detaylari as fd', 'fd.id', '=', 'e.fatura_detay_id')
                ->where('fd.fatura_id', $fatura->id)
                ->select(['e.siparis_detay_id', 'e.miktar'])
                ->get();

            foreach ($existingTransfers as $t) {
                $siparisDetayId = is_numeric($t->siparis_detay_id) ? (int) $t->siparis_detay_id : null;
                if (!$siparisDetayId) {
                    continue;
                }

                $transferQty = (float) ($t->miktar ?? 0);
                if ($transferQty <= 0) {
                    continue;
                }

                $siparisDetay = SiparisDetay::query()
                    ->lockForUpdate()
                    ->whereKey($siparisDetayId)
                    ->first();

                if (!$siparisDetay) {
                    continue;
                }

                $newGelen = max(0.0, (float) $siparisDetay->gelen - $transferQty);
                $openLine = $newGelen + 0.0001 < (float) $siparisDetay->miktar;
                $siparisDetay->update([
                    'gelen' => $newGelen,
                    'durum' => $openLine ? 'A' : 'K',
                ]);
            }

            $fatura->update($data);
            $fatura->detaylar()->delete();

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
                $stokKod = trim((string) ($line['stok_kod'] ?? ''));
                if ($stokKod === '' && $urunId) {
                    $stokKod = trim((string) (Product::whereKey($urunId)->value('kod') ?? ''));
                }
                $siparisDetayId = $line['siparis_detay_id'] ?? null;
                $siparisDetayId = is_numeric($siparisDetayId) ? (int) $siparisDetayId : null;
                $siparisDetay = null;

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

                if ($siparisDetayId) {
                    $siparisDetay = SiparisDetay::query()
                        ->with('siparis')
                        ->lockForUpdate()
                        ->whereKey($siparisDetayId)
                        ->first();

                    if (!$siparisDetay || !$siparisDetay->siparis) {
                        throw ValidationException::withMessages(['lines' => 'Geçersiz sipariş satırı seçildi.']);
                    }

                    if ((string) $siparisDetay->siparis->carikod !== (string) ($data['carikod'] ?? '')) {
                        throw ValidationException::withMessages(['lines' => 'Sipariş satırı seçili firmaya ait değil.']);
                    }

                    if ((string) $siparisDetay->siparis->siparis_turu !== $orderTur) {
                        throw ValidationException::withMessages(['lines' => 'Sipariş satırı, fatura türü ile uyumlu değil.']);
                    }

                    if ((string) $siparisDetay->durum !== 'A') {
                        throw ValidationException::withMessages(['lines' => 'Sipariş satırı açık değil.']);
                    }

                    if ($siparisDetay->urun_id && $urunId && (int) $siparisDetay->urun_id !== (int) $urunId) {
                        throw ValidationException::withMessages(['lines' => 'Sipariş satırı ürünü ile fatura satırı ürünü uyuşmuyor.']);
                    }

                    $kalan = max(0.0, (float) $siparisDetay->miktar - (float) $siparisDetay->gelen);
                    if ($miktar - $kalan > 0.0001) {
                        throw ValidationException::withMessages(['lines' => 'Aktarım miktarı, sipariş kalan miktarını aşıyor.']);
                    }
                }

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

                $projeKodu = trim((string) ($line['proje_kodu'] ?? ''));
                if ($projeKodu === '' && $siparisDetay) {
                    $projeKodu = trim((string) ($siparisDetay->proje_kodu ?? ($siparisDetay->siparis?->proje?->kod ?? '')));
                }
                $projeKodu = $projeKodu !== '' ? $projeKodu : null;

                $createdDetay = FaturaDetay::create([
                    'fatura_id' => $fatura->id,
                    'urun_id' => $urunId,
                    'proje_kodu' => $projeKodu,
                    'stokkod' => $stokKod !== '' ? $stokKod : null,
                    'satir_aciklama' => $satirAciklama,
                    'durum' => $durum,
                    'miktar' => $miktar,
                    'birim' => $birim,
                    'birim_fiyat' => $birimFiyat,
                    'doviz' => $doviz,
                    'kur' => $kur,
                    'iskonto1' => $iskontolar[1],
                    'iskonto2' => $iskontolar[2],
                    'iskonto3' => $iskontolar[3],
                    'iskonto4' => $iskontolar[4],
                    'iskonto5' => $iskontolar[5],
                    'iskonto6' => $iskontolar[6],
                    'iskonto_tutar' => $iskontoTutar,
                    'kdv_orani' => $kdvOrani,
                    'kdv_tutar' => $kdvTutar,
                    'satir_toplam' => $satirToplam,
                    'siparis_detay_id' => $siparisDetayId,
                ]);

                if ($siparisDetayId) {
                    DB::table('fatura_siparis_satir_eslestirmeleri')->insert([
                        'fatura_detay_id' => $createdDetay->id,
                        'siparis_detay_id' => $siparisDetayId,
                        'miktar' => $miktar,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    if ($siparisDetay) {
                        $newGelen = (float) $siparisDetay->gelen + $miktar;
                        $closeLine = $newGelen + 0.0001 >= (float) $siparisDetay->miktar;
                        $siparisDetay->update([
                            'gelen' => $newGelen,
                            'durum' => $closeLine ? 'K' : 'A',
                        ]);
                    }
                }

                $toplam += $brut;
                $iskontoToplam += $iskontoTutar;
                $kdvToplam += $kdvTutar;
            }

            $genelToplam = $toplam - $iskontoToplam + $kdvToplam;

            $fatura->update([
                'toplam' => $toplam,
                'iskonto_tutar' => $iskontoToplam,
                'kdv' => $kdvToplam,
                'genel_toplam' => $genelToplam,
            ]);
        });

        return redirect()->route('invoices.index', ['tur' => $data['siparis_turu']])
            ->with('status', 'Fatura güncellendi.');
    }

    public function destroy(Request $request, Fatura $fatura)
    {
        $tur = $this->normalizeTur($fatura->siparis_turu);

        DB::transaction(function () use ($fatura) {
            $depoId = (int) ($fatura->depo_id ?? 0);
            $multiplier = $this->stockMultiplierForInvoiceTur($this->normalizeTur($fatura->siparis_turu));
            $details = $fatura->detaylar()->with('urun:id,kod')->get();

            foreach ($details as $detail) {
                $stokKod = trim((string) ($detail->stokkod ?? ''));
                if ($stokKod === '') {
                    $stokKod = trim((string) ($detail->urun?->kod ?? ''));
                }
                if ($stokKod === '' || $depoId <= 0) {
                    continue;
                }
                $this->applyStockInventoryDelta($depoId, $stokKod, $multiplier * (-(float) ($detail->miktar ?? 0)));
            }

            $existingTransfers = DB::table('fatura_siparis_satir_eslestirmeleri as e')
                ->join('fatura_detaylari as fd', 'fd.id', '=', 'e.fatura_detay_id')
                ->where('fd.fatura_id', $fatura->id)
                ->select(['e.siparis_detay_id', 'e.miktar'])
                ->get();

            foreach ($existingTransfers as $t) {
                $siparisDetayId = is_numeric($t->siparis_detay_id) ? (int) $t->siparis_detay_id : null;
                if (!$siparisDetayId) {
                    continue;
                }

                $transferQty = (float) ($t->miktar ?? 0);
                if ($transferQty <= 0) {
                    continue;
                }

                $siparisDetay = SiparisDetay::query()
                    ->lockForUpdate()
                    ->whereKey($siparisDetayId)
                    ->first();

                if (!$siparisDetay) {
                    continue;
                }

                $newGelen = max(0.0, (float) $siparisDetay->gelen - $transferQty);
                $openLine = $newGelen + 0.0001 < (float) $siparisDetay->miktar;
                $siparisDetay->update([
                    'gelen' => $newGelen,
                    'durum' => $openLine ? 'A' : 'K',
                ]);
            }

            $fatura->delete();
        });

        return redirect()->route('invoices.index', ['tur' => $tur])
            ->with('status', 'Fatura silindi.');
    }

    public function destroyLine(Request $request, Fatura $fatura, FaturaDetay $detay)
    {
        if ((int) $detay->fatura_id !== (int) $fatura->id) {
            abort(404);
        }

        DB::transaction(function () use ($detay, $fatura) {
            $depoId = (int) ($fatura->depo_id ?? 0);
            $multiplier = $this->stockMultiplierForInvoiceTur($this->normalizeTur($fatura->siparis_turu));
            $stokKod = trim((string) ($detay->stokkod ?? ''));
            if ($stokKod === '' && $depoId > 0 && $detay->urun_id) {
                $stokKod = trim((string) (Product::whereKey($detay->urun_id)->value('kod') ?? ''));
            }
            if ($stokKod !== '' && $depoId > 0) {
                $this->applyStockInventoryDelta($depoId, $stokKod, $multiplier * (-(float) ($detay->miktar ?? 0)));
            }

            $links = DB::table('fatura_siparis_satir_eslestirmeleri')
                ->where('fatura_detay_id', $detay->id)
                ->get(['siparis_detay_id', 'miktar']);

            foreach ($links as $link) {
                $siparisDetayId = is_numeric($link->siparis_detay_id) ? (int) $link->siparis_detay_id : null;
                if (!$siparisDetayId) {
                    continue;
                }

                $transferQty = (float) ($link->miktar ?? 0);
                if ($transferQty <= 0) {
                    continue;
                }

                $siparisDetay = SiparisDetay::query()
                    ->lockForUpdate()
                    ->whereKey($siparisDetayId)
                    ->first();

                if (!$siparisDetay) {
                    continue;
                }

                $newGelen = max(0.0, (float) $siparisDetay->gelen - $transferQty);
                $openLine = $newGelen + 0.0001 < (float) $siparisDetay->miktar;

                $siparisDetay->update([
                    'gelen' => $newGelen,
                    'durum' => $openLine ? 'A' : 'K',
                ]);
            }

            DB::table('fatura_siparis_satir_eslestirmeleri')
                ->where('fatura_detay_id', $detay->id)
                ->delete();

            $detay->delete();
        });

        return response()->json(['ok' => true]);
    }

    public function lineLinks(Request $request, Fatura $fatura, FaturaDetay $detay)
    {
        if ((int) $detay->fatura_id !== (int) $fatura->id) {
            abort(404);
        }

        $links = DB::table('fatura_siparis_satir_eslestirmeleri as e')
            ->join('siparis_detaylari as sd', 'sd.id', '=', 'e.siparis_detay_id')
            ->join('siparisler as s', 's.id', '=', 'sd.siparis_id')
            ->leftJoin('projeler as p', 'p.id', '=', 's.proje_id')
            ->leftJoin('islem_turleri as it', 'it.id', '=', 's.islem_turu_id')
            ->leftJoin('urunler as u', 'u.id', '=', 'sd.urun_id')
            ->where('e.fatura_detay_id', $detay->id)
            ->orderByDesc('e.id')
            ->get([
                's.siparis_no as siparis_no',
                's.tarih as siparis_tarih',
                'sd.proje_kodu as satir_proje_kodu',
                'p.kod as header_proje_kodu',
                'it.ad as islem_tipi',
                'u.kod as stok_kod',
                'u.aciklama as stok_aciklama',
                'sd.satir_aciklama as satir_aciklama',
                'sd.miktar as siparis_miktar',
                'e.miktar as aktarim_miktar',
            ])
            ->map(function ($row) {
                $aciklama = $row->satir_aciklama;
                if ($aciklama === null || trim((string) $aciklama) === '') {
                    $aciklama = $row->stok_aciklama;
                }

                return [
                    'siparis_no' => $row->siparis_no,
                    'siparis_tarih' => $row->siparis_tarih,
                    'proje_kodu' => $row->satir_proje_kodu ?: $row->header_proje_kodu,
                    'islem_tipi' => $row->islem_tipi,
                    'stok_kod' => $row->stok_kod,
                    'stok_aciklama' => $aciklama,
                    'siparis_miktar' => (float) $row->siparis_miktar,
                    'aktarim_miktar' => (float) $row->aktarim_miktar,
                ];
            })
            ->values();

        return response()->json(['data' => $links]);
    }

    protected function validatedHeader(Request $request): array
    {
        return $request->validate([
            'siparis_turu' => ['nullable', 'string', 'in:alim,satis,alim-iade,satis-iade'],
            'carikod' => ['required', 'string', 'max:50'],
            'cariaciklama' => ['required', 'string', 'max:255'],
            'depo_id' => ['nullable', 'integer', 'exists:depolar,id'],
            'tarih' => ['required', 'date'],
            'siparis_no' => ['required', 'string', 'max:50'],
            'belge_no' => ['nullable', 'string', 'max:50'],
            'teklif_no' => ['nullable', 'string', 'max:50'],
            'aciklama' => ['nullable', 'string'],
            'siparis_durum' => ['nullable', 'string', 'max:50'],
            'onay_durum' => ['nullable', 'string', 'max:50'],
            'onay_tarihi' => ['nullable', 'date'],
            'yetkili_personel' => ['nullable', 'string', 'max:150'],
            'hazirlayan' => ['nullable', 'string', 'max:150'],
            'islem_turu_id' => ['nullable', 'integer', 'exists:islem_turleri,id'],
            'proje_id' => ['nullable', 'integer', 'exists:projeler,id'],
            'siparis_doviz' => ['nullable', 'string', 'max:3', 'in:TL,USD,EUR'],
            'siparis_kur' => ['nullable', 'numeric', 'min:0'],
        ]);
    }

    protected function durumlar(): array
    {
        return [
            'hepsi' => 'Hepsi',
            'Taslak' => 'Taslak',
            'Gönderildi' => 'Gönderildi',
            'Kabul' => 'Kabul',
            'Reddedildi' => 'Reddedildi',
        ];
    }

    protected function normalizeTur(?string $tur): string
    {
        $t = strtolower(trim((string) $tur));
        return in_array($t, ['alim', 'satis', 'alim-iade', 'satis-iade'], true) ? $t : 'alim';
    }

    protected function orderTurFromInvoiceTur(string $tur): string
    {
        return str_starts_with($tur, 'satis') ? 'satis' : 'alim';
    }

    public function orderLinesForTransfer(Request $request)
    {
        $validated = $request->validate([
            'carikod' => ['required', 'string', 'max:50'],
            'tur' => ['nullable', 'string'],
        ]);

        $invoiceTur = $this->normalizeTur($validated['tur'] ?? null);
        $orderTur = $this->orderTurFromInvoiceTur($invoiceTur);
        $carikod = $validated['carikod'];

        $rows = SiparisDetay::query()
            ->with([
                'urun:id,kod,aciklama',
                'siparis.proje:id,kod',
                'siparis.islemTuru:id,ad',
            ])
            ->where('durum', 'A')
            ->whereRaw('(miktar - gelen) > 0')
            ->whereHas('siparis', function ($q) use ($carikod, $orderTur) {
                $q->where('carikod', $carikod)->where('siparis_turu', $orderTur);
            })
            ->orderByDesc('id')
            ->limit(500)
            ->get()
            ->map(function (SiparisDetay $detay) {
                $miktar = (float) $detay->miktar;
                $gelen = (float) $detay->gelen;
                $kalan = max(0.0, $miktar - $gelen);

                $desc = $detay->satir_aciklama;
                if ($desc === null || trim((string) $desc) === '') {
                    $desc = $detay->urun?->aciklama;
                }

                return [
                    'id' => $detay->id,
                    'urun_id' => $detay->urun_id,
                    'stok_kod' => $detay->urun?->kod,
                    'stok_aciklama' => $desc,
                    'proje_kodu' => $detay->proje_kodu ?: $detay->siparis?->proje?->kod,
                    'siparis_miktar' => $miktar,
                    'gelen_miktar' => $gelen,
                    'kalan_miktar' => $kalan,
                    'birim' => $detay->birim,
                    'birim_fiyat' => (float) $detay->birim_fiyat,
                    'doviz' => $detay->doviz,
                    'kur' => (float) $detay->kur,
                    'proje' => $detay->siparis?->proje?->kod,
                    'islem_tipi' => $detay->siparis?->islemTuru?->ad,
                ];
            })
            ->values();

        return response()->json(['data' => $rows]);
    }

    protected function heading(string $tur): string
    {
        return match ($tur) {
            'satis' => 'Satış Faturası',
            'alim-iade' => 'Alım İade Faturası',
            'satis-iade' => 'Satış İade Faturası',
            default => 'Alım Faturası',
        };
    }

    protected function activeKey(string $tur): string
    {
        return match ($tur) {
            'satis' => 'sales-invoices',
            'alim-iade' => 'purchase-return-invoices',
            'satis-iade' => 'sales-return-invoices',
            default => 'purchase-invoices',
        };
    }

    protected function startNo(string $tur): int
    {
        return match ($tur) {
            'satis' => 10000001,
            'alim' => 20000001,
            'satis-iade' => 30000001,
            'alim-iade' => 40000001,
            default => 20000001,
        };
    }

    protected function nextNo(string $tur): string
    {
        $start = $this->startNo($tur);

        $maxNo = Fatura::query()
            ->where('siparis_turu', $tur)
            ->select('siparis_no')
            ->get()
            ->pluck('siparis_no')
            ->filter(fn ($v) => is_numeric($v))
            ->map(fn ($v) => (int) $v)
            ->max();

        return $maxNo ? (string) ($maxNo + 1) : (string) $start;
    }
}
