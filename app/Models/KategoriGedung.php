<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriGedung extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_kategori';
    public $incrementing = false;
    protected $keyType = 'string';

    // Add this line to specify the correct table name
    protected $table = 'kategori_gedung';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    public function gedung()
    {
        return $this->hasMany(Gedung::class, 'id_kategori', 'id_kategori');
    }
}