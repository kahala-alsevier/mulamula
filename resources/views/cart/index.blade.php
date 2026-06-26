<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - MulaMula</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .font-serif {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>

<body class="bg-[#f3ebd8] text-gray-800 antialiased min-h-screen">

    <div class="flex items-center justify-between px-4 pt-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <a href="/"
            class="inline-flex items-center space-x-2 text-sm font-medium text-gray-600 hover:text-[#f297aa] transition-colors">
            <x-heroicon-o-arrow-left class="w-4 h-4" />
            <span>Kembali ke Toko</span>
        </a>
        <span class="font-serif text-xl font-bold text-[#f297aa]">MulaMula Bag</span>
    </div>

    <main class="px-4 py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <h1 class="mb-8 font-serif text-3xl font-bold md:text-4xl text-gray-950">Keranjang Belanja Anda</h1>

        @if (session('success'))
            <div
                class="mb-6 p-4 bg-[#b6bb79]/20 border border-[#b6bb79] text-gray-900 text-sm rounded-xl flex items-center space-x-2">
                <span>✨ {{ session('success') }}</span>
            </div>
        @endif

        @if (count($cart) > 0)
            <div class="grid items-start grid-cols-1 gap-8 lg:grid-cols-3">

                <div class="space-y-4 lg:col-span-2">
                    @php $subtotal = 0; @endphp
                    @foreach ($cart as $id => $item)
                        @php
                            $cardFee = $item['has_card'] ? 15000 : 0;
                            $itemTotal = $item['price'] * $item['quantity'] + $cardFee;
                            $subtotal += $itemTotal;
                        @endphp

                        <div class="bg-white rounded-3xl p-6 border border-[#f9d0ce]/60 shadow-sm">
                            <div
                                class="flex flex-col justify-between gap-4 pb-4 border-b border-gray-100 sm:flex-row sm:items-center">
                                <div class="flex items-center space-x-4">
                                    <img src="{{ asset('storage/' . $item['image']) }}"
                                        class="object-cover w-20 h-20 bg-gray-100 rounded-2xl">
                                    <div>
                                        <h3 class="font-serif text-lg font-bold text-gray-950">{{ $item['name'] }}</h3>
                                        <p class="text-sm font-medium text-gray-500">Rp
                                            {{ number_format($item['price'], 0, ',', '.') }} / pcs</p>
                                    </div>
                                </div>

                                <div class="flex items-center self-end space-x-4 sm:self-center">
                                    <form action="{{ route('cart.update', $id) }}" method="POST"
                                        class="flex items-center space-x-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}"
                                            min="1"
                                            class="w-16 bg-[#f3ebd8]/20 border border-[#f9d0ce] rounded-xl px-2 py-1.5 text-center text-sm focus:outline-none">
                                        <button type="submit"
                                            class="text-xs bg-[#b6bb79] text-white px-2.5 py-2 rounded-xl font-semibold hover:bg-opacity-90">Update</button>
                                    </form>

                                    <form action="{{ route('cart.remove', $id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-red-400 transition-colors hover:text-red-600 bg-red-50 rounded-xl">
                                            <x-heroicon-o-trash class="w-5 h-5" />
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <form action="{{ route('cart.update', $id) }}" method="POST" class="pt-2 mt-4 space-y-3">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="quantity" value="{{ $item['quantity'] }}">

                                <label class="inline-flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" name="has_card" value="1" onchange="this.form.submit()"
                                        {{ $item['has_card'] ? 'checked' : '' }}
                                        class="rounded text-[#f297aa] focus:ring-[#f297aa] border-[#f9d0ce]">
                                    <span class="text-xs font-semibold text-gray-700">+ Tambah Kartu Ucapan Premium
                                        Eksklusif MulaMula (+Rp 15.000)</span>
                                </label>

                                @if ($item['has_card'])
                                    <div
                                        class="grid grid-cols-1 sm:grid-cols-2 gap-3 bg-[#f3ebd8]/30 p-4 rounded-2xl border border-[#f9d0ce]/40">
                                        <div>
                                            <label
                                                class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Nama
                                                Penerima Bunga</label>
                                            <input type="text" name="recipient_name"
                                                value="{{ $item['recipient_name'] }}"
                                                placeholder="Contoh: Amanda Anastasia, S.Ked"
                                                class="w-full bg-white border border-[#f9d0ce] rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-[#f297aa]">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Isi
                                                Pesan Surat / Ucapan</label>
                                            <textarea name="greeting_text" rows="2" placeholder="Tulis ucapan selamat atau pesan hangatmu di sini..."
                                                class="w-full bg-white border border-[#f9d0ce] rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-[#f297aa]">{{ $item['greeting_text'] }}</textarea>
                                        </div>
                                        <div class="flex justify-end sm:col-span-2">
                                            <button type="submit"
                                                class="text-[11px] bg-[#f297aa] text-white px-3 py-1.5 rounded-lg font-medium hover:bg-opacity-90">Simpan
                                                Detail Ucapan</button>
                                        </div>
                                    </div>
                                @endif
                            </form>
                        </div>
                    @endforeach
                </div>

                <div class="bg-white rounded-3xl p-6 border border-[#f9d0ce]/60 shadow-sm space-y-6">
                    <div class="pb-4 space-y-3 text-sm border-b border-gray-100">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal Produk & Kartu</span>
                            <span class="font-semibold text-gray-950">Rp
                                {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Metode Pengambilan</span>
                            <span class="text-xs text-[#b6bb79] font-bold uppercase tracking-wider">Ambil di Toko (Rp
                                0)</span>
                        </div>
                        <div
                            class="bg-[#f3ebd8]/40 p-3 rounded-xl border border-[#f9d0ce]/40 text-[11px] text-gray-600 mt-2">
                            📍 <strong>Alamat Toko:</strong> Jl. Florist MulaMula No. 12, Kota Kita. Silakan ambil
                            pesanan Anda setelah status pembayaran <strong>"Berhasil"</strong>.
                        </div>
                    </div>

                    @auth
                        <form id="payment-form" action="{{ route('checkout.process') }}" method="POST">
                            @csrf
                            <button type="submit" id="pay-button"
                                class="w-full bg-[#f297aa] text-white py-3.5 rounded-xl text-sm font-semibold hover:bg-opacity-90 shadow-md shadow-[#f297aa]/20 transition-all flex items-center justify-center space-x-2">
                                <x-heroicon-o-credit-card class="w-4 h-4" />
                                <span>Bayar Sekarang (Popup Midtrans)</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="w-full bg-gray-950 text-white py-3.5 rounded-xl text-sm font-semibold hover:bg-opacity-90 transition-all block text-center">
                            Masuk Akun untuk Checkout
                        </a>
                    @endauth
                </div>

            </div>
        @else
            <div
                class="bg-white rounded-3xl p-16 text-center border border-[#f9d0ce]/40 shadow-sm max-w-md mx-auto mt-12 space-y-4">
                <x-heroicon-o-shopping-bag class="w-16 h-16 text-[#f297aa]/40 mx-auto" />
                <h2 class="font-serif text-xl font-bold text-gray-950">Keranjang Anda Masih Kosong</h2>
                <p class="text-xs leading-relaxed text-gray-500">Belum ada karangan bunga yang Anda pilih. Mari jelajahi
                    koleksi eksklusif kami hari ini.</p>
                <a href="/"
                    class="inline-block bg-[#b6bb79] text-white px-6 py-2.5 rounded-xl text-xs font-semibold hover:bg-opacity-90 transition-all">
                    Lihat Koleksi Bunga
                </a>
            </div>
        @endif
    </main>
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('payment-form');
            const button = document.getElementById('pay-button');

            if (form) {
                form.addEventListener('submit', function(e) {
                    // 🌟 INI KUNCINYA: Menghentikan browser agar TIDAK redirect ke halaman JSON
                    e.preventDefault();

                    const originalText = button.innerHTML;
                    button.disabled = true;
                    button.innerHTML = '<span>Membuat Sesi Pembayaran...</span>';

                    // Tembak rute checkout via AJAX POST
                    fetch(form.action, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Jaringan bermasalah atau server error');
                            }
                            return response.json();
                        })
                        .then(data => {
                            button.disabled = false;
                            button.innerHTML = originalText;

                            if (data.snap_token) {
                                // Pemicu Utama Popup Midtrans Snap Keluar!
                                snap.pay(data.snap_token, {
                                    onSuccess: function(result) {
                                        window.location.href =
                                            "{{ route('checkout.finish') }}?order_id=" +
                                            result.order_id + "&transaction_status=" +
                                            result.transaction_status;
                                    },
                                    onPending: function(result) {
                                        window.location.href =
                                            "{{ route('checkout.finish') }}?order_id=" +
                                            result.order_id + "&transaction_status=" +
                                            result.transaction_status;
                                    },
                                    onError: function(result) {
                                        alert("Pembayaran gagal, silakan coba lagi.");
                                        window.location.reload();
                                    },
                                    onClose: function() {
                                        alert(
                                            'Kamu menutup halaman pembayaran sebelum selesai.');
                                    }
                                });
                            } else {
                                alert('Gagal mendapatkan token pembayaran dari server.');
                            }
                        })
                        .catch(error => {
                            button.disabled = false;
                            button.innerHTML = originalText;
                            console.error("Error detail:", error);
                            alert("Terjadi kesalahan sistem saat menghubungi Midtrans.");
                        });
                });
            }
        });
    </script>
</body>


</html>
