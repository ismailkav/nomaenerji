<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürünler - NomaEnerji</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
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
                Ürünler
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
            <div class="products-card" style="background:#fff;border-radius:16px;border:1px solid #e5e7eb;box-shadow:0 18px 45px rgba(15,23,42,0.06);padding:1.75rem 1.5rem;">
                <div class="products-header" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
                    <div>
                        <h1 style="font-size:1.5rem;font-weight:600;margin-bottom:0.25rem;">Ürünler</h1>
                    </div>
                    <form method="GET" action="{{ route('products.index') }}" style="display:flex;align-items:center;gap:0.75rem;">
                        <input type="text"
                               name="q"
                               value="{{ request('q') }}"
                               placeholder="Ürün ara..."
                               style="padding:0.55rem 0.8rem;border-radius:999px;border:1px solid #e2e8f0;font-size:0.9rem;min-width:220px;outline:none;">

                        <select name="kategori_id" onchange="this.form.submit()" style="padding:0.55rem 0.9rem;border-radius:999px;border:1px solid #e2e8f0;font-size:0.9rem;min-width:160px;outline:none;background-color:#fff;">
                            <option value="">Tüm kategoriler</option>
                            @isset($categories)
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ (string)request('kategori_id') === (string)$category->id ? 'selected' : '' }}>
                                        {{ $category->ad }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>

                        <select name="durum" onchange="this.form.submit()" style="padding:0.55rem 0.9rem;border-radius:999px;border:1px solid #e2e8f0;font-size:0.9rem;min-width:140px;outline:none;background-color:#fff;">
                            <option value="">Tüm durumlar</option>
                            <option value="aktif" {{ request('durum') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="pasif" {{ request('durum') === 'pasif' ? 'selected' : '' }}>Pasif</option>
                        </select>

                        <button type="submit" style="display:none;"></button>

                        <button type="button" id="viewToggleButton" style="padding:0.55rem 1.1rem;border-radius:999px;border:1px solid #111827;background-color:#111827;color:#fff;font-size:0.9rem;font-weight:500;cursor:pointer;">Grid Görünüm</button>
                        <a href="{{ route('products.create') }}" style="padding:0.55rem 1.1rem;border-radius:999px;border:none;background-color:#111827;color:#fff;font-size:0.9rem;font-weight:500;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;cursor:pointer;">Ürün Ekle</a>
                    </form>
                </div>

                <div id="productsTableWrapper" class="products-table-wrapper" style="overflow-x:auto;">
                    <table class="products-table" style="width:100%;border-collapse:collapse;font-size:0.9rem;">
                        <thead>
                        <tr>
                            <th style="text-align:left;padding:0.75rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">Görsel</th>
                            <th style="text-align:left;padding:0.75rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">Kod</th>
                            <th style="text-align:left;padding:0.75rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">İsim</th>
                            <th style="text-align:left;padding:0.75rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">Kategori</th>
                            <th style="text-align:left;padding:0.75rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">Fiyat</th>
                            <th style="text-align:left;padding:0.75rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">KDV Oran</th>
                            <th style="text-align:left;padding:0.75rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">Pasif</th>
                            <th style="text-align:right;padding:0.75rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">İşlemler</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td style="padding:0.75rem 0.5rem;">
                                    @if($product->resim_yolu)
                                        <img src="{{ asset($product->resim_yolu) }}" alt="{{ $product->kod }}" style="width:32px;height:32px;border-radius:8px;object-fit:cover;">
                                    @else
                                        <div style="width:32px;height:32px;border-radius:10px;border:1px solid #e5e7eb;display:flex;align-items:center;justify-content:center;color:#6b7280;">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M3 7l9-4 9 4-9 4-9-4zM3 17l9 4 9-4M3 12l9 4 9-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                    @endif
                                </td>
                                <td style="padding:0.75rem 0.5rem;font-weight:500;">{{ $product->kod }}</td>
                                <td style="padding:0.75rem 0.5rem;">{{ $product->aciklama }}</td>
                                <td style="padding:0.75rem 0.5rem;">{{ optional($product->category)->ad ?? '-' }}</td>
                                <td style="padding:0.75rem 0.5rem;">{{ $product->satis_doviz ?? 'TL' }} {{ number_format($product->satis_fiyat, 2, ',', '.') }}</td>
                                <td style="padding:0.75rem 0.5rem;">% {{ $product->kdv_oran }}</td>
                                <td style="padding:0.75rem 0.5rem;">
                                    <div class="toggle" style="position:relative;width:44px;height:24px;border-radius:999px;background-color:{{ $product->pasif ? '#111827' : '#e5e7eb' }};transition:background-color 0.2s;">
                                        <div style="position:absolute;top:2px;left:{{ $product->pasif ? '22px' : '2px' }};width:20px;height:20px;border-radius:999px;background-color:#fff;box-shadow:0 1px 3px rgba(0,0,0,0.2);transition:left 0.2s;"></div>
                                    </div>
                                </td>
                                <td style="padding:0.75rem 0.5rem;text-align:right;">
                                    <a href="{{ route('products.edit', $product) }}" style="display:inline-flex;align-items:center;justify-content:center;padding:0.4rem 0.9rem;border-radius:999px;border:1px solid #e5e7eb;background-color:#fff;font-size:0.85rem;color:#111827;text-decoration:none;margin-right:0.4rem;">Düzenle</a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Bu ürünü silmek istiyor musunuz?')" style="display:inline-flex;align-items:center;justify-content:center;padding:0.4rem 0.9rem;border-radius:999px;border:none;background-color:#ef4444;font-size:0.85rem;color:#fff;cursor:pointer;">Sil</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="padding:1.25rem 0.5rem;text-align:center;color:#9ca3af;font-size:0.9rem;">
                                    Henüz ürün eklenmemiş.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div id="productsGridWrapper" style="display:none;margin-top:1rem;">
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1.25rem;">
                        @forelse($products as $product)
                            <div style="background:#fff;border-radius:18px;border:1px solid #e5e7eb;box-shadow:0 18px 45px rgba(15,23,42,0.06);padding:1.5rem;display:flex;flex-direction:column;justify-content:space-between;min-height:260px;">
                                <div>
                                    <div style="font-size:1.1rem;font-weight:600;margin-bottom:0.75rem;">{{ $product->kod }}</div>
                                    <div style="margin-bottom:1rem;">
                                        @if($product->resim_yolu)
                                            <img src="{{ asset($product->resim_yolu) }}" alt="{{ $product->kod }}" style="width:96px;height:96px;border-radius:18px;object-fit:cover;">
                                        @else
                                            <div style="width:96px;height:96px;border-radius:24px;border:1px solid #e5e7eb;display:flex;align-items:center;justify-content:center;color:#111827;">
                                                <svg width="56" height="56" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M3 7l9-4 9 4-9 4-9-4zM3 17l9 4 9-4M3 12l9 4 9-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div style="font-size:0.9rem;color:#6b7280;margin-bottom:1.25rem;">
                                        {{ $product->aciklama }}
                                    </div>
                                </div>
                                <div style="display:flex;align-items:center;justify-content:space-between;">
                                    <div style="font-size:1.2rem;font-weight:600;">
                                        {{ $product->satis_doviz ?? 'TL' }} {{ number_format($product->satis_fiyat, 2, ',', '.') }}
                                    </div>
                                    <div>
                                        <a href="{{ route('products.edit', $product) }}" style="display:inline-flex;align-items:center;justify-content:center;padding:0.45rem 0.95rem;border-radius:999px;border:1px solid #e5e7eb;background-color:#fff;font-size:0.85rem;color:#111827;text-decoration:none;margin-right:0.4rem;">Düzenle</a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Bu ürünü silmek istiyor musunuz?')" style="display:inline-flex;align-items:center;justify-content:center;padding:0.45rem 0.95rem;border-radius:999px;border:none;background-color:#ef4444;font-size:0.85rem;color:#fff;cursor:pointer;">Sil</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div style="padding:1.25rem 0.5rem;text-align:center;color:#9ca3af;font-size:0.9rem;">
                                Henüz ürün eklenmemiş.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div style="margin-top:1.25rem;">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
        </section>
    </main>
</div>
<script src="{{ asset('js/dashboard.js') }}"></script>
<script>
    (function () {
        var toggleButton = document.getElementById('viewToggleButton');
        var tableWrapper = document.getElementById('productsTableWrapper');
        var gridWrapper = document.getElementById('productsGridWrapper');

        if (!toggleButton || !tableWrapper || !gridWrapper) {
            return;
        }

        var isGrid = false;

        toggleButton.addEventListener('click', function () {
            isGrid = !isGrid;

            if (isGrid) {
                tableWrapper.style.display = 'none';
                gridWrapper.style.display = 'block';
                toggleButton.textContent = 'Tablo Görünüm';
            } else {
                tableWrapper.style.display = 'block';
                gridWrapper.style.display = 'none';
                toggleButton.textContent = 'Grid Görünüm';
            }
        });
    })();
</script>
</body>
</html>
