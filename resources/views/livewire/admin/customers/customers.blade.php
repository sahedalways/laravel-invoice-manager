<div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            <h5 class="fw-500 text-white">Customers Management</h5>
        </div>
        <div class="col-auto">
            <a data-bs-toggle="modal" data-bs-target="#adduser" wire:click="resetInputFields"
                class="btn btn-icon btn-3 btn-white text-primary mb-0">
                <i class="fa fa-plus me-2"></i> Add New User
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header p-4">
                    <div class="row">
                        <div class="col-md-12" wire:ignore>
                            <input type="text" class="form-control" placeholder="Search by name , email or phone no."
                                wire:model="search" />

                            <button type="button" wire:click="searchCustomers" class="btn btn-primary mt-2">
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
                                    <th class="text-uppercase text-secondary text-xs opacity-7">#</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                        Name</th>
                                    <th class="text-uppercase text-secondary text-xs  opacity-7">
                                        Contact</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7">Address</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7">Email Verified</th>
                                    <th class="text-uppercase text-secondary text-xs  opacity-7">
                                        Created</th>
                                    <th class="text-secondary opacity-7"> Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp

                                @forelse($infos as $index => $row)
                                    <tr>
                                        <td>
                                            <p class="text-sm px-3 mb-0">{{ $i++ }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $row->name }}</p>
                                        </td>

                                        <td>
                                            <p class="text-sm px-3 mb-0">{{ $row->phone }}</p>
                                            <p class="text-sm px-3 mb-0">{{ $row->email }}</p>
                                        </td>

                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $row->address }}</p>
                                        </td>
                                        </td>

                                        <td>
                                            <span class="badge bg-success">Verified</span>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">
                                                {{ $row->created_at->format('d M Y') }}</p>
                                        </td>
                                        <td>

                                            <a data-bs-toggle="modal" data-bs-target="#edituser"
                                                wire:click="edit({{ $row->id }})" type="button"
                                                class="badge badge-success text-dark fw-600 text-xs cursor-pointer hover-white">
                                                Edit Info
                                            </a>

                                            <a href="#" class="badge badge-xs badge-danger fw-600 text-xs"
                                                wire:click.prevent="$dispatch('confirmDelete', {{ $row->id }})">
                                                Delete
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center">No Customers found</td>
                                    </tr>
                                @endforelse


                            </tbody>
                        </table>
                        @if ($hasMore)
                            <div class="load-more-wrapper text-center mt-5">
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

                <form wire:submit.prevent="store">
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
                                wire:target="store">
                                <span wire:loading wire:target="store">
                                    <i class="fas fa-spinner fa-spin me-2"></i> Saving...
                                </span>
                                <span wire:loading.remove wire:target="store">
                                    Save
                                </span>
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="edituser" tabindex="-1" role="dialog"
        aria-labelledby="edituser" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600" id="edituser">Edit User</h6>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border:none;">
                        <i class="fas fa-times" style="color:black;"></i>
                    </button>
                </div>

                <form wire:submit.prevent="update">
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

                        <div class="">
                            <button type="submit" class="btn btn-success" wire:loading.attr="disabled"
                                wire:target="update">
                                <span wire:loading wire:target="update">
                                    <i class="fas fa-spinner fa-spin me-2"></i> updating...
                                </span>
                                <span wire:loading.remove wire:target="update">
                                    Update
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
    Livewire.on('confirmDelete', userId => {
        if (confirm("Are you sure you want to delete this customer? This action cannot be undone.")) {
            Livewire.dispatch('deleteUser', {
                id: userId
            });
        }
    });
</script>
