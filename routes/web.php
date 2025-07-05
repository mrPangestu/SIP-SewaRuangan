<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\GedungController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\AuthController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [BerandaController::class, 'index'])->name('beranda');
Route::get('/gedung/{id_gedung}', [GedungController::class, 'show'])->name('gedung.show');
Route::get('/gedung/{id_gedung}/bookings/{date}', [GedungController::class, 'getDateBookings']);
Route::get('/gedung/filter', [BerandaController::class, 'filter'])->name('gedung.filter');


// Auth Routes
Route::middleware('auth')->group(function () {
    
    // Pemesanan
    Route::post('/pemesanan', [PemesananController::class, 'store'])->name('pemesanan.store');
    Route::get('/pemesanan/{id_pemesanan}', [PemesananController::class, 'show'])->name('pemesanan.show');

    // Pembayaran
    Route::get('/pembayaran/{id_pemesanan}', [PembayaranController::class, 'show'])->name('pembayaran.show');
    Route::post('/pembayaran/proses', [PembayaranController::class, 'process'])->name('pembayaran.proses');
    Route::get('/pembayaran/sukses/{id_pembayaran}', [PembayaranController::class, 'success'])->name('pembayaran.success');
    
    Route::patch('/pemesanan/{id_pemesanan}/batal', [PemesananController::class, 'batal'])->name('pemesanan.batal');
    Route::get('/pemesanan', [PemesananController::class, 'index'])->name('pemesanan.index');
});
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

