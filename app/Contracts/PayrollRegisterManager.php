<?php

namespace App\Contracts;

use App\FactoryProducts\Employee;

// use App\Contracts\Abler\Wageable;


abstract class PayrollRegisterManager
{
    protected $employee_info;

    public function __construct(Employee $employee_info)
    {
        $this->employee_info = $employee_info;
    }

    public function compute_basic()
    {
        $this->employee_info->compute_basic();
    }


}
