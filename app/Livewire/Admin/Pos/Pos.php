<?php

namespace App\Livewire\Admin\Pos;

use App\Livewire\Admin\Components\BaseComponent;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Services\UserService;

class Pos extends BaseComponent
{
    public $name, $email, $phone, $address;
    public $search = '';
    public $cart = [];
    public $customers = [];
    public $cartItems = [];
    public $selectedCustomer = '';
    public $cartTotal = 0;


    public $orderNumber, $todayDate;
    public $showOrderModal = false;
    public $discount = 0;
    public $finalTotal = 0;

    public function mount()
    {
        $this->generateOrderNumber();
        $this->todayDate = now()->format('d M Y');
    }



    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|string|unique:customers,phone|max:20',
            'address' => 'required|string|max:500',
        ];
    }

    public function render()
    {
        $products = Product::query()
            ->when($this->search, fn($q) =>
            $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('sku', 'like', "%{$this->search}%"))
            ->latest()
            ->get();

        $this->customers = Customer::orderBy('name')->get();
        $this->updateTotal();

        return view('livewire.admin.pos.pos', compact('products'));
    }

    public function addToCart($productId)
    {
        $product = Product::findOrFail($productId);

        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['quantity']++;
        } else {
            $this->cart[$productId] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'price'    => $product->price,
                'sku'    => $product->sku,
                'stock_quantity'    => $product->stock_quantity,
                'quantity' => 1,
            ];
        }

        $this->updateTotal();
    }

    public function increaseQty($productId)
    {
        if (isset($this->cart[$productId])) {
            if ($this->cart[$productId]['quantity'] < $this->cart[$productId]['stock_quantity']) {
                $this->cart[$productId]['quantity']++;
                $this->updateTotal();
            } else {
                $this->toast("Cannot exceed available stock!", 'error');
            }
        }
    }

    public function decreaseQty($productId)
    {
        if (isset($this->cart[$productId])) {
            if ($this->cart[$productId]['quantity'] > 1) {
                $this->cart[$productId]['quantity']--;
            } else {
                unset($this->cart[$productId]);
            }
            $this->updateTotal();
        }
    }

    public function removeItem($productId)
    {
        unset($this->cart[$productId]);
        $this->updateTotal();
    }

    public function clearCart()
    {
        $this->cart = [];
        $this->cartTotal = 0;
    }

    private function updateTotal()
    {
        $this->cartTotal = collect($this->cart)->sum(fn($item) =>
        $item['price'] * $item['quantity']);
    }

    public function openOrderModal()
    {
        if (!$this->selectedCustomer) {
            $this->toast('Select a customer first!', 'error');
            return;
        }

        if (empty($this->cart)) {
            $this->toast('Your cart is empty!', 'error');
            return;
        }

        $this->finalTotal = $this->cartTotal;
        $this->showOrderModal = true;
        $this->dispatch('show-order-modal');
    }

    public function updatedDiscount($value)
    {
        $discountValue = is_numeric($value) ? $value : 0;
        $this->finalTotal = $this->cartTotal - $discountValue;
    }

    public function confirmOrder()
    {
        // Save order into DB
        $order = Order::create([
            'order_number' => $this->orderNumber,
            'customer_id' => $this->selectedCustomer,
            'discount'    => $this->discount,
            'total_price' => $this->finalTotal,
            'status'      => 'completed',
            'date'        => now(),
        ]);


        foreach ($this->cart as $item) {
            $order->details()->create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'qty'        => $item['quantity'],
                'price'      => $item['price'],
                'total'      => $item['price'] * $item['quantity'],
            ]);
        }

        // Clear cart and reset
        $this->clearCart();
        $this->generateOrderNumber();
        $this->discount = 0;
        $this->selectedCustomer = '';
        $this->finalTotal = 0;
        $this->showOrderModal = false;

        $this->dispatch('close-order-modal');
        $this->toast('Order placed successfully!', 'success');
    }



    public function addCustomer(UserService $userService)
    {
        $this->validate($this->rules());

        $customer = $userService->register([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
        ]);


        $this->customers = Customer::orderBy('name')->get();
        $this->selectedCustomer = $customer->id;


        $this->dispatch('closemodal');
        $this->toast('Customer added successfully!', 'success');

        // Reset form fields
        $this->reset(['name', 'email', 'phone', 'address']);
    }



    private function generateOrderNumber()
    {
        $lastOrder = Order::latest('id')->first();
        $nextNumber = $lastOrder ? $lastOrder->id + 1 : 1;

        $this->orderNumber = 'ORD-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
