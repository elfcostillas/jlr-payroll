<?php

namespace App\Http\Controllers\PayrollTransaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\EmployeeFileMapper\EmployeeWeeklyMapper;
use Illuminate\Support\Facades\Auth;
use App\Mappers\TimeKeepingMapper\PayrollPeriodWeeklyMapper;
use App\Mappers\PayrollTransaction\UnpostedPayrollRegisterWeeklyMapper;
use App\Excel\UnpostedPayrollRegisterWeekly;
use Maatwebsite\Excel\Facades\Excel;
use App\Mappers\EmployeeFileMapper\Repository\WeeklyEmployee;
use App\Mappers\EmployeeFileMapper\Repository\SemiMonthly;
use App\Mappers\EmployeeFileMapper\Repository\Daily;

class PayrollRegisterWeeklyController extends Controller
{
    //

    private $employee;
    private $period;
    private $mapper;

    public function __construct(EmployeeWeeklyMapper $employee,PayrollPeriodWeeklyMapper $period,UnpostedPayrollRegisterWeeklyMapper $mapper,UnpostedPayrollRegisterWeekly $excel)
    {
        $this->employee = $employee;
        $this->period = $period;
        $this->mapper = $mapper;
        $this->excel = $excel;
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

    public function compute(Request $request)
    {
        $period = $request->id;
        $payreg = [];

    //     $result = $this->mapper->compute($period);
       
    //     $data = $this->mapper->showComputed($period);

    //     return view('app.payroll-transaction.payroll-register-weekly.payroll-register',['data' => $data]);
   
        $employees = $this->mapper->getEmployeeWithDTRW($period,'non-confi');

        foreach($employees as $employee){
            $holidays = $this->mapper->getHolidayCounts($employee->biometric_id,$employee->period_id);

            $employee->actual_reghol = 0;
            $employee->actual_sphol = 0;
            $employee->actual_dblhol = 0;

            foreach($holidays as $holiday){
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
            
            $person = new WeeklyEmployee($employee,new Daily);
            $person->compute($period);
            $deductions = $this->mapper->getDeductions($employee->biometric_id,$employee->period_id);
            $person->computeGrossTotal($deductions);
            $person->computeNetPay();
          

            array_push($payreg,$person);
        }

        $flag = $this->mapper->reInsert($period,$payreg,'weekly');

        $headers = $this->mapper->getHeaders($period)->toArray();
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
        
        $collections = $this->mapper->getEmployees($period);

        return view('app.payroll-transaction.payroll-register-weekly.payroll-register',[
            'data' => $collections,
            'nopay' => $nopay,
            'headers' => $headers , 
            'labels' => $label,
            ]);
    }

    public function downloadExcelUnposted(Request $request)
    {
        $period = $request->id;
        $headers = $this->mapper->getHeaders($period)->toArray();
        $colHeaders = $this->mapper->getColHeaders();
        //$label = '';
        // $data = $this->mapper->showComputed($period);

        // $this->excel->setValues($data,$label);
        // return Excel::download($this->excel,'PayrollRegisterWeekly'.$period.'.xlsx');

        foreach($headers as $key => $value){
            if($value==0){
                unset($headers[$key]);
            }
        }

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

        $this->excel->setValues($collections,$label,$headers);
        return Excel::download($this->excel,'PayrollRegisterWeekly'.$period.'.xlsx');

        
    }

    public function postPayroll(Request $request)
    {
    
        $result = $this->mapper->postPayroll($request->period_id);


        return response()->json($result);
    }

    
}
