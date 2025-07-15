<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\FootageSize;
use App\Models\Package;
use App\Models\Service;
use App\Models\ServiceItem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ServiceController extends Controller {
    public function index(Request $request) {
        try {
            if ($request->ajax()) {
                $data = Service::with(['package', 'footageSize', 'serviceItem'])->latest()->get();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('package_title', function ($data) {
                        return $data->package ? $data->package->title : 'N/A';
                    })
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
                                <input class="form-check-input" type="checkbox" role="switch" id="SwitchCheck' . $data->id . '" ' . ($data->status == 'active' ? 'checked' : '') . ' onclick="showStatusChangeAlert(' . $data->id . ')">
                            </div>
                        </div>';
                    })
                    ->addColumn('action', function ($data) {
                        return '
                        <div class="d-flex justify-content-center hstack gap-3 fs-base">
                            <a href="javascript:void(0);" class="link-primary text-decoration-none edit-service" data-id="' . $data->id . '" title="Edit">
                                <i class="ri-pencil-line" style="font-size:24px;"></i>
                            </a>

                            <a href="javascript:void(0);" onclick="showServiceDeleteConfirm(' . $data->id . ')" class="link-danger text-decoration-none" title="Delete">
                                <i class="ri-delete-bin-5-line" style="font-size:24px;"></i>
                            </a>
                        </div>';
                    })
                    ->rawColumns(['package_title', 'footage_size', 'service_item_name', 'formatted_price', 'status', 'action'])
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
            $packages     = Package::where('status', 'active')->select('id', 'name', 'title')->get();
            $footageSizes = FootageSize::where('status', 'active')->select('id', 'size')->get();
            $serviceItems = ServiceItem::where('status', 'active')->select('id', 'service_name')->get();

            return response()->json([
                'success' => true,
                'data'    => [
                    'packages'      => $packages,
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
            'package_id'      => 'required|integer|exists:packages,id',
            'footage_size_id' => 'required|integer|exists:footage_sizes,id',
            'service_item_id' => 'required|integer|exists:service_items,id',
            'quantity'        => 'required|integer|min:1',
            'price'           => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        try {
            // Check if this exact combination already exists.
            $existingService = Service::where('package_id', $request->package_id)
                ->where('footage_size_id', $request->footage_size_id)
                ->where('service_item_id', $request->service_item_id)
                ->where('quantity', $request->quantity)
                ->where('price', $request->price)
                ->first();

            if ($existingService) {
                return response()->json([
                    'success' => false,
                    'message' => 'A service with this exact combination (package, footage size, service item, quantity, and price) already exists.',
                ]);
            }

            Service::create([
                'package_id'      => $request->package_id,
                'footage_size_id' => $request->footage_size_id,
                'service_item_id' => $request->service_item_id,
                'quantity'        => $request->quantity,
                'price'           => $request->price,
                'status'          => 'active',
            ]);

            return response()->json(['success' => true, 'message' => 'Service Created Successfully.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, int $id) {
        $validator = Validator::make($request->all(), [
            'package_id'      => 'required|integer|exists:packages,id',
            'footage_size_id' => 'required|integer|exists:footage_sizes,id',
            'service_item_id' => 'required|integer|exists:service_items,id',
            'quantity'        => 'required|integer|min:1',
            'price'           => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        try {
            $service = Service::findOrFail($id);

            // Check if this exact combination already exists (excluding current record)
            $existingService = Service::where('package_id', $request->package_id)
                ->where('footage_size_id', $request->footage_size_id)
                ->where('service_item_id', $request->service_item_id)
                ->where('quantity', $request->quantity)
                ->where('price', $request->price)
                ->where('id', '!=', $id)
                ->first();

            if ($existingService) {
                return response()->json([
                    'success' => false,
                    'message' => 'A service with this exact combination (package, footage size, service item, quantity, and price) already exists.',
                ]);
            }

            $service->update([
                'package_id'      => $request->package_id,
                'footage_size_id' => $request->footage_size_id,
                'service_item_id' => $request->service_item_id,
                'quantity'        => $request->quantity,
                'price'           => $request->price,
            ]);

            return response()->json(['success' => true, 'message' => 'Service Updated Successfully.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while updating: ' . $e->getMessage()]);
        }
    }

    public function status(int $id) {
        try {
            $data = Service::findOrFail($id);

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
            $data = Service::findOrFail($id);
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
