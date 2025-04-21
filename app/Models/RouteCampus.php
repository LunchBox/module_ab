<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteCampus extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'campus_id',
        'order'
    ];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }
}