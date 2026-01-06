<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        .offers-filter-actions .small-btn {
            border-radius: 999px;
            border: none;
            background: transparent;
            color: #2563eb;
            width: 34px;
            height: 34px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .offers-filter-actions .small-btn:hover {
            background: #f3f4f6;
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

        .offers-table thead th.sortable {
            cursor: pointer;
            user-select: none;
        }
        .offers-table thead th.sortable:hover {
            background-color: #e5e7eb;
        }
        .offers-table thead th.sort-asc::after {
            content: " ▲";
            font-size: 0.75em;
            color: #6b7280;
        }
        .offers-table thead th.sort-desc::after {
            content: " ▼";
            font-size: 0.75em;
            color: #6b7280;
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

        .offer-line-setting-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.45rem 0.25rem;
            border-bottom: 1px solid #f1f5f9;
            user-select: none;
        }
        .offer-line-setting-item:last-child {
            border-bottom: none;
        }
        .offer-line-setting-item.dragging {
            opacity: 0.55;
        }
        .offer-line-setting-no {
            width: 28px;
            font-size: 0.8rem;
            color: #6b7280;
            text-align: right;
        }
        .offer-line-setting-handle {
            width: 18px;
            color: #9ca3af;
            cursor: grab;
            text-align: center;
        }
        .offer-line-setting-label {
            flex: 1;
            font-size: 0.85rem;
        }

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 50;
        }
        .modal {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(15, 23, 42, 0.25);
            max-width: 720px;
            width: 100%;
            max-height: 80vh;
            display: flex;
            flex-direction: column;
        }
        .modal-header {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-title {
            font-size: 0.9rem;
            font-weight: 600;
        }
        .modal-body {
            padding: 0.75rem 1rem;
            overflow: auto;
        }
        .btn {
            padding: 0.45rem 1.2rem;
            border-radius: 999px;
            border: none;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-primary {
            background: #2563eb;
            color: #fff;
        }
        .modal-close {
            border: none;
            background: transparent;
            cursor: pointer;
            color: #6b7280;
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
                            <button type="button" id="btnOrderListSettings" class="small-btn" title="Tablo DÇ¬zenle" aria-label="Tablo DÇ¬zenle">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z" stroke="currentColor" stroke-width="2"/>
                                    <path d="M19.4 15a1.9 1.9 0 0 0 .38 2.1l.06.06a2.3 2.3 0 0 1 0 3.25 2.3 2.3 0 0 1-3.25 0l-.06-.06a1.9 1.9 0 0 0-2.1-.38 1.9 1.9 0 0 0-1.15 1.74V22a2.3 2.3 0 0 1-4.6 0v-.09A1.9 1.9 0 0 0 7.5 20.7a1.9 1.9 0 0 0-2.1.38l-.06.06a2.3 2.3 0 0 1-3.25 0 2.3 2.3 0 0 1 0-3.25l.06-.06A1.9 1.9 0 0 0 2.5 15a1.9 1.9 0 0 0-1.74-1.15H.67a2.3 2.3 0 0 1 0-4.6h.09A1.9 1.9 0 0 0 2.5 7.5a1.9 1.9 0 0 0-.38-2.1l-.06-.06a2.3 2.3 0 0 1 0-3.25 2.3 2.3 0 0 1 3.25 0l.06.06A1.9 1.9 0 0 0 7.5 2.5a1.9 1.9 0 0 0 1.15-1.74V.67a2.3 2.3 0 0 1 4.6 0v.09A1.9 1.9 0 0 0 16.5 2.5a1.9 1.9 0 0 0 2.1-.38l.06-.06a2.3 2.3 0 0 1 3.25 0 2.3 2.3 0 0 1 0 3.25l-.06.06A1.9 1.9 0 0 0 21.5 7.5c0 .76.46 1.45 1.15 1.74H22a2.3 2.3 0 0 1 0 4.6h-.09A1.9 1.9 0 0 0 19.4 15Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </form>

                <div class="offers-table-wrapper">
                    <table class="offers-table" id="ordersListTable">
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

<div id="orderListSettingsModal" class="modal-overlay">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="orderListSettingsTitle">
        <div class="modal-header">
            <div style="display:flex; align-items:center; gap: 0.75rem;">
                <div class="modal-title" id="orderListSettingsTitle">Tablo DÇ¬zenle</div>
                <label style="display:flex; align-items:center; gap: 0.35rem; font-size: 0.8rem; color:#374151;">
                    <input type="checkbox" id="orderListToggleAll">
                    <span>Hepsini SeÇ÷/KaldÇ¬r</span>
                </label>
            </div>
            <div style="display:flex; align-items:center; gap: 0.5rem;">
                <button type="button" id="orderListSaveBtn" class="btn btn-primary">Kaydet</button>
                <button type="button" id="orderListCloseBtn" class="modal-close" aria-label="Kapat">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>
        </div>
        <div class="modal-body">
            <div id="orderListSettingsList"></div>
        </div>
    </div>
</div>

<script src="{{ asset('js/dashboard.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var btn = document.getElementById('btnOrderListSettings');
        var modal = document.getElementById('orderListSettingsModal');
        var closeBtn = document.getElementById('orderListCloseBtn');
        var listEl = document.getElementById('orderListSettingsList');
        var toggleAllEl = document.getElementById('orderListToggleAll');
        var saveBtn = document.getElementById('orderListSaveBtn');
        var table = document.getElementById('ordersListTable');

        if (!table) return;

        var isInvoiceList = @json((($resource ?? 'orders') === 'invoices'));
        var indexUrl = @json(route((($resource ?? 'orders') === 'invoices') ? 'invoice-list-columns.index' : 'order-list-columns.index'));
        var storeUrl = @json(route((($resource ?? 'orders') === 'invoices') ? 'invoice-list-columns.store' : 'order-list-columns.store'));

        var columns = [];
        var draggingEl = null;
        var currentSortKey = null;
        var currentSortDir = 'asc';

        function getCsrfToken() {
            var meta = document.querySelector('meta[name="csrf-token"]');
            if (meta) return meta.getAttribute('content') || '';
            return '';
        }

        function openModal() {
            if (!modal) return;
            modal.style.display = 'flex';
        }

        function closeModal() {
            if (!modal) return;
            modal.style.display = 'none';
        }

        function normalizeColumns(cols) {
            if (!Array.isArray(cols)) return [];
            return cols
                .filter(function (c) { return c && (c.key || c.sutun); })
                .map(function (c) {
                    return {
                        key: String(c.key || c.sutun),
                        label: String(c.label || c.key || c.sutun),
                        durum: (typeof c.durum === 'boolean') ? c.durum : String(c.durum) === '1' || String(c.durum).toLowerCase() === 'true',
                        sirano: (c.sirano !== undefined && c.sirano !== null) ? parseInt(c.sirano, 10) : 0
                    };
                })
                .sort(function (a, b) {
                    var aa = (Number.isFinite(a.sirano) ? a.sirano : 0);
                    var bb = (Number.isFinite(b.sirano) ? b.sirano : 0);
                    return aa - bb;
                });
        }

        function parseDateTr(text) {
            var s = (text || '').trim();
            var m = s.match(/^(\d{1,2})\.(\d{1,2})\.(\d{4})$/);
            if (!m) return null;
            var d = parseInt(m[1], 10);
            var mo = parseInt(m[2], 10) - 1;
            var y = parseInt(m[3], 10);
            var dt = new Date(y, mo, d);
            if (Number.isNaN(dt.getTime())) return null;
            return dt.getTime();
        }

        function parseNumberTr(text) {
            var s = (text || '').trim();
            if (!s) return null;
            s = s.replace(/\s/g, '');
            s = s.replace(/[^0-9,.-]/g, '');
            if (!s) return null;
            if (s.indexOf(',') >= 0 && s.lastIndexOf(',') > s.lastIndexOf('.')) {
                s = s.replace(/\./g, '').replace(',', '.');
            } else {
                s = s.replace(/,/g, '');
            }
            var n = parseFloat(s);
            return Number.isFinite(n) ? n : null;
        }

        function compareValues(a, b) {
            if (a === null && b === null) return 0;
            if (a === null) return -1;
            if (b === null) return 1;
            if (typeof a === 'number' && typeof b === 'number') return a - b;
            return String(a).localeCompare(String(b), 'tr', { sensitivity: 'base' });
        }

        function getCellValue(tr, key) {
            var td = tr.querySelector('td[data-col-key=\"' + key + '\"]');
            var text = td ? (td.textContent || '').trim() : '';

            if (key === 'tarih' || key === 'onay_tarihi') {
                return parseDateTr(text);
            }
            if (key === 'toplam_tutar') {
                return parseNumberTr(text);
            }
            return text;
        }

        function setSortableHeaders() {
            var ths = table.querySelectorAll('thead th[data-col-key]');
            Array.prototype.forEach.call(ths, function (th) {
                var key = th.getAttribute('data-col-key');
                if (!key || key === 'islem') return;
                th.classList.add('sortable');
            });
        }

        function updateSortIndicator() {
            var ths = table.querySelectorAll('thead th.sortable');
            Array.prototype.forEach.call(ths, function (th) {
                th.classList.remove('sort-asc', 'sort-desc');
                var key = th.getAttribute('data-col-key');
                if (key && key === currentSortKey) {
                    th.classList.add(currentSortDir === 'desc' ? 'sort-desc' : 'sort-asc');
                }
            });
        }

        function sortTableByKey(key, dir) {
            var tbody = table.querySelector('tbody');
            if (!tbody) return;

            var rows = Array.prototype.slice.call(tbody.querySelectorAll('tr'));
            var sortableRows = rows.filter(function (tr) {
                return !!tr.querySelector('td[data-col-key]');
            });
            if (!sortableRows.length) return;

            var mapped = sortableRows.map(function (tr, idx) {
                return { tr: tr, idx: idx, val: getCellValue(tr, key) };
            });

            mapped.sort(function (a, b) {
                var cmp = compareValues(a.val, b.val);
                if (cmp === 0) return a.idx - b.idx;
                return (dir === 'desc' ? -cmp : cmp);
            });

            mapped.forEach(function (m) { tbody.appendChild(m.tr); });
        }

        function ensureDataColKeys() {
            var keys = isInvoiceList
                ? ['siparis_no', 'belge_no', 'tarih', 'carikod', 'cariaciklama', 'onay_durum', 'onay_tarihi', 'hazirlayan', 'toplam_tutar', 'islem']
                : ['siparis_no', 'tarih', 'islem_turu', 'proje', 'carikod', 'cariaciklama', 'onay_durum', 'onay_tarihi', 'hazirlayan', 'toplam_tutar', 'islem'];

            var ths = table.querySelectorAll('thead th');
            Array.prototype.forEach.call(ths, function (th, idx) {
                if (!th.getAttribute('data-col-key') && keys[idx]) th.setAttribute('data-col-key', keys[idx]);
            });

            var trs = table.querySelectorAll('tbody tr');
            Array.prototype.forEach.call(trs, function (tr) {
                var tds = tr.querySelectorAll('td');
                Array.prototype.forEach.call(tds, function (td, idx) {
                    if (!td.getAttribute('data-col-key') && keys[idx]) td.setAttribute('data-col-key', keys[idx]);
                });
            });
        }

        function fetchColumns() {
            return fetch(indexUrl, { headers: { 'Accept': 'application/json' } })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    columns = normalizeColumns((data && data.columns) ? data.columns : []);
                    return columns;
                });
        }

        function applyColumns() {
            ensureDataColKeys();
            if (!Array.isArray(columns) || !columns.length) return;

            var theadRow = table.querySelector('thead tr');
            if (!theadRow) return;

            var currentOrder = columns.map(function (c) { return c.key; });

            currentOrder.forEach(function (key) {
                var th = theadRow.querySelector('th[data-col-key=\"' + key + '\"]');
                if (th) theadRow.appendChild(th);
            });

            Array.prototype.forEach.call(table.querySelectorAll('tbody tr'), function (tr) {
                currentOrder.forEach(function (key) {
                    var td = tr.querySelector('td[data-col-key=\"' + key + '\"]');
                    if (td) tr.appendChild(td);
                });
            });

            var visibleMap = {};
            columns.forEach(function (c) { visibleMap[c.key] = !!c.durum; });

            Array.prototype.forEach.call(theadRow.querySelectorAll('th[data-col-key]'), function (th) {
                var key = th.getAttribute('data-col-key');
                var visible = (key in visibleMap) ? visibleMap[key] : true;
                th.style.display = visible ? '' : 'none';
            });

            Array.prototype.forEach.call(table.querySelectorAll('tbody tr'), function (tr) {
                Array.prototype.forEach.call(tr.querySelectorAll('td[data-col-key]'), function (td) {
                    var key = td.getAttribute('data-col-key');
                    var visible = (key in visibleMap) ? visibleMap[key] : true;
                    td.style.display = visible ? '' : 'none';
                });
            });

            setSortableHeaders();
            updateSortIndicator();
        }

        function getItems() {
            return listEl ? Array.prototype.slice.call(listEl.querySelectorAll('.offer-line-setting-item')) : [];
        }

        function getCheckboxes() {
            return listEl ? Array.prototype.slice.call(listEl.querySelectorAll('input[type=\"checkbox\"][data-key]')) : [];
        }

        function syncToggleAll() {
            if (!toggleAllEl) return;
            var cbs = getCheckboxes();
            if (!cbs.length) {
                toggleAllEl.checked = false;
                toggleAllEl.indeterminate = false;
                return;
            }
            var checkedCount = cbs.filter(function (cb) { return cb.checked; }).length;
            toggleAllEl.checked = checkedCount === cbs.length;
            toggleAllEl.indeterminate = checkedCount > 0 && checkedCount < cbs.length;
        }

        function renumberItems() {
            getItems().forEach(function (item, idx) {
                var no = item.querySelector('.offer-line-setting-no');
                if (no) no.textContent = String(idx + 1);
            });
        }

        function buildList() {
            if (!listEl) return;
            listEl.innerHTML = '';
            columns.forEach(function (col) {
                var item = document.createElement('div');
                item.className = 'offer-line-setting-item';
                item.setAttribute('draggable', 'true');
                item.setAttribute('data-key', col.key);

                var no = document.createElement('div');
                no.className = 'offer-line-setting-no';
                no.textContent = '';

                var handle = document.createElement('div');
                handle.className = 'offer-line-setting-handle';
                handle.textContent = '⋮⋮';

                var cb = document.createElement('input');
                cb.type = 'checkbox';
                cb.checked = !!col.durum;
                cb.setAttribute('data-key', col.key);
                cb.addEventListener('change', syncToggleAll);

                var label = document.createElement('div');
                label.className = 'offer-line-setting-label';
                label.textContent = col.label || col.key;

                item.appendChild(no);
                item.appendChild(handle);
                item.appendChild(cb);
                item.appendChild(label);
                listEl.appendChild(item);
            });

            renumberItems();
            syncToggleAll();
        }

        function getDragAfterElement(container, y) {
            var els = Array.prototype.slice.call(container.querySelectorAll('.offer-line-setting-item:not(.dragging)'));
            var closest = null;
            var closestOffset = Number.NEGATIVE_INFINITY;
            els.forEach(function (child) {
                var box = child.getBoundingClientRect();
                var offset = y - box.top - box.height / 2;
                if (offset < 0 && offset > closestOffset) {
                    closestOffset = offset;
                    closest = child;
                }
            });
            return closest;
        }

        function attachDnD() {
            if (!listEl) return;
            listEl.addEventListener('dragstart', function (e) {
                var item = e.target && e.target.closest ? e.target.closest('.offer-line-setting-item') : null;
                if (!item) return;
                draggingEl = item;
                item.classList.add('dragging');
                try {
                    e.dataTransfer.effectAllowed = 'move';
                    e.dataTransfer.setData('text/plain', item.getAttribute('data-key') || '');
                } catch (err) {}
            });
            listEl.addEventListener('dragend', function () {
                if (draggingEl) draggingEl.classList.remove('dragging');
                draggingEl = null;
                renumberItems();
            });
            listEl.addEventListener('dragover', function (e) {
                if (!draggingEl) return;
                e.preventDefault();
                var afterEl = getDragAfterElement(listEl, e.clientY);
                if (!afterEl) listEl.appendChild(draggingEl);
                else if (afterEl !== draggingEl) listEl.insertBefore(draggingEl, afterEl);
                renumberItems();
            });
        }

        attachDnD();

        if (toggleAllEl) {
            toggleAllEl.addEventListener('change', function () {
                var checked = !!toggleAllEl.checked;
                getCheckboxes().forEach(function (cb) { cb.checked = checked; });
                syncToggleAll();
            });
        }

        if (saveBtn) {
            saveBtn.addEventListener('click', function () {
                var items = getItems();
                if (!items.length) return;

                var payload = items.map(function (item) {
                    var key = item.getAttribute('data-key') || '';
                    var cb = item.querySelector('input[type=\"checkbox\"]');
                    return { key: key, durum: !!(cb && cb.checked) };
                });

                saveBtn.disabled = true;
                saveBtn.style.opacity = '0.7';

                fetch(storeUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ columns: payload })
                })
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        if (!data || !data.ok) throw new Error('save_failed');
                        columns = normalizeColumns(data.columns || []);
                        applyColumns();
                        closeModal();
                    })
                    .catch(function () {
                        alert('Tablo ayarlarŽñ kaydedilemedi.');
                    })
                    .finally(function () {
                        saveBtn.disabled = false;
                        saveBtn.style.opacity = '1';
                    });
            });
        }

        if (btn) {
            btn.addEventListener('click', function () {
                fetchColumns()
                    .then(function () {
                        applyColumns();
                        buildList();
                        openModal();
                    })
                    .catch(function () {
                        alert('Tablo ayarlarŽñ yÇ¬klenemedi.');
                    });
            });
        }

        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (modal) {
            modal.addEventListener('click', function (e) {
                if (e.target === modal) closeModal();
            });
        }

        var thead = table.querySelector('thead');
        if (thead) {
            thead.addEventListener('click', function (e) {
                var th = e.target && e.target.closest ? e.target.closest('th[data-col-key]') : null;
                if (!th) return;
                if (th.style.display === 'none') return;
                var key = th.getAttribute('data-col-key');
                if (!key || key === 'islem') return;

                if (currentSortKey === key) currentSortDir = (currentSortDir === 'asc') ? 'desc' : 'asc';
                else {
                    currentSortKey = key;
                    currentSortDir = 'asc';
                }

                updateSortIndicator();
                sortTableByKey(key, currentSortDir);
            });
        }
        setSortableHeaders();

        fetchColumns().then(function () { applyColumns(); }).catch(function () { });
    });
</script>

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
