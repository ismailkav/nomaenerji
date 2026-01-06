<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipariş Planlama - NomaEnerji</title>
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
        .offers-filter-row input[type="date"] {
            min-width: 180px;
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
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(15,23,42,0.55);
            padding: 1.5rem;
        }

        .modal {
            width: 100%;
            max-width: 820px;
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.25);
            overflow: hidden;
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.9rem 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .modal-title {
            font-weight: 600;
            color: #111827;
        }

        .modal-body {
            padding: 0.75rem 1rem 1rem;
            max-height: 70vh;
            overflow: auto;
        }

        .small-btn {
            border: 1px solid #e5e7eb;
            background: #fff;
            border-radius: 999px;
            padding: 0.25rem 0.55rem;
            cursor: pointer;
            color: #111827;
        }

        .modal-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }

        .modal-table th,
        .modal-table td {
            padding: 0.55rem 0.6rem;
            border-bottom: 1px solid #f1f5f9;
            text-align: left;
        }

        .modal-table thead {
            background: #f3f4f6;
        }

        .modal-table tr:hover {
            background: #f8fafc;
            cursor: pointer;
        }

        .offers-filter-create {
            margin-left: auto;
            display: flex;
            align-items: flex-end;
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
            table-layout: fixed;
        }

        .offers-table thead {
            background-color: #f3f4f6;
        }

        .offers-table thead th {
            padding: 0.75rem 0.9rem;
            text-align: left;
            font-size: 0.6875rem;
            font-weight: 600;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
            white-space: nowrap;
        }

        .offers-table tbody td {
            padding: 0.7rem 0.9rem;
            font-size: 0.7375rem;
            color: #111827;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        #planningTable thead th:nth-child(2),
        #planningTable tbody td:nth-child(2),
        #planningTable thead th:nth-child(3),
        #planningTable tbody td:nth-child(3),
        #planningTable thead th:nth-child(4),
        #planningTable tbody td:nth-child(4) {
            padding-left: 0.45rem;
            padding-right: 0.45rem;
        }

        .offers-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .col-check {
            width: 44px;
            min-width: 44px;
            text-align: center !important;
        }

        .num {
            text-align: right;
            white-space: nowrap;
        }

        .offers-table thead th.num,
        .offers-table tbody td.num {
            width: 48px;
            min-width: 48px;
            padding-left: 0.3rem;
            padding-right: 0.3rem;
        }

        .offers-table thead th.num {
            white-space: normal;
            line-height: 1.15;
        }

        #planningTable thead th:nth-child(10),
        #planningTable tbody td:nth-child(10),
        #planningTable thead th:nth-child(11),
        #planningTable tbody td:nth-child(11),
        #planningTable thead th:nth-child(14),
        #planningTable tbody td:nth-child(14) {
            width: 72px;
            min-width: 72px;
        }

        .num input[type="number"] {
            width: 100%;
            max-width: 48px;
            box-sizing: border-box;
            border-radius: 999px;
            border: 1px solid #e5e7eb;
            padding: 0.25rem 0.35rem;
            font-size: 0.8rem;
            text-align: right;
            outline: none;
        }

        .offers-table tbody td.siparis-verilen-cell,
        .offers-table tbody td.siparis-revize-cell {
            color: #2563eb;
        }

        .offers-table tbody td.siparis-kalan-cell {
            color: #dc2626;
        }

        .truncate {
            max-width: 260px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: block;
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    @include('partials.sidebar', ['active' => $active ?? 'order-planning'])

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
                Sipariş Planlama
            </div>
        </header>

        <section class="content-section" style="padding: 2rem;">
            <div class="offers-table-card">
                <form method="GET" action="{{ route('orders.planning') }}">
                    <div class="offers-filter-row">
                        <div class="filter-group">
                            <label for="tarih_baslangic">Başlama Tarih</label>
                            <input type="date" id="tarih_baslangic" name="tarih_baslangic" value="{{ $filters['tarih_baslangic'] ?? '' }}">
                        </div>
                        <div class="filter-group">
                            <label for="tarih_bitis">Bitiş Tarih</label>
                            <input type="date" id="tarih_bitis" name="tarih_bitis" value="{{ $filters['tarih_bitis'] ?? '' }}">
                        </div>
                        <div class="filter-group" style="flex:0 0 260px; min-width: 200px;">
                            <label for="q">Arama</label>
                            <input type="text" id="q" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Sipariş no, cari kod, stok...">
                        </div>

                        <div class="offers-filter-actions">
                            <button type="submit" class="offers-filter-button">Filtrele</button>
                            <a href="{{ route('orders.planning') }}" class="offers-filter-reset">Temizle</a>
                        </div>

                        <div class="offers-filter-create">
                            <button type="button" id="btnCreatePurchase" class="offers-new-button">Sipariş Oluştur</button>
                        </div>
                    </div>
                </form>

                <div class="offers-table-wrapper">
                    <table class="offers-table" id="planningTable">
                        <thead>
                        <tr>
                            <th class="col-check">
                                <input type="checkbox" id="selectAllRows">
                            </th>
                            <th>Sipariş No</th>
                            <th>Sipariş Tarih</th>
                            <th>Cari Kod</th>
                            <th>Proje Kod</th>
                            <th>Stok Kod</th>
                            <th>Stok Açıklama</th>
                            <th class="num">Miktar</th>
                            <th class="num">Sipariş Miktar</th>
                            <th class="num">Siparis Revize</th>
                            <th class="num">Revize Miktar</th>
                            <th class="num">Stok Miktar</th>
                            <th class="num">Revize Edilen</th>
                            <th class="num">Gerçek Stok</th>
                            <th class="num">Sipariş Verilen</th>
                            <th class="num">Sipariş Kalan</th>
                        </tr>
                        </thead>
                        <tbody id="planningTbody">
                        @forelse(($rows ?? []) as $row)
                            <tr data-search="{{ strtolower(trim(($row->siparis_no ?? '') . ' ' . ($row->carikod ?? '') . ' ' . ($row->proje_kod ?? '') . ' ' . ($row->stok_kod ?? '') . ' ' . ($row->stok_aciklama ?? ''))) }}"
                                data-satis-detay-id="{{ $row->siparis_detay_id }}"
                                data-urun-id="{{ $row->urun_id }}"
                                data-stok-kod="{{ $row->stok_kod }}"
                                data-stok-aciklama="{{ $row->stok_aciklama }}"
                                data-stok-miktar="{{ (int) round((float) ($row->stok_miktar ?? 0)) }}"
                                data-siparis-miktar="{{ (int) round((float) ($row->miktar ?? 0)) }}"
                                data-siparis-verilen="{{ (int) round((float) ($row->planlanan_miktar ?? 0)) }}"
                                data-revize-edilen="{{ (int) round((float) ($row->revize_toplam ?? 0)) }}"
                                data-revize-this="{{ (int) round((float) ($row->revize_satir_miktar ?? 0)) }}">
                                <td class="col-check">
                                    <input type="checkbox" class="row-check" value="{{ $row->siparis_id }}:{{ $row->siparis_detay_id }}">
                                </td>
                                <td>{{ $row->siparis_no }}</td>
                                <td>
                                    @php
                                        $t = $row->tarih ?? null;
                                        $dt = $t ? \Carbon\Carbon::parse($t) : null;
                                    @endphp
                                    {{ $dt ? $dt->format('d.m.Y') : '' }}
                                </td>
                                <td>{{ $row->carikod }}</td>
                                <td>{{ $row->proje_kod }}</td>
                                <td>
                                    {{ $row->stok_kod }}
                                    <span style="color:#6b7280;font-size:0.75rem;">
                                        ({{ number_format((float)($row->stok_miktar ?? 0), 0, ',', '.') }})
                                    </span>
                                </td>
                                <td><span class="truncate" title="{{ $row->stok_aciklama }}">{{ $row->stok_aciklama }}</span></td>
                                <td class="num">{{ number_format((float)($row->miktar ?? 0), 0, ',', '.') }}</td>
                                <td class="num">
                                    <input type="number"
                                           class="siparis-miktar-input"
                                           min="0"
                                           step="1"
                                           value="{{ (int) max(0, round(((float)($row->miktar ?? 0)) - (((float)($row->planlanan_miktar ?? 0)) + ((float)($row->revize_satir_miktar ?? 0))))) }}">
                                </td>
                                <td class="num siparis-revize-cell">
                                    <div style="display:flex; justify-content:flex-end; align-items:center; gap: 4px; flex-wrap: wrap;">
                                        <span class="siparis-revize-value">{{ number_format((float)($row->revize_satir_miktar ?? 0), 0, ',', '.') }}</span>
                                        <button type="button" class="small-btn revize-list-btn" title="Revize Listesi">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M21 21l-4.3-4.3m1.8-5.2a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <td class="num">
                                    <div style="display:flex; justify-content:flex-end; align-items:center; gap: 4px; flex-wrap: wrap;">
                                        <span class="revize-action-value">0</span>
                                        <button type="button" class="small-btn revize-open-btn" title="Revize Aktar">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M21 21l-4.3-4.3m1.8-5.2a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <td class="num">{{ number_format((float)($row->stok_miktar ?? 0), 0, ',', '.') }}</td>
                                <td class="num revize-edilen-cell">{{ number_format((float)($row->revize_toplam ?? 0), 0, ',', '.') }}</td>
                                <td class="num gercek-stok-cell">{{ number_format(((float)($row->stok_miktar ?? 0)) - ((float)($row->revize_toplam ?? 0)), 0, ',', '.') }}</td>
                                <td class="num siparis-verilen-cell">{{ number_format((float)($row->planlanan_miktar ?? 0), 0, ',', '.') }}</td>
                                <td class="num siparis-kalan-cell">0</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="16" style="text-align:center; padding: 16px;">Kayıt bulunamadı.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if(isset($rows) && method_exists($rows, 'links'))
                    <div style="margin-top: 16px;">
                        {{ $rows->links() }}
                    </div>
                @endif
            </div>
        </section>
    </main>
</div>

<script src="{{ asset('js/dashboard.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var usableStockTh = document.querySelector('#planningTable thead tr th:nth-child(14)');
        if (usableStockTh) {
            usableStockTh.textContent = 'Kullanılabilir Stok';
        }

        var q = document.getElementById('q');
        if (q) {
            q.placeholder = 'Sipariş no, cari kod, proje, stok...';
        }
        var tbody = document.getElementById('planningTbody');
        var selectAll = document.getElementById('selectAllRows');
        var btnCreatePurchase = document.getElementById('btnCreatePurchase');
        var firmModal = document.getElementById('firmModal');
        var firmModalClose = document.getElementById('firmModalClose');
        var firmTbody = document.getElementById('firmTbody');
        var createPurchaseForm = document.getElementById('createPurchaseForm');
        var selectedRowsInput = document.getElementById('selectedRowsInput');
        var selectedCariInput = document.getElementById('selectedCariInput');

        var revisionDepotModal = document.getElementById('revisionDepotModal');
        var revisionDepotClose = document.getElementById('revisionDepotClose');
        var revisionDepotCancel = document.getElementById('revisionDepotCancel');
        var revisionDepotOk = document.getElementById('revisionDepotOk');
        var revisionDepotStokKod = document.getElementById('revisionDepotStokKod');
        var revisionDepotStokAciklama = document.getElementById('revisionDepotStokAciklama');
        var revisionDepotTbody = document.getElementById('revisionDepotTbody');
        var currentRevisionRow = null;
        var currentRevisionDetailId = 0;

        var revisionListModal = document.getElementById('revisionListModal');
        var revisionListClose = document.getElementById('revisionListClose');
        var revisionListCancel = document.getElementById('revisionListCancel');
        var revisionListOk = document.getElementById('revisionListOk');
        var revisionListStokKod = document.getElementById('revisionListStokKod');
        var revisionListStokAciklama = document.getElementById('revisionListStokAciklama');
        var revisionListTbody = document.getElementById('revisionListTbody');
        var currentRevisionListRow = null;
        var currentRevisionListDetailId = 0;

        function applyFilter() {
            if (!tbody) return;
            var val = (q && q.value ? q.value : '').toString().trim().toLowerCase();
            var rows = tbody.querySelectorAll('tr');
            rows.forEach(function (tr) {
                if (!val) {
                    tr.style.display = '';
                    return;
                }
                var hay = (tr.getAttribute('data-search') || '').toString();
                tr.style.display = hay.includes(val) ? '' : 'none';
            });
        }

        if (q) {
            q.addEventListener('input', applyFilter);
            applyFilter();
        }

        if (selectAll && tbody) {
            selectAll.addEventListener('change', function () {
                var checked = !!selectAll.checked;
                tbody.querySelectorAll('input.row-check').forEach(function (cb) {
                    if (cb.closest('tr') && cb.closest('tr').style.display === 'none') return;
                    cb.checked = checked;
                });
            });
        }

        function openModal(modal) {
            if (modal) modal.style.display = 'flex';
        }

        function closeModal(modal) {
            if (modal) modal.style.display = 'none';
        }

        function clampAktarimInput(input) {
            if (!input) return;
            var max = parseInt(input.getAttribute('max') || '0', 10) || 0;
            var v = Math.round(parseFloat(input.value || '0') || 0);
            if (v < 0) v = 0;
            if (v > max) v = max;
            input.value = String(v);
        }

        function openRevisionModalForRow(tr) {
            if (!tr) return;
            var detailId = parseInt(tr.getAttribute('data-satis-detay-id') || '0', 10) || 0;
            if (detailId <= 0) return;

            currentRevisionRow = tr;
            currentRevisionDetailId = detailId;

            if (revisionDepotStokKod) revisionDepotStokKod.textContent = tr.getAttribute('data-stok-kod') || '';
            if (revisionDepotStokAciklama) revisionDepotStokAciklama.textContent = tr.getAttribute('data-stok-aciklama') || '';
            if (revisionDepotTbody) revisionDepotTbody.innerHTML = '';

            openModal(revisionDepotModal);

            fetch('{{ route('orders.planning.revision-depot-data') }}' + '?siparissatirid=' + encodeURIComponent(String(detailId)), {
                headers: { 'Accept': 'application/json' }
            }).then(function (r) {
                return r.json().then(function (data) {
                    if (!r.ok) throw data;
                    return data;
                });
            }).then(function (data) {
                if (!data || !data.ok) throw data;
                if (revisionDepotStokKod) revisionDepotStokKod.textContent = data.stokkod || '';
                if (revisionDepotStokAciklama) revisionDepotStokAciklama.textContent = data.stokaciklama || '';

                if (!revisionDepotTbody) return;
                var rows = Array.isArray(data.rows) ? data.rows : [];
                var html = '';
                rows.forEach(function (row) {
                    var depoId = row.depo_id;
                    var depo = row.depo || '';
                    var stokKod = row.stokkod || '';
                    var stokMiktar = Math.round(parseFloat(row.stokmiktar || '0') || 0);
                    var revizeMiktar = Math.round(parseFloat(row.revizemiktar || '0') || 0);
                    var kullanilabilir = Math.round(parseFloat(row.kullanilabilir || '0') || 0);
                    if (kullanilabilir < 0) kullanilabilir = 0;

                    html += '<tr data-depo-id=\"' + depoId + '\">';
                    html += '<td>' + depo + '</td>';
                    html += '<td>' + stokKod + '</td>';
                    html += '<td class=\"num\">' + formatInt(stokMiktar) + '</td>';
                    html += '<td class=\"num\">' + formatInt(revizeMiktar) + '</td>';
                    html += '<td class=\"num\">' + formatInt(kullanilabilir) + '</td>';
                    html += '<td class=\"num\">' +
                        '<input type=\"number\" class=\"aktarim-input\" min=\"0\" step=\"1\" value=\"0\" max=\"' + kullanilabilir + '\" style=\"width:84px; text-align:right; border-radius:999px; border:1px solid #e5e7eb; padding:0.3rem 0.6rem; font-size:0.8rem;\" />' +
                        '</td>';
                    html += '</tr>';
                });
                revisionDepotTbody.innerHTML = html;

                revisionDepotTbody.querySelectorAll('input.aktarim-input').forEach(function (inp) {
                    inp.addEventListener('input', function () { clampAktarimInput(inp); });
                    inp.addEventListener('blur', function () { clampAktarimInput(inp); });
                });
            }).catch(function () {
                closeModal(revisionDepotModal);
                alert('Revize verileri alınamadı.');
            });
        }

        function formatDateTime(value) {
            if (!value) return '';
            try {
                var d = new Date(value);
                if (isNaN(d.getTime())) return value.toString();
                return d.toLocaleString('tr-TR');
            } catch (e) {
                return value.toString();
            }
        }

        function openRevisionListForRow(tr) {
            if (!tr) return;
            var detailId = parseInt(tr.getAttribute('data-satis-detay-id') || '0', 10) || 0;
            if (detailId <= 0) return;

            currentRevisionListRow = tr;
            currentRevisionListDetailId = detailId;

            if (revisionListStokKod) revisionListStokKod.textContent = tr.getAttribute('data-stok-kod') || '';
            if (revisionListStokAciklama) revisionListStokAciklama.textContent = tr.getAttribute('data-stok-aciklama') || '';
            if (revisionListTbody) revisionListTbody.innerHTML = '';

            openModal(revisionListModal);

            fetch('{{ route('orders.planning.revision-list-data') }}' + '?siparissatirid=' + encodeURIComponent(String(detailId)), {
                headers: { 'Accept': 'application/json' }
            }).then(function (r) {
                return r.json().then(function (data) {
                    if (!r.ok) throw data;
                    return data;
                });
            }).then(function (data) {
                if (!data || !data.ok) throw data;
                if (revisionListStokKod) revisionListStokKod.textContent = data.stokkod || '';
                if (revisionListStokAciklama) revisionListStokAciklama.textContent = data.stokaciklama || '';

                if (!revisionListTbody) return;
                var rows = Array.isArray(data.rows) ? data.rows : [];
                var html = '';
                rows.forEach(function (row) {
                    var id = row.id;
                    var depoKod = row.depo_kod || '-';
                    var qty = Math.round(parseFloat(row.miktar || '0') || 0);
                    var max = Math.round(parseFloat(row.max || '0') || 0);
                    var createdAt = formatDateTime(row.created_at);

                    html += '<tr data-rev-id=\"' + id + '\">';
                    html += '<td>' + depoKod + '</td>';
                    html += '<td class=\"num\">' +
                        '<input type=\"number\" class=\"rev-edit-input\" min=\"0\" step=\"1\" value=\"' + qty + '\" max=\"' + max + '\" style=\"width:84px; text-align:right; border-radius:999px; border:1px solid #e5e7eb; padding:0.3rem 0.6rem; font-size:0.8rem;\" />' +
                        '</td>';
                    html += '<td>' + createdAt + '</td>';
                    html += '<td class=\"num\">' +
                        '<button type=\"button\" class=\"offers-filter-reset rev-delete-btn\" style=\"padding:0.25rem 0.6rem; border-radius:999px; border:1px solid #fecaca; background:#fff; color:#dc2626;\">Sil</button>' +
                        '</td>';
                    html += '</tr>';
                });
                revisionListTbody.innerHTML = html;

                revisionListTbody.querySelectorAll('input.rev-edit-input').forEach(function (inp) {
                    inp.addEventListener('input', function () { clampAktarimInput(inp); });
                    inp.addEventListener('blur', function () { clampAktarimInput(inp); });
                });

                revisionListTbody.querySelectorAll('button.rev-delete-btn').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        var rowEl = btn.closest('tr[data-rev-id]');
                        var id = rowEl ? parseInt(rowEl.getAttribute('data-rev-id') || '0', 10) : 0;
                        if (id <= 0) return;
                        if (!confirm('Silmek istediğinize emin misiniz?')) return;

                        fetch('{{ route('orders.planning.revision-list-delete') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ siparissatirid: currentRevisionListDetailId, id: id })
                        }).then(function (r) {
                            return r.json().then(function (data) {
                                if (!r.ok) throw data;
                                return data;
                            });
                        }).then(function (data) {
                            var siparisRevize = Math.round(parseFloat(data.siparis_revize || '0') || 0);
                            currentRevisionListRow.setAttribute('data-revize-this', String(siparisRevize));
                            var v = currentRevisionListRow.querySelector('.siparis-revize-value');
                            if (v) v.textContent = formatInt(siparisRevize);

                            updateStockRows(data.stokkod, data.stok_miktar, data.revize_toplam);
                            openRevisionListForRow(currentRevisionListRow);
                        }).catch(function () {
                            alert('Silme hatası.');
                        });
                    });
                });
            }).catch(function () {
                closeModal(revisionListModal);
                alert('Revize listesi alınamadı.');
            });
        }

        function collectSelectedSalesRows() {
            if (!tbody) return [];
            var selected = [];
            tbody.querySelectorAll('input.row-check:checked').forEach(function (cb) {
                var tr = cb.closest('tr');
                if (!tr) return;
                var detailId = parseInt(tr.getAttribute('data-satis-detay-id') || '0', 10) || 0;
                var urunId = parseInt(tr.getAttribute('data-urun-id') || '0', 10) || 0;
                var qtyInput = tr.querySelector('.siparis-miktar-input');
                var qty = qtyInput ? Math.round(parseFloat(qtyInput.value || '0') || 0) : 0;
                if (detailId > 0 && urunId > 0 && qty > 0) {
                    selected.push({ satis_detay_id: detailId, urun_id: urunId, siparis_miktar: qty });
                }
            });
            return selected;
        }

        if (btnCreatePurchase) {
            btnCreatePurchase.addEventListener('click', function () {
                var selected = collectSelectedSalesRows();
                if (selected.length === 0) {
                    alert('Lütfen satış siparişi satırlarını seçin.');
                    return;
                }
                if (selectedRowsInput) {
                    selectedRowsInput.value = JSON.stringify(selected);
                }
                openModal(firmModal);
            });
        }

        function debounce(fn, wait) {
            var t = null;
            return function () {
                var ctx = this;
                var args = arguments;
                clearTimeout(t);
                t = setTimeout(function () {
                    fn.apply(ctx, args);
                }, wait);
            };
        }

        function formatInt(value) {
            var n = Math.round(parseFloat(value || '0') || 0);
            try {
                return new Intl.NumberFormat('tr-TR', { maximumFractionDigits: 0 }).format(n);
            } catch (e) {
                return String(n);
            }
        }

        function getIntAttr(tr, name) {
            if (!tr) return 0;
            return Math.round(parseFloat(tr.getAttribute(name) || '0') || 0);
        }

        function recalcStockAndLimits(tr) {
            if (!tr) return;
            var stokMiktar = getIntAttr(tr, 'data-stok-miktar');
            var revizeToplam = getIntAttr(tr, 'data-revize-edilen');
            var gercek = stokMiktar - revizeToplam;

            var gercekCell = tr.querySelector('.gercek-stok-cell');
            if (gercekCell) {
                gercekCell.textContent = formatInt(gercek);
            }

            var input = tr.querySelector('.revize-miktar-input');
            if (input) {
                var thisRevize = getIntAttr(tr, 'data-revize-this');
                var maxForRow = Math.max(0, gercek + thisRevize);
                input.max = String(maxForRow);
            }
        }

        function updateStockRows(stokkod, stokMiktar, revizeToplam) {
            if (!tbody) return;
            var code = (stokkod || '').toString();
            if (!code) return;

            tbody.querySelectorAll('tr[data-stok-kod]').forEach(function (row) {
                if ((row.getAttribute('data-stok-kod') || '').toString() !== code) return;

                row.setAttribute('data-stok-miktar', String(Math.round(parseFloat(stokMiktar || '0') || 0)));
                row.setAttribute('data-revize-edilen', String(Math.round(parseFloat(revizeToplam || '0') || 0)));

                var revCell = row.querySelector('.revize-edilen-cell');
                if (revCell) {
                    revCell.textContent = formatInt(revizeToplam);
                }

                recalcStockAndLimits(row);
                recalcKalan(row);
            });
        }

        function saveRevisionForRow(tr) {
            if (!tr) return;
            var detailId = parseInt(tr.getAttribute('data-satis-detay-id') || '0', 10) || 0;
            var input = tr.querySelector('.revize-miktar-input');
            if (!input || detailId <= 0) return;

            var valRaw = (input.value || '').toString().trim();
            var currentThis = getIntAttr(tr, 'data-revize-this');
            var maxForRow = parseInt(input.max || '0', 10);
            if (!isFinite(maxForRow) || maxForRow < 0) {
                recalcStockAndLimits(tr);
                maxForRow = parseInt(input.max || '0', 10) || 0;
            }
            var payload = new URLSearchParams();
            payload.set('siparissatirid', String(detailId));

            if (valRaw !== '') {
                var nextVal = Math.round(parseFloat(valRaw) || 0);
                if (nextVal > maxForRow) nextVal = maxForRow;
                if (nextVal < 0) nextVal = 0;
                input.value = String(nextVal);
                payload.set('miktar', String(nextVal));
            } else {
                if (currentThis <= 0) {
                    input.style.outline = 'none';
                    return;
                }
            }

            fetch('{{ route('orders.planning.save-revision') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: payload.toString()
            }).then(function (r) {
                return r.json().then(function (data) {
                    if (!r.ok) throw data;
                    return data;
                });
            }).then(function (data) {
                input.style.outline = 'none';
                var thisRevize = data && data.this_revize !== undefined ? Math.round(parseFloat(data.this_revize || '0') || 0) : 0;
                tr.setAttribute('data-revize-this', String(thisRevize));
                    var thisCell = tr.querySelector('.siparis-revize-value');
                    if (thisCell) thisCell.textContent = formatInt(thisRevize);

                if (data && data.stokkod) {
                    updateStockRows(data.stokkod, data.stok_miktar, data.revize_toplam);
                } else {
                    recalcStockAndLimits(tr);
                    recalcKalan(tr);
                }
                input.value = '';
            }).catch(function (err) {
                input.style.outline = '2px solid #ef4444';
                if (err && err.max !== undefined) {
                    input.max = String(Math.round(parseFloat(err.max || '0') || 0));
                }
            });
        }

        function recalcKalan(tr) {
            if (!tr) return;
            var verilen = Math.round(parseFloat(tr.getAttribute('data-siparis-verilen') || '0') || 0);
            var siparisRevize = getIntAttr(tr, 'data-revize-this');
            var siparisMiktar = getIntAttr(tr, 'data-siparis-miktar');
            var kalan = siparisMiktar - (verilen + siparisRevize);
            var cell = tr.querySelector('.siparis-kalan-cell');
            if (cell) {
                cell.textContent = formatInt(kalan);
            }
        }

        if (tbody) {
            tbody.querySelectorAll('tr[data-satis-detay-id]').forEach(function (tr) {
                recalcStockAndLimits(tr);
                recalcKalan(tr);
            });

            tbody.querySelectorAll('.siparis-miktar-input').forEach(function (input) {
                var tr = input.closest('tr');
                if (!tr) return;
                input.addEventListener('input', function () { recalcKalan(tr); });
                input.addEventListener('blur', function () { recalcKalan(tr); });
            });

            tbody.querySelectorAll('.revize-open-btn').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    var tr = btn.closest('tr');
                    openRevisionModalForRow(tr);
                });
            });

            tbody.querySelectorAll('.revize-list-btn').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    var tr = btn.closest('tr');
                    openRevisionListForRow(tr);
                });
            });
        }

        if (firmModalClose) {
            firmModalClose.addEventListener('click', function () {
                closeModal(firmModal);
            });
        }

        if (firmModal) {
            firmModal.addEventListener('click', function (e) {
                if (e.target === firmModal) {
                    closeModal(firmModal);
                }
            });
        }

        if (firmTbody && createPurchaseForm && selectedCariInput) {
            firmTbody.querySelectorAll('tr[data-carikod]').forEach(function (row) {
                row.addEventListener('click', function () {
                    var carikod = row.getAttribute('data-carikod') || '';
                    if (!carikod) return;
                    selectedCariInput.value = carikod;
                    createPurchaseForm.submit();
                });
            });
        }

        if (revisionDepotClose) {
            revisionDepotClose.addEventListener('click', function () { closeModal(revisionDepotModal); });
        }
        if (revisionDepotCancel) {
            revisionDepotCancel.addEventListener('click', function () { closeModal(revisionDepotModal); });
        }
        if (revisionDepotModal) {
            revisionDepotModal.addEventListener('click', function (e) {
                if (e.target === revisionDepotModal) closeModal(revisionDepotModal);
            });
        }

        if (revisionDepotOk) {
            revisionDepotOk.addEventListener('click', function () {
                if (!currentRevisionRow || currentRevisionDetailId <= 0 || !revisionDepotTbody) {
                    closeModal(revisionDepotModal);
                    return;
                }

                var items = [];
                revisionDepotTbody.querySelectorAll('tr[data-depo-id]').forEach(function (row) {
                    var depoId = parseInt(row.getAttribute('data-depo-id') || '0', 10) || 0;
                    var inp = row.querySelector('input.aktarim-input');
                    var qty = inp ? Math.round(parseFloat(inp.value || '0') || 0) : 0;
                    if (depoId > 0 && qty > 0) {
                        items.push({ depo_id: depoId, miktar: qty });
                    }
                });

                if (items.length === 0) {
                    closeModal(revisionDepotModal);
                    return;
                }

                fetch('{{ route('orders.planning.revision-depot-save') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ siparissatirid: currentRevisionDetailId, items: items })
                }).then(function (r) {
                    return r.json().then(function (data) {
                        if (!r.ok) throw data;
                        return data;
                    });
                }).then(function (data) {
                    if (!data || !data.ok) throw data;

                    var siparisRevize = Math.round(parseFloat(data.siparis_revize || '0') || 0);
                    currentRevisionRow.setAttribute('data-revize-this', String(siparisRevize));
                    var cell = currentRevisionRow.querySelector('.siparis-revize-value');
                    if (cell) cell.textContent = formatInt(siparisRevize);

                    updateStockRows(data.stokkod, data.stok_miktar, data.revize_toplam);
                    closeModal(revisionDepotModal);
                }).catch(function (err) {
                    if (err && err.depo_id && err.max !== undefined && revisionDepotTbody) {
                        var targetRow = revisionDepotTbody.querySelector('tr[data-depo-id=\"' + String(err.depo_id) + '\"]');
                        var inp = targetRow ? targetRow.querySelector('input.aktarim-input') : null;
                        if (inp) {
                            inp.max = String(Math.round(parseFloat(err.max || '0') || 0));
                            inp.style.outline = '2px solid #ef4444';
                            clampAktarimInput(inp);
                        }
                    } else {
                        alert('Kaydetme hatası.');
                    }
                });
            });
        }

        if (revisionListClose) {
            revisionListClose.addEventListener('click', function () { closeModal(revisionListModal); });
        }
        if (revisionListCancel) {
            revisionListCancel.addEventListener('click', function () { closeModal(revisionListModal); });
        }
        if (revisionListModal) {
            revisionListModal.addEventListener('click', function (e) {
                if (e.target === revisionListModal) closeModal(revisionListModal);
            });
        }

        if (revisionListOk) {
            revisionListOk.addEventListener('click', function () {
                if (!currentRevisionListRow || currentRevisionListDetailId <= 0 || !revisionListTbody) {
                    closeModal(revisionListModal);
                    return;
                }

                var items = [];
                revisionListTbody.querySelectorAll('tr[data-rev-id]').forEach(function (row) {
                    var id = parseInt(row.getAttribute('data-rev-id') || '0', 10) || 0;
                    var inp = row.querySelector('input.rev-edit-input');
                    var qty = inp ? Math.round(parseFloat(inp.value || '0') || 0) : 0;
                    if (id > 0) items.push({ id: id, miktar: qty });
                });

                fetch('{{ route('orders.planning.revision-list-save') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ siparissatirid: currentRevisionListDetailId, items: items })
                }).then(function (r) {
                    return r.json().then(function (data) {
                        if (!r.ok) throw data;
                        return data;
                    });
                }).then(function (data) {
                    var siparisRevize = Math.round(parseFloat(data.siparis_revize || '0') || 0);
                    currentRevisionListRow.setAttribute('data-revize-this', String(siparisRevize));
                    var v = currentRevisionListRow.querySelector('.siparis-revize-value');
                    if (v) v.textContent = formatInt(siparisRevize);

                    updateStockRows(data.stokkod, data.stok_miktar, data.revize_toplam);
                    closeModal(revisionListModal);
                }).catch(function (err) {
                    if (err && err.id && err.max !== undefined && revisionListTbody) {
                        var targetRow = revisionListTbody.querySelector('tr[data-rev-id=\"' + String(err.id) + '\"]');
                        var inp = targetRow ? targetRow.querySelector('input.rev-edit-input') : null;
                        if (inp) {
                            inp.max = String(Math.round(parseFloat(err.max || '0') || 0));
                            inp.style.outline = '2px solid #ef4444';
                            clampAktarimInput(inp);
                        }
                    } else {
                        alert('Güncelleme hatası.');
                    }
                });
            });
        }
    });
</script>

<form id="createPurchaseForm" method="POST" action="{{ route('orders.planning.create-purchase') }}" style="display:none;">
    @csrf
    <input type="hidden" id="selectedCariInput" name="carikod" value="">
    <input type="hidden" id="selectedRowsInput" name="selected_rows" value="">
</form>

<div id="firmModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Cari Seç</div>
            <button type="button" id="firmModalClose" class="small-btn">X</button>
        </div>
        <div class="modal-body">
            <table class="modal-table">
                <thead>
                <tr>
                    <th>Cari Kod</th>
                    <th>Cari Açıklama</th>
                </tr>
                </thead>
                <tbody id="firmTbody">
                @foreach(($firms ?? []) as $firm)
                    <tr data-carikod="{{ $firm->carikod }}">
                        <td>{{ $firm->carikod }}</td>
                        <td>{{ $firm->cariaciklama }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="revisionDepotModal" class="modal-overlay">
    <div class="modal" style="max-width: 980px;">
        <div class="modal-header">
            <div class="modal-title">Revize Aktarım</div>
            <button type="button" id="revisionDepotClose" class="small-btn">X</button>
        </div>
        <div class="modal-body">
            <div style="display:flex; gap: 16px; margin-bottom: 10px; flex-wrap: wrap;">
                <div><span style="color:#6b7280;">Stok Kod:</span> <strong id="revisionDepotStokKod"></strong></div>
                <div><span style="color:#6b7280;">Stok Açıklama:</span> <strong id="revisionDepotStokAciklama"></strong></div>
            </div>

            <table class="modal-table">
                <thead>
                <tr>
                    <th>Depo</th>
                    <th>Stok Kod</th>
                    <th class="num">Stok Miktar</th>
                    <th class="num">Revize Miktar</th>
                    <th class="num">Kullanılabilir Stok</th>
                    <th class="num">Aktarım Miktar</th>
                </tr>
                </thead>
                <tbody id="revisionDepotTbody"></tbody>
            </table>

            <div style="display:flex; justify-content:flex-end; align-items:center; gap: 12px; margin-top: 14px;">
                <button type="button" id="revisionDepotCancel" class="offers-filter-reset" style="border:none; color:#dc2626; font-weight:700; background:transparent;">Kapat</button>
                <button type="button" id="revisionDepotOk" class="offers-new-button">Tamam</button>
            </div>
        </div>
    </div>
</div>

<div id="revisionListModal" class="modal-overlay">
    <div class="modal" style="max-width: 900px;">
        <div class="modal-header">
            <div class="modal-title">Sipariş Revize</div>
            <button type="button" id="revisionListClose" class="small-btn">X</button>
        </div>
        <div class="modal-body">
            <div style="display:flex; gap: 16px; margin-bottom: 10px; flex-wrap: wrap;">
                <div><span style="color:#6b7280;">Stok Kod:</span> <strong id="revisionListStokKod"></strong></div>
                <div><span style="color:#6b7280;">Stok Açıklama:</span> <strong id="revisionListStokAciklama"></strong></div>
            </div>

            <table class="modal-table">
                <thead>
                <tr>
                    <th>Depo Kodu</th>
                    <th class="num">Revize Miktarı</th>
                    <th>Ekleme Tarihi</th>
                    <th class="num"></th>
                </tr>
                </thead>
                <tbody id="revisionListTbody"></tbody>
            </table>

            <div style="display:flex; justify-content:flex-end; align-items:center; gap: 12px; margin-top: 14px;">
                <button type="button" id="revisionListCancel" class="offers-filter-reset" style="border:none; color:#dc2626; font-weight:700; background:transparent;">Kapat</button>
                <button type="button" id="revisionListOk" class="offers-new-button">Tamam</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
