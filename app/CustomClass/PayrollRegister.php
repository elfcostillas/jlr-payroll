<?php

namespace App\CustomClass;

use App\CustomClass\PayrollRegisterFunctions;
use Illuminate\Support\Facades\Schema;

abstract class PayrollRegister extends PayrollRegisterFunctions
{
    //
    public $db_table;
    public $period;
    public $data;
    public $cols_with_value;

    public $basic_cols;

    public function __construct($db_table)
    {
        $this->db_table = $db_table;
    }

    public function getHeaders()
    {
        // $cols = Schema::getColumnListing($this->db_table);
        // $cols = Schema::getColumnListing('edtr_totals');

        // dd($cols);

        $this->cols_with_value = $this->querySum();
        $this->basic_cols = $this->getColsByType('basic');

        // dd($this->basic_cols);

    }

    public function process($period)
    {
        $this->period = $period;
        $data = $this->getLocations();

        foreach($data as $location)
        {
            /*
                get divisions inside this location
            */
            $divisions = $this->getDivisionByLocation($location);

            foreach($divisions as $division)
            {
                $departments = $this->getDeptByDivisionAndLocation($location,$division);
               
                foreach($departments as $department)
                {
                    $employees = $this->getEmployeesByDeotDivAndLocation($location,$division,$department);
                    $department->employees = $employees;
                }

                $division->departments = $departments;
            }

            $location->divisions = $divisions;
        }

        $this->data = $data;
    }


}

/*
$employees = $this->getEmployees($location);

$location->employees = $employees;
*/