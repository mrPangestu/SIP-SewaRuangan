<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\InvoiceMail;
use PDF;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Midtrans\Transaction;

class PembayaranController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function show($id_pemesanan)
    {
        $pemesanan = Pemesanan::with(['gedung', 'user'])
            ->where('user_id', auth()->id())
            ->findOrFail($id_pemesanan);

        if ($pemesanan->status !== 'menunggu_pembayaran') {
            return redirect()->route('pemesanan.show', $id_pemesanan)
                ->with('info', 'Pemesanan ini sudah dibayar');
        }

        return view('pembayaran.show', [
            'pemesanan' => $pemesanan,
            'paymentMethods' => $this->getPaymentMethods()
        ]);
    }

        private function mapPaymentMethod($method)
    {
        return [
            'credit_card' => ['credit_card'],
            'bank_transfer' => [
                'bank_transfer', 
                'bca_va', 'bni_va', 'bri_va', 'mandiri_va', 'permata_va',
                'other_va', 'echannel'
            ],
            'ewallet' => [
                'gopay', 'shopeepay', 'qris', 'dana', 'ovo', 'linkaja'
            ],
            'cstore' => [
                'indomaret', 'alfamart'
            ],
            'akulaku' => ['akulaku'],
            'kredivo' => ['kredivo']
        ][$method] ?? [$method];

        return $mapping[$method] ?? [];
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
            ],
            'credit_card' => [
                'nama' => 'Kartu Kredit',
                'logo' => asset('img/credit-card.png'),
                'deskripsi' => 'Visa, Mastercard, JCB'
            ]
        ];
    }

    public function process(Request $request)
    {
        $request->validate([
            'id_pemesanan' => 'required|exists:pemesanan,id_pemesanan',
            'metode_pembayaran' => 'required|in:bank_transfer,ewallet,qris,credit_card',
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

        // Proses pembayaran Midtrans
        return $this->handleMidtransPayment($pembayaran);
    }

    private function handleMidtransPayment(Pembayaran $pembayaran)
    {
        $pemesanan = $pembayaran->pemesanan;
        $user = $pemesanan->user;

        $params = [
            'transaction_details' => [
                'order_id' => $pembayaran->referensi_pembayaran,
                'gross_amount' => $pembayaran->jumlah,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '08123456789',
            ],
            'enabled_payments' => $this->mapPaymentMethod($pembayaran->metode_pembayaran),
            'callbacks' => [
                'finish' => route('pembayaran.check-status', $pembayaran->id_pembayaran),
            ],
            'expiry' => [
                'start_time' => date('Y-m-d H:i:s T'),
                'unit' => 'hours',
                'duration' => 24
            ]
        ];

        // Penyesuaian khusus untuk beberapa metode pembayaran
        switch ($pembayaran->metode_pembayaran) {
            case 'bank_transfer':
                $params['bank_transfer'] = [
                    'bank' => 'bca', // Default bank
                    'va_number' => '1234567890', // Nomor VA contoh
                ];
                break;
                
            case 'ewallet':
                $params['ewallet'] = [
                    'channel_code' => $this->getDefaultEwalletChannel()
                ];
                break;
                
            case 'cstore':
                $params['cstore'] = [
                    'store' => 'indomaret',
                    'message' => 'Pembayaran untuk order #'.$pembayaran->referensi_pembayaran
                ];
                break;
        }

        try {
            $snapToken = Snap::getSnapToken($params);
            $pembayaran->update(['snap_token' => $snapToken]);

            return view('pembayaran.midtrans', [
                'snapToken' => $snapToken,
                'pembayaran' => $pembayaran,
                'paymentInstructions' => $this->getPaymentInstructions($pembayaran->metode_pembayaran)
            ]);
        } catch (\Exception $e) {
            \Log::error('Midtrans Error: '.$e->getMessage());
            $pembayaran->update(['status' => 'failed']);
            return back()->with('error', 'Pembayaran gagal: '.$e->getMessage());
        }
    }

    private function getDefaultEwalletChannel()
    {
        // Default channel e-wallet
        return 'gopay';
    }

    private function getPaymentInstructions($method)
    {
        $instructions = [
            'credit_card' => [
                'Silakan masukkan detail kartu kredit Anda',
                'Gunakan kartu uji: 4811 1111 1111 1114 (sandbox)',
                'CVV: 123, Expiry: Masa depan apa saja'
            ],
            'bank_transfer' => [
                'Anda akan menerima instruksi transfer VA setelah memilih bank',
                'Simpan nomor VA untuk referensi pembayaran',
                'Pembayaran harus dilakukan sebelum waktu kadaluarsa'
            ],
            'gopay' => [
                'Buka aplikasi Gojek/Gopay Anda',
                'Pilih "Bayar" dan scan QR code',
                'Atau gunakan nomor telepon terkait akun Gopay'
            ],
            'qris' => [
                'Buka aplikasi e-wallet atau mobile banking Anda',
                'Scan QR code yang ditampilkan',
                'Konfirmasi pembayaran'
            ],
            'shopeepay' => [
                'Buka aplikasi Shopee/ShopeePay',
                'Pilih metode pembayaran ShopeePay',
                'Masukkan PIN untuk menyelesaikan pembayaran'
            ],
            'indomaret' => [
                'Simpan kode pembayaran Anda',
                'Kunjungi gerai Indomaret terdekat',
                'Berikan kode pembayaran ke kasir'
            ],
            'alfamart' => [
                'Simpan kode pembayaran Anda',
                'Kunjungi gerai Alfamart terdekat',
                'Berikan kode pembayaran ke kasir'
            ]
        ];

        return $instructions[$method] ?? [
            'Silakan selesaikan pembayaran menggunakan metode yang dipilih',
            'Ikuti instruksi pada halaman pembayaran'
        ];
    }


    // Fungsi baru untuk mengecek status pembayaran
    public function checkStatus($id_pembayaran)
    {
        $pembayaran = Pembayaran::with('pemesanan')
            ->whereHas('pemesanan', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->findOrFail($id_pembayaran);

        if ($pembayaran->status === 'completed') {
            return redirect()->route('pembayaran.success', $pembayaran->id_pembayaran);
        }

        try {
            $status = Transaction::status($pembayaran->referensi_pembayaran);
            
            DB::transaction(function () use ($pembayaran, $status) {
                $newStatus = $this->mapTransactionStatus(
                    $status->transaction_status, 
                    $status->fraud_status ?? null
                );
                
                $updateData = [
                    'status' => $newStatus,
                    'waktu_pembayaran' => $newStatus === 'completed' ? now() : null,
                    'bukti_pembayaran' => $status->pdf_url ?? null
                ];
                
                // Simpan detail spesifik pembayaran
                if (isset($status->va_numbers[0])) {
                    $updateData['va_number'] = $status->va_numbers[0]->va_number;
                    $updateData['payment_channel'] = $status->va_numbers[0]->bank;
                } elseif ($status->payment_type === 'qris') {
                    $updateData['payment_channel'] = 'qris';
                } elseif ($status->payment_type === 'gopay') {
                    $updateData['payment_channel'] = 'gopay';
                } elseif (in_array($status->payment_type, ['cstore', 'indomaret', 'alfamart'])) {
                    $updateData['payment_channel'] = $status->payment_type;
                    $updateData['store_code'] = $status->merchant_id;
                }
                
                $pembayaran->update($updateData);

                if ($newStatus === 'completed') {
                    $pembayaran->pemesanan->update(['status' => 'dibayar']);
                }
            });

            if ($pembayaran->fresh()->status === 'completed') {
                return redirect()->route('pembayaran.success', $pembayaran->id_pembayaran);
            }

        } catch (\Exception $e) {
            \Log::error('Error checking payment status: ' . $e->getMessage());
        }

        return view('pembayaran.check_status', [
            'pembayaran' => $pembayaran,
            'retryUrl' => route('pembayaran.check-status', $pembayaran->id_pembayaran)
        ]);
    }

    private function mapTransactionStatus($transactionStatus, $fraudStatus)
    {
        if ($transactionStatus == 'capture') {
            return $fraudStatus == 'accept' ? 'completed' : 'failed';
        } elseif ($transactionStatus == 'settlement') {
            return 'completed';
        } elseif ($transactionStatus == 'pending') {
            return 'pending';
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            return 'failed';
        } else {
            return 'pending';
        }
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

    private function sendInvoice(Pembayaran $pembayaran)
    {
        $pdf = $this->generateInvoicePdf($pembayaran);
        $filename = 'invoice-' . $pembayaran->referensi_pembayaran . '.pdf';
        $pdfPath = storage_path('app/public/invoices/' . $filename);
        
        $pdf->save($pdfPath);

        Mail::to($pembayaran->pemesanan->user->email)
            ->send(new InvoiceMail($pembayaran));

        $pembayaran->update(['invoice_sent' => true]);
    }

    private function generateInvoicePdf(Pembayaran $pembayaran)
    {
        $data = [
            'pembayaran' => $pembayaran,
            'pemesanan' => $pembayaran->pemesanan,
            'gedung' => $pembayaran->pemesanan->gedung,
            'user' => $pembayaran->pemesanan->user
        ];

        return PDF::loadView('pdf.invoice', $data)
            ->setPaper('a4', 'portrait');
    }

    public function downloadInvoice($id_pembayaran)
    {
        $pembayaran = Pembayaran::whereHas('pemesanan', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->findOrFail($id_pembayaran);

        $filename = 'invoice-' . $pembayaran->referensi_pembayaran . '.pdf';
        $path = storage_path('app/public/invoices/' . $filename);

        if (!file_exists($path)) {
            $pdf = $this->generateInvoicePdf($pembayaran);
            $pdf->save($path);
        }

        return response()->download($path);
    }
}