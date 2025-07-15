{{-- Create Modal For Square Footage Range Start --}}
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
                        <label for="create_size" class="form-label">Enter Square Footage Range:</label>
                        <input type="text" class="form-control" id="create_size" name="size"
                            placeholder="Please Enter Zip Code">
                        <span class="text-danger error-text create_size_error"></span>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create</button>
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
                <h5 class="modal-title" id="editSizeModalLabel">Edit Square Footage Range:</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSizeForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_size_id" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_size" class="form-label">Square Footage Range:</label>
                        <input type="text" class="form-control" id="edit_size" name="size"
                            placeholder="Please Enter Zip Code">
                        <span class="text-danger error-text edit_size_error"></span>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- Edit Modal For Square Footage Range End --}}
