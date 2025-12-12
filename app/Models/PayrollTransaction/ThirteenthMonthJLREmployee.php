<?php

namespace App\Models\PayrollTransaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThirteenthMonthJLREmployee
{
    // use HasFactory;

    private $employee_info;
    private $basic_pays;
    private $months;
    private $manual_input;

    private $basic_pay_arr = [];
    private $mbasic_pay_arr = [];

    private $conso_array = [];
    private $monthly;

    public function __construct($months,$employee,$basic_pays,$manual_input,$monthly)
    {
        $this->employee_info = $employee;
        $this->basic_pays = $basic_pays;
        $this->months = $months;
        $this->manual_input = $manual_input;
        $this->monthly = $monthly;

        $this->setBasicPayArray();
        
    
    }

    public function setConsoArray()
    {
       
    }

    public function setBasicPayArray()
    {
        if($this->manual_input->count() > 0){
            foreach($this->manual_input as $m_input)
            {
                $this->mbasic_pay_arr[$m_input->period_id] = $m_input->basic_pay;
            }
        }

        foreach($this->basic_pays as $basic_pay)
        {
            $this->basic_pay_arr[$basic_pay->id] = $basic_pay->basic_pay;// - $basic_pay->late_eq_amount;
        }
    }

    public function getBasicPay($period_id)
    {
        // return (float) $this->basic_pay_arr[$period_id];

        return array(
            'value' => (isset($this->mbasic_pay_arr[$period_id]) && $this->mbasic_pay_arr[$period_id] >0 ) ? $this->mbasic_pay_arr[$period_id] : $this->basic_pay_arr[$period_id],
            'tag' => (isset($this->mbasic_pay_arr[$period_id]) && $this->mbasic_pay_arr[$period_id] >0 ) ? 'manual' : 'auto'
        );
    }

    public function getBiometricID()
    {
        return $this->employee_info->biometric_id;
    }

    public function getName()
    {
        return $this->employee_info->lastname.' ,'.$this->employee_info->firstname.' '.substr($this->employee_info->middlename,0,1);
    }

    public function getGrossPay()
    {
        // dd($this->basic_pay_arr);

        $gross_pay = 0;
        foreach($this->basic_pays as $basic_pay)
        {
            // $gross_pay += $basic_pay->basic_pay - $basic_pay->late_eq_amount;
            $gross_pay += (float) $this->getBasicPay($basic_pay->id)['value'];
        }

        return  $gross_pay;
    }

    public function getNetPay()
    {
        $divider = ($this->months == 2) ? 7 : 5;
        
        return round($this->getGrossPay()/$divider,2);
    }

    public function getMonthly()
    {
        return $this->monthly;
    }
}
