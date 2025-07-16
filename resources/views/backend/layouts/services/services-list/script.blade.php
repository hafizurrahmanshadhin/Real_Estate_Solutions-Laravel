<script>
    let serviceItemCounter = 0;
    let editServiceItemCounter = 0;

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
                error: function(xhr, error, code) {
                    console.log('DataTable Ajax Error:', xhr.responseText);
                    toastr.error('Failed to load services. Please refresh the page.');
                }
            },
            dom: "<'row table-topbar'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>>" +
                "<'row'<'col-12'tr>>" +
                "<'row table-bottom'<'col-md-5 dataTables_left'i><'col-md-7'p>>",
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search services...",
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
                    width: '5%'
                },
                {
                    data: 'package_footage_group',
                    name: 'package_footage_group',
                    orderable: true,
                    searchable: true,
                    width: '25%',
                    render: function(data) {
                        return '<div style="white-space:normal;word-break:break-word;">' +
                            data + '</div>';
                    }
                },
                {
                    data: 'service_items_display',
                    name: 'service_items_display',
                    orderable: false,
                    searchable: true,
                    width: '45%',
                    render: function(data) {
                        return '<div style="white-space:normal;word-break:break-word;">' +
                            data + '</div>';
                    }
                },
                {
                    data: 'total_items_count',
                    name: 'total_items_count',
                    orderable: true,
                    searchable: false,
                    className: 'text-center',
                    width: '5%'
                },
                {
                    data: 'total_quantity',
                    name: 'total_quantity',
                    orderable: true,
                    searchable: false,
                    className: 'text-center',
                    width: '5%'
                },
                {
                    data: 'formatted_price',
                    name: 'formatted_price',
                    orderable: true,
                    searchable: false,
                    className: 'text-center',
                    width: '5%'
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
                }
            ]
        });

        // Show Create Modal and Load Form Data
        $('#addNewService').click(function() {
            $('#createServiceModal').modal('show');
            $('#createServiceForm')[0].reset();
            $('.error-text').text('');
            $('.form-control, .form-select').removeClass('is-invalid');

            // Reset service items
            serviceItemCounter = 0;
            $('#serviceItemsContainer').empty();

            loadFormData();
        });

        // Add Service Item Row for Create
        $('#addServiceItem').click(function() {
            addServiceItemRow();
        });

        // Add Service Item Row for Edit
        $('#editAddServiceItem').click(function() {
            addEditServiceItemRow();
        });

        // Load Form Data for Dropdowns
        function loadFormData() {
            $('#package_id, #footage_size_id').html('<option value="">Loading...</option>').prop('disabled',
                true);

            axios.get("{{ route('service.form-data') }}")
                .then(function(response) {
                    if (response.data.success) {
                        const data = response.data.data;

                        // Populate Package Dropdown
                        let packageOptions = '<option value="">Choose a package...</option>';
                        if (data.packages && data.packages.length > 0) {
                            data.packages.forEach(function(package) {
                                packageOptions +=
                                    `<option value="${package.id}">${package.title}</option>`;
                            });
                        }
                        $('#package_id').html(packageOptions).prop('disabled', false);

                        // Populate Footage Size Dropdown
                        let footageOptions = '<option value="">Choose a footage size...</option>';
                        if (data.footage_sizes && data.footage_sizes.length > 0) {
                            data.footage_sizes.forEach(function(footage) {
                                footageOptions +=
                                    `<option value="${footage.id}">${footage.size}</option>`;
                            });
                        }
                        $('#footage_size_id').html(footageOptions).prop('disabled', false);

                        // Store service items data globally
                        window.serviceItemsOptions = data.service_items || [];

                        // Add first service item row
                        addServiceItemRow();
                    } else {
                        toastr.error('Failed to load form data');
                    }
                })
                .catch(function(error) {
                    console.error('Form Data Load Error:', error);
                    toastr.error('An error occurred while loading form data');
                    $('#package_id, #footage_size_id').prop('disabled', false);
                });
        }

        // Add Service Item Row (Create Modal) - Updated with unique validation
        function addServiceItemRow() {
            if (!window.serviceItemsOptions) {
                toastr.error('Service items data not loaded yet');
                return;
            }

            serviceItemCounter++;

            let serviceItemOptions = '<option value="">Choose service item...</option>';
            window.serviceItemsOptions.forEach(function(item) {
                serviceItemOptions += `<option value="${item.id}">${item.service_name}</option>`;
            });

            const serviceItemRow = `
                <div class="service-item-row border rounded p-3 mb-3 bg-light" data-index="${serviceItemCounter}">
                    <div class="row align-items-end">
                        <div class="col-md-7 mb-3">
                            <label class="form-label fw-medium">Service Item <span class="text-danger">*</span></label>
                            <select class="form-select service-item-select" name="service_items[${serviceItemCounter}][service_item_id]" required>
                                ${serviceItemOptions}
                            </select>
                            <span class="text-danger error-text create_service_items_${serviceItemCounter}_service_item_id_error"></span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-medium">Quantity <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="service_items[${serviceItemCounter}][quantity]"
                                   placeholder="Enter quantity" min="1" max="1000" required>
                            <span class="text-danger error-text create_service_items_${serviceItemCounter}_quantity_error"></span>
                        </div>
                        <div class="col-md-2 mb-3">
                            <button type="button" class="btn btn-danger btn-sm remove-service-item w-100"
                                    ${serviceItemCounter === 1 ? 'style="display:none"' : ''} title="Remove this service item">
                                <i class="fas fa-trash-alt me-1"></i>Remove
                            </button>
                        </div>
                    </div>
                </div>
            `;

            $('#serviceItemsContainer').append(serviceItemRow);
            updateRemoveButtons();
            updateServiceItemOptions(); // Update available options
        }

        // Add Edit Service Item Row - Updated with unique validation
        function addEditServiceItemRow(selectedServiceId = '', quantity = '') {
            if (!window.serviceItemsOptions) {
                toastr.error('Service items data not loaded yet');
                return;
            }

            editServiceItemCounter++;

            let serviceItemOptions = '<option value="">Choose service item...</option>';
            window.serviceItemsOptions.forEach(function(item) {
                const selected = item.id == selectedServiceId ? 'selected' : '';
                serviceItemOptions +=
                    `<option value="${item.id}" ${selected}>${item.service_name}</option>`;
            });

            const serviceItemRow = `
                <div class="edit-service-item-row border rounded p-3 mb-3 bg-light" data-index="${editServiceItemCounter}">
                    <div class="row align-items-end">
                        <div class="col-md-7 mb-3">
                            <label class="form-label fw-medium">Service Item <span class="text-danger">*</span></label>
                            <select class="form-select edit-service-item-select" name="service_items[${editServiceItemCounter}][service_item_id]" required>
                                ${serviceItemOptions}
                            </select>
                            <span class="text-danger error-text edit_service_items_${editServiceItemCounter}_service_item_id_error"></span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-medium">Quantity <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="service_items[${editServiceItemCounter}][quantity]"
                                   placeholder="Enter quantity" min="1" max="1000" value="${quantity}" required>
                            <span class="text-danger error-text edit_service_items_${editServiceItemCounter}_quantity_error"></span>
                        </div>
                        <div class="col-md-2 mb-3">
                            <button type="button" class="btn btn-danger btn-sm remove-edit-service-item w-100" title="Remove this service item">
                                <i class="fas fa-trash-alt me-1"></i>Remove
                            </button>
                        </div>
                    </div>
                </div>
            `;

            $('#editServiceItemsContainer').append(serviceItemRow);
            updateEditRemoveButtons();
            updateEditServiceItemOptions(); // Update available options
        }

        // Update Service Item Options (Create Modal)
        function updateServiceItemOptions() {
            const selectedServiceItems = [];
            $('.service-item-select').each(function() {
                const value = $(this).val();
                if (value) {
                    selectedServiceItems.push(value);
                }
            });

            $('.service-item-select').each(function() {
                const currentSelect = $(this);
                const currentValue = currentSelect.val();

                // Clear and rebuild options
                currentSelect.html('<option value="">Choose service item...</option>');

                window.serviceItemsOptions.forEach(function(item) {
                    // Show option if it's not selected elsewhere OR if it's the current selection
                    if (!selectedServiceItems.includes(item.id.toString()) || item.id
                        .toString() === currentValue) {
                        const selected = item.id.toString() === currentValue ? 'selected' : '';
                        currentSelect.append(
                            `<option value="${item.id}" ${selected}>${item.service_name}</option>`
                        );
                    }
                });
            });
        }

        // Update Edit Service Item Options (Edit Modal)
        function updateEditServiceItemOptions() {
            const selectedServiceItems = [];
            $('.edit-service-item-select').each(function() {
                const value = $(this).val();
                if (value) {
                    selectedServiceItems.push(value);
                }
            });

            $('.edit-service-item-select').each(function() {
                const currentSelect = $(this);
                const currentValue = currentSelect.val();

                // Clear and rebuild options
                currentSelect.html('<option value="">Choose service item...</option>');

                window.serviceItemsOptions.forEach(function(item) {
                    // Show option if it's not selected elsewhere OR if it's the current selection
                    if (!selectedServiceItems.includes(item.id.toString()) || item.id
                        .toString() === currentValue) {
                        const selected = item.id.toString() === currentValue ? 'selected' : '';
                        currentSelect.append(
                            `<option value="${item.id}" ${selected}>${item.service_name}</option>`
                        );
                    }
                });
            });
        }

        // Handle service item selection change (Create Modal)
        $(document).on('change', '.service-item-select', function() {
            updateServiceItemOptions();
        });

        // Handle service item selection change (Edit Modal)
        $(document).on('change', '.edit-service-item-select', function() {
            updateEditServiceItemOptions();
        });

        // Remove Service Item Row - Updated to refresh options
        $(document).on('click', '.remove-service-item', function() {
            $(this).closest('.service-item-row').remove();
            updateRemoveButtons();
            updateServiceItemOptions(); // Refresh available options
        });

        // Remove Edit Service Item Row - Updated to refresh options
        $(document).on('click', '.remove-edit-service-item', function() {
            $(this).closest('.edit-service-item-row').remove();
            updateEditRemoveButtons();
            updateEditServiceItemOptions(); // Refresh available options
        });

        // Update Remove Buttons Visibility
        function updateRemoveButtons() {
            const rows = $('.service-item-row');
            if (rows.length <= 1) {
                $('.remove-service-item').hide();
            } else {
                $('.remove-service-item').show();
            }
        }

        // Update Edit Remove Buttons Visibility
        function updateEditRemoveButtons() {
            const rows = $('.edit-service-item-row');
            if (rows.length <= 1) {
                $('.remove-edit-service-item').hide();
            } else {
                $('.remove-edit-service-item').show();
            }
        }

        // Function to handle validation errors properly - FIXED VERSION
        function handleValidationErrors(errors, prefix = 'create') {
            let hasErrors = false;
            console.log('Handling validation errors:', errors); // Debug log

            $.each(errors, function(key, value) {
                hasErrors = true;
                let errorMessage = Array.isArray(value) ? value[0] : value;
                console.log(`Processing error: ${key} = ${errorMessage}`); // Debug log

                // Handle nested service items errors
                if (key.startsWith('service_items.')) {
                    let matches = key.match(/service_items\.(\d+)\.(.+)/);
                    if (matches) {
                        let serverIndex = parseInt(matches[1]); // Server uses 0-based index
                        let field = matches[2];

                        // Find the correct row based on the current order (not server index)
                        let actualIndex = serverIndex + 1; // Convert to 1-based for our counter

                        // Try multiple approaches to find the error element
                        let errorElement = $(`.${prefix}_service_items_${actualIndex}_${field}_error`);
                        let inputElement = $(`[name="service_items[${actualIndex}][${field}]"]`);

                        // If not found with actualIndex, try to find by existing elements
                        if (errorElement.length === 0) {
                            // Find all service item rows and use the server index
                            let allRows = prefix === 'create' ? $('.service-item-row') : $(
                                '.edit-service-item-row');
                            if (allRows.length > serverIndex) {
                                let targetRow = allRows.eq(serverIndex);
                                errorElement = targetRow.find(`.error-text`).filter(function() {
                                    return $(this).attr('class').includes(field);
                                });
                                inputElement = targetRow.find(`[name*="[${field}]"]`);
                            }
                        }

                        console.log(
                            `Service item error - Field: ${field}, Index: ${actualIndex}, Element found: ${errorElement.length}`
                        ); // Debug log

                        if (errorElement.length > 0) {
                            errorElement.text(errorMessage);
                        }
                        if (inputElement.length > 0) {
                            inputElement.addClass('is-invalid');
                        }

                        // Fallback: try to find by partial class name
                        if (errorElement.length === 0) {
                            $(`.error-text[class*="${prefix}_service_items"][class*="${field}_error"]`)
                                .each(function() {
                                    $(this).text(errorMessage);
                                });
                            $(`[name*="service_items"][name*="${field}"]`).addClass('is-invalid');
                        }
                    }
                } else {
                    // Handle regular field errors
                    let errorElement = $(`.${prefix}_${key.replace(/\./g, '_')}_error`);
                    let inputElement = $(`[name="${key}"]`);

                    if (prefix === 'edit') {
                        inputElement = $(`#editServiceForm [name="${key}"]`);
                    }

                    console.log(
                        `Regular error - Field: ${key}, Element found: ${errorElement.length}`
                    ); // Debug log

                    errorElement.text(errorMessage);
                    inputElement.addClass('is-invalid');
                }
            });

            return hasErrors;
        }

        // Handle Create Form Submission
        $('#createServiceForm').submit(function(e) {
            e.preventDefault();
            $('.error-text').text('');
            $('.form-control, .form-select').removeClass('is-invalid');

            // Check for duplicate service items
            const selectedItems = [];
            let hasDuplicates = false;

            $('.service-item-select').each(function() {
                const value = $(this).val();
                if (value) {
                    if (selectedItems.includes(value)) {
                        hasDuplicates = true;
                        $(this).addClass('is-invalid');
                        $(this).siblings('.error-text').text(
                            'This service item is already selected.');
                    } else {
                        selectedItems.push(value);
                    }
                }
            });

            if (hasDuplicates) {
                toastr.error('Please remove duplicate service items before submitting.');
                return;
            }

            // Validate that package and footage size are selected
            if (!$('#package_id').val()) {
                $('.create_package_id_error').text('Please select a package.');
                $('#package_id').addClass('is-invalid');
                return;
            }

            if (!$('#footage_size_id').val()) {
                $('.create_footage_size_id_error').text('Please select a footage size.');
                $('#footage_size_id').addClass('is-invalid');
                return;
            }

            if (!$('#price').val()) {
                $('.create_price_error').text('Please enter the price.');
                $('#price').addClass('is-invalid');
                return;
            }

            // Validate service items
            const serviceItemRows = $('.service-item-row');
            if (serviceItemRows.length === 0) {
                $('.create_service_items_error').text('Please add at least one service item.');
                return;
            }

            let formData = $(this).serialize();
            let submitBtn = $(this).find('button[type="submit"]');
            let originalText = submitBtn.html();

            submitBtn.prop('disabled', true).html(
                '<i class="fas fa-spinner fa-spin me-1"></i>Creating...');

            axios.post("{{ route('service.store') }}", formData)
                .then(function(response) {
                    if (response.data.success) {
                        $('#createServiceModal').modal('hide');
                        $('#createServiceForm')[0].reset();
                        servicesTable.ajax.reload(null, false);
                        toastr.success(response.data.message);

                        // Reset service items
                        serviceItemCounter = 0;
                        $('#serviceItemsContainer').empty();
                    } else {
                        if (response.data.errors) {
                            handleValidationErrors(response.data.errors, 'create');
                            toastr.error('Please fix the validation errors.');
                        } else {
                            toastr.error(response.data.message || 'Failed to create service.');
                        }
                    }
                })
                .catch(function(error) {
                    console.error('Create Service Error:', error);
                    if (error.response && error.response.status === 422 && error.response.data &&
                        error.response.data.errors) {
                        // Handle validation errors from server
                        console.log('Validation errors received:', error.response.data
                            .errors); // Debug log
                        handleValidationErrors(error.response.data.errors, 'create');
                        toastr.error('Please fix the validation errors.');
                    } else if (error.response && error.response.data && error.response.data
                        .message) {
                        toastr.error(error.response.data.message);
                    } else {
                        toastr.error('An error occurred while creating the service.');
                    }
                })
                .finally(function() {
                    submitBtn.prop('disabled', false).html(originalText);
                });
        });

        // Show Edit Modal
        $(document).on('click', '.edit-service', function() {
            let tr = $(this).closest('tr');
            let rowData = servicesTable.row(tr).data();

            if (rowData) {
                $('#edit_service_id').val(rowData.id);
                $('.error-text').text('');
                $('.form-control, .form-select').removeClass('is-invalid');

                // Reset edit service items
                editServiceItemCounter = 0;
                $('#editServiceItemsContainer').empty();

                loadEditFormData(rowData.id);
                $('#editServiceModal').modal('show');
            } else {
                toastr.error('Unable to load service data for editing.');
            }
        });

        // Load Form Data for Edit Modal
        function loadEditFormData(serviceId) {
            $('#edit_package_id, #edit_footage_size_id').html('<option value="">Loading...</option>').prop(
                'disabled', true);

            // Load form data and service details
            Promise.all([
                    axios.get("{{ route('service.form-data') }}"),
                    axios.get("{{ route('service.show', 0) }}".replace('/0', '/' + serviceId))
                ])
                .then(function(responses) {
                    const formDataResponse = responses[0];
                    const serviceResponse = responses[1];

                    if (formDataResponse.data.success && serviceResponse.data.success) {
                        const formData = formDataResponse.data.data;
                        const serviceData = serviceResponse.data.data;

                        // Populate Package Dropdown
                        let packageOptions = '<option value="">Choose a package...</option>';
                        if (formData.packages && formData.packages.length > 0) {
                            formData.packages.forEach(function(package) {
                                const selected = package.id == serviceData.package_id ? 'selected' :
                                    '';
                                packageOptions +=
                                    `<option value="${package.id}" ${selected}>${package.title}</option>`;
                            });
                        }
                        $('#edit_package_id').html(packageOptions).prop('disabled', false);

                        // Populate Footage Size Dropdown
                        let footageOptions = '<option value="">Choose a footage size...</option>';
                        if (formData.footage_sizes && formData.footage_sizes.length > 0) {
                            formData.footage_sizes.forEach(function(footage) {
                                const selected = footage.id == serviceData.footage_size_id ?
                                    'selected' : '';
                                footageOptions +=
                                    `<option value="${footage.id}" ${selected}>${footage.size}</option>`;
                            });
                        }
                        $('#edit_footage_size_id').html(footageOptions).prop('disabled', false);

                        // Store service items data globally
                        window.serviceItemsOptions = formData.service_items || [];

                        // Set price
                        $('#edit_price').val(serviceData.price);

                        // Add existing service items
                        if (serviceData.service_items && serviceData.service_items.length > 0) {
                            serviceData.service_items.forEach(function(item) {
                                addEditServiceItemRow(item.id, item.quantity);
                            });
                        } else {
                            addEditServiceItemRow(); // Add empty row if no items
                        }
                    } else {
                        toastr.error('Failed to load service data for editing');
                    }
                })
                .catch(function(error) {
                    console.error('Edit Form Data Load Error:', error);
                    toastr.error('An error occurred while loading service data');
                    $('#edit_package_id, #edit_footage_size_id').prop('disabled', false);
                });
        }

        // Handle Edit Form Submission
        $('#editServiceForm').submit(function(e) {
            e.preventDefault();
            $('.error-text').text('');
            $('.form-control, .form-select').removeClass('is-invalid');

            // Check for duplicate service items
            const selectedItems = [];
            let hasDuplicates = false;

            $('.edit-service-item-select').each(function() {
                const value = $(this).val();
                if (value) {
                    if (selectedItems.includes(value)) {
                        hasDuplicates = true;
                        $(this).addClass('is-invalid');
                        $(this).siblings('.error-text').text(
                            'This service item is already selected.');
                    } else {
                        selectedItems.push(value);
                    }
                }
            });

            if (hasDuplicates) {
                toastr.error('Please remove duplicate service items before submitting.');
                return;
            }

            let formData = $(this).serialize();
            let serviceId = $('#edit_service_id').val();
            let submitBtn = $(this).find('button[type="submit"]');
            let originalText = submitBtn.html();

            if (!serviceId) {
                toastr.error('Service ID is missing. Please try again.');
                return;
            }

            submitBtn.prop('disabled', true).html(
                '<i class="fas fa-spinner fa-spin me-1"></i>Updating...');

            axios.put("{{ route('service.update', 0) }}".replace('/0', '/' + serviceId), formData)
                .then(function(response) {
                    if (response.data.success) {
                        $('#editServiceModal').modal('hide');
                        $('#editServiceForm')[0].reset();
                        servicesTable.ajax.reload(null, false);
                        toastr.success(response.data.message);
                    } else {
                        if (response.data.errors) {
                            handleValidationErrors(response.data.errors, 'edit');
                            toastr.error('Please fix the validation errors.');
                        } else {
                            toastr.error(response.data.message || 'Failed to update service.');
                        }
                    }
                })
                .catch(function(error) {
                    console.error('Update Service Error:', error);
                    if (error.response && error.response.status === 422 && error.response.data &&
                        error.response.data.errors) {
                        // Handle validation errors from server
                        handleValidationErrors(error.response.data.errors, 'edit');
                        toastr.error('Please fix the validation errors.');
                    } else if (error.response && error.response.data && error.response.data
                        .message) {
                        toastr.error(error.response.data.message);
                    } else {
                        toastr.error('An error occurred while updating the service.');
                    }
                })
                .finally(function() {
                    submitBtn.prop('disabled', false).html(originalText);
                });
        });

        // Clear modal state when hidden
        $('#createServiceModal').on('hidden.bs.modal', function() {
            $(this).find('.error-text').text('');
            $(this).find('.form-control, .form-select').removeClass('is-invalid');
            serviceItemCounter = 0;
            $('#serviceItemsContainer').empty();
        });

        $('#editServiceModal').on('hidden.bs.modal', function() {
            $(this).find('.error-text').text('');
            $(this).find('.form-control, .form-select').removeClass('is-invalid');
            editServiceItemCounter = 0;
            $('#editServiceItemsContainer').empty();
        });
    });

    // Service Status Change
    function showServiceStatusChangeAlert(id) {
        if (event) {
            event.preventDefault();
        }

        Swal.fire({
            title: 'Update Status',
            text: 'Are you sure you want to change the status of this service?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Update',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                serviceStatusChange(id);
            } else {
                let checkbox = $('#SwitchCheck' + id);
                checkbox.prop('checked', !checkbox.prop('checked'));
            }
        });
    }

    function serviceStatusChange(id) {
        let url = '{{ route('service.status', 0) }}'.replace('/0', '/' + id);
        let checkbox = $('#SwitchCheck' + id);
        let originalState = checkbox.prop('checked');
        checkbox.prop('disabled', true);

        $.ajax({
            type: "GET",
            url: url,
            timeout: 10000,
            success: function(resp) {
                $('#datatable-services').DataTable().ajax.reload(null, false);

                if (resp.success === true) {
                    toastr.success(resp.message || 'Status updated successfully.');
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

    // Service Delete
    function showServiceDeleteConfirm(id) {
        if (event) {
            event.preventDefault();
        }

        Swal.fire({
            title: 'Delete Service',
            text: 'Are you sure you want to delete this service? This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                deleteService(id);
            }
        });
    }

    function deleteService(id) {
        let url = '{{ route('service.destroy', 0) }}'.replace('/0', '/' + id);
        let csrfToken = '{{ csrf_token() }}';

        Swal.fire({
            title: 'Deleting...',
            text: 'Please wait while we delete the service.',
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
                $('#datatable-services').DataTable().ajax.reload(null, false);

                if (resp['t-success'] === true) {
                    toastr.success(resp.message || 'Service deleted successfully.');
                } else {
                    toastr.error(resp.message || 'Failed to delete service.');
                }
            },
            error: function(xhr, status, error) {
                Swal.close();
                console.error('Delete Service Error:', xhr.responseText);
                toastr.error('An error occurred while deleting the service.');
            }
        });
    }
</script>
