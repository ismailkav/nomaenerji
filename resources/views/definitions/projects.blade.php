<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projeler - NomaEnerji</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
<div class="dashboard-container">
    @include('partials.sidebar', ['active' => 'projects'])

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
                Projeler
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
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                    <h1 style="font-size:1.4rem;font-weight:600;">Projeler</h1>
                    <button type="button" id="addProjectRow" class="form-header-btn save" style="padding:0.4rem 1rem;font-size:0.9rem;">Yeni</button>
                </div>

                <form method="POST" action="{{ route('definitions.projects.save') }}">
                    @csrf
                    <div class="products-table-wrapper" style="overflow-x:auto;">
                        <table style="width:100%;border-collapse:collapse;font-size:0.9rem;">
                            <thead>
                            <tr>
                                <th style="text-align:left;padding:0.6rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">Proje Kodu</th>
                                <th style="text-align:center;padding:0.6rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">Pasif</th>
                                <th style="text-align:right;padding:0.6rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">İşlem</th>
                            </tr>
                            </thead>
                            <tbody id="projectsTableBody">
                            @forelse($projects as $index => $project)
                                <tr>
                                    <td style="padding:0.5rem 0.5rem;">
                                        <input type="hidden" name="projects[{{ $index }}][id]" value="{{ $project->id }}">
                                        <input type="text" name="projects[{{ $index }}][kod]" value="{{ $project->kod }}" style="width:100%;">
                                    </td>
                                    <td style="padding:0.5rem 0.5rem;text-align:center;">
                                        <input type="checkbox" name="projects[{{ $index }}][pasif]" value="1" {{ $project->pasif ? 'checked' : '' }}>
                                    </td>
                                    <td style="padding:0.5rem 0.5rem;text-align:right;">
                                        <button type="button" class="row-delete-btn" style="background:none;border:none;color:#ef4444;font-size:0.85rem;cursor:pointer;">Sil</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td style="padding:0.5rem 0.5rem;">
                                        <input type="hidden" name="projects[0][id]" value="">
                                        <input type="text" name="projects[0][kod]" value="" style="width:100%;" placeholder="Proje kodu">
                                    </td>
                                    <td style="padding:0.5rem 0.5rem;text-align:center;">
                                        <input type="checkbox" name="projects[0][pasif]" value="1">
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
        var addButton = document.getElementById('addProjectRow');
        var tbody = document.getElementById('projectsTableBody');

        if (!addButton || !tbody) {
            return;
        }

        var index = tbody.querySelectorAll('tr').length;

        addButton.addEventListener('click', function () {
            var tr = document.createElement('tr');
            tr.innerHTML =
                '<td style="padding:0.5rem 0.5rem;">' +
                '<input type="hidden" name="projects[' + index + '][id]" value="">' +
                '<input type="text" name="projects[' + index + '][kod]" value="" style="width:100%;" placeholder="Proje kodu">' +
                '</td>' +
                '<td style="padding:0.5rem 0.5rem;text-align:center;">' +
                '<input type="checkbox" name="projects[' + index + '][pasif]" value="1">' +
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
                if (row && confirm('Bu projeyi silmek istiyor musunuz?')) {
                    row.remove();
                }
            }
        });
    })();
</script>
</body>
</html>

