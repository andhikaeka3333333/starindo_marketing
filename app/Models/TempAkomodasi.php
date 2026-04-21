<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TempAkomodasi extends Model
{
    use HasFactory;

    // Nama tabel disesuaikan (Contoh untuk TempAkomodasi)
    protected $table = 'temp_akomodasi';

    protected $fillable = [
        'marketing_id',
        'tanggal',
        'customer_nama',
        'customer_cp',
        'kategori',
        'level',
        'wilayah',
        'durasi',
        'nominal',
    ];

    public function marketing()
    {
        return $this->belongsTo(Marketing::class);
    }
}
