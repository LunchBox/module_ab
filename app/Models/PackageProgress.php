<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'status',
        'campus_id',
        'returning',
        'datetime'
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }
}