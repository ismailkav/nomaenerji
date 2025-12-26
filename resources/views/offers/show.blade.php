<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teklif Detayı - {{ $teklif->teklif_no }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        .offer-detail-card {
            max-width: 1100px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
            padding: 1.75rem 1.5rem;
        }

        .offer-detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 0.75rem 1.25rem;
            margin-bottom: 1.25rem;
        }

        .offer-detail-item {
            font-size: 0.8rem;
        }

        .offer-detail-label {
            font-weight: 500;
            color: #6b7280;
            display: block;
            margin-bottom: 0.1rem;
        }

        .offer-detail-value {
            color: #111827;
        }

        .offer-lines-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            font-size: 0.8rem;
        }

        .offer-lines-table th,
        .offer-lines-table td {
            border: 1px solid #e5e7eb;
            padding: 0.4rem 0.5rem;
        }

        .offer-lines-table thead {
            background-color: #f3f4f6;
        }

        .offer-summary {
            margin-top: 1rem;
            display: flex;
            justify-content: flex-end;
        }

        .offer-summary-table {
            font-size: 0.8rem;
        }

        .offer-summary-table td {
            padding: 0.25rem 0.5rem;
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
                Teklif Detayı - {{ $teklif->teklif_no }}
            </div>
        </header>

        <section class="content-section" style="padding: 2rem;">
            <div class="offer-detail-card">
                <div class="offer-detail-grid">
                    <div class="offer-detail-item">
                        <span class="offer-detail-label">Cari Kod</span>
                        <span class="offer-detail-value">{{ $teklif->carikod }}</span>
                    </div>
                    <div class="offer-detail-item">
                        <span class="offer-detail-label">Cari Açıklama</span>
                        <span class="offer-detail-value">{{ $teklif->cariaciklama }}</span>
                    </div>
                    <div class="offer-detail-item">
                        <span class="offer-detail-label">Teklif Tarihi</span>
                        <span class="offer-detail-value">{{ $teklif->tarih ? $teklif->tarih->format('d.m.Y') : '' }}</span>
                    </div>
                    <div class="offer-detail-item">
                        <span class="offer-detail-label">Geçerlilik Tarihi</span>
                        <span class="offer-detail-value">{{ $teklif->gecerlilik_tarihi ? $teklif->gecerlilik_tarihi->format('d.m.Y') : '' }}</span>
                    </div>
                    <div class="offer-detail-item">
                        <span class="offer-detail-label">Teklif No</span>
                        <span class="offer-detail-value">{{ $teklif->teklif_no }}</span>
                    </div>
                    <div class="offer-detail-item">
                        <span class="offer-detail-label">Revize No</span>
                        <span class="offer-detail-value">{{ $teklif->revize_no }}</span>
                    </div>
                    <div class="offer-detail-item">
                        <span class="offer-detail-label">Teklif Durum</span>
                        <span class="offer-detail-value">{{ $teklif->teklif_durum }}</span>
                    </div>
                    <div class="offer-detail-item">
                        <span class="offer-detail-label">Onay Durum</span>
                        <span class="offer-detail-value">{{ $teklif->onay_durum }}</span>
                    </div>
                    <div class="offer-detail-item">
                        <span class="offer-detail-label">Onay Tarihi</span>
                        <span class="offer-detail-value">{{ $teklif->onay_tarihi ? $teklif->onay_tarihi->format('d.m.Y') : '' }}</span>
                    </div>
                    <div class="offer-detail-item">
                        <span class="offer-detail-label">Yetkili Personel</span>
                        <span class="offer-detail-value">{{ $teklif->yetkili_personel }}</span>
                    </div>
                    <div class="offer-detail-item">
                        <span class="offer-detail-label">Hazırlayan</span>
                        <span class="offer-detail-value">{{ $teklif->hazirlayan }}</span>
                    </div>
                </div>

                @if($teklif->aciklama)
                    <div class="offer-detail-item" style="margin-bottom: 1rem;">
                        <span class="offer-detail-label">Açıklama</span>
                        <span class="offer-detail-value">{{ $teklif->aciklama }}</span>
                    </div>
                @endif

                <h3 style="font-size: 0.9rem; font-weight: 600; margin-top: 0.5rem;">Teklif Kalemleri</h3>
                <table class="offer-lines-table">
                    <thead>
                    <tr>
                        <th>Satır Açıklama</th>
                        <th>Miktar</th>
                        <th>Birim</th>
                        <th>Birim Fiyat</th>
                        <th>İsk. Toplam</th>
                        <th>KDV%</th>
                        <th>KDV Tutar</th>
                        <th>Satır Toplam</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($teklif->detaylar as $satir)
                        <tr>
                            <td>{{ $satir->satir_aciklama }}</td>
                            <td style="text-align: right;">{{ number_format($satir->miktar, 3, ',', '.') }}</td>
                            <td>{{ $satir->birim }}</td>
                            <td style="text-align: right;">{{ number_format($satir->birim_fiyat, 2, ',', '.') }}</td>
                            <td style="text-align: right;">{{ number_format($satir->iskonto_tutar, 2, ',', '.') }}</td>
                            <td style="text-align: right;">{{ number_format($satir->kdv_orani, 2, ',', '.') }}</td>
                            <td style="text-align: right;">{{ number_format($satir->kdv_tutar, 2, ',', '.') }}</td>
                            <td style="text-align: right;">{{ number_format($satir->satir_toplam, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    @if($teklif->detaylar->isEmpty())
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 12px;">Bu teklife ait satır bulunmuyor.</td>
                        </tr>
                    @endif
                    </tbody>
                </table>

                <div class="offer-summary">
                    <table class="offer-summary-table">
                        <tr>
                            <td>Toplam:</td>
                            <td style="text-align: right;">{{ number_format($teklif->toplam, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>İskonto Tutar:</td>
                            <td style="text-align: right;">{{ number_format($teklif->iskonto_tutar, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>KDV:</td>
                            <td style="text-align: right;">{{ number_format($teklif->kdv, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Genel Toplam:</strong></td>
                            <td style="text-align: right;"><strong>{{ number_format($teklif->genel_toplam, 2, ',', '.') }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </section>
    </main>
</div>
<script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>

