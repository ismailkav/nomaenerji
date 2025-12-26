<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $siparisTuru = old('siparis_turu', $tur ?? ($siparis->siparis_turu ?? 'alim'));
        $siparisBaslik = $siparisTuru === 'satis' ? 'Satış Siparişi' : 'Alım Siparişi';
    @endphp
    <title>{{ isset($siparis) ? ($siparisBaslik . ' Düzenle') : $siparisBaslik }} - NomaEnerji</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        .offer-card {
            width: 100%;
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.06);
            padding: 1.5rem 1.25rem;
        }
        .offer-header {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            margin-bottom: 0.9rem;
            align-items: flex-start;
        }
        .offer-header-left {
            flex: 1 1 0;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .offer-header-right {
            flex: 0 0 320px;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .offer-header-row {
            display: flex;
            gap: 0.5rem;
        }
        .offer-header-row .form-group {
            flex: 1 1 0;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.15rem;
        }
        .form-group label {`n            font-size: 0.8rem;`n            font-weight: 500;`n            color: #2563eb;
            font-size: 0.8rem;
            font-weight: 500;
            color: #2563eb;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            border-radius: 999px;
            border: 1px solid #e5e7eb;
            padding: 0.5rem 0.85rem;
            font-size: 0.85rem;
        }
        .offer-header .form-group {
            flex-direction: row;
            align-items: center;
        }
        .offer-header .form-group label {`n            font-size: 0.8rem;`n            font-weight: 500;`n            color: #2563eb;
            font-weight: 600;
            margin-right: 0.5rem;
            min-width: 120px;
            margin-bottom: 0;
        }
        .offer-header .form-group input,
        \.offer-header \.form-group select {`n            border: none;`n            background: transparent;`n            padding: 0;`n            border-radius: 0;`n        }`n        .header-input {`n            border: none !important;`n            background: transparent !important;`n            padding: 0 !important;`n            border-radius: 0 !important;`n        }`n        .header-input {
            border: none;
            background: transparent;
            padding: 0;
            border-radius: 0;`n        }`n        .header-input {`n            border: none !important;`n            background: transparent !important;`n            padding: 0 !important;`n            border-radius: 0 !important;
        }
        .offer-header-right .form-group label {`n            font-size: 0.8rem;`n            font-weight: 500;`n            color: #2563eb;
            min-width: 110px;
        }
        .offer-header-right .form-group input,
        .offer-header-right .form-group select {
            max-width: 150px;
        }
        .form-group textarea {
            border-radius: 12px;
            min-height: 70px;
        }
        .input-with-button {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .small-btn {`n            border-radius: 999px;`n            border: none;`n            background: transparent;`n            color: #000000;
            border-radius: 999px;
            border: none;
            background: transparent;
            color: #2563eb;
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .small-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }
        .top-bar .small-btn[title="Excel"],
        .top-bar .small-btn[title="PDF"],
        .top-bar .small-btn[title*="Yazd"] {
            display: none;
        }
        .lines-header {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 0.5rem;
        }
        .lines-header button {
            border-radius: 999px;
            border: none;
            background: #e5e7eb;
            padding: 0.4rem 0.9rem;
            font-size: 0.8rem;
            cursor: pointer;
        }
        table.offer-lines {
            table-layout: fixed;
            width: 100%;
            border-collapse: collapse;
            font-size: 0.75rem;
        }
        .offer-lines th,
        .offer-lines td {
            border: 1px solid #e5e7eb;
            padding: 0.3rem 0.35rem;
        }
        .offer-lines thead {
            background: #f3f4f6;
        }
        .offer-lines th.stok-kod,
        .offer-lines td.stok-kod-cell { width: 12%; }
        .offer-lines th.stok-aciklama,
        .offer-lines td.stok-aciklama-cell { width: 16%; }
        .offer-lines th.detay,
        .offer-lines td.detay-cell { width: 4%; text-align: center; }
        .offer-lines th.durum,
        .offer-lines td.durum-cell { width: 4%; }
        .offer-lines th.iskonto,
        .offer-lines td.iskonto-cell { width: 4%; }
        .offer-lines th.birim-fiyat,
        .offer-lines td.birim-fiyat-cell { width: 8%; }
        .offer-lines th.miktar,
        .offer-lines td.miktar-cell { width: 6%; }
        .offer-lines th.doviz,
        .offer-lines td.doviz-cell { width: 4%; }
        .offer-lines th.kur,
        .offer-lines td.kur-cell { width: 4%; }
        .offer-lines th.kdv,
        .offer-lines td.kdv-cell { width: 6%; }
        .offer-lines th.kdv-durum,
        .offer-lines td.kdv-durum-cell { width: 6%; }
        .offer-lines th.satir-tutar,
        .offer-lines td.satir-tutar-cell { width: 16%; }
        .offer-lines input,
        .offer-lines select {
            width: 100%;
            border: none !important;
            background: transparent !important;
            box-shadow: none !important;
            font-size: 0.75rem;
            outline: none;
        }
        .offer-lines input[type="number"] {
            text-align: right;
        }
        .offer-summary-table td:first-child {
            padding-right: 1.5rem;
        }
        .actions {
            display: none;
        }
        .btn {
            padding: 0.45rem 1.2rem;
            border-radius: 999px;
            border: none;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
        }
        .btn-cancel {
            background: #ef4444;
            color: #fff;
        }
        .btn-save {
            background: #2563eb;
            color: #fff;
        }
        .top-menu-wrapper {
            position: relative;
        }
        .top-menu-dropdown {
            position: absolute;
            right: 0;
            top: 110%;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.15);
            padding: 0.25rem 0;
            min-width: 190px;
            z-index: 40;
            display: none;
        }
        .top-menu-dropdown.open {
            display: block;
        }
        .top-menu-item {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 0.35rem 0.75rem;
            font-size: 0.8rem;
            border: none;
            background: transparent;
            cursor: pointer;
            gap: 0.45rem;
            text-align: left;
        }
        .top-menu-item:hover {
            background: #f3f4f6;
        }
        .top-menu-item svg {
            flex-shrink: 0;
        }
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 50;
        }
        .modal {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(15, 23, 42, 0.25);
            max-width: 720px;
            width: 100%;
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
        }
        .modal-title {
            font-size: 0.9rem;
            font-weight: 600;
        }
        .modal-body {
            padding: 0.75rem 1rem;
            overflow: auto;
        }
        .modal-actions {
            padding: 0.75rem 1rem;
            border-top: 1px solid #e5e7eb;
            text-align: right;
        }
        .modal-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8rem;
        }
        .modal-table th,
        .modal-table td {
            border-bottom: 1px solid #e5e7eb;
            padding: 0.35rem 0.4rem;
        }
        .modal-table thead {
            background: #f3f4f6;
        }
        .modal-table tr:hover {
            background: #e5e7eb;
            cursor: pointer;
        }
        .authority-list {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }
        .authority-list li {
            padding: 0.35rem 0.4rem;
            border-bottom: 1px solid #e5e7eb;
            cursor: pointer;
        }
        .authority-list li:hover {
            background: #f3f4f6;
        }
            .offer-header input,
        .offer-header select,
        .offer-header textarea {
            border: none !important;
            background: transparent !important;
            box-shadow: none !important;
            outline: none;
        }
            .offer-header label {\n            color: #2563eb !important;\n        }\n        .offer-header .input-with-button span,\n        .offer-header input,\n        .offer-header select {\n            color: #000000 !important;\n        }\n        .small-btn {\n            color: #000000 !important;\n        }\n    </style>
</head>
<body>
<div class="dashboard-container">
    @include('partials.sidebar', ['active' => $active ?? 'orders'])

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
                        <path d="M15 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                              stroke-linejoin="round"/>
                    </svg>
                </button>
                {{ isset($siparis) ? ($siparisBaslik . ' Düzenle') : $siparisBaslik }}
            </div>
            <div style="margin-left:auto; display:flex; align-items:center; gap:0.5rem;">
                <div style="display:flex; align-items:center; gap:0.35rem;">
                    <button type="button" class="small-btn" title="Excel">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="3" y="4" width="18" height="16" rx="2" ry="2" stroke="currentColor" stroke-width="2"/>
                            <path d="M9 9l6 6M15 9l-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                    <button type="button" class="small-btn" title="PDF">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="4" y="3" width="14" height="18" rx="2" ry="2" stroke="currentColor" stroke-width="2"/>
                            <path d="M8 8h8M8 12h5M8 16h6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                    <button type="button" class="small-btn" title="Yazdır" onclick="window.print()">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="6" y="3" width="12" height="6" stroke="currentColor" stroke-width="2"/>
                            <rect x="6" y="13" width="12" height="8" stroke="currentColor" stroke-width="2"/>
                            <rect x="5" y="9" width="14" height="4" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </button>
                    @if(isset($siparis))
                        <a href="#" target="_blank" class="small-btn" title="Yazdır" onclick="return false;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="6" y="3" width="12" height="6" stroke="currentColor" stroke-width="2"/>
                                <rect x="6" y="13" width="12" height="8" stroke="currentColor" stroke-width="2"/>
                                <rect x="5" y="9" width="14" height="4" stroke="currentColor" stroke-width="2"/>
                            </svg>
                        </a>
                    @endif
                </div>
                <div class="top-menu-wrapper">
                    <button type="button" class="small-btn" id="topMenuToggle" title="Menü">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                    <div class="top-menu-dropdown" id="topMenuDropdown">
                        <button type="button" class="top-menu-item" id="menuPdf" disabled>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="4" y="3" width="14" height="18" rx="2" ry="2" stroke="currentColor" stroke-width="2"/>
                                <path d="M8 8h8M8 12h5M8 16h6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            <span>PDF Kaydet</span>
                        </button>
                        <button type="button" class="top-menu-item" id="menuExcel">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="3" y="4" width="18" height="16" rx="2" ry="2" stroke="currentColor" stroke-width="2"/>
                                <path d="M9 9l6 6M15 9l-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            <span>Excel Kaydet</span>
                        </button>
                        <button type="button" class="top-menu-item" id="menuPrint">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="6" y="3" width="12" height="6" stroke="currentColor" stroke-width="2"/>
                                <rect x="6" y="13" width="12" height="8" stroke="currentColor" stroke-width="2"/>
                                <rect x="5" y="9" width="14" height="4" stroke="currentColor" stroke-width="2"/>
                            </svg>
                            <span>Yazdır</span>
                        </button>
                        <button type="button" class="top-menu-item" id="menuOrder">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7 4h10l-1 14H8L7 4z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M10 4V3a2 2 0 0 1 4 0v1" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            <span>Sipariş Oluştur</span>
                        </button>
                        <button type="button"
                                class="top-menu-item"
                                id="menuOrderDelete"
                                @unless(isset($siparis)) disabled style="opacity:0.4;cursor:not-allowed;" @endunless
                                style="color:#dc2626;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 6h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <path d="M8 6V4h8v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M7 6l1 14h8l1-14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M10 11v6M14 11v6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            <span>Sipariş Sil</span>
                        </button>
                    </div>
                </div>
                <a href="{{ route('orders.index', ['tur' => $tur ?? 'alim']) }}" class="btn btn-cancel" style="margin-left:0.75rem;">İptal</a>
                <button type="submit" form="orderForm" class="btn btn-save">Kaydet</button>
            </div>
        </header>

          <section class="content-section" style="padding: 2rem;">
            @if(isset($siparis))
                <form id="deleteOrderForm" method="POST" action="{{ route('orders.destroy', $siparis) }}" style="display:none;">
                    @csrf
                    @method('DELETE')
                </form>
            @else
                <form id="deleteOrderForm" style="display:none;"></form>
            @endif
            <form id="orderForm" method="POST" action="{{ isset($siparis) ? route('orders.update', $siparis) : route('orders.store') }}">
                 @csrf
                 @if(isset($siparis))
                     @method('PUT')
                 @endif
                 <input type="hidden" name="siparis_turu" value="{{ old('siparis_turu', $tur ?? ($siparis->siparis_turu ?? 'alim')) }}">
                <div class="offer-card">
    <div class="offer-header">
        <div class="offer-header-left">
             <div class="offer-header-row">
     <div class="form-group">
         <label for="siparis_no" style="color:#9ca3af;">Sipari&#351; No:</label>
         <input id="siparis_no" name="siparis_no" type="text" style="text-align:left;" value="{{ old('siparis_no', isset($siparis) ? $siparis->siparis_no : ($nextSiparisNo ?? '')) }}" readonly>
     </div>
    <div class="form-group">
        <label for="teklif_no" style="color:#9ca3af;">Teklif No:</label>
        <div class="input-with-button">
            <input id="teklif_no" name="teklif_no" type="text" style="text-align:left;" value="{{ old('teklif_no', isset($siparis) ? ($siparis->teklif_no ?? '') : '') }}" readonly>
            <button type="button" class="small-btn" id="btnTeklifOpen" title="Teklifi Aç">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="11" cy="11" r="6" stroke="currentColor" stroke-width="2"/>
                    <line x1="16" y1="16" x2="21" y2="21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        </div>
    </div>
  </div>
            <div class="offer-header-row">
                <div class="form-group">
                    <label for="carikod" style="color:#9ca3af;">Firma:</label>
                    <div class="input-with-button">
                        <span id="firma_kod_label">{{ old('carikod', isset($siparis) ? $siparis->carikod : '') }}</span>
                        <span>/</span>
                        <span id="firma_aciklama_label">{{ old('cariaciklama', isset($siparis) ? $siparis->cariaciklama : '') }}</span>
                        <input id="carikod" name="carikod" type="hidden" value="{{ old('carikod', isset($siparis) ? $siparis->carikod : '') }}">
                        <input id="cariaciklama" name="cariaciklama" type="hidden" value="{{ old('cariaciklama', isset($siparis) ? $siparis->cariaciklama : '') }}">
                        <button type="button" class="small-btn" id="btnCariSearch"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="11" cy="11" r="6" stroke="currentColor" stroke-width="2"/><line x1="16" y1="16" x2="21" y2="21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="islem_turu_adi" style="color:#9ca3af;">İşlem Türü:</label>
                    <div class="input-with-button">
                        <input id="islem_turu_adi" type="text"
                               value="{{ old('islem_turu_adi', isset($siparis) && $siparis->islemTuru ? $siparis->islemTuru->ad : '') }}"
                               style="border:none;background:transparent;outline:none;padding:0;" readonly>
                        <input id="islem_turu_id" name="islem_turu_id" type="hidden"
                               value="{{ old('islem_turu_id', isset($siparis) ? $siparis->islem_turu_id : '') }}">
                        <button type="button" class="small-btn" id="btnIslemTuruSearch">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <circle cx="11" cy="11" r="6" stroke="currentColor" stroke-width="2"/>
                                <line x1="16" y1="16" x2="21" y2="21" stroke="currentColor" stroke-width="2"
                                      stroke-linecap="round"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="offer-header-row">
                <div class="form-group">
                    <label style="color:#9ca3af;">Adres:</label>
                    <div>
                        <div id="firma_adres_satir1">{{ isset($selectedFirm) ? $selectedFirm->adres1 : '' }}</div>
                        <div id="firma_adres_satir2">{{ isset($selectedFirm) ? $selectedFirm->adres2 : '' }}</div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="proje_kod" style="color:#9ca3af;">Proje:</label>
                    <div class="input-with-button">
                        <input id="proje_kod" type="text"
                               value="{{ old('proje_kod', isset($siparis) && $siparis->proje ? $siparis->proje->kod : '') }}"
                               style="border:none;background:transparent;outline:none;padding:0;" readonly>
                        <input id="proje_id" name="proje_id" type="hidden"
                               value="{{ old('proje_id', isset($siparis) ? $siparis->proje_id : '') }}">
                        <button type="button" class="small-btn" id="btnProjeSearch">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <circle cx="11" cy="11" r="6" stroke="currentColor" stroke-width="2"/>
                                <line x1="16" y1="16" x2="21" y2="21" stroke="currentColor" stroke-width="2"
                                      stroke-linecap="round"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="offer-header-row">
                <div class="form-group">
                    <label style="color:#9ca3af;">İl / İlçe:</label>
                    <div id="firma_il_ilce">
                        {{ isset($selectedFirm)
                            ? ($selectedFirm->il . ($selectedFirm->ilce ? ' / '.$selectedFirm->ilce : ''))
                            : '' }}
                    </div>
                </div>

                <div class="form-group">
                    <label for="yetkili_personel" style="color:#9ca3af;">Yetkili Personel:</label>
                    <div class="input-with-button">
                        <input id="yetkili_personel" name="yetkili_personel" type="text" value="{{ old('yetkili_personel', isset($siparis) ? $siparis->yetkili_personel : '') }}" style="border:none;background:transparent;outline:none;padding:0;">
                        <button type="button" class="small-btn" id="btnYetkiliSearch"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="11" cy="11" r="6" stroke="currentColor" stroke-width="2"/><line x1="16" y1="16" x2="21" y2="21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></button>
                    </div>
                </div>
            </div>

            @if(false)
            <div class="offer-header-row">
                <div class="form-group">
                    <label for="islem_turu_adi" style="color:#9ca3af;">İşlem Türü:</label>
                    <div class="input-with-button">
                        <input id="islem_turu_adi" type="text"
                               value="{{ old('islem_turu_adi', isset($siparis) && $siparis->islemTuru ? $siparis->islemTuru->ad : '') }}"
                               style="border:none;background:transparent;outline:none;padding:0;" readonly>
                        <input id="islem_turu_id" name="islem_turu_id" type="hidden"
                               value="{{ old('islem_turu_id', isset($siparis) ? $siparis->islem_turu_id : '') }}">
                        <button type="button" class="small-btn" id="btnIslemTuruSearch">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <circle cx="11" cy="11" r="6" stroke="currentColor" stroke-width="2"/>
                                <line x1="16" y1="16" x2="21" y2="21" stroke="currentColor" stroke-width="2"
                                      stroke-linecap="round"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="offer-header-row">
                <div class="form-group">
                    <label for="proje_kod" style="color:#9ca3af;">Proje:</label>
                    <div class="input-with-button">
                        <input id="proje_kod" type="text"
                               value="{{ old('proje_kod', isset($siparis) && $siparis->proje ? $siparis->proje->kod : '') }}"
                               style="border:none;background:transparent;outline:none;padding:0;" readonly>
                        <input id="proje_id" name="proje_id" type="hidden"
                               value="{{ old('proje_id', isset($siparis) ? $siparis->proje_id : '') }}">
                        <button type="button" class="small-btn" id="btnProjeSearch">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <circle cx="11" cy="11" r="6" stroke="currentColor" stroke-width="2"/>
                                <line x1="16" y1="16" x2="21" y2="21" stroke="currentColor" stroke-width="2"
                                      stroke-linecap="round"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="offer-header-right">
            <div class="form-group">
                <label for="tarih" style="color:#9ca3af;">Sipariş Tarihi:</label>
                <input id="tarih" name="tarih" type="date" value="{{ old('tarih', isset($siparis) && $siparis->tarih ? $siparis->tarih->toDateString() : now()->toDateString()) }}" required>
            </div>
            <div class="form-group">
                <label for="gecerlilik_tarihi" style="color:#9ca3af;">Geçerlilik Tarihi:</label>
                <input id="gecerlilik_tarihi" name="gecerlilik_tarihi" type="date" value="{{ old('gecerlilik_tarihi', isset($siparis) && $siparis->gecerlilik_tarihi ? $siparis->gecerlilik_tarihi->toDateString() : null) }}">
            </div>
            <div class="form-group">
                <label for="onay_durum" style="color:#9ca3af;">Onay Durum:</label>
                <div class="input-with-button">
                    <input id="onay_durum" name="onay_durum" type="text" value="{{ old('onay_durum', isset($siparis) ? ($siparis->onay_durum ?? 'Onay bekliyor') : 'Onay bekliyor') }}">
                    @php
                        $currentUser = auth()->user();
                        $canApprove = $currentUser && $currentUser->role === 'yonetici';
                    @endphp
                    <button type="button"
                            class="small-btn"
                            id="btnToggleOnay"
                            @unless($canApprove) disabled style="opacity:0.4;cursor:not-allowed;" @endunless>
                        Onayla
                    </button>
                </div>
            </div>
            <div class="form-group">
                <label for="onay_tarihi" style="color:#9ca3af;">Onay Tarihi:</label>
                <input id="onay_tarihi" name="onay_tarihi" type="date" value="{{ old('onay_tarihi', isset($siparis) && $siparis->onay_tarihi ? $siparis->onay_tarihi->toDateString() : null) }}">
            </div>
            <div class="form-group">
                <label for="hazirlayan" style="color:#9ca3af;">Hazırlayan:</label>
                <input id="hazirlayan" name="hazirlayan" type="text" value="{{ old('hazirlayan', isset($siparis) ? $siparis->hazirlayan : '') }}" style="border:none;background:transparent;outline:none;padding:0;" readonly>
            </div>
        </div>
    </div>

    <div class="form-group" style="margin-bottom: 1rem;">
                        <label for="aciklama" style="color:#9ca3af;">Açıklama</label>
                        <textarea id="aciklama" name="aciklama">{{ old('aciklama') }}</textarea>
                    </div>

                    <div class="lines-header">
                        <button type="button" id="btnAddLine">Satır Ekle</button>
                    </div>

                    <table class="offer-lines">
                        <thead>
                        <tr>
                            <th class="stok-kod">Stok Kod</th>
                            <th class="stok-aciklama">Stok Açıklama</th>
                            <th class="durum">Durum</th>
                            <th class="birim-fiyat">Birim Fiyat</th>                            <th class="miktar">Miktar</th>
                            <th class="doviz">Doviz</th>
                            <th class="kur">Kur</th>                            <th>İsk.1%</th>
                            <th>İsk.2%</th>
                            <th>İsk.3%</th>
                            <th>İsk.4%</th>
                            <th>İsk.5%</th>
                            <th>İsk.6%</th>
                            <th>İsk. Tutar</th>
                            <th class="kdv">KDV %</th>
                            <th class="kdv-durum">KDV Durum</th>
                            <th>Satır Tutar</th>
                            @if(in_array($siparisTuru, ['satis', 'alim'], true))
                                <th class="detay">Detay</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody id="offerLinesBody">
                        </tbody>
                    </table>
                
                    <div class="offer-summary" style="margin-top: 1rem; display:flex; justify-content:flex-end; gap:2rem; align-items:flex-start;">
                        <table class="offer-summary-table" style="font-size:0.8rem;">
                            <tr>
                                <td>Toplam:</td>
                                <td style="text-align:right;"><span id="sumToplam">0,00</span></td>
                            </tr>
                            <tr>
                                <td>İskonto Tutar:</td>
                                <td style="text-align:right;"><span id="sumIskonto">0,00</span></td>
                            </tr>
                            <tr>
                                <td>KDV:</td>
                                <td style="text-align:right;"><span id="sumKdv">0,00</span></td>
                            </tr>
                            <tr>
                                <td><strong>Genel Toplam:</strong></td>
                                <td style="text-align:right;"><strong id="sumGenel">0,00</strong></td>
                            </tr>
                        </table>
                    
                        <div class="offer-summary-exchange" style="font-size:0.8rem;">
                            <div style="margin-bottom:0.35rem;">
                                <label for="offer_currency" style="color:#9ca3af;margin-right:0.5rem;">Sipariş Döviz:</label>
                                <select id="offer_currency" name="siparis_doviz" style="min-width:80px;border-radius:999px;border:1px solid #e5e7eb;padding:0.25rem 0.6rem;font-size:0.8rem;outline:none;">
                                    @php($offerDoviz = old('siparis_doviz', $siparis->siparis_doviz ?? 'TL'))
                                    <option value="TL" @selected($offerDoviz === 'TL')>TL</option>
                                    <option value="USD" @selected($offerDoviz === 'USD')>USD</option>
                                    <option value="EUR" @selected($offerDoviz === 'EUR')>EUR</option>
                                </select>
                            </div>
                            <div>
                                <label for="offer_rate" style="color:#9ca3af;margin-right:0.5rem;">Sipariş Kur:</label>
                                <input id="offer_rate" name="siparis_kur" value="{{ old('siparis_kur', $siparis->siparis_kur ?? 1) }}" type="number" step="0.0001" style="width:100px;border-radius:999px;border:1px solid #e5e7eb;padding:0.25rem 0.6rem;font-size:0.8rem;outline:none;">
                            </div>
                        </div>
                    
                        <table class="offer-summary-table" style="font-size:0.8rem;">
                            <tr>
                                <td>Toplam (Döviz):</td>
                                <td style="text-align:right;"><span id="sumToplamFx">0,00</span></td>
                            </tr>
                            <tr>
                                <td>İskonto Tutar (Döviz):</td>
                                <td style="text-align:right;"><span id="sumIskontoFx">0,00</span></td>
                            </tr>
                            <tr>
                                <td>KDV (Döviz):</td>
                                <td style="text-align:right;"><span id="sumKdvFx">0,00</span></td>
                            </tr>
                            <tr>
                                <td><strong>Genel Toplam (Döviz):</strong></td>
                                <td style="text-align:right;"><strong id="sumGenelFx">0,00</strong></td>
                            </tr>
                        </table>
                    </div>
                    

                    <div class="actions">
                        <a href="{{ route('orders.index', ['tur' => $tur ?? 'alim']) }}" class="btn btn-cancel">İptal</a>
                        <button type="submit" class="btn btn-save">Kaydet</button>
                    </div>
                </div>
            </form>
        </section>
    </main>

    <!-- Cari seÃ§imi modal -->
    <div id="firmModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <div class="modal-title">Cari Seç</div>
                <button type="button" class="small-btn" data-modal-close="firmModal">âœ•</button>
            </div>
            <div class="modal-body">
                <table class="modal-table">
                    <thead>
                    <tr>
                        <th>Cari Kod</th>
                        <th>Cari Açıklama</th>
                        <th>İsk.1</th>
                        <th>İsk.2</th>
                        <th>İsk.3</th>
                        <th>İsk.4</th>
                        <th>İsk.5</th>
                        <th>İsk.6</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($firms as $firm)
                        <tr class="firm-row"
                            data-carikod="{{ $firm->carikod }}"
                            data-cariaciklama="{{ $firm->cariaciklama }}"
                            data-isk1="{{ $firm->iskonto1 ?? 0 }}"
                            data-isk2="{{ $firm->iskonto2 ?? 0 }}"
                            data-isk3="{{ $firm->iskonto3 ?? 0 }}"
                            data-isk4="{{ $firm->iskonto4 ?? 0 }}"
                            data-isk5="{{ $firm->iskonto5 ?? 0 }}"
                            data-isk6="{{ $firm->iskonto6 ?? 0 }}" data-adres1="{{ $firm->adres1 }}" data-adres2="{{ $firm->adres2 }}" data-il="{{ $firm->il }}" data-ilce="{{ $firm->ilce }}"
                            data-authorities='@json($firm->authorities->pluck("full_name"))'>
                            <td>{{ $firm->carikod }}</td>
                            <td>{{ $firm->cariaciklama }}</td>
                            <td>{{ $firm->iskonto1 }}</td>
                            <td>{{ $firm->iskonto2 }}</td>
                            <td>{{ $firm->iskonto3 }}</td>
                            <td>{{ $firm->iskonto4 }}</td>
                            <td>{{ $firm->iskonto5 }}</td>
                            <td>{{ $firm->iskonto6 }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" data-modal-close="firmModal">Kapat</button>
            </div>
        </div>
    </div>

    <!-- Yetkili seÃ§imi modal -->
    <div id="authorityModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <div class="modal-title">Yetkili Seç</div>
                <button type="button" class="small-btn" data-modal-close="authorityModal">âœ•</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Tanımlı Yetkililer</label>
                    <ul id="authorityList" class="authority-list"></ul>
                </div>
                <div class="form-group" style="margin-top: 0.75rem;">
                    <label for="authorityNewInput">Yeni Yetkili Ad Soyad</label>
                    <input id="authorityNewInput" type="text">
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-save" id="authorityUseButton">Seç / Kullan</button>
                <button type="button" class="btn btn-cancel" data-modal-close="authorityModal">Kapat</button>
            </div>
        </div>
    </div>
    <!-- Ürün seçimi modal -->
    <div id="islemTuruModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <div class="modal-title">Islem Turu Sec</div>
                <button type="button" class="small-btn" data-modal-close="islemTuruModal">X</button>
            </div>
            <div class="modal-body">
                <table class="modal-table">
                    <thead>
                    <tr>
                        <th>Ad</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($islemTurleri as $tur)
                        <tr class="islem-turu-row" data-id="{{ $tur->id }}" data-ad="{{ $tur->ad }}">
                            <td>{{ $tur->ad }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" data-modal-close="islemTuruModal">Kapat</button>
            </div>
        </div>
    </div>

    <div id="projeModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <div class="modal-title">Proje Sec</div>
                <button type="button" class="small-btn" data-modal-close="projeModal">X</button>
            </div>
            <div class="modal-body">
                <table class="modal-table">
                    <thead>
                    <tr>
                        <th>Kod</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($projects as $project)
                        <tr class="project-row" data-id="{{ $project->id }}" data-kod="{{ $project->kod }}">
                            <td>{{ $project->kod }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" data-modal-close="projeModal">Kapat</button>
            </div>
        </div>
    </div>

    <div id="productModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <div class="modal-title">Ürün Seç</div>
                <button type="button" class="small-btn" data-modal-close="productModal">?</button>
            </div>
            <div class="modal-body">
                <table class="modal-table">
                    <thead>
                    <tr>
                        <th>Stok Kod</th>
                        <th>Stok Açıklama</th>
                        <th>Birim Fiyat</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($products as $product)
                        <tr class="product-row"
                            data-id="{{ $product->id }}"
                            data-kod="{{ $product->kod }}"
                            data-aciklama="{{ $product->aciklama }}"
                            data-fiyat="{{ $product->satis_fiyat }}">
                            <td>{{ $product->kod }}</td>
                            <td>{{ $product->aciklama }}</td>
                            <td>{{ number_format($product->satis_fiyat, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" data-modal-close="productModal">Kapat</button>
            </div>
        </div>
    </div>

    @if($siparisTuru === 'satis')
        <div id="planningModal" class="modal-overlay">
            <div class="modal" style="max-width:520px;">
                <div class="modal-header">
                    <div class="modal-title">Planlama Detayı</div>
                    <button type="button" class="small-btn" data-modal-close="planningModal">X</button>
                </div>
                <div class="modal-body">
                    <div style="display:flex; flex-direction:column; gap: 0.75rem;">
                        <div style="display:flex; gap: 0.5rem; align-items:center;">
                            <strong style="min-width: 150px;">Planlama Durum:</strong>
                            <select id="planningModalStatus" style="min-width: 220px; border-radius: 999px; border: 1px solid #e5e7eb; padding: 0.25rem 0.6rem; font-size: 0.85rem; outline: none;">
                                <option value="beklemede">Beklemede</option>
                                <option value="kismi_yapildi">Kısmi Yapıldı</option>
                                <option value="yapildi">Yapıldı</option>
                            </select>
                        </div>
                        <div style="display:flex; gap: 0.5rem; align-items:center;">
                            <strong style="min-width: 150px;">Planlanan Miktar:</strong>
                            <input id="planningModalQty" type="number" min="0" step="0.01" style="min-width: 220px; border-radius: 999px; border: 1px solid #e5e7eb; padding: 0.25rem 0.6rem; font-size: 0.85rem; outline: none;">
                        </div>
                    </div>

                    @if($siparisTuru === 'satis')
                        <input type="hidden" id="planlama_durum" name="planlama_durum" value="{{ old('planlama_durum', $siparis->planlama_durum ?? 'beklemede') }}">
                        <input type="hidden" id="planlanan_miktar" name="planlanan_miktar" value="{{ old('planlanan_miktar', $siparis->planlanan_miktar ?? '') }}">
                    @endif
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-cancel" data-modal-close="planningModal">Kapat</button>
                </div>
            </div>
        </div>
    @endif

    @if($siparisTuru === 'alim')
        <div id="salesLinksModal" class="modal-overlay">
            <div class="modal" style="max-width:820px;">
                <div class="modal-header">
                    <div class="modal-title">Satış Siparişi Detayları</div>
                    <button type="button" class="small-btn" id="salesLinksClose">X</button>
                </div>
                <div class="modal-body">
                    <table class="modal-table">
                        <thead>
                        <tr>
                            <th>Cari Kod</th>
                            <th>Sipariş No</th>
                            <th>Sipariş Tarih</th>
                            <th style="text-align:right;">Miktar</th>
                        </tr>
                        </thead>
                        <tbody id="salesLinksTbody">
                        <tr>
                            <td colspan="4" style="padding:12px; text-align:center; color:#6b7280;">Kayıt yok.</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-cancel" id="salesLinksClose2">Kapat</button>
                </div>
            </div>
        </div>
    @endif
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var onayDurumInput = document.getElementById('onay_durum');
        var onayTarihiInput = document.getElementById('onay_tarihi');
        var btnToggleOnay = document.getElementById('btnToggleOnay');

        function todayISO() {
            var d = new Date();
            var m = String(d.getMonth() + 1).padStart(2, '0');
            var day = String(d.getDate()).padStart(2, '0');
            return d.getFullYear() + '-' + m + '-' + day;
        }

        // Sayfa ilk açıldığında mevcut onay durumuna göre buton metnini ayarla
        if (btnToggleOnay && onayDurumInput) {
            var initialVal = (onayDurumInput.value || '').trim();
            if (initialVal === 'Onayl') {
                btnToggleOnay.textContent = 'Onay Kaldr';
            } else {
                btnToggleOnay.textContent = 'Onayla';
            }
        }

        if (btnToggleOnay) {
            btnToggleOnay.addEventListener('click', function () {
                var val = (onayDurumInput.value || '').trim();
                if (val === 'Onaylı') {
                    onayDurumInput.value = 'Onay bekliyor';
                    onayTarihiInput.value = '';
                    btnToggleOnay.textContent = 'Onayla';
                } else {
                    onayDurumInput.value = 'Onaylı';
                    onayTarihiInput.value = todayISO();
                    btnToggleOnay.textContent = 'Onay Kaldır';
                }
            });
        }

        // Onay durumu metin/encoding farklılıklarına toleranslı olarak düzelt (Onaylı ise: "Onay Kaldır")
        function isApprovedStatus(value) {
            var v = (value || '').toString().trim().toLowerCase();
            return v.startsWith('onayl');
        }

        function syncApprovalButton() {
            if (!btnToggleOnay || !onayDurumInput) return;
            btnToggleOnay.textContent = isApprovedStatus(onayDurumInput.value) ? 'Onay Kaldır' : 'Onayla';
        }

        syncApprovalButton();

        if (btnToggleOnay && onayDurumInput && onayTarihiInput) {
            // capture phase: alttaki eski handler'ı ezmek için
            btnToggleOnay.addEventListener(
                'click',
                function (e) {
                    e.preventDefault();
                    e.stopImmediatePropagation();

                    if (isApprovedStatus(onayDurumInput.value)) {
                        onayDurumInput.value = 'Onay bekliyor';
                        onayTarihiInput.value = '';
                    } else {
                        onayDurumInput.value = 'Onaylı';
                        onayTarihiInput.value = todayISO();
                    }

                    syncApprovalButton();
                },
                true
            );
        }

        var linesBody = document.getElementById('offerLinesBody');
        var btnAddLine = document.getElementById('btnAddLine');
        var lineIndex = 0;
        var initialLines = @json($prefillLines ?? (isset($siparis) ? $siparis->detaylar : []));
        var isSalesOrder = @json($siparisTuru === 'satis');
        var isPurchaseOrder = @json($siparisTuru === 'alim');

        function applyCurrencyBehavior(tr, initializeDefault) {
            var currencySelect = tr.querySelector('.doviz');
            var kurInput = tr.querySelector('.kur');
            if (!currencySelect || !kurInput) return;

            function updateForCurrency() {
                var val = currencySelect.value || 'TL';
                if (val === 'TL') {
                    kurInput.value = '1.0000';
                } else {
                    kurInput.value = '0.0000';
                }
            }

            currencySelect.addEventListener('change', updateForCurrency);

            if (initializeDefault && !kurInput.value) {
                updateForCurrency();
            }
        }

        function recalcTotals() {
            if (!linesBody) return;

            var rows = linesBody.querySelectorAll('tr');
            var toplam = 0;
            var iskontoToplam = 0;
            var kdvToplam = 0;

            rows.forEach(function (tr) {
                var price = parseFloat(tr.querySelector('.birim-fiyat')?.value || '0') || 0;
                var qty = parseFloat(tr.querySelector('.miktar')?.value || '0') || 0;

                if (!price && !qty) return;

                var brut = price * qty;

                var discounts = [];
                ['isk1', 'isk2', 'isk3', 'isk4', 'isk5', 'isk6'].forEach(function (cls) {
                    var el = tr.querySelector('.' + cls);
                    var val = parseFloat(el && el.value !== '' ? el.value : '0') || 0;
                    discounts.push(val);
                });

                var remaining = brut;
                var totalDiscount = 0;

                discounts.forEach(function (rate) {
                    if (!rate) return;
                    var d = remaining * (rate / 100);
                    totalDiscount += d;
                    remaining -= d;
                });

                var net = brut - totalDiscount;
                if (net < 0) net = 0;

                var kdvOranEl  = tr.querySelector('.kdv-oran');
                var kdvDurumEl = tr.querySelector('.kdv-durum');
                var kdvOran    = parseFloat(kdvOranEl && kdvOranEl.value !== '' ? kdvOranEl.value : '0') || 0;
                var kdvDurum   = kdvDurumEl ? (kdvDurumEl.value || 'H') : 'H';

                var kdv = 0;

                if (kdvOran > 0 && net > 0) {
                    if (kdvDurum === 'H') {
                        kdv = net * (kdvOran / 100);
                    } else if (kdvDurum === 'E' || kdvDurum === 'D') {
                        var oran = kdvOran / 100;
                        kdv = net - (net / (1 + oran));
                    }
                }

                toplam += brut;
                iskontoToplam += totalDiscount;
                kdvToplam += kdv;
            });

            var genelToplam = toplam - iskontoToplam + kdvToplam;

            function fmt(val) {
                return val.toLocaleString('tr-TR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            var elToplam = document.getElementById('sumToplam');
            var elIskonto = document.getElementById('sumIskonto');
            var elKdv = document.getElementById('sumKdv');
            var elGenel = document.getElementById('sumGenel');

            if (elToplam) elToplam.textContent = fmt(toplam);
            if (elIskonto) elIskonto.textContent = fmt(iskontoToplam);
            if (elKdv) elKdv.textContent = fmt(kdvToplam);
            if (elGenel) elGenel.textContent = fmt(genelToplam);

            var elToplamFx = document.getElementById('sumToplamFx');
            var elIskontoFx = document.getElementById('sumIskontoFx');
            var elKdvFx = document.getElementById('sumKdvFx');
            var elGenelFx = document.getElementById('sumGenelFx');
            var offerRateInput = document.getElementById('offer_rate');

            if (offerRateInput && (elToplamFx || elIskontoFx || elKdvFx || elGenelFx)) {
                var rate = parseFloat(offerRateInput.value || '0') || 0;
                if (rate > 0) {
                    var toplamFx = toplam / rate;
                    var iskontoFx = iskontoToplam / rate;
                    var kdvFx = kdvToplam / rate;
                    var genelFx = genelToplam / rate;

                    if (elToplamFx) elToplamFx.textContent = fmt(toplamFx);
                    if (elIskontoFx) elIskontoFx.textContent = fmt(iskontoFx);
                    if (elKdvFx) elKdvFx.textContent = fmt(kdvFx);
                    if (elGenelFx) elGenelFx.textContent = fmt(genelFx);
                } else {
                    if (elToplamFx) elToplamFx.textContent = '0,00';
                    if (elIskontoFx) elIskontoFx.textContent = '0,00';
                    if (elKdvFx) elKdvFx.textContent = '0,00';
                    if (elGenelFx) elGenelFx.textContent = '0,00';
                }
            }
        }

        function addLineRow() {
            var tr = document.createElement('tr');
            var rowHtml =
                '<td class="stok-kod-cell"><input class="line-input stok-kod"><input type="hidden" class="line-input urun-id"></td>' +
                '<td class="stok-aciklama-cell"><input class="line-input stok-aciklama"></td>' +
                '<td class="durum-cell"><select class="line-input durum"><option value="A" selected>A</option><option value="K">K</option></select></td>' +
                '<td class="birim-fiyat-cell"><input type="number" step="0.01" class="line-input birim-fiyat"></td>' +
                '<td class="miktar-cell"><input type="number" step="0.001" class="line-input miktar"></td>' +
                '<td class="doviz-cell"><select class="line-input doviz"><option value="TL" selected>TL</option><option value="USD">USD</option><option value="EUR">EUR</option></select></td>' +
                '<td class="kur-cell"><input type="number" step="0.0001" class="line-input kur"></td>' +
                '<td class="iskonto-cell"><input type="number" step="0.01" class="line-input isk1"></td>' +
                '<td class="iskonto-cell"><input type="number" step="0.01" class="line-input isk2"></td>' +
                '<td class="iskonto-cell"><input type="number" step="0.01" class="line-input isk3"></td>' +
                '<td class="iskonto-cell"><input type="number" step="0.01" class="line-input isk4"></td>' +
                '<td class="iskonto-cell"><input type="number" step="0.01" class="line-input isk5"></td>' +
                '<td class="iskonto-cell"><input type="number" step="0.01" class="line-input isk6"></td>' +
                '<td class="iskonto-cell"><input type="number" step="0.01" class="line-input isk-tutar" readonly></td>' +
                '<td class="kdv-cell"><input type="number" step="0.01" class="line-input kdv-oran"></td>' +
                '<td class="kdv-durum-cell"><select class="line-input kdv-durum"><option value="D">D</option><option value="H">H</option></select></td>' +
                '<td class="satir-tutar-cell"><input type="number" step="0.01" class="line-input satir-tutar" readonly></td>';

            if (isSalesOrder) {
                rowHtml += '<td class="detay-cell">' +
                    '<button type="button" class="order-planning-detail-button" aria-label="Planlama Detay" style="border:none;background:transparent;cursor:pointer;padding:0;color:#111827;">' +
                    '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">' +
                    '<path d="M12 11c0 1.1046-.8954 2-2 2s-2-.8954-2-2 .8954-2 2-2 2 .8954 2 2Z" stroke="currentColor" stroke-width="2"/>' +
                    '<path d="M22 12c-2.2 4-6 7-10 7S4.2 16 2 12c2.2-4 6-7 10-7s7.8 3 10 7Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>' +
                    '</svg>' +
                    '</button>' +
                    '</td>';
            }

            if (isPurchaseOrder) {
                rowHtml += '<td class="detay-cell">' +
                    '<button type="button" class="purchase-sales-detail-button" aria-label="Satış Siparişi Detay" style="border:none;background:transparent;cursor:pointer;padding:0;color:#111827;">' +
                    '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto;">' +
                    '<path d="M12 11c0 1.1046-.8954 2-2 2s-2-.8954-2-2 .8954-2 2-2 2 .8954 2 2Z" stroke="currentColor" stroke-width="2"/>' +
                    '<path d="M22 12c-2.2 4-6 7-10 7S4.2 16 2 12c2.2-4 6-7 10-7s7.8 3 10 7Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>' +
                    '</svg>' +
                    '</button>' +
                    '</td>';
            }

            rowHtml += '<input type="hidden" class="satir-aciklama-hidden">' +
                '<input type="hidden" class="line-input satis-detay-ids">' +
                '<input type="hidden" class="sales-links-json">';
            tr.innerHTML = rowHtml;

            // Varsayılan değerler: miktar 1, iskonto alanları 0, iskonto tutar 0, KDV %20, KDV Durum H
            var miktarInput = tr.querySelector('.miktar');
            if (miktarInput) {
                miktarInput.value = '1';
            }

            applyCurrencyBehavior(tr, true);

            ['isk1', 'isk2', 'isk3', 'isk4', 'isk5', 'isk6'].forEach(function (cls) {
                var el = tr.querySelector('.' + cls);
                if (el) {
                    el.value = '0';
                }
            });

            var iskTutarInput = tr.querySelector('.isk-tutar');
            if (iskTutarInput) {
                iskTutarInput.value = '0';
            }

            var kdvOranInput = tr.querySelector('.kdv-oran');
            if (kdvOranInput) {
                kdvOranInput.value = '20';
            }

            var kdvDurumSelect = tr.querySelector('.kdv-durum');
            if (kdvDurumSelect) {
                kdvDurumSelect.value = 'H';
            }

            // Görünen stok açıklamasını gizli satır açıklamasına yansıt
            var stokAciklamaInput = tr.querySelector('.stok-aciklama');
            var satirAciklamaHidden = tr.querySelector('.satir-aciklama-hidden');
            if (stokAciklamaInput && satirAciklamaHidden) {
                stokAciklamaInput.addEventListener('input', function () {
                    satirAciklamaHidden.value = stokAciklamaInput.value;
                });
            }

            // Satır iskonto tutarı hesaplama fonksiyonu
            function recalcDiscountBase() {
                var price = parseFloat(tr.querySelector('.birim-fiyat')?.value || '0') || 0;
                var qty = parseFloat(tr.querySelector('.miktar')?.value || '0') || 0;
                var baseAmount = price * qty;

                var discounts = [];
                ['isk1', 'isk2', 'isk3', 'isk4', 'isk5', 'isk6'].forEach(function (cls) {
                    var el = tr.querySelector('.' + cls);
                    var val = parseFloat(el && el.value !== '' ? el.value : '0') || 0;
                    discounts.push(val);
                });

                var remaining = baseAmount;
                var totalDiscount = 0;

                discounts.forEach(function (rate) {
                    if (!rate) return;
                    var d = remaining * (rate / 100);
                    totalDiscount += d;
                    remaining -= d;
                });

                var iskField = tr.querySelector('.isk-tutar');
                if (iskField) {
                    iskField.value = totalDiscount ? totalDiscount.toFixed(2) : '0';
                }
            }

            // Satır iskonto, KDV ve satır toplam hesaplama fonksiyonu
            function recalcDiscount() {
                var price = parseFloat(tr.querySelector('.birim-fiyat')?.value || '0') || 0;
                var qty = parseFloat(tr.querySelector('.miktar')?.value || '0') || 0;
                var baseAmount = price * qty;

                var discounts = [];
                ['isk1', 'isk2', 'isk3', 'isk4', 'isk5', 'isk6'].forEach(function (cls) {
                    var el = tr.querySelector('.' + cls);
                    var val = parseFloat(el && el.value !== '' ? el.value : '0') || 0;
                    discounts.push(val);
                });

                var remaining = baseAmount;
                var totalDiscount = 0;

                discounts.forEach(function (rate) {
                    if (!rate) return;
                    var d = remaining * (rate / 100);
                    totalDiscount += d;
                    remaining -= d;
                });

                // iskonto sonrası net (KDV hesaplanacak tutar)
                var net = baseAmount - totalDiscount;
                if (net < 0) net = 0;

                // İskonto tutarı alanı
                var iskField = tr.querySelector('.isk-tutar');
                if (iskField) {
                    iskField.value = totalDiscount ? totalDiscount.toFixed(2) : '0';
                }

                // KDV oranı ve durumu
                var kdvOranEl  = tr.querySelector('.kdv-oran');
                var kdvDurumEl = tr.querySelector('.kdv-durum');
                var kdvOran    = parseFloat(kdvOranEl && kdvOranEl.value !== '' ? kdvOranEl.value : '0') || 0;
                var kdvDurum   = kdvDurumEl ? (kdvDurumEl.value || 'H') : 'H';

                var kdv       = 0;
                var lineTotal = net;

                if (kdvOran > 0 && net > 0) {
                    if (kdvDurum === 'H') {
                        // KDV hariç: net * oran / 100, satır toplam = net + KDV
                        kdv       = net * (kdvOran / 100);
                        lineTotal = net + kdv;
                    } else if (kdvDurum === 'E' || kdvDurum === 'D') {
                        // KDV dahil: KDV net tutarın içinden ayrıştırılır, satır toplam = net
                        var oran  = kdvOran / 100;
                        lineTotal = net;
                        kdv       = net - (net / (1 + oran));
                    } else {
                        lineTotal = net;
                        kdv       = 0;
                    }
                }

                // Satır tutar alanı (Satır Tutar sütununda görünen veri)
                var satirField = tr.querySelector('.satir-tutar');
                if (satirField) {
                    satirField.value = lineTotal ? lineTotal.toFixed(2) : '0';
                }

                recalcTotals();
            }

            var inputs = tr.querySelectorAll('.line-input, .satir-aciklama-hidden');
            inputs.forEach(function (input) {
                var base = null;
                if (input.classList.contains('stok-kod')) base = 'stok_kod';
                else if (input.classList.contains('urun-id')) base = 'urun_id';
                else if (input.classList.contains('stok-aciklama')) base = 'stok_aciklama';
                else if (input.classList.contains('durum')) base = 'durum';
                else if (input.classList.contains('birim-fiyat')) base = 'birim_fiyat';
                else if (input.classList.contains('miktar')) base = 'miktar';
                else if (input.classList.contains('doviz')) base = 'doviz';
                else if (input.classList.contains('kur')) base = 'kur';
                else if (input.classList.contains('isk1')) base = 'iskonto1';
                else if (input.classList.contains('isk2')) base = 'iskonto2';
                else if (input.classList.contains('isk3')) base = 'iskonto3';
                else if (input.classList.contains('isk4')) base = 'iskonto4';
                else if (input.classList.contains('isk5')) base = 'iskonto5';
                else if (input.classList.contains('isk6')) base = 'iskonto6';
                else if (input.classList.contains('isk-tutar')) base = 'iskonto_tutar';
                else if (input.classList.contains('kdv-oran')) base = 'kdv_orani';
                else if (input.classList.contains('kdv-durum')) base = 'kdv_durum';
                else if (input.classList.contains('satir-tutar')) base = 'satir_toplam';
                else if (input.classList.contains('satir-aciklama-hidden')) base = 'satir_aciklama';
                else if (input.classList.contains('satis-detay-ids')) base = 'satis_detay_ids';

                if (base) {
                    input.name = 'lines[' + lineIndex + '][' + base + ']';
                }

                // Birim fiyat, miktar veya iskonto oranları değişince iskonto tutarı yeniden hesapla
                if (
                    input.classList.contains('birim-fiyat') ||
                    input.classList.contains('miktar') ||
                    input.classList.contains('isk1') ||
                    input.classList.contains('isk2') ||
                    input.classList.contains('isk3') ||
                    input.classList.contains('isk4') ||
                    input.classList.contains('isk5') ||
                    input.classList.contains('isk6') ||
                    input.classList.contains('kdv-oran')
                ) {
                    input.addEventListener('input', recalcDiscount);
                }

                if (input.classList.contains('kdv-durum')) {
                    input.addEventListener('change', recalcDiscount);
                }
            });

            // Başlangıçta da iskonto tutarını hesapla
            recalcDiscount();

            if (linesBody) {
                linesBody.appendChild(tr);
                lineIndex++;
            }
        }

        if (btnAddLine && linesBody) {
            btnAddLine.addEventListener('click', addLineRow);
        }

        // baï¿½langï¿½ï¿½ta bir satï¿½r olsun
        if (linesBody) {
            addLineRow();
        }

        if (linesBody && Array.isArray(initialLines) && initialLines.length > 0) {
            linesBody.innerHTML = '';
            lineIndex = 0;

            initialLines.forEach(function (line) {
                addLineRow();
                var tr = linesBody.lastElementChild;
                if (!tr) return;

                var kodInput = tr.querySelector('.stok-kod');
                var aciklamaInput = tr.querySelector('.stok-aciklama');
                var durumInput = tr.querySelector('.durum');
                var fiyatInput = tr.querySelector('.birim-fiyat');
                var miktarInput = tr.querySelector('.miktar');
                var isk1 = tr.querySelector('.isk1');
                var isk2 = tr.querySelector('.isk2');
                var isk3 = tr.querySelector('.isk3');
                var isk4 = tr.querySelector('.isk4');
                var isk5 = tr.querySelector('.isk5');
                var isk6 = tr.querySelector('.isk6');
                var kdvOran = tr.querySelector('.kdv-oran');
                var urunIdInput = tr.querySelector('.urun-id');
                var dovizInput = tr.querySelector('.doviz');
                var kurInput = tr.querySelector('.kur');
                var satirAciklamaHidden = tr.querySelector('.satir-aciklama-hidden');
                var satisDetayIdsHidden = tr.querySelector('.satis-detay-ids');
                var salesLinksHidden = tr.querySelector('.sales-links-json');

                if (kodInput && line.urun && line.urun.kod) {
                    kodInput.value = line.urun.kod;
                }

                var lineDesc = '';
                if (line.satir_aciklama) {
                    lineDesc = line.satir_aciklama;
                } else if (line.urun && line.urun.aciklama) {
                    lineDesc = line.urun.aciklama || '';
                }

                if (aciklamaInput && lineDesc) {
                    aciklamaInput.value = lineDesc;
                }
                if (satirAciklamaHidden && lineDesc) {
                    satirAciklamaHidden.value = lineDesc;
                }
                if (durumInput) {
                    durumInput.value = (line.durum || 'A').toString().trim().toUpperCase() === 'K' ? 'K' : 'A';
                }
                if (fiyatInput && line.birim_fiyat != null) fiyatInput.value = line.birim_fiyat;
                if (miktarInput && line.miktar != null) miktarInput.value = line.miktar;
                if (dovizInput && line.doviz) dovizInput.value = line.doviz;
                if (kurInput && line.kur != null) kurInput.value = line.kur;
                if (isk1 && line.iskonto1 != null) isk1.value = line.iskonto1;
                if (isk2 && line.iskonto2 != null) isk2.value = line.iskonto2;
                if (isk3 && line.iskonto3 != null) isk3.value = line.iskonto3;
                if (isk4 && line.iskonto4 != null) isk4.value = line.iskonto4;
                if (isk5 && line.iskonto5 != null) isk5.value = line.iskonto5;
                if (isk6 && line.iskonto6 != null) isk6.value = line.iskonto6;
                if (kdvOran && line.kdv_orani != null) kdvOran.value = line.kdv_orani;
                if (urunIdInput && line.urun_id != null) urunIdInput.value = line.urun_id;
                if (satisDetayIdsHidden && line.satis_detay_ids != null) {
                    try {
                        satisDetayIdsHidden.value = typeof line.satis_detay_ids === 'string' ? line.satis_detay_ids : JSON.stringify(line.satis_detay_ids);
                    } catch (e) {
                        satisDetayIdsHidden.value = '';
                    }
                }
                if (salesLinksHidden && line.sales_links != null) {
                    try {
                        salesLinksHidden.value = typeof line.sales_links === 'string' ? line.sales_links : JSON.stringify(line.sales_links);
                    } catch (e) {
                        salesLinksHidden.value = '';
                    }
                }

                applyCurrencyBehavior(tr, false);

                var trigger = tr.querySelector('.birim-fiyat') || tr.querySelector('.miktar');
                if (trigger) {
                    var ev = new Event('input', { bubbles: true });
                    trigger.dispatchEvent(ev);
                }
            });
        }

        var offerRateInput = document.getElementById('offer_rate');
        if (offerRateInput) {
            offerRateInput.addEventListener('input', function () {
                recalcTotals();
            });
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var carikodInput = document.getElementById('carikod');
        var cariaciklamaInput = document.getElementById('cariaciklama');
        var firmaKodLabel = document.getElementById('firma_kod_label');
        var firmaAciklamaLabel = document.getElementById('firma_aciklama_label');
        var firmaAdres1 = document.getElementById('firma_adres_satir1');
        var firmaAdres2 = document.getElementById('firma_adres_satir2');
        var firmaIlIlce = document.getElementById('firma_il_ilce');
        var yetkiliInput = document.getElementById('yetkili_personel');
        var teklifNoInput = document.getElementById('teklif_no');
        var btnCariSearch = document.getElementById('btnCariSearch');
        var btnYetkiliSearch = document.getElementById('btnYetkiliSearch');
        var btnTeklifOpen = document.getElementById('btnTeklifOpen');

        var firmModal = document.getElementById('firmModal');
        var authorityModal = document.getElementById('authorityModal');
        var authorityList = document.getElementById('authorityList');
        var authorityNewInput = document.getElementById('authorityNewInput');
        var authorityUseButton = document.getElementById('authorityUseButton');
        var islemTuruModal = document.getElementById('islemTuruModal');
        var projeModal = document.getElementById('projeModal');
        var islemTuruIdInput = document.getElementById('islem_turu_id');
        var islemTuruAdiInput = document.getElementById('islem_turu_adi');
        var projeIdInput = document.getElementById('proje_id');
        var projeKodInput = document.getElementById('proje_kod');
        var btnIslemTuruSearch = document.getElementById('btnIslemTuruSearch');
        var btnProjeSearch = document.getElementById('btnProjeSearch');
        var productModal = document.getElementById('productModal');
        var planningModal = document.getElementById('planningModal');
        var salesLinksModal = document.getElementById('salesLinksModal');
        var currentProductRow = null;
        var linesBody = document.getElementById('offerLinesBody');
        var planningDurumHidden = document.getElementById('planlama_durum');
        var planningMiktarHidden = document.getElementById('planlanan_miktar');
        var currentFirmAuthorities = [];
        var currentFirmDiscounts = {
            isk1: {{ isset($selectedFirm) && $selectedFirm->iskonto1 !== null ? (float)$selectedFirm->iskonto1 : 0 }},
            isk2: {{ isset($selectedFirm) && $selectedFirm->iskonto2 !== null ? (float)$selectedFirm->iskonto2 : 0 }},
            isk3: {{ isset($selectedFirm) && $selectedFirm->iskonto3 !== null ? (float)$selectedFirm->iskonto3 : 0 }},
            isk4: {{ isset($selectedFirm) && $selectedFirm->iskonto4 !== null ? (float)$selectedFirm->iskonto4 : 0 }},
            isk5: {{ isset($selectedFirm) && $selectedFirm->iskonto5 !== null ? (float)$selectedFirm->iskonto5 : 0 }},
            isk6: {{ isset($selectedFirm) && $selectedFirm->iskonto6 !== null ? (float)$selectedFirm->iskonto6 : 0 }}
        };

        if (btnYetkiliSearch && carikodInput) {
            btnYetkiliSearch.disabled = !carikodInput.value;
        }

        if (btnTeklifOpen && teklifNoInput) {
            btnTeklifOpen.disabled = !teklifNoInput.value;
            if (btnTeklifOpen.disabled) {
                btnTeklifOpen.style.opacity = '0.4';
                btnTeklifOpen.style.cursor = 'not-allowed';
            }

            btnTeklifOpen.addEventListener('click', function () {
                var teklifNo = (teklifNoInput.value || '').trim();
                if (!teklifNo) return;
                window.location.href = "{{ route('offers.by-no', ['teklifNo' => '__NO__']) }}".replace('__NO__', encodeURIComponent(teklifNo));
            });
        }

        function openModal(modal) {
            if (modal) {
                modal.style.display = 'flex';
            }
        }

        function closeModal(modal) {
            if (modal) {
                modal.style.display = 'none';
            }
        }

        function labelPlanningStatus(v) {
            if (!v) return '-';
            if (v === 'bekliyor') return 'Bekliyor';
            if (v === 'kismi_tamam') return 'Kısmi Tamam';
            if (v === 'tamam') return 'Tamam';
            return v;
        }

        function formatDateTr(v) {
            if (!v) return '';
            var s = String(v);
            if (/^\\d{4}-\\d{2}-\\d{2}/.test(s)) {
                var parts = s.slice(0, 10).split('-');
                return parts[2] + '.' + parts[1] + '.' + parts[0];
            }
            return s;
        }

        document.querySelectorAll('[data-modal-close]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var id = this.getAttribute('data-modal-close');
                var modal = document.getElementById(id);
                closeModal(modal);
            });
        });

        if (linesBody && planningModal) {
            var planningModalStatus = document.getElementById('planningModalStatus');
            var planningModalQty = document.getElementById('planningModalQty');

            linesBody.addEventListener('click', function (e) {
                var btn = e.target.closest('.order-planning-detail-button');
                if (!btn) return;
                e.preventDefault();

                if (planningModalStatus && planningDurumHidden) {
                    planningModalStatus.value = planningDurumHidden.value || 'beklemede';
                }
                if (planningModalQty && planningMiktarHidden) {
                    planningModalQty.value = planningMiktarHidden.value || '';
                }
                openModal(planningModal);
            });

            planningModal.addEventListener('click', function (e) {
                if (e.target === planningModal) {
                    closeModal(planningModal);
                }
            });

            if (planningModalStatus && planningDurumHidden) {
                planningModalStatus.addEventListener('change', function () {
                    planningDurumHidden.value = planningModalStatus.value || 'beklemede';
                });
            }

            if (planningModalQty && planningMiktarHidden) {
                planningModalQty.addEventListener('input', function () {
                    planningMiktarHidden.value = planningModalQty.value || '';
                });
            }
        }


        if (linesBody && salesLinksModal) {
            var salesLinksTbody = document.getElementById('salesLinksTbody');
            var salesLinksClose = document.getElementById('salesLinksClose');
            var salesLinksClose2 = document.getElementById('salesLinksClose2');

            linesBody.addEventListener('click', function (e) {
                var btn = e.target.closest('.purchase-sales-detail-button');
                if (!btn) return;
                e.preventDefault();

                var tr = btn.closest('tr');
                if (!tr) return;

                var hidden = tr.querySelector('.sales-links-json');
                var links = [];
                if (hidden && hidden.value) {
                    try {
                        links = JSON.parse(hidden.value) || [];
                    } catch (e2) {
                        links = [];
                    }
                }

                if (salesLinksTbody) {
                    salesLinksTbody.innerHTML = '';
                    if (!Array.isArray(links) || links.length === 0) {
                        salesLinksTbody.innerHTML = '<tr><td colspan=\"4\" style=\"padding:12px; text-align:center; color:#6b7280;\">Eşleşen satış satırı bulunamadı.</td></tr>';
                    } else {
                        links.forEach(function (l) {
                            var row = document.createElement('tr');
                            var carikod = (l.carikod || '').toString();
                            var siparisNo = (l.siparis_no || '').toString();
                            var tarih = formatDateTr(l.tarih || '');
                            var miktar = (l.miktar != null ? l.miktar : '').toString();
                            row.innerHTML =
                                '<td>' + carikod + '</td>' +
                                '<td>' + siparisNo + '</td>' +
                                '<td>' + tarih + '</td>' +
                                '<td style=\"text-align:right;\">' + miktar + '</td>';
                            salesLinksTbody.appendChild(row);
                        });
                    }
                }

                openModal(salesLinksModal);
            });

            salesLinksModal.addEventListener('click', function (e) {
                if (e.target === salesLinksModal) {
                    closeModal(salesLinksModal);
                }
            });

            if (salesLinksClose) {
                salesLinksClose.addEventListener('click', function () {
                    closeModal(salesLinksModal);
                });
            }

            if (salesLinksClose2) {
                salesLinksClose2.addEventListener('click', function () {
                    closeModal(salesLinksModal);
                });
            }
        }

        if (btnCariSearch && firmModal) {
            btnCariSearch.addEventListener('click', function () {
                openModal(firmModal);
            });
        }

        if (firmModal) {
            firmModal.addEventListener('click', function (e) {
                if (e.target === firmModal) {
                    closeModal(firmModal);
                }
            });

            // ÃœrÃ¼n seÃ§imi â€“ stok kodu veya aÃ§Ä±klamaya Ã§ift tÄ±k
        if (linesBody && productModal) {
            linesBody.addEventListener('dblclick', function (e) {
                var target = e.target;
                if (!target || !target.classList) return;
                if (!target.classList.contains('stok-kod') &&
                    !target.classList.contains('stok-aciklama')) {
                    return;
                }
                currentProductRow = target.closest('tr');
                if (currentProductRow) {
                    openModal(productModal);
                }
            });

            productModal.addEventListener('click', function (e) {
                if (e.target === productModal) {
                    closeModal(productModal);
                }
            });

            document.querySelectorAll('.product-row').forEach(function (row) {
                row.addEventListener('click', function () {
                    if (!currentProductRow) return;

                    var kod = this.dataset.kod || '';
                    var aciklama = this.dataset.aciklama || '';
                    var fiyat = this.dataset.fiyat || '';
                    var urunId = this.dataset.id || '';

                    var kodInput = currentProductRow.querySelector('.stok-kod');
                    var aciklamaInput = currentProductRow.querySelector('.stok-aciklama');
                    var fiyatInput = currentProductRow.querySelector('.birim-fiyat');
                    var urunIdInput = currentProductRow.querySelector('.urun-id');
                    var satirAciklamaHidden = currentProductRow.querySelector('.satir-aciklama-hidden');

                    if (kodInput) kodInput.value = kod;
                    if (aciklamaInput) aciklamaInput.value = aciklama;
                    if (fiyatInput) fiyatInput.value = fiyat;
                    if (urunIdInput) urunIdInput.value = urunId;
                    if (satirAciklamaHidden) satirAciklamaHidden.value = aciklama;

                    closeModal(productModal);
                });
            });
        }


            document.querySelectorAll('.firm-row').forEach(function (row) {
                row.addEventListener('click', function () {
                    var carikod = this.dataset.carikod || '';
                    var cariaciklama = this.dataset.cariaciklama || '';

                    if (carikodInput) {
                        carikodInput.value = carikod;
                    }
                    if (cariaciklamaInput) {
                        cariaciklamaInput.value = cariaciklama;
                    }

                    if (firmaKodLabel) {
                        firmaKodLabel.textContent = carikod;
                    }
                    if (firmaAciklamaLabel) {
                        firmaAciklamaLabel.textContent = cariaciklama;
                    }

                    if (firmaAdres1 || firmaAdres2) {
                        var adres1 = this.dataset.adres1 || '';
                        var adres2 = this.dataset.adres2 || '';
                        if (firmaAdres1) firmaAdres1.textContent = adres1;
                        if (firmaAdres2) firmaAdres2.textContent = adres2;
                    }

                    if (firmaIlIlce) {
                        var il = this.dataset.il || '';
                        var ilce = this.dataset.ilce || '';
                        var ilIlceText = il;
                        if (ilce) {
                            ilIlceText = il ? (il + ' / ' + ilce) : ilce;
                        }
                        firmaIlIlce.textContent = ilIlceText;
                    }

                    var authJson = this.dataset.authorities || '[]';
                    try {
                        currentFirmAuthorities = JSON.parse(authJson) || [];
                    } catch (e) {
                        currentFirmAuthorities = [];
                    }

                    // Cariye ait varsayılan iskonto oranlarını sakla
                    currentFirmDiscounts = {
                        isk1: parseFloat(this.dataset.isk1 || '0') || 0,
                        isk2: parseFloat(this.dataset.isk2 || '0') || 0,
                        isk3: parseFloat(this.dataset.isk3 || '0') || 0,
                        isk4: parseFloat(this.dataset.isk4 || '0') || 0,
                        isk5: parseFloat(this.dataset.isk5 || '0') || 0,
                        isk6: parseFloat(this.dataset.isk6 || '0') || 0
                    };

                    if (btnYetkiliSearch) {
                        btnYetkiliSearch.disabled = false;
                    }

                    closeModal(firmModal);
                });
            });
        }

        // İşlem türü seçimi
        if (btnIslemTuruSearch && islemTuruModal) {
            btnIslemTuruSearch.addEventListener('click', function () {
                openModal(islemTuruModal);
            });
        }

        if (islemTuruModal) {
            islemTuruModal.addEventListener('click', function (e) {
                if (e.target === islemTuruModal) {
                    closeModal(islemTuruModal);
                }
            });

            document.querySelectorAll('.islem-turu-row').forEach(function (row) {
                row.addEventListener('click', function () {
                    var id = this.dataset.id || '';
                    var ad = this.dataset.ad || '';

                    if (islemTuruIdInput) islemTuruIdInput.value = id;
                    if (islemTuruAdiInput) islemTuruAdiInput.value = ad;

                    closeModal(islemTuruModal);
                });
            });
        }

        // Proje seçimi
        if (btnProjeSearch && projeModal) {
            btnProjeSearch.addEventListener('click', function () {
                openModal(projeModal);
            });
        }

        if (projeModal) {
            projeModal.addEventListener('click', function (e) {
                if (e.target === projeModal) {
                    closeModal(projeModal);
                }
            });

            document.querySelectorAll('.project-row').forEach(function (row) {
                row.addEventListener('click', function () {
                    var id = this.dataset.id || '';
                    var kod = this.dataset.kod || '';

                    if (projeIdInput) projeIdInput.value = id;
                    if (projeKodInput) projeKodInput.value = kod;

                    closeModal(projeModal);
                });
            });
        }

        // ï¿½rï¿½n seï¿½imi - stok kodu veya aï¿½ï¿½klamaya ï¿½ift tï¿½k
        if (linesBody && productModal) {
            linesBody.addEventListener('dblclick', function (e) {
                var cell = e.target.closest('td');
                if (!cell) return;

                if (!cell.classList.contains('stok-kod-cell') &&
                    !cell.classList.contains('stok-aciklama-cell')) {
                    return;
                }

                currentProductRow = cell.parentElement;
                if (currentProductRow) {
                    openModal(productModal);
                }
            });

            productModal.addEventListener('click', function (e) {
                if (e.target === productModal) {
                    closeModal(productModal);
                }
            });

            document.querySelectorAll('.product-row').forEach(function (row) {
                row.addEventListener('click', function () {
                    if (!currentProductRow) return;

                    var kod = this.dataset.kod || '';
                    var aciklama = this.dataset.aciklama || '';
                    var fiyat = this.dataset.fiyat || '';

                    var kodInput = currentProductRow.querySelector('.stok-kod');
                    var aciklamaInput = currentProductRow.querySelector('.stok-aciklama');
                    var fiyatInput = currentProductRow.querySelector('.birim-fiyat');
                    var miktarInput = currentProductRow.querySelector('.miktar');

                    if (kodInput) kodInput.value = kod;
                    if (aciklamaInput) aciklamaInput.value = aciklama;
                    if (fiyatInput) fiyatInput.value = fiyat;

                    // Ürün seçildiğinde miktar 1 olsun (boşsa)
                    if (miktarInput && !miktarInput.value) {
                        miktarInput.value = '1';
                    }

                    // Seçili carinin iskonto oranlarını satıra uygula
                    if (currentFirmDiscounts) {
                        var d1 = currentProductRow.querySelector('.isk1');
                        var d2 = currentProductRow.querySelector('.isk2');
                        var d3 = currentProductRow.querySelector('.isk3');
                        var d4 = currentProductRow.querySelector('.isk4');
                        var d5 = currentProductRow.querySelector('.isk5');
                        var d6 = currentProductRow.querySelector('.isk6');

                        if (d1) d1.value = currentFirmDiscounts.isk1;
                        if (d2) d2.value = currentFirmDiscounts.isk2;
                        if (d3) d3.value = currentFirmDiscounts.isk3;
                        if (d4) d4.value = currentFirmDiscounts.isk4;
                        if (d5) d5.value = currentFirmDiscounts.isk5;
                        if (d6) d6.value = currentFirmDiscounts.isk6;
                    }

                    // Ürün seçildiğinde satır hesaplamasını tetikle
                    var triggerInput = currentProductRow.querySelector('.birim-fiyat') || currentProductRow.querySelector('.miktar');
                    if (triggerInput) {
                        var ev = new Event('input', { bubbles: true });
                        triggerInput.dispatchEvent(ev);
                    }

                    closeModal(productModal);
                });
            });
        }

        if (btnYetkiliSearch && authorityModal) {
            btnYetkiliSearch.addEventListener('click', function () {
                if (!carikodInput || !carikodInput.value) {
                    return;
                }

                if (authorityList) {
                    authorityList.innerHTML = '';
                    currentFirmAuthorities.forEach(function (name) {
                        var li = document.createElement('li');
                        li.textContent = name;
                        li.addEventListener('click', function () {
                            if (yetkiliInput) {
                                yetkiliInput.value = name;
                            }
                            closeModal(authorityModal);
                        });
                        authorityList.appendChild(li);
                    });
                }

                if (authorityNewInput) {
                    authorityNewInput.value = '';
                }

                openModal(authorityModal);
            });
        }

        if (authorityModal) {
            authorityModal.addEventListener('click', function (e) {
                if (e.target === authorityModal) {
                    closeModal(authorityModal);
                }
            });
        }

        if (authorityUseButton) {
            authorityUseButton.addEventListener('click', function () {
                var val = (authorityNewInput && authorityNewInput.value || '').trim();
                if (val && yetkiliInput) {
                    yetkiliInput.value = val;
                }
                closeModal(authorityModal);
            });
        }

        // Enter ile formun kazara gönderilmesini engelle
        var offerForm = document.getElementById('orderForm');
        if (offerForm) {
            offerForm.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' && e.target && e.target.tagName !== 'TEXTAREA') {
                    e.preventDefault();
                }
            });
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var topMenuToggle = document.getElementById('topMenuToggle');
        var topMenuDropdown = document.getElementById('topMenuDropdown');
        var menuPrint = document.getElementById('menuPrint');
        var menuOrderDelete = document.getElementById('menuOrderDelete');
        var deleteOrderForm = document.getElementById('deleteOrderForm');

        if (topMenuToggle && topMenuDropdown) {
            topMenuToggle.addEventListener('click', function (e) {
                e.stopPropagation();
                topMenuDropdown.classList.toggle('open');
            });

            document.addEventListener('click', function () {
                topMenuDropdown.classList.remove('open');
            });

            topMenuDropdown.addEventListener('click', function (e) {
                e.stopPropagation();
            });
        }

        if (menuPrint) {
            menuPrint.addEventListener('click', function () {
                var printLink = document.querySelector('.top-bar a.small-btn[title*="Yazd"]');
                if (printLink && printLink.href) {
                    // Yazdır formunu aynı sekmede aç
                    window.location.href = printLink.href;
                    return;
                }
                var printButton = document.querySelector('.top-bar button.small-btn[title*="Yazd"]');
                if (printButton) {
                    printButton.click();
                }
            });
        }

        if (menuOrderDelete && deleteOrderForm && !menuOrderDelete.disabled) {
            menuOrderDelete.addEventListener('click', function (e) {
                e.preventDefault();
                var ok = window.confirm('Sipariş silinsin mi? Bu işlem geri alınamaz.');
                if (ok) {
                    deleteOrderForm.requestSubmit();
                }
            });
        }
    });
</script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>

















