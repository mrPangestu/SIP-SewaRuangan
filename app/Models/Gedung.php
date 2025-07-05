<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gedung extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_gedung';
    public $incrementing = false;
    protected $keyType = 'string';

    // Add this line to specify the correct table name
    protected $table = 'gedung';

    protected $fillable = [
        'id_gedung',
        'id_kategori',
        'nama',
        'lokasi',
        'daerah',
        'kapasitas',
        'fasilitas',
        'harga',
        'deskripsi',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriGedung::class, 'id_kategori', 'id_kategori');
    }

    public function kalenderKetersediaan()
    {
        return $this->hasMany(KalenderKetersediaan::class, 'id_gedung', 'id_gedung');
    }

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'gedung_id', 'id_gedung');
    }
}