<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'buyer_id',
        'seller_id',
        'total',
        'status',
        'bill_code',
        'payment_ref',
        'paid_at',
    ];

    // ✅ MUST MATCH CONTROLLER
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
