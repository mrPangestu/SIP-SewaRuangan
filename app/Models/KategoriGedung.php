<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KategoriGedung extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_kategori';
    public $incrementing = false;
    protected $keyType = 'string';

    // Add this line to specify the correct table name
    protected $table = 'kategori_gedung';

    protected $fillable = [
        'id_kategori',
        'nama_kategori',
        'deskripsi',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id_kategori)) {
                $model->id_kategori = (string) Str::uuid();
            }
        });
    }

    public function gedung()
    {
        return $this->hasMany(Gedung::class, 'id_kategori', 'id_kategori');
    }
}