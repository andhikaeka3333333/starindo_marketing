<?php

// app/Models/Pengajuan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    protected $fillable = [
        'marketing_id', 'tanggal', 'customer_nama',
        'customer_cp', 'customer_alamat',
        'jenis_pengajuan', 'nominal_value', 'alamat'
    ];

    public function marketing()
    {
        return $this->belongsTo(Marketing::class);
    }
}
