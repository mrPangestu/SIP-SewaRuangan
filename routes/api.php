<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// routes/api.php
Route::get('/pemesanan', function(Request $request) {
    return Pemesanan::with('user')
        ->where('gedung_id', $request->gedung_id)
        ->whereBetween('tanggal_mulai', [
            $request->start_date, 
            $request->end_date
        ])
        ->get()
        ->groupBy(function($item) {
            return explode(' ', $item->tanggal_mulai)[0];
        });
});

Route::post('/gedung/{id}/check-availability', [GedungController::class, 'checkAvailability']);
