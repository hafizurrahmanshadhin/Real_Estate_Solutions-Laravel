<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ContactUsRequest;
use App\Http\Resources\Api\ContactUs\ContactPageResource;
use App\Http\Resources\Api\ContactUs\ContactUsResource;
use App\Models\CMS;
use App\Models\ContactUs;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class ContactUsController extends Controller {
    /**
     * Get contact page data from CMS with caching
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function index(): JsonResponse {
        try {
            // Cache the data for 60 minutes
            $contactPageData = Cache::remember('contact_page_data', 3600, function () {
                return CMS::where('section', 'contact_us_page')->where('status', 'active')->first();
            });

            if (!$contactPageData) {
                return Helper::jsonResponse(false, 'No active contact page data found', 404);
            }

            return Helper::jsonResponse(true, 'Contact page data retrieved successfully.', 200, new ContactPageResource($contactPageData));

        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred while fetching contact page data', 500,
                ['error' => $e->getMessage()]
            );
        }
    }

    /**
     * Store a new contact us message
     *
     * @param ContactUsRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(ContactUsRequest $request): JsonResponse {
        try {
            $validatedData = $request->validated();

            $contactUs = ContactUs::create([
                'first_name'   => $validatedData['first_name'],
                'last_name'    => $validatedData['last_name'],
                'email'        => $validatedData['email'],
                'phone_number' => $validatedData['phone_number'],
                'message'      => $validatedData['message'],
                'is_agree'     => $validatedData['is_agree'],
            ]);

            return Helper::jsonResponse(true, 'Contact message submitted successfully. We will get back to you soon!', 201,
                new ContactUsResource($contactUs)
            );

        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to submit contact message. Please try again.', 500,
                ['error' => $e->getMessage()]
            );
        }
    }
}
