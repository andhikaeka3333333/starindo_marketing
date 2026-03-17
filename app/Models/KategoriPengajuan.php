<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriPengajuan extends Model
{
    protected $fillable = ['nama_kategori'];

    public function pengajuans() {
        return $this->hasMany(Pengajuan::class);
    }
}
    
