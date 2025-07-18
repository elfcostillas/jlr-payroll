<?php

namespace App\CustomClass;

use App\CustomClass\PayrollRegisterFunctions;
use Illuminate\Support\Facades\Schema;

abstract class PayrollRegister extends PayrollRegisterFunctions
{
    //
    public $db_table;
    public $period;
    public $data;
    public $cols_with_value;

    public $basic_cols;
    public $gross_cols;

    public $payroll_status;

    public $fixed_comp_hcols;
    public $other_comp_hcols;

    public $govloans_hcols;
    public $installemnts;
    public $fixed_deduction;
    public $onetimededuction;
    public $contri;
    public $deduction_hcols;

    public function __construct($db_table,$payroll_status)
    {
        $this->db_table = $db_table;
        $this->payroll_status = $payroll_status;
    }

    public function getHeaders()
    {
        // $cols = Schema::getColumnListing($this->db_table);
        // $cols = Schema::getColumnListing('edtr_totals');

        // dd($cols);

        $this->cols_with_value = $this->querySum();
        $this->basic_cols = $this->getColsByType('basic');
        $this->gross_cols = $this->getColsByType('gross');
        $this->contri = $this->getColsByType('contri');

        // dd($this->getCompensationTypeCols(''));
        if($this->payroll_status == 'unposted'){
            $fixed_comp_hcols = $this->getCompensationTypeCols('unposted_fixed_compensations');
            $other_comp_hcols = $this->getCompensationTypeCols('unposted_other_compensations');

            $deductions = $this->getDeductionLabel();
            $govloans_hcols = $this->getGovLoansLabel();
        }else{
            $fixed_comp_hcols = $this->getCompensationTypeCols('posted_fixed_compensations');
            $other_comp_hcols = $this->getCompensationTypeCols('posted_other_compensations');

            $deductions = $this->getDeductionLabel();
            $govloans_hcols = $this->getGovLoansLabel();
        }

        $this->fixed_comp_hcols = $fixed_comp_hcols;
        $this->other_comp_hcols = $other_comp_hcols;
        $this->deduction_hcols = $deductions;
        $this->govloans_hcols = $govloans_hcols;


        // dd($this->basic_cols);

        /*
        unposted_fixed_compensations
        unposted_other_compensations
        posted_other_compensations
        posted_fixed_compensations
        */

    }

    public function process($period)
    {
        $this->period = $period;
        $data = $this->getLocations();

        foreach($data as $location)
        {
            /*
                get divisions inside this location
            */
            $divisions = $this->getDivisionByLocation($location);

            foreach($divisions as $division)
            {
                $departments = $this->getDeptByDivisionAndLocation($location,$division);
               
                foreach($departments as $department)
                {
                    $employees = $this->getEmployeesByDeotDivAndLocation($location,$division,$department);

                    /* compensations for employees */
                    foreach($employees as $employee)
                    {
                        $other_earning = $this->otherEarnings($employee,$period);

                        $employee->other_earning = $other_earning;
                       
                    /*******************************/

                    /* Deductions for employee */
                        $deductions = $this->getDeductions($employee->biometric_id,$period->id);
                     
                        $employee->deductions = $deductions;
                      
                    /*******************************/

                    /* Gov Loan for employee */

                        $gov_loans = $this->getGovLoans($employee->biometric_id,$period->id);

                        $employee->gov_loans = $gov_loans;

                    /*******************************/

                    } 
                    
                    $department->employees = $employees;
                }

                $division->departments = $departments;
            }

            $location->divisions = $divisions;
        }

        $this->data = $data;
    }


}

/*
$employees = $this->getEmployees($location);

$location->employees = $employees;
*/