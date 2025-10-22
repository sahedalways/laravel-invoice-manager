<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomersExport implements FromCollection, WithHeadings
{
    protected $customers;

    public function __construct(Collection $customers)
    {
        $this->customers = $customers;
    }

    public function collection()
    {
        return $this->customers->map(function ($customer) {
            return [
                'ID' => $customer->id,
                'Name' => $customer->name,
                'Email' => $customer->email,
                'Phone' => $customer->phone,
                'Address' => $customer->address,
                'Created At' => $customer->created_at->format('d M Y'),
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Name', 'Email', 'Phone', 'Address', 'Created At'];
    }
}
