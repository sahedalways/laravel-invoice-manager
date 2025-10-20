<?php

namespace App\Livewire\Products;

use App\Livewire\Components\BaseComponent;
use App\Models\Product;
use App\Services\ProductManage\ProductManageService;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;

class Products extends BaseComponent
{
    public $items, $item, $id, $name, $price, $stock_quantity, $image, $current_image, $description, $sku, $search;
    public $expandedDescriptions = [];

    public $perPage = 10;
    public $loaded;
    public $lastId = null;
    public $hasMore = true;
    public $editMode = false;

    protected $productManage;
    protected $listeners = ['deleteItem'];

    use WithFileUploads;

    public function boot(ProductManageService $productManage)
    {
        $this->productManage = $productManage;
    }

    public function getRules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'name')->ignore($this->item->id ?? null),
            ],
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'description' => 'nullable|string|max:255',
            'image' => $this->editMode
                ? 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
                : 'required|image|mimes:jpg,jpeg,png,webp|max:2048',

        ];
    }

    public function mount()
    {
        $this->loaded = collect();
        $this->loadMore();
    }

    public function render()
    {
        return view('livewire.products.products', [
            'infos' => $this->loaded
        ]);
    }

    /* reset input fields */
    public function resetInputFields()
    {
        $this->item = '';
        $this->name = '';
        $this->price = '';
        $this->description = '';
        $this->image = '';
        $this->stock_quantity = '';
        $this->current_image = '';
        $this->sku = '';
        $this->sku =  $this->productManage->generateSKU();


        $this->resetErrorBag();
    }

    /* store event service data */
    public function store()
    {
        $this->validate($this->getRules());

        $this->productManage->saveProductManage([
            'name' => $this->name,
            'price' => $this->price,
            'stock_quantity' => $this->stock_quantity,
            'image' => $this->image,
            'description' => $this->description,
            'sku' => $this->sku,

        ]);

        $this->resetInputFields();
        $this->dispatch('closemodal');
        $this->toast('Product saved successfully!', 'success');
        $this->resetLoaded();
    }

    public function edit($id)
    {
        $this->editMode = true;
        $this->item = $this->productManage->getSingleProduct($id);

        if (!$this->item) {
            $this->toast('Product not found!', 'error');
            return;
        }

        $this->name = $this->item->name;
        $this->price = $this->item->price;
        $this->stock_quantity = $this->item->stock_quantity;
        $this->description = $this->item->description;
        $this->current_image = $this->item->image_url;
        $this->sku = $this->item->sku;
    }

    public function update()
    {
        $this->validate($this->getRules());

        if (!$this->item) {
            $this->toast('Product not found!', 'error');
            return;
        }

        $this->productManage->updateProductManageSingleData($this->item, [
            'name' => $this->name,
            'price' => $this->price,
            'stock_quantity' => $this->stock_quantity,
            'description' => $this->description,
            'image' => $this->image,
        ]);

        $this->resetInputFields();
        $this->editMode = false;
        $this->dispatch('closemodal');
        $this->toast('Product has been updated successfully!', 'success');
        $this->resetLoaded();
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

    public function deleteItem($id)
    {
        $this->productManage->deleteProductManage($id);
        $this->toast('Product has been deleted!', 'success');
        $this->resetLoaded();
    }


    public function toggleDescription($productId)
    {
        if (isset($this->expandedDescriptions[$productId]) && $this->expandedDescriptions[$productId]) {
            $this->expandedDescriptions[$productId] = false;
        } else {
            $this->expandedDescriptions[$productId] = true;
        }
    }
}
