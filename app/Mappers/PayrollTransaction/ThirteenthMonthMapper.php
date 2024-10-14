<?php

namespace App\Mappers\PayrollTransaction;

use App\Mappers\Mapper as AbstractMapper;
use App\Models\PayrollTransaction\ThirteenthMonthEmployee;
use Illuminate\Support\Facades\DB;

// use App\Models\PayrollTransaction\ThirteenthMonth;

class ThirteenthMonthMapper extends AbstractMapper
{
    //

    protected $modelClassName = 'App\Models\PayrollTransaction\ThirteenthMonth';

    protected $rules = [
    	
    ];

    function getYears()
    {   
        //SELECT pyear FROM payroll_year 

        $result = DB::table("payroll_year")
            ->select(DB::raw("pyear as text,pyear as value"))->get();
        return $result;
    }

    function buildData($year)
    {
        $locations = $this->baseQuery()->select(DB::raw("employees.location_id,location_name"))
                ->distinct()
                ->where('payroll_period_weekly.pyear',$year)
                ->orderBy('location_id','ASC')
                ->get();
        
        foreach($locations as $location)
        {
            $employee_array = [];

            $employees = $this->baseQuery()
                ->select(DB::raw("employees.biometric_id,employees.lastname,employees.firstname,employees.middlename"))
                ->distinct()
                ->where('payroll_period_weekly.pyear',$year)
                ->where('employees.location_id','=',$location->location_id)
                ->orderBy('employees.lastname','asc')->orderBy('employees.firstname','asc')->orderBy('employees.middlename','asc')
                ->get();

            foreach($employees as $employee)
            {
                // array_push($employee_array,)
                
                $e = $this->employeeFactory($employee,$year);

                array_push($employee_array,$e);
            }

            $location->employees = $employee_array;
           
        }

        $payrollPeriodOfYear = DB::table("payroll_period_weekly")
                ->select(DB::raw("id,date_from,date_to"))
                ->where("pyear",$year)
                ->get();

        return [
            'location' => $locations,
            'payroll_period' => $payrollPeriodOfYear
        ];
    }

    function employeeFactory($employee,$year)
    {
        $basic_pays = $this->basicPayQuery($employee,$year);
        return new ThirteenthMonthEmployee($employee,$basic_pays);
    }


    function baseQuery()
    {
        $result = DB::table("payrollregister_posted_weekly")
            ->leftJoin('employees','payrollregister_posted_weekly.biometric_id','=','employees.biometric_id')
            ->leftJoin('payroll_period_weekly','payroll_period_weekly.id','=','payrollregister_posted_weekly.period_id')
            ->leftJoin('locations','employees.location_id','=','locations.id')
            ->where('employees.exit_status',1);

        return $result;
    }

    function basicPayQuery($employee,$year)
    {   
        $subQuery = DB::table('payrollregister_posted_weekly')
                ->select(DB::raw("period_id,biometric_id,basic_pay,basic_salary,ndays"))
                ->leftJoin('payroll_period_weekly','payrollregister_posted_weekly.period_id','=','payroll_period_weekly.id')
                ->where('payroll_period_weekly.pyear','=',$year)
                ->where('biometric_id','=',$employee->biometric_id);
        
        
        // dd($employee->biometric_id);
        $result = DB::table("payroll_period_weekly")
            ->select(DB::raw("payroll_period_weekly.id,ifnull(subQuery.basic_pay,0.00) as basic_pay"))
            ->leftJoinSub($subQuery,'subQuery',function($join){
                $join->on('payroll_period_weekly.id','=','subQuery.period_id');
            })
            ->where('payroll_period_weekly.pyear',$year)->orderBy('payroll_period_weekly.date_from','ASC')
            ->get();

        return $result;
    }

    function insertOrUpdate($key, $value)
    {
        DB::table('payrollregister_posted_weekly')
        ->updateOrInsert(
            ['biometric_id' => $key[0], 'period_id' => $key[1]],
            ['basic_pay' => $value]
        );
    }
}
