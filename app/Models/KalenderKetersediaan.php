<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KalenderKetersediaan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_kalender';
    public $incrementing = false;
    protected $keyType = 'string';

    // Add this line to specify the correct table name
    protected $table = 'kalender_ketersediaan';

    protected $fillable = [
        'id_gedung',
        'tanggal',
        'status',
        'keterangan',
    ];


}