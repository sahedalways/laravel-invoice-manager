<?php

namespace App\Livewire\Admin\Reports;

use App\Exports\OrdersExport;
use App\Livewire\Admin\Components\BaseComponent;
use App\Models\Order;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class Reports extends BaseComponent
{
    public $search;

    public $perPage = 10;
    public $loaded;
    public $lastId = null;
    public $hasMore = true;
    public $editMode = false;

    public $fromDate;
    public $toDate;
    public $status;

    public $products;
    public $selectedOrder;
    public $selectedProduct;
    public $showOrderModal = false;



    public function mount()
    {
        $this->fromDate = now()->startOfMonth()->toDateString();
        $this->toDate = now()->endOfMonth()->toDateString();
        $this->products = Product::orderBy('name')->get();
        $this->loaded = collect();
        $this->loadMore();
    }

    public function render()
    {
        return view('livewire.admin.reports.reports', [
            'infos' => $this->loaded,
            'products' => $this->products,
        ]);
    }



    public function searchProduct()
    {
        $this->resetLoaded();
    }



    // Load more function
    public function loadMore()
    {
        if (!$this->hasMore) return;

        $query = Order::query()
            ->with('details.product', 'customer')
            ->when($this->search && $this->search !== '', function ($q) {
                $q->where(function ($query) {
                    $query->where('order_number', 'like', '%' . $this->search . '%')
                        ->orWhereHas(
                            'customer',
                            fn($subQuery) =>
                            $subQuery->where('name', 'like', '%' . $this->search . '%')
                        )
                        ->orWhereHas(
                            'details.product',
                            fn($subQuery) =>
                            $subQuery->where('name', 'like', '%' . $this->search . '%')
                        );
                });
            })
            ->when(
                $this->fromDate && $this->toDate,
                fn($q) =>
                $q->whereBetween(DB::raw('DATE(created_at)'), [$this->fromDate, $this->toDate])
            );


        if ($this->lastId) {
            $query->where('id', '<', $this->lastId);
        }

        $items = $query->orderBy('id', 'desc')
            ->limit($this->perPage)
            ->get();

        if ($items->count() < $this->perPage) {
            $this->hasMore = false;
        }

        if ($items->count()) {
            $this->lastId = $items->last()->id;
            $this->loaded = $this->loaded->merge($items);
        }
    }


    // Reset loaded collection
    private function resetLoaded()
    {
        $this->loaded = collect();
        $this->lastId = null;
        $this->hasMore = true;
        $this->loadMore();
    }


    public function viewOrder($id)
    {
        $this->selectedOrder = Order::with('details.product', 'customer')->find($id);

        if (!$this->selectedOrder) {
            $this->toast('Order not found!', 'error');
            return;
        }

        $this->showOrderModal = true;
        $this->dispatch('openOrderModal');
    }



    public function returnProduct($orderId, $productId)
    {
        $order = Order::with('details')->find($orderId);
        $item = $order->details->where('product_id', $productId)->first();

        if (!$item) {
            $this->toast('Product not found!', 'error');
            return;
        }

        if ($item->status === 'returned') {
            $this->toast('Product already returned!', 'warning');
            return;
        }

        $item->update(['status' => 'returned']);


        $product = Product::find($productId);
        $product->increment('stock_quantity', $item->qty);


        Stock::create([
            'product_id' => $productId,
            'type' => 'in',
            'quantity' => $item->qty,
            'reference' => $order->order_number,
        ]);

        $allReturned = $order->details->every(fn($item) => $item->status === 'returned');
        if ($allReturned) {
            $order->update(['status' => 'returned']);
            $this->resetLoaded();
        }

        $this->selectedOrder = $order->fresh();

        $this->toast('Product returned successfully and stock updated!', 'success');
    }



    public function returnOrder($orderId)
    {
        $order = Order::with('details.product')->find($orderId);

        if (!$order) {
            $this->toast('Order not found!', 'error');
            return;
        }

        if ($order->status !== 'completed') {
            $this->toast('Only completed orders can be returned!', 'warning');
            return;
        }

        // Loop through each item in the order
        foreach ($order->details as $item) {
            if ($item->status !== 'returned') {

                $item->update(['status' => 'returned']);


                $product = $item->product;
                $product->increment('stock_quantity', $item->qty);


                Stock::create([
                    'product_id' => $product->id,
                    'type' => 'in',
                    'quantity' => $item->qty,
                    'reference' => $order->order_number,
                ]);
            }
        }


        $allReturned = $order->details->every(fn($item) => $item->status === 'returned');
        if ($allReturned) {
            $order->update(['status' => 'returned']);
        }

        $this->toast('Order returned successfully and stock updated!', 'success');


        $this->resetLoaded();
    }



    public function filterSales()
    {
        $this->loaded = collect();
        $this->lastId = null;
        $this->hasMore = true;

        $query = Order::query()->with('details.product', 'customer');


        if ($this->fromDate && $this->toDate) {
            $query->whereBetween(DB::raw('DATE(created_at)'), [$this->fromDate, $this->toDate]);
        }


        if ($this->status && $this->status !== '') {
            $query->where('status', $this->status);
        }


        if ($this->selectedProduct && $this->selectedProduct !== '') {
            $query->whereHas('details', function ($q) {
                $q->where('product_id', $this->selectedProduct);
            });
        }

        if ($this->search && $this->search !== '') {
            $query->where(function ($q) {
                $q->where('order_number', 'like', '%' . $this->search . '%')
                    ->orWhereHas('customer', function ($q2) {
                        $q2->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }


        if ($this->lastId) {
            $query->where('id', '<', $this->lastId);
        }

        $items = $query->orderBy('id', 'desc')->limit($this->perPage)->get();

        if ($items->count() < $this->perPage) {
            $this->hasMore = false;
        }

        if ($items->count()) {
            $this->lastId = $items->last()->id;
            $this->loaded = $this->loaded->merge($items);
        }
    }



    public function exportReport($format = 'xlsx')
    {
        $filters = [
            'fromDate' => $this->fromDate,
            'toDate' => $this->toDate,
            'status' => $this->status,
            'productId' => $this->selectedProduct,
            'search' => $this->search,
        ];

        $fileName = 'Orders_Report_' . now()->format('Ymd_His') . '.' . $format;

        if ($format === 'pdf') {
            $orders = (new OrdersExport($filters))->collection();
            $pdf = \PDF::loadView('exports.orders_pdf', compact('orders'));
            return response()->streamDownload(fn() => print($pdf->output()), $fileName);
        }

        return Excel::download(new OrdersExport($filters), $fileName);
    }
}
