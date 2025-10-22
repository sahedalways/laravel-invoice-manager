<div>
    <div class="row align-items-center mb-4 mt-5">
        <div class="col d-flex align-items-center">
            <!-- Left: Title -->
            <h5 class="fw-500 text-white mb-0">Product Management</h5>
        </div>

        <!-- Center: Add Button -->
        <div class="col d-flex justify-content-center gap-2">

            <button wire:click="exportProducts('pdf')" class="btn btn-sm btn-white text-primary">
                <i class="fa fa-file-pdf me-1"></i> PDF
            </button>
            <button wire:click="exportProducts('excel')" class="btn btn-sm btn-white text-success">
                <i class="fa fa-file-excel me-1"></i> Excel
            </button>
            <button wire:click="exportProducts('csv')" class="btn btn-sm btn-white text-info">
                <i class="fa fa-file-csv me-1"></i> CSV
            </button>
        </div>

        <!-- Right: Export Buttons -->
        <div class="col d-flex justify-content-end gap-2">
            <a data-bs-toggle="modal" data-bs-target="#addProduct" wire:click="resetInputFields"
                class="btn btn-icon btn-3 btn-white text-primary mb-0">
                <i class="fa fa-plus me-2"></i> Add New Product
            </a>
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
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($infos as $index => $product)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $product->sku }}</td>
                                        <td>
                                            <img src="{{ $product->image_url }}" alt="Product"
                                                style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;"
                                                wire:click="$dispatch('openImageModal', { url: '{{ $product->image_url }}' })"
                                                class="cursor-pointer">
                                        </td>
                                        <td class="fw-bold">{{ $product->name }}</td>
                                        <td class="fw-bold" style="white-space: normal; word-break: break-word;">
                                            @php
                                                $isExpanded = $expandedDescriptions[$product->id] ?? false;
                                            @endphp

                                            {{ $isExpanded ? $product->description : \Illuminate\Support\Str::limit($product->description, 55, '...') }}

                                            @if (strlen($product->description) > 55)
                                                <a href="#"
                                                    wire:click.prevent="toggleDescription({{ $product->id }})"
                                                    style="font-size: 0.75rem;" class="text-primary ms-1">
                                                    {{ $isExpanded ? 'View Less' : 'View More' }}
                                                </a>
                                            @endif
                                        </td>

                                        <td>${{ number_format($product->price, 2) }}</td>
                                        <td>{{ $product->stock_quantity }}</td>

                                        <td>

                                            <a data-bs-toggle="modal" data-bs-target="#editProduct"
                                                wire:click="edit({{ $product->id }})"
                                                class="badge badge-success text-dark fw-600 text-xs cursor-pointer hover-white">
                                                Edit
                                            </a>

                                            <a href="#" class="badge badge-danger fw-600 text-xs"
                                                wire:click.prevent="$dispatch('confirmDelete', {{ $product->id }})">
                                                Delete
                                            </a>
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

    <!-- Add Product Modal -->
    <div wire:ignore.self class="modal fade" id="addProduct" tabindex="-1" role="dialog" aria-labelledby="addProduct"
        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">Add Product</h6>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border:none;">
                        <i class="fas fa-times text-dark"></i>
                    </button>
                </div>

                <form wire:submit.prevent="store">
                    <div class="modal-body">
                        <div class="row g-2 align-items-center">

                            <div class="col-md-12 mb-2">
                                <label class="form-label">SKU</label>
                                <input type="text" class="form-control" wire:model="sku" readonly>
                            </div>

                            <div class="col-md-12 mb-2">
                                <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                <input type="text" required class="form-control" placeholder="Enter Product Name"
                                    wire:model="name">
                                @error('name')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="col-md-12 mb-2">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" placeholder="Enter product description" wire:model="description"></textarea>
                            </div>


                            <div class="col-md-12 mb-2">
                                <label class="form-label">Image <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" wire:model="image" required
                                    accept="image/*">
                                @if ($image)
                                    <img src="{{ $image->temporaryUrl() }}" class="img-fluid mt-2 rounded shadow"
                                        width="80">
                                @endif
                            </div>





                            <div class="col-md-6 mb-2">
                                <label class="form-label">Price <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" wire:model="price">
                                @error('price')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-2">
                                <label class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                                <input type="number" min="0" class="form-control"
                                    wire:model="stock_quantity" required>
                            </div>


                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>


                        <button type="submit" class="btn btn-success" wire:loading.attr="disabled"
                            wire:target="store">
                            <span wire:loading wire:target="store">
                                <i class="fas fa-spinner fa-spin me-2"></i> Saving...
                            </span>
                            <span wire:loading.remove wire:target="store">
                                Save
                            </span>
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div wire:ignore.self class="modal fade" id="editProduct" tabindex="-1" role="dialog"
        aria-labelledby="editProduct" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">Edit Product</h6>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border:none;">
                        <i class="fas fa-times text-dark"></i>
                    </button>
                </div>

                <form wire:submit.prevent="update">
                    <div class="modal-body">
                        <div class="row g-2 align-items-center">

                            <div class="col-md-12 mb-2">
                                <label class="form-label">SKU</label>
                                <input type="text" class="form-control" wire:model="sku" readonly>
                            </div>

                            <div class="col-md-12 mb-2">
                                <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                <input type="text" required class="form-control" wire:model="name">
                            </div>

                            <div class="col-md-12 mb-2">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" placeholder="Enter product description" wire:model="description"></textarea>
                            </div>



                            <div class="col-md-12 mb-2">
                                <label class="form-label">Change Image</label>
                                <input type="file" class="form-control" wire:model="image" accept="image/*">
                                @if ($image)
                                    <img src="{{ $image->temporaryUrl() }}" class="img-fluid mt-2 rounded shadow"
                                        width="80">
                                @elseif ($current_image)
                                    <img src="{{ $current_image }}" class="img-fluid mt-2 rounded shadow"
                                        width="80">
                                @endif
                            </div>



                            <div class="col-md-6 mb-2">
                                <label class="form-label">Price <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" wire:model="price">
                            </div>

                            <div class="col-md-6 mb-2">
                                <label class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                                <input type="number" min="0" class="form-control"
                                    wire:model="stock_quantity" required>
                            </div>



                        </div>
                    </div>



                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" wire:loading.attr="disabled"
                            wire:target="update">
                            <span wire:loading wire:target="update">
                                <i class="fas fa-spinner fa-spin me-2"></i> Saving...
                            </span>
                            <span wire:loading.remove wire:target="update">
                                Save
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation -->
    <script>
        Livewire.on('confirmDelete', id => {
            if (confirm("Are you sure you want to delete this product?")) {
                Livewire.dispatch('deleteItem', {
                    id
                });
            }
        });
    </script>
</div>
