<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firma Düzenle - NomaEnerji</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
<div class="dashboard-container">
    @include('partials.sidebar', ['active' => 'firms'])

    <main class="main-content">
        <header class="main-header form-header">
            <div class="form-header-left" style="display:flex;align-items:center;gap:0.75rem;">
                <button class="desktop-sidebar-toggle" id="desktopSidebarToggle">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div>
                    <h1 class="page-title">Firma Düzenle</h1>
                    <p class="page-subtitle">{{ $firm->carikod }} - {{ $firm->cariaciklama }}</p>
                </div>
            </div>
            <div class="form-header-actions">
                <a href="{{ route('firms.index') }}" class="form-header-btn cancel">İptal</a>
                <button type="submit" form="firmForm" class="form-header-btn save">Güncelle</button>
            </div>
        </header>

        <section class="content-section">
            <div class="form-page-card">
                <form id="firmForm"
                      action="{{ route('firms.update', $firm) }}"
                      method="POST"
                      class="form form-firm">
                    @csrf
                    @method('PUT')

                    <div class="form-grid">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="carikod">Cari Kod</label>
                                <input type="text" id="carikod" name="carikod"
                                       value="{{ old('carikod', $firm->carikod) }}" required>
                                @error('carikod')<div class="form-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="cari_kategori_id">Kategori</label>
                            <select id="cari_kategori_id" name="cari_kategori_id">
                                <option value="">Kategori seçin</option>
                                @isset($categories)
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ (string)old('cari_kategori_id', $firm->cari_kategori_id) === (string)$category->id ? 'selected' : '' }}>
                                            {{ $category->ad }}
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                            @error('cari_kategori_id')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="cariaciklama">Cari Açıklama</label>
                            <input type="text" id="cariaciklama" name="cariaciklama"
                                   value="{{ old('cariaciklama', $firm->cariaciklama) }}" required>
                            @error('cariaciklama')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="adres1">Adres 1</label>
                            <input type="text" id="adres1" name="adres1"
                                   value="{{ old('adres1', $firm->adres1) }}">
                            @error('adres1')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="adres2">Adres 2</label>
                            <input type="text" id="adres2" name="adres2"
                                   value="{{ old('adres2', $firm->adres2) }}">
                            @error('adres2')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="il">İl</label>
                                <input type="text" id="il" name="il"
                                       value="{{ old('il', $firm->il) }}">
                                @error('il')<div class="form-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="ilce">İlçe</label>
                                <input type="text" id="ilce" name="ilce"
                                       value="{{ old('ilce', $firm->ilce) }}">
                                @error('ilce')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="ulke">Ülke</label>
                                <input type="text" id="ulke" name="ulke"
                                       value="{{ old('ulke', $firm->ulke) }}">
                                @error('ulke')<div class="form-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="telefon">Telefon</label>
                                <input type="text" id="telefon" name="telefon"
                                       value="{{ old('telefon', $firm->telefon) }}">
                                @error('telefon')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="mail">Mail</label>
                                <input type="email" id="mail" name="mail"
                                       value="{{ old('mail', $firm->mail) }}">
                                @error('mail')<div class="form-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="web_sitesi">Web Sitesi</label>
                                <input type="text" id="web_sitesi" name="web_sitesi"
                                       value="{{ old('web_sitesi', $firm->web_sitesi) }}">
                                @error('web_sitesi')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="iskonto1">İskonto 1</label>
                                <input type="text" id="iskonto1" name="iskonto1"
                                       value="{{ old('iskonto1', $firm->iskonto1) }}">
                                @error('iskonto1')<div class="form-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="iskonto2">İskonto 2</label>
                                <input type="text" id="iskonto2" name="iskonto2"
                                       value="{{ old('iskonto2', $firm->iskonto2) }}">
                                @error('iskonto2')<div class="form-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="iskonto3">İskonto 3</label>
                                <input type="text" id="iskonto3" name="iskonto3"
                                       value="{{ old('iskonto3', $firm->iskonto3) }}">
                                @error('iskonto3')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="iskonto4">İskonto 4</label>
                                <input type="text" id="iskonto4" name="iskonto4"
                                       value="{{ old('iskonto4', $firm->iskonto4) }}">
                                @error('iskonto4')<div class="form-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="iskonto5">İskonto 5</label>
                                <input type="text" id="iskonto5" name="iskonto5"
                                       value="{{ old('iskonto5', $firm->iskonto5) }}">
                                @error('iskonto5')<div class="form-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label for="iskonto6">İskonto 6</label>
                                <input type="text" id="iskonto6" name="iskonto6"
                                       value="{{ old('iskonto6', $firm->iskonto6) }}">
                                @error('iskonto6')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <hr style="margin:2rem 0;border:none;border-top:1px solid #e2e8f0;">

                    @php
                        $oldAuthorities = old('authorities');
                        $authorities = $oldAuthorities !== null
                            ? collect($oldAuthorities)->map(function($item){ return (object)$item; })
                            : ($firm->authorities ?? collect());
                    @endphp

                    <div class="form-section">
                        <div class="form-section-header" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.75rem;">
                            <h2 class="section-title" style="font-size:1rem;font-weight:600;">Yetkililer</h2>
                            <button type="button" id="addAuthorityButton" class="form-header-btn save" style="padding:0.35rem 0.9rem;font-size:0.85rem;">Yetkili Ekle</button>
                        </div>

                        <div class="table-wrapper" style="overflow-x:auto;">
                            <table id="authoritiesTable" style="width:100%;border-collapse:collapse;font-size:0.9rem;">
                                <thead>
                                <tr>
                                    <th style="text-align:left;padding:0.5rem;border-bottom:1px solid #e2e8f0;">Ad Soyad</th>
                                    <th style="text-align:left;padding:0.5rem;border-bottom:1px solid #e2e8f0;">Mail</th>
                                    <th style="text-align:left;padding:0.5rem;border-bottom:1px solid #e2e8f0;">Telefon</th>
                                    <th style="text-align:left;padding:0.5rem;border-bottom:1px solid #e2e8f0;">Görev</th>
                                    <th style="text-align:right;padding:0.5rem;border-bottom:1px solid #e2e8f0;">İşlem</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($authorities as $index => $authority)
                                    <tr>
                                        <td style="padding:0.4rem 0.5rem;">
                                            <input type="text"
                                                   name="authorities[{{ $index }}][full_name]"
                                                   value="{{ $authority->full_name ?? '' }}"
                                                   placeholder="Ad Soyad"
                                                   style="width:100%;">
                                        </td>
                                        <td style="padding:0.4rem 0.5rem;">
                                            <input type="email"
                                                   name="authorities[{{ $index }}][email]"
                                                   value="{{ $authority->email ?? '' }}"
                                                   placeholder="mail@ornek.com"
                                                   style="width:100%;">
                                        </td>
                                        <td style="padding:0.4rem 0.5rem;">
                                            <input type="text"
                                                   name="authorities[{{ $index }}][phone]"
                                                   value="{{ $authority->phone ?? '' }}"
                                                   placeholder="0 (5xx) xxx xx xx"
                                                   style="width:100%;">
                                        </td>
                                        <td style="padding:0.4rem 0.5rem;">
                                            <input type="text"
                                                   name="authorities[{{ $index }}][role]"
                                                   value="{{ $authority->role ?? '' }}"
                                                   placeholder="Görev"
                                                   style="width:100%;">
                                        </td>
                                        <td style="padding:0.4rem 0.5rem;text-align:right;">
                                            <button type="button" class="authority-delete-button" style="background:none;border:none;color:#e53e3e;font-size:0.8rem;cursor:pointer;">Sil</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td style="padding:0.4rem 0.5rem;">
                                            <input type="text" name="authorities[0][full_name]" placeholder="Ad Soyad" style="width:100%;">
                                        </td>
                                        <td style="padding:0.4rem 0.5rem;">
                                            <input type="email" name="authorities[0][email]" placeholder="mail@ornek.com" style="width:100%;">
                                        </td>
                                        <td style="padding:0.4rem 0.5rem;">
                                            <input type="text" name="authorities[0][phone]" placeholder="0 (5xx) xxx xx xx" style="width:100%;">
                                        </td>
                                        <td style="padding:0.4rem 0.5rem;">
                                            <input type="text" name="authorities[0][role]" placeholder="Görev" style="width:100%;">
                                        </td>
                                        <td style="padding:0.4rem 0.5rem;text-align:right;">
                                            <button type="button" class="authority-delete-button" style="background:none;border:none;color:#e53e3e;font-size:0.8rem;cursor:pointer;">Sil</button>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main>
</div>
<script src="{{ asset('js/dashboard.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var table = document.getElementById('authoritiesTable');
        var addButton = document.getElementById('addAuthorityButton');

        if (!table || !addButton) {
            return;
        }

        var tbody = table.querySelector('tbody');
        var rowIndex = tbody.querySelectorAll('tr').length;

        function createAuthorityRow(index) {
            var tr = document.createElement('tr');
            tr.innerHTML =
                '<td style="padding:0.4rem 0.5rem;">' +
                '<input type="text" name="authorities[' + index + '][full_name]" placeholder="Ad Soyad" style="width:100%;">' +
                '</td>' +
                '<td style="padding:0.4rem 0.5rem;">' +
                '<input type="email" name="authorities[' + index + '][email]" placeholder="mail@ornek.com" style="width:100%;">' +
                '</td>' +
                '<td style="padding:0.4rem 0.5rem;">' +
                '<input type="text" name="authorities[' + index + '][phone]" placeholder="0 (5xx) xxx xx xx" style="width:100%;">' +
                '</td>' +
                '<td style="padding:0.4rem 0.5rem;">' +
                '<input type="text" name="authorities[' + index + '][role]" placeholder="Görev" style="width:100%;">' +
                '</td>' +
                '<td style="padding:0.4rem 0.5rem;text-align:right;">' +
                '<button type="button" class="authority-delete-button" style="background:none;border:none;color:#e53e3e;font-size:0.8rem;cursor:pointer;">Sil</button>' +
                '</td>';
            return tr;
        }

        addButton.addEventListener('click', function () {
            var newRow = createAuthorityRow(rowIndex++);
            tbody.appendChild(newRow);
        });

        tbody.addEventListener('click', function (event) {
            var target = event.target;
            if (target.classList.contains('authority-delete-button')) {
                var row = target.closest('tr');
                if (row && confirm('Bu yetkiliyi silmek istiyor musunuz?')) {
                    row.remove();
                }
            }
        });
    });
</script>
</body>
</html>
