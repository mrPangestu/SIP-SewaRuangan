<nav class="bg-white shadow-lg">
    <div class="container mx-auto px-4 py-3">
        <div class="flex justify-between items-center">
            <a href="" class="text-xl font-bold text-blue-600"></a>
            <div class="flex space-x-4">
                <a href="{{ Route('beranda') }}" class="px-3 py-2 text-gray-700 hover:text-blue-600">Beranda</a>
                <a href="{{ Route('pemesanan.index') }}" class="px-3 py-2 text-gray-700 hover:text-blue-600">Pesanan</a>
                @auth
                    <a href="#" class="px-3 py-2 text-gray-700 hover:text-blue-600">Profil</a>
                @else
                    <a href="" class="px-3 py-2 text-gray-700 hover:text-blue-600">Masuk</a>
                @endauth
            </div>
        </div>
    </div>
</nav>