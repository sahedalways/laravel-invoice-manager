<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['image', 'name', 'sku', 'description', 'price', 'stock_quantity'];



    protected $appends = ['image_url'];

    // Accessor for image url
    public function getImageUrlAttribute()
    {
        return $this->image
            ? getFileUrl($this->image)
            : asset('assets/img/default-image.jpg');
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }


    protected static function booted()
    {
        static::created(function ($product) {
            if ($product->stock_quantity > 0) {
                Stock::create([
                    'product_id' => $product->id,
                    'type' => 'in',
                    'quantity' => $product->stock_quantity,
                    'reference' => 'Initial stock',
                ]);
            }
        });
    }
}
