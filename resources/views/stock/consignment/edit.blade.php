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

        #proje_id.header-input {
            max-width: 180px;
        }

        .header-value {
            color: #000000;
            font-size: 0.9rem;
            line-height: 1.2;
        }

        .firm-display {
            max-width: 260px;
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

        .btn-primary {
            padding: 0.55rem 1.1rem;
            border-radius: 999px;
            border: none;
            background-color: #2563eb;
            color: #ffffff;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
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
        }

        .line-input-wide {
            width: 220px;
        }

        .btn-danger {
            background: none;
            border: none;
            color: #ef4444;
            cursor: pointer;
        }

        .modal-title {
            font-size: 1rem;
            font-weight: 600;
            color: #111827;
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
            <div style="margin-left:auto; display:flex; align-items:center; gap:0.5rem;">
                <a href="{{ $type === 'cikis' ? route('stock.consignment-out.index') : route('stock.consignment-in.index') }}"
                   class="btn btn-cancel" style="margin-left:0.75rem;">İptal</a>
                <button type="submit" form="consignmentForm" class="btn btn-save">Kaydet</button>
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

                <form id="consignmentForm" method="POST"
                      action="{{ ($isNew ?? false)
                            ? ($type === 'cikis' ? route('stock.consignment-out.store') : route('stock.consignment-in.store'))
                            : ($type === 'cikis' ? route('stock.consignment-out.update', $fiche) : route('stock.consignment-in.update', $fiche)) }}">
                    @csrf

                    <div class="offer-header">
                        <div class="offer-header-left">
                            <div class="offer-header-row">
                                <div class="form-group">
                                    <label>Fiş No</label>
                                    <input class="header-input short" type="text" value="{{ $fiche->fis_no }}" readonly>
                                </div>

                                <div class="form-group">
                                    <label>Depo</label>
                                    <div class="input-with-button">
                                        <span id="depo_kod_label" class="header-value">{{ $fiche->depo?->kod ?? '' }}</span>
                                        <input type="hidden" id="depo_id" name="depo_id" value="{{ old('depo_id', $fiche->depo_id) }}">
                                        <button type="button" class="small-btn" id="btnDepotSearch" title="Depo Seç">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="11" cy="11" r="6" stroke="currentColor" stroke-width="2"/>
                                                <line x1="16" y1="16" x2="21" y2="21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="offer-header-row">
                                <div class="form-group">
                                    <label for="tarih">Tarih</label>
                                    <input class="header-input short" type="date" id="tarih" name="tarih" value="{{ optional($fiche->tarih)->format('Y-m-d') }}">
                                </div>

                                <div class="form-group">
                                    <label for="teslim_tarihi">Teslim Tarihi</label>
                                    <input class="header-input short" type="date" id="teslim_tarihi" name="teslim_tarihi" value="{{ optional($fiche->teslim_tarihi)->format('Y-m-d') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="carikod">Firma</label>
                                <div class="input-with-button">
                                    <div class="header-value firm-display" style="flex:1; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                        <span id="firma_kod_label">{{ old('carikod', $fiche->carikod) }}</span>
                                        <span style="color:#6b7280;"> / </span>
                                        <span id="firma_aciklama_label">{{ old('cariaciklama', $fiche->cariaciklama) }}</span>
                                        <input id="carikod" name="carikod" type="hidden" value="{{ old('carikod', $fiche->carikod) }}">
                                        <input id="cariaciklama" name="cariaciklama" type="hidden" value="{{ old('cariaciklama', $fiche->cariaciklama) }}">
                                    </div>
                                    <button type="button" class="small-btn" id="btnCariSearch" title="Firma Seç">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="11" cy="11" r="6" stroke="currentColor" stroke-width="2"/>
                                            <line x1="16" y1="16" x2="21" y2="21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="proje_id">Proje</label>
                                <select class="header-input" id="proje_id" name="proje_id">
                                    <option value="">-</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ (string) old('proje_id', $fiche->proje_id) === (string) $project->id ? 'selected' : '' }}>
                                            {{ $project->kod }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="offer-header-right">
                            <div class="form-group">
                                <label for="durum">Durum</label>
                                <select class="header-input" id="durum" name="durum">
                                    <option value="">-</option>
                                    <option value="açık" {{ old('durum', $fiche->durum) === 'açık' ? 'selected' : '' }}>Açık</option>
                                    <option value="kapalı" {{ old('durum', $fiche->durum) === 'kapalı' ? 'selected' : '' }}>Kapalı</option>
                                </select>
                            </div>

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
                                <th>İade Miktar</th>
                                <th>Kalan</th>
                                <th>Durum</th>
                                <th style="text-align:right;">İşlem</th>
                            </tr>
                            </thead>
                            <tbody id="linesBody">
                            @forelse($fiche->lines as $index => $line)
                                @php($kalan = ((float) $line->miktar) - ((float) $line->iade_miktar))
                                <tr>
                                    <td>
                                        <input type="hidden" name="lines[{{ $index }}][id]" value="{{ $line->id }}">
                                        <input type="text" class="line-input stok-kod" name="lines[{{ $index }}][stokkod]" value="{{ $line->stokkod }}">
                                    </td>
                                    <td>
                                        <input type="text" class="line-input-wide stok-aciklama" name="lines[{{ $index }}][stokaciklama]" value="{{ $line->stokaciklama }}">
                                    </td>
                                    <td>
                                        <input type="number" step="0.0001" class="line-input miktar" name="lines[{{ $index }}][miktar]" value="{{ $line->miktar }}">
                                    </td>
                                    <td>
                                        <span class="iade-miktar" style="display:inline-block; min-width: 80px;">{{ number_format((float) $line->iade_miktar, 4, '.', '') }}</span>
                                    </td>
                                    <td class="kalan">{{ number_format($kalan, 4, '.', '') }}</td>
                                    <td>
                                        <select class="line-input" name="lines[{{ $index }}][durum]">
                                            @php($durum = old('lines.' . $index . '.durum', $line->durum))
                                            <option value="">-</option>
                                            <option value="açık" {{ $durum === 'açık' ? 'selected' : '' }}>Açık</option>
                                            <option value="kısmi iade" {{ $durum === 'kısmi iade' ? 'selected' : '' }}>Kısmi İade</option>
                                            <option value="kapalı" {{ $durum === 'kapalı' ? 'selected' : '' }}>Kapalı</option>
                                        </select>
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
                                    <td>
                                        <span class="iade-miktar" style="display:inline-block; min-width: 80px;">0.0000</span>
                                    </td>
                                    <td class="kalan">0.0000</td>
                                    <td>
                                        <select class="line-input" name="lines[0][durum]">
                                            <option value="">-</option>
                                            <option value="açık" selected>Açık</option>
                                            <option value="kısmi iade">Kısmi İade</option>
                                            <option value="kapalı">Kapalı</option>
                                        </select>
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

@include('partials.firm-modal', ['firms' => $firms])
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
@include('partials.firm-modal-script')
@include('partials.depot-modal-script')
<script>
    (function () {
        var form = document.getElementById('consignmentForm');
        var carikodHidden = document.getElementById('carikod');
        var firmaKodLabel = document.getElementById('firma_kod_label');
        var firmaAciklamaLabel = document.getElementById('firma_aciklama_label');
        var cariaciklamaHidden = document.getElementById('cariaciklama');
        var depotIdHidden = document.getElementById('depo_id');

        if (form) {
            form.addEventListener('submit', function (e) {
                var kod = (carikodHidden && carikodHidden.value ? carikodHidden.value : '').trim();
                if (!kod) {
                    e.preventDefault();
                    alert('Firma seçilmeden kaydedilemez.');
                    return;
                }

                var depoId = (depotIdHidden && depotIdHidden.value ? depotIdHidden.value : '').trim();
                if (!depoId) {
                    e.preventDefault();
                    alert('Depo seçilmeden kaydedilemez.');
                    return;
                }
            });
        }

        var linesBody = document.getElementById('linesBody');
        var addLineBtn = document.getElementById('addLineBtn');
        var index = linesBody.querySelectorAll('tr').length;

        function recalcRow(row) {
            var miktar = parseFloat((row.querySelector('.miktar') || {}).value || '0') || 0;
            var iade = parseFloat((row.querySelector('.iade-miktar') || {}).textContent || '0') || 0;
            var kalan = miktar - iade;
            var cell = row.querySelector('.kalan');
            if (cell) {
                cell.textContent = kalan.toFixed(4);
            }
        }

        function bindRow(row) {
            var miktarEl = row.querySelector('.miktar');
            if (miktarEl) miktarEl.addEventListener('input', function () { recalcRow(row); });
        }

        linesBody.querySelectorAll('tr').forEach(bindRow);

        addLineBtn.addEventListener('click', function () {
            var tr = document.createElement('tr');
            tr.innerHTML =
                '<td>' +
                '<input type="hidden" name="lines[' + index + '][id]" value="">' +
                '<input type="text" class="line-input stok-kod" name="lines[' + index + '][stokkod]" value="">' +
                '</td>' +
                '<td><input type="text" class="line-input-wide stok-aciklama" name="lines[' + index + '][stokaciklama]" value=""></td>' +
                '<td><input type="number" step="0.0001" class="line-input miktar" name="lines[' + index + '][miktar]" value="0"></td>' +
                '<td><span class="iade-miktar" style="display:inline-block; min-width: 80px;">0.0000</span></td>' +
                '<td class="kalan">0.0000</td>' +
                '<td>' +
                '<select class="line-input" name="lines[' + index + '][durum]">' +
                '<option value=\"\">-</option>' +
                '<option value=\"açık\" selected>Açık</option>' +
                '<option value=\"kısmi iade\">Kısmi İade</option>' +
                '<option value=\"kapalı\">Kapalı</option>' +
                '</select>' +
                '</td>' +
                '<td style="text-align:right;"><button type="button" class="btn-danger delete-line">Sil</button></td>';
            linesBody.appendChild(tr);
            bindRow(tr);
            index++;
        });

        linesBody.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('delete-line')) {
                var row = e.target.closest('tr');
                if (row && confirm('Bu satırı silmek istiyor musunuz?')) {
                    row.remove();
                }
            }
        });

        var modal = document.getElementById('productModal');
        var currentRow = null;

        function openProductModal(row) {
            currentRow = row;
            modal.style.display = 'flex';
        }

        function closeProductModal() {
            modal.style.display = 'none';
            currentRow = null;
        }

        document.querySelectorAll('[data-modal-close="productModal"]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                closeProductModal();
            });
        });

        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeProductModal();
        });

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

        linesBody.addEventListener('dblclick', function (e) {
            if (e.target && e.target.classList && (e.target.classList.contains('stok-kod') || e.target.classList.contains('stok-aciklama'))) {
                var row = e.target.closest('tr');
                if (row) openProductModal(row);
            }
        });
    })();
</script>
</body>
</html>
