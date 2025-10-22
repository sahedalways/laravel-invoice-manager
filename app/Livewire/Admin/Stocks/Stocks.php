<?php

namespace App\Livewire\Admin\Stocks;

use App\Exports\StockExport;
use App\Livewire\Admin\Components\BaseComponent;
use App\Models\Product;
use App\Services\StockManage\StockService;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class Stocks extends BaseComponent
{
    public  $search;

    public $stockHistory = [];
    public $currentProductName;
    public $perPage = 10;
    public $loaded;
    public $lastId = null;
    public $hasMore = true;
    public $editMode = false;

    protected $stockService;



    public function boot(StockService $stockService)
    {
        $this->stockService = $stockService;
    }


    public function mount()
    {
        $this->loaded = collect();
        $this->loadMore();
    }

    public function render()
    {
        return view('livewire.admin.stocks.stocks', [
            'infos' => $this->loaded
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

        $query = Product::query();
        if ($this->search && $this->search != '') {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('sku', 'like', '%' . $this->search . '%');
            });
        }

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



    public function adjustStock($productId, $quantity, $type, $reference)
    {

        $this->stockService->adjustStock($productId, $quantity, $type, $reference);

        $this->toast('Stock has been updated!', 'success');
        $this->resetLoaded();
    }

    public function openStockModal($productId)
    {
        $stockData = $this->stockService->getStockHistory($productId);

        $this->currentProductName = $stockData['product_name'];
        $this->stockHistory = $stockData['history'];

        $this->dispatch('openStockModal');
    }


    public function exportStock($type)
    {
        $products = $this->loaded->map(function ($p) {
            return [
                'SKU' => $p->sku,
                'Name' => $p->name,
                'Stock' => $p->stock_quantity
            ];
        })->toArray();

        $fileName = 'stock_report_' . now()->format('Ymd_His');

        if ($type === 'pdf') {
            $pdf = Pdf::loadView('exports.stock_pdf', compact('products'));
            return response()->streamDownload(fn() => print($pdf->output()), $fileName . '.pdf');
        } elseif ($type === 'excel') {
            return Excel::download(new StockExport($products), $fileName . '.xlsx');
        } elseif ($type === 'csv') {
            return Excel::download(new StockExport($products), $fileName . '.csv');
        }
    }
}
