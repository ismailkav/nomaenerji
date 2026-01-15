<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Yeni Teklif - NomaEnerji</title>
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
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .lines-header #btnAddLine {
            border-radius: 999px;
            border: none;
            background: #e5e7eb;
            padding: 0.4rem 0.9rem;
            font-size: 0.8rem;
            cursor: pointer;
        }
        .lines-header .lines-header-left {
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }
        .offer-line-setting-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.45rem 0.25rem;
            border-bottom: 1px solid #f1f5f9;
            user-select: none;
        }
        .offer-line-setting-item:last-child {
            border-bottom: none;
        }
        .offer-line-setting-item.dragging {
            opacity: 0.55;
        }
        .offer-line-setting-no {
            width: 28px;
            font-size: 0.8rem;
            color: #6b7280;
            text-align: right;
        }
        .offer-line-setting-handle {
            width: 18px;
            color: #9ca3af;
            cursor: grab;
            text-align: center;
        }
        .offer-line-setting-label {
            flex: 1;
            font-size: 0.85rem;
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
        .montaj-group-row td {
            background: #e0f2fe;
            color: #0c4a6e;
            font-weight: 600;
        }
        .offer-lines th.stok-kod,
        .offer-lines td.stok-kod-cell { width: 12%; }
        .offer-lines th.stok-aciklama,
        .offer-lines td.stok-aciklama-cell { width: 20%; }
        .offer-lines th.iskonto,
        .offer-lines td.iskonto-cell { width: 4%; }
        .offer-lines th.birim-fiyat,
        .offer-lines td.birim-fiyat-cell { width: 8%; }
        .offer-lines th.miktar,
        .offer-lines td.miktar-cell { width: 6%; }
        .offer-lines th.doviz,
        .offer-lines td.doviz-cell { width: 5%; }
        .offer-lines th.kur,
        .offer-lines td.kur-cell { width: 6%; }
        .offer-lines th.kdv,
        .offer-lines td.kdv-cell { width: 6%; }
        .offer-lines th.kdv-durum,
        .offer-lines td.kdv-durum-cell { width: 6%; }
        .offer-lines th.satir-tutar-doviz,
        .offer-lines td.satir-tutar-doviz-cell { width: 8%; }
        .offer-lines th.satir-tutar,
        .offer-lines td.satir-tutar-cell { width: 8%; }
        #montajDetailTable th:nth-child(1),
        #montajDetailTable td:nth-child(1) { width: 40%; }
        #montajDetailTable th:nth-child(2),
        #montajDetailTable td:nth-child(2) { width: 6%; }
        #montajDetailTable th:nth-child(6),
        #montajDetailTable td:nth-child(6) { width: 10%; }
        .offer-lines input,
        .offer-lines select,
        .offer-lines textarea {
            width: 100%;
            border: none !important;
            background: transparent !important;
            box-shadow: none !important;
            font-size: 0.75rem;
            outline: none;
        }
        .offer-lines textarea {
            resize: none;
            overflow: hidden;
            line-height: 1.15rem;
        }
        .offer-lines input[type="number"] {
            text-align: right;
        }
        .offer-lines input[type="number"]::-webkit-outer-spin-button,
        .offer-lines input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .offer-lines input[type="number"] {
            -moz-appearance: textfield;
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
    @php
        $offerTur = $offerTur ?? request('tur', 'satis');
        $offerTur = in_array($offerTur, ['alim', 'satis'], true) ? $offerTur : 'satis';
    @endphp
    @include('partials.sidebar', ['active' => $offerTur === 'alim' ? 'offers-purchase' : 'offers-sales'])

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
                Yeni Teklif
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
                    @if(isset($teklif))
                        <a href="{{ route('offers.print', $teklif) }}" target="_blank" class="small-btn" title="Yazdır">
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
                        <button type="button" class="top-menu-item" id="menuPdf" @unless(isset($teklif)) disabled @endunless>
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
                        @php
                            $canCreateSalesOrder = isset($teklif)
                                && in_array(mb_strtolower(trim((string) ($teklif->onay_durum ?? '')), 'UTF-8'), ['onaylı', 'onayli'], true);
                        @endphp
                         <button type="submit"
                                 class="top-menu-item"
                                 id="menuOrder"
                                 form="createSalesOrderForm"
                                 @unless($canCreateSalesOrder) disabled style="opacity:0.4;cursor:not-allowed;" @endunless>
                             <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                 <path d="M7 4h10l-1 14H8L7 4z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                 <path d="M10 4V3a2 2 0 0 1 4 0v1" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                             </svg>
                             <span>Sipariş Oluştur</span>
                         </button>
                     </div>
                 </div>
                <a href="{{ route('offers.index', ['tur' => $offerTur]) }}" class="btn btn-cancel" style="margin-left:0.75rem;">İptal</a>
                <button type="submit" form="offerForm" class="btn btn-save">Kaydet</button>
            </div>
        </header>

          <section class="content-section" style="padding: 2rem;">
            @if(isset($teklif))
                <form id="revizeCreateForm" method="POST" action="{{ route('offers.revize', $teklif) }}" style="display:none;">
                    @csrf
                </form>
                <form id="createSalesOrderForm" method="POST" action="{{ route('offers.create-sales-order', $teklif) }}" style="display:none;">
                    @csrf
                </form>
                @if(((int) ($teklif->revize_no ?? 1)) > 1)
                    <form id="revizeDeleteForm" method="POST" action="{{ route('offers.revize.destroy', $teklif) }}" style="display:none;">
                        @csrf
                        @method('DELETE')
                    </form>
                @else
                    <form id="revizeDeleteForm" style="display:none;"></form>
                @endif
            @else
                <form id="revizeCreateForm" style="display:none;"></form>
                <form id="revizeDeleteForm" style="display:none;"></form>
                <form id="createSalesOrderForm" style="display:none;"></form>
            @endif
            <form id="offerForm" method="POST" action="{{ isset($teklif) ? route('offers.update', $teklif) : route('offers.store') }}">
                 @csrf
                 @if(isset($teklif))
                     @method('PUT')
                 @endif
                <input type="hidden" name="tur" value="{{ $offerTur }}">
                <div class="offer-card">
    <div class="offer-header">
        <div class="offer-header-left">
            <div class="offer-header-row">
                <div class="form-group">
                    <label for="teklif_no" style="color:#9ca3af;">Teklif No:</label>
                    <input id="teklif_no" name="teklif_no" type="text" style="text-align:left;" value="{{ old('teklif_no', isset($teklif) ? $teklif->teklif_no : ($nextTeklifNo ?? '')) }}" readonly>
                </div>
                <div class="form-group">
                    <label for="revize_no" style="color:#9ca3af;">Revize No:</label>
                    <div class="input-with-button" style="gap:0.25rem;">
                        <input id="revize_no" name="revize_no" type="text" style="text-align:left !important;width:60px;" value="{{ old('revize_no', $initialRevizeNo ?? '1') }}" readonly>
                        <button type="button" class="small-btn" id="btnRevizeList" title="Revize Listesi" @if(!isset($teklif)) disabled @endif>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="11" cy="11" r="6" stroke="currentColor" stroke-width="2"/>
                                <line x1="16" y1="16" x2="21" y2="21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </button>
                        <button type="button" class="small-btn" id="btnRevizeCreate" title="Revize Oluştur" @if(!isset($teklif)) disabled @endif>
                            Revize Oluştur
                        </button>
                    </div>
                </div>
            </div>
            <div class="offer-header-row">
                <div class="form-group">
                    <label for="carikod" style="color:#9ca3af;">Firma:</label>
                    <div class="input-with-button">
                        <span id="firma_kod_label">{{ old('carikod', isset($teklif) ? $teklif->carikod : '') }}</span>
                        <span>/</span>
                        <span id="firma_aciklama_label">{{ old('cariaciklama', isset($teklif) ? $teklif->cariaciklama : '') }}</span>
                        <input id="carikod" name="carikod" type="hidden" value="{{ old('carikod', isset($teklif) ? $teklif->carikod : '') }}">
                        <input id="cariaciklama" name="cariaciklama" type="hidden" value="{{ old('cariaciklama', isset($teklif) ? $teklif->cariaciklama : '') }}">
                        <button type="button" class="small-btn" id="btnCariSearch"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="11" cy="11" r="6" stroke="currentColor" stroke-width="2"/><line x1="16" y1="16" x2="21" y2="21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="islem_turu_adi" style="color:#9ca3af;">İşlem Türü:</label>
                    <div class="input-with-button">
                        <input id="islem_turu_adi" type="text"
                               value="{{ old('islem_turu_adi', isset($teklif) && $teklif->islemTuru ? $teklif->islemTuru->ad : '') }}"
                               style="border:none;background:transparent;outline:none;padding:0;" readonly>
                        <input id="islem_turu_id" name="islem_turu_id" type="hidden"
                               value="{{ old('islem_turu_id', isset($teklif) ? $teklif->islem_turu_id : '') }}">
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
                               value="{{ old('proje_kod', isset($teklif) && $teklif->proje ? $teklif->proje->kod : '') }}"
                               style="border:none;background:transparent;outline:none;padding:0;" readonly>
                        <input id="proje_id" name="proje_id" type="hidden"
                               value="{{ old('proje_id', isset($teklif) ? $teklif->proje_id : '') }}">
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
                        <input id="yetkili_personel" name="yetkili_personel" type="text" value="{{ old('yetkili_personel', isset($teklif) ? $teklif->yetkili_personel : '') }}" style="border:none;background:transparent;outline:none;padding:0;">
                        <button type="button" class="small-btn" id="btnYetkiliSearch"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="11" cy="11" r="6" stroke="currentColor" stroke-width="2"/><line x1="16" y1="16" x2="21" y2="21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></button>
                    </div>
                </div>
            </div>

            <div class="offer-header-row">
                <div class="form-group">
                    <label for="gecen_sure" style="color:#9ca3af;">Geçen Süre:</label>
                    <input id="gecen_sure" type="text" value="" style="border:none;background:transparent;outline:none;padding:0;" readonly>
                </div>

                <div class="form-group">
                    <label for="gerceklesme_olasiligi" style="color:#9ca3af;">Gerçekleşme Olasılığı:</label>
                    <select id="gerceklesme_olasiligi" name="gerceklesme_olasiligi" style="border:none;background:transparent;outline:none;padding:0;">
                        @php
                            $olasilikDegeri = old('gerceklesme_olasiligi', isset($teklif) ? $teklif->gerceklesme_olasiligi : 25);
                        @endphp
                        <option value="" {{ $olasilikDegeri === null || $olasilikDegeri === '' ? 'selected' : '' }}>Seçiniz</option>
                        @foreach([25, 50, 75, 100] as $oran)
                            <option value="{{ $oran }}" {{ (string) $olasilikDegeri === (string) $oran ? 'selected' : '' }}>{{ $oran }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @if(false)
            <div class="offer-header-row">
                <div class="form-group">
                    <label for="islem_turu_adi" style="color:#9ca3af;">İşlem Türü:</label>
                    <div class="input-with-button">
                        <input id="islem_turu_adi" type="text"
                               value="{{ old('islem_turu_adi', isset($teklif) && $teklif->islemTuru ? $teklif->islemTuru->ad : '') }}"
                               style="border:none;background:transparent;outline:none;padding:0;" readonly>
                        <input id="islem_turu_id" name="islem_turu_id" type="hidden"
                               value="{{ old('islem_turu_id', isset($teklif) ? $teklif->islem_turu_id : '') }}">
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
                               value="{{ old('proje_kod', isset($teklif) && $teklif->proje ? $teklif->proje->kod : '') }}"
                               style="border:none;background:transparent;outline:none;padding:0;" readonly>
                        <input id="proje_id" name="proje_id" type="hidden"
                               value="{{ old('proje_id', isset($teklif) ? $teklif->proje_id : '') }}">
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
                <label for="tarih" style="color:#9ca3af;">Teklif Tarihi:</label>
                <input id="tarih" name="tarih" type="date" value="{{ old('tarih', isset($teklif) && $teklif->tarih ? $teklif->tarih->toDateString() : now()->toDateString()) }}" required>
            </div>
            <div class="form-group">
                <label for="gecerlilik_tarihi" style="color:#9ca3af;">Geçerlilik Tarihi:</label>
                <input id="gecerlilik_tarihi" name="gecerlilik_tarihi" type="date" value="{{ old('gecerlilik_tarihi', isset($teklif) && $teklif->gecerlilik_tarihi ? $teklif->gecerlilik_tarihi->toDateString() : null) }}">
            </div>
            <div class="form-group">
                <label for="teklif_durum" style="color:#9ca3af;">Teklif Durum:</label>
                <select id="teklif_durum" name="teklif_durum">
                    @foreach($durumlar as $key => $label)
                        @if($key === 'hepsi') @continue @endif
                        <option value="{{ $key }}" {{ old('teklif_durum', isset($teklif) ? ($teklif->teklif_durum ?? 'Taslak') : 'Taslak') === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="onay_durum" style="color:#9ca3af;">Onay Durum:</label>
                <div class="input-with-button">
                    <input id="onay_durum" name="onay_durum" type="text" value="{{ old('onay_durum', isset($teklif) ? ($teklif->onay_durum ?? 'Onay bekliyor') : 'Onay bekliyor') }}">
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
                <input id="onay_tarihi" name="onay_tarihi" type="date" value="{{ old('onay_tarihi', isset($teklif) && $teklif->onay_tarihi ? $teklif->onay_tarihi->toDateString() : null) }}">
            </div>
            <div class="form-group">
                <label for="hazirlayan" style="color:#9ca3af;">Hazırlayan:</label>
                <input id="hazirlayan" name="hazirlayan" type="text" value="{{ old('hazirlayan', isset($teklif) ? $teklif->hazirlayan : '') }}" style="border:none;background:transparent;outline:none;padding:0;" readonly>
            </div>
        </div>
    </div>

    <div class="form-group" style="margin-bottom: 1rem;">
                        <label for="aciklama" style="color:#9ca3af;">Açıklama</label>
                        <textarea id="aciklama" name="aciklama">{{ old('aciklama', isset($teklif) ? ($teklif->aciklama ?? '') : '') }}</textarea>
                    </div>

                    <div class="lines-header">
                        <div class="lines-header-left">
                            <button type="button" id="btnOfferLineSettings" class="small-btn" title="Sütun Ayarları">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z" stroke="currentColor" stroke-width="2"/>
                                    <path d="M19.4 15a8.07 8.07 0 0 0 .04-1 8.07 8.07 0 0 0-.04-1l2.1-1.64a.5.5 0 0 0 .12-.65l-2-3.46a.5.5 0 0 0-.6-.22l-2.48 1a7.74 7.74 0 0 0-1.73-1l-.38-2.65A.5.5 0 0 0 13.94 3h-4a.5.5 0 0 0-.49.42l-.38 2.65c-.62.24-1.2.57-1.73 1l-2.48-1a.5.5 0 0 0-.6.22l-2 3.46a.5.5 0 0 0 .12.65L4.6 13c-.03.33-.04.66-.04 1s.01.67.04 1l-2.1 1.64a.5.5 0 0 0-.12.65l2 3.46a.5.5 0 0 0 .6.22l2.48-1c.53.43 1.11.76 1.73 1l.38 2.65a.5.5 0 0 0 .49.42h4a.5.5 0 0 0 .49-.42l.38-2.65c.62-.24 1.2-.57 1.73-1l2.48 1a.5.5 0 0 0 .6-.22l2-3.46a.5.5 0 0 0-.12-.65L19.4 15Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                        <button type="button" id="btnAddLine">Satır Ekle</button>
                    </div>

                    <table class="offer-lines">
                        <thead>
                        <tr>
                            <th class="stok-kod">Stok Kod</th>
                            <th class="stok-aciklama">Stok Açıklama</th>
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
                            <th class="satir-tutar-doviz">Satır Tutar Döviz</th>
                            <th>Satır Tutar</th>
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
                                <label for="offer_currency" style="color:#9ca3af;margin-right:0.5rem;">Teklif Döviz:</label>
                                <select id="offer_currency" name="teklif_doviz" style="min-width:80px;border-radius:999px;border:1px solid #e5e7eb;padding:0.25rem 0.6rem;font-size:0.8rem;outline:none;">
                                    @php($offerDoviz = old('teklif_doviz', $teklif->teklif_doviz ?? 'TL'))
                                    <option value="TL" @selected($offerDoviz === 'TL')>TL</option>
                                    <option value="USD" @selected($offerDoviz === 'USD')>USD</option>
                                    <option value="EUR" @selected($offerDoviz === 'EUR')>EUR</option>
                                </select>
                            </div>
                            <div style="display:flex; align-items:center; gap: 6px;">
                                <label for="offer_rate" style="color:#9ca3af;margin-right:0.5rem;">Teklif Kur:</label>
                                <input id="offer_rate" name="teklif_kur" value="{{ old('teklif_kur', $teklif->teklif_kur ?? 1) }}" type="number" step="0.0001" style="width:100px;border-radius:999px;border:1px solid #e5e7eb;padding:0.25rem 0.6rem;font-size:0.8rem;outline:none;">
                                <button type="button" class="small-btn rate-search-btn" data-rate-target="header" title="Kur Seç">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M21 21l-4.3-4.3m1.8-5.2a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                </button>
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
                        <a href="{{ route('offers.index', ['tur' => $offerTur]) }}" class="btn btn-cancel">İptal</a>
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
                <input id="firmModalSearch" type="text" placeholder="Ara (Cari Kod / Açıklama)"
                       style="margin-left:auto;min-width:260px;border-radius:999px;border:1px solid #e5e7eb;padding:0.35rem 0.75rem;font-size:0.9rem;outline:none;">
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

    <div id="productModal" class="modal-overlay" style="z-index: 60;">
        <div class="modal">
            <div class="modal-header">
                <div class="modal-title">Ürün Seç</div>
                <input id="productModalSearch" type="text" placeholder="Ara (Stok Kod / Aciklama)"
                       style="margin-left:auto;min-width:260px;border-radius:999px;border:1px solid #e5e7eb;padding:0.35rem 0.75rem;font-size:0.9rem;outline:none;">
                <button type="button" class="small-btn" data-modal-close="productModal">?</button>
            </div>
            <div class="modal-body">
                <table class="modal-table">
                    <thead>
                    <tr>
                        <th>Stok Kod</th>
                        <th>Stok Açıklama</th>
                        <th>Birim Fiyat</th>
                        <th>Doviz</th>
                        <th class="num">Stok Miktar</th>
                        <th class="num">Rezerve Miktar</th>
                        <th class="num">Kullanılabilir</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($products as $product)
                        @php($stokMiktar = (float) ($product->envanter_stok_miktar ?? 0))
                        @php($rezerveMiktar = (float) ($product->rezerve_miktar ?? 0))
                        @php($kullanilabilir = $stokMiktar - $rezerveMiktar)
                        <tr class="product-row"
                            data-id="{{ $product->id }}"
                            data-kod="{{ $product->kod }}"
                            data-aciklama="{{ $product->aciklama }}"
                            data-fiyat="{{ $product->satis_fiyat }}"
                            data-doviz="{{ $product->satis_doviz ?? 'TL' }}"
                            data-detaygrup="{{ $product->urun_detay_grup_id ?? '' }}"
                            data-resim="{{ $product->resim_yolu ?? '' }}"
                            data-multi="{{ !empty($product->multi) ? 1 : 0 }}"
                            data-montaj="{{ !empty($product->montaj) ? 1 : 0 }}"
                            data-stokmiktar="{{ (int) round($stokMiktar) }}"
                            data-rezervemiktar="{{ (int) round($rezerveMiktar) }}"
                            data-kullanilabilirmiktar="{{ (int) round($kullanilabilir) }}">
                            <td>{{ $product->kod }}</td>
                            <td>{{ $product->aciklama }}</td>
                            <td>{{ number_format($product->satis_fiyat, 2) }}</td>
                            <td>{{ $product->satis_doviz ?? 'TL' }}</td>
                            <td class="num" style="text-align:right;">{{ number_format((float) $stokMiktar, 0, ',', '.') }}</td>
                            <td class="num" style="text-align:right;">{{ number_format((float) $rezerveMiktar, 0, ',', '.') }}</td>
                            <td class="num" style="text-align:right;">{{ number_format((float) $kullanilabilir, 0, ',', '.') }}</td>
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

    <div id="revizeModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <div class="modal-title">Revize Listesi</div>
                <button type="button" class="small-btn" data-modal-close="revizeModal">X</button>
            </div>
            <div class="modal-body">
                <table class="modal-table">
                    <thead>
                    <tr>
                        <th>Revize No</th>
                        <th>Revize Tarihi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse(($revizyonlar ?? collect()) as $rev)
                        <tr class="revize-row" data-href="{{ route('offers.edit', $rev->id) }}">
                            <td>{{ $rev->revize_no }}</td>
                            <td>{{ $rev->revize_tarihi ? $rev->revize_tarihi->format('d.m.Y') : '' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" style="text-align:center;padding:12px;">Revize bulunamadı.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" data-modal-close="revizeModal">Kapat</button>
            </div>
        </div>
    </div>
</div>

<!-- Kur seçimi modal -->
<div id="rateModal" class="modal-overlay">
    <div class="modal" style="max-width: 560px;">
        <div class="modal-header">
            <div class="modal-title">Kur Seç</div>
            <button type="button" class="small-btn" id="rateModalClose">✕</button>
        </div>
        <div class="modal-body">
            <div id="rateModalMeta" style="font-size:0.85rem;color:#6b7280;margin-bottom:0.75rem;"></div>
            <div id="rateModalError" style="display:none;color:#dc2626;font-size:0.85rem;margin-bottom:0.75rem;"></div>
            <table style="width:100%;border-collapse:collapse;font-size:0.9rem;">
                <thead>
                <tr>
                    <th style="text-align:left;padding:0.5rem;border-bottom:1px solid #e5e7eb;color:#6b7280;font-weight:600;">Tip</th>
                    <th style="text-align:right;padding:0.5rem;border-bottom:1px solid #e5e7eb;color:#6b7280;font-weight:600;">Kur</th>
                    <th style="text-align:right;padding:0.5rem;border-bottom:1px solid #e5e7eb;color:#6b7280;font-weight:600;">Seç</th>
                </tr>
                </thead>
                <tbody id="rateModalBody"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Takım detay modal -->
<div id="teamDetailModal" class="modal-overlay">
    <div class="modal" style="max-width:none;width:calc(100vw - 32px);">
        <div class="modal-header">
            <div class="modal-title">Takım Detay</div>
            <div style="display:flex;gap:8px;align-items:center;">
                <button type="button" class="btn btn-secondary" id="btnTeamDetailAddRow">Satr Ekle</button>
                <button type="button" class="small-btn" data-modal-close="teamDetailModal">X</button>
            </div>
        </div>
        <div class="modal-body">
            <div id="teamDetailMeta" style="font-size:0.9rem;color:#374151;margin-bottom:0.75rem;"></div>
            <table class="offer-lines" style="font-size:0.9rem;">
                <thead>
                <tr>
                    <th class="stok-kod">Stok Kod</th>
                    <th class="stok-aciklama">Stok Açıklama</th>
                    <th class="miktar">Miktar</th>
                    <th class="birim-fiyat">Birim Fiyat</th>
                    <th>İsk.1%</th>
                    <th>İsk.2%</th>
                    <th class="doviz">Döviz</th>
                    <th class="kur">Kur</th>
                    <th>Satır Tutar</th>
                    <th></th>
                </tr>
                </thead>
                <tbody id="teamDetailBody"></tbody>
            </table>
        </div>
        <div class="modal-actions" style="display:flex;gap:8px;justify-content:flex-end;">
            <button type="button" class="btn btn-secondary" id="btnTeamDetailAddRow">Satır Ekle</button>
            <button type="button" class="btn btn-primary" id="btnTeamDetailSave">Kaydet</button>
            <button type="button" class="btn btn-cancel" data-modal-close="teamDetailModal">Kapat</button>
        </div>
    </div>
</div>


 <!-- Sütun ayarları modal -->
<div id="montajDetailModal" class="modal-overlay">
    <div class="modal" style="max-width:80vw;width:80vw;">
        <div class="modal-header">
            <div class="modal-title">Montaj Detay</div>
            <button type="button" class="small-btn" data-modal-close="montajDetailModal">X</button>
        </div>
        <div class="modal-body">
            <div id="montajDetailMeta" style="font-size:0.9rem;color:#374151;margin-bottom:0.75rem;"></div>
            <table id="montajDetailTable" class="offer-lines" style="font-size:0.9rem;">
                <thead>
                <tr>
                    <th class="stok-kod">Ürün Kod</th>
                    <th class="birim">Birim</th>
                    <th class="miktar">Miktar</th>
                    <th class="birim-fiyat">Birim Fiyat</th>
                    <th class="doviz">D”viz</th>
                    <th>Satr Tutar</th>
                </tr>
                </thead>
                <tbody id="montajDetailBody"></tbody>
            </table>
        </div>
        <div class="modal-actions" style="display:flex;gap:8px;justify-content:flex-end;">
            <button type="button" class="btn btn-primary" id="btnMontajDetailSave">Kaydet</button>
            <button type="button" class="btn btn-cancel" data-modal-close="montajDetailModal">Kapat</button>
        </div>
    </div>
</div>

<div id="pdfPreviewModal" class="modal-overlay">
    <div class="modal" style="max-width:none;width:calc(100vw - 32px);">
        <div class="modal-header">
            <div class="modal-title">PDF Önizleme</div>
            <button type="button" class="small-btn" data-modal-close="pdfPreviewModal">X</button>
        </div>
        <div class="modal-body" style="padding:0;">
            <iframe id="pdfPreviewFrame" style="width:100%;height:80vh;border:none;"></iframe>
        </div>
    </div>
</div>

<div id="pdfJsonModal" class="modal-overlay">
    <div class="modal" style="max-width:none;width:calc(100vw - 32px);">
        <div class="modal-header">
            <div class="modal-title">PDF JSON</div>
            <button type="button" class="small-btn" data-modal-close="pdfJsonModal">X</button>
        </div>
        <div class="modal-body">
            <div style="font-size:0.85rem;color:#6b7280;margin-bottom:0.5rem;">
                Servlet: <span id="pdfJsonServletUrl" style="color:#111827;"></span>
            </div>
            <textarea id="pdfJsonTextarea" readonly style="width:100%;height:60vh;resize:vertical;border:1px solid #e5e7eb;border-radius:12px;padding:0.75rem;font-family:ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, \"Liberation Mono\", \"Courier New\", monospace;font-size:0.85rem;line-height:1.25rem;"></textarea>
        </div>
        <div class="modal-actions" style="display:flex;gap:8px;justify-content:flex-end;">
            <button type="button" class="btn btn-primary" id="btnPdfJsonContinue">PDF Oluştur</button>
            <button type="button" class="btn btn-cancel" data-modal-close="pdfJsonModal">İptal</button>
        </div>
    </div>
</div>

 <div id="offerLineSettingsModal" class="modal-overlay">
     <div class="modal" style="max-width: 520px;">
         <div class="modal-header">
             <div class="modal-title">Sütun Ayarları</div>
             <div style="display:flex; align-items:center; gap:0.5rem;">
                 <button type="button" class="btn btn-save" id="offerLineSettingsSave">Kaydet</button>
                 <button type="button" class="small-btn" data-modal-close="offerLineSettingsModal">✕</button>
             </div>
         </div>
         <div class="modal-body">
             <label style="display:flex; align-items:center; gap:0.5rem; font-size:0.85rem; padding-bottom:0.5rem; border-bottom:1px solid #e5e7eb; margin-bottom:0.5rem;">
                 <input type="checkbox" id="offerLineSettingsToggleAll">
                 <span>Hepsini Seç/Kaldır</span>
             </label>
             <div id="offerLineSettingsList" class="offer-line-settings-list"></div>
         </div>
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
        var btnOfferLineSettings = document.getElementById('btnOfferLineSettings');
        var lineIndex = 0;
        var initialLines = @json(isset($teklif) ? $teklif->detaylar : []);

        var offerLinesTable = document.querySelector('table.offer-lines');
        var offerLineColumns = null; // [{key,label,durum,sirano}]
        var offerLineThByKey = null;

        function getCsrfToken() {
            var token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (token) return token;
            return document.querySelector('input[name="_token"]')?.value || '';
        }

        function normalizeOfferLineColumns(columns) {
            var cols = Array.isArray(columns) ? columns.slice() : [];
            cols = cols.filter(function (c) { return c && c.key; });
            cols.sort(function (a, b) {
                return ((parseInt(a.sirano || 0, 10) || 0) - (parseInt(b.sirano || 0, 10) || 0)) || String(a.key).localeCompare(String(b.key));
            });
            return cols.map(function (c, idx) {
                return {
                    key: String(c.key),
                    label: String(c.label || c.key),
                    durum: !!c.durum,
                    sirano: idx + 1,
                };
            });
        }

        function fetchOfferLineColumns() {
            return fetch('{{ route('offer-line-columns.index') }}', {
                headers: { 'Accept': 'application/json' }
            })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (!data || !data.ok) throw new Error('load_failed');
                    offerLineColumns = normalizeOfferLineColumns(data.columns || []);
                    return offerLineColumns;
                });
        }

        function getLineCellByKey(tr, key) {
            if (!tr || !key) return null;
            var selector = null;
            if (key === 'stok_kod') selector = '.stok-kod';
            else if (key === 'stok_aciklama') selector = '.stok-aciklama';
            else if (key === 'birim_fiyat') selector = '.birim-fiyat';
            else if (key === 'miktar') selector = '.miktar';
            else if (key === 'doviz') selector = '.doviz';
            else if (key === 'kur') selector = '.kur';
            else if (key === 'isk1') selector = '.isk1';
            else if (key === 'isk2') selector = '.isk2';
            else if (key === 'isk3') selector = '.isk3';
            else if (key === 'isk4') selector = '.isk4';
            else if (key === 'isk5') selector = '.isk5';
            else if (key === 'isk6') selector = '.isk6';
            else if (key === 'isk_tutar') selector = '.isk-tutar';
            else if (key === 'kdv_orani') selector = '.kdv-oran';
            else if (key === 'kdv_durum') selector = '.kdv-durum';
            else if (key === 'satir_tutar_doviz') selector = '.satir-tutar-doviz';
            else if (key === 'satir_tutar') selector = '.satir-tutar';

            if (!selector) return null;
            var el = tr.querySelector(selector);
            return el ? el.closest('td') : null;
        }

        function ensureOfferLineHeaderMap() {
            if (offerLineThByKey) return;
            if (!offerLinesTable) return;
            var headerRow = offerLinesTable.querySelector('thead tr');
            if (!headerRow) return;
            var ths = Array.prototype.slice.call(headerRow.querySelectorAll('th'));
            var keyOrder = [
                'stok_kod',
                'stok_aciklama',
                'birim_fiyat',
                'miktar',
                'doviz',
                'kur',
                'isk1',
                'isk2',
                'isk3',
                'isk4',
                'isk5',
                'isk6',
                'isk_tutar',
                'kdv_orani',
                'kdv_durum',
                'satir_tutar_doviz',
                'satir_tutar',
            ];
            if (ths.length < keyOrder.length) return;

            offerLineThByKey = {};
            keyOrder.forEach(function (key, idx) {
                offerLineThByKey[key] = ths[idx];
            });
        }

        function applyOfferLineColumnSettingsToRow(tr) {
            if (!tr || !offerLineColumns) return;
            offerLineColumns.forEach(function (col) {
                var td = getLineCellByKey(tr, col.key);
                if (td) {
                    td.style.display = col.durum ? '' : 'none';
                }
            });
        }

        function reorderOfferLineColumns() {
            if (!offerLinesTable || !offerLineColumns) return;
            ensureOfferLineHeaderMap();
            var headerRow = offerLinesTable.querySelector('thead tr');
            if (headerRow && offerLineThByKey) {
                offerLineColumns.forEach(function (col) {
                    var th = offerLineThByKey[col.key];
                    if (th) headerRow.appendChild(th);
                });
            }

            var rows = offerLinesTable.querySelectorAll('tbody tr');
            Array.prototype.forEach.call(rows, function (tr) {
                offerLineColumns.forEach(function (col) {
                    var td = getLineCellByKey(tr, col.key);
                    if (td) tr.appendChild(td);
                });
            });
        }

        function applyOfferLineColumnSettingsToTable() {
            if (!offerLinesTable || !offerLineColumns) return;
            ensureOfferLineHeaderMap();
            reorderOfferLineColumns();

            if (offerLineThByKey) {
                offerLineColumns.forEach(function (col) {
                    var th = offerLineThByKey[col.key];
                    if (th) th.style.display = col.durum ? '' : 'none';
                });
            }

            var rows = offerLinesTable.querySelectorAll('tbody tr');
            Array.prototype.forEach.call(rows, function (tr) {
                applyOfferLineColumnSettingsToRow(tr);
            });
        }

        function fetchTodayForexSelling(currencyCode) {
            var code = (currencyCode || '').toString().trim().toUpperCase();
            if (!['USD', 'EUR'].includes(code)) {
                return Promise.reject(new Error('unsupported'));
            }

            var url = '{{ route('exchange-rate.today') }}' + '?currency_code=' + encodeURIComponent(code);
            return fetch(url, { headers: { 'Accept': 'application/json' } })
                .then(function (r) {
                    return r.json().then(function (data) {
                        if (!r.ok || !data || !data.ok) throw (data || {});
                        return data;
                    });
                })
                .then(function (data) {
                    return parseFloat((data.forex_selling || '0').toString()) || 0;
                });
        }

        function setKurValue(kurInput, rate) {
            if (!kurInput) return;
            var n = parseFloat(rate || '0') || 0;
            kurInput.value = n.toFixed(4);
            try {
                kurInput.dispatchEvent(new Event('input', { bubbles: true }));
                kurInput.dispatchEvent(new Event('change', { bubbles: true }));
            } catch (e) {
            }
            try {
                if (typeof recalcTotals === 'function') recalcTotals();
            } catch (e) {
            }
        }

        function fetchRatesForDate(currencyCode, dateISO) {
            var code = (currencyCode || '').toString().trim().toUpperCase();
            var date = (dateISO || '').toString().trim();
            if (!code) {
                return Promise.reject(new Error('currency_missing'));
            }
            if (!date) {
                return Promise.reject(new Error('date_missing'));
            }

            var url = '{{ route('exchange-rate.by-date') }}' +
                '?currency_code=' + encodeURIComponent(code) +
                '&tarih=' + encodeURIComponent(date);

            return fetch(url, { headers: { 'Accept': 'application/json' } })
                .then(function (r) {
                    return r.json().then(function (data) {
                        if (!r.ok || !data || !data.ok) throw (data || {});
                        return data;
                    });
                });
        }

        (function setupRatePicker() {
            var modal = document.getElementById('rateModal');
            var closeBtn = document.getElementById('rateModalClose');
            var metaEl = document.getElementById('rateModalMeta');
            var errorEl = document.getElementById('rateModalError');
            var bodyEl = document.getElementById('rateModalBody');

            var currentTargetInput = null;

            function openModal() {
                if (modal) modal.style.display = 'flex';
            }

            function closeModal() {
                if (modal) modal.style.display = 'none';
                currentTargetInput = null;
            }

            function showError(message) {
                if (!errorEl) return;
                errorEl.textContent = message || 'Kur verisi alınamadı.';
                errorEl.style.display = 'block';
            }

            function clearError() {
                if (!errorEl) return;
                errorEl.textContent = '';
                errorEl.style.display = 'none';
            }

            function fmtRate(val) {
                if (val === null || val === undefined || val === '') return '-';
                var n = parseFloat(val.toString().replace(',', '.'));
                if (!isFinite(n)) return '-';
                return n.toFixed(6);
            }

            function buildRows(data) {
                if (!bodyEl) return;
                bodyEl.innerHTML = '';

                var rows = [
                    { key: 'forex_buying', label: 'Merkez Alış', value: data.forex_buying },
                    { key: 'forex_selling', label: 'Merkez Satış', value: data.forex_selling },
                    { key: 'banknote_buying', label: 'Serbest Alış', value: data.banknote_buying },
                    { key: 'banknote_selling', label: 'Serbest Satış', value: data.banknote_selling },
                ];

                rows.forEach(function (row) {
                    var canPick = row.value !== null && row.value !== undefined && row.value !== '';
                    var tr = document.createElement('tr');
                    tr.innerHTML =
                        '<td style="padding:0.5rem;border-bottom:1px solid #f1f5f9;">' + row.label + '</td>' +
                        '<td style="padding:0.5rem;border-bottom:1px solid #f1f5f9;text-align:right;">' + fmtRate(row.value) + '</td>' +
                        '<td style="padding:0.5rem;border-bottom:1px solid #f1f5f9;text-align:right;">' +
                        '<button type="button" class="small-btn rate-pick-btn" data-rate="' + (canPick ? row.value : '') + '"' + (canPick ? '' : ' disabled style="opacity:0.4;cursor:not-allowed;"') + '>Seç</button>' +
                        '</td>';
                    bodyEl.appendChild(tr);
                });
            }

            function loadAndShow(targetInput, currencyCode, tarih) {
                currentTargetInput = targetInput;
                clearError();

                if (metaEl) {
                    metaEl.textContent = (currencyCode || '') + ' - ' + (tarih || '');
                }
                if (bodyEl) {
                    bodyEl.innerHTML = '<tr><td colspan="3" style="padding:0.75rem;color:#6b7280;">Yükleniyor...</td></tr>';
                }

                openModal();

                fetchRatesForDate(currencyCode, tarih)
                    .then(function (data) {
                        if (metaEl) {
                            metaEl.textContent = (data.currency_code || currencyCode) + ' - ' + (data.tarih || tarih);
                        }
                        buildRows(data);
                    })
                    .catch(function (err) {
                        var msg = (err && err.message) ? err.message : 'Kur verisi alınamadı.';
                        showError(msg);
                        if (bodyEl) bodyEl.innerHTML = '';
                    });
            }

            if (closeBtn) {
                closeBtn.addEventListener('click', function () {
                    closeModal();
                });
            }

            if (modal) {
                modal.addEventListener('click', function (e) {
                    if (e.target === modal) closeModal();
                });
            }

            document.addEventListener('click', function (e) {
                var btn = e.target && e.target.closest ? e.target.closest('.rate-search-btn') : null;
                if (!btn) return;

                e.preventDefault();

                var target = btn.getAttribute('data-rate-target') || 'line';
                var tarihEl = document.getElementById('tarih');
                var tarih = tarihEl ? (tarihEl.value || '') : '';

                if (!tarih) {
                    alert('Teklif tarihi seçiniz.');
                    return;
                }

                var currencyCode = 'TL';
                var input = null;

                if (target === 'header') {
                    currencyCode = (document.getElementById('offer_currency')?.value || 'TL').toString();
                    input = document.getElementById('offer_rate');
                } else {
                    var tr = btn.closest('tr');
                    currencyCode = (tr?.querySelector('.doviz')?.value || 'TL').toString();
                    input = tr ? tr.querySelector('.kur') : null;
                }

                if (!input) return;

                loadAndShow(input, currencyCode, tarih);
            });

            document.addEventListener('click', function (e) {
                var pickBtn = e.target && e.target.closest ? e.target.closest('.rate-pick-btn') : null;
                if (!pickBtn) return;

                e.preventDefault();
                if (!currentTargetInput) return;

                var rateVal = pickBtn.getAttribute('data-rate') || '';
                if (rateVal === '') return;

                setKurValue(currentTargetInput, rateVal);
                closeModal();
            });
        })();

        function applyCurrencyBehavior(tr, initializeDefault) {
            var currencySelect = tr.querySelector('.doviz');
            var kurInput = tr.querySelector('.kur');
            if (!currencySelect || !kurInput) return;

            function updateForCurrency() {
                var val = currencySelect.value || 'TL';
                if (val === 'TL') {
                    setKurValue(kurInput, 1);
                    return;
                }

                if (val === 'USD' || val === 'EUR') {
                    fetchTodayForexSelling(val)
                        .then(function (rate) { setKurValue(kurInput, rate); })
                        .catch(function () { setKurValue(kurInput, 0); });
                    return;
                }

                setKurValue(kurInput, 0);
            }

            currencySelect.addEventListener('change', updateForCurrency);

            if (initializeDefault && !kurInput.value) {
                updateForCurrency();
            }
        }

        (function applyHeaderCurrency() {
            var headerCurrency = document.getElementById('offer_currency');
            var headerRate = document.getElementById('offer_rate');
            if (!headerCurrency || !headerRate) return;

            function updateHeaderKur() {
                var val = headerCurrency.value || 'TL';
                if (val === 'TL') {
                    setKurValue(headerRate, 1);
                    return;
                }
                if (val === 'USD' || val === 'EUR') {
                    fetchTodayForexSelling(val)
                        .then(function (rate) { setKurValue(headerRate, rate); })
                        .catch(function () { setKurValue(headerRate, 0); });
                    return;
                }
                setKurValue(headerRate, 0);
            }

            headerCurrency.addEventListener('change', updateHeaderKur);
        })();

        function recalcTotals() {
            if (!linesBody) return;

            var rows = linesBody.querySelectorAll('tr');
            var toplam = 0;
            var iskontoToplam = 0;
            var kdvToplam = 0;

            rows.forEach(function (tr) {
                var price = parseFloat(tr.querySelector('.birim-fiyat')?.value || '0') || 0;
                var qty = parseFloat(tr.querySelector('.miktar')?.value || '0') || 0;
                var doviz = (tr.querySelector('.doviz')?.value || 'TL').toString();
                var kur = parseFloat(tr.querySelector('.kur')?.value || '0') || 0;
                var lineRate = doviz === 'TL' ? 1 : kur;
                if (lineRate <= 0) lineRate = 0;

                if (!price && !qty) return;

                var brut = (price * qty) * lineRate;

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

        window.__offerSetLineDetayVisible = function (tr, visible) {
            if (!tr) return;
            var btn = tr.querySelector('.stok-detay-btn');
            if (!btn) return;
            btn.style.display = visible ? '' : 'none';
        };

        function autoGrowTextarea(el) {
            if (!el || !el.style || String(el.tagName || '').toUpperCase() !== 'TEXTAREA') return;
            el.style.height = 'auto';
            el.style.height = String(el.scrollHeight || 0) + 'px';
        }

        function autoGrowTextareasInRow(tr) {
            if (!tr) return;
            autoGrowTextarea(tr.querySelector('.stok-kod'));
            autoGrowTextarea(tr.querySelector('.stok-aciklama'));
        }

        function addLineRow() {
            var tr = document.createElement('tr');
            tr.innerHTML =
                '<td class="stok-kod-cell">' +
                '<div style="display:flex; align-items:center; gap:6px;">' +
                '<textarea class="line-input stok-kod auto-grow" rows="1" style="flex:1 1 auto; width:auto;"></textarea>' +
                '<input type="hidden" class="line-input urun-id">' +
                '<input type="hidden" class="line-input teklif-detay-id">' +
                '<button type="button" class="small-btn stok-detay-btn" style="display:none;" title="Detay">' +
                '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">' +
                '<path d="M12 8h.01M11 12h1v4h1M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>' +
                '</svg>' +
                '</button>' +
                '</div>' +
                '</td>' +
                '<td class="stok-aciklama-cell"><textarea class="line-input stok-aciklama auto-grow" rows="1"></textarea></td>' +
                '<td class="birim-fiyat-cell"><input type="number" step="0.01" class="line-input birim-fiyat"></td>' +
                '<td class="miktar-cell"><input type="number" step="0.001" class="line-input miktar"></td>' +
                '<td class="doviz-cell"><select class="line-input doviz"><option value="TL" selected>TL</option><option value="USD">USD</option><option value="EUR">EUR</option></select></td>' +
                '<td class="kur-cell">' +
                '<div style="display:flex; align-items:center; justify-content:flex-end; gap:4px;">' +
                '<input type="number" step="0.0001" class="line-input kur" style="width: 96px;">' +
                '<button type="button" class="small-btn rate-search-btn" data-rate-target="line" title="Kur Seç">' +
                '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">' +
                '<path d="M21 21l-4.3-4.3m1.8-5.2a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>' +
                '</svg>' +
                '</button>' +
                '</div>' +
                '</td>' +
                '<td class="iskonto-cell"><input type="number" step="0.01" class="line-input isk1"></td>' +
                '<td class="iskonto-cell"><input type="number" step="0.01" class="line-input isk2"></td>' +
                '<td class="iskonto-cell"><input type="number" step="0.01" class="line-input isk3"></td>' +
                '<td class="iskonto-cell"><input type="number" step="0.01" class="line-input isk4"></td>' +
                '<td class="iskonto-cell"><input type="number" step="0.01" class="line-input isk5"></td>' +
                '<td class="iskonto-cell"><input type="number" step="0.01" class="line-input isk6"></td>' +
                '<td class="iskonto-cell"><input type="number" step="0.01" class="line-input isk-tutar" readonly></td>' +
                '<td class="kdv-cell"><input type="number" step="0.01" class="line-input kdv-oran"></td>' +
                '<td class="kdv-durum-cell"><select class="line-input kdv-durum"><option value="D">D</option><option value="H">H</option></select></td>' +
                '<td class="satir-tutar-doviz-cell"><input type="number" step="0.01" class="line-input satir-tutar-doviz" readonly></td>' +
                '<td class="satir-tutar-cell"><input type="number" step="0.01" class="line-input satir-tutar" readonly></td>' +
                '<input type="hidden" class="satir-aciklama-hidden">';

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
                var doviz = (tr.querySelector('.doviz')?.value || 'TL').toString();
                var kur = parseFloat(tr.querySelector('.kur')?.value || '0') || 0;
                var lineRate = doviz === 'TL' ? 1 : kur;
                if (lineRate <= 0) lineRate = 0;
                var baseAmountFx = (price * qty);

                var discounts = [];
                ['isk1', 'isk2', 'isk3', 'isk4', 'isk5', 'isk6'].forEach(function (cls) {
                    var el = tr.querySelector('.' + cls);
                    var val = parseFloat(el && el.value !== '' ? el.value : '0') || 0;
                    discounts.push(val);
                });

                var remainingFx = baseAmountFx;
                var totalDiscountFx = 0;

                discounts.forEach(function (rate) {
                    if (!rate) return;
                    var d = remainingFx * (rate / 100);
                    totalDiscountFx += d;
                    remainingFx -= d;
                });

                var iskField = tr.querySelector('.isk-tutar');
                if (iskField) {
                    var totalDiscountTl = totalDiscountFx * lineRate;
                    iskField.value = totalDiscountTl ? totalDiscountTl.toFixed(2) : '0';
                }
            }

            // Satır iskonto, KDV ve satır toplam hesaplama fonksiyonu
            function recalcDiscount() {
                var price = parseFloat(tr.querySelector('.birim-fiyat')?.value || '0') || 0;
                var qty = parseFloat(tr.querySelector('.miktar')?.value || '0') || 0;
                var doviz = (tr.querySelector('.doviz')?.value || 'TL').toString();
                var kur = parseFloat(tr.querySelector('.kur')?.value || '0') || 0;
                var lineRate = doviz === 'TL' ? 1 : kur;
                if (lineRate <= 0) lineRate = 0;
                var baseAmount = (price * qty) * lineRate;

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

                // FX net (kur carpilmadan) hesaplama
                var baseAmountFx = (price * qty);
                var remainingFx = baseAmountFx;
                var totalDiscountFx = 0;

                discounts.forEach(function (rate) {
                    if (!rate) return;
                    var d = remainingFx * (rate / 100);
                    totalDiscountFx += d;
                    remainingFx -= d;
                });

                var netFx = baseAmountFx - totalDiscountFx;
                if (netFx < 0) netFx = 0;

                // İskonto tutarı alanı
                var iskField = tr.querySelector('.isk-tutar');
                if (iskField) {
                    var totalDiscountTl = totalDiscountFx * lineRate;
                    iskField.value = totalDiscountTl ? totalDiscountTl.toFixed(2) : '0';
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
                var kdvFx = 0;
                var lineTotalFx = netFx;

                if (kdvOran > 0 && netFx > 0) {
                    if (kdvDurum === 'H') {
                        kdvFx = netFx * (kdvOran / 100);
                        lineTotalFx = netFx + kdvFx;
                    } else if (kdvDurum === 'E' || kdvDurum === 'D') {
                        var oranFx = kdvOran / 100;
                        lineTotalFx = netFx;
                        kdvFx = netFx - (netFx / (1 + oranFx));
                    } else {
                        lineTotalFx = netFx;
                        kdvFx = 0;
                    }
                }

                var satirDovizField = tr.querySelector('.satir-tutar-doviz');
                var lineTotalFxRounded = Math.round((lineTotalFx || 0) * 100) / 100;
                if (satirDovizField) {
                    satirDovizField.value = lineTotalFxRounded ? lineTotalFxRounded.toFixed(2) : '0';
                }

                var satirField = tr.querySelector('.satir-tutar');
                if (satirField) {
                    var lineTotalTl = lineTotalFxRounded * lineRate;
                    satirField.value = lineTotalTl ? lineTotalTl.toFixed(2) : '0';
                }

                recalcTotals();
            }

            var inputs = tr.querySelectorAll('.line-input, .satir-aciklama-hidden');
            inputs.forEach(function (input) {
                var base = null;
                if (input.classList.contains('stok-kod')) base = 'stok_kod';
                else if (input.classList.contains('urun-id')) base = 'urun_id';
                else if (input.classList.contains('teklif-detay-id')) base = 'id';
                else if (input.classList.contains('stok-aciklama')) base = 'stok_aciklama';
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

                if (base) {
                    input.name = 'lines[' + lineIndex + '][' + base + ']';
                }

                // Birim fiyat, miktar veya iskonto oranları değişince iskonto tutarı yeniden hesapla
                if (
                    input.classList.contains('birim-fiyat') ||
                    input.classList.contains('miktar') ||
                    input.classList.contains('kur') ||
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

                if (input.classList.contains('doviz')) {
                    input.addEventListener('change', recalcDiscount);
                }
            });

            // Başlangıçta da iskonto tutarını hesapla
            recalcDiscount();
            autoGrowTextareasInRow(tr);

            if (linesBody) {
                tr.dataset.multi = '0';
                tr.dataset.montaj = '0';
                linesBody.appendChild(tr);
                applyOfferLineColumnSettingsToRow(tr);
                lineIndex++;
            }
        }

        if (btnAddLine && linesBody) {
            btnAddLine.addEventListener('click', addLineRow);
        }

        if (linesBody && !linesBody.dataset.autoGrowBound) {
            linesBody.dataset.autoGrowBound = '1';
            linesBody.addEventListener('input', function (e) {
                var t = e.target;
                if (!t || !t.classList) return;
                if (t.classList.contains('stok-kod') || t.classList.contains('stok-aciklama')) {
                    autoGrowTextarea(t);
                }
            });
            linesBody.addEventListener('keydown', function (e) {
                var t = e.target;
                if (!t || !t.classList) return;
                if (e.key === 'Enter' && (t.classList.contains('stok-kod') || t.classList.contains('stok-aciklama'))) {
                    e.preventDefault();
                }
            });
        }

        // baï¿½langï¿½ï¿½ta bir satï¿½r olsun
        if (linesBody) {
            addLineRow();
            fetchOfferLineColumns()
                .then(function () { applyOfferLineColumnSettingsToTable(); })
                .catch(function () { });
        }

        (function setupOfferLineSettings() {
             var modal = document.getElementById('offerLineSettingsModal');
             var listEl = document.getElementById('offerLineSettingsList');
             var toggleAllEl = document.getElementById('offerLineSettingsToggleAll');
             var saveBtn = document.getElementById('offerLineSettingsSave');

             var draggingEl = null;

            function openSelf() {
                if (modal) modal.style.display = 'flex';
            }

            function closeSelf() {
                if (modal) modal.style.display = 'none';
            }

            function getItems() {
                if (!listEl) return [];
                return Array.prototype.slice.call(listEl.querySelectorAll('.offer-line-setting-item'));
            }

            function getItemCheckboxes() {
                return getItems()
                    .map(function (el) { return el.querySelector('input[type=\"checkbox\"]'); })
                    .filter(Boolean);
            }

            function renumberItems() {
                getItems().forEach(function (item, idx) {
                    var no = item.querySelector('.offer-line-setting-no');
                    if (no) no.textContent = String(idx + 1);
                });
            }

            function syncToggleAllState() {
                if (!toggleAllEl) return;
                var cbs = getItemCheckboxes();
                if (!cbs.length) {
                    toggleAllEl.checked = true;
                    toggleAllEl.indeterminate = false;
                    return;
                }
                var checkedCount = cbs.filter(function (cb) { return cb.checked; }).length;
                toggleAllEl.checked = checkedCount === cbs.length;
                toggleAllEl.indeterminate = checkedCount > 0 && checkedCount < cbs.length;
            }

            function buildList() {
                if (!listEl || !offerLineColumns) return;

                listEl.innerHTML = '';
                offerLineColumns.forEach(function (col, idx) {
                    var label = document.createElement('div');
                    label.className = 'offer-line-setting-item';
                    label.draggable = true;
                    label.setAttribute('data-key', col.key);

                    var no = document.createElement('div');
                    no.className = 'offer-line-setting-no';
                    no.textContent = String(idx + 1);

                    var handle = document.createElement('div');
                    handle.className = 'offer-line-setting-handle';
                    handle.textContent = '≡';
                    var cb = document.createElement('input');
                    cb.type = 'checkbox';
                    cb.checked = !!col.durum;
                    cb.addEventListener('change', syncToggleAllState);

                    var text = document.createElement('div');
                    text.className = 'offer-line-setting-label';
                    text.textContent = col.label || ('Sütun ' + String(col.index + 1));

                    label.appendChild(no);
                    label.appendChild(handle);
                    label.appendChild(cb);
                    label.appendChild(text);
                    listEl.appendChild(label);
                });

                renumberItems();
                syncToggleAllState();
            }

            function getDragAfterElement(container, y) {
                var els = Array.prototype.slice.call(container.querySelectorAll('.offer-line-setting-item:not(.dragging)'));
                var closest = null;
                var closestOffset = Number.NEGATIVE_INFINITY;

                els.forEach(function (child) {
                    var box = child.getBoundingClientRect();
                    var offset = y - box.top - box.height / 2;
                    if (offset < 0 && offset > closestOffset) {
                        closestOffset = offset;
                        closest = child;
                    }
                });

                return closest;
            }

            function attachDnD() {
                if (!listEl) return;

                listEl.addEventListener('dragstart', function (e) {
                    var item = e.target && e.target.closest ? e.target.closest('.offer-line-setting-item') : null;
                    if (!item) return;
                    draggingEl = item;
                    item.classList.add('dragging');
                    try {
                        e.dataTransfer.effectAllowed = 'move';
                        e.dataTransfer.setData('text/plain', item.getAttribute('data-key') || '');
                    } catch (err) {
                    }
                });

                listEl.addEventListener('dragend', function () {
                    if (draggingEl) draggingEl.classList.remove('dragging');
                    draggingEl = null;
                    renumberItems();
                });

                listEl.addEventListener('dragover', function (e) {
                    if (!draggingEl) return;
                    e.preventDefault();
                    var afterEl = getDragAfterElement(listEl, e.clientY);
                    if (!afterEl) {
                        listEl.appendChild(draggingEl);
                    } else if (afterEl !== draggingEl) {
                        listEl.insertBefore(draggingEl, afterEl);
                    }
                    renumberItems();
                });
            }

            attachDnD();

            if (toggleAllEl) {
                toggleAllEl.addEventListener('change', function () {
                    var checked = !!toggleAllEl.checked;
                    getItemCheckboxes().forEach(function (cb) {
                        cb.checked = checked;
                    });
                    syncToggleAllState();
                });
            }

            if (saveBtn) {
                saveBtn.addEventListener('click', function () {
                    var items = getItems();
                    if (!items.length) return;

                    var payload = items.map(function (item) {
                        var key = item.getAttribute('data-key') || '';
                        var cb = item.querySelector('input[type=\"checkbox\"]');
                        return { key: key, durum: !!(cb && cb.checked) };
                    });

                    saveBtn.disabled = true;
                    saveBtn.style.opacity = '0.7';

                    fetch('{{ route('offer-line-columns.store') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': getCsrfToken(),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ columns: payload })
                    })
                        .then(function (r) { return r.json(); })
                        .then(function (data) {
                            if (!data || !data.ok) throw new Error('save_failed');
                            offerLineColumns = normalizeOfferLineColumns(data.columns || []);
                            applyOfferLineColumnSettingsToTable();
                            closeSelf();
                        })
                        .catch(function () {
                            alert('Sütun ayarları kaydedilemedi.');
                        })
                        .finally(function () {
                            saveBtn.disabled = false;
                            saveBtn.style.opacity = '1';
                        });
                });
        }

        if (btnOfferLineSettings && modal) {
            btnOfferLineSettings.addEventListener('click', function () {
                fetchOfferLineColumns()
                    .then(function () {
                        applyOfferLineColumnSettingsToTable();
                        buildList();
                        openSelf();
                    })
                    .catch(function () {
                        alert('Sütun ayarları yüklenemedi.');
                    });
            });
        }

        if (modal) {
            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    closeSelf();
                }
            });
        }
    })();

        if (linesBody && Array.isArray(initialLines) && initialLines.length > 0) {
            linesBody.innerHTML = '';
            lineIndex = 0;

            initialLines.forEach(function (line) {
                addLineRow();
                var tr = linesBody.lastElementChild;
                if (!tr) return;

                var kodInput = tr.querySelector('.stok-kod');
                var aciklamaInput = tr.querySelector('.stok-aciklama');
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
                var teklifDetayIdInput = tr.querySelector('.teklif-detay-id');
                var dovizInput = tr.querySelector('.doviz');
                var kurInput = tr.querySelector('.kur');
                var satirAciklamaHidden = tr.querySelector('.satir-aciklama-hidden');

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
                autoGrowTextareasInRow(tr);
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
                if (teklifDetayIdInput && line.id != null) teklifDetayIdInput.value = line.id;

                var isMulti = !!(line.urun && (line.urun.multi === true || line.urun.multi === 1 || line.urun.multi === '1'));
                var isMontaj = !!(line.urun && (line.urun.montaj === true || line.urun.montaj === 1 || line.urun.montaj === '1'));
                tr.dataset.multi = isMulti ? '1' : '0';
                tr.dataset.montaj = isMontaj ? '1' : '0';
                tr.dataset.detaygrup = (line.urun && line.urun.urun_detay_grup_id != null) ? String(line.urun.urun_detay_grup_id) : '';
                tr.dataset.resim = (line.urun && line.urun.resim_yolu) ? String(line.urun.resim_yolu) : '';
                if (window.__offerSetLineDetayVisible) {
                    window.__offerSetLineDetayVisible(tr, isMulti || isMontaj);
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
        var btnCariSearch = document.getElementById('btnCariSearch');
        var btnYetkiliSearch = document.getElementById('btnYetkiliSearch');

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
        var revizeModal = document.getElementById('revizeModal');
        var teamDetailModal = document.getElementById('teamDetailModal');
        var teamDetailMeta = document.getElementById('teamDetailMeta');
        var teamDetailBody = document.getElementById('teamDetailBody');
        var teamDetailTotal = document.getElementById('teamDetailTotal');
        var btnTeamDetailAddRow = document.getElementById('btnTeamDetailAddRow');
        var btnTeamDetailSave = document.getElementById('btnTeamDetailSave');
        var montajDetailModal = document.getElementById('montajDetailModal');
        var montajDetailMeta = document.getElementById('montajDetailMeta');
        var montajDetailBody = document.getElementById('montajDetailBody');
        var btnMontajDetailSave = document.getElementById('btnMontajDetailSave');
        var teamDetailsBaseUrl = @json(url('teklifler/satirlar'));
        var currentTeamDetailLineRow = null;
        var currentTeamDetailProductRow = null;
        var currentTeamDefaults = null;
        var currentMontajDetailLineRow = null;
        var currentProductRow = null;
        var linesBody = document.getElementById('offerLinesBody');
        var btnRevizeList = document.getElementById('btnRevizeList');
        var btnRevizeCreate = document.getElementById('btnRevizeCreate');
        var revizeCreateForm = document.getElementById('revizeCreateForm');
        var revizeDeleteForm = document.getElementById('revizeDeleteForm');
        var revizeNoInput = document.getElementById('revize_no');
        var teklifTarihInput = document.getElementById('tarih');
        var gecerlilikTarihiInput = document.getElementById('gecerlilik_tarihi');
        var gecenSureInput = document.getElementById('gecen_sure');

        function parseISODate(value) {
            if (!value) return null;
            var d = new Date(value + 'T00:00:00');
            return isNaN(d.getTime()) ? null : d;
        }

        function updateGecenSure() {
            if (!gecenSureInput) return;
            var teklifTarih = parseISODate(teklifTarihInput && teklifTarihInput.value);

            if (!teklifTarih) {
                gecenSureInput.value = '';
                return;
            }

            var today = new Date();
            today.setHours(0, 0, 0, 0);

            var diffMs = today.getTime() - teklifTarih.getTime();
            var diffDays = Math.floor(diffMs / 86400000);
            if (!isFinite(diffDays) || diffDays < 0) diffDays = 0;
            gecenSureInput.value = diffDays + ' gün';
        }

        function formatISO(d) {
            if (!d) return '';
            var y = d.getFullYear();
            var m = String(d.getMonth() + 1).padStart(2, '0');
            var day = String(d.getDate()).padStart(2, '0');
            return y + '-' + m + '-' + day;
        }

        function addDays(d, days) {
            var copy = new Date(d.getTime());
            copy.setDate(copy.getDate() + (parseInt(days, 10) || 0));
            return copy;
        }

        function syncGecerlilikFromTeklifTarih(force) {
            if (!teklifTarihInput || !gecerlilikTarihiInput) return;
            if (!force && (gecerlilikTarihiInput.value || '').trim() !== '') return;

            var teklifTarih = parseISODate(teklifTarihInput.value);
            if (!teklifTarih) return;

            gecerlilikTarihiInput.value = formatISO(addDays(teklifTarih, 15));
            updateGecenSure();
        }

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

        syncGecerlilikFromTeklifTarih(false);
        updateGecenSure();
        if (teklifTarihInput) {
            teklifTarihInput.addEventListener('change', function () { syncGecerlilikFromTeklifTarih(true); });
            teklifTarihInput.addEventListener('change', updateGecenSure);
            teklifTarihInput.addEventListener('input', updateGecenSure);
        }
        if (gecerlilikTarihiInput) {
            gecerlilikTarihiInput.addEventListener('change', updateGecenSure);
            gecerlilikTarihiInput.addEventListener('input', updateGecenSure);
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

        function getCsrfToken() {
            var tokenEl = document.querySelector('meta[name="csrf-token"]');
            return tokenEl ? (tokenEl.getAttribute('content') || '') : '';
        }

        function getOfferLineTeamDefaults(lineRow) {
            var doviz = (lineRow && lineRow.querySelector('.doviz') && lineRow.querySelector('.doviz').value) ? lineRow.querySelector('.doviz').value : 'TL';
            doviz = doviz.toString().trim().toUpperCase();
            if (doviz !== 'TL' && doviz !== 'USD' && doviz !== 'EUR') doviz = 'TL';

            var kur = 1;
            if (doviz !== 'TL') {
                kur = parseFloat(lineRow && lineRow.querySelector('.kur') && lineRow.querySelector('.kur').value ? lineRow.querySelector('.kur').value : '0') || 0;
            }

            var isk1 = parseFloat(lineRow && lineRow.querySelector('.isk1') && lineRow.querySelector('.isk1').value ? lineRow.querySelector('.isk1').value : '0') || 0;
            var isk2 = parseFloat(lineRow && lineRow.querySelector('.isk2') && lineRow.querySelector('.isk2').value ? lineRow.querySelector('.isk2').value : '0') || 0;

            if (!isFinite(isk1) || isk1 < 0) isk1 = 0;
            if (!isFinite(isk2) || isk2 < 0) isk2 = 0;
            if (isk1 > 100) isk1 = 100;
            if (isk2 > 100) isk2 = 100;

            return { doviz: doviz, kur: kur, isk1: isk1, isk2: isk2 };
        }

        function applyTeamDefaultsToRow(row, defaults) {
            if (!row || !defaults) return;
            var dovizSelect = row.querySelector('.team-doviz');
            var kurInput = row.querySelector('.team-kur');
            var isk1Input = row.querySelector('.team-isk1');
            var isk2Input = row.querySelector('.team-isk2');

            if (isk1Input) {
                isk1Input.value = defaults.isk1;
                isk1Input.readOnly = true;
            }
            if (isk2Input) {
                isk2Input.value = defaults.isk2;
                isk2Input.readOnly = true;
            }

            if (dovizSelect) {
                dovizSelect.value = defaults.doviz;
                dovizSelect.disabled = true;
            }

            if (kurInput) {
                if (defaults.doviz === 'TL') {
                    kurInput.value = '1';
                    kurInput.disabled = true;
                } else {
                    kurInput.value = defaults.kur;
                    kurInput.disabled = true;
                }
            }
        }

        function formatTeamTotal(v) {
            var n = parseFloat(v || '0') || 0;
            var s = (Math.round(n * 100) / 100).toFixed(2);
            return s.replace('.', ',');
        }

        function recalcTeamDetailTotal() {
            if (!teamDetailTotal || !teamDetailBody) return;
            var sum = 0;
            teamDetailBody.querySelectorAll('.team-satir-tutar').forEach(function (el) {
                var v = parseFloat(el && el.value ? el.value : '0') || 0;
                sum += v;
            });
            teamDetailTotal.value = formatTeamTotal(sum);
        }

        function recalcTeamDetailRow(row) {
            if (!row) return;
            var qtyInput = row.querySelector('.team-miktar');
            var priceInput = row.querySelector('.team-birim-fiyat');
            var isk1Input = row.querySelector('.team-isk1');
            var isk2Input = row.querySelector('.team-isk2');
            var dovizSelect = row.querySelector('.team-doviz');
            var kurInput = row.querySelector('.team-kur');
            var tutarInput = row.querySelector('.team-satir-tutar');

            var qty = parseFloat(qtyInput && qtyInput.value ? qtyInput.value : '0') || 0;
            var price = parseFloat(priceInput && priceInput.value ? priceInput.value : '0') || 0;
            var isk1 = parseFloat(isk1Input && isk1Input.value ? isk1Input.value : '0') || 0;
            var isk2 = parseFloat(isk2Input && isk2Input.value ? isk2Input.value : '0') || 0;
            var doviz = (dovizSelect && dovizSelect.value ? dovizSelect.value : 'TL').toString().trim().toUpperCase();
            var kur = parseFloat(kurInput && kurInput.value ? kurInput.value : '0') || 0;

            if (qty < 0) qty = 0;

            if (doviz === 'TL') {
                if (kurInput) {
                    kurInput.value = '1';
                    kurInput.disabled = true;
                }
                kur = 1;
            } else {
                if (kurInput) {
                    kurInput.disabled = false;
                }
            }

            var rate = doviz === 'TL' ? 1 : kur;
            if (rate <= 0) rate = 0;

            var tutar = (qty * price) * rate;
            if (isFinite(isk1) && isk1 > 0) {
                if (isk1 > 100) isk1 = 100;
                tutar = tutar * (1 - (isk1 / 100));
            }
            if (isFinite(isk2) && isk2 > 0) {
                if (isk2 > 100) isk2 = 100;
                tutar = tutar * (1 - (isk2 / 100));
            }
            if (tutarInput) {
                tutarInput.value = (Math.round(tutar * 100) / 100).toFixed(2);
            }
            recalcTeamDetailTotal();
        }

        function createTeamDetailRow(item) {
            var tr = document.createElement('tr');
            tr.innerHTML =
                '<td class="stok-kod-cell"><input type="hidden" class="team-urun-id"><input class="line-input team-kod"></td>' +
                '<td class="stok-aciklama-cell"><input class="line-input team-aciklama"></td>' +
                '<td class="miktar-cell"><input type="number" step="0.001" min="0" class="line-input team-miktar" style="text-align:right;"></td>' +
                '<td class="birim-fiyat-cell"><input type="number" step="0.01" class="line-input team-birim-fiyat"></td>' +
                '<td class="iskonto-cell"><input type="number" step="0.01" class="line-input team-isk1"></td>' +
                '<td class="iskonto-cell"><input type="number" step="0.01" class="line-input team-isk2"></td>' +
                '<td class="doviz-cell"><select class="line-input team-doviz"><option value="TL">TL</option><option value="USD">USD</option><option value="EUR">EUR</option></select></td>' +
                '<td class="kur-cell"><input type="number" step="0.0001" class="line-input team-kur" style="width: 96px;"></td>' +
                '<td class="satir-tutar-cell"><input type="number" step="0.01" class="line-input team-satir-tutar" readonly></td>' +
                '<td style="text-align:right;"><button type="button" class="small-btn team-remove-row" title="Sil">X</button></td>';

            var urunIdInput = tr.querySelector('.team-urun-id');
            var kodInput = tr.querySelector('.team-kod');
            var aciklamaInput = tr.querySelector('.team-aciklama');
            var qtyInput = tr.querySelector('.team-miktar');
            var priceInput = tr.querySelector('.team-birim-fiyat');
            var isk1Input = tr.querySelector('.team-isk1');
            var isk2Input = tr.querySelector('.team-isk2');
            var dovizSelect = tr.querySelector('.team-doviz');
            var kurInput = tr.querySelector('.team-kur');

            if (urunIdInput) urunIdInput.value = (item && item.urun_id != null ? item.urun_id : '') || '';
            if (kodInput) kodInput.value = (item && item.stokkod != null ? item.stokkod : '') || '';
            if (aciklamaInput) aciklamaInput.value = (item && item.stok_aciklama != null ? item.stok_aciklama : '') || '';
            if (qtyInput) qtyInput.value = (item && item.miktar != null ? item.miktar : '1') || '1';
            if (priceInput) priceInput.value = (item && item.birim_fiyat != null ? item.birim_fiyat : '') || '';
            if (isk1Input) isk1Input.value = (item && item.iskonto1 != null ? item.iskonto1 : '0') || '0';
            if (isk2Input) isk2Input.value = (item && item.iskonto2 != null ? item.iskonto2 : '0') || '0';
            if (dovizSelect) dovizSelect.value = ((item && item.doviz ? item.doviz : 'TL') || 'TL').toString().trim().toUpperCase();
            if (kurInput) kurInput.value = (item && item.kur != null ? item.kur : '') || '';

            var defaults = currentTeamDefaults || (currentTeamDetailLineRow ? getOfferLineTeamDefaults(currentTeamDetailLineRow) : null);
            if (defaults) {
                applyTeamDefaultsToRow(tr, defaults);
            }

            recalcTeamDetailRow(tr);
            return tr;
        }

        function loadTeamDetailItems(teklifDetayId, lineRow) {
            if (!teamDetailBody) return;
            teamDetailBody.innerHTML = '';

            var kod = (lineRow && lineRow.querySelector('.stok-kod') && lineRow.querySelector('.stok-kod').value) ? lineRow.querySelector('.stok-kod').value : '';
            var aciklama = (lineRow && lineRow.querySelector('.stok-aciklama') && lineRow.querySelector('.stok-aciklama').value) ? lineRow.querySelector('.stok-aciklama').value : '';
            if (teamDetailMeta) {
                teamDetailMeta.textContent = (kod || '') + (aciklama ? (' - ' + aciklama) : '');
            }

            currentTeamDefaults = getOfferLineTeamDefaults(lineRow);

            fetch(teamDetailsBaseUrl + '/' + encodeURIComponent(teklifDetayId) + '/takim-detaylari', {
                credentials: 'same-origin',
                headers: { 'Accept': 'application/json' }
            })
                .then(function (r) { return r.ok ? r.json() : Promise.reject(r); })
                .then(function (data) {
                    var items = (data && Array.isArray(data.items)) ? data.items : [];
                    if (!items.length) {
                        var recipe = (data && Array.isArray(data.recipe_items)) ? data.recipe_items : [];
                        if (recipe.length) {
                            recipe.forEach(function (it) {
                                teamDetailBody.appendChild(createTeamDetailRow(it));
                            });
                        } else {
                            teamDetailBody.appendChild(createTeamDetailRow({ doviz: 'TL', kur: 1 }));
                        }
                        recalcTeamDetailTotal();
                        return;
                    }
                    items.forEach(function (it) {
                        teamDetailBody.appendChild(createTeamDetailRow(it));
                    });
                    recalcTeamDetailTotal();
                })
                .catch(function () {
                    teamDetailBody.appendChild(createTeamDetailRow({ doviz: 'TL', kur: 1 }));
                    recalcTeamDetailTotal();
                });
        }

        if (teamDetailModal) {
            teamDetailModal.addEventListener('click', function (e) {
                if (e.target === teamDetailModal) {
                    closeModal(teamDetailModal);
                }
            });
        }

        if (teamDetailModal && !teamDetailModal.dataset.layoutBound) {
            teamDetailModal.dataset.layoutBound = '1';
            var actions = teamDetailModal.querySelector('.modal-actions');
            if (actions) {
                actions.style.justifyContent = 'space-between';
                actions.style.alignItems = 'center';

                var extraAdd = actions.querySelector('#btnTeamDetailAddRow');
                if (extraAdd) {
                    extraAdd.style.display = 'none';
                }

                if (!teamDetailTotal) {
                    var left = document.createElement('div');
                    left.style.display = 'flex';
                    left.style.alignItems = 'center';
                    left.style.gap = '8px';

                    var label = document.createElement('span');
                    label.textContent = 'Toplam Tutar:';
                    label.style.color = '#6b7280';
                    label.style.fontSize = '0.9rem';

                    var input = document.createElement('input');
                    input.id = 'teamDetailTotal';
                    input.type = 'text';
                    input.className = 'line-input';
                    input.style.width = '140px';
                    input.style.textAlign = 'right';
                    input.readOnly = true;
                    input.value = '0,00';

                    left.appendChild(label);
                    left.appendChild(input);

                    actions.insertBefore(left, actions.firstChild);
                    teamDetailTotal = input;
                }
            }
        }

        if (teamDetailBody && !teamDetailBody.dataset.bound) {
            teamDetailBody.dataset.bound = '1';
            teamDetailBody.addEventListener('dblclick', function (e) {
                var target = e.target;
                if (!target || !target.classList || !target.classList.contains('team-kod')) return;
                if (!productModal) return;

                currentTeamDetailProductRow = target.closest('tr');
                if (!currentTeamDetailProductRow) return;

                currentProductRow = null;
                openModal(productModal);
            });
            teamDetailBody.addEventListener('input', function (e) {
                var row = e.target && e.target.closest ? e.target.closest('tr') : null;
                if (!row) return;
                recalcTeamDetailRow(row);
            });
            teamDetailBody.addEventListener('change', function (e) {
                var row = e.target && e.target.closest ? e.target.closest('tr') : null;
                if (!row) return;
                recalcTeamDetailRow(row);
            });
            teamDetailBody.addEventListener('click', function (e) {
                var btn = e.target && e.target.closest ? e.target.closest('.team-remove-row') : null;
                if (!btn) return;
                var row = btn.closest('tr');
                if (row && row.parentElement) row.parentElement.removeChild(row);
                recalcTeamDetailTotal();
            });
        }

        if (btnTeamDetailAddRow && teamDetailBody) {
            btnTeamDetailAddRow.addEventListener('click', function () {
                teamDetailBody.appendChild(createTeamDetailRow({ doviz: 'TL', kur: 1 }));
                recalcTeamDetailTotal();
            });
        }

        if (btnTeamDetailSave && teamDetailBody) {
            btnTeamDetailSave.addEventListener('click', function () {
                if (!currentTeamDetailLineRow) return;

                var teklifDetayId = '';
                var teklifDetayIdInput = currentTeamDetailLineRow.querySelector('.teklif-detay-id');
                if (teklifDetayIdInput && teklifDetayIdInput.value) teklifDetayId = teklifDetayIdInput.value;
                if (!teklifDetayId) {
                    alert('Önce teklifi kaydedin.');
                    return;
                }

                var csrf = getCsrfToken();
                var defaults = currentTeamDefaults || getOfferLineTeamDefaults(currentTeamDetailLineRow);

                var items = [];
                teamDetailBody.querySelectorAll('tr').forEach(function (row) {
                    var stokkod = (row.querySelector('.team-kod') && row.querySelector('.team-kod').value) ? row.querySelector('.team-kod').value.toString().trim() : '';
                    var stokAciklama = (row.querySelector('.team-aciklama') && row.querySelector('.team-aciklama').value) ? row.querySelector('.team-aciklama').value.toString().trim() : '';
                    var urunIdRaw = (row.querySelector('.team-urun-id') && row.querySelector('.team-urun-id').value) ? row.querySelector('.team-urun-id').value.toString().trim() : '';
                    var urunId = urunIdRaw ? (parseInt(urunIdRaw, 10) || null) : null;
                    var miktar = parseFloat(row.querySelector('.team-miktar') && row.querySelector('.team-miktar').value ? row.querySelector('.team-miktar').value : '0') || 0;
                    var birimFiyat = parseFloat(row.querySelector('.team-birim-fiyat') && row.querySelector('.team-birim-fiyat').value ? row.querySelector('.team-birim-fiyat').value : '0') || 0;
                    var isk1 = defaults.isk1;
                    var isk2 = defaults.isk2;
                    var doviz = defaults.doviz;
                    var kur = defaults.doviz === 'TL' ? 1 : defaults.kur;

                    if (!stokkod && !stokAciklama && miktar === 0 && birimFiyat === 0) return;

                    items.push({
                        urun_id: urunId,
                        stokkod: stokkod || null,
                        stok_aciklama: stokAciklama || null,
                        miktar: miktar,
                        birim_fiyat: birimFiyat,
                        iskonto1: isk1,
                        iskonto2: isk2,
                        doviz: doviz || 'TL',
                        kur: doviz === 'TL' ? 1 : kur,
                    });
                });

                fetch(teamDetailsBaseUrl + '/' + encodeURIComponent(teklifDetayId) + '/takim-detaylari', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: JSON.stringify({ items: items })
                })
                    .then(function (r) { return r.ok ? r.json() : r.json().then(function (j) { throw j; }); })
                    .then(function () {
                        closeModal(teamDetailModal);
                    })
                    .catch(function () {
                        alert('Takım detay kaydedilemedi.');
                    });
            });
        }

        function montajNumber(v) {
            var n = parseFloat(v || '0');
            return isFinite(n) ? n : 0;
        }

        function montajFmt2(n) {
            var x = montajNumber(n);
            return (Math.round(x * 100) / 100).toFixed(2);
        }

        function recalcMontajDetailRow(row) {
            if (!row) return;
            var qtyInput = row.querySelector('.montaj-miktar');
            var priceInput = row.querySelector('.montaj-birim-fiyat');
            var totalInput = row.querySelector('.montaj-satir-tutar');
            if (!qtyInput || !priceInput || !totalInput) return;

            var qty = montajNumber(qtyInput.value);
            var price = montajNumber(priceInput.value);
            totalInput.value = montajFmt2(qty * price);
        }

        function createMontajGroupRow(kod) {
            var tr = document.createElement('tr');
            tr.className = 'montaj-group-row';
            var td = document.createElement('td');
            td.colSpan = 6;
            td.textContent = (kod || '').toString();
            tr.appendChild(td);
            return tr;
        }

        function createMontajItemRow(groupId, item) {
            var tr = document.createElement('tr');
            tr.setAttribute('data-montaj-item', '1');
            tr.setAttribute('data-group-id', String(groupId || ''));

            var urunIds = (item && Array.isArray(item.urun_ids)) ? item.urun_ids : [];
            if (!Array.isArray(urunIds)) urunIds = [];
            urunIds = urunIds
                .map(function (v) { return v == null ? '' : String(v).trim(); })
                .filter(function (v) { return !!v; });
            tr.setAttribute('data-urun-ids', urunIds.join(','));

            var urunKod = (item && item.urun_kod ? item.urun_kod : '') || '';
            var birim = (item && item.birim ? item.birim : 'Adet') || 'Adet';
            var miktar = montajNumber(item && item.miktar !== undefined ? item.miktar : 0);
            var birimFiyat = montajNumber(item && item.birim_fiyat !== undefined ? item.birim_fiyat : 0);
            var doviz = ((item && item.doviz ? item.doviz : 'TL') || 'TL').toString().trim().toUpperCase();
            if (doviz !== 'TL' && doviz !== 'USD' && doviz !== 'EUR') doviz = 'TL';

            tr.innerHTML =
                '<td class="stok-kod-cell"><input class="line-input montaj-urun-kod" value="' + (urunKod || '').replace(/\"/g, '&quot;') + '" readonly></td>' +
                '<td class="birim-cell">' +
                '<select class="line-input montaj-birim" disabled>' +
                '<option value="Adet">Adet</option>' +
                '<option value="Metre">Metre</option>' +
                '<option value="Kilo">Kilo</option>' +
                '</select>' +
                '</td>' +
                '<td class="miktar-cell"><input type="number" step="0.001" min="0" class="line-input montaj-miktar" value="' + String(miktar) + '" style="text-align:right;"></td>' +
                '<td class="birim-fiyat-cell"><input type="number" step="0.01" min="0" class="line-input montaj-birim-fiyat" value="' + montajFmt2(birimFiyat) + '" style="text-align:right;"></td>' +
                '<td class="doviz-cell">' +
                '<select class="line-input montaj-doviz">' +
                '<option value="TL">TL</option>' +
                '<option value="USD">USD</option>' +
                '<option value="EUR">EUR</option>' +
                '</select>' +
                '</td>' +
                '<td class="satir-tutar-cell"><input type="number" step="0.01" class="line-input montaj-satir-tutar" value="0" readonly style="text-align:right;"></td>';

            var birimSel = tr.querySelector('.montaj-birim');
            if (birimSel) {
                birimSel.value = (birim === 'Metre' || birim === 'Kilo') ? birim : 'Adet';
            }
            var dovizSel = tr.querySelector('.montaj-doviz');
            if (dovizSel) dovizSel.value = doviz;

            recalcMontajDetailRow(tr);
            return tr;
        }

        function renderMontajDetailGroups(groups) {
            if (!montajDetailBody) return;
            montajDetailBody.innerHTML = '';

            (Array.isArray(groups) ? groups : []).forEach(function (g) {
                montajDetailBody.appendChild(createMontajGroupRow(g && g.kod ? g.kod : ''));
                var items = (g && Array.isArray(g.items)) ? g.items : [];
                items.forEach(function (it) {
                    montajDetailBody.appendChild(createMontajItemRow(g.id, it));
                });
            });
        }

        function collectOfferLineQtyAndPresenceByUrunId() {
            var qtyByUrunId = {};
            var present = {};
            if (!linesBody) return { qtyByUrunId: qtyByUrunId, presentUrunIds: present };

            var currentLineId = '';
            if (currentMontajDetailLineRow) {
                var idInp = currentMontajDetailLineRow.querySelector('.teklif-detay-id');
                if (idInp && idInp.value) currentLineId = String(idInp.value);
            }

            linesBody.querySelectorAll('tr').forEach(function (tr) {
                if (!tr || tr === currentMontajDetailLineRow) return;

                var idInp = tr.querySelector('.teklif-detay-id');
                var thisLineId = idInp && idInp.value ? String(idInp.value) : '';
                if (currentLineId && thisLineId && thisLineId === currentLineId) return;

                var urunIdInp = tr.querySelector('.urun-id');
                var urunId = (urunIdInp && urunIdInp.value) ? String(urunIdInp.value).trim() : '';
                if (!urunId) return;

                present[urunId] = true;

                var qty = montajNumber(tr.querySelector('.miktar') && tr.querySelector('.miktar').value ? tr.querySelector('.miktar').value : 0);
                if (!isFinite(qty) || qty < 0) qty = 0;

                qtyByUrunId[urunId] = (qtyByUrunId[urunId] || 0) + qty;
            });

            return { qtyByUrunId: qtyByUrunId, presentUrunIds: present };
        }

        function applyOfferLineQuantitiesToMontajRows() {
            if (!montajDetailBody) return;
            var collected = collectOfferLineQtyAndPresenceByUrunId();
            var qtyByUrunId = collected.qtyByUrunId || {};
            var presentUrunIds = collected.presentUrunIds || {};

            montajDetailBody.querySelectorAll('tr[data-montaj-item=\"1\"]').forEach(function (row) {
                if ((row.dataset.manualQty || '') === '1') return;

                var idsRaw = (row.getAttribute('data-urun-ids') || '').toString().trim();
                if (!idsRaw) return;

                var sum = 0;
                var hasAny = false;
                idsRaw.split(',').forEach(function (id) {
                    id = (id || '').toString().trim();
                    if (!id) return;
                    if (presentUrunIds[id]) hasAny = true;
                    sum += (qtyByUrunId[id] || 0);
                });

                if (!hasAny) return;
                sum = Math.round(sum * 1000) / 1000;

                var qtyInput = row.querySelector('.montaj-miktar');
                if (qtyInput) {
                    var newVal = String(sum);
                    if (String(qtyInput.value || '') !== newVal) {
                        qtyInput.value = newVal;
                        recalcMontajDetailRow(row);
                    }
                }
            });
        }

        function isMontajDetailModalOpen() {
            return !!(montajDetailModal && montajDetailModal.style && montajDetailModal.style.display === 'flex');
        }

        var montajDetailSyncTimer = null;
        function scheduleMontajDetailAutoFill() {
            if (!isMontajDetailModalOpen()) return;
            if (montajDetailSyncTimer) clearTimeout(montajDetailSyncTimer);
            montajDetailSyncTimer = setTimeout(function () {
                if (!isMontajDetailModalOpen()) return;
                applyOfferLineQuantitiesToMontajRows();
            }, 150);
        }

        function loadMontajDetailItems(teklifDetayId, lineRow) {
            if (!montajDetailBody) return;

            var stokKod = (lineRow && lineRow.querySelector('.stok-kod') && lineRow.querySelector('.stok-kod').value) ? lineRow.querySelector('.stok-kod').value : '';
            var stokAciklama = (lineRow && lineRow.querySelector('.stok-aciklama') && lineRow.querySelector('.stok-aciklama').value) ? lineRow.querySelector('.stok-aciklama').value : '';
            if (montajDetailMeta) {
                montajDetailMeta.textContent = (stokKod || '') + (stokAciklama ? (' - ' + stokAciklama) : '');
            }

            montajDetailBody.innerHTML = '';

            fetch(teamDetailsBaseUrl + '/' + encodeURIComponent(teklifDetayId) + '/montaj-detaylari', {
                credentials: 'same-origin',
                headers: { 'Accept': 'application/json' }
            })
                .then(function (r) { return r.ok ? r.json() : r.json().then(function (j) { throw j; }); })
                .then(function (data) {
                    var groups = (data && Array.isArray(data.groups)) ? data.groups : [];
                    renderMontajDetailGroups(groups);
                    applyOfferLineQuantitiesToMontajRows();
                })
                .catch(function () {
                    alert('Montaj detay verileri alinamadi.');
                    closeModal(montajDetailModal);
                });
        }

        if (montajDetailBody && !montajDetailBody.dataset.bound) {
            montajDetailBody.dataset.bound = '1';
            montajDetailBody.addEventListener('input', function (e) {
                var row = e.target && e.target.closest ? e.target.closest('tr[data-montaj-item=\"1\"]') : null;
                if (!row) return;
                if (e.target && e.target.classList && e.target.classList.contains('montaj-miktar')) {
                    row.dataset.manualQty = '1';
                }
                recalcMontajDetailRow(row);
            });
            montajDetailBody.addEventListener('change', function (e) {
                var row = e.target && e.target.closest ? e.target.closest('tr[data-montaj-item=\"1\"]') : null;
                if (!row) return;
                if (e.target && e.target.classList && e.target.classList.contains('montaj-miktar')) {
                    row.dataset.manualQty = '1';
                }
                recalcMontajDetailRow(row);
            });
        }

        if (linesBody && !linesBody.dataset.montajDetailSyncBound) {
            linesBody.dataset.montajDetailSyncBound = '1';
            linesBody.addEventListener('input', function (e) {
                var t = e.target;
                if (!t || !t.classList) return;
                if (!t.classList.contains('miktar')) return;
                scheduleMontajDetailAutoFill();
            });
            linesBody.addEventListener('change', function () {
                scheduleMontajDetailAutoFill();
            });
        }

        if (btnMontajDetailSave && montajDetailBody && montajDetailModal && !btnMontajDetailSave.dataset.bound) {
            btnMontajDetailSave.dataset.bound = '1';
            btnMontajDetailSave.addEventListener('click', function () {
                if (!currentMontajDetailLineRow) return;

                var teklifDetayIdInput = currentMontajDetailLineRow.querySelector('.teklif-detay-id');
                var teklifDetayId = teklifDetayIdInput && teklifDetayIdInput.value ? teklifDetayIdInput.value : '';
                if (!teklifDetayId) {
                    alert('Once teklifi kaydedin.');
                    return;
                }

                var items = [];
                montajDetailBody.querySelectorAll('tr[data-montaj-item=\"1\"]').forEach(function (row) {
                    var groupId = parseInt(row.getAttribute('data-group-id') || '0', 10) || null;
                    var urunKod = (row.querySelector('.montaj-urun-kod') && row.querySelector('.montaj-urun-kod').value) ? row.querySelector('.montaj-urun-kod').value.toString().trim() : '';
                    var birim = (row.querySelector('.montaj-birim') && row.querySelector('.montaj-birim').value) ? row.querySelector('.montaj-birim').value.toString() : 'Adet';
                    var doviz = (row.querySelector('.montaj-doviz') && row.querySelector('.montaj-doviz').value) ? row.querySelector('.montaj-doviz').value.toString().trim().toUpperCase() : 'TL';
                    var miktar = montajNumber(row.querySelector('.montaj-miktar') && row.querySelector('.montaj-miktar').value ? row.querySelector('.montaj-miktar').value : 0);
                    var birimFiyat = montajNumber(row.querySelector('.montaj-birim-fiyat') && row.querySelector('.montaj-birim-fiyat').value ? row.querySelector('.montaj-birim-fiyat').value : 0);

                    items.push({
                        montaj_grup_id: groupId,
                        urun_kod: urunKod || null,
                        birim: birim || 'Adet',
                        miktar: miktar,
                        birim_fiyat: birimFiyat,
                        doviz: doviz || 'TL',
                    });
                });

                var csrf = getCsrfToken();

                fetch(teamDetailsBaseUrl + '/' + encodeURIComponent(teklifDetayId) + '/montaj-detaylari', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: JSON.stringify({ items: items })
                })
                    .then(function (r) { return r.ok ? r.json() : r.json().then(function (j) { throw j; }); })
                    .then(function () {
                        closeModal(montajDetailModal);
                    })
                    .catch(function () {
                        alert('Montaj detay kaydedilemedi.');
                    });
            });
        }

        if (linesBody && (teamDetailModal || montajDetailModal) && !linesBody.dataset.teamDetailBound) {
            linesBody.dataset.teamDetailBound = '1';
            linesBody.addEventListener('click', function (e) {
                var btn = e.target && e.target.closest ? e.target.closest('.stok-detay-btn') : null;
                if (!btn) return;

                var lineRow = btn.closest('tr');
                if (!lineRow) return;

                var isMulti = (lineRow.dataset.multi || '') === '1';
                var isMontaj = (lineRow.dataset.montaj || '') === '1';
                if (!isMulti && !isMontaj) return;

                var teklifDetayIdInput = lineRow.querySelector('.teklif-detay-id');
                var teklifDetayId = teklifDetayIdInput && teklifDetayIdInput.value ? teklifDetayIdInput.value : '';
                if (!teklifDetayId) {
                    alert('Önce teklifi kaydedin.');
                    return;
                }

                if (isMulti) {
                    if (!teamDetailModal) return;
                    currentTeamDetailLineRow = lineRow;
                    currentTeamDefaults = getOfferLineTeamDefaults(lineRow);
                    openModal(teamDetailModal);
                    loadTeamDetailItems(teklifDetayId, lineRow);
                } else if (isMontaj) {
                    if (!montajDetailModal) return;
                    currentMontajDetailLineRow = lineRow;
                    openModal(montajDetailModal);
                    loadMontajDetailItems(teklifDetayId, lineRow);
                }
            });
        }

        function syncTeamDetailModalFromOfferLine(lineRow) {
            if (!teamDetailModal || !teamDetailBody) return;
            if (!currentTeamDetailLineRow) return;
            var currentIdInput = currentTeamDetailLineRow.querySelector('.teklif-detay-id');
            var changedIdInput = lineRow && lineRow.querySelector ? lineRow.querySelector('.teklif-detay-id') : null;
            var currentId = currentIdInput && currentIdInput.value ? currentIdInput.value : '';
            var changedId = changedIdInput && changedIdInput.value ? changedIdInput.value : '';
            if (!currentId || !changedId || currentId !== changedId) return;

            if (!teamDetailModal.style || teamDetailModal.style.display !== 'flex') return;

            currentTeamDefaults = getOfferLineTeamDefaults(lineRow);
            teamDetailBody.querySelectorAll('tr').forEach(function (row) {
                applyTeamDefaultsToRow(row, currentTeamDefaults);
                recalcTeamDetailRow(row);
            });
            recalcTeamDetailTotal();
        }

        var teamDetailSyncTimers = {};

        function persistTeamDefaultsToDb(lineRow) {
            var teklifDetayIdInput = lineRow.querySelector('.teklif-detay-id');
            var teklifDetayId = teklifDetayIdInput && teklifDetayIdInput.value ? teklifDetayIdInput.value : '';
            if (!teklifDetayId) return;

            var defaults = getOfferLineTeamDefaults(lineRow);
            var csrf = getCsrfToken();

            fetch(teamDetailsBaseUrl + '/' + encodeURIComponent(teklifDetayId) + '/takim-detaylari', {
                credentials: 'same-origin',
                headers: { 'Accept': 'application/json' }
            })
                .then(function (r) { return r.ok ? r.json() : Promise.reject(r); })
                .then(function (data) {
                    var items = (data && Array.isArray(data.items)) ? data.items : [];
                    var payloadItems = items.map(function (it) {
                        return {
                            urun_id: it.urun_id != null ? it.urun_id : null,
                            stokkod: it.stokkod || null,
                            stok_aciklama: it.stok_aciklama || null,
                            miktar: it.miktar != null ? it.miktar : 0,
                            birim_fiyat: it.birim_fiyat != null ? it.birim_fiyat : 0,
                            iskonto1: defaults.isk1,
                            iskonto2: defaults.isk2,
                            doviz: defaults.doviz,
                            kur: defaults.doviz === 'TL' ? 1 : defaults.kur,
                        };
                    });

                    return fetch(teamDetailsBaseUrl + '/' + encodeURIComponent(teklifDetayId) + '/takim-detaylari', {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf
                        },
                        body: JSON.stringify({ items: payloadItems })
                    });
                })
                .then(function () { })
                .catch(function () { });
        }

        if (linesBody && !linesBody.dataset.teamDetailSyncBound) {
            linesBody.dataset.teamDetailSyncBound = '1';
            linesBody.addEventListener('change', function (e) {
                var t = e.target;
                if (!t || !t.classList) return;
                if (!t.classList.contains('doviz') &&
                    !t.classList.contains('kur') &&
                    !t.classList.contains('isk1') &&
                    !t.classList.contains('isk2')) {
                    return;
                }

                var lineRow = t.closest('tr');
                if (!lineRow) return;
                if ((lineRow.dataset.multi || '') !== '1') return;

                syncTeamDetailModalFromOfferLine(lineRow);

                var teklifDetayIdInput = lineRow.querySelector('.teklif-detay-id');
                var teklifDetayId = teklifDetayIdInput && teklifDetayIdInput.value ? teklifDetayIdInput.value : '';
                if (!teklifDetayId) return;

                if (teamDetailSyncTimers[teklifDetayId]) {
                    clearTimeout(teamDetailSyncTimers[teklifDetayId]);
                }
                teamDetailSyncTimers[teklifDetayId] = setTimeout(function () {
                    persistTeamDefaultsToDb(lineRow);
                }, 500);
            });
        }

        if (productModal && !productModal.dataset.teamDetailPickBound) {
            productModal.dataset.teamDetailPickBound = '1';
            productModal.addEventListener('click', function (e) {
                var picked = e.target && e.target.closest ? e.target.closest('.product-row') : null;
                if (!picked) return;
                if (!currentTeamDetailProductRow) return;

                var kod = picked.dataset.kod || '';
                var aciklama = picked.dataset.aciklama || '';
                var fiyat = picked.dataset.fiyat || '';
                var urunId = picked.dataset.id || '';
                var defaults = currentTeamDefaults || (currentTeamDetailLineRow ? getOfferLineTeamDefaults(currentTeamDetailLineRow) : { doviz: 'TL', kur: 1, isk1: 0, isk2: 0 });

                var urunIdInput = currentTeamDetailProductRow.querySelector('.team-urun-id');
                var kodInput = currentTeamDetailProductRow.querySelector('.team-kod');
                var aciklamaInput = currentTeamDetailProductRow.querySelector('.team-aciklama');
                var fiyatInput = currentTeamDetailProductRow.querySelector('.team-birim-fiyat');
                var qtyInput = currentTeamDetailProductRow.querySelector('.team-miktar');
                var dovizSelect = currentTeamDetailProductRow.querySelector('.team-doviz');
                var kurInput = currentTeamDetailProductRow.querySelector('.team-kur');

                if (urunIdInput) urunIdInput.value = urunId;
                if (kodInput) kodInput.value = kod;
                if (aciklamaInput) aciklamaInput.value = aciklama;
                if (fiyatInput) fiyatInput.value = fiyat;
                if (qtyInput && !qtyInput.value) qtyInput.value = '1';

                applyTeamDefaultsToRow(currentTeamDetailProductRow, defaults);

                recalcTeamDetailRow(currentTeamDetailProductRow);
                currentTeamDetailProductRow = null;
                closeModal(productModal);
            });
        }

        if (btnRevizeList) {
            btnRevizeList.addEventListener('click', function () {
                openModal(revizeModal);
            });
        }

        document.querySelectorAll('.revize-row').forEach(function (row) {
            row.addEventListener('click', function () {
                var href = this.getAttribute('data-href');
                if (href) {
                    window.location.href = href;
                }
            });
        });

        if (btnRevizeCreate) {
            btnRevizeCreate.addEventListener('click', function () {
                if (!revizeCreateForm) return;
                var ok = window.confirm('Revize oluşturulsun mu?');
                if (ok) {
                    revizeCreateForm.submit();
                }
            });
        }

        (function setupRevizeDelete() {
            if (!btnRevizeCreate || !revizeDeleteForm || !revizeNoInput) return;

            var revNo = parseInt(revizeNoInput.value || '1', 10);
            if (!(revNo > 1)) return;

            if (document.getElementById('btnRevizeDelete')) return;

            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'small-btn';
            btn.id = 'btnRevizeDelete';
            btn.title = 'Revize Sil';
            btn.textContent = 'Revize Sil';
            btn.style.marginLeft = '0.5rem';
            btn.style.color = '#dc2626';

            btn.addEventListener('click', function () {
                var ok = window.confirm('Revize silinsin mi?');
                if (ok) {
                    revizeDeleteForm.submit();
                }
            });

            btnRevizeCreate.insertAdjacentElement('afterend', btn);
        })();

        document.querySelectorAll('[data-modal-close]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var id = this.getAttribute('data-modal-close');
                var modal = document.getElementById(id);
                closeModal(modal);
            });
        });

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
            var productSearchInput = document.getElementById('productModalSearch');
            if (productSearchInput && !productSearchInput.dataset.bound) {
                productSearchInput.dataset.bound = '1';
                productSearchInput.addEventListener('input', function () {
                    var q = (productSearchInput.value || '').toString().trim().toLowerCase();
                    document.querySelectorAll('#productModal .product-row').forEach(function (row) {
                        var kod = (row.dataset.kod || '').toString().toLowerCase();
                        var aciklama = (row.dataset.aciklama || '').toString().toLowerCase();
                        var ok = !q || kod.indexOf(q) !== -1 || aciklama.indexOf(q) !== -1;
                        row.style.display = ok ? '' : 'none';
                    });
                });
            }

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
                    var doviz = this.dataset.doviz || 'TL';
                    var doviz = this.dataset.doviz || 'TL';
                    var urunId = this.dataset.id || '';
                    var isMulti = (this.dataset.multi || '') === '1';
                    var isMontaj = (this.dataset.montaj || '') === '1';
                    var detayGrupId = this.dataset.detaygrup || '';
                    var resimYolu = this.dataset.resim || '';

                    var kodInput = currentProductRow.querySelector('.stok-kod');
                    var aciklamaInput = currentProductRow.querySelector('.stok-aciklama');
                    var fiyatInput = currentProductRow.querySelector('.birim-fiyat');
                    var dovizSelect = currentProductRow.querySelector('.doviz');
                    var dovizSelect = currentProductRow.querySelector('.doviz');
                    var urunIdInput = currentProductRow.querySelector('.urun-id');
                    var satirAciklamaHidden = currentProductRow.querySelector('.satir-aciklama-hidden');

                    if (kodInput) kodInput.value = kod;
                    if (aciklamaInput) aciklamaInput.value = aciklama;
                    if (fiyatInput) fiyatInput.value = fiyat;
                    if (dovizSelect) {
                        var val = (doviz || 'TL').toString().trim().toUpperCase();
                        if (val !== 'TL' && val !== 'USD' && val !== 'EUR') val = 'TL';
                        var old = (dovizSelect.value || '').toString().trim().toUpperCase();
                        dovizSelect.value = val;
                        if (old !== val) {
                            dovizSelect.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    }
                    if (urunIdInput) urunIdInput.value = urunId;
                    if (satirAciklamaHidden) satirAciklamaHidden.value = aciklama;
                    if (dovizSelect) {
                        var val = (doviz || 'TL').toString().trim().toUpperCase();
                        if (val !== 'TL' && val !== 'USD' && val !== 'EUR') val = 'TL';
                        var old = (dovizSelect.value || '').toString().trim().toUpperCase();
                        dovizSelect.value = val;
                        if (old !== val) {
                            dovizSelect.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    }
                    if (dovizSelect) {
                        var val = (doviz || 'TL').toString().trim().toUpperCase();
                        if (val !== 'TL' && val !== 'USD' && val !== 'EUR') val = 'TL';
                        var old = (dovizSelect.value || '').toString().trim().toUpperCase();
                        dovizSelect.value = val;
                        if (old !== val) {
                            dovizSelect.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    }
                    if (urunIdInput) urunIdInput.value = urunId;
                    if (satirAciklamaHidden) satirAciklamaHidden.value = aciklama;
                    if (window.__offerSetLineDetayVisible) {
                        window.__offerSetLineDetayVisible(currentProductRow, isMulti || isMontaj);
                    }
                    currentProductRow.dataset.multi = isMulti ? '1' : '0';
                    currentProductRow.dataset.montaj = isMontaj ? '1' : '0';
                    currentProductRow.dataset.detaygrup = detayGrupId ? String(detayGrupId) : '';
                    currentProductRow.dataset.resim = resimYolu ? String(resimYolu) : '';

                    autoGrowTextareasInRow(currentProductRow);
                    scheduleMontajDetailAutoFill();
                    closeModal(productModal);
                });
            });
        }


            var firmSearchInput = document.getElementById('firmModalSearch');
            if (firmSearchInput && !firmSearchInput.dataset.bound) {
                firmSearchInput.dataset.bound = '1';
                firmSearchInput.addEventListener('input', function () {
                    var q = (firmSearchInput.value || '').toString().trim().toLowerCase();
                    document.querySelectorAll('#firmModal .firm-row').forEach(function (row) {
                        var kod = (row.dataset.carikod || '').toString().toLowerCase();
                        var aciklama = (row.dataset.cariaciklama || '').toString().toLowerCase();
                        var ok = !q || kod.indexOf(q) !== -1 || aciklama.indexOf(q) !== -1;
                        row.style.display = ok ? '' : 'none';
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
            var productSearchInput2 = document.getElementById('productModalSearch');
            if (productSearchInput2 && !productSearchInput2.dataset.bound) {
                productSearchInput2.dataset.bound = '1';
                productSearchInput2.addEventListener('input', function () {
                    var q = (productSearchInput2.value || '').toString().trim().toLowerCase();
                    document.querySelectorAll('#productModal .product-row').forEach(function (row) {
                        var kod = (row.dataset.kod || '').toString().toLowerCase();
                        var aciklama = (row.dataset.aciklama || '').toString().toLowerCase();
                        var ok = !q || kod.indexOf(q) !== -1 || aciklama.indexOf(q) !== -1;
                        row.style.display = ok ? '' : 'none';
                    });
                });
            }

            linesBody.addEventListener('dblclick', function (e) {
                if (e.target && e.target.closest && e.target.closest('.stok-detay-btn')) {
                    return;
                }
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
                    var doviz = this.dataset.doviz || 'TL';
                    var urunId = this.dataset.id || '';
                    var isMulti = (this.dataset.multi || '') === '1';
                    var isMontaj = (this.dataset.montaj || '') === '1';

                    var kodInput = currentProductRow.querySelector('.stok-kod');
                    var aciklamaInput = currentProductRow.querySelector('.stok-aciklama');
                    var fiyatInput = currentProductRow.querySelector('.birim-fiyat');
                    var miktarInput = currentProductRow.querySelector('.miktar');
                    var dovizSelect = currentProductRow.querySelector('.doviz');
                    var urunIdInput = currentProductRow.querySelector('.urun-id');
                    var satirAciklamaHidden = currentProductRow.querySelector('.satir-aciklama-hidden');

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
                    if (window.__offerSetLineDetayVisible) {
                        window.__offerSetLineDetayVisible(currentProductRow, isMulti || isMontaj);
                    }
                    currentProductRow.dataset.multi = isMulti ? '1' : '0';
                    currentProductRow.dataset.montaj = isMontaj ? '1' : '0';

                    autoGrowTextareasInRow(currentProductRow);
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
        var offerForm = document.getElementById('offerForm');
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
        var menuPdf = document.getElementById('menuPdf');
        var menuPrint = document.getElementById('menuPrint');
        var menuOrder = document.getElementById('menuOrder');
        var createSalesOrderForm = document.getElementById('createSalesOrderForm');
        var pdfPreviewModal = document.getElementById('pdfPreviewModal');
        var pdfPreviewFrame = document.getElementById('pdfPreviewFrame');
        var pdfPreviewBlobUrl = null;
        var pdfJsonModal = document.getElementById('pdfJsonModal');
        var pdfJsonTextarea = document.getElementById('pdfJsonTextarea');
        var pdfJsonServletUrl = document.getElementById('pdfJsonServletUrl');
        var btnPdfJsonContinue = document.getElementById('btnPdfJsonContinue');
        var pendingPdfPayload = null;
        var pendingPdfUrl = null;

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

        function safeNumber(v) {
            if (v === null || v === undefined) return 0;
            var s = v.toString().trim();
            if (!s) return 0;

            // Handles "1.234,56" and "1234.56"
            if (s.indexOf(',') >= 0) {
                s = s.replace(/\./g, '').replace(',', '.');
            }

            s = s.replace(/\s/g, '');
            var n = parseFloat(s);
            return isFinite(n) ? n : 0;
        }

        function buildProductImageUrl(resimYolu) {
            if (!resimYolu) return null;
            var s = resimYolu.toString().trim();
            if (!s) return null;
            if (s.startsWith('http://') || s.startsWith('https://')) return s;
            s = s.replace(/\\/g, '/');
            var parts = s.split('/').filter(Boolean);
            var fileName = parts.length ? parts[parts.length - 1] : s;
            return 'http://localhost:8000/storage/products/' + encodeURIComponent(fileName);
        }

        function buildProductStorageUrl(resimYolu) {
            if (!resimYolu) return null;
            var s = resimYolu.toString().trim();
            if (!s) return null;
            s = s.replace(/\\/g, '/');
            var parts = s.split('/').filter(Boolean);
            var fileName = parts.length ? parts[parts.length - 1] : s;
            if (!fileName) return null;
            return '/storage/products/' + encodeURIComponent(fileName);
        }

        function getMimeFromFileName(fileName) {
            var s = (fileName || '').toString().toLowerCase();
            if (s.endsWith('.png')) return 'image/png';
            if (s.endsWith('.webp')) return 'image/webp';
            if (s.endsWith('.gif')) return 'image/gif';
            if (s.endsWith('.jpg') || s.endsWith('.jpeg')) return 'image/jpeg';
            return 'image/jpeg';
        }

        function loadImageFromBlob(blob) {
            return new Promise(function (resolve, reject) {
                var url = null;
                try {
                    url = URL.createObjectURL(blob);
                } catch (e) {
                    reject(e);
                    return;
                }

                var img = new Image();
                img.onload = function () {
                    try { URL.revokeObjectURL(url); } catch (e) { }
                    resolve(img);
                };
                img.onerror = function () {
                    try { URL.revokeObjectURL(url); } catch (e) { }
                    reject(new Error('img_load_failed'));
                };
                img.src = url;
            });
        }

        var offerImageBase64Endpoint = @json(route('offers.image-base64', [], false));
        var offerImageCache = {};

        async function getSmallBase64Image(resimYolu) {
            var fileName = (resimYolu || '').toString().trim();
            if (!fileName) return null;

            fileName = fileName.replace(/\\/g, '/');
            var parts = fileName.split('/').filter(Boolean);
            var baseName = parts.length ? parts[parts.length - 1] : fileName;
            baseName = (baseName || '').split('?')[0].split('#')[0];
            if (!baseName) return null;

            if (offerImageCache[baseName]) return offerImageCache[baseName];
            if (!offerImageBase64Endpoint) return null;

            var csrf = document.querySelector('meta[name=\"csrf-token\"]')?.getAttribute('content') || '';
            try {
                var resp = await fetch(offerImageBase64Endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: JSON.stringify({ src: resimYolu }),
                    credentials: 'same-origin'
                });
                if (!resp.ok) {
                    try { console.warn('image-base64 http error', resp.status); } catch (e) { }
                    return null;
                }
                var json = await resp.json();
                if (!json || json.ok !== true || !json.image) {
                    try { console.warn('image-base64 not ok', json); } catch (e) { }
                    return null;
                }
                offerImageCache[baseName] = json.image;
                return json.image;
            } catch (e) {
                try { console.warn('image-base64 exception', e); } catch (ex) { }
                return null;
            }
        }

        async function enrichPayloadWithImages(payload) {
            if (!payload || !Array.isArray(payload.satirlar)) return payload;

            var tasks = payload.satirlar.map(async function (line) {
                if (!line) return;
                var src = line.resim_yolu || line.resim_url;
                if (!src) return;
                try {
                    var img = await getSmallBase64Image(src);
                    if (img) {
                        line.resim = img;
                    }
                } catch (e) { }
            });

            await Promise.all(tasks);
            return payload;
        }

        var offerMontajDetailCache = {};
        async function fetchMontajDetails(teklifDetayId) {
            var id = (teklifDetayId === null || teklifDetayId === undefined) ? '' : String(teklifDetayId);
            id = id.trim();
            if (!id) return null;

            if (offerMontajDetailCache[id]) {
                try { return await offerMontajDetailCache[id]; } catch (e) { return null; }
            }

            var baseUrl = @json(url('teklifler/satirlar'));
            var url = String(baseUrl || '').replace(/\/+$/, '') + '/' + encodeURIComponent(id) + '/montaj-detaylari';

            offerMontajDetailCache[id] = fetch(url, {
                method: 'GET',
                credentials: 'same-origin',
                headers: { 'Accept': 'application/json' }
            })
                .then(function (r) {
                    return r.json().then(function (j) {
                        if (!r.ok || !j || j.ok !== true) throw (j || {});
                        return j;
                    });
                })
                .catch(function () { return null; });

            return offerMontajDetailCache[id];
        }

        async function enrichPayloadWithMontajDetails(payload) {
            if (!payload || !Array.isArray(payload.satirlar)) return payload;

            var allMontajItems = [];

            var tasks = payload.satirlar.map(async function (line) {
                if (!line || !line.montaj) return;
                if (!line.teklif_detay_id) {
                    line.montaj_groups = [];
                    line.montaj_satirlari = [];
                    line.montaj_satir_sayisi = 0;
                    return;
                }

                var data = await fetchMontajDetails(line.teklif_detay_id);
                var groups = (data && Array.isArray(data.groups)) ? data.groups : [];

                line.montaj_groups = groups;

                var flat = [];
                groups.forEach(function (g) {
                    var groupId = (g && g.id != null) ? g.id : null;
                    var groupKod = (g && g.kod != null) ? String(g.kod) : '';
                    var items = (g && Array.isArray(g.items)) ? g.items : [];
                    items.forEach(function (it) {
                        if (!it) return;
                        flat.push({
                            teklif_detay_id: line.teklif_detay_id,
                            line_sira: line.sira || null,
                            stok_kod: (line.stok_kod || '').toString(),
                            stok_aciklama: (line.stok_aciklama || '').toString(),
                            montaj_grup_id: groupId,
                            montaj_grup_kod: groupKod,
                            urun_kod: (it.urun_kod || '').toString(),
                            birim: (it.birim || '').toString(),
                            miktar: safeNumber(it.miktar),
                            birim_fiyat: safeNumber(it.birim_fiyat),
                            doviz: (it.doviz || 'TL').toString(),
                            satir_tutar: safeNumber(it.satir_tutar),
                            sirano: safeNumber(it.sirano),
                        });
                    });
                });

                line.montaj_satirlari = flat;
                line.montaj_satir_sayisi = flat.length;

                allMontajItems = allMontajItems.concat(flat);
            });

            await Promise.all(tasks);

            payload.montaj_satirlari = allMontajItems;
            if (payload.header) {
                payload.header.montaj_var = allMontajItems.length > 0;
                payload.header.montaj_satir_sayisi = allMontajItems.length;
            }

            return payload;
        }

        function buildOfferPrintPayload() {
            var header = {
                tur: (document.querySelector('input[name=\"tur\"]')?.value || '').toString(),

                teklif_no: (document.getElementById('teklif_no')?.value || '').toString(),
                revize_no: (document.getElementById('revize_no')?.value || '').toString(),
                tarih: (document.getElementById('tarih')?.value || '').toString(),
                gecerlilik_tarihi: (document.getElementById('gecerlilik_tarihi')?.value || '').toString(),

                teklif_durum: (document.getElementById('teklif_durum')?.value || '').toString(),
                gerceklesme_olasiligi: safeNumber(document.getElementById('gerceklesme_olasiligi')?.value),

                onay_durum: (document.getElementById('onay_durum')?.value || '').toString(),
                onay_tarihi: (document.getElementById('onay_tarihi')?.value || '').toString(),

                carikod: (document.getElementById('carikod')?.value || '').toString(),
                cariaciklama: (document.getElementById('cariaciklama')?.value || '').toString(),
                firma_kod: (document.getElementById('firma_kod_label')?.textContent || '').toString().trim(),
                firma_unvan: (document.getElementById('firma_aciklama_label')?.textContent || '').toString().trim(),
                firma_adres1: (document.getElementById('firma_adres_satir1')?.textContent || '').toString().trim(),
                firma_adres2: (document.getElementById('firma_adres_satir2')?.textContent || '').toString().trim(),
                firma_il_ilce: (document.getElementById('firma_il_ilce')?.textContent || '').toString().trim(),

                yetkili_personel: (document.getElementById('yetkili_personel')?.value || '').toString(),
                hazirlayan: (document.getElementById('hazirlayan')?.value || '').toString(),

                islem_turu_id: safeNumber(document.getElementById('islem_turu_id')?.value),
                islem_turu_adi: (document.getElementById('islem_turu_adi')?.value || '').toString(),
                proje_id: safeNumber(document.getElementById('proje_id')?.value),
                proje_kod: (document.getElementById('proje_kod')?.value || '').toString(),

                teklif_doviz: (document.getElementById('offer_currency')?.value || '').toString(),
                teklif_kur: safeNumber(document.getElementById('offer_rate')?.value),

                aciklama: (document.getElementById('aciklama')?.value || '').toString(),

                alt_toplam_tl: safeNumber(document.getElementById('sumToplam')?.textContent),
                iskonto_tutar_tl: safeNumber(document.getElementById('sumIskonto')?.textContent),
                kdv_tl: safeNumber(document.getElementById('sumKdv')?.textContent),
                genel_toplam_tl: safeNumber(document.getElementById('sumGenel')?.textContent),

                alt_toplam_doviz: safeNumber(document.getElementById('sumToplamFx')?.textContent),
                iskonto_tutar_doviz: safeNumber(document.getElementById('sumIskontoFx')?.textContent),
                kdv_doviz: safeNumber(document.getElementById('sumKdvFx')?.textContent),
                genel_toplam_doviz: safeNumber(document.getElementById('sumGenelFx')?.textContent),
            };

            var lines = [];
            var tbody = document.getElementById('offerLinesBody');
            if (tbody) {
                Array.prototype.slice.call(tbody.querySelectorAll('tr')).forEach(function (tr, idx) {
                    var kod = (tr.querySelector('.stok-kod')?.value || '').toString().trim();
                    var aciklama = (tr.querySelector('.stok-aciklama')?.value || '').toString().trim();
                    var urunId = (tr.querySelector('.urun-id')?.value || '').toString().trim();
                    var teklifDetayId = (tr.querySelector('.teklif-detay-id')?.value || '').toString().trim();
                    var satirAciklama = (tr.querySelector('.satir-aciklama-hidden')?.value || '').toString().trim();
                    var miktar = safeNumber(tr.querySelector('.miktar')?.value);
                    var birimFiyat = safeNumber(tr.querySelector('.birim-fiyat')?.value);
                    var doviz = (tr.querySelector('.doviz')?.value || 'TL').toString().trim().toUpperCase();
                    var kur = safeNumber(tr.querySelector('.kur')?.value);
                    var isk1 = safeNumber(tr.querySelector('.isk1')?.value);
                    var isk2 = safeNumber(tr.querySelector('.isk2')?.value);
                    var isk3 = safeNumber(tr.querySelector('.isk3')?.value);
                    var isk4 = safeNumber(tr.querySelector('.isk4')?.value);
                    var isk5 = safeNumber(tr.querySelector('.isk5')?.value);
                    var isk6 = safeNumber(tr.querySelector('.isk6')?.value);
                    var iskTutar = safeNumber(tr.querySelector('.isk-tutar')?.value);
                    var kdvOran = safeNumber(tr.querySelector('.kdv-oran')?.value);
                    var kdvDurum = (tr.querySelector('.kdv-durum')?.value || '').toString();
                    var satirTutarDoviz = safeNumber(tr.querySelector('.satir-tutar-doviz')?.value);
                    var satirTutar = safeNumber(tr.querySelector('.satir-tutar')?.value);
                    var resimYolu = (tr.dataset.resim || '').toString().trim();
                    var detayGrupId = (tr.dataset.detaygrup || '').toString().trim();
                    var isMulti = (tr.dataset.multi || '') === '1';
                    var isMontaj = (tr.dataset.montaj || '') === '1';

                    if (!kod && !aciklama && miktar <= 0 && birimFiyat <= 0) return;

                    lines.push({
                        sira: idx + 1,
                        teklif_detay_id: teklifDetayId ? parseInt(teklifDetayId, 10) || null : null,
                        urun_id: urunId ? parseInt(urunId, 10) || null : null,
                        stok_kod: kod,
                        stok_aciklama: aciklama,
                        satir_aciklama: satirAciklama || aciklama,
                        miktar: miktar,
                        birim_fiyat: birimFiyat,
                        doviz: doviz || 'TL',
                        kur: doviz === 'TL' ? 1 : kur,
                        iskonto1: isk1,
                        iskonto2: isk2,
                        iskonto3: isk3,
                        iskonto4: isk4,
                        iskonto5: isk5,
                        iskonto6: isk6,
                        iskonto_tutar: iskTutar,
                        kdv_orani: kdvOran,
                        kdv_durum: kdvDurum,
                        satir_tutar_doviz: satirTutarDoviz,
                        satir_tutar: satirTutar,
                        detay_grup_id: detayGrupId ? parseInt(detayGrupId, 10) || null : null,
                        multi: isMulti,
                        montaj: isMontaj,
                        montaj_groups: [],
                        montaj_satirlari: [],
                        montaj_satir_sayisi: 0,
                        resim_yolu: resimYolu || null,
                        resim_url: buildProductImageUrl(resimYolu),
                        resim: null,
                    });
                });
            }

            return {
                kaynak: 'nomaenerji-laravel',
                olusturma_zamani: new Date().toISOString(),
                header: header,
                satirlar: lines,
            };
        }

        function sendToJavaServlet(payload) {
            var url = (window.JAVA_SERVLET_URL || 'http://localhost:8080/teklif-print').toString();

            try {
                window.__lastOfferPrintPayload = payload;
                console.log('Teklif JSON:', payload);
                console.log(JSON.stringify(payload, null, 2));
            } catch (e) { }

            try {
                if (navigator && typeof navigator.sendBeacon === 'function') {
                    var blob = new Blob([JSON.stringify(payload)], { type: 'application/json' });
                    navigator.sendBeacon(url, blob);
                    return;
                }
            } catch (e) { }

            try {
                fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(payload),
                    keepalive: true,
                    mode: 'cors'
                }).catch(function () { });
            } catch (e) { }
        }

        async function handleOfferPrintJsonSend() {
            try {
                var payload = buildOfferPrintPayload();
                try { await enrichPayloadWithMontajDetails(payload); } catch (e) { }
                try { await enrichPayloadWithImages(payload); } catch (e) { }
                sendToJavaServlet(payload);
            } catch (e) { }
        }

        function buildTomcatServletUrl() {
            var ip = @json($tomcatIp ?? 'localhost');
            var port = @json($tomcatPort ?? '8080');
            var project = @json($tomcatProje ?? '');
            ip = (ip || 'localhost').toString().trim();
            port = (port || '8080').toString().trim();
            project = (project || '').toString().trim();

            var base = ip;
            if (!base.startsWith('http://') && !base.startsWith('https://')) {
                base = 'http://' + base;
            }
            // If base already has port (e.g. http://x:8080) keep it, otherwise append port
            if (!/:[0-9]+$/.test(base.replace(/\/+$/, ''))) {
                base = base.replace(/\/+$/, '') + ':' + port;
            }

            var path = '';
            if (project) {
                project = project.replace(/^\/+/, '').replace(/\/+$/, '');
                if (project) {
                    path += '/' + project;
                }
            }
            path += '/json-to-pdf';

            return base.replace(/\/+$/, '') + path;
        }

        function openPdfPreview(blob) {
            if (!pdfPreviewModal || !pdfPreviewFrame) return;
            try {
                if (pdfPreviewBlobUrl) URL.revokeObjectURL(pdfPreviewBlobUrl);
            } catch (e) { }
            pdfPreviewBlobUrl = URL.createObjectURL(blob);
            pdfPreviewFrame.src = pdfPreviewBlobUrl;
            pdfPreviewModal.style.display = 'flex';
        }

        function openPdfJson(payload, url) {
            if (!pdfJsonModal || !pdfJsonTextarea) return;
            pendingPdfPayload = payload;
            pendingPdfUrl = url;
            if (pdfJsonServletUrl) pdfJsonServletUrl.textContent = url || '';
            try {
                pdfJsonTextarea.value = JSON.stringify(payload, null, 2);
            } catch (e) {
                pdfJsonTextarea.value = String(payload || '');
            }
            pdfJsonModal.style.display = 'flex';
        }

        function closePdfJson() {
            if (!pdfJsonModal) return;
            pdfJsonModal.style.display = 'none';
        }

        function requestPdfFromTomcat(payload, url) {
            if (!payload) return;

            if (menuPdf) {
                menuPdf.disabled = true;
                menuPdf.style.opacity = '0.6';
            }

            var csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            fetch(@json(route('offers.json-to-pdf', [], false)), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/pdf',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify(payload),
                credentials: 'same-origin'
            })
                .then(function (r) {
                    if (!r.ok) {
                        return r.text().then(function (t) {
                            throw new Error(t || ('HTTP ' + r.status));
                        });
                    }
                    return r.blob();
                })
                .then(function (blob) {
                    if (!blob || !blob.size) {
                        throw new Error('PDF bos dondu.');
                    }
                    openPdfPreview(blob);
                })
                .catch(function () {
                    alert('PDF olusturulamadi (403 aliyorsaniz CORS/Yetki olabilir).');
                })
                .finally(function () {
                    if (menuPdf) {
                        menuPdf.disabled = false;
                        menuPdf.style.opacity = '1';
                    }
                });
        }

        if (pdfPreviewModal && !pdfPreviewModal.dataset.boundClose) {
            pdfPreviewModal.dataset.boundClose = '1';
            pdfPreviewModal.addEventListener('click', function (e) {
                if (e.target !== pdfPreviewModal) return;
                pdfPreviewModal.style.display = 'none';
                if (pdfPreviewFrame) pdfPreviewFrame.src = 'about:blank';
                try {
                    if (pdfPreviewBlobUrl) URL.revokeObjectURL(pdfPreviewBlobUrl);
                } catch (ex) { }
                pdfPreviewBlobUrl = null;
            });
        }

        if (pdfJsonModal && !pdfJsonModal.dataset.boundClose) {
            pdfJsonModal.dataset.boundClose = '1';
            pdfJsonModal.addEventListener('click', function (e) {
                if (e.target !== pdfJsonModal) return;
                closePdfJson();
            });
        }

        if (btnPdfJsonContinue && !btnPdfJsonContinue.dataset.bound) {
            btnPdfJsonContinue.dataset.bound = '1';
            btnPdfJsonContinue.addEventListener('click', function () {
                closePdfJson();
                requestPdfFromTomcat(pendingPdfPayload, pendingPdfUrl);
            });
        }

        if (menuPdf && !menuPdf.dataset.boundPdf) {
            menuPdf.dataset.boundPdf = '1';
            menuPdf.addEventListener('click', async function () {
                if (menuPdf.disabled) return;

                var payload = null;
                try {
                    payload = buildOfferPrintPayload();
                } catch (e) {
                    alert('JSON veri hazrlanamad.');
                    return;
                }

                var url = buildTomcatServletUrl();
                try { alert(url); } catch (e) { }

                try {
                    menuPdf.disabled = true;
                    menuPdf.style.opacity = '0.6';
                    await enrichPayloadWithMontajDetails(payload);
                    await enrichPayloadWithImages(payload);
                } catch (e) { }
                finally {
                    menuPdf.disabled = false;
                    menuPdf.style.opacity = '1';
                }

                openPdfJson(payload, url);
            });
        }

        if (menuPrint && !menuPrint.dataset.jsonSendBound) {
            menuPrint.dataset.jsonSendBound = '1';
            menuPrint.addEventListener('click', function () {
                var printLink = document.querySelector('.top-bar a.small-btn[title*="Yazd"]');
                var url = (printLink && printLink.href) ? printLink.href : '';
                if (url) {
                    alert(url);
                }
                handleOfferPrintJsonSend();
            }, true);
        }

        var topBarPrintLink = document.querySelector('.top-bar a.small-btn[title*="Yazd"]');
        if (topBarPrintLink && !topBarPrintLink.dataset.jsonSendBound) {
            topBarPrintLink.dataset.jsonSendBound = '1';
            topBarPrintLink.addEventListener('click', function () {
                var url = topBarPrintLink.href ? topBarPrintLink.href : '';
                if (url) {
                    alert(url);
                }
                handleOfferPrintJsonSend();
            }, true);
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

        if (menuOrder && createSalesOrderForm && !menuOrder.disabled) {
            menuOrder.addEventListener('click', function (e) {
                e.preventDefault();
                var ok = window.confirm('Tekliften Satış Siparişi oluşturulsun mu?');
                if (ok) {
                    createSalesOrderForm.requestSubmit();
                }
            });
        }
    });
</script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>

















