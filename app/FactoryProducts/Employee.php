<?php

namespace App\FactoryProducts;

abstract class Employee
{
    //
    protected $employee_info;

    abstract function compute_basic();

    public function setInfo($employe_info)
    {
        $this->employee_info = $employe_info;
    }

   
}
