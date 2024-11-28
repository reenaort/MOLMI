@extends('layouts.admin-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Dashboard')
@push('styles')
    <style>
        .hidden {
            display: none;
        }

        .error {
            color: red;
        }
    </style>
@endpush
@section('content')
    <div id="kt_app_toolbar" class="app-toolbar py-2 py-lg-4">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Candidate
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
                    <li class="breadcrumb-item text-gray-700">Candidate Wise Matrix</li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
            </div>
        </div>
    </div>
    <div id="kt_app_content_container" class="app-container container-fluid">
        <div class="card card-flush mb-4">
            <div class="card-body py-4">
                <div class="filter">
                    <div class="row">
                        @php
                            $departments = \App\Models\Department::where('status', 1)->get();
                        @endphp
                        {{-- <div class="col-lg-2">
                            <label class="form-label">Department</label>
                            <select class="form-select" data-control="select2" data-placeholder="Select Department"
                                data-hide-search="true" name="dep_id" id="dep_id">
                                <option value=""></option>
                                @foreach ($departments as $item)
                                    <option value="{{ $item->id }}">{{ $item->dep_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label">Rank</label>
                            <select class="form-select" data-control="select2" data-placeholder="Select Rank">
                            </select>
                        </div> --}}
                        <div class="col-lg-2">
                            <label class="form-label">Candidate Name</label>
                            <select class="form-select" data-control="select2" data-placeholder="Select Candidate Name"
                                id="candidate_id" name="candidate_id">
                                <option value="">-Select-</option>
                                @foreach (\App\Models\Candidate::all() as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->candidate_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label">Vessel</label>
                            <select class="form-select" data-control="select2" data-placeholder="Select Vessel"
                                id="vessel_id" name="vessel_id">
                                <option value="">-Select-</option>
                                @foreach (\App\Models\Vessels::all() as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->vessel_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <div class="pt-9">
                                <button type="button" class="btn btn-sm btn-primary" onclick="return SearchCourses();"><i
                                        class="ki-outline ki-plus fs-2"></i>Search</button>
                            </div>
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
                            <th class="min-w-60px">Sr. No.</th>
                            <th class="min-w-125px">Name</th>
                            <th class="min-w-125px">Priority</th>
                            <th class="min-w-150px">Course by</th>
                            <th class="min-w-150px">Training Center</th>
                            <th class="min-w-150px">Duration</th>
                            <th class="min-w-150px">Status</th>
                            <th class="min-w-60px">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="certificationModal" tabindex="-1" aria-labelledby="certificationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="certificationModalLabel">Certification Details</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="certificationForm">
                        <input type="hidden" id="candidate_modal_id" val="">
                        <input type="hidden" id="course_modal_id" val="">
                        <div class="mb-3">
                            <label for="candidateName" class="form-label" id="candidate_name"></label>
                        </div>
                        <div class="mb-3">
                            <label for="certificationDate" class="form-label required">Certification Date</label>
                            <input type="date" class="form-control " id="certification_date" name="certification_date">
                            <span class="hidden error" id="certification_date_error">Please select date</span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveCertificationBtn"
                        onclick="SaveCertificationDate()">Save changes</button>
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
                url: "{{ route('get-candidate-wise-course') }}",
                method: "POST",
                data: function(data) {
                    data.candidate_id = $("#candidate_id").val();
                    data.vessel_id = $("#vessel_id").val();
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
                    data: 'priority_label',
                    name: 'priority_label',
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
                    data: 'duration',
                    name: 'duration',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'course_enrollment_status',
                    name: 'course_enrollment_status',
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
            createdRow: function(row, data, dataIndex) {
                // Check the `course_enrollment_status` value and add a background color
                if (data.course_enrollment_status === 'Completed') {
                    $(row).css('background-color', '#d4edda'); // Light green for completed
                }
            },
            drawCallback: function(settings) {
                var api = this.api();
                var recordsFound = api.rows().count();

                if (recordsFound === 0) {
                    toastr.error("No Records found");
                }
            },
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
        $(document).ready(function() {
            $("#filter").click(function() {
                $(".filter-card").slideToggle("slow");
            });

        });

        function SearchCourses() {
            table.ajax.reload();
        }

        function coursedone(course_id) {
            var candidate_id = $('#candidate_id').val();
            var candidateName = $("#candidate_id option:selected").text();
            $('#candidate_name').html(candidateName + ' completed the course on the below date.')
            $('#candidate_modal_id').val(candidate_id);
            $('#course_modal_id').val(course_id);
            $('#certificationModal').modal('show');
            //var course_id = course_id
        }

        function SaveCertificationDate() {
            if ($('#certification_date').val() == '') {
                $('#certification_date_error').removeClass('hidden');
                return false;
            } else
                $('#certification_date_error').addClass('hidden');

            var formdata = new FormData();
            formdata.append('candidate_id', $('#candidate_modal_id').val());
            formdata.append('course_id', $('#course_modal_id').val());
            formdata.append('certification_date', $('#certification_date').val());
            $.ajax({
                url: "{{ route('store-course-certification-date') }}",
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
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                    $('#certificationModal').modal('hide');
                    table.ajax.reload();
                },
                error: function(response) {

                }
            });
        }
    </script>
@endpush
