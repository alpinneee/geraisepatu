<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'size',
        'quantity',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'size' => 'integer',
        'quantity' => 'integer',
    ];

    /**
     * Get the user that owns the cart item.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product that belongs to the cart item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the subtotal for this cart item.
     */
    public function getSubtotalAttribute()
    {
        $price = $this->product->discount_price ?? $this->product->price;
        return $price * $this->quantity;
    }
}
