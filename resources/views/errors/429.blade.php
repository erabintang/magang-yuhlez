<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>429 - Terlalu Banyak Permintaan | YUHLEZ</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,600,700,800" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-zinc-50 flex items-center justify-center font-sans antialiased">
    <div class="text-center px-6">
        <div class="mb-6">
            <img src="{{ asset('brand/yuhlez-logo.png') }}" alt="YUHLEZ" class="h-8 mx-auto" />
        </div>
        <h1 class="text-7xl font-extrabold text-zinc-900 mb-4">429</h1>
        <h2 class="text-2xl font-bold text-zinc-800 mb-3">Terlalu Banyak Permintaan</h2>
        <p class="text-zinc-500 mb-8 max-w-md mx-auto">Anda melakukan terlalu banyak permintaan dalam waktu singkat. Silakan tunggu beberapa saat sebelum mencoba lagi.</p>
        <a href="{{ route('home') }}" class="rounded-xl bg-yellow-400 px-6 py-3 text-sm font-semibold text-zinc-950 transition-colors hover:bg-yellow-300">Kembali ke Beranda</a>
    </div>
</body>
</html>
