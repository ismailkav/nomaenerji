<?php

namespace App\Http\Controllers;

use App\Models\Depot;
use App\Models\Product;
use App\Models\StockFiche;
use App\Models\StockFicheLine;
use App\Models\StockInventory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StockFicheController extends Controller
{
    private function titleForType(string $type): string
    {
        return match ($type) {
            'sayim_giris' => 'Sayım Giriş',
            'sayim_cikis' => 'Sayım Çıkış',
            'depo_transfer' => 'Depo Transfer',
            default => 'Stok Fişi',
        };
    }

    private function activeForType(string $type): string
    {
        return match ($type) {
            'sayim_giris' => 'stock-count-in',
            'sayim_cikis' => 'stock-count-out',
            'depo_transfer' => 'stock-depot-transfer',
            default => 'stock',
        };
    }

    private function prefixForType(string $type): string
    {
        return match ($type) {
            'sayim_giris' => 'SG',
            'sayim_cikis' => 'SC',
            'depo_transfer' => 'DT',
            default => 'SF',
        };
    }

    public function indexCountIn(Request $request)
    {
        return $this->index($request, 'sayim_giris');
    }

    public function indexCountOut(Request $request)
    {
        return $this->index($request, 'sayim_cikis');
    }

    public function indexTransfer(Request $request)
    {
        return $this->index($request, 'depo_transfer');
    }

    private function index(Request $request, string $type)
    {
        $query = StockFiche::query()
            ->with(['depo', 'cikisDepo', 'girisDepo'])
            ->where('tip', $type)
            ->orderByDesc('tarih')
            ->orderByDesc('id');

        if ($request->filled('tarih_baslangic')) {
            $query->whereDate('tarih', '>=', $request->date('tarih_baslangic'));
        }

        if ($request->filled('tarih_bitis')) {
            $query->whereDate('tarih', '<=', $request->date('tarih_bitis'));
        }

        if ($request->filled('fis_no')) {
            $query->where('fis_no', 'like', '%' . $request->input('fis_no') . '%');
        }

        $fiches = $query->paginate(20)->withQueryString();

        return view('stock.vouchers.index', [
            'type' => $type,
            'title' => $this->titleForType($type),
            'active' => $this->activeForType($type),
            'fiches' => $fiches,
        ]);
    }

    public function createCountIn(Request $request)
    {
        return $this->create($request, 'sayim_giris');
    }

    public function createCountOut(Request $request)
    {
        return $this->create($request, 'sayim_cikis');
    }

    public function createTransfer(Request $request)
    {
        return $this->create($request, 'depo_transfer');
    }

    private function create(Request $request, string $type)
    {
        $max = StockFiche::where('tip', $type)->max('fis_sira');
        $next = (int) ($max ?? 0) + 1;
        $prefix = $this->prefixForType($type);

        $fiche = new StockFiche([
            'tip' => $type,
            'fis_sira' => $next,
            'fis_no' => $prefix . '-' . str_pad((string) $next, 6, '0', STR_PAD_LEFT),
            'tarih' => Carbon::today(),
            'hazirlayan_user_id' => Auth::id(),
            'islem_tarihi' => Carbon::now(),
        ]);

        return $this->renderEditForm($request, $fiche, $type, true);
    }

    public function editCountIn(Request $request, StockFiche $fiche)
    {
        return $this->edit($request, $fiche, 'sayim_giris');
    }

    public function editCountOut(Request $request, StockFiche $fiche)
    {
        return $this->edit($request, $fiche, 'sayim_cikis');
    }

    public function editTransfer(Request $request, StockFiche $fiche)
    {
        return $this->edit($request, $fiche, 'depo_transfer');
    }

    private function edit(Request $request, StockFiche $fiche, string $type)
    {
        abort_unless($fiche->tip === $type, 404);

        $fiche->load(['lines', 'depo', 'cikisDepo', 'girisDepo']);

        return $this->renderEditForm($request, $fiche, $type, false);
    }

    private function renderEditForm(Request $request, StockFiche $fiche, string $type, bool $isNew)
    {
        $depots = Depot::orderBy('kod')->get();
        $products = Product::orderBy('kod')->get(['id', 'kod', 'aciklama']);
        $user = Auth::user();

        return view('stock.vouchers.edit', [
            'type' => $type,
            'title' => $this->titleForType($type) . ' Fişi',
            'active' => $this->activeForType($type),
            'fiche' => $fiche,
            'isNew' => $isNew,
            'depots' => $depots,
            'products' => $products,
            'userDisplay' => $user ? trim(($user->ad ?? '') . ' ' . ($user->soyad ?? '')) : '',
        ]);
    }

    public function storeCountIn(Request $request)
    {
        return $this->store($request, 'sayim_giris');
    }

    public function storeCountOut(Request $request)
    {
        return $this->store($request, 'sayim_cikis');
    }

    public function storeTransfer(Request $request)
    {
        return $this->store($request, 'depo_transfer');
    }

    private function store(Request $request, string $type)
    {
        $fiche = new StockFiche(['tip' => $type]);
        $this->saveFicheFromRequest($request, $fiche, $type, true);

        return redirect()->route($this->editRouteNameForType($type), ['fiche' => $fiche->id])
            ->with('status', 'Fiş kaydedildi.');
    }

    public function updateCountIn(Request $request, StockFiche $fiche)
    {
        return $this->update($request, $fiche, 'sayim_giris');
    }

    public function updateCountOut(Request $request, StockFiche $fiche)
    {
        return $this->update($request, $fiche, 'sayim_cikis');
    }

    public function updateTransfer(Request $request, StockFiche $fiche)
    {
        return $this->update($request, $fiche, 'depo_transfer');
    }

    private function update(Request $request, StockFiche $fiche, string $type)
    {
        abort_unless($fiche->tip === $type, 404);

        $this->saveFicheFromRequest($request, $fiche, $type, false);

        return redirect()->back()->with('status', 'Fiş güncellendi.');
    }

    public function destroyCountIn(Request $request, StockFiche $fiche)
    {
        return $this->destroy($request, $fiche, 'sayim_giris');
    }

    public function destroyCountOut(Request $request, StockFiche $fiche)
    {
        return $this->destroy($request, $fiche, 'sayim_cikis');
    }

    public function destroyTransfer(Request $request, StockFiche $fiche)
    {
        return $this->destroy($request, $fiche, 'depo_transfer');
    }

    private function destroy(Request $request, StockFiche $fiche, string $type)
    {
        abort_unless($fiche->tip === $type, 404);

        DB::transaction(function () use ($fiche, $type) {
            $fiche->load(['lines']);
            $this->reverseStockEffects($fiche, $type);
            $fiche->delete();
        });

        return redirect()->route($this->indexRouteNameForType($type))->with('status', 'Fiş silindi.');
    }

    private function saveFicheFromRequest(Request $request, StockFiche $fiche, string $type, bool $isNew): void
    {
        $baseRules = [
            'tarih' => ['nullable', 'date'],
            'aciklama' => ['nullable', 'string'],
            'lines' => ['array'],
            'lines.*.id' => ['nullable', 'integer'],
            'lines.*.stokkod' => ['nullable', 'string', 'max:100'],
            'lines.*.stokaciklama' => ['nullable', 'string', 'max:255'],
            'lines.*.miktar' => ['nullable', 'numeric', 'min:0'],
        ];

        if ($type === 'depo_transfer') {
            $baseRules['cikis_depo_id'] = ['required', 'integer', Rule::exists('depolar', 'id')];
            $baseRules['giris_depo_id'] = ['required', 'integer', Rule::exists('depolar', 'id')];
        } else {
            $baseRules['depo_id'] = ['required', 'integer', Rule::exists('depolar', 'id')];
        }

        $validated = $request->validate($baseRules);

        if ($type === 'depo_transfer') {
            $cikis = (int) ($validated['cikis_depo_id'] ?? 0);
            $giris = (int) ($validated['giris_depo_id'] ?? 0);
            if ($cikis > 0 && $giris > 0 && $cikis === $giris) {
                $request->validate([
                    'giris_depo_id' => ['different:cikis_depo_id'],
                ]);
            }
        }

        DB::transaction(function () use ($validated, $fiche, $type, $isNew) {
            $oldDepotId = $fiche->exists ? (int) ($fiche->depo_id ?? 0) : 0;
            $oldCikisDepotId = $fiche->exists ? (int) ($fiche->cikis_depo_id ?? 0) : 0;
            $oldGirisDepotId = $fiche->exists ? (int) ($fiche->giris_depo_id ?? 0) : 0;
            $oldLines = $fiche->exists
                ? $fiche->lines()->get(['id', 'stokkod', 'miktar'])
                : collect();

            if ($isNew) {
                $max = StockFiche::where('tip', $type)->lockForUpdate()->max('fis_sira');
                $next = (int) ($max ?? 0) + 1;
                $prefix = $this->prefixForType($type);
                $fiche->tip = $type;
                $fiche->fis_sira = $next;
                $fiche->fis_no = $prefix . '-' . str_pad((string) $next, 6, '0', STR_PAD_LEFT);
            }

            $payload = [
                'tarih' => $validated['tarih'] ?? null,
                'aciklama' => $validated['aciklama'] ?? null,
                'hazirlayan_user_id' => Auth::id(),
                'islem_tarihi' => Carbon::now(),
            ];

            if ($type === 'depo_transfer') {
                $payload['cikis_depo_id'] = $validated['cikis_depo_id'] ?? null;
                $payload['giris_depo_id'] = $validated['giris_depo_id'] ?? null;
                $payload['depo_id'] = null;
            } else {
                $payload['depo_id'] = $validated['depo_id'] ?? null;
                $payload['cikis_depo_id'] = null;
                $payload['giris_depo_id'] = null;
            }

            $fiche->fill($payload);
            $fiche->save();

            $lines = $validated['lines'] ?? [];
            $keptIds = [];
            $newLines = [];

            foreach ($lines as $line) {
                $stokKod = trim((string) ($line['stokkod'] ?? ''));
                $miktar = (float) ($line['miktar'] ?? 0);

                if ($stokKod === '' && $miktar <= 0) {
                    continue;
                }

                $linePayload = [
                    'stokkod' => $stokKod !== '' ? $stokKod : null,
                    'stokaciklama' => $line['stokaciklama'] ?? null,
                    'miktar' => $line['miktar'] ?? 0,
                ];

                if (!empty($line['id'])) {
                    $existing = StockFicheLine::where('stok_fis_id', $fiche->id)->find($line['id']);
                    if ($existing) {
                        $existing->update($linePayload);
                        $keptIds[] = $existing->id;
                        $newLines[] = ['stokkod' => $stokKod, 'miktar' => $miktar];
                    }
                } else {
                    $created = $fiche->lines()->create($linePayload);
                    $keptIds[] = $created->id;
                    $newLines[] = ['stokkod' => $stokKod, 'miktar' => $miktar];
                }
            }

            if (!empty($keptIds)) {
                $fiche->lines()->whereNotIn('id', $keptIds)->delete();
            } else {
                $fiche->lines()->delete();
            }

            $newDepotId = (int) ($fiche->depo_id ?? 0);
            $newCikisDepotId = (int) ($fiche->cikis_depo_id ?? 0);
            $newGirisDepotId = (int) ($fiche->giris_depo_id ?? 0);

            // Reverse old effects.
            foreach ($oldLines as $oldLine) {
                $stokKod = trim((string) ($oldLine->stokkod ?? ''));
                $qty = (float) ($oldLine->miktar ?? 0);
                $this->applyStockEffectsForLine($type, $oldDepotId, $oldCikisDepotId, $oldGirisDepotId, $stokKod, -$qty);
            }

            // Apply new effects.
            foreach ($newLines as $newLine) {
                $stokKod = trim((string) ($newLine['stokkod'] ?? ''));
                $qty = (float) ($newLine['miktar'] ?? 0);
                $this->applyStockEffectsForLine($type, $newDepotId, $newCikisDepotId, $newGirisDepotId, $stokKod, $qty);
            }
        });
    }

    private function reverseStockEffects(StockFiche $fiche, string $type): void
    {
        $depoId = (int) ($fiche->depo_id ?? 0);
        $cikisDepotId = (int) ($fiche->cikis_depo_id ?? 0);
        $girisDepotId = (int) ($fiche->giris_depo_id ?? 0);

        foreach ($fiche->lines as $line) {
            $stokKod = trim((string) ($line->stokkod ?? ''));
            $qty = (float) ($line->miktar ?? 0);
            $this->applyStockEffectsForLine($type, $depoId, $cikisDepotId, $girisDepotId, $stokKod, -$qty);
        }
    }

    private function applyStockEffectsForLine(
        string $type,
        int $depoId,
        int $cikisDepotId,
        int $girisDepotId,
        string $stokKod,
        float $qty
    ): void {
        if ($stokKod === '' || abs($qty) < 0.0000001) {
            return;
        }

        if ($type === 'sayim_giris') {
            $this->applyStockInventoryDelta($depoId, $stokKod, $qty);
            return;
        }

        if ($type === 'sayim_cikis') {
            $this->applyStockInventoryDelta($depoId, $stokKod, -$qty);
            return;
        }

        if ($type === 'depo_transfer') {
            $this->applyStockInventoryDelta($cikisDepotId, $stokKod, -$qty);
            $this->applyStockInventoryDelta($girisDepotId, $stokKod, $qty);
        }
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

    private function indexRouteNameForType(string $type): string
    {
        return match ($type) {
            'sayim_giris' => 'stock.count-in.index',
            'sayim_cikis' => 'stock.count-out.index',
            'depo_transfer' => 'stock.depot-transfer.index',
            default => 'stock.count-in.index',
        };
    }

    private function editRouteNameForType(string $type): string
    {
        return match ($type) {
            'sayim_giris' => 'stock.count-in.edit',
            'sayim_cikis' => 'stock.count-out.edit',
            'depo_transfer' => 'stock.depot-transfer.edit',
            default => 'stock.count-in.edit',
        };
    }
}
