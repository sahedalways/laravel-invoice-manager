<?php

namespace App\Services\StockManage;

use App\Models\Product;
use App\Models\Stock;

class StockService
{
  public function adjustStock(Product $product, int $quantity, string $type, string $reference)
  {
    if (!in_array($type, ['in', 'out'])) {
      throw new \Exception('Invalid stock type');
    }

    $newQuantity = $type === 'in'
      ? $product->stock_quantity + $quantity
      : $product->stock_quantity - $quantity;

    $product->update(['stock_quantity' => max($newQuantity, 0)]);

    Stock::create([
      'product_id' => $product->id,
      'type' => $type,
      'quantity' => $quantity,
      'reference' => $reference,
    ]);
  }
}
