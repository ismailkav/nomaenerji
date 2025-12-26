<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firmalar - NomaEnerji</title>
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
    @include('partials.sidebar', ['active' => 'firms'])

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
                Firmalar
            </div>
            <div class="user-profile" style="display: flex; align-items: center; gap: 1rem;">
                <a href="{{ route('firms.create') }}"
                   class="btn btn-primary"
                   style="padding: 0.5rem 1rem; border-radius: 999px; border: none; background: #16a34a; color: #fff; font-weight: 500; text-decoration: none; cursor: pointer;">
                    Yeni Firma
                </a>
            </div>
        </header>

        <section class="content-section" style="padding: 2rem;">
            <div class="user-table-card">
                @if (session('status'))
                    <div class="alert alert-success" style="margin-bottom: 16px;">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="user-table-wrapper">
                    <table class="user-table-modern">
                        <thead>
                        <tr>
                            <th>Cari Kod</th>
                            <th>Cari Açıklama</th>
                            <th>Adres 1</th>
                            <th>Adres 2</th>
                            <th>İl</th>
                            <th>İlçe</th>
                            <th>Ülke</th>
                            <th>Telefon</th>
                            <th>Mail</th>
                            <th>Web Sitesi</th>
                            <th style="width: 160px;">İşlemler</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($firms as $firm)
                            <tr>
                                <td>{{ $firm->carikod }}</td>
                                <td>{{ $firm->cariaciklama }}</td>
                                <td>{{ $firm->adres1 }}</td>
                                <td>{{ $firm->adres2 }}</td>
                                <td>{{ $firm->il }}</td>
                                <td>{{ $firm->ilce }}</td>
                                <td>{{ $firm->ulke }}</td>
                                <td>{{ $firm->telefon }}</td>
                                <td>{{ $firm->mail }}</td>
                                <td>{{ $firm->web_sitesi }}</td>
                                <td>
                                    <div class="user-action-group">
                                        <a href="{{ route('firms.edit', $firm) }}" class="user-action-link edit">
                                            Düzenle
                                        </a>
                                        <form action="{{ route('firms.destroy', $firm) }}" method="POST" style="display:inline-block"
                                              onsubmit="return confirm('Bu firmayı silmek istediğinize emin misiniz?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="user-action-link delete" style="border:none;background:none;">
                                                Sil
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" style="text-align:center;padding:16px;">Kayıtlı firma bulunamadı.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div style="margin-top:16px;">
                    {{ $firms->links() }}
                </div>
            </div>
        </section>
    </main>
</div>
<script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>
