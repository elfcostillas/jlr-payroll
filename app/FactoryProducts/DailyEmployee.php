<?php

namespace App\FactoryProducts;

use App\Repository\SupportGroupEmployeeRepository;

class DailyEmployee extends Employee
{
    //
    protected $repo;

    public function __construct(SupportGroupEmployeeRepository $repo)
    {
        $this->repo = $repo;
    }

    public function compute_basic()
    {
        dd($this->employee_info);
    }
}
