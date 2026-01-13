<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Detay Grup - NomaEnerji</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
<div class="dashboard-container">
    @include('partials.sidebar', ['active' => 'product-detail-groups'])

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
                Ürün Detay Grup
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

        <section class="content-section" style="padding:2rem;">
            <div class="form-page-card">
                <div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1rem;">
                    <div style="display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap;">
                        <h1 style="font-size:1.4rem;font-weight:600;margin:0;">Ürün Detay Grup</h1>
                        <select id="productGroupSelect" style="min-width:220px;">
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" {{ (int)$selectedGroupId === (int)$group->id ? 'selected' : '' }}>
                                    {{ $group->ad }}
                                </option>
                            @endforeach
                        </select>
                        <select id="productSubGroupSelect" style="min-width:220px;">
                            @foreach($subGroups as $subGroup)
                                <option value="{{ $subGroup->id }}" {{ (int)$selectedSubGroupId === (int)$subGroup->id ? 'selected' : '' }}>
                                    {{ $subGroup->ad }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" id="addProductDetailGroupRow" class="form-header-btn save" style="padding:0.4rem 1rem;font-size:0.9rem;">Yeni</button>
                </div>

                @error('group_id')<div class="form-error" style="margin-bottom:0.5rem;">{{ $message }}</div>@enderror
                @error('sub_group_id')<div class="form-error" style="margin-bottom:0.75rem;">{{ $message }}</div>@enderror

                <form method="POST" action="{{ route('definitions.product-detail-groups.save') }}">
                    @csrf
                    <input type="hidden" name="group_id" value="{{ $selectedGroupId }}">
                    <input type="hidden" name="sub_group_id" value="{{ $selectedSubGroupId }}">

                    <div class="products-table-wrapper" style="overflow-x:auto;">
                        <table style="width:100%;border-collapse:collapse;font-size:0.9rem;">
                            <thead>
                            <tr>
                                <th style="text-align:left;padding:0.6rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">Ad</th>
                                <th style="text-align:center;padding:0.6rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">Montaj Grubu</th>
                                <th style="text-align:right;padding:0.6rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">İşlem</th>
                            </tr>
                            </thead>
                            <tbody id="productDetailGroupsTableBody">
                            @forelse($detailGroups as $index => $detailGroup)
                                <tr>
                                    <td style="padding:0.5rem 0.5rem;">
                                        <input type="hidden" name="items[{{ $index }}][id]" value="{{ $detailGroup->id }}">
                                        <input type="text" name="items[{{ $index }}][name]" value="{{ $detailGroup->ad }}" style="width:100%;">
                                    </td>
                                    <td style="padding:0.5rem 0.5rem;text-align:center;">
                                        <input type="checkbox" name="items[{{ $index }}][montaj_grubu]" value="1" {{ !empty($detailGroup->montaj_grubu) ? 'checked' : '' }}>
                                    </td>
                                    <td style="padding:0.5rem 0.5rem;text-align:right;">
                                        <button type="button" class="row-delete-btn" style="background:none;border:none;color:#ef4444;font-size:0.85rem;cursor:pointer;">Sil</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td style="padding:0.5rem 0.5rem;">
                                        <input type="hidden" name="items[0][id]" value="">
                                        <input type="text" name="items[0][name]" value="" style="width:100%;" placeholder="Yeni detay grup adı">
                                    </td>
                                    <td style="padding:0.5rem 0.5rem;text-align:right;">
                                        <button type="button" class="row-delete-btn" style="background:none;border:none;color:#ef4444;font-size:0.85rem;cursor:pointer;">Sil</button>
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
        var baseUrl = @json(route('definitions.product-detail-groups'));
        var subGroupsByGroup = @json($subGroupsByGroup);

        var groupSelect = document.getElementById('productGroupSelect');
        var subGroupSelect = document.getElementById('productSubGroupSelect');
        var addButton = document.getElementById('addProductDetailGroupRow');
        var tbody = document.getElementById('productDetailGroupsTableBody');

        function buildUrl(groupId, subGroupId) {
            var qs = [];
            if (groupId) qs.push('group_id=' + encodeURIComponent(groupId));
            if (subGroupId) qs.push('sub_group_id=' + encodeURIComponent(subGroupId));
            return baseUrl + (qs.length ? ('?' + qs.join('&')) : '');
        }

        function refreshSubGroupOptions() {
            if (!groupSelect || !subGroupSelect) return;
            var groupId = groupSelect.value || '';
            var items = subGroupsByGroup[groupId] || [];
            var current = subGroupSelect.value || '';

            subGroupSelect.innerHTML = '';
            items.forEach(function (it) {
                var opt = document.createElement('option');
                opt.value = String(it.id);
                opt.textContent = it.ad;
                subGroupSelect.appendChild(opt);
            });

            if (items.some(function (it) { return String(it.id) === String(current); })) {
                subGroupSelect.value = current;
            } else if (items.length) {
                subGroupSelect.value = String(items[0].id);
            }
        }

        if (groupSelect && subGroupSelect) {
            groupSelect.addEventListener('change', function () {
                refreshSubGroupOptions();
                window.location.href = buildUrl(groupSelect.value || '', subGroupSelect.value || '');
            });

            subGroupSelect.addEventListener('change', function () {
                window.location.href = buildUrl(groupSelect.value || '', subGroupSelect.value || '');
            });
        }

        if (!addButton || !tbody) {
            return;
        }

        var index = tbody.querySelectorAll('tr').length;

        (function ensureMontajColumnForInitialRow() {
            var firstRow = tbody.querySelector('tr');
            if (!firstRow) return;
            if (firstRow.querySelector('input[type=\"checkbox\"][name*=\"[montaj_grubu]\"]')) return;
            var actionCell = firstRow.querySelector('td[style*=\"text-align:right\"]');
            if (!actionCell) return;

            var td = document.createElement('td');
            td.style.padding = '0.5rem 0.5rem';
            td.style.textAlign = 'center';
            td.innerHTML = '<input type=\"checkbox\" name=\"items[0][montaj_grubu]\" value=\"1\">';
            firstRow.insertBefore(td, actionCell);
        })();

        addButton.addEventListener('click', function () {
            var tr = document.createElement('tr');
            tr.innerHTML =
                '<td style="padding:0.5rem 0.5rem;">' +
                '<input type="hidden" name="items[' + index + '][id]" value="">' +
                '<input type="text" name="items[' + index + '][name]" value="" style="width:100%;" placeholder="Yeni detay grup adı">' +
                '</td>' +
                '<td style="padding:0.5rem 0.5rem;text-align:center;">' +
                '<input type="checkbox" name="items[' + index + '][montaj_grubu]" value="1">' +
                '</td>' +
                '<td style="padding:0.5rem 0.5rem;text-align:right;">' +
                '<button type="button" class="row-delete-btn" style="background:none;border:none;color:#ef4444;font-size:0.85rem;cursor:pointer;">Sil</button>' +
                '</td>';
            tbody.appendChild(tr);
            index++;
        });

        tbody.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('row-delete-btn')) {
                var row = e.target.closest('tr');
                if (row && confirm('Bu satırı silmek istiyor musunuz?')) {
                    row.remove();
                }
            }
        });
    })();
</script>
</body>
</html>
