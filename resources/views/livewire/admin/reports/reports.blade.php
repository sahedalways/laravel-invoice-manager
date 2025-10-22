<div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            <h5 class="fw-500 text-white">Sales Report & Order Management</h5>
        </div>
        <div class="col-auto">
            <div class="btn-group">
                <button wire:click="exportReport('xlsx')" class="btn btn-white text-primary me-2">
                    <i class="fa fa-file-excel me-2"></i> Excel
                </button>
                <button wire:click="exportReport('csv')" class="btn btn-white text-primary me-2">
                    <i class="fa fa-file-csv me-2"></i> CSV
                </button>
                <button wire:click="exportReport('pdf')" class="btn btn-white text-primary">
                    <i class="fa fa-file-pdf me-2"></i> PDF
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header bg-light p-3">
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <div class="flex-grow-1" style="min-width:150px;">
                            <label class="form-label mb-1">Search</label>
                            <input type="text" wire:model.debounce.500ms="search" class="form-control"
                                placeholder="Order number or customer name">
                        </div>
                        <div class="flex-grow-1" style="min-width:150px;">
                            <label class="form-label mb-1">From Date</label>
                            <input type="date" wire:model="fromDate" class="form-control">
                        </div>
                        <div class="flex-grow-1" style="min-width:150px;">
                            <label class="form-label mb-1">To Date</label>
                            <input type="date" wire:model="toDate" class="form-control">
                        </div>
                        <div class="flex-grow-1" style="min-width:150px;">
                            <label class="form-label mb-1">Order Status</label>
                            <select wire:model="status" class="form-select">
                                <option value="">All</option>
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="returned">Returned</option>
                            </select>
                        </div>
                        <div class="flex-grow-1" style="min-width:150px;">
                            <label class="form-label mb-1">Product</label>
                            <select wire:model="selectedProduct" class="form-select">
                                <option value="">All Products</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div style="margin-top:45px;">
                            <button class="btn btn-primary px-4" wire:click="filterSales">Filter</button>
                        </div>
                    </div>
                </div>



                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Order No</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Payment</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($infos as $index => $order)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="fw-bold text-primary">{{ $order->order_number }}</td>
                                        <td>{{ $order->customer->name ?? 'Guest' }}</td>
                                        <td>{{ $order->created_at->format('d M, Y') }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : ($order->status == 'cancelled' ? 'danger' : 'info')) }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>${{ number_format($order->total_price, 2) }}</td>
                                        <td>Cash</td>
                                        <td>
                                            <a href="#" wire:click="viewOrder({{ $order->id }})"
                                                class="badge bg-info text-dark me-1"
                                                style="cursor:pointer; transition: all 0.2s ease; display:inline-block;"
                                                onmouseover="this.style.transform='scale(1.05)'; this.style.opacity='0.85'; this.style.boxShadow='0 2px 6px rgba(0,0,0,0.15)';"
                                                onmouseout="this.style.transform='scale(1)'; this.style.opacity='1'; this.style.boxShadow='none';">
                                                View
                                            </a>

                                            @if ($order->status == 'completed')
                                                <a href="#" wire:click="returnOrder({{ $order->id }})"
                                                    class="badge bg-warning text-dark"
                                                    style="cursor:pointer; transition: all 0.2s ease; display:inline-block;"
                                                    onmouseover="this.style.transform='scale(1.05)'; this.style.opacity='0.85'; this.style.boxShadow='0 2px 6px rgba(0,0,0,0.15)';"
                                                    onmouseout="this.style.transform='scale(1)'; this.style.opacity='1'; this.style.boxShadow='none';">
                                                    Return
                                                </a>
                                            @endif
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No orders found!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        @if ($hasMore)
                            <div class="text-center my-3">
                                <button wire:click="loadMore"
                                    class="btn btn-sm btn-outline-primary rounded-pill px-4 py-2 shadow-sm">
                                    Load More
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Order Modal -->
    <div wire:ignore.self class="modal fade" id="viewOrderModal" tabindex="-1" aria-labelledby="viewOrderModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow-sm rounded-3">

                @if ($selectedOrder)
                    <!-- Modal Header -->
                    <div class="modal-header bg-primary text-white py-3 px-4">
                        <h5 class="modal-title text-white">Order #{{ $selectedOrder->order_number }}</h5>
                        <span
                            class="badge {{ $selectedOrder->status == 'completed' ? 'bg-success' : ($selectedOrder->status == 'pending' ? 'bg-warning text-dark' : 'bg-danger') }} ms-3 text-uppercase">
                            {{ $selectedOrder->status }}
                        </span>
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border:none;">
                            <i class="fas fa-times text-dark"></i>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body px-4 py-3">
                        <!-- Customer & Order Info -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <h6 class="fw-semibold">Customer Info</h6>
                                        <p class="mb-1"><strong>Name:</strong>
                                            {{ $selectedOrder->customer->name ?? 'N/A' }}</p>
                                        <p class="mb-1"><strong>Email:</strong>
                                            {{ $selectedOrder->customer->email ?? 'N/A' }}</p>
                                        <p class="mb-0"><strong>Phone:</strong>
                                            {{ $selectedOrder->customer->phone ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <h6 class="fw-semibold">Order Summary</h6>
                                        <p class="mb-1"><strong>Order Date:</strong>
                                            {{ $selectedOrder->created_at->format('d M, Y') }}</p>
                                        <p class="mb-1"><strong>Total Items:</strong>
                                            {{ $selectedOrder->details->count() }}</p>
                                        <p class="mb-0"><strong>Payment Status:</strong>
                                            {{ ucfirst($selectedOrder->payment_status) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Details Table -->
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($selectedOrder->details as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->product->name ?? 'N/A' }}</td>
                                            <td>{{ $item->qty }}</td>
                                            <td>${{ number_format($item->price, 2) }}</td>
                                            <td>${{ number_format($item->total, 2) }}</td>
                                            <td>
                                                @if ($item->status != 'returned')
                                                    <button
                                                        wire:click="returnProduct({{ $selectedOrder->id }}, {{ $item->product_id }})"
                                                        class="btn btn-sm btn-warning">Return</button>
                                                @else
                                                    <span class="badge bg-secondary">Returned</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Total Amount -->
                        <div class="text-end mt-3">
                            <h5>Total: <span
                                    class="badge bg-success fs-6">${{ number_format($selectedOrder->total_price, 2) }}</span>
                            </h5>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>




</div>


<script>
    Livewire.on('openOrderModal', () => {
        const modal = new bootstrap.Modal(document.getElementById('viewOrderModal'));
        modal.show();
    });
</script>
