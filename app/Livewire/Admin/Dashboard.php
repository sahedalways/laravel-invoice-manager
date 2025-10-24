<?php

namespace App\Livewire\Admin;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Livewire\Component;


class Dashboard extends Component
{

    public $todaySales, $totalSales;
    public $totalProducts, $totalCustomers;
    public $completedOrders, $returnedOrders, $pendingOrders, $processingOrders;
    public $ordersChartData = [];

    public function mount()
    {
        // Sales
        $this->todaySales = Order::whereDate('created_at', now())->sum('total_price');
        $this->totalSales = Order::sum('total_price');

        // Products & Customers
        $this->totalProducts = Product::count();
        $this->totalCustomers = Customer::count();

        // Orders by status
        $this->completedOrders = Order::where('status', 'completed')->count();
        $this->returnedOrders = Order::where('status', 'returned')->count();
        $this->pendingOrders = Order::where('status', 'pending')->count();
        $this->processingOrders = Order::where('status', 'processing')->count();

        // Chart data
        $this->ordersChartData = [
            $this->pendingOrders,
            $this->processingOrders,
            $this->completedOrders,
            $this->returnedOrders,
        ];
    }


    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
