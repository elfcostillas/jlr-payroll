<?php

namespace App\Http\Controllers\PayrollTransaction;

use App\CustomClass\PayrollRegisterConfi;
use App\CustomClass\PayrollRegisterService;
use App\CustomClass\PayrollRegisterServiceFinance;
use App\CustomClass\PayrollTransaction\PostedPayrollRegister;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\PayrollTransaction\UnpostedPayrollRegisterMapper;
use App\Mappers\PayrollTransaction\PostedPayrollRegisterMapper;
use App\Mappers\EmployeeFileMapper\Repository\Employee;
use App\Mappers\EmployeeFileMapper\Repository\SemiMonthly;
use App\Mappers\EmployeeFileMapper\Repository\Daily;
use App\Mappers\EmployeeFileMapper\EmployeeMapper;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Excel\UnpostedPayrollRegister;
use Maatwebsite\Excel\Facades\Excel;
use App\Excel\BankTransmittal;
use App\Models\Timekeeping\PayrollPeriod;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Excel\PayRegFinanceTemplate;

class PayrollRegisterConfiController extends Controller
{
    //
    private $unposted;
    private $posted;
    private $employee;
    private $excel;
    private $rcbc;
    private $fin_excel;

    public function __construct(
        UnpostedPayrollRegister $excel,
        UnpostedPayrollRegisterMapper $unposted,
        PostedPayrollRegisterMapper $posted,
        EmployeeMapper $employee,
        BankTransmittal $rcbc,
        PayRegFinanceTemplate $fin_excel)
    {
        $this->unposted = $unposted;
        $this->posted = $posted;
        $this->employee = $employee;
        $this->excel = $excel;
        $this->rcbc = $rcbc;
        $this->fin_excel = $fin_excel;
    }

    public function index()
    {
        return view('app.payroll-transaction.payroll-register-confi.index');
    }

    public function getUnpostedPeriod()
    {
        $user = Auth::user();
        if($user->biometric_id!="" && $user->biometric_id!=0 && $user->biometric_id != null){
            $position = $this->employee->getPosition($user->biometric_id);

            $result = $this->unposted->unpostedPeriodList('semi',$position,'confi');

            return response()->json($result);
        }
       
    }

    public function compute(Request $request)
    {
        $user = Auth::user();

        $period = $this->unposted->getPeriod($request->id);
        $phil_rate = $this->unposted->getPhilRate();
        //dd($phil_rate->rate);
        $payreg = [];

        $employees = $this->unposted->getEmployeeWithDTR($period->id,'confi');

        if($period->period_type==1){
            /* loans for 15 */
        }else{
            /* loans for 30 */ //,$user_id,$emp_level
        }

        $gloans = $this->unposted->runGovLoans($period,$employees->pluck('biometric_id'),$user->id,'confi');
        $installments = $this->unposted->runInstallments($period,$employees->pluck('biometric_id'),$user->id,'confi');
        $onetime = $this->unposted->runOneTimeDeduction($period,$employees->pluck('biometric_id'),$user->id,'confi');
        $fixed = $this->unposted->runFixedDeduction($period,$employees->pluck('biometric_id'),$user->id,'confi');

        $fixed = $this->unposted->runFixedCompensation($period,$employees->pluck('biometric_id'),$user->id,'confi');
        $other = $this->unposted->runOtherCompensation($period,$employees->pluck('biometric_id'),$user->id,'confi');

        foreach($employees as $employee)
        {
           
            $holidays = $this->unposted->getHolidayCounts($employee->biometric_id,$employee->period_id);
            //dd($employee);
            $employee->under_time_amount = 0;
            $employee->vl_wpay = 0;
            $employee->vl_wpay_amount = 0;
            $employee->vl_wopay = 0;
            $employee->vl_wopay_amount = 0;
            $employee->sl_wopay = 0;
            $employee->sl_wopay_amount = 0;
            $employee->sl_wpay = 0;
            $employee->sl_wpay_amount = 0;
            $employee->bl_wpay = 0;
            $employee->bl_wpay_amount = 0;
            $employee->bl_wopay = 0;
            $employee->bl_wopay_amount = 0;

            $employee->svl = 0;
            $employee->svl_amount = 0;

            $employee->actual_reghol = 0;
            $employee->actual_sphol = 0;
            $employee->actual_dblhol = 0;

            foreach($holidays as $holiday){
                //dd();
                switch($holiday->holiday_type){
                    case 1 : case '1' :
                        $employee->actual_reghol += 1;
                        break;
                    case 2 : case '2' :
                        $employee->actual_sphol += 1;
                        break;
                    case 3 : case '3' :
                        $employee->actual_dblhol += 1;
                        break;
                }
            }

            $leaves = $this->unposted->getFiledLeaves($employee->biometric_id,$period->id);
            
            if($leaves->count()>0){
                foreach($leaves as $leave){
                    switch($leave->leave_type){
                       
                        case 'VL' :
                            $employee->vl_wpay += $leave->with_pay;
                            //$employee->vl_wopay += $leave->without_pay;
                            $employee->absences += $leave->without_pay;
                           
                            break;
                        case 'SL' :
                            $employee->sl_wpay += $leave->with_pay;
                            //$employee->sl_wopay += $leave->without_pay;
                            $employee->absences += $leave->without_pay;
                            break;
                        case 'UT' : case 'EL' :
                            $employee->under_time  += $leave->without_pay;
                            break;
        
                        case 'BL' :
                            //dd($employee->date_hired);
                            if($employee->date_hired==null || $employee->date_hired == ''){
                                $employee->absences += $leave->without_pay;
                            }else {
                                $withPayDate = Carbon::createFromFormat('Y-m-d',$employee->date_hired)->addyear();
                                if($withPayDate<now()){
                                    $employee->bl_wpay += $leave->without_pay + $leave->with_pay;
                                }else {
                                    $employee->absences += $leave->without_pay + $leave->with_pay;
                                }

                            }
                            //$employee->bl_wpay += $leave->with_pay;
                            //$employee->bl_wopay += $leave->without_pay;
                            break;

                        case 'SVL' :
                                $employee->svl += $leave->with_pay;
                            break;
                        
                        default : 
                            $employee->vl_wpay += $leave->with_pay;
                            //$employee->vl_wopay += $leave->without_pay;
                            $employee->absences += $leave->without_pay;
                        break;
                    }
                    
                }
            }

            /* AWOL*/
            $awol = $this->unposted->getAwolCount($employee->biometric_id,$period);
            if($awol > 0)
            {
                $employee->absences += $awol;
            }


            $person = ($employee->pay_type==1) ? new Employee($employee,new SemiMonthly) : new Employee($employee,new Daily);
            
            $person->setPhilRate($phil_rate->rate);
            $person->compute($period);
            
            $oe = $this->unposted->otherEarnings($employee->biometric_id,$employee->period_id);

            $person->computeGrossTotal($oe);

            $compd = $this->unposted->getDeductions($employee->biometric_id,$employee->period_id);
            $govloan = $this->unposted->getGovLoans($employee->biometric_id,$employee->period_id);
            $person->computeTotalDeduction($compd,$govloan);

            $person->computeNetPay();

            array_push($payreg,$person);

        }
       
        $flag = $this->unposted->reInsert($period->id,$payreg,'confi');

        if($flag){
            $noPay = $this->unposted->semiEmployeeNoPayroll($period->id);
        }else{
            return false;
        }

        $collections = $this->unposted->getPprocessed($period,'confi');
        $headers =  $this->unposted->getHeaders($period)->toArray();
        $colHeaders = $this->unposted->getColHeaders();

        $deductions = $this->unposted->getDeductionLabel($period);
        $gov = $this->unposted->getGovLoanLabel($period);
        $compensation = $this->unposted->getUsedCompensation($period);
        //dd($compensation);
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

        //dd($colHeaders);
        //dd($headers);
        
        return view('app.payroll-transaction.payroll-register.payroll-register',[
            'data' => $collections,
            'no_pay' => $noPay,
            'headers' => $headers , 
            'labels' => $label,
            'deductionLabel' => $deductions,
            'govLoan' => $gov,
            'compensation' => $compensation,
            'colHeaders' => $colHeaders
        ]);
    }
        
    //     return view('app.payroll-transaction.payroll-register-confi.payroll-register',['data' => $collections,'no_pay' => $noPay,'headers' => $headers , 'labels' => $label,'deductionLabel' => $deductions,'govLoan' => $gov,'compensation' => $compensation]);
    // }

    public function downloadExcelUnposted(Request $request)
    {
        $payroll = new PayrollRegisterService(new PayrollRegisterConfi('payrollregister_unposted_s','unposted'));
        $period = $payroll->getPeriod($request->id);

        $data = $payroll->getPayrollData($period);

        $payroll->getHeaders();

        if($period){
            $date_from = Carbon::createFromFormat('Y-m-d',$period->date_from);
            $date_to = Carbon::createFromFormat('Y-m-d',$period->date_to);

            $label = $date_from->format('m/d/Y').' - '.$date_to->format('m/d/Y');
        

            $this->excel->setValues($data,$label);
            return Excel::download($this->excel,'PayrollRegister'.$period->id.'.xlsx');
        }

        /*
        $period = $this->unposted->getPeriod($request->id);
        
        if($period){
            $period_from = Carbon::createFromFormat('Y-m-d',$period->date_from);
            $period_to = Carbon::createFromFormat('Y-m-d',$period->date_to);

            $payperiod_label = 'Payroll Period '.$period_from->format('F d-').$period_to->format('d, Y');

            $noPay = $this->unposted->semiEmployeeNoPayroll($period->id);

            $collections = $this->unposted->getPprocessed($period,'confi');

            $headers =  $this->unposted->getHeaders($period)->toArray();
            $colHeaders = $this->unposted->getColHeaders();

            $deductions = $this->unposted->getDeductionLabel($period);
            $gov = $this->unposted->getGovLoanLabel($period);
            $compensation = $this->unposted->getUsedCompensation($period);
           
            $label = [];

            foreach($headers as $key => $value){
                if($value==0){
                    unset($headers[$key]);
                }
            }

            foreach($colHeaders  as  $value ){
               
                $label[$value->var_name] = $value->col_label;
            }

            $this->excel->setValues($collections,$noPay,$headers,$deductions,$gov,$compensation,$label, $payperiod_label,$colHeaders);
            return Excel::download($this->excel,'PayrollRegister'.$period->id.'.xlsx');
        }
        */
    }

    public function postPayroll(Request $request)
    {
        //dd($request->period_id);
        $user = Auth::user();
        //dd($user->biometric_id);

        if($user->biometric_id!="" && $user->biometric_id!=0 && $user->biometric_id != null){
            $position = $this->employee->getPosition($user->biometric_id);
            //dd($position->job_title_id);
            switch($position->job_title_id){
                case 11 : case 10 : case 105 : case 15 :
                        $result = $this->unposted->postConfi($request->period_id);

                    break;
                
                case 6: case 60 :

                    break;
                
                default : 
                        return response()->json(['error' => 'Unauthorized Access.']);
                    break;
            }   
        }

        return response()->json($result);
        
        //$position = $this->employee->getPosition();
    }

    public function getPostedPeriod(Request $request)
    {
       
        $result = $this->posted->getPostedPeriod('confi');

        return response()->json($result);
    }

    public function downloadRCBCTemplate(Request $request)
    {
        $result = $this->posted->getPostedDataforRCBC($request->period_id,'confi');

        // dd($result);

        $this->rcbc->setValues($result);
        return Excel::download($this->rcbc,'BankTransmittal.xlsx');
    }

    public function unpost(Request $request)
    {
        
        $result = $this->posted->unpost($request->period_id,'confi');

        return response()->json($result);
    }

    public function viewPDF(Request $request)
    {
        $label = '';

        
        // $this->getSchema();
        

        $payroll = new PayrollRegisterService(new PayrollRegisterConfi('payrollregister_unposted_s','unposted'));

        $period = $payroll->getPeriod($request->period_id);
        
        $data = $payroll->getPayrollData($period);

        $payroll->getHeaders();


        if($period){
            $date_from = Carbon::createFromFormat('Y-m-d',$period->date_from);
            $date_to = Carbon::createFromFormat('Y-m-d',$period->date_to);

            $label = $date_from->format('m/d/Y').' - '.$date_to->format('m/d/Y');
        }

        $pdf = PDF::loadView('app.payroll-transaction.payroll-register-confi.print',[
            'data' => $data,
            // 'headers' => $headers,
            'label' => $label,
            // 'period' => $periodObject,
            // 'period_label' => $period_label->drange,
            // 'perf' => $period_label->perf,
        ])->setPaper('Folio','landscape');
       
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
    
        $canvas = $dom_pdf ->get_canvas();
        $canvas->page_text(850, 590, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
       
        return $pdf->stream('JLR Semi Monthly Payreg.pdf'); 
    }

    public function getSchema()
    {
        $cols = Schema::getColumnListing('edtr_totals');

        foreach($cols as $key => $value)
        {
            if($key>=4){
                // dd($value);
            }
        }
    }

    public function buildSchema()
    {
        $cols = Schema::getColumnListing('payrollregister_unposted_s'); //payrollregister_unposted_s // edtr_totals

        foreach($cols as $col)
        {
            // dd($cols);
            DB::table('payroll_column_header')->insert(['var_name' => $col]);
            // DB::table('payroll_column_header')->insert(['var_name' => $col.'_amount']);
        }
    }

    public function downloadExcelPosted(Request $request)
    {
        $payroll = new PayrollRegisterService(new PayrollRegisterConfi('payrollregister_posted_s','posted'));
        $period = $payroll->getPeriod($request->id);

        $data = $payroll->getPayrollData($period);

        $payroll->getHeaders();

        if($period){
            $date_from = Carbon::createFromFormat('Y-m-d',$period->date_from);
            $date_to = Carbon::createFromFormat('Y-m-d',$period->date_to);

            $label = $date_from->format('m/d/Y').' - '.$date_to->format('m/d/Y');
        

            $this->excel->setValues($data,$label);
            return Excel::download($this->excel,'PayrollRegister'.$period->id.'.xlsx');
        }
    
        
    }

    public function downloadFinanceTemplate(Request $request)
    {

        // dd(bccomp(1.001,1.002,2));
        $payroll = new PayrollRegisterServiceFinance(new PayrollRegisterConfi('payrollregister_posted_s','posted'));

        $period = $payroll->getPeriod($request->id);


        $data = $payroll->getPayrollData($period);

        $payroll->getHeaders();

        if($period){
            $date_from = Carbon::createFromFormat('Y-m-d',$period->date_from);
            $date_to = Carbon::createFromFormat('Y-m-d',$period->date_to);

            $label = $date_from->format('m/d/Y').' - '.$date_to->format('m/d/Y');
            $label2 = $date_from->format('m/d').'-'.$date_to->format('d/Y');


            $this->fin_excel->setValues($data,$label,$label2,$payroll);
            return Excel::download($this->fin_excel,'PayrollRegisterFinanceTemplate'.$period->id.'.xlsx');

            // return view('app.payroll-transaction.payroll-register-confi.payroll-register-finance',['data' => $data,'label' => $label,'label2' => $label2,'payroll' => $payroll]);
        }

      
       

    }
}
