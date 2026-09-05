<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - Sesi Berakhir | YUHLEZ</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,600,700,800" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="min-h-screen bg-zinc-50 flex items-center justify-center font-sans antialiased">
    <div class="text-center px-6">
        <div class="mb-6">
            <img src="{{ asset('brand/yuhlez-logo.png') }}" alt="YUHLEZ" class="h-8 mx-auto" />
        </div>
        <h1 class="text-7xl font-extrabold text-zinc-900 mb-4">419</h1>
        <h2 class="text-2xl font-bold text-zinc-800 mb-3">Sesi Berakhir</h2>
        <p class="text-zinc-500 mb-8 max-w-md mx-auto">Sesi Anda telah berakhir karena tidak aktif terlalu lama. Silakan masuk kembali.</p>
        <a href="{{ route('login') }}" class="rounded-xl bg-yellow-400 px-6 py-3 text-sm font-semibold text-zinc-950 transition-colors hover:bg-yellow-300">Masuk Kembali</a>
    </div>
</body>
</html>
