@extends('backend.app')

@section('title', 'Services')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                {{-- Services Item Table Start --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">All Services Item List</h5>
                            <button type="button" class="btn btn-primary btn-sm" id="addNewItem">Add New</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatable-service-items"
                                    class="table table-bordered table-striped align-middle dt-responsive nowrap"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="column-id">#</th>
                                            <th class="column-content">Item Name</th>
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
                {{-- Services Item Table End --}}

                {{-- Square Footage Size Table Start --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">All Square Footage Size List</h5>
                            <button type="button" class="btn btn-primary btn-sm" id="addNewSize">Add New</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatable-footage-sizes"
                                    class="table table-bordered table-striped align-middle dt-responsive nowrap"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="column-id">#</th>
                                            <th class="column-content">Square Footage Range</th>
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
                {{-- Square Footage Size Table End --}}

                {{-- Services List Table Start --}}
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">All Service List</h5>
                            <button type="button" class="btn btn-primary btn-sm" id="addNewService">Add New</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatable-services"
                                    class="table table-bordered table-striped align-middle dt-responsive nowrap"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="column-id">#</th>
                                            <th class="column-content">Package</th>
                                            <th class="column-content">Footage Size</th>
                                            <th class="column-content">Service Item</th>
                                            <th class="column-content">Quantity</th>
                                            <th class="column-content">Price</th>
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
                {{-- Services List Table End --}}
            </div>
        </div>
    </div>

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

    {{-- Create Modal For Service Item Start --}}
    <div class="modal fade" id="createItemModal" tabindex="-1" aria-labelledby="createItemModalLabel"
        aria-hidden="true">
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
                            <label for="service_name" class="form-label">Enter Service Item Name:</label>
                            <input type="text" class="form-control" id="service_name" name="service_name"
                                placeholder="Please Enter Service Item Name">
                            <span class="text-danger error-text create_item_error"></span>
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
    {{-- Create Modal For Service Item End --}}

    {{-- Edit Modal For Service Item Start --}}
    <div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editItemModalLabel">Edit Service Item Name:</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editItemForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_item_id" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_item" class="form-label">Enter Service Item Name:</label>
                            <input type="text" class="form-control" id="edit_item" name="service_name"
                                placeholder="Please Enter Service Item Name">
                            <span class="text-danger error-text edit_item_error"></span>
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
    {{-- Edit Modal For Service Item End --}}

    {{-- Create Modal For Service Start --}}
    <div class="modal fade" id="createServiceModal" tabindex="-1" aria-labelledby="createServiceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createServiceModalLabel">Create New Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="createServiceForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="package_id" class="form-label">Select Package <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="package_id" name="package_id">
                                    <option value="">Choose a package...</option>
                                    {{-- Options will be populated via JavaScript --}}
                                </select>
                                <span class="text-danger error-text create_package_id_error"></span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="footage_size_id" class="form-label">Select Footage Size <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="footage_size_id" name="footage_size_id">
                                    <option value="">Choose a footage size...</option>
                                    {{-- Options will be populated via JavaScript --}}
                                </select>
                                <span class="text-danger error-text create_footage_size_id_error"></span>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="service_item_id" class="form-label">Select Service Item <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="service_item_id" name="service_item_id">
                                    <option value="">Choose a service item...</option>
                                    {{-- Options will be populated via JavaScript --}}
                                </select>
                                <span class="text-danger error-text create_service_item_id_error"></span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="quantity" class="form-label">Quantity <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="quantity" name="quantity"
                                    placeholder="Enter quantity" min="1">
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
    {{-- Create Modal For Service End --}}

    {{-- Edit Modal For Service Start --}}
    <div class="modal fade" id="editServiceModal" tabindex="-1" aria-labelledby="editServiceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editServiceModalLabel">Edit Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editServiceForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_service_id" name="id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_package_id" class="form-label">Select Package <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="edit_package_id" name="package_id">
                                    <option value="">Choose a package...</option>
                                    {{-- Options will be populated via JavaScript --}}
                                </select>
                                <span class="text-danger error-text edit_package_id_error"></span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="edit_footage_size_id" class="form-label">Select Footage Size <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="edit_footage_size_id" name="footage_size_id">
                                    <option value="">Choose a footage size...</option>
                                    {{-- Options will be populated via JavaScript --}}
                                </select>
                                <span class="text-danger error-text edit_footage_size_id_error"></span>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="edit_service_item_id" class="form-label">Select Service Item <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="edit_service_item_id" name="service_item_id">
                                    <option value="">Choose a service item...</option>
                                    {{-- Options will be populated via JavaScript --}}
                                </select>
                                <span class="text-danger error-text edit_service_item_id_error"></span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="edit_quantity" class="form-label">Quantity <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="edit_quantity" name="quantity"
                                    placeholder="Enter quantity" min="1">
                                <span class="text-danger error-text edit_quantity_error"></span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="edit_price" class="form-label">Price ($) <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="edit_price" name="price"
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
    {{-- Edit Modal For Service End --}}
@endsection

@push('scripts')
    {{-- This Script is for Services Module Item --}}
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            var table = $('#datatable-service-items').DataTable({
                responsive: true,
                order: [],
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"],
                ],
                processing: true,
                serverSide: true,
                pagingType: "full_numbers",
                ajax: {
                    url: "{{ route('service.item.index') }}",
                    type: "GET",
                },
                dom: "<'row table-topbar'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>>" +
                    "<'row'<'col-12'tr>>" +
                    "<'row table-bottom'<'col-md-5 dataTables_left'i><'col-md-7'p>>",
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search records...",
                    lengthMenu: "Show _MENU_ entries",
                    processing: `
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>`,
                },
                // Turn off autoWidth so column widths are respected.
                autoWidth: false,
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '5%'
                    },
                    {
                        data: 'service_name',
                        name: 'service_name',
                        orderable: true,
                        searchable: true,
                        width: '85%',
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '5%'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '5%'
                    },
                ],
            });

            // Show Create Modal
            $('#addNewItem').click(function() {
                $('#createItemModal').modal('show');
                $('#createItemForm')[0].reset();
                $('.error-text').text('');
            });

            // Handle Create Form Submission
            $('#createItemForm').submit(function(e) {
                e.preventDefault();
                $('.error-text').text('');
                let formData = $(this).serialize();

                axios.post("{{ route('service.item.store') }}", formData)
                    .then(function(response) {
                        if (response.data.success) {
                            $('#createItemModal').modal('hide');
                            $('#createItemForm')[0].reset();
                            table.ajax.reload();
                            toastr.success(response.data.message);
                        } else {
                            $.each(response.data.errors, function(key, value) {
                                $('.create_' + key + '_error').text(value[0]);
                            });
                            toastr.error('Please fix the errors.');
                        }
                    })
                    .catch(function(error) {
                        toastr.error('An error occurred while creating');
                    });
            });

            // Show Edit Modal
            $(document).on('click', '.edit-item', function() {
                let tr = $(this).closest('tr');
                let rowData = table.row(tr).data();
                $('#edit_item_id').val(rowData.id);
                $('#edit_item').val(rowData.service_name);
                $('#editItemModal').modal('show');
            });

            // Handle Edit Form Submission
            $('#editItemForm').submit(function(e) {
                e.preventDefault();
                $('.error-text').text('');
                let formData = $(this).serialize();
                let itemId = $('#edit_item_id').val();

                axios.put("{{ route('service.item.update', 0) }}".replace('/0', '/' + itemId),
                        formData)
                    .then(function(response) {
                        if (response.data.success) {
                            $('#editItemModal').modal('hide');
                            $('#editItemForm')[0].reset();
                            table.ajax.reload();
                            toastr.success(response.data.message);
                        } else {
                            $.each(response.data.errors, function(key, value) {
                                $('.edit_' + key + '_error').text(value[0]);
                            });
                            toastr.error('Please fix the errors.');
                        }
                    })
                    .catch(function(error) {
                        toastr.error('An error occurred while updating.');
                    });
            });
        });

        // Service Item Status Change Confirm Alert
        function showItemStatusChangeAlert(id) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to update the service item status?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }).then((result) => {
                if (result.isConfirmed) {
                    itemStatusChange(id);
                }
            });
        }

        // Service Item Status Change
        function itemStatusChange(id) {
            let url = '{{ route('service.item.status', 0) }}'.replace('/0', '/' + id);
            $.ajax({
                type: "GET",
                url: url,
                success: function(resp) {
                    $('#datatable-service-items').DataTable().ajax.reload();
                    if (resp.success === true) {
                        toastr.success(resp.message);
                    } else if (resp.errors) {
                        toastr.error(resp.errors[0]);
                    } else {
                        toastr.error(resp.message);
                    }
                },
                error: function(error) {
                    toastr.error('An error occurred. Please try again.');
                }
            });
        }

        // Service Item Delete Confirm
        function showItemDeleteConfirm(id) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure you want to delete this service item?',
                text: 'If you delete this, it will be gone forever.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteServiceItem(id);
                }
            });
        }

        // Delete Service Item
        function deleteServiceItem(id) {
            let url = '{{ route('service.item.destroy', 0) }}'.replace('/0', '/' + id);
            let csrfToken = '{{ csrf_token() }}';
            $.ajax({
                type: "DELETE",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(resp) {
                    $('#datatable-service-items').DataTable().ajax.reload();
                    if (resp['t-success']) {
                        toastr.success(resp.message);
                    } else {
                        toastr.error(resp.message);
                    }
                },
                error: function(error) {
                    toastr.error('An error occurred. Please try again.');
                }
            });
        }
    </script>

    {{-- This Script is for Square Footage Sizes Module --}}
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            var table = $('#datatable-footage-sizes').DataTable({
                responsive: true,
                order: [],
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"],
                ],
                processing: true,
                serverSide: true,
                pagingType: "full_numbers",
                ajax: {
                    url: "{{ route('service.footage-sizes.index') }}",
                    type: "GET",
                },
                dom: "<'row table-topbar'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>>" +
                    "<'row'<'col-12'tr>>" +
                    "<'row table-bottom'<'col-md-5 dataTables_left'i><'col-md-7'p>>",
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search records...",
                    lengthMenu: "Show _MENU_ entries",
                    processing: `
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>`,
                },
                // Turn off autoWidth so column widths are respected.
                autoWidth: false,
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '5%'
                    },
                    {
                        data: 'size',
                        name: 'size',
                        orderable: true,
                        searchable: true,
                        width: '85%',
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '5%'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '5%'
                    },
                ],
            });

            // Show Create Modal
            $('#addNewSize').click(function() {
                $('#createSizeModal').modal('show');
                $('#createSizeForm')[0].reset();
                $('.error-text').text('');
            });

            // Handle Create Form Submission
            $('#createSizeForm').submit(function(e) {
                e.preventDefault();
                $('.error-text').text('');
                let formData = $(this).serialize();

                axios.post("{{ route('service.footage-sizes.store') }}", formData)
                    .then(function(response) {
                        if (response.data.success) {
                            $('#createSizeModal').modal('hide');
                            $('#createSizeForm')[0].reset();
                            table.ajax.reload();
                            toastr.success(response.data.message);
                        } else {
                            $.each(response.data.errors, function(key, value) {
                                $('.create_' + key + '_error').text(value[0]);
                            });
                            toastr.error('Please fix the errors.');
                        }
                    })
                    .catch(function(error) {
                        toastr.error('An error occurred while creating the service.');
                    });
            });

            // Show Edit Modal
            $(document).on('click', '.edit-size', function() {
                let tr = $(this).closest('tr');
                let rowData = table.row(tr).data();
                $('#edit_size_id').val(rowData.id);
                $('#edit_size').val(rowData.size);
                $('#editSizeModal').modal('show');
            });

            // Handle Edit Form Submission
            $('#editSizeForm').submit(function(e) {
                e.preventDefault();
                $('.error-text').text('');
                let formData = $(this).serialize();
                let sizeId = $('#edit_size_id').val();

                axios.put("{{ route('service.footage-sizes.update', 0) }}".replace('/0', '/' + sizeId),
                        formData)
                    .then(function(response) {
                        if (response.data.success) {
                            $('#editSizeModal').modal('hide');
                            $('#editSizeForm')[0].reset();
                            table.ajax.reload();
                            toastr.success(response.data.message);
                        } else {
                            $.each(response.data.errors, function(key, value) {
                                $('.edit_' + key + '_error').text(value[0]);
                            });
                            toastr.error('Please fix the errors.');
                        }
                    })
                    .catch(function(error) {
                        toastr.error('An error occurred while updating.');
                    });
            });
        });

        // Status Change Confirm Alert
        function showFootageStatusChangeAlert(id) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to update the footage size status?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }).then((result) => {
                if (result.isConfirmed) {
                    footageStatusChange(id);
                }
            });
        }

        // Status Change
        function footageStatusChange(id) {
            let url = '{{ route('service.footage-sizes.status', 0) }}'.replace('/0', '/' + id);
            $.ajax({
                type: "GET",
                url: url,
                success: function(resp) {
                    $('#datatable-footage-sizes').DataTable().ajax.reload();
                    if (resp.success === true) {
                        toastr.success(resp.message);
                    } else if (resp.errors) {
                        toastr.error(resp.errors[0]);
                    } else {
                        toastr.error(resp.message);
                    }
                },
                error: function(error) {
                    toastr.error('An error occurred. Please try again.');
                }
            });
        }

        // Delete Confirm
        function showFootageDeleteConfirm(id) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure you want to delete this footage size?',
                text: 'If you delete this, it will be gone forever.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteFootageSize(id);
                }
            });
        }

        // Delete Item
        function deleteFootageSize(id) {
            let url = '{{ route('service.footage-sizes.destroy', 0) }}'.replace('/0', '/' + id);
            let csrfToken = '{{ csrf_token() }}';
            $.ajax({
                type: "DELETE",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(resp) {
                    $('#datatable-footage-sizes').DataTable().ajax.reload();
                    if (resp['t-success']) {
                        toastr.success(resp.message);
                    } else {
                        toastr.error(resp.message);
                    }
                },
                error: function(error) {
                    toastr.error('An error occurred. Please try again.');
                }
            });
        }
    </script>

    {{-- This Script is for Services Module --}}
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            var servicesTable = $('#datatable-services').DataTable({
                responsive: true,
                order: [],
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"],
                ],
                processing: true,
                serverSide: true,
                pagingType: "full_numbers",
                ajax: {
                    url: "{{ route('service.index') }}",
                    type: "GET",
                },
                dom: "<'row table-topbar'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>>" +
                    "<'row'<'col-12'tr>>" +
                    "<'row table-bottom'<'col-md-5 dataTables_left'i><'col-md-7'p>>",
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search records...",
                    lengthMenu: "Show _MENU_ entries",
                    processing: `
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>`,
                },
                // Turn off autoWidth so column widths are respected.
                autoWidth: false,
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '5%'
                    },
                    {
                        data: 'package_title',
                        name: 'package_title',
                        orderable: true,
                        searchable: true,
                        width: '20%',
                    },
                    {
                        data: 'footage_size',
                        name: 'footage_size',
                        orderable: true,
                        searchable: true,
                        width: '15%',
                    },
                    {
                        data: 'service_item_name',
                        name: 'service_item_name',
                        orderable: true,
                        searchable: true,
                        width: '20%',
                    },
                    {
                        data: 'quantity',
                        name: 'quantity',
                        orderable: true,
                        searchable: false,
                        className: 'text-center',
                        width: '10%',
                    },
                    {
                        data: 'formatted_price',
                        name: 'formatted_price',
                        orderable: true,
                        searchable: false,
                        className: 'text-center',
                        width: '10%',
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '10%'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '10%'
                    },
                ],
            });

            // Show Create Modal and Load Form Data
            $('#addNewService').click(function() {
                $('#createServiceModal').modal('show');
                $('#createServiceForm')[0].reset();
                $('.error-text').text('');
                loadFormData();
            });

            // Load Form Data for Dropdowns
            function loadFormData() {
                axios.get("{{ route('service.form-data') }}")
                    .then(function(response) {
                        if (response.data.success) {
                            const data = response.data.data;

                            // Populate Package Dropdown
                            let packageOptions = '<option value="">Choose a package...</option>';
                            data.packages.forEach(function(package) {
                                packageOptions +=
                                    `<option value="${package.id}">${package.title}</option>`;
                            });
                            $('#package_id').html(packageOptions);

                            // Populate Footage Size Dropdown
                            let footageOptions = '<option value="">Choose a footage size...</option>';
                            data.footage_sizes.forEach(function(footage) {
                                footageOptions +=
                                    `<option value="${footage.id}">${footage.size}</option>`;
                            });
                            $('#footage_size_id').html(footageOptions);

                            // Populate Service Item Dropdown
                            let serviceItemOptions = '<option value="">Choose a service item...</option>';
                            data.service_items.forEach(function(item) {
                                serviceItemOptions +=
                                    `<option value="${item.id}">${item.service_name}</option>`;
                            });
                            $('#service_item_id').html(serviceItemOptions);
                        } else {
                            toastr.error('Failed to load form data');
                        }
                    })
                    .catch(function(error) {
                        toastr.error('An error occurred while loading form data');
                        console.error(error);
                    });
            }

            // Handle Create Form Submission
            $('#createServiceForm').submit(function(e) {
                e.preventDefault();
                $('.error-text').text('');
                let formData = $(this).serialize();

                axios.post("{{ route('service.store') }}", formData)
                    .then(function(response) {
                        if (response.data.success) {
                            $('#createServiceModal').modal('hide');
                            $('#createServiceForm')[0].reset();
                            servicesTable.ajax.reload();
                            toastr.success(response.data.message);
                        } else {
                            if (response.data.errors) {
                                $.each(response.data.errors, function(key, value) {
                                    $('.create_' + key + '_error').text(value[0]);
                                });
                                toastr.error('Please fix the errors.');
                            } else {
                                toastr.error(response.data.message);
                            }
                        }
                    })
                    .catch(function(error) {
                        toastr.error('An error occurred while creating service.');
                        console.error(error);
                    });
            });

            // Show Edit Modal
            $(document).on('click', '.edit-service', function() {
                let tr = $(this).closest('tr');
                let rowData = servicesTable.row(tr).data();
                // Set the service ID
                $('#edit_service_id').val(rowData.id);
                // Load form data first, then populate with current values
                loadEditFormData(rowData);
                $('#editServiceModal').modal('show');
            });

            // Load Form Data for Edit Modal
            function loadEditFormData(currentData) {
                axios.get("{{ route('service.form-data') }}")
                    .then(function(response) {
                        if (response.data.success) {
                            const data = response.data.data;

                            // Populate Package Dropdown
                            let packageOptions = '<option value="">Choose a package...</option>';
                            data.packages.forEach(function(package) {
                                const selected = package.id == currentData.package_id ? 'selected' : '';
                                packageOptions +=
                                    `<option value="${package.id}" ${selected}>${package.title}</option>`;
                            });
                            $('#edit_package_id').html(packageOptions);

                            // Populate Footage Size Dropdown
                            let footageOptions = '<option value="">Choose a footage size...</option>';
                            data.footage_sizes.forEach(function(footage) {
                                const selected = footage.id == currentData.footage_size_id ?
                                    'selected' : '';
                                footageOptions +=
                                    `<option value="${footage.id}" ${selected}>${footage.size}</option>`;
                            });
                            $('#edit_footage_size_id').html(footageOptions);

                            // Populate Service Item Dropdown
                            let serviceItemOptions = '<option value="">Choose a service item...</option>';
                            data.service_items.forEach(function(item) {
                                const selected = item.id == currentData.service_item_id ? 'selected' :
                                    '';
                                serviceItemOptions +=
                                    `<option value="${item.id}" ${selected}>${item.service_name}</option>`;
                            });
                            $('#edit_service_item_id').html(serviceItemOptions);

                            // Set quantity and price
                            $('#edit_quantity').val(currentData.quantity);
                            $('#edit_price').val(currentData.price);
                        } else {
                            toastr.error('Failed to load form data');
                        }
                    })
                    .catch(function(error) {
                        toastr.error('An error occurred while loading form data');
                        console.error(error);
                    });
            }

            // Handle Edit Form Submission
            $('#editServiceForm').submit(function(e) {
                e.preventDefault();
                $('.error-text').text('');
                let formData = $(this).serialize();
                let serviceId = $('#edit_service_id').val();

                axios.put("{{ route('service.update', 0) }}".replace('/0', '/' + serviceId), formData)
                    .then(function(response) {
                        if (response.data.success) {
                            $('#editServiceModal').modal('hide');
                            $('#editServiceForm')[0].reset();
                            servicesTable.ajax.reload(); // Use servicesTable instead of table
                            toastr.success(response.data.message);
                        } else {
                            if (response.data.errors) {
                                $.each(response.data.errors, function(key, value) {
                                    $('.edit_' + key + '_error').text(value[0]);
                                });
                                toastr.error('Please fix the errors.');
                            } else {
                                toastr.error(response.data.message);
                            }
                        }
                    })
                    .catch(function(error) {
                        if (error.response && error.response.data && error.response.data.message) {
                            toastr.error(error.response.data.message);
                        } else {
                            toastr.error('An error occurred while updating.');
                        }
                        console.error(error);
                    });
            });
        });

        // Status Change Confirm Alert
        function showStatusChangeAlert(id) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to update the status?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }).then((result) => {
                if (result.isConfirmed) {
                    statusChange(id);
                }
            });
        }

        // Status Change
        function statusChange(id) {
            let url = '{{ route('service.status', 0) }}'.replace('/0', '/' + id);
            $.ajax({
                type: "GET",
                url: url,
                success: function(resp) {
                    $('#datatable-services').DataTable().ajax.reload();
                    if (resp.success === true) {
                        toastr.success(resp.message);
                    } else if (resp.errors) {
                        toastr.error(resp.errors[0]);
                    } else {
                        toastr.error(resp.message);
                    }
                },
                error: function(error) {
                    toastr.error('An error occurred. Please try again.');
                }
            });
        }

        // Delete Confirm
        function showServiceDeleteConfirm(id) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure you want to delete this service?',
                text: 'If you delete this, it will be gone forever.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteService(id);
                }
            });
        }

        // Delete Service
        function deleteService(id) {
            let url = '{{ route('service.destroy', 0) }}'.replace('/0', '/' + id);
            let csrfToken = '{{ csrf_token() }}';
            $.ajax({
                type: "DELETE",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(resp) {
                    $('#datatable-services').DataTable().ajax.reload();
                    if (resp['t-success']) {
                        toastr.success(resp.message);
                    } else {
                        toastr.error(resp.message);
                    }
                },
                error: function(error) {
                    toastr.error('An error occurred. Please try again.');
                }
            });
        }
    </script>
@endpush
