<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

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

        return view('products.create', compact('categories'));
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

        return view('products.edit', compact('product', 'categories'));
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

        return $request->validate([
            'kod'         => ['required', 'string', 'max:50', $uniqueRule],
            'aciklama'    => ['required', 'string', 'max:255'],
            'satis_fiyat' => ['required', 'numeric', 'min:0'],
            'kdv_oran'    => ['required', 'integer', 'min:0', 'max:100'],
            'kategori_id' => ['nullable', 'integer', 'exists:urun_kategorileri,id'],
            'resim'       => ['nullable', 'image', 'max:2048'],
            'pasif'       => ['sometimes', 'boolean'],
        ]);
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
