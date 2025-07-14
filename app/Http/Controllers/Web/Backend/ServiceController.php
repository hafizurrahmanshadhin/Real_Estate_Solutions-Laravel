<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\FootageSize;
use App\Models\ServiceItem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ServiceController extends Controller {
    public function index(Request $request) {
        try {
            if ($request->ajax()) {
                $data = FootageSize::latest()->get();
                return DataTables::of($data)
                    ->addIndexColumn()
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
                                <a href="javascript:void(0);" class="link-primary text-decoration-none edit-size" data-id="' . $data->id . '" title="Edit">
                                    <i class="ri-pencil-line" style="font-size:24px;"></i>
                                </a>

                                <a href="javascript:void(0);" onclick="showDeleteConfirm(' . $data->id . ')" class="link-danger text-decoration-none" title="Delete">
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

    public function storeSquareFootageSize(Request $request) {
        $validator = Validator::make($request->all(), [
            'size' => 'required|string|max:15|unique:footage_sizes,size',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        try {
            FootageSize::create([
                'size' => $request->input('size'),
            ]);

            return response()->json(['success' => true, 'message' => 'Data Created Successfully.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function updateSquareFootageSize(Request $request, int $id) {
        $validator = Validator::make($request->all(), [
            'size' => 'required|string|max:255|unique:footage_sizes,size,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        $data = FootageSize::findOrFail($id);

        try {
            $data->update([
                'size' => $request->input('size'),
            ]);

            return response()->json(['success' => true, 'message' => 'Data Updated Successfully.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while updating the service area: ' . $e->getMessage()]);
        }
    }

    public function statusSquareFootageSize(int $id) {
        try {
            $data = FootageSize::findOrFail($id);

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

    public function destroySquareFootageSize(int $id) {
        try {
            $data = FootageSize::findOrFail($id);
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

    public function indexItem(Request $request) {
        try {
            if ($request->ajax()) {
                $data = ServiceItem::latest()->get();
                return DataTables::of($data)
                    ->addIndexColumn()
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
                                <a href="javascript:void(0);" class="link-primary text-decoration-none edit-item" data-id="' . $data->id . '" title="Edit">
                                    <i class="ri-pencil-line" style="font-size:24px;"></i>
                                </a>

                                <a href="javascript:void(0);" onclick="showDeleteConfirm(' . $data->id . ')" class="link-danger text-decoration-none" title="Delete">
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

    public function storeServiceItem(Request $request) {
        $validator = Validator::make($request->all(), [
            'service_name' => 'required|string|max:100|unique:service_items,service_name',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        try {
            ServiceItem::create([
                'service_name' => $request->input('service_name'),
            ]);

            return response()->json(['success' => true, 'message' => 'Data Created Successfully.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function updateServiceItem(Request $request, int $id) {
        $validator = Validator::make($request->all(), [
            'service_name' => 'required|string|max:100|unique:service_items,service_name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        $data = ServiceItem::findOrFail($id);

        try {
            $data->update([
                'service_name' => $request->input('service_name'),
            ]);

            return response()->json(['success' => true, 'message' => 'Data Updated Successfully.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while updating the service area: ' . $e->getMessage()]);
        }
    }

    public function statusServiceItem(int $id) {
        try {
            $data = ServiceItem::findOrFail($id);

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

    public function destroyServiceItem(int $id) {
        try {
            $data = ServiceItem::findOrFail($id);
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
