@extends('layouts.admin-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Dashboard')
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
            <form id="addEditForm" action="{{ route('save-course') }}" method="POST" enctype="multipart/form-data"
                class="card card-flush">
                @csrf
                <input type="hidden" id="course_id" name="course_id" value="{{ @$course->id }}">
                <div class="card-body">
                    <div class="mb-5 fv-row fv-plugins-icon-container row gy-3">
                        <div class="col-lg-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" id="cat_id" name="cat_id" data-control="select2"
                                data-close-on-select="false" data-placeholder="Select Category" data-hide-search="true">
                                <option value=""></option>
                                @foreach (\App\Models\Category::where('status',1)->get() as $item)
                                    <option value="{{ $item->id }}"
                                        {{ isset($course->cat_id) && $course->cat_id == $item->id ? 'selected' : '' }}>
                                        {{ $item->cat_name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text cat_id_error"></span>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label">Sub Category</label>
                            <select class="form-select" id="subcat_id" name="subcat_id[]" data-control="select2"
                                data-close-on-select="false" data-placeholder="Select Sub Category" data-allow-clear="true"
                                multiple="multiple">
                                @if (isset($course))
                                    @foreach (\App\Models\SubCategory::where('status',1)->get() as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($course->subcat_id) && in_array($item->id, explode(',', $course->subcat_id)) ? 'selected' : '' }}>
                                            {{ $item->subcat_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <span class="text-danger error-text subcat_id_error"></span>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label">Department</label>
                            <select class="form-select" id="dep_id" name="dep_id" data-control="select2"
                                data-placeholder="Select Department" data-hide-search="true">
                                <option value="0">All</option>
                                @foreach (\App\Models\Department::where('status',1)->get() as $item)
                                    <option value="{{ $item->id }}"
                                        {{ isset($course->dep_id) && $course->dep_id == $item->id ? 'selected' : '' }}>
                                        {{ $item->dep_name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text dep_id_error"></span>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label">Rank</label>
                            <select class="form-select" id="rank_id" name="rank_id[]" data-control="select2"
                                data-close-on-select="false" data-placeholder="Select Rank" data-allow-clear="true"
                                multiple="multiple">
                                <option value="0"
                                    {{ isset($course->rank_id) && $course->rank_id == 0 ? 'selected' : '' }}>All</option>
                                @if (isset($course))
                                    @foreach (\App\Models\Rank::where('status',1)->get() as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($course->rank_id) && in_array($item->id, explode(',', $course->rank_id)) ? 'selected' : '' }}>
                                            {{ $item->rank_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <span class="text-danger error-text rank_id_error"></span>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label">Course Name</label>
                            <input type="text" id="course_name" name="course_name" class="form-control"
                                placeholder="Course Name" value="{{ @$course->course_name }}">
                            <span class="text-danger error-text course_name_error"></span>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label">Course Code</label>
                            <input type="text" id="course_code" name="course_code" class="form-control"
                                placeholder="Course Code" value="{{ @$course->course_code }}">
                            <span class="text-danger error-text course_code_error"></span>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label">Course By</label>
                            <div>
                                <label class="form-check-image active me-6">
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input" id="course_by_molmi" name="course_by"
                                            type="radio"
                                            {{ isset($course->course_by) && $course->course_by == 1 ? 'checked' : '' }}
                                            value="1" />
                                        <div class="form-check-label fs-6 text-gray-800">
                                            MOLMI
                                        </div>
                                    </div>
                                </label>
                                <label class="form-check-image">
                                    <div class="form-check form-check-custom form-check-solid me-10">
                                        <input class="form-check-input" id="course_by_external" name="course_by"
                                            type="radio"
                                            {{ isset($course->course_by) && $course->course_by == 2 ? 'checked' : '' }}
                                            value="2" />
                                        <div class="form-check-label fs-6 text-gray-800">
                                            External
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <span class="text-danger error-text course_by_error"></span>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label">Upload Logo</label>
                            <div class="row">
                                <div class="col-lg-9">
                                    <input type="file" id="fileInput" name="course_logo" class="form-control"
                                        accept="image/*">
                                </div>
                                <div class="col-lg-3">
                                    <div class="preview" id="preview"></div>
                                </div>
                            </div>
                            <span class="text-danger error-text course_logo_error"></span>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label"> Training Center</label>
                            <select class="form-select" id="tc_id" name="tc_id" data-control="select2"
                                data-placeholder="Select Training Center" data-hide-search="true">
                                @foreach (\App\Models\TrainingCenter::all() as $item)
                                    <option value="{{ $item->id }}"
                                        {{ isset($course->tc_id) && $course->tc_id == $item->id ? 'selected' : '' }}>
                                        {{ $item->center_name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text tc_id_error"></span>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label class="form-label">Duration (In Days)</label>
                                <input type="text" id="duration" name="duration" class="form-control"
                                    placeholder="Duration" maxlength="3" value="{{ @$course->duration }}">
                                <span class="text-danger error-text duration_error"></span>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            @php
                                $course_types = ['Simulator Based', 'Vessel Specific', 'Classroom Based', 'CBT'];
                            @endphp
                            <label class="form-label"> Course Type</label>
                            <select class="form-select" id="course_type" name="course_type" data-control="select2"
                                data-placeholder="Select Course Type" data-hide-search="true">
                                @foreach ($course_types as $item)
                                    <option value="{{ $item }}"
                                        {{ isset($course->course_type) && $course->course_type == $item ? 'selected' : '' }}>
                                        {{ $item }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text course_type_error"></span>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label">Mode</label>
                            <div>
                                <label class="form-check-image active me-6">
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input"
                                            {{ isset($course->course_mode) && $course->course_mode == 1 ? 'checked' : '' }}
                                            type="radio" value="1" id="course_mode_online" name="course_mode" />
                                        <div class="form-check-label fs-6 text-gray-800">
                                            Online
                                        </div>
                                    </div>
                                </label>
                                <label class="form-check-image">
                                    <div class="form-check form-check-custom form-check-solid me-10">
                                        <input class="form-check-input"
                                            {{ isset($course->course_mode) && $course->course_mode == 2 ? 'checked' : '' }}
                                            type="radio" value="2" id="course_mode_offline" name="course_mode" />
                                        <div class="form-check-label fs-6 text-gray-800">
                                            Offline
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <span class="text-danger error-text course_mode_error"></span>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label">Choose Option</label>
                            <div>
                                <label class="form-check-image active me-6">
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input" type="radio"
                                            {{ isset($course->course_priority) && $course->course_priority == 1 ? 'checked' : '' }}
                                            value="1" id="course_priority_mandatory" name="course_priority" />
                                        <div class="form-check-label fs-6 text-gray-800">
                                            Mandatory
                                        </div>
                                    </div>
                                </label>
                                <label class="form-check-image">
                                    <div class="form-check form-check-custom form-check-solid me-10">
                                        <input class="form-check-input" type="radio"
                                            {{ isset($course->course_priority) && $course->course_priority == 2 ? 'checked' : '' }}
                                            value="2" id="course_priority_recommended" name="course_priority" />
                                        <div class="form-check-label fs-6 text-gray-800">
                                            Recommended
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <span class="text-danger error-text course_priority_error"></span>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label"> Should be followed by?</label>
                            <select class="form-select" data-control="select2" data-placeholder="Select Course List"
                                data-hide-search="true" id="course_followed_by" name="course_followed_by">
                                @php
                                    if (isset($course->id)) {
                                        $courses = \App\Models\Course::where('id', '!=', $course->id)->get();
                                    } else {
                                        $courses = \App\Models\Course::all();
                                    }
                                @endphp
                                @foreach ($courses as $item)
                                    <option value="{{ $item->id }}"
                                        {{ isset($course->course_followed_by) && $course->course_followed_by == $item->id ? 'selected' : '' }}>
                                        {{ $item->course_name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text course_followed_by_error"></span>
                        </div>
                        <div class="col-lg-6">
                            <div class="d-flex align-items-center pt-6">
                                <div class="form-check me-8">
                                    <input class="form-check-input"
                                        {{ isset($course->course_repeated) && $course->course_repeated == 1 ? 'checked' : '' }}
                                        id="course_repeated" name="course_repeated" type="checkbox" value="1" />
                                    <label class="form-check-label text-gray-800" for="course_repeated">
                                        Course should be repeated after intervals?
                                    </label>
                                </div>
                                <div class="w-200px">
                                    @php
                                        $course_intervals = [2, 3, 5, 6];
                                    @endphp
                                    <select id="course_intervals" name="course_intervals" class="form-select w-200px"
                                        data-control="select2" data-placeholder="Select No of Years"
                                        data-hide-search="true">
                                        <option value="">None</option>
                                        @foreach ($course_intervals as $item)
                                            <option value="{{ $item }}" {{ (isset($course->course_intervals) && $course->course_intervals == $item) ? 'selected' : '' }}>{{ $item }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text course_intervals_error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Comments</label>
                            <textarea id="course_comments" name="course_comments" class="form-control" data-kt-autosize="true"
                                placeholder="Comments">{{ @$course->course_comments }}</textarea>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('course-list') }}" class="btn btn-sm btn-light me-5">Cancel</a>
                        <button type="submit" class="btn btn-sm btn-primary">
                            <span class="indicator-label">Save</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("form#addEditForm").submit(function(e) {
            e.preventDefault();
            var form = this;
            var formdata = new FormData(form);
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
                    if (response.success) {
                        toastr.success(response.message);
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
        });

        $("#cat_id").on('change', function(e) {
            if ($(this).val() != 0) {
                get_subcategories($(this).val());
            }
        });

        $("#dep_id").on('change', function(e) {
            //if ($(this).val() != 0) {
            get_ranks($(this).val());
            //}
        });

        function get_subcategories(cat_id) {
            var formdata = new FormData();
            formdata.append('cat_id', cat_id);
            $.ajax({
                url: "{{ route('get-subcategories') }}",
                method: "POST",
                data: formdata,
                processData: false,
                dataType: "json",
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        let opt = '';
                        if (response.data.length > 0) {
                            $('#subcat_id').select2('destroy');
                            response.data.forEach(element => {
                                opt += `<option value="${element.id}">${element.subcat_name}</option>`;
                            });
                        } else {
                            opt += `<option value="">None</option>`;
                        }
                        $("#subcat_id").html(opt);
                        $('#subcat_id').select2();
                        $('#subcat_id').val(null).trigger('change');
                    } else {
                        toastr.error(response.message);
                    }
                }
            });
        }

        function get_ranks(dep_id) {
            var formdata = new FormData();
            formdata.append('dep_id', dep_id);
            $.ajax({
                url: "{{ route('get-ranks') }}",
                method: "POST",
                data: formdata,
                processData: false,
                dataType: "json",
                contentType: false,
                success: function(response) {
                    let opt = '';
                    $('#rank_id').select2('destroy');
                    if (response.success) {
                        if (response.data.length > 0) {
                            opt += `<option value="0">All</option>`;
                            response.data.forEach(element => {
                                opt += `<option value="${element.id}">${element.rank_name}</option>`;
                            });
                        } else {
                            opt += `<option value="0">All</option>`;
                        }
                    } else {
                        opt += `<option value="0">All</option>`;
                    }
                    $("#rank_id").html(opt);
                    $('#rank_id').select2();
                    $('#rank_id').val(null).trigger('change');
                }
            });
        }
    </script>
@endpush
