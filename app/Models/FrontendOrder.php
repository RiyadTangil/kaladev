<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrontendOrder extends Model
{
    use HasFactory;

    protected $table = "orders";
    protected $fillable = [
        'order_serial_no',
        'token',
        'user_id',
        'branch_id',
        'subtotal',
        'discount',
        'point_discount_amount',
        'rider_tip',
        'delivery_charge',
        'total_tax',
        'payment_method_fee',
        'total',
        'order_type',
        'order_datetime',
        'delivery_time',
        'preparation_time',
        'is_advance_order',
        'address',
        'payment_method',
        'payment_status',
        'status',
        'dining_table_id',
        'source'
    ];

    protected $casts = [
        'id'                    => 'integer',
        'order_serial_no'       => 'string',
        'token'                 => 'string',
        'user_id'               => 'integer',
        'branch_id'             => 'integer',
        'subtotal'              => 'decimal:6',
        'discount'              => 'decimal:6',
        'point_discount_amount' => 'decimal:6',
        'rider_tip'             => 'decimal:6',
        'delivery_charge'       => 'decimal:6',
        'total_tax'             => 'decimal:6',
        'payment_method_fee'    => 'decimal:6',
        'total'                 => 'decimal:6',
        'order_type'            => 'integer',
        'order_datetime'        => 'datetime',
        'delivery_time'         => 'string',
        'preparation_time'      => 'integer',
        'is_advance_order'      => 'integer',
        'payment_method'        => 'integer',
        'payment_status'        => 'integer',
        'status'                => 'integer',
        'dining_table_id'       => 'integer',
        'source'                => 'string'
    ];

    public function orderItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function items(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'order_items');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function address(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(OrderAddress::class, 'order_id', 'id');
    }

    public function branch(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function deliveryBoy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'delivery_boy_id', 'id');
    }

    public function coupon(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(OrderCoupon::class, 'order_id', 'id');
    }

    public function scopePending($query)
    {
        return $query->where('status', OrderStatus::PENDING);
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', OrderStatus::PROCESSING);
    }

    public function scopeOutForDelivery($query)
    {
        return $query->where('status', OrderStatus::OUT_FOR_DELIVERY);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', OrderStatus::DELIVERED);
    }

    public function scopeCanceled($query)
    {
        return $query->where('status', OrderStatus::CANCELED);
    }

    public function scopeReturned($query)
    {
        return $query->where('status', OrderStatus::RETURNED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', OrderStatus::REJECTED);
    }

    public function transaction(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Transaction::class, 'order_id', 'id');
    }

    public function diningTable(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(FrontendDiningTable::class);
    }

    public function pointDiscount(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(OrderPointDiscount::class, 'order_id');
    }
}