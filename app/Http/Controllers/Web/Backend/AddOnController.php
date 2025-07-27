<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\AddOn;
use App\Models\FootageSize;
use App\Models\ServiceItem;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class AddOnController extends Controller {
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
                $data = AddOn::with(['footageSize', 'serviceItem'])->latest()->get();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('footage_size', function ($data) {
                        return $data->footageSize ? $data->footageSize->size : 'N/A';
                    })
                    ->addColumn('service_item_name', function ($data) {
                        return $data->serviceItem ? $data->serviceItem->service_name : 'N/A';
                    })
                    ->addColumn('quantity_display', function ($data) {
                        if ($data->isCommunityImages()) {
                            return $data->quantity . ' images - ' . $data->locations . ' location(s)';
                        }
                        return $data->quantity;
                    })
                    ->addColumn('formatted_price', function ($data) {
                        return '$' . number_format($data->price, 2);
                    })
                    ->addColumn('status', function ($data) {
                        return '
                    <div class="d-flex justify-content-center">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="SwitchCheck' . $data->id . '" ' . ($data->status == 'active' ? 'checked' : '') . ' onclick="showAddOnStatusChangeAlert(' . $data->id . ')">
                        </div>
                    </div>';
                    })
                    ->addColumn('action', function ($data) {
                        return '
                    <div class="d-flex justify-content-center hstack gap-3 fs-base">
                        <a href="javascript:void(0);" class="link-primary text-decoration-none edit-add-on" data-id="' . $data->id . '" title="Edit">
                            <i class="ri-pencil-line" style="font-size:24px;"></i>
                        </a>

                        <a href="javascript:void(0);" onclick="showAddOnDeleteConfirm(' . $data->id . ')" class="link-danger text-decoration-none" title="Delete">
                            <i class="ri-delete-bin-5-line" style="font-size:24px;"></i>
                        </a>
                    </div>';
                    })
                    ->rawColumns(['footage_size', 'service_item_name', 'quantity_display', 'formatted_price', 'status', 'action'])
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
     * Get form data for Add-On creation.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function getFormData(): JsonResponse {
        try {
            $footageSizes = FootageSize::where('status', 'active')->select('id', 'size')->get();
            $serviceItems = ServiceItem::where('status', 'active')->select('id', 'service_name')->get();

            return response()->json([
                'success' => true,
                'data'    => [
                    'footage_sizes' => $footageSizes,
                    'service_items' => $serviceItems,
                ],
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
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
        $serviceItem       = ServiceItem::find($request->input('service_item_id'));
        $isCommunityImages = $serviceItem && strtolower($serviceItem->service_name) === 'community image';

        $rules = [
            'footage_size_id' => 'required|integer|exists:footage_sizes,id',
            'service_item_id' => 'required|integer|exists:service_items,id',
            'quantity'        => 'nullable|integer|min:1',
            'price'           => 'required|numeric|min:0',
        ];

        // Add locations validation for Community Images
        if ($isCommunityImages) {
            $rules['locations'] = 'required|integer|min:1|max:5';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        try {
            // Enhanced duplicate check including locations
            $query = AddOn::where('footage_size_id', $request->input('footage_size_id'))
                ->where('service_item_id', $request->input('service_item_id'))
                ->where('quantity', $request->input('quantity'))
                ->where('price', $request->input('price'));

            if ($isCommunityImages) {
                $query->where('locations', $request->input('locations'));
            }

            $existingAddOn = $query->first();

            if ($existingAddOn) {
                $message = $isCommunityImages
                ? 'A Community Images service with this exact combination already exists.'
                : 'A service with this exact combination already exists.';

                return response()->json([
                    'success' => false,
                    'message' => $message,
                ]);
            }

            // Create with locations if Community Images
            $data = [
                'footage_size_id' => $request->input('footage_size_id'),
                'service_item_id' => $request->input('service_item_id'),
                'quantity'        => $request->input('quantity'),
                'price'           => $request->input('price'),
            ];

            if ($isCommunityImages) {
                $data['locations'] = $request->input('locations');
            }

            AddOn::create($data);

            return response()->json(['success' => true, 'message' => 'Add-On Created Successfully.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
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
            $addOn = AddOn::with(['footageSize', 'serviceItem'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data'    => [
                    'id'                  => $addOn->id,
                    'footage_size_id'     => $addOn->footage_size_id,
                    'service_item_id'     => $addOn->service_item_id,
                    'quantity'            => $addOn->quantity,
                    'locations'           => $addOn->locations,
                    'price'               => number_format((float) $addOn->price, 2, '.', ''),
                    'status'              => $addOn->status,
                    'is_community_images' => $addOn->isCommunityImages(),
                    'footage_size_name'   => $addOn->footageSize ? $addOn->footageSize->size : null,
                    'service_item_name'   => $addOn->serviceItem ? $addOn->serviceItem->service_name : null,
                ],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load add-on details: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public function update(Request $request, int $id): JsonResponse {
        $serviceItem       = ServiceItem::find($request->input('service_item_id'));
        $isCommunityImages = $serviceItem && strtolower($serviceItem->service_name) === 'community image';

        $rules = [
            'footage_size_id' => 'required|integer|exists:footage_sizes,id',
            'service_item_id' => 'required|integer|exists:service_items,id',
            'quantity'        => 'nullable|integer|min:1',
            'price'           => 'required|numeric|min:0',
        ];

        if ($isCommunityImages) {
            $rules['locations'] = 'sometimes|required|integer|min:1|max:5';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        try {
            $addOn = AddOn::findOrFail($id);

            // Enhanced duplicate check
            $query = AddOn::where('footage_size_id', $request->input('footage_size_id'))
                ->where('service_item_id', $request->input('service_item_id'))
                ->where('quantity', $request->input('quantity'))
                ->where('price', $request->input('price'))
                ->where('id', '!=', $id);

            if ($isCommunityImages) {
                $query->where('locations', $request->input('locations'));
            }

            $existingAddOn = $query->first();

            if ($existingAddOn) {
                return response()->json([
                    'success' => false,
                    'message' => 'A service with this exact combination already exists.',
                ]);
            }

            $updateData = [
                'footage_size_id' => $request->input('footage_size_id'),
                'service_item_id' => $request->input('service_item_id'),
                'quantity'        => $request->input('quantity'),
                'price'           => $request->input('price'),
            ];

            if ($isCommunityImages) {
                $updateData['locations'] = $request->input('locations');
            } else {
                $updateData['locations'] = null;
            }

            $addOn->update($updateData);

            return response()->json(['success' => true, 'message' => 'Add-On Updated Successfully.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while updating: ' . $e->getMessage()]);
        }
    }

    /**
     * Change the status of the Add-On.
     *
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public function status(int $id): JsonResponse {
        try {
            $data = AddOn::findOrFail($id);

            if ($data->status == 'active') {
                $data->status = 'inactive';
                $data->save();

                return response()->json([
                    'success' => false,
                    'message' => 'Unpublished successfully.',
                    'data'    => $data,
                ]);
            } else {
                $data->status = 'active';
                $data->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Published successfully.',
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
            $data = AddOn::findOrFail($id);
            $data->delete();

            return response()->json([
                't-success' => true,
                'message'   => 'Deleted successfully.',
            ]);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
