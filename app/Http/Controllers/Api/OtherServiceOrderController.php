<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OtherServiceOrderRequest;
use App\Http\Resources\Api\OtherServiceOrderResource;
use App\Services\Api\OtherServiceOrderService;
use Exception;
use Illuminate\Http\JsonResponse;

class OtherServiceOrderController extends Controller {
    /**
     * Handle the incoming request to place an order.
     *
     * @param OtherServiceOrderRequest $request
     * @param OtherServiceOrderService $service
     * @return JsonResponse
     * @throws Exception
     */
    public function __invoke(OtherServiceOrderRequest $request, OtherServiceOrderService $service): JsonResponse {
        try {
            $order = $service->placeOrder($request->validated());

            return Helper::jsonResponse(true, 'Your order has been placed successfully.', 201, new OtherServiceOrderResource($order));
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to place order: ' . $e->getMessage(), 500);
        }
    }
}
