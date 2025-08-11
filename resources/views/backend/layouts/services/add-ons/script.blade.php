<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        var addOnsTable = $('#datatable-add-ons').DataTable({
            responsive: true,
            order: [],
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            processing: true,
            serverSide: true,
            pagingType: "full_numbers",
            ajax: {
                url: "{{ route('service.add-on.index') }}",
                type: "GET",
                error: function(xhr, error, code) {
                    console.log('DataTable Ajax Error:', xhr.responseText);
                    toastr.error('Failed to load add-ons. Please refresh the page.');
                }
            },
            dom: "<'row table-topbar'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>>" +
                "<'row'<'col-12'tr>>" +
                "<'row table-bottom'<'col-md-5 dataTables_left'i><'col-md-7'p>>",
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search add-ons...",
                lengthMenu: "Show _MENU_ entries",
                processing: `<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>`,
            },
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
                    data: 'footage_size',
                    name: 'footage_size',
                    orderable: true,
                    searchable: true,
                    width: '20%'
                },
                {
                    data: 'service_item_name',
                    name: 'service_item_name',
                    orderable: true,
                    searchable: true,
                    width: '30%'
                },
                {
                    data: 'quantity_display',
                    name: 'quantity_display',
                    orderable: true,
                    searchable: true,
                    className: 'text-center',
                    width: '20%'
                },
                {
                    data: 'maximum_number',
                    name: 'maximum_number',
                    orderable: true,
                    searchable: true,
                    className: 'text-center',
                    width: '5%'
                },
                {
                    data: 'formatted_price',
                    name: 'formatted_price',
                    orderable: true,
                    searchable: true,
                    className: 'text-center',
                    width: '10%'
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

        // helper: toggle create maximum_number UI
        function toggleCreateIncrementUI(checked) {
            if (checked) {
                $('#maximum_number_field').show();
                $('#maximum_number').prop('disabled', false).prop('required', true);
            } else {
                $('#maximum_number_field').hide();
                $('#maximum_number').prop('disabled', true).prop('required', false).val('');
            }
        }

        // helper: toggle edit maximum_number UI
        function toggleEditIncrementUI(checked) {
            if (checked) {
                $('#edit_maximum_number_field').show();
                $('#edit_maximum_number').prop('disabled', false).prop('required', true);
            } else {
                $('#edit_maximum_number_field').hide();
                $('#edit_maximum_number').prop('disabled', true).prop('required', false).val('');
            }
        }

        // Show Create Modal
        $('#addNewAddOn').click(function() {
            $('#createAddOnModal').modal('show');
            $('#createAddOnForm')[0].reset();
            $('.error-text').text('');
            $('.form-control, .form-select').removeClass('is-invalid');

            // Hide Community Images fields initially
            $('#locations_field, #community_pricing_guide').hide();

            $('#is_increment').prop('checked', false);
            toggleCreateIncrementUI(false);

            loadFormData();
        });

        // Create checkbox change
        $(document).on('change', '#is_increment', function() {
            toggleCreateIncrementUI(this.checked);
        });

        // Handle Service Item Change for Create Modal
        $(document).on('change', '#service_item_id', function() {
            const selectedText = $(this).find('option:selected').text().toLowerCase();
            const isCommunityImages = selectedText === 'community image';

            if (isCommunityImages) {
                $('#locations_field, #community_pricing_guide').show();
                $('#locations').prop('required', true);
            } else {
                $('#locations_field, #community_pricing_guide').hide();
                $('#locations').prop('required', false).val('');
            }
        });

        // Handle Service Item Change for Edit Modal
        $(document).on('change', '#edit_service_item_id', function() {
            const selectedText = $(this).find('option:selected').text().toLowerCase();
            const isCommunityImages = selectedText === 'community image';

            if (isCommunityImages) {
                $('#edit_locations_field, #edit_community_pricing_guide').show();
                $('#edit_locations').prop('required', true);
            } else {
                $('#edit_locations_field, #edit_community_pricing_guide').hide();
                $('#edit_locations').prop('required', false).val('');
            }
        });

        // Load Form Data for Dropdowns
        function loadFormData() {
            axios.get("{{ route('service.add-on.form-data') }}")
                .then(function(response) {
                    if (response.data.success) {
                        const data = response.data.data;

                        let footageOptions = '<option value="">Choose a footage size...</option>';
                        if (data.footage_sizes && data.footage_sizes.length > 0) {
                            data.footage_sizes.forEach(function(footage) {
                                footageOptions +=
                                    `<option value="${footage.id}">${footage.size}</option>`;
                            });
                        }
                        $('#footage_size_id_for_add_ons').html(footageOptions);

                        let serviceItemOptions = '<option value="">Choose a service item...</option>';
                        if (data.service_items && data.service_items.length > 0) {
                            data.service_items.forEach(function(item) {
                                serviceItemOptions +=
                                    `<option value="${item.id}">${item.service_name}</option>`;
                            });
                        }
                        $('#service_item_id').html(serviceItemOptions);
                    } else {
                        toastr.error('Failed to load form data');
                    }
                })
                .catch(function() {
                    toastr.error('An error occurred while loading form data');
                });
        }

        // Create Form Submission
        $('#createAddOnForm').submit(function(e) {
            e.preventDefault();

            $('.error-text').text('');
            $('.form-control, .form-select').removeClass('is-invalid');

            let formData = $(this).serialize();

            let submitBtn = $(this).find('button[type="submit"]');
            let originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html(
                '<i class="fas fa-spinner fa-spin me-1"></i>Creating...');

            axios.post("{{ route('service.add-on.store') }}", formData)
                .then(function(response) {
                    if (response.data.success) {
                        $('#createAddOnModal').modal('hide');
                        $('#createAddOnForm')[0].reset();
                        addOnsTable.ajax.reload();
                        toastr.success(response.data.message);
                    } else {
                        if (response.data.errors) {
                            handleValidationErrors(response.data.errors, 'create');
                            toastr.error('Please fix the errors.');
                        } else {
                            toastr.error(response.data.message);
                        }
                    }
                })
                .catch(function(error) {
                    if (error.response && error.response.data && error.response.data.errors) {
                        handleValidationErrors(error.response.data.errors, 'create');
                        toastr.error('Please fix the validation errors.');
                    } else {
                        toastr.error('An error occurred while creating add-on.');
                    }
                })
                .finally(function() {
                    submitBtn.prop('disabled', false).html(originalText);
                });
        });

        // Show Edit Modal
        $(document).on('click', '.edit-add-on', function() {
            let tr = $(this).closest('tr');
            let rowData = addOnsTable.row(tr).data();

            $('.error-text').text('');
            $('.form-control, .form-select').removeClass('is-invalid');

            $('#edit_service_id').val(rowData.id);

            // Hide Community Images fields initially
            $('#edit_locations_field, #edit_community_pricing_guide').hide();

            // Reset increment UI
            $('#edit_is_increment').prop('checked', false);
            toggleEditIncrementUI(false);

            loadEditFormData(rowData.id);
            $('#editAddOnModal').modal('show');
        });

        // Load Edit Form Data
        function loadEditFormData(addOnId) {
            // Show loading state
            $('#edit_footage_size_id_for_add_ons, #edit_service_item_id').html(
                '<option value="">Loading...</option>').prop('disabled', true);
            $('#edit_quantity, #edit_price_for_add_ons, #edit_locations').val('').prop('disabled', true);

            Promise.all([
                    axios.get("{{ route('service.add-on.form-data') }}"),
                    axios.get("{{ route('service.add-on.show', 0) }}".replace('/0', '/' + addOnId))
                ])
                .then(function(responses) {
                    const formDataResponse = responses[0];
                    const addOnResponse = responses[1];

                    if (formDataResponse.data.success && addOnResponse.data.success) {
                        const formData = formDataResponse.data.data;
                        const addOnData = addOnResponse.data.data;

                        // Populate Footage Size
                        let footageOptions = '<option value="">Choose a footage size...</option>';
                        if (formData.footage_sizes && formData.footage_sizes.length > 0) {
                            formData.footage_sizes.forEach(function(footage) {
                                const selected = footage.id == addOnData.footage_size_id ?
                                    'selected' : '';
                                footageOptions +=
                                    `<option value="${footage.id}" ${selected}>${footage.size}</option>`;
                            });
                        }
                        $('#edit_footage_size_id_for_add_ons').html(footageOptions).prop('disabled', false);

                        // Populate Service Item
                        let serviceItemOptions = '<option value="">Choose a service item...</option>';
                        if (formData.service_items && formData.service_items.length > 0) {
                            formData.service_items.forEach(function(item) {
                                const selected = item.id == addOnData.service_item_id ? 'selected' :
                                    '';
                                serviceItemOptions +=
                                    `<option value="${item.id}" ${selected}>${item.service_name}</option>`;
                            });
                        }
                        $('#edit_service_item_id').html(serviceItemOptions).prop('disabled', false);

                        // Set quantity and price
                        $('#edit_quantity').val(addOnData.quantity || '').prop('disabled', false);
                        $('#edit_price_for_add_ons').val(addOnData.price || '').prop('disabled', false);

                        // Handle Community Images fields
                        if (addOnData.is_community_images) {
                            $('#edit_locations_field, #edit_community_pricing_guide').show();
                            $('#edit_locations').val(addOnData.locations || '').prop('required', true);
                        } else {
                            $('#edit_locations_field, #edit_community_pricing_guide').hide();
                            $('#edit_locations').prop('required', false).val('');
                        }

                        // New: set increment UI
                        const isInc = !!addOnData.is_increment;
                        $('#edit_is_increment').prop('checked', isInc);
                        toggleEditIncrementUI(isInc);
                        if (isInc) {
                            $('#edit_maximum_number').val(addOnData.maximum_number || '');
                        }

                        toastr.success('Edit form loaded successfully!');
                    } else {
                        toastr.error('Failed to load add-on data for editing');
                        $('#edit_footage_size_id_for_add_ons, #edit_service_item_id').prop('disabled',
                            false);
                        $('#edit_quantity, #edit_price_for_add_ons, #edit_locations').prop('disabled',
                            false);
                    }
                })
                .catch(function() {
                    toastr.error('An error occurred while loading add-on data');
                    $('#edit_footage_size_id_for_add_ons, #edit_service_item_id').prop('disabled', false);
                    $('#edit_quantity, #edit_price_for_add_ons, #edit_locations').prop('disabled', false);
                });
        }

        // Edit checkbox change
        $(document).on('change', '#edit_is_increment', function() {
            toggleEditIncrementUI(this.checked);
        });

        // Edit Form Submission
        $('#editAddOnForm').submit(function(e) {
            e.preventDefault();

            $('.error-text').text('');
            $('.form-control, .form-select').removeClass('is-invalid');

            let formData = $(this).serialize();
            let addOnId = $('#edit_service_id').val();

            if (!addOnId) {
                toastr.error('Add-on ID is missing. Please try again.');
                return;
            }

            let submitBtn = $(this).find('button[type="submit"]');
            let originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html(
                '<i class="fas fa-spinner fa-spin me-1"></i>Updating...');

            axios.put("{{ route('service.add-on.update', 0) }}".replace('/0', '/' + addOnId), formData)
                .then(function(response) {
                    if (response.data.success) {
                        $('#editAddOnModal').modal('hide');
                        $('#editAddOnForm')[0].reset();
                        addOnsTable.ajax.reload();
                        toastr.success(response.data.message);
                    } else {
                        if (response.data.errors) {
                            handleValidationErrors(response.data.errors, 'edit');
                            toastr.error('Please fix the errors.');
                        } else {
                            toastr.error(response.data.message);
                        }
                    }
                })
                .catch(function(error) {
                    if (error.response && error.response.data) {
                        if (error.response.data.errors) {
                            handleValidationErrors(error.response.data.errors, 'edit');
                            toastr.error('Please fix the validation errors.');
                        } else {
                            toastr.error(error.response.data.message ||
                                'An error occurred while updating.');
                        }
                    } else {
                        toastr.error('An error occurred while updating.');
                    }
                })
                .finally(function() {
                    submitBtn.prop('disabled', false).html(originalText);
                });
        });

        // Validation Error Handler
        function handleValidationErrors(errors, prefix) {
            $.each(errors, function(key, value) {
                let errorMessage = Array.isArray(value) ? value[0] : value;
                let errorElement = $(`.${prefix}_${key}_error`);
                let inputElement = $(`[name="${key}"]`);

                if (prefix === 'edit') {
                    inputElement = $(`#editAddOnForm [name="${key}"]`);
                }

                errorElement.text(errorMessage);
                inputElement.addClass('is-invalid');
            });
        }

        // Clear modal states
        $('#createAddOnModal').on('hidden.bs.modal', function() {
            $(this).find('.error-text').text('');
            $(this).find('.form-control, .form-select').removeClass('is-invalid');
            $('#locations_field, #community_pricing_guide').hide();
            // reset increment ui
            $('#is_increment').prop('checked', false);
            toggleCreateIncrementUI(false);
        });

        $('#editAddOnModal').on('hidden.bs.modal', function() {
            $(this).find('.error-text').text('');
            $(this).find('.form-control, .form-select').removeClass('is-invalid');
            $('#edit_locations_field, #edit_community_pricing_guide').hide();
            // reset increment ui
            $('#edit_is_increment').prop('checked', false);
            toggleEditIncrementUI(false);
        });
    });

    function showAddOnStatusChangeAlert(id) {
        if (event) event.preventDefault();
        Swal.fire({
            title: 'Update Status',
            text: 'Are you sure you want to change the status of this add-on?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Update',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                addOnStatusChange(id);
            } else {
                let checkbox = $('#SwitchCheck' + id);
                checkbox.prop('checked', !checkbox.prop('checked'));
            }
        });
    }

    function addOnStatusChange(id) {
        let url = '{{ route('service.add-on.status', 0) }}'.replace('/0', '/' + id);
        let checkbox = $('#SwitchCheck' + id);
        let originalState = checkbox.prop('checked');
        checkbox.prop('disabled', true);

        $.ajax({
            type: "GET",
            url: url,
            timeout: 10000,
            success: function(resp) {
                $('#datatable-add-ons').DataTable().ajax.reload(null, false);
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

    function showAddOnDeleteConfirm(id) {
        if (event) event.preventDefault();
        Swal.fire({
            title: 'Delete Add-on',
            text: 'Are you sure you want to delete this add-on? This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                deleteAddOn(id);
            }
        });
    }

    function deleteAddOn(id) {
        let url = '{{ route('service.add-on.destroy', 0) }}'.replace('/0', '/' + id);
        let csrfToken = '{{ csrf_token() }}';

        Swal.fire({
            title: 'Deleting...',
            text: 'Please wait while we delete the add-on.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => Swal.showLoading()
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
                $('#datatable-add-ons').DataTable().ajax.reload(null, false);
                if (resp['t-success']) {
                    toastr.success(resp.message);
                } else {
                    toastr.error(resp.message);
                }
            },
            error: function(xhr, status, error) {
                Swal.close();
                console.error('Delete Error:', xhr.responseText);
                toastr.error('An error occurred while deleting the add-on.');
            }
        });
    }
</script>
