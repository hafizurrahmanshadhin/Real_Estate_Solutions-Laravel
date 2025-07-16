{{-- Create Modal For Service Item Start --}}
<div class="modal fade" id="createItemModal" tabindex="-1" aria-labelledby="createItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createItemModalLabel">Create New Service Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createItemForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="service_name" class="form-label">Service Item Name <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="service_name" name="service_name"
                            placeholder="Please Enter Service Item Name" maxlength="100">
                        <div class="form-text text-muted mt-2">
                            <strong>Rules:</strong>
                            <ul class="mb-0 ps-3">
                                <li>Only letters, numbers, spaces, hyphens, and apostrophes allowed</li>
                                <li>Must be between 2-100 characters</li>
                                <li>Cannot contain special symbols like *, /, @, etc.</li>
                                <li>Each service item name must be unique</li>
                            </ul>
                        </div>
                        <span class="text-danger error-text create_service_name_error"></span>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-add-line me-1"></i>Create
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- Create Modal For Service Item End --}}

{{-- Edit Modal For Service Item Start --}}
<div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editItemModalLabel">Edit Service Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editItemForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_item_id" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_service_name" class="form-label">Service Item Name <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_service_name" name="service_name"
                            placeholder="Please Enter Service Item Name" maxlength="100">
                        <div class="form-text text-muted mt-2">
                            <strong>Rules:</strong>
                            <ul class="mb-0 ps-3">
                                <li>Only letters, numbers, spaces, hyphens, and apostrophes allowed</li>
                                <li>Must be between 2-100 characters</li>
                                <li>Cannot contain special symbols like *, /, @, etc.</li>
                                <li>Each service item name must be unique</li>
                            </ul>
                        </div>
                        <span class="text-danger error-text edit_service_name_error"></span>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-edit-line me-1"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- Edit Modal For Service Item End --}}
