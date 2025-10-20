<div class="row g-3">
    <!-- Left: Product List -->
    <div class="col-lg-7">
        <div class="card shadow-sm border-0" style="margin-top:20px;"> <!-- Added margin-top -->
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-white">POS</h6>

            </div>

            <div class="card-body" style="padding:12px;">

                <input type="text" class="form-control form-control-sm w-50 mb-3" placeholder="Search products..."
                    wire:model="search" wire:keyup="set('search', $event.target.value)">

                <div class="row" style="gap:12px; display:flex; flex-wrap:wrap;">
                    @forelse($products as $product)
                        <div style="width:calc(25% - 12px); padding:6px;">
                            <div @if ($product->stock_quantity > 0) wire:click="addToCart({{ $product->id }})" @endif
                                style="cursor:{{ $product->stock_quantity > 0 ? 'pointer' : 'not-allowed' }};
                           border:1px solid #e0e0e0; 
                           border-radius:10px; 
                           overflow:hidden; 
                           transition:all 0.3s ease; 
                           box-shadow:0 3px 8px rgba(0,0,0,0.12); 
                           display:flex; 
                           flex-direction:column; 
                           height:100%;
                           opacity: {{ $product->stock_quantity > 0 ? '1' : '0.5' }};"
                                onmouseover="if({{ $product->stock_quantity > 0 ? 'true' : 'false' }}) this.style.boxShadow='0 5px 15px rgba(0,0,0,0.2)';"
                                onmouseout="if({{ $product->stock_quantity > 0 ? 'true' : 'false' }}) this.style.boxShadow='0 3px 8px rgba(0,0,0,0.12)';">

                                <!-- Image & Price Badge -->
                                <div style="position:relative;">
                                    <img src="{{ $product->image_url ?? 'https://via.placeholder.com/150' }}"
                                        alt="{{ $product->name }}"
                                        style="width:100%; height:100px; object-fit:cover; border-bottom:1px solid #f0f0f0;">
                                    <span
                                        style="position:absolute; top:6px; left:6px; background-color:#1E3A8A; color:white; font-size:0.65rem; padding:2px 6px; border-radius:4px; font-weight:600;">
                                        ${{ number_format($product->price, 2) }}
                                    </span>
                                </div>

                                <!-- Product Info -->
                                <div style="padding:6px 4px; text-align:center; flex-grow:1;">
                                    <h6 title="{{ $product->name }}"
                                        style="margin:2px 0; font-size:0.78rem; font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                        {{ $product->name }}
                                    </h6>
                                    <small style="font-size:0.65rem; color:#666;">{{ $product->sku }}</small>
                                </div>

                                <!-- Out of Stock Badge -->
                                @if ($product->stock_quantity == 0)
                                    <div
                                        style="text-align:center; padding:4px 0; background-color:#f8d7da; color:#842029; font-size:0.7rem; font-weight:600; border-top:1px solid #f5c2c7;">
                                        Out of Stock
                                    </div>
                                @endif

                            </div>
                        </div>
                    @empty
                        <div style="width:100%; text-align:center; padding:30px; color:#999; font-size:0.9rem;">
                            <i class="fas fa-box-open" style="font-size:28px; margin-bottom:10px;"></i><br>
                            No products found
                        </div>
                    @endforelse
                </div>
            </div>



        </div>
    </div>

    <!-- Right: Cart & Customer -->
    <div class="col-lg-5 ">
        <div class="card "
            style="margin-top:90px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.12); border:none;">


            <!-- Cart Table -->
            <div style="max-height:480px; overflow-y:auto; padding:12px;">
                @if (count($cart) > 0)
                    <table style="width:100%; border-collapse:collapse; font-size:0.85rem;">
                        <thead>
                            <tr style="background-color:#f8f9fa; border-bottom:1px solid #dee2e6;">
                                <th style="text-align:left; padding:6px;">Name</th>
                                <th style="text-align:left; padding:6px;">SKU</th>
                                <th style="text-align:center; padding:6px;">Qty</th>
                                <th style="text-align:right; padding:6px;">Price</th>
                                <th style="text-align:center; padding:6px;">×</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cart as $item)
                                <tr style="border-bottom:1px solid #f1f1f1;">
                                    <td style="padding:6px; font-weight:600;">{{ $item['name'] }}</td>
                                    <td style="padding:6px; color:#777;">{{ $item['sku'] ?? '-' }}</td>
                                    <td style="padding:6px; text-align:center;">
                                        <div style="display:flex; justify-content:center; align-items:center; gap:4px;">
                                            <button wire:click="decreaseQty({{ $item['id'] }})"
                                                style="border:none; background:#f0f0f0; padding:2px 6px; border-radius:4px; cursor:pointer;">-</button>
                                            <span
                                                style="min-width:24px; text-align:center;">{{ $item['quantity'] }}</span>
                                            <button wire:click="increaseQty({{ $item['id'] }})"
                                                style="border:none; background:#f0f0f0; padding:2px 6px; border-radius:4px; cursor:pointer;">+</button>
                                        </div>
                                    </td>
                                    <td style="padding:6px; text-align:right;">${{ number_format($item['price'], 2) }}
                                    </td>
                                    <td style="padding:6px; text-align:center;">
                                        <button wire:click="removeItem({{ $item['id'] }})"
                                            style="border:none; background:#f8d7da; color:#842029; padding:2px 6px; border-radius:4px; cursor:pointer;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p style="text-align:center; color:#999; padding:30px 0;">Your cart is empty.</p>
                @endif
            </div>

            <!-- Footer -->
            <div style="padding:12px; border-top:1px solid #e0e0e0;">
                <!-- Customer Select -->
                <div class="d-flex align-items-start gap-2 mb-3">
                    <div class="flex-grow-1">
                        <label class="form-label mb-1" style="font-size:0.85rem; font-weight:600;">Select
                            Customer</label>
                        <select id="customer-select" wire:model="selectedCustomer" class="form-select form-select-sm">
                            <option value="">-- Choose Customer --</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">
                                    {{ $customer->name }} ({{ $customer->email }}, {{ $customer->phone }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Add Customer Button -->
                    <div style="margin-top:27px;">
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                            data-bs-target="#adduser">
                            <i class="fas fa-user-plus"></i>
                        </button>
                    </div>
                </div>



                <!-- Total -->
                <div
                    style="display:flex; justify-content:space-between; font-weight:600; font-size:0.95rem; padding-top:6px; border-top:1px solid #e0e0e0;">
                    <span>Gross Total:</span>
                    <span>${{ number_format($cartTotal, 2) }}</span>
                </div>

                <!-- Buttons -->
                <div style="display:flex; gap:8px; margin-top:12px;">
                    <button wire:click="checkout"
                        style="flex:1; background-color:#1E3A8A; color:white; padding:8px 0; border:none; border-radius:6px; font-weight:600; cursor:pointer;">
                        <i class="fas fa-credit-card me-1"></i> Pay
                    </button>
                    <button wire:click="clearCart"
                        style="background-color:#dc3545; color:white; padding:8px 0; border:none; border-radius:6px; font-weight:600; cursor:pointer; min-width:90px;">
                        Clear All
                    </button>
                </div>
            </div>
        </div>
    </div>



    <div wire:ignore.self class="modal fade" id="adduser" tabindex="-1" role="dialog" aria-labelledby="adduser"
        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600" id="adduser">Add Customer</h6>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border:none;">
                        <i class="fas fa-times" style="color:black;"></i>
                    </button>
                </div>

                <form wire:submit.prevent="addCustomer">
                    <div class="modal-body">
                        <div class="row g-2 align-items-center">

                            <!-- Name -->
                            <div class="col-md-12 mb-1">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" required class="form-control" placeholder="Enter Full Name"
                                    wire:model="name">
                                @error('name')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-12 mb-1">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" required class="form-control" placeholder="Enter Email Address"
                                    wire:model="email">
                                @error('email')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Phone Number -->
                            <div class="col-md-12 mb-1">
                                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" required class="form-control" placeholder="Enter Phone Number"
                                    wire:model="phone" inputmode="numeric" pattern="[0-9]*"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                @error('phone')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div class="col-md-12 mb-1">
                                <label class="form-label">Address <span class="text-danger">*</span></label>
                                <textarea class="form-control" required placeholder="Enter Address" wire:model="address" rows="2"></textarea>
                                @error('address')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                        <div>
                            <button type="submit" class="btn btn-success" wire:loading.attr="disabled"
                                wire:target="addCustomer">
                                <span wire:loading wire:target="addCustomer">
                                    <i class="fas fa-spinner fa-spin me-2"></i> Saving...
                                </span>
                                <span wire:loading.remove wire:target="addCustomer">
                                    Save
                                </span>
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>








<script>
    document.addEventListener("livewire:load", function() {
        new TomSelect("#customer-select", {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            },
            onChange: function(value) {
                @this.set('selectedCustomer', value);
            }
        });
    });
</script>
