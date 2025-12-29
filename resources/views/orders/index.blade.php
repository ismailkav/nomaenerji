<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ ($resource ?? 'orders') === 'invoices' ? 'Faturalar' : 'Siparişler' }} - NomaEnerji</title>
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

        .offers-header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        .offers-status-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.18rem 0.6rem;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 500;
            background-color: #e5e7eb;
            color: #374151;
        }

        @media (max-width: 1024px) {
            .offers-filter-row {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    @include('partials.sidebar', ['active' => $active ?? 'orders'])

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
                {{ $pageHeading ?? (($tur ?? 'alim') === 'satis' ? 'Satış Siparişleri' : 'Alım Siparişleri') }}
            </div>
        </header>

        <section class="content-section" style="padding: 2rem;">
            <div class="offers-table-card">
                <div class="offers-header-row">
                    <div>
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                    </div>
                    <a href="{{ route(($resource ?? 'orders') . '.create', ['tur' => $tur ?? 'alim']) }}" class="offers-new-button">
                        {{ $newButtonText ?? 'Yeni Sipariş' }}
                    </a>
                </div>

                <form method="GET" action="{{ route(($resource ?? 'orders') . '.index') }}">
                    <input type="hidden" name="tur" value="{{ $tur ?? 'alim' }}">
                    <div class="offers-filter-row">
                        <div class="filter-group">
                            <label for="q">Arama ({{ ($resource ?? 'orders') === 'invoices' ? 'Fatura No' : 'Sipariş No' }} / Cari)</label>
                            <input type="text" id="q" name="q" value="{{ request('q') }}"
                                   placeholder="{{ ($resource ?? 'orders') === 'invoices' ? 'Fatura no, cari kod veya açıklama' : 'Sipariş no, cari kod veya açıklama' }}">
                        </div>

                        <div class="filter-group">
                            <label for="tarih_baslangic">{{ ($resource ?? 'orders') === 'invoices' ? 'Fatura Tarihi' : 'Sipariş Tarihi' }} (Başlangıç)</label>
                            <input type="date" id="tarih_baslangic" name="tarih_baslangic"
                                   value="{{ request('tarih_baslangic') }}">
                        </div>

                        <div class="filter-group">
                            <label for="tarih_bitis">{{ ($resource ?? 'orders') === 'invoices' ? 'Fatura Tarihi' : 'Sipariş Tarihi' }} (Bitiş)</label>
                            <input type="date" id="tarih_bitis" name="tarih_bitis"
                                   value="{{ request('tarih_bitis') }}">
                        </div>

                        @if(($resource ?? 'orders') !== 'invoices')
                            <div class="filter-group">
                                <label for="islem_turu_id">İşlem Türü</label>
                                <select id="islem_turu_id" name="islem_turu_id">
                                    <option value="">Hepsi</option>
                                    @foreach($islemTurleri as $islemTuru)
                                        <option value="{{ $islemTuru->id }}" {{ (string) request('islem_turu_id') === (string) $islemTuru->id ? 'selected' : '' }}>
                                            {{ $islemTuru->ad }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="filter-group">
                                <label for="proje_id">Proje</label>
                                <select id="proje_id" name="proje_id">
                                    <option value="">Hepsi</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ (string) request('proje_id') === (string) $project->id ? 'selected' : '' }}>
                                            {{ $project->kod }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="offers-filter-actions">
                            <button type="submit" class="offers-filter-button">Filtrele</button>
                            <a href="{{ route(($resource ?? 'orders') . '.index', ['tur' => $tur ?? 'alim']) }}" class="offers-filter-reset">Temizle</a>
                        </div>
                    </div>
                </form>

                <div class="offers-table-wrapper">
                    <table class="offers-table">
                        @php($isSales = ($tur ?? 'alim') === 'satis')
                        <thead>
                        <tr>
                            <th>{{ ($resource ?? 'orders') === 'invoices' ? 'Fatura No' : 'Sipariş No' }}</th>
                            @if(($resource ?? 'orders') === 'invoices')
                                <th>Belge No</th>
                            @endif
                            <th>{{ ($resource ?? 'orders') === 'invoices' ? 'Fatura Tarih' : 'Sipariş Tarih' }}</th>
                            @if(($resource ?? 'orders') !== 'invoices')
                                <th>İşlem Türü</th>
                                <th>Proje</th>
                            @endif
                            <th>Cari Kod</th>
                            <th>Cari Açıklama</th>
                            <th>Onay Durum</th>
                            <th>Onay Tarih</th>
                            <th>Hazırlayan</th>
                            <th>Toplam Tutar</th>
                            <th style="text-align:right;">İşlem</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($siparisler as $siparis)
                            <tr>
                                <td>{{ $siparis->siparis_no }}</td>
                                @if(($resource ?? 'orders') === 'invoices')
                                    <td>{{ $siparis->belge_no ?? '' }}</td>
                                @endif
                                <td>{{ $siparis->tarih ? $siparis->tarih->format('d.m.Y') : '' }}</td>
                                @if(($resource ?? 'orders') !== 'invoices')
                                    <td>{{ $siparis->islemTuru->ad ?? '' }}</td>
                                    <td>{{ $siparis->proje->kod ?? '' }}</td>
                                @endif
                                <td>{{ $siparis->carikod }}</td>
                                <td>{{ $siparis->cariaciklama }}</td>
                                <td>{{ $siparis->onay_durum }}</td>
                                <td>{{ $siparis->onay_tarihi ? \Carbon\Carbon::parse($siparis->onay_tarihi)->format('d.m.Y') : '' }}</td>
                                @if(false && $isSales)
                                    <td>
                                        <form id="planningForm-{{ $siparis->id }}" method="POST" action="{{ route('orders.planning.update', $siparis) }}" style="display:none;">
                                            @csrf
                                            @method('PATCH')
                                        </form>
                                        <select name="planlama_durum"
                                                form="planningForm-{{ $siparis->id }}"
                                                data-planning-form="planningForm-{{ $siparis->id }}"
                                                style="width: 100%; max-width: 160px;">
                                            <option value="">-</option>
                                            <option value="bekliyor" {{ ($siparis->planlama_durum ?? '') === 'bekliyor' ? 'selected' : '' }}>Bekliyor</option>
                                            <option value="kismi_tamam" {{ ($siparis->planlama_durum ?? '') === 'kismi_tamam' ? 'selected' : '' }}>Kısmi Tamam</option>
                                            <option value="tamam" {{ ($siparis->planlama_durum ?? '') === 'tamam' ? 'selected' : '' }}>Tamam</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number"
                                               name="planlanan_miktar"
                                               form="planningForm-{{ $siparis->id }}"
                                               data-planning-form="planningForm-{{ $siparis->id }}"
                                               min="0"
                                               step="0.01"
                                               value="{{ $siparis->planlanan_miktar ?? '' }}"
                                               style="width: 100%; max-width: 140px;">
                                    </td>
                                @endif
                                <td>{{ $siparis->hazirlayan }}</td>
                                <td>
                                    {{ number_format(((float) ($siparis->genel_toplam ?? 0)) / (((float) ($siparis->siparis_kur ?? 1)) > 0 ? (float) $siparis->siparis_kur : 1), 2, ',', '.') }}
                                    {{ $siparis->siparis_doviz ?? 'TL' }}
                                </td>
                                <td style="text-align:right;">
                                    <x-edit-icon :href="route(($resource ?? 'orders') . '.edit', $siparis)" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ ($resource ?? 'orders') === 'invoices' ? 9 : 11 }}" style="text-align: center; padding: 16px;">Kayıtlı sipariş bulunamadı.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 16px;">
                    {{ $siparisler->links() }}
                </div>
            </div>
        </section>
    </main>
</div>

@if(false)
<div id="planningDetailModal" style="display:none; position:fixed; inset:0; z-index:9999;">
    <div id="planningDetailBackdrop" style="position:absolute; inset:0; background:rgba(15,23,42,0.55);"></div>
    <div style="position:relative; max-width: 520px; margin: 10vh auto; background:#fff; border-radius:14px; padding: 1.25rem 1.25rem 1rem; box-shadow: 0 18px 45px rgba(15, 23, 42, 0.25);">
        <div style="font-size: 1rem; font-weight: 600; color:#111827; margin-bottom: 0.75rem;">Planlama Detayı</div>
        <div style="display:flex; flex-direction:column; gap: 0.5rem; font-size: 0.9rem; color:#374151;">
            <div><span style="font-weight:600;">Planlama Durum:</span> <span id="planningDetailStatus">-</span></div>
            <div><span style="font-weight:600;">Planlanan Miktar:</span> <span id="planningDetailQty">-</span></div>
        </div>
        <div style="margin-top: 1rem; display:flex; justify-content:flex-end;">
            <button type="button" id="planningDetailClose" class="offers-filter-reset" style="text-decoration:none;">Kapat</button>
        </div>
    </div>
</div>
<script src="{{ asset('js/dashboard.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function labelStatus(v) {
            if (!v) return '-';
            if (v === 'bekliyor') return 'Bekliyor';
            if (v === 'kismi_tamam') return 'Kısmi Tamam';
            if (v === 'tamam') return 'Tamam';
            return v;
        }

        var modal = document.getElementById('planningDetailModal');
        var backdrop = document.getElementById('planningDetailBackdrop');
        var closeBtn = document.getElementById('planningDetailClose');
        var statusEl = document.getElementById('planningDetailStatus');
        var qtyEl = document.getElementById('planningDetailQty');

        function openModal(status, qty) {
            if (!modal) return;
            if (statusEl) statusEl.textContent = labelStatus(status);
            if (qtyEl) qtyEl.textContent = (qty !== null && qty !== undefined && String(qty).trim() !== '') ? qty : '-';
            modal.style.display = 'block';
        }

        function closeModal() {
            if (!modal) return;
            modal.style.display = 'none';
        }

        document.querySelectorAll('.order-planning-detail-button').forEach(function (btn) {
            btn.addEventListener('click', function () {
                openModal(btn.getAttribute('data-planlama-durum'), btn.getAttribute('data-planlanan-miktar'));
            });
        });

        if (backdrop) backdrop.addEventListener('click', closeModal);
        if (closeBtn) closeBtn.addEventListener('click', closeModal);
    });
</script>
@endif
</body>
</html>
