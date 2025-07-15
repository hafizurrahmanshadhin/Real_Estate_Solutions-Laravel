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
