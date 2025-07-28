<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Payment\CheckoutRequest;
use App\Http\Resources\Api\Payment\CheckoutResource;
use App\Services\Api\Payment\CheckoutService;
use Exception;
use Illuminate\Http\JsonResponse;

class CheckoutController extends Controller {
    /**
     * Handle the incoming checkout request.
     *
     * @param CheckoutRequest $request
     * @param CheckoutService $checkout
     * @return JsonResponse
     * @throws Exception
     */
    public function __invoke(CheckoutRequest $request, CheckoutService $checkout): JsonResponse {
        try {
            $url = $checkout->createSession($request->validated());

            return Helper::jsonResponse(true, 'Checkout session created successfully.', 200, new CheckoutResource($url));
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to create checkout session: ' . $e->getMessage(), 500);
        }
    }
}
