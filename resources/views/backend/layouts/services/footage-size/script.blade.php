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
