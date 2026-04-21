<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempBensin extends Model
{
    protected $fillable = ['marketing_id', 'tanggal', 'customer_nama', 'customer_cp', 'kategori', 'km', 'keterangan', 'nominal'];
    public function marketing()
    {
        return $this->belongsTo(Marketing::class);
    }
}
