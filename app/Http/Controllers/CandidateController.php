<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Spatie\Image\Image;

class CandidateController extends Controller
{
    // Get Candidates (DataTables)
    public function getCandidates(Request $request)
    {
        if ($request->ajax()) {
            $data = Candidate::select('candidates.*', 'd.dep_name', 'r.rank_name')
                ->leftJoin('departments as d', 'candidates.dep_id', '=', 'd.id')
                ->leftJoin('ranks as r', 'candidates.rank_id', '=', 'r.id')
                ->orderByDesc('candidates.id');

            if (isset($request->dep_id) && !empty($request->dep_id)) {
                $data = $data->where('candidates.dep_id', '=', $request->dep_id);
            }
            if (isset($request->rank_id) && !empty($request->rank_id)) {
                $data = $data->where('candidates.rank_id', '=', $request->rank_id);
            }
            if (isset($request->location) && !empty($request->location)) {
                $data = $data->where('candidates.type', '=', $request->location);
            }

            $data = $data->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('candidate_details', function ($row) {
                    $canDetails = '';
                    if ($row->candidate_photo != null && Storage::disk('public')->exists($row->candidate_photo)) {
                        $canDetails = '<div class="d-flex align-items-center">
                                            <img src="' . asset('storage/' . $row->candidate_photo) . '" class="w-50px border-radius-6px" />
                                            <div class="ms-3">
                                                <span class="fw-bold">Name: </span>' . $row->candidate_name . '<br/>
                                                <span class="fw-bold">Contact: </span>' . $row->contact_no . '<br/>
                                                <span class="fw-bold">Email: </span>' . $row->email . '<br/>
                                            </div>
                                        </div>';
                    } else {
                        $canDetails = '<span class="fw-bold">Name: </span>' . $row->candidate_name . '<br/><span class="fw-bold">Contact: </span>' . $row->contact_no . '<br/><span class="fw-bold">Email: </span>' . $row->email . '<br/>';
                    }
                    return $canDetails;
                })
                ->addColumn('identities', function ($row) {
                    if ($row->indos_no) {
                        $identities = '<span class="fw-bold">CoC No.: </span>' . $row->coc_no . '<br/><span class="fw-bold">Passport No.: </span>' . $row->passport_no . '<br/><span class="fw-bold">INDoS No.: </span>' . $row->indos_no;
                    } else {
                        $identities = '<span class="fw-bold">CoC No.: </span>' . $row->coc_no . '<br/><span class="fw-bold">Passport No.: </span>' . $row->passport_no;
                    }

                    return $identities;
                })
                ->addColumn('designation', function ($row) {
                    $designation = '<span class="fw-bold">Department: </span>' . $row->dep_name . '<br/><span class="fw-bold">Rank: </span>' . $row->rank_name . '<br/>';
                    return $designation;
                })
                ->addColumn('can_type', function ($row) {
                    $can_type = '';
                    if ($row->type == 2) {
                        $can_type = '<span class="fw-bold">Type: </span>Onshore<br/><span class="fw-bold">Till Date: </span>' . date('d M, Y', strtotime($row->till_date));
                    } else {
                        $can_type = '<span class="fw-bold">Type: </span>Offshore';
                    }

                    return $can_type;
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '
                    <div>
                        <a href="' . route('edit-candidate', [$row->id]) . '"
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
                ->rawColumns(['candidate_details', 'identities', 'designation', 'can_type', 'action'])
                ->make(true);
        }
    }

    // Add or Update Candidates
    public function save_candidate(Request $request)
    {
        $response = array();

        $image_path = 'images/candidates/';
        $passport_path = 'documents/passports/';

        if (isset($request->can_id) && !empty($request->can_id)) {

            $request->validate([
                'can_id' => ['required', 'integer', Rule::exists('candidates', 'id')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })],
                'candidate_name' => ['required', 'min:3', 'string'],
                'contact_no' => ['required', 'digits:10', Rule::unique('candidates', 'contact_no')->ignore($request->can_id)->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })],
                'email' => ['required', 'email', Rule::unique('candidates', 'email')->ignore($request->can_id)->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })],
                'dob' => ['required', 'date'],
                'location' => ['required'],
                'coc_no' => ['required', 'regex:/^[A-Z]{1}\d{5}[A-Z]{2}\d{4}[A-Z]{3}\d{6}$/', Rule::unique('candidates', 'coc_no')->ignore($request->can_id)->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })],
                'indos_no' => ['nullable', 'regex:/^[A-Z0-9]{12}$/', Rule::unique('candidates', 'indos_no')->ignore($request->can_id)->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })],
                'passport_no' => ['required', 'regex:/^[A-Z]{2}\d{7}$/', Rule::unique('candidates', 'passport_no')->ignore($request->can_id)->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })],
                'till_date' => ['nullable', 'date'],
            ], [
                'candidate_name.required' => 'Candidate Name must be provided',
                'candidate_name.min' => 'Candidate Name must be at least 3 characters',
                'email.required' => 'Candidate Email must be provided',
                'email.email' => 'Candidate Email is invalid',
                'email.unique' => 'Candidate Email already exists',
                'contact_no.required' => 'Contact No must be provided',
                'contact_no.digits' => 'Contact No must be 10 digits',
                'contact_no.unique' => 'Contact No already exists',
                'email.unique' => 'Email already exists',
                'dob.required' => 'Date of Birth must be provided',
                'coc_no.required' => 'CoC Number must be provided',
                'location.required' => 'Location must be provided',
                'coc_no.regex' => 'The CoC Number format is invalid',
                'coc_no.unique' => 'The CoC Number already exists',
                'indos_no.regex' => 'The INDoS Number format is invalid',
                'indos_no.unique' => 'The INDoS Number already exists',
                'passport_no.required' => 'Passport Number must be provided',
                'passport_no.regex' => 'The Passport Number format is invalid',
                'passport_no.unique' => 'The Passport Number already exists',
            ]);

            $update_data = $request->all();
            
            //dd($update_data);
            unset($update_data['_token']);
            unset($update_data['can_id']);
            unset($update_data['/save-candidate']);
            $update_data['candidate_name'] = Str::title($update_data['candidate_name']);
            $update_data['email'] = Str::lower($update_data['email']);
            $update_data['coc_no'] = Str::upper($update_data['coc_no']);
            $update_data['indos_no'] = Str::upper($update_data['indos_no']);
            $update_data['passport_no'] = Str::upper($update_data['passport_no']);

            if ($request->type == 1) {
                $update_data['till_date'] = null;
            }

            if ($request->hasFile('candidate_photo')) {
                $file = $request->file('candidate_photo');
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
                    $update_data['candidate_photo'] = $image_path . $new_filename;

                    $old_image = Candidate::find($request->can_id);

                    if ($old_image->candidate_photo != null && Storage::disk('public')->exists($old_image->candidate_photo)) {
                        Storage::disk('public')->delete($old_image->candidate_photo);
                    }
                }
            }

            if ($request->hasFile('passport_file')) {
                $file2 = $request->file('passport_file');
                $filename2 = $file->getClientOriginalName();
                $new_filename2 = time() . '_' . $filename2;
                $upload2 = Storage::disk('public')->put($passport_path . $new_filename2, (string) file_get_contents($file2));

                if ($upload2) {
                    $update_data['passport_file'] = $passport_path . $new_filename2;

                    $old_file = Candidate::find($request->can_id);

                    if ($old_file->passport_file != null && Storage::disk('public')->exists($old_file->passport_file)) {
                        Storage::disk('public')->delete($old_file->passport_file);
                    }
                }
            }

            $save_data = Candidate::where('id', '=', $request->can_id)->update($update_data);
            if ($save_data) {
                $response = array('success' => true, 'message' => 'Candidate Data Updated');
            } else {
                $response = array('success' => false, 'message' => 'Something went wrong while updating');
            }
        } else {
            $request->validate([
                'candidate_photo' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
                'candidate_name' => ['required', 'min:3', 'string'],
                'contact_no' => ['required', 'digits:10', Rule::unique('candidates', 'contact_no')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })],
                'email' => ['required', 'email', Rule::unique('candidates', 'email')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })],
                'dob' => ['required', 'date'],
                'location' => ['required'],
                'coc_no' => ['required', 'regex:/^[A-Z]{1}\d{5}[A-Z]{2}\d{4}[A-Z]{3}\d{6}$/', Rule::unique('candidates', 'coc_no')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })],
                'indos_no' => ['nullable', 'regex:/^[A-Z0-9]{12}$/', Rule::unique('candidates', 'indos_no')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })],
                'passport_no' => ['required', 'regex:/^[A-Z]{2}\d{7}$/', Rule::unique('candidates', 'passport_no')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })],
                'till_date' => ['nullable', 'date'],
            ], [
                'candidate_name.required' => 'Candidate Name must be provided',
                'candidate_name.min' => 'Candidate Name must be at least 3 characters',
                'email.required' => 'Candidate Email must be provided',
                'email.email' => 'Candidate Email is invalid',
                'email.unique' => 'Candidate Email already exists',
                'contact_no.required' => 'Contact No must be provided',
                'contact_no.digits' => 'Contact No must be 10 digits',
                'contact_no.unique' => 'Contact No already exists',
                'email.unique' => 'Email already exists',
                'dob.required' => 'Date of Birth must be provided',
                'location.required' => 'Location must be provided',
                'coc_no.required' => 'CoC Number must be provided',
                'coc_no.regex' => 'The CoC Number format is invalid',
                'coc_no.unique' => 'The CoC Number already exists',
                'indos_no.regex' => 'The INDoS Number format is invalid',
                'indos_no.unique' => 'The INDoS Number already exists',
                'passport_no.required' => 'Passport Number must be provided',
                'passport_no.regex' => 'The Passport Number format is invalid',
                'passport_no.unique' => 'The Passport Number already exists',
            ]);


            $insert_data = $request->all();
            unset($insert_data['_token']);

            $insert_data['candidate_name'] = Str::title($insert_data['candidate_name']);
            $insert_data['email'] = Str::lower($insert_data['email']);
            $insert_data['coc_no'] = Str::upper($insert_data['coc_no']);
            $insert_data['indos_no'] = Str::upper($insert_data['indos_no']);
            $insert_data['passport_no'] = Str::upper($insert_data['passport_no']);

            if ($request->hasFile('candidate_photo')) {
                $file = $request->file('candidate_photo');
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
                    $insert_data['candidate_photo'] = $image_path . $new_filename;
                }
            }

            if ($request->hasFile('passport_file')) {
                $file2 = $request->file('passport_file');
                $filename2 = $file->getClientOriginalName();
                $new_filename2 = time() . '_' . $filename2;
                $upload2 = Storage::disk('public')->put($passport_path . $new_filename2, (string) file_get_contents($file2));

                if ($upload2) {
                    $insert_data['passport_file'] = $passport_path . $new_filename2;
                }
            }

            $save_data = Candidate::create($insert_data);

            if ($save_data) {
                $response = array('success' => true, 'message' => 'New Candidate Added');
            } else {
                $response = array('success' => false, 'message' => 'Something went wrong while adding');
            }
        }
        return response()->json($response);
    }

    // Edit Candidate
    public function edit_candidate(Request $request, $can_id)
    {

        if ($can_id  == "") {
            abort(404);
        } else {
            $candidate = Candidate::find($can_id);
            if (!$candidate) {
                abort(404, 'Candidate not Found');
            } else {
                $data['pageTitle'] = 'Edit Candidate';
                $data['candidate'] = $candidate;

                //dd($data);
                return view('pages.candidate-add-update', $data);
            }
        }
    }

    // Delete Candidate
    public function delete_candidate(Request $request)
    {
        $response = array();

        $request->validate([
            'can_id' => ['required', 'integer', Rule::exists('candidates', 'id')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })]
        ]);

        DB::enableQueryLog();

        $delete_candidates = Candidate::where('id', $request->can_id)->delete();

        if ($delete_candidates) {
            $response = array('success' => true, 'message' => 'Candidate Deleted');
        } else {
            $response = array('success' => false, 'message' => 'Something went wrong');
        }

        return response()->json($response);
    }
    public function getCandidateInfo(Request $request)
    {
        $response = array();
        $course_id = $request->course_id;
        // Convert comma-separated department ids to an array
        if ($request->candidate_ids != 0) {
            $CanIds = explode(',', $request->candidate_ids);

            $can_list = Candidate::select(
                'candidates.id',
                'candidates.candidate_photo',
                'candidates.candidate_name',
                'candidates.contact_no',
                'candidates.type',
                'candidates.location',
                'candidates.till_date',
                'departments.dep_name',
                'ranks.rank_name',
                'course_expenses.status', // Use the alias here
                'course_expenses.expenditure_amount', // Use the alias here
                'course_expenses.refund_amount' // Use the alias here
            )
                ->whereIn('candidates.id', $CanIds)
                ->leftJoin('departments', 'candidates.dep_id', '=', 'departments.id')
                ->leftJoin('ranks', 'candidates.rank_id', '=', 'ranks.id')
                ->leftJoin(DB::raw('(SELECT can_id, status, expenditure_amount, refund_amount
                                 FROM course_enrollment_expenses
                                 WHERE course_id = ?
                                 AND id IN (SELECT MAX(id) FROM course_enrollment_expenses GROUP BY can_id)) AS course_expenses'), function ($join) {
                    $join->on('candidates.id', '=', 'course_expenses.can_id');
                })
                ->setBindings([$course_id, $CanIds]) // Bind the course_id for the subquery
                ->get();
        } else {
            $can_list = Candidate::select(
                'candidates.id',
                'candidates.candidate_photo',
                'candidates.candidate_name',
                'candidates.contact_no',
                'candidates.type',
                'candidates.location',
                'candidates.till_date',
                'departments.dep_name',
                'ranks.rank_name',
                'course_enrollment_expenses.status', // Select the status from CourseEnrollmentExpense
                'course_enrollment_expenses.expenditure_amount', // Select the expenditure from CourseEnrollmentExpense
                'course_enrollment_expenses.refund_amount'
            )
                ->leftjoin('departments', 'candidates.dep_id', '=', 'departments.id')
                ->leftjoin('ranks', 'candidates.rank_id', '=', 'ranks.id')
                ->leftJoin(DB::raw('(SELECT can_id, status, expenditure_amount, refund_amount FROM course_enrollment_expenses
                         WHERE course_id = ?
                         AND id IN (SELECT MAX(id) FROM course_enrollment_expenses GROUP BY can_id)) AS course_expenses'), function ($join) {
                    $join->on('candidates.id', '=', 'course_expenses.can_id');
                })
                ->setBindings([$course_id])->get();
        }

        if ($can_list) {
            $response = [
                'success' => true,
                'message' => 'Candidates Found',
                'data' => $can_list
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Candidates Not Found',
                'data' => []
            ];
        }
        return response()->json($response);
    }
}