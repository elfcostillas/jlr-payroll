<?php

namespace App\Models\PayrollTransaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThirteenthMonthEmployee {
    // use HasFactory;

    private $employee_info;
    private $basic_pays;

    private $basic_pay_arr = [];

    public function __construct($employee,$basic_pays)
    {
        $this->employee_info = $employee;
        $this->basic_pays = $basic_pays;

        $this->setBasicPayArray();
    }

    public function setBasicPayArray()
    {
        foreach($this->basic_pays as $basic_pay)
        {
            $this->basic_pay_arr[$basic_pay->id] = $basic_pay->basic_pay;
        }
    }

    public function getBasicPay($period_id)
    {
        return (float) $this->basic_pay_arr[$period_id];
    }

    public function getName()
    {
        return $this->employee_info->lastname.' ,'.$this->employee_info->firstname.' '.substr($this->employee_info->middlename,0,1);
    }

    public function getGrossPay()
    {
        $gross_pay = 0;
        foreach($this->basic_pays as $basic_pay)
        {
            $gross_pay += $basic_pay->basic_pay;
        }

        return  $gross_pay;
    }

    public function getNetPay()
    {
        return round($this->getGrossPay()/$this->basic_pays->count(),2);
    }
}
