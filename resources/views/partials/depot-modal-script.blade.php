<script>
    document.addEventListener('DOMContentLoaded', function () {
        var depotModal = document.getElementById('depotModal');
        var btnDepotSearch = document.getElementById('btnDepotSearch');

        var depotIdInput = document.getElementById('depo_id');
        var depotKodLabel = document.getElementById('depo_kod_label');

        function openModal(modal) {
            if (!modal) return;
            modal.style.display = 'flex';
        }

        function closeModal(modal) {
            if (!modal) return;
            modal.style.display = 'none';
        }

        document.querySelectorAll('[data-modal-close="depotModal"]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                closeModal(depotModal);
            });
        });

        if (btnDepotSearch && depotModal) {
            btnDepotSearch.addEventListener('click', function () {
                openModal(depotModal);
            });
        }

        if (depotModal) {
            depotModal.addEventListener('click', function (e) {
                if (e.target === depotModal) {
                    closeModal(depotModal);
                }
            });
        }

        document.querySelectorAll('.depot-row').forEach(function (row) {
            row.addEventListener('click', function () {
                var id = this.dataset.id || '';
                var kod = this.dataset.kod || '';

                if (depotIdInput) depotIdInput.value = id;
                if (depotKodLabel) depotKodLabel.textContent = kod;

                closeModal(depotModal);
            });
        });
    });
</script>

