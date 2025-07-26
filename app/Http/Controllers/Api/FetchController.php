<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Pricing\FetchPackageResource;
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
     * Fetch packages with services by footage size.
     *
     * @param int $footage
     * @return JsonResponse
     * @throws Exception
     */
    public function FetchPackagesByFootageSize(int $footage) {
        try {
            // only packages that have at least one active service at this footage size
            $packages = Package::whereHas('services', function ($q) use ($footage) {
                $q->where('footage_size_id', $footage)
                    ->where('status', 'active');
            })
            // eager‐load just those services
                ->with(['services' => function ($q) use ($footage) {
                    $q->where('footage_size_id', $footage)
                        ->where('status', 'active')
                        ->orderBy('price', 'asc');
                }])
                ->where('status', 'active')
                ->get();

            return Helper::jsonResponse(true, 'Packages with services fetched successfully', 200,
                FetchPackageResource::collection($packages)
            );
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
