<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\AddOn;
use App\Models\FootageSize;
use App\Models\ServiceItem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class AddOnController extends Controller {
    public function index(Request $request) {
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
                    ->rawColumns(['footage_size', 'service_item_name', 'formatted_price', 'status', 'action'])
                    ->make();
            }
            return view('backend.layouts.services.index');
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function getFormData() {
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

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'footage_size_id' => 'required|integer|exists:footage_sizes,id',
            'service_item_id' => 'required|integer|exists:service_items,id',
            'quantity'        => 'nullable|integer|min:1',
            'price'           => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        try {
            // Check if this exact combination already exists.
            $existingAddOn = AddOn::where('footage_size_id', $request->footage_size_id)
                ->where('service_item_id', $request->service_item_id)
                ->where('quantity', $request->quantity)
                ->where('price', $request->price)
                ->first();

            if ($existingAddOn) {
                return response()->json([
                    'success' => false,
                    'message' => 'A service with this exact combination (footage size, service item, quantity, and price) already exists.',
                ]);
            }

            AddOn::create([
                'footage_size_id' => $request->footage_size_id,
                'service_item_id' => $request->service_item_id,
                'quantity'        => $request->quantity,
                'price'           => $request->price,
            ]);

            return response()->json(['success' => true, 'message' => 'Add-Ons Created Successfully.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, int $id) {
        $validator = Validator::make($request->all(), [
            'footage_size_id' => 'required|integer|exists:footage_sizes,id',
            'service_item_id' => 'required|integer|exists:service_items,id',
            'quantity'        => 'nullable|integer|min:1',
            'price'           => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        try {
            $addOn = AddOn::findOrFail($id);

            // Check if this exact combination already exists (excluding current record)
            $existingAddOn = AddOn::where('footage_size_id', $request->footage_size_id)
                ->where('service_item_id', $request->service_item_id)
                ->where('quantity', $request->quantity)
                ->where('price', $request->price)
                ->where('id', '!=', $id)
                ->first();

            if ($existingAddOn) {
                return response()->json([
                    'success' => false,
                    'message' => 'A service with this exact combination (footage size, service item, quantity, and price) already exists.',
                ]);
            }

            $addOn->update([
                'footage_size_id' => $request->footage_size_id,
                'service_item_id' => $request->service_item_id,
                'quantity'        => $request->quantity,
                'price'           => $request->price,
            ]);

            return response()->json(['success' => true, 'message' => 'Add-Ons Updated Successfully.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while updating: ' . $e->getMessage()]);
        }
    }

    public function status(int $id) {
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

    public function destroy(int $id) {
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
