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
        $period_id = $request->id;

        $payreg = [];

        $employees = $this->unposted->getEmployeeWithDTR($period_id,'semi');

        foreach($employees as $employee){
            $person = ($employee->pay_type==1) ? new Employee($employee,new SemiMonthly) : new Employee($employee,new Daily);
            $person->compute();
            
            array_push($payreg,$person);

        }

        //$this->unposted->reInsert($period_id,$payreg);


        return view('app.payroll-transaction.payroll-register.payroll-register',['employees' => $employees]);
    }
}
