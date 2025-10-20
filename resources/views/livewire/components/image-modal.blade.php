<div class="modal fade" id="globalImageModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-0">
                <img id="modalImage" src="" class="img-fluid w-100" alt="Image">
            </div>
            <div class="modal-footer p-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('openImageModal', event => {
        const modal = new bootstrap.Modal(document.getElementById('globalImageModal'));
        document.getElementById('modalImage').src = event.detail.url;
        modal.show();
    });
</script>
