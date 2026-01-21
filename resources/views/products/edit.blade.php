<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Düzenle - NomaEnerji</title>
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
            z-index: 2000;
        }
        .modal {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(15, 23, 42, 0.25);
            max-width: 900px;
            width: calc(100vw - 32px);
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
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        .modal-title {
            font-size: 0.95rem;
            font-weight: 600;
        }
        .modal-body {
            padding: 1rem;
            overflow: auto;
        }
        .modal-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }
        .modal-table th,
        .modal-table td {
            padding: 0.6rem 0.5rem;
            border-bottom: 1px solid #eef2f7;
        }
        .modal-table th {
            text-align: left;
            color: #6b7280;
            font-weight: 600;
        }
        .modal-table tr:hover {
            background: #f8fafc;
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    @include('partials.sidebar', ['active' => 'products'])

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
                        <path d="M15 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                Ürün Düzenle
            </div>
            <div class="user-profile" style="display:flex;align-items:center;gap:1rem;">
                <div class="user-avatar">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                    @csrf
                    <button type="submit" title="Çıkış Yap" style="background:none;border:none;color:#94a3b8;cursor:pointer;padding:0.5rem;display:flex;align-items:center;justify-content:center;transition:color 0.2s;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </form>
            </div>
        </header>

        <section class="content-section">
            <div class="form-page-card">
                <form action="{{ route('products.update', $product) }}" method="POST" class="form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="kod">Kod</label>
                            <input type="text" id="kod" name="kod" value="{{ old('kod', $product->kod) }}" required>
                            @error('kod')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="aciklama">İsim / Açıklama</label>
                            <input type="text" id="aciklama" name="aciklama" value="{{ old('aciklama', $product->aciklama) }}" required>
                            @error('aciklama')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="kategori_id">Kategori</label>
                            <select id="kategori_id" name="kategori_id">
                                <option value="">Kategori seçin</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ (string)old('kategori_id', $product->kategori_id) === (string)$category->id ? 'selected' : '' }}>
                                        {{ $category->ad }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="urun_alt_grup_id">Alt Grup</label>
                                <select id="urun_alt_grup_id" name="urun_alt_grup_id">
                                    <option value="">Alt grup seçin</option>
                                </select>
                                @error('urun_alt_grup_id')<div class="form-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="urun_detay_grup_id">Detay Grup</label>
                                <select id="urun_detay_grup_id" name="urun_detay_grup_id">
                                    <option value="">Detay grup seçin</option>
                                </select>
                                @error('urun_detay_grup_id')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="satis_fiyat">Satış Fiyatı</label>
                                <div style="display:flex;gap:0.5rem;align-items:center;">
                                    <input type="text" id="satis_fiyat" name="satis_fiyat" value="{{ old('satis_fiyat', $product->satis_fiyat) }}" required style="flex:1 1 auto;width:auto;">
                                    @php($satisDoviz = old('satis_doviz', $product->satis_doviz ?? 'TL'))
                                    <select id="satis_doviz" name="satis_doviz" aria-label="Döviz" style="flex:0 0 90px;min-width:90px;width:90px;">
                                        <option value="TL" @selected($satisDoviz === 'TL')>TL</option>
                                        <option value="USD" @selected($satisDoviz === 'USD')>USD</option>
                                        <option value="EUR" @selected($satisDoviz === 'EUR')>EUR</option>
                                    </select>
                                </div>
                                @error('satis_fiyat')<div class="form-error">{{ $message }}</div>@enderror
                                @error('satis_doviz')<div class="form-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="kdv_oran">KDV Oranı (%)</label>
                                <input type="text" id="kdv_oran" name="kdv_oran" value="{{ old('kdv_oran', $product->kdv_oran) }}" required>
                                @error('kdv_oran')<div class="form-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group" style="display:flex;align-items:center;gap:0.5rem;margin-top:1.6rem;">
                                <label for="pasif">Pasif</label>
                                <input type="checkbox" id="pasif" name="pasif" value="1" {{ old('pasif', $product->pasif) ? 'checked' : '' }}>
                            </div>

                            <div class="form-group" style="display:flex;align-items:center;gap:0.5rem;margin-top:1.6rem;">
                                <label for="multi">Takım</label>
                                <input type="checkbox" id="multi" name="multi" value="1" {{ old('multi', $product->multi) ? 'checked' : '' }}>
                            </div>

                            <div class="form-group" style="display:flex;align-items:center;gap:0.5rem;margin-top:1.6rem;">
                                <label for="montaj">Montaj</label>
                                <input type="checkbox" id="montaj" name="montaj" value="1" {{ old('montaj', $product->montaj) ? 'checked' : '' }}>
                            </div>
                        </div>

                        @php($recipe = old('recipe', $recipeItems ?? []))
                        @php($stockById = ($stockCards ?? collect())->keyBy('id'))
                        <div id="recipeSection" class="form-group" style="grid-column:1 / -1;display:none;border:1px solid #e5e7eb;border-radius:12px;padding:1rem;">
                            <div style="display:flex;align-items:center;justify-content:space-between;gap:0.75rem;flex-wrap:wrap;margin-bottom:0.75rem;">
                                <div style="font-size:1rem;font-weight:600;">Reçete</div>
                                <button type="button" id="recipeAddBtn" class="form-header-btn save" style="padding:0.35rem 0.9rem;font-size:0.85rem;">Satır Ekle</button>
                            </div>
                            @error('recipe')<div class="form-error" style="margin-bottom:0.5rem;">{{ $message }}</div>@enderror
                            <div style="overflow-x:auto;">
                                <table style="width:100%;border-collapse:collapse;font-size:0.9rem;">
                                    <thead>
                                    <tr>
                                        <th style="width:200px;text-align:left;padding:0.6rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">Stok Kod</th>
                                        <th style="text-align:left;padding:0.6rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">Stok Açıklama</th>
                                        <th style="width:140px;text-align:right;padding:0.6rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">Miktar</th>
                                        <th style="width:80px;text-align:right;padding:0.6rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">İşlem</th>
                                    </tr>
                                    </thead>
                                    <tbody id="recipeBody">
                                    @foreach($recipe as $i => $row)
                                        @php($sid = (int) ($row['stok_urun_id'] ?? 0))
                                        @php($stok = $stockById->get($sid))
                                        @php($kod = $row['kod'] ?? ($stok ? $stok->kod : ''))
                                        @php($aciklama = $row['aciklama'] ?? ($stok ? $stok->aciklama : ''))
                                        <tr data-stok-id="{{ $sid }}">
                                            <td style="padding:0.5rem 0.5rem;font-weight:600;">
                                                <input type="hidden" class="recipe-stok-id" name="recipe[{{ $i }}][stok_urun_id]" value="{{ $sid }}">
                                                <span class="recipe-kod">{{ $kod }}</span>
                                            </td>
                                            <td style="padding:0.5rem 0.5rem;">
                                                <span class="recipe-aciklama">{{ $aciklama }}</span>
                                            </td>
                                            <td style="padding:0.5rem 0.5rem;text-align:right;">
                                                <input type="number" step="0.001" min="0" class="recipe-miktar" name="recipe[{{ $i }}][miktar]" value="{{ $row['miktar'] ?? 0 }}" style="text-align:right;">
                                            </td>
                                            <td style="padding:0.5rem 0.5rem;text-align:right;">
                                                <button type="button" class="recipe-delete-btn" style="background:none;border:none;color:#ef4444;font-size:0.85rem;cursor:pointer;">Sil</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="prm1">Prm1</label>
                                <input type="text" id="prm1" name="prm1" value="{{ old('prm1', $product->prm1) }}">
                                @error('prm1')<div class="form-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="prm2">Prm2</label>
                                <input type="text" id="prm2" name="prm2" value="{{ old('prm2', $product->prm2) }}">
                                @error('prm2')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="prm3">Parametre 3</label>
                                @php($selectedPrm3 = old('prm3', $product->prm3))
                                <select id="prm3" name="prm3">
                                    <option value="">Seçiniz</option>
                                    @foreach(($stockParameters3 ?? []) as $value)
                                        <option value="{{ $value }}" {{ (string)$selectedPrm3 === (string)$value ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('prm3')<div class="form-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="prm4">Parametre 4</label>
                                @php($selectedPrm4 = old('prm4', $product->prm4))
                                <select id="prm4" name="prm4">
                                    <option value="">Seçiniz</option>
                                    @foreach(($stockParameters4 ?? []) as $value)
                                        <option value="{{ $value }}" {{ (string)$selectedPrm4 === (string)$value ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('prm4')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="fatura_kodu">Fatura Kodu</label>
                                <input type="text" id="fatura_kodu" name="fatura_kodu" value="{{ old('fatura_kodu', $product->fatura_kodu) }}">
                                @error('fatura_kodu')<div class="form-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="marka">Marka</label>
                                <input type="text" id="marka" name="marka" value="{{ old('marka', $product->marka) }}">
                                @error('marka')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="resim">Resim</label>
                            <div id="imageDropzoneEdit" style="border:1px dashed #d1d5db;border-radius:12px;padding:1rem;display:flex;align-items:center;justify-content:center;flex-direction:column;cursor:pointer;text-align:center;color:#6b7280;">
                                @if($product->resim_yolu)
                                    <img src="{{ asset($product->resim_yolu) }}" alt="{{ $product->kod }}" style="max-width:120px;max-height:120px;border-radius:12px;margin-bottom:0.5rem;object-fit:cover;">
                                @endif
                                <span style="font-size:0.9rem;margin-bottom:0.25rem;">Yeni resim için çift tıklayın</span>
                                <span style="font-size:0.8rem;">veya dosyayı bu alana sürükleyip bırakın</span>
                            </div>
                            <input type="file" id="resim" name="resim" accept="image/*" style="display:none;">
                            @error('resim')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div style="margin-top:1.5rem;display:flex;justify-content:flex-end;gap:0.75rem;">
                        <a href="{{ route('products.index') }}" class="form-header-btn cancel" style="text-decoration:none;">İptal</a>
                        <button type="submit" class="form-header-btn save">Güncelle</button>
                    </div>
                </form>
            </div>
        </section>
    </main>
</div>
<script src="{{ asset('js/dashboard.js') }}"></script>

<div id="recipeProductModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Stok Kart Seç</div>
            <div style="display:flex;gap:0.5rem;align-items:center;flex-wrap:wrap;margin-left:auto;">
                <input id="recipeProductSearch" type="text" placeholder="Ara (Stok Kod / Açıklama)" style="padding:0.35rem 0.5rem;font-size:0.9rem;min-width:260px;">
                <button type="button" class="small-btn" id="recipeProductOk">Tamam</button>
                <button type="button" class="small-btn" id="recipeProductCancel">Vazgeç</button>
            </div>
        </div>
        <div class="modal-body">
            <table class="modal-table" id="recipeProductTable">
                <thead>
                <tr>
                    <th style="width:50px;">Seç</th>
                    <th style="width:200px;">Stok Kod</th>
                    <th>Stok Açıklama</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    (function () {
        var groupSelect = document.getElementById('kategori_id');
        var subGroupSelect = document.getElementById('urun_alt_grup_id');
        var detailGroupSelect = document.getElementById('urun_detay_grup_id');

        if (!groupSelect || !subGroupSelect || !detailGroupSelect) {
            return;
        }

        var subGroupsByGroup = @json($subGroupsByGroup ?? []);
        var detailGroupsBySubGroup = @json($detailGroupsBySubGroup ?? []);

        var selectedSubGroupId = @json((string)old('urun_alt_grup_id', $product->urun_alt_grup_id));
        var selectedDetailGroupId = @json((string)old('urun_detay_grup_id', $product->urun_detay_grup_id));

        function setOptions(selectEl, items, placeholder) {
            selectEl.innerHTML = '';

            var placeholderOpt = document.createElement('option');
            placeholderOpt.value = '';
            placeholderOpt.textContent = placeholder;
            selectEl.appendChild(placeholderOpt);

            (items || []).forEach(function (it) {
                var opt = document.createElement('option');
                opt.value = String(it.id);
                opt.textContent = it.ad;
                selectEl.appendChild(opt);
            });
        }

        function hasId(items, id) {
            return (items || []).some(function (it) {
                return String(it.id) === String(id);
            });
        }

        function refreshDetailGroups() {
            var subGroupId = subGroupSelect.value || '';
            var details = detailGroupsBySubGroup[subGroupId] || [];

            setOptions(detailGroupSelect, details, 'Detay grup seçin');

            if (selectedDetailGroupId && hasId(details, selectedDetailGroupId)) {
                detailGroupSelect.value = String(selectedDetailGroupId);
            } else {
                detailGroupSelect.value = '';
            }

            selectedDetailGroupId = '';
        }

        function refreshSubGroups() {
            var groupId = groupSelect.value || '';
            var subs = subGroupsByGroup[groupId] || [];

            setOptions(subGroupSelect, subs, 'Alt grup seçin');

            if (selectedSubGroupId && hasId(subs, selectedSubGroupId)) {
                subGroupSelect.value = String(selectedSubGroupId);
            } else {
                subGroupSelect.value = '';
            }

            selectedSubGroupId = '';
            refreshDetailGroups();
        }

        groupSelect.addEventListener('change', function () {
            selectedSubGroupId = '';
            selectedDetailGroupId = '';
            refreshSubGroups();
        });

        subGroupSelect.addEventListener('change', function () {
            selectedDetailGroupId = '';
            refreshDetailGroups();
        });

        refreshSubGroups();
    })();

    (function () {
        var dropzone = document.getElementById('imageDropzoneEdit');
        var fileInput = document.getElementById('resim');

        if (!dropzone || !fileInput) {
            return;
        }

        function openFileDialog() {
            fileInput.click();
        }

        dropzone.ondblclick = function () {
            openFileDialog();
        };

        dropzone.addEventListener('dragover', function (e) {
            e.preventDefault();
            e.stopPropagation();
            dropzone.style.backgroundColor = '#f9fafb';
        });

        dropzone.addEventListener('dragleave', function (e) {
            e.preventDefault();
            e.stopPropagation();
            dropzone.style.backgroundColor = 'transparent';
        });

        dropzone.addEventListener('drop', function (e) {
            e.preventDefault();
            e.stopPropagation();
            dropzone.style.backgroundColor = 'transparent';

            if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0]) {
                fileInput.files = e.dataTransfer.files;
            }
        });
    })();

    (function () {
        var multi = document.getElementById('multi');
        var montaj = document.getElementById('montaj');

        if (!multi || !montaj) return;

        multi.addEventListener('change', function () {
            if (multi.checked) {
                montaj.checked = false;
            }
        });

        montaj.addEventListener('change', function () {
            if (montaj.checked) {
                multi.checked = false;
            }
        });
    })();

    (function () {
        var multi = document.getElementById('multi');
        var recipeSection = document.getElementById('recipeSection');
        var recipeBody = document.getElementById('recipeBody');
        var addBtn = document.getElementById('recipeAddBtn');

        var modal = document.getElementById('recipeProductModal');
        var modalBody = document.querySelector('#recipeProductTable tbody');
        var modalSearch = document.getElementById('recipeProductSearch');
        var modalOk = document.getElementById('recipeProductOk');
        var modalCancel = document.getElementById('recipeProductCancel');

        var stockCards = @json($stockCards ?? []);

        function esc(s) {
            return (s == null ? '' : String(s))
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/\"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function recipeSelectedIds() {
            var ids = new Set();
            if (!recipeBody) return ids;
            recipeBody.querySelectorAll('tr[data-stok-id]').forEach(function (tr) {
                var id = (tr.getAttribute('data-stok-id') || '').toString().trim();
                if (id) ids.add(id);
            });
            return ids;
        }

        function renumberRecipeRows() {
            if (!recipeBody) return;
            var rows = Array.prototype.slice.call(recipeBody.querySelectorAll('tr'));
            rows.forEach(function (tr, idx) {
                var idInput = tr.querySelector('.recipe-stok-id');
                var qtyInput = tr.querySelector('.recipe-miktar');
                if (idInput) idInput.name = 'recipe[' + idx + '][stok_urun_id]';
                if (qtyInput) qtyInput.name = 'recipe[' + idx + '][miktar]';
            });
        }

        function bindRecipeDeletes() {
            if (!recipeBody) return;
            recipeBody.querySelectorAll('.recipe-delete-btn').forEach(function (btn) {
                btn.onclick = function () {
                    var tr = btn.closest('tr');
                    if (tr && tr.parentNode) tr.parentNode.removeChild(tr);
                    renumberRecipeRows();
                };
            });
        }

        function createRecipeRow(p, qty) {
            var tr = document.createElement('tr');
            tr.setAttribute('data-stok-id', String(p.id));
            tr.innerHTML =
                '<td style="padding:0.5rem 0.5rem;font-weight:600;">' +
                '<input type="hidden" class="recipe-stok-id" value="' + esc(p.id) + '">' +
                '<span class="recipe-kod">' + esc(p.kod) + '</span>' +
                '</td>' +
                '<td style="padding:0.5rem 0.5rem;"><span class="recipe-aciklama">' + esc(p.aciklama) + '</span></td>' +
                '<td style="padding:0.5rem 0.5rem;text-align:right;"><input type="number" step="0.001" min="0" class="recipe-miktar" value="' + esc(qty != null ? qty : 0) + '" style="text-align:right;"></td>' +
                '<td style="padding:0.5rem 0.5rem;text-align:right;"><button type="button" class="recipe-delete-btn" style="background:none;border:none;color:#ef4444;font-size:0.85rem;cursor:pointer;">Sil</button></td>';
            return tr;
        }

        function renderModalRows() {
            if (!modalBody) return;
            var q = ((modalSearch && modalSearch.value) ? modalSearch.value : '').toString().trim().toLowerCase();
            var selected = recipeSelectedIds();
            modalBody.innerHTML = '';

            (Array.isArray(stockCards) ? stockCards : []).forEach(function (p) {
                var id = p && p.id != null ? String(p.id) : '';
                var kod = p && p.kod != null ? String(p.kod) : '';
                var aciklama = p && p.aciklama != null ? String(p.aciklama) : '';
                if (!id) return;
                if (selected.has(id)) return;
                if (q) {
                    var k = kod.toLowerCase();
                    var a = aciklama.toLowerCase();
                    if (k.indexOf(q) === -1 && a.indexOf(q) === -1) return;
                }
                var tr = document.createElement('tr');
                tr.innerHTML =
                    '<td style="width:50px;text-align:center;"><input type="checkbox" data-id="' + esc(id) + '"></td>' +
                    '<td style="font-weight:600;">' + esc(kod) + '</td>' +
                    '<td>' + esc(aciklama) + '</td>';
                modalBody.appendChild(tr);
            });
        }

        function toggleModal(show) {
            if (!modal) return;
            modal.style.display = show ? 'flex' : 'none';
            if (show) {
                if (modalSearch) modalSearch.value = '';
                renderModalRows();
            }
        }

        function setRecipeEnabled(enabled) {
            if (!recipeSection) return;
            recipeSection.style.display = enabled ? 'block' : 'none';
            recipeSection.querySelectorAll('.recipe-stok-id, .recipe-miktar').forEach(function (inp) {
                inp.disabled = !enabled;
            });
            if (addBtn) addBtn.disabled = !enabled;
        }

        if (multi) {
            multi.addEventListener('change', function () {
                setRecipeEnabled(!!multi.checked);
            });
            setRecipeEnabled(!!multi.checked);
        }

        if (addBtn) {
            addBtn.addEventListener('click', function () {
                if (!multi || !multi.checked) return;
                toggleModal(true);
            });
        }

        if (modalSearch) modalSearch.addEventListener('input', renderModalRows);
        if (modalCancel) modalCancel.addEventListener('click', function () { toggleModal(false); });

        if (modalOk) {
            modalOk.addEventListener('click', function () {
                if (!recipeBody) {
                    toggleModal(false);
                    return;
                }
                var pickedIds = [];
                modalBody.querySelectorAll('input[type="checkbox"]:checked').forEach(function (cb) {
                    pickedIds.push((cb.getAttribute('data-id') || '').toString());
                });
                if (!pickedIds.length) {
                    toggleModal(false);
                    return;
                }

                var byId = {};
                (Array.isArray(stockCards) ? stockCards : []).forEach(function (p) {
                    if (p && p.id != null) byId[String(p.id)] = p;
                });

                pickedIds.forEach(function (id) {
                    var p = byId[String(id)];
                    if (!p) return;
                    recipeBody.appendChild(createRecipeRow(p, 1));
                });

                bindRecipeDeletes();
                renumberRecipeRows();
                toggleModal(false);
            });
        }

        bindRecipeDeletes();
        renumberRecipeRows();
    })();
</script>
</body>
</html>
