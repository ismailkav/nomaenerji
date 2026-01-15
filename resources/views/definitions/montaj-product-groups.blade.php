<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Montaj Urun Grup - NomaEnerji</title>
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
    @include('partials.sidebar', ['active' => 'montaj-product-groups'])

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
                Montaj Urun Grup
            </div>
        </header>

        <section class="content-section" style="padding:2rem;">
            <div class="form-page-card">
                <div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;margin-bottom:1rem;">
                    <div style="display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap;">
                        <h1 style="font-size:1.4rem;font-weight:600;margin:0;">Montaj Urun Grup</h1>
                        <select id="montajGroupSelect" style="min-width:220px;">
                            @foreach($montajGroups as $g)
                                <option value="{{ $g->id }}" {{ (int)$selectedGroupId === (int)$g->id ? 'selected' : '' }}>
                                    {{ $g->kod }}
                                </option>
                            @endforeach
                        </select>
                        <select id="montajProductSelect" style="min-width:220px;">
                            @php($productsForGroup = $productsByGroup->get($selectedGroupId, collect()))
                            @foreach($productsForGroup as $p)
                                <option value="{{ $p['id'] }}" {{ (int)$selectedProductId === (int)$p['id'] ? 'selected' : '' }}>
                                    {{ $p['kod'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" id="addRowsBtn" class="form-header-btn save" style="padding:0.4rem 1rem;font-size:0.9rem;">Satir Ekle</button>
                </div>

                @if($errors->any())
                    <div class="form-error" style="margin-bottom:0.75rem;">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('definitions.montaj-product-groups.save') }}">
                    @csrf
                    <input type="hidden" name="montaj_grup_id" id="montajGroupInput" value="{{ $selectedGroupId }}">
                    <input type="hidden" name="montaj_urun_id" id="montajProductInput" value="{{ $selectedProductId }}">

                    <div class="products-table-wrapper" style="overflow-x:auto;">
                        <table style="width:100%;border-collapse:collapse;font-size:0.9rem;">
                            <thead>
                            <tr>
                                <th style="width:80px;text-align:center;padding:0.6rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">Sira</th>
                                <th style="width:220px;text-align:left;padding:0.6rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">Stok Kod</th>
                                <th style="text-align:left;padding:0.6rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">Stok Aciklama</th>
                                <th style="width:120px;text-align:right;padding:0.6rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">Islem</th>
                            </tr>
                            </thead>
                            <tbody id="montajProductGroupsBody">
                            @forelse($items as $i => $row)
                                <tr>
                                    <td class="sirano-cell" style="padding:0.5rem 0.5rem;text-align:center;color:#6b7280;">{{ (int) ($row->sirano ?? 0) > 0 ? (int) $row->sirano : ($i + 1) }}</td>
                                    <td style="padding:0.5rem 0.5rem;font-weight:600;">
                                        <input type="hidden" name="items[{{ $i }}][id]" value="{{ $row->id }}">
                                        <input type="hidden" class="sirano-input" name="items[{{ $i }}][sirano]" value="{{ (int) ($row->sirano ?? 0) > 0 ? (int) $row->sirano : ($i + 1) }}">
                                        <input type="hidden" class="urun-id-input" name="items[{{ $i }}][urun_id]" value="{{ $row->urun_id }}">
                                        {{ optional($row->urun)->kod }}
                                    </td>
                                    <td style="padding:0.5rem 0.5rem;">
                                        {{ optional($row->urun)->aciklama }}
                                    </td>
                                    <td style="padding:0.5rem 0.5rem;text-align:right;white-space:nowrap;">
                                        <button type="button" class="row-delete-btn" title="Sil" style="background:none;border:none;color:#ef4444;font-size:0.85rem;cursor:pointer;">Sil</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="sirano-cell" style="padding:0.5rem 0.5rem;text-align:center;color:#6b7280;">1</td>
                                    <td style="padding:0.5rem 0.5rem;">
                                        <input type="hidden" name="items[0][id]" value="">
                                        <input type="hidden" class="sirano-input" name="items[0][sirano]" value="1">
                                        <input type="hidden" class="urun-id-input" name="items[0][urun_id]" value="">
                                        <div style="color:#9ca3af;">Stok kart seciniz.</div>
                                    </td>
                                    <td style="padding:0.5rem 0.5rem;color:#9ca3af;">
                                    </td>
                                    <td style="padding:0.5rem 0.5rem;text-align:right;white-space:nowrap;">
                                        <button type="button" class="row-delete-btn" title="Sil" style="background:none;border:none;color:#ef4444;font-size:0.85rem;cursor:pointer;">Sil</button>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div style="margin-top:1.25rem;display:flex;justify-content:flex-end;gap:0.5rem;">
                        <button type="submit" class="form-header-btn save">Kaydet</button>
                    </div>
                </form>
            </div>
        </section>
    </main>
</div>

<div id="stockCardModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header" style="flex-wrap:wrap;">
            <div class="modal-title">Stok Kart Sec</div>
            <div style="display:flex;gap:0.5rem;align-items:center;flex-wrap:wrap;margin-left:auto;">
                <button type="button" class="small-btn" id="stockModalOk">Tamam</button>
                <button type="button" class="small-btn" id="stockModalCancel">Vazgec</button>
                <button type="button" class="small-btn" id="stockModalClose">X</button>
            </div>
            <div style="width:100%;display:flex;gap:0.5rem;align-items:center;flex-wrap:wrap;margin-top:0.6rem;">
                <select id="stockGroupFilter" style="min-width:200px;">
                    <option value="">Stok Ana Grup (Hepsi)</option>
                </select>
                <select id="stockSubGroupFilter" style="min-width:220px;">
                    <option value="">Stok Alt Grup (Hepsi)</option>
                </select>
                <select id="stockDetailGroupFilter" style="min-width:240px;">
                    <option value="">Stok Detay Grup (Hepsi)</option>
                </select>
                <input id="stockSearch" type="text" placeholder="Ara (Stok Kod / Aciklama)..." style="margin-left:auto;padding:0.35rem 0.5rem;font-size:0.9rem;min-width:260px;">
            </div>
        </div>
        <div class="modal-body">
            <table class="modal-table" id="stockCardTable">
                <thead>
                <tr>
                    <th style="width:50px;">Sec</th>
                    <th style="width:200px;">Stok Kod</th>
                    <th>Stok Aciklama</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script src="{{ asset('js/dashboard.js') }}"></script>
    <script>
    (function () {
        var productsByGroup = @json($productsByGroup);
        var stockCards = @json($stockCards);
        var stockGroups = @json($stockGroups);
        var stockSubGroups = @json($stockSubGroups);
        var stockDetailGroups = @json($stockDetailGroups);

        var groupSelect = document.getElementById('montajGroupSelect');
        var productSelect = document.getElementById('montajProductSelect');
        var groupInput = document.getElementById('montajGroupInput');
        var productInput = document.getElementById('montajProductInput');
        var addRowsBtn = document.getElementById('addRowsBtn');
        var tbody = document.getElementById('montajProductGroupsBody');

        var modal = document.getElementById('stockCardModal');
        var modalTableBody = document.querySelector('#stockCardTable tbody');
        var modalClose = document.getElementById('stockModalClose');
        var modalCancel = document.getElementById('stockModalCancel');
        var modalOk = document.getElementById('stockModalOk');
        var modalSearch = document.getElementById('stockSearch');
        var groupFilter = document.getElementById('stockGroupFilter');
        var subGroupFilter = document.getElementById('stockSubGroupFilter');
        var detailGroupFilter = document.getElementById('stockDetailGroupFilter');

        function renumberRows() {
            var rows = tbody ? Array.prototype.slice.call(tbody.querySelectorAll('tr')) : [];
            rows.forEach(function (tr, idx) {
                var no = idx + 1;
                var cell = tr.querySelector('.sirano-cell');
                if (cell) cell.textContent = String(no);
                var input = tr.querySelector('.sirano-input');
                if (input) input.value = String(no);
            });
        }

        function existingUrunIds() {
            var ids = new Set();
            if (!tbody) return ids;
            var inputs = tbody.querySelectorAll('.urun-id-input');
            inputs.forEach(function (input) {
                if (input.value) ids.add(String(input.value));
            });
            return ids;
        }

        function buildProductOptions(groupId) {
            var options = productsByGroup[String(groupId)] || [];
            productSelect.innerHTML = '';
            options.forEach(function (opt) {
                var option = document.createElement('option');
                option.value = opt.id;
                option.textContent = opt.kod;
                productSelect.appendChild(option);
            });
        }

        function toggleAddButton() {
            if (!addRowsBtn || !productSelect) return;
            addRowsBtn.disabled = !productSelect.value;
            addRowsBtn.style.opacity = addRowsBtn.disabled ? 0.6 : 1;
        }

        function redirectWithSelection() {
            var g = groupSelect ? groupSelect.value : '';
            var p = productSelect ? productSelect.value : '';
            var url = new URL(window.location.href);
            if (g) {
                url.searchParams.set('montaj_grup_id', g);
            } else {
                url.searchParams.delete('montaj_grup_id');
            }
            if (p) {
                url.searchParams.set('montaj_urun_id', p);
            } else {
                url.searchParams.delete('montaj_urun_id');
            }
            window.location.href = url.toString();
        }

        if (groupSelect) {
            groupSelect.addEventListener('change', function () {
                var gid = groupSelect.value;
                buildProductOptions(gid);
                var first = productSelect && productSelect.options.length ? productSelect.options[0].value : '';
                if (first) {
                    productSelect.value = first;
                }
                toggleAddButton();
                redirectWithSelection();
            });
        }

        if (productSelect) {
            productSelect.addEventListener('change', function () {
                toggleAddButton();
                redirectWithSelection();
            });
        }

        function renderModalRows() {
            if (!modalTableBody) return;
            var keyword = ((modalSearch && modalSearch.value) ? modalSearch.value : '').toString().trim().toLowerCase();
            var selectedGroupId = groupFilter && groupFilter.value ? String(groupFilter.value) : '';
            var selectedSubId = subGroupFilter && subGroupFilter.value ? String(subGroupFilter.value) : '';
            var selectedDetailId = detailGroupFilter && detailGroupFilter.value ? String(detailGroupFilter.value) : '';
            modalTableBody.innerHTML = '';
            (Array.isArray(stockCards) ? stockCards : []).forEach(function (p) {
                var kod = (p && p.kod != null) ? String(p.kod) : '';
                var aciklama = (p && p.aciklama != null) ? String(p.aciklama) : '';
                var kategoriId = (p && p.kategori_id != null) ? String(p.kategori_id) : '';
                var altGrupId = (p && p.urun_alt_grup_id != null) ? String(p.urun_alt_grup_id) : '';
                var detayGrupId = (p && p.urun_detay_grup_id != null) ? String(p.urun_detay_grup_id) : '';

                if (selectedGroupId && kategoriId !== selectedGroupId) return;
                if (selectedSubId && altGrupId !== selectedSubId) return;
                if (selectedDetailId && detayGrupId !== selectedDetailId) return;

                if (keyword) {
                    var k = kod.toLowerCase();
                    var a = aciklama.toLowerCase();
                    if (k.indexOf(keyword) === -1 && a.indexOf(keyword) === -1) {
                        return;
                    }
                }
                if (!kod && !aciklama) {
                    return;
                }
                var tr = document.createElement('tr');
                tr.innerHTML =
                    '<td style=\"width:50px;text-align:center;\"><input type=\"checkbox\" data-id=\"' + p.id + '\"></td>' +
                    '<td style=\"font-weight:600;\">' + kod + '</td>' +
                    '<td>' + aciklama + '</td>';
                modalTableBody.appendChild(tr);
            });
        }

        function setSelectOptions(select, options, selectedValue) {
            if (!select) return;
            var current = selectedValue != null ? String(selectedValue) : (select.value ? String(select.value) : '');
            select.innerHTML = '';
            (Array.isArray(options) ? options : []).forEach(function (opt) {
                var o = document.createElement('option');
                o.value = opt.value;
                o.textContent = opt.label;
                select.appendChild(o);
            });
            if (current && Array.prototype.some.call(select.options, function (o) { return String(o.value) === current; })) {
                select.value = current;
            } else {
                select.value = (select.options.length ? select.options[0].value : '');
            }
        }

        function rebuildFilterOptions() {
            var gid = groupFilter && groupFilter.value ? String(groupFilter.value) : '';
            var sid = subGroupFilter && subGroupFilter.value ? String(subGroupFilter.value) : '';

            setSelectOptions(groupFilter, [{ value: '', label: 'Stok Ana Grup (Hepsi)' }].concat(
                (Array.isArray(stockGroups) ? stockGroups : []).map(function (g) {
                    return { value: String(g.id), label: String(g.ad || '') };
                })
            ));

            var subOptions = (Array.isArray(stockSubGroups) ? stockSubGroups : []).filter(function (sg) {
                if (!gid) return true;
                return String(sg.urun_grup_id) === gid;
            }).map(function (sg) {
                return { value: String(sg.id), label: String(sg.ad || '') };
            });
            setSelectOptions(subGroupFilter, [{ value: '', label: 'Stok Alt Grup (Hepsi)' }].concat(subOptions), sid);

            sid = subGroupFilter && subGroupFilter.value ? String(subGroupFilter.value) : '';
            var detailOptions = (Array.isArray(stockDetailGroups) ? stockDetailGroups : []).filter(function (dg) {
                if (gid && String(dg.urun_grup_id) !== gid) return false;
                if (sid && String(dg.urun_alt_grup_id) !== sid) return false;
                return true;
            }).map(function (dg) {
                return { value: String(dg.id), label: String(dg.ad || '') };
            });
            setSelectOptions(detailGroupFilter, [{ value: '', label: 'Stok Detay Grup (Hepsi)' }].concat(detailOptions));
        }

        function toggleModal(show) {
            if (!modal) return;
            modal.style.display = show ? 'flex' : 'none';
            if (show) {
                if (modalSearch) modalSearch.value = '';
                if (groupFilter) groupFilter.value = '';
                if (subGroupFilter) subGroupFilter.value = '';
                if (detailGroupFilter) detailGroupFilter.value = '';
                rebuildFilterOptions();
                renderModalRows();
            }
        }

        if (addRowsBtn) {
            addRowsBtn.addEventListener('click', function () {
                toggleModal(true);
            });
        }

        if (modalClose) modalClose.addEventListener('click', function () { toggleModal(false); });
        if (modalCancel) modalCancel.addEventListener('click', function () { toggleModal(false); });
        if (modalSearch) modalSearch.addEventListener('input', renderModalRows);
        if (groupFilter) groupFilter.addEventListener('change', function () { rebuildFilterOptions(); renderModalRows(); });
        if (subGroupFilter) subGroupFilter.addEventListener('change', function () { rebuildFilterOptions(); renderModalRows(); });
        if (detailGroupFilter) detailGroupFilter.addEventListener('change', renderModalRows);

        if (modalOk) {
            modalOk.addEventListener('click', function () {
                var selected = [];
                if (modalTableBody) {
                    modalTableBody.querySelectorAll('input[type=\"checkbox\"]:checked').forEach(function (cb) {
                        selected.push(cb.getAttribute('data-id'));
                    });
                }
                if (!selected.length) {
                    toggleModal(false);
                    return;
                }
                if (tbody) {
                    Array.prototype.slice.call(tbody.querySelectorAll('tr')).forEach(function (tr) {
                        var input = tr.querySelector('.urun-id-input');
                        if (input && !input.value) {
                            tr.parentNode.removeChild(tr);
                        }
                    });
                }
                var existing = existingUrunIds();
                var idx = tbody ? tbody.querySelectorAll('tr').length : 0;
                selected.forEach(function (id) {
                    if (existing.has(String(id))) {
                        return;
                    }
                    var p = (Array.isArray(stockCards) ? stockCards : []).find(function (d) { return String(d.id) === String(id); });
                    var kod = p && p.kod != null ? String(p.kod) : ('#' + String(id));
                    var aciklama = p && p.aciklama != null ? String(p.aciklama) : '';
                    var tr = document.createElement('tr');
                    tr.innerHTML =
                        '<td class=\"sirano-cell\" style=\"padding:0.5rem 0.5rem;text-align:center;color:#6b7280;\"></td>' +
                        '<td style=\"padding:0.5rem 0.5rem;font-weight:600;\">' +
                        '<input type=\"hidden\" name=\"items[' + idx + '][id]\" value=\"\">' +
                        '<input type=\"hidden\" class=\"sirano-input\" name=\"items[' + idx + '][sirano]\" value=\"0\">' +
                        '<input type=\"hidden\" class=\"urun-id-input\" name=\"items[' + idx + '][urun_id]\" value=\"' + String(id).replace(/\"/g, '&quot;') + '\">' +
                        kod +
                        '</td>' +
                        '<td style=\"padding:0.5rem 0.5rem;\">' + aciklama + '</td>' +
                        '<td style=\"padding:0.5rem 0.5rem;text-align:right;white-space:nowrap;\">' +
                        '<button type=\"button\" class=\"row-delete-btn\" title=\"Sil\" style=\"background:none;border:none;color:#ef4444;font-size:0.85rem;cursor:pointer;\">Sil</button>' +
                        '</td>';
                    tbody.appendChild(tr);
                    idx += 1;
                });
                bindRowDeletes();
                renumberRows();
                toggleModal(false);
            });
        }

        function bindRowDeletes() {
            if (!tbody) return;
            tbody.querySelectorAll('.row-delete-btn').forEach(function (btn) {
                btn.removeEventListener('click', btn._handler || function () {});
                var handler = function () {
                    var tr = btn.closest('tr');
                    if (tr && tr.parentNode) {
                        tr.parentNode.removeChild(tr);
                        renumberRows();
                    }
                };
                btn._handler = handler;
                btn.addEventListener('click', handler);
            });
        }

        bindRowDeletes();
        toggleAddButton();
    })();
</script>
</body>
</html>
