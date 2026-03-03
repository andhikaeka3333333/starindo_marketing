<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tol extends Model
{
    protected $table = 'tols';
    protected $fillable = ['keterangan', 'tipe', 'nominal', 'saldo_akhir'];
}
