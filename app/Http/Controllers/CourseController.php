<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Rank;
use App\Models\SubCategory;
use App\Models\Vessels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;
use Spatie\Image\Image;

class CourseController extends Controller
{
    // Get Courses (DataTables)
    public function getCourses(Request $request)
    {
        if ($request->ajax()) {

            $data = DB::table('courses as c')
                ->select('c.*') // Select required fields
                ->selectRaw("string_agg(DISTINCT tc.center_name, ', ') as center_names")
                ->leftjoin('training_centers as tc', function ($join) {
                    $join->whereRaw("tc.id = ANY(string_to_array(c.training_center, ',')::int[])");
                })
                ->whereNull('c.deleted_at')
                ->groupBy('c.id');

            // Apply filters if they are present
            if (!empty($request->cat_id)) {
                $data->whereRaw("? = ANY(string_to_array(c.categories, ',')::int[])", [$request->cat_id]);
            }
            if (!empty($request->subcat_id)) {
                $data->whereRaw("? = ANY(string_to_array(c.subcategories, ',')::int[])", [$request->subcat_id]);
            }
            if (!empty($request->vessel_id)) {
                $data->whereRaw("? = ANY(string_to_array(c.vessels, ',')::int[])", [$request->vessel_id]);
            }
            if (!empty($request->dep_id)) {
                $data->whereRaw("? = ANY(string_to_array(c.departments, ',')::int[])", [$request->dep_id]);
            }
            if (!empty($request->rank_id)) {
                $data->whereRaw("? = ANY(string_to_array(c.ranks, ',')::int[])", [$request->rank_id]);
            }
            if (!empty($request->training_center)) {
                $data->whereRaw("? = ANY(string_to_array(c.training_center, ',')::int[])", [$request->training_center]);
            }
            if (!empty($request->course_type)) {
                $data->whereRaw("? = ANY(string_to_array(c.course_type, ',')::int[])", [$request->course_type]);
            }
            if (!empty($request->course_by)) {
                // Cast both sides to the same type (course_type is likely varchar)
                $data->whereRaw("c.course_by = ?", [(int)$request->course_by]);
            }
            $data = $data->orderBy('c.id', 'desc')->get();


            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('course_details', function ($row) {
                    $courseDetails = '';

                    if ($row->course_logo != null && Storage::disk('public')->exists($row->course_logo)) {
                        $courseDetails = '<div class="d-flex align-items-center">
                                            <img src="' . asset('storage/' . $row->course_logo) . '" class="w-50px border-radius-6px" />
                                            <div class="ms-3">';
                        if (isset($row->course_code))
                            $courseDetails .= '  <span class="fw-bold">Code: </span>' . $row->course_code . '<br/>
                                                <span class="fw-bold">Name: </span>' . $row->course_name . '<br/>';
                        if (isset($row->duration))
                            $courseDetails .= '<span class="fw-bold">Duration: </span>' . $row->duration . ' days
                                            </div>
                                        </div>';
                    } else {
                        $courseDetails = '';

                        if (isset($row->course_code)) {
                            $courseDetails .= '<span class="fw-bold">Code: </span>' . $row->course_code . '<br/>';
                        }

                        if (isset($row->course_name)) {
                            $courseDetails .= '<span class="fw-bold">Name: </span>' . $row->course_name . '<br/>';
                        }

                        if (isset($row->duration)) {
                            $courseDetails .= '<span class="fw-bold">Duration: </span>' . $row->duration . ' days';
                        }

                        //$courseDetails = '<span class="fw-bold">Code: </span>' . $row->course_code . '<br/><span class="fw-bold">Name: </span>' . $row->course_name . '<br/><span class="fw-bold">Duration: </span>' . $row->duration . ' days';
                    }

                    return $courseDetails;
                })
                ->addColumn('course_by', function ($row) {
                    $courseBy = '';
                    if ($row->course_by == 1) {
                        $courseBy = '<strong>Molmi</strong>';
                    } else {
                        $courseBy = '<strong>External</strong>';
                    }
                    return $courseBy;
                })
                ->addColumn('priorities', function ($row) {

                    $priorities = '<span class="fw-bold">Online Priority: </span>' . $row->online_priority . '<br/><span class="fw-bold">Offline Priority: </span>' . $row->offline_priority . '<br/><span class="fw-bold">E-Learning Priority: </span>' . $row->elearning_priority;
                    return $priorities;
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
                        <a href="' . route('edit-course', [$row->id]) . '" onclick="return edit_data(' . $row->id . ');"
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
                ->rawColumns(['course_details', 'course_by', 'priorities', 'action', 'status'])
                ->make(true);
        }
    }

    // Add or Update Courses
    public function save_course(Request $request)
    {
        $response = array();

        $image_path = 'images/courses/';
        //dd($request->course_id);
        if ($request->step == 1) {
            if ($request->course_id != "" && $request->course_id != null) {
                $request->validate([
                    'course_id' => [
                        'required',
                        'integer',
                        Rule::exists('courses', 'id')->where(function ($query) {
                            return $query->whereNull('deleted_at');
                        })
                    ],
                    'course_name' => [
                        'required',
                        'min:3',
                        Rule::unique('courses', 'course_name')->ignore($request->course_id)->where(function ($query) {
                            return $query->whereNull('deleted_at');
                        })
                    ],
                    'course_code' => [
                        'nullable',
                        //'min:3',
                        Rule::unique('courses', 'course_code')->ignore($request->course_id)->where(function ($query) {
                            return $query->whereNull('deleted_at');
                        })
                    ],
                    'course_logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg'],
                    //'course_by' => ['required'],
                    //'training_center' => ['required'],
                    //'duration' => ['nullable', 'numeric'],
                ], [
                    'course_name.required' => 'Course Name must be provided',
                    'course_name.min' => 'Course Name must be at least 3 characters',
                    'course_name.unique' => 'Course Name already exists',
                    'course_code.required' => 'Course Code must be provided',
                    'course_code.min' => 'Course Code must be at least 3 characters',
                    'course_code.unique' => 'Course Code already exists',
                    'course_by.required' => 'Course By must be provided',
                    'training_center.required' => 'Select a training center',
                    'duration.required' => 'Please provide number of days',
                ]);

                $update_data = [
                    'course_code' => $request->course_code,
                    'course_name' => $request->course_name,
                    'course_by' => $request->course_by,
                    'duration' => $request->duration,
                    "course_followed_by" => $request->course_followed_by,
                    "course_repeated" => $request->course_repeated,
                    "course_intervals" => $request->course_intervals,
                    "offline_priority" => $request->offline_priority,
                    "online_priority" => $request->online_priority,
                    "elearning_priority" => $request->elearning_priority,
                    "course_comments" => $request->course_comments
                ];
                if (isset($request->training_center)) {
                    $update_data['training_center'] = implode(',', $request->training_center);
                }

                if ($request->hasFile('course_logo')) {
                    $file = $request->file('course_logo');
                    $filename = $file->getClientOriginalName();
                    //$filename = Str::slug(strtolower(pathinfo($file, PATHINFO_FILENAME)), '_');
                    $new_filename = time() . '_' . $filename;

                    // Use Spatie Image to resize the image
                    $tempPath = sys_get_temp_dir() . '/' . $new_filename;
                    Image::load($file->getPathname())
                        ->optimize()
                        ->save($tempPath);

                    $upload = Storage::disk('public')->put($image_path . $new_filename, (string) file_get_contents($tempPath));

                    if ($upload) {
                        $update_data['course_logo'] = $image_path . $new_filename;

                        $old_image = Course::find($request->course_id);

                        if ($old_image->course_logo != null && Storage::disk('public')->exists($old_image->course_logo)) {
                            Storage::disk('public')->delete($old_image->course_logo);
                        }
                    }
                }

                $save_data = Course::where('id', '=', $request->course_id)->update($update_data);

                if ($save_data) {
                    $response = array('success' => true, 'course_id' => $request->course_id, 'message' => 'Course Updated');
                } else {
                    $response = array('success' => false, 'message' => 'Something went wrong while updating');
                }
            } else {
                $request->validate([
                    'course_name' => [
                        'required',
                        'min:3',
                        Rule::unique('courses', 'course_name')->where(function ($query) {
                            return $query->whereNull('deleted_at');
                        })
                    ],
                    'course_code' => [
                        'nullable',
                        //'min:3',
                        Rule::unique('courses', 'course_code')->where(function ($query) {
                            return $query->whereNull('deleted_at');
                        })
                    ],
                    'course_logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg'],
                    //'course_by' => ['required'],
                    //'training_center' => ['required'],
                    'duration' => ['nullable', 'numeric'],
                ], [
                    'course_name.required' => 'Course Name must be provided',
                    'course_name.min' => 'Course Name must be at least 3 characters',
                    'course_name.unique' => 'Course Name already exists',
                    'course_code.required' => 'Course Code must be provided',
                    'course_code.min' => 'Course Code must be at least 3 characters',
                    'course_code.unique' => 'Course Code already exists',
                    'course_by.required' => 'Course By must be provided',
                    'training_center.required' => 'Select a training center',
                    'duration.required' => 'Please provide number of days',
                ]);

                $insert_data = $request->all();
                if (isset($insert_data['training_center']))
                    $insert_data['training_center'] = implode(',', $insert_data['training_center']);
                unset($insert_data['step']);

                if ($request->hasFile('course_logo')) {
                    $file = $request->file('course_logo');
                    $filename = $file->getClientOriginalName();
                    //$filename = Str::slug(strtolower(pathinfo($file, PATHINFO_FILENAME)), '_');
                    $new_filename = time() . '_' . $filename;

                    // Use Spatie Image to resize the image
                    $tempPath = sys_get_temp_dir() . '/' . $new_filename;
                    Image::load($file->getPathname())
                        ->optimize()
                        ->save($tempPath);

                    $upload = Storage::disk('public')->put($image_path . $new_filename, (string) file_get_contents($tempPath));

                    if ($upload) {
                        $insert_data['course_logo'] = $image_path . $new_filename;
                    }
                }

                $save_data = Course::create($insert_data);

                if ($save_data) {
                    $response = array('success' => true, 'course_id' => $save_data->id, 'message' => 'Course Added');
                } else {
                    $response = array('success' => false, 'message' => 'Something went wrong while adding');
                }
            }
        } else {
            $data['categories'] = isset($request->categories) ? implode(' ,', $request->categories) : '';
            $data['subcategories'] = isset($request->subcategories) ? implode(' ,', $request->subcategories) : '';
            $data['vessels'] = isset($request->vessels) ? implode(' ,', $request->vessels) : '';
            $data['course_type'] = isset($request->course_type) ? implode(' ,', $request->course_type) : '';
            $data['departments'] = isset($request->departments) ? implode(' ,', $request->departments) : '';
            $data['ranks'] = isset($request->ranks) ? implode(' ,', $request->ranks) : '';

            $priority = [];
            if (isset($request->ranks)) {
                foreach ($request->ranks as $rank) {
                    if ($request->input('rank_status_' . $rank) == "mandatory")
                        $priority[] = 1;
                    else
                        $priority[] = 0;
                }
            }
            $data['rank_priorities'] = implode(' ,', $priority);
            //dd($request->course_id);
            $save_map = Course::where('id', '=', $request->course_id)->update($data);
            if ($save_map) {
                $response = array('success' => true, 'message' => 'Mapping Updated');
            } else {
                $response = array('success' => false, 'message' => 'Something went wrong while updating');
            }
        }
        return response()->json($response);
    }
    // Edit Course
    public function edit_course(Request $request, $course_id)
    {
        if ($course_id == "") {
            abort(404);
        } else {
            $course = Course::find($course_id);
            if (!$course) {
                abort(404, 'Course not Found');
            } else {

                if ($course->course_logo != null && Storage::disk('public')->exists($course->course_logo)) {
                    $course->course_logo = asset('storage/' . $course->course_logo);
                }

                $data['pageTitle'] = 'Edit Course';
                $data['course'] = $course;
                //dd($data['course']->id);
                return view('pages.course-add-update', $data);
            }
        }
    }

    // Delete Course
    public function delete_course(Request $request)
    {
        $response = array();

        $request->validate([
            'course_id' => [
                'required',
                'integer',
                Rule::exists('courses', 'id')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })
            ]
        ]);

        DB::enableQueryLog();

        $delete_course = Course::where('id', $request->course_id)->delete();

        if ($delete_course) {
            $response = array('success' => true, 'message' => 'Course Deleted');
        } else {
            $response = array('success' => false, 'message' => 'Something went wrong');
        }

        return response()->json($response);
    }

    // Change Status of Course
    public function change_status(Request $request)
    {
        $response = array();

        $request->validate([
            'course_id' => [
                'required',
                'integer',
                Rule::exists('courses', 'id')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })
            ],
            'status' => ['required', 'integer'],
        ]);

        $change_status = Course::where('id', $request->course_id)->update(['status' => $request->status]);

        if ($change_status) {
            $response = array('success' => true, 'message' => 'Course Status Updated');
        } else {
            $response = array('success' => false, 'message' => 'Something went wrong');
        }

        return response()->json($response);
    }

    // Get All Subcategories of Category
    public function get_subcategories(Request $request)
    {
        $response = array();

        $subcategories = SubCategory::where('cat_id', '=', $request->cat_id)->where('status', 1)->get();

        if ($subcategories->isNotEmpty()) {
            $response = array('success' => true, 'message' => 'Subcategories Found', 'data' => $subcategories);
        } else {
            $response = array('success' => false, 'message' => 'Subcategories Not Found', 'data' => []);
        }

        return response()->json($response);
    }

    public function get_multiple_subcategories(Request $request)
    {
        $response = array();

        // Convert comma-separated cat_ids to an array
        if ($request->cat_id != 0) {
            $catIds = explode(',', $request->cat_id);

            // Fetch subcategories where cat_id is in the array and status is 1
            $subcategories = SubCategory::whereIn('sub_categories.cat_id', $catIds)
                ->where('sub_categories.status', 1)
                ->join('categories', 'sub_categories.cat_id', '=', 'categories.id') // Join subcategories with categories
                ->get(['sub_categories.id', 'sub_categories.subcat_name', 'sub_categories.cat_id', 'categories.cat_name']) // Select required fields
                ->groupBy('cat_id'); // Group by cat_id
        } else {
            $subcategories = SubCategory::where('sub_categories.status', 1)
                ->join('categories', 'sub_categories.cat_id', '=', 'categories.id') // Join subcategories with categories
                ->get(['sub_categories.id', 'sub_categories.subcat_name', 'sub_categories.cat_id', 'categories.cat_name']) // Select required fields
                ->groupBy('cat_id'); // Group by cat_id
        }

        if ($subcategories->isNotEmpty()) {
            $groupedSubcategories = [];

            // Format the subcategories into an array grouped by cat_id
            foreach ($subcategories as $cat_id => $subs) {
                $groupedSubcategories[] = [
                    'cat_id' => $cat_id,
                    'cat_name' => $subs->first()->cat_name, // Get category name from the first element in the group
                    'subcategories' => $subs->map(function ($sub) {
                        return [
                            'id' => $sub->id,
                            'subcat_name' => $sub->subcat_name
                        ];
                    })->toArray() // Map subcategories and convert to an array
                ];
            }

            $response = [
                'success' => true,
                'message' => 'Subcategories Found',
                'data' => $groupedSubcategories
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Subcategories Not Found',
                'data' => []
            ];
        }
        return response()->json($response);
    }

    public function get_vessels(Request $request)
    {
        $response = array();

        // Convert comma-separated subcat_ids to an array
        if (isset($request->subcat_id)) {
            if ($request->subcat_id != 0) {
                $SubcatIds = explode(',', $request->subcat_id);

                // Fetch subcategories where cat_id is in the array and status is 1
                $vessels = Vessels::whereIn('vessels.subcat_id', $SubcatIds)
                    ->where('vessels.status', 1)
                    ->join('categories', 'vessels.cat_id', '=', 'categories.id')
                    ->join('sub_categories', 'vessels.subcat_id', '=', 'sub_categories.id') // Join subcategories with categories
                    ->get(['vessels.subcat_id', 'sub_categories.subcat_name', 'sub_categories.cat_id', 'categories.cat_name', 'vessels.id', 'vessels.vessel_name']) // Select required fields
                    ->groupBy('subcat_id'); // Group by subcat_id
            } else {
                $vessels = Vessels::where('vessels.status', 1)
                    ->join('categories', 'vessels.cat_id', '=', 'categories.id')
                    ->join('sub_categories', 'vessels.subcat_id', '=', 'sub_categories.id') // Join subcategories with categories
                    ->get(['vessels.subcat_id', 'sub_categories.subcat_name', 'sub_categories.cat_id', 'categories.cat_name', 'vessels.id', 'vessels.vessel_name']) // Select required fields
                    ->groupBy('subcat_id'); // Group by subcat_id
            }
        } else {
            $response = [
                'success' => true,
                'message' => 'Vessels Found',
                'data' => []
            ];
            return response()->json($response);
        }

        if ($vessels->isNotEmpty()) {
            $groupedVessels = [];

            // Format the vessels into an array grouped by subcat_id
            foreach ($vessels as $vessel_id => $vess) {
                $groupedVessels[] = [
                    'subcat_id' => $vess->first()->subcat_id,
                    'subcat_name' => $vess->first()->subcat_name, // Get category name from the first element in the group
                    'vessels' => $vess->map(function ($sub) {
                        return [
                            'id' => $sub->id,
                            'vessel_name' => $sub->vessel_name
                        ];
                    })->toArray() // Map vessels and convert to an array
                ];
            }

            $response = [
                'success' => true,
                'message' => 'Vessels Found',
                'data' => $groupedVessels
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Vessels Not Found',
                'data' => []
            ];
        }
        return response()->json($response);
    }

    // Get All Ranks of Department
    public function get_ranks(Request $request)
    {
        $response = array();

        $ranks = Rank::where('dep_id', '=', $request->dep_id)->get();

        if ($ranks->isNotEmpty()) {
            $response = array('success' => true, 'message' => 'Ranks Found', 'data' => $ranks);
        } else {
            $response = array('success' => false, 'message' => 'Ranks Not Found', 'data' => []);
        }

        return response()->json($response);
    }

    public function get_multiple_ranks(Request $request)
    {
        $response = array();

        // Convert comma-separated department ids to an array
        if ($request->dept_id != 0) {
            $deptIds = explode(',', $request->dept_id);

            // Fetch ranks where cat_id is in the array and status is 1
            $ranks = Rank::whereIn('ranks.dep_id', $deptIds)
                ->where('ranks.status', 1)
                ->join('departments', 'ranks.dep_id', '=', 'departments.id') // Join ranks with departments
                ->get(['ranks.id', 'ranks.rank_name', 'departments.dep_name', 'ranks.dep_id']) // Select required fields
                ->groupBy('dep_id'); // Group by department id

        } else {
            $ranks = Rank::where('ranks.status', 1)
                ->join('departments', 'ranks.dep_id', '=', 'departments.id') // Join ranks with departments
                ->get(['ranks.id', 'ranks.rank_name', 'departments.dep_name', 'ranks.dep_id']) // Select required fields
                ->groupBy('dep_id'); // Group by department id
        }

        if ($ranks->isNotEmpty()) {
            $groupedRanks = [];

            // Format the ranks into an array grouped by dep_id
            foreach ($ranks as $dep_id => $rank) {
                $groupedRanks[] = [
                    'dep_id' => $dep_id,
                    'dep_name' => $rank->first()->dep_name, // Get department name from the first element in the group
                    'ranks' => $rank->map(function ($sub) {
                        return [
                            'id' => $sub->id,
                            'rank_name' => $sub->rank_name
                        ];
                    })->toArray() // Map ranks and convert to an array
                ];
            }

            $response = [
                'success' => true,
                'message' => 'Ranks Found',
                'data' => $groupedRanks
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Ranks Not Found',
                'data' => []
            ];
        }
        return response()->json($response);
    }
}