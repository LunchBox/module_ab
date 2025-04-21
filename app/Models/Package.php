<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracking_number',
        'client_id',
        'from_campus_id',
        'from_address',
        'to_campus_id',
        'to_address',
        'recipient_name',
        'recipient_phone_number',
        'status',
        'returning',
        'pickup_courier_id',
        'delivery_courier_id',
        'container_id'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function fromCampus()
    {
        return $this->belongsTo(Campus::class, 'from_campus_id');
    }

    public function toCampus()
    {
        return $this->belongsTo(Campus::class, 'to_campus_id');
    }

    public function pickupCourier()
    {
        return $this->belongsTo(Staff::class, 'pickup_courier_id');
    }

    public function deliveryCourier()
    {
        return $this->belongsTo(Staff::class, 'delivery_courier_id');
    }

    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    public function progresses()
    {
        return $this->hasMany(PackageProgress::class);
    }
}