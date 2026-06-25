<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $category->name }} - Katalog Premium MulaMula</title>
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

<body class="bg-[#f3ebd8] text-gray-800 antialiased">

    <div class="px-4 pt-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <a href="{{ route('home') }}"
            class="inline-flex items-center space-x-2 text-sm font-medium text-gray-600 hover:text-[#f297aa] transition-colors group">
            <svg class="w-5 h-5 transition-transform transform group-hover:-translate-x-1" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            <span>Kembali ke Beranda</span>
        </a>
    </div>

    <header class="px-4 pt-6 pb-12 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div
            class="relative rounded-3xl overflow-hidden bg-[#f9d0ce] border border-[#f9d0ce] h-64 flex items-center px-8 md:px-16 shadow-md group">

            @if ($category->thumbnail)
                <img src="{{ asset('storage/' . $category->thumbnail) }}" alt="{{ $category->name }}"
                    class="absolute inset-0 w-full h-full object-cover object-center filter contrast-[1.05] brightness-95 group-hover:scale-105 transition-transform duration-700">

                <div
                    class="absolute inset-0 bg-gradient-to-r from-[#f3ebd8] via-[#f3ebd8]/90 to-transparent md:to-[#f3ebd8]/20">
                </div>
            @else
                <div class="absolute inset-0 bg-gradient-to-r from-[#f9d0ce] via-[#f9d0ce]/70 to-[#f3ebd8]"></div>
                <div class="absolute right-12 top-4 text-9xl opacity-10">🌸</div>
            @endif

            <div class="absolute right-0 bottom-0 w-48 h-48 rounded-full bg-[#b6bb79]/20 blur-3xl -z-0"></div>

            <div class="relative z-10 max-w-xl space-y-3">
                <span
                    class="text-[10px] font-bold tracking-widest uppercase text-[#b6bb79] bg-white/80 backdrop-blur-sm px-2.5 py-1 rounded-md inline-block">
                    Koleksi Eksklusif
                </span>
                <h1 class="font-serif text-4xl font-bold md:text-5xl text-gray-950 drop-shadow-sm">
                    {{ $category->name }}
                </h1>
                <p class="text-xs font-medium leading-relaxed text-gray-700 md:text-sm">
                    Rangkaian mahakarya terbaik yang dikurasi secara personal menggunakan bunga segar pilihan untuk
                    menyampaikan pesan terdalam Anda.
                </p>
            </div>
        </div>
    </header>

    <main class="px-4 pb-24 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($category->products as $product)
                <div
                    class="bg-white rounded-2xl overflow-hidden border border-[#f9d0ce]/40 shadow-sm hover:shadow-xl transition-all duration-300 group flex flex-col justify-between">
                    <div>
                        <div
                            class="aspect-[4/5] bg-[#f3ebd8]/40 relative overflow-hidden flex items-center justify-center">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-105">
                            @else
                                <span
                                    class="text-6xl transition-transform duration-300 opacity-40 group-hover:scale-110">💐</span>
                            @endif

                            @if ($product->stock > 0)
                                <span
                                    class="absolute top-4 left-4 bg-[#b6bb79] text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-md">
                                    Tersedia
                                </span>
                            @else
                                <span
                                    class="absolute top-4 left-4 bg-gray-400 text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-md">
                                    Habis
                                </span>
                            @endif
                        </div>

                        <div class="p-6 space-y-2">
                            <h3
                                class="font-serif text-xl font-bold text-gray-900 group-hover:text-[#f297aa] transition-colors line-clamp-1">
                                {{ $product->name }}
                            </h3>
                            <p class="text-xs leading-relaxed text-gray-500 line-clamp-2">
                                {{ $product->description ?? 'Tidak ada deskripsi untuk produk premium ini.' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-6 pt-0 mt-4 border-t border-gray-50">
                        <div class="flex flex-col">
                            <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Harga</span>
                            <span class="text-lg font-bold text-gray-950 text-[#f297aa]">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </span>
                        </div>

                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="bg-[#f297aa] text-white p-2 rounded-xl hover:bg-opacity-90 transition-all flex items-center space-x-1 text-xs font-semibold">
                                <x-heroicon-o-shopping-bag class="w-4 h-4" />
                                <span>Pesan</span>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20 bg-white rounded-3xl border border-[#f9d0ce]/40 space-y-3">
                    <span class="block text-5xl">🍃</span>
                    <h3 class="font-serif text-lg font-bold text-gray-800">Koleksi Belum Tersedia</h3>
                    <p class="max-w-xs mx-auto text-xs text-gray-400">Kami sedang merangkai produk-product baru untuk
                        kategori ini. Mohon kembali beberapa saat lagi!</p>
                </div>
            @endforelse
        </div>
    </main>

</body>

</html>
