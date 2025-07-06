<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Gedung extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_gedung';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'gedung';

    protected $fillable = [
        'id_gedung', 'id_kategori', 'nama', 'lokasi', 'daerah',
        'kapasitas', 'fasilitas', 'harga', 'deskripsi'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id_gedung)) {
                $model->id_gedung = (string) Str::uuid();
            }
        });
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriGedung::class, 'id_kategori', 'id_kategori');
    }


    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'id_gedung', 'id_gedung');
    }
}