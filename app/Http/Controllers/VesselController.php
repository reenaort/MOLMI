<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vessels;
use App\Models\SubCategory;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class VesselController extends Controller
{

    // Get Vessels (Datatables)
    public function getVessels(Request $request)
    {
        if ($request->ajax()) {

            $data = Vessels::select('categories.cat_name', 'sub_categories.subcat_name', 'vessels.*')
                ->leftJoin('categories', 'vessels.cat_id', '=', 'categories.id')
                ->leftJoin('sub_categories', 'vessels.subcat_id', '=', 'sub_categories.id')
                ->orderByDesc('vessels.id')
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    $statusBox = '';
                    if ($row->status == 1) {
                        $statusBox = '<label class="form-check form-switch form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" checked="checked" onchange="return change_status(' . $row->id . ',2)">
                                        </label>';
                    } else {
                        $statusBox = '<label class="form-check form-switch form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" onchange="return change_status(' . $row->id . ',1)">
                                        </label>';
                    }
                    return $statusBox;
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '
                        <div>
                            <a href="javascript:void(0)" onclick="return edit_data(' . $row->id . ');"
                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 mb-2">
                                <i class="ki-duotone ki-pencil fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </a>
                            <a href="javascript:void(0)" onclick="return delete_data(' . $row->id . ');"
                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 mb-2">
                                <i class="ki-duotone ki-trash fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                </i>
                            </a>
                        </div>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
    }

    // Add or Update Vessel
    public function save_vessel(Request $request)
    {
        $response = array();

        if ($request->vessel_id != null && $request->vessel_id != "") {

            $request->validate([
                'vessel_id' => [
                    'required',
                    'integer',
                    Rule::exists('vessels', 'id')->where(function ($query) {
                        return $query->whereNull('deleted_at');
                    })
                ],
                'cat_id' => ['required', Rule::exists('categories', 'id')],
                'subcat_id' => ['required', Rule::exists('sub_categories', 'id')],
                'vessel_name' => [
                    'required',
                    'min:3',
                    Rule::unique('vessels')->ignore($request->vessel_id)->where(function ($query) {
                        return $query->whereNull('deleted_at');
                    })
                ],
            ], [
                'cat_id.required' => 'Please Select a Category',
                'subcat_id.required' => 'Please Select a Sub Category',
                'vessel_name.required' => 'Vessel Name must be provided',
                'vessel_name.min' => 'Vessel Name must be at least 3 characters',
                'vessel_name.unique' => 'Vessel Name already exists',
            ]);

            $update_data = array(
                'cat_id' => $request->cat_id,
                'subcat_id' => $request->subcat_id,
                'vessel_name' => Str::title($request->vessel_name),
            );

            $save_vessel = Vessels::where('id', '=', $request->vessel_id)->update($update_data);

            if ($save_vessel) {
                $response = array(
                    'success' => true,
                    'message' => 'Vessel Updated',
                );
            } else {
                $response = array(
                    'success' => false,
                    'message' => 'Something went wrong while updating vessel',
                );
            }
        } else {

            $request->validate([
                'cat_id' => ['required', Rule::exists('categories', 'id')],
                'subcat_id' => ['required', Rule::exists('sub_categories', 'id')],
                'vessel_name' => [
                    'required',
                    'min:3',
                    Rule::unique('vessels')->ignore($request->vessel_id)->where(function ($query) {
                        return $query->whereNull('deleted_at');
                    })
                ],
            ], [
                'cat_id.required' => 'Please Select a Category',
                'subcat_id.required' => 'Please Select a Sub Category',
                'vessel_name.required' => 'Vessel Name must be provided',
                'vessel_name.min' => 'Vessel Name must be at least 3 characters',
                'vessel_name.unique' => 'Vessel Name already exists',
            ]);


            $insert_data = array(
                'cat_id' => $request->cat_id,
                'subcat_id' => $request->subcat_id,
                'vessel_name' => Str::title($request->vessel_name),
                'status' => 1,
            );

            $save_vessel = Vessels::create($insert_data);

            if ($save_vessel) {
                $response = array(
                    'success' => true,
                    'message' => 'Vessel Added',
                );
            } else {
                $response = array(
                    'success' => false,
                    'message' => 'Something went wrong while adding vessel',
                );
            }
        }

        return response()->json($response);
    }

    //Edit Vessel
    public function edit_vessel(Request $request)
    {
        $response = array();

        $request->validate([
            'vessel_id' => ['required', 'integer', Rule::exists('vessels', 'id')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })]
        ]);

        $vesseldata = Vessels::find($request->vessel_id);

        if ($vesseldata) {
            $response = array('success' => true, 'message' => 'SubCategory Found', 'data' => $vesseldata);
        } else {
            $response = array('success' => false, 'message' => 'SubCategory Not Found', 'data' => []);
        }

        return response()->json($response);
    }

    // Change Status of Vessel
    public function change_status(Request $request)
    {
        $response = array();

        $request->validate([
            'vessel_id' => ['required', 'integer', Rule::exists('vessels', 'id')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            'status' => ['required', 'integer'],
        ]);

        $change_status = Vessels::where('id', $request->vessel_id)->update(['status' => $request->status]);

        if ($change_status) {
            $response = array('success' => true, 'message' => 'Vessel Status Updated');
        } else {
            $response = array('success' => false, 'message' => 'Something went wrong');
        }

        return response()->json($response);
    }

    // Delete Vessels
    public function delete_vessel(Request $request)
    {
        $response = array();

        $request->validate([
            'vessel_id' => ['required', 'integer', Rule::exists('vessels', 'id')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })]
        ]);

        $delete_vessel = Vessels::where('id', $request->vessel_id)->delete();

        if ($delete_vessel) {
            $response = array('success' => true, 'message' => 'Vessel Deleted');
        } else {
            $response = array('success' => false, 'message' => 'Something went wrong');
        }

        return response()->json($response);
    }
}
