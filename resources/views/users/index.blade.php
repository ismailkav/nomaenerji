@php
    $roleLabels = $roles;
@endphp

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcılar - NomaEnerji</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        .user-table-card {
            border-radius: 16px;
            border: 1px solid rgba(148, 163, 184, 0.25);
            padding: 1rem;
            background: radial-gradient(circle at top left, rgba(59,130,246,0.06), transparent 55%),
                        radial-gradient(circle at bottom right, rgba(16,185,129,0.05), transparent 55%),
                        var(--card-bg);
            backdrop-filter: blur(10px);
        }

        .user-table-wrapper {
            margin-top: 0.5rem;
            overflow: hidden;
            border-radius: 12px;
            border: 1px solid rgba(203, 213, 225, 0.7);
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.10);
        }

        .user-table-modern {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
            border: 1px solid #e5e7eb;
        }

        .user-table-modern thead {
            background-color: #f3f4f6;
        }

        .user-table-modern thead th {
            padding: 0.75rem 1rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
        }

        .user-table-modern tbody tr {
            background-color: #ffffff;
            transition: background 0.12s ease;
        }

        .user-table-modern tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .user-table-modern tbody tr:hover {
            background-color: #f3f4f6;
        }

        .user-table-modern tbody td {
            padding: 0.75rem 1rem;
            font-size: 0.85rem;
            color: #111827;
            border-bottom: 1px solid #e5e7eb;
        }

        .user-name-cell {
            display: flex;
            flex-direction: column;
            gap: 0.1rem;
        }

        .user-name-primary {
            font-weight: 600;
            color: #f9fafb;
        }

        .user-name-secondary {
            font-size: 0.75rem;
            color: #9ca3af;
        }

        .user-role-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.2rem 0.55rem;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 500;
            background: rgba(59,130,246,0.12);
            color: #bfdbfe;
            border: 1px solid rgba(59,130,246,0.4);
        }

        .user-action-group {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .user-action-link {
            padding: 0.28rem 0.7rem;
            border-radius: 999px;
            border: 1px solid transparent;
            font-size: 0.78rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .user-action-link.edit {
            background: rgba(59,130,246,0.12);
            color: #bfdbfe;
            border-color: rgba(59,130,246,0.35);
        }

        .user-action-link.edit:hover {
            background: rgba(59,130,246,0.22);
            border-color: rgba(59,130,246,0.7);
        }

        .user-action-link.delete {
            background: rgba(239,68,68,0.10);
            color: #fecaca;
            border-color: rgba(239,68,68,0.35);
        }

        .user-action-link.delete:hover {
            background: rgba(239,68,68,0.18);
            border-color: rgba(239,68,68,0.7);
        }

        .user-table-modern tbody tr:last-child td {
            border-bottom: none;
        }

        @media (max-width: 768px) {
            .user-table-modern thead {
                display: none;
            }

            .user-table-modern,
            .user-table-modern tbody,
            .user-table-modern tr,
            .user-table-modern td {
                display: block;
                width: 100%;
            }

            .user-table-modern tr {
                margin-bottom: 0.75rem;
                border-radius: 12px;
                overflow: hidden;
            }

            .user-table-modern td {
                border-bottom: 1px solid rgba(31, 41, 55, 0.9);
            }

            .user-table-modern td:last-child {
                border-bottom: none;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        @include('partials.sidebar', ['active' => 'users'])
    
        <main class="main-content">
            <header class="top-bar">
                <button class="mobile-menu-toggle" id="mobileMenuToggle">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 12h18M3 6h18M3 18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
                <div class="page-title">
                    <button class="desktop-sidebar-toggle" id="desktopSidebarToggle">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    Kullanıcılar
                </div>
                <div class="user-profile" style="display: flex; align-items: center; gap: 1rem;">
                    <a href="{{ route('users.create') }}"
                       class="btn btn-primary"
                       style="padding: 0.5rem 1rem; border-radius: 999px; border: none; background: #16a34a; color: #fff; font-weight: 500; text-decoration: none; cursor: pointer;">
                        Yeni Kullanıcı
                    </a>
                    <div class="user-avatar">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" title="Çıkış Yap" style="background: none; border: none; color: #94a3b8; cursor: pointer; padding: 0.5rem; display: flex; align-items: center; justify-content: center; transition: color 0.2s;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </header>
    
            <section class="content-section" style="padding: 2rem;">
                <div style="background-color: var(--card-bg); border-radius: 12px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
                    @if (session('status'))
                        <div class="alert alert-success" style="margin-bottom: 16px;">
                            {{ session('status') }}
                        </div>
                    @endif
    
                    {{-- FİLTRELER (kart içinde, tablonun üstünde) --}}
                    <form method="GET" action="{{ route('users.index') }}" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;margin-bottom:16px;">
                        <input
                            type="text"
                            name="q"
                            value="{{ $filters['q'] ?? '' }}"
                            placeholder="Ara (ad, soyad, mail, telefon)"
                            class="input"
                            style="padding:8px 12px;border-radius:6px;border:1px solid #d1d5db;min-width:220px;"
                        >
                        <select
                            name="role"
                            class="select"
                            style="padding:8px 12px;border-radius:6px;border:1px solid #d1d5db;min-width:160px;"
                        >
                            <option value="">Tüm Roller</option>
                            @foreach($roleLabels as $value => $label)
                                <option value="{{ $value }}" @selected(($filters['role'] ?? '') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary" style="padding:8px 14px;border-radius:6px;border:none;background:#2563eb;color:#fff;font-weight:500;cursor:pointer;">
                            Filtrele
                        </button>
                    </form>
    
                    {{-- TABLO (kart içinde) --}}
                    <div class="user-table-wrapper">
                        <table class="user-table-modern">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Ad</th>
                                <th>Soyad</th>
                                <th>Mail</th>
                                <th>Telefon</th>
                                <th>Rol</th>
                                <th>Durum</th>
                                <th style="width:140px;">İşlemler</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->ad }}</td>
                                    <td>{{ $user->soyad }}</td>
                                    <td>{{ $user->mail }}</td>
                                    <td>{{ $user->telefon }}</td>
                                    <td>{{ $roleLabels[$user->role] ?? $user->role }}</td>
                                    <td>
                                        @if($user->aktif)
                                            <span style="display:inline-block;padding:2px 8px;border-radius:999px;background:#dcfce7;color:#166534;font-size:12px;">Aktif</span>
                                        @else
                                            <span style="display:inline-block;padding:2px 8px;border-radius:999px;background:#fee2e2;color:#991b1b;font-size:12px;">Pasif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-link" style="margin-right:8px;color:#2563eb;text-decoration:none;font-weight:500;">
                                            Düzenle
                                        </a>
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link" style="color:#b91c1c;text-decoration:none;font-weight:500;border:none;background:none;cursor:pointer;">
                                                Sil
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" style="text-align:center;padding:16px;">Kayıtlı kullanıcı bulunamadı.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
    
                    <div style="margin-top:16px;">
                        {{ $users->links() }}
                    </div>
                </div>
            </section>
        </main>
    </div>
    </body>
    </html>
    
