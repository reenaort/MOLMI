@extends('layouts.admin-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Dashboard')
@push('styles')
    <style>
        hr.dashed {
            border: none;
            border-top: 2px dashed #1B84FF;
            /* Dashed border with custom blue color */
            margin: 20px 0;
            /* Add spacing */
        }

        #regForm {
            padding: 5px;
        }

        /* Hide all steps by default: */
        .tab {
            display: none;
        }

        button {
            background-color: #03599F;
            color: #ffffff;
            border: none;
            cursor: pointer;
        }

        #prevBtn,
        #nextBtn {
            border: none;
            padding: 10px 20px;
            font-size: 17px;
        }

        button:hover {
            opacity: 0.8;
        }

        #prevBtn {
            background-color: #bbbbbb;
        }

        /* Make circles that indicate the steps of the form: */
        .step {
            height: 15px;
            width: 15px;
            margin: 0 2px;
            background-color: #bbbbbb;
            border: none;
            border-radius: 50%;
            display: inline-block;
            opacity: 0.5;
        }

        .step.active {
            opacity: 1;
        }

        /* Mark the steps that are finished and valid: */
        .step.finish {
            background-color: #03599F;
        }

        .errors {
            color: red;
        }



        .rank-item {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
            /* Space between rows */
        }

        .radio-buttons {
            display: flex;
            gap: 20px;
            /* Space between radio buttons */
        }


        .form-check-label {
            display: flex;
            align-items: center;
            margin-right: 15px;
            /* Space between each label */
        }

        input.form-check-input {
            margin-right: 5px;
            /* Space between the radio button and its label */
        }

        .step-details {
            border: 1px dashed #cadceb;
            border-radius: 4px;
            padding: 5px 10px;
        }

        .step-details-head {
            font-size: 1rem;
            font-weight: 400;
            color: #737477;
            margin-bottom: 2px;
        }

        .step-details-content {
            color: #03599f;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 0;
        }

        .s-details {
            border-bottom: 1px solid rgb(1 98 173 / 20%);
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
    </style>
@endpush
@section('content')

    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-3">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Add Course
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Courses</li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-gray-700">{{ !isset($course) ? 'Add' : 'Edit' }} Course</li>
                </ul>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <!-- One "tab" for each step in the form: -->
            <form id="addEditForm" action="{{ route('save-course') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="tab card card-flush p-5">
                    <h3>Course Details</h3>
                    <input type="hidden" id="course_id" name="course_id" value="{{ @$course->id }}">
                    <input type="hidden" id="subcat_id" name="subcat_id" value="{{ @$course->subcategories }}">
                    <input type="hidden" id="vessel_id" name="vessel_id" value="{{ @$course->vessels }}">
                    <input type="hidden" id="rank_id" name="rank_id" value="{{ @$course->ranks }}">
                    <input type="hidden" id="rank_priority" name="rank_priority" value="{{ @$course->rank_priorities }}">

                    <div class="card-body p-5">
                        <div class="mb-5 fv-row fv-plugins-icon-container row gy-3">
                            <div class="col-lg-3">
                                <label class="form-label required">Course Name</label>
                                <input type="text" id="course_name" name="course_name" class="form-control"
                                    placeholder="Course Name" value="{{ @$course->course_name }}"
                                    onkeypress="return allowAlphabetsSpaceNumbers(event);" maxlength="200">
                                <span class="text-danger error-text course_name_error"></span>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label ">Course Code</label>
                                <input type="text" id="course_code" name="course_code" class="form-control"
                                    placeholder="Course Code" value="{{ @$course->course_code }}" maxlength="200">
                                <span class="text-danger error-text course_code_error"></span>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label">Course By</label>
                                @php
                                    $course_by = ['Molmi' => 1, 'External' => 2];
                                @endphp
                                <div>
                                    @foreach ($course_by as $item => $value)
                                        <label class="form-check-image me-6">
                                            <div class="form-check form-check-custom form-check-solid">
                                                <input class="form-check-input" id="course_by_{{ $item }}"
                                                    name="course_by" type="radio" value="{{ $value }}"
                                                    {{ isset($course->course_by) ? ($course->course_by == $value ? 'checked' : '') : ($value == 1 ? 'checked' : '') }} />
                                                <div class="form-check-label fs-6 text-gray-800">
                                                    {{ $item }}
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                <span class="text-danger error-text course_by_error"></span>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label">Training Center</label>
                                <select class="form-select" id="training_center" name="training_center[]"
                                    data-control="select2" data-close-on-select="false"
                                    data-placeholder="Select Training Center" data-allow-clear="true" multiple="multiple">
                                    @foreach (\App\Models\TrainingCenter::all() as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($course->training_center) && in_array($item->id, explode(',', $course->training_center)) ? 'selected' : '' }}>
                                            {{ $item->center_name }}</option>
                                    @endforeach
                                </select>

                                <span class="text-danger error-text training_center_error"></span>
                            </div>


                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-label ">Duration (In Days)</label>
                                    <input type="text" id="duration" name="duration" class="form-control"
                                        placeholder="Duration" maxlength="3" value="{{ @$course->duration }}">
                                    <span class="text-danger error-text duration_error"></span>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label">Should be followed by?</label>
                                <select class="form-select" data-control="select2" data-placeholder="Select Course List"
                                    data-hide-search="true" id="course_followed_by" name="course_followed_by">
                                    @php
                                        if (isset($course->id)) {
                                            $courses = \App\Models\Course::where('id', '!=', $course->id)->get();
                                        } else {
                                            $courses = \App\Models\Course::all();
                                        }
                                    @endphp
                                    <option value="">Select Course</option>
                                    @foreach ($courses as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($course->course_followed_by) && $course->course_followed_by == $item->id ? 'selected' : '' }}>
                                            {{ $item->course_name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error-text course_followed_by_error"></span>
                            </div>
                            <div class="col-lg-5 col-xs-12">
                                <div class="d-flex align-items-center pt-6">
                                    <div class="form-check me-8">
                                        <input class="form-check-input"
                                            {{ isset($course->course_repeated) && $course->course_repeated == 1 ? 'checked' : '' }}
                                            id="course_repeated" name="course_repeated" type="checkbox"
                                            value="1" />
                                        <label class="form-check-label text-gray-800" for="course_repeated">
                                            Course should be repeated after intervals?
                                        </label>
                                    </div>
                                    <div class="w-200px">
                                        @php
                                            $course_intervals = [1, 2, 3, 4, 5, 6];
                                        @endphp
                                        <select id="course_intervals" name="course_intervals" class="form-select w-200px"
                                            data-control="select2" data-placeholder="Select No of Years"
                                            data-hide-search="true">
                                            <option value="">None</option>
                                            @foreach ($course_intervals as $item)
                                                <option value="{{ $item }}"
                                                    {{ isset($course->course_intervals) && $course->course_intervals == $item ? 'selected' : '' }}>
                                                    {{ $item }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-text course_intervals_error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label" for="fileInput">Upload Logo</label>
                                <div class="row">
                                    <div class="col-lg-9">
                                        <input type="file" id="fileInput" name="course_logo" class="form-control"
                                            accept=".jpg,.png,.jpeg">
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="preview" id="preview">
                                            <img src="{{ !empty($course->course_logo) ? $course->course_logo : 'https://placehold.co/100x100/eee/bbb?text=Logo' }}"
                                                alt="course_logo">
                                        </div>
                                    </div>
                                </div>
                                <span class="text-danger error-text course_logo_error"></span>
                            </div>
                        </div>
                        <hr class="dashed">
                        <div class="row mb-5">
                            @php
                                $priorities = ['Highest' => 1, 'Medium' => 2, 'Lowest' => 3];
                            @endphp
                            <div class="col-lg-4">
                                <!-- Online Mode Priority -->
                                <div class="form-group">
                                    <label class="form-label">Online Mode Priority:</label>
                                    <select class="form-select" data-control="select2" data-placeholder="Select Priority"
                                        data-hide-search="true" data-allow-clear="true" id="online_priority"
                                        name="online_priority">
                                        <option value="">Select Priority</option>
                                        @foreach ($priorities as $item => $value)
                                            <option value="{{ $value }}"
                                                {{ isset($course->online_priority) && $course->online_priority == $value ? 'selected' : '' }}>
                                                {{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-label">Offline Mode Priority:</label>
                                    <select class="form-select" data-control="select2" data-placeholder="Select Priority"
                                        data-hide-search="true" data-allow-clear="true" id="offline_priority"
                                        name="offline_priority">
                                        <option value="">Select Priority</option>
                                        @foreach ($priorities as $item => $value)
                                            <option value="{{ $value }}"
                                                {{ isset($course->offline_priority) && $course->offline_priority == $value ? 'selected' : '' }}>
                                                {{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-label">E-Learning Mode Priority:</label>
                                    <select class="form-select" data-control="select2" data-placeholder="Select Priority"
                                        data-hide-search="true" data-allow-clear="true" id="elearning_priority"
                                        name="elearning_priority">
                                        <option value="">Select Priority</option>
                                        @foreach ($priorities as $item => $value)
                                            <option value="{{ $value }}"
                                                {{ isset($course->elearning_priority) && $course->elearning_priority == $value ? 'selected' : '' }}>
                                                {{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr class="dashed">
                        <div class="row mb-5">
                            <div class="col-lg-6">
                                <label class="form-label">Comments</label>
                                <textarea id="course_comments" name="course_comments" class="form-control" data-kt-autosize="true"
                                    placeholder="Comments">{{ @$course->course_comments }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab card card-flush p-5">
                    <div class="s-details">
                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <div class="step-details">
                                    <p class="step-details-head">Course Name</p>
                                    <p class="step-details-content" id="coursename_field"></p>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="step-details">
                                    <p class="step-details-head">Course By</p>
                                    <p class="step-details-content" id="courseby_field"></p>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="step-details">
                                    <p class="step-details-head">Duration</p>
                                    <p class="step-details-content" id="duration_field"></p>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="step-details">
                                    <p class="step-details-head">Training Centers</p>
                                    <p class="step-details-content" id="tc_field"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h3>Course Mapping<h3>
                            <div class="mb-5 fv-row fv-plugins-icon-container row gy-3">
                                <div class="col-md-4">
                                    <label for="categories" class="form-label ">Select Categories</label>
                                    <select class="form-select" id="categories" name="categories[]"
                                        data-control="select2" data-close-on-select="false"
                                        data-placeholder="Select Categories" data-allow-clear="true" multiple="multiple">
                                        <option value="0">All</option>
                                        @foreach (\App\Models\Category::where('status', 1)->get() as $item)
                                            <option value="{{ $item->id }}"
                                                {{ isset($course->categories) && in_array($item->id, explode(',', $course->categories)) ? 'selected' : '' }}>
                                                {{ $item->cat_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="subcategories" class="form-label ">Select SubCategories</label>
                                    <select class="form-select" id="subcategories" name="subcategories[]"
                                        data-control="select2" data-close-on-select="false"
                                        data-placeholder="Select SubCategories" data-allow-clear="true"
                                        multiple="multiple">
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="vessels" class="form-label ">Select Vessels</label>
                                    <select class="form-select" id="vessels" name="vessels[]" data-control="select2"
                                        data-close-on-select="false" data-placeholder="Select Vessels"
                                        data-allow-clear="true" multiple="multiple">
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="course_type" class="form-label">Course Type</label>
                                    <select class="form-select" id="course_type" name="course_type[]"
                                        data-control="select2" data-close-on-select="false"
                                        data-placeholder="Select Course Type" data-allow-clear="true"
                                        multiple="multiple">
                                        <option value="0">All</option>
                                        @foreach (\App\Models\CourseType::where('status', 1)->get() as $item)
                                            <option value="{{ $item->id }}"
                                                {{ isset($course->course_type) && in_array($item->id, explode(',', $course->course_type)) ? 'selected' : '' }}>
                                                {{ $item->type_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="departments" class="form-label ">Select Department</label>
                                    <select class="form-select" id="departments" name="departments[]"
                                        data-control="select2" data-close-on-select="false"
                                        data-placeholder="Select Department" data-allow-clear="true" multiple="multiple">
                                        <option value="0">All</option>
                                        @foreach (\App\Models\Department::where('status', 1)->get() as $item)
                                            <option value="{{ $item->id }}"
                                                {{ isset($course->departments) && in_array($item->id, explode(',', $course->departments)) ? 'selected' : '' }}>
                                                {{ $item->dep_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="ranks" class="form-label ">Select Ranks</label>
                                    <select class="form-select" id="ranks" name="ranks[]" data-control="select2"
                                        data-close-on-select="false" data-placeholder="Select Ranks"
                                        data-allow-clear="true" multiple="multiple">
                                    </select>
                                </div>

                            </div>
                            <div class="row" id="RankPrioritydiv">
                            </div>
                </div>
            </form>
            <div style="overflow:auto;">
                <div style="float:right;">
                    <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                    <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
                </div>
            </div>
            <!-- Circles which indicates the steps of the form: -->
            {{-- <div style="text-align:center;margin-top:40px;">
                <span class="step"></span>
                <span class="step"></span>
            </div> --}}
        </div>
    </div>

@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>

    <script>
        //form jquery
        var currentTab = 0; // Current tab is set to be the first tab (0)
        showTab(currentTab); // Display the current tab
        $("#addEditForm").validate({
            errorClass: 'errors',
            rules: {
                course_name: {
                    required: true
                },
                // course_code: "required",
                // duration: "required",
            },
            messages: {
                course_name: {
                    required: "Please enter course name",
                },
                // course_code: "Please enter course code",
                // duration: "Please add duration",
            }
        });

        function showTab(n) {
            // This function will display the specified tab of the form...
            var x = document.getElementsByClassName("tab");
            x[n].style.display = "block";
            //... and fix the Previous/Next buttons:
            if (n == 0) {
                document.getElementById("prevBtn").style.display = "none";
            } else {
                document.getElementById("prevBtn").style.display = "inline";
            }
            if (n == (x.length - 1)) {
                document.getElementById("nextBtn").innerHTML = "Submit";
            } else {
                document.getElementById("nextBtn").innerHTML = "Next";
            }
            //... and run a function that will display the correct step indicator:
            //fixStepIndicator(n)
        }

        function nextPrev(n) {

            // This function will figure out which tab to display
            var x = document.getElementsByClassName("tab");
            if (n == -1) {
                x[currentTab].style.display = "none";
                currentTab = currentTab - 1;
                showTab(currentTab);
                return false;
            }
            // Increase or decrease the current tab by 1:
            if (currentTab == 0) {

                var variable = $("#addEditForm").valid();
                if (variable) {
                    var onlinePriority = $('#online_priority').val();
                    var offlinePriority = $('#offline_priority').val();
                    var elearningPriority = $('#elearning_priority').val();

                    // Check if all three priorities are different
                    if ((onlinePriority && offlinePriority && onlinePriority === offlinePriority) ||
                        (onlinePriority && elearningPriority && onlinePriority === elearningPriority) ||
                        (offlinePriority && elearningPriority && offlinePriority === elearningPriority)) {
                        toastr.error("Each priority must be different for Online, Offline, and E-Learning modes.");
                        return false;
                    }


                    var form = document.getElementById("addEditForm");
                    var formdata = new FormData(form);
                    formdata.append('step', 1);
                    $.ajax({
                        url: $(form).attr('action'),
                        method: $(form).attr('method'),
                        data: formdata,
                        processData: false,
                        dataType: "json",
                        contentType: false,
                        beforeSend: function() {
                            toastr.remove();
                            $(form).find('input, select, textarea').removeClass('is-invalid');
                            $(form).find('span.error-text').text('');
                        },
                        success: function(response) {
                            toastr.remove();
                            //console.log(response);
                            if (response.success) {
                                toastr.success(response.message);
                                $('#course_id').val(response.course_id); // Or handle it in another way


                            } else {
                                toastr.error(response.message);
                            }
                            x[currentTab].style.display = "none";
                            currentTab = currentTab + 1;

                            //load info on step 2
                            $('#coursename_field').html($('#course_name').val() + ' (' + $('#course_code')
                                .val() + ')');
                            $('#courseby_field').html($('input[name="course_by"]:checked').next(
                                '.form-check-label').text());
                            $('#duration_field').html($('#duration').val() + ' days');

                            var trainingCenterNames = {};
                            $('#training_center option').each(function() {
                                trainingCenterNames[$(this).val()] = $(this).text();
                            });

                            var selectedValues = $('#training_center').val();
                            var selectedNames = [];
                            if (selectedValues) {
                                selectedValues.forEach(function(value) {
                                    selectedNames.push(trainingCenterNames[value]);
                                });
                            }
                            $('#tc_field').html((selectedNames.length > 0 ? selectedNames.join(', ') : 'None'));

                            showTab(currentTab);
                        },
                        error: function(response) {
                            toastr.remove();
                            $.each(response.responseJSON.errors, function(prefix, val) {
                                $(form).find('span.' + prefix + '_error').text(val[0]);
                                $(form).find('[name="' + prefix + '"]').addClass('is-invalid');
                            });
                        }
                    });
                }
            }
            if (currentTab == 1) {
                var form = document.getElementById("addEditForm");
                var formdata = new FormData(form);
                //formdata.append('course_id', $('#course_id').val());
                formdata.append('step', 2);
                $.ajax({
                    url: $(form).attr('action'),
                    method: $(form).attr('method'),
                    data: formdata,
                    processData: false,
                    dataType: "json",
                    contentType: false,
                    beforeSend: function() {
                        toastr.remove();
                        $(form).find('input, select, textarea').removeClass('is-invalid');
                        $(form).find('span.error-text').text('');
                    },
                    success: function(response) {
                        toastr.remove();
                        if (response.success) {
                            toastr.success(response.message);
                            setTimeout(() => {
                                location.href = "{{ route('course-list') }}";
                            }, 2000);
                            setTimeout(() => {
                                location.href = "{{ route('course-list') }}";
                            }, 2000);
                        } else {
                            toastr.error(response.message);
                        }

                    },
                    error: function(response) {
                        toastr.remove();
                        $.each(response.responseJSON.errors, function(prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                            $(form).find('[name="' + prefix + '"]').addClass('is-invalid');
                        });
                    }
                });
                return false;
            }
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // $("form#addEditForm").submit(function(e) {
        //     e.preventDefault();
        //     var form = this;
        //     var formdata = new FormData(form);
        //     $.ajax({
        //         url: $(form).attr('action'),
        //         method: $(form).attr('method'),
        //         data: formdata,
        //         processData: false,
        //         dataType: "json",
        //         contentType: false,
        //         beforeSend: function() {
        //             toastr.remove();
        //             $(form).find('input, select, textarea').removeClass('is-invalid');
        //             $(form).find('span.error-text').text('');
        //             toastr.info('Please wait...');
        //         },
        //         success: function(response) {
        //             toastr.remove();
        //             if (response.success) {
        //                 toastr.success(response.message);
        //             } else {
        //                 toastr.error(response.message);
        //             }
        //             showTab(currentTab);
        //         },
        //         error: function(response) {
        //             toastr.remove();
        //             $.each(response.responseJSON.errors, function(prefix, val) {
        //                 $(form).find('span.' + prefix + '_error').text(val[0]);
        //                 $(form).find('[name="' + prefix + '"]').addClass('is-invalid');
        //             });
        //         }
        //     });
        // });

        $('#fileInput').on('change', function() {
            var file = this.files[0];
            var maxSize = 500 * 1024; // 500KB in bytes

            if (file) {
                // Check file size
                if (file.size > maxSize) {
                    toastr.error('The image size should not exceed 500KB.');
                    // Clear the file input and image preview
                    $('#fileInput').val('');
                    //$('#preview img').attr('src', 'https://placehold.co/100x100/eee/bbb?text=Logo');
                    return;
                }

                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview img').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });
        $(document).ready(function() {
            //trigger change event on load wire

            var selectedCategories = $('#categories').val(); // Get the initially selected categories
            //var selectedsubCategories = $('#subcat_id').val();
            var selectedDepartments = $('#departments').val();
            // Trigger the 'change' event if there are any pre-selected categories
            if (selectedCategories && selectedCategories.length > 0) {
                get_subcategories();
                //get_vessels();
            }

            // if (selectedsubCategories && selectedsubCategories.length > 0) {
            //     get_vessels();
            // }

            if (selectedDepartments && selectedDepartments.length > 0) {
                get_ranks();
            }


            // When the category dropdown changes
            var lastSelectedValue = null; // Variable to store the last selected value

            $('#categories').on('change', function(event) {
                var selectedCategories = $(this).val(); // Get selected options
                var selectedValues = $('#categories').val();  // Get the selected values as an array

                // if (selectedCategories.includes("0")) {
                //     //alert("all selected");
                //     // $('#categories').val(null).trigger('change');

                //     // If "All" is selected, unselect all other options
                //     $('#categories').val(["0"]).trigger('change');
                // } else {
                //     // If any other options are selected, unselect "All" option
                //     // var index = selectedCategories.indexOf("0");
                //     // if (index !== -1) {
                //     //     selectedCategories.splice(index, 1);  // Remove "All" from selected
                //     //     $('#categories').val(selectedCategories).trigger('change');
                //     // }
                // }

                // Call your functions based on the selected categories
                if (selectedCategories && selectedCategories.length > 0) {
                    get_subcategories();
                }

                // // Update the data for previous selections
                // $(event.target).find('option').each(function() {
                //     $(this).data('previouslySelected', $(this).prop('selected'));
                // });
            });


            $('#subcategories').on('change', function() {
                var selectedSubcategories = $(this).val();
                if (selectedSubcategories && selectedSubcategories.length > 0) {
                    get_vessels();
                }
            });
            $('#departments').on('change', function() {
                var selectedDepartments = $(this).val();
                if (!selectedDepartments || selectedDepartments.length > 0) {
                    get_ranks();
                }
            })
            $('#ranks').on('change', function() {
                var selectedRanks = $(this).find('option:selected');
                const selectedRanksContainer = $('#RankPrioritydiv');
                selectedRanksContainer.html(''); // Clear previous content

                // Loop through selected ranks and display in the container
                $.each(selectedRanks, function(index, rankOption) {
                    var rankName = $(rankOption).text();
                    var deptName = $(rankOption).data('dept-name');

                    // Create HTML for displaying rank and department with radio buttons
                    let rankHtml = `<div class="col-md-6">
            <div class="rank-item">
                <div class="rank-info">
                    <span>${rankName} (${deptName})</span>
                </div>
                <div class="radio-buttons">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="rank_status_${rankOption.value}" value="mandatory">
                        Mandatory
                    </label>
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="rank_status_${rankOption.value}" value="recommended">
                        Recommended
                    </label>
                </div>
            </div>
        </div>`;

                    // Append the rank item to the container
                    selectedRanksContainer.append(rankHtml);
                });
            });
        });

        function get_subcategories() {
            var formdata = new FormData();
            formdata.append('cat_id', $('#categories').val());
            $.ajax({
                url: "{{ route('get-multiple-subcategories') }}",
                method: "POST",
                data: formdata,
                processData: false,
                dataType: "json",
                contentType: false,
                beforeSend: function() {
                    toastr.remove();
                },
                success: function(response) {
                    if (response.success) {
                        const subcategoriesSelect = $('#subcategories');
                        subcategoriesSelect.empty(); // Clear previous content
                        let selectedSubcatIds = $('#subcat_id').val().split(',').map(function(id) {
                            return id.trim();
                        });
                        // Loop through the response data (categories and subcategories)
                        $.each(response.data, function(index, category) {
                            // Create optgroup for each category
                            let optgroupHtml =
                                `<optgroup label="${category.cat_name}">`;

                            // Loop through subcategories and append them as options
                            $.each(category.subcategories, function(i,
                                subcategory) {
                                let isSelected = selectedSubcatIds.includes(subcategory.id
                                    .toString()) ? 'selected' : '';

                                optgroupHtml +=
                                    `<option value="${subcategory.id}" ${isSelected}>${subcategory.subcat_name}</option>`;

                            });

                            optgroupHtml += `</optgroup>`; // End the optgroup
                            // Append the optgroup to the select element
                            subcategoriesSelect.append(optgroupHtml);

                        });
                        get_vessels();
                    }
                }
            });
        }

        function get_vessels() {
            var formdata = new FormData();
            formdata.append('subcat_id', $('#subcategories').val());
            console.log($('#subcategories').val());
            $.ajax({
                url: "{{ route('get-vessels') }}", // Adjust to your route
                method: "POST",
                data: formdata,
                processData: false,
                dataType: "json",
                contentType: false,
                beforeSend: function() {
                    toastr.remove();
                },
                success: function(response) {
                    if (response.success) {
                        //console.log(response);
                        const vesselsSelect = $('#vessels');
                        vesselsSelect.empty(); // Clear previous content
                        let selectedVesselIds = $('#vessel_id').val().split(',').map(function(id) {
                            return id.trim();
                        });
                        $.each(response.data, function(index, subcategory) {
                            let optgroupHtml =
                                `<optgroup label="${subcategory.subcat_name}">`;

                            // Loop through subcategories and append them as options
                            $.each(subcategory.vessels, function(i,
                                vessel) {
                                let isSelected = selectedVesselIds.includes(vessel.id
                                    .toString()) ? 'selected' : '';

                                optgroupHtml +=
                                    `<option value="${vessel.id}" ${isSelected}>${vessel.vessel_name}</option>`;
                            });

                            optgroupHtml += `</optgroup>`; // End the optgroup

                            // Append the optgroup to the select element
                            vesselsSelect.append(optgroupHtml);
                        });

                    }
                }
            });
        }

        function get_ranks() {
            var formdata = new FormData();
            formdata.append('dept_id', $('#departments').val());
            $.ajax({
                url: "{{ route('get-multiple-ranks') }}",
                method: "POST",
                data: formdata,
                processData: false,
                dataType: "json",
                contentType: false,
                beforeSend: function() {
                    toastr.remove();
                },
                success: function(response) {
                    if (response.success) {
                        const ranksSelect = $('#ranks');
                        ranksSelect.empty(); // Clear previous content
                        let selectedRankIds = $('#rank_id').val().split(',').map(function(id) {
                            return id.trim();
                        });
                        let selectedRankPriorityIds = $('#rank_priority').val().split(',').map(function(id) {
                            return id.trim();
                        });
                        const selectedRanksContainer = $('#RankPrioritydiv');
                        selectedRanksContainer.html('');
                        // Loop through the response data (categories and subcategories)
                        $.each(response.data, function(index, dept) {
                            // Create optgroup for each category
                            let optgroupHtml =
                                `<optgroup label="${dept.dep_name}">`;

                            // Loop through subcategories and append them as options
                            $.each(dept.ranks, function(i,
                                rank) {
                                let isSelected = selectedRankIds.includes(rank.id
                                    .toString()) ? 'selected' : '';

                                optgroupHtml +=
                                    `<option value="${rank.id}" ${isSelected}>${rank.rank_name}</option>`;

                                if (isSelected == 'selected') {
                                    // Determine the rank's priority from the selectedRankPriorityIds array
                                    let rankPriority = selectedRankPriorityIds[selectedRankIds
                                        .indexOf(rank.id.toString())];


                                    // Determine which radio should be checked based on rankPriority
                                    let isMandatoryChecked = rankPriority === '1' ?
                                        'checked' : '';
                                    let isRecommendedChecked = rankPriority === '0' ?
                                        'checked' : '';

                                    let rankHtml = `<div class="col-md-6">
                                    <div class="rank-item">
                                        <div class="rank-info">
                                            <span>${rank.rank_name} (${dept.dep_name})</span>
                                        </div>
                                        <div class="radio-buttons">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="radio" name="rank_status_${rank.id}" value="mandatory" ${isMandatoryChecked}>
                                                Mandatory
                                            </label>
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="radio" name="rank_status_${rank.id}" value="recommended" ${isRecommendedChecked}>
                                                Recommended
                                            </label>
                                        </div>
                                    </div>
                                </div>`;

                                    // Append the rank item to the container
                                    selectedRanksContainer.append(rankHtml);
                                }
                            });

                            optgroupHtml += `</optgroup>`; // End the optgroup

                            // Append the optgroup to the select element
                            ranksSelect.append(optgroupHtml);
                        });


                    }
                }
            });
        }
    </script>
@endpush
