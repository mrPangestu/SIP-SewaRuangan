<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Gedung;
use App\Models\User;
use App\Models\KategoriGedung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\DepositReminderManualMail;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Spatie\Activitylog\Facades\Activity;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalPemesanan = Pemesanan::count();
        $totalGedung = Gedung::count();
        $pendapatanBulanIni = Pemesanan::where('status', 'dibayar')
            ->whereMonth('created_at', now()->month)
            ->sum('total_harga');

        // Data untuk chart pendapatan 6 bulan terakhir
        $revenueData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $revenueData[$date->format('M Y')] = Pemesanan::where('status', 'dibayar')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('total_harga');
        }

        // Data untuk chart status pemesanan
        $statusData = [
            'Menunggu Pembayaran' => Pemesanan::where('status', 'menunggu_pembayaran')->count(),
            'Dibayar' => Pemesanan::where('status', 'dibayar')->count(),
            'Dikonfirmasi' => Pemesanan::where('status', 'dikonfirmasi')->count(),
            'Selesai' => Pemesanan::where('status', 'selesai')->count(),
            'Dibatalkan' => Pemesanan::where('status', 'dibatalkan')->count(),
        ];

        $recentPemesanan = Pemesanan::with(['gedung', 'user'])
            ->latest()
            ->take(5)
            ->get();

        $statusColorMapping = [
            'Menunggu Pembayaran' => 'warning',
            'Dibayar' => 'primary',
            'Dikonfirmasi' => 'info',
            'Selesai' => 'success',
            'Dibatalkan' => 'danger',

        ];

        return view('admin.dashboard', compact(
            'totalPemesanan',
            'totalGedung',
            'pendapatanBulanIni',
            'revenueData',
            'statusData',
            'recentPemesanan',
            'statusColorMapping'
        ));
    }

        public function getStatusColorAttribute()
    {
        return [
            'menunggu_pembayaran' => 'warning',
            'dibayar' => 'primary',
            'dikonfirmasi' => 'info',
            'selesai' => 'success',
            'dibatalkan' => 'danger',
        ][$this->status] ?? 'secondary';
    }

    public function pemesananIndex(Request $request)
    {
        $query = Pemesanan::with(['gedung', 'user'])
            ->latest();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $pemesanan = $query->paginate(100);

        return view('admin.pemesanan.index', compact('pemesanan'));
    }

    public function pemesananShow($id_pemesanan)
    {
        $pemesanan = Pemesanan::with(['gedung', 'user', 'pembayaran'])
            ->findOrFail($id_pemesanan);

            

        return view('pemesanan.show', compact('pemesanan'));
    }

    public function pemesananDone($id_pemesanan)
    {

        $pemesanan = Pemesanan::where('status', 'dibayar')
        ->whereDoesntHave('pembayaran', function($query) {
            $query->where('status', '!=', 'completed');
        })
        ->lockForUpdate()
        ->findOrFail($id_pemesanan);

    $pemesanan->update(['status' => 'dikonfirmasi']);

    return redirect()->back()
        ->with('success', 'Pemesanan berhasil dikonfirmasi');
    }
        /**
     * Detail pemesanan untuk admin
     */
    public function pemesananDetail($id_pemesanan)
    {
        $pemesanan = Pemesanan::with([
            'gedung', 
            'user', 
            'pembayaran' => function($query) {
                $query->orderBy('created_at', 'desc');
            }
        ])->findOrFail($id_pemesanan);

        return view('admin.pemesanan.detail', compact('pemesanan'));
    }

    public function completeBooking($id_pemesanan)
    {
        $pemesanan = Pemesanan::whereIn('status', ['dibayar', 'dikonfirmasi'])
            ->lockForUpdate()
            ->findOrFail($id_pemesanan);

        DB::transaction(function () use ($pemesanan) {
            $pemesanan->update([
                'status' => 'selesai',
            ]);

            // Log activity
        });

        return redirect()->back()
            ->with('success', 'Pemesanan berhasil diselesaikan');
    }

    /**
     * Mengirim pengingat deposit manual
     */
    public function sendDepositReminder($id_pemesanan)
    {
        $pemesanan = Pemesanan::with(['user', 'gedung'])
            ->where('status', 'deposit')
            ->findOrFail($id_pemesanan);

        // Kirim email
        Mail::to($pemesanan->user->email)
            ->send(new DepositReminderManualMail($pemesanan));

        // Update waktu pengingat
        $pemesanan->update([
            'reminder_sent_at' => now(),
            'version' => DB::raw('version + 1')
        ]);

        // Log activity
        return redirect()->back()
            ->with('success', 'Email pengingat deposit berhasil dikirim');
    }

    public function pemesananConfirm($id_pemesanan)
    {

        $pemesanan = Pemesanan::where('status', 'dibayar')
        ->whereDoesntHave('pembayaran', function($query) {
            $query->where('status', '!=', 'completed');
        })
        ->lockForUpdate()
        ->findOrFail($id_pemesanan);

    $pemesanan->update(['status' => 'dikonfirmasi']);

    return redirect()->back()
        ->with('success', 'Pemesanan berhasil dikonfirmasi');
    }



   // ==============================================
    // KATEGORI GEDUNG CRUD
    // ==============================================

    public function kategoriIndex()
    {
        $kategories = KategoriGedung::latest()->get();
        return view('admin.kategori.index', compact('kategories'));
    }

    public function kategoriStore(Request $request)
    {
        $validated = $request->validate([
            
            'nama_kategori' => 'required|string|max:20',
            'deskripsi' => 'nullable|string|max:100'
        ]);

        KategoriGedung::create($validated);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    public function kategoriUpdate(Request $request, $id_kategori)
    {
        $validated = $request->validate([
            
            'nama_kategori' => 'required|string|max:20|unique:kategori_gedung,nama_kategori,'.$id_kategori.',id_kategori',
            'deskripsi' => 'nullable|string|max:100'
        ]);

        $kategori = KategoriGedung::findOrFail($id_kategori);
        $kategori->update($validated);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui');
    }

    public function kategoriDestroy($id_kategori)
    {
        $kategori = KategoriGedung::findOrFail($id_kategori);
        
        // Cek apakah kategori digunakan oleh gedung
        if ($kategori->gedung()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus kategori karena masih digunakan oleh beberapa gedung');
        }

        $kategori->delete();

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil dihapus');
    }

    // ==============================================
    // GEDUNG CRUD
    // ==============================================

    public function gedungIndex()
    {
        $gedungs = Gedung::with('kategori')->latest()->get();
        $kategories = KategoriGedung::all();
        $daerahOptions = [
            'kota bandung utara',
            'kota bandung barat',
            'kota bandung selatan',
            'kota bandung timur',
            'kabupaten bandung barat',
            'kabupaten bandung',
            'kota cimahi',
            'kabupaten sumedang'
        ];
        
        return view('admin.gedung.index', compact('gedungs', 'kategories', 'daerahOptions'));
    }

public function gedungStore(Request $request)
{
    // Basic validation (keep your existing validation rules)
    $validated = $request->validate([
        'id_kategori' => 'required|exists:kategori_gedung,id_kategori',
        'nama' => 'required|string|max:30',
        'lokasi' => 'required|string',
        'daerah' => 'required|in:kota bandung utara,kota bandung barat,kota bandung selatan,kota bandung timur,kabupaten bandung barat,kabupaten bandung,kota cimahi,kabupaten sumedang',
        'kapasitas' => 'required|integer|min:1',
        'fasilitas' => 'required|string',
        'harga' => 'required|numeric|min:0|max:9999999.99',
        'deskripsi' => 'nullable|string',
        'images.*' => 'nullable|image|mimes:jpeg,png,jpg'
    ]);

    // Handle image upload (simplified version)
    try {
        // Buat direktori jika belum ada
        if (!Storage::disk('public')->exists('gedung_images')) {
            Storage::disk('public')->makeDirectory('gedung_images');
        }

        $imagePaths = [];
        foreach ($request->file('images') as $image) {
            $filename = 'gedung_'.time().'_'.Str::random(5).'.'.$image->extension();
            
            // Simpan gambar
            $path = $image->storeAs('gedung_images', $filename, 'public');
            $imagePaths[] = $filename;
        }

        Gedung::create([
            'id_gedung' => Str::uuid(),
            'id_kategori' => $request->id_kategori,
            'nama' => $request->nama,
            'lokasi' => $request->lokasi,
            'daerah' => $request->daerah,
            'kapasitas' => $request->kapasitas,
            'fasilitas' => $request->fasilitas,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
            'image' => implode(',', $imagePaths),
        ]);

        return redirect()->route('admin.gedung.index')
               ->with('success', 'Gedung berhasil ditambahkan');

    } catch (\Exception $e) {
        return back()->with('error', 'Gagal upload gambar: '.$e->getMessage());
    }
}

    public function gedungUpdate(Request $request, $id_gedung)
    {
        $validated = $request->validate([
            'id_kategori' => 'required|exists:kategori_gedung,id_kategori',
            'nama' => 'required|string|max:30',
            'lokasi' => 'required|string',
            'daerah' => 'required|in:kota bandung utara,kota bandung barat,kota bandung selatan,kota bandung timur,kabupaten bandung barat,kabupaten bandung,kota cimahi,kabupaten sumedang',
            'kapasitas' => 'required|integer',
            'fasilitas' => 'required|string',
            'harga' => 'required|numeric|min:0|max:9999999.99',
            'deskripsi' => 'nullable|string'
        ]);

        $gedung = Gedung::findOrFail($id_gedung);
        $gedung->update($validated);

        return redirect()->route('admin.gedung.index')
            ->with('success', 'Gedung berhasil diperbarui');
    }

    public function gedungDestroy($id_gedung)
    {
        $gedung = Gedung::findOrFail($id_gedung);
        $gedung->delete();

        return redirect()->route('admin.gedung.index')
            ->with('success', 'Gedung berhasil dihapus');
    }

   public function userIndex(Request $request)
{
    $search = $request->query('search');

    $users = User::where('role', 'user')
        ->withCount([
            'pemesanan as total_pemesanan'
        ])
        ->when($search, function($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        })
        ->latest()
        ->paginate(10);

    return view('admin.users.index', compact('users', 'search'));
}

public function userShow($id)
{
    $user = User::where('role', 'user')
        ->withCount(['pemesanan as total_pemesanan', 'pemesanan as pemesanan_aktif' => function($query) {
            $query->whereIn('status', ['dibayar', 'dikonfirmasi']);
        }])
        ->findOrFail($id);

    $pemesanan = $user->pemesanan()
        ->with('gedung')
        ->latest()
        ->take(5)
        ->get();

    return view('admin.users.show', compact('user', 'pemesanan'));
}

public function userDestroy($id)
{
    $user = User::where('role', 'user')->findOrFail($id);
    
    // Cek jika user memiliki pemesanan aktif
    if ($user->pemesanan()->whereIn('status', ['dibayar', 'dikonfirmasi'])->exists()) {
        return redirect()->back()
            ->with('error', 'Tidak dapat menghapus pengguna dengan pemesanan aktif');
    }

    $user->delete();

    return redirect()->route('admin.users.index')
        ->with('success', 'Pengguna berhasil dihapus');
}


    
}