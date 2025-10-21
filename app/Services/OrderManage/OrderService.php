<?php

namespace App\Services\OrderManage;

use App\Models\Order;
use App\Models\Product;
use App\Models\Stock;

class OrderService
{
  /**
   * Create an order with order details and update stock.
   *
   * @param int $customerId
   * @param array $cart
   * @param float $discount
   * @param string $orderNumber
   * @return Order
   */
  public function createOrder($customerId, $cart, $discount, $orderNumber)
  {
    // Save order
    $order = Order::create([
      'order_number' => $orderNumber,
      'customer_id'  => $customerId,
      'discount'     => $discount,
      'total_price'  => collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']) - $discount,
      'status'       => 'completed',
      'date'         => now(),
    ]);

    foreach ($cart as $item) {
      // Save order details
      $order->details()->create([
        'product_id' => $item['id'],
        'qty'        => $item['quantity'],
        'price'      => $item['price'],
        'total'      => $item['price'] * $item['quantity'],
      ]);

      // Update product stock
      $product = Product::find($item['id']);
      if ($product) {
        $newStock = max(0, $product->stock_quantity - $item['quantity']);
        $product->update(['stock_quantity' => $newStock]);
      }

      // Record stock movement
      Stock::create([
        'product_id' => $item['id'],
        'type'       => 'out',
        'quantity'   => $item['quantity'],
        'reference'  => $orderNumber,
      ]);
    }

    return $order;
  }
}
