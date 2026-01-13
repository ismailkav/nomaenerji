<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Montaj Ürün - NomaEnerji</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        #montajProductsTable thead th:nth-child(2),
        #montajProductsTable tbody td:nth-child(2) { width: 320px; }

        #montajProductsTable thead th:nth-child(3),
        #montajProductsTable tbody td:nth-child(3) { width: 80px; }

        #montajProductsTable thead th:nth-child(4),
        #montajProductsTable tbody td:nth-child(4) { width: 85px; }
    </style>
</head>
<body>
<div class="dashboard-container">
    @include('partials.sidebar', ['active' => 'montaj-products'])

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
                Montaj Ürün
            </div>
        </header>

        <section class="content-section" style="padding:2rem;">
            <div class="form-page-card">
                <div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1rem;flex-wrap:wrap;">
                    <div style="display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap;">
                        <h1 style="font-size:1.4rem;font-weight:600;margin:0;">Montaj Ürün</h1>
                        <select id="montajGroupSelect" style="min-width:260px;">
                            @foreach($montajGroups as $g)
                                <option value="{{ $g->id }}" {{ (int)$selectedMontajGroupId === (int)$g->id ? 'selected' : '' }}>
                                    {{ $g->kod }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" id="addMontajProductRow" class="form-header-btn save" style="padding:0.4rem 1rem;font-size:0.9rem;">Yeni</button>
                </div>

                @error('montaj_grup_id')<div class="form-error" style="margin-bottom:0.75rem;">{{ $message }}</div>@enderror

                <form method="POST" action="{{ route('definitions.montaj-products.save') }}">
                    @csrf
                    <input type="hidden" name="montaj_grup_id" value="{{ $selectedMontajGroupId }}">

                    <div class="products-table-wrapper" style="overflow-x:auto;">
                        <table id="montajProductsTable" style="width:100%;border-collapse:collapse;font-size:0.9rem;">
                            <thead>
                            <tr>
                                <th style="width:90px;text-align:center;padding:0.6rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">Sıra No</th>
                                <th style="text-align:left;padding:0.6rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">Ürün Kod</th>
                                <th style="width:80px;text-align:left;padding:0.6rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">Birim</th>
                                <th style="width:85px;text-align:right;padding:0.6rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">Birim Fiyat</th>
                                <th style="width:120px;text-align:left;padding:0.6rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">Döviz</th>
                                <th style="width:190px;text-align:right;padding:0.6rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">İşlem</th>
                            </tr>
                            </thead>
                            <tbody id="montajProductsBody">
                            @forelse($items as $i => $row)
                                <tr>
                                    <td class="sirano-cell" style="padding:0.5rem 0.5rem;text-align:center;color:#6b7280;">{{ (int) ($row->sirano ?? 0) > 0 ? (int) $row->sirano : ($i + 1) }}</td>
                                    <td style="padding:0.5rem 0.5rem;">
                                        <input type="hidden" name="items[{{ $i }}][id]" value="{{ $row->id }}">
                                        <input type="hidden" class="sirano-input" name="items[{{ $i }}][sirano]" value="{{ (int) ($row->sirano ?? 0) > 0 ? (int) $row->sirano : ($i + 1) }}">
                                        <input type="text" name="items[{{ $i }}][urun_kod]" value="{{ $row->urun_kod }}" style="width:100%;">
                                    </td>
                                    <td style="padding:0.5rem 0.5rem;">
                                        @php($unit = $row->birim ?? 'Adet')
                                        <select name="items[{{ $i }}][birim]" style="width:100%;">
                                            <option value="Adet" @selected($unit === 'Adet')>Adet</option>
                                            <option value="Metre" @selected($unit === 'Metre')>Metre</option>
                                            <option value="Kilo" @selected($unit === 'Kilo')>Kilo</option>
                                        </select>
                                    </td>
                                    <td style="padding:0.5rem 0.5rem;text-align:right;">
                                        <input type="number" step="0.01" name="items[{{ $i }}][birim_fiyat]" value="{{ $row->birim_fiyat }}" style="width:100%;text-align:right;">
                                    </td>
                                    <td style="padding:0.5rem 0.5rem;">
                                        @php($cur = $row->doviz ?? 'TL')
                                        <select name="items[{{ $i }}][doviz]" style="width:100%;">
                                            <option value="TL" @selected($cur === 'TL')>TL</option>
                                            <option value="USD" @selected($cur === 'USD')>USD</option>
                                            <option value="EUR" @selected($cur === 'EUR')>EUR</option>
                                        </select>
                                    </td>
                                    <td style="padding:0.5rem 0.5rem;text-align:right;white-space:nowrap;">
                                        <button type="button" class="small-btn move-up" title="Yukarı" style="margin-right:6px;">▲</button>
                                        <button type="button" class="small-btn move-down" title="Aşağı" style="margin-right:6px;">▼</button>
                                        <button type="button" class="row-delete-btn" title="Sil" style="background:none;border:none;color:#ef4444;font-size:0.85rem;cursor:pointer;">Sil</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="sirano-cell" style="padding:0.5rem 0.5rem;text-align:center;color:#6b7280;">1</td>
                                    <td style="padding:0.5rem 0.5rem;">
                                        <input type="hidden" name="items[0][id]" value="">
                                        <input type="hidden" class="sirano-input" name="items[0][sirano]" value="1">
                                        <input type="text" name="items[0][urun_kod]" value="" style="width:100%;" placeholder="Ürün Kod">
                                    </td>
                                    <td style="padding:0.5rem 0.5rem;">
                                        <select name="items[0][birim]" style="width:100%;">
                                            <option value="Adet" selected>Adet</option>
                                            <option value="Metre">Metre</option>
                                            <option value="Kilo">Kilo</option>
                                        </select>
                                    </td>
                                    <td style="padding:0.5rem 0.5rem;text-align:right;">
                                        <input type="number" step="0.01" name="items[0][birim_fiyat]" value="0" style="width:100%;text-align:right;">
                                    </td>
                                    <td style="padding:0.5rem 0.5rem;">
                                        <select name="items[0][doviz]" style="width:100%;">
                                            <option value="TL" selected>TL</option>
                                            <option value="USD">USD</option>
                                            <option value="EUR">EUR</option>
                                        </select>
                                    </td>
                                    <td style="padding:0.5rem 0.5rem;text-align:right;white-space:nowrap;">
                                        <button type="button" class="small-btn move-up" title="Yukarı" style="margin-right:6px;">▲</button>
                                        <button type="button" class="small-btn move-down" title="Aşağı" style="margin-right:6px;">▼</button>
                                        <button type="button" class="row-delete-btn" title="Sil" style="background:none;border:none;color:#ef4444;font-size:0.85rem;cursor:pointer;">Sil</button>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div style="margin-top:1.25rem;display:flex;justify-content:flex-end;">
                        <button type="submit" class="form-header-btn save">Kaydet</button>
                    </div>
                </form>
            </div>
        </section>
    </main>
</div>

<script src="{{ asset('js/dashboard.js') }}"></script>
<script>
    (function () {
        var baseUrl = @json(route('definitions.montaj-products'));
        var groupSelect = document.getElementById('montajGroupSelect');
        var tbody = document.getElementById('montajProductsBody');
        var addButton = document.getElementById('addMontajProductRow');

        var index = tbody ? tbody.querySelectorAll('tr').length : 0;

        function renumberRows() {
            if (!tbody) return;
            var rows = Array.prototype.slice.call(tbody.querySelectorAll('tr'));
            rows.forEach(function (tr, i) {
                var no = i + 1;
                var cell = tr.querySelector('.sirano-cell');
                if (cell) cell.textContent = String(no);
                var input = tr.querySelector('.sirano-input');
                if (input) input.value = String(no);
            });
        }

        if (groupSelect) {
            groupSelect.addEventListener('change', function () {
                var id = groupSelect.value || '';
                window.location.href = baseUrl + (id ? ('?montaj_grup_id=' + encodeURIComponent(id)) : '');
            });
        }

        if (addButton && tbody) {
            addButton.addEventListener('click', function () {
                var tr = document.createElement('tr');
                tr.innerHTML =
                    '<td class="sirano-cell" style="padding:0.5rem 0.5rem;text-align:center;color:#6b7280;"></td>' +
                    '<td style="padding:0.5rem 0.5rem;">' +
                    '<input type="hidden" name="items[' + index + '][id]" value="">' +
                    '<input type="hidden" class="sirano-input" name="items[' + index + '][sirano]" value="0">' +
                    '<input type="text" name="items[' + index + '][urun_kod]" value="" style="width:100%;" placeholder="Ürün Kod">' +
                    '</td>' +
                    '<td style="padding:0.5rem 0.5rem;">' +
                    '<select name="items[' + index + '][birim]" style="width:100%;">' +
                    '<option value="Adet" selected>Adet</option>' +
                    '<option value="Metre">Metre</option>' +
                    '<option value="Kilo">Kilo</option>' +
                    '</select>' +
                    '</td>' +
                    '<td style="padding:0.5rem 0.5rem;text-align:right;">' +
                    '<input type="number" step="0.01" name="items[' + index + '][birim_fiyat]" value="0" style="width:100%;text-align:right;">' +
                    '</td>' +
                    '<td style="padding:0.5rem 0.5rem;">' +
                    '<select name="items[' + index + '][doviz]" style="width:100%;">' +
                    '<option value="TL" selected>TL</option>' +
                    '<option value="USD">USD</option>' +
                    '<option value="EUR">EUR</option>' +
                    '</select>' +
                    '</td>' +
                    '<td style="padding:0.5rem 0.5rem;text-align:right;white-space:nowrap;">' +
                    '<button type="button" class="small-btn move-up" title="Yukarı" style="margin-right:6px;">▲</button>' +
                    '<button type="button" class="small-btn move-down" title="Aşağı" style="margin-right:6px;">▼</button>' +
                    '<button type="button" class="row-delete-btn" title="Sil" style="background:none;border:none;color:#ef4444;font-size:0.85rem;cursor:pointer;">Sil</button>' +
                    '</td>';
                tbody.appendChild(tr);
                index++;
                renumberRows();
            });
        }

        if (tbody) {
            tbody.addEventListener('click', function (e) {
                var upBtn = e.target && e.target.closest ? e.target.closest('.move-up') : null;
                if (upBtn) {
                    var rowUp = upBtn.closest('tr');
                    if (rowUp && rowUp.previousElementSibling) {
                        tbody.insertBefore(rowUp, rowUp.previousElementSibling);
                        renumberRows();
                    }
                    return;
                }

                var downBtn = e.target && e.target.closest ? e.target.closest('.move-down') : null;
                if (downBtn) {
                    var rowDown = downBtn.closest('tr');
                    if (rowDown && rowDown.nextElementSibling) {
                        tbody.insertBefore(rowDown.nextElementSibling, rowDown);
                        renumberRows();
                    }
                    return;
                }

                if (e.target && e.target.classList && e.target.classList.contains('row-delete-btn')) {
                    var row = e.target.closest('tr');
                    if (row && confirm('Bu satırı silmek istiyor musunuz?')) {
                        row.remove();
                        renumberRows();
                    }
                }
            });
        }

        renumberRows();
    })();
</script>
</body>
</html>
