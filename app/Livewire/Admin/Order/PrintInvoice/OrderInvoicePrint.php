<?php

namespace App\Livewire\Admin\Order\PrintInvoice;

use App\Livewire\Admin\Components\BaseComponent;
use App\Models\Order;

class OrderInvoicePrint extends BaseComponent
{
    public $order;
    public $orderdetails;

    public function mount($id)
    {
        $this->order = Order::with('customer')->findOrFail($id);
        $this->orderdetails = $this->order->details;
    }

    public function render()
    {
        return view('livewire.admin.order.print-invoice.order-invoice-print');
    }
}
