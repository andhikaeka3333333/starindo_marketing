<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiayaOperasional extends Model
{
    protected $table = 'biaya_operasional';

    protected $fillable = [
        'marketing_id',
        'tanggal',
        'customer_nama',
        'customer_cp',
        'kategori',
        'keterangan',
        'nominal',
    ];

    public function marketing()
    {
        return $this->belongsTo(Marketing::class);
    }
}
