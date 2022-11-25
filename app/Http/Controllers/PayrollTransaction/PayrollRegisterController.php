<?php

namespace App\Http\Controllers\PayrollTransaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mappers\PayrollTransaction\UnpostedPayrollRegisterMapper;
use App\Mappers\PayrollTransaction\PostedPayrollRegisterMapper;
use App\Mappers\EmployeeFileMapper\Repository\Employee;
use App\Mappers\EmployeeFileMapper\Repository\SemiMonthly;
use App\Mappers\EmployeeFileMapper\Repository\Daily;

class PayrollRegisterController extends Controller
{
    //
    private $unposted;
    private $posted;

    public function __construct(UnpostedPayrollRegisterMapper $unposted,PostedPayrollRegisterMapper $posted)
    {
        $this->unposted = $unposted;
        $this->posted = $posted;
    }

    public function index()
    {
        return view('app.payroll-transaction.payroll-register.index');
    }

    public function getUnpostedPeriod()
    {
        $result = $this->unposted->unpostedPeriodList('semi');

        return response()->json($result);
    }

    public function compute(Request $request)
    {
        $period = $this->unposted->getPeriod($request->id);
        $phil_rate = $this->unposted->getPhilRate();
        //dd($phil_rate->rate);
        $payreg = [];

        $employees = $this->unposted->getEmployeeWithDTR($period->id,'semi');
       
        if($period->period_type==1){
            /* loans for 15 */
        }else{
            /* loans for 30 */
        }

        $gloans = $this->unposted->runGovLoans($period,$employees->pluck('biometric_id'));
        $installments = $this->unposted->runInstallments($period,$employees->pluck('biometric_id'));
        $onetime = $this->unposted->runOneTimeDeduction($period,$employees->pluck('biometric_id'));
        $fixed = $this->unposted->runFixedDeduction($period,$employees->pluck('biometric_id'));

        $fixed = $this->unposted->runFixedCompensation($period,$employees->pluck('biometric_id'));
        $other = $this->unposted->runOtherCompensation($period,$employees->pluck('biometric_id'));

        foreach($employees as $employee)
        {

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

            $leaves = $this->unposted->getFiledLeaves($employee->biometric_id,$period->id);

            if($leaves->count()>0){
                foreach($leaves as $leave){
                    switch($leave->leave_type){
                       
                        case 'VL' :
                            $employee->vl_wpay += $leave->with_pay;
                            $employee->vl_wopay += $leave->without_pay;
                           
                            break;
                        case 'SL' :
                            $employee->sl_wpay += $leave->with_pay;
                            $employee->sl_wopay += $leave->without_pay;
                            break;
                        case 'UT' : case 'EL' :
                            $employee->under_time  += $leave->without_pay;
                            break;
        
                        case 'BL' :
                            $employee->bl_wpay += $leave->with_pay;
                            $employee->bl_wopay += $leave->without_pay;
                            break;
                        
                        default : 
                            $employee->vl_wpay += $leave->with_pay;
                            $employee->vl_wopay += $leave->without_pay;
                        break;
                    }
                    
                }
            }

            $person = ($employee->pay_type==1) ? new Employee($employee,new SemiMonthly) : new Employee($employee,new Daily);
            $person->setPhilRate($phil_rate->rate);
            $person->compute($period);

            array_push($payreg,$person);

        }
       
        $flag = $this->unposted->reInsert($period->id,$payreg);

       
        if($flag){
            $noPay = $this->unposted->semiEmployeeNoPayroll($period->id);
        }else{
            return false;
        }

        $collections = $this->unposted->getPprocessed($period);
        $headers =  $this->unposted->getHeaders($period)->toArray();
        foreach($headers as $key => $value){
            if($value==0){
                unset($headers[$key]);
            }
            
        }
        
        return view('app.payroll-transaction.payroll-register.payroll-register',['data' => $collections,'no_pay' => $noPay, ]);
    }
}
