<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Home\PackageResource;
use App\Models\Package;

class HomeController extends Controller {
    public function index() {
        $packages = Package::where('status', 'active')
            ->withMin(['services' => function ($q) {
                $q->where('status', 'active');
            }], 'price')
            ->get();

        return Helper::jsonResponse(true, 'Data fetched successfully', 200,
            PackageResource::collection($packages)
        );
    }
}
