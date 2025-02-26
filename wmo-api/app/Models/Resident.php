<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resident extends Model {
    use HasFactory;

    protected $fillable = ['name', 'email', 'home_address_id', 'default_budget', 'remaining_budget'];
}
