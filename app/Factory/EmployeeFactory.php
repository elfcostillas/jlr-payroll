<?php

namespace App\Factory;

use App\FactoryProducts\DailyEmployee;
use App\FactoryProducts\SemiMonthlyEmployee;

class EmployeeFactory
{
    //
    public function __construct()
    {
        
    }

    public function makeEmployee($employee_info)
    {
        // dd($employee_info->pay_type);
        if($employee_info->pay_type == 3){
            // return new DailyEmployee($employee_info);
            $employee = app(DailyEmployee::class);
            $employee->setInfo($employee_info);
        }else{
            // return new SemiMonthlyEmployee($employee_info);
            // $employee =  new SemiMonthlyEmployee;
            $employee = app(SemiMonthlyEmployee::class);
            $employee->setInfo($employee_info);
        }

        return $employee;
    }


}
