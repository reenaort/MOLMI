<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // Get Categories (Datatables)
    public function getCategories(Request $request)
    {
        if ($request->ajax()) {

            $data = Category::orderByDesc('id')->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('cat_tags', function ($row) {
                    $tags = '';
                    if (!empty($row->cat_tags)) {
                        $tagList = explode(',', $row->cat_tags);
                        $tags = '<span class="badge badge-secondary">' . $tagList[0] ;
                        if(count($tagList) - 1 > 0){
                            $tags .= '<span class="badge badge-circle badge-light-warning ms-2 cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $row->cat_tags . '">+' . count($tagList) - 1 . '</span>';
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
                ->rawColumns(['cat_tags', 'action', 'status'])
                ->make(true);
        }
    }

    // Add or Update Category
    public function save_category(Request $request)
    {
        $response = array();

        if ($request->cat_id != null && $request->cat_id != "") {

            $request->validate([
                'cat_id' => [
                    'required',
                    'integer',
                    Rule::exists('categories', 'id')->where(function ($query) {
                        return $query->whereNull('deleted_at');
                    })
                ],
                'cat_name' => [
                    'required',
                    'min:3',
                    Rule::unique('categories')->ignore($request->cat_id)->where(function ($query) {
                        return $query->whereNull('deleted_at');
                    })
                ],
            ], [
                'cat_name.required' => 'Category Name must be provided',
                'cat_name.min' => 'Category Name must be at least 3 characters',
                'cat_name.unique' => 'Category Name already exists',
            ]);

            $update_data = array(
                'cat_name' => Str::title($request->cat_name),
                'cat_tags' => $request->cat_tags,
            );

            $save_category = Category::where('id', '=', $request->cat_id)->update($update_data);

            if ($save_category) {
                $response = array(
                    'success' => true,
                    'message' => 'Category Updated',
                );
            } else {
                $response = array(
                    'success' => false,
                    'message' => 'Something went wrong while updating category',
                );
            }
        } else {

            $request->validate([
                'cat_name' => [
                    'required',
                    'min:3',
                    Rule::unique('categories', 'cat_name')->where(function ($query) {
                        return $query->whereNull('deleted_at');
                    })
                ],
            ], [
                'cat_name.required' => 'Category Name must be provided',
                'cat_name.min' => 'Category Name must be at least 3 characters',
                'cat_name.unique' => 'Category Name already exists',
            ]);


            $insert_data = array(
                'cat_name' => Str::title($request->cat_name),
                'cat_tags' => $request->cat_tags,
                'status' => 1,
            );

            $save_category = Category::create($insert_data);

            if ($save_category) {
                $response = array(
                    'success' => true,
                    'message' => 'Category Added',
                );
            } else {
                $response = array(
                    'success' => false,
                    'message' => 'Something went wrong while adding category',
                );
            }
        }

        return response()->json($response);
    }

    // Edit Category
    public function edit_category(Request $request)
    {
        $response = array();

        $request->validate([
            'cat_id' => ['required', 'integer', Rule::exists('categories', 'id')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })]
        ]);

        $category = Category::find($request->cat_id);

        if ($category) {
            $response = array('success' => true, 'message' => 'Category Found', 'data' => $category);
        } else {
            $response = array('success' => false, 'message' => 'Category Not Found', 'data' => []);
        }

        return response()->json($response);
    }

    // Delete Category
    public function delete_category(Request $request)
    {
        $response = array();

        $request->validate([
            'cat_id' => ['required', 'integer', Rule::exists('categories', 'id')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })]
        ]);

        DB::enableQueryLog();

        $delete_category = Category::where('id', $request->cat_id)->delete();

        if ($delete_category) {
            $response = array('success' => true, 'message' => 'Category Deleted');
        } else {
            $response = array('success' => false, 'message' => 'Something went wrong');
        }

        return response()->json($response);
    }

    // Change Status of Category
    public function change_status(Request $request)
    {
        $response = array();

        $request->validate([
            'cat_id' => ['required', 'integer', Rule::exists('categories', 'id')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            'status' => ['required', 'integer'],
        ]);

        $change_status = Category::where('id', $request->cat_id)->update(['status' => $request->status]);

        if ($change_status) {
            $response = array('success' => true, 'message' => 'Category Status Updated');
        } else {
            $response = array('success' => false, 'message' => 'Something went wrong');
        }

        return response()->json($response);
    }
}
