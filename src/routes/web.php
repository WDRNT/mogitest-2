<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\LoginController as AdminLoginController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceRequestController;
use App\Http\Controllers\AdminAttendanceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('auth')->group(function(){
    Route::get('/attendance', [AttendanceController::class, 'index']);
    Route::get('/attendance/list', [AttendanceController::class, 'list']);
    Route::get('/attendance/detail/{id}', [AttendanceController::class, 'edit']);
});


Route::post('/attendance', [AttendanceController::class, 'storeAttendance']);

Route::post('/attendance/detail/{id}', [AttendanceRequestController::class, 'store']);

Route::get('/stamp_correction_request/list', [AttendanceRequestController::class, 'show'])
    ->middleware('auth');

Route::get('/stamp_correction_request/approve/{attendance_correct_request_id}', [AttendanceRequestController::class, 'edit'])
    ->middleware('auth');

Route::post('/stamp_correction_request/approve/{attendance_correct_request_id}', [AttendanceRequestController::class, 'update']);

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    })->middleware('throttle:6,1')->name('verification.send');



Route::get('/admin/login', function () {
        return view('admin.login');
    });

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/attendance/list',[AdminAttendanceController::class, 'index']);

    Route::get('/attendance/csv',[AdminAttendanceController::class, 'exportCsv']);

    Route::get('/attendance/{id}',[AdminAttendanceController::class, 'edit']);
    Route::post('/attendance/{id}',[AdminAttendanceController::class, 'update']);

    Route::get('/staff/list',[AdminAttendanceController::class, 'staff_list']);

    Route::get('/attendance/staff/{id}',[AdminAttendanceController::class, 'show']);

});