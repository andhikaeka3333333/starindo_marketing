<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bensin extends Model
{
    protected $table = 'bensins';
    protected $fillable = ['tanggal', 'nominal', 'km', 'keterangan'];
}
