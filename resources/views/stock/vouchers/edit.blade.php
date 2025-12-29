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

        .modal-body {
            padding: 0.75rem 1rem;
            overflow: auto;
        }

        .modal-actions {
            padding: 0.75rem 1rem;
            border-top: 1px solid #e5e7eb;
            text-align: right;
        }

        .modal-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8rem;
        }

        .modal-table th,
        .modal-table td {
            border-bottom: 1px solid #e5e7eb;
            padding: 0.35rem 0.4rem;
        }

        .modal-table thead {
            background: #f3f4f6;
        }

        .modal-table tr:hover {
            background: #e5e7eb;
            cursor: pointer;
        }

        .offer-card {
            border-radius: 16px;
            border: 1px solid rgba(148, 163, 184, 0.25);
            padding: 1rem 1.25rem 1.5rem;
            background: var(--card-bg);
        }

        .offer-header {
            display: flex;
            gap: 2rem;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .offer-header-left,
        .offer-header-right {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }

        .offer-header-left {
            flex: 1 1 0;
            min-width: 420px;
        }

        .offer-header-right {
            flex: 0 0 320px;
            max-width: 320px;
        }

        .offer-header-row {
            display: flex;
            gap: 1.75rem;
            align-items: flex-start;
        }

        .offer-header-row .form-group {
            flex: 1;
            min-width: 0;
        }

        .form-group {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 0.5rem;
        }

        .form-group label {
            display: inline-block;
            font-size: 0.8rem;
            font-weight: 500;
            color: #9ca3af;
            min-width: 110px;
            margin: 0;
        }

        .header-input {
            border: none !important;
            background: transparent !important;
            outline: none !important;
            padding: 0 !important;
            border-radius: 0 !important;
            color: #000000 !important;
            width: 100%;
        }

        .header-input.short {
            max-width: 180px;
        }

        .offer-header-right .header-input {
            max-width: 170px;
        }

        .offer-header-right textarea.header-input {
            max-width: 320px;
        }

        .header-value {
            color: #000000;
            font-size: 0.9rem;
            line-height: 1.2;
        }

        .input-with-button {
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .small-btn {
            border-radius: 999px;
            border: none;
            background: transparent;
            color: #000000;
            cursor: pointer;
            padding: 0.25rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn {
            padding: 0.45rem 1.2rem;
            border-radius: 999px;
            border: none;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-cancel {
            background: #ef4444;
            color: #fff;
        }

        .btn-save {
            background: #2563eb;
            color: #fff;
        }

        .btn-secondary {
            padding: 0.55rem 1.1rem;
            border-radius: 999px;
            border: 1px solid #e5e7eb;
            background-color: #ffffff;
            color: #4b5563;
            font-size: 0.85rem;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-danger {
            padding: 0.45rem 0.9rem;
            border-radius: 999px;
            border: none;
            background-color: #ef4444;
            color: #ffffff;
            font-size: 0.8rem;
            cursor: pointer;
        }

        .table-wrapper {
            margin-top: 1rem;
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid rgba(203, 213, 225, 0.7);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
        }

        thead {
            background-color: #f3f4f6;
        }

        th, td {
            padding: 0.65rem 0.75rem;
            font-size: 0.82rem;
            border-bottom: 1px solid #e5e7eb;
            white-space: nowrap;
        }

        th {
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            color: #374151;
        }

        .line-input {
            width: 140px;
            padding: 0.4rem 0.6rem;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            font-size: 0.8rem;
        }

        .line-input-wide {
            width: 100%;
            min-width: 260px;
            padding: 0.4rem 0.6rem;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            font-size: 0.8rem;
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
                {{ $title }}
            </div>

            @php
                $isNewRecord = ($isNew ?? false) || !($fiche->exists ?? false);
                $indexRoute = $type === 'sayim_giris'
                    ? route('stock.count-in.index')
                    : ($type === 'sayim_cikis' ? route('stock.count-out.index') : route('stock.depot-transfer.index'));
                $storeRoute = $type === 'sayim_giris'
                    ? route('stock.count-in.store')
                    : ($type === 'sayim_cikis' ? route('stock.count-out.store') : route('stock.depot-transfer.store'));
                $updateRoute = $isNewRecord
                    ? null
                    : ($type === 'sayim_giris'
                        ? route('stock.count-in.update', $fiche)
                        : ($type === 'sayim_cikis' ? route('stock.count-out.update', $fiche) : route('stock.depot-transfer.update', $fiche)));
                $destroyRoute = $isNewRecord
                    ? null
                    : ($type === 'sayim_giris'
                        ? route('stock.count-in.destroy', $fiche)
                        : ($type === 'sayim_cikis' ? route('stock.count-out.destroy', $fiche) : route('stock.depot-transfer.destroy', $fiche)));
            @endphp

            <div style="margin-left:auto; display:flex; align-items:center; gap:0.5rem;">
                <a href="{{ $indexRoute }}" class="btn btn-cancel" style="margin-left:0.75rem;">İptal</a>
                @if(!$isNewRecord)
                    <form method="POST" action="{{ $destroyRoute }}" onsubmit="return confirm('Bu fişi silmek istiyor musunuz?');" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-cancel" style="background:#b91c1c;">Sil</button>
                    </form>
                @endif
                <button type="submit" form="voucherForm" class="btn btn-save">Kaydet</button>
            </div>
        </header>

        <section class="content-section" style="padding: 2rem;">
            <div class="offer-card">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ implode(' ', $errors->all()) }}
                    </div>
                @endif

                <form id="voucherForm" method="POST" action="{{ $isNewRecord ? $storeRoute : $updateRoute }}">
                    @csrf

                    <div class="offer-header">
                        <div class="offer-header-left">
                            <div class="offer-header-row">
                                <div class="form-group">
                                    <label>Fiş No</label>
                                    <input class="header-input short" type="text" value="{{ $fiche->fis_no }}" readonly>
                                </div>

                                @if($type === 'depo_transfer')
                                    <div class="form-group">
                                        <label>Çıkış Depo</label>
                                        <div class="input-with-button">
                                            <span id="cikis_depo_kod_label" class="header-value">{{ $fiche->cikisDepo?->kod ?? '' }}</span>
                                            <input type="hidden" id="cikis_depo_id" name="cikis_depo_id" value="{{ old('cikis_depo_id', $fiche->cikis_depo_id) }}">
                                            <button type="button" class="small-btn" data-depo-button="cikis" title="Depo Seç">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="11" cy="11" r="6" stroke="currentColor" stroke-width="2"/>
                                                    <line x1="16" y1="16" x2="21" y2="21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div class="form-group">
                                        <label>Depo</label>
                                        <div class="input-with-button">
                                            <span id="depo_kod_label" class="header-value">{{ $fiche->depo?->kod ?? '' }}</span>
                                            <input type="hidden" id="depo_id" name="depo_id" value="{{ old('depo_id', $fiche->depo_id) }}">
                                            <button type="button" class="small-btn" data-depo-button="single" title="Depo Seç">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="11" cy="11" r="6" stroke="currentColor" stroke-width="2"/>
                                                    <line x1="16" y1="16" x2="21" y2="21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="offer-header-row">
                                <div class="form-group">
                                    <label for="tarih">Tarih</label>
                                    <input class="header-input short" type="date" id="tarih" name="tarih" value="{{ optional($fiche->tarih)->format('Y-m-d') }}">
                                </div>

                                @if($type === 'depo_transfer')
                                    <div class="form-group">
                                        <label>Giriş Depo</label>
                                        <div class="input-with-button">
                                            <span id="giris_depo_kod_label" class="header-value">{{ $fiche->girisDepo?->kod ?? '' }}</span>
                                            <input type="hidden" id="giris_depo_id" name="giris_depo_id" value="{{ old('giris_depo_id', $fiche->giris_depo_id) }}">
                                            <button type="button" class="small-btn" data-depo-button="giris" title="Depo Seç">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="11" cy="11" r="6" stroke="currentColor" stroke-width="2"/>
                                                    <line x1="16" y1="16" x2="21" y2="21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="offer-header-right">
                            <div class="form-group">
                                <label>İşlem Tarihi</label>
                                <input class="header-input" type="text" value="{{ $fiche->islem_tarihi ? $fiche->islem_tarihi->format('d.m.Y H:i') : '' }}" readonly>
                            </div>

                            <div class="form-group">
                                <label>Hazırlayan</label>
                                <input class="header-input" type="text" value="{{ $userDisplay }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="aciklama">Açıklama</label>
                                <textarea class="header-input" id="aciklama" name="aciklama" style="min-height: 72px;">{{ old('aciklama', $fiche->aciklama) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div style="display:flex; justify-content:flex-end; margin-top: 0.75rem;">
                        <button type="button" id="addLineBtn" class="btn-secondary">Satır Ekle</button>
                    </div>

                    <div class="table-wrapper">
                        <table>
                            <thead>
                            <tr>
                                <th>Stok Kod</th>
                                <th>Stok Açıklama</th>
                                <th>Miktar</th>
                                <th style="text-align:right;">İşlem</th>
                            </tr>
                            </thead>
                            <tbody id="linesBody">
                            @forelse(old('lines', $fiche->lines ?? []) as $index => $line)
                                @php
                                    $lineId = is_array($line) ? ($line['id'] ?? '') : ($line->id ?? '');
                                    $stokKod = is_array($line) ? ($line['stokkod'] ?? '') : ($line->stokkod ?? '');
                                    $stokAciklama = is_array($line) ? ($line['stokaciklama'] ?? '') : ($line->stokaciklama ?? '');
                                    $miktar = is_array($line) ? ($line['miktar'] ?? 0) : ($line->miktar ?? 0);
                                @endphp
                                <tr>
                                    <td>
                                        <input type="hidden" name="lines[{{ $index }}][id]" value="{{ $lineId }}">
                                        <input type="text" class="line-input stok-kod" name="lines[{{ $index }}][stokkod]" value="{{ $stokKod }}">
                                    </td>
                                    <td>
                                        <input type="text" class="line-input-wide stok-aciklama" name="lines[{{ $index }}][stokaciklama]" value="{{ $stokAciklama }}">
                                    </td>
                                    <td>
                                        <input type="number" step="0.0001" class="line-input miktar" name="lines[{{ $index }}][miktar]" value="{{ $miktar }}">
                                    </td>
                                    <td style="text-align:right;">
                                        <button type="button" class="btn-danger delete-line">Sil</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td>
                                        <input type="hidden" name="lines[0][id]" value="">
                                        <input type="text" class="line-input stok-kod" name="lines[0][stokkod]" value="">
                                    </td>
                                    <td>
                                        <input type="text" class="line-input-wide stok-aciklama" name="lines[0][stokaciklama]" value="">
                                    </td>
                                    <td>
                                        <input type="number" step="0.0001" class="line-input miktar" name="lines[0][miktar]" value="0">
                                    </td>
                                    <td style="text-align:right;">
                                        <button type="button" class="btn-danger delete-line">Sil</button>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                </form>
            </div>
        </section>
    </main>
</div>

@include('partials.depot-modal', ['depots' => $depots])

<div id="productModal" class="modal-overlay" style="z-index: 60;">
    <div class="modal" style="max-width: 760px;">
        <div class="modal-header">
            <div class="modal-title">Ürün Seç</div>
            <button type="button" class="small-btn" data-modal-close="productModal">✕</button>
        </div>
        <div class="modal-body">
            <table class="modal-table">
                <thead>
                <tr>
                    <th>Stok Kod</th>
                    <th>Stok Açıklama</th>
                </tr>
                </thead>
                <tbody>
                @foreach($products as $product)
                    <tr class="product-row"
                        data-kod="{{ $product->kod }}"
                        data-aciklama="{{ $product->aciklama }}">
                        <td>{{ $product->kod }}</td>
                        <td>{{ $product->aciklama }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="modal-actions">
            <button type="button"
                    data-modal-close="productModal"
                    style="background:none;border:none;padding:0;color:#dc2626;font-weight:700;cursor:pointer;">
                Kapat
            </button>
        </div>
    </div>
</div>

<script src="{{ asset('js/dashboard.js') }}"></script>
<script>
    (function () {
        var ficheType = @json($type);

        var voucherForm = document.getElementById('voucherForm');
        var depotModal = document.getElementById('depotModal');
        var activeDepotTarget = null;

        function openDepotModal(target) {
            activeDepotTarget = target;
            if (depotModal) depotModal.style.display = 'flex';
        }

        function closeDepotModal() {
            if (depotModal) depotModal.style.display = 'none';
            activeDepotTarget = null;
        }

        document.querySelectorAll('[data-modal-close=\"depotModal\"]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                closeDepotModal();
            });
        });

        if (depotModal) {
            depotModal.addEventListener('click', function (e) {
                if (e.target === depotModal) closeDepotModal();
            });
        }

        document.querySelectorAll('[data-depo-button]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var key = this.getAttribute('data-depo-button');
                if (key === 'single') {
                    openDepotModal({
                        input: document.getElementById('depo_id'),
                        label: document.getElementById('depo_kod_label')
                    });
                    return;
                }
                if (key === 'cikis') {
                    openDepotModal({
                        input: document.getElementById('cikis_depo_id'),
                        label: document.getElementById('cikis_depo_kod_label')
                    });
                    return;
                }
                if (key === 'giris') {
                    openDepotModal({
                        input: document.getElementById('giris_depo_id'),
                        label: document.getElementById('giris_depo_kod_label')
                    });
                }
            });
        });

        document.querySelectorAll('.depot-row').forEach(function (row) {
            row.addEventListener('click', function () {
                if (!activeDepotTarget) return;
                var id = this.dataset.id || '';
                var kod = this.dataset.kod || '';
                if (activeDepotTarget.input) activeDepotTarget.input.value = id;
                if (activeDepotTarget.label) activeDepotTarget.label.textContent = kod;
                closeDepotModal();
            });
        });

        if (voucherForm) {
            voucherForm.addEventListener('submit', function (e) {
                if (ficheType === 'depo_transfer') {
                    var cikis = (document.getElementById('cikis_depo_id') || {}).value || '';
                    var giris = (document.getElementById('giris_depo_id') || {}).value || '';
                    if (!cikis.trim() || !giris.trim()) {
                        e.preventDefault();
                        alert('Çıkış Depo ve Giriş Depo seçilmeden kaydedilemez.');
                        return;
                    }
                    if (cikis.trim() === giris.trim()) {
                        e.preventDefault();
                        alert('Çıkış Depo ve Giriş Depo aynı olamaz.');
                        return;
                    }
                } else {
                    var depo = (document.getElementById('depo_id') || {}).value || '';
                    if (!depo.trim()) {
                        e.preventDefault();
                        alert('Depo seçilmeden kaydedilemez.');
                    }
                }
            });
        }

        var linesBody = document.getElementById('linesBody');
        var addLineBtn = document.getElementById('addLineBtn');
        var index = linesBody ? linesBody.querySelectorAll('tr').length : 0;

        if (addLineBtn && linesBody) {
            addLineBtn.addEventListener('click', function () {
                var tr = document.createElement('tr');
                tr.innerHTML =
                    '<td>' +
                    '<input type=\"hidden\" name=\"lines[' + index + '][id]\" value=\"\">' +
                    '<input type=\"text\" class=\"line-input stok-kod\" name=\"lines[' + index + '][stokkod]\" value=\"\">' +
                    '</td>' +
                    '<td><input type=\"text\" class=\"line-input-wide stok-aciklama\" name=\"lines[' + index + '][stokaciklama]\" value=\"\"></td>' +
                    '<td><input type=\"number\" step=\"0.0001\" class=\"line-input miktar\" name=\"lines[' + index + '][miktar]\" value=\"0\"></td>' +
                    '<td style=\"text-align:right;\"><button type=\"button\" class=\"btn-danger delete-line\">Sil</button></td>';
                linesBody.appendChild(tr);
                index++;
            });
        }

        if (linesBody) {
            linesBody.addEventListener('click', function (e) {
                if (e.target && e.target.classList.contains('delete-line')) {
                    var row = e.target.closest('tr');
                    if (row && confirm('Bu satırı silmek istiyor musunuz?')) {
                        row.remove();
                    }
                }
            });
        }

        var productModal = document.getElementById('productModal');
        var currentRow = null;

        function openProductModal(row) {
            currentRow = row;
            if (productModal) productModal.style.display = 'flex';
        }

        function closeProductModal() {
            if (productModal) productModal.style.display = 'none';
            currentRow = null;
        }

        document.querySelectorAll('[data-modal-close=\"productModal\"]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                closeProductModal();
            });
        });

        if (productModal) {
            productModal.addEventListener('click', function (e) {
                if (e.target === productModal) closeProductModal();
            });
        }

        document.querySelectorAll('.product-row').forEach(function (row) {
            row.addEventListener('click', function () {
                if (!currentRow) return;
                var kod = this.dataset.kod || '';
                var aciklama = this.dataset.aciklama || '';
                var kodEl = currentRow.querySelector('.stok-kod');
                var acikEl = currentRow.querySelector('.stok-aciklama');
                if (kodEl) kodEl.value = kod;
                if (acikEl) acikEl.value = aciklama;
                closeProductModal();
            });
        });

        if (linesBody) {
            linesBody.addEventListener('dblclick', function (e) {
                if (e.target && e.target.classList && (e.target.classList.contains('stok-kod') || e.target.classList.contains('stok-aciklama'))) {
                    var row = e.target.closest('tr');
                    if (row) openProductModal(row);
                }
            });
        }
    })();
</script>
</body>
</html>
