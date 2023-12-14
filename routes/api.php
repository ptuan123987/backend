<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\TopicController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::apiResource('/courses', CourseController::class);
Route::apiResource('/categories', CategoryController::class);
Route::apiResource('/topics', TopicController::class);
Route::apiResource('/lectures', LectureController::class);
Route::apiResource('/chapters', ChapterController::class);


Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('/forgot-password', [PasswordResetController::class, 'sendEmail']);
    Route::post('/reset-password', [ChangePasswordController::class, 'passwordResetProcess']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'user'
], function () {
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me', [AuthController::class, 'userProfile']);
    Route::post('/change-password', [AuthController::class, 'changePassWord']);
});
