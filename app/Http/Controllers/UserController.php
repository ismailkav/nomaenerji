<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = $this->roles();

        $query = User::query();

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('ad', 'like', '%' . $search . '%')
                    ->orWhere('soyad', 'like', '%' . $search . '%')
                    ->orWhere('mail', 'like', '%' . $search . '%')
                    ->orWhere('telefon', 'like', '%' . $search . '%');
            });
        }

        if ($role = $request->get('role')) {
            $query->where('role', $role);
        }

        $users = $query->orderBy('ad')->orderBy('soyad')->paginate(15)->withQueryString();

        return view('users.index', [
            'users' => $users,
            'roles' => $roles,
            'filters' => [
                'q' => $search ?? '',
                'role' => $role ?? '',
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create', [
            'roles' => $this->roles(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $roles = array_keys($this->roles());

        $data = $request->validate([
            'ad' => ['required', 'string', 'max:255'],
            'soyad' => ['required', 'string', 'max:255'],
            'mail' => ['required', 'email', 'max:255', 'unique:users,mail'],
            'telefon' => ['nullable', 'string', 'max:255'],
            'role' => ['required', 'in:' . implode(',', $roles)],
            'aktif' => ['nullable', 'boolean'],
            'sifre' => ['required', 'string', 'min:6'],
        ]);

        $data['aktif'] = $request->boolean('aktif', true);

        User::create($data);

        return redirect()->route('users.index')->with('status', 'Kullanıcı başarıyla oluşturuldu.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', [
            'user' => $user,
            'roles' => $this->roles(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $roles = array_keys($this->roles());

        $data = $request->validate([
            'ad' => ['required', 'string', 'max:255'],
            'soyad' => ['required', 'string', 'max:255'],
            'mail' => ['required', 'email', 'max:255', 'unique:users,mail,' . $user->id],
            'telefon' => ['nullable', 'string', 'max:255'],
            'role' => ['required', 'in:' . implode(',', $roles)],
            'aktif' => ['nullable', 'boolean'],
            'sifre' => ['nullable', 'string', 'min:6'],
        ]);

        $data['aktif'] = $request->boolean('aktif', true);

        if (empty($data['sifre'])) {
            unset($data['sifre']);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('status', 'Kullanıcı başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('status', 'Kullanıcı başarıyla silindi.');
    }

    /**
     * Uygulamadaki rollerin listesi.
     *
     * @return array<string, string>
     */
    protected function roles(): array
    {
        return [
            'yonetici' => 'Yönetici',
            'kullanici' => 'Kullanıcı',
            'muhasebe' => 'Muhasebe',
            'satis' => 'Satış',
        ];
    }
}

