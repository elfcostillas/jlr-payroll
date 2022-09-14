<?php
namespace App\Mappers\EmployeeFileMapper\Repository;

use App\Mappers\EmployeeFileMapper\Interfaces\EmployeeInterface;

class Daily implements EmployeeInterface
{
    public function compute()
    {
        
    }

    function getBasicPay($data)
    {
        return (float) round($data['basic_salary'] * $data['ndays'],2);
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