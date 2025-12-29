<div id="depotModal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Depo Seç</div>
            <button type="button" class="small-btn" data-modal-close="depotModal">✕</button>
        </div>
        <div class="modal-body">
            <table class="modal-table">
                <thead>
                <tr>
                    <th>Depo Kodu</th>
                    <th>Pasif</th>
                </tr>
                </thead>
                <tbody>
                @foreach($depots as $depot)
                    <tr class="depot-row"
                        data-id="{{ $depot->id }}"
                        data-kod="{{ $depot->kod }}">
                        <td>{{ $depot->kod }}</td>
                        <td>{{ $depot->pasif ? 'Evet' : 'Hayır' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="modal-actions">
            <button type="button"
                    data-modal-close="depotModal"
                    style="background:none;border:none;padding:0;color:#dc2626;font-weight:700;cursor:pointer;">
                Kapat
            </button>
        </div>
    </div>
</div>
