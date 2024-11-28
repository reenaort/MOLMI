<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrainingCenter;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class TrainCenterController extends Controller
{
    // Get Training Centers (DataTables)
    public function getTrainingCenters(Request $request)
    {
        if ($request->ajax()) {

            $data = TrainingCenter::orderByDesc('id')->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('center_type', function ($row) {
                    $statusBox = '';
                    if ($row->center_type == 1) {
                        $statusBox = 'MOLMI';
                    } else {
                        $statusBox = 'External';
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

    // Save Training Center
    public function save_center(Request $request)
    {
        $response = array();

        if ($request->tc_id != "" && $request->tc_id != null) {
            $request->validate([
                'tc_id' => [
                    'required',
                    'integer',
                    Rule::exists('training_centers', 'id')->where(function ($query) {
                        return $query->whereNull('deleted_at');
                    })
                ],
                'center_name' => [
                    'required',
                    'min:3',
                    Rule::unique('training_centers')->ignore($request->tc_id)->where(function ($query) {
                        return $query->whereNull('deleted_at');
                    })
                ],
            ], [
                'center_name.required' => 'Center Name must be provided',
                'center_name.min' => 'Center Name must be at least 3 characters',
                'center_name.unique' => 'Center Name already exists',
            ]);

            $update_data = array(
                //'center_name' => Str::title($request->center_name),
                'center_name' => $request->center_name,
                'center_location' => $request->center_location,
                'center_type' => !empty($request->center_type) ? $request->center_type : 1,
            );

            $save_category = TrainingCenter::where('id', '=', $request->tc_id)->update($update_data);

            if ($save_category) {
                $response = array(
                    'success' => true,
                    'message' => 'Center Updated',
                );
            } else {
                $response = array(
                    'success' => false,
                    'message' => 'Something went wrong while updating center',
                );
            }
        } else {
            $request->validate([
                'center_name' => [
                    'required',
                    'min:3',
                    Rule::unique('training_centers')->where(function ($query) {
                        return $query->whereNull('deleted_at');
                    })
                ],
            ], [
                'center_name.required' => 'Center Name must be provided',
                'center_name.min' => 'Center Name must be at least 3 characters',
                'center_name.unique' => 'Center Name already exists',
            ]);

            $insert_data = array(
                //'center_name' => Str::title($request->center_name),
                'center_name' => $request->center_name,
                'center_location' => $request->center_location,
                'center_type' => !empty($request->center_type) ? $request->center_type : 1,
            );

            $save_category = TrainingCenter::create($insert_data);

            if ($save_category) {
                $response = array(
                    'success' => true,
                    'message' => 'Center Added',
                );
            } else {
                $response = array(
                    'success' => false,
                    'message' => 'Something went wrong while adding center',
                );
            }
        }

        return response()->json($response);
    }

    // Edit Training Center
    public function edit_center(Request $request)
    {

        $response = array();

        $request->validate([
            'tc_id' => ['required', 'integer', Rule::exists('training_centers', 'id')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })]
        ]);

        $center = TrainingCenter::find($request->tc_id);

        if ($center) {
            $response = array('success' => true, 'message' => 'Training Center Found', 'data' => $center);
        } else {
            $response = array('success' => false, 'message' => 'Center Not Found', 'data' => []);
        }

        return response()->json($response);
    }

    // Delete Training Center
    public function delete_center(Request $request)
    {
        $response = array();

        $request->validate([
            'tc_id' => ['required', 'integer', Rule::exists('training_centers', 'id')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })]
        ]);

        DB::enableQueryLog();

        $delete_center = TrainingCenter::where('id', $request->tc_id)->delete();

        if ($delete_center) {
            $response = array('success' => true, 'message' => 'Training Center Removed');
        } else {
            $response = array('success' => false, 'message' => 'Something went wrong');
        }

        return response()->json($response);
    }
}
