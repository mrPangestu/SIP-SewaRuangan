<?php

namespace App\Http\Controllers;

use App\Models\Gedung;
use App\Models\Pemesanan;
use App\Models\Review;
use Illuminate\Http\Request;

class GedungController extends Controller
{
    //


    public function show($id_gedung)
    {
        $gedung = Gedung::with('kategori')->findOrFail($id_gedung);

        $bookedDates = Pemesanan::where('id_gedung', $id_gedung)
            ->whereIn('status', ['deposit', 'dibayar', 'dikonfirmasi'])
            ->selectRaw('DISTINCT DATE(tanggal_mulai) as date')
            ->pluck('date')
            ->toArray();

        $reviews = Review::where('id_gedung', $gedung->id_gedung)
            ->with('user')
            ->latest()
            ->get();

        return view('gedung.show', [
            'gedung' => $gedung,
            'bookedDates' => $bookedDates,
            'reviews' => $reviews
        ]);
    }


    public function getDateBookings($id_gedung, $date)
    {
        $bookings = Pemesanan::with([
            'user' => function ($query) {
                $query->select('id', 'name'); // Hanya ambil data yang diperlukan
            }
        ])
            ->where('id_gedung', $id_gedung)
            ->whereIn('status', ['deposit', 'dibayar', 'dikonfirmasi'])
            ->whereDate('tanggal_mulai', $date)
            ->get(['nama_acara', 'tanggal_mulai', 'tanggal_selesai', 'user_id']);

        $formattedBookings = $bookings->map(function ($booking) {
            return [
                'nama_acara' => $booking->nama_acara,
                'waktu_mulai' => $booking->tanggal_mulai->format('H:i'),
                'waktu_selesai' => $booking->tanggal_selesai->format('H:i'),
                'durasi' => $booking->tanggal_mulai->diffInHours($booking->tanggal_selesai) . ' jam',
                'penyewa' => $booking->user->name ?? 'Guest' // Fallback jika tidak ada user
            ];
        });

        return response()->json($formattedBookings);
    }





}

