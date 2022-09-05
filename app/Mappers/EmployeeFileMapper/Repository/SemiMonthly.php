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
}

?>