<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Home\HeroSectionResource;
use App\Http\Resources\Api\Home\OtherServiceResource;
use App\Http\Resources\Api\Home\PackageResource;
use App\Models\CMS;
use App\Models\OtherService;
use App\Models\Package;
use Exception;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller {
    /**
     * Display the home page data.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function __invoke(): JsonResponse {
        try {
            // 1) Hero section record
            $cms_hero_section_data = CMS::where('section', 'home_page')->where('status', 'active')->first();

            // 2) Packages with the min active-service price
            $packages = Package::where('status', 'active')
                ->withMin(['services' => fn($q) => $q->where('status', 'active')], 'price')
                ->get();

            // 3) “Singleton” service_description
            $descRecord = OtherService::whereNull('title')
                ->whereNull('image')
                ->whereNotNull('service_description')
                ->first();

            // 4) The rest of other_services
            $otherServices = OtherService::where('id', '>', 1)->where('status', 'active')->get();

            return Helper::jsonResponse(true, 'Data fetched successfully', 200, [
                'hero_section'   => $cms_hero_section_data ? new HeroSectionResource($cms_hero_section_data) : null,
                'packages'       => PackageResource::collection($packages),
                'other_services' => [
                    'service_description' => $descRecord ? strip_tags($descRecord->service_description) : null,
                    'services'            => OtherServiceResource::collection($otherServices),
                ],
            ]);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
