<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductDetailGroup;
use App\Models\ProductRecipe;
use App\Models\ProductSubGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('kod', 'like', '%' . $search . '%')
                    ->orWhere('aciklama', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->input('kategori_id'));
        }

        if ($request->filled('durum')) {
            if ($request->input('durum') === 'aktif') {
                $query->where('pasif', false);
            } elseif ($request->input('durum') === 'pasif') {
                $query->where('pasif', true);
            }
        }

        $products = $query->orderBy('kod')->paginate(15)->appends($request->query());

        $categories = ProductCategory::orderBy('ad')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = ProductCategory::orderBy('ad')->get();

        $subGroupsByGroup = ProductSubGroup::orderBy('ad')->get()->groupBy('urun_grup_id')->map(function ($items) {
            return $items->map(fn ($i) => ['id' => $i->id, 'ad' => $i->ad])->values();
        });

        $detailGroupsBySubGroup = ProductDetailGroup::orderBy('ad')->get()->groupBy('urun_alt_grup_id')->map(function ($items) {
            return $items->map(fn ($i) => ['id' => $i->id, 'ad' => $i->ad])->values();
        });

        $stockCards = Product::query()->orderBy('kod')->get(['id', 'kod', 'aciklama']);

        return view('products.create', compact('categories', 'subGroupsByGroup', 'detailGroupsBySubGroup', 'stockCards'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $recipeItems = $this->validatedRecipeItems($request, null);

        if ($request->hasFile('resim')) {
            $data['resim_yolu'] = $this->storeProductImage($request);
        }

        DB::transaction(function () use ($data, $recipeItems) {
            $product = Product::create($data);
            $this->persistRecipeItems($product, $recipeItems);
        });

        return redirect()->route('products.index')
            ->with('status', 'Ürün başarıyla oluşturuldu.');
    }

    public function edit(Product $product)
    {
        $categories = ProductCategory::orderBy('ad')->get();

        $subGroupsByGroup = ProductSubGroup::orderBy('ad')->get()->groupBy('urun_grup_id')->map(function ($items) {
            return $items->map(fn ($i) => ['id' => $i->id, 'ad' => $i->ad])->values();
        });

        $detailGroupsBySubGroup = ProductDetailGroup::orderBy('ad')->get()->groupBy('urun_alt_grup_id')->map(function ($items) {
            return $items->map(fn ($i) => ['id' => $i->id, 'ad' => $i->ad])->values();
        });

        $stockCards = Product::query()
            ->where('id', '!=', $product->id)
            ->orderBy('kod')
            ->get(['id', 'kod', 'aciklama']);

        $recipeItems = ProductRecipe::query()
            ->with('stokUrun:id,kod,aciklama')
            ->where('urun_id', $product->id)
            ->orderBy('sirano')
            ->orderBy('id')
            ->get()
            ->map(function (ProductRecipe $r) {
                return [
                    'stok_urun_id' => (int) $r->stok_urun_id,
                    'kod' => (string) (optional($r->stokUrun)->kod ?? ''),
                    'aciklama' => (string) (optional($r->stokUrun)->aciklama ?? ''),
                    'miktar' => (string) ($r->miktar ?? '0'),
                ];
            })
            ->values();

        return view('products.edit', compact('product', 'categories', 'subGroupsByGroup', 'detailGroupsBySubGroup', 'stockCards', 'recipeItems'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validatedData($request, $product->id);
        $recipeItems = $this->validatedRecipeItems($request, $product->id);

        if ($request->hasFile('resim')) {
            if ($product->resim_yolu && str_starts_with($product->resim_yolu, 'uploads/products/')) {
                $oldPath = public_path($product->resim_yolu);
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $data['resim_yolu'] = $this->storeProductImage($request);
        }

        DB::transaction(function () use ($product, $data, $recipeItems) {
            $product->update($data);
            $this->persistRecipeItems($product, $recipeItems);
        });

        return redirect()->route('products.index')
            ->with('status', 'Ürün güncellendi.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('status', 'Ürün silindi.');
    }

    protected function validatedData(Request $request, ?int $productId = null): array
    {
        $uniqueRule = 'unique:urunler,kod';
        if ($productId) {
            $uniqueRule .= ',' . $productId;
        }

        $validator = Validator::make($request->all(), [
            'kod'         => ['required', 'string', 'max:50', $uniqueRule],
            'aciklama'    => ['required', 'string', 'max:255'],
            'marka'      => ['nullable', 'string', 'max:150'],
            'satis_fiyat' => ['required', 'numeric', 'min:0'],
            'satis_doviz' => ['nullable', 'string', 'in:TL,USD,EUR'],
            'kdv_oran'    => ['required', 'integer', 'min:0', 'max:100'],
            'kategori_id' => ['nullable', 'integer', 'exists:urun_kategorileri,id'],
            'urun_alt_grup_id' => ['nullable', 'integer', 'exists:urun_alt_gruplari,id'],
            'urun_detay_grup_id' => ['nullable', 'integer', 'exists:urun_detay_gruplari,id'],
            'prm1'        => ['nullable', 'string', 'max:255'],
            'prm2'        => ['nullable', 'string', 'max:255'],
            'prm3'        => ['nullable', 'string', 'max:255'],
            'prm4'        => ['nullable', 'string', 'max:255'],
            'fatura_kodu' => ['nullable', 'string', 'max:50'],
            'resim'       => ['nullable', 'image', 'max:2048'],
            'pasif'       => ['sometimes', 'boolean'],
            'multi'       => ['sometimes', 'boolean'],
            'montaj'      => ['sometimes', 'boolean'],
            'recipe' => ['sometimes', 'array'],
            'recipe.*.stok_urun_id' => ['nullable', 'integer', 'exists:urunler,id'],
            'recipe.*.miktar' => ['nullable', 'numeric', 'min:0'],
        ]);

        $validator->after(function ($validator) use ($request) {
            $groupId = (int) ($request->input('kategori_id') ?? 0);
            $subGroupId = (int) ($request->input('urun_alt_grup_id') ?? 0);
            $detailGroupId = (int) ($request->input('urun_detay_grup_id') ?? 0);

            if ($request->boolean('multi') && $request->boolean('montaj')) {
                $validator->errors()->add('multi', 'Multi ve Montaj aynı anda seçilemez.');
                $validator->errors()->add('montaj', 'Multi ve Montaj aynı anda seçilemez.');
            }

            if ($subGroupId > 0 && $groupId > 0) {
                $ok = ProductSubGroup::whereKey($subGroupId)->where('urun_grup_id', $groupId)->exists();
                if (!$ok) {
                    $validator->errors()->add('urun_alt_grup_id', 'Seçilen alt grup, seçilen ürün gruba ait değil.');
                }
            }

            if ($detailGroupId > 0) {
                if ($subGroupId <= 0) {
                    $validator->errors()->add('urun_detay_grup_id', 'Detay grup seçildiğinde alt grup da seçilmelidir.');
                    return;
                }

                $query = ProductDetailGroup::whereKey($detailGroupId)->where('urun_alt_grup_id', $subGroupId);
                if ($groupId > 0) {
                    $query->where('urun_grup_id', $groupId);
                }

                if (!$query->exists()) {
                    $validator->errors()->add('urun_detay_grup_id', 'Seçilen detay grup, seçilen alt gruba ait değil.');
                }
            }
        });

        $data = $validator->validate();
        $data['satis_doviz'] = strtoupper(trim((string) ($data['satis_doviz'] ?? 'TL')));

        return $data;
    }

    protected function validatedRecipeItems(Request $request, ?int $productId): array
    {
        if (!$request->boolean('multi')) {
            return [];
        }

        $items = $request->input('recipe', []);
        if (!is_array($items)) {
            return [];
        }

        $clean = [];
        foreach ($items as $it) {
            $stokId = isset($it['stok_urun_id']) && is_numeric($it['stok_urun_id']) ? (int) $it['stok_urun_id'] : 0;
            $miktar = isset($it['miktar']) && is_numeric($it['miktar']) ? (float) $it['miktar'] : null;

            if ($stokId <= 0) {
                continue;
            }

            if ($productId && $stokId === (int) $productId) {
                throw ValidationException::withMessages([
                    'recipe' => ['Reçeteye ürünün kendisi eklenemez.'],
                ]);
            }

            if ($miktar === null) {
                $miktar = 0;
            }

            if ($miktar < 0) {
                $miktar = 0;
            }

            $clean[] = ['stok_urun_id' => $stokId, 'miktar' => $miktar];
        }

        $unique = [];
        $deduped = [];
        foreach ($clean as $row) {
            $key = (string) $row['stok_urun_id'];
            if (isset($unique[$key])) {
                continue;
            }
            $unique[$key] = true;
            $deduped[] = $row;
        }

        return $deduped;
    }

    protected function persistRecipeItems(Product $product, array $items): void
    {
        ProductRecipe::where('urun_id', $product->id)->delete();

        if (!$product->multi) {
            return;
        }

        $sirano = 0;
        foreach ($items as $row) {
            $sirano++;
            ProductRecipe::create([
                'urun_id' => $product->id,
                'stok_urun_id' => $row['stok_urun_id'],
                'miktar' => $row['miktar'],
                'sirano' => $sirano,
            ]);
        }
    }

    protected function storeProductImage(Request $request): string
    {
        $file = $request->file('resim');

        $dir = public_path('uploads/products');
        File::ensureDirectoryExists($dir);

        $ext = $file->getClientOriginalExtension();
        $name = 'product_' . date('Ymd_His') . '_' . bin2hex(random_bytes(6)) . ($ext ? ('.' . $ext) : '');

        $file->move($dir, $name);

        return 'uploads/products/' . $name;
    }
}
