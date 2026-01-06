<?php

namespace App\Http\Controllers;

use App\Models\Firm;
use App\Models\Product;
use App\Models\FiyatListesi;
use App\Models\FiyatListesiDetay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FiyatListesiController extends Controller
{
    public function index()
    {
        $listeler = FiyatListesi::query()
            ->with('firm')
            ->orderByDesc('id')
            ->paginate(15);

        return view('price-lists.index', compact('listeler'));
    }

    public function create()
    {
        $firms = Firm::orderBy('carikod')->get();
        $products = Product::where('pasif', false)->orderBy('kod')->get();

        $hazirlayan = null;
        if (Auth::check()) {
            $user = Auth::user();
            $fullName = trim(($user->ad ?? '') . ' ' . ($user->soyad ?? ''));
            $hazirlayan = $fullName !== '' ? $fullName : ($user->mail ?? null);
        }

        return view('price-lists.create', compact('firms', 'products', 'hazirlayan'));
    }

    public function edit(FiyatListesi $fiyatListesi)
    {
        $fiyatListesi->load(['firm', 'detaylar.urun']);

        $firms = Firm::orderBy('carikod')->get();
        $products = Product::where('pasif', false)->orderBy('kod')->get();

        return view('price-lists.edit', compact('fiyatListesi', 'firms', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'firm_id' => ['required', 'integer', 'exists:firmalar,id'],
            'baslangic_tarihi' => ['required', 'date'],
            'bitis_tarihi' => ['required', 'date', 'after_or_equal:baslangic_tarihi'],
            'hazirlayan' => ['nullable', 'string', 'max:150'],
            'lines' => ['array'],
            'lines.*.urun_id' => ['nullable', 'integer', 'exists:urunler,id'],
            'lines.*.stok_kod' => ['nullable', 'string', 'max:50'],
            'lines.*.stok_aciklama' => ['nullable', 'string', 'max:255'],
            'lines.*.birim_fiyat' => ['nullable', 'numeric'],
            'lines.*.doviz' => ['nullable', Rule::in(['TL', 'USD', 'EUR'])],
        ]);

        if (empty($validated['hazirlayan']) && Auth::check()) {
            $user = Auth::user();
            $fullName = trim(($user->ad ?? '') . ' ' . ($user->soyad ?? ''));
            $validated['hazirlayan'] = $fullName !== '' ? $fullName : ($user->mail ?? null);
        }

        $lines = $request->input('lines', []);

        DB::transaction(function () use ($validated, $lines) {
            $liste = FiyatListesi::create([
                'firm_id' => $validated['firm_id'],
                'baslangic_tarihi' => $validated['baslangic_tarihi'],
                'bitis_tarihi' => $validated['bitis_tarihi'] ?? null,
                'hazirlayan' => $validated['hazirlayan'] ?? null,
            ]);

            foreach ($lines as $line) {
                $urunId = $line['urun_id'] ?? null;
                $stokKod = trim((string) ($line['stok_kod'] ?? ''));
                $stokAciklama = trim((string) ($line['stok_aciklama'] ?? ''));
                $birimFiyat = (float) ($line['birim_fiyat'] ?? 0);
                $doviz = strtoupper(trim((string) ($line['doviz'] ?? 'TL')));
                if (!in_array($doviz, ['TL', 'USD', 'EUR'], true)) {
                    $doviz = 'TL';
                }

                if ($urunId) {
                    $urun = Product::find($urunId);
                    if ($urun) {
                        if ($stokKod === '') $stokKod = (string) ($urun->kod ?? '');
                        if ($stokAciklama === '') $stokAciklama = (string) ($urun->aciklama ?? '');
                    }
                }

                if ($urunId === null && $stokKod === '' && $stokAciklama === '' && $birimFiyat <= 0) {
                    continue;
                }

                FiyatListesiDetay::create([
                    'fiyat_listesi_id' => $liste->id,
                    'urun_id' => $urunId,
                    'stok_kod' => $stokKod !== '' ? $stokKod : null,
                    'stok_aciklama' => $stokAciklama !== '' ? $stokAciklama : null,
                    'birim_fiyat' => $birimFiyat,
                    'doviz' => $doviz,
                ]);
            }
        });

        return redirect()->route('price-lists.index')
            ->with('status', 'Fiyat listesi kaydedildi.');
    }

    public function update(Request $request, FiyatListesi $fiyatListesi)
    {
        $validated = $request->validate([
            'firm_id' => ['required', 'integer', 'exists:firmalar,id'],
            'baslangic_tarihi' => ['required', 'date'],
            'bitis_tarihi' => ['required', 'date', 'after_or_equal:baslangic_tarihi'],
            'hazirlayan' => ['nullable', 'string', 'max:150'],
            'lines' => ['array'],
            'lines.*.urun_id' => ['nullable', 'integer', 'exists:urunler,id'],
            'lines.*.stok_kod' => ['nullable', 'string', 'max:50'],
            'lines.*.stok_aciklama' => ['nullable', 'string', 'max:255'],
            'lines.*.birim_fiyat' => ['nullable', 'numeric'],
            'lines.*.doviz' => ['nullable', Rule::in(['TL', 'USD', 'EUR'])],
        ]);

        $lines = $request->input('lines', []);

        DB::transaction(function () use ($validated, $lines, $fiyatListesi) {
            $fiyatListesi->update([
                'firm_id' => $validated['firm_id'],
                'baslangic_tarihi' => $validated['baslangic_tarihi'],
                'bitis_tarihi' => $validated['bitis_tarihi'],
                'hazirlayan' => $validated['hazirlayan'] ?? null,
            ]);

            $fiyatListesi->detaylar()->delete();

            foreach ($lines as $line) {
                $urunId = $line['urun_id'] ?? null;
                $stokKod = trim((string) ($line['stok_kod'] ?? ''));
                $stokAciklama = trim((string) ($line['stok_aciklama'] ?? ''));
                $birimFiyat = (float) ($line['birim_fiyat'] ?? 0);
                $doviz = strtoupper(trim((string) ($line['doviz'] ?? 'TL')));
                if (!in_array($doviz, ['TL', 'USD', 'EUR'], true)) {
                    $doviz = 'TL';
                }

                if ($urunId) {
                    $urun = Product::find($urunId);
                    if ($urun) {
                        if ($stokKod === '') $stokKod = (string) ($urun->kod ?? '');
                        if ($stokAciklama === '') $stokAciklama = (string) ($urun->aciklama ?? '');
                    }
                }

                if ($urunId === null && $stokKod === '' && $stokAciklama === '' && $birimFiyat <= 0) {
                    continue;
                }

                FiyatListesiDetay::create([
                    'fiyat_listesi_id' => $fiyatListesi->id,
                    'urun_id' => $urunId,
                    'stok_kod' => $stokKod !== '' ? $stokKod : null,
                    'stok_aciklama' => $stokAciklama !== '' ? $stokAciklama : null,
                    'birim_fiyat' => $birimFiyat,
                    'doviz' => $doviz,
                ]);
            }
        });

        return redirect()->route('price-lists.index')
            ->with('status', 'Fiyat listesi güncellendi.');
    }

    public function lookupPrice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'carikod' => ['required', 'string', 'max:50'],
            'tarih' => ['required', 'date'],
            'urun_id' => ['nullable', 'integer', 'min:1'],
            'stok_kod' => ['nullable', 'string', 'max:50'],
        ]);

        if ($validator->fails()) {
            return response()->json(['ok' => false, 'errors' => $validator->errors()], 422);
        }

        $carikod = (string) $request->input('carikod');
        $tarih = (string) $request->input('tarih');
        $urunId = $request->filled('urun_id') ? (int) $request->input('urun_id') : null;
        $stokKod = trim((string) $request->input('stok_kod', ''));

        $firm = Firm::query()->where('carikod', $carikod)->first();
        if (!$firm) {
            return response()->json(['ok' => false, 'message' => 'Firma bulunamadı.'], 404);
        }

        $list = FiyatListesi::query()
            ->where('firm_id', $firm->id)
            ->where('baslangic_tarihi', '<=', $tarih)
            ->where(function ($q) use ($tarih) {
                $q->whereNull('bitis_tarihi')
                    ->orWhere('bitis_tarihi', '>=', $tarih);
            })
            ->orderByDesc('baslangic_tarihi')
            ->orderByDesc('id')
            ->first();

        if (!$list) {
            return response()->json(['ok' => true, 'found' => false]);
        }

        $detay = null;

        if ($urunId) {
            $detay = FiyatListesiDetay::query()
                ->where('fiyat_listesi_id', $list->id)
                ->where('urun_id', $urunId)
                ->orderByDesc('id')
                ->first();
        }

        if (!$detay && $stokKod !== '') {
            $detay = FiyatListesiDetay::query()
                ->where('fiyat_listesi_id', $list->id)
                ->whereNull('urun_id')
                ->where('stok_kod', $stokKod)
                ->orderByDesc('id')
                ->first();
        }

        if (!$detay) {
            return response()->json(['ok' => true, 'found' => false]);
        }

        return response()->json([
            'ok' => true,
            'found' => true,
            'fiyat_listesi_id' => $list->id,
            'birim_fiyat' => (float) ($detay->birim_fiyat ?? 0),
            'doviz' => (string) ($detay->doviz ?? 'TL'),
        ]);
    }
}
