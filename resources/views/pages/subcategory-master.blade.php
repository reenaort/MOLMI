@extends('layouts.admin-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Dashboard')
@section('content')

    <div id="kt_app_toolbar" class="app-toolbar py-2 py-lg-4">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Sub
                    Category
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Masters</li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-gray-700">Sub Category</li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="card card-flush mb-4">
                <div class="card-body py-4">
                    <div class="filter">
                        <form id="addEditForm" action="{{ route('save-subcategory') }}" method="POST" class="row">
                            @csrf
                            <input type="hidden" name="subcat_id">
                            <div class="col-lg-3">
                                <label class="form-label">Category Name<span class="mandat">*</span></label>
                                <select class="form-select" data-control="select2" data-placeholder="Select Category"
                                    name="cat_id" data-hide-search="true">
                                    <option></option>
                                    @foreach (\App\Models\Category::where('status', 1)->get() as $item)
                                        <option value="{{ $item->id }}">{{ $item->cat_name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error-text cat_id_error"></span>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label">Sub Category Name<span class="mandat">*</span></label>
                                <input type="text" id="subcat_name" name="subcat_name" class="form-control mb-2"
                                    placeholder="Sub Category Name" onkeypress="return allowAlphabetsSpaceNumbers(event);"
                                    maxlength="100">
                                <span class="text-danger error-text subcat_name_error"></span>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">Tags</label>
                                <input class="form-control" placeholder="Tags" id="subcat_tags" name="subcat_tags"
                                    onkeypress="return allowAlphabetsSpaceNumbers(event);" maxlength="100" />
                                <span class="text-danger error-text subcat_tags_error"></span>
                            </div>
                            <div class="col-lg-2">
                                <div class="pt-9">
                                    <button type="submit" class="btn btn-sm btn-primary">Add</button>
                                    <button type="button" class="btn btn-sm btn-light"
                                        onclick="return resetForm();">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card card-flush">
                <div class="card-body">
                    <table class="table align-middle table-row-dashed fs-6 gy-3 checkbox-table" id="subcategory_table">
                        <thead>
                            <tr class="text-start fw-600 fs-6 gs-0">
                                <th class="min-w-60px">Sr. No.</th>
                                <th class="min-w-125px"> Category Name</th>
                                <th class="min-w-175px">Sub Category Name</th>
                                <th class="min-w-100px">Tags</th>
                                <th class="min-w-80px">Status</th>
                                <th class="min-w-60px">Action</th>
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

        $('input[name="subcat_tags"]').amsifySuggestags();

        var table = $("#subcategory_table").DataTable({
            processing: true,
            serverSide: true,
            //responsive: true,
            aLengthMenu: [
                [10, 15, 25, 50, 100, -1],
                [10, 15, 25, 50, 100, "All"]
            ],
            ajax: "{{ route('getSubCategories') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: true,
                },
                {
                    data: 'cat_name',
                    name: 'cat_name',
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'subcat_name',
                    name: 'subcat_name',
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'subcat_tags',
                    name: 'subcat_tags',
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

        table.on('draw', function() {
            $('[data-bs-toggle="tooltip"]').tooltip(); // Reinitialize tooltips
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
                        table.ajax.reload();
                        resetForm();
                        toastr.success(response.message);
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

        function edit_data(id) {
            toastr.remove();
            var formdata = new FormData();
            formdata.append('subcat_id', id);
            $.ajax({
                url: "{{ route('edit-subcategory') }}",
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
                        $("[name='subcat_id']").val(id);
                        $("[name='cat_id']").val(response.data.cat_id).trigger('change');
                        $("[name='subcat_name']").val(response.data.subcat_name);
                        $("[name='subcat_tags']").val(response.data.subcat_tags);
                        $("button[type='submit']").text('Update');
                        $('input[name="subcat_tags"]').amsifySuggestags({}, 'refresh');
                        $("[name='subcat_name']").focus();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(response) {
                    toastr.error(response.message);
                }
            });
        }

        function change_status(id, status) {
            let formData = new FormData();
            formData.append('subcat_id', id);
            formData.append('status', status);
            $.ajax({
                url: "{{ route('change-subcategory-status') }}",
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
                title: 'Delete Sub Category',
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
                    formdata.append('subcat_id', id);
                    $.ajax({
                        url: "{{ route('delete-subcategory') }}",
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

        function resetForm() {
            $("form#addEditForm")[0].reset();
            $("form#addEditForm input[name='subcat_id']").val('');
            $('input[name="subcat_tags"]').amsifySuggestags({}, 'refresh');
            $("form").find('input, select, textarea').removeClass('is-invalid');
            $("form").find('span.error-text').text('');
            $("[name='cat_id']").val(null).trigger('change');
            $("form#addEditForm button[type='submit']").text("Add");
        }
    </script>
@endpush
