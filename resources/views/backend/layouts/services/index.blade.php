@extends('backend.app')

@section('title', 'Services')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
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
@endsection

@push('scripts')
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
                columnDefs: [{
                    targets: -1,
                    render: function(data, type, row) {
                        return `
                            <div class="hstack gap-3 fs-base">
                                <a href="javascript:void(0);" class="link-primary text-decoration-none edit-size" data-id="${row.id}" title="Edit">
                                    <i class="ri-pencil-line" style="font-size: 24px;"></i>
                                </a>
                                <a href="javascript:void(0);" onclick="showDeleteConfirm('${row.id}')" class="link-danger text-decoration-none" title="Delete">
                                    <i class="ri-delete-bin-5-line" style="font-size: 24px;"></i>
                                </a>
                            </div>
                        `;
                    },
                }],
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
        function showDeleteConfirm(id) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure you want to delete this record?',
                text: 'If you delete this, it will be gone forever.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteItem(id);
                }
            });
        }

        // Delete Item
        function deleteItem(id) {
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
                columnDefs: [{
                    targets: -1,
                    render: function(data, type, row) {
                        return `
                            <div class="hstack gap-3 fs-base">
                                <a href="javascript:void(0);" class="link-primary text-decoration-none edit-item" data-id="${row.id}" title="Edit">
                                    <i class="ri-pencil-line" style="font-size: 24px;"></i>
                                </a>
                                <a href="javascript:void(0);" onclick="showDeleteConfirm('${row.id}')" class="link-danger text-decoration-none" title="Delete">
                                    <i class="ri-delete-bin-5-line" style="font-size: 24px;"></i>
                                </a>
                            </div>
                        `;
                    },
                }],
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

        // Delete Confirm
        function showDeleteConfirm(id) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure you want to delete this record?',
                text: 'If you delete this, it will be gone forever.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteItem(id);
                }
            });
        }

        // Delete Item
        function deleteItem(id) {
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
@endpush
