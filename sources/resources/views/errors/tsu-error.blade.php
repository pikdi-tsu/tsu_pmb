<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Terjadi Kesalahan' }} - TSU PMB</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --tsu-primary: #1d7a87;
            --tsu-primary-dark: #094b54;
            --tsu-gold: #f8c12a;
            --tsu-bg: #f4fbfc;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        body {
            background-color: var(--tsu-bg);
            color: #334155;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1.5rem;
        }
        .error-card {
            background: #ffffff;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
            max-width: 520px;
            width: 100%;
            padding: 2.5rem 2rem;
            text-align: center;
            border-top: 5px solid var(--tsu-primary);
        }
        .icon-circle {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: #fee2e2;
            color: #ef4444;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 1.5rem;
        }
        .error-code {
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--tsu-primary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }
        .error-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 1rem;
        }
        .error-message {
            font-size: 0.95rem;
            line-height: 1.6;
            color: #64748b;
            margin-bottom: 2rem;
            background: #f8fafc;
            padding: 1rem;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            word-break: break-word;
        }
        .btn-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-primary {
            background-color: var(--tsu-primary);
            color: #ffffff;
            border: 1px solid var(--tsu-primary);
        }
        .btn-primary:hover {
            background-color: var(--tsu-primary-dark);
        }
        .btn-outline {
            background-color: transparent;
            color: #64748b;
            border: 1px solid #cbd5e1;
        }
        .btn-outline:hover {
            background-color: #f1f5f9;
            color: #0f172a;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="icon-circle">
            <i class="fas fa-triangle-exclamation"></i>
        </div>
        <div class="error-code">Status Code: {{ $code ?? 500 }}</div>
        <h1 class="error-title">{{ $title ?? 'Terjadi Kesalahan' }}</h1>
        <div class="error-message">
            {!! $message ?? 'Sistem tidak dapat memproses permintaan Anda.' !!}
        </div>
        <div class="btn-group">
            <a href="{{ route('loginadmin') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Kembali ke Login
            </a>
            <a href="{{ route('rescue') }}" class="btn btn-outline">
                <i class="fas fa-shield-halved"></i> Rescue Mode
            </a>
        </div>
    </div>
</body>
</html>
