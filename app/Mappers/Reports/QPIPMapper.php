<?php

namespace App\Mappers\Reports;

use Illuminate\Support\Facades\DB;

class QPIPMapper
{
    public function getYears()
    {
        // $years = []; // select distinct year(dtr_date) as dtr_year from edtr_detailed order by dtr_year asc;
        // return $years;
        $result = DB::table('edtr_detailed')
            ->select(DB::raw('DISTINCT YEAR(dtr_date) as dtr_year'))
            ->orderBy('dtr_year', 'desc')
            ->get();

        return $result;
    }

    public function getEmployeesByDept($level)
    {
        $result = DB::table('employees')
            ->select('department', DB::raw('COUNT(*) as employee_count'))
            ->groupBy('department')
            ->get();

        return $result;
    }
}
