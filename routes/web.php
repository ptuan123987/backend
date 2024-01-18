<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/user/choose-marital-status', [UserController::class, 'chooseMaritalStatus']);
Route::post('/user/update-marital-status', [UserController::class, 'updateMaritalStatus'])->name('updateMaritalStatus');
