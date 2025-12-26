@php
    $roleLabels = $roles;
@endphp
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Kullanıcı - NomaEnerji</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
<div class="dashboard-container">
    @include('partials.sidebar', ['active' => 'users'])

    <main class="main-content">
        <header class="main-header form-header">
            <div class="form-header-left">
                <h1 class="page-title">Yeni Kullanıcı</h1>
                <p class="page-subtitle">Sisteme yeni bir kullanıcı ekleyin.</p>
            </div>
            <div class="form-header-actions">
                <a href="{{ route('users.index') }}" class="form-header-btn cancel">İptal</a>
                <button type="submit" form="userForm" class="form-header-btn save">Kaydet</button>
            </div>
        </header>

        <section class="content-section">
            <div class="form-page-card">
                <form id="userForm" action="{{ route('users.store') }}" method="POST" class="form">
                    @csrf

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="ad">İsim</label>
                            <input type="text" id="ad" name="ad" value="{{ old('ad') }}" required>
                            @error('ad')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="soyad">Soyad</label>
                            <input type="text" id="soyad" name="soyad" value="{{ old('soyad') }}" required>
                            @error('soyad')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="mail">E-posta</label>
                            <input type="email" id="mail" name="mail" value="{{ old('mail') }}" required>
                            @error('mail')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="telefon">Telefon</label>
                            <input type="text" id="telefon" name="telefon" value="{{ old('telefon') }}">
                            @error('telefon')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="role">Rol</label>
                            <select id="role" name="role" required>
                                @foreach($roleLabels as $value => $label)
                                    <option value="{{ $value }}" @selected(old('role') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('role')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <div class="toggle-row">
                                <label class="toggle">
                                    <input type="checkbox" id="aktif" name="aktif" value="1" {{ old('aktif', '1') ? 'checked' : '' }}>
                                    <span class="toggle-slider"></span>
                                </label>
                                <span class="toggle-label">Durum</span>
                            </div>
                            @error('aktif')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="sifre">Şifre</label>
                            <input type="password" id="sifre" name="sifre" required>
                            @error('sifre')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main>
</div>
</body>
</html>

