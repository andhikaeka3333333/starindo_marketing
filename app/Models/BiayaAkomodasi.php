<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiayaAkomodasi extends Model
{
    protected $table = 'biaya_akomodasi';

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
