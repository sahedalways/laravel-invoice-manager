<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithHeadings
{
    public $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Order::with('customer', 'details.product');

        // Apply filters
        if (!empty($this->filters['fromDate']) && !empty($this->filters['toDate'])) {
            $query->whereBetween('created_at', [$this->filters['fromDate'], $this->filters['toDate']]);
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['productId'])) {
            $query->whereHas('details', fn($q) => $q->where('product_id', $this->filters['productId']));
        }

        if (!empty($this->filters['search'])) {
            $query->where(function ($q) {
                $q->where('order_number', 'like', '%' . $this->filters['search'] . '%')
                    ->orWhereHas('customer', fn($q2) => $q2->where('name', 'like', '%' . $this->filters['search'] . '%'));
            });
        }

        return $query->get()->map(function ($order) {
            return [
                'Order No' => $order->order_number,
                'Customer' => $order->customer->name ?? 'Guest',
                'Date' => $order->created_at->format('d-m-Y'),
                'Status' => ucfirst($order->status),
                'Total' => $order->total_price,
                'Payment' => 'Cash',
            ];
        });
    }

    public function headings(): array
    {
        return ['Order No', 'Customer', 'Date', 'Status', 'Total', 'Payment'];
    }
}
