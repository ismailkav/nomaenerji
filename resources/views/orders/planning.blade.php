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
            white-space: nowrap;
        }

        .offers-table tbody td {
            padding: 0.7rem 0.9rem;
            font-size: 0.8rem;
            color: #111827;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
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

        .num input[type="number"] {
            width: 120px;
            border-radius: 999px;
            border: 1px solid #e5e7eb;
            padding: 0.3rem 0.6rem;
            font-size: 0.8rem;
            text-align: right;
            outline: none;
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
                            <th>Stok Kod</th>
                            <th>Stok Açıklama</th>
                            <th class="num">Miktar</th>
                            <th class="num">Sipariş Miktar</th>
                            <th class="num">Stok Miktar</th>
                            <th class="num">Planlanan Miktar</th>
                        </tr>
                        </thead>
                        <tbody id="planningTbody">
                        @forelse(($rows ?? []) as $row)
                            <tr data-search="{{ strtolower(trim(($row->siparis_no ?? '') . ' ' . ($row->carikod ?? '') . ' ' . ($row->stok_kod ?? '') . ' ' . ($row->stok_aciklama ?? ''))) }}"
                                data-satis-detay-id="{{ $row->siparis_detay_id }}"
                                data-urun-id="{{ $row->urun_id }}">
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
                                <td>{{ $row->stok_kod }}</td>
                                <td><span class="truncate" title="{{ $row->stok_aciklama }}">{{ $row->stok_aciklama }}</span></td>
                                <td class="num">{{ number_format((float)($row->miktar ?? 0), 3, ',', '.') }}</td>
                                <td class="num">
                                    <input type="number"
                                           class="siparis-miktar-input"
                                           min="0"
                                           step="0.001"
                                           value="{{ (float)($row->miktar ?? 0) }}">
                                </td>
                                <td class="num">{{ number_format((float)($row->stok_miktar ?? 0), 3, ',', '.') }}</td>
                                <td class="num">{{ number_format((float)($row->planlanan_miktar ?? 0), 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" style="text-align:center; padding: 16px;">Kayıt bulunamadı.</td>
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
        var q = document.getElementById('q');
        var tbody = document.getElementById('planningTbody');
        var selectAll = document.getElementById('selectAllRows');
        var btnCreatePurchase = document.getElementById('btnCreatePurchase');
        var firmModal = document.getElementById('firmModal');
        var firmModalClose = document.getElementById('firmModalClose');
        var firmTbody = document.getElementById('firmTbody');
        var createPurchaseForm = document.getElementById('createPurchaseForm');
        var selectedRowsInput = document.getElementById('selectedRowsInput');
        var selectedCariInput = document.getElementById('selectedCariInput');

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

        function collectSelectedSalesRows() {
            if (!tbody) return [];
            var selected = [];
            tbody.querySelectorAll('input.row-check:checked').forEach(function (cb) {
                var tr = cb.closest('tr');
                if (!tr) return;
                var detailId = parseInt(tr.getAttribute('data-satis-detay-id') || '0', 10) || 0;
                var urunId = parseInt(tr.getAttribute('data-urun-id') || '0', 10) || 0;
                var qtyInput = tr.querySelector('.siparis-miktar-input');
                var qty = qtyInput ? parseFloat(qtyInput.value || '0') || 0 : 0;
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
</body>
</html>
