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
use App\Models\TeklifSatirTakimDetay;
use App\Models\TeklifSatirMontajDetay;
use App\Models\MontajGroup;
use App\Models\MontajProduct;
use App\Models\MontajProductGroup;
use App\Models\ProductRecipe;
use App\Models\Parameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class TeklifController extends Controller
{
    public function productImageBase64(Request $request)
    {
        $src = trim((string) $request->input('src', ''));
        if ($src === '') {
            return response()->json(['ok' => true, 'image' => null]);
        }

        $path = parse_url($src, PHP_URL_PATH);
        $baseName = basename((string) ($path !== null ? $path : $src));
        $baseName = preg_replace('/[?#].*$/', '', $baseName);
        $baseName = trim((string) $baseName);

        if ($baseName === '' || $baseName === '.' || $baseName === '..') {
            return response()->json(['ok' => false, 'message' => 'Gecersiz dosya adi.'], 422);
        }

        // Prevent path traversal / odd characters
        if (!preg_match('/^[A-Za-z0-9._-]+$/', $baseName)) {
            return response()->json(['ok' => false, 'message' => 'Gecersiz dosya adi.'], 422);
        }

        $candidates = [
            'products/' . $baseName,
            'uploads/products/' . $baseName,
        ];

        $fullPath = null;
        foreach ($candidates as $cand) {
            if (Storage::disk('public')->exists($cand)) {
                $fullPath = Storage::disk('public')->path($cand);
                break;
            }
        }

        if ($fullPath === null) {
            $publicCandidates = [
                public_path('storage/products/' . $baseName),
                public_path('storage/uploads/products/' . $baseName),
                public_path('uploads/products/' . $baseName),
            ];

            foreach ($publicCandidates as $cand) {
                if (is_file($cand)) {
                    $fullPath = $cand;
                    break;
                }
            }
        }

        if ($fullPath === null) {
            return response()->json(['ok' => false, 'message' => 'Resim bulunamadi.'], 404);
        }

        $raw = @file_get_contents($fullPath);
        if ($raw === false || $raw === '') {
            return response()->json(['ok' => false, 'message' => 'Resim okunamadi.'], 500);
        }

        $mime = @mime_content_type($fullPath) ?: 'image/jpeg';
        $maxDim = 320;

        $outData = $raw;
        $outMime = $mime === 'image/png' ? 'image/png' : 'image/jpeg';
        $w = 0;
        $h = 0;
        $tw = 0;
        $th = 0;

        $info = @getimagesizefromstring($raw);
        if (is_array($info)) {
            $w = (int) ($info[0] ?? 0);
            $h = (int) ($info[1] ?? 0);
        }

        $tw = $w;
        $th = $h;

        if ($w > 0 && $h > 0 && function_exists('imagecreatefromstring')) {
            $scale = min(1, $maxDim / max($w, $h));
            $tw = max(1, (int) round($w * $scale));
            $th = max(1, (int) round($h * $scale));

            try {
                $srcImg = @imagecreatefromstring($raw);
                if ($srcImg) {
                    $dstImg = imagecreatetruecolor($tw, $th);
                    if ($outMime === 'image/png') {
                        imagealphablending($dstImg, false);
                        imagesavealpha($dstImg, true);
                        $transparent = imagecolorallocatealpha($dstImg, 0, 0, 0, 127);
                        imagefilledrectangle($dstImg, 0, 0, $tw, $th, $transparent);
                    } else {
                        $white = imagecolorallocate($dstImg, 255, 255, 255);
                        imagefilledrectangle($dstImg, 0, 0, $tw, $th, $white);
                    }

                    imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $tw, $th, $w, $h);

                    ob_start();
                    if ($outMime === 'image/png') {
                        imagepng($dstImg);
                    } else {
                        imagejpeg($dstImg, null, 70);
                    }
                    $buf = ob_get_clean();
                    if (is_string($buf) && $buf !== '') {
                        $outData = $buf;
                    }

                    imagedestroy($dstImg);
                    imagedestroy($srcImg);
                }
            } catch (\Throwable $e) {
                // fall back to raw
            }
        }

        return response()->json([
            'ok' => true,
            'image' => [
                'baslik' => $baseName,
                'mime' => $outMime,
                'base64' => base64_encode($outData),
                'width' => $tw ?: null,
                'height' => $th ?: null,
            ],
        ]);
    }

    public function jsonToPdf(Request $request)
    {
        $payload = $request->all();

        $params = Parameter::query()
            ->whereIn('anahtar', ['tomcat_ip', 'tomcat_port', 'tomcat_proje'])
            ->get(['anahtar', 'deger'])
            ->keyBy('anahtar');

        $ip = trim((string) ($params['tomcat_ip']->deger ?? 'localhost'));
        $port = trim((string) ($params['tomcat_port']->deger ?? '8080'));
        $project = trim((string) ($params['tomcat_proje']->deger ?? ''));

        $base = $ip !== '' ? $ip : 'localhost';
        if (!str_starts_with($base, 'http://') && !str_starts_with($base, 'https://')) {
            $base = 'http://' . $base;
        }
        $base = rtrim($base, '/');
        if (!preg_match('/:[0-9]+$/', $base)) {
            $base .= ':' . ($port !== '' ? $port : '8080');
        }

        $path = '';
        if ($project !== '') {
            $project = trim($project, '/');
            if ($project !== '') {
                $path .= '/' . $project;
            }
        }
        $path .= '/json-to-pdf';

        $url = $base . $path;

        try {
            $resp = Http::timeout(60)
                ->accept('application/pdf')
                ->asJson()
                ->post($url, $payload);
        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Tomcat servisine baglanilamadi.',
            ], 502);
        }

        if (!$resp->successful()) {
            return response()->json([
                'ok' => false,
                'message' => 'Tomcat hata dondu.',
                'status' => $resp->status(),
                'body' => (string) $resp->body(),
            ], 502);
        }

        return response($resp->body(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="teklif.pdf"',
        ]);
    }

    public function index(Request $request)
    {
        $tur = strtolower(trim((string) $request->input('tur', 'satis')));
        if ($tur !== 'alim' && $tur !== 'satis') {
            $tur = 'satis';
        }
        $prefix = $tur === 'alim' ? '2' : '1';

        $query = Teklif::query()
            ->with(['islemTuru', 'proje'])
            ->where('teklif_no', 'like', $prefix . '%')
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
                    ->orWhere('cariaciklama', 'like', '%' . $search . '%')
                    ->orWhereHas('proje', function ($q3) use ($search) {
                        $q3->where('kod', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('islemTuru', function ($q3) use ($search) {
                        $q3->where('ad', 'like', '%' . $search . '%');
                    });
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

        return view('offers.index', compact('teklifler', 'durumlar', 'islemTurleri', 'projects', 'tur') + [
            'offerTur' => $tur,
        ]);
    }

    public function create()
    {
        $tur = strtolower(trim((string) request()->input('tur', 'satis')));
        if ($tur !== 'alim' && $tur !== 'satis') {
            $tur = 'satis';
        }
        $prefix = $tur === 'alim' ? '2' : '1';

        $params = Parameter::query()
            ->whereIn('anahtar', ['tomcat_ip', 'tomcat_port', 'tomcat_proje'])
            ->get(['anahtar', 'deger'])
            ->keyBy('anahtar');
        $tomcatIp = (string) ($params['tomcat_ip']->deger ?? 'localhost');
        $tomcatPort = (string) ($params['tomcat_port']->deger ?? '8080');
        $tomcatProje = (string) ($params['tomcat_proje']->deger ?? '');

        $durumlar = $this->durumlar();

        $firms = Firm::with('authorities')
            ->orderBy('carikod')
            ->get();

        $openDurumlar = ['A', 'Açık', 'AÇIK', 'ACIK'];
        $products = Product::query()
            ->where('pasif', false)
            ->select('urunler.*')
            ->selectSub(
                DB::table('stokenvanter as se')
                    ->selectRaw('COALESCE(SUM(se.stokmiktar),0)')
                    ->whereColumn('se.stokkod', 'urunler.kod'),
                'envanter_stok_miktar'
            )
            ->selectSub(
                DB::table('stokrevize as sr')
                    ->selectRaw('COALESCE(SUM(sr.miktar),0)')
                    ->whereColumn('sr.stokkod', 'urunler.kod')
                    ->whereIn('sr.durum', $openDurumlar),
                'rezerve_miktar'
            )
            ->orderBy('kod')
            ->get();

        $islemTurleri = IslemTuru::orderBy('ad')->get();
        $projects = Project::where('pasif', false)->orderBy('kod')->get();

        $maxTeklifNo = Teklif::query()
            ->where('teklif_no', 'like', $prefix . '%')
            ->select('teklif_no')
            ->get()
            ->pluck('teklif_no')
            ->filter(fn ($v) => is_numeric($v))
            ->map(fn ($v) => (int) $v)
            ->max();

        $base = ((int) $prefix) * 10000000 + 1;
        $nextTeklifNo = $maxTeklifNo ? (string) ($maxTeklifNo + 1) : (string) $base;

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
            'offerTur'        => $tur,
            'tomcatIp'        => $tomcatIp,
            'tomcatPort'      => $tomcatPort,
            'tomcatProje'     => $tomcatProje,
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

                // UI ile tutarlılık: Satır Tutar (TL), Satır Tutar Döviz (2 hane) * kur olacak şekilde normalize edilir.
                if ($lineRate > 0) {
                    $satirToplamFxRounded = round($satirToplam / $lineRate, 2);
                    $satirToplam = round($satirToplamFxRounded * $lineRate, 2);
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

        $params = Parameter::query()
            ->whereIn('anahtar', ['tomcat_ip', 'tomcat_port', 'tomcat_proje'])
            ->get(['anahtar', 'deger'])
            ->keyBy('anahtar');
        $tomcatIp = (string) ($params['tomcat_ip']->deger ?? 'localhost');
        $tomcatPort = (string) ($params['tomcat_port']->deger ?? '8080');
        $tomcatProje = (string) ($params['tomcat_proje']->deger ?? '');

        $firms = Firm::with('authorities')
            ->orderBy('carikod')
            ->get();

        $openDurumlar = ['A', 'Açık', 'AÇIK', 'ACIK'];
        $products = Product::query()
            ->where('pasif', false)
            ->select('urunler.*')
            ->selectSub(
                DB::table('stokenvanter as se')
                    ->selectRaw('COALESCE(SUM(se.stokmiktar),0)')
                    ->whereColumn('se.stokkod', 'urunler.kod'),
                'envanter_stok_miktar'
            )
            ->selectSub(
                DB::table('stokrevize as sr')
                    ->selectRaw('COALESCE(SUM(sr.miktar),0)')
                    ->whereColumn('sr.stokkod', 'urunler.kod')
                    ->whereIn('sr.durum', $openDurumlar),
                'rezerve_miktar'
            )
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

        $offerTur = str_starts_with((string) ($teklif->teklif_no ?? ''), '2') ? 'alim' : 'satis';

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
            'offerTur'        => $offerTur,
            'tomcatIp'        => $tomcatIp,
            'tomcatPort'      => $tomcatPort,
            'tomcatProje'     => $tomcatProje,
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

            $existingDetails = $teklif->detaylar()->get()->keyBy('id');
            $keptIds = [];

            $toplam = 0;
            $iskontoToplam = 0;
            $kdvToplam = 0;
            $headerDoviz = strtoupper(trim((string) ($data['teklif_doviz'] ?? 'TL')));
            $headerKur = (float) ($data['teklif_kur'] ?? 0);

            foreach ($lines as $line) {
                $lineId = isset($line['id']) && is_numeric($line['id']) ? (int) $line['id'] : null;
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

                // UI ile tutarlılık: Satır Tutar (TL), Satır Tutar Döviz (2 hane) * kur olacak şekilde normalize edilir.
                if ($lineRate > 0) {
                    $satirToplamFxRounded = round($satirToplam / $lineRate, 2);
                    $satirToplam = round($satirToplamFxRounded * $lineRate, 2);
                }

                $payload = [
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
                ];

                if ($lineId && $existingDetails->has($lineId)) {
                    $detail = $existingDetails->get($lineId);
                    $detail->update($payload);
                    $keptIds[] = $detail->id;
                } else {
                    $detail = TeklifDetay::create($payload);
                    $keptIds[] = $detail->id;
                }

                $toplam += $brut;
                $iskontoToplam += $iskontoTutar;
                $kdvToplam += $kdvTutar;
            }

            $deleteIds = $existingDetails->keys()->diff($keptIds)->values()->all();
            if (!empty($deleteIds)) {
                TeklifDetay::where('teklif_id', $teklif->id)
                    ->whereIn('id', $deleteIds)
                    ->delete();
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

    public function teamLineDetails(TeklifDetay $detay)
    {
        $items = TeklifSatirTakimDetay::query()
            ->where('teklif_detay_id', $detay->id)
            ->orderBy('id')
            ->get([
                'id',
                'urun_id',
                'stokkod',
                'stok_aciklama',
                'miktar',
                'birim_fiyat',
                'iskonto1',
                'iskonto2',
                'doviz',
                'kur',
                'satir_tutar',
            ]);

        $recipeItems = [];
        if ($items->isEmpty() && !empty($detay->urun_id)) {
            $product = Product::query()->whereKey($detay->urun_id)->first(['id', 'multi']);
            if ($product && !empty($product->multi)) {
                $recipeItems = ProductRecipe::query()
                    ->with('stokUrun:id,kod,aciklama')
                    ->where('urun_id', $product->id)
                    ->orderBy('sirano')
                    ->orderBy('id')
                    ->get()
                    ->map(function (ProductRecipe $r) {
                        return [
                            'urun_id' => (int) $r->stok_urun_id,
                            'stokkod' => (string) (optional($r->stokUrun)->kod ?? ''),
                            'stok_aciklama' => (string) (optional($r->stokUrun)->aciklama ?? ''),
                            'miktar' => (float) ($r->miktar ?? 0),
                            'birim_fiyat' => 0,
                            'iskonto1' => 0,
                            'iskonto2' => 0,
                            'doviz' => 'TL',
                            'kur' => 1,
                        ];
                    })
                    ->values()
                    ->all();
            }
        }

        return response()->json([
            'items' => $items,
            'recipe_items' => $recipeItems,
        ]);
    }

    public function saveTeamLineDetails(Request $request, TeklifDetay $detay)
    {
        $validated = $request->validate([
            'items' => ['array'],
            'items.*.urun_id' => ['nullable', 'integer'],
            'items.*.stokkod' => ['nullable', 'string', 'max:50'],
            'items.*.stok_aciklama' => ['nullable', 'string', 'max:255'],
            'items.*.miktar' => ['nullable', 'numeric', 'min:0'],
            'items.*.birim_fiyat' => ['nullable', 'numeric', 'min:0'],
            'items.*.iskonto1' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'items.*.iskonto2' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'items.*.doviz' => ['nullable', 'in:TL,USD,EUR'],
            'items.*.kur' => ['nullable', 'numeric', 'min:0'],
        ]);

        $items = $validated['items'] ?? [];

        DB::transaction(function () use ($detay, $items) {
            TeklifSatirTakimDetay::where('teklif_detay_id', $detay->id)->delete();

            foreach ($items as $item) {
                $qty = (float) ($item['miktar'] ?? 0);
                $price = (float) ($item['birim_fiyat'] ?? 0);
                $isk1 = (float) ($item['iskonto1'] ?? 0);
                $isk2 = (float) ($item['iskonto2'] ?? 0);
                $doviz = (string) ($item['doviz'] ?? 'TL');
                $kur = (float) ($item['kur'] ?? 1);

                $rate = $doviz === 'TL' ? 1 : $kur;
                if ($rate < 0) {
                    $rate = 0;
                }

                $base = ($qty * $price) * $rate;
                $d1 = max(0, min(100, $isk1));
                $d2 = max(0, min(100, $isk2));
                $net = $base;
                $net = $net * (1 - ($d1 / 100));
                $net = $net * (1 - ($d2 / 100));
                $satirTutar = round($net, 2);

                TeklifSatirTakimDetay::create([
                    'teklif_detay_id' => $detay->id,
                    'urun_id' => $item['urun_id'] ?? null,
                    'stokkod' => $item['stokkod'] ?? null,
                    'stok_aciklama' => $item['stok_aciklama'] ?? null,
                    'miktar' => $qty,
                    'birim_fiyat' => $price,
                    'iskonto1' => $d1,
                    'iskonto2' => $d2,
                    'doviz' => $doviz ?: 'TL',
                    'kur' => $kur,
                    'satir_tutar' => $satirTutar,
                ]);
            }
        });

        return response()->json(['ok' => true]);
    }

    public function montajLineDetails(TeklifDetay $detay)
    {
        $saved = TeklifSatirMontajDetay::query()
            ->where('teklif_detay_id', $detay->id)
            ->orderBy('sirano')
            ->get([
                'id',
                'montaj_grup_id',
                'urun_id',
                'urun_kod',
                'birim',
                'miktar',
                'birim_fiyat',
                'doviz',
                'satir_tutar',
                'sirano',
            ]);

        $groups = MontajGroup::query()
            ->orderBy('sirano')
            ->orderBy('kod')
            ->get(['id', 'kod', 'sirano', 'urun_detay_grup_id']);

        $montajProducts = MontajProduct::query()
            ->whereIn('montaj_grup_id', $groups->pluck('id')->all())
            ->get(['id', 'montaj_grup_id', 'urun_id', 'urun_kod']);

        $montajProductIdByGroupUrunId = [];
        $montajProductIdByGroupKod = [];
        foreach ($montajProducts as $mp) {
            if (!empty($mp->urun_id)) {
                $montajProductIdByGroupUrunId[$mp->montaj_grup_id . ':' . $mp->urun_id] = $mp->id;
            }
            $kod = trim((string) ($mp->urun_kod ?? ''));
            if ($kod !== '') {
                $montajProductIdByGroupKod[$mp->montaj_grup_id . ':' . $kod] = $mp->id;
            }
        }

        $product = $detay->urun_id ? Product::find($detay->urun_id) : null;
        $stokKod = $detay->stokkod ?: ($product->kod ?? '');
        $stokAciklama = $detay->stok_aciklama ?: ($product->aciklama ?? '');

        if ($saved->isNotEmpty()) {
            $byGroup = $saved->groupBy('montaj_grup_id');
            $montajUrunIds = [];
            $payloadGroups = $groups->map(function (MontajGroup $g) use ($byGroup, $montajProductIdByGroupUrunId, $montajProductIdByGroupKod, &$montajUrunIds) {
                $items = ($byGroup->get($g->id) ?? collect())->map(function (TeklifSatirMontajDetay $row) use ($g, $montajProductIdByGroupUrunId, $montajProductIdByGroupKod, &$montajUrunIds) {
                    $urunKod = trim((string) ($row->urun_kod ?? ''));
                    $montajUrunId = null;
                    if (!empty($row->urun_id)) {
                        $montajUrunId = $montajProductIdByGroupUrunId[$g->id . ':' . $row->urun_id] ?? null;
                    }
                    if (!$montajUrunId && $urunKod !== '') {
                        $montajUrunId = $montajProductIdByGroupKod[$g->id . ':' . $urunKod] ?? null;
                    }
                    if ($montajUrunId) {
                        $montajUrunIds[] = $montajUrunId;
                    }

                    return [
                        'id' => $row->id,
                        'montaj_grup_id' => $row->montaj_grup_id,
                        'montaj_urun_id' => $montajUrunId,
                        'urun_id' => $row->urun_id,
                        'urun_kod' => $urunKod,
                        'birim' => $row->birim ?? 'Adet',
                        'miktar' => (float) ($row->miktar ?? 0),
                        'birim_fiyat' => (float) ($row->birim_fiyat ?? 0),
                        'doviz' => $row->doviz ?? 'TL',
                        'satir_tutar' => (float) ($row->satir_tutar ?? 0),
                        'sirano' => (int) ($row->sirano ?? 0),
                        'urun_ids' => [],
                    ];
                })->values();

                return [
                    'id' => $g->id,
                    'kod' => $g->kod,
                    'sirano' => $g->sirano,
                    'urun_detay_grup_id' => $g->urun_detay_grup_id,
                    'items' => $items,
                ];
            })->values();

            $urunIdsByMontajUrunId = MontajProductGroup::query()
                ->whereIn('montaj_urun_id', array_values(array_unique(array_filter($montajUrunIds))))
                ->get(['montaj_urun_id', 'urun_id'])
                ->groupBy('montaj_urun_id')
                ->map(function ($rows) {
                    return $rows->pluck('urun_id')->map(fn ($v) => (int) $v)->unique()->values()->all();
                })
                ->all();

            $payloadGroups = $payloadGroups->map(function ($g) use ($urunIdsByMontajUrunId) {
                $g['items'] = collect($g['items'])->map(function ($it) use ($urunIdsByMontajUrunId) {
                    $mid = $it['montaj_urun_id'] ?? null;
                    if ($mid) {
                        $it['urun_ids'] = $urunIdsByMontajUrunId[$mid] ?? [];
                    }
                    return $it;
                })->values()->all();
                return $g;
            })->values();

            return response()->json([
                'ok' => true,
                'line' => [
                    'teklif_detay_id' => $detay->id,
                    'stok_kod' => $stokKod,
                    'stok_aciklama' => $stokAciklama,
                ],
                    'groups' => $payloadGroups,
            ]);
        }

        $productsByGroup = MontajProduct::query()
            ->orderBy('sirano')
            ->get([
                'id',
                'montaj_grup_id',
                'urun_id',
                'urun_kod',
                'birim',
                'birim_fiyat',
                'doviz',
                'sirano',
            ])
            ->groupBy('montaj_grup_id');

        $montajUrunIds = [];
        $payloadGroups = $groups->map(function (MontajGroup $g) use ($productsByGroup, &$montajUrunIds) {
            $rows = $productsByGroup->get($g->id) ?? collect();
            $items = $rows->map(function (MontajProduct $row) {
                return [
                    'id' => null,
                    'montaj_grup_id' => $row->montaj_grup_id,
                    'montaj_urun_id' => $row->id,
                    'urun_id' => $row->urun_id,
                    'urun_kod' => $row->urun_kod,
                    'birim' => $row->birim ?? 'Adet',
                    'miktar' => 1,
                    'birim_fiyat' => $row->birim_fiyat ?? 0,
                    'doviz' => $row->doviz ?? 'TL',
                    'satir_tutar' => 0,
                    'sirano' => $row->sirano ?? 0,
                    'urun_ids' => [],
                ];
            })->values();

            $rows->each(function (MontajProduct $row) use (&$montajUrunIds) {
                $montajUrunIds[] = $row->id;
            });

            return [
                'id' => $g->id,
                'kod' => $g->kod,
                'sirano' => $g->sirano,
                'urun_detay_grup_id' => $g->urun_detay_grup_id,
                'items' => $items,
            ];
        })->values();

        $urunIdsByMontajUrunId = MontajProductGroup::query()
            ->whereIn('montaj_urun_id', array_values(array_unique(array_filter($montajUrunIds))))
            ->get(['montaj_urun_id', 'urun_id'])
            ->groupBy('montaj_urun_id')
            ->map(function ($rows) {
                return $rows->pluck('urun_id')->map(fn ($v) => (int) $v)->unique()->values()->all();
            })
            ->all();

        $payloadGroups = $payloadGroups->map(function ($g) use ($urunIdsByMontajUrunId) {
            $g['items'] = collect($g['items'])->map(function ($it) use ($urunIdsByMontajUrunId) {
                $mid = $it['montaj_urun_id'] ?? null;
                if ($mid) {
                    $it['urun_ids'] = $urunIdsByMontajUrunId[$mid] ?? [];
                }
                return $it;
            })->values()->all();
            return $g;
        })->values();

        return response()->json([
            'ok' => true,
            'line' => [
                'teklif_detay_id' => $detay->id,
                'stok_kod' => $stokKod,
                'stok_aciklama' => $stokAciklama,
            ],
            'groups' => $payloadGroups,
        ]);
    }

    public function saveMontajLineDetails(Request $request, TeklifDetay $detay)
    {
        $validated = $request->validate([
            'items' => ['array'],
            'items.*.montaj_grup_id' => ['nullable', 'integer'],
            'items.*.urun_id' => ['nullable', 'integer'],
            'items.*.urun_kod' => ['nullable', 'string', 'max:50'],
            'items.*.birim' => ['nullable', 'in:Adet,Metre,Kilo'],
            'items.*.miktar' => ['nullable', 'numeric', 'min:0'],
            'items.*.birim_fiyat' => ['nullable', 'numeric', 'min:0'],
            'items.*.doviz' => ['nullable', 'in:TL,USD,EUR'],
            'items.*.sirano' => ['nullable', 'integer', 'min:0'],
        ]);

        $items = $validated['items'] ?? [];

        DB::transaction(function () use ($detay, $items) {
            TeklifSatirMontajDetay::where('teklif_detay_id', $detay->id)->delete();

            $sirano = 0;
            foreach ($items as $item) {
                $sirano++;
                $qty = (float) ($item['miktar'] ?? 0);
                $price = (float) ($item['birim_fiyat'] ?? 0);
                $satirTutar = round($qty * $price, 2);

                TeklifSatirMontajDetay::create([
                    'teklif_detay_id' => $detay->id,
                    'montaj_grup_id' => $item['montaj_grup_id'] ?? null,
                    'urun_id' => $item['urun_id'] ?? null,
                    'urun_kod' => $item['urun_kod'] ?? null,
                    'birim' => $item['birim'] ?? 'Adet',
                    'miktar' => $qty,
                    'birim_fiyat' => $price,
                    'doviz' => $item['doviz'] ?? 'TL',
                    'satir_tutar' => $satirTutar,
                    'sirano' => $sirano,
                ]);
            }
        });

        return response()->json(['ok' => true]);
    }

    protected function validatedHeader(Request $request): array
    {
        $validated = $request->validate([
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

        $tur = strtolower(trim((string) $request->input('tur', '')));
        if ($tur === 'alim' || $tur === 'satis') {
            $prefix = $tur === 'alim' ? '2' : '1';
            $no = (string) ($validated['teklif_no'] ?? '');
            if (!str_starts_with($no, $prefix)) {
                throw ValidationException::withMessages([
                    'teklif_no' => ['Teklif no ilk rakam ' . $prefix . ' ile baŸlamal.'],
                ]);
            }
        }

        return $validated;
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

