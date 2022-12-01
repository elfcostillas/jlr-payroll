<?php

namespace App\Mappers\EmployeeFileMapper\Repository;

class SemiMonthly
{
    public function compute()
    {
        
    }

    function getBasicPay($data)
    {
        //dd($data);
        return (float) round($data['basic_salary']/2,2) - $data['late_eq_amount'] - $data['under_time_amount'] - $data['vl_wpay_amount'] - $data['sl_wpay_amount']
        - $data['absences_amount'] - $data['leghol_count_amount'] - $data['sphol_count_amount'];
    }

    function getMonthlyCredit($data){
        if($data['is_daily']=='Y'){
            $monthly_credit = $data['basic_salary'] * 26;
        }else{
            $monthly_credit = $data['basic_salary'];
        }

        return  (float) $monthly_credit;
    }
}

?>