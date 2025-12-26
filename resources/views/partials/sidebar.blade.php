<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="NomaEnerji Logo" class="logo-full">
            <img src="{{ asset('images/logo-icon.png') }}" alt="NomaEnerji Icon" class="logo-icon">
        </div>
    </div>

    <nav class="sidebar-nav">
        <a href="{{ route('dashboard') }}"
           class="nav-item {{ ($active ?? 'dashboard') === 'dashboard' ? 'active' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round"
                      stroke-linejoin="round"/>
                <path d="M9 22V12h6v10"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round"
                      stroke-linejoin="round"/>
            </svg>
            <span class="nav-text">Gösterge Paneli</span>
        </a>

        <a href="{{ route('offers.index') }}"
           class="nav-item {{ ($active ?? '') === 'offers' ? 'active' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path d="M4 4h16v4H4z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M4 10h16v4H4z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M4 16h10v4H4z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="nav-text">Teklifler</span>
        </a>

        <a href="#" id="ordersToggle"
           class="nav-item {{ in_array(($active ?? ''), ['orders','purchase-orders','sales-orders','order-planning']) ? 'active' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path d="M7 4h14l-1 14H8L7 4z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M10 4V3a2 2 0 0 1 4 0v1" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M6 8H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span class="nav-text">Siparişler</span>
        </a>

        <div id="ordersSubmenu" style="display:none;">
            <a href="{{ route('orders.index', ['tur' => 'alim']) }}"
               class="nav-item {{ ($active ?? '') === 'purchase-orders' ? 'active' : '' }}"
               style="padding-left:2.75rem;font-size:0.9rem;">
                <span class="nav-text">Alım Siparişleri</span>
            </a>
            <a href="{{ route('orders.index', ['tur' => 'satis']) }}"
               class="nav-item {{ ($active ?? '') === 'sales-orders' ? 'active' : '' }}"
               style="padding-left:2.75rem;font-size:0.9rem;">
                <span class="nav-text">Satış Siparişleri</span>
            </a>
            <a href="{{ route('orders.planning') }}"
               class="nav-item {{ ($active ?? '') === 'order-planning' ? 'active' : '' }}"
               style="padding-left:2.75rem;font-size:0.9rem;">
                <span class="nav-text">Sipariş Planlama</span>
            </a>
        </div>

        <a href="{{ route('users.index') }}"
           class="nav-item {{ ($active ?? '') === 'users' ? 'active' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round"
                      stroke-linejoin="round"/>
                <circle cx="12" cy="7" r="4"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round"/>
            </svg>
            <span class="nav-text">Kullanıcılar</span>
        </a>

        <a href="{{ route('firms.index') }}"
           class="nav-item {{ ($active ?? '') === 'firms' ? 'active' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path d="M3 21V8a2 2 0 012-2h4v15"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round"
                      stroke-linejoin="round"/>
                <path d="M10 21V4a2 2 0 012-2h4a2 2 0 012 2v17"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round"
                      stroke-linejoin="round"/>
                <path d="M3 21h18"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round"
                      stroke-linejoin="round"/>
            </svg>
            <span class="nav-text">Firmalar</span>
        </a>

        <a href="{{ route('products.index') }}"
           class="nav-item {{ ($active ?? '') === 'products' ? 'active' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path d="M3 9l9-7 9 7-9 7-9-7z" stroke="currentColor" stroke-width="2"
                      stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M3 15l9 7 9-7" stroke="currentColor" stroke-width="2"
                      stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M12 2v14" stroke="currentColor" stroke-width="2"
                      stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="nav-text">Ürünler</span>
        </a>

        <a href="#" id="definitionsToggle"
           class="nav-item {{ in_array(($active ?? ''), ['cari-groups','product-groups','islem-turleri','projects']) ? 'active' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path d="M4 4h16v4H4z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M4 10h16v4H4z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M4 16h10v4H4z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="nav-text">Tanımlamalar</span>
        </a>

        <div id="definitionsSubmenu" style="display:none;">
            <a href="{{ route('definitions.cari-groups') }}"
               class="nav-item {{ ($active ?? '') === 'cari-groups' ? 'active' : '' }}"
               style="padding-left:2.75rem;font-size:0.9rem;">
                <span class="nav-text">Cari Grup</span>
            </a>
            <a href="{{ route('definitions.product-groups') }}"
               class="nav-item {{ ($active ?? '') === 'product-groups' ? 'active' : '' }}"
               style="padding-left:2.75rem;font-size:0.9rem;">
                <span class="nav-text">Ürün Grup</span>
            </a>
            <a href="{{ route('definitions.islem-turleri') }}"
               class="nav-item {{ ($active ?? '') === 'islem-turleri' ? 'active' : '' }}"
               style="padding-left:2.75rem;font-size:0.9rem;">
                <span class="nav-text">İşlem Türü</span>
            </a>
            <a href="{{ route('definitions.projects') }}"
               class="nav-item {{ ($active ?? '') === 'projects' ? 'active' : '' }}"
               style="padding-left:2.75rem;font-size:0.9rem;">
                <span class="nav-text">Projeler</span>
            </a>
        </div>
    </nav>

    <div class="sidebar-footer">
        <div class="user-profile">
            <div class="user-avatar">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="sidebar-logout-form">
            @csrf
            <button type="submit" title="Çıkış Yap" class="sidebar-logout-button">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </form>
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var toggle = document.getElementById('definitionsToggle');
        var submenu = document.getElementById('definitionsSubmenu');

        if (!toggle || !submenu) {
            return;
        }

        if (toggle.classList.contains('active')) {
            submenu.style.display = 'block';
        }

        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            if (submenu.style.display === 'none' || submenu.style.display === '') {
                submenu.style.display = 'block';
            } else {
                submenu.style.display = 'none';
            }
        });

        var ordersToggle = document.getElementById('ordersToggle');
        var ordersSubmenu = document.getElementById('ordersSubmenu');

        if (ordersToggle && ordersSubmenu) {
            if (ordersToggle.classList.contains('active')) {
                ordersSubmenu.style.display = 'block';
            }

            ordersToggle.addEventListener('click', function (e) {
                e.preventDefault();
                if (ordersSubmenu.style.display === 'none' || ordersSubmenu.style.display === '') {
                    ordersSubmenu.style.display = 'block';
                } else {
                    ordersSubmenu.style.display = 'none';
                }
            });
        }
    });
</script>
