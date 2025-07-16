<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\FootageSize;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class FootageSizeController extends Controller {
    public function index(Request $request) {
        try {
            if ($request->ajax()) {
                $data = FootageSize::get();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('status', function ($data) {
                        return '
                            <div class="d-flex justify-content-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="SwitchCheck' . $data->id . '" ' . ($data->status == 'active' ? 'checked' : '') . ' onclick="showFootageStatusChangeAlert(' . $data->id . ')">
                                </div>
                            </div>
                        ';
                    })
                    ->addColumn('action', function ($data) {
                        return '
                        <div class="d-flex justify-content-center hstack gap-3 fs-base">
                            <a href="javascript:void(0);" class="link-primary text-decoration-none edit-size" data-id="' . $data->id . '" title="Edit">
                                <i class="ri-pencil-line" style="font-size:24px;"></i>
                            </a>

                            <a href="javascript:void(0);" onclick="showFootageDeleteConfirm(' . $data->id . ')" class="link-danger text-decoration-none" title="Delete">
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
            'size' => [
                'required',
                'string',
                'max:50',
                function ($attribute, $value, $fail) {
                    $this->validateFootageRange($attribute, $value, $fail);
                },
                function ($attribute, $value, $fail) {
                    $this->validateUniqueness($attribute, $value, $fail);
                },
            ],
        ], [
            'size.required' => 'Square footage range is required.',
            'size.string'   => 'Square footage range must be a valid text.',
            'size.max'      => 'Square footage range cannot exceed 50 characters.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        try {
            // Normalize the input before storing
            $normalizedSize = $this->normalizeFootageRange($request->input('size'));

            FootageSize::create([
                'size' => $normalizedSize,
            ]);

            return response()->json(['success' => true, 'message' => 'Square footage range created successfully.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, int $id) {
        $validator = Validator::make($request->all(), [
            'size' => [
                'required',
                'string',
                'max:50',
                function ($attribute, $value, $fail) {
                    $this->validateFootageRange($attribute, $value, $fail);
                },
                function ($attribute, $value, $fail) use ($id) {
                    $this->validateUniqueness($attribute, $value, $fail, $id);
                },
            ],
        ], [
            'size.required' => 'Square footage range is required.',
            'size.string'   => 'Square footage range must be a valid text.',
            'size.max'      => 'Square footage range cannot exceed 50 characters.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        $data = FootageSize::findOrFail($id);

        try {
            // Normalize the input before storing
            $normalizedSize = $this->normalizeFootageRange($request->input('size'));

            $data->update([
                'size' => $normalizedSize,
            ]);

            return response()->json(['success' => true, 'message' => 'Square footage range updated successfully.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while updating: ' . $e->getMessage()]);
        }
    }

    /**
     * Validate footage range format and values
     */
    private function validateFootageRange($attribute, $value, $fail) {
        // Remove extra spaces and normalize
        $normalized = trim(preg_replace('/\s+/', ' ', $value));

        // Check if it matches the pattern: number-number or number - number
        if (!preg_match('/^\d+\s*-\s*\d+$/', $normalized)) {
            $fail('The square footage range must be in format: number-number (e.g., 0-10, 11-20).');
            return;
        }

        // Extract numbers
        preg_match('/^(\d+)\s*-\s*(\d+)$/', $normalized, $matches);
        $start = (int) $matches[1];
        $end   = (int) $matches[2];

        // Validate range rules
        if ($start < 0 || $end < 0) {
            $fail('Square footage range cannot contain negative numbers. Minimum value is 0.');
            return;
        }

        if ($start >= $end) {
            $fail('The first number must be less than the second number in the range.');
            return;
        }

        if ($start > 999999 || $end > 999999) {
            $fail('Square footage values cannot exceed 999,999.');
            return;
        }
    }

    /**
     * Validate uniqueness considering normalized format
     */
    private function validateUniqueness($attribute, $value, $fail, $excludeId = null) {
        $normalized = $this->normalizeFootageRange($value);

        $query = FootageSize::where('size', $normalized);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        if ($query->exists()) {
            $fail('This square footage range already exists.');
        }
    }

    /**
     * Normalize footage range to consistent format
     */
    private function normalizeFootageRange($value) {
        // Remove extra spaces and normalize
        $normalized = trim(preg_replace('/\s+/', ' ', $value));

        // Extract numbers and format consistently
        if (preg_match('/^(\d+)\s*-\s*(\d+)$/', $normalized, $matches)) {
            return $matches[1] . '-' . $matches[2];
        }

        return $normalized;
    }

    public function status(int $id) {
        try {
            $data = FootageSize::findOrFail($id);

            if ($data->status == 'active') {
                $data->status = 'inactive';
                $data->save();

                return response()->json([
                    'success' => false,
                    'message' => 'Square footage range deactivated successfully.',
                    'data'    => $data,
                ]);
            } else {
                $data->status = 'active';
                $data->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Square footage range activated successfully.',
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
            $data = FootageSize::findOrFail($id);

            // Check if this footage size is being used by any services
            if ($data->services()->count() > 0) {
                return response()->json([
                    't-success' => false,
                    'message'   => 'Cannot delete this square footage range because it is being used by one or more services.',
                ]);
            }

            $data->delete();

            return response()->json([
                't-success' => true,
                'message'   => 'Square footage range deleted successfully.',
            ]);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
