<?php

use App\Http\Controllers\AnalyticRevenueController;
use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\SocialAccountController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\CourseReviewController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\LoginRecordsController;
use App\Http\Controllers\PaidCourseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ScheduleLearningReminderController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UserController;
use App\Models\LoginRecords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/upload',[UploadController::class,'upload']);


Route::get('/courses', [CourseController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/topics', [TopicController::class, 'index']);
Route::get('/lectures', [LectureController::class, 'index']);
Route::get('/chapters', [ChapterController::class, 'index']);


Route::get('/courses/{id}', [CourseController::class, 'show']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::get('/topics/{id}', [TopicController::class, 'show']);


Route::middleware('check.paid.course')->get('/lectures/{id}', [LectureController::class, 'show']);
Route::middleware('jwt.auth')->get('/check-paid-course/{id}',[PaidCourseController::class,'checkPaidCourse']);

Route::middleware('jwt.auth')->get('/check-course-wishlist/{id}',[WishlistController::class,'checkCourseInWishlist']);

Route::get('/chapters/{id}', [ChapterController::class, 'show']);

Route::get('/topics/{id}/courses', [TopicController::class, 'getCourses']);
Route::get('/categories/{id}/topics', [CategoryController::class, 'getTopics']);
Route::get('/categories/{id}/courses', [CategoryController::class, 'getCourses']);
Route::get('/categories/{id}/subcategories',[CategoryController::class,'getSubcategories']);
Route::get('/courses/{id}/reviews', [CourseController::class, 'get_reviews']);
Route::get('/courses/{id}/chapters', [CourseController::class, 'get_chapters']);
Route::get('/search/courses', [SearchController::class, 'search_courses']);
Route::get('login/{social}', [
    SocialAccountController::class, 'redirectToProvider'
]);

Route::get('callback/{social}', [
    SocialAccountController::class, 'handleProviderCallback'
]);


Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('/reset-password', [ChangePasswordController::class, 'passwordResetProcess']);
    Route::post('/forgot-password', [PasswordResetController::class, 'sendEmail']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'user'
], function () {
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me', [AuthController::class, 'userProfile']);
    Route::post('/change-password', [AuthController::class, 'changePassWord']);
    Route::apiResource('/wishlists', WishlistController::class);
    Route::apiResource('/course-reviews', CourseReviewController::class);
    Route::put('/edit-profile',[AuthController::class,'editProfile']);
    Route::get('/paid-courses',[PaidCourseController::class,'index']);
    Route::post('/check-out/momo', [PaymentController::class,'momoPayment']);

    Route::post('/create-payment-link', [CheckoutController::class, 'createPaymentLink']);
    Route::get('/check-out/success',[PaymentController::class,'handleSuccessPayment']);
    Route::post('/check-out/vn-pay',[PaymentController::class,'vnPayment']);
    Route::put('/set-schedule-reminder', [ScheduleLearningReminderController::class, 'update']);
    Route::post('/set-schedule-reminder', [ScheduleLearningReminderController::class, 'store']);
    Route::get('/get-schedule-reminder',[ScheduleLearningReminderController::class,'fetchScheduleByUser']);
});

// admin
Route::group([
    'prefix' => 'admin'
], function () {
    Route::post('/login', [AdminController::class, 'login']);
});

Route::middleware("jwt.auth")->post('/accept-course', [EnrollmentController::class,'acceptUserToCourse']);

// API Resources with middleware for POST, PUT, DELETE
Route::group([
    'middleware' => ['api', 'jwt.admin']
], function () {
    Route::apiResource('/courses', CourseController::class)->except(['index', 'show']);
    Route::apiResource('/categories', CategoryController::class)->except(['index', 'show','getTopics','getCourses','getSubcategories']);
    Route::patch('/categories/{id}/courses/{courseId}', [CategoryController::class, 'attachCourse']);
    Route::apiResource('/topics', TopicController::class)->except(['index', 'show']);
    Route::apiResource('/lectures', LectureController::class)->except(['index', 'show']);
    Route::apiResource('/chapters', ChapterController::class)->except(['index', 'show']);

    Route::get('/analytics/daily-login/{days?}', [LoginRecordsController::class, 'countDailyLogins']);
    Route::get('/analytics/weekly-login',[LoginRecordsController::class,'countWeeklyLogins']);
    Route::get('/analytics/monthly-login/{months?}',[LoginRecordsController::class,'countMonthlyLogins']);
    Route::get('/analytics/yearly-login/{years?}',[LoginRecordsController::class,'countYearlyLogins']);

    Route::get('/analytics/daily-revenue/{days?}', [AnalyticRevenueController::class, 'revenueDaily']);
    Route::get('/analytics/monthly-revenue/{months?}',[AnalyticRevenueController::class,'revenueMonthly']);
    Route::get('/analytics/yearly-revenue/{years?}',[AnalyticRevenueController::class,'revenueYearly']);
    Route::apiResource('/users',UserController::class);
});


