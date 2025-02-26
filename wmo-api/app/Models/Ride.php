<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ride extends Model {
    use HasFactory;

    protected $fillable = ['resident_id', 'taxi_id', 'pickup_address_id', 'destination_address_id', 'distance', 'status'];
}
