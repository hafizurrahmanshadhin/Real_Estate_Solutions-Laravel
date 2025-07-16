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
                error: function(xhr, error, code) {
                    console.log('DataTable Ajax Error:', xhr.responseText);
                    toastr.error('Failed to load square footage sizes. Please refresh the page.');
                }
            },
            dom: "<'row table-topbar'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>>" +
                "<'row'<'col-12'tr>>" +
                "<'row table-bottom'<'col-md-5 dataTables_left'i><'col-md-7'p>>",
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search square footage ranges...",
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
                    data: 'size',
                    name: 'size',
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

        // Real-time validation for footage range input - FIXED VERSION
        function validateFootageRange(input) {
            const value = input.val().trim();
            const errorElement = input.siblings('.error-text');

            // Clear previous error
            errorElement.text('');
            input.removeClass('is-invalid is-valid');

            // Check if empty - FIXED: Now shows error message for empty input
            if (!value) {
                errorElement.text('Square footage range is required.');
                input.addClass('is-invalid');
                return false;
            }

            // Check format
            const rangePattern = /^\d+\s*-\s*\d+$/;
            if (!rangePattern.test(value)) {
                errorElement.text('Format must be: number-number (e.g., 0-10, 11-20)');
                input.addClass('is-invalid');
                return false;
            }

            // Extract numbers
            const matches = value.match(/^(\d+)\s*-\s*(\d+)$/);
            const start = parseInt(matches[1]);
            const end = parseInt(matches[2]);

            // Validate range rules
            if (start < 0 || end < 0) {
                errorElement.text('Cannot use negative numbers. Minimum value is 0.');
                input.addClass('is-invalid');
                return false;
            }

            if (start >= end) {
                errorElement.text('First number must be less than second number.');
                input.addClass('is-invalid');
                return false;
            }

            if (start > 999999 || end > 999999) {
                errorElement.text('Values cannot exceed 999,999.');
                input.addClass('is-invalid');
                return false;
            }

            input.addClass('is-valid');
            return true;
        }

        // Add real-time validation to input fields
        $('#create_size, #edit_size').on('input blur', function() {
            validateFootageRange($(this));
        });

        // Format input as user types
        $('#create_size, #edit_size').on('input', function() {
            let value = $(this).val();
            // Remove multiple spaces
            value = value.replace(/\s+/g, ' ');
            // Ensure proper spacing around dash
            value = value.replace(/(\d)\s*-\s*(\d)/g, '$1-$2');
            $(this).val(value);
        });

        // Show Create Modal
        $('#addNewSize').click(function() {
            $('#createSizeModal').modal('show');
            $('#createSizeForm')[0].reset();
            $('.error-text').text('');
            $('.form-control').removeClass('is-invalid is-valid');
        });

        // Handle Create Form Submission - IMPROVED VERSION
        $('#createSizeForm').submit(function(e) {
            e.preventDefault();
            $('.error-text').text('');
            $('.form-control').removeClass('is-invalid');

            // Validate before submission
            const isValid = validateFootageRange($('#create_size'));
            if (!isValid) {
                toastr.error('Please fix the validation errors before submitting.');
                return;
            }

            let formData = $(this).serialize();
            let submitBtn = $(this).find('button[type="submit"]');
            let originalText = submitBtn.html();

            submitBtn.prop('disabled', true).html(
                '<i class="fas fa-spinner fa-spin me-1"></i>Creating...');

            axios.post("{{ route('service.footage-sizes.store') }}", formData)
                .then(function(response) {
                    if (response.data.success) {
                        $('#createSizeModal').modal('hide');
                        $('#createSizeForm')[0].reset();
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
                            toastr.error(response.data.message ||
                                'Failed to create square footage range.');
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
                        toastr.error('An error occurred while creating the square footage range.');
                    }
                })
                .finally(function() {
                    submitBtn.prop('disabled', false).html(originalText);
                });
        });

        // Show Edit Modal
        $(document).on('click', '.edit-size', function() {
            let tr = $(this).closest('tr');
            let rowData = table.row(tr).data();

            $('#edit_size_id').val(rowData.id);
            $('#edit_size').val(rowData.size);
            $('.error-text').text('');
            $('.form-control').removeClass('is-invalid is-valid');
            $('#editSizeModal').modal('show');
        });

        // Handle Edit Form Submission - IMPROVED VERSION
        $('#editSizeForm').submit(function(e) {
            e.preventDefault();
            $('.error-text').text('');
            $('.form-control').removeClass('is-invalid');

            // Validate before submission
            const isValid = validateFootageRange($('#edit_size'));
            if (!isValid) {
                toastr.error('Please fix the validation errors before submitting.');
                return;
            }

            let formData = $(this).serialize();
            let sizeId = $('#edit_size_id').val();
            let submitBtn = $(this).find('button[type="submit"]');
            let originalText = submitBtn.html();

            if (!sizeId) {
                toastr.error('Square footage range ID is missing. Please try again.');
                return;
            }

            submitBtn.prop('disabled', true).html(
                '<i class="fas fa-spinner fa-spin me-1"></i>Updating...');

            axios.put("{{ route('service.footage-sizes.update', 0) }}".replace('/0', '/' + sizeId),
                    formData)
                .then(function(response) {
                    if (response.data.success) {
                        $('#editSizeModal').modal('hide');
                        $('#editSizeForm')[0].reset();
                        $('.form-control').removeClass('is-valid');
                        table.ajax.reload(null, false);
                        toastr.success(response.data.message);
                    } else {
                        if (response.data.errors) {
                            $.each(response.data.errors, function(key, value) {
                                $('.edit_' + key + '_error').text(value[0]);
                                $('#editSizeForm [name="' + key + '"]').addClass(
                                    'is-invalid');
                            });
                            toastr.error('Please fix the validation errors.');
                        } else {
                            toastr.error(response.data.message ||
                                'Failed to update square footage range.');
                        }
                    }
                })
                .catch(function(error) {
                    console.error('Update Error:', error);
                    if (error.response && error.response.data && error.response.data.errors) {
                        $.each(error.response.data.errors, function(key, value) {
                            $('.edit_' + key + '_error').text(value[0]);
                            $('#editSizeForm [name="' + key + '"]').addClass('is-invalid');
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
        $('#createSizeModal, #editSizeModal').on('hidden.bs.modal', function() {
            $(this).find('.error-text').text('');
            $(this).find('.form-control').removeClass('is-invalid is-valid');
        });
    });

    // Status Change Confirm Alert
    function showFootageStatusChangeAlert(id) {
        if (event) {
            event.preventDefault();
        }

        Swal.fire({
            title: 'Update Status',
            text: 'Are you sure you want to change the status of this square footage range?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Update',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                footageStatusChange(id);
            } else {
                let checkbox = $('#SwitchCheck' + id);
                checkbox.prop('checked', !checkbox.prop('checked'));
            }
        });
    }

    // Status Change
    function footageStatusChange(id) {
        let url = '{{ route('service.footage-sizes.status', 0) }}'.replace('/0', '/' + id);
        let checkbox = $('#SwitchCheck' + id);
        let originalState = checkbox.prop('checked');
        checkbox.prop('disabled', true);

        $.ajax({
            type: "GET",
            url: url,
            timeout: 10000,
            success: function(resp) {
                $('#datatable-footage-sizes').DataTable().ajax.reload(null, false);
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

    // Delete Confirm
    function showFootageDeleteConfirm(id) {
        if (event) {
            event.preventDefault();
        }

        Swal.fire({
            title: 'Delete Square Footage Range',
            text: 'Are you sure you want to delete this square footage range? This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true
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

        Swal.fire({
            title: 'Deleting...',
            text: 'Please wait while we delete the square footage range.',
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
                $('#datatable-footage-sizes').DataTable().ajax.reload(null, false);
                if (resp['t-success']) {
                    toastr.success(resp.message);
                } else {
                    toastr.error(resp.message);
                }
            },
            error: function(xhr, status, error) {
                Swal.close();
                console.error('Delete Error:', xhr.responseText);
                toastr.error('An error occurred while deleting the square footage range.');
            }
        });
    }
</script>
