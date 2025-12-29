<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - NomaEnerji</title>
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

        .offers-filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .offers-filter-row .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .offers-filter-row label {
            font-size: 0.75rem;
            font-weight: 500;
            color: #4b5563;
        }

        .offers-filter-row input[type="text"],
        .offers-filter-row input[type="date"],
        .offers-filter-row select {
            min-width: 160px;
            border-radius: 999px;
            border: 1px solid #e5e7eb;
            background-color: #ffffff;
            padding: 0.45rem 0.85rem;
            font-size: 0.8rem;
            color: #111827;
            outline: none;
        }

        .offers-filter-actions {
            display: flex;
            align-items: flex-end;
            gap: 0.5rem;
        }

        .offers-filter-button {
            padding: 0.45rem 1rem;
            border-radius: 999px;
            border: none;
            background-color: #2563eb;
            color: #ffffff;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
        }

        .offers-filter-reset {
            padding: 0.45rem 0.9rem;
            border-radius: 999px;
            border: 1px solid #e5e7eb;
            background-color: #ffffff;
            color: #4b5563;
            font-size: 0.8rem;
            cursor: pointer;
            text-decoration: none;
        }

        .offers-new-button {
            padding: 0.45rem 1rem;
            border-radius: 999px;
            border: none;
            background-color: #16a34a;
            color: #ffffff;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
        }

        .offers-table-wrapper {
            margin-top: 0.75rem;
            overflow-x: auto;
            overflow-y: hidden;
            border-radius: 12px;
            border: 1px solid rgba(203, 213, 225, 0.7);
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.10);
        }

        .offers-table {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
            border: 1px solid #e5e7eb;
        }

        .offers-table thead {
            background-color: #f3f4f6;
        }

        .offers-table thead th {
            padding: 0.75rem 0.9rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
        }

        .offers-table tbody td {
            padding: 0.7rem 0.9rem;
            font-size: 0.8rem;
            color: #111827;
            border-bottom: 1px solid #e5e7eb;
            white-space: nowrap;
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    @include('partials.sidebar', ['active' => $active])

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
                {{ $title }} Fişleri
            </div>
        </header>

        <section class="content-section" style="padding: 2rem;">
            <div class="offers-table-card">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="GET"
                      action="{{ $type === 'cikis' ? route('stock.consignment-out.index') : route('stock.consignment-in.index') }}">
                    <div class="offers-filter-row">
                        <div class="filter-group">
                            <label for="tarih_baslangic">Tarih (Başlangıç)</label>
                            <input type="date" id="tarih_baslangic" name="tarih_baslangic" value="{{ request('tarih_baslangic') }}">
                        </div>

                        <div class="filter-group">
                            <label for="tarih_bitis">Tarih (Bitiş)</label>
                            <input type="date" id="tarih_bitis" name="tarih_bitis" value="{{ request('tarih_bitis') }}">
                        </div>

                        <div class="filter-group">
                            <label for="carikod">Firma</label>
                            <input type="text" id="carikod" name="carikod" value="{{ request('carikod') }}" placeholder="Cari kod">
                        </div>

                        <div class="filter-group">
                            <label for="durum">Durum</label>
                            <select id="durum" name="durum">
                                <option value="">Hepsi</option>
                                <option value="açık" {{ request('durum') === 'açık' ? 'selected' : '' }}>Açık</option>
                                <option value="kapalı" {{ request('durum') === 'kapalı' ? 'selected' : '' }}>Kapalı</option>
                            </select>
                        </div>

                        <div class="offers-filter-actions">
                            <button type="submit" class="offers-filter-button">Filtrele</button>
                            <a href="{{ $type === 'cikis' ? route('stock.consignment-out.index') : route('stock.consignment-in.index') }}"
                               class="offers-filter-reset">Temizle</a>
                        </div>
                    </div>
                </form>

                <div style="display:flex; justify-content:flex-end; margin-top: 0.25rem;">
                    <a href="{{ $type === 'cikis' ? route('stock.consignment-out.create') : route('stock.consignment-in.create') }}"
                       class="offers-new-button"
                       style="display:inline-flex; align-items:center; justify-content:center; text-decoration:none;">
                        Yeni Kayıt
                    </a>
                </div>

                <div class="offers-table-wrapper">
                    <table class="offers-table">
                        <thead>
                        <tr>
                            <th>Tarih</th>
                            <th>Fiş No</th>
                            <th>Firma</th>
                            <th>Cari Açıklama</th>
                            <th>Durum</th>
                            <th style="text-align:right;">İşlem</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($fiches as $fiche)
                            @php($editHref = $type === 'cikis'
                                ? route('stock.consignment-out.edit', $fiche)
                                : route('stock.consignment-in.edit', $fiche))
                            <tr>
                                <td>{{ $fiche->tarih ? $fiche->tarih->format('d.m.Y') : '' }}</td>
                                <td>{{ $fiche->fis_no }}</td>
                                <td>{{ $fiche->carikod }}</td>
                                <td>{{ $fiche->cariaciklama }}</td>
                                <td>{{ $fiche->durum }}</td>
                                <td style="text-align:right;">
                                    <x-edit-icon :href="$editHref" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center; padding: 16px;">Kayıt bulunamadı.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 16px;">
                    {{ $fiches->links() }}
                </div>
            </div>
        </section>
    </main>
</div>

<script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>
