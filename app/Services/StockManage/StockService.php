<?php

namespace App\Services\StockManage;

use App\Models\Product;
use App\Models\Stock;

class StockService
{

  /**
   * Get a product by ID or fail
   *
   * @param int $productId
   * @return Product
   */
  public function getProduct(int $productId): Product
  {
    return Product::findOrFail($productId);
  }


  /**
   * Adjust product stock
   *
   * @param int $productId
   * @param int $quantity
   * @param string $type ('in' or 'out')
   * @param string $reference
   */
  public function adjustStock(int $productId, int $quantity, string $type, string $reference)
  {

    $product = $this->getProduct($productId);


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

    return $product;
  }



  /**
   * Get stock history for a product
   *
   * @param int $productId
   * @return array
   */
  public function getStockHistory(int $productId): array
  {
    $product = $this->getProduct($productId);


    $stocks = $product->stocks()->latest()->get();


    $groupedStocks = $stocks->groupBy(function ($stock) {
      return $stock->created_at->format('Y-m-d') . '|' . $stock->type . '|' . $stock->reference;
    })->map(function ($group) {
      $first = $group->first();
      return [
        'date' => $first->created_at->format('Y-m-d'),
        'type' => $first->type,
        'reference' => $first->reference,
        'quantity' => $group->sum('quantity'),
      ];
    })->values()->toArray();

    return [
      'product_name' => $product->name,
      'history' => $groupedStocks,
    ];
  }
}
