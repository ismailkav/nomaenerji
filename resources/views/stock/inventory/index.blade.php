<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Envanter - NomaEnerji</title>
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

        .offers-filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .offers-filter-row label {
            font-size: 0.75rem;
            font-weight: 500;
            color: #4b5563;
        }

        .offers-filter-row input[type="text"] {
            min-width: 360px;
            border-radius: 999px;
            border: 1px solid #e5e7eb;
            background-color: #ffffff;
            padding: 0.45rem 0.85rem;
            font-size: 0.8rem;
            color: #111827;
            outline: none;
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
            max-width: 980px;
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
                Stok Envanter
            </div>
        </header>

        <section class="content-section" style="padding: 2rem;">
            <div class="offers-table-card">
                <div class="offers-filter-row">
                    <div style="display:flex; align-items:center; gap:0.6rem;">
                        <label for="inventorySearch" style="margin:0;">Arama (Depo / Stok Kod / Açıklama)</label>
                        <input type="text" id="inventorySearch">
                    </div>
                </div>
                <div class="offers-table-wrapper">
                    <table class="offers-table">
                        <thead>
                        <tr>
                            <th>Depo</th>
                            <th>Stok Kod</th>
                            <th>Stok Açıklama</th>
                            <th style="text-align:right;">Stok Miktar</th>
                            <th style="text-align:right;">Rezerve Miktar</th>
                            <th style="text-align:right;">Kullanılabilir Miktar</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($items as $item)
                            <tr data-search="{{ strtolower(trim(($item->depo_kod ?? '') . ' ' . ($item->stokkod ?? '') . ' ' . ($item->stokaciklama ?? ''))) }}">
                                <td>{{ $item->depo_kod }}</td>
                                <td>{{ $item->stokkod }}</td>
                                <td>{{ $item->stokaciklama }}</td>
                                <td style="text-align:right;">{{ number_format((float) $item->stokmiktar, 4, ',', '.') }}</td>
                                <td style="text-align:right;">
                                    <div style="display:flex; justify-content:flex-end; align-items:center; gap: 8px;">
                                        <span>{{ number_format((float) ($item->revize_miktar ?? 0), 4, ',', '.') }}</span>
                                        <button type="button"
                                                class="small-btn reserve-detail-btn"
                                                data-depo-id="{{ $item->depo_id }}"
                                                data-stokkod="{{ $item->stokkod }}"
                                                data-stokaciklama="{{ $item->stokaciklama }}"
                                                title="Rezerve Detay">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <td style="text-align:right;">{{ number_format(((float) $item->stokmiktar) - ((float) ($item->revize_miktar ?? 0)), 4, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center; padding: 16px;">Kayıt bulunamadı.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
</div>

<script src="{{ asset('js/dashboard.js') }}"></script>
<script>
    (function () {
        var input = document.getElementById('inventorySearch');
        if (!input) return;

        var rows = Array.prototype.slice.call(document.querySelectorAll('table.offers-table tbody tr[data-search]'));
        input.addEventListener('input', function () {
            var q = (input.value || '').toLowerCase().trim();
            rows.forEach(function (row) {
                var hay = (row.getAttribute('data-search') || '').toLowerCase();
                row.style.display = hay.indexOf(q) !== -1 ? '' : 'none';
            });
        });
    })();
</script>

<div id="reserveDetailModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Rezerve Detay</div>
            <button type="button" id="reserveDetailClose" class="small-btn">X</button>
        </div>
        <div class="modal-body">
            <div style="display:flex; gap: 16px; margin-bottom: 10px; flex-wrap: wrap;">
                <div><span style="color:#6b7280;">Stok Kod:</span> <strong id="reserveDetailStokKod"></strong></div>
                <div><span style="color:#6b7280;">Stok Açıklama:</span> <strong id="reserveDetailStokAciklama"></strong></div>
            </div>

            <table class="modal-table">
                <thead>
                <tr>
                    <th>Sipariş No</th>
                    <th>Sipariş Tarih</th>
                    <th>Sipariş Cari Kod</th>
                    <th>Sipariş Durum</th>
                    <th style="text-align:right;">Rezerve Miktar</th>
                </tr>
                </thead>
                <tbody id="reserveDetailTbody"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    (function () {
        var modal = document.getElementById('reserveDetailModal');
        var closeBtn = document.getElementById('reserveDetailClose');
        var tbody = document.getElementById('reserveDetailTbody');
        var stokKodEl = document.getElementById('reserveDetailStokKod');
        var stokAciklamaEl = document.getElementById('reserveDetailStokAciklama');

        function openModal() { if (modal) modal.style.display = 'flex'; }
        function closeModal() { if (modal) modal.style.display = 'none'; }

        function fmtQty(v) {
            var n = parseFloat(v || '0') || 0;
            try { return new Intl.NumberFormat('tr-TR', { minimumFractionDigits: 4, maximumFractionDigits: 4 }).format(n); } catch (e) { return String(n); }
        }

        function fmtDate(v) {
            if (!v) return '';
            try {
                var d = new Date(v);
                if (isNaN(d.getTime())) return v.toString();
                return d.toLocaleDateString('tr-TR');
            } catch (e) {
                return v.toString();
            }
        }

        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (modal) modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });

        document.querySelectorAll('.reserve-detail-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var depoId = btn.getAttribute('data-depo-id') || '';
                var stokkod = btn.getAttribute('data-stokkod') || '';
                var stokaciklama = btn.getAttribute('data-stokaciklama') || '';

                if (stokKodEl) stokKodEl.textContent = stokkod;
                if (stokAciklamaEl) stokAciklamaEl.textContent = stokaciklama;
                if (tbody) tbody.innerHTML = '';

                openModal();

                var url = '{{ route('stock.inventory.reserve-details') }}' + '?depo_id=' + encodeURIComponent(depoId) + '&stokkod=' + encodeURIComponent(stokkod);
                fetch(url, { headers: { 'Accept': 'application/json' } })
                    .then(function (r) { return r.json().then(function (data) { if (!r.ok) throw data; return data; }); })
                    .then(function (data) {
                        if (!data || !data.ok || !tbody) return;
                        var rows = Array.isArray(data.rows) ? data.rows : [];
                        if (rows.length === 0) {
                            tbody.innerHTML = '<tr><td colspan="5" style="text-align:center; padding: 12px;">Kayıt bulunamadı.</td></tr>';
                            return;
                        }
                        var html = '';
                        rows.forEach(function (row) {
                            html += '<tr>';
                            html += '<td>' + (row.siparis_no || '') + '</td>';
                            html += '<td>' + fmtDate(row.tarih) + '</td>';
                            html += '<td>' + (row.carikod || '') + '</td>';
                            html += '<td>' + (row.siparis_durum || '') + '</td>';
                            html += '<td style="text-align:right;">' + fmtQty(row.rezerve_miktar) + '</td>';
                            html += '</tr>';
                        });
                        tbody.innerHTML = html;
                    })
                    .catch(function () {
                        closeModal();
                        alert('Rezerve detay alınamadı.');
                    });
            });
        });
    })();
</script>
</body>
</html>
