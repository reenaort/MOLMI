<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Rank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use App\Models\courseenrollmentexpense;
use App\Models\course_enrollment;

class MatrixController extends Controller
{
    public function get_date_wise_courses(Request $request)
    {
        //dd($request->date_range);
        if ($request->filled('date_range') && !empty($request->date_range)) {

            $date_range = $request->date_range;

            $dates = explode(' - ', $date_range);

            // $start_date = $dates[0];
            // $end_date = $dates[1];
            $start_date = date('Y-m-d', strtotime($dates[0]));
            $end_date = date('Y-m-d', strtotime($dates[1]));

            $data = DB::table('courses as c')
                ->select('c.*') // Select all course fields
                ->selectRaw("string_agg(DISTINCT r.rank_name, ', ') as rank_names")
                ->selectRaw("string_agg(DISTINCT ct.type_name, ', ') as coursetype_names") // Aggregate rank names
                ->selectRaw("COUNT(DISTINCT CASE WHEN candidates.type = 2
                AND candidates.till_date > CURRENT_DATE
                AND candidates.till_date  > ? THEN candidates.id END) as candidateCount", [$start_date]) // Count candidates within date range
                ->selectRaw("string_agg(DISTINCT CASE WHEN candidates.type = 2
                AND candidates.till_date > CURRENT_DATE
                AND candidates.till_date  > ? THEN candidates.id::text END, ', ') as candidate_ids", [$start_date]) // Aggregate candidate IDs within date range

                ->leftJoin('ranks as r', function ($join) {
                    // Join on ranks using the comma-separated 'ranks' field in 'courses'
                    $join->whereRaw("r.id = ANY(string_to_array(c.ranks, ',')::int[])");
                })
                ->leftJoin('course_types as ct', function ($join) {
                    // Join on ranks using the comma-separated 'ranks' field in 'courses'
                    $join->whereRaw("ct.id = ANY(string_to_array(c.course_type, ',')::int[])");
                })
                ->leftJoin('candidates', function ($join) {
                    // Join candidates based on rank
                    $join->whereRaw("candidates.rank_id = ANY(string_to_array(c.ranks, ',')::int[])");
                })
                ->whereNull('c.deleted_at') // Ensure 'deleted_at' is null
                ->where('c.status', 1); // Filter for active courses

            if (!empty($request->cat_id)) {
                $data->whereRaw("? = ANY(string_to_array(c.categories, ',')::int[])", [$request->cat_id]);
            }
            if (!empty($request->subcat_id)) {
                $data->whereRaw("? = ANY(string_to_array(c.subcategories, ',')::int[])", [$request->subcat_id]);
            }

            if (!empty($request->dep_id)) {
                $data->whereRaw("? = ANY(string_to_array(c.departments, ',')::int[])", [$request->dep_id]);
            }
            if (!empty($request->rank_id)) {
                $data->whereRaw("? = ANY(string_to_array(c.ranks, ',')::int[])", [$request->rank_id]);
            }

            // Group by course ID to aggregate rank names and types
            $data = $data
                ->groupBy('c.id') // Group by course ID to aggregate rank names
                ->havingRaw('COUNT(DISTINCT CASE WHEN candidates.type = 2
        AND candidates.till_date > CURRENT_DATE
        AND candidates.till_date > ? THEN candidates.id END) > 0', [$start_date]) // Filter where candidateCount > 0
                ->orderByRaw('candidateCount desc');
                
                if(isset($request->search) && !empty($request->search)){
                    
                    $search = '%'.$request->search.'%';
                    
                    $data = $data->whereRaw("(c.course_name LIKE ? OR c.course_code LIKE ?)",[$search,$search]);
                }
                
            $total_pages = $data->count();

            if (isset($request->page)) {
                
                $offset = ($request->page - 1) * 10;
                
                $data = $data->offset($offset); 
            };
            
            if (isset($request->limit)) {
                $data = $data->limit($request->limit); // Apply the limit if present
            };
            // Order by candidateCount in descending order
            $data =  $data->get(); // Retrieve the results

            // $rawSql = vsprintf(str_replace(['?'], ['\'%s\''], $data->toSql()), $data->getBindings());
            
            // print_r($rawSql); exit;

            if ($data) {
                $response = array('success' => true, 'data' => $data, 'message' => 'Data found successfully.', 'total_pages' => $total_pages);
            } else {
                $response = array('success' => false, 'message' => 'Something went wrong while updating');
            }
        } else {
            $data = DB::table('courses as c')
                ->select('c.*') // Select all course fields
                ->selectRaw("string_agg(DISTINCT r.rank_name, ', ') as rank_names")
                ->selectRaw("string_agg(DISTINCT ct.type_name, ', ') as coursetype_names") // Aggregate rank names
                //->selectRaw("COUNT(DISTINCT candidates.id) as candidateCount") // Count eligible candidates
                ->selectRaw("COUNT(DISTINCT CASE WHEN candidates.type = 2 AND candidates.till_date > CURRENT_DATE THEN candidates.id END) as candidateCount")
                ->selectRaw("string_agg(DISTINCT CASE WHEN candidates.type = 2 AND candidates.till_date > CURRENT_DATE THEN candidates.id::text END,',') as candidate_ids") // Count eligible candidates with till_date greater than current date
                ->leftJoin('ranks as r', function ($join) {
                    // Join on ranks using the comma-separated 'ranks' field in 'courses'
                    $join->whereRaw("r.id = ANY(string_to_array(c.ranks, ',')::int[])");
                })
                ->leftJoin('course_types as ct', function ($join) {
                    // Join on ranks using the comma-separated 'ranks' field in 'courses'
                    $join->whereRaw("ct.id = ANY(string_to_array(c.course_type, ',')::int[])");
                })
                ->leftJoin('candidates', function ($join) {
                    // Join candidates based on rank
                    $join->whereRaw("candidates.rank_id = ANY(string_to_array(c.ranks, ',')::int[])");
                })
                ->whereNull('c.deleted_at') // Ensure 'deleted_at' is null
                ->where('c.status', 1); // Filter for active courses


            if (!empty($request->cat_id)) {
                $data->whereRaw("? = ANY(string_to_array(c.categories, ',')::int[])", [$request->cat_id]);
            }
            if (!empty($request->subcat_id)) {
                $data->whereRaw("? = ANY(string_to_array(c.subcategories, ',')::int[])", [$request->subcat_id]);
            }

            if (!empty($request->dep_id)) {
                $data->whereRaw("? = ANY(string_to_array(c.departments, ',')::int[])", [$request->dep_id]);
            }
            if (!empty($request->rank_id)) {
                $data->whereRaw("? = ANY(string_to_array(c.ranks, ',')::int[])", [$request->rank_id]);
            }
            $data = $data
                ->groupBy('c.id') // Group by course ID to aggregate rank names
                ->havingRaw('COUNT(DISTINCT CASE WHEN candidates.type = 2
        AND candidates.till_date > CURRENT_DATE THEN candidates.id END) > 0') // Filter where candidateCount > 0
                ->orderByRaw('candidateCount desc') // Order by candidateCount in descending order
                ->orderByRaw('candidateCount desc');

            if (isset($request->limit)) {
                $data = $data->limit($request->limit); // Apply the limit if present
            };

            // Order by candidateCount in descending order
            $data =  $data->get(); // Retrieve the results

            if ($data) {
                $response = array('success' => true, 'data' => $data, 'message' => 'Data found successfully.');
            } else {
                $response = array('success' => false, 'message' => 'Something went wrong while updating');
            }
        }
        return response()->json($response);
    }
    public function get_candidate_wise_course(Request $request)
    {
        if ($request->ajax()) {

            if ($request->candidate_id || $request->vessel_id) {
                $candidate = DB::table('candidates')
                    ->select('rank_id', 'dep_id', 'id')
                    ->where('id', $request->candidate_id)
                    ->first();
                $data = DB::table('courses as c')
                    ->select('c.*') // Select required fields
                    ->selectRaw("string_agg(DISTINCT tc.center_name, ', ') as center_names")
                    ->selectRaw("
                    CASE
                        WHEN ccm.course_id IS NOT NULL THEN
                            CASE
                                WHEN c.course_repeated = 1 AND (
                                    EXTRACT(YEAR FROM age(CURRENT_DATE, ccm.certificate_date)) * 12 +
                                    EXTRACT(MONTH FROM age(CURRENT_DATE, ccm.certificate_date)) >= c.course_intervals * 12
                                ) THEN 'Re-enroll'
                                ELSE 'Completed'
                            END
                        ELSE 'Pending'
                    END as course_enrollment_status
                ")
                    ->selectRaw("
                    array_position(string_to_array(c.ranks, ',')::int[], ?) as rank_position,
                    (string_to_array(c.rank_priorities, ',')::int[])[array_position(string_to_array(c.ranks, ',')::int[], ?)] as rank_priority,
                    CASE
                        WHEN (string_to_array(c.rank_priorities, ',')::int[])[array_position(string_to_array(c.ranks, ',')::int[], ?)] = 1 THEN 'Mandatory'
                        ELSE 'Recommended'
                    END as priority_label
                ", [$candidate->rank_id, $candidate->rank_id, $candidate->rank_id])
                    ->leftjoin('training_centers as tc', function ($join) {
                        $join->on('tc.id', '=', DB::raw("ANY(string_to_array(c.training_center, ',')::int[])"));
                    })
                    ->leftJoin('course_candidate_mapping as ccm', function ($join) use ($candidate) {
                        $join->on('ccm.course_id', '=', 'c.id')
                            ->where('ccm.can_id', '=', $candidate->id);
                    })
                    ->whereNull('c.deleted_at')
                    // Match candidate's rank and department with course's applicable ranks and departments
                    ->whereRaw("? = ANY(string_to_array(c.ranks, ',')::int[])", [$candidate->rank_id])
                    ->whereRaw("? = ANY(string_to_array(c.departments, ',')::int[])", [$candidate->dep_id]);
                if (!empty($request->vessel_id)) {
                    $data->whereRaw("? = ANY(string_to_array(c.vessels, ',')::int[])", [$request->vessel_id]);
                }

                // Group by course ID
                $data = $data->groupBy('c.id', 'ccm.course_id', 'ccm.certificate_date')
                    ->orderByRaw("
                CASE
                    WHEN ccm.course_id IS NOT NULL THEN
                        CASE
                            WHEN c.course_repeated = 1 AND (
                                EXTRACT(YEAR FROM age(ccm.certificate_date, CURRENT_DATE)) * 12 +
                                EXTRACT(MONTH FROM age(ccm.certificate_date, CURRENT_DATE)) >= c.course_intervals * 12
                            ) THEN 0  -- re-enroll
                            ELSE 1  -- completed
                        END
                    ELSE 0  -- enroll
                END, c.id DESC
            ")  // Ensure 'completed' courses appear last, then order by 'id' descending
                    ->get();

                //$data = $data->get();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('course_details', function ($row) {
                        $courseDetails = '';

                        if ($row->course_logo != null && Storage::disk('public')->exists($row->course_logo)) {
                            $courseDetails = '<div class="d-flex align-items-center">
                                            <img src="' . asset('storage/' . $row->course_logo) . '" class="w-50px border-radius-6px" />
                                            <div class="ms-3">
                                                <span class="fw-bold">Code: </span>' . $row->course_code . '<br/>
                                                <span class="fw-bold">Name: </span>' . $row->course_name . '<br/>
                                            </div>
                                        </div>';
                        } else {
                            $courseDetails = '<span class="fw-bold">Code: </span>' . $row->course_code . '<br/><span class="fw-bold">Name: </span>' . $row->course_name . '<br/><span class="fw-bold">';
                        }

                        return $courseDetails;
                    })
                    ->addColumn('priority_label', function ($row) {
                        return '<span class="badge badge-primary">' . $row->priority_label . '</span>';
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
                    ->addColumn('duration', function ($row) {
                        return $row->duration . " days";
                    })
                    ->addColumn('course_enrollment_status', function ($row) {
                        return $row->course_enrollment_status;
                    })
                    ->addColumn('action', function ($row) {
                        $actionBtn = '<div class="d-flex">';
                        if ($row->course_enrollment_status != 'Completed') {
                            //         $actionBtn .= '<a onclick=" return enroll(' . $row->id . ')"
                            //     class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 mb-2"
                            //     data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Renotify"
                            //     data-bs-original-title="Course Done">
                            //     <i class="ki-duotone ki-like fs-1">
                            //         <span class="path1"></span>
                            //         <span class="path2"></span>
                            //     </i>
                            // </a>';
                            $actionBtn .= '<a onclick=" return coursedone(' . $row->id . ')"
                        class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 mb-2"
                        data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Renotify"
                        data-bs-original-title="Enroll">
                        <i class="ki-duotone ki-questionnaire-tablet fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </a>';
                        }
                        return $actionBtn;
                    })
                    ->rawColumns(['course_details', 'course_by',  'action', 'priority_label'])

                    ->make(true);
            } else {
                return Datatables::of(new Collection())->make(true);
            }
        }
    }
    public function store_course_certification_date(Request $request)
    {

        // Validate incoming request data
        $request->validate([
            'course_id' => [
                'required',
                'integer',
                'exists:courses,id' // Ensure the course_id exists in the courses table
            ],
            'candidate_id' => [
                'required',
                'integer',
                'exists:candidates,id' // Ensure the candidate_id exists in the candidates table
            ],
            'certification_date' => 'required|date',
        ]);


        // Get the input data
        $courseId = $request->input('course_id');
        $candidateId = $request->input('candidate_id');
        $certificationDate = $request->input('certification_date');

        // Check if the mapping exists
        $mapping = DB::table('course_candidate_mapping')
            ->where('course_id', $courseId)
            ->where('can_id', $candidateId)
            ->first();

        if ($mapping) {
            // Update the certification date if the record exists
            $save_data = DB::table('course_candidate_mapping')
                ->where('course_id', $courseId)
                ->where('can_id', $candidateId)
                ->update(['certificate_date' => $certificationDate]);
        } else {
            // Insert a new record if it doesn't exist
            $save_data = DB::table('course_candidate_mapping')->insert([
                'course_id' => $courseId,
                'can_id' => $candidateId,
                'certificate_date' => $certificationDate
            ]);
        }

        if ($save_data) {
            $response = array('success' => true,  'message' => 'Certification date updated successfully.');
        } else {
            $response = array('success' => false, 'message' => 'Something went wrong while updating');
        }
        return response()->json($response);
    }

    public function store_course_enrollment(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'candidate_id' => 'required|integer',
            'course_id' => 'required|integer',
            'status' => 'required|string', // Assuming status is a string like 'accepted' or 'declined'
            'amount' => 'required|numeric|max:999999', // Limit expenditure to 6 digits
        ]);

        try {
            // Create a new course enrollment expense record
            $courseEnrollment = new courseenrollmentexpense();
            $courseEnrollment->can_id = $validatedData['candidate_id'];
            $courseEnrollment->course_id = $validatedData['course_id'];
            $courseEnrollment->status = $validatedData['status'];
            if ($validatedData['status'] == 1)
                $courseEnrollment->expenditure_amount = $validatedData['amount'];
            else
                $courseEnrollment->refund_amount = $validatedData['amount'];
            $save_data = $courseEnrollment->save(); // Save to the database

            // Return success response
            if ($save_data) {
                $response = array('success' => true,  'message' => 'Data updated successfully.');
            } else {
                $response = array('success' => false, 'message' => 'Something went wrong while updating');
            }
            return response()->json($response);
        } catch (\Exception $e) {
            // Handle exceptions and return error response
            return response()->json([
                'success' => false,
                'message' => 'Failed to store course enrollment expense: ' . $e->getMessage(),
            ], 500);
        }
    }
}