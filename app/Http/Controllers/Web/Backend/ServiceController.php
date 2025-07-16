<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\FootageSize;
use App\Models\Package;
use App\Models\Service;
use App\Models\ServiceItem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ServiceController extends Controller {
    public function index(Request $request) {
        try {
            if ($request->ajax()) {
                $services = Service::with(['package', 'footageSize', 'serviceItems'])->select('services.*')->latest()->get();

                return DataTables::of($services)
                    ->addIndexColumn()
                    ->addColumn('package_title', function ($data) {
                        return $data->package ? $data->package->title : 'N/A';
                    })
                    ->addColumn('footage_size', function ($data) {
                        return $data->footageSize ? $data->footageSize->size : 'N/A';
                    })
                    ->addColumn('service_items_display', function ($data) {
                        if ($data->serviceItems && $data->serviceItems->count() > 0) {
                            $items = $data->serviceItems->map(function ($item) {
                                return $item->service_name . ' (' . $item->pivot->quantity . ')';
                            })->toArray();
                            return '<span class="text-muted small">' . implode(', ', $items) . '</span>';
                        }
                        return '<span class="text-muted">N/A</span>';
                    })
                    ->addColumn('total_items_count', function ($data) {
                        $count = $data->serviceItems ? $data->serviceItems->count() : 0;
                        return '<span class="badge bg-info">' . $count . ' item(s)</span>';
                    })
                    ->addColumn('total_quantity', function ($data) {
                        if ($data->serviceItems && $data->serviceItems->count() > 0) {
                            $totalQty = $data->serviceItems->sum('pivot.quantity');
                            return '<span class="badge bg-secondary">' . $totalQty . '</span>';
                        }
                        return '<span class="badge bg-secondary">0</span>';
                    })
                    ->addColumn('formatted_price', function ($data) {
                        return '<span class="fw-bold text-success">$' . number_format($data->price, 2) . '</span>';
                    })
                    ->addColumn('package_footage_group', function ($data) {
                        $packageName = $data->package ? $data->package->title : 'N/A';
                        $footageSize = $data->footageSize ? $data->footageSize->size : 'N/A';
                        return '<span class="fw-medium">' . $packageName . ' - ' . $footageSize . '</span>';
                    })
                    ->addColumn('status', function ($data) {
                        return '
                        <div class="d-flex justify-content-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="SwitchCheck' . $data->id . '" ' . ($data->status == 'active' ? 'checked' : '') . ' onclick="showServiceStatusChangeAlert(' . $data->id . ')">
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
                    ->rawColumns(['package_footage_group', 'service_items_display', 'total_items_count', 'total_quantity', 'formatted_price', 'status', 'action'])
                    ->make(true);
            }
            return view('backend.layouts.services.index');
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred: ' . $e->getMessage(),
                ], 500);
            }
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
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
            return response()->json([
                'success' => false,
                'message' => 'Failed to load form data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request) {
        $serviceItems   = $request->input('service_items', []);
        $serviceItemIds = array_column($serviceItems, 'service_item_id');

        if (count($serviceItemIds) !== count(array_unique($serviceItemIds))) {
            return response()->json([
                'success' => false,
                'message' => 'Duplicate service items are not allowed. Each service item can only be selected once.',
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'package_id'                      => 'required|integer|exists:packages,id',
            'footage_size_id'                 => 'required|integer|exists:footage_sizes,id',
            'price'                           => 'required|numeric|min:0|max:999999.99',
            'service_items'                   => 'required|array|min:1',
            'service_items.*.service_item_id' => 'required|integer|exists:service_items,id',
            'service_items.*.quantity'        => 'required|integer|min:1|max:1000',
        ], [
            'package_id.required'                      => 'Please select a package.',
            'package_id.exists'                        => 'Selected package does not exist.',
            'footage_size_id.required'                 => 'Please select a footage size.',
            'footage_size_id.exists'                   => 'Selected footage size does not exist.',
            'price.required'                           => 'Please enter the price.',
            'price.min'                                => 'Price cannot be negative.',
            'price.max'                                => 'Price cannot exceed $999,999.99.',
            'service_items.required'                   => 'Please add at least one service item.',
            'service_items.min'                        => 'Please add at least one service item.',
            'service_items.*.service_item_id.required' => 'Please select a service item.',
            'service_items.*.service_item_id.exists'   => 'Selected service item does not exist.',
            'service_items.*.quantity.required'        => 'Quantity is required for each service item.',
            'service_items.*.quantity.min'             => 'Quantity must be at least 1.',
            'service_items.*.quantity.max'             => 'Quantity cannot exceed 1000.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Create the main service record
            $service = Service::create([
                'package_id'      => $request->package_id,
                'footage_size_id' => $request->footage_size_id,
                'price'           => $request->price,
                'status'          => 'active',
            ]);

            // Attach service items with quantities using pivot table
            $serviceItemsData = [];
            foreach ($request->service_items as $serviceItem) {
                $serviceItemsData[$serviceItem['service_item_id']] = [
                    'quantity' => $serviceItem['quantity'],
                ];
            }

            $service->serviceItems()->attach($serviceItemsData);

            DB::commit();

            $itemCount = count($request->service_items);
            $message   = 'Service created successfully with ' . $itemCount . ' service item(s).';

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create service: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show(int $id) {
        try {
            $service = Service::with(['package', 'footageSize', 'serviceItems'])->findOrFail($id);

            $serviceDetails = $service->serviceItems->map(function ($item) {
                return [
                    'id'                => $item->id,
                    'service_item_name' => $item->service_name,
                    'quantity'          => $item->pivot->quantity,
                ];
            });

            return response()->json([
                'success' => true,
                'data'    => [
                    'id'              => $service->id,
                    'package_id'      => $service->package_id,
                    'package_title'   => $service->package->title,
                    'footage_size_id' => $service->footage_size_id,
                    'footage_size'    => $service->footageSize->size,
                    'price'           => $service->price,
                    'status'          => $service->status,
                    'service_items'   => $serviceDetails,
                ],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load service details: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, int $id) {
        $serviceItems   = $request->input('service_items', []);
        $serviceItemIds = array_column($serviceItems, 'service_item_id');

        if (count($serviceItemIds) !== count(array_unique($serviceItemIds))) {
            return response()->json([
                'success' => false,
                'message' => 'Duplicate service items are not allowed. Each service item can only be selected once.',
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'package_id'                      => 'required|integer|exists:packages,id',
            'footage_size_id'                 => 'required|integer|exists:footage_sizes,id',
            'price'                           => 'required|numeric|min:0|max:999999.99',
            'service_items'                   => 'required|array|min:1',
            'service_items.*.service_item_id' => 'required|integer|exists:service_items,id',
            'service_items.*.quantity'        => 'required|integer|min:1|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            $service = Service::findOrFail($id);

            // Update the main service record
            $service->update([
                'package_id'      => $request->package_id,
                'footage_size_id' => $request->footage_size_id,
                'price'           => $request->price,
            ]);

            // Detach existing service items and attach new ones
            $service->serviceItems()->detach();

            $serviceItemsData = [];
            foreach ($request->service_items as $serviceItem) {
                $serviceItemsData[$serviceItem['service_item_id']] = [
                    'quantity' => $serviceItem['quantity'],
                ];
            }

            $service->serviceItems()->attach($serviceItemsData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Service updated successfully.',
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update service: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function status(int $id) {
        try {
            $service = Service::findOrFail($id);

            $service->status = ($service->status == 'active') ? 'inactive' : 'active';
            $service->save();

            return response()->json([
                'success' => true,
                'message' => 'Service status updated successfully.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(int $id) {
        try {
            $service = Service::findOrFail($id);

            // Detach service items first
            $service->serviceItems()->detach();

            // Delete the service
            $service->delete();

            return response()->json([
                't-success' => true,
                'message'   => 'Service deleted successfully.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                't-success' => false,
                'message'   => 'Failed to delete service: ' . $e->getMessage(),
            ], 500);
        }
    }
}
