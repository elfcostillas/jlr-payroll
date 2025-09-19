<?php

namespace App\Http\Controllers\PayrollTransaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\EmployeeFileMapper\EmployeeWeeklyMapper;
use Illuminate\Support\Facades\Auth;
use App\Mappers\TimeKeepingMapper\PayrollPeriodWeeklyMapper;
use App\Mappers\PayrollTransaction\UnpostedPayrollRegisterWeeklyMapper;
use App\Mappers\PayrollTransaction\PostedPayrollRegisterWeeklyMapper;
use App\Excel\UnpostedPayrollRegisterWeekly;
use Maatwebsite\Excel\Facades\Excel;
use App\Mappers\EmployeeFileMapper\Repository\WeeklyEmployee;
use App\Mappers\EmployeeFileMapper\Repository\SemiMonthly;
use App\Mappers\EmployeeFileMapper\Repository\Daily;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mappers\PayrollTransaction\PayslipMapper;
use App\Mappers\PayrollTransaction\UnpostedPayrollRegisterMapper;

use  App\Excel\BankTransmittal;
use App\Excel\PayrollRegisterWeekly;

use App\Mappers\TimeKeepingMapper\DailyTimeRecordMapper;
use App\Models\Timekeeping\PayrollPeriod;
use App\Models\Timekeeping\PayrollPeriodWeekly;
use App\Services\PayrollRegisterService;
use Illuminate\Support\Facades\DB;
use App\Contracts\PayrollPeriodContract;

class PayrollRegisterWeeklyController extends Controller
{
    //

    private $employee;
    private $period;
    private $mapper;
    private $excel;
    private $payslip;
    private $posted;
    private $rcbc;
    private $dtr;
    private $unposted;
    

    public function __construct(UnpostedPayrollRegisterMapper $unposted,DailyTimeRecordMapper $dtr,PostedPayrollRegisterWeeklyMapper $posted,PayslipMapper $payslip,EmployeeWeeklyMapper $employee,PayrollPeriodWeeklyMapper $period,UnpostedPayrollRegisterWeeklyMapper $mapper,PayrollRegisterWeekly $excel,BankTransmittal $rcbc)
    {
        $this->employee = $employee;
        $this->period = $period;
        $this->mapper = $mapper;
        $this->excel = $excel;
        $this->payslip = $payslip;
        $this->posted = $posted;
        $this->rcbc = $rcbc;
        $this->dtr = $dtr;
        $this->unposted = $unposted;
        
    }

    public function index()
    {
        return view('app.payroll-transaction.payroll-register-weekly.index');
    }

    public function getUnpostedPeriod()
    {
        $user = Auth::user();
        $result = $this->period->listforDropDown();
        // if($user->biometric_id!="" && $user->biometric_id!=0 && $user->biometric_id != null){
        //     $position = $this->employee->getPosition($user->biometric_id);

        //     $result = $this->unposted->unpostedPeriodList('semi',$position);

        //     return response()->json($result);
        // }
        return response()->json($result);
    }

    
    public function computeV2(Request $period)
    {
    
        $service = new PayrollRegisterService;
        $service->set_employee_type('support')
                ->setPayrollPeriod($period->period)
                ->process();
        
    }

    public function compute(Request $request)
    {
        $period = $request->id;
        $payreg = [];
        $user = Auth::user();

        $pperiod = DB::table('payroll_period_weekly')->select()->where('id',$period)->first();

        $employees = $this->mapper->getEmployeeWithDTRW($period,'non-confi');
       
        $gloans = $this->mapper->runGovtLoansSG($pperiod,$employees->pluck('biometric_id'),$user->id,'sg');
        $installments = $this->mapper->runInstallments($pperiod,$employees->pluck('biometric_id'),$user->id,'sg');
        
        $phil_rate = $this->mapper->getPhilRate();

        $sil_flag = false;
        

        //$gloans = $this->unposted->runGovLoansSG($pperiod,$employees->pluck('biometric_id'),$user->id,'sg');

        
        foreach($employees as $employee){

            $employee->vl_wpay = 0;
            $employee->vl_wpay_amount = 0;

            $leaves = $this->mapper->getFiledLeaves($employee->biometric_id,$pperiod->id);

            if($leaves->count()>0){
                 foreach($leaves as $leave){
                    switch($leave->leave_type){
                        case 'SIL' :
                                $employee->vl_wpay += $leave->with_pay;                           
                            break;
                    }
                }
                $sil_flag = true;
            }

            // $holidays = $this->mapper->getHolidayCounts($employee->biometric_id,$employee->period_id);

            // $employee->actual_reghol = 0;
            // $employee->actual_sphol = 0;
            // $employee->actual_dblhol = 0;

            // foreach($holidays as $holiday){
            //     switch($holiday->holiday_type){
            //         case 1 : case '1' :
            //             $employee->actual_reghol += 1;
            //             break;
            //         case 2 : case '2' :
            //             $employee->actual_sphol += 1;
            //             break;
            //         case 3 : case '3' :
            //             $employee->actual_dblhol += 1;
            //             break;
            //     }
            // }
            
            $person = new WeeklyEmployee($employee,new Daily);

            $p_deductions = $this->mapper->getDeductionsInstallments($employee->biometric_id,$employee->period_id);
            $p_gloans = $this->mapper->getGovLoans($employee->biometric_id,$employee->period_id); 

            
            $person->compute($period);
            $deductions = $this->mapper->getDeductions($employee->biometric_id,$employee->period_id);
            $person->setPhilRate($phil_rate->rate);
            // $person->computeGovtLoan($pperiod);
            $person->computeGovContri($pperiod);
            $person->computeGrossTotal($deductions);
            $person->computeTotalDeductions($p_deductions,$p_gloans);
            $person->computeNetPay();
          

            array_push($payreg,$person);
        }

        $flag = $this->mapper->reInsert($period,$payreg,'weekly');

        $headers = $this->mapper->getHeaders($period)->toArray();
        $deductions =  $this->mapper->getDeductionLabel($pperiod);
        $govtLoans =  $this->mapper->getGovLoanLabel($pperiod);

        // dd($deductions);
   
        //dd($headers->toArray());
        $colHeaders = $this->mapper->getColHeaders();

        $label = [];

        foreach($headers as $key => $value){
            if($value==0){
                unset($headers[$key]);
            }
        }

        foreach($colHeaders  as  $value ){
            //dd($value->var_name,$vaue->col_label);
            $label[$value->var_name] = $value->col_label;
        }

        $nopay = $this->mapper->weeklyEmployeeNoPayroll($period);
        
        $collections = $this->mapper->getEmployeesV1($period);

        return view('app.payroll-transaction.payroll-register-weekly.payroll-register',[
            'data' => $collections,
            'nopay' => $nopay,
            'deductions_label' => $deductions,
            'govloans_label' => $govtLoans,
            'headers' => $headers , 
            'labels' => $label,
            'sil_flag' => $sil_flag
        ]);
    }

    public function downloadExcelUnposted(Request $request)
    {
        
        
        $sil_flag = false;
        $period = $request->id;
        $headers = $this->mapper->getHeaders($period)->toArray();
        $colHeaders = $this->mapper->getColHeaders();

        $periodObject = $this->period->find($period);
        //$label = '';
        // $data = $this->mapper->showComputed($period);

        // $this->excel->setValues($data,$label);
        // return Excel::download($this->excel,'PayrollRegisterWeekly'.$period.'.xlsx');

        foreach($headers as $key => $value){
            if($value==0){
                unset($headers[$key]);
            }
        }

        $deductions =  $this->mapper->getDeductionLabel($periodObject);
        $govtLoans =  $this->mapper->getGovLoanLabel($periodObject);

        $sil_flag = $this->mapper->sil_total($period,'unposted');


        $period_label = $this->period->makeRange($period);
        foreach($colHeaders  as  $value ){
            //dd($value->var_name,$vaue->col_label);
            $label[$value->var_name] = $value->col_label;
        }

        $collections = $this->mapper->getEmployees($period);

        // return view('app.payroll-transaction.payroll-register-weekly.payroll-register',[
        //     'data' => $collections,
           
        //     'headers' => $headers , 
        //     'labels' => $label,
        //     ]);

        $this->excel->setValues($collections,$label,$headers,$period_label,$sil_flag,$deductions,$govtLoans);
        return Excel::download($this->excel,'PayrollRegisterWeekly'.$period.'.xlsx');

        
    }

    public function downloadPdfUnposted(Request $request)
    {

        $period = $request->id;
        $headers = $this->mapper->getHeaders($period)->toArray();
        $periodObject = $this->period->find($period);

        // dd($periodObject);

        $colHeaders = $this->mapper->getColHeaders();

        foreach($headers as $key => $value){
            if($value==0){
                unset($headers[$key]);
            }
        }

        $sil_flag = $this->mapper->sil_total($period,'unposted');

        $period_label = $this->period->makeRange($period);

        foreach($colHeaders  as  $value ){
            //dd($value->var_name,$vaue->col_label);
            $label[$value->var_name] = $value->col_label;
        }

        $deductions =  $this->mapper->getDeductionLabel($periodObject);
        $govtLoans =  $this->mapper->getGovLoanLabel($periodObject);

        $collections = $this->mapper->getEmployees($period);

        $pdf = PDF::loadView('app.payroll-transaction.payroll-register-weekly.print',[
                'data' => $collections,
                'headers' => $headers,
                'label' => $label,
                'period' => $periodObject,
                'period_label' => $period_label->drange,
                'perf' => $period_label->perf,
                'sil_flag' => $sil_flag,
                'deductions_label' => $deductions,
                'govloans_label' => $govtLoans,
            ])->setPaper('Folio','landscape');
       
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
    
        $canvas = $dom_pdf ->get_canvas();
        $canvas->page_text(850, 590, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
       
        return $pdf->stream('JLR-PayReg-Print.pdf'); 


    }

    
    public function downloadPdfPosted(Request $request)
    {
        $period = $request->id;
        $headers = $this->mapper->getHeaders($period)->toArray();
        $periodObject = $this->period->find($period);
        
        $colHeaders = $this->mapper->getColHeaders();

        foreach($headers as $key => $value){
            if($value==0){
                unset($headers[$key]);
            }
        }

        $sil_flag = $this->mapper->sil_total($period,'unposted');

        $period_label = $this->period->makeRange($period);

        foreach($colHeaders  as  $value ){
            //dd($value->var_name,$vaue->col_label);
            $label[$value->var_name] = $value->col_label;
        }

        $deductions =  $this->mapper->getDeductionLabel($periodObject);
        $govtLoans =  $this->mapper->getGovLoanLabel($periodObject);

        $collections = $this->mapper->getEmployeesPosted($period);

        $pdf = PDF::loadView('app.payroll-transaction.payroll-register-weekly.print',[
                'data' => $collections,
                'headers' => $headers,
                'label' => $label,
                'period' => $periodObject,
                'period_label' => $period_label->drange,
                'perf' => $period_label->perf,
                'sil_flag' => $sil_flag,
                // 'data' => $collections,
                // 'headers' => $headers,
                // 'label' => $label,
                // 'period' => $periodObject,
                // 'period_label' => $period_label->drange,
                // 'perf' => $period_label->perf,
                // 'sil_flag' => $sil_flag,
                'deductions_label' => $deductions,
                'govloans_label' => $govtLoans,
            ])->setPaper('Folio','landscape');
       
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
    
        $canvas = $dom_pdf ->get_canvas();
        $canvas->page_text(850, 590, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
       
        return $pdf->stream('JLR-DTR-Print.pdf'); 
    }

    public function postPayroll(Request $request)
    {
    
        $result = $this->mapper->postPayroll($request->period_id);


        return response()->json($result);
    }

    public function unpost(Request $request)
    {
        $result = $this->posted->unpost($request->period_id);

        return response()->json($result);
    }

    public function getPostedPeriod()
    {
        $result = $this->payslip->getWeeklyPosytedPeriod();

        return response()->json($result);
    }

    public function downloadRCBCTemplate(Request $request)
    {
        $result = $this->posted->getPostedDataforRCBC($request->period_id);

        // dd($result);

        $this->rcbc->setValues($result);
        return Excel::download($this->rcbc,'BankTransmittal.xlsx');
    }


    public function downloadExcelPosted(Request $request)
    {
        $period = $request->id;
        $headers = $this->mapper->getHeaders($period)->toArray();

        $colHeaders = $this->mapper->getColHeaders();

        foreach($headers as $key => $value){
            if($value==0){
                unset($headers[$key]);
            }
        }

        $periodObject = $this->period->find($period);
        $deductions =  $this->mapper->getDeductionLabel($periodObject);
        $govtLoans =  $this->mapper->getGovLoanLabel($periodObject);


        $period_label = $this->period->makeRange($period);

        foreach($colHeaders  as  $value ){
            //dd($value->var_name,$vaue->col_label);
            $label[$value->var_name] = $value->col_label;
        }

        $collections = $this->mapper->getEmployeesPosted($period);
        $sil_flag = $this->mapper->sil_total($period,'posted');

        // $this->excel->setValues($collections,$label,$headers,$period_label,$sil_flag);
        $this->excel->setValues($collections,$label,$headers,$period_label,$sil_flag,$deductions,$govtLoans);
        
        return Excel::download($this->excel,'PayrollRegisterWeekly'.$period.'.xlsx');
    }

    public function showOTBreakdown(Request $request)
    {
        $result = $this->posted->oTBeakDown($request->id);

        return view('app.payroll-transaction.payroll-register-weekly.ot-breakdown',['data' => $result]);
    }

    
}
