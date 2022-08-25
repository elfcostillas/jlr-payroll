<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\UserRightsController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

use App\Http\Controllers\Timekeeping\PayrollPeriodWeeklyController;
use App\Http\Controllers\Timekeeping\HolidayController;
use App\Http\Controllers\Timekeeping\PayrollPeriodController;
use App\Http\Controllers\Timekeeping\UploadLogController;

use App\Http\Controllers\Settings\LocationController;

use App\Http\Controllers\EmployeeFile\DivisionController;
use App\Http\Controllers\EmployeeFile\EmployeeController;
use App\Http\Controllers\EmployeeFile\DepartmentController;
use App\Http\Controllers\EmployeeFile\JobTitleController;
use App\Http\Controllers\Reports\EmployeeReportController;



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
    //return view('welcome');
    return redirect('login');
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

    Route::prefix('payroll-period-weekly')->group(function(){
        Route::get('/',[PayrollPeriodWeeklyController::class,'index']);
        Route::get('list',[PayrollPeriodWeeklyController::class,'list']);
        Route::post('create',[PayrollPeriodWeeklyController::class,'create']);
        Route::post('update',[PayrollPeriodWeeklyController::class,'update']);
    });

    Route::prefix('holiday')->group(function(){
        Route::get('/',[HolidayController::class,'index']);
        Route::get('list',[HolidayController::class,'list']);
        Route::get('types',[HolidayController::class,'getHolidayTypes']);
        Route::post('create',[HolidayController::class,'create']);
        Route::post('update',[HolidayController::class,'update']);
        Route::post('read-locations',[HolidayController::class,'showLocation']);
        Route::post('location-create',[HolidayController::class,'createLocation']);
        Route::post('location-destroy',[HolidayController::class,'destroyLocation']);
    
    });

    Route::prefix('upload-log')->group(function(){
        Route::get('/',[UploadLogController::class,'index']);
        Route::post('upload',[UploadLogController::class,'upload']);
    });
    
});

//divisions-departments
Route::middleware('auth')->prefix('employee-files')->group(function(){

    Route::prefix('employee-master-data')->group(function(){ 
        Route::get('/',[EmployeeController::class,'index']);
        Route::get('read/{id}',[EmployeeController::class,'readById']);
        Route::get('list',[EmployeeController::class,'list']);
        //Route::post('create',[EmployeeController::class,'create']);
        //Route::post('update',[EmployeeController::class,'update']);
        Route::post('save',[EmployeeController::class,'save']);
        
    });

    Route::prefix('divisions-departments')->group(function(){ 
        Route::get('/',[DivisionController::class,'index']);
        Route::get('division/list',[DivisionController::class,'list']);
        Route::post('division/create',[DivisionController::class,'create']);
        Route::post('division/update',[DivisionController::class,'update']);
        Route::get('division/get-divisions',[DivisionController::class,'getDivisions']);

        Route::get('department/list',[DepartmentController::class,'list']);
        Route::post('department/create',[DepartmentController::class,'create']);
        Route::post('department/update',[DepartmentController::class,'update']);
        
        Route::get('department/list-option/{div_id}',[DepartmentController::class,'listOption']);

    });

    Route::prefix('job-title')->group(function(){ 
        Route::get('/',[JobTitleController::class,'index']);
        Route::get('list',[JobTitleController::class,'list']);
        Route::post('create',[JobTitleController::class,'create']);
        Route::post('update',[JobTitleController::class,'update']);
        Route::get('get-departments',[JobTitleController::class,'getDepartments']);
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

Route::middleware('auth')->prefix('settings')->group(function(){
    //Route::get('locations',[LocationController::class,'index']);
    Route::prefix('locations')->group(function(){
        Route::get('/',[LocationController::class,'index']);
        Route::get('list',[LocationController::class,'list']);
        Route::post('create',[LocationController::class,'create']);
        Route::post('update',[LocationController::class,'update']);
        Route::get('get-locations',[LocationController::class,'listOption']);
    });
});

Route::middleware('auth')->prefix('reports')->group(function(){
    //Route::get('locations',[LocationController::class,'index']);
    Route::prefix('employee-report')->group(function(){
        Route::get('/',[EmployeeReportController::class,'index']);
        Route::get('generate',[EmployeeReportController::class,'generate']);
    });
});

Route::post('logout',[AuthenticatedSessionController::class,'logout'])->middleware('auth');


require __DIR__.'/auth.php';
