<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiderTip extends Model
{
    protected $table = "rider_tips";
     protected $fillable = ['label', 'amount', 'type'];

    protected $casts = [
        'id'     => 'integer',
        'label'  => 'string',
        'amount' => 'decimal:6',
        'type'   => 'string',
    ];
}
