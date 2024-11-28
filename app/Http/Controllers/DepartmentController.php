<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class DepartmentController extends Controller
{

    // Get Departments (Datatables)
    public function getDepartments(Request $request)
    {
        if ($request->ajax()) {

            $data = Department::orderByDesc('id')->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('dep_tags', function ($row) {
                    $tags = '';
                    if (!empty($row->dep_tags)) {
                        $tagList = explode(',', $row->dep_tags);
                        $tags = '<span class="badge badge-secondary">' . $tagList[0] ;
                        if(count($tagList) - 1 > 0){
                            $tags .= '<span class="badge badge-circle badge-light-warning ms-2 cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $row->dep_tags . '">+' . count($tagList) - 1 . '</span>';
                        }
                        $tags .= '</span>';
                    }
                    return $tags;
                })
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
                ->rawColumns(['dep_tags', 'action', 'status'])
                ->make(true);
        }
    }

    // Add or Update Department
    public function save_department(Request $request)
    {
        $response = array();

        if ($request->dep_id != null && $request->dep_id != "") {

            $request->validate([
                'dep_id' => [
                    'required',
                    'integer',
                    Rule::exists('departments', 'id')->where(function ($query) {
                        return $query->whereNull('deleted_at');
                    })
                ],
                'dep_name' => [
                    'required',
                    'min:3',
                    Rule::unique('departments')->ignore($request->dep_id)->where(function ($query) {
                        return $query->whereNull('deleted_at');
                    })
                ],
            ], [
                'dep_name.required' => 'Department Name must be provided',
                'dep_name.min' => 'Department Name must be at least 3 characters',
                'dep_name.unique' => 'Department Name already exists',
            ]);

            $update_data = array(
                'dep_name' => Str::title($request->dep_name),
                'dep_tags' => $request->dep_tags,
            );

            $save_department = Department::where('id', '=', $request->dep_id)->update($update_data);

            if ($save_department) {
                $response = array(
                    'success' => true,
                    'message' => 'Department Updated',
                );
            } else {
                $response = array(
                    'success' => false,
                    'message' => 'Something went wrong while updating department',
                );
            }
        } else {

            $request->validate([
                'dep_name' => [
                    'required',
                    'min:3',
                    Rule::unique('departments', 'dep_name')->where(function ($query) {
                        return $query->whereNull('deleted_at');
                    })
                ],
            ], [
                'dep_name.required' => 'Department Name must be provided',
                'dep_name.min' => 'Department Name must be at least 3 characters',
                'dep_name.unique' => 'Department Name already exists',
            ]);


            $insert_data = array(
                'dep_name' => Str::title($request->dep_name),
                'dep_tags' => $request->dep_tags,
                'status' => 1,
            );

            $save_department = Department::create($insert_data);

            if ($save_department) {
                $response = array(
                    'success' => true,
                    'message' => 'Department Added',
                );
            } else {
                $response = array(
                    'success' => false,
                    'message' => 'Something went wrong while adding department',
                );
            }
        }

        return response()->json($response);
    }

    // Edit Department
    public function edit_department(Request $request)
    {
        $response = array();

        $request->validate([
            'dep_id' => ['required', 'integer', Rule::exists('departments', 'id')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })]
        ]);

        $department = Department::find($request->dep_id);

        if ($department) {
            $response = array('success' => true, 'message' => 'Department Found', 'data' => $department);
        } else {
            $response = array('success' => false, 'message' => 'Department Not Found', 'data' => []);
        }

        return response()->json($response);
    }

    // Delete Department
    public function delete_department(Request $request)
    {
        $response = array();

        $request->validate([
            'dep_id' => ['required', 'integer', Rule::exists('departments', 'id')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })]
        ]);

        DB::enableQueryLog();

        $delete_department = Department::where('id', $request->dep_id)->delete();

        if ($delete_department) {
            $response = array('success' => true, 'message' => 'Department Deleted');
        } else {
            $response = array('success' => false, 'message' => 'Something went wrong');
        }

        return response()->json($response);
    }

    // Change Status of Department
    public function change_status(Request $request)
    {
        $response = array();

        $request->validate([
            'dep_id' => ['required', 'integer', Rule::exists('departments', 'id')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            'status' => ['required', 'integer'],
        ]);

        $change_status = Department::where('id', $request->dep_id)->update(['status' => $request->status]);

        if ($change_status) {
            $response = array('success' => true, 'message' => 'Department Status Updated');
        } else {
            $response = array('success' => false, 'message' => 'Something went wrong');
        }

        return response()->json($response);
    }
}
