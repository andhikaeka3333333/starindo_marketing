<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Omset extends Model
{
    protected $fillable = ['marketing_id', 'periode_dari', 'periode_sampai', 'nominal'];

    public function marketing(): BelongsTo
    {
        return $this->belongsTo(Marketing::class);
    }
}
