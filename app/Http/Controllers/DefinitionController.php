<?php

namespace App\Http\Controllers;

use App\Models\CariCategory;
use App\Models\Depot;
use App\Models\Product;
use App\Models\ProductDetailGroup;
use App\Models\ProductCategory;
use App\Models\ProductSubGroup;
use App\Models\MontajGroup;
use App\Models\MontajProduct;
use App\Models\MontajProductGroup;
use App\Models\IslemTuru;
use App\Models\Project;
use App\Models\Parameter;
use Illuminate\Http\Request;

class DefinitionController extends Controller
{
    public function parameters()
    {
        $keys = [
            'tomcat_ip',
            'tomcat_port',
            'tomcat_proje',
            'resim_yol',
        ];

        $params = Parameter::query()
            ->whereIn('anahtar', $keys)
            ->get(['anahtar', 'deger'])
            ->keyBy('anahtar');

        return view('definitions.parameters', [
            'tomcatIp' => (string) ($params['tomcat_ip']->deger ?? ''),
            'tomcatPort' => (string) ($params['tomcat_port']->deger ?? ''),
            'tomcatProje' => (string) ($params['tomcat_proje']->deger ?? ''),
            'resimYol' => (string) ($params['resim_yol']->deger ?? ''),
        ]);
    }

    public function saveParameters(Request $request)
    {
        $validated = $request->validate([
            'tomcat_ip' => ['nullable', 'string', 'max:100'],
            'tomcat_port' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'tomcat_proje' => ['nullable', 'string', 'max:100'],
            'resim_yol' => ['nullable', 'string', 'max:255'],
        ]);

        foreach (['tomcat_ip', 'tomcat_port', 'tomcat_proje', 'resim_yol'] as $key) {
            $value = $validated[$key] ?? null;
            $value = is_null($value) ? null : (string) $value;

            Parameter::updateOrCreate(
                ['anahtar' => $key],
                ['deger' => $value]
            );
        }

        return redirect()->route('definitions.parameters')
            ->with('status', 'Parametreler kaydedildi.');
    }

    public function cariGroups(Request $request)
    {
        $categories = CariCategory::orderBy('ad')->get();

        return view('definitions.cari-groups', compact('categories'));
    }

    public function saveCariGroups(Request $request)
    {
        $items = $request->input('groups', []);
        $keptIds = [];

        foreach ($items as $item) {
            $name = trim($item['name'] ?? '');
            $id = $item['id'] ?? null;

            if ($name === '') {
                continue;
            }

            if ($id) {
                $category = CariCategory::find($id);
                if ($category) {
                    $category->update(['ad' => $name]);
                    $keptIds[] = $category->id;
                }
            } else {
                $category = CariCategory::create(['ad' => $name]);
                $keptIds[] = $category->id;
            }
        }

        if (!empty($keptIds)) {
            CariCategory::whereNotIn('id', $keptIds)->delete();
        } else {
            CariCategory::query()->delete();
        }

        return redirect()->route('definitions.cari-groups')
            ->with('status', 'Cari gruplar güncellendi.');
    }

    public function productGroups(Request $request)
    {
        $categories = ProductCategory::orderBy('ad')->get();

        return view('definitions.product-groups', compact('categories'));
    }

    public function saveProductGroups(Request $request)
    {
        $items = $request->input('groups', []);
        $keptIds = [];

        foreach ($items as $item) {
            $name = trim($item['name'] ?? '');
            $id = $item['id'] ?? null;

            if ($name === '') {
                continue;
            }

            if ($id) {
                $category = ProductCategory::find($id);
                if ($category) {
                    $category->update(['ad' => $name]);
                    $keptIds[] = $category->id;
                }
            } else {
                $category = ProductCategory::create(['ad' => $name]);
                $keptIds[] = $category->id;
            }
        }

        if (!empty($keptIds)) {
            ProductCategory::whereNotIn('id', $keptIds)->delete();
        } else {
            ProductCategory::query()->delete();
        }

        return redirect()->route('definitions.product-groups')
            ->with('status', 'Ürün grupları güncellendi.');
    }

    public function productSubGroups(Request $request)
    {
        $groups = ProductCategory::orderBy('ad')->get();
        $selectedGroupId = (int) $request->query('group_id', 0);

        if ($selectedGroupId <= 0 && $groups->isNotEmpty()) {
            $selectedGroupId = (int) $groups->first()->id;
        }

        $subGroups = $selectedGroupId > 0
            ? ProductSubGroup::where('urun_grup_id', $selectedGroupId)->orderBy('ad')->get()
            : collect();

        return view('definitions.product-sub-groups', compact('groups', 'selectedGroupId', 'subGroups'));
    }

    public function saveProductSubGroups(Request $request)
    {
        $groupId = (int) $request->input('group_id', 0);
        if ($groupId <= 0 || !ProductCategory::whereKey($groupId)->exists()) {
            return redirect()->route('definitions.product-sub-groups')
                ->withErrors(['group_id' => 'Ürün grup seçiniz.']);
        }

        $items = $request->input('items', []);
        $keptIds = [];

        foreach ($items as $item) {
            $name = trim($item['name'] ?? '');
            $id = $item['id'] ?? null;

            if ($name === '') {
                continue;
            }

            if ($id) {
                $subGroup = ProductSubGroup::whereKey($id)->where('urun_grup_id', $groupId)->first();
                if ($subGroup) {
                    $subGroup->update(['ad' => $name]);
                    $keptIds[] = $subGroup->id;
                }
            } else {
                $subGroup = ProductSubGroup::create([
                    'urun_grup_id' => $groupId,
                    'ad' => $name,
                ]);
                $keptIds[] = $subGroup->id;
            }
        }

        if (!empty($keptIds)) {
            ProductSubGroup::where('urun_grup_id', $groupId)->whereNotIn('id', $keptIds)->delete();
        } else {
            ProductSubGroup::where('urun_grup_id', $groupId)->delete();
        }

        return redirect()->route('definitions.product-sub-groups', ['group_id' => $groupId])
            ->with('status', 'Ürün alt grupları güncellendi.');
    }

    public function productDetailGroups(Request $request)
    {
        $groups = ProductCategory::orderBy('ad')->get();
        $selectedGroupId = (int) $request->query('group_id', 0);

        if ($selectedGroupId <= 0 && $groups->isNotEmpty()) {
            $selectedGroupId = (int) $groups->first()->id;
        }

        $subGroups = $selectedGroupId > 0
            ? ProductSubGroup::where('urun_grup_id', $selectedGroupId)->orderBy('ad')->get()
            : collect();

        $selectedSubGroupId = (int) $request->query('sub_group_id', 0);
        if ($selectedSubGroupId <= 0 && $subGroups->isNotEmpty()) {
            $selectedSubGroupId = (int) $subGroups->first()->id;
        }

        $detailGroups = ($selectedGroupId > 0 && $selectedSubGroupId > 0)
            ? ProductDetailGroup::where('urun_grup_id', $selectedGroupId)
                ->where('urun_alt_grup_id', $selectedSubGroupId)
                ->orderBy('ad')
                ->get()
            : collect();

        $subGroupsByGroup = ProductSubGroup::orderBy('ad')->get()->groupBy('urun_grup_id')->map(function ($items) {
            return $items->map(fn ($i) => ['id' => $i->id, 'ad' => $i->ad])->values();
        });

        return view('definitions.product-detail-groups', compact(
            'groups',
            'selectedGroupId',
            'subGroups',
            'selectedSubGroupId',
            'detailGroups',
            'subGroupsByGroup'
        ));
    }

    public function saveProductDetailGroups(Request $request)
    {
        $groupId = (int) $request->input('group_id', 0);
        $subGroupId = (int) $request->input('sub_group_id', 0);

        if ($groupId <= 0 || !ProductCategory::whereKey($groupId)->exists()) {
            return redirect()->route('definitions.product-detail-groups')
                ->withErrors(['group_id' => 'Ürün grup seçiniz.']);
        }

        if ($subGroupId <= 0 || !ProductSubGroup::whereKey($subGroupId)->where('urun_grup_id', $groupId)->exists()) {
            return redirect()->route('definitions.product-detail-groups', ['group_id' => $groupId])
                ->withErrors(['sub_group_id' => 'Ürün alt grup seçiniz.']);
        }

        $items = $request->input('items', []);
        $keptIds = [];

        foreach ($items as $item) {
            $name = trim($item['name'] ?? '');
            $id = $item['id'] ?? null;
            $montajGrubu = !empty($item['montaj_grubu']);

            if ($name === '') {
                continue;
            }

            if ($id) {
                $detailGroup = ProductDetailGroup::whereKey($id)
                    ->where('urun_grup_id', $groupId)
                    ->where('urun_alt_grup_id', $subGroupId)
                    ->first();

                if ($detailGroup) {
                    $detailGroup->update([
                        'ad' => $name,
                        'montaj_grubu' => $montajGrubu,
                    ]);
                    $keptIds[] = $detailGroup->id;
                }
            } else {
                $detailGroup = ProductDetailGroup::create([
                    'urun_grup_id' => $groupId,
                    'urun_alt_grup_id' => $subGroupId,
                    'ad' => $name,
                    'montaj_grubu' => $montajGrubu,
                ]);
                $keptIds[] = $detailGroup->id;
            }
        }

        if (!empty($keptIds)) {
            ProductDetailGroup::where('urun_grup_id', $groupId)
                ->where('urun_alt_grup_id', $subGroupId)
                ->whereNotIn('id', $keptIds)
                ->delete();
        } else {
            ProductDetailGroup::where('urun_grup_id', $groupId)
                ->where('urun_alt_grup_id', $subGroupId)
                ->delete();
        }

        return redirect()->route('definitions.product-detail-groups', ['group_id' => $groupId, 'sub_group_id' => $subGroupId])
            ->with('status', 'Ürün detay grupları güncellendi.');
    }

    public function montajGroups(Request $request)
    {
        $groups = ProductCategory::orderBy('ad')->get();
        $subGroupsByGroup = ProductSubGroup::orderBy('ad')->get()->groupBy('urun_grup_id')->map(function ($items) {
            return $items->map(fn ($i) => ['id' => $i->id, 'ad' => $i->ad])->values();
        });

        $montajGroups = MontajGroup::orderBy('sirano')->orderBy('kod')->get();

        return view('definitions.montaj-groups', compact('montajGroups', 'groups', 'subGroupsByGroup'));
    }

    public function montajDetailGroups(Request $request)
    {
        $groupId = (int) $request->query('group_id', 0);
        $subGroupId = (int) $request->query('sub_group_id', 0);

        if ($groupId <= 0 || $subGroupId <= 0) {
            return response()->json(['items' => []]);
        }

        $items = ProductDetailGroup::query()
            ->where('urun_grup_id', $groupId)
            ->where('urun_alt_grup_id', $subGroupId)
            ->orderBy('ad')
            ->get(['id', 'ad', 'montaj_grubu']);

        return response()->json(['items' => $items]);
    }

    public function saveMontajGroups(Request $request)
    {
        $items = $request->input('items', []);
        $keptIds = [];

        foreach ($items as $item) {
            $id = $item['id'] ?? null;
            $kod = trim((string) ($item['kod'] ?? ''));
            $urunDetayGrupId = isset($item['urun_detay_grup_id']) && is_numeric($item['urun_detay_grup_id'])
                ? (int) $item['urun_detay_grup_id']
                : null;
            $sirano = isset($item['sirano']) && is_numeric($item['sirano']) ? (int) $item['sirano'] : 0;

            if ($kod === '') {
                continue;
            }

            if ($id) {
                $row = MontajGroup::find($id);
                if ($row) {
                    $row->update([
                        'kod' => $kod,
                        'urun_detay_grup_id' => $urunDetayGrupId,
                        'sirano' => $sirano,
                    ]);
                    $keptIds[] = $row->id;
                }
            } else {
                $row = MontajGroup::create([
                    'kod' => $kod,
                    'urun_detay_grup_id' => $urunDetayGrupId,
                    'sirano' => $sirano,
                ]);
                $keptIds[] = $row->id;
            }
        }

        if (!empty($keptIds)) {
            MontajGroup::whereNotIn('id', $keptIds)->delete();
        } else {
            MontajGroup::query()->delete();
        }

        return redirect()->route('definitions.montaj-groups')
            ->with('status', 'Montaj gruplar güncellendi.');
    }

    public function montajProducts()
    {
        $montajGroups = MontajGroup::orderBy('sirano')->orderBy('kod')->get();
        $selectedMontajGroupId = (int) request()->query('montaj_grup_id', 0);
        if ($selectedMontajGroupId <= 0 && $montajGroups->isNotEmpty()) {
            $selectedMontajGroupId = (int) $montajGroups->first()->id;
        }

        $items = $selectedMontajGroupId > 0
            ? MontajProduct::where('montaj_grup_id', $selectedMontajGroupId)->orderBy('sirano')->orderBy('id')->get()
            : collect();

        return view('definitions.montaj-products', compact('montajGroups', 'selectedMontajGroupId', 'items'));
    }

    public function saveMontajProducts(Request $request)
    {
        $montajGroupId = (int) $request->input('montaj_grup_id', 0);
        if ($montajGroupId <= 0 || !MontajGroup::whereKey($montajGroupId)->exists()) {
            return redirect()->route('definitions.montaj-products')
                ->withErrors(['montaj_grup_id' => 'Montaj grup seçiniz.']);
        }

        $items = $request->input('items', []);
        $keptIds = [];

        foreach ($items as $item) {
            $id = $item['id'] ?? null;
            $urunKod = trim((string) ($item['urun_kod'] ?? ''));
            $birim = trim((string) ($item['birim'] ?? 'Adet'));
            $birimFiyat = isset($item['birim_fiyat']) ? (float) $item['birim_fiyat'] : 0.0;
            $doviz = strtoupper(trim((string) ($item['doviz'] ?? 'TL')));
            $sirano = isset($item['sirano']) && is_numeric($item['sirano']) ? (int) $item['sirano'] : 0;

            if ($urunKod === '') {
                continue;
            }

            if (!in_array($birim, ['Adet', 'Metre', 'Kilo'], true)) {
                $birim = 'Adet';
            }
            if (!in_array($doviz, ['TL', 'USD', 'EUR'], true)) {
                $doviz = 'TL';
            }

            $payload = [
                'montaj_grup_id' => $montajGroupId,
                'urun_kod' => $urunKod,
                'birim' => $birim,
                'birim_fiyat' => $birimFiyat,
                'doviz' => $doviz,
                'sirano' => $sirano,
            ];

            if ($id) {
                $row = MontajProduct::whereKey($id)->where('montaj_grup_id', $montajGroupId)->first();
                if ($row) {
                    $row->update($payload);
                    $keptIds[] = $row->id;
                }
            } else {
                $row = MontajProduct::create($payload);
                $keptIds[] = $row->id;
            }
        }

        if (!empty($keptIds)) {
            MontajProduct::where('montaj_grup_id', $montajGroupId)->whereNotIn('id', $keptIds)->delete();
        } else {
            MontajProduct::where('montaj_grup_id', $montajGroupId)->delete();
        }

        return redirect()->route('definitions.montaj-products', ['montaj_grup_id' => $montajGroupId])
            ->with('status', 'Montaj ürünler güncellendi.');
    }

    public function montajProductGroups()
    {
        $montajGroups = MontajGroup::orderBy('sirano')->orderBy('kod')->get();
        $selectedGroupId = (int) request()->query('montaj_grup_id', 0);
        if ($selectedGroupId <= 0 && $montajGroups->isNotEmpty()) {
            $selectedGroupId = (int) $montajGroups->first()->id;
        }

        $productsByGroup = MontajProduct::orderBy('sirano')->orderBy('id')->get()
            ->groupBy('montaj_grup_id')
            ->map(function ($items) {
                return $items->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'kod' => $p->urun_kod ?: ('#' . $p->id),
                    ];
                })->values();
            });

        $productsForSelected = $productsByGroup->get($selectedGroupId, collect());
        $selectedProductId = (int) request()->query('montaj_urun_id', 0);
        if ($selectedProductId <= 0 && $productsForSelected && $productsForSelected->isNotEmpty()) {
            $selectedProductId = (int) $productsForSelected->first()['id'];
        }

        $items = collect();
        if ($selectedGroupId > 0 && $selectedProductId > 0) {
            $items = MontajProductGroup::with('urun')
                ->where('montaj_grup_id', $selectedGroupId)
                ->where('montaj_urun_id', $selectedProductId)
                ->orderBy('sirano')
                ->orderBy('id')
                ->get();
        }

        $stockCards = Product::query()
            ->orderBy('kod')
            ->get(['id', 'kod', 'aciklama', 'kategori_id', 'urun_alt_grup_id', 'urun_detay_grup_id']);

        $stockGroups = ProductCategory::query()
            ->orderBy('ad')
            ->get(['id', 'ad']);

        $stockSubGroups = ProductSubGroup::query()
            ->orderBy('ad')
            ->get(['id', 'urun_grup_id', 'ad']);

        $stockDetailGroups = ProductDetailGroup::query()
            ->orderBy('ad')
            ->get(['id', 'urun_grup_id', 'urun_alt_grup_id', 'ad']);

        return view('definitions.montaj-product-groups', [
            'montajGroups' => $montajGroups,
            'productsByGroup' => $productsByGroup,
            'selectedGroupId' => $selectedGroupId,
            'selectedProductId' => $selectedProductId,
            'items' => $items,
            'stockCards' => $stockCards,
            'stockGroups' => $stockGroups,
            'stockSubGroups' => $stockSubGroups,
            'stockDetailGroups' => $stockDetailGroups,
        ]);
    }

    public function saveMontajProductGroups(Request $request)
    {
        $groupId = (int) $request->input('montaj_grup_id', 0);
        $productId = (int) $request->input('montaj_urun_id', 0);

        if ($groupId <= 0 || !MontajGroup::whereKey($groupId)->exists()) {
            return redirect()->route('definitions.montaj-product-groups')
                ->withErrors(['montaj_grup_id' => 'Montaj grup seciniz.']);
        }

        if ($productId <= 0 || !MontajProduct::whereKey($productId)->where('montaj_grup_id', $groupId)->exists()) {
            return redirect()->route('definitions.montaj-product-groups', ['montaj_grup_id' => $groupId])
                ->withErrors(['montaj_urun_id' => 'Montaj urun seciniz.']);
        }

        $items = $request->input('items', []);
        $keptIds = [];

        foreach ($items as $item) {
            $id = $item['id'] ?? null;
            $urunId = isset($item['urun_id']) && is_numeric($item['urun_id'])
                ? (int) $item['urun_id']
                : 0;
            $sirano = isset($item['sirano']) && is_numeric($item['sirano']) ? (int) $item['sirano'] : 0;

            if ($urunId <= 0 || !Product::whereKey($urunId)->exists()) {
                continue;
            }

            $payload = [
                'montaj_grup_id' => $groupId,
                'montaj_urun_id' => $productId,
                'urun_id' => $urunId,
                'sirano' => $sirano,
            ];

            if ($id) {
                $row = MontajProductGroup::whereKey($id)
                    ->where('montaj_grup_id', $groupId)
                    ->where('montaj_urun_id', $productId)
                    ->first();
                if ($row) {
                    $row->update($payload);
                    $keptIds[] = $row->id;
                }
            } else {
                $row = MontajProductGroup::updateOrCreate(
                    [
                        'montaj_grup_id' => $groupId,
                        'montaj_urun_id' => $productId,
                        'urun_id' => $urunId,
                    ],
                    ['sirano' => $sirano]
                );
                $keptIds[] = $row->id;
            }
        }

        if (!empty($keptIds)) {
            MontajProductGroup::where('montaj_grup_id', $groupId)
                ->where('montaj_urun_id', $productId)
                ->whereNotIn('id', $keptIds)
                ->delete();
        } else {
            MontajProductGroup::where('montaj_grup_id', $groupId)
                ->where('montaj_urun_id', $productId)
                ->delete();
        }

        return redirect()->route('definitions.montaj-product-groups', [
            'montaj_grup_id' => $groupId,
            'montaj_urun_id' => $productId,
        ])->with('status', 'Montaj urun grup satirlari guncellendi.');
    }

    public function islemTurleri(Request $request)
    {
        $categories = IslemTuru::orderBy('ad')->get();

        return view('definitions.islem-turleri', compact('categories'));
    }

    public function saveIslemTurleri(Request $request)
    {
        $items = $request->input('groups', []);
        $keptIds = [];

        foreach ($items as $item) {
            $name = trim($item['name'] ?? '');
            $id = $item['id'] ?? null;

            if ($name === '') {
                continue;
            }

            if ($id) {
                $tur = IslemTuru::find($id);
                if ($tur) {
                    $tur->update(['ad' => $name]);
                    $keptIds[] = $tur->id;
                }
            } else {
                $tur = IslemTuru::create(['ad' => $name]);
                $keptIds[] = $tur->id;
            }
        }

        if (!empty($keptIds)) {
            IslemTuru::whereNotIn('id', $keptIds)->delete();
        } else {
            IslemTuru::query()->delete();
        }

        return redirect()->route('definitions.islem-turleri')
            ->with('status', 'İşlem türleri güncellendi.');
    }
    public function projects(Request $request)
    {
        $projects = Project::orderBy('kod')->get();

        return view('definitions.projects', compact('projects'));
    }

    public function saveProjects(Request $request)
    {
        $items = $request->input('projects', []);
        $keptIds = [];

        foreach ($items as $item) {
            $kod = trim($item['kod'] ?? '');
            $id = $item['id'] ?? null;
            $pasif = !empty($item['pasif']);
            $iskonto1 = isset($item['iskonto1']) ? (float) $item['iskonto1'] : 0.0;
            $iskonto2 = isset($item['iskonto2']) ? (float) $item['iskonto2'] : 0.0;

            if ($kod === '') {
                continue;
            }

            if ($id) {
                $project = Project::find($id);
                if ($project) {
                    $project->update([
                        'kod' => $kod,
                        'pasif' => $pasif,
                        'iskonto1' => $iskonto1,
                        'iskonto2' => $iskonto2,
                    ]);
                    $keptIds[] = $project->id;
                }
            } else {
                $project = Project::create([
                    'kod' => $kod,
                    'pasif' => $pasif,
                    'iskonto1' => $iskonto1,
                    'iskonto2' => $iskonto2,
                ]);
                $keptIds[] = $project->id;
            }
        }

        if (!empty($keptIds)) {
            Project::whereNotIn('id', $keptIds)->delete();
        } else {
            Project::query()->delete();
        }

        return redirect()->route('definitions.projects')
            ->with('status', 'Projeler gÇ¬ncellendi.');
    }

    public function depots(Request $request)
    {
        $depots = Depot::orderBy('kod')->get();

        return view('definitions.depots', compact('depots'));
    }

    public function saveDepots(Request $request)
    {
        $items = $request->input('depots', []);
        $keptIds = [];

        foreach ($items as $item) {
            $kod = trim($item['kod'] ?? '');
            $id = $item['id'] ?? null;
            $pasif = !empty($item['pasif']);

            if ($kod === '') {
                continue;
            }

            if ($id) {
                $depot = Depot::find($id);
                if ($depot) {
                    $depot->update([
                        'kod' => $kod,
                        'pasif' => $pasif,
                    ]);
                    $keptIds[] = $depot->id;
                }
            } else {
                $depot = Depot::create([
                    'kod' => $kod,
                    'pasif' => $pasif,
                ]);
                $keptIds[] = $depot->id;
            }
        }

        if (!empty($keptIds)) {
            Depot::whereNotIn('id', $keptIds)->delete();
        } else {
            Depot::query()->delete();
        }

        return redirect()->route('definitions.depots')
            ->with('status', 'Depolar güncellendi.');
    }
}
