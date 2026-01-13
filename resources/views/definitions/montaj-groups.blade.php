<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Montaj Grup - NomaEnerji</title>
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
        .modal-table tr.pick-row {
            cursor: pointer;
        }
        .modal-table tr.pick-row:hover {
            background: #f8fafc;
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    @include('partials.sidebar', ['active' => 'montaj-groups'])

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
                Montaj Grup
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
                    <h1 style="font-size:1.4rem;font-weight:600;margin:0;">Montaj Grup</h1>
                    <button type="button" id="addMontajGroupRow" class="form-header-btn save" style="padding:0.4rem 1rem;font-size:0.9rem;">Yeni</button>
                </div>

                <form method="POST" action="{{ route('definitions.montaj-groups.save') }}">
                    @csrf
                    <div class="products-table-wrapper" style="overflow-x:auto;">
                        <table style="width:100%;border-collapse:collapse;font-size:0.9rem;">
                            <thead>
                            <tr>
                                <th style="width:90px;text-align:center;padding:0.6rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">Sıra No</th>
                                <th style="text-align:left;padding:0.6rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">Kod</th>
                                <th style="text-align:right;padding:0.6rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">İşlem</th>
                            </tr>
                            </thead>
                            <tbody id="montajGroupsBody">
                            @forelse($montajGroups as $i => $row)
                                <tr>
                                    <td class="sirano-cell" style="padding:0.5rem 0.5rem;text-align:center;color:#6b7280;">
                                        {{ (int) ($row->sirano ?? 0) > 0 ? (int) $row->sirano : ($i + 1) }}
                                    </td>
                                    <td style="padding:0.5rem 0.5rem;">
                                        <input type="hidden" name="items[{{ $i }}][id]" value="{{ $row->id }}">
                                        <input type="hidden" class="urun-detay-grup-id" name="items[{{ $i }}][urun_detay_grup_id]" value="{{ $row->urun_detay_grup_id }}">
                                        <input type="hidden" class="sirano-input" name="items[{{ $i }}][sirano]" value="{{ (int) ($row->sirano ?? 0) > 0 ? (int) $row->sirano : ($i + 1) }}">
                                        <input type="text" class="kod-input" name="items[{{ $i }}][kod]" value="{{ $row->kod }}" style="width:100%;">
                                    </td>
                                    <td style="padding:0.5rem 0.5rem;text-align:right;white-space:nowrap;">
                                        <button type="button" class="small-btn move-up" title="Yukarı" style="margin-right:6px;">▲</button>
                                        <button type="button" class="small-btn move-down" title="Aşağı" style="margin-right:6px;">▼</button>
                                        <button type="button" class="small-btn pick-detail-group" title="Seç">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M21 21l-4.3-4.3m1.8-5.2a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                            </svg>
                                        </button>
                                        <button type="button" class="row-delete-btn" title="Sil" style="background:none;border:none;color:#ef4444;font-size:0.85rem;cursor:pointer;margin-left:6px;">Sil</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="sirano-cell" style="padding:0.5rem 0.5rem;text-align:center;color:#6b7280;">1</td>
                                    <td style="padding:0.5rem 0.5rem;">
                                        <input type="hidden" name="items[0][id]" value="">
                                        <input type="hidden" class="urun-detay-grup-id" name="items[0][urun_detay_grup_id]" value="">
                                        <input type="hidden" class="sirano-input" name="items[0][sirano]" value="1">
                                        <input type="text" class="kod-input" name="items[0][kod]" value="" style="width:100%;" placeholder="Kod">
                                    </td>
                                    <td style="padding:0.5rem 0.5rem;text-align:right;white-space:nowrap;">
                                        <button type="button" class="small-btn move-up" title="Yukarı" style="margin-right:6px;">▲</button>
                                        <button type="button" class="small-btn move-down" title="Aşağı" style="margin-right:6px;">▼</button>
                                        <button type="button" class="small-btn pick-detail-group" title="Seç">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M21 21l-4.3-4.3m1.8-5.2a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                            </svg>
                                        </button>
                                        <button type="button" class="row-delete-btn" title="Sil" style="background:none;border:none;color:#ef4444;font-size:0.85rem;cursor:pointer;margin-left:6px;">Sil</button>
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

<div id="detailGroupPickModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Detay Grup Seç</div>
            <button type="button" class="small-btn" id="detailGroupPickClose">X</button>
        </div>
        <div class="modal-body">
            <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;margin-bottom:0.75rem;">
                <select id="pickGroupSelect" style="min-width:240px;">
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}">{{ $group->ad }}</option>
                    @endforeach
                </select>
                <select id="pickSubGroupSelect" style="min-width:240px;"></select>
            </div>

            <table class="modal-table">
                <thead>
                <tr>
                    <th>Ad</th>
                    <th style="width:130px;">Montaj</th>
                </tr>
                </thead>
                <tbody id="detailGroupPickBody"></tbody>
            </table>
        </div>
    </div>
</div>

<script src="{{ asset('js/dashboard.js') }}"></script>
<script>
    (function () {
        var subGroupsByGroup = @json($subGroupsByGroup);
        var lookupUrl = @json(route('definitions.montaj-groups.detail-groups'));

        var tbody = document.getElementById('montajGroupsBody');
        var addButton = document.getElementById('addMontajGroupRow');

        var modal = document.getElementById('detailGroupPickModal');
        var modalClose = document.getElementById('detailGroupPickClose');
        var pickGroupSelect = document.getElementById('pickGroupSelect');
        var pickSubGroupSelect = document.getElementById('pickSubGroupSelect');
        var pickBody = document.getElementById('detailGroupPickBody');

        var currentRow = null;
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

        function openModal() {
            if (modal) modal.style.display = 'flex';
        }

        function closeModal() {
            if (modal) modal.style.display = 'none';
            currentRow = null;
        }

        function refreshSubGroupOptions() {
            if (!pickGroupSelect || !pickSubGroupSelect) return;
            var groupId = pickGroupSelect.value || '';
            var items = subGroupsByGroup[groupId] || [];

            pickSubGroupSelect.innerHTML = '';
            items.forEach(function (it) {
                var opt = document.createElement('option');
                opt.value = String(it.id);
                opt.textContent = it.ad;
                pickSubGroupSelect.appendChild(opt);
            });
        }

        function loadDetailGroups() {
            if (!pickBody || !pickGroupSelect || !pickSubGroupSelect) return;
            pickBody.innerHTML = '';
            var groupId = pickGroupSelect.value || '';
            var subGroupId = pickSubGroupSelect.value || '';
            if (!groupId || !subGroupId) return;

            fetch(lookupUrl + '?group_id=' + encodeURIComponent(groupId) + '&sub_group_id=' + encodeURIComponent(subGroupId), {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin'
            })
                .then(function (r) { return r.ok ? r.json() : Promise.reject(r); })
                .then(function (data) {
                    var items = data && Array.isArray(data.items) ? data.items : [];
                    if (!items.length) {
                        var empty = document.createElement('tr');
                        empty.innerHTML = '<td colspan="2" style="color:#6b7280;">Kayıt bulunamadı.</td>';
                        pickBody.appendChild(empty);
                        return;
                    }

                    items.forEach(function (it) {
                        var tr = document.createElement('tr');
                        tr.className = 'pick-row';
                        tr.dataset.id = it.id;
                        tr.dataset.ad = it.ad;
                        tr.innerHTML =
                            '<td>' + (it.ad || '') + '</td>' +
                            '<td>' + (it.montaj_grubu ? 'Evet' : 'Hayır') + '</td>';
                        pickBody.appendChild(tr);
                    });
                })
                .catch(function () {
                    var err = document.createElement('tr');
                    err.innerHTML = '<td colspan="2" style="color:#ef4444;">Yüklenemedi.</td>';
                    pickBody.appendChild(err);
                });
        }

        function ensureSubGroupsThenLoad() {
            refreshSubGroupOptions();
            if (pickSubGroupSelect && pickSubGroupSelect.options.length) {
                pickSubGroupSelect.value = pickSubGroupSelect.options[0].value;
            }
            loadDetailGroups();
        }

        if (modalClose) {
            modalClose.addEventListener('click', closeModal);
        }
        if (modal) {
            modal.addEventListener('click', function (e) {
                if (e.target === modal) closeModal();
            });
        }

        if (pickGroupSelect) {
            pickGroupSelect.addEventListener('change', function () {
                refreshSubGroupOptions();
                loadDetailGroups();
            });
        }
        if (pickSubGroupSelect) {
            pickSubGroupSelect.addEventListener('change', loadDetailGroups);
        }

        if (pickBody) {
            pickBody.addEventListener('click', function (e) {
                var row = e.target && e.target.closest ? e.target.closest('tr.pick-row') : null;
                if (!row || !currentRow) return;

                var kodInput = currentRow.querySelector('.kod-input');
                var detailIdInput = currentRow.querySelector('.urun-detay-grup-id');
                if (kodInput) kodInput.value = row.dataset.ad || '';
                if (detailIdInput) detailIdInput.value = row.dataset.id || '';
                closeModal();
            });
        }

        if (addButton && tbody) {
            addButton.addEventListener('click', function () {
                var tr = document.createElement('tr');
                tr.innerHTML =
                    '<td class="sirano-cell" style="padding:0.5rem 0.5rem;text-align:center;color:#6b7280;"></td>' +
                    '<td style="padding:0.5rem 0.5rem;">' +
                    '<input type="hidden" name="items[' + index + '][id]" value="">' +
                    '<input type="hidden" class="urun-detay-grup-id" name="items[' + index + '][urun_detay_grup_id]" value="">' +
                    '<input type="hidden" class="sirano-input" name="items[' + index + '][sirano]" value="0">' +
                    '<input type="text" class="kod-input" name="items[' + index + '][kod]" value="" style="width:100%;" placeholder="Kod">' +
                    '</td>' +
                    '<td style="padding:0.5rem 0.5rem;text-align:right;white-space:nowrap;">' +
                    '<button type="button" class="small-btn move-up" title="Yukarı" style="margin-right:6px;">▲</button>' +
                    '<button type="button" class="small-btn move-down" title="Aşağı" style="margin-right:6px;">▼</button>' +
                    '<button type="button" class="small-btn pick-detail-group" title="Seç">' +
                    '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">' +
                    '<path d="M21 21l-4.3-4.3m1.8-5.2a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>' +
                    '</svg>' +
                    '</button>' +
                    '<button type="button" class="row-delete-btn" title="Sil" style="background:none;border:none;color:#ef4444;font-size:0.85rem;cursor:pointer;margin-left:6px;">Sil</button>' +
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

                var pickBtn = e.target && e.target.closest ? e.target.closest('.pick-detail-group') : null;
                if (pickBtn) {
                    currentRow = pickBtn.closest('tr');
                    if (currentRow) {
                        if (pickGroupSelect && pickGroupSelect.options.length) {
                            if (!pickGroupSelect.value) pickGroupSelect.value = pickGroupSelect.options[0].value;
                        }
                        ensureSubGroupsThenLoad();
                        openModal();
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

        ensureSubGroupsThenLoad();
        renumberRows();
    })();
</script>
</body>
</html>
