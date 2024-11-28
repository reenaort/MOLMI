<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CourseType;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CoursetypeController extends Controller
{
    // Get Course Types (Datatables)
    public function getCourseTypes(Request $request)
    {
        if ($request->ajax()) {

            $data = CourseType::orderByDesc('id')->get();

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

    // Add or Update CourseType
    public function save_coursetype(Request $request)
    {
        $response = array();

        if ($request->type_id != null && $request->type_id != "") {

            $request->validate([
                'type_id' => [
                    'required',
                    'integer',
                    Rule::exists('course_types', 'id')->where(function ($query) {
                        return $query->whereNull('deleted_at');
                    })
                ],
                'type_name' => [
                    'required',
                    'min:3',
                    Rule::unique('course_types')->ignore($request->type_id)->where(function ($query) {
                        return $query->whereNull('deleted_at');
                    })
                ],
            ], [
                'type_name.required' => 'Course Type must be provided',
                'type_name.min' => 'Course Type must be at least 3 characters',
                'type_name.unique' => 'Course Type already exists',
            ]);

            $update_data = array(
                'type_name' => Str::title($request->type_name),
            );

            $save_CourseType = CourseType::where('id', '=', $request->type_id)->update($update_data);

            if ($save_CourseType) {
                $response = array(
                    'success' => true,
                    'message' => 'CourseType Updated',
                );
            } else {
                $response = array(
                    'success' => false,
                    'message' => 'Something went wrong while updating CourseType',
                );
            }
        } else {

            $request->validate([
                'type_name' => [
                    'required',
                    'min:3',
                    Rule::unique('course_types', 'type_name')->where(function ($query) {
                        return $query->whereNull('deleted_at');
                    })
                ],
            ], [
                'type_name.required' => 'Course Type must be provided',
                'type_name.min' => 'Course Type must be at least 3 characters',
                'type_name.unique' => 'Course Type already exists',
            ]);


            $insert_data = array(
                'type_name' => Str::title($request->type_name),
                'status' => 1,
            );

            $save_CourseType = CourseType::create($insert_data);

            if ($save_CourseType) {
                $response = array(
                    'success' => true,
                    'message' => 'CourseType Added',
                );
            } else {
                $response = array(
                    'success' => false,
                    'message' => 'Something went wrong while adding CourseType',
                );
            }
        }

        return response()->json($response);
    }

    // Edit CourseType
    public function edit_CourseType(Request $request)
    {
        $response = array();

        $request->validate([
            'type_id' => ['required', 'integer', Rule::exists('course_types', 'id')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })]
        ]);

        $CourseType = CourseType::find($request->type_id);

        if ($CourseType) {
            $response = array('success' => true, 'message' => 'Course Type Found', 'data' => $CourseType);
        } else {
            $response = array('success' => false, 'message' => 'Course Type Not Found', 'data' => []);
        }

        return response()->json($response);
    }

    // Delete CourseType
    public function delete_CourseType(Request $request)
    {
        $response = array();

        $request->validate([
            'type_id' => ['required', 'integer', Rule::exists('course_types', 'id')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })]
        ]);

        $delete_CourseType = CourseType::where('id', $request->type_id)->delete();

        if ($delete_CourseType) {
            $response = array('success' => true, 'message' => 'Course Type Deleted');
        } else {
            $response = array('success' => false, 'message' => 'Something went wrong');
        }

        return response()->json($response);
    }

    // Change Status of CourseType
    public function change_status(Request $request)
    {
        $response = array();

        $request->validate([
            'type_id' => ['required', 'integer', Rule::exists('course_types', 'id')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            'status' => ['required', 'integer'],
        ]);

        $change_status = CourseType::where('id', $request->type_id)->update(['status' => $request->status]);

        if ($change_status) {
            $response = array('success' => true, 'message' => 'Course Type Status Updated');
        } else {
            $response = array('success' => false, 'message' => 'Something went wrong');
        }

        return response()->json($response);
    }
}
