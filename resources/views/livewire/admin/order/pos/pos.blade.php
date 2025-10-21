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
            style="margin-top:92px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.12); border:none;">
            <div class="card-header bg-light d-flex justify-content-between align-items-center"
                style="padding:8px 12px;">
                <div>
                    <strong>Order No:</strong> <span>{{ $orderNumber }}</span>
                </div>
                <div>
                    <strong>Date:</strong> <span>{{ $todayDate }}</span>
                </div>
            </div>

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
                        <select id="customer-select" wire:model="selectedCustomer"
                            wire:key="customer-select-{{ count($customers) }}" class="form-select form-select-sm">

                            <option value="">-- Choose Customer --</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" wire:key="customer-{{ $customer->id }}">
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
                    <button wire:click="openOrderModal"
                        style="flex:1; background-color:#1E3A8A; color:white; padding:8px 0; border:none; border-radius:6px; font-weight:600; cursor:pointer;">
                        <i class="fas fa-credit-card me-1"></i> Checkout
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
                                    oninput="this.value = this.value.replace(/[^0-n]/g, '')">
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

    <div wire:ignore.self class="modal fade" id="orderSummaryModal" tabindex="-1" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-xl rounded-4 overflow-hidden" style="background-color:#f8faff;">

                <!-- Header -->
                <div class="modal-header border-0 py-3 px-4 bg-white shadow-sm">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 text-white rounded-circle d-flex align-items-center justify-content-center me-2"
                            style="width:40px; height:40px;">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <h5 class="modal-title fw-semibold mb-0 text-dark">Order Summary</h5>
                    </div>
                    <button type="button" class="btn btn-light border-0" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="fas fa-times text-muted"></i>
                    </button>
                </div>

                <!-- Body -->
                <div class="modal-body p-4">

                    <!-- Order Info -->
                    <div class="d-flex justify-content-between align-items-center rounded-3 border p-3 bg-white mb-4">
                        <div class="fw-semibold text-dark">
                            <i class="fas fa-hashtag text-primary me-1"></i>
                            Order No: <span class="text-primary">{{ $orderNumber }}</span>
                        </div>
                        <div class="text-muted small">
                            <i class="fas fa-calendar-day me-1"></i>
                            {{ $todayDate }}
                        </div>
                    </div>

                    <!-- Customer Info -->
                    <div class="border rounded-3 bg-white shadow-sm p-3 mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                style="width:50px; height:50px; font-weight:600;">
                                {{ strtoupper(substr(optional($customers->firstWhere('id', $selectedCustomer))->name ?? 'C', 0, 1)) }}
                            </div>
                            <div>
                                @php $customer = $customers->firstWhere('id', $selectedCustomer); @endphp
                                @if ($customer)
                                    <h6 class="mb-0 fw-bold text-dark">{{ $customer->name }}</h6>
                                    <small class="text-muted"><i
                                            class="fas fa-envelope me-1"></i>{{ $customer->email }}</small><br>
                                    <small class="text-muted"><i
                                            class="fas fa-phone me-1"></i>{{ $customer->phone }}</small>
                                @else
                                    <p class="text-muted mb-0">No customer selected</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Cart Items -->
                    <div class="border rounded-3 bg-white shadow-sm p-0 mb-4">
                        <div class="px-3 py-2 border-bottom bg-light fw-semibold text-secondary">
                            <i class="fas fa-shopping-cart me-2"></i>Cart Items
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless mb-0 align-middle">
                                <thead class="bg-light text-muted">
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Price</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cart as $item)
                                        <tr class="border-top">
                                            <td>{{ $item['name'] }}</td>
                                            <td class="text-center">{{ $item['quantity'] }}</td>
                                            <td class="text-end">${{ number_format($item['price'], 2) }}</td>
                                            <td class="text-end fw-semibold text-success">
                                                ${{ number_format($item['price'] * $item['quantity'], 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Totals -->
                    <div class="border rounded-3 bg-white shadow-sm p-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <label class="fw-semibold text-dark mb-0">Discount</label>
                            <input type="number" class="form-control form-control-sm text-end w-25"
                                placeholder="0.00" wire:model.live="discount">
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold text-secondary mb-0">Gross Total</h6>
                            <h5 class="fw-bold text-primary mb-0">${{ number_format($finalTotal, 2) }}</h5>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer border-0 bg-white shadow-sm py-3 px-4 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                        data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-primary rounded-pill px-4 fw-semibold"
                        wire:click="confirmOrder">
                        <i class="fas fa-check me-1"></i> Confirm Order
                    </button>
                </div>

            </div>
        </div>
    </div>



</div>

<script>
    Livewire.on('printPage', (orderId, categoryDiscAmount, sellerName) => {
        var $id = orderId;
        var $categoryDiscount = categoryDiscAmount;
        var $sellerName = sellerName;

        // Open the new window
        var printWindow = window.open(
            '{{ url('admin/orders/print-order/') }}' + '/' + $id + '?categoryDiscount=' +
            $categoryDiscount + '&sellerName=' + encodeURIComponent($sellerName),
            '_blank'
        );

        var checkWindowClosed = setInterval(() => {
            if (printWindow.closed) {
                clearInterval(checkWindowClosed);
                window.location.reload();
            }
        }, 500);
    });



    window.addEventListener('show-order-modal', () => {
        const modal = new bootstrap.Modal(document.getElementById('orderSummaryModal'));
        modal.show();
    });

    window.addEventListener('close-order-modal', () => {
        const modalEl = document.getElementById('orderSummaryModal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();
    });
</script>
