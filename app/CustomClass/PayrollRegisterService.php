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
        // dd(get_class($this->payroll));

        if(get_class($this->payroll) == 'App\CustomClass\PayrollRegisterConfi'){ 
            $this->payroll->processV2($period);
        }else{
            $this->payroll->process($period);
        }
      
        return $this->payroll;
    }

    
}
