@php
    $selectedFirm = null;
    $oldFirmId = old('firm_id', $fiyatListesi->firm_id);
    if ($oldFirmId) {
        $selectedFirm = $firms->firstWhere('id', (int) $oldFirmId);
    }
    $firmKod = $selectedFirm ? (string) $selectedFirm->carikod : '';
    $firmAciklama = $selectedFirm ? (string) $selectedFirm->cariaciklama : '';

    $initialLines = old('lines');
    if (!is_array($initialLines)) {
        $initialLines = $fiyatListesi->detaylar->map(function ($d) {
            return [
                'urun_id' => $d->urun_id,
                'stok_kod' => $d->stok_kod,
                'stok_aciklama' => $d->stok_aciklama,
                'birim_fiyat' => $d->birim_fiyat,
                'doviz' => $d->doviz ?? 'TL',
            ];
        })->values()->all();
    }
@endphp
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Fiyat Listesi Düzenle - NomaEnerji</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        .offer-card {
            width: 100%;
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.06);
            padding: 1.5rem 1.25rem;
        }
        .offer-header {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            margin-bottom: 0.9rem;
            align-items: flex-start;
        }
        .offer-header-left {
            flex: 1 1 0;
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }
        .offer-header-right {
            flex: 0 0 320px;
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.15rem;
        }
        .form-group label {
            font-size: 0.8rem;
            font-weight: 500;
            color: #2563eb;
        }
        .offer-header .form-group {
            flex-direction: row;
            align-items: center;
            gap: 0.5rem;
        }
        .offer-header .form-group label {
            font-weight: 600;
            min-width: 140px;
            margin-bottom: 0;
        }
        .header-input {
            border: none !important;
            background: transparent !important;
            padding: 0 !important;
            border-radius: 0 !important;
            outline: none !important;
            width: auto !important;
        }
        .header-input-sm {
            max-width: 240px;
        }
        .offer-header-right .form-group label {
            min-width: 120px;
        }
        .offer-header-right .form-group input {
            max-width: 160px;
        }
        .input-with-button {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .small-btn {
            border-radius: 999px;
            border: none;
            background: transparent;
            color: #2563eb;
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .lines-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 0.75rem 0 0.5rem;
        }
        .lines-header #btnAddLine {
            border-radius: 999px;
            border: none;
            background: #e5e7eb;
            padding: 0.4rem 0.9rem;
            font-size: 0.8rem;
            cursor: pointer;
        }
        table.offer-lines {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }
        .offer-lines th,
        .offer-lines td {
            border: 1px solid #e5e7eb;
            padding: 0.5rem;
            vertical-align: middle;
        }
        .offer-lines thead {
            background: #f8fafc;
            color: #374151;
        }
        .offer-lines input,
        .offer-lines select {
            width: 100%;
            border: none;
            outline: none;
            background: transparent;
            font-size: 0.85rem;
        }
        .offer-lines input[type="number"] {
            text-align: right;
        }
        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-top: 1rem;
        }
        .btn {
            border-radius: 999px;
            padding: 0.55rem 1.1rem;
            font-size: 0.85rem;
            border: 1px solid #e5e7eb;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .btn-save {
            background: #16a34a;
            border-color: #16a34a;
            color: #fff;
        }
        .btn-cancel {
            background: #fff;
            color: #111827;
        }
        .line-remove-btn {
            border-radius: 999px;
            border: none;
            background: transparent;
            color: #dc2626;
            width: 34px;
            height: 34px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
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
                Fiyat Listesi Düzenle #{{ $fiyatListesi->id }}
            </div>
        </header>

        <section class="content-section" style="padding: 2rem;">
            <div class="offer-card">
                @if ($errors->any())
                    <div class="alert alert-danger" style="margin-bottom: 1rem;">
                        <ul style="margin:0; padding-left: 1.2rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('price-lists.update', $fiyatListesi) }}" id="priceListForm">
                    @csrf
                    @method('PUT')

                    <div class="offer-header">
                        <div class="offer-header-left">
                            <div class="form-group">
                                <label style="color:#9ca3af;">Tedarikçi Firma:</label>
                                <div class="input-with-button">
                                    <span id="firm_kod_label">{{ old('firm_kod', $firmKod) }}</span>
                                    <span style="opacity:0.7;">/</span>
                                    <span id="firm_aciklama_label">{{ old('firm_aciklama', $firmAciklama) }}</span>
                                    <input id="firm_id" name="firm_id" type="hidden" value="{{ old('firm_id', $fiyatListesi->firm_id) }}">
                                    <button type="button" class="small-btn" id="btnFirmSearch" title="Firma Seç">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M21 21l-4.3-4.3m1.8-5.2a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="hazirlayan" style="color:#9ca3af;">Hazırlayan:</label>
                                <input id="hazirlayan" class="header-input header-input-sm" name="hazirlayan" type="text" value="{{ old('hazirlayan', $fiyatListesi->hazirlayan) }}">
                            </div>
                        </div>

                        <div class="offer-header-right">
                            <div class="form-group">
                                <label for="baslangic_tarihi" style="color:#9ca3af;">Başlangıç Tarihi:</label>
                                <input id="baslangic_tarihi" class="header-input" name="baslangic_tarihi" type="date" value="{{ old('baslangic_tarihi', optional($fiyatListesi->baslangic_tarihi)->toDateString()) }}">
                            </div>
                            <div class="form-group">
                                <label for="bitis_tarihi" style="color:#9ca3af;">Bitiş Tarihi:</label>
                                <input id="bitis_tarihi" class="header-input" name="bitis_tarihi" type="date" required value="{{ old('bitis_tarihi', optional($fiyatListesi->bitis_tarihi)->toDateString()) }}">
                            </div>
                        </div>
                    </div>

                    <div class="lines-header">
                        <div></div>
                        <button type="button" id="btnAddLine">Satır Ekle</button>
                    </div>

                    <table class="offer-lines">
                        <thead>
                        <tr>
                            <th style="width: 15%;">Stok Kod</th>
                            <th>Stok Açıklama</th>
                            <th style="width: 14%;">Birim Fiyat</th>
                            <th style="width: 10%;">Döviz</th>
                            <th style="width: 54px;"></th>
                        </tr>
                        </thead>
                        <tbody id="priceListLinesBody"></tbody>
                    </table>

                    <div class="actions">
                        <a href="{{ route('price-lists.index') }}" class="btn btn-cancel">İptal</a>
                        <button type="submit" class="btn btn-save">Kaydet</button>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <div id="firmModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <div class="modal-title">Firma Seç</div>
                <button type="button" class="small-btn" data-modal-close="firmModal">X</button>
            </div>
            <div class="modal-body">
                <table class="modal-table">
                    <thead>
                    <tr>
                        <th>Cari Kod</th>
                        <th>Cari Açıklama</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($firms as $firm)
                        <tr class="firm-row"
                            data-id="{{ $firm->id }}"
                            data-carikod="{{ $firm->carikod }}"
                            data-cariaciklama="{{ $firm->cariaciklama }}">
                            <td>{{ $firm->carikod }}</td>
                            <td>{{ $firm->cariaciklama }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" data-modal-close="firmModal">Kapat</button>
            </div>
        </div>
    </div>

    <div id="productModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <div class="modal-title">Ürün Seç</div>
                <button type="button" class="small-btn" data-modal-close="productModal">X</button>
            </div>
            <div class="modal-body">
                <table class="modal-table">
                    <thead>
                    <tr>
                        <th>Stok Kod</th>
                        <th>Stok Açıklama</th>
                        <th>Birim Fiyat</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($products as $product)
                        <tr class="product-row"
                            data-id="{{ $product->id }}"
                            data-kod="{{ $product->kod }}"
                            data-aciklama="{{ $product->aciklama }}"
                            data-fiyat="{{ $product->satis_fiyat }}">
                            <td>{{ $product->kod }}</td>
                            <td>{{ $product->aciklama }}</td>
                            <td style="text-align:right;">{{ number_format((float) $product->satis_fiyat, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" data-modal-close="productModal">Kapat</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/dashboard.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var firmModal = document.getElementById('firmModal');
        var productModal = document.getElementById('productModal');
        var btnFirmSearch = document.getElementById('btnFirmSearch');
        var firmIdInput = document.getElementById('firm_id');
        var firmKodLabel = document.getElementById('firm_kod_label');
        var firmAciklamaLabel = document.getElementById('firm_aciklama_label');

        var linesBody = document.getElementById('priceListLinesBody');
        var btnAddLine = document.getElementById('btnAddLine');

        var lineIndex = 0;
        var currentProductRow = null;

        function openModal(modal) {
            if (modal) modal.style.display = 'flex';
        }

        function closeModal(modal) {
            if (modal) modal.style.display = 'none';
        }

        document.querySelectorAll('[data-modal-close]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var id = this.getAttribute('data-modal-close');
                closeModal(document.getElementById(id));
            });
        });

        if (btnFirmSearch && firmModal) {
            btnFirmSearch.addEventListener('click', function () {
                openModal(firmModal);
            });
        }

        if (firmModal) {
            firmModal.addEventListener('click', function (e) {
                if (e.target === firmModal) closeModal(firmModal);
            });
        }

        document.querySelectorAll('.firm-row').forEach(function (row) {
            row.addEventListener('click', function () {
                var id = this.dataset.id || '';
                var carikod = this.dataset.carikod || '';
                var cariaciklama = this.dataset.cariaciklama || '';

                if (firmIdInput) firmIdInput.value = id;
                if (firmKodLabel) firmKodLabel.textContent = carikod;
                if (firmAciklamaLabel) firmAciklamaLabel.textContent = cariaciklama;

                closeModal(firmModal);
            });
        });

        function addLineRow(initial) {
            if (!linesBody) return;

            var idx = lineIndex++;
            var tr = document.createElement('tr');
            tr.innerHTML =
                '<td><input class="line-input stok-kod" autocomplete="off"><input type="hidden" class="line-input urun-id"></td>' +
                '<td><input class="line-input stok-aciklama" autocomplete="off"></td>' +
                '<td><input type="number" step="0.0001" class="line-input birim-fiyat"></td>' +
                '<td><select class="line-input doviz"><option value="TL">TL</option><option value="USD">USD</option><option value="EUR">EUR</option></select></td>' +
                '<td style="text-align:center;">' +
                '<button type="button" class="line-remove-btn" title="Sil" aria-label="Sil">' +
                '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">' +
                '<path d="M3 6h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>' +
                '<path d="M8 6V4h8v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>' +
                '<path d="M6 6l1 16h10l1-16" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>' +
                '<path d="M10 11v6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>' +
                '<path d="M14 11v6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>' +
                '</svg>' +
                '</button>' +
                '</td>';

            var stokKodInput = tr.querySelector('.stok-kod');
            var urunIdInput = tr.querySelector('.urun-id');
            var stokAciklamaInput = tr.querySelector('.stok-aciklama');
            var birimFiyatInput = tr.querySelector('.birim-fiyat');
            var dovizSelect = tr.querySelector('.doviz');

            if (stokKodInput) stokKodInput.name = 'lines[' + idx + '][stok_kod]';
            if (urunIdInput) urunIdInput.name = 'lines[' + idx + '][urun_id]';
            if (stokAciklamaInput) stokAciklamaInput.name = 'lines[' + idx + '][stok_aciklama]';
            if (birimFiyatInput) birimFiyatInput.name = 'lines[' + idx + '][birim_fiyat]';
            if (dovizSelect) dovizSelect.name = 'lines[' + idx + '][doviz]';

            tr.querySelector('.line-remove-btn')?.addEventListener('click', function () {
                tr.remove();
            });

            if (initial) {
                if (urunIdInput && initial.urun_id != null) urunIdInput.value = initial.urun_id;
                if (stokKodInput && initial.stok_kod != null) stokKodInput.value = initial.stok_kod;
                if (stokAciklamaInput && initial.stok_aciklama != null) stokAciklamaInput.value = initial.stok_aciklama;
                if (birimFiyatInput && initial.birim_fiyat != null) birimFiyatInput.value = initial.birim_fiyat;
                if (dovizSelect && initial.doviz != null) dovizSelect.value = initial.doviz;
            } else {
                if (dovizSelect) dovizSelect.value = 'TL';
                if (birimFiyatInput) birimFiyatInput.value = '0';
            }

            linesBody.appendChild(tr);
        }

        if (btnAddLine) {
            btnAddLine.addEventListener('click', function () {
                addLineRow();
            });
        }

        if (linesBody && productModal) {
            linesBody.addEventListener('dblclick', function (e) {
                var target = e.target;
                if (!target || !target.classList) return;
                if (!target.classList.contains('stok-kod') && !target.classList.contains('stok-aciklama')) return;
                currentProductRow = target.closest('tr');
                if (currentProductRow) openModal(productModal);
            });
        }

        if (productModal) {
            productModal.addEventListener('click', function (e) {
                if (e.target === productModal) closeModal(productModal);
            });
        }

        document.querySelectorAll('.product-row').forEach(function (row) {
            row.addEventListener('click', function () {
                if (!currentProductRow) return;

                var kod = this.dataset.kod || '';
                var aciklama = this.dataset.aciklama || '';
                var urunId = this.dataset.id || '';

                var kodInput = currentProductRow.querySelector('.stok-kod');
                var aciklamaInput = currentProductRow.querySelector('.stok-aciklama');
                var fiyatInput = currentProductRow.querySelector('.birim-fiyat');
                var urunIdInput = currentProductRow.querySelector('.urun-id');

                if (kodInput) kodInput.value = kod;
                if (aciklamaInput) aciklamaInput.value = aciklama;
                if (fiyatInput) fiyatInput.value = '0';
                if (urunIdInput) urunIdInput.value = urunId;

                closeModal(productModal);
            });
        });

        var initialLines = @json($initialLines);
        if (Array.isArray(initialLines) && initialLines.length) {
            initialLines.forEach(function (l) { addLineRow(l); });
        }
    });
</script>
</body>
</html>

