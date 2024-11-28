<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubCategory;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    // Get SubCategories (Datatables)
    public function getSubCategories(Request $request)
    {
        if ($request->ajax()) {

            $data = SubCategory::select('categories.cat_name', 'sub_categories.*')
                ->leftJoin('categories', 'sub_categories.cat_id', '=', 'categories.id')
                ->orderByDesc('sub_categories.id')
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('subcat_tags', function ($row) {
                    $tags = '';
                    if (!empty($row->subcat_tags)) {
                        $tagList = explode(',', $row->subcat_tags);
                        $tags = '<span class="badge badge-secondary">' . $tagList[0];
                        if (count($tagList) - 1 > 0) {
                            $tags .= '<span class="badge badge-circle badge-light-warning ms-2 cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $row->subcat_tags . '">+' . count($tagList) - 1 . '</span>';
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
                ->rawColumns(['subcat_tags', 'action', 'status'])
                ->make(true);
        }
    }

    // Add or Update SubCategory
    public function save_subcategory(Request $request)
    {
        $response = array();

        if ($request->subcat_id != null && $request->subcat_id != "") {

            $request->validate([
                'subcat_id' => [
                    'required',
                    'integer',
                    Rule::exists('sub_categories', 'id')->where(function ($query) {
                        return $query->whereNull('deleted_at');
                    })
                ],
                'cat_id' => ['required', Rule::exists('categories', 'id')],
                'subcat_name' => [
                    'required',
                    'min:3',
                    Rule::unique('sub_categories')->ignore($request->cat_id)->where(function ($query) {
                        return $query->whereNull('deleted_at');
                    })
                ],
            ], [
                'cat_id.required' => 'Please Select a Category',
                'subcat_name.required' => 'SubCategory Name must be provided',
                'subcat_name.min' => 'SubCategory Name must be at least 3 characters',
                'subcat_name.unique' => 'SubCategory Name already exists',
            ]);

            $update_data = array(
                'cat_id' => $request->cat_id,
                'subcat_name' => Str::title($request->subcat_name),
                'subcat_tags' => $request->subcat_tags,
            );

            $save_subcategory = SubCategory::where('id', '=', $request->subcat_id)->update($update_data);

            if ($save_subcategory) {
                $response = array(
                    'success' => true,
                    'message' => 'SubCategory Updated',
                );
            } else {
                $response = array(
                    'success' => false,
                    'message' => 'Something went wrong while updating subcategory',
                );
            }
        } else {

            $request->validate([
                'cat_id' => ['required', Rule::exists('categories', 'id')],
                'subcat_name' => [
                    'required',
                    'min:3',
                    Rule::unique('sub_categories', 'subcat_name')->where(function ($query) {
                        return $query->whereNull('deleted_at');
                    })
                ],
            ], [
                'cat_id.required' => 'Please Select a Category',
                'subcat_name.required' => 'SubCategory Name must be provided',
                'subcat_name.min' => 'SubCategory Name must be at least 3 characters',
                'subcat_name.unique' => 'SubCategory Name already exists',
            ]);


            $insert_data = array(
                'cat_id' => $request->cat_id,
                'subcat_name' => Str::title($request->subcat_name),
                'subcat_tags' => $request->subcat_tags,
                'status' => 1,
            );

            $save_subcategory = SubCategory::create($insert_data);

            if ($save_subcategory) {
                $response = array(
                    'success' => true,
                    'message' => 'SubCategory Added',
                );
            } else {
                $response = array(
                    'success' => false,
                    'message' => 'Something went wrong while adding subcategory',
                );
            }
        }

        return response()->json($response);
    }

    // Edit SubCategory
    public function edit_subcategory(Request $request)
    {
        $response = array();

        $request->validate([
            'subcat_id' => ['required', 'integer', Rule::exists('sub_categories', 'id')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })]
        ]);

        $subcategory = SubCategory::find($request->subcat_id);

        if ($subcategory) {
            $response = array('success' => true, 'message' => 'SubCategory Found', 'data' => $subcategory);
        } else {
            $response = array('success' => false, 'message' => 'SubCategory Not Found', 'data' => []);
        }

        return response()->json($response);
    }

    // Delete SubCategory
    public function delete_subcategory(Request $request)
    {
        $response = array();

        $request->validate([
            'subcat_id' => ['required', 'integer', Rule::exists('sub_categories', 'id')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })]
        ]);

        DB::enableQueryLog();

        $delete_subcategory = SubCategory::where('id', $request->subcat_id)->delete();

        if ($delete_subcategory) {
            $response = array('success' => true, 'message' => 'SubCategory Deleted');
        } else {
            $response = array('success' => false, 'message' => 'Something went wrong');
        }

        return response()->json($response);
    }

    // Change Status of SubCategory
    public function change_status(Request $request)
    {
        $response = array();

        $request->validate([
            'subcat_id' => ['required', 'integer', Rule::exists('sub_categories', 'id')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            'status' => ['required', 'integer'],
        ]);

        $change_status = SubCategory::where('id', $request->subcat_id)->update(['status' => $request->status]);

        if ($change_status) {
            $response = array('success' => true, 'message' => 'SubCategory Status Updated');
        } else {
            $response = array('success' => false, 'message' => 'Something went wrong');
        }

        return response()->json($response);
    }
}
