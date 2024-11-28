<!--@extends('layouts.admin-layout')-->
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Dashboard')
@push('styles')
    <style>
        .card-header {
            padding-bottom: 5px;
            /* Reduce padding between the header and the table */
        }

        .card-title {
            margin-bottom: 0px;
            /* Remove bottom margin from the title */
        }

        .table {
            margin-top: 0px;
            /* Ensure no margin between the title and the table */
        }
    </style>
@endpush
@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        Dashboard</h1>
                    <!--end::Title-->

                </div>
                <!--end::Page title-->

            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!--begin::Row-->
                <div class="row g-2 gx-xl-5 mb-2 mb-xl-2">
                    <!--begin::Col - Total Courses-->
                    <div class="col-md-2">
                        <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end mb-5"
                            style="background-color: #cadceb;">
                            <div class="card-body py-5">
                                <div class="card-title d-flex flex-column">
                                    <span class="fs-2hx fw-bold logo-color me-2 lh-1 ls-n2">
                                        {{ isset($total_courses) ? $total_courses : '-' }}</span>
                                    <span class="logo-color opacity-75 pt-2 fw-semibold fs-5">Total Courses</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Col-->

                    <!--begin::Col - Active Courses-->
                    <div class="col-md-2">
                        <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end mb-5"
                            style="background-color: #cadceb;">
                            <div class="card-body py-5">
                                <div class="card-title d-flex flex-column">
                                    <span class="fs-2hx fw-bold logo-color me-2 lh-1 ls-n2">
                                        {{ isset($total_candidates) ? $total_candidates : '-' }}</span>
                                    <span class="logo-color opacity-75 pt-2 fw-semibold fs-5">Total Candidates</span>
                                </div>
                            </div>
                        </div>
                    </div>
                       <div class="col-md-2">
                        <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end mb-5"
                            style="background-color: #cadceb;">
                            <div class="card-body py-5">
                                <div class="card-title d-flex flex-column">
                                    <span class="fs-2hx fw-bold logo-color me-2 lh-1 ls-n2">
                                        {{ isset($total_enrollments) ? $total_enrollments : '-' }}</span>
                                    <span class="logo-color opacity-75 pt-2 fw-semibold fs-5">Enrolled Candidates</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Col-->

                    <!--begin::Col - Total Candidates-->
                    <div class="col-md-2">
                        <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end mb-5"
                            style="background-color: #cadceb;">
                            <div class="card-body py-5">
                                <div class="card-title d-flex flex-column">
                                    <span
                                        class="fs-2hx fw-bold logo-color me-2 lh-1 ls-n2">{{ isset($total_expense) ? $total_expense : '-' }}</span>
                                    <span class="logo-color opacity-75 pt-2 fw-semibold fs-5">Total Expenditure</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end mb-5"
                            style="background-color: #cadceb;">
                            <div class="card-body py-5">
                                <div class="card-title d-flex flex-column">
                                    <span
                                        class="fs-2hx fw-bold logo-color me-2 lh-1 ls-n2">{{ isset($total_refund) ? $total_refund : '-' }}</span>
                                    <span class="logo-color opacity-75 pt-2 fw-semibold fs-5">Total Refund</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Col-->

                    <!--begin::Col - Accepted Candidates-->
                    <div class="col-md-2">
                        <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end mb-5"
                            style="background-color: #cadceb;">
                            <div class="card-body py-5">
                                <div class="card-title d-flex flex-column">
                                    <span
                                        class="fs-2hx fw-bold logo-color me-2 lh-1 ls-n2">{{ isset($total_loss) ? $total_loss : '-' }}</span>
                                    <span class="logo-color opacity-75 pt-2 fw-semibold fs-5">Total Loss</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->
                <!--begin::Row for Course Selection and Pie Chart-->
                <div class="row g-5 g-xl-10 mb-5">
                    <div class="col-xl-6">
                        <!--begin::Pie Chart-->
                        <div class="card card-flush overflow-hidden ">
                            <div class="card-header">
                                <h3 class="card-title">Course Enrollment Status</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="acceptedCandidatesChart" width="400" height="200"></canvas>
                            </div>
                        </div>

                        <!--end::Pie Chart-->
                    </div>

                    <div class="col-xl-6">
                        <div class="card card-flush">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title">Top 5 Courses</h3>
                                <!-- Tabs for This Month and Next Month -->
                                <ul class="nav nav-tabs" id="courseTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="thisMonth-tab" data-bs-toggle="tab"
                                            data-bs-target="#thisMonth" type="button" role="tab"
                                            aria-controls="thisMonth" aria-selected="true">This Month</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="nextMonth-tab" data-bs-toggle="tab"
                                            data-bs-target="#nextMonth" type="button" role="tab"
                                            aria-controls="nextMonth" aria-selected="false">Next Month</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <!-- Tab content for the table -->
                                <div class="tab-content" id="courseTabContent">
                                    <!-- This Month's Courses -->
                                    <div class="tab-pane fade show active" id="thisMonth" role="tabpanel"
                                        aria-labelledby="thisMonth-tab">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Course Name</th>
                                                    <th>Max Candidates</th>
                                                    <!--<th>Start Dates</th>-->
                                                </tr>
                                            </thead>
                                            <tbody id="topCoursesThisMonth">
                                                <!-- Rows for This Month will be dynamically added here -->
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Next Month's Courses -->
                                    <div class="tab-pane fade" id="nextMonth" role="tabpanel"
                                        aria-labelledby="nextMonth-tab">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Course Name</th>
                                                    <th>Max Candidates</th>
                                                    <!--<th>Start Dates</th>-->
                                                </tr>
                                            </thead>
                                            <tbody id="topCoursesNextMonth">
                                                <!-- Rows for Next Month will be dynamically added here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!--end::Row-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
        @csrf
    </div>

@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        document.addEventListener("DOMContentLoaded", function() {

            // Example data for the last 6 months
            // var lastSixMonths = moment().subtract(5, 'months').startOf('month').format('MMMM');
            // var months = [
            //     moment().subtract(5, 'months').format('MMMM'), // 5 months ago
            //     moment().subtract(4, 'months').format('MMMM'), // 4 months ago
            //     moment().subtract(3, 'months').format('MMMM'), // 3 months ago
            //     moment().subtract(2, 'months').format('MMMM'), // 2 months ago
            //     moment().subtract(1, 'months').format('MMMM'), // 1 month ago
            //     moment().format('MMMM') // Current month
            // ];

            // Sample accepted data for the last 6 months
            var acceptedData =
                @json($candidate_count); // [50, 70, 80, 60, 90, 100]; // Replace with your dynamic data

            // Chart Configuration
            var ctx = document.getElementById('acceptedCandidatesChart').getContext('2d');
            var acceptedCandidatesChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($months),
                    datasets: [{
                        //label: 'Accepted Candidates',
                        data: acceptedData,
                        backgroundColor: 'rgba(3, 89, 159, 0.6)', // Shade of blue
                        borderColor: '#03599F',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Accepted Candidates'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Months'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false // Hide the legend
                        }

                    }
                }
            });



            var start = moment().startOf('month');
            var end = moment().endOf('month');
            var startDate = start.format('Y-MM-DD');
            var endDate = end.format('Y-MM-DD');
            var dateText_thismonth = `${startDate} - ${endDate}`;
            loadcourses(dateText_thismonth);

            var start = moment().add(1, 'months').startOf('month');
            var end = moment().add(1, 'months').endOf('month');
            var startDate = start.format('YYYY-MM-DD');
            var endDate = end.format('YYYY-MM-DD');
            var dateText_nextmonth = `${startDate} - ${endDate}`;


            //top courses data
            $('#courseTabs button[data-bs-toggle="tab"]').on('shown.bs.tab', function(event) {
                var target = $(event.target).data(
                    "bs-target"); // Get the data-bs-target attribute of the clicked tab

                // Call specific functions based on the active tab
                if (target === "#thisMonth") {
                    loadcourses(dateText_thismonth, target); // Function to fetch data for this month
                } else if (target === "#nextMonth") {

                    loadcourses(dateText_nextmonth, target); // Function to fetch data for next month
                }
            });

            function loadcourses(dateText, target) {
                var formdata = new FormData();
                formdata.append('date_range', dateText);
                formdata.append('limit', 5);
                $.ajax({
                    url: "{{ route('get-date-wise-courses') }}",
                    method: 'POST',
                    data: formdata,
                    processData: false,
                    dataType: "json",
                    contentType: false,
                    beforeSend: function() {
                        toastr.remove();
                    },
                    success: function(response) {
                        toastr.remove();
                        if (response.success) {
                            console.log(response);
                            if (target == '#nextMonth') {
                                const tableBody = $('#topCoursesNextMonth');
                                tableBody.empty(); // Clear any existing rows

                                response.data.forEach(item => {
                                    const row = `<tr>
                                        <td>${item.course_name}</td>
                                        <td>${item.candidatecount}</td>
                                    //   
                                    </tr>`;
                                    tableBody.append(row);
                                });
                            } else {
                                const tableBody = $('#topCoursesThisMonth');
                                tableBody.empty(); // Clear any existing rows

                                response.data.forEach(item => {
                                    const row = `<tr>
                                        <td>${item.course_name}</td>
                                        <td>${item.candidatecount}</td>
                                      
                                    </tr>`;
                                    tableBody.append(row);
                                });

                            }
                        }
                    }
                })
            }
        })
    </script>
@endpush
