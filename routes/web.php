<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Timekeeping\PayrollPeriodController;
use App\Http\Controllers\Admin\UserRightsController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    //return view('dashboard');
    return view('layouts.theme.layout');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->prefix('timekeeping')->group(function(){
    Route::prefix('payroll-period')->group(function(){
        Route::get('/',[PayrollPeriodController::class,'index']);
        Route::get('list',[PayrollPeriodController::class,'list']);

        Route::post('create',[PayrollPeriodController::class,'create']);
        Route::post('update',[PayrollPeriodController::class,'update']);
    });
    
});


Route::middleware('auth')->prefix('admin')->group(function(){
    Route::get('/',[UserRightsController::class,'index']);
    Route::get('user-list',[UserRightsController::class,'showAllUsers']);
    Route::get('show-user-rights/{id}',[UserRightsController::class,'showUserRights']);

    Route::post('rights-create',[UserRightsController::class,'createRights']);
    Route::post('rights-destroy',[UserRightsController::class,'destroyRights']);
    Route::post('userrights',[UserRightsController::class,'userRights']);
    
    
    
});


require __DIR__.'/auth.php';
