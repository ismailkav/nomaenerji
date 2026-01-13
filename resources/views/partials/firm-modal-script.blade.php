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

                if (carikodInput) carikodInput.value = carikod;
                if (cariaciklamaInput) cariaciklamaInput.value = cariaciklama;
                if (firmaKodLabel) firmaKodLabel.textContent = carikod;
                if (firmaAciklamaLabel) firmaAciklamaLabel.textContent = cariaciklama;

                closeModal(firmModal);
            });
        });
    });
</script>
