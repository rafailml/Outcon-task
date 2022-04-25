<?php

use App\Http\Controllers\ManagerEmployeesController;
use App\Http\Controllers\EmployeeManagersController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UserProfileImageUploadController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/user/managers', EmployeeManagersController::class)->name('user_managers');
    Route::get('/user/employees', ManagerEmployeesController::class)->name('user_employees');

    Route::get('/user/profile', [UserProfileController::class, 'show'])->name('show_user_profile');
    Route::put('/user/profile', [UserProfileController::class, 'update'])->name('update_user_profile');
    Route::post('/user/profile/image-upload', UserProfileImageUploadController::class)->name('update_profile_picture');

    Route::get('/user', function (Request $request) {
        return new UserResource($request->user());
    })->name('get_user');

});
