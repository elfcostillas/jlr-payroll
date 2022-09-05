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
        return (float) round($data['basic_salary'] * $data['n_days'],2);
    }
}

?>