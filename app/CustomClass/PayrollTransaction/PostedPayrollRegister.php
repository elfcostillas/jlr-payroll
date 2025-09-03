<?php

namespace App\CustomClass\PayrollTransaction;

use App\Models\Timekeeping\PayrollPeriod;
use App\Traits\PayrollTraits;
use App\CustomClass\PayrollTransaction\PayrollRegister;

class PostedPayrollRegister extends PayrollRegister
{
    //
    use PayrollTraits;

    const status    = 'POSTED';
    const db        = 'payrollregister_posted_s';


    public function __construct(PayrollPeriod $period,$emp_level)
    {
        $this->period = $period;
        $this->emp_level = $emp_level;
    }

    public function fn()
    {
        $this->traits_sample();
        // return $this->period;
    }





}
