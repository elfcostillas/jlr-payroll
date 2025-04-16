<?php

namespace App\Mappers\TimeKeepingMapper;

use App\Mappers\Mapper as AbstractMapper;
use App\Libraries\Filters;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LeaveCreditMaker extends AbstractMapper {

	protected $modelClassName = 'App\Models\Timekeeping\LeaveCredits';
    protected $rules = [
      
    ];

    protected $messages = [
       
    ];

    public function process()
    {
        $result =DB::table('employees')->select(DB::raw("biometric_id,birthdate"))->whereNotNull('birthdate')->get();

        //dd($result);
    }

    public function makeSVL($year)
    {
        // dd($year);

        $start_year = $year.'-01-01';

        $employees = DB::table('employees')
            ->select(DB::raw("floor(DATEDIFF('$year-01-01',date_hired)/365) as no_of_years,employees.*"))
            ->where('employees.emp_level','<=',4)
            ->where('employees.exit_status','=',1)
            ->get();

        foreach($employees as $employee)
        {
            $count = 0;

            $array_key = [
                'fy_year' => $year,
                'biometric_id' => $employee->biometric_id
            ];

            if($employee->no_of_years >= 2 && $employee->no_of_years <= 4){
                $count = 3;
            }

            if($employee->no_of_years >= 5 && $employee->no_of_years <= 7){
                $count = 5;
            }

            if($employee->no_of_years >= 8){
                $count = 7;
            }

            $array = [
                'summer_vacation_leave' => $count
            ];

            DB::table('leave_credits')->updateOrInsert($array_key,$array);
        }
    }

}

/*

2-4 = 3
5-7 = 5
8 = 7

*/
