<?php

namespace App\CustomClass\PayrollTransaction;

use Illuminate\Support\Facades\DB;

class PayrollRegister
{
    //

    protected $cols;

    public $period;
    public $emp_level;

    const name = 'parent';

    public $fin_cols_gross = [
        'Name' => 'employee_name',
        'Period Covered' => 'period',
        'Dept' => 'employee_dept',
        'Basic Salary (Reg Hours)' => '',
        'BELS' => '',
        'DYNAMIC' => '',
        'Gross Pay' => '',
    ];

    public function getColsHeader()
    {

    }

    // public function getCols()
    // {
    //     return $this->fin_cols_gross;
    // }

    public function getColsFinanceTemplate()
    {
        return $this->getCols();
    }

    public function getData()
    {
        $depts = $this->getDepts();
    }

    public function getDepts()
    {
        // $result = DB::table('')

        // dd($this::db,$this->emp_level,$this->period);
        
        $result = DB::table($this::db)
            ->leftJoin('employees','employees.biometric_id','=',$this::db.'.biometric_id')
            ->leftJoin('sub_sub_dept','employees.sub_sub_dept','=','sub_sub_dept.id')
            ->where('period_id','=',$this->period->id);

        if($this->emp_level == 'confi'){
            $result = $result->where('employees.emp_level','<',5);
        }else{
            $result = $result->where('employees.emp_level','=',5);
        }

        dd($result->get());
    
    }


}
