<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>HallRent</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Animasi modal */
        #filterModal {
            transition: opacity 0.3s ease;
        }
        
        #filterModal > div {
            transform: translateY(-20px);
            transition: transform 0.3s ease;
        }
        
        #filterModal:not(.hidden) {
            display: flex;
            opacity: 1;
        }
        
        #filterModal:not(.hidden) > div {
            transform: translateY(0);
        }
        
        /* Scroll untuk daftar gedung */
        .gedung-list {
            max-height: 650px;
            overflow-y: auto;
            background: rgb(221, 221, 221);
        }
        
        /* Style untuk select dan input */
        select, input[type="number"] {
            transition: border-color 0.3s;
        }
        
        select:focus, input[type="number"]:focus {
            border-color: #9333ea;
            outline: none;
        }
        
    </style>
</head>
<body class="bg-gray-100" style="height: 100%">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md p-4">
            <h1 class="text-xl font-bold text-purple-800 mb-8">HallRent!</h1>
            <nav class="space-y-4">
                <a href="{{ route('beranda') }}" class="block text-gray-700 hover:text-purple-600">Beranda</a>
                <a href="{{ route('pemesanan.index') }}" class="block text-purple-700 hover:underline mt-4">Pemesanan</a>
                @auth
                    <form action="{{ route('logout') }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit" class="w-full text-left text-red-600 hover:text-red-800 px-2 py-1 rounded">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block text-purple-700 hover:underline mt-4">Login</a>
                @endauth
            </nav>
        </aside>

        <!-- Main -->
        <div class="container py-4">
            <h2 class="text-2xl font-bold mb-4"></h2>
        
            <!-- Search bar -->
            
            
            <div class="flex justify-center mb-4 " >
                <form id="searchForm" method="GET" action="{{ route('beranda') }}" style="width: 600px">
                    <input type="text" name="search" placeholder="Cari gedung..." 
                           class="w-full px-4 py-2 border rounded-lg pl-10 focus:outline-none focus:ring-2 focus:ring-purple-500"
                           value="{{ request('search') }}">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </form>
            </div>
        
            <!-- Filter & Kalender -->
            <div class="flex justify-center gap-4 mb-6">
                <button id="filterBtn" class="px-4 py-2 border rounded-lg bg-white shadow hover:bg-gray-100">Filter</button>
                <button id="calendarBtn" class="px-4 py-2 border rounded-lg bg-white shadow hover:bg-gray-100">Kalender</button>
            </div>
        
            <!-- List Gedung -->
            <div class="m-5 py-5 gedung-list">
                <div class="space-y-6 mx-5">
                    @forelse ($gedungs as $gedung)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow cursor-pointer" 
                         onclick="detail('{{ $gedung->id_gedung }}')">
                         
                        <div class="p-6">
                            
                            <div class="flex flex-col md:flex-row gap-4"> 
                                <div class="flex-shrink-0">
                                    <img src="./img/img1.jpg" class="rounded" alt="..." width="300">
                                </div>
                                <div class="flex flex-col justify-between flex-grow">
                                    <div>
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h2 class="text-2xl font-semibold text-gray-800 mb-2">{{ $gedung->nama }}</h2>
                                                <span class="inline-block px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded-full mb-2">
                                                    {{ $gedung->kategori->nama_kategori }}
                                                </span>
                                            </div>
                                            <span class="text-sm text-gray-500">{{ ucfirst($gedung->daerah) }}</span>
                                        </div>
                                        
                                        <p class="text-gray-600 mb-4 line-clamp-2">{{ $gedung->deskripsi }}</p>
                                        
                                        <div class="flex items-center text-gray-700 mb-2">
                                            <i class="fas fa-map-marker-alt mr-2"></i>
                                            <span>{{ $gedung->lokasi }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-between items-center mt-2">
                                        <div class="text-lg font-semibold text-blue-600">
                                            Rp {{ number_format($gedung->harga, 0, ',', '.') }} / Jam
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <i class="fas fa-users mr-1"></i> {{ $gedung->kapasitas }} orang
                                        </div>
                                    </div>
                                </div>
                            </div>
                                                    
                            
                            <div class="mt-4">
                                <h3 class="font-medium text-gray-700 mb-2">Fasilitas :</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(explode(',', $gedung->fasilitas) as $item)
                                    <span class="px-3 py-1 bg-gray-100 rounded-full text-sm text-gray-700">
                                        {{ trim($item) }}
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white rounded-lg shadow-md p-6 text-center">
                        <p class="text-gray-600">Tidak ada gedung yang sesuai dengan filter Anda.</p>
                        <a href="{{ route('beranda') }}" class="text-purple-600 hover:underline mt-2 inline-block">
                            Reset filter
                        </a>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- Filter Modal -->
        <div id="filterModal" class="fixed inset-0 bg-black bg-opacity-30 hidden items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md">
                <h3 class="text-lg font-bold mb-4">Filter Ruangan</h3>
                <form id="filterForm" method="GET" action="{{ route('beranda') }}">
                    <div class="space-y-4">
                        <!-- Filter Daerah -->
                        <div>
                            <label class="block text-gray-700 mb-2">Daerah</label>
                            <select name="daerah" class="w-full px-3 py-2 border rounded-lg">
                                <option value="">Semua Daerah</option>
                                <option value="kota bandung utara">Kota Bandung Utara</option>
                                <option value="kota bandung barat">Kota Bandung Barat</option>
                                <option value="kota bandung selatan">Kota Bandung Selatan</option>
                                <option value="kota bandung timur">Kota Bandung Timur</option>
                                <option value="kabupaten bandung barat">Kab. Bandung Barat</option>
                                <option value="kabupaten bandung">Kab. Bandung</option>
                                <option value="kota cimahi">Kota Cimahi</option>
                                <option value="kabupaten sumedang">Kab. Sumedang</option>
                            </select>
                        </div>
                        
                        <!-- Filter Kategori -->
                        <div>
                            <label class="block text-gray-700 mb-2">Kategori</label>
                            <select name="kategori" class="w-full px-3 py-2 border rounded-lg">
                                <option value="">Semua Kategori</option>
                                @foreach($kategories as $kategori)
                                    <option value="{{ $kategori->id_kategori }}">{{ $kategori->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Filter Kapasitas -->
                        <div>
                            <label class="block text-gray-700 mb-2">Kapasitas Minimum</label>
                            <input type="number" name="kapasitas" min="1" 
                                class="w-full px-3 py-2 border rounded-lg" 
                                placeholder="Masukkan jumlah orang">
                        </div>
                    </div>
                    
                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" id="resetFilter" 
                            class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                            Reset
                        </button>
                        <button type="submit" 
                            class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                            Terapkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>    
         

<script>
    function detail(id) {
        window.location.href = '/gedung/' + id;
    }


    document.addEventListener('DOMContentLoaded', function() {
    // Toggle modal filter
    const filterBtn = document.getElementById('filterBtn');
    const filterModal = document.getElementById('filterModal');
    const closeFilterModal = document.getElementById('closeFilterModal');
    
    filterBtn.addEventListener('click', () => {
        filterModal.classList.remove('hidden');
    });
    
    closeFilterModal.addEventListener('click', () => {
        filterModal.classList.add('hidden');
    });
    
    // Reset filter
    document.getElementById('resetFilter').addEventListener('click', function() {
        document.getElementById('filterForm').reset();
    });
    
    // Set nilai filter dari URL
    const urlParams = new URLSearchParams(window.location.search);
    document.querySelector('select[name="daerah"]').value = urlParams.get('daerah') || '';
    document.querySelector('select[name="kategori"]').value = urlParams.get('kategori') || '';
    document.querySelector('input[name="kapasitas"]').value = urlParams.get('kapasitas') || '';
});
    // Kalender modal control
    const calendarBtn = document.getElementById('calendarBtn');
    const calendarModal = document.getElementById('calendarModal');
    const closeCalendarModal = document.getElementById('closeCalendarModal');

    calendarBtn.addEventListener('click', () => {
        calendarModal.classList.remove('hidden');
        calendarModal.classList.add('flex');
    });

    closeCalendarModal.addEventListener('click', () => {
        calendarModal.classList.add('hidden');
        calendarModal.classList.remove('flex');
    });


    // Di bagian script yang sama dengan filter
document.getElementById('searchForm').addEventListener('submit', function(e) {
    // Submit form secara normal
});

// Untuk auto-search (opsional)
const searchInput = document.querySelector('input[name="search"]');
let searchTimer;

searchInput.addEventListener('input', function() {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        document.getElementById('searchForm').submit();
    }, 500); // Delay 500ms setelah user berhenti mengetik
});

</script>
    
</body>
</html>


