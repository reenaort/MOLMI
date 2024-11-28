@extends('layouts.admin-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Dashboard')
@push('styles')
    <style>
        .hidden {
            display: none;
        }

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .c-header {
            height: 60px; /* Set your desired fixed height */
            overflow: hidden; /* Hide any overflow content */
            display: flex; /* Use flexbox for alignment */
            align-items: center; /* Center items vertically */
        }
    </style>
@endpush
@section('content')
    <div id="kt_app_toolbar" class="app-toolbar py-2 py-lg-4">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Course
                    Wise Matrix</h1>
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
                    <li class="breadcrumb-item text-gray-700">Course Wise Matrix</li>
                </ul>
            </div>
            <div>
                <div class="d-flex">
                    <form data-kt-search-element="form" class="d-none d-lg-block w-100 mb-5 mb-lg-0 position-relative me-2" autocomplete="off">
                        <i class="ki-duotone ki-magnifier fs-2 text-gray-500 position-absolute top-50 translate-middle-y ms-4"><span class="path1"></span><span class="path2"></span></i>
                        <input type="text" class="form-control border-gray-200 h-40px bg-body ps-13 fs-7" name="search" value="" placeholder="Search..." data-kt-search-element="input">
                        <span class="position-absolute top-50 end-0 translate-middle-y lh-0 d-none me-5" data-kt-search-element="spinner">
                            <span class="spinner-border h-15px w-15px align-middle text-gray-500"></span>
                        </span>
                        <span class="btn btn-flush btn-active-color-primary position-absolute top-50 end-0 translate-middle-y lh-0 d-none me-4" data-kt-search-element="clear">
                            <i class="ki-duotone ki-cross fs-2 me-0"><span class="path1"></span><span class="path2"></span></i>
                        </span>
                    </form>
                    <a href="javascript:void(0);" class="btn btn-sm btn-flex btn-secondary fw-bold me-2" id="filter">
                        <i class="ki-duotone ki-filter fs-6 text-muted me-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>Filter
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="card card-flush mb-4 filter-card">
                <div class="card-body py-4">
                    <div class="filter">
                        <div class="row">
                            <div class="col-lg-3">
                                <label class="form-label">Date Range</label>
                                <div id="date_range" class="form-control" style="cursor: pointer; width: 100%">
                                    <i class="fa fa-calendar"></i>&nbsp;
                                    <span></span> <i class="fa fa-caret-down"></i>
                                </div>
                            </div>
                            @php
                                $categories = \App\Models\Category::where('status', 1)->get();
                                $departments = \App\Models\Department::where('status', 1)->get();
                            @endphp
                            <div class="col-lg-2">
                                <label class="form-label">Category</label>
                                <select class="form-select" data-control="select2" data-placeholder="Select Category"
                                    data-hide-search="true" name="categories" id="categories">
                                    <option value="0">Select Category</option>
                                    @foreach ($categories as $item)
                                        <option value="{{ $item->id }}">{{ $item->cat_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label class="form-label">Sub Category</label>
                                <select class="form-select" data-control="select2" data-placeholder="Select Sub Category"
                                    data-hide-search="true" name="subcategories" id="subcategories">
                                    <option value="0">Select Subcategory</option>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label class="form-label">Department</label>
                                <select class="form-select" data-control="select2" data-placeholder="Select Department"
                                    data-hide-search="true" name="departments" id="departments">
                                    <option value="0">All</option>
                                    @foreach ($departments as $item)
                                        <option value="{{ $item->id }}">{{ $item->dep_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label class="form-label">Rank</label>
                                <select class="form-select" data-control="select2" data-placeholder="Select Rank"
                                    data-hide-search="true" name="ranks" id="ranks">
                                    <option value="0">All</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="d-flex justify-content-end pt-5">
                                    <button type="button" class="btn btn-sm btn-primary me-2"
                                        onclick="applyfilter()">Apply</button>
                                    <button type="button" class="btn btn-sm btn-border-primary"
                                        onclick="resetfilter()">Reset</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row row-cols-5" id="courses-container">
            </div>
            <div id="bottomOfPage"></div>
        </div>
    </div>
    <div class="modal fade" id="CandidateModal" tabindex="-1" aria-labelledby="certificationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="certificationModalLabel">Candidate List</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @php
                        $courses = \App\Models\Course::where('status', 1)->get();
                    @endphp
                    <div class="container">
                        <div class="row align-items-center mb-3">
                            <div class="col-md-8">
                                <h5 id="course-name">Course Name: <span id="course-name-value">Basic Navigation</span>
                                </h5>
                                <h5 id="course-duration">Duration: <span id="course-duration-value">4 weeks</span></h5>
                                <h5 id="course-mode">Mode: <span id="course-mode-value">Online</span></h5>
                            </div>
                            {{-- <div class="col-md-4 text-end">
                                <select id="course-select" class="form-select">
                                    <option value="">Select Course</option>
                                    @foreach ($courses as $item)
                                        <option value="{{ $item->id }}">{{ $item->course_name }}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                        </div>
                        <table class="table table-bordered" id="candidateTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Photo</th>
                                    <th>Candidate Details</th>
                                    <th>Status</th>
                                    <th>Till Date</th>
                                    <th>Position</th>
                                    <th>Enrollment Status</th>
                                    <th>Expenditure/Refund amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Candidate rows will be dynamically injected here -->
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            onclick="closecandidatemodal()">Close</button>
                    </div>
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
            var start = moment().startOf('month');
            var end = moment().endOf('month');
            var startDate = start.format('Y-MM-DD');
            var endDate = end.format('Y-MM-DD');
            var dateText = `${startDate} - ${endDate}`;
            $(document).ready(function() {

                function cb(start, end) {
                    $('#date_range span').html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
                }

                $('#date_range').daterangepicker({
                    language: "en",
                    dateFormat: 'yyyy-mm-dd',
                    startDate: start,
                    endDate: end,
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                            'month').endOf('month')]
                        // 'Last Year': [moment().startOf('year').subtract(1, 'year'), moment().endOf('year').subtract(1, 'year')]
                    }
                }, function(start, end, label) {
                    startDate = start.format('Y-MM-DD');
                    endDate = end.format('Y-MM-DD');
                    dateText = `${startDate} - ${endDate}`;
                    cb(start, end);
                    // loadcourses();
                });
                cb(start, end);
                $("#filter").click(function() {
                    $(".filter-card").slideToggle("slow");
                });

                loadcourses();

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
            
            let currentPage = 1;
            let loading = false;
            let totalPages = 1; // You should update this value when you fetch the total pages from the response
            
            let debounceTimeout;
            function debounce(fn, delay) {
                return function () {
                    clearTimeout(debounceTimeout);
                    debounceTimeout = setTimeout(() => fn.apply(this, arguments), delay);
                };
            }

            // Event listener for the search field
            $("[name='search']").on('keyup', debounce(function () {
                currentPage = 1;
                loading = false;
                totalPages = 1;
                $('#courses-container').html('');
                const searchTerm = $(this).val().trim();
    
                if (searchTerm.length > 2 || searchTerm.length === 0) {
                    // Call loadCourses if more than 2 characters or field is cleared
                    loadcourses();
                }
            }, 300));  // Debounce delay of 300ms


            function loadcourses() {
                if (loading || currentPage > totalPages) return;

                loading = true;
                
                var formdata = new FormData();
                formdata.append('date_range', dateText);
                formdata.append('cat_id', $("#categories").val());
                formdata.append('subcat_id', $("#subcategories").val() ?? 0);
                formdata.append('dep_id', $("#departments").val());
                formdata.append('rank_id', $("#ranks").val() ?? 0);
                
                // Search
                $.trim($("[name='search']").val()).length >= 2 ? formdata.append('search',$.trim($("[name='search']").val())) : '';
                
                // Lazy Loading
                formdata.append('page', currentPage);
                formdata.append('limit', 10);
                
                $.ajax({
                    url: "{{ route('get-date-wise-courses') }}",
                    method: 'POST',
                    data: formdata,
                    processData: false,
                    dataType: "json",
                    contentType: false,
                    beforeSend: function() {
                        toastr.remove();
                        //toastr.info('Please Wait ....');
                    },
                    success: function(response) {
                        toastr.remove();
                        
                        if (response.success) {
                            totalPages = response.total_pages;
                            let courseCard = ``;
                            response.data.forEach(data => {
                                
                               if (data.rank_names) {
                                    const rankNames = data.rank_names.split(', '); // Split rank names by commas
                                    const maxDisplayCount = 3;
                                    const displayRankNames = rankNames.slice(0, maxDisplayCount); // Get the first 3 names
                                    const remainingCount = rankNames.length - maxDisplayCount; // Calculate remaining names count
                                
                                    // Create badges for displayed names
                                    rankNamesBadges = displayRankNames.map(rankName =>
                                        `<span class="badge badge-secondary me-1">${rankName}</span>`
                                    ).join(' ');
                                
                                    // Prepare tooltip content
                                    tooltipContent = rankNames.join(', '); // Join all names for tooltip
                                
                                    // Add ellipses if there are more than 3 names
                                    if (remainingCount > 0) {
                                        rankNamesBadges += ` <span class="badge badge-secondary me-1" data-bs-toggle="tooltip" title="${tooltipContent}">...</span>`;
                                    }
                                }
                                let typeBadges = '';
                                if (data.coursetype_names) {
                                    const typeNames = data.coursetype_names.split(
                                        ', '); // Split rank names by commas
                                    typeBadges = typeNames.map(typeName =>
                                            `<span class="badge badge-secondary me-1">${typeName}</span>`)
                                        .join(' ');
                                }
                                let mode = '';
                                if (data.online_priority == 1) {
                                    mode = 'Online'; // Replace with actual value if needed
                                } else if (data.offline_priority == 1) {
                                    mode = 'Offline'; // Replace with actual value if needed
                                } else if (data.elearning_priority == 1) {
                                    mode = 'E-Learning'; // Replace with actual value if needed
                                } else {
                                    mode =
                                        'N/A'; // Default value if none of the priorities have a value
                                }

                                // Create course card HTML
                                // Assuming 'data' contains the values and 'rankNamesBadges' and 'typeBadges' have been processed
                                courseCard += `
                                        <div class="col">
                                            <div class="card custom-cards shadow-sm">
                                                <div class="card-body">
                                                    <div class="c-header p-3">
                                                        ${data.course_name ? `
                                                            <div>
                                                                <span class="fs-6 text-white fw-semibold" data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="${data.course_name}">
                                                                    ${data.course_name} <!-- Replace with actual course title -->
                                                                </span>
                                                            </div>` : ''}
                                                    </div>
                                                    <div class="d-flex align-items justify-content-between light-bg-color p-3">
                                                      
                                                        ${mode ? `
                                                            <div>
                                                                <span class="fs-7 text-gray-500">Mode</span><br />
                                                                <span class="text-gray-700 fs-7 fw-bold">
                                                                    ${mode} <!-- Replace with actual mode -->
                                                                </span>
                                                            </div>` : ''}
                                                        ${data.duration ? `
                                                            <div>
                                                                <span class="fs-7 text-gray-500">Duration</span><br />
                                                                <span class="text-gray-700 fs-7 fw-bold">
                                                                    ${data.duration} days <!-- Replace with actual duration -->
                                                                </span>
                                                            </div>` : ''}
                                                    </div>
                                                    <div class="px-3 mb-2">
                                                        ${rankNamesBadges ? `
                                                            <div>
                                                                <span class="fs-7 text-gray-500">For</span><br />
                                                                <span class="text-gray-700 fs-7 fw-bold">
                                                                    ${rankNamesBadges} <!-- Replace with actual role -->
                                                                </span>
                                                            </div>` : ''}
                                                        ${typeBadges ? `
                                                            <div class="py-3">
                                                                <span class="fs-7 text-gray-500">Type</span><br />
                                                                <span class="text-gray-700 fs-7 fw-bold">
                                                                    ${typeBadges} <!-- Replace with actual type -->
                                                                </span>
                                                            </div>` : `<div class="py-3">
                                                                <span class="fs-7 text-gray-500">Type</span><br />
                                                                <span class="text-gray-700 fs-7 fw-bold">N/A
                                                                </span>
                                                            </div>`}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
                                                        <div class=" pb-3 pt-3 d-flex justify-content-between align-items-center">
                                                            <span class="fs-7 text-gray-500">Approximate Candidate Count</span>
                                                            <span class="logo-color fs-5 fw-bold" style="cursor: pointer;"  onclick="getcandidateinfo('${data.candidate_ids}','${data.id}','${data.course_name}','${data.duration}','${mode}')">
                                                                ${data.candidatecount?data.candidatecount:0} <!-- Replace with actual candidate count -->
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                // The card will only render the divs that have non-null data
                                // Append the course card to the container
                            });
                            $('#courses-container').append(courseCard);
                            currentPage++; // Increment page
                            //toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                            $('#courses-container').html(`<div class="col-lg-12"><h4 class="text-center">Sorry No Courses Found!</h4></div>`);
                        }
                        loading = false; // Reset loading flag
                    },
                    error: function(response) {
                        toastr.error(response.message);
                    }
                });
            }

            function resetfilter() {
                $("#categories").val('0').trigger('change');
                $("#subcategories").val('0').trigger('change');
                $("#departments").val('0').trigger('change');
                $("#ranks").val('0').trigger('change');
                $('#date_range').val('0');
                dateText = `${startDate} - ${endDate}`;
                currentPage = 1;
                loading = false;
                totalPages = 1;
                $('#courses-container').html('');
                loadcourses();

            }

            function applyfilter() {
                currentPage = 1;
                loading = false;
                totalPages = 1;
                $('#courses-container').html('');
                loadcourses();
            }
            
            // Set up an IntersectionObserver to trigger turfFilter when reaching the bottom
            let observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        //console.log(currentPage);
                        loadcourses(); // Load next page when the user scrolls to the bottom
                    }
                });
            }, {
                root: null, // Observe relative to the viewport
                threshold: 0.5 // Trigger when 50% of the element is visible
            });
    
            // Observe the element at the bottom of your page
            observer.observe(document.querySelector('#bottomOfPage'));

            function getcandidateinfo(candidateIds, course_id, course_name, duration, mode) {
                //let idsArray = candidateIds.split(',').map(id => id.trim());
                $('#CandidateModal').modal('show');
                $('#course-name-value').html(course_name);
                $('#course-duration-value').html(duration);
                $('#course-mode-value').html(mode);
                // $('#course-select').val(course_id);

                // Perform AJAX request
                $.ajax({
                    url: "{{ route('getCandidate-info') }}", // Replace with your actual endpoint
                    type: 'POST',
                    data: {
                        candidate_ids: candidateIds,
                        course_id: course_id
                    }, // Send candidate IDs as data
                    success: function(response) {
                        console.log(response);
                        if (response.success) {

                            displayCandidates(response.data, course_id);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        console.error('Error fetching candidate info:', xhr);
                    }
                });
            }

            function closecandidatemodal() {
                $('#CandidateModal').modal('hide');
            }

            function displayCandidates(candidates, course_id) {
                if($.fn.DataTable.isDataTable('#candidateTable')){
                    $('#candidateTable').DataTable().destroy();
                }
                var $tableBody = $('#candidateTable tbody');
                $tableBody.empty(); // Clear previous rows
                $tableBody.html("");
                var dummyPhotoUrl = "{{ asset('assets/img/dummy_photo.jpg') }}";
                candidates.forEach(function(candidate, index) {
                    var photo = candidate.candidate_photo ? '<img src="' + candidate.candidate_photo +
                        '" alt="Photo" width="50">' : '<img src="' + dummyPhotoUrl + '" alt="No Photo" width="50">';
                    // Format the till_date in 'Day Month, Year' format
                    if (candidate.type == 2) {
                        var tillDate = new Date(candidate.till_date).toLocaleDateString('en-GB', {
                            day: '2-digit',
                            month: 'long',
                            year: 'numeric'
                        });
                    } else {
                        var tillDate = "";
                    }

                    var actionButtons = "";
                    var statusstring = "Pending";
                    if (candidate.status == 1) {
                        // Display the expenditure amount input field
                        expenditureInput =
                            '<input type="number" class="form-control" maxlength="6" placeholder="Enter expenditure amount" value="' +
                            (candidate.expenditure_amount ? candidate.expenditure_amount : '') + '" id="expend_' +
                            candidate.id +
                            '" >';

                        actionButtons += '<button onclick="decline_course(' + candidate.id +
                            ', ' + course_id + ', ' + 'document.getElementById(\'expend_' + candidate.id +
                            '\').value)" ' +
                            'class="btn btn-sm decline-btn" style="background:#F1F1F4;" data-id="' +
                            candidate.id + '" data-toggle="tooltip" title="Decline">' +
                            '<i class="fas fa-times" style="background:#F1F1F4;color: #03599F;"></i></button>';
                        statusstring = "Enrolled";
                    } else if (candidate.status == 2) {
                        // Display the refund amount input field
                        expenditureInput =
                            '<input type="number" class="form-control" maxlength="6" placeholder="Enter refund amount" value="' +
                            (candidate.refund_amount ? candidate.refund_amount : '') + '" id="expend_' + candidate.id +
                            '" >';
                        actionButtons += '<button onclick="accept_course(' + candidate.id +
                            ', ' + course_id + ', ' + 'document.getElementById(\'expend_' + candidate.id +
                            '\').value)" ' +
                            'class="btn btn-sm accept-btn" style="background:#03599F;" data-id="' +
                            candidate.id + '" data-toggle="tooltip" title="Accept">' +
                            '<i class="fas fa-check" style="color: #fff;"></i></button> ';
                        statusstring = "Declined";
                    } else {
                        // If status is something else or null, display nothing
                        expenditureInput =
                            '<input type="number" class="form-control" maxlength="6" placeholder="Enter refund amount" value="" id="expend_' +
                            candidate.id +
                            '" >'; // You can display a dash or a message if no input is required
                        actionButtons += '<button onclick="decline_course(' + candidate.id +
                            ', ' + course_id + ', ' + 'document.getElementById(\'expend_' + candidate.id +
                            '\').value)" ' +
                            'class="btn btn-sm decline-btn" style="background:#F1F1F4;" data-id="' +
                            candidate.id + '" data-toggle="tooltip" title="Decline">' +
                            '<i class="fas fa-times" style="background:#F1F1F4;color: #03599F;"></i></button>';
                        actionButtons += '<button onclick="accept_course(' + candidate.id +
                            ', ' + course_id + ', ' + 'document.getElementById(\'expend_' + candidate.id +
                            '\').value)" ' +
                            'class="btn btn-sm accept-btn" style="background:#03599F;" data-id="' +
                            candidate.id + '" data-toggle="tooltip" title="Accept">' +
                            '<i class="fas fa-check" style="color: #fff;"></i></button> ';
                    }

                    var row = '<tr>' +
                        '<td>' + (index + 1) + '</td>' +
                        '<td>' + photo + '</td>' +
                        '<td><strong>Name: </strong>' + candidate.candidate_name + '<br>\r\n<strong>Contact: </strong>'+candidate.contact_no+'<br>\r\n<strong>Location: </strong>'+(candidate.location == null ? '' : candidate.location)+'</td>' +
                        '<td>' + (candidate.type == 2 ? 'OnShore' : 'OffShore') + '</td>' +
                        '<td>' + tillDate + '</td>' +
                        '<td>' + candidate.rank_name + '(' + candidate.dep_name + ')' + '</td>' +
                        '<td><span class="badge badge-primary me-1">' + statusstring + '</span></td>' +
                        '<td>' + expenditureInput +
                        '</td>' +
                        '<td class="d-flex">' + actionButtons + '</td>' +
                        '</tr>';

                    $tableBody.append(row);
                });
                
                $('#candidateTable').DataTable({
                    "language": {
                        "lengthMenu": "Show _MENU_",
                    },
                    "dom": "<'row mb-2'" +
                    "<'col-sm-6 d-flex align-items-center justify-conten-start dt-toolbar'lB>" +
                    "<'col-sm-6 d-flex align-items-center justify-content-end dt-toolbar'f>" +
                    ">" +
                    "<'table-responsive'tr>" +
                    "<'row'" +
                    "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
                    "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
                    ">",
                    "buttons": [
                    {
                        extend: 'excel',
                        text: 'EXCEL',
                        title: "MOLMI - Candidates",
                        filename: 'candidates_' + new Date().getTime(),
                        exportOptions: {
                            columns: ':not(:eq(0), :eq(1), :last)' // Exclude the first 2 columns and the last column
                        }
                    },
                    {
                        extend: 'csv',
                        text: 'CSV',
                        title: "MOLMI - Candidates",
                        filename: 'candidates_' + new Date().getTime(),
                        exportOptions: {
                            columns: ':not(:eq(0), :eq(1), :last)' // Exclude the first 2 columns and the last column
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        title: "MOLMI - Candidates",
                        filename: 'candidates_' + new Date().getTime(),
                        exportOptions: {
                            columns: ':not(:eq(0), :eq(1), :last)' // Exclude the first 2 columns and the last column
                        }
                    },
                ],
                });
                
            }

            function accept_course(candidateId, courseId, expenditure) {
                ////console.log('Accepting course for candidate ID:', candidateId, 'with expenditure:', expenditure);
                $.ajax({
                    url: "{{ route('store-course-enrollment') }}", // Replace with your actual endpoint
                    type: 'POST',
                    data: {
                        candidate_id: candidateId,
                        status: 1,
                        amount: expenditure,
                        course_id: courseId
                    }, // Send candidate IDs as data
                    success: function(response) {
                        //console.log(response);
                        if (response.success) {
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                        $('#CandidateModal').modal('hide');
                    },
                    error: function(xhr) {
                        console.error('Error fetching candidate info:', xhr);
                    }
                });
                // Add your logic for accepting the course here
            }

            function decline_course(candidateId, courseId, refund) {
                //console.log('Declining course for candidate ID:', candidateId, 'with expenditure:', expenditure);
                $.ajax({
                    url: "{{ route('store-course-enrollment') }}", // Replace with your actual endpoint
                    type: 'POST',
                    data: {
                        candidate_id: candidateId,
                        status: 2,
                        amount: refund,
                        course_id: courseId
                    }, // Send candidate IDs as data
                    success: function(response) {
                        console.log(response);
                        if (response.success) {
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        console.error('Error fetching candidate info:', xhr);
                    }
                });
            }
            
            
        </script>
    @endpush
