<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\ServiceItem;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class ServiceItemController extends Controller {
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
                $data = ServiceItem::get();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('status', function ($data) {
                        return '
                        <div class="d-flex justify-content-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="SwitchCheck' . $data->id . '" ' . ($data->status == 'active' ? 'checked' : '') . ' onclick="showItemStatusChangeAlert(' . $data->id . ')">
                            </div>
                        </div>';
                    })
                    ->addColumn('action', function ($data) {
                        return '
                        <div class="d-flex justify-content-center hstack gap-3 fs-base">
                            <a href="javascript:void(0);" class="link-primary text-decoration-none edit-item" data-id="' . $data->id . '" title="Edit">
                                <i class="ri-pencil-line" style="font-size:24px;"></i>
                            </a>

                            <a href="javascript:void(0);" onclick="showItemDeleteConfirm(' . $data->id . ')" class="link-danger text-decoration-none" title="Delete">
                                <i class="ri-delete-bin-5-line" style="font-size:24px;"></i>
                            </a>
                        </div>';
                    })
                    ->rawColumns(['status', 'action'])
                    ->make();
            }
            return view('backend.layouts.services.index');
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'service_name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[a-zA-Z0-9\s\-\']+$/',
                'unique:service_items,service_name',
                function ($attribute, $value, $fail) {
                    $this->validateServiceName($attribute, $value, $fail);
                },
            ],
        ], [
            'service_name.required' => 'Service item name is required.',
            'service_name.string'   => 'Service item name must be valid text.',
            'service_name.min'      => 'Service item name must be at least 2 characters.',
            'service_name.max'      => 'Service item name cannot exceed 100 characters.',
            'service_name.regex'    => 'Service item name can only contain letters, numbers, spaces, hyphens, and apostrophes.',
            'service_name.unique'   => 'This service item name already exists.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        try {
            // Clean and format the service name
            $cleanedName = $this->cleanServiceName($request->input('service_name'));

            ServiceItem::create([
                'service_name' => $cleanedName,
            ]);

            return response()->json(['success' => true, 'message' => 'Service item created successfully.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function update(Request $request, int $id): JsonResponse {
        $validator = Validator::make($request->all(), [
            'service_name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[a-zA-Z0-9\s\-\']+$/',
                'unique:service_items,service_name,' . $id,
                function ($attribute, $value, $fail) {
                    $this->validateServiceName($attribute, $value, $fail);
                },
            ],
        ], [
            'service_name.required' => 'Service item name is required.',
            'service_name.string'   => 'Service item name must be valid text.',
            'service_name.min'      => 'Service item name must be at least 2 characters.',
            'service_name.max'      => 'Service item name cannot exceed 100 characters.',
            'service_name.regex'    => 'Service item name can only contain letters, numbers, spaces, hyphens, and apostrophes.',
            'service_name.unique'   => 'This service item name already exists.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        $data = ServiceItem::findOrFail($id);

        try {
            // Clean and format the service name
            $cleanedName = $this->cleanServiceName($request->input('service_name'));

            $data->update([
                'service_name' => $cleanedName,
            ]);

            return response()->json(['success' => true, 'message' => 'Service item updated successfully.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while updating: ' . $e->getMessage()]);
        }
    }

    /**
     * Additional validation for service name
     */
    private function validateServiceName($attribute, $value, $fail) {
        $trimmed = trim($value);

        // Check if it's not just spaces or special characters
        if (empty($trimmed)) {
            $fail('Service item name cannot be empty or contain only spaces.');
            return;
        }

        // Check for consecutive spaces
        if (preg_match('/\s{2,}/', $trimmed)) {
            $fail('Service item name cannot contain consecutive spaces.');
            return;
        }

        // Check if it starts or ends with special characters
        if (preg_match('/^[\s\-\']+|[\s\-\']+$/', $trimmed)) {
            $fail('Service item name cannot start or end with spaces, hyphens, or apostrophes.');
            return;
        }

        // Must contain at least one letter
        if (!preg_match('/[a-zA-Z]/', $trimmed)) {
            $fail('Service item name must contain at least one letter.');
            return;
        }
    }

    /**
     * Clean and format service name
     */
    private function cleanServiceName($name) {
        // Trim whitespace
        $cleaned = trim($name);

        // Replace multiple spaces with single space
        $cleaned = preg_replace('/\s+/', ' ', $cleaned);

        // Capitalize each word properly
        $cleaned = ucwords(strtolower($cleaned));

        return $cleaned;
    }

    /**
     * Change the status of the service item.
     *
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public function status(int $id): JsonResponse {
        try {
            $data = ServiceItem::findOrFail($id);

            if ($data->status == 'active') {
                $data->status = 'inactive';
                $data->save();

                return response()->json([
                    'success' => false,
                    'message' => 'Service item deactivated successfully.',
                    'data'    => $data,
                ]);
            } else {
                $data->status = 'active';
                $data->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Service item activated successfully.',
                    'data'    => $data,
                ]);
            }
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(int $id): JsonResponse {
        try {
            $data = ServiceItem::findOrFail($id);

            // Debug logging
            Log::info('Attempting to delete ServiceItem ID: ' . $id);

            // Check many-to-many relationship with services through pivot table
            $servicesCount = $data->services()->count();
            Log::info('Services count (via service_items_pivot): ' . $servicesCount);

            // Check one-to-many relationship with add-ons
            $addOnsCount = $data->addOns()->count();
            Log::info('Add-ons count: ' . $addOnsCount);

            // If you want to see which specific services are using this item:
            if ($servicesCount > 0) {
                $serviceIds = $data->services()->pluck('services.id')->toArray();
                Log::info('Service IDs using this item: ' . implode(', ', $serviceIds));
            }

            // Check if this service item is being used
            if ($servicesCount > 0 || $addOnsCount > 0) {
                Log::info('Cannot delete - item is in use');

                $usageDetails = [];
                if ($servicesCount > 0) {
                    $usageDetails[] = "$servicesCount service(s)";
                }
                if ($addOnsCount > 0) {
                    $usageDetails[] = "$addOnsCount add-on(s)";
                }

                return response()->json([
                    't-success' => false,
                    'message'   => 'Cannot delete this service item because it is being used by ' . implode(' and ', $usageDetails) . '.',
                ]);
            }

            // Delete the service item
            $data->delete();
            Log::info('ServiceItem deleted successfully');

            return response()->json([
                't-success' => true,
                'message'   => 'Service item deleted successfully.',
            ]);

        } catch (ModelNotFoundException $e) {
            Log::error('ServiceItem not found: ' . $e->getMessage());
            return response()->json([
                't-success' => false,
                'message'   => 'Service item not found.',
            ], 404);

        } catch (QueryException $e) {
            Log::error('Database error while deleting ServiceItem: ' . $e->getMessage());
            return response()->json([
                't-success' => false,
                'message'   => 'Database error occurred while deleting.',
            ], 500);

        } catch (Exception $e) {
            Log::error('Delete ServiceItem Error: ' . $e->getMessage());
            return response()->json([
                't-success' => false,
                'message'   => 'An unexpected error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }
}
