<?php

namespace App\Http\Controllers;

use App\Models\ConsignmentFiche;
use App\Models\ConsignmentFicheLine;
use App\Models\Depot;
use App\Models\Firm;
use App\Models\Product;
use App\Models\Project;
use App\Models\StockInventory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ConsignmentController extends Controller
{
    private function titleForType(string $type): string
    {
        return $type === 'cikis' ? 'Konsinye Çıkış' : 'Konsinye Giriş';
    }

    private function activeForType(string $type): string
    {
        return $type === 'cikis' ? 'stock-consignment-out' : 'stock-consignment-in';
    }

    private function prefixForType(string $type): string
    {
        return $type === 'cikis' ? 'KC' : 'KG';
    }

    public function indexIn(Request $request)
    {
        return $this->index($request, 'giris');
    }

    public function indexOut(Request $request)
    {
        return $this->index($request, 'cikis');
    }

    private function index(Request $request, string $type)
    {
        $query = ConsignmentFiche::query()->where('tip', $type)->orderByDesc('tarih')->orderByDesc('id');

        if ($request->filled('tarih_baslangic')) {
            $query->whereDate('tarih', '>=', $request->date('tarih_baslangic'));
        }

        if ($request->filled('tarih_bitis')) {
            $query->whereDate('tarih', '<=', $request->date('tarih_bitis'));
        }

        if ($request->filled('carikod')) {
            $query->where('carikod', 'like', '%' . $request->input('carikod') . '%');
        }

        if ($request->filled('durum')) {
            $query->where('durum', $request->input('durum'));
        }

        $fiches = $query->paginate(20)->withQueryString();

        return view('stock.consignment.index', [
            'type' => $type,
            'title' => $this->titleForType($type),
            'active' => $this->activeForType($type),
            'fiches' => $fiches,
        ]);
    }

    public function newIn(Request $request)
    {
        return $this->new($request, 'giris');
    }

    public function newOut(Request $request)
    {
        return $this->new($request, 'cikis');
    }

    private function new(Request $request, string $type)
    {
        $max = ConsignmentFiche::where('tip', $type)->max('fis_sira');
        $next = (int) ($max ?? 0) + 1;
        $prefix = $this->prefixForType($type);

        $fiche = new ConsignmentFiche([
            'tip' => $type,
            'fis_sira' => $next,
            'fis_no' => $prefix . '-' . str_pad((string) $next, 6, '0', STR_PAD_LEFT),
            'tarih' => Carbon::today(),
            'durum' => 'açık',
            'hazirlayan_user_id' => Auth::id(),
            'islem_tarihi' => Carbon::now(),
        ]);

        return $this->renderEditForm($request, $fiche, $type, true);
    }

    public function editIn(Request $request, ConsignmentFiche $fiche)
    {
        return $this->edit($request, $fiche, 'giris');
    }

    public function editOut(Request $request, ConsignmentFiche $fiche)
    {
        return $this->edit($request, $fiche, 'cikis');
    }

    private function edit(Request $request, ConsignmentFiche $fiche, string $type)
    {
        abort_unless($fiche->tip === $type, 404);

        $fiche->load(['lines', 'depo']);

        return $this->renderEditForm($request, $fiche, $type, false);
    }

    private function renderEditForm(Request $request, ConsignmentFiche $fiche, string $type, bool $isNew)
    {
        $firms = Firm::with('authorities')->orderBy('carikod')->get();
        $depots = Depot::orderBy('kod')->get();
        $projects = Project::orderBy('kod')->get();
        $products = Product::orderBy('kod')->get(['id', 'kod', 'aciklama']);
        $user = Auth::user();

        return view('stock.consignment.edit', [
            'type' => $type,
            'title' => $this->titleForType($type) . ' Fişi',
            'active' => $this->activeForType($type),
            'fiche' => $fiche,
            'isNew' => $isNew,
            'firms' => $firms,
            'depots' => $depots,
            'projects' => $projects,
            'products' => $products,
            'userDisplay' => $user ? trim(($user->ad ?? '') . ' ' . ($user->soyad ?? '')) : '',
        ]);
    }

    public function storeIn(Request $request)
    {
        return $this->store($request, 'giris');
    }

    public function storeOut(Request $request)
    {
        return $this->store($request, 'cikis');
    }

    private function store(Request $request, string $type)
    {
        $fiche = new ConsignmentFiche(['tip' => $type]);

        $this->saveFicheFromRequest($request, $fiche, $type, true);

        return redirect()->route(
            $type === 'cikis' ? 'stock.consignment-out.edit' : 'stock.consignment-in.edit',
            ['fiche' => $fiche->id]
        )->with('status', 'Fiş kaydedildi.');
    }

    public function updateIn(Request $request, ConsignmentFiche $fiche)
    {
        return $this->update($request, $fiche, 'giris');
    }

    public function updateOut(Request $request, ConsignmentFiche $fiche)
    {
        return $this->update($request, $fiche, 'cikis');
    }

    private function update(Request $request, ConsignmentFiche $fiche, string $type)
    {
        abort_unless($fiche->tip === $type, 404);

        $this->saveFicheFromRequest($request, $fiche, $type, false);

        return redirect()->back()->with('status', 'Fiş güncellendi.');
    }

    private function saveFicheFromRequest(Request $request, ConsignmentFiche $fiche, string $type, bool $isNew): void
    {
        $validated = $request->validate([
            'tarih' => ['nullable', 'date'],
            'carikod' => ['required', 'string', 'max:50', Rule::exists('firmalar', 'carikod')],
            'cariaciklama' => ['nullable', 'string', 'max:255'],
            'depo_id' => ['required', 'integer', Rule::exists('depolar', 'id')],
            'teslim_tarihi' => ['nullable', 'date'],
            'durum' => ['nullable', 'string', 'max:50'],
            'aciklama' => ['nullable', 'string'],
            'proje_id' => ['nullable', 'integer'],
            'lines' => ['array'],
            'lines.*.id' => ['nullable', 'integer'],
            'lines.*.stokkod' => ['nullable', 'string', 'max:100'],
            'lines.*.stokaciklama' => ['nullable', 'string', 'max:255'],
            'lines.*.miktar' => ['nullable', 'numeric'],
            'lines.*.iade_miktar' => ['nullable', 'numeric'],
            'lines.*.durum' => ['nullable', 'string', 'max:50', Rule::in(['açık', 'kısmi iade', 'kapalı'])],
        ]);

        DB::transaction(function () use ($validated, $fiche, $type, $isNew) {
            $oldDepotId = $fiche->exists ? (int) ($fiche->depo_id ?? 0) : 0;
            $oldLines = $fiche->exists
                ? $fiche->lines()->get(['id', 'stokkod', 'miktar'])
                : collect();

            if ($isNew) {
                $max = ConsignmentFiche::where('tip', $type)->lockForUpdate()->max('fis_sira');
                $next = (int) ($max ?? 0) + 1;
                $prefix = $this->prefixForType($type);
                $fiche->tip = $type;
                $fiche->fis_sira = $next;
                $fiche->fis_no = $prefix . '-' . str_pad((string) $next, 6, '0', STR_PAD_LEFT);
            }

            $carikod = trim((string) ($validated['carikod'] ?? ''));
            $cari = $carikod !== '' ? Firm::where('carikod', $carikod)->first() : null;

            $fiche->fill([
                'tarih' => $validated['tarih'] ?? null,
                'cari_id' => $cari?->id,
                'carikod' => $carikod !== '' ? $carikod : null,
                'cariaciklama' => $cari?->cariaciklama ?? ($validated['cariaciklama'] ?? null),
                'depo_id' => $validated['depo_id'] ?? null,
                'teslim_tarihi' => $validated['teslim_tarihi'] ?? null,
                'durum' => $validated['durum'] ?? null,
                'aciklama' => $validated['aciklama'] ?? null,
                'proje_id' => $validated['proje_id'] ?? null,
                'hazirlayan_user_id' => Auth::id(),
                'islem_tarihi' => Carbon::now(),
            ]);
            $fiche->save();

            $lines = $validated['lines'] ?? [];
            $keptIds = [];
            $newDepotId = (int) ($fiche->depo_id ?? 0);
            $newLineQuantities = [];

            foreach ($lines as $line) {
                $stokKod = trim((string) ($line['stokkod'] ?? ''));
                $miktar = (float) ($line['miktar'] ?? 0);
                $iade = (float) ($line['iade_miktar'] ?? 0);

                if ($stokKod === '' && $miktar <= 0 && $iade <= 0) {
                    continue;
                }

                $payload = [
                    'stokkod' => $stokKod !== '' ? $stokKod : null,
                    'stokaciklama' => $line['stokaciklama'] ?? null,
                    'miktar' => $line['miktar'] ?? 0,
                    'durum' => $line['durum'] ?? null,
                ];

                if (!empty($line['id'])) {
                    $existing = ConsignmentFicheLine::where('konsinye_fis_id', $fiche->id)->find($line['id']);
                    if ($existing) {
                        if (array_key_exists('iade_miktar', $line)) {
                            $payload['iade_miktar'] = $line['iade_miktar'] ?? 0;
                        }
                        $existing->update($payload);
                        $keptIds[] = $existing->id;
                        $newLineQuantities[] = [
                            'stokkod' => $stokKod,
                            'miktar' => $miktar,
                        ];
                    }
                } else {
                    $payload['iade_miktar'] = $line['iade_miktar'] ?? 0;
                    $created = $fiche->lines()->create($payload);
                    $keptIds[] = $created->id;
                    $newLineQuantities[] = [
                        'stokkod' => $stokKod,
                        'miktar' => $miktar,
                    ];
                }
            }

            if (!empty($keptIds)) {
                $fiche->lines()->whereNotIn('id', $keptIds)->delete();
            } else {
                $fiche->lines()->delete();
            }

            $multiplier = $type === 'cikis' ? -1.0 : 1.0;

            foreach ($oldLines as $oldLine) {
                $stokKod = trim((string) ($oldLine->stokkod ?? ''));
                if ($stokKod === '' || $oldDepotId <= 0) {
                    continue;
                }
                $this->applyStockInventoryDelta($oldDepotId, $stokKod, $multiplier * (-(float) ($oldLine->miktar ?? 0)));
            }

            foreach ($newLineQuantities as $newLine) {
                $stokKod = trim((string) ($newLine['stokkod'] ?? ''));
                if ($stokKod === '' || $newDepotId <= 0) {
                    continue;
                }
                $this->applyStockInventoryDelta($newDepotId, $stokKod, $multiplier * ((float) ($newLine['miktar'] ?? 0)));
            }
        });
    }

    private function applyStockInventoryDelta(int $depoId, string $stokKod, float $delta): void
    {
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

    public function lookupCari(Request $request)
    {
        $kod = trim((string) $request->query('carikod', ''));
        if ($kod === '') {
            return response()->json(['ok' => false], 400);
        }

        $cari = Firm::where('carikod', $kod)->first();
        if (!$cari) {
            return response()->json(['ok' => false], 404);
        }

        return response()->json([
            'ok' => true,
            'carikod' => $cari->carikod,
            'cariaciklama' => $cari->cariaciklama,
        ]);
    }

    public function searchProducts(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $products = Product::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('kod', 'like', '%' . $q . '%')
                    ->orWhere('aciklama', 'like', '%' . $q . '%');
            })
            ->orderBy('kod')
            ->limit(50)
            ->get(['id', 'kod', 'aciklama']);

        return response()->json([
            'ok' => true,
            'items' => $products,
        ]);
    }
}
