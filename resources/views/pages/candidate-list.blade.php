@extends('layouts.admin-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Dashboard')
@section('content')
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-3">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Candidate
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-gray-700">Candidate</li>
                </ul>
            </div>
            <div>
                <a href="javascript:void(0);" class="btn btn-sm btn-flex btn-secondary fw-bold me-2" id="filter">
                    <i class="ki-duotone ki-filter fs-6 text-muted me-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>Filter</a>
                <a href="{{ route('add-candidate') }}" class="btn btn-sm btn-primary"><i
                        class="ki-outline ki-plus fs-2"></i>Add
                    Candidate</a>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="card card-flush mb-4 filter-card">
                <div class="card-body py-4">
                    <div class="filter">
                        <div class="row row-cols-3">
                            @php
                                $departments = \App\Models\Department::where('status', 1)->get();
                            @endphp
                            <div class="col-lg-2">
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
                                <select class="form-select" data-control="select2" data-placeholder="Select Rank"
                                    data-hide-search="true" name="rank_id" id="rank_id">
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label class="form-label">Candidate Location</label>
                                <select class="form-select" data-control="select2" data-placeholder="Location"
                                    id="location" name="type" data-hide-search="true">
                                    <option></option>
                                    <option value="1">Offshore</option>
                                    <option value="2">Onshore</option>
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <div class="pt-8">
                                    <button type="button" class="btn btn-sm btn-border-primary"
                                        onclick="return applyFilter();">Apply Filter</button>
                                    <button type="button" class="btn btn-sm" onclick="return resetFilter();">Reset</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-flush">
                <div class="card-body">
                    <table class="table align-middle table-row-dashed fs-6 gy-3 checkbox-table" id="candidate_table">
                        <thead>
                            <tr class="text-start fw-600 fs-6 gs-0">
                                <th class="min-w-40px">Sr. No.</th>
                                <th class="min-w-100px">Candidate Details</th>
                                <th class="min-w-100px">Identities</th>
                                <th class="min-w-125px">Designation</th>
                                <th class="min-w-125px">Location</th>
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

        var table = $("#candidate_table").DataTable({
            processing: true,
            serverSide: true,
            //responsive: true,
            aLengthMenu: [
                [10, 15, 25, 50, 100, -1],
                [10, 15, 25, 50, 100, "All"]
            ],
            ajax: {
                url: "{{ route('getCandidates') }}",
                method: "POST",
                data: function(data) {
                    data.dep_id = $("#dep_id").val();
                    data.rank_id = $("#rank_id").val();
                    data.location = $("#location").val();
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: true,
                },
                {
                    data: 'candidate_details',
                    name: 'candidate_details',
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'identities',
                    name: 'identities',
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'designation',
                    name: 'designation',
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'location',
                    name: 'location',
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'can_type',
                    name: 'can_type',
                    orderable: true,
                    searchable: true,
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
            table.draw();
        }

        function resetFilter() {
            $("#dep_id").val(null).trigger('change');
            $("#rank_id").val(null).trigger('change');
            $("#location").val(null).trigger('change');
            table.draw();
        }

        $("#dep_id").on('change', function(e) {
            get_ranks($(this).val());
        });

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
                            response.data.forEach(element => {
                                opt += `<option value="${element.id}">${element.rank_name}</option>`;
                            });
                        } else {
                            opt += ``;
                        }
                    } else {
                        opt += ``;
                    }
                    $("#rank_id").html(opt);
                    $('#rank_id').select2();
                    $('#rank_id').val(null).trigger('change');
                }
            });
        }

        $(document).ready(function() {
            $("#filter").click(function() {
                $(".filter-card").slideToggle("slow");
            });
        });
        
          function delete_data(id) {
            swal.fire({
                title: 'Delete Candidate',
                text: 'Are You Sure ?',
                imageWidth: 48,
                imageHeight: 48,
                showCloseButton: true,
                showCancelButton: true,
                cancelButtonText: 'Cancel',
                confirmButtonText: 'Yes, Delete it!',
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                width: 320,
                allowOutsideClick: false,
            }).then(function(result) {
                if (result.value) {
                    var formdata = new FormData();
                    formdata.append('can_id', id);
                    $.ajax({
                        url: "{{ route('delete-candidate') }}",
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
    </script>
@endpush
