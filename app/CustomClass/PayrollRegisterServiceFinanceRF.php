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
                    }else{
                        dd($key,3);
                    }
                }else{
                    if(property_exists($key,'subtype'))
                    {
                        if($key->subtype == 'installments')
                        {
                            if(property_exists($employee,'deductions'))
                            {
                                // dd($employee->deductions,$key);
                                if(array_key_exists($key->id,$employee->deductions))
                                {
                                    $totals += $employee->deductions[$key->id];
                                }
                               
                            }
                        }

                        if($key->subtype == 'govloan')
                        {  
                            if(property_exists($employee,'gov_loans'))
                            {
                                if(array_key_exists($key->id,$employee->gov_loans))
                                {
                                    $totals += $employee->gov_loans[$key->id];
                                }
                               
                            }
                        }

                    }else{
                        dd($key,2);
                    }


                }
            }else{
              
                $totals += $employee->$key;
            }
        }

        return $totals;
    }

    public function getDeptTotalOverAll($data,$key)
    {
        $totals = 0;

        foreach($data as $division)
        {
            foreach($division->departments as $department)
            {
                $totals +=  $this->getDeptTotal($department->data,$key);
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
