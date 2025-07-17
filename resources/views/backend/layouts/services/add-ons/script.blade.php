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
                [10, 25, 50, 100, "All"],
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
                    data: 'footage_size',
                    name: 'footage_size',
                    orderable: true,
                    searchable: true,
                    width: '25%',
                },
                {
                    data: 'service_item_name',
                    name: 'service_item_name',
                    orderable: true,
                    searchable: true,
                    width: '30%',
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
        $('#addNewAddOn').click(function() {
            $('#createAddOnModal').modal('show');
            $('#createAddOnForm')[0].reset();
            $('.error-text').text('');
            loadFormData();
        });

        // Load Form Data for Dropdowns
        function loadFormData() {
            axios.get("{{ route('service.add-on.form-data') }}")
                .then(function(response) {
                    if (response.data.success) {
                        const data = response.data.data;

                        // Populate Footage Size Dropdown
                        let footageOptions = '<option value="">Choose a footage size...</option>';
                        data.footage_sizes.forEach(function(footage) {

                            footageOptions +=
                                `<option value="${footage.id}">${footage.size}</option>`;
                        });
                        $('#footage_size_id_for_add_ons').html(footageOptions);

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
        $('#createAddOnForm').submit(function(e) {
            e.preventDefault();
            $('.error-text').text('');
            let formData = $(this).serialize();

            axios.post("{{ route('service.add-on.store') }}", formData)
                .then(function(response) {
                    if (response.data.success) {
                        $('#createAddOnModal').modal('hide');
                        $('#createAddOnForm')[0].reset();
                        addOnsTable.ajax.reload();
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
                    toastr.error('An error occurred while creating add-on.');
                    console.error(error);
                });
        });

        // Show Edit Modal
        $(document).on('click', '.edit-add-on', function() {
            let tr = $(this).closest('tr');
            let rowData = addOnsTable.row(tr).data();
            $('#edit_service_id').val(rowData.id);
            loadEditFormData(rowData);
            $('#editAddOnModal').modal('show');
        });

        // Load Form Data for Edit Modal
        function loadEditFormData(currentData) {
            axios.get("{{ route('service.add-on.form-data') }}")
                .then(function(response) {
                    if (response.data.success) {
                        const data = response.data.data;

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
        $('#editAddOnForm').submit(function(e) {
            e.preventDefault();
            $('.error-text').text('');
            let formData = $(this).serialize();
            let addOnId = $('#edit_service_id').val();

            axios.put("{{ route('service.add-on.update', 0) }}".replace('/0', '/' + addOnId), formData)
                .then(function(response) {
                    if (response.data.success) {
                        $('#editAddOnModal').modal('hide');
                        $('#editAddOnForm')[0].reset();
                        addOnsTable.ajax.reload();
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
    function showAddOnStatusChangeAlert(id) {
        if (event) {
            event.preventDefault();
        }

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

    // Status Change
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

    // Delete Confirm
    function showAddOnDeleteConfirm(id) {
        if (event) {
            event.preventDefault();
        }

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

    // Delete Add-on
    function deleteAddOn(id) {
        let url = '{{ route('service.add-on.destroy', 0) }}'.replace('/0', '/' + id);
        let csrfToken = '{{ csrf_token() }}';

        Swal.fire({
            title: 'Deleting...',
            text: 'Please wait while we delete the add-on.',
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
