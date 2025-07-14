@extends('backend.app')

@section('title', 'Services Area')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">All Services Area List</h5>
                            <button type="button" class="btn btn-primary btn-sm" id="addNewServiceArea">Add New</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatable"
                                    class="table table-bordered table-striped align-middle dt-responsive nowrap"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="column-id">#</th>
                                            <th class="column-content">Zip Code</th>
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

    {{-- Create Modal Start --}}
    <div class="modal fade" id="createServiceAreaModal" tabindex="-1" aria-labelledby="createServiceAreaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createServiceAreaModalLabel">Create New Service Area</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="createServiceAreaForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="create_zip_code" class="form-label">Enter Zip Code:</label>
                            <input type="text" class="form-control" id="create_zip_code" name="zip_code"
                                placeholder="Please Enter Zip Code">
                            <span class="text-danger error-text create_zip_code_error"></span>
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
    {{-- Create Modal End --}}

    {{-- Edit Modal Start --}}
    <div class="modal fade" id="editServiceAreaModal" tabindex="-1" aria-labelledby="editServiceAreaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editServiceAreaModalLabel">Edit Service Area</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editServiceAreaForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_service_area_id" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_zip_code" class="form-label">Zip Code:</label>
                            <input type="text" class="form-control" id="edit_zip_code" name="zip_code"
                                placeholder="Please Enter Zip Code">
                            <span class="text-danger error-text edit_zip_code_error"></span>
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
    {{-- Edit Modal End --}}
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            var table = $('#datatable').DataTable({
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
                    url: "{{ route('service-area.index') }}",
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
                        data: 'zip_code',
                        name: 'zip_code',
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
                                <a href="javascript:void(0);" class="link-primary text-decoration-none edit-service-area" data-id="${row.id}" title="Edit">
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
            $('#addNewServiceArea').click(function() {
                $('#createServiceAreaModal').modal('show');
                $('#createServiceAreaForm')[0].reset();
                $('.error-text').text('');
            });

            // Handle Create Form Submission
            $('#createServiceAreaForm').submit(function(e) {
                e.preventDefault();
                $('.error-text').text('');
                let formData = $(this).serialize();

                axios.post("{{ route('service-area.store') }}", formData)
                    .then(function(response) {
                        if (response.data.success) {
                            $('#createServiceAreaModal').modal('hide');
                            $('#createServiceAreaForm')[0].reset();
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
            $(document).on('click', '.edit-service-area', function() {
                let tr = $(this).closest('tr');
                let rowData = table.row(tr).data();
                $('#edit_service_area_id').val(rowData.id);
                $('#edit_zip_code').val(rowData.zip_code);
                $('#editServiceAreaModal').modal('show');
            });

            // Handle Edit Form Submission
            $('#editServiceAreaForm').submit(function(e) {
                e.preventDefault();
                $('.error-text').text('');
                let formData = $(this).serialize();
                let serviceAreaId = $('#edit_service_area_id').val();

                axios.put("{{ route('service-area.update', 0) }}".replace('/0', '/' + serviceAreaId),
                        formData)
                    .then(function(response) {
                        if (response.data.success) {
                            $('#editServiceAreaModal').modal('hide');
                            $('#editServiceAreaForm')[0].reset();
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
                        toastr.error('An error occurred while updating the service.');
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
            let url = '{{ route('service-area.status', 0) }}'.replace('/0', '/' + id);
            $.ajax({
                type: "GET",
                url: url,
                success: function(resp) {
                    $('#datatable').DataTable().ajax.reload();
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
            let url = '{{ route('service-area.destroy', 0) }}'.replace('/0', '/' + id);
            let csrfToken = '{{ csrf_token() }}';
            $.ajax({
                type: "DELETE",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(resp) {
                    $('#datatable').DataTable().ajax.reload();
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
