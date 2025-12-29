<script>
    document.addEventListener('DOMContentLoaded', function () {
        var firmModal = document.getElementById('firmModal');
        var btnCariSearch = document.getElementById('btnCariSearch');

        var carikodInput = document.getElementById('carikod');
        var cariaciklamaInput = document.getElementById('cariaciklama');
        var firmaKodLabel = document.getElementById('firma_kod_label');
        var firmaAciklamaLabel = document.getElementById('firma_aciklama_label');

        function openModal(modal) {
            if (!modal) return;
            modal.style.display = 'flex';
        }

        function closeModal(modal) {
            if (!modal) return;
            modal.style.display = 'none';
        }

        document.querySelectorAll('[data-modal-close="firmModal"]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                closeModal(firmModal);
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
        }

        document.querySelectorAll('.firm-row').forEach(function (row) {
            row.addEventListener('click', function () {
                var carikod = this.dataset.carikod || '';
                var cariaciklama = this.dataset.cariaciklama || '';

                if (carikodInput) carikodInput.value = carikod;
                if (cariaciklamaInput) cariaciklamaInput.value = cariaciklama;
                if (firmaKodLabel) firmaKodLabel.textContent = carikod;
                if (firmaAciklamaLabel) firmaAciklamaLabel.textContent = cariaciklama;

                closeModal(firmModal);
            });
        });
    });
</script>

