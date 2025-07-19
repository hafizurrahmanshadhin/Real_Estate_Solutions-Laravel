{{-- Create Modal For Add-ons Start --}}
<div class="modal fade" id="createAddOnModal" tabindex="-1" aria-labelledby="createAddOnModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createAddOnModalLabel">Create New Add-ons</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createAddOnForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="footage_size_id_for_add_ons" class="form-label">Select Footage Size <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" id="footage_size_id_for_add_ons" name="footage_size_id">
                                <option value="">Choose a footage size...</option>
                                {{-- Options will be populated via JavaScript --}}
                            </select>
                            <span class="text-danger error-text create_footage_size_id_error"></span>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="service_item_id" class="form-label">Select Service Item <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" id="service_item_id" name="service_item_id">
                                <option value="">Choose a service item...</option>
                                {{-- Options will be populated via JavaScript --}}
                            </select>
                            <span class="text-danger error-text create_service_item_id_error"></span>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" value="1"
                                placeholder="Enter quantity">
                            <span class="text-danger error-text create_quantity_error"></span>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Price ($) <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="price" name="price"
                                placeholder="Enter price" min="0" step="0.01">
                            <span class="text-danger error-text create_price_error"></span>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create Service</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- Create Modal For Add-ons End --}}

{{-- Edit Modal For Add-ons Start --}}
<div class="modal fade" id="editAddOnModal" tabindex="-1" aria-labelledby="editAddOnModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAddOnModalLabel">Edit Add-ons</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editAddOnForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_service_id" name="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_footage_size_id_for_add_ons" class="form-label">Select Footage Size <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" id="edit_footage_size_id_for_add_ons" name="footage_size_id">
                                <option value="">Choose a footage size...</option>
                                {{-- Options will be populated via JavaScript --}}
                            </select>
                            <span class="text-danger error-text edit_footage_size_id_error"></span>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_service_item_id" class="form-label">Select Service Item <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" id="edit_service_item_id" name="service_item_id">
                                <option value="">Choose a service item...</option>
                                {{-- Options will be populated via JavaScript --}}
                            </select>
                            <span class="text-danger error-text edit_service_item_id_error"></span>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="edit_quantity" name="quantity"
                                placeholder="Enter quantity">
                            <span class="text-danger error-text edit_quantity_error"></span>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_price_for_add_ons" class="form-label">Price ($) <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_price_for_add_ons" name="price"
                                placeholder="Enter price" min="0" step="0.01">
                            <span class="text-danger error-text edit_price_error"></span>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Service</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- Edit Modal For Add-ons End --}}
