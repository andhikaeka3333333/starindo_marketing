<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BiayaPerjalanan extends Model
{
    protected $guarded = [];

    public function marketing(): BelongsTo
    {
        return $this->belongsTo(Marketing::class, 'marketing_id');
    }
}
