<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Package;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class PackageController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return JsonResponse|View
     * @throws Exception
     */
    public function index(Request $request): JsonResponse | View {
        try {
            if ($request->ajax()) {
                $data = Package::latest()->get();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('description', function ($data) {
                        $description      = $data->description;
                        $shortDescription = strlen($description) > 200 ? substr($description, 0, 200) . '...' : $description;
                        return '<p>' . $shortDescription . '</p>';
                    })
                    ->addColumn('image', function ($data) {
                        $defaultImage = asset('backend/images/users/user-dummy-img.jpg');
                        $url          = $data->image ? asset($data->image) : $defaultImage;

                        return '
                            <div class="d-flex justify-content-center">
                                <img src="' . $url . '" alt="Image" width="50" height="50" style="cursor:pointer;"
                                     data-bs-toggle="modal" data-bs-target="#imagePreviewModal"
                                     onclick="showImagePreview(\'' . $url . '\');" />
                            </div>
                        ';
                    })
                    ->addColumn('is_popular', function ($data) {
                        $buttonClass = $data->is_popular ? 'btn-success' : 'btn-outline-secondary';
                        $buttonText  = $data->is_popular ? 'Popular' : 'Set Popular';
                        $icon        = $data->is_popular ? 'ri-star-fill' : 'ri-star-line';

                        // Disable button if package is inactive and not currently popular
                        $isDisabled    = ($data->status === 'inactive' && !$data->is_popular);
                        $disabledClass = $isDisabled ? ' disabled' : '';
                        $disabledAttr  = $isDisabled ? ' disabled' : '';
                        $titleText     = $isDisabled ? 'Package must be active to set as popular' : ($data->is_popular ? 'Remove from Popular' : 'Set as Popular');

                        // If package is inactive and not popular, show different styling
                        if ($isDisabled) {
                            $buttonClass = 'btn-secondary';
                            $buttonText  = 'Inactive';
                            $icon        = 'ri-close-line';
                        }

                        return '
                            <div class="d-flex justify-content-center">
                                <button class="btn ' . $buttonClass . ' btn-sm' . $disabledClass . '" onclick="togglePopular(' . $data->id . ')" title="' . $titleText . '"' . $disabledAttr . '>
                                    <i class="' . $icon . '"></i> ' . $buttonText . '
                                </button>
                            </div>
                        ';
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
                                <a href="' . route('package.edit', ['id' => $data->id]) . '" class="link-primary text-decoration-none" title="Edit">
                                    <i class="ri-pencil-line" style="font-size: 24px;"></i>
                                </a>

                                <a href="javascript:void(0);" onclick="showPackageDetails(' . $data->id . ')" class="link-primary text-decoration-none" data-bs-toggle="modal" data-bs-target="#viewPackageModal" title="View">
                                    <i class="ri-eye-line" style="font-size: 24px;"></i>
                                </a>
                            </div>
                        ';
                    })
                    ->rawColumns(['description', 'image', 'is_popular', 'status', 'action'])
                    ->make();
            }
            return view('backend.layouts.package.index');
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public function show(int $id): JsonResponse {
        try {
            $data = Package::findOrFail($id);
            return Helper::jsonResponse(true, 'Data fetched successfully', 200, $data);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return JsonResponse|View
     * @throws Exception
     */
    public function edit(int $id): JsonResponse | View {
        try {
            $packages = Package::findOrFail($id);
            return view('backend.layouts.package.edit', compact('packages'));
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     * @throws Exception
     */
    public function update(Request $request, int $id): RedirectResponse {
        try {
            $validator = Validator::make($request->all(), [
                'title'       => 'required|string',
                'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
                'name'        => 'required|string',
                'description' => 'required|string',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $package              = Package::findOrFail($id);
            $package->name        = $request->name;
            $package->title       = $request->title;
            $package->description = $request->description;

            // If a new image is uploaded, delete the old image and upload the new one.
            if ($request->hasFile('image')) {
                if ($package->image) {
                    Helper::fileDelete(public_path($package->image));
                }
                $uploadPath = Helper::fileUpload($request->file('image'), 'packages', $request->name);
                if ($uploadPath !== null) {
                    $package->image = $uploadPath;
                }
            }

            $package->save();

            return redirect()->route('package.index')->with('t-success', 'Package updated successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('t-error', 'Failed to update package')->withInput();
        }
    }

    /**
     * Toggle the popular status of the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public function togglePopular(int $id): JsonResponse {
        try {
            $package = Package::findOrFail($id);

            // Check if package is inactive - cannot set as popular
            if ($package->status === 'inactive' && !$package->is_popular) {
                return Helper::jsonResponse(false, 'Cannot set inactive package as popular. Please activate the package first.', 400);
            }

            if ($package->is_popular) {
                // If already popular, remove it
                $package->is_popular = false;
                $package->save();
                return Helper::jsonResponse(true, 'Removed from popular successfully.', 200, $package);
            } else {
                // Only allow setting as popular if package is active
                if ($package->status === 'active') {
                    // Remove popular status from all other packages
                    Package::where('is_popular', true)->update(['is_popular' => false]);

                    // Set this package as popular
                    $package->is_popular = true;
                    $package->save();
                    return Helper::jsonResponse(true, 'Set as popular successfully.', 200, $package);
                } else {
                    return Helper::jsonResponse(false, 'Cannot set inactive package as popular. Please activate the package first.', 400);
                }
            }
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Toggle the status of the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public function status(int $id): JsonResponse {
        try {
            $package = Package::findOrFail($id);

            if ($package->status === 'active') {
                $package->status = 'inactive';

                // If the package is being set to inactive and it's currently popular, remove popular status
                if ($package->is_popular) {
                    $package->is_popular = false;
                }

                $package->save();

                return Helper::jsonResponse(false, 'Unpublished Successfully.', 200, $package);
            } else {
                $package->status = 'active';
                $package->save();

                return Helper::jsonResponse(true, 'Published Successfully.', 200, $package);
            }
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
