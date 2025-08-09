@extends('backend.app')

@section('title', 'Dashboard')

@section('content')
    <div class="page-content wrapper">
        <div class="container-fluid">
            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card card-h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Orders</span>
                                    <h4 class="mb-3">
                                        <span class="counter-value"
                                            data-target="{{ $stats['total_orders'] }}">{{ $stats['total_orders'] }}</span>
                                    </h4>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm rounded-circle bg-primary">
                                        <span class="avatar-title rounded-circle bg-primary">
                                            <i class="bx bx-shopping-bag font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card card-h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <span class="text-muted mb-3 lh-1 d-block text-truncate">Pending Orders</span>
                                    <h4 class="mb-3">
                                        <span class="counter-value"
                                            data-target="{{ $stats['pending_orders'] }}">{{ $stats['pending_orders'] }}</span>
                                    </h4>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm rounded-circle bg-warning">
                                        <span class="avatar-title rounded-circle bg-warning">
                                            <i class="bx bx-time font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card card-h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Revenue</span>
                                    <h4 class="mb-3">
                                        $<span class="counter-value"
                                            data-target="{{ $stats['total_revenue'] }}">{{ number_format($stats['total_revenue'], 2) }}</span>
                                    </h4>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm rounded-circle bg-success">
                                        <span class="avatar-title rounded-circle bg-success">
                                            <i class="bx bx-dollar-circle font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card card-h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <span class="text-muted mb-3 lh-1 d-block text-truncate">Today's Orders</span>
                                    <h4 class="mb-3">
                                        <span class="counter-value"
                                            data-target="{{ $stats['today_orders'] }}">{{ $stats['today_orders'] }}</span>
                                    </h4>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm rounded-circle bg-info">
                                        <span class="avatar-title rounded-circle bg-info">
                                            <i class="bx bx-calendar font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Secondary Stats -->
            <div class="row">
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="avatar-sm mx-auto mb-4">
                                <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-16">
                                    <i class="bx bx-package"></i>
                                </span>
                            </div>
                            <h5 class="font-size-15">{{ $stats['active_packages'] }}</h5>
                            <p class="text-muted mb-0">Active Packages</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="avatar-sm mx-auto mb-4">
                                <span class="avatar-title rounded-circle bg-success bg-soft text-success font-size-16">
                                    <i class="bx bx-cog"></i>
                                </span>
                            </div>
                            <h5 class="font-size-15">{{ $stats['active_services'] }}</h5>
                            <p class="text-muted mb-0">Active Services</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="avatar-sm mx-auto mb-4">
                                <span class="avatar-title rounded-circle bg-warning bg-soft text-warning font-size-16">
                                    <i class="bx bx-plus-circle"></i>
                                </span>
                            </div>
                            <h5 class="font-size-15">{{ $stats['active_addons'] }}</h5>
                            <p class="text-muted mb-0">Active Add-ons</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="avatar-sm mx-auto mb-4">
                                <span class="avatar-title rounded-circle bg-info bg-soft text-info font-size-16">
                                    <i class="bx bx-message-dots"></i>
                                </span>
                            </div>
                            <h5 class="font-size-15">{{ $stats['contact_inquiries'] }}</h5>
                            <p class="text-muted mb-0">Contact Inquiries</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="avatar-sm mx-auto mb-4">
                                <span class="avatar-title rounded-circle bg-secondary bg-soft text-secondary font-size-16">
                                    <i class="bx bx-buildings"></i>
                                </span>
                            </div>
                            <h5 class="font-size-15">{{ $stats['other_service'] }}</h5>
                            <p class="text-muted mb-0">Other Services</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="avatar-sm mx-auto mb-4">
                                <span class="avatar-title rounded-circle bg-dark bg-soft text-dark font-size-16">
                                    <i class="bx bx-map-pin"></i>
                                </span>
                            </div>
                            <h5 class="font-size-15">{{ $stats['zip_codes'] }}</h5>
                            <p class="text-muted mb-0">Service Areas</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Tables Row -->
            <div class="row">
                <!-- Revenue Chart -->
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Monthly Revenue</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="revenueChart" height="300"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Order Status Pie Chart -->
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Order Status</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="orderStatusChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders and Popular Packages -->
            <div class="row">
                <!-- Recent Orders -->
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Recent Orders</h4>
                            <div class="flex-shrink-0">
                                <a href="{{ route('order.index') }}" class="btn btn-primary btn-sm">View All</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">Order ID</th>
                                            <th scope="col">Customer</th>
                                            <th scope="col">Amount</th>
                                            <th scope="col">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentOrders as $order)
                                            <tr>
                                                <td>#{{ $order->id }}</td>
                                                <td>
                                                    <div>
                                                        <h5 class="font-size-14 mb-1">{{ $order->full_name }}</h5>
                                                        <p class="text-muted mb-0">{{ $order->email }}</p>
                                                    </div>
                                                </td>
                                                <td>${{ number_format($order->total_amount, 2) }}</td>
                                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No orders found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Popular Packages -->
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Popular Packages</h4>
                        </div>
                        <div class="card-body">
                            @forelse($popularPackages as $package)
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <h5 class="font-size-14 mb-1">{{ $package->title }}</h5>
                                        <p class="text-muted mb-0">{!! Str::limit($package->description, 50) !!}</p>
                                        <p class="text-success mb-0 font-size-12">{{ $package->services_count }} orders
                                        </p>
                                    </div>
                                </div>
                                @if (!$loop->last)
                                    <hr class="my-3">
                                @endif
                            @empty
                                <p class="text-center text-muted">No packages found</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    {{-- Chart.js Scripts --}}
    <script src="{{ asset('backend/custom_downloaded_file/chart.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Revenue Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($monthlyRevenue['months']) !!},
                    datasets: [{
                        label: 'Revenue ($)',
                        data: {!! json_encode($monthlyRevenue['revenue']) !!},
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });

            // Order Status Chart
            const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'Completed', 'Cancelled'],
                    datasets: [{
                        data: [
                            {{ $orderStatusData['pending'] }},
                            {{ $orderStatusData['completed'] }},
                            {{ $orderStatusData['cancelled'] }}
                        ],
                        backgroundColor: [
                            '#ffc107',
                            '#28a745',
                            '#dc3545'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        });
    </script>
@endpush
