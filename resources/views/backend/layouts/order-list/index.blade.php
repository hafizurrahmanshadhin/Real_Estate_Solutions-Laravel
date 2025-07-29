@extends('backend.app')

@section('title', 'Order List')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">List of all Orders</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatable"
                                    class="table table-bordered dt-responsive nowrap table-striped align-middle"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="column-id">#</th>
                                            <th class="column-content">Name</th>
                                            <th class="column-content">Email</th>
                                            <th class="column-content">Phone Number</th>
                                            <th class="column-content">Address</th>
                                            <th class="column-content">Appointment Schedule</th>
                                            <th class="column-content">Amount Paid</th>
                                            <th class="column-content">Transaction ID</th>
                                            <th class="column-content">Order Status</th>
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

    {{-- Modal for viewing order details start --}}
    <div class="modal fade" id="viewOrderModal" tabindex="-1" aria-labelledby="OrderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="OrderModalLabel" class="modal-title">Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Dynamic data filled by JS --}}
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal for viewing order details end --}}
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            if (!$.fn.DataTable.isDataTable('#datatable')) {
                let table = $('#datatable').DataTable({
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
                        url: "{{ route('order.index') }}",
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
                    autoWidth: false,
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            className: 'text-center',
                            width: '2%'
                        },
                        {
                            data: 'full_name',
                            name: 'full_name',
                            orderable: true,
                            searchable: true,
                            width: '12%',
                            render: function(data) {
                                return '<div style="white-space:normal;word-break:break-word;">' +
                                    data + '</div>';
                            }
                        },
                        {
                            data: 'email',
                            name: 'email',
                            orderable: true,
                            searchable: true,
                            width: '14%',
                            render: function(data) {
                                return '<div style="white-space:normal;word-break:break-word;">' +
                                    data + '</div>';
                            }
                        },
                        {
                            data: 'phone_number',
                            name: 'phone_number',
                            orderable: true,
                            searchable: true,
                            width: '10%',
                            render: function(data) {
                                return '<div style="white-space:normal;word-break:break-word;">' +
                                    data + '</div>';
                            }
                        },
                        {
                            data: 'full_address',
                            name: 'full_address',
                            orderable: false,
                            searchable: false,
                            width: '20%',
                            render: function(data) {
                                return '<div style="white-space:normal;word-break:break-word;">' +
                                    data + '</div>';
                            }
                        },
                        {
                            data: 'appointment_schedule',
                            name: 'appointment_schedule',
                            orderable: true,
                            searchable: true,
                            width: '10%',
                            render: function(data) {
                                return '<div style="white-space:normal;word-break:break-word;">' +
                                    data + '</div>';
                            }
                        },
                        {
                            data: 'total_amount',
                            name: 'total_amount',
                            orderable: true,
                            searchable: true,
                            width: '10%',
                            render: function(data) {
                                return '<div style="white-space:normal;word-break:break-word;">' +
                                    data + '</div>';
                            }
                        },
                        {
                            data: 'transaction_id',
                            name: 'transaction_id',
                            orderable: true,
                            searchable: true,
                            width: '10%',
                            render: function(data) {
                                return '<div style="white-space:normal;word-break:break-word;">' +
                                    data + '</div>';
                            }
                        },
                        {
                            data: 'order_status',
                            name: 'order_status',
                            orderable: true,
                            searchable: false,
                            width: '10%',
                            render: function(data, type, row) {
                                // Dropdown with current status selected
                                let options = ['pending', 'completed', 'cancelled'].map(function(
                                    status) {
                                    return `<option value="${status}" ${data === status ? 'selected' : ''}>${status.charAt(0).toUpperCase() + status.slice(1)}</option>`;
                                }).join('');
                                return `<select class="form-select order-status-dropdown" data-id="${row.id}">${options}</select>`;
                            }
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'text-center',
                            width: '2%'
                        },
                    ],
                });
            }
        });

        $(document).on('change', '.order-status-dropdown', function() {
            let orderId = $(this).data('id');
            let status = $(this).val();
            axios.post("{{ route('order.update.status') }}", {
                id: orderId,
                order_status: status
            }).then(function(response) {
                // Only show success toast
                toastr.success(response.data.message || 'Order status updated!');
                table.ajax.reload(null, false);
            }).catch(function(error) {
                // Only show error toast
                if (error.response && error.response.data && error.response.data.message) {
                    toastr.error(error.response.data.message);
                } else {
                    // toastr.error('Failed to update status');
                }
            });
        });

        // Fetch and display order details in the modal
        async function showOrderDetails(id) {
            let url = '{{ route('order.show', ['order' => ':id']) }}';
            url = url.replace(':id', id);

            try {
                let response = await axios.get(url);
                if (response.data && response.data.data) {
                    let data = response.data.data;
                    let items = data.items || [];

                    let itemsHtml = items.map(item => {
                        return `<tr>
                        <td>${item.name}</td>
                        <td>${item.quantity}</td>
                        <td>$${item.unit_price}</td>
                        <td>$${item.line_total}</td>
                    </tr>`;
                    }).join('');

                    let modalBody = document.querySelector('#viewOrderModal .modal-body');
                    modalBody.innerHTML = `
                    <h5>Order #${data.id}</h5>
                    <p><strong>Transaction ID:</strong> ${data.transaction_id}</p>
                    <p><strong>Name:</strong> ${data.full_name}</p>
                    <p><strong>Email:</strong> ${data.email}</p>
                    <p><strong>Phone:</strong> ${data.phone_number}</p>
                    <p><strong>Address:</strong> ${data.address}</p>
                    <p><strong>Property Type:</strong> ${data.property_type}</p>
                    <p><strong>Footage Size:</strong> ${data.footage_size}</p>
                    <p><strong>Appointment:</strong> ${data.appointment_date} ${data.appointment_time}</p>
                    <p><strong>Status:</strong> ${data.order_status}</p>
                    <p><strong>Amount Paid:</strong> $${data.total_amount} ${data.currency}</p>
                    <hr>
                    <h6>Order Items</h6>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Service / Add-On</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Line Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${itemsHtml}
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total</strong></td>
                                <td><strong>$${data.items_total}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                `;
                } else {
                    toastr.error('No data returned from the server.');
                }
            } catch (error) {
                console.error(error);
                let modalBody = document.querySelector('#viewOrderModal .modal-body');
                modalBody.innerHTML =
                    `<div class="alert alert-danger">Error: ${error.response?.data?.error || error.message}</div>`;
                toastr.error('Could not fetch order request details.');
            }
        }
    </script>
@endpush
