<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Web\OrderDetailsResource;
use App\Models\Order;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class OrderController extends Controller {
    /**
     * Display a listing of the orders.
     *
     * @param Request $request
     * @return JsonResponse|View
     * @throws Exception
     */
    public function index(Request $request): JsonResponse | View {
        try {
            if ($request->ajax()) {
                $data = Order::with(['properties', 'appointments'])->where('status', 'paid')->latest()->get();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('full_name', fn($row) => $row->full_name)
                    ->addColumn('full_address', function ($row) {
                        $property = $row->properties->first();
                        return $property ? $property->full_address : '';
                    })
                    ->addColumn('appointment_schedule', function ($row) {
                        $appointment = $row->appointments->first();
                        if ($appointment && $appointment->date && $appointment->time) {
                            $date = Carbon::parse($appointment->date)->format('M d, Y');
                            $time = date('g:i A', strtotime($appointment->time));
                            return "$date $time";
                        }
                        return '';
                    })
                    ->addColumn('total_amount', function ($row) {
                        return number_format($row->total_amount, 2) . ' ' . strtoupper($row->currency);
                    })
                    ->addColumn('order_status', fn($row) => $row->order_status)
                    ->addColumn('action', function ($data) {
                        return '
                            <div class="d-flex justify-content-center hstack gap-3 fs-base">
                                <a href="javascript:void(0);" onclick="showOrderDetails(' . $data->id . ')" class="link-primary text-decoration-none" data-bs-toggle="modal" data-bs-target="#viewOrderModal" title="View">
                                    <i class="ri-eye-line" style="font-size: 24px;"></i>
                                </a>
                            </div>';
                    })
                    ->rawColumns(['full_name', 'full_address', 'appointment_schedule', 'total_amount', 'order_status', 'action'])
                    ->make();
            }
            return view('backend.layouts.order-list.index');
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Show the details of a specific order.
     *
     * @param Order $order
     * @return JsonResponse
     * @throws Exception
     */
    public function show(Order $order): JsonResponse {
        try {
            $order->load(['properties.footageSize', 'appointments', 'items.itemable']);
            return Helper::jsonResponse(true, 'Order details fetched', 200, new OrderDetailsResource($order));
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Update the order status.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     * @throws Exception
     */
    public function updateStatus(Request $request): JsonResponse {
        try {
            $request->validate([
                'id'           => 'required|integer|exists:orders,id',
                'order_status' => 'required|in:pending,completed,cancelled',
            ]);
            $order               = Order::findOrFail($request->id);
            $order->order_status = $request->order_status;
            $order->save();

            return Helper::jsonResponse(true, 'Order status updated!', 200, [
                'order_status' => $order->order_status,
            ]);
        } catch (ValidationException $e) {
            return Helper::jsonResponse(false, 'Validation failed', 422, null, $e->errors());
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to update status', 500, null, $e->getMessage());
        }
    }
}
