<?php

use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\SocialAccountController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\CourseReviewController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\SearchController;
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



Route::get('/courses', [CourseController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/topics', [TopicController::class, 'index']);
Route::get('/lectures', [LectureController::class, 'index']);
Route::get('/chapters', [ChapterController::class, 'index']);


Route::get('/courses/{id}', [CourseController::class, 'show']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::get('/topics/{id}', [TopicController::class, 'show']);
Route::get('/lectures/{id}', [LectureController::class, 'show']);
Route::get('/chapters/{id}', [ChapterController::class, 'show']);

Route::get('/topics/{id}/courses', [TopicController::class, 'getCourses']);
Route::get('/categories/{id}/topics', [CategoryController::class, 'getTopics']);
Route::get('/categories/{id}/courses', [CategoryController::class, 'getCourses']);
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
});



// admin
Route::group([
    'prefix' => 'admin'
], function () {
    Route::post('/login', [AdminController::class, 'login']);
});


// API Resources with middleware for POST, PUT, DELETE
Route::group([
    'middleware' => ['api', 'jwt.admin']
], function () {
    Route::apiResource('/courses', CourseController::class)->except(['index', 'show']);
    Route::apiResource('/categories', CategoryController::class)->except(['index', 'show']);
    Route::patch('/categories/{id}/courses/{courseId}', [CategoryController::class, 'attachCourse']);
    Route::apiResource('/topics', TopicController::class)->except(['index', 'show']);
    Route::apiResource('/lectures', LectureController::class)->except(['index', 'show']);
    Route::apiResource('/chapters', ChapterController::class)->except(['index', 'show']);
});
