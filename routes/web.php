<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashBoardController;

use App\Http\Controllers\Admin\UserRightsController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

use App\Http\Controllers\Timekeeping\PayrollPeriodWeeklyController;
use App\Http\Controllers\Timekeeping\HolidayController;
use App\Http\Controllers\Timekeeping\PayrollPeriodController;
use App\Http\Controllers\Timekeeping\UploadLogController;
use App\Http\Controllers\Timekeeping\ManageDTRWeeklyController;
use App\Http\Controllers\Timekeeping\ManageDTRController;
use App\Http\Controllers\Timekeeping\ManualDTRController;
use App\Http\Controllers\Timekeeping\FTPController;
use App\Http\Controllers\Timekeeping\LeavesAbsencesController;
use App\Http\Controllers\Timekeeping\LeaveCreditsController;

use App\Http\Controllers\Settings\LocationController;
use App\Http\Controllers\Settings\DefaultScheduleController;
use App\Http\Controllers\Settings\DeductionTypeController;
use App\Http\Controllers\Settings\LoanTypeController;

use App\Http\Controllers\EmployeeFile\DivisionController;
use App\Http\Controllers\EmployeeFile\EmployeeController;
use App\Http\Controllers\EmployeeFile\DepartmentController;
use App\Http\Controllers\EmployeeFile\JobTitleController;
use App\Http\Controllers\Reports\EmployeeReportController;
use Carbon\CarbonPeriod;

use App\Http\Controllers\PayrollTransaction\PayrollRegisterController;
use App\Http\Controllers\PayrollTransaction\BankTransmittalController;
use App\Http\Controllers\PayrollTransaction\PayslipController;

use App\Http\Controllers\Accounts\BiometricController;
use App\Http\Controllers\Accounts\LeaveRequestController;

use  App\Http\Controllers\Deductions\OneTimeDeductionController;
use  App\Http\Controllers\Deductions\FixedDeductionController;
use  App\Http\Controllers\Deductions\InstallmentDeductionController;
use  App\Http\Controllers\Deductions\GovernmentLoanController;

use App\Http\Controllers\Compentsations\OtherCompensationController;
use App\Http\Controllers\Compentsations\FixCompensationController;

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
/*

Route::get('dashboard', function () {
    //return view('dashboard');
    return view('layouts.theme.layout');
    //Route::get('/',[DashBoardController::class,'index']);

})->middleware(['auth'])->name('dashboard');
*/
Route::get('dashboard', [DashBoardController::class,'index'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->prefix('timekeeping')->group(function(){
    
    Route::prefix('payroll-period')->middleware('access:timekeeping/payroll-period')->group(function(){
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

    //timekeeping/manage-dtr-weekly

    Route::prefix('manage-dtr-weekly')->middleware('access:timekeeping/manage-dtr-weekly')->group(function(){
        Route::get('/',[ManageDTRWeeklyController::class,'index']);
        Route::post('prepare',[ManageDTRWeeklyController::class,'prepareDTR']);
        Route::get('get-employee-list/{period_id}',[ManageDTRWeeklyController::class,'getEmployeeList']);
        Route::get('get-employee-raw-logs/{period_id}/{biometric_id}',[ManageDTRWeeklyController::class,'getEmployeeRawLogs']);
        Route::get('get-employee-dtr-logs/{period_id}/{biometric_id}',[ManageDTRWeeklyController::class,'getweeklyDTR']);
        
        Route::get('get-employee-schedules',[ManageDTRWeeklyController::class,'getSchedules']);
        Route::get('get-employee-schedules-sat',[ManageDTRWeeklyController::class,'getSchedulesSat']);
        
        Route::post('update-dtr',[ManageDTRWeeklyController::class,'updateDTR']);
        Route::post('draw-logs',[ManageDTRWeeklyController::class,'drawLogs']);
        Route::post('compute-logs',[ManageDTRWeeklyController::class,'computeLogs']);
        
    });

    // Route::prefix('manage-dtr-semi-monthly')->middleware('access:timekeeping/manage-dtr-semi-monthly')->group(function(){
    //     Route::get('/',[ManageDTRWeeklyController::class,'index']);
    //     Route::post('prepare',[ManageDTRWeeklyController::class,'prepareDTR']);
    //     Route::get('get-employee-list/{period_id}',[ManageDTRWeeklyController::class,'getEmployeeList']);
    //     Route::get('get-employee-raw-logs/{period_id}/{biometric_id}',[ManageDTRWeeklyController::class,'getEmployeeRawLogs']);
    //     Route::get('get-employee-dtr-logs/{period_id}/{biometric_id}',[ManageDTRWeeklyController::class,'getweeklyDTR']);
        
    //     Route::get('get-employee-schedules',[ManageDTRWeeklyController::class,'getSchedules']);
        
    //     Route::post('update-dtr',[ManageDTRWeeklyController::class,'updateDTR']);
    //     Route::post('draw-logs',[ManageDTRWeeklyController::class,'drawLogs']);
        
    // });

    Route::prefix('manage-dtr')->middleware('access:timekeeping/manage-dtr')->group(function(){
        Route::get('/',[ManageDTRController::class,'index']);
        Route::post('prepare',[ManageDTRController::class,'prepareDTR']);
        Route::get('get-employee-list/{period_id}',[ManageDTRController::class,'getEmployeeList']);
        Route::get('get-employee-raw-logs/{period_id}/{biometric_id}',[ManageDTRController::class,'getEmployeeRawLogs']);
        Route::get('get-employee-dtr-logs/{period_id}/{biometric_id}',[ManageDTRController::class,'getSemiDTR']);
        
        Route::get('get-employee-schedules',[ManageDTRController::class,'getSchedules']);
        
        Route::post('update-dtr',[ManageDTRController::class,'updateDTR']);
        Route::post('draw-logs',[ManageDTRController::class,'drawLogs']);
        Route::post('compute-logs',[ManageDTRController::class,'computeLogs']);
        Route::post('clear-logs',[ManageDTRController::class,'clearLogs']);
        Route::get('print/{period_id}',[ManageDTRController::class,'print']);
    });

    Route::prefix('upload-log')->group(function(){
        Route::get('/',[UploadLogController::class,'index']);
        Route::post('upload',[UploadLogController::class,'upload']);
    });

    Route::prefix('manual-dtr')->middleware('access:timekeeping/manual-dtr')->group(function(){
        Route::get('/',[ManualDTRController::class,'index']);
        Route::get('list',[ManualDTRController::class,'list']);
        Route::get('employee-list',[ManualDTRController::class,'getEmployees']);
        Route::get('header/{id}',[ManualDTRController::class,'header']);
        Route::post('save',[ManualDTRController::class,'save']);
        Route::get('details/{id}',[ManualDTRController::class,'details']);
        Route::post('detail-update',[ManualDTRController::class,'detailUpdate']);
        Route::get('print/{id}',[ManualDTRController::class,'print']);

        
    });
    
    Route::prefix('ftp')->middleware('access:timekeeping/ftp')->group(function(){
        Route::get('/',[FTPController::class,'index']);
        Route::get('list',[FTPController::class,'list']);
        //Route::get('employee-list',[FTPController::class,'getEmployees']);
       // Route::get('header/{id}',[FTPController::class,'header']);
        // Route::post('create',[FTPController::class,'save']);
        // Route::get('details/{id}',[FTPController::class,'details']);
        // Route::post('detail-update',[FTPController::class,'detailUpdate']);
        // Route::get('print/{id}',[FTPController::class,'print']);

        
    });

    Route::prefix('leaves-absences')->group(function(){
        Route::get('/',[LeavesAbsencesController::class,'index']);
        Route::get('list',[LeavesAbsencesController::class,'list']);
        Route::post('receive',[LeavesAbsencesController::class,'receive']);
        Route::post('update-detail',[LeavesAbsencesController::class,'updateDetail']);
        
        Route::get('copy',[LeavesAbsencesController::class,'getLeavesFrom100']);


    });

    Route::prefix('leave-credits')->group(function(){
        Route::get('/',[LeaveCreditsController::class,'index']);
        Route::get('list',[LeaveCreditsController::class,'list']);
        Route::get('year',[LeaveCreditsController::class,'yearList']);
        Route::get('employees/{year}',[LeaveCreditsController::class,'empList']);
        
        Route::post('save',[LeaveCreditsController::class,'save']);

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
        Route::get('job-titles/{id}',[EmployeeController::class,'getJobTitles']);
        
        
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

    Route::post('update-rights',[UserRightsController::class,'updateRights']);
    

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

    Route::prefix('default-schedules')->group(function(){
        Route::get('/',[DefaultScheduleController::class,'index']);
        Route::get('list',[DefaultScheduleController::class,'list']);
        Route::post('update',[DefaultScheduleController::class,'update']);
    });

    Route::prefix('deduction-type')->group(function(){
        Route::get('/',[DeductionTypeController::class,'index']);
        Route::get('list',[DeductionTypeController::class,'list']);
        Route::post('create',[DeductionTypeController::class,'create']);
        Route::post('update',[DeductionTypeController::class,'update']);
        Route::get('deduct-sched',[DeductionTypeController::class,'getDeductSched']);
       
        //Route::get('get-locations',[LocationController::class,'listOption']);
    });

    Route::prefix('loan-type')->group(function(){
        Route::get('/',[LoanTypeController::class,'index']);
        Route::get('list',[LoanTypeController::class,'list']);
        Route::post('create',[LoanTypeController::class,'create']);
        Route::post('update',[LoanTypeController::class,'update']);
       
    });

    
});

Route::middleware('auth')->prefix('deductions')->group(function(){
    Route::prefix('one-time')->group(function(){
        Route::get('/',[OneTimeDeductionController::class,'index']);
        Route::get('read-header/{id}',[OneTimeDeductionController::class,'readHeader']);
        Route::get('list/{id}',[OneTimeDeductionController::class,'list']);
        Route::get('list-details/{id}',[OneTimeDeductionController::class,'readDetail']);

        Route::post('save',[OneTimeDeductionController::class,'save']);
        //Route::post('update',[OneTimeDeductionController::class,'update']);

        Route::post('create-detail',[OneTimeDeductionController::class,'createDetail']);
        Route::post('update-detail',[OneTimeDeductionController::class,'updateDetail']);
        Route::post('delete-detail',[OneTimeDeductionController::class,'destroyDetail']);

        Route::get('list-types',[OneTimeDeductionController::class,'getTypes']);
        Route::get('list-payroll-period',[OneTimeDeductionController::class,'getPayrollPeriod']);
        Route::get('employee-list',[OneTimeDeductionController::class,'getEmployees']);
    
    });

    Route::prefix('fixed-deductions')->group(function(){
        Route::get('/',[FixedDeductionController::class,'index']);
        Route::get('list/{id}',[FixedDeductionController::class,'list']);

        Route::post('create',[FixedDeductionController::class,'create']);
        Route::post('update',[FixedDeductionController::class,'update']);
        Route::post('delete',[FixedDeductionController::class,'delete']);
        
        Route::get('list-types',[FixedDeductionController::class,'getTypes']);
        Route::get('list-payroll-period',[FixedDeductionController::class,'getPayrollPeriod']);
        Route::get('employee-list',[FixedDeductionController::class,'getEmployees']);
  
    });

    Route::prefix('installments')->group(function(){ 
        Route::get('/',[InstallmentDeductionController::class,'index']);
        Route::get('list/{biometric_id}',[InstallmentDeductionController::class,'list']);
        Route::get('employee-list',[InstallmentDeductionController::class,'getEmployees']);
        Route::get('deduct-sched-list',[InstallmentDeductionController::class,'getDeductSched']);
        Route::get('read-header/{id}',[InstallmentDeductionController::class,'readHeader']);
        
        Route::get('list-payroll-period',[InstallmentDeductionController::class,'getPayrollPeriod']);
        Route::get('list-types',[InstallmentDeductionController::class,'getTypes']);
        Route::post('save',[InstallmentDeductionController::class,'save']);
        //list-payroll-period
    });

    Route::prefix('government-loans')->group(function(){ 
        Route::get('/',[GovernmentLoanController::class,'index']);
        Route::get('list/{biometric_id}',[GovernmentLoanController::class,'list']);
        Route::get('employee-list',[GovernmentLoanController::class,'getEmployees']);
        Route::get('deduct-sched-list',[GovernmentLoanController::class,'getDeductSched']);
        Route::get('read-header/{id}',[GovernmentLoanController::class,'readHeader']);
        
        Route::get('list-payroll-period',[GovernmentLoanController::class,'getPayrollPeriod']);
        Route::get('list-types',[GovernmentLoanController::class,'getTypes']);
        Route::post('save',[GovernmentLoanController::class,'save']);
    });
});

Route::middleware('auth')->prefix('reports')->group(function(){
    //Route::get('locations',[LocationController::class,'index']);
    Route::prefix('employee-report')->group(function(){
        Route::get('/',[EmployeeReportController::class,'index']);
        Route::get('generate',[EmployeeReportController::class,'generate']);
    });
});

Route::middleware('auth')->prefix('accounts')->group(function(){
    //Route::get('locations',[LocationController::class,'index']);
    Route::prefix('biometric')->group(function(){
        Route::get('/',[BiometricController::class,'index']);
        Route::post('save',[BiometricController::class,'save']);
        Route::get('get-id',[BiometricController::class,'getID']);
        
    });

    Route::prefix('leave-request')->group(function(){
        Route::get('/',[LeaveRequestController::class,'index']);
        Route::get('list',[LeaveRequestController::class,'list']);
        Route::get('employee-list',[LeaveRequestController::class,'getEmployees']);
        Route::post('save',[LeaveRequestController::class,'save']);
        Route::get('read-header/{id}',[LeaveRequestController::class,'readHeader']);
        Route::get('read-detail/{id}',[LeaveRequestController::class,'readDetails']);
        Route::post('update-detail',[LeaveRequestController::class,'updateDetail']);
        Route::post('recreate',[LeaveRequestController::class,'recreate']);
        
    });

});


Route::middleware('auth')->prefix('payroll-transaction')->group(function(){
    //Route::get('locations',[LocationController::class,'index']);
    Route::prefix('payroll-register')->middleware('access:payroll-transaction/payroll-register')->group(function(){
        Route::get('/',[PayrollRegisterController::class,'index']);
        Route::get('unposted-payroll',[PayrollRegisterController::class,'getUnpostedPeriod']);
        Route::get('compute/{id}',[PayrollRegisterController::class,'compute']);
        
    }); 
});

Route::middleware('auth')->prefix('compensations')->group(function(){
    Route::prefix('fixed-compensations')->middleware('access:compensations/fixed-compensations')->group(function(){
        Route::get('/',[FixCompensationController::class,'index']);
        Route::get('list-types',[FixCompensationController::class,'getFixeddComp']);
        Route::get('list-payroll-period',[FixCompensationController::class,'getPayrollPeriod']);
        Route::get('list/{id}',[FixCompensationController::class,'list']);
        Route::post('save',[FixCompensationController::class,'save']);
        Route::get('read-header/{id}',[FixCompensationController::class,'readHeader']);
        Route::get('list-details/{id}',[FixCompensationController::class,'readDetail']);

        Route::post('update-detail',[FixCompensationController::class,'updateDetail']);

    }); 

    Route::prefix('other-compensations')->middleware('access:compensations/other-compensations')->group(function(){
        Route::get('/',[OtherCompensationController::class,'index']);
        Route::get('list-types',[OtherCompensationController::class,'getOtherComp']);
        Route::get('list-payroll-period',[OtherCompensationController::class,'getPayrollPeriod']);
        Route::get('list/{id}',[OtherCompensationController::class,'list']);
        Route::post('save',[OtherCompensationController::class,'save']);
        Route::get('read-header/{id}',[OtherCompensationController::class,'readHeader']);
        Route::get('list-details/{id}',[OtherCompensationController::class,'readDetail']);

        Route::post('update-detail',[OtherCompensationController::class,'updateDetail']);
    }); 
});



Route::post('logout',[AuthenticatedSessionController::class,'logout'])->middleware('auth');

Route::get('test',function(){
    
    $period = CarbonPeriod::create('2018-06-14','2018-06-20');

    // Iterate over the period
    foreach ($period as $date) {
        echo $date->format('Y-m-d')."<br>"; 
    }
});

Route::get('test2',function(){
    
    $period = CarbonPeriod::create('2018-06-14','2018-06-20');

    // Iterate over the period
    foreach ($period as $date) {
        echo $date->format('Y-m-d')."<br>"; 
    }
})->middleware('access:employee-report');


Route::get('now',function(){
    
    echo now();
});


require __DIR__.'/auth.php';
