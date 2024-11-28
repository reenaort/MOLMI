@extends('layouts.admin-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Dashboard')
@push('styles')
    <style>
        .image-input-placeholder {
            background-image: url('assets/media/svg/files/blank-image.svg');
        }

        [data-bs-theme="dark"] .image-input-placeholder {
            background-image: url('assets/media/svg/files/blank-image-dark.svg');
        }

        #type_date {
            display: none;
        }
    </style>
@endpush
@section('content')
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-3">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Add
                    Candidate</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-gray-700">{{ !isset($candidate) ? 'Add' : 'Edit' }} Candidate</li>
                </ul>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="row g-5 gx-xl-10 mb-2 mb-xl-4">
                <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                    <form id="addEditForm" action="{{ route('save-candidate') }}" method="POST"
                        enctype="multipart/form-data"
                        class=" card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end mb-5 mb-xl-10">
                        @csrf
                        <input type="hidden" name="can_id" value="{{ @$candidate->id }}">
                        <div class="card-body py-5">
                            <div class="row">
                                <div class="col-lg-2">
                                    <div class="mb-7">
                                        <label class="fs-6 fw-semibold mb-6">
                                            <span>Candidate Photo</span>
                                        </label>
                                        <div class="mt-1">
                                            <div class="image-input image-input-outline image-input-placeholder image-input-empty image-input-empty"
                                                data-kt-image-input="true">
                                                <div class="image-input-wrapper w-120px h-120px"
                                                    style="background-image: url('{{ isset($candidate->candidate_photo) ? asset('storage/' . $candidate->candidate_photo) : '' }}')">
                                                </div>
                                                <label
                                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                    data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                                    title="Update Photo">
                                                    <i class="ki-duotone ki-pencil fs-7">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    <input type="file" id="candidate_photo" name="candidate_photo"
                                                        accept=".png, .jpg, .jpeg" />
                                                    {{-- <input type="hidden" name="avatar_remove" /> --}}
                                                </label>
                                                <span
                                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                    data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                                    title="Cancel Photo">
                                                    <i class="ki-duotone ki-cross fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </span>
                                                <span
                                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                    data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                                    title="Remove avatar">
                                                    <i class="ki-duotone ki-cross fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </span>
                                            </div>
                                        </div>
                                        <span class="text-danger error-text candidate_photo_error"></span>
                                    </div>
                                </div>
                                <div class="col-lg-10">
                                    <div class="mb-5 fv-row fv-plugins-icon-container row gy-3">
                                        <div class="col-lg-4">
                                            <label class="form-label required">Candidate Name </label>
                                            <input type="text" id="candidate_name" name="candidate_name"
                                                class="form-control mb-2" placeholder="Candidate Name"
                                                value="{{ @$candidate->candidate_name }}"
                                                onkeypress="return allowAlphabetsSpaceNumbers(event);" maxlength="100">
                                            <span class="text-danger error-text candidate_name_error"></span>
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="form-label required"> Contact No.</label>
                                            <input type="text" id="contact_no" name="contact_no"
                                                class="form-control mb-2" placeholder=" Contact No."
                                                value="{{ @$candidate->contact_no }}" maxlength="10">
                                            <span class="text-danger error-text contact_no_error"></span>
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="form-label required"> Candidate Email</label>
                                            <input type="text" id="email" name="email" class="form-control mb-2"
                                                placeholder=" Candidate Email" value="{{ @$candidate->email }}"
                                                maxlength="100">
                                            <span class="text-danger error-text email_error"></span>
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="form-label required">Date of Birth</label>
                                            <input type="date" id="dob" name="dob"
                                                class="form-control datepicker" placeholder="Date of Birth"
                                                id="kt_datepicker_1" max="{{ date('Y-m-d', strtotime('-16 years')) }}"
                                                value="{{ isset($candidate->dob) ? date('Y-m-d', strtotime($candidate->dob)) : '' }}" />
                                            <span class="text-danger error-text dob_error"></span>
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="form-label required">Department</label>
                                            <select class="form-select" id="dep_id" name="dep_id"
                                                data-control="select2" data-placeholder="Select Department"
                                                data-hide-search="true">
                                                <option value=""></option>
                                                @foreach (\App\Models\Department::where('status', 1)->get() as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ isset($candidate->dep_id) && $candidate->dep_id == $item->id ? 'selected' : '' }}>
                                                        {{ $item->dep_name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text dep_id_error"></span>
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="form-label required">Rank</label>
                                            <select class="form-select" id="rank_id" name="rank_id"
                                                data-control="select2" data-close-on-select="false"
                                                data-placeholder="Select Rank" data-allow-clear="true">
                                                @if (isset($candidate))
                                                    @foreach (\App\Models\Rank::where('status', 1)->get() as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ isset($candidate->rank_id) && $item->id == $candidate->rank_id ? 'selected' : '' }}>
                                                            {{ $item->rank_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <span class="text-danger error-text rank_id_error"></span>
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="form-label required">CoC No.</label>
                                            <input type="text" id="coc_no" name="coc_no"
                                                class="form-control mb-2" placeholder="COC No."
                                                value="{{ @$candidate->coc_no }}">
                                            <span class="text-danger error-text coc_no_error"></span>
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="form-label">INDoS No.</label>
                                            <input type="text" id="indos_no" name="indos_no"
                                                class="form-control mb-2" placeholder="Endorse No."
                                                value="{{ @$candidate->indos_no }}">
                                            <span class="text-danger error-text indos_no_error"></span>
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="form-label required">Location</label>
                                            <input type="text" id="location" name="location"
                                                class="form-control mb-2" placeholder="Location"
                                                value="{{ @$candidate->location }}">
                                            <span class="text-danger error-text location_error"></span>
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="form-label required">Passport No</label>
                                            <input type="text" id="passport_no" name="passport_no"
                                                class="form-control mb-2" placeholder="Passport No"
                                                value="{{ @$candidate->passport_no }}">
                                            <span class="text-danger error-text passport_no_error"></span>
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="form-label required">Choose Option</label>
                                            <div>
                                                <label class="form-check-image active me-6">
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio" value="1"
                                                            name="type" id="type_offshore"
                                                            {{ isset($candidate->type) && $candidate->type == 1 ? 'checked' : '' }} />
                                                        <div class="form-check-label">
                                                            Offshore
                                                        </div>
                                                    </div>
                                                </label>
                                                <label class="form-check-image">
                                                    <div class="form-check form-check-custom form-check-solid me-10">
                                                        <input class="form-check-input" type="radio" value="2"
                                                            name="type" id="type_onshore"
                                                            {{ isset($candidate->type) && $candidate->type == 2 ? 'checked' : '' }} />
                                                        <div class="form-check-label">
                                                            Onshore
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                            <span class="text-danger error-text type_error"></span>
                                        </div>
                                        <div class="col-lg-4" id="type_date">
                                            <label class="form-label required">Till Date</label>
                                            <input type="date" id="till_date" name="till_date" class="form-control"
                                                placeholder="Till Date"
                                                value="{{ isset($candidate->till_date) ? date('Y-m-d', strtotime($candidate->till_date)) : '' }}" />
                                            <span class="text-danger error-text till_date_error"></span>
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="form-label">Upload Passport</label>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <input type="file" id="passport_file" name="passport_file"
                                                        class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center pt-4">
                                <a href="{{ route('candidate-list') }}" class="btn btn-sm btn-light me-5">Cancel</a>
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <span class="indicator-label">Save</span>
                                </button>
                            </div>
                        </div>
                    </form>
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
                    $(form).find('button[type="submit"]').prop('disabled',true);
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.href = "{{ route('candidate-list') }}";
                        }, 2000);
                    } else {
                        toastr.error(response.message);
                    }
                    $(form).find('button[type="submit"]').prop('disabled',false);
                },
                error: function(response) {
                    toastr.remove();
                    $.each(response.responseJSON.errors, function(prefix, val) {
                        $(form).find('span.' + prefix + '_error').text(val[0]);
                        $(form).find('[name="' + prefix + '"]').addClass('is-invalid');
                    });
                    $(form).find('button[type="submit"]').prop('disabled',false);
                }
            });
        });

        $("#dep_id").on('change', function(e) {
            get_ranks($(this).val());
        });

        if ($("[name='type']:checked").val() == 2) {
            $("#type_date").show();
        }

        $("[name='type']").on('change', function(e) {
            if ($(this).val() == 2) {
                $("#type_date").show();
            } else {
                $("#type_date").hide();
            }
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
    </script>
@endpush
