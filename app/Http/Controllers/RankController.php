<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rank;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class RankController extends Controller
{
    // Get Ranks (Datatables)
    public function getRanks(Request $request)
    {
        if ($request->ajax()) {

            $data = Rank::select('departments.dep_name', 'ranks.*')
                ->leftJoin('departments', 'ranks.dep_id', '=', 'departments.id')
                ->orderByDesc('ranks.id')
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('rank_tags', function ($row) {
                    $tags = '';
                    if (!empty($row->rank_tags)) {
                        $tagList = explode(',', $row->rank_tags);
                        $tags = '<span class="badge badge-secondary">' . $tagList[0];
                        if (count($tagList) - 1 > 0) {
                            $tags .= '<span class="badge badge-circle badge-light-warning ms-2 cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $row->rank_tags . '">+' . count($tagList) - 1 . '</span>';
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
                ->rawColumns(['rank_tags', 'action', 'status'])
                ->make(true);
        }
    }

    // Add or Update Rank
    public function save_rank(Request $request)
    {
        $response = array();

        if ($request->rank_id != null && $request->rank_id != "") {

            $request->validate([
                'rank_id' => [
                    'required',
                    'integer',
                    Rule::exists('ranks', 'id')->where(function ($query) {
                        return $query->whereNull('deleted_at');
                    })
                ],
                'dep_id' => ['required', Rule::exists('departments', 'id')],
                'rank_name' => [
                    'required',
                    'min:3',
                    Rule::unique('ranks')->ignore($request->dep_id)->where(function ($query) {
                        return $query->whereNull('deleted_at');
                    })
                ],
            ], [
                'dep_id.required' => 'Please Select a Department',
                'rank_name.required' => 'Rank Name must be provided',
                'rank_name.min' => 'Rank Name must be at least 3 characters',
                'rank_name.unique' => 'Rank Name already exists',
            ]);

            $update_data = array(
                'dep_id' => $request->dep_id,
                //'rank_name' => Str::title($request->rank_name),
                'rank_name' => $request->rank_name,
                'rank_tags' => $request->rank_tags,
            );

            $save_rank = Rank::where('id', '=', $request->rank_id)->update($update_data);

            if ($save_rank) {
                $response = array(
                    'success' => true,
                    'message' => 'Rank Updated',
                );
            } else {
                $response = array(
                    'success' => false,
                    'message' => 'Something went wrong while updating rank',
                );
            }
        } else {

            $request->validate([
                'dep_id' => ['required', Rule::exists('departments', 'id')],
                'rank_name' => [
                    'required',
                    'min:3',
                    Rule::unique('ranks', 'rank_name')->where(function ($query) {
                        return $query->whereNull('deleted_at');
                    })
                ],
            ], [
                'dep_id.required' => 'Please Select a Department',
                'rank_name.required' => 'Rank Name must be provided',
                'rank_name.min' => 'Rank Name must be at least 3 characters',
                'rank_name.unique' => 'Rank Name already exists',
            ]);


            $insert_data = array(
                'dep_id' => $request->dep_id,
                //'rank_name' => Str::title($request->rank_name),
                'rank_name' => $request->rank_name,
                'rank_tags' => $request->rank_tags,
                'status' => 1,
            );

            $save_rank = Rank::create($insert_data);

            if ($save_rank) {
                $response = array(
                    'success' => true,
                    'message' => 'Rank Added',
                );
            } else {
                $response = array(
                    'success' => false,
                    'message' => 'Something went wrong while adding rank',
                );
            }
        }

        return response()->json($response);
    }

    // Edit Rank
    public function edit_rank(Request $request)
    {
        $response = array();

        $request->validate([
            'rank_id' => ['required', 'integer', Rule::exists('ranks', 'id')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })]
        ]);

        $rank = Rank::find($request->rank_id);

        if ($rank) {
            $response = array('success' => true, 'message' => 'Rank Found', 'data' => $rank);
        } else {
            $response = array('success' => false, 'message' => 'Rank Not Found', 'data' => []);
        }

        return response()->json($response);
    }

    // Delete Rank
    public function delete_rank(Request $request)
    {
        $response = array();

        $request->validate([
            'rank_id' => ['required', 'integer', Rule::exists('ranks', 'id')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })]
        ]);

        DB::enableQueryLog();

        $delete_rank = Rank::where('id', $request->rank_id)->delete();

        if ($delete_rank) {
            $response = array('success' => true, 'message' => 'Rank Deleted');
        } else {
            $response = array('success' => false, 'message' => 'Something went wrong');
        }

        return response()->json($response);
    }

    // Change Status of Rank
    public function change_status(Request $request)
    {
        $response = array();

        $request->validate([
            'rank_id' => ['required', 'integer', Rule::exists('ranks', 'id')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })],
            'status' => ['required', 'integer'],
        ]);

        $change_status = Rank::where('id', $request->rank_id)->update(['status' => $request->status]);

        if ($change_status) {
            $response = array('success' => true, 'message' => 'Rank Status Updated');
        } else {
            $response = array('success' => false, 'message' => 'Something went wrong');
        }

        return response()->json($response);
    }
}
