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
use Illuminate\Support\Facades\Http;

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
        $period_id = $request->id;
        $period = $this->unposted->getPeriod($request->id);
        $url = "http://172.17.42.108/payroll-processor/process/non-confi/$period_id/$user->id";

        $response = Http::get($url);

       $collections = $this->unposted->getPprocessed($period,'non-confi');
        $headers =  $this->unposted->getHeaders($period)->toArray();
        $colHeaders = $this->unposted->getColHeaders();

        $deductions = $this->unposted->getDeductionLabel($period);
        $gov = $this->unposted->getGovLoanLabel($period);
        $compensation = $this->unposted->getUsedCompensation($period);
        $noPay = $this->unposted->semiEmployeeNoPayroll($period->id);
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

        return view('app.payroll-transaction.payroll-register.payroll-register',[
            'data' => $collections,
            'no_pay' => $noPay,
            'headers' => $headers , 
            'labels' => $label,
            'deductionLabel' => $deductions,
            'govLoan' => $gov,
            'colHeaders' => $colHeaders,
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

            $this->excel->setValues($collections,$noPay,$headers,$deductions,$gov,$compensation,$label, $payperiod_label,$colHeaders);
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
