<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\GedungController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReviewController;

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

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Auth Routes
Route::middleware('auth')->group(function () {

    // Pemesanan
    Route::post('/pemesanan', [PemesananController::class, 'store'])->name('pemesanan.store');
    Route::get('/pemesanan/{id_pemesanan}', [PemesananController::class, 'show'])->name('pemesanan.show');

    // Deposit routes
    Route::get('/pembayaran/{id_pemesanan}/deposit', [PembayaranController::class, 'showDeposit'])->name('pembayaran.deposit');
    Route::post('/pembayaran/{id_pemesanan}/deposit/proses', [PembayaranController::class, 'processDeposit'])->name('pembayaran.proses-deposit');

    // Pelunasan routes
    Route::get('/pembayaran/{id_pemesanan}/pelunasan', [PembayaranController::class, 'showPelunasan'])->name('pembayaran.pelunasan');
    Route::post('/pembayaran/{id_pemesanan}/pelunasan/proses', [PembayaranController::class, 'processPelunasan'])->name('pembayaran.proses-pelunasan');


    Route::get('/pembayaran/sukses/{id_pembayaran}', [PembayaranController::class, 'success'])->name('pembayaran.success');
    Route::post('/pembayaran/check-status/{id_pembayaran}', [PembayaranController::class, 'checkPaymentStatus'])->name('pembayaran.check-status');
    Route::get('/pembayaran/{id_pembayaran}/check-status', [PembayaranController::class, 'checkStatus'])->name('pembayaran.check-status');

    Route::get('/invoice/{id_pembayaran}/download', [PembayaranController::class, 'downloadInvoice'])->name('invoice.download');

    Route::patch('/pemesanan/{id_pemesanan}/batal', [PemesananController::class, 'batal'])->name('pemesanan.batal');

    Route::get('/pemesanan', [PemesananController::class, 'index'])->name('pemesanan.index');

    // Review
    Route::post('/review', [ReviewController::class, 'store'])->name('review.store')->middleware('auth');
});


// Admin Routes
// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    // Route untuk laporan
    Route::get('/pemesanan/report', [AdminController::class, 'generateReport'])->name('pemesanan.report');

    // Route untuk data filter (optional)
    Route::get('/pemesanan/filter-data', [AdminController::class, 'getFilterData'])->name('pemesanan.filter.data');

    // Pemesanan
    Route::prefix('pemesanan')->name('pemesanan.')->group(function () {
        Route::get('/', [AdminController::class, 'pemesananIndex'])->name('index');
        Route::get('/{id_pemesanan}', [AdminController::class, 'pemesananShow'])->name('show');
        Route::post('/{id_pemesanan}/confirm', [AdminController::class, 'pemesananConfirm'])->name('confirm');
    });

    Route::get('/pemesanan', [AdminController::class, 'pemesananIndex'])->name('pemesanan.index');
    Route::get('/pemesanan/{id_pemesanan}', [AdminController::class, 'pemesananDetail'])->name('pemesanan.detail');
    Route::post('/pemesanan/{id_pemesanan}/complete', [AdminController::class, 'completeBooking'])->name('pemesanan.complete');
    Route::post('/pemesanan/{id_pemesanan}/send-reminder', [AdminController::class, 'sendDepositReminder'])->name('pemesanan.send-reminder');


    Route::prefix('kategori')->name('kategori.')->group(function () {
        Route::get('/', [AdminController::class, 'kategoriIndex'])->name('index');
        Route::post('/', [AdminController::class, 'kategoriStore'])->name('store');
        Route::put('/{id_kategori}', [AdminController::class, 'kategoriUpdate'])->name('update');
        Route::delete('/{id_kategori}', [AdminController::class, 'kategoriDestroy'])->name('destroy');
    });

    // Gedung
    Route::prefix('gedung')->name('gedung.')->group(function () {
        Route::get('/', [AdminController::class, 'gedungIndex'])->name('index');
        Route::post('/', [AdminController::class, 'gedungStore'])->name('store');
        Route::put('/{id_gedung}', [AdminController::class, 'gedungUpdate'])->name('update');
        Route::delete('/{id_gedung}', [AdminController::class, 'gedungDestroy'])->name('destroy');
    });
    // user
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminController::class, 'userIndex'])->name('index');
        Route::get('/{id}', [AdminController::class, 'userShow'])->name('show');
        Route::delete('/{id}', [AdminController::class, 'userDestroy'])->name('destroy');
        Route::post('/{id}/toggle-status', [AdminController::class, 'userToggleStatus'])->name('toggle-status');
    });
});
