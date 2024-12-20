<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashBoardController;

use App\Http\Controllers\Admin\UserRightsController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PasswordResetController;

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
use App\Http\Controllers\Timekeeping\DTRSummaryController;
use App\Http\Controllers\Timekeeping\AttController;
use App\Http\Controllers\Timekeeping\ManageLocationController;

use App\Http\Controllers\Settings\LocationController;
use App\Http\Controllers\Settings\DefaultScheduleController;
use App\Http\Controllers\Settings\DeductionTypeController;
use App\Http\Controllers\Settings\LoanTypeController;
use App\Http\Controllers\Settings\SSSTableController;
use App\Http\Controllers\Settings\PhilHealthController;
use App\Http\Controllers\Settings\OtherIncomeWeeklyController;

use App\Http\Controllers\EmployeeFile\DivisionController;
use App\Http\Controllers\EmployeeFile\EmployeeController;
use App\Http\Controllers\EmployeeFile\EmployeeWeeklyController;
use App\Http\Controllers\EmployeeFile\DepartmentController;
use App\Http\Controllers\EmployeeFile\JobTitleController;
use App\Http\Controllers\Reports\EmployeeReportController;
use App\Http\Controllers\Reports\LeaveReportsController;
use App\Http\Controllers\Reports\ManHoursController;
use Carbon\CarbonPeriod;

use App\Http\Controllers\PayrollTransaction\PayrollRegisterController;
use App\Http\Controllers\PayrollTransaction\PayrollRegisterWeeklyController;
use App\Http\Controllers\PayrollTransaction\BankTransmittalController;
use App\Http\Controllers\PayrollTransaction\PayslipController;
use App\Http\Controllers\PayrollTransaction\PayslipWeeklyController;
use App\Http\Controllers\PayrollTransaction\PayrollRegisterConfiController;

use App\Http\Controllers\Accounts\BiometricController;
use App\Http\Controllers\Accounts\LeaveRequestController;

use  App\Http\Controllers\Deductions\OneTimeDeductionController;
use  App\Http\Controllers\Deductions\FixedDeductionController;
use  App\Http\Controllers\Deductions\InstallmentDeductionController;
use  App\Http\Controllers\Deductions\GovernmentLoanController;
use  App\Http\Controllers\Deductions\WeeklyDeductionController;

use App\Http\Controllers\Compentsations\OtherCompensationController;
use App\Http\Controllers\Compentsations\FixCompensationController;
use App\Http\Controllers\Compentsations\OtherIncomeWeeklyAppController;
use App\Http\Controllers\Memo\AWOLMemoController;
use App\Http\Controllers\Reports\TardinessReportsController;
use App\Http\Controllers\Memo\TardinessMemoController;
use App\Http\Controllers\PayrollTransaction\ThirteenthMonthController;
use App\Http\Controllers\Reports\AttendanceReportController;
use App\Http\Controllers\Reports\PayrollSupportGroupController;
use App\Http\Controllers\Timekeeping\WeeklyDTRUploaderController;



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

    Route::prefix('att')->middleware('access:timekeeping/att')->group(function(){
        Route::get('/',[AttController::class,'index']);
        Route::post('download',[AttController::class,'download']);
       
       
    });
    
    Route::prefix('manage-location')->middleware('access:timekeeping/payroll-period')->group(function(){
        Route::get('/',[ManageLocationController::class,'index']);
        Route::get('list',[ManageLocationController::class,'list']);
        Route::get('get-employee-list/{period_id}',[ManageLocationController::class,'employeeList']);
        Route::get('print/{period_id}',[ManageLocationController::class,'print']);
        Route::post('update',[ManageLocationController::class,'update']);

       
    });

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

    Route::prefix('manage-dtr-weekly')->group(function(){
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
        Route::post('draw-logs-manual',[ManageDTRWeeklyController::class,'drawLogsM']);
        Route::post('compute-all',[ManageDTRWeeklyController::class,'computeAll']);

        Route::get('download/{period_id}',[ManageDTRWeeklyController::class,'download']);
        
    });

    Route::prefix('manage-dtr-weekly-sub')->middleware('access:timekeeping/manage-dtr-weekly-sub')->group(function(){
        Route::get('/',[ManageDTRWeeklyController::class,'index_sub']);
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
        Route::get('iprint/{period_id}/{biometric_id}',[ManageDTRController::class,'iprint']);

        Route::get('onetimebigtime/{period_id}',[ManageDTRController::class,'onetimebigtime']);

        Route::get('download/{period_id}',[ManageDTRController::class,'exportSemiDTR']);

        Route::post('compute-all',[ManageDTRController::class,'onetimebigtime']);
        Route::get('set-sched/{period_id}',[ManageDTRController::class,'scheduleSetter']);

        Route::get('payroll-period',[PayrollPeriodController::class,'list']);

        Route::get('list-department',[ManageDTRController::class,'listDepartment']);

        
    });

    Route::prefix('upload-log')->group(function(){
        Route::get('/',[UploadLogController::class,'index']);
        Route::post('upload',[UploadLogController::class,'upload']);
    });

     Route::prefix('upload-csv')->group(function(){
        Route::get('/',[UploadLogController::class,'index_csv']);
        Route::post('upload',[UploadLogController::class,'upload_csv']);
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
        Route::get('weekly-period',[ManualDTRController::class,'weeklyPeriod']);

        
    });

    Route::prefix('upload-weekly')->middleware('access:timekeeping/upload-weekly')->group(function(){
        Route::get('/',[WeeklyDTRUploaderController::class,'index']);
        Route::post('upload',[WeeklyDTRUploaderController::class,'upload']);
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
        Route::post('approve',[FTPController::class,'approve']);
        Route::post('save',[FTPController::class,'save']);

        Route::get('employee-list',[FTPController::class,'getEmployees']);
        Route::get('read/{id}',[FTPController::class,'readByID']);

        Route::post('update',[FTPController::class,'update']);


        
    });

    Route::prefix('leaves-absences')->group(function(){
        Route::get('/',[LeavesAbsencesController::class,'index']);
        Route::get('list',[LeavesAbsencesController::class,'list']);
        Route::post('receive',[LeavesAbsencesController::class,'receive']);
        Route::post('unpost',[LeavesAbsencesController::class,'unpost']);
        Route::post('update-detail',[LeavesAbsencesController::class,'updateDetail']);
        
        Route::get('copy',[LeavesAbsencesController::class,'getLeavesFrom100']);
        Route::get('get-encode-leave-credits',[LeavesAbsencesController::class,'makeQueryfor100']);

    });

    Route::prefix('leave-credits')->group(function(){
        Route::get('/',[LeaveCreditsController::class,'index']);
        Route::get('list',[LeaveCreditsController::class,'list']);
        Route::get('year',[LeaveCreditsController::class,'yearList']);
        Route::get('employees/{year}',[LeaveCreditsController::class,'empList']);
        
        Route::post('save',[LeaveCreditsController::class,'save']);

        Route::get('download-balance/{year}',[LeaveCreditsController::class,'download']);

        Route::get('show-leaves/{biometric_id}/{year}',[LeaveCreditsController::class,'showLeaves']);
        Route::get('make-leave-credits',[LeaveCreditsController::class,'makeLeaveCredits']);

    });

    //DTRSummaryController

    Route::prefix('dtr-summary')->group(function(){
        Route::get('/',[DTRSummaryController::class,'index']);
        Route::get('period-list',[DTRSummaryController::class,'periodList']);
        Route::get('download/{period_id}',[DTRSummaryController::class,'download']);
    });

    Route::prefix('dtr-summary-uploader')->group(function(){
        
        Route::get('/',[UploadLogController::class,'index_summary']);
        Route::post('upload',[UploadLogController::class,'upload_summary']);
    });
        
   
    Route::prefix('leave-credits')->group(function(){

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

        Route::get('biometric-assignment',[EmployeeController::class,'bioAssignment']);
        Route::post('copy-onlinerequest',[EmployeeController::class,'copyToOR']);
        
    });

    Route::prefix('employee-master-data-weekly')->group(function(){ 
        Route::get('/',[EmployeeWeeklyController::class,'index']);
        Route::get('read/{id}',[EmployeeWeeklyController::class,'readById']);
        Route::get('list',[EmployeeWeeklyController::class,'list']);
        //Route::post('create',[EmployeeController::class,'create']);
        //Route::post('update',[EmployeeController::class,'update']);
        Route::post('save',[EmployeeWeeklyController::class,'save']);
        Route::get('job-titles/{id}',[EmployeeWeeklyController::class,'getJobTitles']);

        Route::get('biometric-assignment',[EmployeeWeeklyController::class,'bioAssignment']);
        
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

    Route::prefix('sss-table')->group(function(){
        Route::get('/',[SSSTableController::class,'index']);
        Route::get('list',[SSSTableController::class,'list']);
        // Route::get('create',[SSSTableController::class,'create']);
        // Route::get('update',[SSSTableController::class,'update']);
        Route::post('save',[SSSTableController::class,'save']);
        
    });

    Route::prefix('philhealth')->group(function(){
        Route::get('/',[PhilHealthController::class,'index']);
        Route::post('save',[PhilHealthController::class,'save']);
        Route::get('get-rate',[PhilHealthController::class,'getPhicRate']);
        
    });

    Route::prefix('other-income-weekly')->group(function(){
        Route::get('/',[OtherIncomeWeeklyController::class,'index']);
        Route::get('list',[OtherIncomeWeeklyController::class,'list']);

        Route::post('create',[OtherIncomeWeeklyController::class,'create']);
        Route::post('update',[OtherIncomeWeeklyController::class,'update']);

        //Route::post('save',[OtherIncomeWeeklyController::class,'save']);
        //Route::get('get-rate',[OtherIncomeWeeklyController::class,'getPhicRate']);
        
    });
    
});

Route::middleware('auth')->prefix('deductions')->group(function(){
    Route::prefix('one-time')->group(function(){
        Route::get('/',[OneTimeDeductionController::class,'index']);
        Route::get('read-header/{id}',[OneTimeDeductionController::class,'readHeader']);
        Route::get('list/{id}',[OneTimeDeductionController::class,'list']);
        Route::get('list-details/{id}',[OneTimeDeductionController::class,'readDetail']);

        Route::get('download/{id}',[OneTimeDeductionController::class,'download']);

        Route::post('save',[OneTimeDeductionController::class,'save']);
        //Route::post('update',[OneTimeDeductionController::class,'update']);

        Route::post('create-detail',[OneTimeDeductionController::class,'createDetail']);
        Route::post('update-detail',[OneTimeDeductionController::class,'updateDetail']);
        Route::post('delete-detail',[OneTimeDeductionController::class,'destroyDetail']);

        Route::get('list-types',[OneTimeDeductionController::class,'getTypes']);
        Route::get('list-payroll-period',[OneTimeDeductionController::class,'getPayrollPeriod']);
        Route::get('employee-list',[OneTimeDeductionController::class,'getEmployees']);

        Route::post('upload',[OneTimeDeductionController::class,'upload']);
    
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

        Route::get('download-non-confi',[InstallmentDeductionController::class,'dlNonConfi']);
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

    Route::prefix('weekly')->group(function(){
        Route::get('/',[WeeklyDeductionController::class,'index']);
        Route::get('list',[WeeklyDeductionController::class,'list']);
        Route::get('emp-list/{period_id}',[WeeklyDeductionController::class,'employeeList']);
        Route::post('update',[OtherIncomeWeeklyAppController::class,'update']);

        Route::get('print/{period_id}',[WeeklyDeductionController::class,'print']);
    });
});

Route::middleware('auth')->prefix('reports')->group(function(){
    //Route::get('locations',[LocationController::class,'index']);

    Route::prefix('leave-reports')->group(function(){

    });
    
    Route::prefix('employee-report')->group(function(){
        Route::get('/',[EmployeeReportController::class,'index']);
        Route::get('generate',[EmployeeReportController::class,'generate']);
        Route::get('generate-weekly',[EmployeeReportController::class,'generateWeekly']);

        Route::get('print-weekly',[EmployeeReportController::class,'printWeekly']);

        Route::post('include-header',[EmployeeReportController::class,'includeHeader']);
        Route::post('remove-header',[EmployeeReportController::class,'removeHeader']);
        Route::get('get-header',[EmployeeReportController::class,'getHeader']);
        Route::get('custom-report',[EmployeeReportController::class,'customReport']);
    });

    Route::prefix('leave-reports')->group(function(){
        Route::get('/',[LeaveReportsController::class,'index']);
        Route::get('generate/{from}/{to}',[LeaveReportsController::class,'getLeavesFromTo']);
        Route::get('view/{from}/{to}',[LeaveReportsController::class,'getLeavesFromToWeb']);
        Route::get('generate-summary/{from}/{to}',[LeaveReportsController::class,'getLeaveSumamry']);
        Route::get('generate-by-employee/{from}/{to}',[LeaveReportsController::class,'getLeaveByEmployee']);
        Route::get('generate-by-employee-confi/{from}/{to}',[LeaveReportsController::class,'getLeaveByEmployeeConfi']);

        Route::get('view-kpi/{from}/{to}',[LeaveReportsController::class,'viewKPI']);
        Route::get('generate-by-pay-type/{from}/{to}',[LeaveReportsController::class,'getLeavesByPayType']);
        Route::get('leave-on-date/{date}',[LeaveReportsController::class,'leaveOnDate']);

        //Route::get('generate',[LeaveReportsController::class,'generate']);
    });

    Route::prefix('tardiness-reports')->group(function(){
        Route::get('/',[TardinessReportsController::class,'index']);

        Route::get('generate-detailed/{from}/{to}/{div}/{dept}',[TardinessReportsController::class,'detailedReport']);
        Route::get('generate-summary/{from}/{to}/{div}/{dept}',[TardinessReportsController::class,'summarizedReport']);

        Route::get('yearly-tardiness/{year}',[TardinessReportsController::class,'tardindessYearly']);
    });
    //
    Route::prefix('man-hours')->group(function(){
        Route::get('/',[ManHoursController::class,'index']);
        Route::get('generate/{from}/{to}/{hr1}/{hr2}',[ManHoursController::class,'generateReport']);
        Route::get('pdf/{from}/{to}/{hr1}/{hr2}',[ManHoursController::class,'viewPDF']);

        Route::get('generate-ot/{from}/{to}/{hr1}/{hr2}',[ManHoursController::class,'generateReportOT']);
        Route::get('pdf-ot/{from}/{to}/{hr1}/{hr2}',[ManHoursController::class,'viewPDFOT']);
       
       
    });

    Route::prefix('attendance')->group(function(){
        Route::get('/',[AttendanceReportController::class,'index']);
        Route::get('generate-detailed/{from}/{to}',[AttendanceReportController::class,'generate']);
        Route::get('awol-setter/{year}/{month}',[AttendanceReportController::class,'setAWOL']);
        Route::get('fill-blank',[AttendanceReportController::class,'fillBlank']);
        Route::get('sub/{from}/{to}/{biometric_id}/{type}',[AttendanceReportController::class,'sub']);

        Route::get('tardy-setter/{year}/{month}',[AttendanceReportController::class,'setTARDY']);
       
       
    });

    Route::prefix('payroll-support-group')->group(function(){
        Route::get('/',[PayrollSupportGroupController::class,'index']);
        Route::get('period-list',[PayrollSupportGroupController::class,'periodList']);
        Route::get('payroll-report/{id}',[PayrollSupportGroupController::class,'downloadPayrollReport']);
        
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
        
        Route::get('showBalance/{from}/{biometric_id}',[LeaveRequestController::class,'showBalance']);
        
    });

});


Route::middleware('auth')->prefix('payroll-transaction')->group(function(){
    //Route::get('locations',[LocationController::class,'index']);
    Route::prefix('payroll-register')->middleware('access:payroll-transaction/payroll-register')->group(function(){
        Route::get('/',[PayrollRegisterController::class,'index']);
        Route::get('unposted-payroll',[PayrollRegisterController::class,'getUnpostedPeriod']);
        Route::get('compute/{id}',[PayrollRegisterController::class,'compute']);
        Route::get('download-unposted/{id}',[PayrollRegisterController::class,'downloadExcelUnposted']);
        Route::post('post',[PayrollRegisterController::class,'postPayroll']);
     

        
        
    }); 
    //PayslipController
    Route::prefix('payslip')->group(function(){
        Route::get('/',[PayslipController::class,'index']);

        Route::get('posted-period',[PayslipController::class,'getPostedPeriods']);
        Route::get('get-employees/{period}/{div}/{dept}',[PayslipController::class,'getEmployees']);
        Route::get('web-view/{period}/{div}/{dept}/{bio_id}',[PayslipController::class,'webView']);
        Route::get('print/{period}/{div}/{dept}/{bio_id}',[PayslipController::class,'print']);
    });

    Route::prefix('payslip-weekly')->group(function(){
        Route::get('/',[PayslipWeeklyController::class,'index']);

        Route::get('posted-period',[PayslipWeeklyController::class,'getPostedPeriods']);
        Route::get('get-employees/{period}/{div}/{dept}',[PayslipWeeklyController::class,'getEmployees']);
        Route::get('web-view/{loc}/{period}/{div}/{dept}/{bio_id}',[PayslipWeeklyController::class,'webView']);
        Route::get('print/{period}/{div}/{dept}/{bio_id}',[PayslipWeeklyController::class,'print']);
        Route::get('dtr-summary/{loc}/{period}/{div}/{dept}/{bio_id}',[PayslipWeeklyController::class,'dtrSummary']);
        Route::get('pdf-view/{loc}/{period}/{div}/{dept}/{bio_id}',[PayslipWeeklyController::class,'pdfView']);
        Route::get('get-locations',[LocationController::class,'listOption']);
    });

    Route::prefix('bank-transmittal')->group(function(){
        Route::get('/',[BankTransmittalController::class,'index']);
        Route::get('get-periods',[BankTransmittalController::class,'postedPeriods']);
        Route::get('download/{period_id}',[BankTransmittalController::class,'generateExcel']);
    });

    Route::prefix('payroll-register-weekly')->middleware('access:payroll-transaction/payroll-register-weekly')->group(function(){
        Route::get('/',[PayrollRegisterWeeklyController::class,'index']);
        Route::get('unposted-payroll',[PayrollRegisterWeeklyController::class,'getUnpostedPeriod']);
        Route::get('compute/{id}',[PayrollRegisterWeeklyController::class,'compute']);
        Route::get('pdf-unposted/{id}',[PayrollRegisterWeeklyController::class,'downloadPdfUnposted']);
        Route::get('download-unposted/{id}',[PayrollRegisterWeeklyController::class,'downloadExcelUnposted']);
        Route::post('post',[PayrollRegisterWeeklyController::class,'postPayroll']);
        Route::get('posted-payroll',[PayrollRegisterWeeklyController::class,'getPostedPeriod']);

        Route::post('unpost',[PayrollRegisterWeeklyController::class,'unpost']);

        Route::get('download-rcbc-template/{period_id}',[PayrollRegisterWeeklyController::class,'downloadRCBCTemplate']);
        Route::get('download-posted/{id}',[PayrollRegisterWeeklyController::class,'downloadExcelPosted']);

        Route::get('show-ot-breakdown/{id}',[PayrollRegisterWeeklyController::class,'showOTBreakdown']);


        
    });

    Route::prefix('payroll-register-confi')->middleware('access:payroll-transaction/payroll-register-confi')->group(function(){
        Route::get('/',[PayrollRegisterConfiController::class,'index']);
        Route::get('unposted-payroll',[PayrollRegisterConfiController::class,'getUnpostedPeriod']);
        Route::get('compute/{id}',[PayrollRegisterConfiController::class,'compute']);
        Route::get('download-unposted/{id}',[PayrollRegisterConfiController::class,'downloadExcelUnposted']);
        Route::post('post',[PayrollRegisterConfiController::class,'postPayroll']);
        // Route::get('/',[PayrollRegisterController::class,'index']);
        // Route::get('unposted-payroll',[PayrollRegisterController::class,'getUnpostedPeriod']);
        // Route::get('compute/{id}',[PayrollRegisterController::class,'compute']);
        // Route::get('download-unposted/{id}',[PayrollRegisterController::class,'downloadExcelUnposted']);
        // Route::post('post',[PayrollRegisterController::class,'postPayroll']);
        
    }); 

    //thirteenth-month/weekly

    Route::prefix('thirteenth-month-weekly')->middleware('access:payroll-transaction/thirteenth-month-weekly')->group(function(){
      
        // Route::get('unposted-payroll',[ThirteenthMonthController::class,'getUnpostedPeriod']);
        Route::get('/',[ThirteenthMonthController::class,'index']);
        Route::get('show-table/{year}',[ThirteenthMonthController::class,'showTable']);
        Route::get('print-payslip/{year}',[ThirteenthMonthController::class,'print']);
        Route::post('post',[ThirteenthMonthController::class,'post']);
        Route::post('insert-or-update',[ThirteenthMonthController::class,'insertOrUpdate']);
        Route::get('download-excel/{year}',[ThirteenthMonthController::class,'download']);

        Route::get('post',[ThirteenthMonthController::class,'post']);
        Route::get('download-banktransmittal/{year}',[ThirteenthMonthController::class,'bank_transmittal']);
        Route::get('print/{year}/{location}',[ThirteenthMonthController::class,'print']);

        Route::get('download-conso/{year}',[ThirteenthMonthController::class,'conso']);
        Route::get('download-banktransmittal-conso/{year}',[ThirteenthMonthController::class,'conso_bank_transmittal']);
        
    });
});


Route::middleware('auth')->prefix('memo')->group(function(){
   
    Route::prefix('tardiness-to-employee')->middleware('access:memo/tardiness-to-employee')->group(function(){
        Route::get('/',[TardinessMemoController::class,'index']);
        Route::get('list',[TardinessMemoController::class,'list']);
        Route::get('read/{id}',[TardinessMemoController::class,'readMemo']);
        Route::get('employee-list',[TardinessMemoController::class,'getNames']);

        Route::get('print/{id}',[TardinessMemoController::class,'print']);
        Route::get('year',[TardinessMemoController::class,'getYear']);
        Route::post('save',[TardinessMemoController::class,'save']);
    }); 

    Route::prefix('awol')->middleware('access:memo/awol')->controller(AWOLMemoController::class)->group(function(){
        Route::get('/','index');
      
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

    Route::prefix('other-income-app-weekly')->group(function(){
        Route::get('/',[OtherIncomeWeeklyAppController::class,'index']);
        Route::get('list',[OtherIncomeWeeklyAppController::class,'list']);
        Route::get('emp-list/{period_id}',[OtherIncomeWeeklyAppController::class,'employeeList']);
        Route::post('update',[OtherIncomeWeeklyAppController::class,'update']);
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

Route::get('change-password/{username}',[PasswordResetController::class,'passwordResetForm']);

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

use App\Libraries\LeaveCreditsMaker;

Route::get('leave-credits-maker',function(){
    $maker = new LeaveCreditsMaker();

    // $period = CarbonPeriod::create('2023-01-01','2023-09-06');

    // // Iterate over the period
    // foreach ($period as $date) {
    //     echo $date->format('Y-m-d')."<br>"; 
    //     $maker($date);
    // }

    $maker(now());

  
    // $leavecreditsmaker();
});

// Route::get('process-sheet4',function(){
//     $employees = DB::table('sheet4')
//         ->select(DB::raw('biometric_id,sum(ndays) as ndays,sum(actual_late) as actual_late'))
//         ->groupBy('biometric_id')
//         ->having('ndays','>',0)
//         ->get();

//     foreach($employees as $e){
//         DB::table('edtr_totals')
//         ->where('biometric_id','=',$e->biometric_id)
//         ->where('period_id','=',21)
//         ->update([
//             'ndays' => $e->ndays,
//             'late_eq' => $e->actual_late
//         ]);
//     }
// });

require __DIR__.'/auth.php';
