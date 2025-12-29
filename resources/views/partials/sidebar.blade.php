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
               class="nav-item nav-sub-item {{ ($active ?? '') === 'purchase-orders' ? 'active' : '' }}"
               style="padding-left:2.75rem;font-size:0.9rem;">
                <span class="nav-text">Alım Siparişleri</span>
            </a>
            <a href="{{ route('orders.index', ['tur' => 'satis']) }}"
               class="nav-item nav-sub-item {{ ($active ?? '') === 'sales-orders' ? 'active' : '' }}"
               style="padding-left:2.75rem;font-size:0.9rem;">
                <span class="nav-text">Satış Siparişleri</span>
            </a>
            <a href="{{ route('orders.planning') }}"
               class="nav-item nav-sub-item {{ ($active ?? '') === 'order-planning' ? 'active' : '' }}"
               style="padding-left:2.75rem;font-size:0.9rem;">
                <span class="nav-text">Sipariş Planlama</span>
            </a>
        </div>

        <a href="#" id="invoicesToggle"
           class="nav-item {{ in_array(($active ?? ''), ['purchase-invoices','sales-invoices','purchase-return-invoices','sales-return-invoices']) ? 'active' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path d="M6 2h9l3 3v17a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Z"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M14 2v4h4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M8 11h8M8 15h8M8 19h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="nav-text">Fatura</span>
        </a>

        <div id="invoicesSubmenu" style="display:none;">
            <a href="{{ route('invoices.index', ['tur' => 'alim']) }}"
               class="nav-item nav-sub-item {{ ($active ?? '') === 'purchase-invoices' ? 'active' : '' }}"
               style="padding-left:2.75rem;font-size:0.9rem;">
                <span class="nav-text">Alım Faturası</span>
            </a>
            <a href="{{ route('invoices.index', ['tur' => 'satis']) }}"
               class="nav-item nav-sub-item {{ ($active ?? '') === 'sales-invoices' ? 'active' : '' }}"
               style="padding-left:2.75rem;font-size:0.9rem;">
                <span class="nav-text">Satış Faturası</span>
            </a>
            <a href="{{ route('invoices.index', ['tur' => 'alim-iade']) }}"
               class="nav-item nav-sub-item {{ ($active ?? '') === 'purchase-return-invoices' ? 'active' : '' }}"
               style="padding-left:2.75rem;font-size:0.9rem;">
                <span class="nav-text">Alım İade Faturası</span>
            </a>
            <a href="{{ route('invoices.index', ['tur' => 'satis-iade']) }}"
               class="nav-item nav-sub-item {{ ($active ?? '') === 'sales-return-invoices' ? 'active' : '' }}"
               style="padding-left:2.75rem;font-size:0.9rem;">
                <span class="nav-text">Satış İade Faturası</span>
            </a>
        </div>

        <a href="#" id="stockToggle"
           class="nav-item {{ in_array(($active ?? ''), ['stock-consignment-in','stock-consignment-out','stock-count-in','stock-count-out','stock-depot-transfer','stock-inventory','stock-ledger']) ? 'active' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M3.27 6.96 12 12.01l8.73-5.05" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M12 22.08V12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="nav-text">Stok</span>
        </a>

        <div id="stockSubmenu" style="display:none;">
            <a href="{{ route('stock.count-in.index') }}"
               class="nav-item nav-sub-item {{ ($active ?? '') === 'stock-count-in' ? 'active' : '' }}"
               style="padding-left:2.75rem;font-size:0.9rem;">
                <span class="nav-text">Sayım Giriş</span>
            </a>
            <a href="{{ route('stock.count-out.index') }}"
               class="nav-item nav-sub-item {{ ($active ?? '') === 'stock-count-out' ? 'active' : '' }}"
               style="padding-left:2.75rem;font-size:0.9rem;">
                <span class="nav-text">Sayım Çıkış</span>
            </a>
            <a href="{{ route('stock.depot-transfer.index') }}"
               class="nav-item nav-sub-item {{ ($active ?? '') === 'stock-depot-transfer' ? 'active' : '' }}"
               style="padding-left:2.75rem;font-size:0.9rem;">
                <span class="nav-text">Depo Transfer</span>
            </a>
            <a href="{{ route('stock.consignment-in.index') }}"
               class="nav-item nav-sub-item {{ ($active ?? '') === 'stock-consignment-in' ? 'active' : '' }}"
               style="padding-left:2.75rem;font-size:0.9rem;">
                <span class="nav-text">Konsinye Giriş</span>
            </a>
            <a href="{{ route('stock.consignment-out.index') }}"
               class="nav-item nav-sub-item {{ ($active ?? '') === 'stock-consignment-out' ? 'active' : '' }}"
               style="padding-left:2.75rem;font-size:0.9rem;">
                <span class="nav-text">Konsinye Çıkış</span>
            </a>
            <a href="{{ route('stock.inventory.index') }}"
               class="nav-item nav-sub-item {{ ($active ?? '') === 'stock-inventory' ? 'active' : '' }}"
               style="padding-left:2.75rem;font-size:0.9rem;">
                <span class="nav-text">Stok Envanter</span>
            </a>
            <a href="{{ url('/stok/ekstre') }}"
               class="nav-item nav-sub-item {{ ($active ?? '') === 'stock-ledger' ? 'active' : '' }}"
               style="padding-left:2.75rem;font-size:0.9rem;">
                <span class="nav-text">Stok Ekstre</span>
            </a>
        </div>

        <a href="{{ route('users.index') }}"
           id="navUsers"
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
           id="navFirms"
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
           id="navProducts"
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
           class="nav-item {{ in_array(($active ?? ''), ['users','firms','products','cari-groups','product-groups','islem-turleri','projects','depots']) ? 'active' : '' }}">
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
               class="nav-item nav-sub-item {{ ($active ?? '') === 'cari-groups' ? 'active' : '' }}"
               style="padding-left:2.75rem;font-size:0.9rem;">
                <span class="nav-text">Cari Grup</span>
            </a>
            <a href="{{ route('definitions.product-groups') }}"
               class="nav-item nav-sub-item {{ ($active ?? '') === 'product-groups' ? 'active' : '' }}"
               style="padding-left:2.75rem;font-size:0.9rem;">
                <span class="nav-text">Ürün Grup</span>
            </a>
            <a href="{{ route('definitions.islem-turleri') }}"
               class="nav-item nav-sub-item {{ ($active ?? '') === 'islem-turleri' ? 'active' : '' }}"
               style="padding-left:2.75rem;font-size:0.9rem;">
                <span class="nav-text">İşlem Türü</span>
            </a>
            <a href="{{ route('definitions.projects') }}"
               class="nav-item nav-sub-item {{ ($active ?? '') === 'projects' ? 'active' : '' }}"
               style="padding-left:2.75rem;font-size:0.9rem;">
                <span class="nav-text">Projeler</span>
            </a>
            <a href="{{ route('definitions.depots') }}"
               class="nav-item nav-sub-item {{ ($active ?? '') === 'depots' ? 'active' : '' }}"
               style="padding-left:2.75rem;font-size:0.9rem;">
                <span class="nav-text">Depo</span>
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

        function moveToDefinitionsSubmenu(link) {
            if (!link) return;
            link.classList.add('nav-sub-item');
            link.style.paddingLeft = '2.75rem';
            link.style.fontSize = '0.9rem';
            var icon = link.querySelector('svg');
            if (icon) icon.style.display = 'none';
            submenu.insertBefore(link, submenu.firstChild);
        }

        moveToDefinitionsSubmenu(document.getElementById('navProducts'));
        moveToDefinitionsSubmenu(document.getElementById('navFirms'));
        moveToDefinitionsSubmenu(document.getElementById('navUsers'));

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

        var invoicesToggle = document.getElementById('invoicesToggle');
        var invoicesSubmenu = document.getElementById('invoicesSubmenu');

        if (invoicesToggle && invoicesSubmenu) {
            if (invoicesToggle.classList.contains('active')) {
                invoicesSubmenu.style.display = 'block';
            }

            invoicesToggle.addEventListener('click', function (e) {
                e.preventDefault();
                if (invoicesSubmenu.style.display === 'none' || invoicesSubmenu.style.display === '') {
                    invoicesSubmenu.style.display = 'block';
                } else {
                    invoicesSubmenu.style.display = 'none';
                }
            });
        }

        var stockToggle = document.getElementById('stockToggle');
        var stockSubmenu = document.getElementById('stockSubmenu');

        if (stockToggle && stockSubmenu) {
            if (stockToggle.classList.contains('active')) {
                stockSubmenu.style.display = 'block';
            }

            stockToggle.addEventListener('click', function (e) {
                e.preventDefault();
                if (stockSubmenu.style.display === 'none' || stockSubmenu.style.display === '') {
                    stockSubmenu.style.display = 'block';
                } else {
                    stockSubmenu.style.display = 'none';
                }
            });
        }
    });
</script>
