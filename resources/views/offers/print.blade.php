<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Teklif Formu - {{ $teklif->teklif_no }}</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 16px 24px 40px;
            color: #111827;
        }
        .print-container {
            max-width: 900px;
            margin: 0 auto;
        }
        .print-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 18px;
        }
        .print-left {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .print-logo {
            margin-bottom: 10px;
        }
        .print-logo img {
            max-height: 48px;
        }
        .header-block {
            font-size: 11px;
        }
        .header-label {
            font-weight: 600;
            color: #374151;
        }
        .header-value {
            margin-left: 4px;
        }
        .print-right {
            text-align: left;
        }
        .print-right-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .header-row {
            margin-bottom: 2px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        th, td {
            border: 1px solid #e5e7eb;
            padding: 4px 6px;
        }
        thead {
            background: #f3f4f6;
        }
        th {
            font-weight: 600;
        }
        .text-right {
            text-align: right;
        }
        .summary {
            margin-top: 8px;
            display: flex;
            justify-content: flex-end;
        }
        .summary table {
            width: auto;
        }
        .summary td:first-child {
            padding-right: 16px;
        }
        .summary td {
            border: 1px solid #e5e7eb;
        }
        .summary strong {
            font-weight: 700;
        }
        .print-footer {
            position: fixed;
            bottom: 8px;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 9pt;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 4px;
        }
        .print-footer-line {
            margin: 0;
        }
        .print-footer-line + .print-footer-line {
            margin-top: 2px;
        }
        @media print {
            body {
                padding: 12px 24px 32px;
            }
        }
    </style>
</head>
<body onload="window.print()">
<div class="print-container">
    <div class="print-header">
        <div class="print-left">
            <div class="print-logo">
                <img src="{{ asset('images/logo.png') }}" alt="Noma Energy Logosu">
            </div>
            <div class="header-block">
                <div class="header-row">
                    <span class="header-label">Firma Ünvanı:</span>
                    <span class="header-value">{{ $teklif->cariaciklama }}</span>
                </div>
                <div class="header-row">
                    <span class="header-label">Yetkili:</span>
                    <span class="header-value">{{ $teklif->yetkili_personel }}</span>
                </div>
                @if(isset($firm) && $firm)
                    @if($firm->adres1)
                        <div class="header-row">
                            <span class="header-label">Adres:</span>
                            <span class="header-value">{{ $firm->adres1 }}</span>
                        </div>
                    @endif
                    @if($firm->adres2)
                        <div class="header-row">
                            <span class="header-label">&nbsp;</span>
                            <span class="header-value">{{ $firm->adres2 }}</span>
                        </div>
                    @endif
                    @if($firm->il || $firm->ilce)
                        <div class="header-row">
                            <span class="header-label">İl / İlçe:</span>
                            <span class="header-value">
                                {{ $firm->il }}
                                @if($firm->il && $firm->ilce)
                                    /
                                @endif
                                {{ $firm->ilce }}
                            </span>
                        </div>
                    @endif
                @endif
            </div>
        </div>
        <div class="print-right">
            <div class="print-right-title">Teklif Formu</div>
            <div class="header-block">
                <div class="header-row">
                    <span class="header-label">Teklif Tarihi:</span>
                    <span class="header-value">{{ optional($teklif->tarih)->format('d.m.Y') }}</span>
                </div>
                <div class="header-row">
                    <span class="header-label">Geçerlilik Tarihi:</span>
                    <span class="header-value">{{ optional($teklif->gecerlilik_tarihi)->format('d.m.Y') }}</span>
                </div>
                <div class="header-row">
                    <span class="header-label">Teklif No:</span>
                    <span class="header-value">{{ $teklif->teklif_no }}</span>
                </div>
            </div>
        </div>
    </div>

    <table>
        <thead>
        <tr>
            <th>Satır Açıklama</th>
            <th class="text-right">Miktar</th>
            <th>Birim</th>
            <th class="text-right">Birim Fiyat</th>
            <th class="text-right">İsk. Toplam</th>
            <th class="text-right">KDV%</th>
            <th class="text-right">KDV Tutar</th>
            <th class="text-right">Satır Toplam</th>
        </tr>
        </thead>
        <tbody>
        @forelse($teklif->detaylar as $satir)
            <tr>
                <td>{{ $satir->satir_aciklama }}</td>
                <td class="text-right">{{ number_format($satir->miktar, 3, ',', '.') }}</td>
                <td>{{ $satir->birim }}</td>
                <td class="text-right">{{ number_format($satir->birim_fiyat, 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($satir->iskonto_tutar, 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($satir->kdv_orani, 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($satir->kdv_tutar, 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($satir->satir_toplam, 2, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" style="text-align:center; padding:8px;">Bu teklife ait satır bulunmuyor.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="summary">
        <table>
            <tr>
                <td>Toplam:</td>
                <td class="text-right">{{ number_format($teklif->toplam, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>İskonto Tutar:</td>
                <td class="text-right">{{ number_format($teklif->iskonto_tutar, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>KDV:</td>
                <td class="text-right">{{ number_format($teklif->kdv, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Genel Toplam:</strong></td>
                <td class="text-right"><strong>{{ number_format($teklif->genel_toplam, 2, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>

    @if($teklif->aciklama)
        <div style="margin-top: 12px;">
            <span class="header-label">Açıklama:</span>
            <span class="header-value">{{ $teklif->aciklama }}</span>
        </div>
    @endif

    <div class="print-footer">
        <p class="print-footer-line">Emirgan, Hekim Tahsin Sk. No:9/A, 34337 Sarıyer/İstanbul</p>
        <p class="print-footer-line">Telefon: (0212) 211 11 29&nbsp;&nbsp;&nbsp;&nbsp;Web: www.nomaenergy.com</p>
    </div>
</div>
</body>
</html>

