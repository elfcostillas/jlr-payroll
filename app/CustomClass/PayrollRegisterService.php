<?php

namespace App\CustomClass;
use App\CustomClass\PayrollRegister;

class PayrollRegisterService
{
    public $payroll;

    public function __construct(PayrollRegister $payroll)
    {
        $this->payroll = $payroll;
    }

    public function getHeaders()
    {
        return $this->payroll->getHeaders();
    }

    public function getPeriod($id)
    {
        return $this->payroll->getPeriod($id);
    }

    public function getLocations()
    {
        return $this->payroll->getLocations();
       
    }

    public function getPayrollData($period)
    {

        $this->payroll->processV2($period);
        return $this->payroll;
    }

    
}
