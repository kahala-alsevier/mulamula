<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - MulaMula</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght=600;700&family=Plus+Jakarta+Sans:wght=400;500;600;700&display=swap"
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

<body class="bg-[#f3ebd8] text-gray-800 antialiased">

    <div class="flex items-center justify-between px-4 pt-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <a href="{{ route('home') }}"
            class="inline-flex items-center space-x-2 text-sm font-medium text-gray-600 hover:text-[#f297aa] transition-colors">
            &larr; Kembali ke Beranda
        </a>
        <span class="font-serif text-xl font-bold text-[#f297aa]">MulaMula Account</span>
    </div>

    <main class="px-4 py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-6 p-4 bg-[#b6bb79]/20 border border-[#b6bb79] text-gray-900 text-sm rounded-xl">
                ✨ {{ session('success') }}
            </div>
        @endif

        <div class="grid items-start grid-cols-1 gap-8 lg:grid-cols-3">

            <div class="bg-white rounded-3xl p-8 border border-[#f9d0ce]/60 shadow-sm space-y-6">
                <div>
                    <h2 class="font-serif text-2xl font-bold text-gray-950">Data Pemesan</h2>
                    <p class="mt-1 text-xs text-gray-400">Pastikan Nomor WhatsApp Anda aktif untuk Komunikasi.</p>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block mb-2 text-xs font-bold tracking-wider text-gray-400 uppercase">Nama Lengkap
                            *</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            class="w-full bg-[#f3ebd8]/30 border border-[#f9d0ce] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#f297aa]"
                            required>
                    </div>

                    <div>
                        <label class="block mb-2 text-xs font-bold tracking-wider text-gray-400 uppercase">Nomor
                            WhatsApp *</label>
                        <input type="text" name="whatsapp_number"
                            value="{{ old('whatsapp_number', $user->whatsapp_number) }}"
                            placeholder="Contoh: 08123456789"
                            class="w-full bg-[#f3ebd8]/30 border border-[#f9d0ce] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#f297aa]"
                            required>
                    </div>

                    <div>
                        <label class="block mb-2 text-xs font-bold tracking-wider text-gray-400 uppercase">Alamat Utama
                            (Opsional)</label>
                        <textarea name="address" rows="3"
                            class="w-full bg-[#f3ebd8]/30 border border-[#f9d0ce] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#f297aa]">{{ old('address', $user->address) }}</textarea>
                    </div>

                    <button type="submit"
                        class="w-full bg-[#f297aa] text-white py-3.5 rounded-xl text-sm font-semibold hover:bg-opacity-90 shadow-md shadow-[#f297aa]/20 transition-all">
                        Simpan Perubahan
                    </button>
                </form>

                <div class="pt-4 border-t border-gray-100">
                    <div x-data="{ openLogout: false }">
                        <!-- Tombol Pemicu -->
                        <button type="button" @click="openLogout = true"
                            class="flex items-center justify-center w-full py-3 space-x-2 text-sm font-medium text-gray-500 transition-all border border-gray-200 bg-gray-50 rounded-xl hover:bg-red-50 hover:text-red-600 hover:border-red-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                            </svg>
                            <span>Keluar Akun</span>
                        </button>

                        <!-- Modal Konfirmasi Kustom (Muncul jika klik tombol) -->
                        <div x-show="openLogout" x-transition
                            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm"
                            x-cloak>
                            <div
                                class="w-full max-w-sm p-6 bg-white border border-[#f9d0ce]/60 rounded-3xl shadow-xl space-y-4">
                                <div class="text-center">
                                    <span
                                        class="inline-flex items-center justify-center w-12 h-12 mb-3 text-red-500 rounded-full bg-red-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                                        </svg>
                                    </span>
                                    <h3 class="font-serif text-lg font-bold text-gray-950">Konfirmasi Keluar</h3>
                                    <p class="mt-1 text-xs text-gray-400">Apakah Anda yakin ingin keluar dari akun
                                        MulaMula?</p>
                                </div>
                                <div class="grid grid-cols-2 gap-3 pt-2">
                                    <button type="button" @click="openLogout = false"
                                        class="w-full py-3 text-sm font-medium text-gray-700 transition-all border border-gray-200 bg-gray-50 rounded-xl hover:bg-gray-100">
                                        Batal
                                    </button>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-full py-3 text-sm font-semibold text-white transition-all bg-red-500 shadow-md rounded-xl hover:bg-red-600 shadow-red-500/20">
                                            Ya, Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 bg-white rounded-3xl p-8 border border-[#f9d0ce]/60 shadow-sm space-y-6">
                <div>
                    <h2 class="font-serif text-2xl font-bold text-gray-950">Riwayat Pesanan Anda</h2>
                    <p class="mt-1 text-xs text-gray-400">Daftar pesanan bunga eksklusif yang pernah Anda lakukan.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="border-b border-[#f9d0ce]/40 text-xs font-bold uppercase text-gray-400 tracking-wider">
                                <th class="pb-4">Nomor Faktur</th>
                                <th class="pb-4">Penerima & Ucapan</th>
                                <th class="pb-4">Total Pembayaran</th>
                                <th class="pb-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-50">
                            @forelse($transactions as $tx)
                                <tr>
                                    <td class="py-4 font-medium text-gray-900">
                                        {{ $tx->invoice_number }}
                                        <span
                                            class="block text-[10px] text-gray-400 font-normal">{{ $tx->created_at->format('d M Y, H:i') }}</span>
                                    </td>
                                    <td class="max-w-xs py-4 text-xs text-gray-600">
                                        <p class="font-semibold text-gray-800">Untuk:
                                            {{ $tx->recipient_name ?? 'Diri Sendiri' }}</p>
                                        @if ($tx->greeting_card_text)
                                            <p class="italic bg-[#f3ebd8]/40 p-2 rounded-lg mt-1 text-gray-500">
                                                "{{ $tx->greeting_card_text }}"</p>
                                        @else
                                            <span class="font-light text-gray-400">Tanpa kartu ucapan</span>
                                        @endif
                                    </td>
                                    <td class="py-4 font-bold text-gray-900">
                                        Rp {{ number_format($tx->total_amount, 0, ',', '.') }}
                                        @if ($tx->greeting_card_fee > 0)
                                            <span class="block text-[10px] text-[#b6bb79] font-normal">(Termasuk Cetak
                                                Kartu)</span>
                                        @endif
                                    </td>
                                    <td class="py-4">
                                        <span
                                            class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider
                                            {{ $tx->status === 'completed' ? 'bg-[#b6bb79]/20 text-gray-900' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $tx->status === 'completed' ? 'Sukses' : $tx->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-12 text-sm text-center text-gray-400">
                                        🍃 Anda belum memiliki riwayat pemesanan bunga.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

</body>

</html>
