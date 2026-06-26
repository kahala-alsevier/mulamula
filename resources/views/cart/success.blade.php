<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Diproses - MulaMula</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-[#f3ebd8] min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-8 max-w-md w-full text-center border border-[#f9d0ce] shadow-sm space-y-4">

        <div class="w-16 h-16 bg-[#b6bb79]/20 rounded-full flex items-center justify-center mx-auto text-[#b6bb79]">
            <x-heroicon-o-check-circle class="w-8 h-8" />
        </div>

        <h1 class="text-2xl font-bold text-gray-950">Pemesanan Berhasil dibuat!</h1>
        <p class="text-sm text-gray-600">ID Pesanan Anda: <span class="font-mono font-bold text-gray-900">{{ $order_id }}</span></p>
        <p class="text-xs text-gray-500">Silakan cek status pembayaran berkala. Jika status sudah lunas, Anda bisa langsung mengambil bunga ke toko offline kami dengan menunjukkan ID Pesanan di atas.</p>
        <a href="/" class="inline-block bg-[#f297aa] text-white px-6 py-2.5 rounded-xl text-xs font-semibold hover:bg-opacity-90">
            Kembali ke Beranda
        </a>
    </div>
</body>
</html>
