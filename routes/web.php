<?php

use App\Http\Controllers\AttendanceController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DashboardController;

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

require __DIR__.'/auth.php';
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/attendance', [AttendanceController::class, 'index'])->middleware(['can:doing-attendance']);
    Route::get('/attendance/working-user', [AttendanceController::class, 'workingUser'])->middleware(['can:doing-attendance']);
    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])->middleware(['can:doing-attendance']);
    Route::post('/attendance', [AttendanceController::class, 'addActivity'])->middleware(['can:doing-attendance']);
    Route::post('/attendance/update/{id}', [AttendanceController::class, 'updateActivity'])->middleware(['can:doing-attendance']);
    Route::post('/attendance/struggle/{id}', [AttendanceController::class, 'struggle'])->middleware(['can:doing-attendance']);
    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])->middleware(['can:doing-attendance']);
    Route::post('/attendance/out-of-office', [AttendanceController::class, 'outOfOffice'])->middleware(['can:doing-attendance']);
    Route::post('/attendance/out-sick', [AttendanceController::class, 'outSick'])->middleware(['can:doing-attendance']);
    Route::get('/attendance/history', [AttendanceController::class, 'history'])->middleware(['can:doing-attendance']);
    Route::get('/attendance/performance', [AttendanceController::class, 'performance'])->middleware(['can:doing-attendance']);
    Route::get('/profile', [ProfileController::class, 'edit'])->middleware(['can:manage-profile']);
    Route::post('/profile', [ProfileController::class, 'update'])->middleware(['can:manage-profile']);
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->middleware(['can:manage-profile']);
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->middleware(['can:manage-profile']);
    Route::post('/profile/background', [ProfileController::class, 'updateBackground'])->middleware(['can:manage-profile']);
});