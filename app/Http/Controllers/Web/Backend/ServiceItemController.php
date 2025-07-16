<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\ServiceItem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ServiceItemController extends Controller {
    public function index(Request $request) {
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

    public function store(Request $request) {
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

    public function update(Request $request, int $id) {
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

    public function status(int $id) {
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

    public function destroy(int $id) {
        try {
            $data = ServiceItem::findOrFail($id);

            // Check if this service item is being used by any services
            if ($data->services()->count() > 0) {
                return response()->json([
                    't-success' => false,
                    'message'   => 'Cannot delete this service item because it is being used by one or more services.',
                ]);
            }

            $data->delete();

            return response()->json([
                't-success' => true,
                'message'   => 'Service item deleted successfully.',
            ]);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
