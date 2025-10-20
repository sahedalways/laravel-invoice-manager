<div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            <h5 class="fw-500 text-white">Stock Management</h5>
        </div>

    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header p-4">
                    <div class="row">
                        <div class="col-md-12" wire:ignore>
                            <input type="text" class="form-control" placeholder="Search by product name or sku..."
                                wire:model="search" />
                            <button type="button" wire:click="searchProduct" class="btn btn-primary mt-2">
                                Search
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>SKU</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($infos as $index => $product)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $product->sku }}</td>

                                        <td class="fw-bold">{{ $product->name }}</td>

                                        <td>
                                            <div class="d-flex flex-column align-items-center gap-2">
                                                <!-- Current stock display -->
                                                <span class="badge bg-primary text-white px-3 py-1"
                                                    style="min-width: 50px; text-align:center; font-weight:500;">
                                                    {{ $product->stock_quantity }}
                                                </span>

                                                <!-- Buttons row -->
                                                <div class="d-flex gap-1">
                                                    <!-- Increase stock button -->
                                                    <button
                                                        wire:click="adjustStock({{ $product->id }}, 1, 'in', 'Manual Adjustment')"
                                                        class="btn btn-outline-success btn-sm d-flex align-items-center justify-content-center"
                                                        title="Add Stock">
                                                        <i class="fas fa-plus"></i>
                                                    </button>

                                                    <!-- Decrease stock button -->
                                                    <button
                                                        wire:click="adjustStock({{ $product->id }}, 1, 'out', 'Manual Adjustment')"
                                                        class="btn btn-outline-danger btn-sm d-flex align-items-center justify-content-center"
                                                        title="Reduce Stock">
                                                        <i class="fas fa-minus"></i>
                                                    </button>

                                                    <!-- View stock history -->
                                                    <button wire:click="openStockModal({{ $product->id }})"
                                                        class="btn btn-outline-info btn-sm d-flex align-items-center justify-content-center"
                                                        title="Stock History">
                                                        <i class="fas fa-history"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No products found!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        @if ($hasMore)
                            <div class="load-more-wrapper text-center mt-4 mb-3">
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




    <div wire:ignore.self class="modal fade" id="stockModal" tabindex="-1" role="dialog"
        aria-labelledby="stockModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">Stock History - {{ $currentProductName ?? '' }}</h6>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border:none;">
                        <i class="fas fa-times text-dark"></i>
                    </button>
                </div>

                <div class="modal-body">
                    <div style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Quantity</th>
                                    <th>Reference</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stockHistory as $index => $stock)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $stock['type'] }}</td>
                                        <td>{{ $stock['quantity'] }}</td>
                                        <td>{{ $stock['reference'] }}</td>
                                        <td>{{ \Carbon\Carbon::parse($stock['date'])->format('d F, Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No stock history found!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Delete Confirmation -->
    <script>
        window.addEventListener('openStockModal', event => {
            var stockModal = new bootstrap.Modal(document.getElementById('stockModal'));
            stockModal.show();
        });
    </script>
</div>
