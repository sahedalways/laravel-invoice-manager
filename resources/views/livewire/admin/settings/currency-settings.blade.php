<div>
    <div class="row align-items-center justify-content-between mb-4 mt-5">
        <div class="col">
            <h5 class="fw-500 text-white">Currency Settings</h5>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">

                    <form class="row g-3 align-items-center" wire:submit.prevent="save">

                        <div class="col-md-4">
                            <label class="form-label">Currency Code <span class="text-danger">*</span></label>
                            <input type="text" required class="form-control" wire:model="currency_code"
                                placeholder="Enter Currency Code (e.g., USD, EUR, BDT)">
                            @error('currency_code')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Currency Symbol <span class="text-danger">*</span></label>
                            <input type="text" required class="form-control" wire:model="currency_symbol"
                                placeholder="Enter Symbol (e.g., $, €, ৳)">
                            @error('currency_symbol')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-12 d-flex align-items-center justify-content-start mt-3">
                            <button type="submit" class="btn btn-success" wire:loading.attr="disabled"
                                wire:target="save">
                                <span wire:loading wire:target="save">
                                    <i class="fas fa-spinner fa-spin me-2"></i> Saving...
                                </span>
                                <span wire:loading.remove wire:target="save">
                                    Save
                                </span>
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
