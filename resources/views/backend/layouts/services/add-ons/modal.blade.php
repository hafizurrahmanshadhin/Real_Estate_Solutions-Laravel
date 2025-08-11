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
                            </select>
                            <span class="text-danger error-text create_footage_size_id_error"></span>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="service_item_id" class="form-label">Select Service Item <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" id="service_item_id" name="service_item_id">
                                <option value="">Choose a service item...</option>
                            </select>
                            <span class="text-danger error-text create_service_item_id_error"></span>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" value="1"
                                placeholder="Enter quantity">
                            <span class="text-danger error-text create_quantity_error"></span>
                        </div>

                        {{-- Locations field for Community Images --}}
                        <div class="col-md-6 mb-3" id="locations_field" style="display: none;">
                            <label for="locations" class="form-label">Number of Locations <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" id="locations" name="locations">
                                <option value="">Choose number of locations...</option>
                                <option value="1">1 Location</option>
                                <option value="2">2 Locations</option>
                                <option value="3">3 Locations</option>
                                <option value="4">4 Locations</option>
                                <option value="5">5 Locations</option>
                            </select>
                            <span class="text-danger error-text create_locations_error"></span>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Price ($) <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="price" name="price"
                                placeholder="Enter price" min="0" step="0.01">
                            <span class="text-danger error-text create_price_error"></span>
                        </div>

                        {{-- Increment option --}}
                        <div class="col-md-6 mb-3 d-flex align-items-center">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="is_increment" name="is_increment">
                                <label class="form-check-label" for="is_increment">
                                    Enable maximum quantity limit
                                </label>
                            </div>
                            <span class="text-danger error-text create_is_increment_error ms-2"></span>
                        </div>

                        {{-- Maximum number (shown only if is_increment is checked) --}}
                        <div class="col-md-6 mb-3" id="maximum_number_field" style="display: none;">
                            <label for="maximum_number" class="form-label">Maximum Number <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="maximum_number" name="maximum_number"
                                placeholder="Enter maximum number" min="1" step="1" disabled>
                            <span class="text-danger error-text create_maximum_number_error"></span>
                        </div>

                        {{-- Community Images Pricing Guide --}}
                        <div class="col-12" id="community_pricing_guide" style="display: none;">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle"></i> Community Images Pricing Guide:</h6>
                                <ul class="mb-0">
                                    <li><strong>2 Community Images - 1 location:</strong> $19.00</li>
                                    <li><strong>5 Community Images - 2 locations:</strong> $49.00</li>
                                </ul>
                            </div>
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
<div class="modal fade" id="editAddOnModal" tabindex="-1" aria-labelledby="editAddOnModalLabel"
    aria-hidden="true">
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
                            </select>
                            <span class="text-danger error-text edit_footage_size_id_error"></span>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_service_item_id" class="form-label">Select Service Item <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" id="edit_service_item_id" name="service_item_id">
                                <option value="">Choose a service item...</option>
                            </select>
                            <span class="text-danger error-text edit_service_item_id_error"></span>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="edit_quantity" name="quantity"
                                placeholder="Enter quantity">
                            <span class="text-danger error-text edit_quantity_error"></span>
                        </div>

                        {{-- Edit Locations field --}}
                        <div class="col-md-6 mb-3" id="edit_locations_field" style="display: none;">
                            <label for="edit_locations" class="form-label">Number of Locations <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" id="edit_locations" name="locations">
                                <option value="">Choose number of locations...</option>
                                <option value="1">1 Location</option>
                                <option value="2">2 Locations</option>
                                <option value="3">3 Locations</option>
                                <option value="4">4 Locations</option>
                                <option value="5">5 Locations</option>
                            </select>
                            <span class="text-danger error-text edit_locations_error"></span>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_price_for_add_ons" class="form-label">Price ($) <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_price_for_add_ons" name="price"
                                placeholder="Enter price" min="0" step="0.01">
                            <span class="text-danger error-text edit_price_error"></span>
                        </div>

                        {{-- Increment option --}}
                        <div class="col-md-6 mb-3 d-flex align-items-center">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="edit_is_increment"
                                    name="is_increment">
                                <label class="form-check-label" for="edit_is_increment">
                                    Enable maximum quantity limit
                                </label>
                            </div>
                            <span class="text-danger error-text edit_is_increment_error ms-2"></span>
                        </div>

                        {{-- Maximum number (shown only if edit_is_increment is checked) --}}
                        <div class="col-md-6 mb-3" id="edit_maximum_number_field" style="display: none;">
                            <label for="edit_maximum_number" class="form-label">Maximum Number <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_maximum_number"
                                name="maximum_number" placeholder="Enter maximum number" min="1"
                                step="1" disabled>
                            <span class="text-danger error-text edit_maximum_number_error"></span>
                        </div>

                        {{-- Edit Community Images Pricing Guide --}}
                        <div class="col-12" id="edit_community_pricing_guide" style="display: none;">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle"></i> Community Images Pricing Guide:</h6>
                                <ul class="mb-0">
                                    <li><strong>2 Community Images - 1 location:</strong> $19.00</li>
                                    <li><strong>5 Community Images - 2 locations:</strong> $49.00</li>
                                </ul>
                            </div>
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
