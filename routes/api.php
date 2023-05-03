<?php

use App\Http\Controllers\Api\V1\Admin\AdjustmentController;
use App\Http\Controllers\Api\V1\Admin\AttendanceRecordController;
use App\Http\Controllers\Api\V1\Admin\DepartmentController;
use App\Http\Controllers\Api\V1\Admin\EmployeeTypeController;
use App\Http\Controllers\Api\V1\Admin\InvitationUrlController;
use App\Http\Controllers\Api\V1\Admin\JobDetailController;
use App\Http\Controllers\Api\V1\Admin\ProfileController;
use App\Http\Controllers\Api\V1\Admin\RoleController;
use App\Http\Controllers\Api\V1\Admin\ShiftController;
use App\Http\Controllers\Api\V1\Admin\WorkspaceController;
use App\Http\Controllers\Api\V1\User\AuthController;
use App\Http\Controllers\Api\V1\User\UserController;
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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
