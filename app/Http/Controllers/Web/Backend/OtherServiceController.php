<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\OtherService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
                                <a href="javascript:void(0);" class="link-primary text-decoration-none edit-feature" data-id="' . $data->id . '" title="Edit">
                                    <i class="ri-pencil-line" style="font-size:24px;"></i>
                                </a>

                                <a href="javascript:void(0);" onclick="showFeatureDetails(' . $data->id . ')" class="link-primary text-decoration-none" data-bs-toggle="modal" data-bs-target="#viewFeatureModal" title="View">
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
            return view('backend.layouts.other-services.index');
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    // public function updateOtherService(Request $request) {
    //     try {
    //         $AIPrompt = AIPrompt::firstOrNew();

    //         $rules = [
    //             'title'        => 'required|string',
    //             'image'        => $AIPrompt->exists
    //             ? 'nullable|image|mimes:jpg,jpeg,png,gif|max:20480'
    //             : 'required|image|mimes:jpg,jpeg,png,gif|max:20480',
    //             'remove_image' => 'nullable|boolean',
    //         ];

    //         $validator = Validator::make($request->all(), $rules);
    //         if ($validator->fails()) {
    //             return redirect()->back()->withErrors($validator)->withInput();
    //         }

    //         $AIPrompt->title = $request->title;

    //         //* Handle image file
    //         if ($request->boolean('remove_image')) {
    //             if ($AIPrompt->image) {
    //                 Helper::fileDelete(public_path($AIPrompt->image));
    //                 $AIPrompt->image = null;
    //             }
    //         } elseif ($request->hasFile('image')) {
    //             if ($AIPrompt->image) {
    //                 Helper::fileDelete(public_path($AIPrompt->image));
    //             }
    //             $AIPrompt->image = Helper::fileUpload($request->file('image'), 'AIPrompt', $AIPrompt->image);
    //         }
    //         $AIPrompt->save();

    //         return redirect()->route('cms.about-page.feature.index')->with('t-success', 'AI Prompt Updated Successfully.');
    //     } catch (Exception $e) {
    //         return back()->with('t-error', $e->getMessage());
    //     }
    // }

    // public function show(int $id) {
    //     try {
    //         $data = Feature::findOrFail($id);
    //         return Helper::jsonResponse(true, 'Data fetched successfully', 200, $data);
    //     } catch (Exception $e) {
    //         return Helper::jsonResponse(false, 'An error occurred', 500, [
    //             'error' => $e->getMessage(),
    //         ]);
    //     }
    // }

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

            $otherService              = new OtherService();
            $otherService->title       = $request->input('title');
            $otherService->description = $request->input('description');

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

    // public function update(Request $request, int $id) {
    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'title'       => 'required|string',
    //             'description' => 'required|string',
    //             'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
    //         ]);

    //         if ($validator->fails()) {
    //             return Helper::jsonResponse(false, 'Validation errors', 422, null, $validator->errors());
    //         }

    //         $feature              = Feature::findOrFail($id);
    //         $feature->title       = $request->title;
    //         $feature->description = $request->description;

    //         // If a new image is uploaded, delete the old image and upload the new one.
    //         if ($request->hasFile('image')) {
    //             if (!empty($feature->image)) {
    //                 Helper::fileDelete(public_path($feature->image));
    //             }
    //             $uploadPath = Helper::fileUpload($request->file('image'), 'features', $request->title);
    //             if ($uploadPath !== null) {
    //                 $feature->image = $uploadPath;
    //             }
    //         }

    //         $feature->save();

    //         return Helper::jsonResponse(true, 'Feature updated successfully.', 200, $feature);
    //     } catch (Exception $e) {
    //         return Helper::jsonResponse(false, 'Error updating feature: ' . $e->getMessage(), 500);
    //     }
    // }

    // public function status(int $id) {
    //     try {
    //         $feature = Feature::findOrFail($id);

    //         if ($feature->status === 'active') {
    //             $feature->status = 'inactive';
    //             $feature->save();

    //             return Helper::jsonResponse(false, 'Unpublished Successfully.', 200, $feature);
    //         } else {
    //             $feature->status = 'active';
    //             $feature->save();

    //             return Helper::jsonResponse(true, 'Published Successfully.', 200, $feature);
    //         }
    //     } catch (Exception $e) {
    //         return Helper::jsonResponse(false, 'An error occurred', 500, [
    //             'error' => $e->getMessage(),
    //         ]);
    //     }
    // }

    // public function destroy(int $id) {
    //     try {
    //         $feature = Feature::findOrFail($id);

    //         // If there's an associated image, remove it from storage first
    //         if ($feature->image) {
    //             $imagePath = public_path($feature->image);
    //             Helper::fileDelete($imagePath);
    //         }

    //         // Delete the record
    //         $feature->delete();

    //         return Helper::jsonResponse(true, 'Deleted successfully.', 200, $feature);
    //     } catch (Exception $e) {
    //         return Helper::jsonResponse(false, 'An error occurred while deleting.', 500, [
    //             'error' => $e->getMessage(),
    //         ]);
    //     }
    // }
}
