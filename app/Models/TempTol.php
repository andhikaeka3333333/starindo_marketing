<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempTol extends Model
{
    protected $fillable = ['marketing_id', 'tanggal', 'customer_nama', 'customer_cp', 'kategori', 'keterangan', 'nominal', 'nama_gerbang'];
    public function marketing()
    {
        return $this->belongsTo(Marketing::class);
    }
}
