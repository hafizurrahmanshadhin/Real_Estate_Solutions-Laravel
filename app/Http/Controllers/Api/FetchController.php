<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Pricing\AddOnResource;
use App\Http\Resources\Api\Pricing\FetchPackageResource;
use App\Models\AddOn;
use App\Models\FootageSize;
use App\Models\Package;
use App\Models\ZipCode;
use Exception;
use Illuminate\Http\JsonResponse;

class FetchController extends Controller {
    /**
     * Fetch the list of active zip codes.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function FetchZipCodes(): JsonResponse {
        try {
            $data = ZipCode::where('status', 'active')->orderBy('zip_code', 'asc')->get(['id', 'zip_code']);

            return Helper::jsonResponse(true, 'Zip codes fetched successfully', 200, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Fetch the list of active square footage sizes.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function FetchSquareFootageSizes(): JsonResponse {
        try {
            $data = FootageSize::where('status', 'active')->orderBy('size', 'asc')->get(['id', 'size']);

            return Helper::jsonResponse(true, 'Square footage sizes fetched successfully', 200, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Fetch packages with services and add-ons by footage size.
     *
     * @param int $footage
     * @return JsonResponse
     * @throws Exception
     */
    public function FetchPackagesByFootageSize(int $footage) {
        try {
            // 1) Only packages that have an active service at this footage size
            $packages = Package::whereHas('services', function ($q) use ($footage) {
                $q->where('footage_size_id', $footage)
                    ->where('status', 'active');
            })
                ->with(['services' => function ($q) use ($footage) {
                    $q->where('footage_size_id', $footage)
                        ->where('status', 'active')
                        ->orderBy('price', 'asc');
                }])
                ->where('status', 'active')
                ->get();

            // 2) All active add‑ons for that footage size
            $addOns = AddOn::with(['footageSize:id,size', 'serviceItem:id,service_name'])
                ->where('status', 'active')
                ->where('footage_size_id', $footage)
                ->get();

            return Helper::jsonResponse(true, 'Packages and add-ons data fetched successfully', 200,
                [
                    'packages' => FetchPackageResource::collection($packages),
                    'add_ons'  => AddOnResource::collection($addOns),
                ]
            );
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500,
                ['error' => $e->getMessage()]
            );
        }
    }
}
