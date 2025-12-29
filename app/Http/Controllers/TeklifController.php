<?php

namespace App\Http\Controllers;

use App\Models\Teklif;
use App\Models\TeklifDetay;
use App\Models\Firm;
use App\Models\Product;
use App\Models\IslemTuru;
use App\Models\Project;
use App\Models\Siparis;
use App\Models\SiparisDetay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;

class TeklifController extends Controller
{
    public function index(Request $request)
    {
        $query = Teklif::query()
            ->with(['islemTuru', 'proje'])
            ->whereIn('id', function ($sub) {
                $sub->from('teklifler')
                    ->selectRaw('MAX(id)')
                    ->groupBy('teklif_no');
            });

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q2) use ($search) {
                $q2->where('teklif_no', 'like', '%' . $search . '%')
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

        if ($request->filled('teklif_durum') && $request->input('teklif_durum') !== 'hepsi') {
            $query->where('teklif_durum', $request->input('teklif_durum'));
        }

        if ($request->filled('islem_turu_id')) {
            $query->where('islem_turu_id', $request->integer('islem_turu_id'));
        }

        if ($request->filled('proje_id')) {
            $query->where('proje_id', $request->integer('proje_id'));
        }

        if ($request->filled('gerceklesme_olasiligi')) {
            $query->where('gerceklesme_olasiligi', $request->integer('gerceklesme_olasiligi'));
        }

        $teklifler = $query
            ->orderByDesc('tarih')
            ->orderByDesc('id')
            ->paginate(15)
            ->appends($request->query());

        $durumlar = $this->durumlar();
        $islemTurleri = IslemTuru::orderBy('ad')->get();
        $projects = Project::where('pasif', false)->orderBy('kod')->get();

        return view('offers.index', compact('teklifler', 'durumlar', 'islemTurleri', 'projects'));
    }

    public function create()
    {
        $durumlar = $this->durumlar();

        $firms = Firm::with('authorities')
            ->orderBy('carikod')
            ->get();

        $products = Product::where('pasif', false)
            ->orderBy('kod')
            ->get();

        $islemTurleri = IslemTuru::orderBy('ad')->get();
        $projects = Project::where('pasif', false)->orderBy('kod')->get();

        $maxTeklifNo = Teklif::query()
            ->select('teklif_no')
            ->get()
            ->pluck('teklif_no')
            ->filter(fn ($v) => is_numeric($v))
            ->map(fn ($v) => (int) $v)
            ->max();

        $nextTeklifNo = $maxTeklifNo ? (string) ($maxTeklifNo + 1) : '10000001';

        $initialRevizeNo = '1';

        return view('offers.create', [
            'durumlar'        => $durumlar,
            'firms'           => $firms,
            'products'        => $products,
            'islemTurleri'    => $islemTurleri,
            'projects'        => $projects,
            'nextTeklifNo'    => $nextTeklifNo,
            'initialRevizeNo' => $initialRevizeNo,
            'revizyonlar'     => collect(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedHeader($request);
        $data['revize_no'] = $data['revize_no'] ?? '1';
        $data['revize_tarihi'] = Carbon::now()->toDateString();

        if (Auth::check()) {
            $user = Auth::user();
            $fullName = trim(($user->ad ?? '') . ' ' . ($user->soyad ?? ''));
            $data['hazirlayan'] = $fullName !== '' ? $fullName : ($user->mail ?? null);
        }
        $lines = $request->input('lines', []);

        DB::transaction(function () use ($data, $lines) {
            $teklif = Teklif::create($data);

            $toplam = 0;
            $iskontoToplam = 0;
            $kdvToplam = 0;
            $headerDoviz = strtoupper(trim((string) ($data['teklif_doviz'] ?? 'TL')));
            $headerKur = (float) ($data['teklif_kur'] ?? 0);

              foreach ($lines as $line) {
                  $satirAciklama = trim($line['satir_aciklama'] ?? '');
                  $urunId = $line['urun_id'] ?? null;
                  $miktar = (float)($line['miktar'] ?? 0);
                  $birimFiyat = (float)($line['birim_fiyat'] ?? 0);

                  if ($satirAciklama === '' && $miktar <= 0 && $birimFiyat <= 0) {
                    continue;
                }

                  $birim = $line['birim'] ?? null;
                  $doviz = strtoupper(trim((string) ($line['doviz'] ?? 'TL')));
                  $kur   = isset($line['kur']) ? (float) $line['kur'] : 0.0;
                  if ($doviz === 'TL') {
                      $kur = 1.0;
                  }
                $iskontolar = [];
                for ($i = 1; $i <= 6; $i++) {
                    $iskontolar[$i] = isset($line["iskonto{$i}"]) ? (float)$line["iskonto{$i}"] : 0.0;
                }

                $kdvOrani = isset($line['kdv_orani']) ? (float)$line['kdv_orani'] : 0.0;
                $kdvDurum = $line['kdv_durum'] ?? 'H'; // H: hariç, D/E: dahil

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
                        // KDV hariç: net tutar üzerinden KDV hesaplanır, satır toplam = net + KDV
                        $kdvTutar = $net * ($kdvOrani / 100);
                        $satirToplam = $net + $kdvTutar;
                    } elseif ($kdvDurum === 'E' || $kdvDurum === 'D') {
                        // KDV dahil: KDV, net tutarın içinden ayrıştırılır, satır toplam = net (KDV dahil)
                        $oran = $kdvOrani / 100;
                        $kdvTutar = $net - ($net / (1 + $oran));
                        $satirToplam = $net;
                    } else {
                        // Tanımsız durumlarda KDV uygulanmaz
                        $kdvTutar = 0.0;
                        $satirToplam = $net;
                    }
                } else {
                    $kdvTutar = 0.0;
                    $satirToplam = $net;
                }

                TeklifDetay::create([
                    'teklif_id'      => $teklif->id,
                    'urun_id'        => $urunId,
                    'satir_aciklama' => $satirAciklama,
                    'miktar'         => $miktar,
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

                $toplam += $brut;
                $iskontoToplam += $iskontoTutar;
                $kdvToplam += $kdvTutar;
            }

            $genelToplam = $toplam - $iskontoToplam + $kdvToplam;

            $teklif->update([
                'toplam'        => $toplam,
                'iskonto_tutar' => $iskontoToplam,
                'kdv'           => $kdvToplam,
                'genel_toplam'  => $genelToplam,
            ]);
        });

        return redirect()->route('offers.index')
            ->with('status', 'Teklif oluşturuldu.');
    }

    public function show(Teklif $teklif)
    {
        $teklif->load('detaylar');

        return view('offers.show', compact('teklif'));
    }

    public function edit(Teklif $teklif)
    {
        $durumlar = $this->durumlar();

        $firms = Firm::with('authorities')
            ->orderBy('carikod')
            ->get();

        $products = Product::where('pasif', false)
            ->orderBy('kod')
            ->get();

        $islemTurleri = IslemTuru::orderBy('ad')->get();
        $projects = Project::where('pasif', false)->orderBy('kod')->get();

        $teklif->load(['detaylar.urun', 'islemTuru', 'proje']);

        $selectedFirm = null;
        if ($teklif->carikod) {
            $selectedFirm = Firm::where('carikod', $teklif->carikod)->first();
        }

        $revizyonlar = Teklif::query()
            ->select(['id', 'teklif_no', 'revize_no', 'revize_tarihi'])
            ->where('teklif_no', $teklif->teklif_no)
            ->get()
            ->sortBy(fn ($t) => (int) ($t->revize_no ?? 0))
            ->values();

        return view('offers.create', [
            'durumlar'        => $durumlar,
            'firms'           => $firms,
            'products'        => $products,
            'islemTurleri'    => $islemTurleri,
            'projects'        => $projects,
            'nextTeklifNo'    => $teklif->teklif_no,
            'initialRevizeNo' => $teklif->revize_no ?? '1',
            'teklif'          => $teklif,
            'selectedFirm'    => $selectedFirm,
            'revizyonlar'     => $revizyonlar,
        ]);
    }

    public function revize(Teklif $teklif)
    {
        $revizeNoList = Teklif::where('teklif_no', $teklif->teklif_no)->pluck('revize_no');
        $maxRevizeNo = $revizeNoList
            ->map(fn ($v) => (int) ($v ?? 0))
            ->max() ?? 0;

        $newRevizeNo = (string) ($maxRevizeNo + 1);

        $newTeklif = null;

        DB::transaction(function () use ($teklif, $newRevizeNo, &$newTeklif) {
            $newTeklif = $teklif->replicate();
            $newTeklif->revize_no = $newRevizeNo;
            $newTeklif->revize_tarihi = Carbon::now()->toDateString();
            $newTeklif->save();

            $teklif->load('detaylar');
            foreach ($teklif->detaylar as $detay) {
                $newDetay = $detay->replicate();
                $newDetay->teklif_id = $newTeklif->id;
                $newDetay->save();
            }
        });

        return redirect()->route('offers.edit', $newTeklif)
            ->with('status', 'Revize oluşturuldu.');
    }

    public function revizeDestroy(Teklif $teklif)
    {
        $currentRevizeNo = (int) ($teklif->revize_no ?? 1);
        if ($currentRevizeNo <= 1) {
            return redirect()->route('offers.edit', $teklif)
                ->with('status', 'Revize 1 silinemez.');
        }

        $revizyonlar = Teklif::query()
            ->where('teklif_no', $teklif->teklif_no)
            ->get(['id', 'revize_no'])
            ->map(function ($t) {
                $t->revize_no_int = (int) ($t->revize_no ?? 0);
                return $t;
            });

        $previous = $revizyonlar
            ->filter(fn ($t) => $t->revize_no_int < $currentRevizeNo)
            ->sortByDesc('revize_no_int')
            ->first();

        if (!$previous) {
            return redirect()->route('offers.index')
                ->with('status', 'Önceki revize bulunamadı.');
        }

        DB::transaction(function () use ($teklif) {
            $teklif->delete();
        });

        return redirect()->route('offers.edit', $previous->id)
            ->with('status', 'Revize silindi.');
    }

    public function update(Request $request, Teklif $teklif)
    {
        $data = $this->validatedHeader($request);

        if (Auth::check()) {
            $user = Auth::user();
            $fullName = trim(($user->ad ?? '') . ' ' . ($user->soyad ?? ''));
            $data['hazirlayan'] = $fullName !== '' ? $fullName : ($user->mail ?? null);
        }
        $lines = $request->input('lines', []);

        DB::transaction(function () use ($data, $lines, $teklif) {
            $teklif->update($data);

            $teklif->detaylar()->delete();

            $toplam = 0;
            $iskontoToplam = 0;
            $kdvToplam = 0;
            $headerDoviz = strtoupper(trim((string) ($data['teklif_doviz'] ?? 'TL')));
            $headerKur = (float) ($data['teklif_kur'] ?? 0);

            foreach ($lines as $line) {
                $satirAciklama = trim($line['satir_aciklama'] ?? '');
                $urunId = $line['urun_id'] ?? null;
                $miktar = (float)($line['miktar'] ?? 0);
                $birimFiyat = (float)($line['birim_fiyat'] ?? 0);

                if ($satirAciklama === '' && $miktar <= 0 && $birimFiyat <= 0) {
                    continue;
                }

                $birim = $line['birim'] ?? null;
                $doviz = strtoupper(trim((string) ($line['doviz'] ?? 'TL')));
                $kur   = isset($line['kur']) ? (float) $line['kur'] : 0.0;
                if ($doviz === 'TL') {
                    $kur = 1.0;
                }
                $iskontolar = [];
                for ($i = 1; $i <= 6; $i++) {
                    $iskontolar[$i] = isset($line["iskonto{$i}"]) ? (float)$line["iskonto{$i}"] : 0.0;
                }

                $kdvOrani = isset($line['kdv_orani']) ? (float)$line['kdv_orani'] : 0.0;
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

                TeklifDetay::create([
                    'teklif_id'      => $teklif->id,
                    'urun_id'        => $urunId,
                    'satir_aciklama' => $satirAciklama,
                    'miktar'         => $miktar,
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

                $toplam += $brut;
                $iskontoToplam += $iskontoTutar;
                $kdvToplam += $kdvTutar;
            }

            $genelToplam = $toplam - $iskontoToplam + $kdvToplam;

            $teklif->update([
                'toplam'        => $toplam,
                'iskonto_tutar' => $iskontoToplam,
                'kdv'           => $kdvToplam,
                'genel_toplam'  => $genelToplam,
            ]);
        });

        return redirect()->route('offers.index')
            ->with('status', 'Teklif güncellendi.');
    }

    public function createSalesOrder(Request $request, Teklif $teklif)
    {
        $onay = trim((string) ($teklif->onay_durum ?? ''));
        $onayLower = mb_strtolower($onay, 'UTF-8');
        if (!in_array($onayLower, ['onaylı', 'onayli'], true)) {
            return back()->with('status', 'Sipariş oluşturmak için teklif onay durumu Onaylı olmalıdır.');
        }

        $existing = Siparis::query()
            ->where('siparis_turu', 'satis')
            ->where('teklif_no', $teklif->teklif_no)
            ->first();

        if ($existing) {
            return redirect()->route('orders.edit', $existing)
                ->with('status', 'Bu teklif için daha önce sipariş oluşturulmuş.');
        }

        $siparis = DB::transaction(function () use ($teklif) {
            $teklif->load('detaylar');

            $maxSiparisNo = Siparis::query()
                ->where('siparis_turu', 'satis')
                ->select('siparis_no')
                ->lockForUpdate()
                ->get()
                ->pluck('siparis_no')
                ->filter(fn ($v) => is_numeric($v))
                ->map(fn ($v) => (int) $v)
                ->max();

            $nextSiparisNo = $maxSiparisNo ? (string) ($maxSiparisNo + 1) : '20000001';

            $siparis = Siparis::create([
                'siparis_turu'      => 'satis',
                'carikod'           => $teklif->carikod,
                'cariaciklama'      => $teklif->cariaciklama,
                'tarih'             => $teklif->tarih ?? now(),
                'gecerlilik_tarihi' => $teklif->gecerlilik_tarihi,
                'siparis_no'        => $nextSiparisNo,
                'teklif_no'         => $teklif->teklif_no,
                'aciklama'          => $teklif->aciklama,
                'onay_durum'        => 'Onay bekliyor',
                'onay_tarihi'       => null,
                'yetkili_personel'  => $teklif->yetkili_personel,
                'hazirlayan'        => $teklif->hazirlayan,
                'islem_turu_id'     => $teklif->islem_turu_id,
                'proje_id'          => $teklif->proje_id,
                'siparis_doviz'     => $teklif->teklif_doviz ?? 'TL',
                'siparis_kur'       => $teklif->teklif_kur ?? 1,
                'toplam'            => $teklif->toplam ?? 0,
                'iskonto_tutar'     => $teklif->iskonto_tutar ?? 0,
                'kdv'               => $teklif->kdv ?? 0,
                'genel_toplam'      => $teklif->genel_toplam ?? 0,
            ]);

            foreach ($teklif->detaylar as $detay) {
                SiparisDetay::create([
                    'siparis_id'     => $siparis->id,
                    'urun_id'        => $detay->urun_id,
                    'satir_aciklama' => $detay->satir_aciklama,
                    'durum'          => 'A',
                    'miktar'         => $detay->miktar,
                    'birim'          => $detay->birim,
                    'birim_fiyat'    => $detay->birim_fiyat,
                    'doviz'          => $detay->doviz,
                    'kur'            => $detay->kur,
                    'iskonto1'       => $detay->iskonto1,
                    'iskonto2'       => $detay->iskonto2,
                    'iskonto3'       => $detay->iskonto3,
                    'iskonto4'       => $detay->iskonto4,
                    'iskonto5'       => $detay->iskonto5,
                    'iskonto6'       => $detay->iskonto6,
                    'iskonto_tutar'  => $detay->iskonto_tutar,
                    'kdv_orani'      => $detay->kdv_orani,
                    'kdv_tutar'      => $detay->kdv_tutar,
                    'satir_toplam'   => $detay->satir_toplam,
                ]);
            }

            $teklif->update([
                'teklif_durum' => 'Kabul Edildi',
            ]);

            return $siparis;
        });

        return redirect()->route('orders.edit', $siparis)
            ->with('status', 'Sipariş oluşturuldu.');
    }

    public function redirectByTeklifNo(string $teklifNo)
    {
        $no = trim($teklifNo);
        if ($no === '') {
            return redirect()->route('offers.index');
        }

        $teklif = Teklif::query()
            ->where('teklif_no', $no)
            ->orderByDesc('id')
            ->first();

        if (!$teklif) {
            return redirect()->route('offers.index')
                ->with('status', 'Teklif bulunamadı.');
        }

        return redirect()->route('offers.edit', $teklif);
    }

    public function print(Teklif $teklif)
    {
        $teklif->load('detaylar');

        $firm = Firm::where('carikod', $teklif->carikod)->first();

        return view('offers.print', compact('teklif', 'firm'));
    }

    public function pdf(Teklif $teklif)
    {
        $teklif->load('detaylar');
        $firm = Firm::where('carikod', $teklif->carikod)->first();

        $pdf = Pdf::loadView('offers.print', compact('teklif', 'firm'))
            ->setPaper('a4', 'portrait');

        $filename = 'Teklif_Formu_' . ($teklif->teklif_no ?? $teklif->id);

        return $pdf->download($filename . '.pdf');
    }

    protected function validatedHeader(Request $request): array
    {
        return $request->validate([
            'carikod'           => ['required', 'string', 'max:50'],
            'cariaciklama'      => ['required', 'string', 'max:255'],
            'tarih'             => ['required', 'date'],
            'gecerlilik_tarihi' => ['nullable', 'date'],
            'teklif_no'         => ['required', 'string', 'max:50'],
            'revize_no'         => ['nullable', 'string', 'max:20'],
            'aciklama'          => ['nullable', 'string'],
            'teklif_durum'      => ['nullable', 'string', 'max:50'],
            'gerceklesme_olasiligi' => ['nullable', 'integer', 'in:25,50,75,100'],
            'onay_durum'        => ['nullable', 'string', 'max:50'],
            'onay_tarihi'       => ['nullable', 'date'],
            'yetkili_personel'  => ['nullable', 'string', 'max:150'],
            'hazirlayan'        => ['nullable', 'string', 'max:150'],
            'islem_turu_id'     => ['nullable', 'integer', 'exists:islem_turleri,id'],
            'proje_id'          => ['nullable', 'integer', 'exists:projeler,id'],
            'teklif_doviz'      => ['nullable', 'string', 'max:3', 'in:TL,USD,EUR'],
            'teklif_kur'        => ['nullable', 'numeric', 'min:0'],
        ]);
    }

    protected function durumlar(): array
    {
        return [
            'hepsi'        => 'Hepsi',
            'Taslak'       => 'Taslak',
            'Gönderildi'   => 'Gönderildi',
            'Kabul Edildi' => 'Kabul Edildi',
            'Reddedildi'   => 'Reddedildi',
            'Süresi Doldu' => 'Süresi Doldu',
        ];
    }
}

