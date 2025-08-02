@extends('backend.app')

@section('title', 'Other Services')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            {{-- Service Description Update Section (Single Entity) --}}
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Service Description Settings</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('other-service.header.update') }}">
                                @csrf
                                @method('PATCH')
                                <div class="row gy-4">
                                    <div class="col-md-12">
                                        <div>
                                            <label for="service_description" class="form-label">Service Description:</label>
                                            <textarea class="form-control @error('service_description') is-invalid @enderror" name="service_description"
                                                id="service_description" placeholder="Please Enter Service Description" rows="4">{{ old('service_description', $otherService->service_description ?? '') }}</textarea>
                                            @error('service_description')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i>Update Service Description
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Other Services CRUD Section --}}
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Other Services Management</h5>
                            <button type="button" class="btn btn-primary btn-sm" id="addNewOtherService">
                                <i class="fas fa-plus me-1"></i>Add New Service
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatable"
                                    class="table table-bordered table-striped align-middle dt-responsive nowrap"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="column-id">#</th>
                                            <th class="column-content">Title</th>
                                            <th class="column-content">Image</th>
                                            <th class="column-content">Description</th>
                                            <th class="column-status">Status</th>
                                            <th class="column-action">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Dynamic Data --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Create Modal with CKEditor + Dropify --}}
    <div class="modal fade" id="createOtherServiceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Other Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="createOtherServiceForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="modal_create_title" class="form-label">Title <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="title" id="modal_create_title" class="form-control">
                            <span class="text-danger error-text create_title_error"></span>
                        </div>

                        <div class="mb-3">
                            <label for="modal_create_description" class="form-label">Description <span
                                    class="text-danger">*</span></label>
                            <textarea name="description" id="modal_create_description" class="form-control" rows="4"></textarea>
                            <span class="text-danger error-text create_description_error"></span>
                        </div>

                        <div class="mb-3">
                            <label for="modal_create_image" class="form-label">Image <span
                                    class="text-danger">*</span></label>
                            <input type="file" name="image" id="modal_create_image" class="form-control dropify"
                                accept="image/*">
                            <span class="text-danger error-text create_image_error"></span>
                            <small class="text-muted">Accepted formats: JPG, PNG, GIF (Max: 20MB)</small>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="modal_create_is_bundle"
                                    name="is_bundle">
                                <label class="form-check-label" for="modal_create_is_bundle">
                                    Is Bundle Service?
                                </label>
                            </div>
                            <span class="text-danger error-text create_is_bundle_error"></span>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Create Service
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Modal with CKEditor + Dropify --}}
    <div class="modal fade" id="editOtherServiceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Other Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editOtherServiceForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_other_service_id" name="id">

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="modal_edit_title" class="form-label">Title <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="modal_edit_title" name="title" class="form-control">
                            <span class="text-danger error-text edit_title_error"></span>
                        </div>

                        <div class="mb-3">
                            <label for="modal_edit_description" class="form-label">Description <span
                                    class="text-danger">*</span></label>
                            <textarea id="modal_edit_description" name="description" class="form-control" rows="4"></textarea>
                            <span class="text-danger error-text edit_description_error"></span>
                        </div>

                        <div class="mb-3">
                            <label for="modal_edit_image" class="form-label">Image</label>
                            <input type="file" id="modal_edit_image" name="image" class="form-control dropify"
                                accept="image/*">
                            <span class="text-danger error-text edit_image_error"></span>
                            <small class="text-muted">Leave empty to keep current image. Accepted formats: JPG, PNG, GIF
                                (Max: 20MB)</small>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="modal_edit_is_bundle"
                                    name="is_bundle">
                                <label class="form-check-label" for="modal_edit_is_bundle">
                                    Is Bundle Service?
                                </label>
                            </div>
                            <span class="text-danger error-text edit_is_bundle_error"></span>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update Service
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- View Modal --}}
    <div class="modal fade" id="viewOtherServiceModal" tabindex="-1" aria-labelledby="OtherServiceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="OtherServiceModalLabel" class="modal-title">Other Service Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Dynamic content --}}
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Image Preview Modal --}}
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="imagePreviewModalLabel" class="modal-title">Image Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Dynamic content --}}
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        // Complete updated JavaScript code for your blade file
        let createEditor, editEditor, serviceDescriptionEditor;

        $(document).ready(function() {
            // Initialize Dropify
            $('.dropify').dropify();

            // Get CSRF Token
            let csrfToken = $('meta[name="csrf-token"]').attr('content');

            // AJAX Setup with CSRF Token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            // Initialize CKEditor for Service Description (Main Form)
            ClassicEditor.create(document.querySelector('#service_description'))
                .then(editor => {
                    serviceDescriptionEditor = editor;
                    console.log('Service Description editor initialized');
                })
                .catch(error => {
                    console.error('Service Description editor error:', error);
                });

            // Initialize DataTable
            let table = $('#datatable').DataTable({
                responsive: true,
                order: [],
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('other-service.index') }}",
                    type: "GET",
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                },
                dom: "<'row table-topbar'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>><'row'<'col-12'tr>><'row table-bottom'<'col-md-5 dataTables_left'i><'col-md-7'p>>",
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search records...",
                    lengthMenu: "Show _MENU_ entries",
                    processing: '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>'
                },
                autoWidth: false,
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '5%'
                    },
                    {
                        data: 'title',
                        orderable: true,
                        searchable: true,
                        width: '25%',
                        render: function(data) {
                            return '<div style="white-space:normal;word-break:break-word;">' +
                                data + '</div>';
                        }
                    },
                    {
                        data: 'image',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '5%'
                    },
                    {
                        data: 'description',
                        orderable: false,
                        searchable: false,
                        width: '55%',
                        render: function(data) {
                            return '<div style="white-space:normal;word-break:break-word;">' +
                                data + '</div>';
                        }
                    },
                    {
                        data: 'status',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '5%'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '5%'
                    }
                ]
            });

            // Add New Button
            $('#addNewOtherService').click(function() {
                console.log('Add New button clicked');

                // Reset form
                $('#createOtherServiceForm')[0].reset();
                $('.error-text').text('');

                // Clear CKEditor
                if (createEditor) {
                    createEditor.setData('');
                }

                // Properly reset Dropify for create modal
                let $createImageInput = $('#modal_create_image');

                // Remove existing dropify wrapper completely
                if ($createImageInput.closest('.dropify-wrapper').length) {
                    // Store the original attributes
                    let originalClass = $createImageInput.attr('class');
                    let originalAccept = $createImageInput.attr('accept');
                    let originalName = $createImageInput.attr('name');
                    let originalId = $createImageInput.attr('id');

                    // Create a fresh input element
                    let newInput = $('<input>')
                        .attr('type', 'file')
                        .attr('name', originalName)
                        .attr('id', originalId)
                        .attr('accept', originalAccept)
                        .addClass(originalClass);

                    // Replace the existing input
                    $createImageInput.closest('.dropify-wrapper').replaceWith(newInput);
                    $createImageInput = newInput;
                }

                // Initialize fresh Dropify without any default file
                $createImageInput.dropify({
                    messages: {
                        'default': 'Drag and drop a file here or click',
                        'replace': 'Drag and drop or click to replace',
                        'remove': 'Remove',
                        'error': 'Ooops, something wrong happened.'
                    }
                });

                // Show modal
                $('#createOtherServiceModal').modal('show');
            });

            // Also add this helper function to completely reset dropify
            function resetDropify(selector) {
                let $input = $(selector);

                if ($input.closest('.dropify-wrapper').length) {
                    // Store original attributes
                    let originalAttributes = {
                        class: $input.attr('class'),
                        accept: $input.attr('accept'),
                        name: $input.attr('name'),
                        id: $input.attr('id')
                    };

                    // Create fresh input
                    let newInput = $('<input type="file">')
                        .attr(originalAttributes);

                    // Replace completely
                    $input.closest('.dropify-wrapper').replaceWith(newInput);

                    // Initialize fresh Dropify
                    newInput.dropify({
                        messages: {
                            'default': 'Drag and drop a file here or click',
                            'replace': 'Drag and drop or click to replace',
                            'remove': 'Remove',
                            'error': 'Ooops, something wrong happened.'
                        }
                    });

                    return newInput;
                }

                return $input;
            }

            // Initialize CKEditor when Create Modal is shown
            $('#createOtherServiceModal').on('shown.bs.modal', function() {
                if (!createEditor && document.querySelector('#modal_create_description')) {
                    ClassicEditor.create(document.querySelector('#modal_create_description'))
                        .then(editor => {
                            createEditor = editor;
                            console.log('Create editor initialized');
                        })
                        .catch(error => {
                            console.error('Create editor error:', error);
                        });
                }
            });

            // Initialize CKEditor when Edit Modal is shown
            $('#editOtherServiceModal').on('shown.bs.modal', function() {
                if (!editEditor && document.querySelector('#modal_edit_description')) {
                    ClassicEditor.create(document.querySelector('#modal_edit_description'))
                        .then(editor => {
                            editEditor = editor;
                            console.log('Edit editor initialized');
                        })
                        .catch(error => {
                            console.error('Edit editor error:', error);
                        });
                }
            });

            // Create Form Submit with Proper CSRF
            $('#createOtherServiceForm').submit(function(e) {
                e.preventDefault();
                console.log('Create form submitted');

                // Clear previous errors
                $('.error-text').text('');

                // Get form data
                let formData = new FormData();

                let title = $('#modal_create_title').val().trim();
                let description = createEditor ? createEditor.getData().trim() : $(
                    '#modal_create_description').val().trim();
                let image = $('#modal_create_image')[0].files[0];
                let is_bundle = $('#modal_create_is_bundle').is(':checked') ? 1 : 0;

                // Validation
                if (!title || !description || !image) {
                    toastr.error('Please fill all required fields.');
                    return;
                }

                // Build FormData with CSRF token
                formData.append('_token', csrfToken);
                formData.append('title', title);
                formData.append('description', description);
                formData.append('image', image);
                formData.append('is_bundle', is_bundle);

                // Submit form
                $.ajax({
                    url: "{{ route('other-service.store') }}",
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        console.log('Success:', response);
                        if (response.status) {
                            $('#createOtherServiceModal').modal('hide');
                            table.ajax.reload();
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        console.log('Error:', xhr);

                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            let errors = xhr.responseJSON.errors;
                            Object.keys(errors).forEach(function(key) {
                                $(`.create_${key}_error`).text(errors[key][0]);
                            });
                        }

                        toastr.error(xhr.responseJSON?.message || 'Something went wrong.');
                    }
                });
            });

            // Alternative Edit Button Click - Using setTimeout for better Dropify handling
            $(document).on('click', '.edit-other-service', function() {
                let row = table.row($(this).closest('tr')).data();
                let id = row.id;

                // Fetch the data via AJAX to get accurate image path
                $.ajax({
                    url: "{{ route('other-service.show', ':id') }}".replace(':id', id),
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        if (response.status && response.data) {
                            let data = response.data;

                            // Set form values
                            $('#edit_other_service_id').val(data.id);
                            $('#modal_edit_title').val(data.title);

                            // Set is_bundle checkbox
                            $('#modal_edit_is_bundle').prop('checked', data.is_bundle == 1);

                            // Set CKEditor content
                            if (editEditor) {
                                editEditor.setData(data.description || '');
                            } else {
                                $('#modal_edit_description').val(data.description || '');
                            }

                            // Clear errors
                            $('.error-text').text('');

                            // Show modal first
                            $('#editOtherServiceModal').modal('show');

                            // Handle Dropify after modal is shown (use setTimeout to ensure DOM is ready)
                            setTimeout(function() {
                                let $imageInput = $('#modal_edit_image');

                                // Remove existing dropify
                                if ($imageInput.closest('.dropify-wrapper').length) {
                                    let originalInput = $imageInput.clone();
                                    $imageInput.closest('.dropify-wrapper').replaceWith(
                                        originalInput);
                                    $imageInput = originalInput;
                                }

                                // Initialize dropify with existing image
                                if (data.image) {
                                    let imageUrl = data.image_full_url || (
                                        "{{ url('/') }}/" + data.image);
                                    console.log('Image URL:', imageUrl);

                                    // Create a temporary image to check if URL is valid
                                    let tempImg = new Image();
                                    tempImg.onload = function() {
                                        console.log('Image loaded successfully');
                                        $imageInput.attr('data-default-file',
                                            imageUrl);
                                        $imageInput.dropify({
                                            messages: {
                                                'default': 'Drag and drop a file here or click',
                                                'replace': 'Drag and drop or click to replace',
                                                'remove': 'Remove',
                                                'error': 'Ooops, something wrong happened.'
                                            }
                                        });
                                    };
                                    tempImg.onerror = function() {
                                        console.log(
                                            'Image failed to load, initializing without default'
                                        );
                                        $imageInput.dropify();
                                    };
                                    tempImg.src = imageUrl;
                                } else {
                                    console.log('No image, initializing empty dropify');
                                    $imageInput.dropify();
                                }
                            }, 300); // Small delay to ensure modal is fully rendered

                        } else {
                            toastr.error('Could not fetch record details');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error fetching data:', xhr);
                        toastr.error('Error fetching record details');
                    }
                });
            });

            // Edit Form Submit with Proper CSRF
            $('#editOtherServiceForm').submit(function(e) {
                e.preventDefault();

                $('.error-text').text('');

                let formData = new FormData();
                let id = $('#edit_other_service_id').val();

                let title = $('#modal_edit_title').val().trim();
                let description = editEditor ? editEditor.getData().trim() : $('#modal_edit_description')
                    .val().trim();
                let image = $('#modal_edit_image')[0].files[0];
                let is_bundle = $('#modal_edit_is_bundle').is(':checked') ? 1 : 0;

                // Validation
                if (!title || !description) {
                    toastr.error('Please fill all required fields.');
                    return;
                }

                // Build FormData with CSRF token
                formData.append('_token', csrfToken);
                formData.append('_method', 'PUT');
                formData.append('title', title);
                formData.append('description', description);
                formData.append('is_bundle', is_bundle);

                if (image) {
                    formData.append('image', image);
                }

                $.ajax({
                    url: "{{ route('other-service.update', ':id') }}".replace(':id', id),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        if (response.status) {
                            $('#editOtherServiceModal').modal('hide');
                            table.ajax.reload();
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            let errors = xhr.responseJSON.errors;
                            Object.keys(errors).forEach(function(key) {
                                $(`.edit_${key}_error`).text(errors[key][0]);
                            });
                        }
                        toastr.error(xhr.responseJSON?.message || 'Something went wrong.');
                    }
                });
            });

            // Handle Service Description Form Submit
            $('form[action="{{ route('other-service.header.update') }}"]').submit(function(e) {
                if (serviceDescriptionEditor) {
                    // Update the hidden textarea with CKEditor content
                    let content = serviceDescriptionEditor.getData();
                    $('#service_description').val(content);
                }
            });
        });

        // Status Functions with CSRF
        function showStatusChangeAlert(id) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to update the status?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) statusChange(id);
            });
        }

        function statusChange(id) {
            let csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "{{ route('other-service.status', ':id') }}".replace(':id', id),
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    $('#datatable').DataTable().ajax.reload();
                    if (response.status === true) {
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('An error occurred. Please try again.');
                }
            });
        }

        // Delete Functions with CSRF
        function showDeleteConfirm(id) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) deleteItem(id);
            });
        }

        function deleteItem(id) {
            let csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "{{ route('other-service.destroy', ':id') }}".replace(':id', id),
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    $('#datatable').DataTable().ajax.reload();
                    if (response.status === true) {
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('An error occurred. Please try again.');
                }
            });
        }

        // View Details with CSRF
        function showOtherServiceDetails(id) {
            let csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "{{ route('other-service.show', ':id') }}".replace(':id', id),
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    if (response.data) {
                        let data = response.data;
                        let imgPath = data.image ? `{{ url('/') }}/${data.image}` :
                            "{{ asset('backend/images/users/user-dummy-img.jpg') }}";

                        $('#viewOtherServiceModal .modal-body').html(`
                    <div class="text-center mb-3">
                        <img src="${imgPath}" alt="Image" width="200" height="200" class="rounded">
                    </div>
                    <p><strong>Title:</strong> ${data.title}</p>
                    <p><strong>Description:</strong> ${data.description}</p>
                    <p><strong>Status:</strong> <span class="badge ${data.status === 'active' ? 'bg-success' : 'bg-danger'}">${data.status}</span></p>
                    <p><strong>Is Bundle:</strong> <span class="badge ${data.is_bundle ? 'bg-success' : 'bg-danger'}">${data.is_bundle ? 'Yes' : 'No'}</span></p>
                `);
                    }
                },
                error: function() {
                    toastr.error('Could not fetch details.');
                }
            });
        }

        // Image Preview
        function showImagePreview(imageUrl) {
            $('#imagePreviewModal .modal-body').html(`
        <div class="text-center">
            <img src="${imageUrl}" alt="Preview" class="img-fluid" />
        </div>
    `);
        }
    </script>
@endpush
