<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\AddOn;
use App\Models\ContactUs;
use App\Models\Order;
use App\Models\OtherServiceOrder;
use App\Models\Package;
use App\Models\Service;
use App\Models\ZipCode;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller {
    /**
     * Display the dashboard page.
     *
     * @return View
     */
    public function index(): View {
        // Get dashboard statistics
        $stats = $this->getDashboardStats();

        // Get recent orders
        $recentOrders = $this->getRecentOrders();

        // Get monthly revenue data
        $monthlyRevenue = $this->getMonthlyRevenue();

        // Get order status distribution
        $orderStatusData = $this->getOrderStatusData();

        // Get popular packages
        $popularPackages = $this->getPopularPackages();

        return view('backend.layouts.dashboard.index', compact(
            'stats',
            'recentOrders',
            'monthlyRevenue',
            'orderStatusData',
            'popularPackages'
        ));
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats(): array {
        $today     = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            'total_orders'         => Order::count(),
            'pending_orders'       => Order::where('order_status', 'pending')->count(),
            'completed_orders'     => Order::where('order_status', 'completed')->count(),
            'total_revenue'        => Order::where('status', 'paid')->sum('total_amount'),
            'monthly_revenue'      => Order::where('status', 'paid')
                ->where('created_at', '>=', $thisMonth)
                ->sum('total_amount'),
            'today_orders'         => Order::whereDate('created_at', $today)->count(),
            'contact_inquiries'    => ContactUs::count(),
            'other_service_orders' => OtherServiceOrder::count(),
            'active_packages'      => Package::where('status', 'active')->count(),
            'active_services'      => Service::where('status', 'active')->count(),
            'active_addons'        => AddOn::where('status', 'active')->count(),
            'zip_codes'            => ZipCode::where('status', 'active')->count(),
        ];
    }

    /**
     * Get recent orders
     */
    private function getRecentOrders() {
        return Order::with(['properties.footageSize', 'appointments'])
            ->latest()
            ->take(5)
            ->get();
    }

    /**
     * Get monthly revenue data for chart
     */
    private function getMonthlyRevenue(): array {
        $months  = [];
        $revenue = [];

        for ($i = 11; $i >= 0; $i--) {
            $date     = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');

            $monthRevenue = Order::where('status', 'paid')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total_amount');

            $revenue[] = floatval($monthRevenue);
        }

        return [
            'months'  => $months,
            'revenue' => $revenue,
        ];
    }

    /**
     * Get order status distribution
     */
    private function getOrderStatusData(): array {
        return [
            'pending'   => Order::where('order_status', 'pending')->count(),
            'completed' => Order::where('order_status', 'completed')->count(),
            'cancelled' => Order::where('order_status', 'cancelled')->count(),
        ];
    }

    /**
     * Get popular packages
     */
    private function getPopularPackages() {
        return Package::withCount(['services' => function ($query) {
            $query->whereHas('orderItems');
        }])
            ->orderBy('services_count', 'desc')
            ->take(3)
            ->get();
    }
}
