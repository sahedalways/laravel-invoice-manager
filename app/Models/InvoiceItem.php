<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = ['invoice_id', 'product_id', 'quantity', 'price', 'subtotal'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    protected static function booted()
    {
        static::created(function ($item) {
            $item->product->decrement('stock_quantity', $item->quantity);
            Stock::create([
                'product_id' => $item->product_id,
                'type' => 'out',
                'quantity' => $item->quantity,
                'reference' => 'Invoice #' . $item->invoice_id,
            ]);
        });
    }
}
