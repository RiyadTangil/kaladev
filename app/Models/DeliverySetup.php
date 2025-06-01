<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeliverySetup extends Model
{
    use HasFactory;

    protected $table = "delivery_setups";

    protected $fillable = [
        'branch_id',
        'kilometer_range',
        'minimum_order_amount',
        'delivery_charge',
    ];
    protected $casts = [
        'id'                   => 'integer',
        'branch_id'            => 'integer',
        'kilometer_range'      => 'string',
        'minimum_order_amount' => 'decimal:6',
        'delivery_charge'      => 'decimal:6',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}