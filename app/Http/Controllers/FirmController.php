<?php

namespace App\Http\Controllers;

use App\Models\Firm;
use App\Models\FirmAuthority;
use App\Models\CariCategory;
use Illuminate\Http\Request;

class FirmController extends Controller
{
    public function index()
    {
        $firms = Firm::orderBy('carikod')->paginate(15);

        return view('firms.index', compact('firms'));
    }

    public function create()
    {
        $categories = CariCategory::orderBy('ad')->get();

        return view('firms.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        $firm = Firm::create($data);

        $this->syncAuthorities($firm, $request->input('authorities', []));

        return redirect()->route('firms.index')
            ->with('status', 'Firma başarıyla oluşturuldu.');
    }

    public function edit(Firm $firm)
    {
        $firm->load('authorities');
        $categories = CariCategory::orderBy('ad')->get();

        return view('firms.edit', compact('firm', 'categories'));
    }

    public function update(Request $request, Firm $firm)
    {
        $data = $this->validatedData($request);
        $firm->update($data);

        $this->syncAuthorities($firm, $request->input('authorities', []));

        return redirect()->route('firms.index')
            ->with('status', 'Firma bilgileri güncellendi.');
    }

    public function destroy(Firm $firm)
    {
        $firm->delete();

        return redirect()->route('firms.index')
            ->with('status', 'Firma silindi.');
    }

    protected function validatedData(Request $request): array
    {
        return $request->validate([
            'carikod'          => ['required', 'string', 'max:50'],
            'cari_kategori_id' => ['nullable', 'integer', 'exists:cari_kategorileri,id'],
            'cariaciklama'     => ['required', 'string', 'max:255'],
            'adres1'           => ['nullable', 'string', 'max:255'],
            'adres2'           => ['nullable', 'string', 'max:255'],
            'il'               => ['nullable', 'string', 'max:100'],
            'ilce'             => ['nullable', 'string', 'max:100'],
            'ulke'             => ['nullable', 'string', 'max:100'],
            'telefon'          => ['nullable', 'string', 'max:50'],
            'mail'             => ['nullable', 'email', 'max:150'],
            'web_sitesi'       => ['nullable', 'string', 'max:150'],
            'iskonto1'         => ['nullable', 'numeric'],
            'iskonto2'         => ['nullable', 'numeric'],
            'iskonto3'         => ['nullable', 'numeric'],
            'iskonto4'         => ['nullable', 'numeric'],
            'iskonto5'         => ['nullable', 'numeric'],
            'iskonto6'         => ['nullable', 'numeric'],
        ]);
    }

    protected function syncAuthorities(Firm $firm, array $authorities): void
    {
        // Mevcut yetkilileri sil
        FirmAuthority::where('firm_id', $firm->id)->delete();

        // Gelen verileri filtrele ve kaydet
        foreach ($authorities as $authority) {
            $fullName = trim($authority['full_name'] ?? '');
            $email = trim($authority['email'] ?? '');
            $phone = trim($authority['phone'] ?? '');
            $role = trim($authority['role'] ?? '');

            // Tamamen boş satırları atla
            if ($fullName === '' && $email === '' && $phone === '' && $role === '') {
                continue;
            }

            FirmAuthority::create([
                'firm_id'   => $firm->id,
                'full_name' => $fullName,
                'email'     => $email ?: null,
                'phone'     => $phone ?: null,
                'role'      => $role ?: null,
            ]);
        }
    }
}
