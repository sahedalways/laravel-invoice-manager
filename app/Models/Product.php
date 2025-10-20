<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['image', 'name', 'sku', 'description', 'price', 'stock_quantity'];

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}
