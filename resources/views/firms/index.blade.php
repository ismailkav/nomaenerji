<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firmalar - NomaEnerji</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        .user-table-card {
            border-radius: 16px;
            border: 1px solid rgba(148, 163, 184, 0.25);
            padding: 1rem;
            background: radial-gradient(circle at top left, rgba(59,130,246,0.06), transparent 55%),
                        radial-gradient(circle at bottom right, rgba(16,185,129,0.05), transparent 55%),
                        var(--card-bg);
            backdrop-filter: blur(10px);
        }

        .user-table-wrapper {
            margin-top: 0.5rem;
            overflow: hidden;
            border-radius: 12px;
            border: 1px solid rgba(203, 213, 225, 0.7);
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.10);
        }

        .table-toolbar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 0.75rem;
        }

        .table-filter-input {
            width: min(360px, 100%);
            border-radius: 999px;
            border: 1px solid rgba(203, 213, 225, 0.9);
            background: #ffffff;
            padding: 0.65rem 0.95rem;
            font-size: 0.9rem;
            color: #111827;
            outline: none;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
        }

        .table-filter-input:focus {
            border-color: #60a5fa;
            box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.18);
        }

        .user-table-modern {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
            border: 1px solid #e5e7eb;
        }

        .user-table-modern thead {
            background-color: #f3f4f6;
        }

        .user-table-modern thead th {
            padding: 0.75rem 1rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
        }

        .sortable-header {
            cursor: pointer;
            user-select: none;
            position: relative;
            padding-right: 2rem !important;
        }

        .sortable-header::after {
            content: "↕";
            position: absolute;
            right: 0.8rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.78rem;
            color: #9ca3af;
        }

        .sortable-header[data-sort-dir="asc"]::after {
            content: "↑";
            color: #2563eb;
        }

        .sortable-header[data-sort-dir="desc"]::after {
            content: "↓";
            color: #2563eb;
        }

        .user-table-modern tbody tr {
            background-color: #ffffff;
            transition: background 0.12s ease;
        }

        .user-table-modern tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .user-table-modern tbody tr:hover {
            background-color: #f3f4f6;
        }

        .user-table-modern tbody td {
            padding: 0.75rem 1rem;
            font-size: 0.85rem;
            color: #111827;
            border-bottom: 1px solid #e5e7eb;
        }

        .user-action-group {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .user-action-link {
            padding: 0.28rem 0.7rem;
            border-radius: 999px;
            border: 1px solid transparent;
            font-size: 0.78rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .user-action-link.edit {
            background: rgba(59,130,246,0.12);
            color: #bfdbfe;
            border-color: rgba(59,130,246,0.35);
        }

        .user-action-link.edit:hover {
            background: rgba(59,130,246,0.22);
            border-color: rgba(59,130,246,0.7);
        }

        .user-action-link.delete {
            background: rgba(239,68,68,0.10);
            color: #fecaca;
            border-color: rgba(239,68,68,0.35);
        }

        .user-action-link.delete:hover {
            background: rgba(239,68,68,0.18);
            border-color: rgba(239,68,68,0.7);
        }

        .user-table-modern tbody tr:last-child td {
            border-bottom: none;
        }

        .filter-empty-row td {
            text-align: center;
            color: #6b7280;
            font-style: italic;
        }

        @media (max-width: 768px) {
            .table-toolbar {
                justify-content: stretch;
            }

            .table-filter-input {
                width: 100%;
            }

            .user-table-modern thead {
                display: none;
            }

            .user-table-modern,
            .user-table-modern tbody,
            .user-table-modern tr,
            .user-table-modern td {
                display: block;
                width: 100%;
            }

            .user-table-modern tr {
                margin-bottom: 0.75rem;
                border-radius: 12px;
                overflow: hidden;
            }

            .user-table-modern td {
                border-bottom: 1px solid rgba(31, 41, 55, 0.9);
            }

            .user-table-modern td:last-child {
                border-bottom: none;
            }
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    @include('partials.sidebar', ['active' => 'firms'])

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
                Firmalar
            </div>
            <div class="user-profile" style="display: flex; align-items: center; gap: 1rem;">
                <a href="{{ route('firms.create') }}"
                   class="btn btn-primary"
                   style="padding: 0.5rem 1rem; border-radius: 999px; border: none; background: #16a34a; color: #fff; font-weight: 500; text-decoration: none; cursor: pointer;">
                    Yeni Firma
                </a>
            </div>
        </header>

        <section class="content-section" style="padding: 2rem;">
            <div class="user-table-card">
                @if (session('status'))
                    <div class="alert alert-success" style="margin-bottom: 16px;">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="table-toolbar">
                    <input id="firmTableFilter"
                           class="table-filter-input"
                           type="text"
                           placeholder="Filtrele (kod, açıklama, il, ilçe, telefon...)">
                </div>

                <div class="user-table-wrapper">
                    <table class="user-table-modern" id="firmsTable">
                        <thead>
                        <tr>
                            <th class="sortable-header" data-sort-index="0">Cari Kod</th>
                            <th class="sortable-header" data-sort-index="1">Cari Açıklama</th>
                            <th class="sortable-header" data-sort-index="2">Adres 1</th>
                            <th class="sortable-header" data-sort-index="3">Adres 2</th>
                            <th class="sortable-header" data-sort-index="4">İl</th>
                            <th class="sortable-header" data-sort-index="5">İlçe</th>
                            <th class="sortable-header" data-sort-index="6">Ülke</th>
                            <th class="sortable-header" data-sort-index="7">Telefon</th>
                            <th class="sortable-header" data-sort-index="8">Mail</th>
                            <th class="sortable-header" data-sort-index="9">Web Sitesi</th>
                            <th style="width: 160px;">İşlemler</th>
                        </tr>
                        </thead>
                        <tbody id="firmsTableBody">
                        @forelse($firms as $firm)
                            <tr data-firm-row="1">
                                <td>{{ $firm->carikod }}</td>
                                <td>{{ $firm->cariaciklama }}</td>
                                <td>{{ $firm->adres1 }}</td>
                                <td>{{ $firm->adres2 }}</td>
                                <td>{{ $firm->il }}</td>
                                <td>{{ $firm->ilce }}</td>
                                <td>{{ $firm->ulke }}</td>
                                <td>{{ $firm->telefon }}</td>
                                <td>{{ $firm->mail }}</td>
                                <td>{{ $firm->web_sitesi }}</td>
                                <td>
                                    <div class="user-action-group">
                                        <a href="{{ route('firms.edit', $firm) }}" class="user-action-link edit">
                                            Düzenle
                                        </a>
                                        <form action="{{ route('firms.destroy', $firm) }}" method="POST" style="display:inline-block"
                                              onsubmit="return confirm('Bu firmayı silmek istediğinize emin misiniz?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="user-action-link delete" style="border:none;background:none;">
                                                Sil
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="firmsTableServerEmpty">
                                <td colspan="11" style="text-align:center;padding:16px;">Kayıtlı firma bulunamadı.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div style="margin-top:16px;">
                    {{ $firms->links() }}
                </div>
            </div>
        </section>
    </main>
</div>
<script src="{{ asset('js/dashboard.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var filterInput = document.getElementById('firmTableFilter');
        var tableBody = document.getElementById('firmsTableBody');
        if (!tableBody) return;

        var rows = Array.prototype.slice.call(tableBody.querySelectorAll('tr[data-firm-row="1"]'));
        var headers = Array.prototype.slice.call(document.querySelectorAll('.sortable-header'));
        var serverEmptyRow = document.getElementById('firmsTableServerEmpty');
        var noMatchRow = document.createElement('tr');
        noMatchRow.className = 'filter-empty-row';
        noMatchRow.style.display = 'none';
        noMatchRow.innerHTML = '<td colspan="11">Filtreye uygun firma bulunamadı.</td>';
        tableBody.appendChild(noMatchRow);

        var currentSortIndex = null;
        var currentSortDir = 'asc';

        rows.forEach(function (row) {
            var searchText = Array.prototype.slice.call(row.children)
                .slice(0, 10)
                .map(function (cell) { return (cell.textContent || '').trim().toLocaleLowerCase('tr'); })
                .join(' ');
            row.dataset.searchText = searchText;
        });

        function getCellValue(row, index) {
            var cell = row.children[index];
            return cell ? (cell.textContent || '').trim() : '';
        }

        function compareRows(a, b, index, dir) {
            var aValue = getCellValue(a, index);
            var bValue = getCellValue(b, index);

            var aNumber = Number(aValue.replace(',', '.'));
            var bNumber = Number(bValue.replace(',', '.'));
            var isNumeric = aValue !== '' && bValue !== '' && !Number.isNaN(aNumber) && !Number.isNaN(bNumber);

            var result = isNumeric
                ? (aNumber - bNumber)
                : aValue.localeCompare(bValue, 'tr', { sensitivity: 'base', numeric: true });

            return dir === 'asc' ? result : -result;
        }

        function updateHeaderState() {
            headers.forEach(function (header) {
                var headerIndex = Number(header.getAttribute('data-sort-index'));
                if (headerIndex === currentSortIndex) {
                    header.setAttribute('data-sort-dir', currentSortDir);
                } else {
                    header.removeAttribute('data-sort-dir');
                }
            });
        }

        function renderRows() {
            var query = filterInput && filterInput.value
                ? filterInput.value.trim().toLocaleLowerCase('tr')
                : '';

            var orderedRows = rows.slice();
            if (currentSortIndex !== null) {
                orderedRows.sort(function (a, b) {
                    return compareRows(a, b, currentSortIndex, currentSortDir);
                });
            }

            var visibleCount = 0;
            var fragment = document.createDocumentFragment();

            orderedRows.forEach(function (row) {
                var matches = !query || (row.dataset.searchText || '').indexOf(query) !== -1;
                row.style.display = matches ? '' : 'none';
                if (matches) {
                    visibleCount += 1;
                    fragment.appendChild(row);
                }
            });

            if (serverEmptyRow) {
                serverEmptyRow.style.display = rows.length === 0 ? '' : 'none';
            }

            if (visibleCount === 0 && rows.length > 0) {
                noMatchRow.style.display = '';
                fragment.appendChild(noMatchRow);
            } else {
                noMatchRow.style.display = 'none';
            }

            tableBody.innerHTML = '';
            if (rows.length === 0 && serverEmptyRow) {
                tableBody.appendChild(serverEmptyRow);
            } else {
                tableBody.appendChild(fragment);
            }
        }

        headers.forEach(function (header) {
            header.addEventListener('click', function () {
                var nextIndex = Number(this.getAttribute('data-sort-index'));
                if (currentSortIndex === nextIndex) {
                    currentSortDir = currentSortDir === 'asc' ? 'desc' : 'asc';
                } else {
                    currentSortIndex = nextIndex;
                    currentSortDir = 'asc';
                }
                updateHeaderState();
                renderRows();
            });
        });

        if (filterInput) {
            filterInput.addEventListener('input', renderRows);
        }

        updateHeaderState();
        renderRows();
    });
</script>
</body>
</html>
