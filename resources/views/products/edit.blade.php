<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Düzenle - NomaEnerji</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
<div class="dashboard-container">
    @include('partials.sidebar', ['active' => 'products'])

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
                        <path d="M15 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                Ürün Düzenle
            </div>
            <div class="user-profile" style="display:flex;align-items:center;gap:1rem;">
                <div class="user-avatar">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                    @csrf
                    <button type="submit" title="Çıkış Yap" style="background:none;border:none;color:#94a3b8;cursor:pointer;padding:0.5rem;display:flex;align-items:center;justify-content:center;transition:color 0.2s;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </form>
            </div>
        </header>

        <section class="content-section">
            <div class="form-page-card">
                <form action="{{ route('products.update', $product) }}" method="POST" class="form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="kod">Kod</label>
                            <input type="text" id="kod" name="kod" value="{{ old('kod', $product->kod) }}" required>
                            @error('kod')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="aciklama">İsim / Açıklama</label>
                            <input type="text" id="aciklama" name="aciklama" value="{{ old('aciklama', $product->aciklama) }}" required>
                            @error('aciklama')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="kategori_id">Kategori</label>
                            <select id="kategori_id" name="kategori_id">
                                <option value="">Kategori seçin</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ (string)old('kategori_id', $product->kategori_id) === (string)$category->id ? 'selected' : '' }}>
                                        {{ $category->ad }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="urun_alt_grup_id">Alt Grup</label>
                                <select id="urun_alt_grup_id" name="urun_alt_grup_id">
                                    <option value="">Alt grup seçin</option>
                                </select>
                                @error('urun_alt_grup_id')<div class="form-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="urun_detay_grup_id">Detay Grup</label>
                                <select id="urun_detay_grup_id" name="urun_detay_grup_id">
                                    <option value="">Detay grup seçin</option>
                                </select>
                                @error('urun_detay_grup_id')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="satis_fiyat">Satış Fiyatı</label>
                                <input type="text" id="satis_fiyat" name="satis_fiyat" value="{{ old('satis_fiyat', $product->satis_fiyat) }}" required>
                                @error('satis_fiyat')<div class="form-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="kdv_oran">KDV Oranı (%)</label>
                                <input type="text" id="kdv_oran" name="kdv_oran" value="{{ old('kdv_oran', $product->kdv_oran) }}" required>
                                @error('kdv_oran')<div class="form-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group" style="display:flex;align-items:center;gap:0.5rem;margin-top:1.6rem;">
                                <label for="pasif">Pasif</label>
                                <input type="checkbox" id="pasif" name="pasif" value="1" {{ old('pasif', $product->pasif) ? 'checked' : '' }}>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="prm1">Prm1</label>
                                <input type="text" id="prm1" name="prm1" value="{{ old('prm1', $product->prm1) }}">
                                @error('prm1')<div class="form-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="prm2">Prm2</label>
                                <input type="text" id="prm2" name="prm2" value="{{ old('prm2', $product->prm2) }}">
                                @error('prm2')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="prm3">Prm3</label>
                                <input type="text" id="prm3" name="prm3" value="{{ old('prm3', $product->prm3) }}">
                                @error('prm3')<div class="form-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="prm4">Prm4</label>
                                <input type="text" id="prm4" name="prm4" value="{{ old('prm4', $product->prm4) }}">
                                @error('prm4')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fatura_kodu">Fatura Kodu</label>
                            <input type="text" id="fatura_kodu" name="fatura_kodu" value="{{ old('fatura_kodu', $product->fatura_kodu) }}">
                            @error('fatura_kodu')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="resim">Resim</label>
                            <div id="imageDropzoneEdit" style="border:1px dashed #d1d5db;border-radius:12px;padding:1rem;display:flex;align-items:center;justify-content:center;flex-direction:column;cursor:pointer;text-align:center;color:#6b7280;">
                                @if($product->resim_yolu)
                                    <img src="{{ asset($product->resim_yolu) }}" alt="{{ $product->kod }}" style="max-width:120px;max-height:120px;border-radius:12px;margin-bottom:0.5rem;object-fit:cover;">
                                @endif
                                <span style="font-size:0.9rem;margin-bottom:0.25rem;">Yeni resim için çift tıklayın</span>
                                <span style="font-size:0.8rem;">veya dosyayı bu alana sürükleyip bırakın</span>
                            </div>
                            <input type="file" id="resim" name="resim" accept="image/*" style="display:none;">
                            @error('resim')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div style="margin-top:1.5rem;display:flex;justify-content:flex-end;gap:0.75rem;">
                        <a href="{{ route('products.index') }}" class="form-header-btn cancel" style="text-decoration:none;">İptal</a>
                        <button type="submit" class="form-header-btn save">Güncelle</button>
                    </div>
                </form>
            </div>
        </section>
    </main>
</div>
<script src="{{ asset('js/dashboard.js') }}"></script>
<script>
    (function () {
        var groupSelect = document.getElementById('kategori_id');
        var subGroupSelect = document.getElementById('urun_alt_grup_id');
        var detailGroupSelect = document.getElementById('urun_detay_grup_id');

        if (!groupSelect || !subGroupSelect || !detailGroupSelect) {
            return;
        }

        var subGroupsByGroup = @json($subGroupsByGroup ?? []);
        var detailGroupsBySubGroup = @json($detailGroupsBySubGroup ?? []);

        var selectedSubGroupId = @json((string)old('urun_alt_grup_id', $product->urun_alt_grup_id));
        var selectedDetailGroupId = @json((string)old('urun_detay_grup_id', $product->urun_detay_grup_id));

        function setOptions(selectEl, items, placeholder) {
            selectEl.innerHTML = '';

            var placeholderOpt = document.createElement('option');
            placeholderOpt.value = '';
            placeholderOpt.textContent = placeholder;
            selectEl.appendChild(placeholderOpt);

            (items || []).forEach(function (it) {
                var opt = document.createElement('option');
                opt.value = String(it.id);
                opt.textContent = it.ad;
                selectEl.appendChild(opt);
            });
        }

        function hasId(items, id) {
            return (items || []).some(function (it) {
                return String(it.id) === String(id);
            });
        }

        function refreshDetailGroups() {
            var subGroupId = subGroupSelect.value || '';
            var details = detailGroupsBySubGroup[subGroupId] || [];

            setOptions(detailGroupSelect, details, 'Detay grup seçin');

            if (selectedDetailGroupId && hasId(details, selectedDetailGroupId)) {
                detailGroupSelect.value = String(selectedDetailGroupId);
            } else {
                detailGroupSelect.value = '';
            }

            selectedDetailGroupId = '';
        }

        function refreshSubGroups() {
            var groupId = groupSelect.value || '';
            var subs = subGroupsByGroup[groupId] || [];

            setOptions(subGroupSelect, subs, 'Alt grup seçin');

            if (selectedSubGroupId && hasId(subs, selectedSubGroupId)) {
                subGroupSelect.value = String(selectedSubGroupId);
            } else {
                subGroupSelect.value = '';
            }

            selectedSubGroupId = '';
            refreshDetailGroups();
        }

        groupSelect.addEventListener('change', function () {
            selectedSubGroupId = '';
            selectedDetailGroupId = '';
            refreshSubGroups();
        });

        subGroupSelect.addEventListener('change', function () {
            selectedDetailGroupId = '';
            refreshDetailGroups();
        });

        refreshSubGroups();
    })();

    (function () {
        var dropzone = document.getElementById('imageDropzoneEdit');
        var fileInput = document.getElementById('resim');

        if (!dropzone || !fileInput) {
            return;
        }

        function openFileDialog() {
            fileInput.click();
        }

        dropzone.ondblclick = function () {
            openFileDialog();
        };

        dropzone.addEventListener('dragover', function (e) {
            e.preventDefault();
            e.stopPropagation();
            dropzone.style.backgroundColor = '#f9fafb';
        });

        dropzone.addEventListener('dragleave', function (e) {
            e.preventDefault();
            e.stopPropagation();
            dropzone.style.backgroundColor = 'transparent';
        });

        dropzone.addEventListener('drop', function (e) {
            e.preventDefault();
            e.stopPropagation();
            dropzone.style.backgroundColor = 'transparent';

            if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0]) {
                fileInput.files = e.dataTransfer.files;
            }
        });
    })();
</script>
</body>
</html>
