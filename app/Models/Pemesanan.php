<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;



class Pemesanan extends Model
{
    use HasFactory; 

    protected $table = 'pemesanan'; // Explicit table name
    protected $primaryKey = 'id_pemesanan';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    public function getStatusColorAttribute()
    {
        return [
            'menunggu_pembayaran' => 'warning',
            'dibayar' => 'primary',
            'dikonfirmasi' => 'info',
            'selesai' => 'success',
            'dibatalkan' => 'danger',
        ][$this->status] ?? 'secondary';
    }
    

    protected $fillable = [
        'id_pemesanan',
        'user_id',
        'id_gedung',
        'tanggal_mulai',
        'tanggal_selesai',
        'nama_acara',
        'total_harga',
        'deposit_amount',
        'remaining_amount',
        'deposit_paid_at',
        'full_payment_paid_at',
        'reminder_sent_at',
        'status',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    

    public function gedung()
    {
        return $this->belongsTo(Gedung::class, 'id_gedung', 'id_gedung');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_pemesanan', 'id_pemesanan');
    }
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'dibatalkan')
            ->where('tanggal_selesai', '>', now());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('tanggal_mulai', '>', now());
    }

    public function updateWithLock(array $attributes)
    {
        return DB::transaction(function () use ($attributes) {
            $freshPemesanan = $this->fresh();
            
            if ($freshPemesanan->version !== $this->version) {
                throw new \Exception('Data pemesanan telah diubah oleh proses lain');
            }
            
            $attributes['version'] = $this->version + 1;
            
            return $freshPemesanan->update($attributes);
        });
    }
}