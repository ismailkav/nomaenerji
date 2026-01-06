<?php

namespace App\Http\Controllers;

use App\Models\CariCategory;
use App\Models\Depot;
use App\Models\ProductDetailGroup;
use App\Models\ProductCategory;
use App\Models\ProductSubGroup;
use App\Models\IslemTuru;
use App\Models\Project;
use Illuminate\Http\Request;

class DefinitionController extends Controller
{
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

            if ($name === '') {
                continue;
            }

            if ($id) {
                $detailGroup = ProductDetailGroup::whereKey($id)
                    ->where('urun_grup_id', $groupId)
                    ->where('urun_alt_grup_id', $subGroupId)
                    ->first();

                if ($detailGroup) {
                    $detailGroup->update(['ad' => $name]);
                    $keptIds[] = $detailGroup->id;
                }
            } else {
                $detailGroup = ProductDetailGroup::create([
                    'urun_grup_id' => $groupId,
                    'urun_alt_grup_id' => $subGroupId,
                    'ad' => $name,
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
