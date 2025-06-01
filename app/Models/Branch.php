<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $table = "branches";
    protected $fillable = ['name', 'email', 'phone', 'latitude', 'longitude', 'zone', 'city', 'state', 'zip_code', 'address', 'status', 'webhook_url'];
    protected $casts = [
        'id'        => 'integer',
        'name'      => 'string',
        'email'     => 'string',
        'phone'     => 'string',
        'latitude'  => 'string',
        'longitude' => 'string',
        'zone'      => 'string',
        'city'      => 'string',
        'state'     => 'string',
        'zip_code'  => 'string',
        'address'   => 'string',
        'status'    => 'integer',
        'webhook_url'=> 'string'
    ];
}
