<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductDetailGroup;
use App\Models\ProductSubGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

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

        return view('products.create', compact('categories', 'subGroupsByGroup', 'detailGroupsBySubGroup'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('resim')) {
            $data['resim_yolu'] = $this->storeProductImage($request);
        }

        Product::create($data);

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

        return view('products.edit', compact('product', 'categories', 'subGroupsByGroup', 'detailGroupsBySubGroup'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validatedData($request, $product->id);

        if ($request->hasFile('resim')) {
            if ($product->resim_yolu && str_starts_with($product->resim_yolu, 'uploads/products/')) {
                $oldPath = public_path($product->resim_yolu);
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $data['resim_yolu'] = $this->storeProductImage($request);
        }

        $product->update($data);

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
