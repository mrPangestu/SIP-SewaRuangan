<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    public function show($id_pemesanan)
    {
        $pemesanan = Pemesanan::with(['gedung', 'user'])
            ->where('user_id', auth()->id())
            ->findOrFail($id_pemesanan);

        // Jika sudah dibayar, redirect ke detail
        if ($pemesanan->status !== 'menunggu_pembayaran') {
            return redirect()->route('pemesanan.show', $id_pemesanan)
                ->with('info', 'Pemesanan ini sudah dibayar');
        }

        return view('pembayaran.show', [
            'pemesanan' => $pemesanan,
            'paymentMethods' => $this->getPaymentMethods()
        ]);
    }

    public function process(Request $request)
    {
        $request->validate([
            'id_pemesanan' => 'required|',
            'metode_pembayaran' => 'required|',
        ]);

        $pemesanan = Pemesanan::where('user_id', auth()->id())
            ->findOrFail($request->id_pemesanan);

        // Buat record pembayaran
        $pembayaran = Pembayaran::create([
            'id_pembayaran' => Str::uuid(),
            'id_pemesanan' => $pemesanan->id_pemesanan,
            'metode_pembayaran' => $request->metode_pembayaran,
            'jumlah' => $pemesanan->total_harga,
            'status' => 'pending',
            'referensi_pembayaran' => 'INV-' . time() . '-' . Str::random(4),
        ]);

        

        // Redirect ke payment gateway
        return $this->handlePaymentGateway($pembayaran);
    }

    private function getPaymentMethods()
    {
        return [
            'bank_transfer' => [
                'nama' => 'Transfer Bank',
                'logo' => asset('img/transferbank.png'),
                'deskripsi' => 'BCA, BRI, Mandiri, dll'
            ],
            'ewallet' => [
                'nama' => 'E-Wallet',
                'logo' => asset('img/e-wallet.png'),
                'deskripsi' => 'OVO, Gopay, DANA, dll'
            ],
            'qris' => [
                'nama' => 'QRIS',
                'logo' => asset('img/qris.png'),
                'deskripsi' => 'Scan QR Code'
            ]
        ];
    }

    private function handlePaymentGateway($pembayaran)
    {
        // Implementasi nyata akan berbeda tergantung payment gateway
        // Ini contoh sederhana untuk simulasi
        
        // Simulasi pembayaran berhasil
        DB::transaction(function () use ($pembayaran) {
        $pemesanan = Pemesanan::where('id_pemesanan', $pembayaran->id_pemesanan)
            ->where('status', 'menunggu_pembayaran')
            ->lockForUpdate()
            ->firstOrFail();

        $pembayaran->update([
            'status' => 'completed',
            'waktu_pembayaran' => now()
        ]);
        
        $pemesanan->update(['status' => 'dibayar']);
    });

    return redirect()->route('pembayaran.success', $pembayaran->id_pembayaran);
}
    public function success($id_pembayaran)
    {
        $pembayaran = Pembayaran::with(['pemesanan', 'pemesanan.gedung'])
            ->whereHas('pemesanan', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->findOrFail($id_pembayaran);
    
        return view('pembayaran.sukses', compact('pembayaran'));
    }
}

