<?php

namespace App\Http\Controllers;

use App\Models\CariCategory;
use App\Models\ProductCategory;
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

            if ($kod === '') {
                continue;
            }

            if ($id) {
                $project = Project::find($id);
                if ($project) {
                    $project->update([
                        'kod' => $kod,
                        'pasif' => $pasif,
                    ]);
                    $keptIds[] = $project->id;
                }
            } else {
                $project = Project::create([
                    'kod' => $kod,
                    'pasif' => $pasif,
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
}
