<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    @include('partials.sidebar', ['active' => 'offers'])

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
                            <a href="{{ route('offers.index') }}" class="offers-filter-reset">Temizle</a>
                        </div>

                        <a href="{{ route('offers.create') }}" class="offers-new-button">
                            Yeni Teklif
                        </a>
                    </div>
                </form>

                <div class="offers-table-wrapper">
                    <table class="offers-table">
                        <thead>
                        <tr>
                            <th>Teklif No</th>
                            <th>Revize No</th>
                            <th>Teklif Tarih</th>
                            <th>Geçen Süre</th>
                            <th>Teklif Durum</th>
                            <th>Gerçekleşme Olasılığı</th>
                            <th>İşlem Türü</th>
                            <th>Proje</th>
                            <th>Cari Kod</th>
                            <th>Cari Açıklama</th>
                            <th>Onay Durum</th>
                            <th>Onay Tarih</th>
                            <th>Hazırlayan</th>
                            <th>Toplam Tutar</th>
                            <th style="text-align:right;">İşlem</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($teklifler as $teklif)
                            <tr>
                                <td>
                                    <a href="{{ route('offers.show', $teklif) }}"
                                       style="color:#2563eb;text-decoration:none;">
                                        {{ $teklif->teklif_no }}
                                    </a>
                                </td>
                                <td>{{ $teklif->revize_no ?? '' }}</td>
                                <td>{{ $teklif->tarih ? $teklif->tarih->format('d.m.Y') : '' }}</td>
                                <td>
                                    @php
                                        $gecenSure = null;
                                        if ($teklif->tarih && $teklif->gecerlilik_tarihi) {
                                            $gecenSure = $teklif->tarih->diffInDays($teklif->gecerlilik_tarihi) + 1;
                                        }
                                    @endphp
                                    {{ $gecenSure !== null ? ($gecenSure . ' gün') : '' }}
                                </td>
                                <td>
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
                                <td>{{ $teklif->gerceklesme_olasiligi ? ($teklif->gerceklesme_olasiligi . '%') : '' }}</td>
                                <td>{{ $teklif->islemTuru->ad ?? '' }}</td>
                                <td>{{ $teklif->proje->kod ?? '' }}</td>
                                <td>{{ $teklif->carikod }}</td>
                                <td>{{ $teklif->cariaciklama }}</td>
                                <td>{{ $teklif->onay_durum }}</td>
                                <td>{{ $teklif->onay_tarihi ? \Carbon\Carbon::parse($teklif->onay_tarihi)->format('d.m.Y') : '' }}</td>
                                <td>{{ $teklif->hazirlayan }}</td>
                                <td>
                                    @php
                                        $kur = (float) ($teklif->teklif_kur ?? 1);
                                        $dovizTutar = $kur > 0 ? ((float) ($teklif->genel_toplam ?? 0) / $kur) : (float) ($teklif->genel_toplam ?? 0);
                                    @endphp
                                    {{ number_format($dovizTutar, 2, ',', '.') }} {{ $teklif->teklif_doviz ?? 'TL' }}
                                </td>
                                <td style="text-align:right;">
                                    <x-edit-icon :href="route('offers.edit', $teklif)" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" style="text-align: center; padding: 16px;">Kayıtlı teklif bulunamadı.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
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
<script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>
