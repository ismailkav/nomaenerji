<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Fiyat Listeleri - NomaEnerji</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        .offers-table-card {
            border-radius: 16px;
            border: 1px solid rgba(148, 163, 184, 0.25);
            padding: 1rem 1.25rem 1.5rem;
            background: radial-gradient(circle at top left, rgba(59,130,246,0.06), transparent 55%),
            radial-gradient(circle at bottom right, rgba(16,185,129,0.05), transparent 55%),
            var(--card-bg);
            backdrop-filter: blur(10px);
        }

        .offers-filter-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 0.5rem;
            margin-bottom: 0.75rem;
        }

        .offers-new-button {
            padding: 0.45rem 1rem;
            border-radius: 999px;
            border: none;
            background-color: #16a34a;
            color: #ffffff;
            font-size: 0.8rem;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
        }

        .offers-table-wrapper {
            overflow-x: auto;
        }

        table.offers-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }

        table.offers-table th,
        table.offers-table td {
            padding: 0.65rem 0.5rem;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            white-space: nowrap;
        }

        table.offers-table th {
            color: #6b7280;
            font-weight: 600;
            background: #f8fafc;
            position: sticky;
            top: 0;
            z-index: 1;
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    @include('partials.sidebar', ['active' => 'price-lists'])

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
                        <path d="M15 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                              stroke-linejoin="round"/>
                    </svg>
                </button>
                Fiyat Listeleri
            </div>
        </header>

        <section class="content-section" style="padding: 2rem;">
            <div class="offers-table-card">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="offers-filter-footer">
                    <div></div>
                    <a href="{{ route('price-lists.create') }}" class="offers-new-button">Yeni Liste</a>
                </div>

                <div class="offers-table-wrapper">
                    <table class="offers-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Tedarikçi Firma</th>
                            <th>Başlangıç Tarihi</th>
                            <th>Bitiş Tarihi</th>
                            <th>Hazırlayan</th>
                            <th>Oluşturma</th>
                            <th style="text-align:right;">İşlem</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($listeler as $liste)
                            <tr>
                                <td>{{ $liste->id }}</td>
                                <td>
                                    @if($liste->firm)
                                        {{ $liste->firm->carikod }} - {{ $liste->firm->cariaciklama }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $liste->baslangic_tarihi ? $liste->baslangic_tarihi->format('d.m.Y') : '' }}</td>
                                <td>{{ $liste->bitis_tarihi ? $liste->bitis_tarihi->format('d.m.Y') : '' }}</td>
                                <td>{{ $liste->hazirlayan }}</td>
                                <td>{{ $liste->created_at ? $liste->created_at->format('d.m.Y H:i') : '' }}</td>
                                <td style="text-align:right;">
                                    <x-edit-icon :href="route('price-lists.edit', $liste)" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align:center; padding: 16px;">Kayıtlı fiyat listesi bulunamadı.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 16px;">
                    {{ $listeler->links() }}
                </div>
            </div>
        </section>
    </main>
</div>

<script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>
