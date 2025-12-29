<div id="firmModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Cari Seç</div>
            <button type="button" class="small-btn" data-modal-close="firmModal">✕</button>
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
                        data-isk6="{{ $firm->iskonto6 ?? 0 }}"
                        data-adres1="{{ $firm->adres1 }}"
                        data-adres2="{{ $firm->adres2 }}"
                        data-il="{{ $firm->il }}"
                        data-ilce="{{ $firm->ilce }}"
                        data-authorities='@json($firm->authorities?->pluck("full_name") ?? [])'>
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
            <button type="button"
                    data-modal-close="firmModal"
                    style="background:none;border:none;padding:0;color:#dc2626;font-weight:700;cursor:pointer;">
                Kapat
            </button>
        </div>
    </div>
</div>
