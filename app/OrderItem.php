<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the order related to the item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Set the item price in cents.
     *
     * @param  string  $value
     * @return void
     */
    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = is_float($value) ? $value * 100 : $value;
    }

    /**
     * Get the item price as float.
     *
     * @return float|int
     */
    public function getFloatPriceAttribute()
    {
        return $this->price / 100;
    }
}
