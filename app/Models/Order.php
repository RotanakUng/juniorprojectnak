<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_name',
        'phone',
        'address',
        'payment_type',
        'payment_status',
        'delivery_type',
        'shipping_zone',
        'status',
        'total_price',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function recalculateTotal(): self
    {
        $total = $this->orderItems->sum(function (OrderItem $item) {
            return $item->total_price;
        });

        $this->total_price = $total;

        return $this;
    }
}
