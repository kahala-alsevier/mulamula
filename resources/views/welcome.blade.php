<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MulaMula - Toko Bunga & Filosofi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
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

<body class="bg-[#f3ebd8] text-gray-800 antialiased selection:bg-[#f297aa] selection:text-white">

    <nav class="sticky top-0 z-50 bg-[#f3ebd8]/90 backdrop-blur-md border-b border-[#f9d0ce]">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex-shrink-0">
                    <a href="#" class="font-serif text-2xl font-bold text-[#f297aa] tracking-wide">MulaMula.</a>
                </div>

                <div class="hidden space-x-8 md:flex">
                    <a href="#beranda" class="text-sm font-medium hover:text-[#f297aa] transition-colors">Beranda</a>
                    <a href="#kategori" class="text-sm font-medium hover:text-[#f297aa] transition-colors">Kategori</a>
                    <a href="#makna" class="text-sm font-medium hover:text-[#f297aa] transition-colors">Makna Bunga</a>
                    <a href="#tentang" class="text-sm font-medium hover:text-[#f297aa] transition-colors">Tentang
                        Kami</a>
                </div>

                <div class="flex items-center space-x-4">
                    @auth
                        <a href="/keranjang"
                            class="relative p-2.5 text-gray-700 hover:text-[#f297aa] transition-colors bg-[#f9d0ce]/40 rounded-full">

                            <x-heroicon-o-shopping-bag class="w-6 h-6" />

                            <span
                                class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-[#b6bb79] rounded-full">
                                {{ count(session('cart', [])) }}
                            </span>
                        </a>

                        @if (auth()->user()->role === 'admin')
                            <a href="/admin"
                                class="text-xs bg-[#b6bb79] text-white px-4 py-2 rounded-lg font-medium hover:bg-opacity-90 transition-all flex items-center space-x-1">
                                <x-heroicon-m-squares-plus class="inline w-4 h-4" />
                                <span>Panel Admin</span>
                            </a>
                        @else
                            <a href="{{ route('profile.index') }}"
                                class="text-xs bg-[#f297aa] text-white px-4 py-2 rounded-lg font-medium hover:bg-opacity-90 transition-all flex items-center space-x-1">
                                <x-heroicon-m-user class="inline w-4 h-4" />
                                <span>Profil Saya</span>
                            </a>
                        @endif
                    @else
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('login') }}"
                                class="text-sm font-medium text-gray-700 hover:text-[#f297aa] px-3 py-2 transition-colors">Masuk</a>
                            <a href="{{ route('register') }}"
                                class="text-sm font-medium bg-[#f297aa] text-white px-4 py-2 rounded-full hover:bg-opacity-90 shadow-sm shadow-[#f297aa]/30 transition-all">Daftar</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <header id="beranda" class="relative overflow-hidden min-h-[calc(100vh-5rem)] flex items-center">
        <div class="relative z-10 w-full px-4 py-20 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid items-center grid-cols-1 gap-12 md:grid-cols-2">
                <div class="space-y-6">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#f9d0ce] text-[#f297aa]">
                        🌸 Toko Bunga Premium & Edukasi Filosofi
                    </span>
                    <h1 class="font-serif text-5xl font-bold leading-tight text-gray-900 md:text-6xl">
                        Ungkapkan Perasaan Lewat <span class="text-[#f297aa] italic">Keindahan</span> Bunga
                    </h1>
                    <p class="max-w-lg text-base leading-relaxed text-gray-600">
                        Setiap helai kelopak menyimpan kisah, setiap warna menyimpan arti. Temukan rangkaian bunga
                        terbaik yang dirancang khusus untuk mewakili detak emosi dan pesan rahasia Anda.
                    </p>
                    <div class="flex flex-col pt-4 space-y-3 sm:flex-row sm:space-y-0 sm:space-x-4">
                        <a href="#kategori"
                            class="bg-[#f297aa] text-white text-center px-8 py-4 rounded-full font-semibold shadow-lg shadow-[#f297aa]/40 hover:translate-y-[-2px] transition-all">Jelajahi
                            Produk</a>
                        <a href="#makna"
                            class="bg-white text-gray-800 text-center px-8 py-4 rounded-full font-semibold border border-[#f9d0ce] hover:bg-[#f9d0ce]/20 transition-all">Pelajari
                            Makna</a>
                    </div>
                </div>
                <div class="relative flex justify-center">
                    <div
                        class="w-72 h-72 md:w-96 md:h-96 rounded-full bg-[#f9d0ce] absolute -z-10 animate-pulse blur-xl opacity-60">
                    </div>
                    <div
                        class="border-8 border-white shadow-2xl rounded-2xl overflow-hidden bg-gradient-to-tr from-[#f9d0ce] to-[#f297aa]/30 w-full max-w-sm h-[400px] flex items-center justify-center">
                        <span class="font-serif text-8xl text-white/70">💐</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section id="kategori" class="py-24 bg-white">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto mb-16 space-y-3 text-center">
                <h2 class="font-serif text-4xl font-bold text-gray-900">Kategori Pilihan</h2>
                <div class="w-16 h-1 bg-[#b6bb79] mx-auto rounded-full"></div>
                <p class="text-sm text-gray-500">Pilih jenis rangkaian yang sesuai dengan momentum spesial Anda hari
                    ini.</p>
            </div>

            <div class="grid grid-cols-2 gap-6 md:grid-cols-4">
                @forelse($categories as $category)
                    <div
                        class="group relative rounded-2xl overflow-hidden bg-[#f3ebd8]/50 p-4 border border-[#f3ebd8] hover:border-[#f9d0ce] hover:shadow-xl transition-all duration-300">
                        <div
                            class="aspect-square rounded-xl bg-[#f9d0ce]/40 mb-4 overflow-hidden flex items-center justify-center">
                            @if ($category->thumbnail)
                                <img src="{{ asset('storage/' . $category->thumbnail) }}" alt="{{ $category->name }}"
                                    class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-110">
                            @else
                                <span class="text-4xl transition-transform duration-300 group-hover:scale-120">🌸</span>
                            @endif
                        </div>
                        <h3
                            class="font-serif text-lg font-bold text-gray-800 group-hover:text-[#f297aa] transition-colors">
                            {{ $category->name }}</h3>
                        <p class="mt-1 text-xs text-gray-400">Lihat Koleksi &rarr;</p>
                        <a href="{{ route('category.show', $category->slug) }}" class="absolute inset-0"></a>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 text-gray-400 text-sm bg-[#f3ebd8]/30 rounded-2xl">
                        Belum ada data kategori yang dimasukkan oleh Admin.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section id="makna" class="py-24 bg-[#f9d0ce]/30">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto mb-16 space-y-3 text-center">
                <h2 class="font-serif text-4xl font-bold text-gray-900">Kamus & Filosofi Bunga</h2>
                <div class="w-16 h-1 bg-[#f297aa] mx-auto rounded-full"></div>
                <p class="text-sm text-gray-500">Jangan sampai salah pilih, kenali makna mendalam di balik setiap jenis
                    bunga.</p>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                @forelse($flowerMeanings as $meaning)
                    <div
                        class="bg-white p-8 rounded-2xl border border-[#f9d0ce]/60 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
                        <div
                            class="absolute top-0 right-0 bg-[#b6bb79] text-white text-xs font-semibold px-4 py-1.5 rounded-bl-xl">
                            {{ $meaning->symbolism }}
                        </div>
                        <div
                            class="w-12 h-12 rounded-xl bg-[#f3ebd8] flex items-center justify-center text-2xl mb-6 text-[#f297aa]">
                            📖
                        </div>
                        <h3 class="mb-3 font-serif text-xl font-bold text-gray-900">{{ $meaning->flower_name }}</h3>
                        <p class="text-sm leading-relaxed text-gray-600">{{ $meaning->description }}</p>
                    </div>
                @empty
                    <div
                        class="col-span-full text-center py-12 text-gray-400 text-sm bg-white rounded-2xl border border-[#f9d0ce]/50">
                        Belum ada data ensiklopedia makna bunga yang tersedia.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section id="tentang" class="py-24 overflow-hidden bg-white">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid items-center grid-cols-1 gap-16 md:grid-cols-2">
                <div class="relative">
                    <div class="absolute -top-6 -left-6 w-24 h-24 bg-[#b6bb79]/20 rounded-full -z-10"></div>
                    <div
                        class="border-4 border-[#f3ebd8] shadow-xl rounded-2xl overflow-hidden bg-[#f9d0ce]/20 p-12 text-center">
                        <span class="block mb-4 text-9xl">🏪</span>
                        <h4 class="font-serif text-2xl font-bold text-gray-800">MulaMula Florist</h4>
                        <p class="text-xs text-[#b6bb79] font-semibold mt-1 tracking-wider uppercase">Sejak Tahun 2026
                        </p>
                    </div>
                </div>
                <div class="space-y-6">
                    <h2 class="font-serif text-4xl font-bold text-gray-900">Mengapa Memilih MulaMula?</h2>
                    <div class="w-12 h-1 bg-[#b6bb79] rounded-full"></div>
                    <p class="text-sm leading-relaxed text-gray-600">
                        MulaMula bukan sekadar toko bunga biasa. Kami berkomitmen untuk merangkai ikatan emosional yang
                        tulus antara pengirim dan penerima melalui kurasi bunga segar berkualitas tinggi.
                    </p>
                    <ul class="space-y-3.5 text-sm font-medium text-gray-700">
                        <li class="flex items-center space-x-3">
                            <span
                                class="flex-shrink-0 w-5 h-5 rounded-full bg-[#b6bb79] text-white flex items-center justify-center text-xs">✓</span>
                            <span>Bunga Segar Pilihan Langsung dari Petani Lokal</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <span
                                class="flex-shrink-0 w-5 h-5 rounded-full bg-[#b6bb79] text-white flex items-center justify-center text-xs">✓</span>
                            <span>Pencantuman Kartu Makna Filosofi di Setiap Paket</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <span
                                class="flex-shrink-0 w-5 h-5 rounded-full bg-[#b6bb79] text-white flex items-center justify-center text-xs">✓</span>
                            <span>Proses Cepat Aman dan Terjaga Kesegarannya</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-[#f3ebd8] border-t border-[#f9d0ce] py-8 text-center text-xs text-gray-500">
        <p>&copy; 2026 MulaMula. Dibuat dengan penuh dedikasi menggunakan Laravel & Filament.</p>
    </footer>

</body>

</html>
