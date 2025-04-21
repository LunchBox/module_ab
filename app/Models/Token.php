<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'tokenable_id',
        'tokenable_type',
        'last_used_at',
        'expires_at'
    ];

    public function tokenable()
    {
        return $this->morphTo();
    }
}