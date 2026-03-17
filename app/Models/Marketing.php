<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marketing extends Model
{
    protected $fillable = ['nama', 'level', 'no_kartu_tol', 'sisa_saldo_tol'];

    public function biayaAkomodasi() { return $this->hasMany(BiayaAkomodasi::class); }
    public function biayaOperasional() { return $this->hasMany(BiayaOperasional::class); }
    public function biayaTol() { return $this->hasMany(BiayaTol::class); }
    public function biayaBensin() { return $this->hasMany(BiayaBensin::class); }
}
