<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\VesselController;
use App\Http\Controllers\CoursetypeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\RankController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrainCenterController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\MatrixController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::middleware(['guest:web'])->group(function () {
    Route::view("/", "pages.login")->name('login');
});

Route::middleware(['auth:web'])->group(function () {
    Route::get("/dashboard", [DashboardController::class, 'dashboard'])->name('dashboard');

    // Category
    Route::view("/category", "pages.category-master")->name('categorymaster');
    Route::get('/getCategories', [CategoryController::class, 'getCategories'])->name('getCategories');
    Route::post('/save-category', [CategoryController::class, 'save_category'])->name('save-category');
    Route::post('/edit-category', [CategoryController::class, 'edit_category'])->name('edit-category');
    Route::post('/delete-category', [CategoryController::class, 'delete_category'])->name('delete-category');
    Route::post('/change-category-status', [CategoryController::class, 'change_status'])->name('change-category-status');

    // Subcategory
    Route::view("/subcategory", "pages.subcategory-master")->name('subcategorymaster');
    Route::get('/getSubCategories', [SubCategoryController::class, 'getSubCategories'])->name('getSubCategories');
    Route::post('/save-subcategory', [SubCategoryController::class, 'save_subcategory'])->name('save-subcategory');
    Route::post('/edit-subcategory', [SubCategoryController::class, 'edit_subcategory'])->name('edit-subcategory');
    Route::post('/delete-subcategory', [SubCategoryController::class, 'delete_subcategory'])->name('delete-subcategory');
    Route::post('/change-subcategory-status', [SubCategoryController::class, 'change_status'])->name('change-subcategory-status');

    // Vessels
    Route::view("/vessels", "pages.vessel-master")->name('vesselsmaster');
    Route::get('/getVessels', [VesselController::class, 'getVessels'])->name('getVessels');
    Route::post('/save-vessel', [VesselController::class, 'save_vessel'])->name('save-vessel');
    Route::post('/edit-vessel', [VesselController::class, 'edit_vessel'])->name('edit-vessel');
    Route::post('/delete-vessel', [VesselController::class, 'delete_vessel'])->name('delete-vessel');
    Route::post('/change-vessel-status', [VesselController::class, 'change_status'])->name('change-vessel-status');

    //CourseType
    Route::view("/course-type", "pages.coursetype-master")->name('coursetypemaster');
    Route::get('/getCourseTypes', [CoursetypeController::class, 'getCourseTypes'])->name('getCourseTypes');
    Route::post('/save-coursetypes', [CoursetypeController::class, 'save_coursetype'])->name('save-coursetype');
    Route::post('/edit-coursetype', [CoursetypeController::class, 'edit_coursetype'])->name('edit-coursetype');
    Route::post('/delete-coursetype', [CoursetypeController::class, 'delete_coursetype'])->name('delete-coursetype');
    Route::post('/change-coursetype-status', [CoursetypeController::class, 'change_status'])->name('change-coursetype-status');

    // Department
    Route::view("/department", "pages.department-master")->name('departmentmaster');
    Route::get('/getDepartments', [DepartmentController::class, 'getDepartments'])->name('getDepartments');
    Route::post('/save-department', [DepartmentController::class, 'save_department'])->name('save-department');
    Route::post('/edit-department', [DepartmentController::class, 'edit_department'])->name('edit-department');
    Route::post('/delete-department', [DepartmentController::class, 'delete_department'])->name('delete-department');
    Route::post('/change-department-status', [DepartmentController::class, 'change_status'])->name('change-department-status');

    // Rank
    Route::view("/rank", "pages.rank-master")->name('rankmaster');
    Route::get('/getRanks', [RankController::class, 'getRanks'])->name('getRanks');
    Route::post('/save-rank', [RankController::class, 'save_rank'])->name('save-rank');
    Route::post('/edit-rank', [RankController::class, 'edit_rank'])->name('edit-rank');
    Route::post('/delete-rank', [RankController::class, 'delete_rank'])->name('delete-rank');
    Route::post('/change-rank-status', [RankController::class, 'change_status'])->name('change-rank-status');

    // Training Center
    Route::view("/training-center", "pages.training-center-master")->name('trainingcentermaster');
    Route::get('/getTrainingCenters', [TrainCenterController::class, 'getTrainingCenters'])->name('getTrainingCenters');
    Route::post('/save-center', [TrainCenterController::class, 'save_center'])->name('save-center');
    Route::post('/edit-center', [TrainCenterController::class, 'edit_center'])->name('edit-center');
    Route::post('/delete-center', [TrainCenterController::class, 'delete_center'])->name('delete-center');

    // Course
    Route::view('/courses', 'pages.course-list')->name('course-list');
    Route::view('/add-course', 'pages.course-add-update')->name('add-course');
    Route::get('/edit-course/{any}', [CourseController::class, 'edit_course'])->name('edit-course');
    Route::post('/getCourses', [CourseController::class, 'getCourses'])->name('getCourses');
    Route::post('/save-course', [CourseController::class, 'save_course'])->name('save-course');
    Route::post('/save-course-map', [CourseController::class, 'save_course_map'])->name('save-course-map');
    Route::post('/change-course-status', [CourseController::class, 'change_status'])->name('change-course-status');
    Route::post('/delete-course', [CourseController::class, 'delete_course'])->name('delete-course');
    Route::post('/get-subcategories', [CourseController::class, 'get_subcategories'])->name('get-subcategories');
    Route::post('/get-multiple-subcategories', [CourseController::class, 'get_multiple_subcategories'])->name('get-multiple-subcategories');
    Route::post('/get-vessels', [CourseController::class, 'get_vessels'])->name('get-vessels');
    Route::post('/get-ranks', [CourseController::class, 'get_ranks'])->name('get-ranks');
    Route::post('/get-multiple-ranks', [CourseController::class, 'get_multiple_ranks'])->name('get-multiple-ranks');

    // Course Mining
    Route::view('/course-mining', 'pages.course-mining')->name('course-mining');

    // Candidate Wise Matrix
    Route::view('/candidate-wise-matrix', 'pages.candidate-wise-matrix')->name('candidate-wise-matrix');

    // Course Wise Matrix
    Route::view('/course-wise-matrix', 'pages.course-wise-matrix')->name('course-wise-matrix');

    // Candidate
    Route::view('/candidates', 'pages.candidate-list')->name('candidate-list');
    Route::view('/add-candidate', 'pages.candidate-add-update')->name('add-candidate');
    Route::get('/edit-candidate/{any}', [CandidateController::class, 'edit_candidate'])->name('edit-candidate');
    Route::post('/getCandidates', [CandidateController::class, 'getCandidates'])->name('getCandidates');
    Route::post('/getCandidate-info', [CandidateController::class, 'getCandidateInfo'])->name('getCandidate-info');
    Route::post('/save-candidate', [CandidateController::class, 'save_candidate'])->name('save-candidate');
    Route::post('/delete-candidate', [CandidateController::class, 'delete_candidate'])->name('delete-candidate');

    Route::post('/get-candidate-wise-course', [MatrixController::class, 'get_candidate_wise_course'])->name('get-candidate-wise-course');
    Route::post('/store-course-certification-date', [MatrixController::class, 'store_course_certification_date'])->name('store-course-certification-date');

    Route::post('/get-date-wise-courses', [MatrixController::class, 'get_date_wise_courses'])->name('get-date-wise-courses');
    Route::post('/store-course-enrollment', [MatrixController::class, 'store_course_enrollment'])->name('store-course-enrollment');

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
