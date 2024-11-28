@extends('layouts.admin-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Dashboard')
@section('content')
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-3">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Course
                    Details</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-gray-700">Course Details</li>
                </ul>
            </div>
            <div>
                <a href="javascript:void(0);" class="btn btn-sm btn-flex btn-secondary fw-bold me-2" id="filter">
                    <i class="ki-duotone ki-filter fs-6 text-muted me-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>Filter</a>
                <a href="{{ route('add-course') }}" class="btn btn-sm btn-primary"><i
                        class="ki-outline ki-plus fs-2"></i>Add
                    Course</a>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="card card-flush mb-4 filter-card">
                <div class="card-body py-4">
                    <div class="filter">
                        <div class="row mb-5">
                            @php
                                //$course_types = ['Simulator Based', 'Vessel Specific', 'Classroom Based', 'CBT'];
                                $course_by = ['Molmi' => 1, 'External' => 2];
                                $centers = \App\Models\TrainingCenter::all();
                                $categories = \App\Models\Category::where('status', 1)->get();
                                $departments = \App\Models\Department::where('status', 1)->get();
                            @endphp
                            <div class="col-lg-3">
                                <label class="form-label">Category</label>
                                <select class="form-select" data-control="select2" data-placeholder="Select Category"
                                    data-hide-search="true" name="categories" id="categories">
                                    <option value="0">Select Category</option>
                                    @foreach ($categories as $item)
                                        <option value="{{ $item->id }}">{{ $item->cat_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label">Sub Category</label>
                                <select class="form-select" data-control="select2" data-placeholder="Select Sub Category"
                                    data-hide-search="true" name="subcategories" id="subcategories">
                                    <option value="0">Select Subcategory</option>
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label">Vessel</label>
                                <select class="form-select" data-control="select2" data-placeholder="Select Vessel"
                                    data-hide-search="true" name="vessels" id="vessels">
                                    <option value="0">Select Vessel</option>
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label">Course Type</label>
                                <select class="form-select" data-control="select2" data-placeholder="Select Course Type"
                                    data-hide-search="true" name="course_type" id="course_type">
                                    <option value="0">All</option>
                                    @foreach (\App\Models\CourseType::where('status', 1)->get() as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($course->course_type) && in_array($item->id, explode(',', $course->course_type)) ? 'selected' : '' }}>
                                            {{ $item->type_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3">
                                <label class="form-label">Department</label>
                                <select class="form-select" data-control="select2" data-placeholder="Select Department"
                                    data-hide-search="true" name="departments" id="departments">
                                    <option value="0">All</option>
                                    @foreach ($departments as $item)
                                        <option value="{{ $item->id }}">{{ $item->dep_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label">Rank</label>
                                <select class="form-select" data-control="select2" data-placeholder="Select Rank"
                                    data-hide-search="true" name="ranks" id="ranks">
                                    <option value="0">All</option>
                                </select>
                            </div>

                            <div class="col-lg-3">
                                <label class="form-label">Course By</label>
                                <select class="form-select" data-control="select2" data-placeholder="Select"
                                    data-hide-search="true" name="course_by" id="course_by">
                                    <option value="0">All</option>
                                    <option value="1">Molmi</option>
                                    <option value="2">External</option>
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label">Training Center</label>
                                <select class="form-select" data-control="select2" data-placeholder="Select Center"
                                    data-hide-search="true" name="training_center" id="training_center">
                                    <option value="0">All</option>
                                    @foreach (\App\Models\TrainingCenter::all() as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($course->training_center) && in_array($item->id, explode(',', $course->training_center)) ? 'selected' : '' }}>
                                            {{ $item->center_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="pt-8 d-flex justify-content-end">
                                <button type="button" class="btn btn-sm btn-border-primary"
                                    onclick="return applyFilter();">Apply Filter</button>
                                <button type="button" class="btn btn-sm" onclick="return resetFilter();">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-flush">
                <div class="card-body">
                    <table class="table align-middle table-row-dashed fs-6 gy-3 checkbox-table" id="course_table">
                        <thead>
                            <tr class="text-start fw-600 fs-6 gs-0">
                                <th class="min-w-40px">Sr. No.</th>
                                <th class="min-w-100px">Course Details</th>
                                {{-- <th class="min-w-100px">Priorities</th> --}}
                                <th class="min-w-100px">Course By</th>
                                <th class="min-w-100px">Center Names</th>
                                <th class="min-w-125px">Status</th>
                                <th class="min-w-40px">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-800">
                        </tbody>
                    </table>
                </div>
            </div>
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
        var table = $("#course_table").DataTable({
            processing: true,
            serverSide: true,
            //responsive: true,
            aLengthMenu: [
                [10, 15, 25, 50, 100, -1],
                [10, 15, 25, 50, 100, "All"]
            ],

            ajax: {
                url: "{{ route('getCourses') }}",
                method: "POST",
                data: function(data) {
                    data.course_type = $("#course_type").val();
                    data.cat_id = $("#categories").val();
                    data.subcat_id = $("#subcategories").val();
                    data.vessel_id = $("#vessels").val();
                    data.dep_id = $("#departments").val();
                    data.rank_id = $("#ranks").val();
                    data.training_center = $('#training_center').val();
                    data.course_by = $('#course_by').val();
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: true,
                },
                {
                    data: 'course_details',
                    name: 'course_details',
                    orderable: true,
                    searchable: true,
                },

                {
                    data: 'course_by',
                    name: 'course_by',
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'center_names',
                    name: 'center_names',
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                },
            ],

            "language": {
                "lengthMenu": "Show _MENU_",
            },
            "dom": "<'row mb-2'" +
                "<'col-sm-6 d-flex align-items-center justify-conten-start dt-toolbar'l>" +
                "<'col-sm-6 d-flex align-items-center justify-content-end dt-toolbar'f>" +
                ">" +
                "<'table-responsive'tr>" +
                "<'row'" +
                "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
                "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
                ">"
        });

        function applyFilter() {
            table.ajax.reload();
        }

        function resetFilter() {
            $("#course_type").val(null).trigger('change');
            $("#categories").val(null).trigger('change');
            $("#subcategories").val(null).trigger('change');
            $("#vessels").val(null).trigger('change');
            $("#departments").val(null).trigger('change');
            $("#ranks").val(null).trigger('change');
            $("#course_by").val(null).trigger('change');
            $("#training_center").val(null).trigger('change');
            table.ajax.reload();
        }

        function change_status(id, status) {
            let formData = new FormData();
            formData.append('course_id', id);
            formData.append('status', status);
            $.ajax({
                url: "{{ route('change-course-status') }}",
                method: 'post',
                data: formData,
                processData: false,
                dataType: "json",
                contentType: false,
                beforeSend: function() {},
                success: function(response) {
                    toastr.remove();
                    if (response.success) {
                        toastr.success(response.message);
                        //table.ajax.reload(null, false);
                    } else {
                        toastr.error(response.message);
                    }
                }
            });
        }

        function delete_data(id) {
            swal.fire({
                title: 'Remove Course',
                text: 'Are You Sure ?',
                imageWidth: 48,
                imageHeight: 48,
                showCloseButton: true,
                showCancelButton: true,
                cancelButtonText: 'Cancel',
                confirmButtonText: 'Yes, Remove it!',
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                width: 320,
                allowOutsideClick: false,
            }).then(function(result) {
                if (result.value) {
                    var formdata = new FormData();
                    formdata.append('course_id', id);
                    $.ajax({
                        url: "{{ route('delete-course') }}",
                        method: "POST",
                        data: formdata,
                        processData: false,
                        dataType: "json",
                        contentType: false,
                        beforeSend: function() {},
                        success: function(response) {
                            toastr.remove();
                            if (response.success) {
                                toastr.success(response.message);
                                table.ajax.reload(null, false);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(response) {
                            toastr.error(response.message);
                        }
                    });
                }
            });
        }
        $(document).ready(function() {
            $("#filter").click(function() {
                $(".filter-card").slideToggle("slow");
            });
            $('#categories').on('change', function() {
                var categoryId = $(this).val();
                var formdata = new FormData();
                formdata.append('cat_id', categoryId);
                if (categoryId) {
                    $.ajax({
                        url: "{{ route('get-subcategories') }}",
                        method: "POST",
                        data: formdata,
                        processData: false,
                        dataType: "json",
                        contentType: false,
                        success: function(data) {
                            //console.log(data);
                            $('#subcategories').empty();
                            if (data.success) {
                                $('#subcategories').append(
                                    '<option value="0">Select Subcategory</option>');
                                $.each(data.data, function(key, value) {
                                    $('#subcategories').append('<option value="' + value
                                        .id +
                                        '">' + value.subcat_name + '</option>');
                                });
                            }
                        }
                    });
                } else {
                    $('#subcategories').empty().append('<option value="0">Select Subcategory</option>');
                    $('#vessels').empty().append('<option value="0">Select Vessel</option>');
                }
            });

            // Fetch vessels based on selected subcategory
            $('#subcategories').on('change', function() {
                var subcategoryId = $(this).val();
                var formdata = new FormData();
                formdata.append('subcat_id', subcategoryId);
                if (subcategoryId) {
                    $.ajax({
                        url: "{{ route('get-vessels') }}",
                        method: "POST",
                        data: formdata,
                        processData: false,
                        dataType: "json",
                        contentType: false,
                        success: function(data) {
                            $('#vessels').empty();
                            if (data.success) {
                                $('#vessels').append(
                                    '<option value="0">Select Vessel</option>');
                                $.each(data.data, function(key, value) {
                                    $.each(value.vessels, function(key, vessels) {
                                        $('#vessels').append('<option value="' +
                                            vessels.id +
                                            '">' + vessels.vessel_name +
                                            '</option>');
                                    });
                                });
                            }
                        }
                    });
                } else {
                    $('#vessels').empty().append('<option value="0">Select Vessel</option>');
                }
            });
            $('#departments').on('change', function() {
                var depId = $(this).val();
                var formdata = new FormData();
                formdata.append('dep_id', depId);
                if (depId) {
                    $.ajax({
                        url: "{{ route('get-ranks') }}",
                        method: "POST",
                        data: formdata,
                        processData: false,
                        dataType: "json",
                        contentType: false,
                        success: function(data) {
                            //console.log(data);
                            $('#ranks').empty();
                            if (data.success) {
                                $('#ranks').append(
                                    '<option value="0">Select Rank</option>');
                                $.each(data.data, function(key, value) {
                                    $('#ranks').append('<option value="' + value
                                        .id +
                                        '">' + value.rank_name + '</option>');
                                });
                            }
                        }
                    });
                } else {
                    $('#ranks').empty().append('<option value="0">Select Rank</option>');
                }
            })
        });
    </script>
@endpush
