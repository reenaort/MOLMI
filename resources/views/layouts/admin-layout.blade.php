<!DOCTYPE html>
<html lang="en">

<head>
    <title>LMS</title>
    <meta charset="utf-8" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="" />
    <meta property="og:url" content="" />
    <meta property="og:site_name" content="" />
    <link rel="canonical" href="" />
    <link rel="shortcut icon" href="{{ asset('assets/img/MOL.png') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href={{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') }} rel="stylesheet"
        type="text/css" />
    <link href={{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }} rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('amsify/amsify.suggestags.css') }}">
    <link href={{ asset('assets/plugins/global/plugins.bundle.css') }} rel="stylesheet" type="text/css" />
    <link href={{ asset('assets/css/style.bundle.css') }} rel="stylesheet" type="text/css" />
    <link href={{ asset('assets/css/custom.css') }} rel="stylesheet" type="text/css" />
    @stack('styles')
    <style>
        .modal-dialog {
            margin: auto;
            /* This will center the modal horizontally */
            top: 20%;
            /* Position it from the top */
            transform: translateY(-20%);
            /* Center it vertically */
        }
    </style>
</head>

<body id="kt_app_body" data-kt-app-layout="light-header" data-kt-app-header-fixed="true"
    data-kt-app-toolbar-enabled="true" class="app-default">
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            <div id="kt_app_header" class="app-header" data-kt-sticky="true"
                data-kt-sticky-activate="{default: true, lg: true}" data-kt-sticky-name="app-header-minimize"
                data-kt-sticky-offset="{default: '200px', lg: '0'}" data-kt-sticky-animation="false">
                <div class="app-container container-fluid d-flex align-items-stretch justify-content-between"
                    id="kt_app_header_container">
                    <div class="d-flex align-items-center d-lg-none ms-n3 me-1 me-md-2" title="Show sidebar menu">
                        <div class="btn btn-icon btn-active-color-primary w-35px h-35px"
                            id="kt_app_sidebar_mobile_toggle">
                            <i class="ki-duotone ki-abstract-14 fs-2 fs-md-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0 me-12">
                        <a href="dashboard.html">
                            <img alt="Logo" src="{{ asset('assets/img/logo.png') }}" class="img-fluid h-50px" />
                        </a>
                    </div>
                    <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1"
                        id="kt_app_header_wrapper">
                        <div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true"
                            data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}"
                            data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="end"
                            data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true"
                            data-kt-swapper-mode="{default: 'append', lg: 'prepend'}"
                            data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
                            <div class="menu menu-rounded menu-column menu-lg-row my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0"
                                id="kt_app_header_menu" data-kt-menu="true">
                                <div class="menu-item  me-0 me-lg-2">
                                    <a class="menu-link {{ request()->is('dashboard') ? 'active' : '' }}"
                                        href="{{ route('dashboard') }}">
                                        <span class="menu-title">Dashboard</span>
                                    </a>
                                </div>
                                @php
                                    $master_array = [
                                        'category',
                                        'subcategory',
                                        'department',
                                        'rank',
                                        'training-center',
                                        'course-type',
                                        'vessels',
                                    ];
                                    $course_array = ['courses', 'candidate-wise-matrix', 'course-wise-matrix'];
                                @endphp
                                <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                                    data-kt-menu-placement="bottom-start"
                                    class="menu-item menu-here-bg menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
                                    <span
                                        class="menu-link  {{ in_array(request()->path(), $master_array) ? 'active' : '' }}">
                                        <span class="menu-title">Masters</span>
                                        <span class="menu-arrow "></span>
                                    </span>
                                    <div
                                        class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-2 w-lg-200px">
                                        <div class="menu-item">
                                            <a class="menu-link py-1 {{ request()->is('category') ? 'active' : '' }}"
                                                href="{{ route('categorymaster') }}">
                                                <span class="menu-title">Category</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link py-1 {{ request()->is('subcategory') ? 'active' : '' }}"
                                                href="{{ route('subcategorymaster') }}">
                                                <span class="menu-title">Sub Category</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link py-1 {{ request()->is('vessels') ? 'active' : '' }}"
                                                href="{{ route('vesselsmaster') }}">
                                                <span class="menu-title">Vessels</span>
                                            </a>
                                        </div>
                                         <div class="menu-item">
                                            <a class="menu-link py-1 {{ request()->is('course-type') ? 'active' : '' }}"
                                                href="{{ route('coursetypemaster') }}">
                                                <span class="menu-title">Course Type</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link py-1 {{ request()->is('department') ? 'active' : '' }}"
                                                href="{{ route('departmentmaster') }}">
                                                <span class="menu-title">Department</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link py-1 {{ request()->is('rank') ? 'active' : '' }}"
                                                href="{{ route('rankmaster') }}">
                                                <span class="menu-title">Ranks</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link py-1 {{ request()->is('training-center') ? 'active' : '' }}"
                                                href="{{ route('trainingcentermaster') }}">
                                                <span class="menu-title">Training Centers</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                                    data-kt-menu-placement="bottom-start"
                                    class="menu-item menu-here-bg menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
                                    <span
                                        class="menu-link {{ in_array(request()->path(), $course_array) ? 'active' : '' }}">
                                        <span class="menu-title">Courses</span>
                                        <span class="menu-arrow "></span>
                                    </span>
                                    <div
                                        class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-2 w-lg-200px">
                                        <div class="menu-item">
                                            <a class="menu-link py-1 {{ request()->is('courses') ? 'active' : '' }}"
                                                href="{{ route('course-list') }}">
                                                <span class="menu-title">Course Details</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link py-1 {{ request()->is('candidate-wise-matrix') ? 'active' : '' }}"
                                                href="{{ route('candidate-wise-matrix') }}">
                                                <span class="menu-title">Candidate Wise Matrix</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link py-1 {{ request()->is('course-wise-matrix') ? 'active' : '' }}"
                                                href="{{ route('course-wise-matrix') }}">
                                                <span class="menu-title">Course Wise Matrix</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link {{ request()->is('candidates') ? 'active' : '' }}"
                                        href="{{ route('candidate-list') }}">
                                        <span class="menu-title">Candidates </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="app-navbar flex-shrink-0">
                            {{-- <div class="app-navbar-item ms-1 ms-md-4">
                                <div class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px"
                                    data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                                    data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end"
                                    id="kt_menu_item_wow">
                                    <i class="ki-duotone ki-notification fs-2x fs-xs-2x">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </div>
                                <div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px notf-dropdown"
                                    data-kt-menu="true" id="kt_menu_notifications">
                                    <div class="d-flex flex-column bgi-no-repeat rounded-top"
                                        style="background-color:#035CA9;">
                                        <h3 class="text-white fw-semibold px-5 mt-6 mb-5">Notifications </h3>
                                    </div>
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="kt_topbar_notifications_1"
                                            role="tabpanel">
                                            <div class="scroll-y mh-325px my-2 px-4">
                                                <div class="d-flex flex-stack py-4 br-dash-btm">
                                                    <div class="d-flex align-items-center">
                                                        <div class="mb-0 me-2">
                                                            <div class="fs-6 text-gray-800 fw-bold"> Ankita Kadale
                                                            </div>
                                                            <div class="text-gray-600 fs-7 notf-text">Sed ut
                                                                perspiciatis unde omnis iste natus</div>
                                                        </div>
                                                    </div>
                                                    <span class="badge badge-light fs-8">1 hr</span>
                                                </div>
                                                <div class="d-flex flex-stack py-4 br-dash-btm">
                                                    <div class="d-flex align-items-center">
                                                        <div class="mb-0 me-2">
                                                            <div class="fs-6 text-gray-800 fw-bold">Kalpesh Mishra
                                                            </div>
                                                            <div class="text-gray-600 fs-7 notf-text">Sed ut
                                                                perspiciatis unde omnis iste natus</div>
                                                        </div>
                                                    </div>
                                                    <span class="badge badge-light fs-8">2 hrs</span>
                                                </div>
                                                <div class="d-flex flex-stack py-4 br-dash-btm">
                                                    <div class="d-flex align-items-center">
                                                        <div class="mb-0 me-2">
                                                            <div class="fs-6 text-gray-800 fw-bold">Smriti Rastogi
                                                            </div>
                                                            <div class="text-gray-600 fs-7 notf-text">Sed ut
                                                                perspiciatis unde omnis iste natus</div>
                                                        </div>
                                                    </div>
                                                    <span class="badge badge-light fs-8">5 hrs</span>
                                                </div>
                                                <div class="d-flex flex-stack py-4 br-dash-btm">
                                                    <div class="d-flex align-items-center">
                                                        <div class="mb-0 me-2">
                                                            <div class="fs-6 text-gray-800  fw-bold"> Ankita Kadale
                                                            </div>
                                                            <div class="text-gray-600 fs-7 notf-text">Sed ut
                                                                perspiciatis unde omnis iste natus</div>
                                                        </div>
                                                    </div>
                                                    <span class="badge badge-light fs-8">1 hr</span>
                                                </div>
                                                <div class="d-flex flex-stack py-4 br-dash-btm">
                                                    <div class="d-flex align-items-center">
                                                        <div class="mb-0 me-2">
                                                            <div class="fs-6 text-gray-800  fw-bold">Kalpesh Mishra
                                                            </div>
                                                            <div class="text-gray-600 fs-7 notf-text">Sed ut
                                                                perspiciatis unde omnis iste natus</div>
                                                        </div>
                                                    </div>
                                                    <span class="badge badge-light fs-8">2 hrs</span>
                                                </div>
                                                <div class="d-flex flex-stack py-4 br-dash-btm">
                                                    <div class="d-flex align-items-center">
                                                        <div class="mb-0 me-2">
                                                            <div class="fs-6 text-gray-800  fw-bold">Smriti Rastogi
                                                            </div>
                                                            <div class="text-gray-600 fs-7 notf-text">Sed ut
                                                                perspiciatis unde omnis iste natus</div>
                                                        </div>
                                                    </div>
                                                    <span class="badge badge-light fs-8">5 hrs</span>
                                                </div>
                                                <div class="d-flex flex-stack py-4 br-dash-btm">
                                                    <div class="d-flex align-items-center">
                                                        <div class="mb-0 me-2">
                                                            <div class="fs-6 text-gray-800  fw-bold"> Ankita Kadale
                                                            </div>
                                                            <div class="text-gray-600 fs-7 notf-text">Sed ut
                                                                perspiciatis unde omnis iste natus</div>
                                                        </div>
                                                    </div>
                                                    <span class="badge badge-light fs-8">1 hr</span>
                                                </div>
                                                <div class="d-flex flex-stack py-4 br-dash-btm">
                                                    <div class="d-flex align-items-center">
                                                        <div class="mb-0 me-2">
                                                            <div class="fs-6 text-gray-800  fw-bold">Kalpesh Mishra
                                                            </div>
                                                            <div class="text-gray-600 fs-7 notf-text">Sed ut
                                                                perspiciatis unde omnis iste natus</div>
                                                        </div>
                                                    </div>
                                                    <span class="badge badge-light fs-8">2 hrs</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="app-navbar-item ms-1 ms-md-4" id="kt_header_user_menu_toggle">
                                <div class="cursor-pointer symbol symbol-35px"
                                    data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                                    data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                                    <img src={{ asset('assets/media/avatars/blank.png') }} class="rounded-3"
                                        alt="user" />
                                </div>
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-2 fs-6 w-275px"
                                    data-kt-menu="true">
                                    <div class="menu-item px-3">
                                        <div class="menu-content d-flex align-items-center px-3 py-0">
                                            <div class="symbol symbol-50px me-5">
                                                <img alt="Logo"
                                                    src={{ asset('assets/media/avatars/blank.png') }} />
                                            </div>
                                            <div class="d-flex flex-column">
                                                <div class="fw-bold d-flex align-items-center fs-5">Welcome
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="separator my-2"></div>
                                    <!--<div class="menu-item px-5">-->
                                    <!--    <a href="#" class="menu-link px-4 py-1">Account Settings</a>-->
                                    <!--</div>-->
                                    <div class="menu-item px-5">
                                        <a href="{{ route('logout') }}" class="menu-link px-4 py-1">Sign Out</a>
                                    </div>
                                </div>
                            </div>
                            <div class="app-navbar-item d-lg-none ms-2 me-n2" title="Show header menu">
                                <div class="btn btn-flex btn-icon btn-active-color-primary w-30px h-30px"
                                    id="kt_app_header_menu_toggle">
                                    <i class="ki-duotone ki-element-4 fs-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <div class="d-flex flex-column flex-column-fluid">
                        @yield('content')
                        <div id="kt_app_footer" class="app-footer">
                            <div class="app-container container-fluid  py-3">
                                <div class="text-gray-900 text-center">
                                    <span class="text-muted fw-semibold me-1"></span> All Right Reserved | Copyright
                                    &copy; MOLMI
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src={{ asset('assets/plugins/global/plugins.bundle.js') }}></script>
        <script src={{ asset('assets/js/scripts.bundle.js') }}></script>
        <script src={{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}></script>
        <script src={{ asset('assets/js/widgets.bundle.js') }}></script>
        <script src={{ asset('assets/js/custom/widgets.js') }}></script>
        <script src={{ asset('assets/js/custom/apps/chat/chat.js') }}></script>
        <script src={{ asset('assets/js/custom/utilities/modals/upgrade-plan.js') }}></script>
        <script src={{ asset('assets/js/custom/utilities/modals/create-app.js') }}></script>
        <script src={{ asset('assets/js/custom/utilities/modals/new-target.js') }}></script>
        <script src={{ asset('assets/js/custom/utilities/modals/users-search.js') }}></script>
        <script src="{{ asset('amsify/jquery.amsify.suggestags.js') }}"></script>
        <script>
            function allowAlphabetsSpaceNumbers(event) {
                var charCode = event.which ? event.which : event.keyCode;

                // Allow only alphabets (A-Z, a-z), space (32), and numbers (0-9)
                if (
                    (charCode >= 65 && charCode <= 90) || // A-Z
                    (charCode >= 97 && charCode <= 122) || // a-z
                    (charCode >= 48 && charCode <= 57) || // 0-9
                    charCode === 32 // space
                ) {
                    return true;
                }

                return false;
            }
        </script>
        @stack('scripts')
</body>

</html>
