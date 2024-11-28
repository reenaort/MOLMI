@extends('layouts.login-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Login')
@section('content')
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <!--begin::Authentication - Sign-in -->
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <!--begin::Body-->
            <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
                <!--begin::Form-->
                <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                    <!--begin::Wrapper-->
                    <div class="w-lg-500px p-10">
                        <!--begin::Form-->
                        <div class="logo">
                            <img src={{ asset('assets/img/logo-big.png') }} class="img-fluid pb-6">
                        </div>
                        <form class="form w-350px center-align" id="loginform">
                            @csrf
                            <!--begin::Heading-->
                            <div class="text-center mb-11">
                                <!--begin::Title-->
                                <h1 class="text-gray-900 fw-bolder mb-3">Sign In</h1>
                                <!--end::Title-->
                            </div>
                            <!--begin::Heading-->
                            <!--begin::Input group=-->
                            <div class="fv-row mb-6">
                                <!--begin::Email-->
                                <input type="text" placeholder="Email" name="email" autocomplete="off"
                                    class="form-control bg-transparent" />
                                <!--end::Email-->
                            </div>
                            <!--end::Input group=-->
                            <div class="fv-row mb-3">
                                <!--begin::Password-->
                                <input type="password" placeholder="Password" name="password" autocomplete="off"
                                    class="form-control bg-transparent" />
                                <!--end::Password-->
                            </div>
                            <!--end::Input group=-->
                            <!--begin::Submit button-->
                            <div class="d-grid mb-10 mt-8">
                                <button type="submit" id="sbmtform" class="indicator-label btn btn-primary">Sign
                                    In</button>

                            </div>
                            <!--end::Submit button-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Form-->

            </div>
            <!--end::Body-->
            <!--begin::Aside-->
            <div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2"
                style="background-image: url('public/assets/img/login-img.png')">
                <!--begin::Content-->

                <!--end::Content-->
            </div>
            <!--end::Aside-->
        </div>
        <!--end::Authentication - Sign-in-->
    </div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('[name="_token"]').val(),
                'Accept': 'application/json',
            }
        });
        $(function() {
            $("#loginform").validate({
                errorClass: 'errors',
                errorPlacement: function(error, element) {
                    error.insertAfter(element.parent());
                },
                rules: {
                    email: "required",
                    password: "required",
                },
                messages: {
                    email: "Please enter valid email",
                    password: "Please enter valid password",
                }
            });

            var url = "{{ url('/') }}"

            $('#loginform').submit(function(e) {
                e.preventDefault();
                var variable = $("#loginform").valid();
                if (variable) {
                    var formData = new FormData($('#loginform')[0]);
                    $.ajax({
                        url: "{{ route('validatelogin') }}",
                        method: "post",
                        data: formData,
                        processData: false,
                        dataType: "json",
                        contentType: false,
                        beforeSend: function() {

                        },
                        success: function(response) {
                            console.log(response);
                            if (response.success) {
                                toastr.remove();
                                toastr.options = {
                                    "closeButton": true,
                                    "progressBar": true
                                }
                                toastr.success(response.message);
                                setTimeout(function() {
                                    location.href = "{{ route('dashboard') }}";
                                }, 2000);
                            } else {
                                toastr.remove();
                                toastr.options = {
                                    "closeButton": true,
                                    "progressBar": true
                                }
                                toastr.error(response.message);
                            }
                        },
                        error: function(jqXhr, json, errorThrown) {
                            toastr.error(jqXhr.responseJSON.message);
                        }
                    });
                }
            })
        })
    </script>
@endpush
