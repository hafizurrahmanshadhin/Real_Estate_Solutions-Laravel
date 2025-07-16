<div class="modal fade" id="createSizeModal" tabindex="-1" aria-labelledby="createSizeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createSizeModalLabel">Create New Square Footage Range</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createSizeForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create_size" class="form-label">Square Footage Range <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="create_size" name="size"
                            placeholder="e.g., 0-10, 11-20, 100-150" maxlength="50">
                        <div class="form-text text-muted mt-2">
                            <i class="ri-information-line me-1"></i>
                            <strong>Format Examples:</strong>
                            <ul class="mb-2 mt-1 ps-3">
                                <li><code>0-10</code> or <code>0 - 10</code></li>
                                <li><code>11-20</code> or <code>11 - 20</code></li>
                                <li><code>500-1000</code> or <code>500 - 1000</code></li>
                            </ul>
                            <strong>Rules:</strong>
                            <ul class="mb-0 ps-3">
                                <li>Minimum value is 0 (no negative numbers)</li>
                                <li>Use format: number-number or number - number</li>
                                <li>Each range must be unique</li>
                                <li>First number must be less than second number</li>
                            </ul>
                        </div>
                        <span class="text-danger error-text create_size_error"></span>
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
{{-- Create Modal For Square Footage Range End --}}

{{-- Edit Modal For Square Footage Range Start --}}
<div class="modal fade" id="editSizeModal" tabindex="-1" aria-labelledby="editSizeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSizeModalLabel">Edit Square Footage Range</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSizeForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_size_id" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_size" class="form-label">Square Footage Range <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_size" name="size"
                            placeholder="e.g., 0-10, 11-20, 100-150" maxlength="50">
                        <div class="form-text text-muted mt-2">
                            <i class="ri-information-line me-1"></i>
                            <strong>Format Examples:</strong>
                            <ul class="mb-2 mt-1 ps-3">
                                <li><code>0-10</code> or <code>0 - 10</code></li>
                                <li><code>11-20</code> or <code>11 - 20</code></li>
                                <li><code>500-1000</code> or <code>500 - 1000</code></li>
                            </ul>
                            <strong>Rules:</strong>
                            <ul class="mb-0 ps-3">
                                <li>Minimum value is 0 (no negative numbers)</li>
                                <li>Use format: number-number or number - number</li>
                                <li>Each range must be unique</li>
                                <li>First number must be less than second number</li>
                            </ul>
                        </div>
                        <span class="text-danger error-text edit_size_error"></span>
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
{{-- Edit Modal For Square Footage Range End --}}
