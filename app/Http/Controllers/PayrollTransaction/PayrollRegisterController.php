<?php

namespace App\Http\Controllers\PayrollTransaction;

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
use Carbon\CarbonPeriod;

class PayrollRegisterController extends Controller
{
    //
    private $unposted;
    private $posted;
    private $employee;
    private $excel;
    private $rcbc;

    public function __construct(UnpostedPayrollRegister $excel,UnpostedPayrollRegisterMapper $unposted,PostedPayrollRegisterMapper $posted,EmployeeMapper $employee,BankTransmittal $rcbc)
    {
        $this->unposted = $unposted;
        $this->posted = $posted;
        $this->employee = $employee;
        $this->excel = $excel;
        $this->rcbc = $rcbc;
    }

    public function index()
    {
        return view('app.payroll-transaction.payroll-register.index');
    }

    public function getUnpostedPeriod()
    {
        $user = Auth::user();
        if($user->biometric_id!="" && $user->biometric_id!=0 && $user->biometric_id != null){
            $position = $this->employee->getPosition($user->biometric_id);

            $result = $this->unposted->unpostedPeriodList('semi',$position,'non-confi');

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

        $employees = $this->unposted->getEmployeeWithDTR($period->id,'non-confi');
        
        if($period->period_type==1){
            /* loans for 15 */
        }else{
            /* loans for 30 */ //,$user_id,$emp_level
        }

        $gloans = $this->unposted->runGovLoans($period,$employees->pluck('biometric_id'),$user->id,'non-confi');
        $installments = $this->unposted->runInstallments($period,$employees->pluck('biometric_id'),$user->id,'non-confi');
        $onetime = $this->unposted->runOneTimeDeduction($period,$employees->pluck('biometric_id'),$user->id,'non-confi');
        $fixed = $this->unposted->runFixedDeduction($period,$employees->pluck('biometric_id'),$user->id,'non-confi');

        $fixed = $this->unposted->runFixedCompensation($period,$employees->pluck('biometric_id'),$user->id,'non-confi');
        $other = $this->unposted->runOtherCompensation($period,$employees->pluck('biometric_id'),$user->id,'non-confi');

        foreach($employees as $employee)
        {
            
            if($employee->new_hire == 'Y' && $employee->fixed_rate == 'N' && $employee->pay_type == 1){
                $employee->absences = $this->count_absent($employee,$period);
            }
           
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
                if($employee->date_hired < $holiday->holiday_date){
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
                // dd($holiday->holiday_date < );
               
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

            // dd($employee);

            $awol = $this->unposted->getAwolCount($employee->biometric_id,$period);

            if($awol > 0){
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
       
        $flag = $this->unposted->reInsert($period->id,$payreg,'non-confi');

        if($flag){
            $noPay = $this->unposted->semiEmployeeNoPayroll($period->id);
        }else{
            return false;
        }

        $collections = $this->unposted->getPprocessed($period,'non-confi');
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
            'compensation' => $compensation]);
    }

    public function downloadExcelUnposted(Request $request)
    {
        //dd($request->id);
        $period = $this->unposted->getPeriod($request->id);
        
        if($period){
            $period_from = Carbon::createFromFormat('Y-m-d',$period->date_from);
            $period_to = Carbon::createFromFormat('Y-m-d',$period->date_to);

            $payperiod_label = 'Payroll Period '.$period_from->format('F d-').$period_to->format('d, Y');

            $noPay = $this->unposted->semiEmployeeNoPayroll($period->id);

            $collections = $this->unposted->getPprocessed($period,'non-confi');
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

            $this->excel->setValues($collections,$noPay,$headers,$deductions,$gov,$compensation,$label, $payperiod_label);
            return Excel::download($this->excel,'PayrollRegister'.$period->id.'.xlsx');
        }
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
                        $result = $this->unposted->postNonConfi($request->period_id);

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

    public function count_absent($employee,$period)
    {
      
        $absence = 0;
        $dayBefore = Carbon::createFromFormat('Y-m-d',$employee->date_hired)->subDay();
       
        $period = new CarbonPeriod($period->date_from,$dayBefore->format('Y-m-d'));

        foreach($period as $date)
        {

            if($date->format('D') != 'Sun'){
                $absence++;
            }
            /*
            if($employee->sched_sat){
                if($date->format('D') != 'Sun'){
                    $absence++;
                }
            }else{
                if(!in_array($date->format('D'),['Sun','Sat'])){
                    $absence++;
                }
            }*/
        }

        return $absence * 8.0;
    }

    public function getPostedPeriod()
    {
        $result = $this->posted->getPostedPeriod('non-confi');

        return response()->json($result);
    }

    public function downloadRCBCTemplate(Request $request)
    {
        $result = $this->posted->getPostedDataforRCBC($request->period_id,'non-confi');

        // dd($result);

        $this->rcbc->setValues($result);
        return Excel::download($this->rcbc,'BankTransmittal.xlsx');
    }

    public function unpost(Request $request)
    {
        
        $result = $this->posted->unpost($request->period_id,'non-confi');
        
        return response()->json($result);
    }


}
