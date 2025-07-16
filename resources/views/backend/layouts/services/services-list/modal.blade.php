{{-- Create Modal For Service Start --}}
<div class="modal fade" id="createServiceModal" tabindex="-1" aria-labelledby="createServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createServiceModalLabel">Create New Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createServiceForm" novalidate>
                @csrf
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="package_id" class="form-label">
                                Package <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="package_id" name="package_id" required>
                                <option value="">Choose a package...</option>
                            </select>
                            <span class="text-danger error-text create_package_id_error"></span>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="footage_size_id" class="form-label">
                                Footage Size <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="footage_size_id" name="footage_size_id" required>
                                <option value="">Choose footage size...</option>
                            </select>
                            <span class="text-danger error-text create_footage_size_id_error"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Service Items</h6>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="addServiceItem">
                                    <i class="fas fa-plus me-1"></i>Add Service Item
                                </button>
                            </div>
                            <span class="text-danger error-text create_service_items_error"></span>
                        </div>
                    </div>

                    <div id="serviceItemsContainer">
                        <!-- Service items will be added here dynamically -->
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">
                                Price ($) <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control" id="price" name="price"
                                placeholder="Enter price for all service items" min="0" max="999999.99"
                                step="0.01" required>
                            <span class="text-danger error-text create_price_error"></span>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Create Service
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- Create Modal For Service End --}}

{{-- Edit Modal For Service Start --}}
<div class="modal fade" id="editServiceModal" tabindex="-1" aria-labelledby="editServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editServiceModalLabel">Edit Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editServiceForm" novalidate>
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_service_id" name="id">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="edit_package_id" class="form-label">
                                Package <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="edit_package_id" name="package_id" required>
                                <option value="">Choose a package...</option>
                            </select>
                            <span class="text-danger error-text edit_package_id_error"></span>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_footage_size_id" class="form-label">
                                Footage Size <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="edit_footage_size_id" name="footage_size_id" required>
                                <option value="">Choose footage size...</option>
                            </select>
                            <span class="text-danger error-text edit_footage_size_id_error"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Service Items</h6>
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                    id="editAddServiceItem">
                                    <i class="fas fa-plus me-1"></i>Add Service Item
                                </button>
                            </div>
                            <span class="text-danger error-text edit_service_items_error"></span>
                        </div>
                    </div>

                    <div id="editServiceItemsContainer">
                        <!-- Service items will be loaded here dynamically -->
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6 mb-3">
                            <label for="edit_price" class="form-label">
                                Price ($) <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control" id="edit_price" name="price"
                                placeholder="Enter price for all service items" min="0" max="999999.99"
                                step="0.01" required>
                            <span class="text-danger error-text edit_price_error"></span>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Update Service
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- Edit Modal For Service End --}}
