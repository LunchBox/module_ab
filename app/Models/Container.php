<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Container extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_campus_id',
        'to_campus_id',
        'trucker_id',
        'status'
    ];

    public function fromCampus()
    {
        return $this->belongsTo(Campus::class, 'from_campus_id');
    }

    public function toCampus()
    {
        return $this->belongsTo(Campus::class, 'to_campus_id');
    }

    public function trucker()
    {
        return $this->belongsTo(Staff::class, 'trucker_id');
    }

    public function packages()
    {
        return $this->hasMany(Package::class);
    }
}