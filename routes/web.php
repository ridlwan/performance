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
    Route::get('/attendance', [AttendanceController::class, 'index']);
    Route::post('/attendance', [AttendanceController::class, 'addActivity']);
    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn']);
    Route::post('/attendance/struggle/{id}', [AttendanceController::class, 'struggle']);
    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut']);
    Route::post('/attendance/out-of-office', [AttendanceController::class, 'outOfOffice']);
    Route::post('/attendance/out-sick', [AttendanceController::class, 'outSick']);
    Route::get('/attendance/history', [AttendanceController::class, 'history']);
    Route::get('/attendance/performance', [AttendanceController::class, 'performance']);
});