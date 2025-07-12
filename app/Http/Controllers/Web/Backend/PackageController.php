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
                        $shortDescription = strlen($description) > 100 ? substr($description, 0, 100) . '...' : $description;
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

                                <a href="javascript:void(0);" onclick="showDeleteConfirm(' . $data->id . ')" class="link-danger text-decoration-none" title="Delete">
                                    <i class="ri-delete-bin-5-line" style="font-size: 24px;"></i>
                                </a>
                            </div>
                        ';
                    })
                    ->rawColumns(['description', 'image', 'status', 'action'])
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
     * Show the form for creating a new resource.
     *
     * @return JsonResponse|View
     * @throws Exception
     */
    public function create(): JsonResponse | View {
        try {
            return view('backend.layouts.package.create');
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function store(Request $request): RedirectResponse {
        try {
            $validator = Validator::make($request->all(), [
                'title'       => 'required|string',
                'image'       => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
                'name'        => 'required|string',
                'description' => 'required|string',
                'is_popular'  => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $package              = new Package();
            $package->title       = $request->title;
            $package->name        = $request->name;
            $package->description = $request->description;
            $package->is_popular  = $request->is_popular ?? false;

            // If an image was uploaded, store it
            if ($request->hasFile('image')) {
                $uploadPath = Helper::fileUpload($request->file('image'), 'packages', $request->name);
                if ($uploadPath !== null) {
                    $package->image = $uploadPath;
                }
            }

            $package->save();
            return redirect()->route('package.index')->with('t-success', 'Package Create Successfully');
        } catch (Exception) {
            return redirect()->back()->with('t-error', 'Failed to create')->withInput();
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
                'is_popular'  => 'required|boolean',
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
            return redirect()->back()->with('t-error', 'Failed to update testimonial')->withInput();
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

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(int $id): JsonResponse {
        try {
            $package = Package::findOrFail($id);

            // If there's an associated image, remove it from storage first
            if ($package->image) {
                $imagePath = public_path($package->image);
                Helper::fileDelete($imagePath);
            }

            // Delete the record
            $package->delete();

            return Helper::jsonResponse(true, 'Deleted successfully.', 200, $package);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred while deleting.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
