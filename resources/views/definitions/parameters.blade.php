<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parametreler - NomaEnerji</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
<div class="dashboard-container">
    @include('partials.sidebar', ['active' => 'parameters'])

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
                Parametreler
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
                <h1 style="font-size:1.4rem;font-weight:600;margin:0 0 1rem 0;">Parametreler</h1>
                @if (session('status'))
                    <div class="alert alert-success" style="margin-bottom:1rem;">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('definitions.parameters.save') }}">
                    @csrf
                    <div class="products-table-wrapper" style="overflow-x:auto;">
                        <table style="width:100%;border-collapse:collapse;font-size:0.9rem;">
                            <thead>
                            <tr>
                                <th style="width:260px;text-align:left;padding:0.6rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">Başlık</th>
                                <th style="text-align:left;padding:0.6rem 0.5rem;border-bottom:1px solid #e5e7eb;font-weight:500;color:#6b7280;">Değer</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td style="padding:0.5rem 0.5rem;">Tomcat Ip</td>
                                <td style="padding:0.5rem 0.5rem;">
                                    <input type="text" name="tomcat_ip" value="{{ old('tomcat_ip', $tomcatIp ?? '') }}" style="width:100%;" placeholder="Örn: 127.0.0.1">
                                    @error('tomcat_ip')<div class="form-error">{{ $message }}</div>@enderror
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0.5rem 0.5rem;">Tomcat Port</td>
                                <td style="padding:0.5rem 0.5rem;">
                                    <input type="number" name="tomcat_port" value="{{ old('tomcat_port', $tomcatPort ?? '') }}" style="width:100%;" placeholder="Örn: 8080" min="1" max="65535">
                                    @error('tomcat_port')<div class="form-error">{{ $message }}</div>@enderror
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0.5rem 0.5rem;">Tomcat Proje</td>
                                <td style="padding:0.5rem 0.5rem;">
                                    <input type="text" name="tomcat_proje" value="{{ old('tomcat_proje', $tomcatProje ?? '') }}" style="width:100%;" placeholder="Örn: NomaEnerji">
                                    @error('tomcat_proje')<div class="form-error">{{ $message }}</div>@enderror
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0.5rem 0.5rem;">Resim Yol</td>
                                <td style="padding:0.5rem 0.5rem;">
                                    <input type="text" name="resim_yol" value="{{ old('resim_yol', $resimYol ?? '') }}" style="width:100%;" placeholder="Örn: http://localhost:8000/storage/products/">
                                    @error('resim_yol')<div class="form-error">{{ $message }}</div>@enderror
                                </td>
                            </tr>
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
</body>
</html>
