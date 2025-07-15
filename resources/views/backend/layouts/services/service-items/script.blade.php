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
