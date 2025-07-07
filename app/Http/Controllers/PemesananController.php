<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Gedung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PemesananController extends Controller
{

public function index(Request $request)
{
    $query = Pemesanan::with(['gedung', 'pembayaran'])
        ->where('user_id', auth()->id())
        ->orderBy('created_at', 'desc');

    if ($request->has('status') && $request->status !== 'all') {
        $query->where('status', $request->status);
    }

    $pemesanans = $query->paginate(10);

    // Get counts for each status
    $statusCounts = Pemesanan::where('user_id', auth()->id())
        ->selectRaw('status, count(*) as count')
        ->groupBy('status')
        ->pluck('count', 'status')
        ->toArray();

    // Define all possible statuses and initialize counts to 0
    $allStatuses = [
        'menunggu_pembayaran',
        'dibayar',
        'dikonfirmasi',
        'selesai',
        'dibatalkan'
    ];

    $statusCounts = array_merge(
        array_fill_keys($allStatuses, 0),
        $statusCounts
    );

    return view('pemesanan.index', compact('pemesanans', 'statusCounts'));
}

    public function store(Request $request)
{
    $validated = $request->validate([
        'id_gedung' => 'required|exists:gedung,id_gedung',
        'tanggal_mulai' => 'required|date',
        'durasi' => 'required|integer|min:1|max:12',
        'nama_acara' => 'required|string|max:30',
    ]);

    // Validasi waktu pemesanan
    $startTime = Carbon::parse($validated['tanggal_mulai']);
    $minAllowedTime = now()->addHours(2);
    
    if ($startTime->lt(now())) {
        return back()->withErrors([
            'tanggal_mulai' => 'Waktu pemesanan tidak boleh di masa lalu'
        ])->withInput();
    }

    if ($startTime->lt($minAllowedTime)) {
        return back()->withErrors([
            'tanggal_mulai' => 'Pemesanan harus dibuat minimal 2 jam sebelum waktu mulai'
        ])->withInput();
    }


    // Cek ketersediaan gedung
    $endTime = $startTime->copy()->addHours($validated['durasi']);
        
    $isAvailable = Pemesanan::where('id_gedung', $validated['id_gedung'])
        ->where(function($query) use ($startTime, $endTime) {
            $query->whereBetween('tanggal_mulai', [$startTime, $endTime])
                ->orWhereBetween('tanggal_selesai', [$startTime, $endTime])
                ->orWhere(function($query) use ($startTime, $endTime) {
                    $query->where('tanggal_mulai', '<', $startTime)
                        ->where('tanggal_selesai', '>', $endTime);
                });
        })
        ->whereIn('status', ['menunggu_pembayaran', 'dibayar', 'dikonfirmasi'])
        ->doesntExist();

    if (!$isAvailable) {
        return back()->withErrors([
            'tanggal_mulai' => 'Gedung tidak tersedia pada waktu yang dipilih'
        ])->with('openBookingModal', true);
        
    }


    $gedung = Gedung::findOrFail($validated['id_gedung']);

    // Hitung tanggal selesai
    $tanggalMulai = new \DateTime($validated['tanggal_mulai']);
    $durasi = $validated['durasi'];
    $tanggalSelesai = clone $tanggalMulai;
    $tanggalSelesai->add(new \DateInterval("PT{$durasi}H"));

    // Validasi jadwal bentrok
    $isJadwalBentrok = $this->checkJadwalBentrok(
        $validated['id_gedung'],
        $tanggalMulai,
        $tanggalSelesai
    );

    if ($isJadwalBentrok) {
        return back()
            ->withInput()
            ->withErrors([
                'tanggal_mulai' => 'Gedung sudah dipesan pada waktu tersebut atau kurang dari 2 jam sebelum/sesudahnya'
            ])
            ->with('openBookingModal', true);
            
            
    }

    // Hitung total harga
    $totalHarga = $gedung->harga * $validated['durasi'];

    // Buat pemesanan
    $pemesanan = Pemesanan::create([
        'id_pemesanan' => Str::uuid(),
        'user_id' => Auth::id(),
        'id_gedung' => $validated['id_gedung'],
        'tanggal_mulai' => $tanggalMulai,
        'tanggal_selesai' => $tanggalSelesai,
        'nama_acara' => $validated['nama_acara'],
        'total_harga' => $totalHarga,
        'status' => 'menunggu_pembayaran'
    ]);

    return redirect()->route('pembayaran.show', $pemesanan->id_pemesanan)
        ->with('success', 'Pemesanan berhasil dibuat. Silakan lanjutkan pembayaran.');
}

private function checkJadwalBentrok($id_gedung, $tanggalMulai, $tanggalSelesai)
{
    // Tambah buffer 2 jam sebelum dan sesudah
    $bufferStart = clone $tanggalMulai;
    $bufferStart->sub(new \DateInterval('PT2H'));
    
    $bufferEnd = clone $tanggalSelesai;
    $bufferEnd->add(new \DateInterval('PT2H'));

    // Cek apakah ada pemesanan yang bentrok
    $existingBookings = Pemesanan::where('id_gedung', $id_gedung)
        ->where('status', '!=', 'dibatalkan') // Abaikan yang dibatalkan
        ->where(function($query) use ($bufferStart, $bufferEnd) {
            $query->whereBetween('tanggal_mulai', [$bufferStart, $bufferEnd])
                ->orWhereBetween('tanggal_selesai', [$bufferStart, $bufferEnd])
                ->orWhere(function($q) use ($bufferStart, $bufferEnd) {
                    $q->where('tanggal_mulai', '<', $bufferStart)
                      ->where('tanggal_selesai', '>', $bufferEnd);
                });
        })
        ->exists();

    return $existingBookings;
}




    public function show($id_pemesanan)
    {
        $pemesanan = Pemesanan::with(['gedung', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id_pemesanan);

        return view('pemesanan.show', compact('pemesanan'));
    }



    public function batal($id_pemesanan)
{
    $pemesanan = Pemesanan::where('user_id', auth()->id())
        ->findOrFail($id_pemesanan);

    // Hanya bisa dibatalkan jika status menunggu pembayaran
    if ($pemesanan->status !== 'menunggu_pembayaran') {
        return back()->with('error', 'Pemesanan tidak dapat dibatalkan');
    }

    $pemesanan->update(['status' => 'dibatalkan']);

    return redirect()->route('pemesanan.show', $pemesanan->id_pemesanan)
        ->with('success', 'Pemesanan berhasil dibatalkan');
}


public function checkAvailability(Request $request, $id)
{
    $request->validate([
        'tanggal_mulai' => 'required|date',
        'durasi' => 'required|integer|min:1'
    ]);

    $tanggalMulai = new \DateTime($request->tanggal_mulai);
    $tanggalSelesai = clone $tanggalMulai;
    $tanggalSelesai->add(new \DateInterval("PT{$request->durasi}H"));

    // Tambah buffer 2 jam
    $bufferStart = clone $tanggalMulai;
    $bufferStart->sub(new \DateInterval('PT2H'));
    
    $bufferEnd = clone $tanggalSelesai;
    $bufferEnd->add(new \DateInterval('PT2H'));

    $isAvailable = !Pemesanan::where('id_gedung', $id)
        ->where('status', '!=', 'dibatalkan')
        ->where(function($query) use ($bufferStart, $bufferEnd) {
            $query->whereBetween('tanggal_mulai', [$bufferStart, $bufferEnd])
                ->orWhereBetween('tanggal_selesai', [$bufferStart, $bufferEnd])
                ->orWhere(function($q) use ($bufferStart, $bufferEnd) {
                    $q->where('tanggal_mulai', '<', $bufferStart)
                      ->where('tanggal_selesai', '>', $bufferEnd);
                });
        })
        ->exists();

    return response()->json([
        'available' => $isAvailable,
        'message' => $isAvailable 
            ? 'Gedung tersedia pada waktu tersebut'
            : 'Gedung sudah dipesan pada waktu tersebut atau kurang dari 2 jam sebelum/sesudahnya'
    ]);
}

}