<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class TarifPerjalanan extends Model
{
    protected $table = 'tarif_perjalanan';  
    protected $fillable = ['kategori', 'wilayah', 'level', 'nominal'];
}
