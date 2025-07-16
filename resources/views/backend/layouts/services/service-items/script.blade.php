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
                error: function(xhr, error, code) {
                    console.log('DataTable Ajax Error:', xhr.responseText);
                    toastr.error('Failed to load service items. Please refresh the page.');
                }
            },
            dom: "<'row table-topbar'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>>" +
                "<'row'<'col-12'tr>>" +
                "<'row table-bottom'<'col-md-5 dataTables_left'i><'col-md-7'p>>",
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search service items...",
                lengthMenu: "Show _MENU_ entries",
                processing: `
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>`,
            },
            autoWidth: false,
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    width: '10%'
                },
                {
                    data: 'service_name',
                    name: 'service_name',
                    orderable: true,
                    searchable: true,
                    width: '70%',
                    render: function(data) {
                        return '<div style="white-space:normal;word-break:break-word;">' +
                            data + '</div>';
                    }
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

        // Real-time validation for service name input
        function validateServiceName(input) {
            const value = input.val().trim();
            const errorElement = input.siblings('.error-text');

            // Clear previous error
            errorElement.text('');
            input.removeClass('is-invalid is-valid');

            if (!value) {
                errorElement.text('Service item name is required.');
                input.addClass('is-invalid');
                return false;
            }

            // Check minimum length
            if (value.length < 2) {
                errorElement.text('Service item name must be at least 2 characters.');
                input.addClass('is-invalid');
                return false;
            }

            // Check maximum length
            if (value.length > 100) {
                errorElement.text('Service item name cannot exceed 100 characters.');
                input.addClass('is-invalid');
                return false;
            }

            // Check for valid characters
            const validPattern = /^[a-zA-Z0-9\s\-']+$/;
            if (!validPattern.test(value)) {
                errorElement.text('Only letters, numbers, spaces, hyphens, and apostrophes are allowed.');
                input.addClass('is-invalid');
                return false;
            }

            // Check for consecutive spaces
            if (/\s{2,}/.test(value)) {
                errorElement.text('Cannot contain consecutive spaces.');
                input.addClass('is-invalid');
                return false;
            }

            // Check if starts or ends with special characters
            if (/^[\s\-']+|[\s\-']+$/.test(value)) {
                errorElement.text('Cannot start or end with spaces, hyphens, or apostrophes.');
                input.addClass('is-invalid');
                return false;
            }

            // Must contain at least one letter
            if (!/[a-zA-Z]/.test(value)) {
                errorElement.text('Must contain at least one letter.');
                input.addClass('is-invalid');
                return false;
            }

            input.addClass('is-valid');
            return true;
        }

        // Add real-time validation to input fields
        $('#service_name, #edit_service_name').on('input blur', function() {
            validateServiceName($(this));
        });

        // Format input as user types
        $('#service_name, #edit_service_name').on('input', function() {
            let value = $(this).val();
            // Remove multiple spaces
            value = value.replace(/\s+/g, ' ');
            $(this).val(value);
        });

        // Show Create Modal
        $('#addNewItem').click(function() {
            $('#createItemModal').modal('show');
            $('#createItemForm')[0].reset();
            $('.error-text').text('');
            $('.form-control').removeClass('is-invalid is-valid');
        });

        // Handle Create Form Submission
        $('#createItemForm').submit(function(e) {
            e.preventDefault();
            $('.error-text').text('');
            $('.form-control').removeClass('is-invalid');

            // Validate before submission
            const isValid = validateServiceName($('#service_name'));
            if (!isValid) {
                toastr.error('Please fix the validation errors before submitting.');
                return;
            }

            let formData = $(this).serialize();
            let submitBtn = $(this).find('button[type="submit"]');
            let originalText = submitBtn.html();

            submitBtn.prop('disabled', true).html(
                '<i class="fas fa-spinner fa-spin me-1"></i>Creating...');

            axios.post("{{ route('service.item.store') }}", formData)
                .then(function(response) {
                    if (response.data.success) {
                        $('#createItemModal').modal('hide');
                        $('#createItemForm')[0].reset();
                        $('.form-control').removeClass('is-valid');
                        table.ajax.reload(null, false);
                        toastr.success(response.data.message);
                    } else {
                        if (response.data.errors) {
                            $.each(response.data.errors, function(key, value) {
                                $('.create_' + key + '_error').text(value[0]);
                                $('[name="' + key + '"]').addClass('is-invalid');
                            });
                            toastr.error('Please fix the validation errors.');
                        } else {
                            toastr.error(response.data.message || 'Failed to create service item.');
                        }
                    }
                })
                .catch(function(error) {
                    console.error('Create Error:', error);
                    if (error.response && error.response.data && error.response.data.errors) {
                        $.each(error.response.data.errors, function(key, value) {
                            $('.create_' + key + '_error').text(value[0]);
                            $('[name="' + key + '"]').addClass('is-invalid');
                        });
                        toastr.error('Please fix the validation errors.');
                    } else {
                        toastr.error('An error occurred while creating the service item.');
                    }
                })
                .finally(function() {
                    submitBtn.prop('disabled', false).html(originalText);
                });
        });

        // Show Edit Modal
        $(document).on('click', '.edit-item', function() {
            let tr = $(this).closest('tr');
            let rowData = table.row(tr).data();

            $('#edit_item_id').val(rowData.id);
            $('#edit_service_name').val(rowData.service_name);
            $('.error-text').text('');
            $('.form-control').removeClass('is-invalid is-valid');
            $('#editItemModal').modal('show');
        });

        // Handle Edit Form Submission
        $('#editItemForm').submit(function(e) {
            e.preventDefault();
            $('.error-text').text('');
            $('.form-control').removeClass('is-invalid');

            // Validate before submission
            const isValid = validateServiceName($('#edit_service_name'));
            if (!isValid) {
                toastr.error('Please fix the validation errors before submitting.');
                return;
            }

            let formData = $(this).serialize();
            let itemId = $('#edit_item_id').val();
            let submitBtn = $(this).find('button[type="submit"]');
            let originalText = submitBtn.html();

            if (!itemId) {
                toastr.error('Service item ID is missing. Please try again.');
                return;
            }

            submitBtn.prop('disabled', true).html(
                '<i class="fas fa-spinner fa-spin me-1"></i>Updating...');

            axios.put("{{ route('service.item.update', 0) }}".replace('/0', '/' + itemId), formData)
                .then(function(response) {
                    if (response.data.success) {
                        $('#editItemModal').modal('hide');
                        $('#editItemForm')[0].reset();
                        $('.form-control').removeClass('is-valid');
                        table.ajax.reload(null, false);
                        toastr.success(response.data.message);
                    } else {
                        if (response.data.errors) {
                            $.each(response.data.errors, function(key, value) {
                                $('.edit_' + key + '_error').text(value[0]);
                                $('#editItemForm [name="' + key + '"]').addClass(
                                    'is-invalid');
                            });
                            toastr.error('Please fix the validation errors.');
                        } else {
                            toastr.error(response.data.message || 'Failed to update service item.');
                        }
                    }
                })
                .catch(function(error) {
                    console.error('Update Error:', error);
                    if (error.response && error.response.data && error.response.data.errors) {
                        $.each(error.response.data.errors, function(key, value) {
                            $('.edit_' + key + '_error').text(value[0]);
                            $('#editItemForm [name="' + key + '"]').addClass('is-invalid');
                        });
                        toastr.error('Please fix the validation errors.');
                    } else {
                        toastr.error('An error occurred while updating.');
                    }
                })
                .finally(function() {
                    submitBtn.prop('disabled', false).html(originalText);
                });
        });

        // Clear validation state when modal is hidden
        $('#createItemModal, #editItemModal').on('hidden.bs.modal', function() {
            $(this).find('.error-text').text('');
            $(this).find('.form-control').removeClass('is-invalid is-valid');
        });
    });

    // Service Item Status Change Confirm Alert
    function showItemStatusChangeAlert(id) {
        if (event) {
            event.preventDefault();
        }

        Swal.fire({
            title: 'Update Status',
            text: 'Are you sure you want to change the status of this service item?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Update',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                itemStatusChange(id);
            } else {
                let checkbox = $('#SwitchCheck' + id);
                checkbox.prop('checked', !checkbox.prop('checked'));
            }
        });
    }

    // Service Item Status Change
    function itemStatusChange(id) {
        let url = '{{ route('service.item.status', 0) }}'.replace('/0', '/' + id);
        let checkbox = $('#SwitchCheck' + id);
        let originalState = checkbox.prop('checked');
        checkbox.prop('disabled', true);

        $.ajax({
            type: "GET",
            url: url,
            timeout: 10000,
            success: function(resp) {
                $('#datatable-service-items').DataTable().ajax.reload(null, false);
                if (resp.success === true) {
                    toastr.success(resp.message);
                } else {
                    toastr.error(resp.message || 'Unknown error occurred.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Status Update Error:', xhr.responseText);
                checkbox.prop('checked', originalState);
                toastr.error('An error occurred while updating status.');
            },
            complete: function() {
                checkbox.prop('disabled', false);
            }
        });
    }

    // Service Item Delete Confirm
    function showItemDeleteConfirm(id) {
        if (event) {
            event.preventDefault();
        }

        Swal.fire({
            title: 'Delete Service Item',
            text: 'Are you sure you want to delete this service item? This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true
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

        Swal.fire({
            title: 'Deleting...',
            text: 'Please wait while we delete the service item.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            type: "DELETE",
            url: url,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            timeout: 10000,
            success: function(resp) {
                Swal.close();
                $('#datatable-service-items').DataTable().ajax.reload(null, false);
                if (resp['t-success']) {
                    toastr.success(resp.message);
                } else {
                    toastr.error(resp.message);
                }
            },
            error: function(xhr, status, error) {
                Swal.close();
                console.error('Delete Error:', xhr.responseText);
                toastr.error('An error occurred while deleting the service item.');
            }
        });
    }
</script>
