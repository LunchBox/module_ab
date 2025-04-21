<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'password',
        'firstname',
        'lastname',
        'phone_number',
        'role',
        'campus_id',
        'online',
        'remaining_capacity',
        'total_picked',
        'total_delivered',
        'route_id',
        'plate_number',
        'current_campus_id',
        'total_unloaded'
    ];

    protected $hidden = [
        'password',
    ];

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function currentCampus()
    {
        return $this->belongsTo(Campus::class, 'campus_id');
    }
}