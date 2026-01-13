<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Teklifler - NomaEnerji</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        .offers-table-card {
            border-radius: 16px;
            border: 1px solid rgba(148, 163, 184, 0.25);
            padding: 1rem 1.25rem 1.5rem;
            background: radial-gradient(circle at top left, rgba(59,130,246,0.06), transparent 55%),
            radial-gradient(circle at bottom right, rgba(16,185,129,0.05), transparent 55%),
            var(--card-bg);
            backdrop-filter: blur(10px);
        }

        .offers-header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .offers-new-button {
            padding: 0.45rem 1rem;
            border-radius: 999px;
            border: none;
            background-color: #16a34a;
            color: #ffffff;
            font-size: 0.8rem;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
        }

        .offers-filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .offers-filter-row .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .offers-filter-row label {
            font-size: 0.75rem;
            font-weight: 500;
            color: #4b5563;
        }

        .offers-filter-row input[type="text"],
        .offers-filter-row input[type="date"],
        .offers-filter-row select {
            min-width: 160px;
            border-radius: 999px;
            border: 1px solid #e5e7eb;
            background-color: #ffffff;
            padding: 0.45rem 0.85rem;
            font-size: 0.8rem;
            color: #111827;
            outline: none;
        }

        .offers-filter-row input:focus,
        .offers-filter-row select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 1px #2563eb1f;
        }

        .offers-filter-actions {
            display: flex;
            align-items: flex-end;
            gap: 0.5rem;
        }

        .offers-filter-actions .small-btn {
            border-radius: 999px;
            border: none;
            background: transparent;
            color: #2563eb;
            width: 34px;
            height: 34px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .offers-filter-actions .small-btn:hover {
            background: #f3f4f6;
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
        .btn {
            padding: 0.45rem 1.2rem;
            border-radius: 999px;
            border: none;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
        }
        .btn-save {
            background: #2563eb;
            color: #fff;
        }

        .offers-filter-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }

        .offers-filter-button {
            padding: 0.45rem 1rem;
            border-radius: 999px;
            border: none;
            background-color: #2563eb;
            color: #ffffff;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
        }

        .offers-filter-reset {
            padding: 0.45rem 0.9rem;
            border-radius: 999px;
            border: 1px solid #e5e7eb;
            background-color: #ffffff;
            color: #4b5563;
            font-size: 0.8rem;
            cursor: pointer;
        }

        .offers-table-wrapper {
            margin-top: 0.75rem;
            overflow-x: auto;
            overflow-y: hidden;
            border-radius: 12px;
            border: 1px solid rgba(203, 213, 225, 0.7);
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.10);
        }

        .offers-table {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
            border: 1px solid #e5e7eb;
        }

        .offers-table thead {
            background-color: #f3f4f6;
        }

        .offers-table thead th {
            padding: 0.55rem 0.65rem;
            text-align: left;
            font-size: 0.7rem;
            font-weight: 600;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
            white-space: nowrap;
        }

        .offers-table thead th.sortable {
            cursor: pointer;
            user-select: none;
        }
        .offers-table thead th.sortable:hover {
            background-color: #e5e7eb;
        }
        .offers-table thead th.sort-asc::after {
            content: " ▲";
            font-size: 0.75em;
            color: #6b7280;
        }
        .offers-table thead th.sort-desc::after {
            content: " ▼";
            font-size: 0.75em;
            color: #6b7280;
        }

        .offers-table tbody tr {
            background-color: #ffffff;
            transition: background 0.12s ease;
        }

        .offers-table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .offers-table tbody tr:hover {
            background-color: #f3f4f6;
        }

        .offers-table tbody td {
            padding: 0.5rem 0.65rem;
            font-size: 0.72rem;
            color: #111827;
            border-bottom: 1px solid #e5e7eb;
            white-space: normal;
            word-break: break-word;
            vertical-align: top;
        }

        .offers-table tbody td:last-child {
            white-space: nowrap;
        }

        .offers-status-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.15rem 0.45rem;
            border-radius: 999px;
            font-size: 0.65rem;
            font-weight: 500;
        }

        .status-taslak {
            background-color: #e5e7eb;
            color: #374151;
        }

        .status-gonderildi {
            background-color: #dbeafe;
            color: #1d4ed8;
        }

        .status-kabul {
            background-color: #dcfce7;
            color: #15803d;
        }

        .status-reddedildi {
            background-color: #fee2e2;
            color: #b91c1c;
        }

        .status-suresi-doldu {
            background-color: #fef3c7;
            color: #92400e;
        }

        @media (max-width: 1024px) {
            .offers-filter-row {
                flex-direction: column;
                align-items: flex-start;
            }

            .offers-filter-footer {
                flex-direction: column;
                align-items: stretch;
            }

            .offers-filter-actions {
                align-items: center;
                justify-content: flex-start;
            }
        }
    </style>
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
                Teklifler
            </div>
        </header>

        <section class="content-section" style="padding: 2rem;">
            <div class="offers-table-card">
                <div class="offers-header-row">
                    <div>
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                    </div>
                </div>
                <form method="GET" action="{{ route('offers.index') }}">
                    <input type="hidden" name="tur" value="{{ $offerTur }}">
                    <div class="offers-filter-row">
                        <div class="filter-group">
                            <label for="q">Arama (Teklif No / Cari)</label>
                            <input type="text" id="q" name="q" value="{{ request('q') }}"
                                   placeholder="Teklif no, cari kod veya açıklama">
                        </div>

                        <div class="filter-group">
                            <label for="tarih_baslangic">Teklif Tarihi (Başlangıç)</label>
                            <input type="date" id="tarih_baslangic" name="tarih_baslangic"
                                   value="{{ request('tarih_baslangic') }}">
                        </div>

                        <div class="filter-group">
                            <label for="tarih_bitis">Teklif Tarihi (Bitiş)</label>
                            <input type="date" id="tarih_bitis" name="tarih_bitis"
                                   value="{{ request('tarih_bitis') }}">
                        </div>

                        <div class="filter-group">
                            <label for="teklif_durum">Teklif Durum</label>
                            <select id="teklif_durum" name="teklif_durum">
                                @foreach($durumlar as $key => $label)
                                    <option value="{{ $key }}" {{ request('teklif_durum', 'hepsi') === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="islem_turu_id">İşlem Türü</label>
                            <select id="islem_turu_id" name="islem_turu_id">
                                <option value="">Hepsi</option>
                                @foreach($islemTurleri as $islemTuru)
                                    <option value="{{ $islemTuru->id }}" {{ (string) request('islem_turu_id') === (string) $islemTuru->id ? 'selected' : '' }}>
                                        {{ $islemTuru->ad }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="proje_id">Proje</label>
                            <select id="proje_id" name="proje_id">
                                <option value="">Hepsi</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ (string) request('proje_id') === (string) $project->id ? 'selected' : '' }}>
                                        {{ $project->kod }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="gerceklesme_olasiligi">Gerçekleşme Olasılığı</label>
                            <select id="gerceklesme_olasiligi" name="gerceklesme_olasiligi">
                                <option value="">Hepsi</option>
                                @foreach([25, 50, 75, 100] as $oran)
                                    <option value="{{ $oran }}" {{ (string) request('gerceklesme_olasiligi') === (string) $oran ? 'selected' : '' }}>
                                        {{ $oran }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="offers-filter-footer">
                        <div class="offers-filter-actions">
                            <button type="submit" class="offers-filter-button">Filtrele</button>
                            <a href="{{ route('offers.index', ['tur' => $offerTur]) }}" class="offers-filter-reset">Temizle</a>
                            <button type="button" id="btnOfferListSettings" class="small-btn" title="Tablo Düzenle" aria-label="Tablo Düzenle">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z" stroke="currentColor" stroke-width="2"/>
                                    <path d="M19.4 15a8.07 8.07 0 0 0 .04-1 8.07 8.07 0 0 0-.04-1l2.1-1.64a.5.5 0 0 0 .12-.65l-2-3.46a.5.5 0 0 0-.6-.22l-2.48 1a7.74 7.74 0 0 0-1.73-1l-.38-2.65A.5.5 0 0 0 13.94 3h-4a.5.5 0 0 0-.49.42l-.38 2.65c-.62.24-1.2.57-1.73 1l-2.48-1a.5.5 0 0 0-.6.22l-2 3.46a.5.5 0 0 0 .12.65L4.6 13c-.03.33-.04.66-.04 1s.01.67.04 1l-2.1 1.64a.5.5 0 0 0-.12.65l2 3.46a.5.5 0 0 0 .6.22l2.48-1c.53.43 1.11.76 1.73 1l.38 2.65a.5.5 0 0 0 .49.42h4a.5.5 0 0 0 .49-.42l.38-2.65c.62-.24 1.2-.57 1.73-1l2.48 1a.5.5 0 0 0 .6-.22l2-3.46a.5.5 0 0 0-.12-.65L19.4 15Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>

                        <a href="{{ route('offers.create', ['tur' => $offerTur]) }}" class="offers-new-button">
                            Yeni Teklif
                        </a>
                    </div>
                </form>

                <div class="offers-table-wrapper">
                    <table class="offers-table" id="offersListTable">
                        <thead>
                        <tr>
                            <th data-col-key="teklif_no">Teklif No</th>
                            <th data-col-key="revize_no">Revize No</th>
                            <th data-col-key="tarih">Teklif Tarih</th>
                            <th data-col-key="gecerlilik_tarihi">Geçerlilik Tarihi</th>
                            <th data-col-key="gecen_sure">Geçen Süre</th>
                            <th data-col-key="teklif_durum">Teklif Durum</th>
                            <th data-col-key="gerceklesme_olasiligi">Gerçekleşme Olasılığı</th>
                            <th data-col-key="islem_turu">İşlem Türü</th>
                            <th data-col-key="proje">Proje</th>
                            <th data-col-key="carikod">Cari Kod</th>
                            <th data-col-key="cariaciklama">Cari Açıklama</th>
                            <th data-col-key="onay_durum">Onay Durum</th>
                            <th data-col-key="onay_tarihi">Onay Tarih</th>
                            <th data-col-key="hazirlayan">Hazırlayan</th>
                            <th data-col-key="teklif_doviz">Teklif Döviz</th>
                            <th data-col-key="teklif_kur">Teklif Kur</th>
                            <th data-col-key="alt_toplam">Alt Toplam</th>
                            <th data-col-key="iskonto_tutar">İskonto Tutar</th>
                            <th data-col-key="kdv">KDV</th>
                            <th data-col-key="genel_toplam">Genel Toplam (TL)</th>
                            <th data-col-key="toplam_tutar">Toplam Tutar</th>
                            <th data-col-key="aciklama">Açıklama</th>
                            <th data-col-key="islem" style="text-align:right;">İşlem</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($teklifler as $teklif)
                            <tr>
                                <td data-col-key="teklif_no">
                                    <a href="{{ route('offers.show', $teklif) }}"
                                       style="color:#2563eb;text-decoration:none;">
                                        {{ $teklif->teklif_no }}
                                    </a>
                                </td>
                                <td data-col-key="revize_no">{{ $teklif->revize_no ?? '' }}</td>
                                <td data-col-key="tarih">{{ $teklif->tarih ? $teklif->tarih->format('d.m.Y') : '' }}</td>
                                <td data-col-key="gecerlilik_tarihi">{{ $teklif->gecerlilik_tarihi ? \Carbon\Carbon::parse($teklif->gecerlilik_tarihi)->format('d.m.Y') : '' }}</td>
                                <td data-col-key="gecen_sure">
                                    @php
                                        $gecenSure = null;
                                        if ($teklif->tarih) {
                                            $gecenSure = max(0, $teklif->tarih->startOfDay()->diffInDays(now()->startOfDay(), false));
                                        }
                                    @endphp
                                    {{ $gecenSure !== null ? ($gecenSure . ' gün') : '' }}
                                </td>
                                <td data-col-key="teklif_durum">
                                    @php
                                        $d = $teklif->teklif_durum;
                                        $class = 'status-taslak';
                                        if ($d === 'Gönderildi') $class = 'status-gonderildi';
                                        elseif ($d === 'Kabul Edildi') $class = 'status-kabul';
                                        elseif ($d === 'Reddedildi') $class = 'status-reddedildi';
                                        elseif ($d === 'Süresi Doldu') $class = 'status-suresi-doldu';
                                    @endphp
                                    <span class="offers-status-pill {{ $class }}">
                                        {{ $teklif->teklif_durum ?? 'Belirtilmemiş' }}
                                    </span>
                                </td>
                                <td data-col-key="gerceklesme_olasiligi">{{ $teklif->gerceklesme_olasiligi ? ($teklif->gerceklesme_olasiligi . '%') : '' }}</td>
                                <td data-col-key="islem_turu">{{ $teklif->islemTuru->ad ?? '' }}</td>
                                <td data-col-key="proje">{{ $teklif->proje->kod ?? '' }}</td>
                                <td data-col-key="carikod">{{ $teklif->carikod }}</td>
                                <td data-col-key="cariaciklama">{{ $teklif->cariaciklama }}</td>
                                <td data-col-key="onay_durum">{{ $teklif->onay_durum }}</td>
                                <td data-col-key="onay_tarihi">{{ $teklif->onay_tarihi ? \Carbon\Carbon::parse($teklif->onay_tarihi)->format('d.m.Y') : '' }}</td>
                                <td data-col-key="hazirlayan">{{ $teklif->hazirlayan }}</td>
                                <td data-col-key="teklif_doviz">{{ $teklif->teklif_doviz ?? 'TL' }}</td>
                                <td data-col-key="teklif_kur" style="text-align:right;">{{ number_format((float) ($teklif->teklif_kur ?? 1), 4, ',', '.') }}</td>
                                <td data-col-key="alt_toplam" style="text-align:right;">{{ number_format((float) ($teklif->toplam ?? 0), 2, ',', '.') }}</td>
                                <td data-col-key="iskonto_tutar" style="text-align:right;">{{ number_format((float) ($teklif->iskonto_tutar ?? 0), 2, ',', '.') }}</td>
                                <td data-col-key="kdv" style="text-align:right;">{{ number_format((float) ($teklif->kdv ?? 0), 2, ',', '.') }}</td>
                                <td data-col-key="genel_toplam" style="text-align:right;">{{ number_format((float) ($teklif->genel_toplam ?? 0), 2, ',', '.') }}</td>
                                <td data-col-key="toplam_tutar">
                                    @php
                                        $kur = (float) ($teklif->teklif_kur ?? 1);
                                        $dovizTutar = $kur > 0 ? ((float) ($teklif->genel_toplam ?? 0) / $kur) : (float) ($teklif->genel_toplam ?? 0);
                                    @endphp
                                    {{ number_format($dovizTutar, 2, ',', '.') }} {{ $teklif->teklif_doviz ?? 'TL' }}
                                </td>
                                <td data-col-key="aciklama">{{ $teklif->aciklama }}</td>
                                <td data-col-key="islem" style="text-align:right;">
                                    <x-edit-icon :href="route('offers.edit', $teklif)" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="23" style="text-align: center; padding: 16px;">Kayıtlı teklif bulunamadı.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 16px;">
                    {{ $teklifler->links() }}
                </div>
            </div>
        </section>
    </main>
</div>

<div id="offerListSettingsModal" class="modal-overlay">
    <div class="modal" style="max-width: 520px;">
        <div class="modal-header">
            <div class="modal-title">Tablo Düzenle</div>
            <div style="display:flex; align-items:center; gap:0.5rem;">
                <button type="button" class="btn btn-save" id="offerListSettingsSave">Kaydet</button>
                <button type="button" class="offers-filter-reset" id="offerListSettingsClose" style="padding:0.35rem 0.65rem;">✕</button>
            </div>
        </div>
        <div class="modal-body">
            <label style="display:flex; align-items:center; gap:0.5rem; font-size:0.85rem; padding-bottom:0.5rem; border-bottom:1px solid #e5e7eb; margin-bottom:0.5rem;">
                <input type="checkbox" id="offerListSettingsToggleAll">
                <span>Hepsini Seç/Kaldır</span>
            </label>
            <div id="offerListSettingsList"></div>
        </div>
    </div>
</div>

<script src="{{ asset('js/dashboard.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var btn = document.getElementById('btnOfferListSettings');
        var table = document.getElementById('offersListTable');
        var modal = document.getElementById('offerListSettingsModal');
        var closeBtn = document.getElementById('offerListSettingsClose');
        var listEl = document.getElementById('offerListSettingsList');
        var toggleAllEl = document.getElementById('offerListSettingsToggleAll');
        var saveBtn = document.getElementById('offerListSettingsSave');

        var columns = null;
        var draggingEl = null;
        var currentSortKey = null;
        var currentSortDir = 'asc';

        function getCsrfToken() {
            return document.querySelector('meta[name=\"csrf-token\"]')?.getAttribute('content') || '';
        }

        function parseDateTr(text) {
            var s = (text || '').trim();
            var m = s.match(/^(\d{1,2})\.(\d{1,2})\.(\d{4})$/);
            if (!m) return null;
            var d = parseInt(m[1], 10);
            var mo = parseInt(m[2], 10) - 1;
            var y = parseInt(m[3], 10);
            var dt = new Date(y, mo, d);
            if (Number.isNaN(dt.getTime())) return null;
            return dt.getTime();
        }

        function parseNumberTr(text) {
            var s = (text || '').trim();
            if (!s) return null;
            s = s.replace(/\s/g, '');
            s = s.replace(/[^0-9,.-]/g, '');
            if (!s) return null;
            if (s.indexOf(',') >= 0 && s.lastIndexOf(',') > s.lastIndexOf('.')) {
                s = s.replace(/\./g, '').replace(',', '.');
            } else {
                s = s.replace(/,/g, '');
            }
            var n = parseFloat(s);
            return Number.isFinite(n) ? n : null;
        }

        function compareValues(a, b) {
            if (a === null && b === null) return 0;
            if (a === null) return -1;
            if (b === null) return 1;
            if (typeof a === 'number' && typeof b === 'number') return a - b;
            return String(a).localeCompare(String(b), 'tr', { sensitivity: 'base' });
        }

        function getCellValue(tr, key) {
            var td = tr.querySelector('td[data-col-key=\"' + key + '\"]');
            var text = td ? (td.textContent || '').trim() : '';

            if (key === 'tarih' || key === 'gecerlilik_tarihi' || key === 'onay_tarihi') {
                return parseDateTr(text);
            }
            if (key === 'gecen_sure') {
                var intVal = parseInt(String(text).replace(/[^0-9-]/g, ''), 10);
                return Number.isFinite(intVal) ? intVal : null;
            }
            if (key === 'teklif_no' || key === 'revize_no' || key === 'gerceklesme_olasiligi' || key === 'teklif_kur' || key === 'alt_toplam' || key === 'iskonto_tutar' || key === 'kdv' || key === 'genel_toplam' || key === 'toplam_tutar') {
                return parseNumberTr(text);
            }
            return text;
        }

        function setSortableHeaders() {
            if (!table) return;
            var ths = table.querySelectorAll('thead th[data-col-key]');
            Array.prototype.forEach.call(ths, function (th) {
                var key = th.getAttribute('data-col-key');
                if (!key || key === 'islem') return;
                th.classList.add('sortable');
            });
        }

        function updateSortIndicator() {
            if (!table) return;
            var ths = table.querySelectorAll('thead th.sortable');
            Array.prototype.forEach.call(ths, function (th) {
                th.classList.remove('sort-asc', 'sort-desc');
                var key = th.getAttribute('data-col-key');
                if (key && key === currentSortKey) {
                    th.classList.add(currentSortDir === 'desc' ? 'sort-desc' : 'sort-asc');
                }
            });
        }

        function sortTableByKey(key, dir) {
            if (!table) return;
            var tbody = table.querySelector('tbody');
            if (!tbody) return;

            var rows = Array.prototype.slice.call(tbody.querySelectorAll('tr'));
            var sortableRows = rows.filter(function (tr) {
                return !!tr.querySelector('td[data-col-key]');
            });
            if (!sortableRows.length) return;

            var mapped = sortableRows.map(function (tr, idx) {
                return { tr: tr, idx: idx, val: getCellValue(tr, key) };
            });

            mapped.sort(function (a, b) {
                var cmp = compareValues(a.val, b.val);
                if (cmp === 0) return a.idx - b.idx;
                return (dir === 'desc' ? -cmp : cmp);
            });

            mapped.forEach(function (m) { tbody.appendChild(m.tr); });
        }

        function normalizeColumns(cols) {
            cols = Array.isArray(cols) ? cols.slice() : [];
            cols = cols.filter(function (c) { return c && c.key; });
            cols.sort(function (a, b) {
                return ((parseInt(a.sirano || 0, 10) || 0) - (parseInt(b.sirano || 0, 10) || 0)) || String(a.key).localeCompare(String(b.key));
            });
            return cols.map(function (c, idx) {
                return { key: String(c.key), label: String(c.label || c.key), durum: !!c.durum, sirano: idx + 1 };
            });
        }

        function fetchColumns() {
            return fetch(@json(route('offer-list-columns.index')), { headers: { 'Accept': 'application/json' } })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (!data || !data.ok) throw new Error('load_failed');
                    columns = normalizeColumns(data.columns || []);
                    return columns;
                });
        }

        function applyColumns() {
            if (!table || !columns) return;
            var headerRow = table.querySelector('thead tr');
            if (!headerRow) return;

            columns.forEach(function (col) {
                var th = headerRow.querySelector('th[data-col-key=\"' + col.key + '\"]');
                if (th) headerRow.appendChild(th);
            });

            var rows = table.querySelectorAll('tbody tr');
            Array.prototype.forEach.call(rows, function (tr) {
                columns.forEach(function (col) {
                    var td = tr.querySelector('td[data-col-key=\"' + col.key + '\"]');
                    if (td) tr.appendChild(td);
                });
            });

            columns.forEach(function (col) {
                var th = headerRow.querySelector('th[data-col-key=\"' + col.key + '\"]');
                if (th) th.style.display = col.durum ? '' : 'none';
            });
            Array.prototype.forEach.call(rows, function (tr) {
                columns.forEach(function (col) {
                    var td = tr.querySelector('td[data-col-key=\"' + col.key + '\"]');
                    if (td) td.style.display = col.durum ? '' : 'none';
                });
            });

            setSortableHeaders();
            updateSortIndicator();
        }

        function openModal() {
            if (modal) modal.style.display = 'flex';
        }

        function closeModal() {
            if (modal) modal.style.display = 'none';
        }

        function getItems() {
            if (!listEl) return [];
            return Array.prototype.slice.call(listEl.querySelectorAll('.offer-line-setting-item'));
        }

        function getCheckboxes() {
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

        function syncToggleAll() {
            if (!toggleAllEl) return;
            var cbs = getCheckboxes();
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
            if (!listEl || !columns) return;
            listEl.innerHTML = '';

            columns.forEach(function (col, idx) {
                var item = document.createElement('div');
                item.className = 'offer-line-setting-item';
                item.draggable = true;
                item.setAttribute('data-key', col.key);

                var no = document.createElement('div');
                no.className = 'offer-line-setting-no';
                no.textContent = String(idx + 1);

                var handle = document.createElement('div');
                handle.className = 'offer-line-setting-handle';
                handle.textContent = '≡';

                var cb = document.createElement('input');
                cb.type = 'checkbox';
                cb.checked = !!col.durum;
                cb.addEventListener('change', syncToggleAll);

                var label = document.createElement('div');
                label.className = 'offer-line-setting-label';
                label.textContent = col.label || col.key;

                item.appendChild(no);
                item.appendChild(handle);
                item.appendChild(cb);
                item.appendChild(label);
                listEl.appendChild(item);
            });

            renumberItems();
            syncToggleAll();
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
                } catch (err) {}
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
                if (!afterEl) listEl.appendChild(draggingEl);
                else if (afterEl !== draggingEl) listEl.insertBefore(draggingEl, afterEl);
                renumberItems();
            });
        }

        attachDnD();

        if (toggleAllEl) {
            toggleAllEl.addEventListener('change', function () {
                var checked = !!toggleAllEl.checked;
                getCheckboxes().forEach(function (cb) { cb.checked = checked; });
                syncToggleAll();
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

                fetch(@json(route('offer-list-columns.store')), {
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
                        columns = normalizeColumns(data.columns || []);
                        applyColumns();
                        closeModal();
                    })
                    .catch(function () {
                        alert('Tablo ayarları kaydedilemedi.');
                    })
                    .finally(function () {
                        saveBtn.disabled = false;
                        saveBtn.style.opacity = '1';
                    });
            });
        }

        if (btn) {
            btn.addEventListener('click', function () {
                fetchColumns()
                    .then(function () {
                        applyColumns();
                        buildList();
                        openModal();
                    })
                    .catch(function () {
                        alert('Tablo ayarları yüklenemedi.');
                    });
            });
        }

        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (modal) {
            modal.addEventListener('click', function (e) {
                if (e.target === modal) closeModal();
            });
        }

        if (table) {
            var thead = table.querySelector('thead');
            if (thead) {
                thead.addEventListener('click', function (e) {
                    var th = e.target && e.target.closest ? e.target.closest('th[data-col-key]') : null;
                    if (!th) return;
                    if (th.style.display === 'none') return;
                    var key = th.getAttribute('data-col-key');
                    if (!key || key === 'islem') return;

                    if (currentSortKey === key) currentSortDir = (currentSortDir === 'asc') ? 'desc' : 'asc';
                    else {
                        currentSortKey = key;
                        currentSortDir = 'asc';
                    }

                    updateSortIndicator();
                    sortTableByKey(key, currentSortDir);
                });
            }
            setSortableHeaders();
        }

        fetchColumns().then(function () { applyColumns(); }).catch(function () { });
    });
</script>
</body>
</html>
