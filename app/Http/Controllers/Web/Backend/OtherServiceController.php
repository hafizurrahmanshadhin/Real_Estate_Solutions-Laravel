<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\OtherService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class OtherServiceController extends Controller {
    public function index(Request $request) {
        try {
            if ($request->ajax()) {
                $data = OtherService::latest()->get();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('image', function ($data) {
                        $defaultImage = asset('backend/images/users/user-dummy-img.jpg');
                        $url          = $data->image ? asset($data->image) : $defaultImage;

                        return '
                            <div class="d-flex justify-content-center">
                                <img src="' . $url . '" alt="Image" width="75" height="75" style="cursor:pointer;"
                                     data-bs-toggle="modal" data-bs-target="#imagePreviewModal"
                                     onclick="showImagePreview(\'' . $url . '\');" />
                            </div>
                        ';
                    })
                    ->addColumn('image_url', function ($data) {
                        $defaultImage = asset('backend/images/users/user-dummy-img.jpg');
                        return $data->image ? asset($data->image) : $defaultImage;
                    })
                    ->addColumn('description', function ($data) {
                        $description      = $data->description;
                        $shortDescription = strlen($description) > 150 ? substr($description, 0, 150) . '...' : $description;
                        return '<p>' . $shortDescription . '</p>';
                    })
                    ->addColumn('status', function ($data) {
                        return '
                            <div class="d-flex justify-content-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="SwitchCheck' . $data->id . '" ' . ($data->status == 'active' ? 'checked' : '') . ' onclick="showStatusChangeAlert(' . $data->id . ')">
                                </div>
                            </div>
                        ';
                    })
                    ->addColumn('action', function ($data) {
                        return '
                            <div class="d-flex justify-content-center hstack gap-3 fs-base">
                                <a href="javascript:void(0);" class="link-primary text-decoration-none edit-other-service" data-id="' . $data->id . '" title="Edit">
                                    <i class="ri-pencil-line" style="font-size:24px;"></i>
                                </a>

                                <a href="javascript:void(0);" onclick="showOtherServiceDetails(' . $data->id . ')" class="link-primary text-decoration-none" data-bs-toggle="modal" data-bs-target="#viewOtherServiceModal" title="View">
                                    <i class="ri-eye-line" style="font-size: 24px;"></i>
                                </a>

                                <a href="javascript:void(0);" onclick="showDeleteConfirm(' . $data->id . ')" class="link-danger text-decoration-none" title="Delete">
                                    <i class="ri-delete-bin-5-line" style="font-size:24px;"></i>
                                </a>
                            </div>
                        ';
                    })
                    ->rawColumns(['image', 'image_url', 'description', 'status', 'action'])
                    ->make();
            }

            // ✅ Get or create the service_description record (single entity)
            $otherService = OtherService::whereNull('title')
                ->whereNull('description')
                ->whereNull('image')
                ->first();

            if (!$otherService) {
                // Create the single service_description record
                $otherService                      = new OtherService();
                $otherService->service_description = null;
                $otherService->title               = null;
                $otherService->description         = null;
                $otherService->image               = null;
                $otherService->save();
            }

            return view('backend.layouts.other-services.index', compact('otherService'));
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function updateOtherService(Request $request) {
        try {
            $rules = [
                'service_description' => 'nullable|string|max:1000',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // ✅ Get the single service_description record
            $otherService = OtherService::whereNull('title')
                ->whereNull('description')
                ->whereNull('image')
                ->first();

            if (!$otherService) {
                // Create the single record if it doesn't exist
                $otherService              = new OtherService();
                $otherService->title       = null;
                $otherService->description = null;
                $otherService->image       = null;
            }

            // ✅ Update ONLY service_description
            $otherService->service_description = $request->input('service_description');
            $otherService->save();

            return redirect()->route('other-service.index')->with('t-success', 'Service Description Updated Successfully.');
        } catch (Exception $e) {
            return back()->with('t-error', $e->getMessage());
        }
    }

    public function show(int $id) {
        try {
            $data = OtherService::findOrFail($id);
            return Helper::jsonResponse(true, 'Data fetched successfully', 200, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function store(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'title'       => 'required|string',
                'description' => 'required|string',
                'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
            ]);

            if ($validator->fails()) {
                return Helper::jsonResponse(false, 'Validation errors', 422, null, $validator->errors());
            }

            $otherService                      = new OtherService();
            $otherService->service_description = null;
            $otherService->title               = $request->input('title');
            $otherService->description         = $request->input('description');

            // If an image was uploaded, store it
            if ($request->hasFile('image')) {
                $uploadPath = Helper::fileUpload($request->file('image'), 'otherService', $request->input('title'));
                if ($uploadPath !== null) {
                    $otherService->image = $uploadPath;
                }
            } else {
                $otherService->image = null;
            }
            $otherService->save();

            return Helper::jsonResponse(true, 'Other Service Created Successfully.', 201, $otherService);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Error Creating Other Service: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, int $id) {
        try {
            $validator = Validator::make($request->all(), [
                'title'       => 'required|string',
                'description' => 'required|string',
                'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
            ]);

            if ($validator->fails()) {
                return Helper::jsonResponse(false, 'Validation errors', 422, null, $validator->errors());
            }

            $otherService                      = OtherService::findOrFail($id);
            $otherService->service_description = null;
            $otherService->title               = $request->input('title');
            $otherService->description         = $request->input('description');

            // If a new image is uploaded, delete the old image and upload the new one.
            if ($request->hasFile('image')) {
                if (!empty($otherService->image)) {
                    Helper::fileDelete(public_path($otherService->image));
                }
                $uploadPath = Helper::fileUpload($request->file('image'), 'otherService', $request->input('title'));
                if ($uploadPath !== null) {
                    $otherService->image = $uploadPath;
                }
            }

            $otherService->save();

            return Helper::jsonResponse(true, 'Other Service updated successfully.', 200, $otherService);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Error updating Other Service: ' . $e->getMessage(), 500);
        }
    }

    public function status(int $id) {
        try {
            $otherService = OtherService::findOrFail($id);

            if ($otherService->status === 'active') {
                $otherService->status = 'inactive';
                $otherService->save();

                return Helper::jsonResponse(false, 'Unpublished Successfully.', 200, $otherService);
            } else {
                $otherService->status = 'active';
                $otherService->save();

                return Helper::jsonResponse(true, 'Published Successfully.', 200, $otherService);
            }
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function destroy(int $id) {
        try {
            $otherService = OtherService::findOrFail($id);

            // If there's an associated image, remove it from storage first
            if ($otherService->image) {
                $imagePath = public_path($otherService->image);
                Helper::fileDelete($imagePath);
            }

            // Delete the record
            $otherService->delete();

            return Helper::jsonResponse(true, 'Deleted successfully.', 200, $otherService);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred while deleting.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
