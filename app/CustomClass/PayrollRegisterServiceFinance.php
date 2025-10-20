<?php

namespace App\CustomClass;

class PayrollRegisterServiceFinance extends PayrollRegisterService
{
    //
    // public $basic_cols;
    // public $gross_cols;
    // public $fixed_comp_hcols;
    // public $other_comp_hcols;
    // public $contri;
    // public $deduction_hcols;
    // public $govloans_hcols;

    // public function __construct()
    // {
    //     // $this->basic_cols = null;
    //     // $this->gross_cols = null;
    //     // $this->fixed_comp_hcols = null;
    //     // $this->other_comp_hcols = null;
    //     // $this->contri = null;
    //     // $this->deduction_hcols = null;
    //     // $this->govloans_hcols = null;
    // }

    public function __construct(PayrollRegister $payroll)
    {
       
        $this->payroll = $payroll;

    }

    function getTardyAbsenceUT($employee)
    {
        return $employee->late_eq_amount + $employee->absences_amount + $employee->under_time_amount;
    }

    public function getBasicPay($employee)
    {
        return $employee->basic_pay 
                + $employee->leghol_count_amount
                + $employee->sphol_count_amount
                + $employee->sl_wpay_amount
                + $employee->vl_wpay_amount
                + $employee->svl_amount
                + $employee->bl_wpay_amount
                + $this->getTardyAbsenceUT($employee);
    }

    public function getGrossPay($employee)
    {
        $other_earning = 0;

        // var_dump($this->payroll->fixed_comp_hcols,$this->payroll->other_comp_hcols);
        if(count($this->payroll->fixed_comp_hcols)>0)
        {
            foreach($this->payroll->fixed_comp_hcols as $fxcols)
            {
                if(array_key_exists($fxcols->compensation_type,$employee->other_earning))
                {
                    $other_earning += $employee->other_earning[$fxcols->compensation_type];
                }
                
            }
        }

        if(count($this->payroll->other_comp_hcols)>0)
        {
            foreach($this->payroll->other_comp_hcols as $othcols)
            {
                if(array_key_exists($othcols->compensation_type,$employee->other_earning))
                {
                    $other_earning += $employee->other_earning[$othcols->compensation_type];
                }
                
            }
        }

        return $this->getBasicPay($employee) + $employee->reg_ot_amount + $employee->semi_monthly_allowance + $other_earning + $employee->rd_hrs_amount;
    }

    public function getTotalDeduction($employee)
    {
        $total_deduction = 0;

        //         other_comp_hcols
        // deduction_hcols
        // govloans_hcols

        if($this->payroll->contri)
        {
            foreach($this->payroll->contri as $contri_cols){
                $total_deduction += $employee->{$contri_cols->var_name};
            }
        }

        if($this->payroll->deduction_hcols)
        {
            
            foreach($this->payroll->deduction_hcols as $deduction_hcols){
                if(array_key_exists($deduction_hcols->id,$employee->deductions))
                {
                    $total_deduction += $employee->deductions[$deduction_hcols->id];
                }
               
            }
        }

        if($this->payroll->govloans_hcols)
        {
            
            foreach($this->payroll->govloans_hcols as $govloans_hcols){
                if(array_key_exists($govloans_hcols->id,$employee->gov_loans))
                {
                    $total_deduction += $employee->gov_loans[$govloans_hcols->id];
                }
               
            }
        }


        $total_deduction += $this->getTardyAbsenceUT($employee);

        return $total_deduction;

    }

    public function getDepartmentTotal($col,$department)
    {
        $value = 0;

        switch($col)
        {
            case 'basic_pay' :
                    foreach($department->employees as $employee)
                    {
                        $value += $this->getBasicPay($employee);
                    }
                break;

            case 'semi_monthly_allowance' :
            case 'reg_ot_amount' :
            case 'rd_hrs_amount' :
            case 'net_pay':
                    foreach($department->employees as $employee)
                    {
                        $value += $employee->{$col};
                    }
                break;
            case 'gross_total' :
                    foreach($department->employees as $employee)
                    {
                        $value += $this->getGrossPay($employee);
                    }
                break;
            case 'tardyAbsence':
                    foreach($department->employees as $employee)
                    {
                        $value += $this->getTardyAbsenceUT($employee);
                    }
                break;

            case 'total_deduction':
                    foreach($department->employees as $employee)
                    {
                        $value += $this->getTotalDeduction($employee);
                    }
                break;
                
        }

        return $value;
    }

    public function getDepartmentTotalCompensation($col,$department)
    {
        $value = 0;

        foreach($department->employees as $employee)
        {
            if(array_key_exists($col->compensation_type,$employee->other_earning))
            {
                $value += $employee->other_earning[$col->compensation_type];
            }
            // ? $employee->other_earning[$fxcols->compensation_type]
        }

        return $value;
    }

    public function getDeptTotalContri($col,$department)
    {
        $value = 0;

        foreach($department->employees as $employee)
        {   
            $value += $employee->{$col};
        }

        return $value;
    }

    public function getDeptDeduction($col,$department)
    {

        $value = 0;

        foreach($department->employees as $employee)
        {   
            if(array_key_exists($col->id,$employee->deductions))
            {
                $value += $employee->deductions[$col->id];
            }
        }

        return $value;
    }

    public function getDeptGovLoan($col,$department)
    {
        $value = 0;
            foreach($department->employees as $employee)
            {
                if(array_key_exists($col->id,$employee->gov_loans))
                {
                    $value += $employee->gov_loans[$col->id];
                }
                
            }
            
        return $value;
    }

    /* By Division */
    public function getOverAllCountDivision($division)
    {
        $count = 0;

        foreach($division->departments as $department)
        {
            $count += count($department->employees);
        }
        

        return $count;
    }

    public function getOverAllTotalDivision($col,$division)
    {
        $value = 0;
       
        foreach($division->departments as $department)
        {
            $value += $this->getDepartmentTotal($col,$department);
        }

        return $value;
    }

    public function getOverAllTotalCompensationDivision($col,$division)
    {
        $value = 0;
       
        foreach($division->departments as $department)
        {
            $value += $this->getDepartmentTotalCompensation($col,$department);
        }
       

        return $value;
    }

    public function getTotalContriDivision($col,$division)
    {
        $value = 0;
        
        foreach($division->departments as $department)
        {
            $value += $this->getDeptTotalContri($col,$department);
        }

        return $value;
    }

    public function getOverAllDeductionDivision($col,$division)
    {
        $value = 0;
        
        foreach($division->departments as $department)
        {
            $value += $this->getDeptDeduction($col,$department);
        }

        return $value;
    }

    public function getOverAllALoanDivision($col,$division)
    {
        $value = 0;
        
        foreach($division->departments as $department)
        {
            $value += $this->getDeptGovLoan($col,$department);
        }
        
        return $value;
    }

    /* */

    public function getOverAllCount($data)
    {
        $count = 0;

        foreach($data as $division)
        {
            foreach($division->departments as $department)
            {
                $count += count($department->employees);
            }
        }
    
        return $count;
    }

    public function getOverAllTotal($col,$data)
    {
        $value = 0;
        foreach($data as $division)
        {
            foreach($division->departments as $department)
            {
                $value += $this->getDepartmentTotal($col,$department);
            }
        }
    

        return $value;
    }

    public function getOverAllTotalCompensation($col,$data)
    {
        $value = 0;

        foreach($data as $division)
        {
            foreach($division->departments as $department)
            {
                $value += $this->getDepartmentTotalCompensation($col,$department);
            }
        }

    

        return $value;
    }

    public function getTotalContri($col,$data)
    {
        $value = 0;

        foreach($data as $division)
        {
            foreach($division->departments as $department)
            {
                $value += $this->getDeptTotalContri($col,$department);
            }
        }
       

        return $value;
    }

    public function getOverAllDeduction($col,$data)
    {
        $value = 0;
        foreach($data as $division)
        {
            foreach($division->departments as $department)
            {
                $value += $this->getDeptDeduction($col,$department);
            }
        }

        return $value;
    }

    public function getOverAllALoan($col,$data)
    {
        $value = 0;

        foreach($data as $division)
        {
            foreach($division->departments as $department)
            {
                $value += $this->getDeptGovLoan($col,$department);
            }
        }
        return $value;
    }


  

    // public function getPeriod($id)
    // {
    //     return $this->payroll->getPeriod($id);
    // }

    // public function getPayrollData($period)
    // {

    //     $this->payroll->processV2($period);
    //     return $this->payroll;
    // }

    // public function getHeaders()
    // {
    //     // $this->basic_cols = [];
    //     // $this->gross_cols = [];
    //     // $this->fixed_comp_hcols = [];
    //     // $this->other_comp_hcols = [];
    //     // $this->contri = [];
    //     // $this->deduction_hcols = [];
    //     // $this->govloans_hcols = [];
    // }
}
