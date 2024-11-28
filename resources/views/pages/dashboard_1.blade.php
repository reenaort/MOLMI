@extends('layouts.admin-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Dashboard')
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
                <div class="row g-2 gx-xl-5 mb-2 mb-xl-4">
                    <!--begin::Col-->
                    <div class="col-md-2 col-lg-2 col-xl-2 col-xxl-2">
                        <!--begin::Card widget 20-->
                        <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end mb-5 mb-xl-10"
                            style="background-color: #cadceb;">
                            <!--begin::Header-->
                            <div class="card-body py-5">
                                <!--begin::Title-->
                                <div class="card-title d-flex flex-column">
                                    <!--begin::Amount-->
                                    <span class="fs-2hx fw-bold logo-color me-2 lh-1 ls-n2">100</span>
                                    <!--end::Amount-->
                                    <!--begin::Subtitle-->
                                    <span class="logo-color opacity-75 pt-2 fw-semibold fs-5">Total
                                        Courses</span>
                                    <!--end::Subtitle-->
                                </div>
                                <!--end::Title-->
                            </div>
                            <!--end::Header-->
                        </div>
                        <!--end::Card widget 20-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-md-2 col-lg-2 col-xl-2 col-xxl-2">
                        <!--begin::Card widget 20-->
                        <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end mb-5 mb-xl-10"
                            style="background-color: #cadceb;">
                            <!--begin::Header-->
                            <div class="card-body py-5">
                                <!--begin::Title-->
                                <div class="card-title d-flex flex-column">
                                    <!--begin::Amount-->
                                    <span class="fs-2hx fw-bold logo-color me-2 lh-1 ls-n2">70</span>
                                    <!--end::Amount-->
                                    <!--begin::Subtitle-->
                                    <span class="logo-color opacity-75 pt-2 fw-semibold fs-5">Total
                                        Active Courses</span>
                                    <!--end::Subtitle-->
                                </div>
                                <!--end::Title-->
                            </div>
                            <!--end::Header-->
                        </div>
                        <!--end::Card widget 20-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-md-2 col-lg-2 col-xl-2 col-xxl-2">
                        <!--begin::Card widget 20-->
                        <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end  mb-5 mb-xl-10"
                            style="background-color: #cadceb;">
                            <!--begin::Header-->
                            <div class="card-body py-5">
                                <!--begin::Title-->
                                <div class="card-title d-flex flex-column">
                                    <!--begin::Amount-->
                                    <span class="fs-2hx fw-bold logo-color me-2 lh-1 ls-n2">200</span>
                                    <!--end::Amount-->
                                    <!--begin::Subtitle-->
                                    <span class="logo-color opacity-75 pt-2 fw-semibold fs-5">Total
                                        Candidates</span>
                                    <!--end::Subtitle-->
                                </div>
                                <!--end::Title-->
                            </div>
                            <!--end::Header-->
                        </div>
                        <!--end::Card widget 20-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->

                <!--begin::Row-->
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10 d-none">
                    <!--begin::Col-->
                    <div class="col-xl-6">
                        <!--begin::Chart widget 36-->
                        <div class="card card-flush overflow-hidden h-lg-100">
                            <!--begin::Header-->
                            <div class="card-header pt-5">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-900">Performance</span>
                                    <span class="text-gray-500 mt-1 fw-semibold fs-6">1,046 Inbound
                                        Calls today</span>
                                </h3>
                                <!--end::Title-->
                                <!--begin::Toolbar-->
                                <div class="card-toolbar">
                                    <!--begin::Daterangepicker(defined in src/js/layout/app.js)-->
                                    <div data-kt-daterangepicker="true" data-kt-daterangepicker-opens="left"
                                        data-kt-daterangepicker-range="today"
                                        class="btn btn-sm btn-light d-flex align-items-center px-4">
                                        <!--begin::Display range-->
                                        <div class="text-gray-600 fw-bold">Loading date range...
                                        </div>
                                        <!--end::Display range-->
                                        <i class="ki-outline ki-calendar-8 text-gray-500 lh-0 fs-2 ms-2 me-0"></i>
                                    </div>
                                    <!--end::Daterangepicker-->
                                </div>
                                <!--end::Toolbar-->
                            </div>
                            <!--end::Header-->
                            <!--begin::Card body-->
                            <div class="card-body d-flex align-items-end p-0">
                                <!--begin::Chart-->
                                <div id="kt_charts_widget_36" class="min-h-auto w-100 ps-4 pe-6" style="height: 300px">
                                </div>
                                <!--end::Chart-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Chart widget 36-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-xl-6">
                        <!--begin::Chart widget 18-->
                        <div class="card card-flush h-xl-100">
                            <!--begin::Header-->
                            <div class="card-header pt-7">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-800">Learn
                                        Activity</span>
                                    <span class="text-gray-500 mt-1 fw-semibold fs-6">Hours per
                                        course</span>
                                </h3>
                                <!--end::Title-->
                                <!--begin::Toolbar-->
                                <div class="card-toolbar">
                                    <!--begin::Daterangepicker(defined in src/js/layout/app.js)-->
                                    <div data-kt-daterangepicker="true" data-kt-daterangepicker-opens="left"
                                        class="btn btn-sm btn-light d-flex align-items-center px-4">
                                        <!--begin::Display range-->
                                        <div class="text-gray-600 fw-bold">Loading date range...
                                        </div>
                                        <!--end::Display range-->
                                        <i class="ki-duotone ki-calendar-8 text-gray-500 lh-0 fs-2 ms-2 me-0">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                            <span class="path5"></span>
                                            <span class="path6"></span>
                                        </i>
                                    </div>
                                    <!--end::Daterangepicker-->
                                </div>
                                <!--end::Toolbar-->
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body d-flex align-items-end px-0 pt-3 pb-5">
                                <!--begin::Chart-->
                                <div id="kt_charts_widget_18_chart" class="h-325px w-100 min-h-auto ps-4 pe-6"></div>
                                <!--end::Chart-->
                            </div>
                            <!--end: Card Body-->
                        </div>
                        <!--end::Chart widget 18-->
                    </div>
                    <!--end::Col-->
                </div>

            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
@endsection
