<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sunset | Login</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand: #6474ff;
            --brand-hover: #4f5ef5;
            --page-bg: #f6f8ff;
            --card-bg: #ffffff;
            --text: #0f172a;
            --muted: #475569;
            --border: #e5e7eb;
            --input-bg: #eaf1ff;
            --input-border: #d6e0ff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 48px 16px;
            background:
                radial-gradient(900px 600px at 50% -200px, rgba(100, 116, 255, 0.20), transparent 55%),
                linear-gradient(180deg, #ffffff 0%, var(--page-bg) 100%);
        }

        .auth-wrap {
            width: 100%;
            max-width: 760px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 22px;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .brand-logo {
            display: block;
            width: 100%;
            max-width: 520px;
            height: auto;
        }

        .login-card {
            width: 100%;
            max-width: 700px;
            background: var(--card-bg);
            border-radius: 18px;
            border: 1px solid rgba(100, 116, 255, 0.28);
            box-shadow:
                0 30px 70px rgba(15, 23, 42, 0.15),
                0 2px 10px rgba(15, 23, 42, 0.06);
            overflow: hidden;
        }

        .login-card-header {
            padding: 26px 34px 18px;
            border-top: 4px solid var(--brand);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }

        .login-card-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .login-card-body {
            padding: 28px 34px 34px;
        }

        .form-group {
            margin-bottom: 26px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--text);
        }

        .form-control {
            width: 100%;
            padding: 18px 18px;
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 10px;
            color: var(--text);
            font-size: 1.125rem;
            transition: all 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: rgba(100, 116, 255, 0.9);
            box-shadow: 0 0 0 4px rgba(100, 116, 255, 0.20);
            background: #f3f6ff;
        }

        .form-control::placeholder {
            color: rgba(71, 85, 105, 0.7);
        }

        .btn-primary {
            width: 100%;
            padding: 18px 18px;
            margin-top: 10px;
            background: var(--brand);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.125rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.08s ease, background-color 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 12px 28px rgba(100, 116, 255, 0.35);
        }

        .btn-primary:hover {
            background-color: var(--brand-hover);
            box-shadow: 0 14px 32px rgba(100, 116, 255, 0.40);
        }

        .btn-primary:active {
            transform: translateY(1px);
        }

        .alert-danger {
            background: #fff1f2;
            border: 1px solid #fecdd3;
            color: #9f1239;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 0.95rem;
            margin-bottom: 18px;
        }

        .alert-danger ul {
            margin-left: 18px;
        }

        @media (max-width: 520px) {
            body { padding: 28px 14px; }
            .login-card-header { padding: 22px 18px 14px; }
            .login-card-body { padding: 20px 18px 22px; }
            .brand-logo { max-width: 380px; }
        }
    </style>
</head>
<body>
    <main class="auth-wrap">
        <img src="{{ asset('images/sunset_logo.png') }}" alt="Sunset" class="brand-logo">

        <section class="login-card" aria-label="Login form">
            <header class="login-card-header">
                <h1>Login</h1>
            </header>

            <div class="login-card-body">
                @if ($errors->any())
                    <div class="alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <div class="form-group">
                        <label for="mail">Email</label>
                        <input
                            type="email"
                            id="mail"
                            name="mail"
                            class="form-control"
                            value="{{ old('mail') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="admin@gmail.com"
                        >
                    </div>

                    <div class="form-group">
                        <label for="sifre">Password</label>
                        <input
                            type="password"
                            id="sifre"
                            name="sifre"
                            class="form-control"
                            required
                            autocomplete="current-password"
                        >
                    </div>

                    <button type="submit" class="btn-primary">Login</button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
