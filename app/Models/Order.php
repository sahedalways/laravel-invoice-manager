<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'customer_id',
        'discount',
        'total_price',
        'status',
        'date',
    ];



    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }


    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
