<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserGroupController;
use App\Http\Controllers\DeviceController;

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

Route::get('/', [PageController::class,'index']);

Route::get('/login', [PageController::class,'login'])->name('login');
Route::post('/authenticate', [AuthController::class,'authenticate']);
Route::get('/logout', [AuthController::class,'logout']);
Route::get('/dashboard', [DashboardController::class,'index']);

Route::post('/fetchUser', [UserController::class,'fetchUser']);
Route::resource('/user', UserController::class);

Route::post('resetPassword/{id}', [UserController::class,'resetPassword']);
Route::get('editProfile', [UserController::class,'editProfile']);
Route::post('editProfileChange', [UserController::class,'editProfileChange']);

Route::post('/fetchUserGroup', [UserGroupController::class,'fetchUserGroup']);
Route::resource('/user_group', UserGroupController::class, [
    'except' => [ 'show' ]
]);

Route::post('/fetchDevice', [DeviceController::class,'fetchDevice']);
Route::resource('/device', DeviceController::class);

Route::get('/myApi', [DashboardController::class,'myApi']);