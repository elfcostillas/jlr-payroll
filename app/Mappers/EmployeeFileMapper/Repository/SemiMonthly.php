<?php

namespace App\Mappers\EmployeeFileMapper\Repository;

class SemiMonthly
{
    public function compute()
    {
        
    }

    function getBasicPay($data)
    {
        return (float) round($data['basic_salary']/2,2);
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