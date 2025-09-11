<?php

namespace App\Services;

use App\Contracts\SupportGroupPayrollManager;
use App\Factory\EmployeeFactory;
use App\Mappers\EmployeeFileMapper\EmployeeWeeklyMapper;
use App\Mappers\EmployeeFileMapper\Repository\Employee;
use App\Mappers\TimeKeepingMapper\PayrollPeriodMapper;
use App\Models\Timekeeping\PayrollPeriod;
use App\Models\Timekeeping\PayrollPeriodWeekly;
use Psy\CodeCleaner\FunctionContextPass;

class PayrollRegisterService
{
    //
    protected $employee_type;
    protected $period;

    public function __construct()
    {
        
    }

    public function set_employee_type($employee_type)
    {
        $this->employee_type = $employee_type;
        return $this;
    }

    public function setPayrollPeriod($period)
    {
        if($this->employee_type == 'support'){
            $period = app(PayrollPeriodWeekly::class)->find($period);
        }else{
            $period = app(PayrollPeriod::class)->find($period);
        }

        $this->period = $period;

        return $this;
    }

    public function process()
    {
        $repo = app(EmployeeWeeklyMapper::class);
        $factory = new EmployeeFactory();

        $employeesToPay = $repo->getActiveEmployees();

        foreach($employeesToPay as $e)
        {
            $employee = $factory->makeEmployee($e);

            // dd($employee);

            $manager = new SupportGroupPayrollManager($employee);

            $manager->compute_basic();
            
        }

        
    }
}
