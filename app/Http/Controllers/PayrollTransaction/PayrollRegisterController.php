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
            $gloans = $this->unposted->runGovLoans($period,$employees->pluck('biometric_id'));
        }else{
            /* loans for 30 */
        }

        foreach($employees as $employee){
            $person = ($employee->pay_type==1) ? new Employee($employee,new SemiMonthly) : new Employee($employee,new Daily);
            $person->setPhilRate($phil_rate->rate);
            $person->compute($period);
            
            array_push($payreg,$person);

        }
       
        $this->unposted->reInsert($period->id,$payreg);


        return view('app.payroll-transaction.payroll-register.payroll-register',['employees' => $employees]);
    }
}
