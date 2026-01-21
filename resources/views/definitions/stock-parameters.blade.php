<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Parametre - NomaEnerji</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
<div class="dashboard-container">
    @include('partials.sidebar', ['active' => 'stock-parameters'])

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
                Stok Parametre
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
                <div style="display:flex;align-items:center;justify-content:space-between;gap:0.75rem;flex-wrap:wrap;margin-bottom:1rem;">
                    <h1 style="font-size:1.4rem;font-weight:600;margin:0;">Stok Parametre</h1>

                    <div style="display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap;">
                        <form method="GET" action="{{ route('definitions.stock-parameters') }}" style="margin:0;">
                            <select name="parametre_no" onchange="this.form.submit()" style="padding:0.45rem 0.9rem;border-radius:999px;border:1px solid #e2e8f0;font-size:0.9rem;min-width:180px;outline:none;background-color:#fff;">
                                <option value="3" {{ (int)($selectedParameterNo ?? 3) === 3 ? 'selected' : '' }}>Parametre 3</option>
                                <option value="4" {{ (int)($selectedParameterNo ?? 3) === 4 ? 'selected' : '' }}>Parametre 4</option>
                            </select>
                        </form>

                        <button type="button" id="addStockParameterRow" class="form-header-btn save" style="padding:0.4rem 1rem;font-size:0.9rem;">Yeni</button>
                    </div>
                </div>

                @if (session('status'))
                    <div class="alert alert-success" style="margin-bottom:1rem;">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('definitions.stock-parameters.save') }}">
                    @csrf
                    <input type="hidden" name="parametre_no" value="{{ (int)($selectedParameterNo ?? 3) }}">

                    <div class="products-table-wrapper" style="overflow-x:auto;">
                        <table style="width:100%;border-collapse:collapse;font-size:0.9rem;">
                            <thead>
                            <tr>
                                <th style="text-align:left;padding:0.6rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">Değer</th>
                                <th style="width:110px;text-align:right;padding:0.6rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">İşlem</th>
                            </tr>
                            </thead>
                            <tbody id="stockParametersTableBody">
                            @forelse($items as $index => $item)
                                <tr>
                                    <td style="padding:0.5rem 0.5rem;">
                                        <input type="text" name="items[{{ $index }}][deger]" value="{{ $item->deger }}" style="width:100%;">
                                    </td>
                                    <td style="padding:0.5rem 0.5rem;text-align:right;">
                                        <button type="button" class="row-delete-btn" style="background:none;border:none;color:#ef4444;font-size:0.85rem;cursor:pointer;">Sil</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td style="padding:0.5rem 0.5rem;">
                                        <input type="text" name="items[0][deger]" value="" style="width:100%;" placeholder="Değer">
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
        var addButton = document.getElementById('addStockParameterRow');
        var tbody = document.getElementById('stockParametersTableBody');

        if (!addButton || !tbody) {
            return;
        }

        var index = tbody.querySelectorAll('tr').length;

        addButton.addEventListener('click', function () {
            var tr = document.createElement('tr');
            tr.innerHTML =
                '<td style="padding:0.5rem 0.5rem;">' +
                '<input type="text" name="items[' + index + '][deger]" value="" style="width:100%;" placeholder="Değer">' +
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
                if (row) {
                    row.remove();
                }
            }
        });
    })();
</script>
</body>
</html>

