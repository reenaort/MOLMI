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
    </style>
@endpush
@section('content')

    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-3">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Course
                    Mining
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-gray-700">Course Mining</li>
                </ul>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <form id="addEditForm" action="{{ route('save-course') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card card-flush p-5 mt-5">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="course_id" class="form-label required">Select Course</label>
                            <select class="form-select" id="course_id" name="course_id" data-control="select2"
                                data-placeholder="Select Course" data-allow-clear="true">
                                <option value="">Select Course</option>
                                @foreach (\App\Models\Course::where('status', 1)->get() as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->course_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="course_type" class="form-label">Course Type</label>
                            <select class="form-select" id="course_type" name="course_type[]" data-control="select2"
                                data-close-on-select="false" data-placeholder="Select Course Type" data-allow-clear="true"
                                multiple="multiple">
                                <option value="0">All</option>
                                @foreach (\App\Models\CourseType::where('status', 1)->get() as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->type_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <hr class="dashed">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="categories" class="form-label required">Select Categories</label>
                            <select class="form-select" id="categories" name="categories[]" data-control="select2"
                                data-close-on-select="false" data-placeholder="Select Categories" data-allow-clear="true"
                                multiple="multiple">
                                <option value="0">All</option>
                                @foreach (\App\Models\Category::where('status', 1)->get() as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->cat_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="subcategories" class="form-label required">Select SubCategories</label>
                            <select class="form-select" id="subcategories" name="subcategories[]" data-control="select2"
                                data-close-on-select="false" data-placeholder="Select SubCategories" data-allow-clear="true"
                                multiple="multiple">
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="vessels" class="form-label required">Select Vessels</label>
                            <select class="form-select" id="vessels" name="vessels[]" data-control="select2"
                                data-close-on-select="false" data-placeholder="Select Vessels" data-allow-clear="true"
                                multiple="multiple">
                            </select>
                        </div>
                    </div>
                    <hr class="dashed">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="departments" class="form-label required">Select Department</label>
                            <select class="form-select" id="departments" name="departments[]" data-control="select2"
                                data-close-on-select="false" data-placeholder="Select Department" data-allow-clear="true"
                                multiple="multiple">
                                <option value="0">All</option>
                                @foreach (\App\Models\Department::where('status', 1)->get() as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->dep_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="ranks" class="form-label required">Select Ranks</label>
                            <select class="form-select" id="ranks" name="ranks[]" data-control="select2"
                                data-close-on-select="false" data-placeholder="Select Ranks" data-allow-clear="true"
                                multiple="multiple">
                            </select>
                        </div>
                    </div>
                    <hr class="dashed">
                    <h5>Selected Ranks:</h5>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card border border-dark">
                                <div class="card-body row">
                                    <div class="col-md-12 mb-3">
                                        <h4 class="card-title">Rank (Engine)</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions1"
                                                id="rank_mandatory" value="1" checked>
                                            <label class="form-check-label" for="rank_mandatory">Mandatory</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions1"
                                                id="rank_recommended" value="2">
                                            <label class="form-check-label" for="rank_recommended">Recommended</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card border border-dark">
                                <div class="card-body row">
                                    <div class="col-md-12 mb-3">
                                        <h4 class="card-title">Rank (Deck)</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions2"
                                                id="rank_mandatory" value="1" checked>
                                            <label class="form-check-label" for="rank_mandatory">Mandatory</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions2"
                                                id="rank_recommended" value="2">
                                            <label class="form-check-label" for="rank_recommended">Recommended</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card border border-dark">
                                <div class="card-body row">
                                    <div class="col-md-12 mb-3">
                                        <h4 class="card-title">Rank (Catering)</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions3"
                                                id="rank_mandatory" value="1" checked>
                                            <label class="form-check-label" for="rank_mandatory">Mandatory</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions3"
                                                id="rank_recommended" value="2">
                                            <label class="form-check-label" for="rank_recommended">Recommended</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center mt-5">
                    <a href="{{ route('course-list') }}" class="btn btn-sm btn-light me-5">Cancel</a>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <span class="indicator-label">Save</span>
                    </button>
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

        $(document).ready(function() {

            // When the category dropdown changes
            $('#categories').on('change', function() {
                var selectedCategories = $(this).val();
                if (!selectedCategories || selectedCategories.length === 0) {
                    // Reinitialize (clear) the container's HTML
                    $('#subcategories-container').html('');
                } else {
                    var formdata = new FormData();
                    formdata.append('cat_id', selectedCategories);
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
                                const SubcategoriesContainer = $('#subcategories-container');
                                SubcategoriesContainer.html('');
                                $.each(response.data, function(index, category) {
                                    // Create a dropdown for each category
                                    let dropdownHtml = `<div class="category-group">
                                                            <label> ${category.cat_name}</label>
                                                            <select class="form-select subcatselect2" id="subcategories_${category.cat_id}" name="subcat_id[]"
                                                                data-control="select2" data-close-on-select="false"
                                                                data-placeholder="Select Sub-Category" data-allow-clear="true"
                                                                multiple="multiple">
                                                                <option value="0">All</option>`;

                                    // Loop through each subcategory and add as options
                                    $.each(category.subcategories, function(i,
                                        subcategory) {
                                        dropdownHtml +=
                                            `<option value="${subcategory.id}">${subcategory.subcat_name}</option>`;
                                    });

                                    dropdownHtml += `</select></div>`;

                                    // Append the dropdown to the container
                                    SubcategoriesContainer.append(dropdownHtml);
                                });

                                $('.subcatselect2').select2({
                                    placeholder: 'Select Sub-Category',
                                    allowClear: true
                                });

                                // Attach change event to dynamically created dropdowns
                                SubcategoriesContainer.on('change', '.subcatselect2',
                                    function() {
                                        var selectedSubcategories = $(this).val();
                                        if (!selectedSubcategories || selectedSubcategories
                                            .length === 0) {
                                            // Reinitialize (clear) the container's HTML
                                            $('#vessels-container').html('');
                                        } else {
                                            var formdata = new FormData();
                                            formdata.append('subcat_id',
                                                selectedSubcategories);
                                            $.ajax({
                                                url: "{{ route('get-vessels') }}",
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
                                                        const
                                                            VesselsContainer =
                                                            $(
                                                                '#vessels-container'
                                                            );
                                                        VesselsContainer
                                                            .html('');
                                                        $.each(response.data,
                                                            function(index,
                                                                vessels) {
                                                                // Create a dropdown for each subcategory
                                                                let dropdownHtml = `<div class="category-group">
        <label> ${vessels.cat_name}</label>
        <select class="form-select vesselselect2" id="vessels_${vessels.subcat_id}" name="vessel_id[]"
                                data-control="select2" data-close-on-select="false"
                                data-placeholder="Select Vessel" data-allow-clear="true"
                                multiple="multiple"> <option value="0">All</option>`;

                                                                // Loop through each subcategory vessels and add as options
                                                                $.each(vessels
                                                                    .vessels,
                                                                    function(
                                                                        i,
                                                                        vessobj
                                                                    ) {
                                                                        dropdownHtml
                                                                            +=
                                                                            `<option value="${vessobj.id}">${vessobj.vessel_name}</option>`;
                                                                    });

                                                                dropdownHtml
                                                                    +=
                                                                    `</select></div>`;

                                                                // Append the dropdown to the container
                                                                VesselsContainer
                                                                    .append(
                                                                        dropdownHtml
                                                                    );
                                                            });

                                                        $('.vesselselect2')
                                                            .select2({
                                                                placeholder: 'Select Vessel',
                                                                allowClear: true
                                                            });
                                                    }
                                                }
                                            });
                                        }

                                    });
                            }
                        }
                    });
                }
            })

            $('#departments').on('change', function() {
                var selectedDepartments = $(this).val();
                if (!selectedDepartments || selectedDepartments.length === 0) {
                    // Reinitialize (clear) the container's HTML
                    $('#ranks-container').html('');
                } else {
                    var formdata = new FormData();
                    formdata.append('dept_id', selectedDepartments);
                    console.log(selectedDepartments);
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
                                const RanksContainer = $('#ranks-container');
                                RanksContainer.html('');
                                $.each(response.data, function(index, dept) {
                                    console.log(response.data);
                                    // Create a dropdown for each department
                                    let dropdownHtml = `<div class="rank-group">
        <label> ${dept.dep_name}</label>
        <select class="form-select rankselect2" id="ranks_${dept.dep_id}" name="rank_id[]"
                                data-control="select2" data-close-on-select="false"
                                data-placeholder="Select Rank" data-allow-clear="true"
                                multiple="multiple"> <option value="0">All</option>`;

                                    // Loop through each rank and add as options
                                    $.each(dept.ranks, function(i,
                                        ranks) {
                                        dropdownHtml +=
                                            `<option value="${ranks.id}">${ranks.rank_name}</option>`;
                                    });

                                    dropdownHtml += `</select></div>`;

                                    // Append the dropdown to the container
                                    RanksContainer.append(dropdownHtml);
                                });

                                $('.rankselect2').select2({
                                    placeholder: 'Select Rank',
                                    allowClear: true
                                });
                            }
                        }
                    });
                }
            })

        });
    </script>
@endpush
