<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Web\OrderRequestResource;
use App\Models\OtherServiceOrder;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class OrderRequestController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse|View
     * @throws Exception
     */
    public function index(Request $request): JsonResponse | View {
        try {
            if ($request->ajax()) {
                $data = OtherServiceOrder::with(['otherService', 'footageSize'])->latest()->get();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('full_name', fn($row) => $row->full_name)
                    ->addColumn('footage_size_name', fn($row) => $row->footageSize?->size)
                    ->addColumn('other_service_name', fn($row) => $row->otherService?->title)
                    ->addColumn('full_address', fn($row) => $row->full_address)
                    ->addColumn('additional_info', function ($data) {
                        $additional_info       = $data->additional_info;
                        $short_additional_info = strlen($additional_info) > 100 ? substr($additional_info, 0, 100) . '...' : $additional_info;
                        return $short_additional_info;
                    })
                    ->addColumn('action', function ($data) {
                        return '
                            <div class="d-flex justify-content-center hstack gap-3 fs-base">
                                <a href="javascript:void(0);" onclick="showOrderRequestDetails(' . $data->id . ')" class="link-primary text-decoration-none" data-bs-toggle="modal" data-bs-target="#viewOrderRequestModal" title="View">
                                    <i class="ri-eye-line" style="font-size: 24px;"></i>
                                </a>
                            </div>';
                    })
                    ->rawColumns(['full_name', 'footage_size_name', 'other_service_name', 'full_address', 'additional_info', 'action'])
                    ->make();
            }
            return view('backend.layouts.order-request.index');
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param OtherServiceOrder $orderRequest
     * @return JsonResponse
     * @throws Exception
     */
    public function show(OtherServiceOrder $orderRequest): JsonResponse {
        try {
            $orderRequest->load(['otherService', 'footageSize']);

            return Helper::jsonResponse(true, 'Data fetched successfully', 200, new OrderRequestResource($orderRequest));
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, ['error' => $e->getMessage()]);
        }
    }
}
