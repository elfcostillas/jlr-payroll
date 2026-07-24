<?php

namespace App\CustomClass;

use Override;

class PayrollRegisterServiceFinanceRF extends PayrollRegisterService
{
    public function getPayrollData($period)
    {
        
        $this->payroll->processed($period);

        // // dd(get_class($this->payroll));

        // if(get_class($this->payroll) == 'App\CustomClass\PayrollRegisterConfi'){ 
        //     $this->payroll->processV2($period);
        // }else{
        //     $this->payroll->process($period);
        // }
      
        return $this->payroll;
    }

    public function callthisfunction()
    {
        dd('called');
    }

    public function getDeptTotal($data,$key)
    {
       
        $totals = 0;

        foreach($data as $employee)
        {
            if(is_object($key))
            {
                if(property_exists($key,'col_type'))
                {
                    if($key->col_type == 'contri')
                    {
                        $totals += $employee->{$key->var_name};
                    }
                }else{
                    if(property_exists($key,'subtype'))
                    {
                        if($key->subtype == 'installments')
                        {
                            dd('im here');
                        }
                    }
                }
            }else{
              
                $totals += $employee->$key;
            }
        }

        return $totals;
    }

    public function getOverAllTotal($data,$key)
    {
        dd('called');
    }

    /*
        otherEarnings
        getDeductions
        getGovLoans
    */


}
